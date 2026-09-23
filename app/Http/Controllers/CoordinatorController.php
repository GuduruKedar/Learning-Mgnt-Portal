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

class CoordinatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::role('admin')->with(['profile.school', 'profile.department']);

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

        $coordinators = $query->paginate(10)->withQueryString();
        
        $schools = School::all();
        $departments = Department::all();

        return view('coordinators.index', compact('coordinators', 'schools', 'departments'));
    }

    public function create(Request $request)
    {
        $schools = School::all();
        $departments = Department::all();
        
        $preselectedSchoolId = null;
        $preselectedDepartmentId = null;

        if ($request->has('department')) {
            $dept = Department::where('code', $request->department)->first();
            if ($dept) {
                // School relation might use school_id (code) or id, but $school->id is what the frontend options use
                $school = School::where('code', $dept->school_id)->first();
                if ($school) {
                    $preselectedSchoolId = $school->id;
                    $preselectedDepartmentId = $dept->id;
                }
            }
        }

        return view('coordinators.create', compact('schools', 'departments', 'preselectedSchoolId', 'preselectedDepartmentId'));
    }

    public function departmentCoordinators(Request $request)
    {
        $schools = School::all();
        
        $query = Department::with(['school', 'profiles' => function($q) {
            $q->where('roles_id', 'admin')->has('user')->with('user');
        }]);

        if ($request->filled('school')) {
            $query->whereHas('school', function($q) use ($request) {
                $q->where('code', $request->school);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('profiles', function($pq) use ($search) {
                      $pq->where('roles_id', 'admin')
                         ->where(function($pq2) use ($search) {
                             $pq2->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('username', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($request->filled('department')) {
            $query->where('code', $request->department);
        }

        if ($request->filled('status')) {
            if ($request->status === 'assigned') {
                $query->whereHas('profiles', function($q) {
                    $q->where('roles_id', 'admin')->has('user');
                });
            } elseif ($request->status === 'unassigned') {
                $query->whereDoesntHave('profiles', function($q) {
                    $q->where('roles_id', 'admin')->has('user');
                });
            }
        }
        
        $totalDepartments = Department::count();
        $departmentsWithCoordinators = Department::whereHas('profiles', function($q) {
            $q->where('roles_id', 'admin')->has('user');
        })->count();
        $unassignedDepartments = max(0, $totalDepartments - $departmentsWithCoordinators);
        $totalActiveCoordinators = User::role('admin')->count();
        
        $allDepartments = Department::all();
        $filterDepartments = Department::when($request->filled('school'), function($q) use ($request) {
            $q->whereHas('school', function($sq) use ($request) {
                $sq->where('code', $request->school);
            });
        })->get();
        
        $departments = $query->paginate(10)->withQueryString();

        return view('superadmin.department_coordinators', compact(
            'departments', 'schools', 'allDepartments', 'filterDepartments', 'totalDepartments', 
            'departmentsWithCoordinators', 'unassignedDepartments', 'totalActiveCoordinators'
        ));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'regex:/^\d{5}$/', 'unique:users,username'],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email'],
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => 'required|exists:schools,id',
            'department_id' => [
                'required', 
                Rule::exists('departments', 'id')->where('school_id', \App\Models\School::where('id', $request->school_id)->value('code'))
            ],
        ], [

        ]);

        $deptCode = \App\Models\Department::where('id', $request->department_id)->value('code');



        $password = $request->filled('password') ? $request->password : 'Admin!741';

        $profile = \App\Models\Profile::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone_number'],
            'schools_id' => \App\Models\School::where('id', $validated['school_id'])->value('code'),
            'departments_id' => \App\Models\Department::where('id', $validated['department_id'])->value('code'),
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
            'designation' => $validated['designation'] ?? null,
            'roles_id' => 'admin',
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        return redirect()->route('coordinators.index')->with('success', 'Coordinator created successfully.');
    }

    public function show($id)
    {
        $coordinator = User::findOrFail($id);
        return view('coordinators.show', compact('coordinator'));
    }

    public function edit($id)
    {
        $coordinator = User::role( 'admin')->findOrFail($id);
        $schools = School::all();
        $departments = Department::all();
        return view('coordinators.edit', compact('coordinator', 'schools', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $coordinator = User::role( 'admin')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'regex:/^\d{5}$/', 'unique:users,username,'.$coordinator->id],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email,'.$coordinator->profile_id],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => 'required|exists:schools,id',
            'department_id' => [
                'required', 
                Rule::exists('departments', 'id')->where('school_id', \App\Models\School::where('id', $request->school_id)->value('code'))
            ],
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
        ], [

        ]);

        $deptCode = \App\Models\Department::where('id', $request->department_id)->value('code');



        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone_number'],
            'schools_id' => \App\Models\School::where('id', $validated['school_id'])->value('code'),
            'departments_id' => \App\Models\Department::where('id', $validated['department_id'])->value('code'),
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
            'designation' => $validated['designation'] ?? null,
        ];

        $coordinator->profile->update($profileData);

        $userData = [
            'username' => $validated['username'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $coordinator->update($userData);

        return redirect()->route('coordinators.index')->with('success', 'Coordinator updated successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'sa') {
            abort(403, 'Unauthorized action.');
        }

        $coordinator = User::role( 'admin')->findOrFail($id);
        
        if ($coordinator->photo) {
            Storage::disk('public')->delete($coordinator->photo);
        }
        
        $coordinator->delete();

        return redirect()->route('coordinators.index')->with('success', 'Coordinator deleted successfully.');
    }

    public function getDepartments(School $school)
    {
        return response()->json($school->departments);
    }

    public function getPrograms(Department $department, Request $request)
    {
        $query = $department->programs();
        if ($request->has('level') && $request->level !== '') {
            $query->where('level', $request->level);
        }
        return response()->json($query->get());
    }
}
