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
            // Super Admin Dashboard Logic (Cached - 10 Minutes TTL)
            $admin = $user;
            $stats = \App\Services\CacheService::getSuperAdminDashboardStats();
            $totalUsers = $stats['totalUsers'];
            $coordinators = $stats['coordinators'];
            $totalCoordinators = $stats['totalCoordinators'];
            $totalStaff = $stats['totalStaff'];
            $recentStaff = $stats['recentStaff'];
            $totalStudents = $stats['totalStudents'];
            $recentStudents = $stats['recentStudents'];
            $totalCivil = $stats['totalCivil'];
            $totalCourses = $stats['totalCourses'];
            $totalDepartments = $stats['totalDepartments'];
            
            return view('dashboard', compact('admin', 'totalUsers', 'coordinators', 'totalCoordinators', 'totalStaff', 'recentStaff', 'totalStudents', 'recentStudents', 'totalCivil', 'totalCourses', 'totalDepartments'));
            
        } elseif ($role === 'ssh_admin') {
            // SSH Department (Sciences & Humanities / First Year Directorate) Dashboard
            return app(\App\Http\Controllers\SshAdminController::class)->index();

        } elseif ($role === 'civil_admin') {
            // Civil Services Admin Dashboard (Cached - 15 Minutes TTL)
            $stats = \App\Services\CacheService::getCivilServicesDashboardStats();
            $totalEnrolled = $stats['totalEnrolled'];
            $activeEnrolled = $stats['activeEnrolled'];
            $recentEnrollments = $stats['recentEnrollments'];
            $departmentBreakdown = $stats['departmentBreakdown'];

            return view('civil_admin_dashboard', compact('user', 'totalEnrolled', 'activeEnrolled', 'recentEnrollments', 'departmentBreakdown'));

        } elseif ($role === 'admin') {
            // Admin / Coordinator Dashboard Logic (Cached - 10 Minutes / 24 Hours TTL)
            $admin = $user;
            $profile = $user->profile;
            $school = $profile->school ?? null;
            $department = $profile->department ?? null;

            // Department Programs (Cached 24 Hours)
            if ($department) {
                $departmentPrograms = \App\Services\CacheService::getProgramsByDepartment($department->id);
            } elseif ($school) {
                $departmentIds = \App\Services\CacheService::getDepartmentsWithSchool()
                    ->where('school_id', $school->code)
                    ->pluck('id');
                $departmentPrograms = \App\Services\CacheService::getPrograms()
                    ->whereIn('department_id', $departmentIds);
            } else {
                $departmentPrograms = collect();
            }

            // Filter Regulations by Department Programs (Cached 24 Hours)
            $allSystemRegulations = \App\Services\CacheService::getRegulations();
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

            // Department Stats (Cached 10 Minutes)
            $stats = \App\Services\CacheService::getCoordinatorDashboardStats($department?->code, $school?->code);
            $totalStaff = $stats['facultyCount'];
            $totalStudents = $stats['studentCount'];

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

            // Department Courses (including 1st year SSH centralized courses)
            $shDeptCodes = ['dep_ssh', 'dep_phy', 'dep_chem', 'dep_maths', 'dep_eng'];
            $shDeptCodesFromDb = \App\Models\Department::where('school_id', 'sc_ash')->pluck('code')->toArray();
            $deptCodes = array_unique(array_filter(array_merge([$department?->code], $shDeptCodes, $shDeptCodesFromDb)));

            $departmentCoursesQuery = \App\Models\Course::with(['department', 'regulation'])
                ->where(function($q) use ($department, $deptCodes) {
                    if ($department) {
                        $q->where('department_id', $department->code);
                    }
                    $q->orWhereIn('department_id', $deptCodes);
                });
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

            $enrolledCourseIds = $allEnrolledCourses->pluck('id');

            // Recently Uploaded Materials (for enrolled courses or department)
            $recentMaterialsQuery = \App\Models\CourseMaterial::with(['course.department', 'staff'])
                ->where(function($q) use ($enrolledCourseIds, $department) {
                    if ($enrolledCourseIds->isNotEmpty()) {
                        $q->whereIn('course_id', $enrolledCourseIds);
                    }
                    if ($department) {
                        $q->orWhereHas('course', function($sq) use ($department) {
                            $sq->where('department_id', $department->code);
                        });
                    }
                });
            $recentMaterials = $recentMaterialsQuery->latest()->take(5)->get();
            $totalMaterialsCount = (clone $recentMaterialsQuery)->count();

            return view('student_dashboard', compact(
                'student', 'profile', 'school', 'department', 
                'departmentCoursesCount', 'departmentCourses', 
                'enrolledCoursesCount', 'enrolledCourses',
                'recentMaterials', 'totalMaterialsCount'
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
            'email' => [
                'nullable',
                'string',
                'email:rfc,filter',
                'max:255',
                'not_regex:/@example\.(com|org|net)$/i',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|vignan\.ac\.in)$/',
                'unique:profiles,email,'.$user->profile_id
            ],
            'phone_number' => 'nullable|numeric|digits:10',
            'photo' => 'nullable|file|mimes:webp|max:2048',
        ], [
            'email.not_regex' => 'Dummy or placeholder email domains (@example.com) are not allowed. Please provide a valid email.',
            'email.regex' => 'The email must belong to an official domain (@vignan.ac.in or @gmail.com).',
            'photo.mimes' => 'Invalid file format. Only .webp format is allowed for profile photo.',
            'photo.max' => 'The photo size is too large. Maximum allowed file size is 2MB.',
            'photo.uploaded' => 'The photo failed to upload. Please ensure you select a valid .webp image under 2MB.',
            'photo.file' => 'The selected file is invalid. Please select a valid .webp image file.',
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
