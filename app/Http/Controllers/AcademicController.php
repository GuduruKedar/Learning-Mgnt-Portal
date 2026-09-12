<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulation;
use App\Models\Course;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class AcademicController extends Controller
{
    // --- Regulations ---
    public function regulations()
    {
        $user = Auth::user();
        $regulationsQuery = Regulation::latest();
        
        $programsQuery = \App\Models\Program::query();
        if ($user->role === 'admin' && !empty($user->profile->departments_id)) {
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            } else {
                $programsQuery->where('department_id', -1);
            }
        }
        $programs = $programsQuery->get();
        
        $availableProgramTypes = [];
        foreach ($programs as $prog) {
            $pName = $prog->name;
            if (str_contains($pName, 'B.Tech')) $availableProgramTypes[] = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $availableProgramTypes[] = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $availableProgramTypes[] = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $availableProgramTypes[] = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $availableProgramTypes[] = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $availableProgramTypes[] = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $availableProgramTypes[] = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $availableProgramTypes[] = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $availableProgramTypes[] = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $availableProgramTypes[] = 'BBA';
            elseif (str_contains($pName, 'MBA')) $availableProgramTypes[] = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $availableProgramTypes[] = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $availableProgramTypes[] = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $availableProgramTypes[] = 'LLB';
            elseif (str_contains($pName, 'LLM')) $availableProgramTypes[] = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $availableProgramTypes[] = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $availableProgramTypes[] = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $availableProgramTypes[] = 'Diploma';
            else $availableProgramTypes[] = explode(' ', $pName)[0];
        }
        $availableProgramTypes = array_unique($availableProgramTypes);
        sort($availableProgramTypes);
        
        if ($user->role === 'admin') {
            $regulationsQuery->whereIn('program_type', $availableProgramTypes);
        }
        
        $regulations = $regulationsQuery->paginate(10)->withQueryString();
        
        return view('academic.regulations', compact('regulations', 'availableProgramTypes'));
    }

    public function storeRegulation(Request $request)
    {
        $request->merge([
            'code' => $request->code ? strtoupper($request->code) : null,
            'curriculum' => $request->curriculum ? strtoupper($request->curriculum) : null,
        ]);

        $request->validate([
            'program_type' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('regulations')->where(function ($query) use ($request) {
                    return $query->where('program_type', $request->program_type)
                                 ->where('curriculum', $request->curriculum);
                })
            ],
            'curriculum' => 'nullable|string|max:255',
        ], [
            'code.unique' => 'This regulation code and curriculum already exists for the selected program type.',
        ]);

        $data = $request->only('program_type', 'code', 'curriculum');
        $data['name'] = $request->code;
        $data['status'] = 'Active';

        Regulation::create($data);

        return back()->with('success', 'Regulation created successfully.');
    }

    public function updateRegulation(Request $request, $id)
    {
        $regulation = Regulation::findOrFail($id);

        $request->merge([
            'code' => $request->code ? strtoupper($request->code) : null,
            'curriculum' => $request->curriculum ? strtoupper($request->curriculum) : null,
        ]);

        $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('regulations')->where(function ($query) use ($regulation, $request) {
                    return $query->where('program_type', $regulation->program_type)
                                 ->where('curriculum', $request->curriculum);
                })->ignore($regulation->id)
            ],
            'curriculum' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ], [
            'code.unique' => 'This regulation code and curriculum already exists for this program type.',
        ]);

        $updateData = $request->only('code', 'curriculum', 'status');
        $updateData['name'] = $request->code;

        $regulation->update($updateData);

        return back()->with('success', 'Regulation updated successfully.');
    }

    public function destroyRegulation($id)
    {
        $regulation = Regulation::findOrFail($id);
        
        $regulation->delete();
        return back()->with('success', 'Regulation deleted.');
    }

    // --- Courses ---
    public function createCourse(Request $request)
    {
        $user = Auth::user();
        $departments = Department::all();

        $programsQuery = \App\Models\Program::query();
        if ($user->role === 'admin' && !empty($user->profile->departments_id)) {
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            } else {
                $programsQuery->where('department_id', -1);
            }
        }
        $programs = $programsQuery->get();
        
        $availableProgramTypes = [];
        foreach ($programs as $prog) {
            $pName = $prog->name;
            if (str_contains($pName, 'B.Tech')) $availableProgramTypes[] = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $availableProgramTypes[] = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $availableProgramTypes[] = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $availableProgramTypes[] = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $availableProgramTypes[] = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $availableProgramTypes[] = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $availableProgramTypes[] = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $availableProgramTypes[] = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $availableProgramTypes[] = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $availableProgramTypes[] = 'BBA';
            elseif (str_contains($pName, 'MBA')) $availableProgramTypes[] = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $availableProgramTypes[] = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $availableProgramTypes[] = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $availableProgramTypes[] = 'LLB';
            elseif (str_contains($pName, 'LLM')) $availableProgramTypes[] = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $availableProgramTypes[] = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $availableProgramTypes[] = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $availableProgramTypes[] = 'Diploma';
            else $availableProgramTypes[] = explode(' ', $pName)[0];
        }
        $availableProgramTypes = array_unique($availableProgramTypes);
        sort($availableProgramTypes);

        if ($user->role === 'admin') {
            $regulations = Regulation::whereIn('program_type', $availableProgramTypes)->get();
        } else {
            $regulations = Regulation::all();
        }

        return view('academic.courses_create', compact(
            'regulations', 'departments', 'availableProgramTypes'
        ));
    }

    public function courses(Request $request)
    {
        $user = Auth::user();
        $query = Course::with(['regulation', 'department', 'staff']);
        
        if ($user->role === 'admin') {
            $query->where('department_id', $user->profile->departments_id);
        }

        if ($user->role === 'sa' && $request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('regulation_id')) {
            $query->where('regulation_id', $request->regulation_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('code', 'like', '%' . $searchTerm . '%');
            });
        }

        $courses = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::all();

        // Get available staff for dropdown
        $staffQuery = User::role('sta');
        if ($user->role === 'admin') {
            $staffQuery->whereHas('profile', function($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        $availableStaff = $staffQuery->get(); // Accessors first_name, last_name will be available

        $programsQuery = \App\Models\Program::query();
        if ($user->role === 'admin' && !empty($user->profile->departments_id)) {
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            } else {
                $programsQuery->where('department_id', -1);
            }
        }
        $programs = $programsQuery->get();
        
        $availableProgramTypes = [];
        foreach ($programs as $prog) {
            $pName = $prog->name;
            if (str_contains($pName, 'B.Tech')) $availableProgramTypes[] = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $availableProgramTypes[] = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $availableProgramTypes[] = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $availableProgramTypes[] = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $availableProgramTypes[] = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $availableProgramTypes[] = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $availableProgramTypes[] = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $availableProgramTypes[] = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $availableProgramTypes[] = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $availableProgramTypes[] = 'BBA';
            elseif (str_contains($pName, 'MBA')) $availableProgramTypes[] = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $availableProgramTypes[] = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $availableProgramTypes[] = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $availableProgramTypes[] = 'LLB';
            elseif (str_contains($pName, 'LLM')) $availableProgramTypes[] = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $availableProgramTypes[] = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $availableProgramTypes[] = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $availableProgramTypes[] = 'Diploma';
            else $availableProgramTypes[] = explode(' ', $pName)[0];
        }
        $availableProgramTypes = array_unique($availableProgramTypes);
        sort($availableProgramTypes);

        if ($user->role === 'admin') {
            $regulations = Regulation::whereIn('program_type', $availableProgramTypes)->get();
        } else {
            $regulations = Regulation::all();
        }

        $courseDistribution = \App\Models\Course::select('regulation_id', 'year', 'semester', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->when($user->role === 'admin', function($q) use ($user) {
                $q->where('department_id', $user->profile->departments_id);
            })
            ->groupBy('regulation_id', 'year', 'semester')
            ->with('regulation')
            ->orderBy('year')
            ->orderBy('semester')
            ->get();
        
        $distributionByProgram = [];
        foreach($courseDistribution as $dist) {
            if (!$dist->regulation) continue; // skip if regulation was deleted
            $programType = $dist->regulation->program_type ?? 'Unknown';
            $regCode = $dist->regulation->code ?? 'Unknown';
            $regSuffix = !empty($dist->regulation->curriculum) ? $dist->regulation->curriculum : ($dist->regulation->name && $dist->regulation->name !== $regCode ? $dist->regulation->name : '');
            $displayReg = $regCode . ($regSuffix ? ' - ' . $regSuffix : '');
            
            $key = $programType . ' (' . $displayReg . ')';
            if(!isset($distributionByProgram[$key])) {
                $distributionByProgram[$key] = [];
            }
            
            $distributionByProgram[$key][] = [
                'year' => $dist->year ?: 'N/A',
                'semester' => $dist->semester ?: 'N/A',
                'total' => $dist->total,
                'regulation_id' => $dist->regulation_id,
                'program_type' => $programType
            ];
        }

        $allRawCourses = \App\Models\Course::with(['regulation', 'staff.profile'])
            ->when($user->role === 'admin', function($q) use ($user) {
                $q->where('department_id', $user->profile->departments_id);
            })
            ->get();

        return view('academic.courses', compact('courses', 'regulations', 'departments', 'availableStaff', 'availableProgramTypes', 'distributionByProgram', 'allRawCourses'));
    }

    
    public function courseAllocations(Request $request)
    {
        $user = Auth::user();
        $query = Course::with(['regulation', 'department', 'staff']);
        
        if ($user->role === 'admin') {
            $query->where('department_id', $user->profile->departments_id);
        }

        if ($user->role === 'sa' && $request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('regulation_id')) {
            $query->where('regulation_id', $request->regulation_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('code', 'like', '%' . $searchTerm . '%');
            });
        }

        $courses = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::all();

        // Get available staff for dropdown
        $staffQuery = User::role('sta');
        if ($user->role === 'admin') {
            $staffQuery->whereHas('profile', function($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        $availableStaff = $staffQuery->get(); // Accessors first_name, last_name will be available

        $programsQuery = \App\Models\Program::query();
        if ($user->role === 'admin' && !empty($user->profile->departments_id)) {
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            } else {
                $programsQuery->where('department_id', -1);
            }
        }
        $programs = $programsQuery->get();
        
        $availableProgramTypes = [];
        foreach ($programs as $prog) {
            $pName = $prog->name;
            if (str_contains($pName, 'B.Tech')) $availableProgramTypes[] = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $availableProgramTypes[] = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $availableProgramTypes[] = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $availableProgramTypes[] = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $availableProgramTypes[] = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $availableProgramTypes[] = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $availableProgramTypes[] = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $availableProgramTypes[] = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $availableProgramTypes[] = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $availableProgramTypes[] = 'BBA';
            elseif (str_contains($pName, 'MBA')) $availableProgramTypes[] = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $availableProgramTypes[] = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $availableProgramTypes[] = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $availableProgramTypes[] = 'LLB';
            elseif (str_contains($pName, 'LLM')) $availableProgramTypes[] = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $availableProgramTypes[] = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $availableProgramTypes[] = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $availableProgramTypes[] = 'Diploma';
            else $availableProgramTypes[] = explode(' ', $pName)[0];
        }
        $availableProgramTypes = array_unique($availableProgramTypes);
        sort($availableProgramTypes);

        if ($user->role === 'admin') {
            $regulations = Regulation::whereIn('program_type', $availableProgramTypes)->get();
        } else {
            $regulations = Regulation::all();
        }

        $courseDistribution = \App\Models\Course::select('regulation_id', 'year', 'semester', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->when($user->role === 'admin', function($q) use ($user) {
                $q->where('department_id', $user->profile->departments_id);
            })
            ->groupBy('regulation_id', 'year', 'semester')
            ->with('regulation')
            ->orderBy('year')
            ->orderBy('semester')
            ->get();
        
        $distributionByProgram = [];
        foreach($courseDistribution as $dist) {
            if (!$dist->regulation) continue; // skip if regulation was deleted
            $programType = $dist->regulation->program_type ?? 'Unknown';
            $regCode = $dist->regulation->code ?? 'Unknown';
            $regSuffix = !empty($dist->regulation->curriculum) ? $dist->regulation->curriculum : ($dist->regulation->name && $dist->regulation->name !== $regCode ? $dist->regulation->name : '');
            $displayReg = $regCode . ($regSuffix ? ' - ' . $regSuffix : '');
            
            $key = $programType . ' (' . $displayReg . ')';
            if(!isset($distributionByProgram[$key])) {
                $distributionByProgram[$key] = [];
            }
            
            $distributionByProgram[$key][] = [
                'year' => $dist->year ?: 'N/A',
                'semester' => $dist->semester ?: 'N/A',
                'total' => $dist->total,
                'regulation_id' => $dist->regulation_id,
                'program_type' => $programType
            ];
        }

        $allRawCourses = \App\Models\Course::with(['regulation', 'staff.profile'])
            ->when($user->role === 'admin', function($q) use ($user) {
                $q->where('department_id', $user->profile->departments_id);
            })
            ->get();

        return view('academic.course_allocations', compact('courses', 'regulations', 'departments', 'availableStaff', 'availableProgramTypes', 'distributionByProgram', 'allRawCourses'));
    }

    public function storeCourse(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'no_of_courses' => 'required|integer|min:1|max:9',
            'code' => 'required|array|min:1|max:9',
            'code.*' => 'required|string|max:255|unique:courses,code',
            'name' => 'required|array|min:1|max:9',
            'name.*' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,code',
        ]);

        $regulation = Regulation::findOrFail($request->regulation_id);
        $programType = $regulation->program_type;
        
        $maxYear = 4;
        $maxSem = 8;
        
        if (in_array($programType, ['M.Tech', 'Ph.D', 'M.Sc', 'M.Pharmacy', 'M.A.', 'MBA', 'M.Com', 'LLM', 'M.Arch'])) {
            $maxYear = 2;
            $maxSem = 4;
        } elseif (in_array($programType, ['B.Sc', 'Degree', 'Diploma', 'BBA', 'B.Com', 'B.A.'])) {
            $maxYear = 3;
            $maxSem = 6;
        }

        $request->validate([
            'year' => "required|integer|min:1|max:$maxYear",
            'semester' => "required|integer|min:1|max:$maxSem",
        ], [
            'year.max' => "For this program, the maximum year is $maxYear.",
            'semester.max' => "For this program, the maximum semester is $maxSem.",
        ]);

        $department_id = $request->department_id;
        if ($user->role === 'admin') {
            $department_id = $user->profile->departments_id;
        } elseif (empty($department_id)) {
            return back()->withErrors(['department_id' => 'Department is required.']);
        }

        $numCourses = min($request->no_of_courses, count($request->code));
        for ($i = 0; $i < $numCourses; $i++) {
            Course::create([
                'regulation_id' => $request->regulation_id,
                'department_id' => $department_id,
                'year' => $request->year,
                'semester' => $request->semester,
                'code' => $request->code[$i],
                'name' => $request->name[$i],
            ]);
        }

        return back()->with('success', 'Courses created successfully.');
    }

    public function editCourse(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        $departments = Department::all();

        $programsQuery = \App\Models\Program::query();
        if ($user->role === 'admin' && !empty($user->profile->departments_id)) {
            $dept = \App\Models\Department::where('code', $user->profile->departments_id)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            } else {
                $programsQuery->where('department_id', -1);
            }
        }
        $programs = $programsQuery->get();
        
        $availableProgramTypes = [];
        foreach ($programs as $prog) {
            $pName = $prog->name;
            if (str_contains($pName, 'B.Tech')) $availableProgramTypes[] = 'B.Tech';
            elseif (str_contains($pName, 'M.Tech')) $availableProgramTypes[] = 'M.Tech';
            elseif (str_contains($pName, 'B.Sc')) $availableProgramTypes[] = 'B.Sc';
            elseif (str_contains($pName, 'M.Sc') || str_contains($pName, 'MSc')) $availableProgramTypes[] = 'M.Sc';
            elseif (str_contains($pName, 'Ph.D') || str_contains($pName, 'PhD') || str_contains($pName, 'Doctor of Philosophy')) $availableProgramTypes[] = 'Ph.D';
            elseif (str_contains($pName, 'B.Pharm') || str_contains($pName, 'B. Pharmacy')) $availableProgramTypes[] = 'B.Pharmacy';
            elseif (str_contains($pName, 'M.Pharm') || str_contains($pName, 'M. Pharmacy')) $availableProgramTypes[] = 'M.Pharmacy';
            elseif (str_contains($pName, 'B.A.')) $availableProgramTypes[] = 'B.A.';
            elseif (str_contains($pName, 'M.A.')) $availableProgramTypes[] = 'M.A.';
            elseif (str_contains($pName, 'BBA')) $availableProgramTypes[] = 'BBA';
            elseif (str_contains($pName, 'MBA')) $availableProgramTypes[] = 'MBA';
            elseif (str_contains($pName, 'B.Com')) $availableProgramTypes[] = 'B.Com';
            elseif (str_contains($pName, 'M.Com')) $availableProgramTypes[] = 'M.Com';
            elseif (str_contains($pName, 'LLB')) $availableProgramTypes[] = 'LLB';
            elseif (str_contains($pName, 'LLM')) $availableProgramTypes[] = 'LLM';
            elseif (str_contains($pName, 'B.Arch')) $availableProgramTypes[] = 'B.Arch';
            elseif (str_contains($pName, 'M.Arch')) $availableProgramTypes[] = 'M.Arch';
            elseif (str_contains($pName, 'Diploma')) $availableProgramTypes[] = 'Diploma';
            else $availableProgramTypes[] = explode(' ', $pName)[0];
        }
        $availableProgramTypes = array_unique($availableProgramTypes);
        sort($availableProgramTypes);

        if ($user->role === 'admin') {
            $regulations = Regulation::whereIn('program_type', $availableProgramTypes)->get();
        } else {
            $regulations = Regulation::all();
        }

        return view('academic.courses_edit', compact('course', 'regulations', 'departments', 'availableProgramTypes'));
    }

    public function updateCourse(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'code' => 'required|string|max:255|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,code',
        ]);

        $regulation = Regulation::findOrFail($request->regulation_id);
        $programType = $regulation->program_type;
        
        $maxYear = 4;
        $maxSem = 8;
        
        if (in_array($programType, ['M.Tech', 'Ph.D', 'M.Sc', 'M.Pharmacy', 'M.A.', 'MBA', 'M.Com', 'LLM', 'M.Arch'])) {
            $maxYear = 2;
            $maxSem = 4;
        } elseif (in_array($programType, ['B.Sc', 'Degree', 'Diploma', 'BBA', 'B.Com', 'B.A.'])) {
            $maxYear = 3;
            $maxSem = 6;
        }

        $request->validate([
            'year' => "required|integer|min:1|max:$maxYear",
            'semester' => "required|integer|min:1|max:$maxSem",
        ], [
            'year.max' => "For this program, the maximum year is $maxYear.",
            'semester.max' => "For this program, the maximum semester is $maxSem.",
        ]);

        $department_id = $request->department_id;
        if ($user->role === 'admin') {
            $department_id = $user->profile->departments_id;
        } elseif (empty($department_id)) {
            return back()->withErrors(['department_id' => 'Department is required.']);
        }

        $course->update([
            'regulation_id' => $request->regulation_id,
            'department_id' => $department_id,
            'year' => $request->year,
            'semester' => $request->semester,
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('academic.courses')->with('success', 'Course updated successfully.');
    }

    public function destroyCourse($id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        $course->delete();
        return back()->with('success', 'Course deleted.');
    }

    // --- Staff Allocations ---
    public function allocateStaff(Request $request, $courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $staff = User::role('sta')->findOrFail($request->staff_id);

        if ($staff->profile->departments_id !== $course->department_id) {
            return back()->withErrors(['staff_id' => 'Staff must belong to the same department as the course.']);
        }

        if ($course->staff()->where('users.id', $staff->id)->exists()) {
            return back()->withErrors(['staff_id' => 'This faculty has already been assigned to this course.']);
        }

        // Attach without detaching others
        $course->staff()->syncWithoutDetaching([$staff->id]);

        return back()->with('success', 'Staff allocated to course successfully.');
    }

    public function unallocateStaff($courseId, $staffId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        $course->staff()->detach($staffId);

        return back()->with('success', 'Staff allocation removed.');
    }
    
    // --- Analytics ---
    public function enrollmentInsights()
    {
        $departments = Department::all();
        $departmentCourseCounts = Course::selectRaw('department_id, count(*) as total_courses')
            ->groupBy('department_id')
            ->pluck('total_courses', 'department_id');

        $departmentEnrollmentCounts = \DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->select('courses.department_id', \DB::raw('count(enrollments.id) as total_enrollments'))
            ->groupBy('courses.department_id')
            ->pluck('total_enrollments', 'department_id');
            
        $departmentInsights = collect($departments)->map(function($dept) use ($departmentCourseCounts, $departmentEnrollmentCounts) {
            return [
                'code'             => $dept->code,
                'department'       => $dept->name,
                'total_courses'    => $departmentCourseCounts[$dept->code] ?? 0,
                'total_enrollments'=> $departmentEnrollmentCounts[$dept->code] ?? 0,
            ];
        })->sortByDesc('total_courses')->values()->toArray();

        $topCourses = Course::with(['regulation', 'enrollments.profile'])
            ->withCount('enrollments')
            ->having('enrollments_count', '>', 0)
            ->orderBy('enrollments_count', 'desc')
            ->take(15)
            ->get();

        // All courses grouped by department for client-side filtering
        $allCoursesByDept = Course::with(['regulation', 'enrollments.profile'])
            ->withCount('enrollments')
            ->orderBy('name')
            ->get()
            ->groupBy('department_id')
            ->map(function ($courses) {
                return $courses->map(function ($c) {
                    $students = $c->enrollments->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                            'email' => $student->email ?? 'N/A',
                            'roll_no' => $student->profile->username ?? 'N/A'
                        ];
                    })->values();

                    return [
                        'code'             => $c->code,
                        'name'             => $c->name,
                        'regulation_code'  => optional($c->regulation)->code ?? 'N/A',
                        'enrollments_count'=> $c->enrollments_count,
                        'students'         => $students,
                    ];
                })->values();
            });
            
        $totalEnrollments = \DB::table('enrollments')->count();
        $totalCoursesOffered = Course::count();
        $totalAllocatedCourses = Course::whereHas('staff')->count();
        $overallAllocationRate = $totalCoursesOffered > 0 ? round(($totalAllocatedCourses / $totalCoursesOffered) * 100, 1) : 0;

        return view('academic.enrollments', compact(
            'departmentInsights', 
            'topCourses',
            'allCoursesByDept',
            'totalEnrollments', 
            'totalCoursesOffered',
            'overallAllocationRate'
        ));
    }
}

