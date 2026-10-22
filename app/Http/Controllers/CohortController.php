<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Department;
use App\Models\Program;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;
use App\Services\CacheService;

class CohortController extends Controller
{
    /**
     * Display the Cohort Student Course Enrollment hub.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'sa', 'ssh_admin'])) {
            abort(403, 'Unauthorized access.');
        }

        $coordinatorDept = null;
        if ($user->role === 'admin') {
            $coordinatorDept = $user->profile?->departments_id;
        } elseif ($request->filled('department_id')) {
            $coordinatorDept = $request->department_id;
        }

        // Fetch courses available for enrollment
        $coursesQuery = Course::with(['regulation', 'department', 'staff.profile'])
            ->withCount('enrollments')
            ->orderBy('code');

        if ($coordinatorDept) {
            $coursesQuery->where('department_id', $coordinatorDept);
        }

        $courses = $coursesQuery->get();

        // Target Course Selection
        $selectedCourseId = $request->input('course_id');
        $selectedCourse = null;

        if ($selectedCourseId) {
            $selectedCourse = $courses->firstWhere('id', (int)$selectedCourseId) 
                ?? Course::with(['regulation', 'department', 'staff.profile'])->withCount('enrollments')->find($selectedCourseId);
        } elseif ($courses->isNotEmpty()) {
            $selectedCourse = $courses->first();
            $selectedCourseId = $selectedCourse->id;
        }

        // Default Year from selected course, semester defaults to 'all'
        $yearFilter = $request->input('year', $selectedCourse?->year ?? 'all');
        $semFilter = $request->input('semester', 'all');
        $programFilter = $request->input('program', 'all');
        $statusFilter = $request->input('status', 'all'); // 'all', 'enrolled', 'not_enrolled'
        $search = trim($request->input('search', ''));

        // Query students belonging to this department
        $studentsQuery = User::role('stu')
            ->with(['profile.program', 'profile.department'])
            ->whereHas('profile', function ($q) use ($coordinatorDept, $yearFilter, $semFilter, $programFilter) {
                if ($coordinatorDept) {
                    $q->where('departments_id', $coordinatorDept);
                }
                if ($yearFilter && $yearFilter !== 'all') {
                    $q->where('academic_year', $yearFilter);
                }
                if ($semFilter && $semFilter !== 'all') {
                    $acceptableSems = [(string)$semFilter];
                    if ($yearFilter && $yearFilter !== 'all') {
                        $y = (int)$yearFilter;
                        if ((int)$semFilter === ($y * 2 - 1)) {
                            $acceptableSems[] = '1';
                        } elseif ((int)$semFilter === ($y * 2)) {
                            $acceptableSems[] = '2';
                        } elseif ((int)$semFilter === 1) {
                            $acceptableSems[] = (string)($y * 2 - 1);
                        } elseif ((int)$semFilter === 2) {
                            $acceptableSems[] = (string)($y * 2);
                        }
                    }
                    $acceptableSems = array_values(array_unique($acceptableSems));

                    $q->where(function ($sq) use ($acceptableSems) {
                        $sq->whereIn('semester', $acceptableSems)
                           ->orWhereNull('semester')
                           ->orWhere('semester', '');
                    });
                }
                if ($programFilter && $programFilter !== 'all') {
                    $q->where('programs_id', $programFilter);
                }
            });

        if ($search !== '') {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $allCohortStudents = $studentsQuery->get();

        // Determine enrollment IDs for the selected course
        $enrolledIds = [];
        if ($selectedCourse) {
            $enrolledIds = $selectedCourse->enrollments()->pluck('users.id')->toArray();
        }

        // Tag each student with enrolled status
        $students = $allCohortStudents->map(function ($stu) use ($enrolledIds) {
            $stu->is_enrolled = in_array($stu->id, $enrolledIds);
            return $stu;
        });

        // Apply Status Filter if specified
        if ($statusFilter === 'enrolled') {
            $students = $students->where('is_enrolled', true)->values();
        } elseif ($statusFilter === 'not_enrolled') {
            $students = $students->where('is_enrolled', false)->values();
        }

        // Summary Counts
        $totalCohortCount = $allCohortStudents->count();
        $enrolledCount = $allCohortStudents->whereIn('id', $enrolledIds)->count();
        $pendingCount = $totalCohortCount - $enrolledCount;

        // Distinct Filter Options
        $deptProfileQuery = Profile::where('roles_id', 'stu');
        if ($coordinatorDept) {
            $deptProfileQuery->where('departments_id', $coordinatorDept);
        }

        $userSchool = $user->profile?->schools_id;
        if (!$userSchool && $coordinatorDept) {
            $userSchool = Department::where('code', $coordinatorDept)->value('school_id');
        }

        $programsQuery = Program::query();
        if ($coordinatorDept) {
            $dept = Department::where('code', $coordinatorDept)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            }
        } elseif ($userSchool) {
            $deptIds = Department::where('school_id', $userSchool)->pluck('id');
            if ($deptIds->isNotEmpty()) {
                $programsQuery->whereIn('department_id', $deptIds);
            }
        }
        $programs = $programsQuery->orderBy('name')->get();

        $departments = ($user->role === 'sa') ? Department::orderBy('name')->get() : collect();

        return view('academic.cohorts', compact(
            'courses',
            'selectedCourse',
            'selectedCourseId',
            'students',
            'totalCohortCount',
            'enrolledCount',
            'pendingCount',
            'yearFilter',
            'semFilter',
            'programFilter',
            'statusFilter',
            'search',
            'programs',
            'departments',
            'coordinatorDept'
        ));
    }

    /**
     * Bulk enroll a cohort of students into the specified course.
     */
    public function enroll(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'sa', 'ssh_admin'])) {
            abort(403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ], [
            'student_ids.required' => 'Please select at least one student from the cohort to enroll.',
            'student_ids.min' => 'Please select at least one student from the cohort to enroll.',
        ]);

        $course = Course::with(['department', 'regulation'])->findOrFail($request->course_id);

        // Security: Coordinators can only enroll students into courses of their department
        if ($user->role === 'admin' && !empty($user->profile?->departments_id) && $course->department_id !== $user->profile?->departments_id) {
            abort(403, 'You do not have permission to modify courses outside your department.');
        }

        $studentIds = $request->student_ids;

        // Attach students without duplicate key collisions
        $course->enrollments()->syncWithoutDetaching($studentIds);

        $count = count($studentIds);
        $courseTitle = "{$course->code} - {$course->name}";

        ActivityLogger::log(
            'cohort_enrolled',
            'Cohort Students Enrolled',
            'Academics',
            "Enrolled {$count} students in bulk into course {$courseTitle}.",
            'info',
            [
                'department_id' => $course->department_id,
                'course_id'     => $course->id,
                'course_code'   => $course->code,
                'enrolled_count'=> $count,
                'student_ids'   => $studentIds,
            ]
        );

        CacheService::invalidateCourses($course->department_id);
        CacheService::invalidateCourseAllocations($course->id);

        return back()->with('success', "Successfully enrolled {$count} students into {$courseTitle}!");
    }

    /**
     * Bulk unenroll students from the specified course.
     */
    public function unenroll(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'sa', 'ssh_admin'])) {
            abort(403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ], [
            'student_ids.required' => 'Please select at least one student to unenroll.',
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($user->role === 'admin' && !empty($user->profile?->departments_id) && $course->department_id !== $user->profile?->departments_id) {
            abort(403, 'You do not have permission to modify courses outside your department.');
        }

        $studentIds = $request->student_ids;
        $course->enrollments()->detach($studentIds);

        $count = count($studentIds);
        $courseTitle = "{$course->code} - {$course->name}";

        ActivityLogger::log(
            'cohort_unenrolled',
            'Cohort Students Unenrolled',
            'Academics',
            "Unenrolled {$count} students from course {$courseTitle}.",
            'warning',
            [
                'department_id' => $course->department_id,
                'course_id'     => $course->id,
                'course_code'   => $course->code,
                'unenrolled_count' => $count,
            ]
        );

        CacheService::invalidateCourses($course->department_id);
        CacheService::invalidateCourseAllocations($course->id);

        return back()->with('success', "Successfully removed {$count} students from {$courseTitle}.");
    }
}
