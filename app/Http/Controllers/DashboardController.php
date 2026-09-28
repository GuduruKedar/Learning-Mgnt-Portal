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
            $totalUsers = User::count();
            $coordinators = User::role('admin')->with('profile.school', 'profile.department')->latest()->take(6)->get();
            $totalCoordinators = User::role('admin')->count();
            
            $totalStaff = User::role('sta')->count();
            $recentStaff = User::role('sta')->with('profile.school', 'profile.department')->latest()->take(5)->get();
            
            $totalStudents = User::role('stu')->count();
            $recentStudents = User::role('stu')->with('profile.school', 'profile.department')->latest()->take(5)->get();

            $totalCivil = \App\Models\CivilServiceEnrollment::count();
            $totalCourses = \App\Models\Course::count();
            $totalDepartments = \App\Models\Department::count();
            
            return view('dashboard', compact('admin', 'totalUsers', 'coordinators', 'totalCoordinators', 'totalStaff', 'recentStaff', 'totalStudents', 'recentStudents', 'totalCivil', 'totalCourses', 'totalDepartments'));
            
        } elseif ($role === 'ssh_admin') {
            // SSH Department (Sciences & Humanities / First Year Directorate) Dashboard
            return app(\App\Http\Controllers\SshAdminController::class)->index();

        } elseif ($role === 'civil_admin') {
            // Civil Services Admin Dashboard
            $totalEnrolled = \App\Models\CivilServiceEnrollment::count();
            $activeEnrolled = \App\Models\CivilServiceEnrollment::where('status', 'active')->count();
            $recentEnrollments = User::role('stu')
                ->whereHas('civilServiceEnrollment')
                ->with(['profile.school', 'profile.department', 'civilServiceEnrollment'])
                ->latest()
                ->take(8)
                ->get();

            $departmentBreakdown = \App\Models\Profile::whereHas('user.civilServiceEnrollment')
                ->select('departments_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->groupBy('departments_id')
                ->with('department')
                ->get();

            return view('civil_admin_dashboard', compact('user', 'totalEnrolled', 'activeEnrolled', 'recentEnrollments', 'departmentBreakdown'));

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

            // Assigned Courses (Courses the faculty needs to teach)
            $assignedCourses = $user->courses()
                ->with(['regulation', 'department'])
                ->withCount(['materials', 'assignments', 'enrollments'])
                ->get();

            $assignedCourseIds = $assignedCourses->pluck('id');
            $coursesToTeachCount = $assignedCourses->count();

            // Materials Uploaded Query (uploaded by this faculty or across assigned courses)
            $materialsQuery = \App\Models\CourseMaterial::where(function($q) use ($user, $assignedCourseIds) {
                $q->where('staff_id', $user->id)
                  ->orWhereIn('course_id', $assignedCourseIds);
            });
            $totalMaterialsCount = (clone $materialsQuery)->count();
            $uploadedFilesCount = (clone $materialsQuery)->where('type', 'file')->count();
            $uploadedLinksCount = (clone $materialsQuery)->where('type', 'link')->count();

            // Assessments Given Query (created by this faculty or across assigned courses)
            $assignmentsQuery = \App\Models\Assignment::where(function($q) use ($user, $assignedCourseIds) {
                $q->where('staff_id', $user->id)
                  ->orWhereIn('course_id', $assignedCourseIds);
            });
            $totalAssignmentsCount = (clone $assignmentsQuery)->count();
            $activeAssignmentsCount = (clone $assignmentsQuery)->where('status', 'published')->where(function($q) {
                $q->whereNull('due_date')->orWhere('due_date', '>=', now());
            })->count();
            $draftAssignmentsCount = (clone $assignmentsQuery)->where('status', 'draft')->count();
            $pastDueAssignmentsCount = (clone $assignmentsQuery)->where('status', 'published')->where('due_date', '<', now())->count();
            
            $assignmentIds = (clone $assignmentsQuery)->pluck('id');
            $totalSubmissionsCount = \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)->count();

            // Total enrolled students across faculty's assigned courses
            $totalEnrollmentsCount = \Illuminate\Support\Facades\DB::table('enrollments')
                ->whereIn('course_id', $assignedCourseIds)
                ->distinct('user_id')
                ->count('user_id');

            // Faculty Assignments & Materials collections for direct in-dashboard inspection
            $facultyAssignments = (clone $assignmentsQuery)->with(['course', 'questions'])->withCount('submissions')->latest()->get();
            $facultyMaterials = (clone $materialsQuery)->with('course')->latest()->get();

            return view('staff_dashboard', compact(
                'staff', 'profile', 'school', 'department', 
                'departmentStudentsCount',
                'departmentStaffCount', 
                'assignedCourses',
                'facultyAssignments',
                'facultyMaterials',
                'coursesToTeachCount',
                'totalMaterialsCount',
                'uploadedFilesCount',
                'uploadedLinksCount',
                'totalAssignmentsCount',
                'activeAssignmentsCount',
                'draftAssignmentsCount',
                'pastDueAssignmentsCount',
                'totalSubmissionsCount',
                'totalEnrollmentsCount'
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

            // Enrolled Courses (Regular + Civil Services if enrolled)
            $regularEnrolledCourses = $student->enrolledCourses()->latest()->get();
            $civilCourses = collect();
            if ($student->isCivilServicesEnrolled()) {
                $civilCourses = \App\Models\Course::where('department_id', 'dep_cs')->latest()->get();
            }
            $allEnrolledCourses = $regularEnrolledCourses->concat($civilCourses);
            $enrolledCoursesCount = $allEnrolledCourses->count();
            $enrolledCourses = $allEnrolledCourses->take(5); 

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

        if (!in_array($currentUser->role, ['sa', 'admin', 'ssh_admin'])) {
            abort(403, 'Unauthorized. You do not have permission to reset passwords.');
        }

        if ($currentUser->role === 'ssh_admin') {
            if (in_array($user->role, ['sa', 'admin', 'ssh_admin'])) {
                abort(403, 'Unauthorized. You can only reset passwords for staff and first-year students.');
            }
        } elseif ($currentUser->role === 'admin') {
            if (in_array($user->role, ['sa', 'admin', 'ssh_admin'])) {
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
