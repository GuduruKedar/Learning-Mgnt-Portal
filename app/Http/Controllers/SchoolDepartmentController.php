<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Profile;
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
        $activeTab = $request->query('tab', 'departments'); // 'departments' | 'schools'
        $selectedSchool = $request->query('school', 'all');
        $searchTerm = trim($request->query('search', ''));

        // All schools with count of departments
        $schools = School::withCount('departments')
            ->orderBy('name', 'asc')
            ->get();
        $schoolsMap = $schools->keyBy('code');

        // Detailed collegiate schools with ordered departments (by creation ID so 1st created department is 1st)
        $schoolsWithDepts = School::with(['departments' => function ($q) {
            $q->withCount([
                'profiles as faculty_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['sta', 'staff']);
                },
                'profiles as student_count' => function ($sq) {
                    $sq->whereIn('roles_id', ['stu', 'student']);
                },
                'courses as courses_count'
            ])->orderBy('id', 'asc');
        }])->withCount('departments')->orderBy('name', 'asc')->get();

        foreach ($schoolsWithDepts as $sch) {
            $sch->faculty_count = $sch->departments->sum('faculty_count');
            $sch->student_count = $sch->departments->sum('student_count');
            $sch->courses_count = $sch->departments->sum('courses_count');
        }

        // Query for departments
        $deptQuery = Department::with('school')
            ->withCount([
                'profiles as faculty_count' => function ($q) {
                    $q->whereIn('roles_id', ['sta', 'staff']);
                },
                'profiles as student_count' => function ($q) {
                    $q->whereIn('roles_id', ['stu', 'student']);
                },
                'courses as courses_count'
            ])
            ->orderBy('name', 'asc');

        if ($selectedSchool !== 'all' && !empty($selectedSchool)) {
            $deptQuery->where('school_id', $selectedSchool);
        }

        if (!empty($searchTerm)) {
            $deptQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%")
                  ->orWhere('school_id', 'like', "%{$searchTerm}%");
            });
        }

        // Full collection of matching departments for the interactive slider & inspector
        $allDepartments = (clone $deptQuery)->get();

        $departments = $deptQuery->paginate(15, ['*'], 'dept_page')->withQueryString();

        // Query for schools tab
        $schoolListQuery = School::with(['departments'])
            ->withCount('departments')
            ->orderBy('name', 'asc');

        if (!empty($searchTerm) && $activeTab === 'schools') {
            $schoolListQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }

        $schoolsList = $schoolListQuery->paginate(15, ['*'], 'school_page')->withQueryString();

        // Aggregate statistics
        $totalSchoolsCount = $schools->count();
        $totalDepartmentsCount = Department::count();
        $totalFacultyCount = Profile::whereIn('roles_id', ['sta', 'staff'])->count();
        $totalStudentsCount = Profile::whereIn('roles_id', ['stu', 'student'])->count();
        $totalCoursesCount = Course::count();

        return view('academic.schools_departments', compact(
            'schools',
            'schoolsMap',
            'schoolsWithDepts',
            'departments',
            'allDepartments',
            'schoolsList',
            'activeTab',
            'selectedSchool',
            'searchTerm',
            'totalSchoolsCount',
            'totalDepartmentsCount',
            'totalFacultyCount',
            'totalStudentsCount',
            'totalCoursesCount'
        ));
    }

    /**
     * Store a newly created School.
     */
    public function storeSchool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code',
        ], [
            'code.unique' => 'The School Code is already taken. Please enter a unique code.',
            'name.required' => 'Please enter the full name of the School.',
        ]);

        $code = Str::slug($request->code, '_');

        // Double check code uniqueness after normalization
        if (School::where('code', $code)->exists()) {
            return back()->withInput()->with('error', "A School with code '{$code}' already exists.");
        }

        $school = School::create([
            'code' => $code,
            'name' => trim($request->name),
        ]);

        CacheService::invalidateSchools();

        ActivityLogger::log(
            'school_created',
            'New School Created',
            'Academics',
            "Created academic school '{$school->name}' with code '{$school->code}'.",
            'success',
            ['entity_type' => 'School', 'entity_id' => $school->id, 'entity_name' => $school->name]
        );

        return back()->with('success', "School '{$school->name}' ({$school->code}) was created successfully.");
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
            $school->name = trim($request->name);
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
     * Delete an existing School.
     */
    public function destroySchool($id)
    {
        $school = School::withCount('departments')->findOrFail($id);

        // Safety check: Cannot delete school if it has child departments
        if ($school->departments_count > 0) {
            return back()->with('error', "Cannot delete School '{$school->name}' because it contains {$school->departments_count} active department(s). Please reassign or delete its departments first.");
        }

        // Safety check: Cannot delete school if profiles reference it
        $profilesCount = Profile::where('schools_id', $school->code)->count();
        if ($profilesCount > 0) {
            return back()->with('error', "Cannot delete School '{$school->name}' because {$profilesCount} faculty/student profile(s) belong to it. Please reassign them first.");
        }

        $schoolName = $school->name;
        $schoolCode = $school->code;
        $school->delete();

        CacheService::invalidateSchools();

        ActivityLogger::log(
            'school_deleted',
            'School Deleted',
            'Academics',
            "Deleted school '{$schoolName}' ({$schoolCode}).",
            'warning',
            ['entity_type' => 'School', 'entity_name' => $schoolName]
        );

        return back()->with('success', "School '{$schoolName}' was successfully deleted.");
    }

    /**
     * Store a newly created Department.
     */
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'school_id' => 'required|string|exists:schools,code',
            'name'      => 'required|string|max:255',
            'code'      => 'required|string|max:50|unique:departments,code',
        ], [
            'code.unique'        => 'The Department Code is already in use. Please enter a unique code.',
            'school_id.required' => 'Please select an academic School for this department.',
            'school_id.exists'   => 'The selected School code is invalid.',
            'name.required'      => 'Please enter the Department Name.',
        ]);

        $code = Str::slug($request->code, '_');

        if (Department::where('code', $code)->exists()) {
            return back()->withInput()->with('error', "A Department with code '{$code}' already exists.");
        }

        $department = Department::create([
            'code'      => $code,
            'school_id' => $request->school_id,
            'name'      => trim($request->name),
        ]);

        CacheService::invalidateDepartments();

        ActivityLogger::log(
            'department_created',
            'New Department Created',
            'Academics',
            "Created department '{$department->name}' ({$department->code}) under school '{$department->school_id}'.",
            'success',
            ['entity_type' => 'Department', 'entity_id' => $department->id, 'entity_name' => $department->name, 'department_id' => $department->code]
        );

        return back()->with('success', "Department '{$department->name}' ({$department->code}) was created successfully.");
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
    public function destroyDepartment($id)
    {
        $department = Department::findOrFail($id);

        // Safety check: Cannot delete department if courses are assigned to it
        $coursesCount = Course::where('department_id', $department->code)->count();
        if ($coursesCount > 0) {
            return back()->with('error', "Cannot delete Department '{$department->name}' because {$coursesCount} course(s) are registered under it. Please delete or reassign those courses first.");
        }

        // Safety check: Cannot delete department if faculty or students are assigned
        $profilesCount = Profile::where('departments_id', $department->code)->count();
        if ($profilesCount > 0) {
            return back()->with('error', "Cannot delete Department '{$department->name}' because {$profilesCount} faculty/student profile(s) belong to it. Please reassign them first.");
        }

        $deptName = $department->name;
        $deptCode = $department->code;
        $department->delete();

        CacheService::invalidateDepartments();

        ActivityLogger::log(
            'department_deleted',
            'Department Deleted',
            'Academics',
            "Deleted department '{$deptName}' ({$deptCode}).",
            'warning',
            ['entity_type' => 'Department', 'entity_name' => $deptName, 'department_id' => $deptCode]
        );

        return back()->with('success', "Department '{$deptName}' was successfully deleted.");
    }
}

