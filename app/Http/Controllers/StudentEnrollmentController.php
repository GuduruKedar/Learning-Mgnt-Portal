<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulation;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    /**
     * Show the form for creating a new enrollment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = Auth::user();
        $department = $student->profile?->department;
        
        $programs = $department ? \App\Models\Program::where('department_id', $department->id)->get() : collect();
        
        $studentProgramCode = $student->profile?->programs_id;
        $studentProgram = $programs->where('code', $studentProgramCode)->first();
        
        $programType = '';
        if ($studentProgram) {
            $pName = $studentProgram->name;
            if (str_contains($pName, 'B.Tech')) $programType = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $programType = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $programType = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $programType = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $programType = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $programType = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $programType = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $programType = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $programType = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $programType = 'BBA';
            elseif (str_contains($pName, 'MBA')) $programType = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $programType = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $programType = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $programType = 'LLB';
            elseif (str_contains($pName, 'LLM')) $programType = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $programType = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $programType = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $programType = 'Diploma';
            else $programType = explode(' ', $pName)[0] ?? '';
        }

        // If programType was not inferred from program, check student level
        if (!$programType && !empty($student->profile?->level)) {
            $level = strtoupper($student->profile->level);
            if ($level === 'UG') $programType = 'B.Tech';
            elseif ($level === 'PG') $programType = 'M.Tech';
            elseif ($level === 'PHD' || $level === 'PH.D') $programType = 'Ph.D';
            elseif ($level === 'DIPLOMA') $programType = 'Diploma';
        }

        $regQuery = Regulation::where('status', 'Active');
        if ($programType) {
            $regQuery->where('program_type', $programType);
        } else {
            // Find regulations that have courses in student's department
            $deptCode = $student->profile?->departments_id;
            if ($deptCode) {
                $deptCourseRegIds = Course::where('department_id', $deptCode)->pluck('regulation_id')->unique();
                if ($deptCourseRegIds->isNotEmpty()) {
                    $regQuery->whereIn('id', $deptCourseRegIds);
                }
            }
        }
        $regulationsData = $regQuery->select('id', 'code', 'name', 'curriculum', 'program_type')->get();

        // Fallback: If still empty, return all active regulations
        if ($regulationsData->isEmpty()) {
            $regulationsData = Regulation::where('status', 'Active')->select('id', 'code', 'name', 'curriculum', 'program_type')->get();
        }
            
        $regulations = $regulationsData->map(function($item) {
            $item->display_name = $item->code . ($item->curriculum ? '_' . $item->curriculum : '');
            return $item;
        })->unique('display_name')->values();
        
        $curriculumsByCode = [];
        
        return view('student.enrollment.create', compact('regulations', 'curriculumsByCode', 'department', 'programs'));
    }

    /**
     * Fetch courses based on selected criteria via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchCourses(Request $request)
    {
        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'year' => 'required|string',
            'semester' => 'required|string',
        ]);

        $student = Auth::user();
        $departmentCode = $student->profile?->departments_id;

        $regulation = Regulation::find($request->regulation_id);
        if (!$regulation) {
            return response()->json(['courses' => [], 'enrolled_course_ids' => []]);
        }

        $semester = $request->semester;
        $cumulativeSemester = ($request->year - 1) * 2 + $semester;
        
        $semesterOptions = [
            (string)$semester, 
            (int)$semester, 
            (string)$cumulativeSemester, 
            (int)$cumulativeSemester
        ];

        // Fetch courses for this department, regulation (by code/curriculum), year, and semester
        $courses = Course::where('department_id', $departmentCode)
            ->whereHas('regulation', function($query) use ($regulation) {
                $query->where('code', $regulation->code);
                if (!empty($regulation->curriculum)) {
                    $query->where('curriculum', $regulation->curriculum);
                } else {
                    $query->where(function($q) {
                        $q->whereNull('curriculum')->orWhere('curriculum', '');
                    });
                }
            })
            ->where('year', $request->year)
            ->whereIn('semester', $semesterOptions)
            ->get();
            
        // Get already enrolled course IDs to disable them or mark them as enrolled
        $enrolledCourseIds = $student->enrolledCourses()->pluck('courses.id')->toArray();

        return response()->json([
            'courses' => $courses,
            'enrolled_course_ids' => $enrolledCourseIds
        ]);
    }

    /**
     * Store a newly created enrollment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id'
        ], [
            'courses.required' => 'Please select at least one course to enroll.'
        ]);

        $student = Auth::user();

        $student->enrolledCourses()->syncWithoutDetaching($request->courses);

        return redirect()->route('student.courses.index')->with('success', 'Successfully enrolled in selected courses.');
    }

    /**
     * Display a listing of the enrolled courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function myCourses()
    {
        $student = Auth::user();
        
        // 1. Regular academic enrolled courses
        $regularCourses = $student->enrolledCourses()->withCount('materials')->get();

        // 2. Civil Services courses (included automatically if student is enrolled in Civil Services)
        $civilCourses = collect();
        if ($student->isCivilServicesEnrolled()) {
            $civilCourses = Course::where('department_id', 'dep_cs')->withCount('materials')->get();
        }

        $courses = $regularCourses->concat($civilCourses);
        
        return view('student.courses.index', compact('courses', 'regularCourses', 'civilCourses'));
    }

    /**
     * Display materials for a specific enrolled course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function courseMaterials(Course $course)
    {
        $student = Auth::user();
        
        // Ensure student is enrolled: regular course enrollment OR active Civil Services enrollment
        $isRegularEnrolled = $student->enrolledCourses()->where('courses.id', $course->id)->exists();
        $isCivilEnrolled = ($course->department_id === 'dep_cs' && $student->isCivilServicesEnrolled());

        if (!$isRegularEnrolled && !$isCivilEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Fetch materials
        $materials = $course->materials()->latest()->get();

        return view('student.courses.materials', compact('course', 'materials'));
    }
}
