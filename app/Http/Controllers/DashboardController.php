<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        
        if ($role === 'sa') {
            // Super Admin Dashboard Logic
            $admin = $user;
            $totalUsers = User::whereHas('profile.role', function($q) {
                $q->where('name', 'admin');
            })->count();
            $coordinators = User::role('admin')->with('profile.school', 'profile.department')->latest()->take(5)->get();
            $totalCoordinators = User::role('admin')->count();
            
            $totalStaff = User::role('sta')->count();
            $recentStaff = User::role('sta')->with('profile.school', 'profile.department')->latest()->take(5)->get();
            
            $totalStudents = User::role('stu')->count();
            $recentStudents = User::role('stu')->with('profile.school', 'profile.department')->latest()->take(5)->get();
            
            return view('dashboard', compact('admin', 'totalUsers', 'coordinators', 'totalCoordinators', 'totalStaff', 'recentStaff', 'totalStudents', 'recentStudents'));
            
        } elseif ($role === 'admin') {
            // Admin / Coordinator Dashboard Logic
            $admin = $user;
            $profile = $user->profile;
            $school = $profile->school ?? null;
            $department = $profile->department ?? null;

            // Department Programs
            $programsQuery = \App\Models\Program::query();
            if ($department) {
                $programsQuery->where('department_id', $department->id);
            } elseif ($school) {
                $departmentIds = \App\Models\Department::where('school_id', $school->code)->pluck('id');
                $programsQuery->whereIn('department_id', $departmentIds);
            } else {
                $programsQuery->whereRaw('1 = 0');
            }
            $departmentPrograms = $programsQuery->get();

            // Filter Regulations by Department Programs
            $allSystemRegulations = \App\Models\Regulation::orderBy('created_at', 'desc')->get();
            $allRegulations = $allSystemRegulations->filter(function($reg) use ($departmentPrograms) {
                foreach($departmentPrograms as $prog) {
                    if (str_contains($prog->name, $reg->program_type)) {
                        return true;
                    }
                }
                return false;
            });
            
            // Count courses for each regulation within this department
            if ($user->profile->departments_id) {
                foreach($allRegulations as $reg) {
                    $reg->courses_count = \App\Models\Course::where('regulation_id', $reg->id)
                        ->where('department_id', $user->profile->departments_id)
                        ->count();
                }
            } else {
                foreach($allRegulations as $reg) {
                    $reg->courses_count = 0;
                }
            }

            $totalRegulations = $allRegulations->count();

            // Department Staff
            $departmentStaffQuery = User::role('sta')->with(['profile.school', 'profile.department']);
            if ($department) {
                $departmentStaffQuery->whereHas('profile', function($q) use ($department) {
                    $q->where('departments_id', $department->code);
                });
            } elseif ($school) {
                $departmentStaffQuery->whereHas('profile', function($q) use ($school) {
                    $q->where('schools_id', $school->code);
                });
            }
            $totalStaff = $departmentStaffQuery->count();

            // Department Students
            $departmentStudentsQuery = User::role('stu')->with(['profile.school', 'profile.department']);
            if ($department) {
                $departmentStudentsQuery->whereHas('profile', function($q) use ($department) {
                    $q->where('departments_id', $department->code);
                });
            } elseif ($school) {
                $departmentStudentsQuery->whereHas('profile', function($q) use ($school) {
                    $q->where('schools_id', $school->code);
                });
            }
            $totalStudents = $departmentStudentsQuery->count();

            return view('coordinator_dashboard', compact('admin', 'totalRegulations', 'allRegulations', 'totalStaff', 'totalStudents', 'departmentPrograms'));
            
        } elseif ($role === 'sta') {
            // Staff Dashboard Logic
            $staff = $user;
            $profile = $user->profile;
            $school = $profile->school ?? null;
            $department = $profile->department ?? null;

            // Department Students
            $departmentStudentsQuery = User::role('stu')->with(['profile.school', 'profile.department']);
            if ($department) {
                $departmentStudentsQuery->whereHas('profile', function($q) use ($department) {
                    $q->where('departments_id', $department->code);
                });
            } elseif ($school) {
                $departmentStudentsQuery->whereHas('profile', function($q) use ($school) {
                    $q->where('schools_id', $school->code);
                });
            }
            $departmentStudentsCount = $departmentStudentsQuery->count();

            // Department Staff / Colleagues
            $departmentStaffQuery = User::role('sta')->with(['profile.school', 'profile.department']);
            if ($department) {
                $departmentStaffQuery->whereHas('profile', function($q) use ($department) {
                    $q->where('departments_id', $department->code);
                });
            } elseif ($school) {
                $departmentStaffQuery->whereHas('profile', function($q) use ($school) {
                    $q->where('schools_id', $school->code);
                });
            }
            $departmentStaffCount = $departmentStaffQuery->count();

            // Assigned Courses
            $assignedCourses = $user->courses()->withCount('materials')->get();

            return view('staff_dashboard', compact(
                'staff', 'profile', 'school', 'department', 
                'departmentStudentsCount',
                'departmentStaffCount', 
                'assignedCourses'
            ));
            
        } elseif ($role === 'stu') {
            // Student Dashboard Logic
            $student = $user;
            $profile = $user->profile;
            $school = $profile->school ?? null;
            $department = $profile->department ?? null;

            // Department Courses
            $departmentCoursesQuery = \App\Models\Course::with(['department', 'regulation']);
            if ($department) {
                $departmentCoursesQuery->where('department_id', $department->code);
            }
            $departmentCoursesCount = $departmentCoursesQuery->count();
            $departmentCourses = (clone $departmentCoursesQuery)->latest()->take(5)->get();

            // Enrolled Courses
            $enrolledCoursesCount = $student->enrolledCourses()->count();
            $enrolledCourses = $student->enrolledCourses()->latest()->take(5)->get(); 

            // Recently Uploaded Materials (Last watching/updated)
            $recentMaterialsQuery = \App\Models\CourseMaterial::with(['course.department', 'staff']);
            if ($department) {
                $recentMaterialsQuery->whereHas('course', function($q) use ($department) {
                    $q->where('department_id', $department->code);
                });
            }
            $recentMaterials = $recentMaterialsQuery->latest()->take(5)->get();

            return view('student_dashboard', compact(
                'student', 'profile', 'school', 'department', 
                'departmentCoursesCount', 'departmentCourses', 
                'enrolledCoursesCount', 'enrolledCourses',
                'recentMaterials'
            ));
            
        } else {
            // Fallback for any other or undefined roles
            $admin = $user;
            return view('dashboard', compact('admin'));
        }
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile_edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'middle_name' => ['nullable', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s]+$/', 'different:first_name'],
            'email' => ['nullable', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/', 'unique:profiles,email,'.$user->profile_id],
            'phone_number' => 'nullable|numeric|digits:10',
            'photo' => 'nullable|image|mimes:webp|max:2048',
        ], [

        ]);

        $profileData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone_number'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($user->profile && $user->profile->photo) {
                Storage::disk('public')->delete($user->profile->photo);
            }
            $filename = $user->username . 'profile.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('profiles', $filename, 'public');
            $profileData['photo'] = $path;
        }

        $user->profile->update($profileData);

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function editPassword()
    {
        $user = Auth::user();
        return view('password_edit', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->symbols()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function resetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, ['sa', 'admin'])) {
            abort(403, 'Unauthorized. You do not have permission to reset passwords.');
        }

        if ($currentUser->role === 'admin') {
            if (in_array($user->role, ['sa', 'admin'])) {
                abort(403, 'Unauthorized. You can only reset passwords for staff and students.');
            }
            if ($user->profile->departments_id !== $currentUser->profile->departments_id) {
                abort(403, 'Unauthorized. You can only reset passwords for users in your department.');
            }
        }
        
        $newPassword = $request->input('new_password');
        if (empty($newPassword)) {
            if ($user->role === 'stu') {
                $newPassword = 'Student#963';
            } elseif ($user->role === 'sta') {
                $newPassword = 'Staff@852';
            } elseif ($user->role === 'admin') {
                $newPassword = 'Admin!741';
            } elseif ($user->role === 'sa') {
                $newPassword = 'Vu_Super@123';
            } else {
                $newPassword = 'Student#963';
            }
        }

        $user->update([
            'password' => Hash::make($newPassword),
            'failed_login_attempts' => 0,
            'requires_password_reset' => false
        ]);
        
        return back()->with('success', 'Password reset successfully.');
    }
}
