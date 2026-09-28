<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $query = User::role('stu')->with(['profile.school', 'profile.department']);
        
        if ($role === 'sa') {
            // Super Admin sees all students
        } elseif ($role === 'admin') {
            // Admin only sees students in their department
            $query->whereHas('profile', function ($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('profile', function($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('school')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('schools_id', $request->school);
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('departments_id', $request->department);
            });
        }

        if ($request->filled('program')) {
            $query->whereHas('profile', function($q) use ($request) {
                $q->where('programs_id', $request->program);
            });
        }

        $students = $query->paginate(10)->withQueryString();
        $schools = School::all();
        $departments = Department::with('school')->get();
        $programs = \App\Models\Program::with('department')->get();

        $totalStudents = ($role === 'admin') 
            ? User::role('stu')->whereHas('profile', function ($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            })->count()
            : User::role('stu')->count();

        $departmentStudentCounts = Department::with('school')
            ->withCount(['profiles as student_count' => function($q) {
                $q->where('roles_id', 'stu')->has('user');
            }])
            ->orderBy('name')
            ->get();

        $departmentsWithStudents = $departmentStudentCounts->where('student_count', '>', 0)->values();
        
        return view('students.index', compact(
            'students', 
            'schools', 
            'departments', 
            'programs',
            'totalStudents',
            'departmentsWithStudents'
        ));
    }

    public function create()
    {
        $schools = School::all();
        $departments = Department::all();
        return view('students.create', compact('schools', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'string', 'size:10', 'regex:/^\d{2}[a-zA-Z0-9]{2}[a-zA-Z0-9]{1,2}\d+$/', 'unique:users'], // Registration Number
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email'],
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => [
                Rule::requiredIf(Auth::user()->role === 'sa'),
                'nullable',
                'exists:schools,id',
            ],
            'department_id' => [
                Rule::requiredIf(Auth::user()->role === 'sa'),
                'nullable', 
                Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $schoolCode = \App\Models\School::where('id', $request->school_id)->value('code');
                    return $query->where('school_id', $schoolCode);
                })
            ],
            'level' => 'nullable|string|in:UG,PG,Diploma,PhD',
            'program_id' => [
                'nullable',
                Rule::exists('programs', 'id')->where('department_id', $request->department_id)->where('level', $request->level)
            ],
            'photo' => 'nullable|image|mimes:webp|max:2048',
        ], [
            'school_id.required' => 'The school field is required.',
            'school_id.exists' => 'The selected school is invalid.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department does not belong to the selected school.',
        ]);

        $validated['username'] = strtoupper(trim($validated['username']));

        // If Admin is creating, force their department
        if (Auth::user()->role === 'admin') {
            $validated['school_id'] = Auth::user()->profile->schools_id;
            $validated['department_id'] = Auth::user()->profile->departments_id;
        }

        $password = $request->filled('password') ? $request->password : 'Student#963';
        $studentRole = \App\Models\Role::firstOrCreate(['name' => 'stu']);
        $validated['designation'] = 'Student'; // Default designation for student

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $filename = $validated['username'] . 'profile.' . $request->file('photo')->getClientOriginalExtension();
            $photoPath = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $schoolCode = (Auth::user()->role === 'admin') ? Auth::user()->profile->schools_id : \App\Models\School::where('id', $validated['school_id'])->value('code');
        $deptCode = (Auth::user()->role === 'admin') ? Auth::user()->profile->departments_id : \App\Models\Department::where('id', $validated['department_id'])->value('code');

        $profile = \App\Models\Profile::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
            'designation' => $validated['designation'],
            'roles_id' => 'stu',
            'photo' => $photoPath,
        ]);

        $newUser = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        \App\Services\ActivityLogger::log(
            'student_created',
            'New Student Created',
            'Students',
            'Created student profile for ' . $profile->first_name . ' ' . $profile->last_name . ' (' . $profile->username . ').',
            'success',
            [
                'department_id' => $deptCode,
                'entity_type' => 'User',
                'entity_id' => $newUser->id,
                'entity_name' => $profile->first_name . ' ' . $profile->last_name,
                'payload' => ['username' => $validated['username'], 'department' => $deptCode]
            ]
        );

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show($id)
    {
        $student = User::role('stu')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $student = User::role('stu')->findOrFail($id);
        
        if ($role === 'admin' && $student->profile->departments_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized to edit this student.');
        }

        $schools = School::all();
        $departments = Department::all();
        return view('students.edit', compact('student', 'schools', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $student = User::role('stu')->findOrFail($id);

        if (Auth::user()->role === 'admin' && $student->profile->departments_id !== Auth::user()->profile->departments_id) {
            abort(403, 'Unauthorized to update this student.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'string', 'size:10', 'regex:/^\d{2}[a-zA-Z0-9]{2}[a-zA-Z0-9]{1,2}\d+$/', 'unique:users,username,'.$student->id],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email,'.$student->profile_id],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => [
                Rule::requiredIf(Auth::user()->role === 'sa'),
                'nullable',
                'exists:schools,id',
            ],
            'department_id' => [
                Rule::requiredIf(Auth::user()->role === 'sa'),
                'nullable', 
                Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $schoolCode = \App\Models\School::where('id', $request->school_id)->value('code');
                    return $query->where('school_id', $schoolCode);
                })
            ],
            'level' => 'nullable|string|in:UG,PG,Diploma,PhD',
            'program_id' => [
                'nullable',
                Rule::exists('programs', 'id')->where('department_id', $request->department_id)->where('level', $request->level)
            ],
            'photo' => 'nullable|image|mimes:webp|max:2048',
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
        ], [
            'username.size' => 'The Register Number must be exactly 10 characters.',
            'username.regex' => 'The Register Number format is invalid.',
            'school_id.required' => 'The school field is required.',
            'school_id.exists' => 'The selected school is invalid.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department does not belong to the selected school.',
        ]);

        $validated['username'] = strtoupper(trim($validated['username']));

        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
        ];

        if (Auth::user()->role === 'sa') {
            $profileData['schools_id'] = \App\Models\School::where('id', $validated['school_id'])->value('code');
            $profileData['departments_id'] = \App\Models\Department::where('id', $validated['department_id'])->value('code');
        }

        $student->update(['username' => $validated['username']]);

        if ($request->filled('password')) {
            $student->update(['password' => Hash::make($request->password)]);
        }

        if ($request->hasFile('photo')) {
            if ($student->profile && $student->profile->photo) {
                Storage::disk('public')->delete($student->profile->photo);
            }
            $usernameToUse = $validated['username'] ?? $student->username;
            $filename = $usernameToUse . 'profile.' . $request->file('photo')->getClientOriginalExtension();
            $profileData['photo'] = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $student->profile->update($profileData);

        \App\Services\ActivityLogger::log(
            'student_updated',
            'Student Profile Updated',
            'Students',
            'Updated student profile for ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' (' . $validated['username'] . ').',
            'info',
            [
                'department_id' => $student->profile->departments_id,
                'entity_type' => 'User',
                'entity_id' => $student->id,
                'entity_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            ]
        );

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $role = $user->role ?? null;

        if ($role !== 'sa') {
            abort(403, 'Unauthorized. Only Super Admin can delete students.');
        }

        $student = User::role('stu')->findOrFail($id);
        $studentName = trim(($student->profile->first_name ?? '') . ' ' . ($student->profile->last_name ?? ''));
        $studentUsername = $student->username;
        $studentDept = $student->profile->departments_id ?? null;
        
        if ($student->profile && $student->profile->photo) {
            Storage::disk('public')->delete($student->profile->photo);
        }
        
        $profile = $student->profile;
        $student->delete();
        if ($profile) {
            $profile->delete();
        }

        \App\Services\ActivityLogger::log(
            'student_deleted',
            'Student Deleted',
            'Students',
            'Deleted student ' . $studentName . ' (' . $studentUsername . ').',
            'danger',
            [
                'department_id' => $studentDept,
                'entity_type' => 'User',
                'entity_id' => $id,
                'entity_name' => $studentName,
            ]
        );

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
