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
    public function regulations(Request $request)
    {
        $user = Auth::user();
        $regulationsQuery = Regulation::query();
        
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

        // Include any custom program types from existing regulations
        $existingRegTypes = Regulation::whereNotNull('program_type')->pluck('program_type')->toArray();
        $availableProgramTypes = array_unique(array_merge($availableProgramTypes, $existingRegTypes));
        sort($availableProgramTypes);
        
        if ($user->role === 'admin') {
            $regulationsQuery->whereIn('program_type', $availableProgramTypes);
        }

        if ($request->filled('program_type')) {
            $regulationsQuery->where('program_type', $request->program_type);
        }

        if ($request->filled('status')) {
            $regulationsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $regulationsQuery->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('curriculum', 'like', "%{$search}%")
                  ->orWhere('program_type', 'like', "%{$search}%");
            });
        }

        $allRegulationsQuery = clone $regulationsQuery;
        $totalRegulations = (clone $regulationsQuery)->count();
        $activeRegulationsCount = (clone $regulationsQuery)->where('status', 'Active')->count();
        $allRegulations = (clone $allRegulationsQuery)->withCount('courses')->orderBy('program_type')->orderBy('code')->get();
        
        $regulations = $regulationsQuery->latest()->paginate(10)->withQueryString();
        
        return view('academic.regulations', compact(
            'regulations', 
            'availableProgramTypes',
            'totalRegulations',
            'activeRegulationsCount',
            'allRegulations'
        ));
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

        // Include any custom program types from existing regulations
        $existingRegTypes = Regulation::whereNotNull('program_type')->pluck('program_type')->toArray();
        $availableProgramTypes = array_unique(array_merge($availableProgramTypes, $existingRegTypes));
        sort($availableProgramTypes);

        if ($user->role === 'admin') {
            $regulations = Regulation::whereIn('program_type', $availableProgramTypes)->get();
        } else {
            $regulations = Regulation::all();
        }

        $schools = \App\Models\School::orderBy('name')->get();
        $departments = Department::with('school')->orderBy('name')->get();

        // Available staff for direct faculty allocation
        $staffQuery = User::role('sta')->with(['profile.department', 'profile.school']);
        if ($user->role === 'admin' && !empty($user->profile?->departments_id)) {
            $staffQuery->whereHas('profile', function($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        $availableStaff = $staffQuery->get()->sortBy(function($s) {
            return ($s->username ?? '') . ' ' . ($s->profile->first_name ?? '');
        });

        return view('academic.courses_create', compact(
            'regulations', 'departments', 'schools', 'availableProgramTypes', 'availableStaff'
        ));
    }

    public function courses(Request $request)
    {
        $user = Auth::user();
        $query = Course::with(['regulation', 'department', 'staff.profile.department', 'staff.profile.school']);
        
        if ($user->role === 'admin') {
            $query->where('department_id', $user->profile->departments_id);
        }

        if ($user->role === 'ssh_admin') {
            $query->where('year', 1);
        }

        if (in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('regulation_id')) {
            $query->where('regulation_id', $request->regulation_id);
        }

        if ($request->filled('year')) {
            if ($user->role === 'ssh_admin') {
                $query->where('year', 1);
            } else {
                $query->where('year', $request->year);
            }
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
        $staffQuery = User::role('sta')->with(['profile.department', 'profile.school']);
        if ($user->role === 'admin') {
            $staffQuery->whereHas('profile', function($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        $availableStaff = $staffQuery->get()->sortBy(function($s) {
            return ($s->profile->department->name ?? 'Z') . ' ' . ($s->profile->first_name ?? $s->username);
        });

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

        // Include any custom program types from existing regulations
        $existingRegTypes = Regulation::whereNotNull('program_type')->pluck('program_type')->toArray();
        $availableProgramTypes = array_unique(array_merge($availableProgramTypes, $existingRegTypes));
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
            ->when($user->role === 'ssh_admin', function($q) {
                $q->where('year', 1);
            })
            ->when(in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department'), function($q) use ($request) {
                $q->where('department_id', $request->department);
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
            ->when($user->role === 'ssh_admin', function($q) {
                $q->where('year', 1);
            })
            ->when(in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department'), function($q) use ($request) {
                $q->where('department_id', $request->department);
            })
            ->get();

        $totalCourses = $allRawCourses->count();

        return view('academic.courses', compact('courses', 'regulations', 'departments', 'availableStaff', 'availableProgramTypes', 'distributionByProgram', 'allRawCourses', 'totalCourses'));
    }

    
    public function courseAllocations(Request $request)
    {
        $user = Auth::user();
        $query = Course::with(['regulation', 'department', 'staff.profile.department', 'staff.profile.school']);
        
        if ($user->role === 'admin') {
            $query->where('department_id', $user->profile->departments_id);
        }

        if ($user->role === 'ssh_admin') {
            $query->where('year', 1);
        }

        if (in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('regulation_id')) {
            $query->where('regulation_id', $request->regulation_id);
        }

        if ($request->filled('year')) {
            if ($user->role === 'ssh_admin') {
                $query->where('year', 1);
            } else {
                $query->where('year', $request->year);
            }
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
        $staffQuery = User::role('sta')->with(['profile.department', 'profile.school']);
        if ($user->role === 'admin') {
            $staffQuery->whereHas('profile', function($q) use ($user) {
                $q->where('departments_id', $user->profile->departments_id);
            });
        }
        $availableStaff = $staffQuery->get()->sortBy(function($s) {
            return ($s->profile->department->name ?? 'Z') . ' ' . ($s->profile->first_name ?? $s->username);
        });

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

        // Include any custom program types from existing regulations
        $existingRegTypes = Regulation::whereNotNull('program_type')->pluck('program_type')->toArray();
        $availableProgramTypes = array_unique(array_merge($availableProgramTypes, $existingRegTypes));
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
            ->when($user->role === 'ssh_admin', function($q) {
                $q->where('year', 1);
            })
            ->when(in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department'), function($q) use ($request) {
                $q->where('department_id', $request->department);
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
            ->when($user->role === 'ssh_admin', function($q) {
                $q->where('year', 1);
            })
            ->when(in_array($user->role, ['sa', 'ssh_admin']) && $request->filled('department'), function($q) use ($request) {
                $q->where('department_id', $request->department);
            })
            ->get();

        return view('academic.course_allocations', compact('courses', 'regulations', 'departments', 'availableStaff', 'availableProgramTypes', 'distributionByProgram', 'allRawCourses'));
    }

    public function storeCourse(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'semester' => 'required|integer|min:1|max:12',
            'no_of_courses' => 'required|integer|min:1|max:20',
            'code' => 'required|array|min:1|max:20',
            'code.*' => 'required|string|max:255|unique:courses,code',
            'name' => 'required|array|min:1|max:20',
            'name.*' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,code',
        ]);

        if ($user->role === 'ssh_admin') {
            $year = 1;
        } else {
            $sem = (int) $request->semester;
            $year = $request->filled('year') ? (int) $request->year : (int) ceil($sem / 2);
        }

        $department_id = $request->department_id;
        if ($user->role === 'admin' && !empty($user->profile?->departments_id)) {
            $department_id = $user->profile->departments_id;
        } elseif (empty($department_id)) {
            $department_id = $user->profile?->departments_id ?? 'dep_ssh';
        }

        $numCourses = min($request->no_of_courses, count($request->code));
        for ($i = 0; $i < $numCourses; $i++) {
            $course = Course::create([
                'regulation_id' => $request->regulation_id,
                'department_id' => $department_id,
                'year' => $year,
                'semester' => $request->semester,
                'code' => strtoupper(trim($request->code[$i])),
                'name' => trim($request->name[$i]),
            ]);

            // Assign multiple faculty based on array of IDs, Employee Codes, or Names
            if (isset($request->staff_id[$i])) {
                $rawStaffData = $request->staff_id[$i];
                $staffTokens = [];

                if (is_array($rawStaffData)) {
                    $staffTokens = $rawStaffData;
                } elseif (is_string($rawStaffData) && trim($rawStaffData) !== '') {
                    $staffTokens = preg_split('/[,;\n]+/', $rawStaffData);
                }

                $matchedStaffIds = [];
                foreach ($staffTokens as $token) {
                    $val = trim((string)$token);
                    if ($val === '') continue;

                    $staffUser = null;

                    // 1. Direct numeric user ID
                    if (is_numeric($val)) {
                        $staffUser = User::role('sta')->find($val);
                    }

                    // 2. Direct Employee Code match (e.g. "08001", "EMP01")
                    if (!$staffUser) {
                        $staffUser = User::role('sta')->where('username', $val)->first();
                    }

                    // 3. If entered in format "EMP01 - Full Name" or "08001 - Gopi V"
                    if (!$staffUser && str_contains($val, '-')) {
                        $empCode = trim(explode('-', $val)[0]);
                        $staffUser = User::role('sta')->where('username', $empCode)->first();
                    }

                    // 4. Match by faculty first/last name
                    if (!$staffUser) {
                        $staffUser = User::role('sta')->whereHas('profile', function($q) use ($val) {
                            $q->whereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", ["%{$val}%"]);
                        })->first();
                    }

                    if ($staffUser) {
                        $matchedStaffIds[] = $staffUser->id;
                    }
                }

                if (!empty($matchedStaffIds)) {
                    $course->staff()->sync(array_unique($matchedStaffIds));
                }
            }
        }

        return redirect()->route('academic.courses')->with('success', "$numCourses course(s) created successfully!");
    }

    public function editCourse(Request $request, $id)
    {
        $user = Auth::user();
        $course = Course::findOrFail($id);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        if ($user->role === 'ssh_admin' && $course->year != 1) {
            abort(403, 'Unauthorized. SSH Admin can only manage 1st Year courses.');
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

        // Include any custom program types from existing regulations
        $existingRegTypes = Regulation::whereNotNull('program_type')->pluck('program_type')->toArray();
        $availableProgramTypes = array_unique(array_merge($availableProgramTypes, $existingRegTypes));
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

        if ($user->role === 'ssh_admin' && $course->year != 1) {
            abort(403, 'Unauthorized. SSH Admin can only manage 1st Year courses.');
        }

        $request->validate([
            'regulation_id' => 'required|exists:regulations,id',
            'code' => 'required|string|max:255|unique:courses,code,' . $course->id,
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,code',
        ]);

        if ($user->role === 'ssh_admin') {
            $request->validate([
                'year' => 'required|integer|in:1',
                'semester' => 'required|integer|in:1,2',
            ], [
                'year.in' => 'SSH Admin can only update courses for 1st Year (1-1 and 1-2).',
                'semester.in' => 'Semester must be 1 (1-1) or 2 (1-2).',
            ]);
        } else {
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
        }

        $department_id = $request->department_id;
        if ($user->role === 'admin') {
            $department_id = $user->profile->departments_id;
        } elseif (empty($department_id)) {
            return back()->withErrors(['department_id' => 'Department is required.']);
        }

        $course->update([
            'regulation_id' => $request->regulation_id,
            'department_id' => $department_id,
            'year' => $user->role === 'ssh_admin' ? 1 : $request->year,
            'semester' => $request->semester,
            'code' => strtoupper(trim($request->code)),
            'name' => trim($request->name),
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

        if ($user->role === 'ssh_admin' && $course->year != 1) {
            abort(403, 'Unauthorized. SSH Admin can only manage 1st Year courses.');
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

        if ($user->role === 'ssh_admin' && $course->year != 1) {
            abort(403, 'Unauthorized. SSH Admin can only allocate staff to 1st Year courses.');
        }

        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $staff = User::role('sta')->findOrFail($request->staff_id);

        $isPrivileged = in_array($user->role, ['sa', 'ssh_admin']);
        $staffDept = strtolower($staff->profile->departments_id ?? '');
        $isSshStaff = in_array($staffDept, ['sc_ash', 'dep_ssh', 'dep_maths', 'dep_phy', 'dep_chem', 'dep_eng', 'ash', 'ssh']) 
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'humanities') 
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'science')
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'mathematics')
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'physics')
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'chemistry')
            || str_contains(strtolower($staff->profile->department->name ?? ''), 'english');

        if (!$isPrivileged && !$isSshStaff && $staff->profile->departments_id !== $course->department_id) {
            return back()->withErrors(['staff_id' => 'Staff must belong to the same department as the course or be an S&H faculty member.']);
        }

        if ($course->staff()->where('users.id', $staff->id)->exists()) {
            return back()->withErrors(['staff_id' => 'This faculty has already been assigned to this course.']);
        }

        // Attach without detaching others
        $course->staff()->syncWithoutDetaching([$staff->id]);

        $staffName = trim(($staff->profile->first_name ?? '') . ' ' . ($staff->profile->last_name ?? '')) ?: $staff->username;
        $deptName = $staff->profile->department->name ?? 'Department';

        \App\Services\ActivityLogger::log(
            'course_allocated',
            'Staff Allocated to Course',
            'Academics',
            'Assigned faculty ' . $staffName . ' (' . $deptName . ') to teach ' . $course->name . ' (' . $course->code . ').',
            'success',
            [
                'department_id' => $course->department_id,
                'entity_type' => 'Course',
                'entity_id' => $course->id,
                'entity_name' => $course->name,
                'payload' => ['staff_id' => $staff->id, 'course_code' => $course->code]
            ]
        );

        return back()->with('success', "Faculty {$staffName} ({$deptName}) allocated to {$course->code} successfully.");
    }

    public function unallocateStaff($courseId, $staffId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        if ($user->role === 'admin' && $course->department_id !== $user->profile->departments_id) {
            abort(403, 'Unauthorized.');
        }

        if ($user->role === 'ssh_admin' && $course->year != 1) {
            abort(403, 'Unauthorized. SSH Admin can only manage 1st Year courses.');
        }

        $course->staff()->detach($staffId);

        \App\Services\ActivityLogger::log(
            'course_unallocated',
            'Staff Allocation Removed',
            'Academics',
            'Removed faculty assignment from course ' . $course->name . ' (' . $course->code . ').',
            'warning',
            [
                'department_id' => $course->department_id,
                'entity_type' => 'Course',
                'entity_id' => $course->id,
                'entity_name' => $course->name,
                'payload' => ['staff_id' => $staffId, 'course_code' => $course->code]
            ]
        );

        return back()->with('success', 'Staff allocation removed.');
    }
    
    // --- Analytics ---
    public function enrollmentInsights(Request $request)
    {
        $departments = Department::with('school')->get();
        
        // Fetch all courses with all necessary relations for fast in-memory aggregation
        $courses = Course::with([
            'regulation', 
            'department.school', 
            'staff.profile', 
            'materials', 
            'assignments', 
            'enrollments.profile'
        ])
        ->withCount(['enrollments', 'materials', 'assignments', 'staff'])
        ->get();

        // Department coordinators (admins)
        $coordinators = User::role('admin')->with('profile')->get()->groupBy(function($u) {
            return $u->profile->departments_id ?? '';
        });

        // Faculty by department
        $deptStaff = User::role(['sta', 'staff'])->with('profile')->get()->groupBy(function($u) {
            return $u->profile->departments_id ?? '';
        });

        // Global KPI metrics
        $totalEnrollments = \DB::table('enrollments')->count();
        $totalCoursesOffered = $courses->count();
        $totalAllocatedCourses = $courses->where('staff_count', '>', 0)->count();
        $overallAllocationRate = $totalCoursesOffered > 0 ? round(($totalAllocatedCourses / $totalCoursesOffered) * 100, 1) : 0;
        $totalFaculty = User::role(['sta', 'staff'])->count();
        $totalStudents = User::role(['stu', 'student'])->count();
        $totalMaterials = \App\Models\CourseMaterial::count();
        $totalAssignments = \App\Models\Assignment::count();

        // Build comprehensive per-department payload
        $departmentsData = [];

        foreach ($departments as $dept) {
            $deptCourses = $courses->where('department_id', $dept->code)->values();
            $deptCourseCount = $deptCourses->count();
            $deptAllocatedCount = $deptCourses->where('staff_count', '>', 0)->count();
            $deptAllocRate = $deptCourseCount > 0 ? round(($deptAllocatedCount / $deptCourseCount) * 100, 1) : 0;
            
            // Total enrollments across courses in this dept
            $deptEnrollmentCount = $deptCourses->sum('enrollments_count');
            
            // Unique enrolled student IDs and roster
            $uniqueStudentIds = collect();
            $allDeptStudents = collect();
            
            foreach ($deptCourses as $c) {
                foreach ($c->enrollments as $stu) {
                    if (!$uniqueStudentIds->contains($stu->id)) {
                        $uniqueStudentIds->push($stu->id);
                        $allDeptStudents->push([
                            'id' => $stu->id,
                            'roll_no' => $stu->profile->username ?? $stu->username,
                            'name' => trim(($stu->profile->first_name ?? '') . ' ' . ($stu->profile->last_name ?? '')),
                            'email' => $stu->email ?? $stu->profile->email ?? 'N/A',
                            'phone' => $stu->profile->phone ?? 'N/A',
                            'year' => $c->year ?? 'N/A',
                            'semester' => $c->semester ?? 'N/A',
                            'course_code' => $c->code,
                            'course_name' => $c->name,
                        ]);
                    }
                }
            }

            // Faculty teaching in this department
            $teachingStaffIds = collect();
            $facultyList = collect();
            
            // Add department profile staff
            $baseStaff = $deptStaff->get($dept->code, collect());
            foreach ($baseStaff as $st) {
                if (!$teachingStaffIds->contains($st->id)) {
                    $teachingStaffIds->push($st->id);
                    $assignedCourseObjs = $deptCourses->filter(function($c) use ($st) {
                        return $c->staff->contains('id', $st->id);
                    });
                    $totalTaughtStudents = $assignedCourseObjs->sum('enrollments_count');
                    
                    $facultyList->push([
                        'id' => $st->id,
                        'name' => trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')),
                        'staff_id' => $st->profile->username ?? $st->username,
                        'email' => $st->email ?? $st->profile->email ?? 'N/A',
                        'phone' => $st->profile->phone ?? 'N/A',
                        'photo' => $st->profile->photo ?? null,
                        'courses_count' => $assignedCourseObjs->count(),
                        'courses' => $assignedCourseObjs->map(fn($ac) => ['code' => $ac->code, 'name' => $ac->name])->values(),
                        'students_taught' => $totalTaughtStudents,
                    ]);
                }
            }

            // Also check if any staff from other dept is assigned to courses in this dept
            foreach ($deptCourses as $c) {
                foreach ($c->staff as $st) {
                    if (!$teachingStaffIds->contains($st->id)) {
                        $teachingStaffIds->push($st->id);
                        $assignedCourseObjs = $deptCourses->filter(function($dc) use ($st) {
                            return $dc->staff->contains('id', $st->id);
                        });
                        $totalTaughtStudents = $assignedCourseObjs->sum('enrollments_count');
                        
                        $facultyList->push([
                            'id' => $st->id,
                            'name' => trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')),
                            'staff_id' => $st->profile->username ?? $st->username,
                            'email' => $st->email ?? $st->profile->email ?? 'N/A',
                            'phone' => $st->profile->phone ?? 'N/A',
                            'photo' => $st->profile->photo ?? null,
                            'courses_count' => $assignedCourseObjs->count(),
                            'courses' => $assignedCourseObjs->map(fn($ac) => ['code' => $ac->code, 'name' => $ac->name])->values(),
                            'students_taught' => $totalTaughtStudents,
                        ]);
                    }
                }
            }

            // Coordinators list
            $deptCoords = $coordinators->get($dept->code, collect())->map(function($cd) {
                return [
                    'id' => $cd->id,
                    'name' => trim(($cd->profile->first_name ?? '') . ' ' . ($cd->profile->last_name ?? '')),
                    'username' => $cd->profile->username ?? $cd->username,
                    'email' => $cd->email ?? $cd->profile->email ?? 'N/A',
                    'phone' => $cd->profile->phone ?? 'N/A',
                ];
            })->values();

            // Regulation breakdown
            $regulationsBreakdown = $deptCourses->groupBy(function($c) {
                return $c->regulation ? ($c->regulation->code . ($c->regulation->curriculum ? ' (' . $c->regulation->curriculum . ')' : '')) : 'Direct / Non-Regulated';
            })->map(function($rcourses, $regName) {
                return [
                    'regulation' => $regName,
                    'courses_count' => $rcourses->count(),
                    'enrollments_count' => $rcourses->sum('enrollments_count'),
                ];
            })->values();

            // Formatted courses list
            $formattedCourses = $deptCourses->map(function($c) {
                $students = $c->enrollments->map(function($st) {
                    return [
                        'id' => $st->id,
                        'roll_no' => $st->profile->username ?? $st->username,
                        'name' => trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')),
                        'email' => $st->email ?? $st->profile->email ?? 'N/A',
                    ];
                })->values();

                $staffMembers = $c->staff->map(function($st) {
                    return [
                        'id' => $st->id,
                        'staff_id' => $st->profile->username ?? $st->username,
                        'name' => trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')),
                        'photo' => $st->profile->photo ?? null,
                    ];
                })->values();

                return [
                    'id' => $c->id,
                    'code' => $c->code,
                    'name' => $c->name,
                    'year' => $c->year,
                    'semester' => $c->semester,
                    'regulation_code' => $c->regulation->code ?? 'N/A',
                    'curriculum' => $c->regulation->curriculum ?? 'N/A',
                    'is_allocated' => $c->staff_count > 0,
                    'staff' => $staffMembers,
                    'enrollments_count' => $c->enrollments_count,
                    'materials_count' => $c->materials_count,
                    'assignments_count' => $c->assignments_count,
                    'students' => $students,
                ];
            })->values();

            $departmentsData[$dept->code] = [
                'code' => $dept->code,
                'name' => $dept->name,
                'school_name' => $dept->school->name ?? 'General School',
                'school_code' => $dept->school_id,
                'total_courses' => $deptCourseCount,
                'allocated_courses' => $deptAllocatedCount,
                'unallocated_courses' => $deptCourseCount - $deptAllocatedCount,
                'allocation_rate' => $deptAllocRate,
                'total_enrollments' => $deptEnrollmentCount,
                'unique_students_count' => $uniqueStudentIds->count(),
                'faculty_count' => $facultyList->count(),
                'materials_count' => $deptCourses->sum('materials_count'),
                'assignments_count' => $deptCourses->sum('assignments_count'),
                'coordinators' => $deptCoords,
                'regulations_breakdown' => $regulationsBreakdown,
                'courses' => $formattedCourses,
                'faculty' => $facultyList,
                'students' => $allDeptStudents->values(),
            ];
        }

        // Sort departments list by courses and enrollments
        $departmentsList = collect($departmentsData)->sortByDesc('total_courses')->values();

        // Top courses across university
        $topCourses = Course::with(['regulation', 'enrollments.profile'])
            ->withCount('enrollments')
            ->having('enrollments_count', '>', 0)
            ->orderBy('enrollments_count', 'desc')
            ->take(15)
            ->get();

        $selectedDepartment = $request->query('department', 'all');

        // Legacy compatibility
        $departmentInsights = $departmentsList->map(function($d) {
            return [
                'code' => $d['code'],
                'department' => $d['name'],
                'total_courses' => $d['total_courses'],
                'total_enrollments' => $d['total_enrollments'],
            ];
        })->toArray();

        $allCoursesByDept = collect($departmentsData)->map(fn($d) => $d['courses']);

        return view('academic.enrollments', compact(
            'departmentsData',
            'departmentsList',
            'selectedDepartment',
            'topCourses',
            'departmentInsights',
            'allCoursesByDept',
            'totalEnrollments',
            'totalCoursesOffered',
            'totalAllocatedCourses',
            'overallAllocationRate',
            'totalFaculty',
            'totalStudents',
            'totalMaterials',
            'totalAssignments'
        ));
    }
}


