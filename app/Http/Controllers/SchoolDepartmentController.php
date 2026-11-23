<?php

namespace App\Http\Controllers;

use App\Helpers\RegisterNumberParser;
use App\Models\Course;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Program;
use App\Models\School;
use App\Services\ActivityLogger;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolDepartmentController extends Controller
{
    /**
     * Display the dynamic Schools and Departments directory.
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'departments'); // 'departments' | 'schools' | 'programs'
        if (!in_array($activeTab, ['departments', 'schools', 'programs'])) {
            $activeTab = 'departments';
        }
        $selectedSchool = $request->query('school', 'all');
        $searchTerm = trim($request->query('search', ''));

        // All schools with count of departments
        $schools = School::withCount('departments')
            ->orderBy('name', 'asc')
            ->get();
        $schoolsMap = $schools->keyBy('code');

        // Detailed collegiate schools with ordered departments (by creation ID so 1st created department is 1st)
        $schoolsWithDepts = School::with(['departments' => function ($q) {
            $q->with('programs')->withCount([
                'profiles as faculty_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['sta', 'staff']);
                },
                'profiles as student_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['stu', 'student']);
                },
                'courses as courses_count',
                'programs as programs_count'
            ])->orderBy('id', 'asc');
        }])->withCount('departments')->orderBy('name', 'asc')->get();

        foreach ($schoolsWithDepts as $sch) {
            $sch->faculty_count = $sch->departments->sum('faculty_count');
            $sch->student_count = $sch->departments->sum('student_count');
            $sch->courses_count = $sch->departments->sum('courses_count');
            $sch->programs_count = $sch->departments->sum('programs_count');
        }

        // Query for departments
        $deptQuery = Department::with(['school', 'programs'])
            ->withCount([
                'profiles as faculty_count' => function ($q) {
                    $q->whereIn('roles_id', ['sta', 'staff']);
                },
                'profiles as student_count' => function ($q) {
                    $q->whereIn('roles_id', ['stu', 'student']);
                },
                'courses as courses_count',
                'programs as programs_count'
            ])
            ->orderBy('name', 'asc');

        if ($selectedSchool !== 'all' && !empty($selectedSchool)) {
            $deptQuery->where('school_id', $selectedSchool);
        }

        if (!empty($searchTerm) && $activeTab === 'departments') {
            $deptQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('school_id', 'like', "%{$searchTerm}%");
            });
        }

        // Full collection of matching departments for the interactive slider & inspector
        $allDepartments = (clone $deptQuery)->get();

        $departments = $deptQuery->paginate(50, ['*'], 'dept_page')->withQueryString();

        // Auto-heal out-of-bounds page or stale query parameter
        if ($departments->currentPage() > $departments->lastPage() && $departments->lastPage() > 0) {
            $departments = (clone $deptQuery)->paginate(50, ['*'], 'dept_page', 1)->withQueryString();
        }

        // Fallback: If departments is empty but departments exist, load all departments so table is never blank
        if ($departments->isEmpty() && Department::count() > 0) {
            $fallbackQuery = Department::with(['school', 'programs'])
                ->withCount([
                    'profiles as faculty_count' => function ($q) {
                        $q->whereIn('roles_id', ['sta', 'staff']);
                    },
                    'profiles as student_count' => function ($q) {
                        $q->whereIn('roles_id', ['stu', 'student']);
                    },
                    'courses as courses_count',
                    'programs as programs_count'
                ])
                ->orderBy('name', 'asc');
            $departments = $fallbackQuery->paginate(50, ['*'], 'dept_page', 1)->withQueryString();
            $allDepartments = (clone $fallbackQuery)->get();
        }

        // Query for schools tab
        $schoolListQuery = School::with(['departments' => function ($q) {
            $q->withCount([
                'profiles as faculty_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['sta', 'staff']);
                },
                'profiles as student_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['stu', 'student']);
                },
                'programs as programs_count'
            ]);
        }, 'departments.programs'])
            ->withCount('departments')
            ->orderBy('name', 'asc');

        if (!empty($searchTerm) && $activeTab === 'schools') {
            $schoolListQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }

        $schoolsList = $schoolListQuery->paginate(15, ['*'], 'school_page')->withQueryString();
        if ($schoolsList->currentPage() > $schoolsList->lastPage() && $schoolsList->lastPage() > 0) {
            $schoolsList = (clone $schoolListQuery)->paginate(15, ['*'], 'school_page', 1)->withQueryString();
        }
        foreach ($schoolsList as $sch) {
            $sch->faculty_count = $sch->departments->sum('faculty_count');
            $sch->student_count = $sch->departments->sum('student_count');
            $sch->programs_count = $sch->departments->sum('programs_count');
        }

        // Query for programs tab
        $selectedProgramDept = $request->query('dept', 'all');
        $selectedProgramLevel = $request->query('level', 'all');

        $programQuery = Program::with(['department.school'])
            ->withCount([
                'profiles as student_count' => function ($q) {
                    $q->whereIn('roles_id', ['stu', 'student']);
                }
            ])
            ->orderBy('name', 'asc');

        // Only apply program-specific filters if active tab is programs
        if ($activeTab === 'programs') {
            if ($selectedProgramDept !== 'all' && !empty($selectedProgramDept)) {
                $programQuery->where('department_id', $selectedProgramDept);
            }

            if ($selectedProgramLevel !== 'all' && !empty($selectedProgramLevel)) {
                $programQuery->where('level', $selectedProgramLevel);
            }

            if (!empty($searchTerm)) {
                $programQuery->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('code', 'like', "%{$searchTerm}%");
                });
            }
        }

        // Paginate programs with high threshold (100) so all 87 programs are in DOM for client-side live filtering
        $programsList = $programQuery->paginate(100, ['*'], 'prog_page')->withQueryString();
        if ($programsList->currentPage() > $programsList->lastPage() && $programsList->lastPage() > 0) {
            $programsList = (clone $programQuery)->paginate(100, ['*'], 'prog_page', 1)->withQueryString();
        }

        // Auto-heal fallback: If programsList is empty but programs exist in DB, ensure programs are never blank
        if ($programsList->isEmpty() && Program::count() > 0) {
            $fallbackProgQuery = Program::with(['department.school'])
                ->withCount([
                    'profiles as student_count' => function ($q) {
                        $q->whereIn('roles_id', ['stu', 'student']);
                    }
                ])
                ->orderBy('name', 'asc');
            $programsList = $fallbackProgQuery->paginate(100, ['*'], 'prog_page', 1)->withQueryString();
        }
        $totalProgramsCount = Program::count();
        $allDepartmentsForDropdown = Department::with('school')->orderBy('name', 'asc')->get();

        // Register Number Parser Dynamic Mappings & Lookup Data
        $courseMappings = RegisterNumberParser::getCourseMapping();
        $departmentMappings = RegisterNumberParser::getDepartmentMapping();
        $allMappingsWithSchools = RegisterNumberParser::getAllMappingsWithSchools();
        $dynamicMappingsList = RegisterNumberParser::getDynamicMappingsList();

        // Aggregate statistics
        $totalSchoolsCount = $schools->count();
        $totalDepartmentsCount = Department::count();
        $totalFacultyCount = Profile::whereIn('roles_id', ['sta', 'staff'])->count();
        $totalStudentsCount = Profile::whereIn('roles_id', ['stu', 'student'])->count();
        $totalCoursesCount = Course::count();
        $totalParserMappingsCount = count($allMappingsWithSchools);
        $dynamicMappingsCount = count($dynamicMappingsList);

        return view('academic.schools_departments', compact(
            'schools',
            'schoolsMap',
            'schoolsWithDepts',
            'departments',
            'allDepartments',
            'schoolsList',
            'programsList',
            'totalProgramsCount',
            'allDepartmentsForDropdown',
            'selectedProgramDept',
            'selectedProgramLevel',
            'activeTab',
            'selectedSchool',
            'searchTerm',
            'totalSchoolsCount',
            'totalDepartmentsCount',
            'totalFacultyCount',
            'totalStudentsCount',
            'totalCoursesCount',
            'totalParserMappingsCount',
            'dynamicMappingsCount',
            'courseMappings',
            'departmentMappings',
            'allMappingsWithSchools',
            'dynamicMappingsList'
        ));
    }

    /**
     * Store a newly created School.
     */
    public function storeSchool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ], [
            'name.required' => 'Please enter the full name of the School.',
            'code.required' => 'Please enter the School Code.',
        ]);

        $schoolCode = Str::slug($request->code, '_');
        if (!str_starts_with($schoolCode, 'sc_')) {
            $schoolCode = 'sc_' . $schoolCode;
        }

        // Double check code uniqueness after normalization
        if (School::where('code', $schoolCode)->exists()) {
            return back()->withInput()->with('error', "A School with code '{$schoolCode}' already exists.");
        }

        $createdSchool = School::create([
            'code' => $schoolCode,
            'name' => mb_strtoupper(trim($request->name)),
        ]);

        CacheService::invalidateSchools();

        ActivityLogger::log(
            'school_created',
            'New School Created',
            'Academics',
            "Created academic school '{$createdSchool->name}' with code '{$createdSchool->code}'.",
            'success',
            ['entity_type' => 'School', 'entity_id' => $createdSchool->id, 'entity_name' => $createdSchool->name]
        );

        return redirect()->route('academic.departments.index', ['school' => $createdSchool->code, 'tab' => 'schools'])
            ->with('success', "Collegiate School '{$createdSchool->name}' ({$createdSchool->code}) was created successfully.");
    }

    /**
     * Update an existing School.
     */
    public function updateSchool(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $school->id,
        ]);

        $oldCode = $school->code;
        $newCode = Str::slug($request->code, '_');

        DB::transaction(function () use ($school, $request, $oldCode, $newCode) {
            $school->name = mb_strtoupper(trim($request->name));
            $school->code = $newCode;
            $school->save();

            // If school code changed, cascade update to departments and profiles
            if ($oldCode !== $newCode) {
                Department::where('school_id', $oldCode)->update(['school_id' => $newCode]);
                Profile::where('schools_id', $oldCode)->update(['schools_id' => $newCode]);
            }
        });

        CacheService::invalidateSchools();
        CacheService::invalidateDepartments();

        ActivityLogger::log(
            'school_updated',
            'School Updated',
            'Academics',
            "Updated school details for '{$school->name}' ({$school->code}).",
            'info',
            ['entity_type' => 'School', 'entity_id' => $school->id, 'entity_name' => $school->name]
        );

        return back()->with('success', "School '{$school->name}' was updated successfully.");
    }

    /**
     * Delete an existing School along with all cascading linked data (departments, programs, courses, etc.).
     */
    public function destroySchool($id)
    {
        $school = School::with(['departments.programs'])->findOrFail($id);

        $schoolName = $school->name;
        $schoolCode = $school->code;
        $deptsCount = $school->departments->count();
        $progsCount = $school->departments->sum(fn($d) => $d->programs->count());

        DB::transaction(function () use ($school, $schoolCode) {
            $departmentIds = $school->departments->pluck('id')->toArray();
            $departmentCodes = $school->departments->pluck('code')->toArray();

            // 1. Delete all degree programs under this school's departments
            if (!empty($departmentIds)) {
                Program::whereIn('department_id', $departmentIds)->delete();
            }

            // 2. Cascade delete any courses under these departments (triggers Eloquent cascade hooks)
            if (!empty($departmentCodes)) {
                $courses = Course::whereIn('department_id', $departmentCodes)->get();
                foreach ($courses as $course) {
                    $course->delete();
                }

                // 3. Clear profile references to this school and its departments
                Profile::where('schools_id', $schoolCode)
                    ->orWhereIn('departments_id', $departmentCodes)
                    ->update([
                        'schools_id' => null,
                        'departments_id' => null,
                        'programs_id' => null
                    ]);

                // 4. Delete the departments
                Department::whereIn('id', $departmentIds)->delete();
            } else {
                // If school had no departments, still unbind profiles directly referencing the school
                Profile::where('schools_id', $schoolCode)
                    ->update([
                        'schools_id' => null,
                        'departments_id' => null,
                        'programs_id' => null
                    ]);
            }

            // 5. Delete the school itself
            $school->delete();
        });

        CacheService::invalidateSchools();
        CacheService::invalidateDepartments();

        ActivityLogger::log(
            'school_cascade_deleted',
            'School & Linked Data Deleted',
            'Academics',
            "Permanently deleted collegiate school '{$schoolName}' ({$schoolCode}) along with {$deptsCount} department(s) and {$progsCount} degree program(s).",
            'danger',
            ['entity_type' => 'School', 'entity_name' => $schoolName, 'departments_removed' => $deptsCount, 'programs_removed' => $progsCount]
        );

        return back()->with('success', "Collegiate School '{$schoolName}' and all linked data ({$deptsCount} departments, {$progsCount} programs) were permanently removed.");
    }

    /**
     * Store a newly created Department with optional Register Number mapping.
     */
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'school_id'   => 'required|string|exists:schools,code',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50',
            'course_code' => 'nullable|string|max:5',
            'reg_code'    => 'nullable|string|max:10',
            'prog_name'   => 'nullable|string|max:255',
            'prog_code'   => 'nullable|string|max:50',
            'prog_level'  => 'nullable|string|in:UG,PG,PhD,Diploma',
        ], [
            'school_id.required' => 'Please select an academic School for this department.',
            'school_id.exists'   => 'The selected School code is invalid.',
            'name.required'      => 'Please enter the Department Name.',
            'code.required'      => 'Please enter the Department Code.',
        ]);

        $code = Str::slug($request->code, '_');
        if (!str_starts_with($code, 'dep_')) {
            $code = 'dep_' . $code;
        }

        if (Department::where('code', $code)->exists()) {
            return back()->withInput()->with('error', "A Department with code '{$code}' already exists.");
        }

        $department = null;
        $school = School::where('code', $request->school_id)->first();

        DB::transaction(function () use ($request, $code, $school, &$department) {
            $department = Department::create([
                'code'      => $code,
                'school_id' => $request->school_id,
                'name'      => trim($request->name),
            ]);

            // Create program if provided
            if ($request->filled('prog_name')) {
                $progCode = $request->prog_code ? Str::slug($request->prog_code, '_') : Str::slug($request->prog_name, '_');
                if (Program::where('code', $progCode)->exists()) {
                    $progCode .= '_' . Str::random(3);
                }
                Program::create([
                    'department_id'  => $department->id,
                    'code'           => $progCode,
                    'name'           => trim($request->prog_name),
                    'level'          => $request->prog_level ?: 'UG',
                    'duration_years' => (int) ($request->prog_duration ?: 4),
                ]);
            }

            // If registration codes are provided, register dynamic mapping in RegisterNumberParser
            if ($request->filled('course_code') && $request->filled('reg_code')) {
                $cCode = strtoupper(trim($request->course_code));
                $allCourses = RegisterNumberParser::getCourseMapping();
                $cName = $allCourses[$cCode] ?? ($request->course_name ?: 'Degree Program');

                RegisterNumberParser::registerDynamicMapping(
                    $cCode,
                    $cName,
                    str_pad(trim($request->reg_code), 2, '0', STR_PAD_LEFT),
                    $department->name,
                    $school ? $school->code : $department->school_id,
                    $school ? $school->name : null,
                    $department->code
                );
            }
        });

        CacheService::invalidateDepartments();
        CacheService::invalidatePrograms();

        ActivityLogger::log(
            'department_created',
            'New Department Created',
            'Academics',
            "Created department '{$department->name}' ({$department->code}) under school '{$department->school_id}'.",
            'success',
            ['entity_type' => 'Department', 'entity_id' => $department->id, 'entity_name' => $department->name, 'department_id' => $department->code]
        );

        $msg = "Department '{$department->name}' ({$department->code}) was created successfully.";
        if ($request->filled('course_code') && $request->filled('reg_code')) {
            $msg .= " Register code [{$request->course_code}:{$request->reg_code}] mapped to parser.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Update an existing Department.
     */
    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'school_id' => 'required|string|exists:schools,code',
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:departments,code,' . $department->id,
        ]);

        $oldCode = $department->code;
        $newCode = Str::slug($request->code, '_');

        DB::transaction(function () use ($department, $request, $oldCode, $newCode) {
            $department->name      = trim($request->name);
            $department->school_id = $request->school_id;
            $department->code      = $newCode;
            $department->save();

            // If department code changed, cascade update to profiles and courses
            if ($oldCode !== $newCode) {
                Profile::where('departments_id', $oldCode)->update(['departments_id' => $newCode]);
                Course::where('department_id', $oldCode)->update(['department_id' => $newCode]);
            }
        });

        CacheService::invalidateDepartments();

        ActivityLogger::log(
            'department_updated',
            'Department Updated',
            'Academics',
            "Updated department '{$department->name}' ({$department->code}).",
            'info',
            ['entity_type' => 'Department', 'entity_id' => $department->id, 'entity_name' => $department->name, 'department_id' => $department->code]
        );

        return back()->with('success', "Department '{$department->name}' was updated successfully.");
    }

    /**
     * Delete an existing Department.
     */
    /**
     * Delete an existing Department with cascading cleanup of linked programs, courses, and profile bindings.
     */
    public function destroyDepartment($id)
    {
        $department = Department::with('programs')->findOrFail($id);

        $deptName = $department->name;
        $deptCode = $department->code;
        $progsCount = $department->programs ? $department->programs->count() : 0;

        DB::transaction(function () use ($department, $deptCode) {
            // 1. Delete all degree programs under this department
            Program::where('department_id', $department->id)->delete();

            // 2. Cascade delete any courses under this department code (triggers Eloquent cascade hooks)
            $courses = Course::where('department_id', $deptCode)->get();
            foreach ($courses as $course) {
                $course->delete();
            }

            // 3. Clear profile references to this department
            Profile::where('departments_id', $deptCode)
                ->update([
                    'departments_id' => null,
                    'programs_id' => null
                ]);

            // 4. Delete the department itself
            $department->delete();
        });

        CacheService::invalidateDepartments();
        CacheService::invalidatePrograms();
        CacheService::invalidateSchools();

        ActivityLogger::log(
            'department_deleted',
            'Department Deleted',
            'Academics',
            "Deleted department '{$deptName}' ({$deptCode}) and cascadingly removed {$progsCount} linked degree program(s).",
            'warning',
            ['entity_type' => 'Department', 'entity_name' => $deptName, 'department_id' => $deptCode]
        );

        return back()->with('success', "Department '{$deptName}' and all linked academic programs & records were successfully deleted.");
    }

    /**
     * Store or update a dynamic register number mapping.
     */
    public function storeDynamicMapping(Request $request)
    {
        $request->validate([
            'course_code'     => 'required|string|max:5',
            'course_name'     => 'required|string|max:255',
            'dept_code'       => 'required|string|max:10',
            'department_name' => 'required|string|max:255',
            'school_code'     => 'nullable|string|exists:schools,code',
            'db_dept_code'    => 'nullable|string|max:50',
        ]);

        $courseCode = strtoupper(trim($request->course_code));
        $deptCode = str_pad(trim($request->dept_code), 2, '0', STR_PAD_LEFT);
        $school = $request->school_code ? School::where('code', $request->school_code)->first() : null;

        $saved = RegisterNumberParser::registerDynamicMapping(
            $courseCode,
            $request->course_name,
            $deptCode,
            $request->department_name,
            $school ? $school->code : null,
            $school ? $school->name : null,
            $request->db_dept_code
        );

        if (!$saved) {
            return back()->with('error', 'Failed to save dynamic register number mapping.');
        }

        ActivityLogger::log(
            'dynamic_mapping_created',
            'Dynamic Register Mapping Saved',
            'Academics',
            "Mapped Course '{$courseCode}' ({$request->course_name}) + Dept Code '{$deptCode}' to '{$request->department_name}'.",
            'success',
            ['course_code' => $courseCode, 'dept_code' => $deptCode, 'department_name' => $request->department_name]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Dynamic mapping for {$courseCode}-{$deptCode} registered successfully.",
            ]);
        }

        return redirect()->route('academic.departments.index', ['tab' => 'parser'])
            ->with('success', "Dynamic registration mapping [{$courseCode} : {$deptCode} -> {$request->department_name}] was registered successfully.");
    }

    /**
     * Remove a dynamic register number mapping.
     */
    public function destroyDynamicMapping(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:5',
            'dept_code'   => 'required|string|max:10',
        ]);

        $courseCode = strtoupper(trim($request->course_code));
        $deptCode = str_pad(trim($request->dept_code), 2, '0', STR_PAD_LEFT);

        $removed = RegisterNumberParser::removeDynamicMapping($courseCode, $deptCode);

        ActivityLogger::log(
            'dynamic_mapping_deleted',
            'Dynamic Register Mapping Removed',
            'Academics',
            "Removed dynamic mapping for Course '{$courseCode}', Dept Code '{$deptCode}'.",
            'warning',
            ['course_code' => $courseCode, 'dept_code' => $deptCode]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $removed,
                'message' => $removed ? 'Dynamic mapping removed successfully.' : 'Mapping not found.',
            ]);
        }

        return redirect()->route('academic.departments.index', ['tab' => 'parser'])
            ->with('success', "Dynamic mapping for [{$courseCode} : {$deptCode}] was removed.");
    }

    /**
     * Test / inspect any student register number live via AJAX.
     */
    public function testRegisterNumber(Request $request)
    {
        $regNo = trim($request->query('reg_no', ''));
        if (empty($regNo)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Please provide a 10-character student registration number to test.',
            ], 422);
        }

        $parsed = RegisterNumberParser::parse($regNo);

        if (!$parsed) {
            return response()->json([
                'valid'           => false,
                'register_number' => strtoupper($regNo),
                'message'         => "Registration number '{$regNo}' does not match standard 10-character university schema or contains unmapped course/dept codes.",
            ]);
        }

        return response()->json([
            'valid' => true,
            'data'  => $parsed,
        ]);
    }

    /**
     * Store a newly created Degree Program under a Department.
     */
    public function storeProgram(Request $request)
    {
        $request->validate([
            'department_id'  => 'required|exists:departments,id',
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:programs,code',
            'level'          => 'required|string|in:UG,PG,PhD,Diploma',
            'duration_years' => 'required|integer|min:1|max:10',
        ], [
            'department_id.required' => 'Please select the parent Department for this program.',
            'department_id.exists'   => 'The selected Department is invalid.',
            'name.required'          => 'Please enter the Degree Program Name (e.g., B.Tech in Artificial Intelligence).',
            'code.required'          => 'Please enter a unique Program Code (e.g., btech_aiml).',
            'code.unique'            => 'A Program with this code already exists.',
            'level.required'         => 'Please select the degree level (UG, PG, PhD, Diploma).',
            'duration_years.required'=> 'Please enter duration in years.',
        ]);

        $code = Str::slug($request->code, '_');

        $program = Program::create([
            'department_id'  => $request->department_id,
            'code'           => $code,
            'name'           => trim($request->name),
            'level'          => $request->level,
            'duration_years' => (int) $request->duration_years,
        ]);

        CacheService::invalidatePrograms();

        $dept = Department::find($request->department_id);
        ActivityLogger::log(
            'program_created',
            'Degree Program Created',
            'Academics',
            "Created program '{$program->name}' ({$program->code}) under department '{$dept->name}'.",
            'success',
            ['entity_type' => 'Program', 'entity_id' => $program->id, 'entity_name' => $program->name, 'department_id' => $dept ? $dept->code : null]
        );

        return redirect()->route('academic.departments.index', ['tab' => 'programs'])
            ->with('success', "Degree Program '{$program->name}' ({$program->code}) was created successfully.");
    }

    /**
     * Update an existing Degree Program.
     */
    public function updateProgram(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'department_id'  => 'required|exists:departments,id',
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:programs,code,' . $program->id,
            'level'          => 'required|string|in:UG,PG,PhD,Diploma',
            'duration_years' => 'required|integer|min:1|max:10',
        ]);

        $oldCode = $program->code;
        $newCode = Str::slug($request->code, '_');

        DB::transaction(function () use ($program, $request, $oldCode, $newCode) {
            $program->department_id  = $request->department_id;
            $program->name           = trim($request->name);
            $program->code           = $newCode;
            $program->level          = $request->level;
            $program->duration_years = (int) $request->duration_years;
            $program->save();

            if ($oldCode !== $newCode) {
                Profile::where('programs_id', $oldCode)->update(['programs_id' => $newCode]);
            }
        });

        CacheService::invalidatePrograms();

        ActivityLogger::log(
            'program_updated',
            'Degree Program Updated',
            'Academics',
            "Updated degree program '{$program->name}' ({$program->code}).",
            'info',
            ['entity_type' => 'Program', 'entity_id' => $program->id, 'entity_name' => $program->name]
        );

        return redirect()->route('academic.departments.index', ['tab' => 'programs'])
            ->with('success', "Degree Program '{$program->name}' was successfully updated.");
    }

    /**
     * Delete an existing Degree Program.
     */
    public function destroyProgram($id)
    {
        $program = Program::with('department.school')->findOrFail($id);
        $progName = $program->name;
        $progCode = $program->code;

        $unboundCount = 0;
        DB::transaction(function () use ($program, $progCode, &$unboundCount) {
            // Safely unbind any enrolled student/user profiles referencing this program
            $unboundCount = Profile::where('programs_id', $progCode)->update(['programs_id' => null]);
            $program->delete();
        });

        CacheService::invalidatePrograms();

        ActivityLogger::log(
            'program_deleted',
            'Degree Program Deleted',
            'Academics',
            "Deleted degree program '{$progName}' ({$progCode}). Safely unbound {$unboundCount} student profile(s).",
            'warning',
            ['entity_type' => 'Program', 'entity_name' => $progName]
        );

        $msg = "Degree Program '{$progName}' was successfully deleted.";
        if ($unboundCount > 0) {
            $msg .= " {$unboundCount} enrolled student profile(s) were safely unbound.";
        }

        return redirect()->route('academic.departments.index', ['tab' => 'programs'])
            ->with('success', $msg);
    }
}

