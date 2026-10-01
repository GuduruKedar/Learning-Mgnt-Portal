<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Department;
use App\Models\Program;
use App\Models\Profile;
use App\Models\Course;
use App\Models\Regulation;
use App\Models\CourseMaterial;
use App\Models\Assignment;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SshAdminController extends Controller
{
    /**
     * S&H Department codes belonging to Applied Sciences & Humanities
     */
    protected function getShDeptCodes()
    {
        return Department::where('school_id', 'sc_ash')
            ->pluck('code')
            ->merge(['dep_ssh', 'dep_phy', 'dep_chem', 'dep_maths', 'dep_eng'])
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Dynamic S&H Departments List (Mathematics, Physics, Chemistry, English, and all future S&H depts)
     */
    protected function getShDepartments()
    {
        return Department::where('school_id', 'sc_ash')
            ->orWhereIn('code', ['dep_ssh', 'dep_phy', 'dep_chem', 'dep_maths', 'dep_eng'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Helper query for 1st year students across all branches
     */
    protected function firstYearStudentsQuery()
    {
        $currentYearShort = date('y'); // e.g., '26'
        $prevYearShort = str_pad((int)$currentYearShort - 1, 2, '0', STR_PAD_LEFT); // e.g. '25'
        
        return User::role('stu')->where(function($q) use ($currentYearShort, $prevYearShort) {
            // Match register numbers starting with current or recent intake (e.g. 26... or 25...) or in 1st year level
            $q->where('username', 'like', $currentYearShort . '%')
              ->orWhere('username', 'like', $prevYearShort . '%')
              ->orWhereHas('profile', function($pq) {
                  $pq->whereIn('departments_id', $this->getShDeptCodes())
                     ->orWhere('level', 'UG');
              });
        })->with(['profile.school', 'profile.department', 'profile.program']);
    }

    /**
     * SSH Department Dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // 1. First Year Students KPI
        $firstYearsQuery = $this->firstYearStudentsQuery();
        $totalFirstYears = (clone $firstYearsQuery)->count();

        // 2. Branch Breakdown of 1st Years
        $branchBreakdown = DB::table('users')
            ->join('profiles', 'users.profile_id', '=', 'profiles.id')
            ->leftJoin('departments', 'profiles.departments_id', '=', 'departments.code')
            ->where('profiles.roles_id', 'stu')
            ->select('departments.name as department_name', 'profiles.departments_id', DB::raw('count(*) as student_count'))
            ->groupBy('departments.name', 'profiles.departments_id')
            ->orderByDesc('student_count')
            ->get();

        // 3. S&H Faculty Members
        $shDeptCodes = $this->getShDeptCodes();
        $shFaculty = User::role('sta')->whereHas('profile', function($q) use ($shDeptCodes) {
            $q->whereIn('departments_id', $shDeptCodes)->orWhere('schools_id', 'sc_ash');
        })->with(['profile.department'])->get();
        $totalShFaculty = $shFaculty->count();

        // 4. First Year Foundational Courses (Year 1)
        $firstYearCourses = Course::where('year', 1)
            ->with(['regulation', 'department', 'staff.profile'])
            ->get();
        $totalFirstYearCourses = $firstYearCourses->count();
        $allocatedCoursesCount = $firstYearCourses->filter(function($c) {
            return $c->staff->count() > 0;
        })->count();

        // 5. Recent 1st Year Freshers
        $recentFreshers = (clone $firstYearsQuery)->latest('id')->take(6)->get();

        return view('ssh.dashboard', compact(
            'user',
            'totalFirstYears',
            'branchBreakdown',
            'totalShFaculty',
            'shFaculty',
            'firstYearCourses',
            'totalFirstYearCourses',
            'allocatedCoursesCount',
            'recentFreshers'
        ));
    }

    /**
     * Display 1st Year Students Directory with live filtering
     */
    /**
     * Display 1st Year Students Directory with live filtering
     */
    public function students(Request $request)
    {
        $query = $this->firstYearStudentsQuery();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('section', 'like', "%{$search}%")
                         ->orWhereHas('department', function($dq) use ($search) {
                             $dq->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($request->filled('school')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('schools_id', $request->school);
            });
        }

        if ($request->filled('department')) {
            $dept = trim($request->department);
            $query->whereHas('profile', function($q) use ($dept) {
                $q->where('departments_id', $dept)
                  ->orWhereHas('department', function($dq) use ($dept) {
                      $dq->where('code', $dept)
                        ->orWhere('name', $dept);
                  });
            });
        }

        if ($request->filled('section')) {
            $sec = strtoupper(trim($request->section));
            $query->whereHas('profile', function($q) use ($sec) {
                $q->where('section', $sec)
                  ->orWhere('section', 'like', "%{$sec}%");
            });
        }

        if ($request->filled('year')) {
            $yearVal = (int) $request->year;
            $query->whereHas('profile', function($q) use ($yearVal) {
                $q->where('academic_year', $yearVal);
            });
        }

        if ($request->filled('batch')) {
            $batchYear = trim($request->batch);
            $batchShort = strlen($batchYear) === 4 ? substr($batchYear, -2) : $batchYear;
            $query->where('username', 'like', $batchShort . '%');
        }

        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [5, 10, 15, 25, 50, 100])) {
            $perPage = 15;
        }

        $students = $query->latest('id')->paginate($perPage)->withQueryString();
        $schools = School::orderBy('name')->get();
        $departments = Department::with('school')->orderBy('name')->get();
        $totalStudentsCount = $this->firstYearStudentsQuery()->count();
        
        $availableSections = Profile::whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->pluck('section')
            ->sort()
            ->values()
            ->toArray();
        if (empty($availableSections)) {
            $availableSections = ['A', 'B', 'C', 'D'];
        }

        return view('ssh.students.index', compact('students', 'schools', 'departments', 'totalStudentsCount', 'availableSections'));
    }

    /**
     * Show form for creating a new 1st Year student
     */
    public function createStudent()
    {
        $schools = School::all();
        $departments = Department::all();
        $programs = Program::all();

        return view('ssh.students.create', compact('schools', 'departments', 'programs'));
    }

    /**
     * Store newly created 1st Year student
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'size:10', 'regex:/^\d{2}[a-zA-Z0-9]{2}[a-zA-Z0-9]{1,2}\d+$/', 'unique:users,username'],
            'email' => [
                'nullable',
                'string',
                'email:rfc,filter',
                'max:255',
                'not_regex:/@example\.(com|org|net)$/i',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                'unique:profiles,email'
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'phone_number' => ['nullable', 'numeric', 'digits:10'],
            'school_id' => ['required', 'exists:schools,id'],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $schoolCode = School::where('id', $request->school_id)->value('code');
                    return $query->where('school_id', $schoolCode);
                })
            ],
            'level' => ['nullable', 'string', 'in:UG,PG,Diploma,PhD'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'section' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:webp,jpeg,png,jpg', 'max:2048'],
        ], [
            'email.not_regex' => 'Dummy or placeholder email domains (@example.com) are not allowed. Please provide a valid email.',
            'email.regex' => 'The email must belong to an official domain (@vignan.ac.in or @gmail.com).',
            'username.size' => 'The Register Number must be exactly 10 characters.',
            'username.regex' => 'The Register Number format is invalid (e.g. 261FA04001).',
            'school_id.required' => 'Please select the parent School.',
            'department_id.required' => 'Please select the parent Department / Branch.',
            'department_id.exists' => 'The selected department does not belong to the selected school.',
        ]);

        $validated['username'] = strtoupper(trim($validated['username']));
        $password = $request->filled('password') ? $request->password : 'Student#963';
        $schoolCode = School::where('id', $validated['school_id'])->value('code');
        $deptCode = Department::where('id', $validated['department_id'])->value('code');
        $programCode = isset($validated['program_id']) ? Program::where('id', $validated['program_id'])->value('code') : null;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $filename = $validated['username'] . '_profile.' . $request->file('photo')->getClientOriginalExtension();
            $photoPath = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $profile = Profile::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? 'UG',
            'programs_id' => $programCode,
            'section' => !empty($validated['section']) ? strtoupper(trim($validated['section'])) : null,
            'academic_year' => 1,
            'semester' => 1,
            'designation' => 'First Year Student',
            'roles_id' => 'stu',
            'photo' => $photoPath,
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id() ?? 1,
            'action' => 'Registered 1st Year Student',
            'details' => 'SSH Department registered 1st year student ' . $profile->first_name . ' ' . $profile->last_name . ' (' . $profile->username . ').',
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('ssh.students.index')
            ->with('success', "First Year student {$profile->username} successfully registered!");
    }

    /**
     * Show form for editing an existing 1st Year student
     */
    public function editStudent($id)
    {
        $student = User::role('stu')
            ->with(['profile.school', 'profile.department', 'profile.program'])
            ->findOrFail($id);

        $schools = School::orderBy('name')->get();
        $departments = Department::with('school')->orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('ssh.students.edit', compact('student', 'schools', 'departments', 'programs'));
    }

    /**
     * Update an existing 1st Year student record
     */
    public function updateStudent(Request $request, $id)
    {
        $student = User::role('stu')->with('profile')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'size:10', 'regex:/^[0-9]{2}[0-9A-Za-z]{8}$/', 'unique:users,username,' . $student->id . ',id'],
            'email' => ['nullable', 'email', 'max:255', 'unique:profiles,email,' . ($student->profile->id ?? 0) . ',id'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'school_id' => ['required', 'exists:schools,id'],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $schoolCode = School::where('id', $request->school_id)->value('code');
                    return $query->where('school_id', $schoolCode);
                })
            ],
            'level' => ['nullable', 'string', 'in:UG,PG,Diploma,PhD'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'section' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:webp,jpeg,png,jpg', 'max:2048'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $validated['username'] = strtoupper(trim($validated['username']));
        $schoolCode = School::where('id', $validated['school_id'])->value('code');
        $deptCode = Department::where('id', $validated['department_id'])->value('code');
        $programCode = isset($validated['program_id']) ? Program::where('id', $validated['program_id'])->value('code') : null;

        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? 'UG',
            'programs_id' => $programCode,
            'section' => !empty($validated['section']) ? strtoupper(trim($validated['section'])) : null,
        ];

        if ($request->hasFile('photo')) {
            if ($student->profile && $student->profile->photo) {
                Storage::disk('public')->delete($student->profile->photo);
            }
            $filename = $validated['username'] . '_profile.' . $request->file('photo')->getClientOriginalExtension();
            $profileData['photo'] = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $student->update(['username' => $validated['username']]);
        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }
        $student->profile->update($profileData);

        return redirect()->route('ssh.students.index')->with('success', "Student {$student->username} updated successfully.");
    }

    /**
     * Delete 1st Year student
     */
    public function destroyStudent($id)
    {
        $student = User::role('stu')->with(['profile', 'enrolledCourses', 'civilServiceEnrollment'])->findOrFail($id);
        $username = $student->username;
        $fullName = trim(($student->profile->first_name ?? '') . ' ' . ($student->profile->last_name ?? ''));

        $submissionsCount = \App\Models\AssignmentSubmission::where('student_id', $student->id)->count();
        $submissionIds = \App\Models\AssignmentSubmission::where('student_id', $student->id)->pluck('id');
        $answersCount = \App\Models\AssignmentAnswer::whereIn('submission_id', $submissionIds)->count();
        $enrollmentsCount = \Illuminate\Support\Facades\DB::table('enrollments')->where('user_id', $student->id)->count();
        $civilEnrollmentCount = \App\Models\CivilServiceEnrollment::where('user_id', $student->id)->count();
        $deptId = $student->profile->departments_id ?? 'ssh';

        \Illuminate\Support\Facades\DB::transaction(function () use ($student) {
            // 1. Delete student assignment submissions and answers
            $submissions = \App\Models\AssignmentSubmission::where('student_id', $student->id)->get();
            foreach ($submissions as $sub) {
                if ($sub->file_path && Storage::disk('public')->exists($sub->file_path)) {
                    Storage::disk('public')->delete($sub->file_path);
                }
                \App\Models\AssignmentAnswer::where('submission_id', $sub->id)->delete();
                $sub->delete();
            }

            // 2. Detach / delete enrollments
            \Illuminate\Support\Facades\DB::table('enrollments')->where('user_id', $student->id)->delete();

            // 3. Remove civil service enrollment
            \App\Models\CivilServiceEnrollment::where('user_id', $student->id)->delete();

            // 4. Delete profile photo & record
            $profile = $student->profile;
            if ($profile) {
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $profile->delete();
            }

            // 5. Delete student user record
            $student->delete();
        });

        \App\Services\ActivityLogger::log(
            'cascade_ssh_student_deleted',
            '1st Year Student & Cascading Records Deleted',
            'Students',
            "SSH Directorate permanently deleted 1st Year student {$fullName} ({$username}). Cascaded removals: {$submissionsCount} assignment submission(s), {$answersCount} question response(s), {$enrollmentsCount} course enrollment(s), and {$civilEnrollmentCount} civil service enrollment(s).",
            'danger',
            [
                'department_id' => $deptId,
                'entity_type'   => 'User',
                'entity_id'     => $id,
                'entity_name'   => "{$fullName} ({$username})",
                'payload'       => [
                    'student_username'       => $username,
                    'student_name'           => $fullName,
                    'department'             => $deptId,
                    'submissions_deleted'    => $submissionsCount,
                    'answers_deleted'        => $answersCount,
                    'enrollments_removed'    => $enrollmentsCount,
                    'civil_services_removed' => $civilEnrollmentCount,
                ]
            ]
        );

        return redirect()->route('ssh.students.index')->with('success', "Student {$username} and all cascading data deleted successfully.");
    }

    /**
     * Export 1st Year Students to CSV
     */
    public function exportStudents(Request $request)
    {
        $query = $this->firstYearStudentsQuery();

        if ($request->filled('department')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('departments_id', $request->department);
            });
        }

        $students = $query->get();
        $filename = 'First_Year_Students_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Register Number', 'First Name', 'Middle Name', 'Last Name', 'Email', 'Phone', 'School', 'Parent Department', 'Program', 'Level']);

            foreach ($students as $stu) {
                fputcsv($handle, [
                    $stu->username,
                    $stu->profile->first_name ?? '',
                    $stu->profile->middle_name ?? '',
                    $stu->profile->last_name ?? '',
                    $stu->profile->email ?? '',
                    $stu->profile->phone ?? '',
                    $stu->profile->school->name ?? $stu->profile->schools_id ?? '',
                    $stu->profile->department->name ?? $stu->profile->departments_id ?? '',
                    $stu->profile->program->name ?? $stu->profile->programs_id ?? '',
                    $stu->profile->level ?? 'UG',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * First Year Foundational Courses Catalog
     */
    public function courses(Request $request)
    {
        $query = Course::where('year', 1)
            ->with(['regulation', 'department', 'staff.profile.department', 'staff.profile.school'])
            ->withCount(['staff', 'materials', 'assignments', 'enrollments']);

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('regulation_id')) {
            $query->where('regulation_id', $request->regulation_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest('id')->paginate(10)->withQueryString();
        $regulations = Regulation::where('status', 'Active')->get();
        $departments = Department::all();
        $availableStaff = User::role('sta')->with(['profile.department', 'profile.school'])->get()->sortBy(function($s) {
            return ($s->profile->department->name ?? 'Z') . ' ' . ($s->profile->first_name ?? $s->username);
        });

        return view('ssh.courses.index', compact('courses', 'regulations', 'departments', 'availableStaff'));
    }

    /**
     * Show form for creating 1st Year course
     */
    public function createCourse()
    {
        $regulations = Regulation::where('status', 'Active')->get();
        $departments = Department::all();
        $existingCourseCodes = Course::pluck('code')->map(fn($c) => strtoupper(trim($c)))->unique()->values();

        return view('ssh.courses.create', compact('regulations', 'departments', 'existingCourseCodes'));
    }

    /**
     * Store 1st Year Course
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'department_id' => 'required|exists:departments,code',
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'semester' => 'required|integer|in:1,2',
        ]);

        $courseCode = strtoupper(trim($request->code));
        if (Course::whereRaw('UPPER(TRIM(code)) = ?', [$courseCode])->exists()) {
            return back()->withInput()->withErrors([
                'code' => "Course code '{$courseCode}' already exists in the system.",
            ]);
        }

        $course = Course::create([
            'regulation_id' => $request->regulation_id,
            'department_id' => $request->department_id,
            'year' => 1,
            'semester' => $request->semester,
            'code' => $courseCode,
            'name' => trim($request->name),
        ]);

        \App\Services\ActivityLogger::log(
            'ssh_course_created',
            '1st Year Course Created',
            'Academics',
            'SSH Department created foundational course ' . $course->name . ' (' . $course->code . ').',
            'success',
            ['entity_type' => 'Course', 'entity_id' => $course->id]
        );

        return redirect()->route('ssh.courses.index')->with('success', "Foundational Course {$course->code} created successfully!");
    }

    /**
     * Delete 1st Year Course
     */
    public function destroyCourse($id)
    {
        $course = Course::with(['staff', 'materials', 'assignments.submissions', 'assignments.questions'])
            ->where('year', 1)
            ->findOrFail($id);

        $courseName = $course->name;
        $courseCode = $course->code;
        $deptId = $course->department_id;

        $staffCount = $course->staff()->count();
        $enrollmentCount = \Illuminate\Support\Facades\DB::table('enrollments')->where('course_id', $course->id)->count();
        $materialsCount = $course->materials()->count();
        $assignmentsCount = $course->assignments()->count();

        \Illuminate\Support\Facades\DB::transaction(function () use ($course) {
            foreach ($course->materials as $material) {
                if ($material->type === 'file' && !empty($material->url_or_path)) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($material->url_or_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($material->url_or_path);
                    }
                }
                $material->delete();
            }

            foreach ($course->assignments as $assignment) {
                if (!empty($assignment->attachment_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($assignment->attachment_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->attachment_path);
                }

                foreach ($assignment->submissions as $submission) {
                    if (!empty($submission->file_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($submission->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->file_path);
                    }
                    \App\Models\AssignmentAnswer::where('submission_id', $submission->id)->delete();
                    $submission->delete();
                }

                $assignment->questions()->delete();
                $assignment->delete();
            }

            \Illuminate\Support\Facades\DB::table('enrollments')->where('course_id', $course->id)->delete();
            $course->staff()->detach();
            $course->delete();
        });

        // 6. Log activity
        \App\Services\ActivityLogger::log(
            'cascade_ssh_course_deleted',
            '1st Year Course & Linked Data Deleted',
            'Academics',
            "SSH Directorate permanently deleted course {$courseName} ({$courseCode}). Cascaded removals: {$materialsCount} material(s), {$assignmentsCount} assignment(s), {$enrollmentCount} student enrollment(s), and {$staffCount} faculty allocation(s) detached.",
            'danger',
            [
                'department_id' => $deptId,
                'entity_type'   => 'Course',
                'entity_id'     => $id,
                'entity_name'   => "{$courseCode} - {$courseName}",
                'payload'       => [
                    'course_code'          => $courseCode,
                    'course_name'          => $courseName,
                    'department'           => $deptId,
                    'staff_detached'       => $staffCount,
                    'enrollments_removed'  => $enrollmentCount,
                    'materials_deleted'    => $materialsCount,
                    'assignments_deleted'  => $assignmentsCount,
                ]
            ]
        );

        return redirect()->route('ssh.courses.index')->with('success', "Course '{$courseCode} - {$courseName}' and all linked records ({$staffCount} faculty, {$enrollmentCount} students, {$materialsCount} materials, {$assignmentsCount} assignments) deleted successfully.");
    }

    /**
     * First Year Course Allocations View
     */
    public function courseAllocations(Request $request)
    {
        $query = Course::where('year', 1)
            ->with(['regulation', 'department', 'staff.profile.department', 'staff.profile.school']);

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest('id')->paginate(10)->withQueryString();
        
        // Available faculty from all departments for 1st year subject allocation
        $availableStaff = User::role('sta')->with(['profile.department', 'profile.school'])->get()->sortBy(function($s) {
            return ($s->profile->department->name ?? 'Z') . ' ' . ($s->profile->first_name ?? $s->username);
        });
        $regulations = Regulation::where('status', 'Active')->get();

        return view('ssh.courses.allocations', compact('courses', 'availableStaff', 'regulations'));
    }

    /**
     * Allocate Faculty to 1st Year Course (Supports all departments faculty for 1st year)
     */
    public function allocateStaff(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->year != 1) {
            return back()->with('error', 'SSH Admin can only allocate faculty to 1st Year courses.');
        }

        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $staff = User::role('sta')->with(['profile.department', 'profile.school'])->findOrFail($request->staff_id);

        if ($course->staff()->where('users.id', $staff->id)->exists()) {
            return back()->with('warning', 'This faculty member is already allocated to this course.');
        }

        $course->staff()->syncWithoutDetaching([$staff->id]);

        $staffName = trim(($staff->profile->first_name ?? '') . ' ' . ($staff->profile->last_name ?? '')) ?: $staff->username;
        $deptName = $staff->profile->department->name ?? 'Department';

        \App\Services\ActivityLogger::log(
            'ssh_course_allocated',
            'Faculty Allocated to 1st Year Subject',
            'Academics',
            'SSH Department assigned faculty ' . $staffName . ' (' . $deptName . ') to teach ' . $course->name . ' (' . $course->code . ').',
            'success',
            ['department_id' => $course->department_id]
        );

        return back()->with('success', "Faculty {$staffName} ({$deptName}) successfully allocated to {$course->code}!");
    }

    /**
     * Unallocate Faculty from 1st Year Course
     */
    public function unallocateStaff($courseId, $staffId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->year != 1) {
            return back()->with('error', 'SSH Admin can only manage allocations for 1st Year courses.');
        }

        $staff = User::find($staffId);
        $staffName = $staff ? (trim(($staff->profile->first_name ?? '') . ' ' . ($staff->profile->last_name ?? '')) ?: $staff->username) : 'Faculty';

        $course->staff()->detach($staffId);

        \App\Services\ActivityLogger::log(
            'ssh_course_unallocated',
            'Faculty Allocation Removed',
            'Academics',
            'SSH Department removed faculty ' . $staffName . ' from course ' . $course->name . ' (' . $course->code . ').',
            'warning',
            ['department_id' => $course->department_id]
        );

        return back()->with('success', "Faculty {$staffName} unallocated from {$course->code}.");
    }

    /**
     * S&H Faculty & Staff Directory
     */
    public function staff(Request $request)
    {
        $shDeptCodes = $this->getShDeptCodes();
        $query = User::role('sta')->whereHas('profile', function($q) use ($shDeptCodes) {
            $q->whereIn('departments_id', $shDeptCodes)->orWhere('schools_id', 'sc_ash');
        })->with(['profile.school', 'profile.department']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhereHas('department', function($dq) use ($search) {
                             $dq->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($request->filled('department')) {
            $dept = trim($request->department);
            $query->whereHas('profile', function($q) use ($dept) {
                $q->where('departments_id', $dept)
                  ->orWhereHas('department', function($dq) use ($dept) {
                      $dq->where('code', $dept)->orWhere('name', $dept);
                  });
            });
        }

        $staff = $query->withCount(['courses', 'assignments', 'courseMaterials'])->latest('id')->paginate(10)->withQueryString();
        $departments = $this->getShDepartments();

        return view('ssh.staff.index', compact('staff', 'departments'));
    }

    /**
     * Create S&H Faculty Form
     */
    public function createStaff()
    {
        $departments = $this->getShDepartments();
        return view('ssh.staff.create', compact('departments'));
    }

    /**
     * Store S&H Faculty
     */
    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9]{4,10}$/', 'unique:users,username'],
            'email' => [
                'nullable',
                'string',
                'email:rfc,filter',
                'max:255',
                'not_regex:/@example\.(com|org|net)$/i',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                'unique:profiles,email'
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'phone_number' => ['nullable', 'numeric', 'digits:10'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:webp,jpeg,png,jpg', 'max:2048'],
        ], [
            'email.not_regex' => 'Dummy or placeholder email domains (@example.com) are not allowed. Please provide a valid email.',
            'email.regex' => 'The email must belong to an official domain (@vignan.ac.in or @gmail.com).',
            'username.regex' => 'The Faculty Employee Code must be 4 to 10 alphanumeric characters (e.g. 10008).',
            'department_id.required' => 'Please select the S&H Department.',
            'first_name.regex' => 'First name must contain only alphabetical letters.',
            'last_name.regex' => 'Last name must contain only alphabetical letters.',
        ]);

        $deptCode = Department::where('id', $validated['department_id'])->value('code');
        $password = $request->filled('password') ? $request->password : 'Staff@852';

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $filename = strtoupper(trim($validated['username'])) . '_staff_profile.' . $request->file('photo')->getClientOriginalExtension();
            $photoPath = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $profile = Profile::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => strtoupper(trim($validated['username'])),
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'schools_id' => 'sc_ash',
            'departments_id' => $deptCode,
            'designation' => $validated['designation'],
            'photo' => $photoPath,
            'level' => 'UG',
            'roles_id' => 'sta',
        ]);

        $user = User::create([
            'username' => strtoupper(trim($validated['username'])),
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        \App\Services\ActivityLogger::log(
            'ssh_staff_created',
            'S&H Faculty Registered',
            'Staff',
            'SSH Department added faculty member ' . $profile->first_name . ' ' . $profile->last_name . ' (' . $profile->username . ') in ' . ($profile->department->name ?? $deptCode) . '.',
            'success',
            ['user' => $user, 'department_id' => $deptCode]
        );

        return redirect()->route('ssh.staff.index')->with('success', "Faculty {$user->username} successfully registered in {$profile->department->name}!");
    }

    /**
     * Edit S&H Faculty Form
     */
    public function editStaff($id)
    {
        $staff = User::role('sta')->findOrFail($id);
        $departments = $this->getShDepartments();

        return view('ssh.staff.edit', compact('staff', 'departments'));
    }

    /**
     * Update S&H Faculty
     */
    public function updateStaff(Request $request, $id)
    {
        $staff = User::role('sta')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => [
                'nullable',
                'string',
                'email:rfc,filter',
                'max:255',
                'not_regex:/@example\.(com|org|net)$/i',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                'unique:profiles,email,' . $staff->profile_id
            ],
            'phone_number' => ['nullable', 'numeric', 'digits:10'],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'photo' => ['nullable', 'image', 'mimes:webp,jpeg,png,jpg', 'max:2048'],
        ], [
            'email.not_regex' => 'Dummy or placeholder email domains (@example.com) are not allowed. Please provide a valid email.',
            'email.regex' => 'The email must belong to an official domain (@vignan.ac.in or @gmail.com).',
        ]);

        $deptCode = Department::where('id', $validated['department_id'])->value('code');

        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'departments_id' => $deptCode,
            'designation' => $validated['designation'],
        ];

        if ($request->hasFile('photo')) {
            if ($staff->profile && $staff->profile->photo) {
                Storage::disk('public')->delete($staff->profile->photo);
            }
            $filename = $staff->username . '_staff_profile.' . $request->file('photo')->getClientOriginalExtension();
            $profileData['photo'] = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $staff->profile->update($profileData);

        if ($request->filled('password')) {
            $staff->update([
                'password' => Hash::make($request->password)
            ]);
        }

        \App\Services\ActivityLogger::log(
            'ssh_staff_updated',
            'S&H Faculty Updated',
            'Staff',
            'SSH Department updated details for faculty ' . $staff->profile->first_name . ' ' . $staff->profile->last_name . ' (' . $staff->username . ').',
            'info',
            ['user' => $staff, 'department_id' => $deptCode]
        );

        return redirect()->route('ssh.staff.index')->with('success', "Faculty {$staff->username} profile updated successfully!");
    }

    /**
     * Delete S&H Faculty
     */
    public function destroyStaff($id)
    {
        $staff = User::role('sta')->with(['profile', 'courses', 'assignments.questions', 'assignments.submissions.answers', 'courseMaterials'])->findOrFail($id);
        $username = $staff->username;
        $fullName = $staff->full_name;
        $deptId = $staff->profile->departments_id ?? 'ssh';

        $coursesCount = $staff->courses->count();
        $materialsCount = $staff->courseMaterials->count();
        $assignmentsCount = $staff->assignments->count();
        $submissionsCount = 0;
        $answersCount = 0;
        foreach ($staff->assignments as $a) {
            $submissionsCount += $a->submissions->count();
            foreach ($a->submissions as $s) {
                $answersCount += $s->answers->count();
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($staff, $username, $fullName) {
            // 1. Delete course materials
            foreach ($staff->courseMaterials as $material) {
                if ($material->url_or_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->url_or_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($material->url_or_path);
                }
                $material->delete();
            }

            // 2. Delete assignments and cascade
            foreach ($staff->assignments as $assignment) {
                if ($assignment->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($assignment->attachment_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->attachment_path);
                }

                foreach ($assignment->submissions as $submission) {
                    if ($submission->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($submission->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->file_path);
                    }
                    $submission->answers()->delete();
                    $submission->delete();
                }

                $assignment->questions()->delete();
                $assignment->delete();
            }

            // 3. Detach allocations
            $staff->courses()->detach();

            // 4. Delete profile & photo
            $profile = $staff->profile;
            if ($profile) {
                if ($profile->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->photo);
                }
                $profile->delete();
            }

            // 5. Delete staff user
            $staff->delete();
        });

        \App\Services\ActivityLogger::log(
            'cascade_ssh_staff_deleted',
            'S&H Faculty & Cascading Records Deleted',
            'Staff',
            "SSH Directorate permanently deleted faculty member {$fullName} ({$username}). Cascaded removals: unallocated from {$coursesCount} course(s), deleted {$materialsCount} material(s), and removed {$assignmentsCount} assignment(s) with {$submissionsCount} student submission(s).",
            'danger',
            [
                'department_id' => $deptId,
                'entity_type'   => 'User',
                'entity_id'     => $id,
                'entity_name'   => "{$fullName} ({$username})",
                'payload'       => [
                    'staff_username'      => $username,
                    'staff_name'          => $fullName,
                    'courses_unallocated' => $coursesCount,
                    'materials_deleted'   => $materialsCount,
                    'assignments_deleted' => $assignmentsCount,
                    'submissions_deleted' => $submissionsCount,
                    'answers_deleted'     => $answersCount,
                ]
            ]
        );

        return redirect()->route('ssh.staff.index')->with('success', "Faculty {$username} and all cascading data removed successfully.");
    }

    /**
     * Edit 1st Year Course Form
     */
    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        $regulations = Regulation::where('status', 'Active')->get();
        $departments = Department::all();
        $existingCourseCodes = Course::where('id', '!=', $course->id)->pluck('code')->map(fn($c) => strtoupper(trim($c)))->unique()->values();

        return view('ssh.courses.edit', compact('course', 'regulations', 'departments', 'existingCourseCodes'));
    }

    /**
     * Update 1st Year Course
     */
    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'credits' => 'required|numeric|min:0|max:10',
            'semester' => 'required|integer|in:1,2',
            'regulation_id' => 'required|exists:regulations,id',
            'department_id' => 'required|exists:departments,code',
            'type' => 'nullable|string|in:Theory,Practical,Integrated',
        ]);

        $courseCode = strtoupper(trim($validated['code']));
        if (Course::where('id', '!=', $course->id)->whereRaw('UPPER(TRIM(code)) = ?', [$courseCode])->exists()) {
            return back()->withInput()->withErrors([
                'code' => "Course code '{$courseCode}' already exists for another course.",
            ]);
        }

        $course->update([
            'name' => $validated['name'],
            'code' => $courseCode,
            'credits' => $validated['credits'],
            'semester' => $validated['semester'],
            'year' => 1,
            'regulation_id' => $validated['regulation_id'],
            'department_id' => $validated['department_id'],
            'type' => $validated['type'] ?? $course->type,
        ]);

        \App\Services\ActivityLogger::log(
            'ssh_course_updated',
            '1st Year Subject Updated',
            'Academics',
            'SSH Department updated syllabus/course details for ' . $course->name . ' (' . $course->code . ').',
            'info',
            ['department_id' => $course->department_id]
        );

        return redirect()->route('ssh.courses.index')->with('success', "Course {$course->code} updated successfully!");
    }

    /**
     * Store 1st Year Course Study Material
     */
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:link,file',
            'url' => 'nullable|required_if:type,link|url',
            'file' => 'nullable|required_if:type,file|file|mimes:pdf,doc,docx,ppt,pptx,pps,ppsx,xls,xlsx,csv,txt,zip,rar,png,jpg,jpeg,webp|max:25600',
        ]);

        $course = Course::findOrFail($request->course_id);
        $user = Auth::user();

        $material = new CourseMaterial();
        $material->course_id = $course->id;
        $material->staff_id = $user->id;
        $material->title = $request->title;
        $material->type = $request->type;
        $material->platform = $request->type === 'link' ? 'web' : 'file';

        if ($request->type === 'link') {
            $material->url_or_path = $request->url;
        } else {
            $file = $request->file('file');
            $cleanName = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'ssh_' . $course->code . '_' . time() . '_' . $cleanName . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('materials/' . $course->code, $filename, 'public');
            $material->url_or_path = $path;
        }

        $material->save();

        \App\Services\ActivityLogger::log(
            'ssh_material_uploaded',
            '1st Year Study Material Published',
            'Materials',
            'SSH Department uploaded learning material "' . $material->title . '" for ' . $course->name . ' (' . $course->code . ').',
            'success',
            ['department_id' => $course->department_id]
        );

        return back()->with('success', "Study material \"{$material->title}\" uploaded successfully!");
    }

    /**
     * Delete Study Material
     */
    public function destroyMaterial($id)
    {
        $material = CourseMaterial::with('course')->findOrFail($id);
        $title = $material->title;
        $courseCode = $material->course->code ?? 'General';
        $deptId = $material->course->department_id ?? null;

        if ($material->type === 'file' && $material->url_or_path) {
            Storage::disk('public')->delete($material->url_or_path);
        }

        $material->delete();

        \App\Services\ActivityLogger::log(
            'ssh_material_deleted',
            '1st Year Study Material Removed',
            'Materials',
            "SSH Directorate deleted study material \"{$title}\" from course {$courseCode}.",
            'warning',
            [
                'department_id' => $deptId,
                'entity_type'   => 'CourseMaterial',
                'entity_id'     => $id,
                'entity_name'   => $title,
                'payload'       => [
                    'course_code' => $courseCode,
                    'title'       => $title
                ]
            ]
        );

        return back()->with('success', "Material \"{$title}\" removed successfully.");
    }

    /**
     * Create 1st Year Assignment Form
     */
    public function createAssignment()
    {
        $courses = Course::where('year', 1)->with('department')->get();
        return view('ssh.assignments.create', compact('courses'));
    }

    /**
     * Store 1st Year Assignment
     */
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'total_marks' => 'required|numeric|min:1|max:100',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg|max:10240',
        ]);

        $course = Course::findOrFail($request->course_id);
        $user = Auth::user();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('assignments/' . $course->code, 'public');
        }

        $assignment = Assignment::create([
            'course_id' => $course->id,
            'staff_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'total_marks' => $request->total_marks,
            'attachment_path' => $attachmentPath,
            'status' => 'published',
        ]);

        \App\Services\ActivityLogger::log(
            'ssh_assignment_created',
            '1st Year Assignment Published',
            'Assignments',
            'SSH Department created assignment "' . $assignment->title . '" for ' . $course->name . ' (' . $course->code . ').',
            'success',
            ['department_id' => $course->department_id]
        );

        return redirect()->route('ssh.assignments.index')->with('success', "Assignment \"{$assignment->title}\" created successfully!");
    }

    /**
     * Delete Assignment
     */
    public function destroyAssignment($id)
    {
        $assignment = Assignment::with(['course', 'submissions.answers', 'questions'])->findOrFail($id);
        $title = $assignment->title;
        $courseCode = $assignment->course->code ?? 'General';
        $deptId = $assignment->course->department_id ?? null;
        $submissionsCount = $assignment->submissions->count();
        $questionsCount = $assignment->questions->count();

        \Illuminate\Support\Facades\DB::transaction(function () use ($assignment) {
            if ($assignment->attachment_path && Storage::disk('public')->exists($assignment->attachment_path)) {
                Storage::disk('public')->delete($assignment->attachment_path);
            }

            foreach ($assignment->submissions as $sub) {
                if ($sub->file_path && Storage::disk('public')->exists($sub->file_path)) {
                    Storage::disk('public')->delete($sub->file_path);
                }
                \Illuminate\Support\Facades\DB::table('assignment_answers')->where('submission_id', $sub->id)->delete();
                $sub->delete();
            }

            $assignment->questions()->delete();
            $assignment->delete();
        });

        \App\Services\ActivityLogger::log(
            'cascade_ssh_assignment_deleted',
            '1st Year Assignment & Submissions Deleted',
            'Assignments',
            "SSH Directorate permanently deleted assignment \"{$title}\" for course {$courseCode}. Cascaded removals: {$submissionsCount} student submission(s) and {$questionsCount} question(s).",
            'danger',
            [
                'department_id' => $deptId,
                'entity_type'   => 'Assignment',
                'entity_id'     => $id,
                'entity_name'   => $title,
                'payload'       => [
                    'course_code'         => $courseCode,
                    'assignment_title'    => $title,
                    'submissions_deleted' => $submissionsCount,
                    'questions_deleted'   => $questionsCount,
                ]
            ]
        );

        return back()->with('success', "Assignment \"{$title}\" and all cascading records deleted.");
    }

    /**
     * View Submissions for an Assignment
     */
    public function assignmentSubmissions($id)
    {
        $assignment = Assignment::with(['course.department', 'submissions.user.profile'])->findOrFail($id);
        return view('ssh.assignments.submissions', compact('assignment'));
    }

    /**
     * Download CSV Template for 1st Year Freshers
     */
    public function downloadStudentTemplate()
    {
        $filename = 'SSH_1st_Year_Fresher_Template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            // CSV Header
            fputcsv($handle, ['register_number', 'first_name', 'middle_name', 'last_name', 'email', 'phone', 'school_code', 'department_code', 'level', 'program_code']);
            // Sample Rows
            fputcsv($handle, ['261FA04001', 'Aditya', 'Kumar', 'Sharma', 'aditya.26@vignan.ac.in', '9876543210', 'sc_cse', 'dep_cse', 'UG', 'btech_cse']);
            fputcsv($handle, ['261FA05002', 'Bhavana', '', 'Reddy', 'bhavana.26@vignan.ac.in', '9876543211', 'sc_ece', 'dep_ece', 'UG', 'btech_ece']);
            fputcsv($handle, ['261FA08003', 'Chaitanya', 'Sai', 'Verma', 'chaitanya.26@vignan.ac.in', '9876543212', 'sc_mec', 'dep_mec', 'UG', 'btech_mec']);
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Bulk Upload 1st Year Freshers via CSV
     */
    public function bulkUploadStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        $successCount = 0;
        $errors = [];
        $studentRole = \App\Models\Role::firstOrCreate(['name' => 'stu']);

        if ($ext === 'csv' || $ext === 'txt') {
            if (($handle = fopen($path, 'r')) !== false) {
                $header = fgetcsv($handle);
                $cleanHeader = array_map(function($h) {
                    return strtolower(trim(str_replace([' ', '_', '-'], '', $h)));
                }, $header ?: []);

                $rowNum = 1;
                while (($data = fgetcsv($handle)) !== false) {
                    $rowNum++;
                    if (empty(array_filter($data))) continue;

                    $row = [];
                    foreach ($data as $idx => $val) {
                        $key = $cleanHeader[$idx] ?? $idx;
                        $row[$key] = trim($val);
                    }

                    $regNo = strtoupper($row['registernumber'] ?? $row['regno'] ?? $row['username'] ?? $row[0] ?? '');
                    $firstName = $row['firstname'] ?? $row['fname'] ?? $row[1] ?? '';
                    $middleName = $row['middlename'] ?? $row['mname'] ?? $row[2] ?? null;
                    $lastName = $row['lastname'] ?? $row['lname'] ?? $row[3] ?? '';
                    $email = $row['email'] ?? $row[4] ?? null;
                    $phone = $row['phone'] ?? $row['phonenumber'] ?? $row[5] ?? null;
                    $schoolCode = $row['schoolcode'] ?? $row['school'] ?? $row[6] ?? 'sc_cse';
                    $deptCode = $row['departmentcode'] ?? $row['department'] ?? $row[7] ?? 'dep_cse';
                    $level = $row['level'] ?? $row[8] ?? 'UG';
                    $programCode = $row['programcode'] ?? $row['program'] ?? $row[9] ?? null;

                    if (empty($regNo) || empty($firstName) || empty($lastName)) {
                        $errors[] = "Row {$rowNum}: Missing Register Number or Name.";
                        continue;
                    }

                    if (User::where('username', $regNo)->exists() || Profile::where('username', $regNo)->exists()) {
                        $errors[] = "Row {$rowNum}: Register Number {$regNo} already exists.";
                        continue;
                    }

                    try {
                        $profile = Profile::create([
                            'first_name' => $firstName,
                            'middle_name' => $middleName,
                            'last_name' => $lastName,
                            'username' => $regNo,
                            'email' => $email,
                            'phone' => $phone,
                            'schools_id' => $schoolCode,
                            'departments_id' => $deptCode,
                            'level' => $level,
                            'programs_id' => $programCode,
                            'designation' => 'Student',
                            'roles_id' => 'stu',
                        ]);

                        User::create([
                            'username' => $regNo,
                            'password' => Hash::make('Student#963'),
                            'profile_id' => $profile->id,
                        ]);

                        $successCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNum} ({$regNo}): " . $e->getMessage();
                    }
                }
                fclose($handle);
            }
        } else {
            // Excel integration via Laravel-Excel if available
            try {
                \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\StudentsImport, $file);
                $successCount = 1;
            } catch (\Exception $e) {
                return back()->with('error', 'Error parsing Excel spreadsheet: ' . $e->getMessage());
            }
        }

        \App\Services\ActivityLogger::log(
            'ssh_freshers_bulk_upload',
            '1st Year Freshers Bulk Uploaded',
            'Students',
            'SSH Directorate onboarded ' . $successCount . ' freshers in bulk.',
            'success'
        );

        $msg = "Successfully onboarded {$successCount} first-year students!";
        if (!empty($errors)) {
            $msg .= " (" . count($errors) . " records skipped due to duplicates or formatting).";
        }

        return redirect()->route('ssh.students.index')->with('success', $msg);
    }
}
