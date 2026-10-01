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

    // In-memory caches to eliminate 10,000+ DB queries during bulk upload
    private $schoolsCache = null;
    private $departmentsCache = null;
    private $programsCache = null;

    private $schoolLookup = [];
    private $departmentLookup = [];
    private $programLookup = [];

    private $existingUsernames = [];
    private $existingProfileUsernames = [];
    private $existingEmails = [];
    private $existingPhones = [];

    private $preHashedPasswords = [];
    private $passwordCache = [];

    private $accumulatedErrors = [];

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

        // 1. Pre-load reference metadata once
        $this->schoolsCache = \App\Models\School::all();
        $this->departmentsCache = \App\Models\Department::all();
        $this->programsCache = \App\Models\Program::all();

        // 2. Build fast O(1) hash maps for schools, departments, and programs
        foreach ($this->schoolsCache as $school) {
            $this->schoolLookup[$this->normalizeName($school->name)] = $school->code;
            $this->schoolLookup[$this->normalizeName($school->code)] = $school->code;
        }

        foreach ($this->departmentsCache as $dept) {
            $normName = $this->normalizeName($dept->name);
            $normCode = $this->normalizeName($dept->code);
            $this->departmentLookup['global'][$normName] = $dept->code;
            $this->departmentLookup['global'][$normCode] = $dept->code;
            if ($dept->school_id) {
                $this->departmentLookup[$dept->school_id][$normName] = $dept->code;
                $this->departmentLookup[$dept->school_id][$normCode] = $dept->code;
            }
        }

        foreach ($this->programsCache as $prog) {
            $dept = $this->departmentsCache->firstWhere('id', $prog->department_id);
            if ($dept) {
                $key = $dept->code . '_' . strtoupper(trim($prog->level));
                $this->programLookup[$key] = [
                    'code' => $prog->code,
                    'level' => $prog->level,
                ];
            }
        }

        // 3. Pre-load existing accounts in memory to replace heavy 'unique:' database queries
        $this->existingUsernames = User::pluck('username')->flip()->all();
        $this->existingProfileUsernames = Profile::pluck('username')->flip()->all();
        $this->existingEmails = Profile::whereNotNull('email')->pluck('email')->map(fn($e) => strtolower(trim($e)))->flip()->all();
        $this->existingPhones = Profile::whereNotNull('phone')->pluck('phone')->map(fn($p) => preg_replace('/\D/', '', (string)$p))->flip()->all();

        // 4. Pre-hash default passwords ONCE (massive speed boost: reduces 3000 bcrypt runs from 300s to 0.05s)
        $this->preHashedPasswords = [
            'stu' => Hash::make('Student#963'),
            'sta' => Hash::make('Staff@852'),
            'admin' => Hash::make('Admin!741'),
            'default' => Hash::make($defaultPassword),
        ];

        // 5. Ensure role exists once upfront
        Role::firstOrCreate(['name' => $this->targetRole]);
    }

    public function model(array $rawRow): \Illuminate\Database\Eloquent\Model|array|null
    {
        $row = $this->transformRow($rawRow);

        // Skip empty rows
        if (empty($row['employee_id']) || empty($row['firstname'])) {
            return null;
        }

        try {
            // Instant in-memory duplicate check
            if (isset($this->existingUsernames[$row['employee_id']])) {
                return null; // Skip duplicate
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

            // Create profile
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

            // Track newly created profile in in-memory lookups
            $this->existingUsernames[$row['employee_id']] = true;
            $this->existingProfileUsernames[$row['employee_id']] = true;
            if (!empty($row['email'])) {
                $this->existingEmails[strtolower(trim($row['email']))] = true;
            }
            if (!empty($phone)) {
                $this->existingPhones[$phone] = true;
            }

            // Ultra-fast password retrieval (uses pre-hashed bcrypt string)
            if (!empty($row['password'])) {
                $rawPassword = (string)$row['password'];
                $hashedPassword = $this->passwordCache[$rawPassword] ??= Hash::make($rawPassword);
            } else {
                $hashedPassword = $this->preHashedPasswords[$this->targetRole] ?? $this->preHashedPasswords['default'];
            }

            return new User([
                'username' => $row['employee_id'],
                'password' => $hashedPassword,
                'profile_id' => $profile->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Import Error Row: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
            
            if (count($this->accumulatedErrors) < 1000) {
                $empId = $row['employee_id'] ?? $row['employee id'] ?? $row['register_number'] ?? $row['register number'] ?? 'Unknown ID';
                $identifierLabel = $this->targetRole === 'stu' ? 'Register Number' : 'Employee Code';
                $errMsg = substr($e->getMessage(), 0, 300);
                $this->accumulatedErrors[] = "Row (Database Error) ($identifierLabel: $empId): " . $errMsg;
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
        $norm = $this->normalizeName($name);
        
        if (isset($this->schoolLookup[$norm])) {
            return $this->schoolLookup[$norm];
        }

        // Substring fallback
        foreach ($this->schoolsCache as $school) {
            $dbName = $this->normalizeName($school->name);
            $dbCode = $this->normalizeName($school->code);
            
            if (str_contains($dbName, $norm) || str_contains($dbCode, $norm) ||
                str_contains($norm, $dbName) || str_contains($norm, $dbCode)) {
                $this->schoolLookup[$norm] = $school->code; // Memoize
                return $school->code;
            }
        }
        return null;
    }

    protected function matchDepartment($name, $schoolCode = null)
    {
        if (empty($name)) return null;
        $name = strtolower(trim($name));
        $norm = $this->normalizeName($name);
        $scopeKey = $schoolCode ?? 'global';
        
        if (isset($this->departmentLookup[$scopeKey][$norm])) {
            return $this->departmentLookup[$scopeKey][$norm];
        }

        // Direct check by code
        $deptByCode = $this->departmentsCache->firstWhere('code', $name);
        if ($deptByCode && (!$schoolCode || $deptByCode->school_id === $schoolCode)) {
            $this->departmentLookup[$scopeKey][$norm] = $deptByCode->code;
            return $deptByCode->code;
        }

        $aliasMap = [
            'dep_mech' => ['mech', 'mechanical', 'mechanicalengineering', 'robotics', 'roboticsandautomation', 'smartmanufacturing'],
            'dep_che' => ['che', 'chemical', 'chemicalengineering', 'chemicalengg', 'chemicalenginneering'],
            'dep_civ' => ['civ', 'civil', 'civilengineering', 'sustainablesmartconstruction'],
            'dep_tt' => ['tt', 'textile', 'textiletechnology', 'technicaltextiles'],
            'dep_ece' => ['ece', 'electronics', 'electronicsandcommunicationengineering', 'vlsi', 'electronicsengineeringvlsidesignandtechnology', 'digitalelectronicsandcommunicationsystem', 'embeddedsystems', 'communicationandsignalprocessing', 'electronicsandcomputerengineering'],
            'dep_bme' => ['bme', 'biomedical', 'biomedicalengineering'],
            'dep_eee' => ['eee', 'electrical', 'electricalandelectronicsengineering', 'autonomouselectricvehicles', 'powerelectronicsanddrives', 'powersystems', 'electricalvehicletechnology'],
            'dep_cse' => ['cse', 'cs', 'computerscience', 'computerscienceandengineering', 'computerscienceengineering', 'computernetworksandinformationsecurity'],
            'dep_acse' => ['acse', 'aiml', 'artificialintelligence', 'artificialintelligenceandmachinelearning', 'artificialintelligenceanddatascience', 'cyber', 'cybersecurity', 'csbs', 'computerscienceandbusinesssystems', 'computerscienceandbusinesssystem', 'ds', 'datascience', 'iot', 'internetofthings', 'advancedcomputerscienceandengineering'],
            'dep_it' => ['it', 'informationtechnology', 'datacommunicationandnetworking'],
            'dep_ca' => ['ca', 'computerapplications', 'mca', 'bca', 'masterofcomputerapplications', 'bachelorofcomputerapplications'],
            'dep_bio' => ['bio', 'biotech', 'biotechnology', 'biotechnologyandbioprocessengineering'],
            'dep_ps' => ['ps', 'pharmacy', 'pharmaceutical', 'pharmaceuticalsciences', 'bpharmacy', 'bpharm', 'pharmd', 'mpharm', 'doctorofpharmacy', 'masterofpharmacy'],
            'dep_bioinfo' => ['bioinfo', 'bioinformatics'],
            'dep_mba' => ['mba', 'bba', 'bbm', 'management', 'managementstudies', 'departmentofmanagementstudies', 'businessadministration', 'humanresourse', 'marketing', 'finance', 'bbmmba', 'bachelorofbusinessadministration', 'bachelorofbusinessmanagement', 'masterofbusinessadministration'],
            'dep_law' => ['law', 'instituteoflaw', 'ballb', 'bballb', 'llb'],
            'dep_agri' => ['agri', 'agricultural', 'agriculturalengineering', 'agricultureengineering', 'farmmachinery'],
            'dep_viat' => ['viat', 'agriculture', 'agronomy', 'vignaninstituteofagricultureandtechnology', 'bschonsagriculture'],
            'dep_foodtech' => ['foodtech', 'food', 'foodtechnology', 'foodprocessingtechnology'],
            'dep_phy' => ['phy', 'physics'],
            'dep_chem' => ['chem', 'chemistry', 'organicchemistry', 'pharmaceuticalchemistry'],
            'dep_maths' => ['maths', 'mathematics', 'statistics', 'actuarialscience', 'mathematicsandstatistics', 'actuarial'],
            'dep_eng' => ['eng', 'english', 'departmentofenglishandotherindianforeignlanguages', 'englishliterature'],
            'dep_ssh' => ['ssh', 'socialsciences', 'psychology', 'socialscienceshumanities'],
            'dep_dip' => ['dip', 'diploma'],
            'dep_education' => ['education', 'departmentofeducation', 'babed', 'bscbed'],
            'dep_cs' => ['cs', 'civilservices', 'departmentofcivilservices'],
        ];

        foreach ($aliasMap as $code => $keywords) {
            if ($norm === $code || in_array($norm, $keywords)) {
                $dept = $this->departmentsCache->firstWhere('code', $code);
                if ($dept && (!$schoolCode || $dept->school_id === $schoolCode)) {
                    $this->departmentLookup[$scopeKey][$norm] = $code;
                    return $code;
                }
            }
        }

        // Substring check against cached departments
        $departments = $schoolCode
            ? $this->departmentsCache->where('school_id', $schoolCode)
            : $this->departmentsCache;
        
        foreach ($departments as $dept) {
            $dbName = $this->normalizeName($dept->name);
            $dbCode = $this->normalizeName($dept->code);
            
            if (str_contains($dbName, $norm) || str_contains($dbCode, $norm) ||
                str_contains($norm, $dbName) || str_contains($norm, $dbCode)) {
                $this->departmentLookup[$scopeKey][$norm] = $dept->code;
                return $dept->code;
            }
        }
        
        return null;
    }

    protected function matchProgram($levelInput, $departmentCode = null)
    {
        if (empty($levelInput)) return null;
        $norm = $this->normalizeName($levelInput);
        
        if ($departmentCode) {
            $dept = $this->departmentsCache->firstWhere('code', $departmentCode);
            if ($dept) {
                $deptPrograms = $this->programsCache->where('department_id', $dept->id);

                // 1. Direct match by program code or program name
                foreach ($deptPrograms as $p) {
                    $pCodeNorm = $this->normalizeName($p->code);
                    $pNameNorm = $this->normalizeName($p->name);
                    if ($norm === $pCodeNorm || $norm === $pNameNorm) {
                        return [
                            'code' => $p->code,
                            'level' => $p->level
                        ];
                    }
                }

                // 2. Map level names
                $mappedLevel = 'UG';
                if (in_array($norm, ['ug', 'btech', 'btechin', 'bba', 'bca', 'bpharm', 'bsc', 'ba', 'btechinmechanicalengineering', 'btechincivilengineering', 'btechincomputerscienceengineering', 'btechinelectronicsandcommunicationengineering', 'btechinelectricalandelectronicsengineering'])) {
                    $mappedLevel = 'UG';
                } elseif (in_array($norm, ['pg', 'mtech', 'msc', 'mba', 'mca', 'mpharm', 'ma'])) {
                    $mappedLevel = 'PG';
                } elseif (str_contains($norm, 'phd') || str_contains($norm, 'doctorate')) {
                    $mappedLevel = 'PhD';
                } elseif (str_contains($norm, 'dip')) {
                    $mappedLevel = 'Diploma';
                } elseif (str_starts_with($norm, 'ug')) {
                    $mappedLevel = 'UG';
                } elseif (str_starts_with($norm, 'pg')) {
                    $mappedLevel = 'PG';
                } else {
                    $mappedLevel = strtoupper(trim($levelInput));
                }

                $prog = $deptPrograms->where('level', $mappedLevel)->first();
                if ($prog) {
                    return [
                        'code' => $prog->code,
                        'level' => $prog->level
                    ];
                }

                $firstProg = $deptPrograms->first();
                if ($firstProg) {
                    return [
                        'code' => $firstProg->code,
                        'level' => $firstProg->level
                    ];
                }
            }
        }
        
        return null;
    }

    public function batchSize(): int
    {
        return 250;
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
                    if (!empty($this->accumulatedErrors)) {
                        $history->error_message = json_encode($this->accumulatedErrors);
                    }
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

        // Extract information from Student Register Number if available
        if ($this->targetRole === 'stu' && !empty($data['employee_id'])) {
            $parsedInfo = \App\Helpers\RegisterNumberParser::parse($data['employee_id']);
            if ($parsedInfo) {
                $data['_parsed_dept_name'] = $parsedInfo['department_name'];
                $data['_parsed_course_name'] = $parsedInfo['course_name'];

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
                $regStr = (string)$data['employee_id'];
                if (!preg_match('/^\d{2}[a-zA-Z0-9]{2}[a-zA-Z0-9]{1,2}\d+$/', $regStr) || strlen($regStr) !== 10) {
                    $data['employee_id'] = 'INVALID_REG:' . $regStr;
                }
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
                $data['school'] = $matched;
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
                $data['department'] = $matched;
                
                // If school was not provided, auto-link to the department's school
                if (!$resolvedSchoolCode) {
                    $dModel = $this->departmentsCache->firstWhere('code', $matched);
                    if ($dModel && $dModel->school_id) {
                        $resolvedSchoolCode = $dModel->school_id;
                        $data['school'] = $resolvedSchoolCode;
                    }
                }
            }
        }

        if ($this->targetRole === 'stu' && !empty($providedLevel)) {
            $matched = $this->matchProgram($providedLevel, $resolvedDeptCode);
            if (!$matched && !empty($providedProgram)) {
                $matched = $this->matchProgram($providedProgram, $resolvedDeptCode);
            }
            if (!$matched) {
                $data['level'] = 'INVALID_LEVEL:' . $providedLevel;
                $data['program'] = 'INVALID_PROGRAM:' . $providedLevel;
            } else {
                $data['level'] = $matched['level'];
                $data['program'] = $matched['code'];
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

                    // Ultra-fast in-memory uniqueness check
                    if (isset($this->existingUsernames[$value]) || isset($this->existingProfileUsernames[$value])) {
                        $fail('This ID/Register Number already exists. Please verify and create this account manually if needed.');
                    }
                },
                'max:255',
                'distinct'
            ],
            'email' => [
                'nullable',
                'string',
                'email:rfc,filter',
                'max:255',
                'not_regex:/@example\.(com|org|net)$/i',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                function ($attribute, $value, $fail) {
                    if ($value && isset($this->existingEmails[strtolower(trim($value))])) {
                        $fail('The email has already been taken.');
                    }
                },
                'distinct'
            ],
            'mobile_number' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/',
                function ($attribute, $value, $fail) {
                    $cleanPhone = preg_replace('/\D/', '', (string)$value);
                    if ($cleanPhone && isset($this->existingPhones[$cleanPhone])) {
                        $fail('This mobile number is already in use by another user.');
                    }
                },
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
            'mobile_number.distinct' => 'The mobile number is duplicated within the uploaded file.',
            'firstname.regex' => 'The First Name must only contain letters and spaces.',
            'lastname.regex' => 'The Last Name must only contain letters and spaces.',
            'lastname.different' => 'The Last Name must be different from the First Name.',
            'middlename.regex' => 'The Middle Name must only contain letters and spaces.',
            'employee_id.required' => 'The ID / Register Number field is required.',
            'employee_id.distinct' => 'The ID/Register Number is duplicated within the uploaded file.',
            'firstname.required' => 'The First Name field is required.',
            'lastname.required' => 'The Last Name field is required.',
            'email.distinct' => 'The email is duplicated within the uploaded file.',
            'email.ends_with' => 'The email must be a valid @vignan.ac.in or @gmail.com address.',
            'school.required' => 'The school field is required.',
            'department.required' => 'The department field is required.',
            'program.required' => 'The program field is required for students.',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        // Cap total errors at 1000 to prevent oversized JSON
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
            if (count($this->accumulatedErrors) >= $maxErrors) {
                if (!isset($this->accumulatedErrors['__truncated'])) {
                    $this->accumulatedErrors['__truncated'] = 'Too many errors to display. Only the first ' . $maxErrors . ' errors are shown. Please fix the above rows and re-upload.';
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
            $this->accumulatedErrors[] = "Row $rowNum ($identifierLabel: $empId): $messages";
        }
    }

    public function getAccumulatedErrors(): array
    {
        return $this->accumulatedErrors;
    }
}
