<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterChunk;
use App\Models\UploadHistory;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithEvents, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    protected $uploadHistoryId;
    protected $targetRole;
    protected $defaultPassword;

    private $adminSchoolCode = null;
    private $adminDeptCode = null;

    // Cached lookups – loaded once to avoid N+1 queries across hundreds of rows
    private $schoolsCache = null;
    private $departmentsCache = null;
    private $programsCache = null;

    public function __construct($uploadHistoryId, $targetRole, $defaultPassword = 'Vu_Admin@8080')
    {
        $this->uploadHistoryId = $uploadHistoryId;
        $this->targetRole = $targetRole;
        $this->defaultPassword = $defaultPassword;
        
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->role === 'admin' && $user->profile) {
            if ($user->profile->schools_id) {
                $this->adminSchoolCode = $user->profile->schools_id;
            }
            if ($user->profile->departments_id) {
                $this->adminDeptCode = $user->profile->departments_id;
            }
        }

        // Pre-load all lookup data once
        $this->schoolsCache = \App\Models\School::all();
        $this->departmentsCache = \App\Models\Department::all();
        $this->programsCache = \App\Models\Program::all();
    }

    public function model(array $rawRow): \Illuminate\Database\Eloquent\Model|array|null
    {
        $row = $this->transformRow($rawRow);

        // Skip empty rows
        if (empty($row['employee_id']) || empty($row['firstname'])) {
            return null;
        }

        try {
            // Check if user already exists
            $user = User::where('username', $row['employee_id'])->first();
            if ($user) {
                return null; // Skip duplicates
            }
            
            // Clean phone number (strip non-digits)
            $phone = null;
            if (isset($row['mobile_number'])) {
                $phone = $row['mobile_number'];
            }
            
            $schoolCode = $this->matchSchool($row['school'] ?? '');
            $departmentCode = $this->matchDepartment($row['department'] ?? '', $schoolCode);
            $programData = $this->matchProgram($row['level'] ?? $row['program'] ?? '', $departmentCode);
            $programCode = $programData ? $programData['code'] : null;
            $studentLevel = $programData ? $programData['level'] : null;

            // Ensure the role exists before creating the profile to prevent foreign key errors
            Role::firstOrCreate(['name' => $this->targetRole]);

            // Create profile first
            $profile = Profile::create([
                'first_name' => $row['firstname'],
                'middle_name' => $row['middlename'] ?? null,
                'last_name' => $row['lastname'] ?? 'N/A',
                'username' => $row['employee_id'],
                'email' => $row['email'] ?? null,
                'phone' => $phone,
                'schools_id' => $schoolCode,
                'departments_id' => $departmentCode,
                'programs_id' => $programCode,
                'level' => $studentLevel,
                'designation' => $row['designation'] ?? ($this->targetRole === 'stu' ? 'Student' : ($this->targetRole === 'sta' ? 'Faculty' : 'Coordinator')),
                'roles_id' => $this->targetRole,
                'photo' => $row['photo'] ?? null,
            ]);
            $rolePassword = $this->defaultPassword;
            if ($this->targetRole === 'stu') {
                $rolePassword = 'Student#963';
            } elseif ($this->targetRole === 'sta') {
                $rolePassword = 'Staff@852';
            } elseif ($this->targetRole === 'admin') {
                $rolePassword = 'Admin!741';
            }
            
            $password = !empty($row['password']) ? $row['password'] : $rolePassword;

            return new User([
                'username' => $row['employee_id'],
                'password' => Hash::make($password),
                'profile_id' => $profile->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Import Error Row: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
            
            $history = UploadHistory::find($this->uploadHistoryId);
            if ($history) {
                $errors = json_decode($history->error_message, true) ?? [];
                if (count($errors) < 1000) {
                    $empId = $row['employee_id'] ?? $row['employee id'] ?? $row['register_number'] ?? $row['register number'] ?? 'Unknown ID';
                    $identifierLabel = $this->targetRole === 'stu' ? 'Register Number' : 'Employee Code';
                    $errMsg = substr($e->getMessage(), 0, 300);
                    $errors[] = "Row (Database Error) ($identifierLabel: $empId): " . $errMsg;
                    $history->error_message = json_encode($errors);
                    $history->save();
                }
            }

            return null;
        }
    }

    protected function normalizeName($name)
    {
        if (empty($name)) return '';
        $name = strtolower(trim($name));
        $name = str_replace('&', 'and', $name);
        return preg_replace('/[^a-z0-9]/', '', $name);
    }

    protected function matchSchool($name)
    {
        if (empty($name)) return null;
        $normalizedInput = $this->normalizeName($name);
        
        foreach ($this->schoolsCache as $school) {
            $dbName = $this->normalizeName($school->name);
            $dbCode = $this->normalizeName($school->code);
            
            if ($normalizedInput === $dbName || $normalizedInput === $dbCode ||
                str_contains($dbName, $normalizedInput) || str_contains($dbCode, $normalizedInput) ||
                str_contains($normalizedInput, $dbName) || str_contains($normalizedInput, $dbCode)) {
                return $school->code;
            }
        }
        return null;
    }

    protected function matchDepartment($name, $schoolCode = null)
    {
        if (empty($name)) return null;
        $name = strtolower(trim($name));
        $nameNoSpace = str_replace(' ', '', $name);
        
        // Handle common abbreviations like IT for Information Technology
        if (in_array($nameNoSpace, ['it', 'i.t.', 'informationtechnology'])) {
            $name = 'information technology';
        } elseif (in_array($nameNoSpace, ['cse', 'cs', 'computerscience'])) {
            $name = 'computer science';
        } elseif (in_array($nameNoSpace, ['ece'])) {
            $name = 'electronics';
        } elseif (in_array($nameNoSpace, ['mech', 'me', 'mechanicalengineering'])) {
            $name = 'mechanical';
        } elseif (in_array($nameNoSpace, ['civil', 'ce', 'civilengineering'])) {
            $name = 'civil';
        }

        $normalizedInput = $this->normalizeName($name);
        
        // Use cached departments, optionally filter by school
        $departments = $schoolCode
            ? $this->departmentsCache->where('school_id', $schoolCode)
            : $this->departmentsCache;
        
        foreach ($departments as $dept) {
            $dbName = $this->normalizeName($dept->name);
            $dbCode = $this->normalizeName($dept->code);
            
            if ($normalizedInput === $dbName || $normalizedInput === $dbCode ||
                str_contains($dbName, $normalizedInput) || str_contains($dbCode, $normalizedInput) ||
                str_contains($normalizedInput, $dbName) || str_contains($normalizedInput, $dbCode)) {
                return $dept->code;
            }
        }
        
        return null;
    }

    protected function matchProgram($levelInput, $departmentCode = null)
    {
        if (empty($levelInput)) return null;
        $levelInput = strtolower(trim($levelInput));
        
        // Map user input to standardized level names in DB
        $mappedLevel = 'UG'; // Default fallback
        if (in_array($levelInput, ['b.tech', 'btech', 'b tech', 'ug', 'b. tech'])) {
            $mappedLevel = 'UG';
        } elseif (in_array($levelInput, ['m.tech', 'mtech', 'm tech', 'msc', 'm.sc', 'mba', 'pg'])) {
            $mappedLevel = 'PG';
        } elseif (str_contains($levelInput, 'diploma')) {
            $mappedLevel = 'Diploma';
        } elseif (in_array($levelInput, ['phd', 'ph.d', 'doctorate'])) {
            $mappedLevel = 'PhD';
        } else {
            $mappedLevel = strtoupper($levelInput);
        }

        if ($departmentCode) {
            $dept = $this->departmentsCache->where('code', $departmentCode)->first();
            if ($dept) {
                // Find program in this department that matches the mapped level
                $prog = $this->programsCache->where('department_id', $dept->id)
                                            ->where('level', $mappedLevel)->first();
                if ($prog) {
                    return [
                        'code' => $prog->code,
                        'level' => $prog->level
                    ];
                }
            }
        }
        
        return null;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function registerEvents(): array
    {
        return [
            AfterChunk::class => function(AfterChunk $event) {
                $history = UploadHistory::find($this->uploadHistoryId);
                if ($history) {
                    $processed = $history->processed_rows + $this->chunkSize();
                    if ($processed > $history->total_rows) {
                        $processed = $history->total_rows;
                    }
                    $history->processed_rows = $processed;
                    $history->save();
                }
            },
        ];
    }

    public function prepareForValidation($data, $index)
    {
        return $this->transformRow($data);
    }

    public function isEmptyWhen(array $row): bool
    {
        if ($this->targetRole === 'stu') {
            $empId = $row['register_number'] ?? $row['register number'] ?? null;
        } else {
            $empId = $row['employee_id'] ?? $row['employee id'] ?? null;
        }
        $firstName = $row['firstname'] ?? $row['first_name'] ?? null;
        
        return empty(trim((string)$empId)) && empty(trim((string)$firstName));
    }

    protected function transformRow(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        // Map alternative header names to our expected names
        if (isset($data['first_name']) && !isset($data['firstname'])) $data['firstname'] = $data['first_name'];
        if (isset($data['last_name']) && !isset($data['lastname'])) $data['lastname'] = $data['last_name'];
        if (isset($data['middle_name']) && !isset($data['middlename'])) $data['middlename'] = $data['middle_name'];
        
        if (!empty($data['firstname']) && !empty($data['lastname']) && strtolower($data['firstname']) === strtolower($data['lastname'])) {
            $data['lastname'] = 'ERROR_SAME_AS_FIRSTNAME';
        }
        
        $data['correct_template_used'] = false;
        
        // Handle identifier mapping strictly based on role
        if ($this->targetRole === 'stu') {
            if (isset($data['register_number'])) {
                $data['employee_id'] = (string)$data['register_number'];
                $data['correct_template_used'] = true;
            } elseif (isset($data['register number'])) {
                $data['employee_id'] = (string)$data['register number'];
                $data['correct_template_used'] = true;
            }
        } else {
            if (isset($data['employee_id'])) {
                $data['employee_id'] = (string)$data['employee_id'];
                $data['correct_template_used'] = true;
            } elseif (isset($data['employee id'])) {
                $data['employee_id'] = (string)$data['employee id'];
                $data['correct_template_used'] = true;
            }
        }

        $phoneInput = $data['mobile_number'] ?? $data['phone'] ?? null;
        if (!empty($phoneInput)) {
            $data['mobile_number'] = (string)$phoneInput;
        }
        
        $providedSchool = $data['school'] ?? null;
        $providedDept = $data['department'] ?? null;
        $providedProgram = $data['program'] ?? null;
        $providedLevel = $data['level'] ?? $data['program'] ?? null;

        // Extract information from Student Register Number
        if ($this->targetRole === 'stu' && !empty($data['employee_id'])) {
            $parsedInfo = \App\Helpers\RegisterNumberParser::parse($data['employee_id']);
            if ($parsedInfo) {
                // Save parsed info to compare later
                $data['_parsed_dept_name'] = $parsedInfo['department_name'];
                $data['_parsed_course_name'] = $parsedInfo['course_name'];

                // Auto-fill or validate department/program based on Reg No
                if (empty($providedDept)) {
                    $providedDept = $parsedInfo['department_name'];
                    $data['department'] = $providedDept;
                }
                if (empty($providedLevel)) {
                    $providedLevel = $parsedInfo['course_name'];
                    $data['level'] = $providedLevel;
                    $data['program'] = $providedLevel;
                }
            } else {
                $data['employee_id'] = 'INVALID_REG:' . $data['employee_id'];
            }
        }

        $user = \Illuminate\Support\Facades\Auth::user();
        $isCoordinator = $user && $user->role === 'admin';

        $resolvedSchoolCode = null;
        if ($isCoordinator && empty($providedSchool) && $this->adminSchoolCode) {
            $data['school'] = $this->adminSchoolCode;
            $resolvedSchoolCode = $this->adminSchoolCode;
        } elseif (!empty($providedSchool)) {
            $matched = $this->matchSchool($providedSchool);
            if (!$matched) {
                $data['school'] = 'INVALID_SCHOOL:' . $providedSchool;
            } elseif ($isCoordinator && $this->adminSchoolCode && $matched !== $this->adminSchoolCode) {
                $data['school'] = 'AUTH_FAILED_SCHOOL';
            } else {
                $resolvedSchoolCode = $matched;
                $data['school'] = $matched; // store standard code to prevent double-matching
            }
        }

        $resolvedDeptCode = null;
        if ($isCoordinator && empty($providedDept) && $this->adminDeptCode) {
            $data['department'] = $this->adminDeptCode;
            $resolvedDeptCode = $this->adminDeptCode;
        } elseif (!empty($providedDept)) {
            $matched = $this->matchDepartment($providedDept, $resolvedSchoolCode);
            if (!$matched) {
                $data['department'] = 'INVALID_DEPT:' . $providedDept;
            } elseif ($isCoordinator && $this->adminDeptCode && $matched !== $this->adminDeptCode) {
                $data['department'] = 'AUTH_FAILED_DEPT';
            } else {
                $resolvedDeptCode = $matched;
                $data['department'] = $matched; // store standard code
            }
        }

        if ($this->targetRole === 'stu' && !empty($providedLevel)) {
            $matched = $this->matchProgram($providedLevel, $resolvedDeptCode);
            if (!$matched) {
                $data['level'] = 'INVALID_LEVEL:' . $providedLevel;
                $data['program'] = 'INVALID_PROGRAM:' . $providedLevel;
            } else {
                $data['level'] = $matched['level'];
                $data['program'] = $matched['code']; // store standard program code
            }
        }

        // Strict Validation: Ensure entered department and program match the Register Number
        if ($this->targetRole === 'stu' && !empty($data['_parsed_dept_name'])) {
            $parsedDeptCode = $this->matchDepartment($data['_parsed_dept_name']); // Search globally, not just in $resolvedSchoolCode
            if ($resolvedDeptCode && (!$parsedDeptCode || $resolvedDeptCode !== $parsedDeptCode)) {
                $data['department'] = 'REG_MISMATCH_DEPT';
            }
            
            // Search globally or within the parsed dept
            $parsedProg = $this->matchProgram($data['_parsed_course_name'], $parsedDeptCode ?? $resolvedDeptCode);
            $parsedProgCode = $parsedProg ? $parsedProg['code'] : null;
            if (isset($data['program']) && !str_starts_with($data['program'], 'INVALID_') && (!$parsedProgCode || $data['program'] !== $parsedProgCode)) {
                $data['program'] = 'REG_MISMATCH_PROGRAM';
            }
        }
        
        return $data;
    }

    public function rules(): array
    {
        return [
            'correct_template_used' => [
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        $expected = $this->targetRole === 'stu' ? 'Register Number' : 'Employee ID';
                        $fail("The '{$expected}' column is missing. You uploaded the wrong template for this role.");
                    }
                }
            ],
            'firstname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'lastname' => [
                (in_array($this->targetRole, ['sta', 'stu']) ? 'required' : 'nullable'),
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value === 'ERROR_SAME_AS_FIRSTNAME') {
                        $fail('The Last Name must be different from the First Name.');
                    } else if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
                        $fail('The Last Name must only contain letters and spaces.');
                    }
                }
            ],
            'middlename' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'employee_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_starts_with($value, 'INVALID_REG:')) {
                        $reg = substr($value, 12);
                        $fail("The Register Number '{$reg}' has an invalid format or contains an unknown course/department code.");
                    } else if ($this->targetRole === 'stu' && (!preg_match('/^\d{2}[a-zA-Z0-9]{2}[a-zA-Z0-9]{1,2}\d+$/', $value) || strlen($value) !== 10)) {
                        $fail("The Register Number must be exactly 10 characters, start with 2 digits (e.g., 23), and end with digits only (no trailing letters like 'ab').");
                    } else if ($this->targetRole !== 'stu' && !preg_match('/^\d{5}$/', $value)) {
                        $fail("The Employee ID must contain exactly 5 digits (e.g. no letters like EMP).");
                    }
                },
                'max:255',
                'unique:users,username',
                'unique:profiles,username',
                'distinct'
            ],
            'email' => [
                'nullable',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                'unique:profiles,email',
                'distinct'
            ],
            'mobile_number' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/',
                'unique:profiles,phone',
                'distinct'
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'photo' => 'nullable|string',
            'school' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value === 'AUTH_FAILED_SCHOOL') {
                        $fail('You can only upload accounts to your assigned school.');
                    } elseif (str_starts_with($value, 'INVALID_SCHOOL:')) {
                        $name = substr($value, 15);
                        $fail("The school '{$name}' could not be found.");
                    }
                }
            ],
            'department' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value === 'AUTH_FAILED_DEPT') {
                        $fail('You can only upload accounts to your assigned department.');
                    } elseif ($value === 'REG_MISMATCH_DEPT') {
                        $fail('The entered department does not match the department encoded in the register number.');
                    } elseif (str_starts_with($value, 'INVALID_DEPT:')) {
                        $name = substr($value, 13);
                        $fail("The department '{$name}' could not be found or does not belong to the specified school.");
                    }
                }
            ],
            'program' => [
                $this->targetRole === 'stu' ? 'required' : 'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (str_starts_with($value, 'AUTH_FAILED_PROGRAM:')) {
                        $progName = substr($value, 20);
                        $fail("The program '{$progName}' does not exist in your assigned department.");
                    } elseif ($value === 'REG_MISMATCH_PROGRAM') {
                        $fail('The entered program does not match the program encoded in the register number.');
                    } elseif (str_starts_with($value, 'INVALID_PROGRAM:')) {
                        $name = substr($value, 16);
                        $fail("The program '{$name}' could not be found or does not belong to the specified department.");
                    }
                }
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email.regex' => 'The email address must strictly end in @gmail.com or @vignan.ac.in (lowercase).',
            'mobile_number.regex' => 'The mobile number must be exactly 10 digits and contain only numbers (no symbols or spaces).',
            'mobile_number.unique' => 'This mobile number is already in use by another user.',
            'mobile_number.distinct' => 'The mobile number is duplicated within the uploaded file.',
            'firstname.regex' => 'The First Name must only contain letters and spaces.',
            'lastname.regex' => 'The Last Name must only contain letters and spaces.',
            'lastname.different' => 'The Last Name must be different from the First Name.',
            'middlename.regex' => 'The Middle Name must only contain letters and spaces.',
            'employee_id.required' => 'The ID / Register Number field is required.',
            'employee_id.unique' => 'This ID/Register Number already exists. Please verify and create this account manually if needed.',
            'employee_id.distinct' => 'The ID/Register Number is duplicated within the uploaded file.',
            'firstname.required' => 'The First Name field is required.',
            'lastname.required' => 'The Last Name field is required.',
            'email.unique' => 'The email has already been taken.',
            'email.distinct' => 'The email is duplicated within the uploaded file.',
            'email.ends_with' => 'The email must be a valid @vignan.ac.in or @gmail.com address.',
            'school.required' => 'The school field is required.',
            'department.required' => 'The department field is required.',
            'program.required' => 'The program field is required for students.',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        $history = UploadHistory::find($this->uploadHistoryId);
        if ($history) {
            $errors = json_decode($history->error_message, true) ?? [];
            
            // Cap total errors at 1000 to prevent MySQL max_allowed_packet overflow
            $maxErrors = 1000;
            
            // Group failures by row
            $groupedFailures = [];
            foreach ($failures as $failure) {
                $rowNum = $failure->row();
                if (!isset($groupedFailures[$rowNum])) {
                    $groupedFailures[$rowNum] = [
                        'row' => $rowNum,
                        'values' => $failure->values(),
                        'messages' => []
                    ];
                }
                $groupedFailures[$rowNum]['messages'] = array_merge($groupedFailures[$rowNum]['messages'], $failure->errors());
            }
            
            foreach ($groupedFailures as $grouped) {
                if (count($errors) >= $maxErrors) {
                    // Add a single note at the end instead of more individual errors
                    if (!isset($errors['__truncated'])) {
                        $errors['__truncated'] = 'Too many errors to display. Only the first ' . $maxErrors . ' errors are shown. Please fix the above rows and re-upload.';
                    }
                    break;
                }
                
                $rowNum = $grouped['row'];
                $rowData = $grouped['values'];
                $empId = $rowData['employee_id'] ?? $rowData['employee id'] ?? $rowData['register_number'] ?? $rowData['register number'] ?? 'Unknown ID';
                
                $messages = implode(', ', array_unique($grouped['messages']));
                
                // Truncate individual messages to 300 chars to keep JSON size manageable
                if (strlen($messages) > 300) {
                    $messages = substr($messages, 0, 297) . '...';
                }
                
                $identifierLabel = $this->targetRole === 'stu' ? 'Register Number' : 'Employee Code';
                $errors[] = "Row $rowNum ($identifierLabel: $empId): $messages";
            }
            
            $history->error_message = json_encode($errors);
            $history->save();
        }
    }
}
