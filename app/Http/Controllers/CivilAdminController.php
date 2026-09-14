<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Department;
use App\Models\Program;
use App\Models\Profile;
use App\Models\Role;
use App\Models\CivilServiceEnrollment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CivilAdminController extends Controller
{
    /**
     * Display a listing of students enrolled in Civil Services.
     */
    public function index(Request $request)
    {
        $query = User::role('stu')
            ->whereHas('civilServiceEnrollment')
            ->with(['profile.school', 'profile.department', 'profile.program', 'civilServiceEnrollment']);

        // Search by name, email, or registration number (username)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by School
        if ($request->filled('school')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('schools_id', $request->school);
            });
        }

        // Filter by Department (ensure department belongs to selected school if school is specified)
        if ($request->filled('department')) {
            $validDept = true;
            if ($request->filled('school')) {
                $validDept = Department::where('code', $request->department)
                    ->where('school_id', $request->school)
                    ->exists();
            }
            if ($validDept) {
                $query->whereHas('profile', function ($q) use ($request) {
                    $q->where('departments_id', $request->department);
                });
            }
        }

        // Filter by Enrollment Status
        if ($request->filled('status')) {
            $query->whereHas('civilServiceEnrollment', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter by Batch Year
        if ($request->filled('batch_year')) {
            $query->whereHas('civilServiceEnrollment', function ($q) use ($request) {
                $q->where('batch_year', $request->batch_year);
            });
        }

        $students = $query->latest('id')->paginate(10)->withQueryString();
        $schools = School::where('code', '!=', 'sc_cs')->get();
        $departments = Department::where('code', '!=', 'dep_cs')->get();

        return view('civil_services.students.index', compact('students', 'schools', 'departments'));
    }

    /**
     * Show the form for creating a new student and enrolling in Civil Services.
     */
    public function create()
    {
        $schools = School::where('code', '!=', 'sc_cs')->get();
        $departments = Department::where('code', '!=', 'dep_cs')->get();
        $programs = Program::all();

        return view('civil_services.students.create', compact('schools', 'departments', 'programs'));
    }

    /**
     * Store a newly created student and automatically enroll them in Civil Services.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['required', 'string', 'unique:users,username'],
            'email' => ['nullable', 'string', 'email', 'unique:profiles,email'],
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
            'batch_year' => ['nullable', 'string', 'max:10'],
        ], [
            'school_id.required' => 'Please select the student\'s parent School.',
            'department_id.required' => 'Please select the student\'s parent Department.',
            'department_id.exists' => 'The selected department does not belong to the selected school.',
        ]);

        $password = $request->filled('password') ? $request->password : 'Student#963';
        $schoolCode = School::where('id', $validated['school_id'])->value('code');
        $deptCode = Department::where('id', $validated['department_id'])->value('code');
        $programCode = isset($validated['program_id']) ? Program::where('id', $validated['program_id'])->value('code') : null;

        // Create Profile preserving parent school & department
        $profile = Profile::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? null,
            'programs_id' => $programCode,
            'designation' => 'Student',
            'roles_id' => 'stu',
        ]);

        // Create User account
        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        // Enroll in Civil Services
        CivilServiceEnrollment::create([
            'user_id' => $user->id,
            'batch_year' => $validated['batch_year'] ?? date('Y'),
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('civil.students.index')
            ->with('success', "Student {$user->username} successfully created and enrolled into Civil Services!");
    }

    /**
     * Enroll an existing university student into Civil Services.
     */
    public function enrollExisting(Request $request)
    {
        $request->validate([
            'reg_number' => ['required', 'string'],
            'batch_year' => ['nullable', 'string', 'max:10'],
        ]);

        $regNumber = trim($request->reg_number);
        $user = User::where('username', $regNumber)->first();

        if (!$user) {
            return redirect()->back()->with('error', "No student found with Registration Number: {$regNumber}");
        }

        if ($user->role !== 'stu') {
            return redirect()->back()->with('error', "The account {$regNumber} is not a student account.");
        }

        if ($user->civilServiceEnrollment) {
            return redirect()->back()->with('warning', "Student {$regNumber} is already enrolled in Civil Services.");
        }

        CivilServiceEnrollment::create([
            'user_id' => $user->id,
            'batch_year' => $request->batch_year ?? date('Y'),
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('civil.students.index')
            ->with('success', "Student {$regNumber} has been successfully enrolled into Civil Services!");
    }

    /**
     * Unenroll or remove a student from Civil Services.
     */
    public function unenroll($id)
    {
        $enrollment = CivilServiceEnrollment::where('user_id', $id)->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'Student is not currently enrolled in Civil Services.');
        }

        $enrollment->delete();

        return redirect()->route('civil.students.index')
            ->with('success', 'Student removed from Civil Services enrollment.');
    }
}
