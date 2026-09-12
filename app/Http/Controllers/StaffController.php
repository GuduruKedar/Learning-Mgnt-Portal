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

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $query = User::role('sta')->with(['profile.school', 'profile.department']);
        
        if ($role === 'sa') {
            // Super Admin sees all staff
        } elseif ($role === 'admin') {
            // Admin only sees staff in their department
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

        $staffMembers = $query->paginate(10)->withQueryString();
        $schools = School::all();
        $departments = Department::all();
        
        return view('staff.index', compact('staffMembers', 'schools', 'departments'));
    }

    public function create()
    {
        $schools = School::all();
        $departments = Department::with('school')->get();
        return view('staff.create', compact('schools', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'regex:/^\d{5}$/', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email'],
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => Auth::user()->role === 'sa' ? 'required|exists:schools,id' : 'nullable',
            'department_id' => [
                Auth::user()->role === 'sa' ? 'required' : 'nullable', 
                Rule::exists('departments', 'id')->where('school_id', \App\Models\School::where('id', $request->school_id)->value('code'))
            ],
            'photo' => 'nullable|image|mimes:webp|max:2048',
        ], [

        ]);

        if (Auth::user()->role === 'admin') {
            $validated['school_id'] = Auth::user()->profile->schools_id;
            $validated['department_id'] = Auth::user()->profile->departments_id;
        }

        $password = $request->filled('password') ? $request->password : 'Staff@852';
        $staffRole = \App\Models\Role::firstOrCreate(['name' => 'sta']);

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
            'email' => $validated['email'],
            'phone' => $validated['phone_number'],
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
            'designation' => $validated['designation'] ?? null,
            'roles_id' => 'sta',
            'photo' => $photoPath,
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => Hash::make($password),
            'profile_id' => $profile->id,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show($id)
    {
        $staff = User::role('sta')->findOrFail($id);
        return view('staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $staff = User::role('sta')->findOrFail($id);
        
        if ($role === 'admin' && $staff->profile->departments_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized to edit this staff member.');
        }

        $schools = School::all();
        $departments = Department::with('school')->get();
        return view('staff.edit', compact('staff', 'schools', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::role('sta')->findOrFail($id);

        if (Auth::user()->role === 'admin' && $staff->profile->departments_id !== Auth::user()->profile->departments_id) {
            abort(403, 'Unauthorized to update this staff member.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'username' => ['required', 'regex:/^\d{5}$/', 'unique:users,username,'.$staff->id],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email,'.$staff->profile_id],
            'phone_number' => 'nullable|numeric|digits:10',
            'school_id' => Auth::user()->role === 'sa' ? 'required|exists:schools,id' : 'nullable',
            'department_id' => [
                Auth::user()->role === 'sa' ? 'required' : 'nullable', 
                Rule::exists('departments', 'id')->where('school_id', \App\Models\School::where('id', $request->school_id)->value('code'))
            ],
            'photo' => 'nullable|image|mimes:webp|max:2048',
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
        ], [

        ]);

        if (Auth::user()->role === 'admin') {
            $validated['school_id'] = Auth::user()->profile->schools_id;
            $validated['department_id'] = Auth::user()->profile->departments_id;
        }

        if ($request->filled('password')) {
            $staff->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->username !== $staff->username) {
            $staff->update(['username' => $validated['username']]);
        }

        $schoolCode = (Auth::user()->role === 'admin') ? Auth::user()->profile->schools_id : \App\Models\School::where('id', $validated['school_id'])->value('code');
        $deptCode = (Auth::user()->role === 'admin') ? Auth::user()->profile->departments_id : \App\Models\Department::where('id', $validated['department_id'])->value('code');

        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone_number'],
            'schools_id' => $schoolCode,
            'departments_id' => $deptCode,
            'level' => $validated['level'] ?? null,
            'programs_id' => isset($validated['program_id']) ? \App\Models\Program::where('id', $validated['program_id'])->value('code') : null,
            'designation' => $validated['designation'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($staff->profile && $staff->profile->photo) {
                Storage::disk('public')->delete($staff->profile->photo);
            }
            $usernameToUse = $validated['username'] ?? $staff->username;
            $filename = $usernameToUse . 'profile.' . $request->file('photo')->getClientOriginalExtension();
            $profileData['photo'] = $request->file('photo')->storeAs('profiles', $filename, 'public');
        }

        $staff->profile->update($profileData);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $staff = User::role('sta')->findOrFail($id);

        if ($role === 'sa') {
            // Super Admins can delete
        } else {
            abort(403, 'Unauthorized. Only Super Admins can delete staff members.');
        }
        
        if ($staff->profile->photo) {
            Storage::disk('public')->delete($staff->profile->photo);
        }
        
        $profile = $staff->profile;
        $staff->delete();
        if ($profile) {
            $profile->delete();
        }

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
