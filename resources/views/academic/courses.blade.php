<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="w-full space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Manage Courses</h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/80 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            {{ $courses->total() }} {{ Str::plural('Course', $courses->total()) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('academic.courses.allocations') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold py-2 px-3.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5 text-xs">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Assign Faculty (Allocations)</span>
                        </a>
                        <a href="{{ route('academic.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm shadow-indigo-200 transition-all flex items-center gap-1.5 text-xs focus:outline-none">
                            <span>+ Add New Course</span>
                        </a>
                    </div>
                </div>

                <!-- Total Courses Stat Card -->
                <div id="stats-container" class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-4 sm:p-5 flex items-center justify-between transition-all hover:shadow-md">
                    <div class="flex items-center gap-4 text-left">
                        <div class="p-3 sm:p-3.5 rounded-full bg-indigo-100 text-indigo-600 transition-all shadow-sm shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $courses->total() }}</span>
                                @if(request()->hasAny(['search', 'regulation_id', 'department', 'year', 'semester']) && (request('search') || request('regulation_id') || request('department') || request('year') || request('semester')))
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-0.5 rounded-full">
                                        Filtered Results @if(isset($totalCourses) && $totalCourses != $courses->total()) (Total: {{ $totalCourses }}) @endif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mt-0.5">Total Courses</p>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-xs border border-slate-200/80 mb-6 transition-all hover:shadow-sm">
                    <form action="{{ route('academic.courses') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full" id="filterForm">
                        <!-- Search Box -->
                        <div class="flex-1 min-w-[220px] w-full">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3.5 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium" placeholder="Search by course code or name...">
                            </div>
                        </div>
                        
                        <!-- Regulation -->
                        <div class="w-full sm:w-48 lg:w-56 shrink-0">
                            <label for="regulation_id" class="sr-only">Regulation</label>
                            <select name="regulation_id" id="regulation_id" onchange="this.form.submit()" class="block w-full text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                <option value="">All Regulations</option>
                                @foreach($regulations as $reg)
                                    <option value="{{ $reg->id }}" {{ request('regulation_id') == $reg->id ? 'selected' : '' }}>{{ $reg->code }}{{ !empty($reg->curriculum) ? ' - ' . $reg->curriculum : ($reg->name && $reg->name !== $reg->code ? ' - ' . $reg->name : '') }} ({{ $reg->program_type }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Department -->
                        @if(in_array(Auth::user()->role, ['sa', 'ssh_admin']))
                        <div class="w-full sm:w-48 shrink-0">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" onchange="this.form.submit()" class="block w-full text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <!-- Year & Semester Dual Grid on Mobile -->
                        <div class="grid grid-cols-2 gap-3 w-full sm:w-auto sm:flex sm:items-center sm:gap-3 shrink-0">
                            <div class="w-full sm:w-28">
                                <label for="year" class="sr-only">Year</label>
                                <select name="year" id="year" onchange="this.form.submit()" class="block w-full text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                    @if(Auth::user()->role === 'ssh_admin')
                                        <option value="1">Year 1</option>
                                    @else
                                        <option value="">All Years</option>
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                                        @endfor
                                    @endif
                                </select>
                            </div>

                            <div class="w-full sm:w-28">
                                <label for="semester" class="sr-only">Semester</label>
                                <select name="semester" id="semester" onchange="this.form.submit()" class="block w-full text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                    <option value="">All Sems</option>
                                    @for($i = 1; $i <= 2; $i++)
                                        <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Sem {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <!-- Buttons in responsive flex / dual grid -->
                        <div class="grid grid-cols-2 gap-3 w-full sm:w-auto sm:flex sm:items-center sm:gap-2.5 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-xs sm:text-sm font-semibold rounded-xl shadow-xs text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                                Apply Filters
                            </button>
                            <a href="{{ route('academic.courses') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-slate-200 text-xs sm:text-sm font-medium rounded-xl text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-xs" title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>


                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <!-- List (Full Width) -->
                    <div class="w-full">
                        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/90 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-800">Course List</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    <span class="text-xs text-slate-500 font-medium">Showing <span class="font-bold text-slate-800">{{ $courses->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $courses->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $courses->total() }}</span> {{ Str::plural('course', $courses->total()) }}</span>
                                </div>
                                <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                                    {{ $courses->total() }} Total
                                </span>
                            </div>
                            <div class="overflow-x-auto w-full">
                                <table class="w-full divide-y divide-slate-200 text-left">
                                    <thead class="bg-slate-50/90">
                                        <tr>
                                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[170px]">Subject</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[130px]">Program & Reg</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[100px]">Year/Sem</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[160px]">Dept</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[280px]">Allocated Faculty</th>
                                            <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-700 uppercase tracking-wider whitespace-nowrap min-w-[110px]">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        @forelse($courses as $course)
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="px-5 py-3 text-sm text-gray-900">
                                                <div class="font-bold text-indigo-700 font-mono">{{ $course->code }}</div>
                                                <div class="text-slate-800 font-medium text-xs">{{ $course->name }}</div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <div class="font-medium text-slate-800 text-xs">{{ $course->regulation->program_type ?? 'N/A' }}</div>
                                                <div class="text-slate-400 text-xs">{{ $course->regulation->code ?? 'N/A' }}{{ !empty($course->regulation->curriculum) ? ' - ' . $course->regulation->curriculum : ($course->regulation && $course->regulation->name && $course->regulation->name !== $course->regulation->code ? ' - ' . $course->regulation->name : '') }}</div>
                                            </td>
                                            <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">
                                                    Sem {{ $course->semester ?? '1' }} (Yr {{ $course->year ?? '1' }})
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-xs text-slate-600 font-medium">{{ $course->department->name ?? $course->department_id }}</td>
                                            
                                            <!-- Allocated Faculty -->
                                            <td class="px-5 py-3 text-xs">
                                                <div class="space-y-1.5 min-w-[260px]">
                                                    @if($course->staff->count() > 0)
                                                        <div class="flex flex-wrap items-center gap-1.5">
                                                            @foreach($course->staff as $fac)
                                                                @php
                                                                    $facFullName = trim(($fac->first_name ?? '') . ' ' . ($fac->last_name ?? ''));
                                                                    $facCode = $fac->username ?? 'N/A';
                                                                    $facEmail = $fac->profile->email ?? ($fac->email ?? '');
                                                                    $facPhone = $fac->profile->phone ?? '';
                                                                    $facDesig = $fac->profile->designation ?? 'Faculty';
                                                                    $facDept = $fac->profile->department->name ?? ($fac->profile->departments_id ?? 'Social Sciences & Humanities');
                                                                    $facSchool = $fac->profile->school->name ?? ($fac->profile->schools_id ?? 'School of Applied Sciences & Humanities');
                                                                    $facPhoto = $fac->profile->photo ? asset('storage/' . $fac->profile->photo) : '';
                                                                    $unallocUrl = route('academic.courses.unallocate', [$course->id, $fac->id]);
                                                                    $courseContext = $course->code . ' - ' . $course->name . ' (Sem ' . $course->semester . ')';
                                                                    $facDeptName = $fac->profile->department->name ?? ($fac->profile->departments_id ?? 'Dept');
                                                                    $facDeptShort = $fac->profile->department->code ?? (strlen($facDeptName) > 12 ? substr($facDeptName, 0, 10).'..' : $facDeptName);
                                                                @endphp
                                                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-indigo-50/90 text-indigo-700 border border-indigo-100 text-xs font-semibold shadow-2xs group hover:bg-indigo-100/90 transition-all">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 shrink-0"></span>
                                                                    <button type="button" 
                                                                        onclick="showFacultyDetails('{{ addslashes($facFullName) }}', '{{ addslashes($facCode) }}', '{{ addslashes($facEmail) }}', '{{ addslashes($facPhone) }}', '{{ addslashes($facDesig) }}', '{{ addslashes($facDept) }}', '{{ addslashes($facSchool) }}', '{{ $facPhoto }}', '{{ $unallocUrl }}', '{{ addslashes($courseContext) }}')"
                                                                        class="hover:underline focus:outline-none text-left cursor-pointer font-semibold text-indigo-900"
                                                                        title="Click to view details of {{ $facFullName }}">
                                                                        {{ $facFullName }} <span class="text-[10px] text-indigo-500 font-normal">({{ $facDeptShort }})</span>
                                                                    </button>
                                                                    <button type="button" 
                                                                        onclick="window.openUniversalDeleteModal({
                                                                            title: 'Remove Faculty Allocation',
                                                                            itemName: '{{ addslashes($facFullName) }} ({{ addslashes($facCode) }})',
                                                                            itemType: 'Course Allocation: {{ addslashes($course->code) }}',
                                                                            deleteUrl: '{{ $unallocUrl }}',
                                                                            warningMessage: 'Removing this faculty member will unassign them from teaching {{ addslashes($course->name) }} and revoke their material & grade management access.',
                                                                            cascadeItems: [
                                                                                { label: 'Course Section Access', count: 'Revoked' },
                                                                                { label: 'Faculty Course Link', count: '1 Removed' }
                                                                            ]
                                                                        })"
                                                                        class="text-indigo-400 hover:text-rose-600 font-bold ml-0.5 leading-none transition-colors p-0.5 rounded focus:outline-none" 
                                                                        title="Remove {{ $facFullName }} from {{ $course->code }}">&times;</button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-[11px] text-slate-400 font-medium italic">
                                                            No faculty allocated yet
                                                        </div>
                                                    @endif

                                                    <!-- Quick Inline Assign Dropdown -->
                                                    <form action="{{ route('academic.courses.allocate', $course->id) }}" method="POST" class="flex items-center gap-1.5 w-full">
                                                        @csrf
                                                        <div class="relative flex-1">
                                                            <select name="staff_id" required class="w-full text-xs py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-white hover:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none cursor-pointer transition-all shadow-2xs">
                                                                <option value="">+ Assign Faculty...</option>
                                                                @php
                                                                    $groupedStaff = $availableStaff->groupBy(function($st) {
                                                                        return $st->profile->department->name ?? ($st->profile->school->name ?? 'General / S&H');
                                                                    });
                                                                @endphp
                                                                @foreach($groupedStaff as $deptName => $staffList)
                                                                    <optgroup label="{{ $deptName }}">
                                                                        @foreach($staffList as $st)
                                                                            @if(!$course->staff->contains('id', $st->id))
                                                                                <option value="{{ $st->id }}">
                                                                                    {{ $st->username }} - {{ $st->first_name }} {{ $st->last_name }} ({{ $st->profile->designation ?? 'Faculty' }})
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="py-1 px-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-xs transition-colors shrink-0" title="Assign selected faculty">
                                                            Assign
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                            <td class="px-5 py-3 whitespace-nowrap text-sm font-medium text-center min-w-[110px]">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <a href="{{ route('academic.courses.edit', $course->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Course">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                    </a>
                                                    @php
                                                        $cCode = addslashes($course->code);
                                                        $cName = addslashes($course->name);
                                                        $cDept = addslashes($course->department->name ?? $course->department_id);
                                                        $cReg = addslashes(($course->regulation->code ?? 'N/A') . (!empty($course->regulation->curriculum) ? ' - ' . $course->regulation->curriculum : ''));
                                                        $cYear = $course->year ?? 1;
                                                        $cSem = $course->semester ?? 1;
                                                        $cStaffCount = $course->staff_count ?? $course->staff->count();
                                                        $cEnrollCount = $course->enrollments_count ?? 0;
                                                        $cMatCount = $course->materials_count ?? 0;
                                                        $cAssignCount = $course->assignments_count ?? 0;
                                                        $cDelUrl = route('academic.courses.destroy', $course->id);
                                                    @endphp
                                                    <button type="button" 
                                                        onclick="openDeleteCourseModal('{{ $cCode }}', '{{ $cName }}', '{{ $cDept }}', '{{ $cReg }}', '{{ $cYear }}', '{{ $cSem }}', {{ $cStaffCount }}, {{ $cEnrollCount }}, {{ $cMatCount }}, {{ $cAssignCount }}, '{{ $cDelUrl }}')"
                                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5 cursor-pointer" 
                                                        title="Delete Course & All Linked Data">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No courses found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/40">
                                {{ $courses->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Change Password</h3>
                <button type="button" id="closePasswordModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Enter new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('partials.bulk_upload_modal')
    @include('partials.faculty_details_modal')

    <script>
        const availableStaff = @json($availableStaff->map(function($s) {
            return ['id' => $s->id, 'name' => $s->first_name . ' ' . $s->last_name];
        }));
        const csrfToken = '{{ csrf_token() }}';        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Dynamic Department Filtering based on School Selection
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.getElementById('school');
            const departmentSelect = document.getElementById('department');
            
            if (schoolSelect && departmentSelect) {
                // Store all original department options (excluding the default "All Departments" option)
                const allDepartments = Array.from(departmentSelect.options).filter(opt => opt.value !== '');
                const defaultOption = departmentSelect.options[0];
                
                function filterDepartments() {
                    const selectedSchoolCode = schoolSelect.value;
                    let currentDeptValue = departmentSelect.value;
                    let deptStillValid = false;

                    // Clear current options except the default one
                    departmentSelect.innerHTML = '';
                    departmentSelect.appendChild(defaultOption);

                    allDepartments.forEach(option => {
                        if (!selectedSchoolCode || option.getAttribute('data-school-id') === selectedSchoolCode) {
                            departmentSelect.appendChild(option);
                            if (option.value === currentDeptValue) {
                                deptStillValid = true;
                            }
                        }
                    });

                    // If the previously selected department is no longer in the list, reset to default
                    if (!deptStillValid && currentDeptValue !== '') {
                        departmentSelect.value = '';
                    }
                }
                
                // Listen for school changes
                schoolSelect.addEventListener('change', filterDepartments);
                
                // Run on initial load
                filterDepartments();
                
                // Ensure the selected department from the request is maintained if it's valid
                const requestedDept = "{{ request('department') }}";
                if(requestedDept) {
                     departmentSelect.value = requestedDept;
                }
            }
        });

        // Dynamic Regulation Filtering and Year/Sem populating based on Program Type
        document.addEventListener('DOMContentLoaded', function() {
            const progSelect = document.getElementById('program_type_select');
            const regSelect = document.getElementById('regulation_id_select');
            const yearSelect = document.getElementById('year_select');
            const semesterSelect = document.getElementById('semester_select');
            
            if (progSelect && regSelect && yearSelect && semesterSelect) {
                const allRegs = Array.from(regSelect.options).filter(opt => opt.value !== '');
                const defRegOpt = regSelect.options[0];
                
                const updateYearSem = function() {
                    const selectedProg = progSelect.value;
                    if (!selectedProg) {
                        yearSelect.innerHTML = '<option value="">-- Year --</option>';
                        semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                        return;
                    }

                    let maxYear = 4;
                    let maxSem = 8;
                    
                    if (selectedProg === 'M.Tech' || selectedProg === 'Ph.D') {
                        maxYear = 2;
                        maxSem = 4;
                    } else if (selectedProg === 'Degree' || selectedProg === 'Diploma') {
                        maxYear = 3;
                        maxSem = 6;
                    }

                    const currentYear = yearSelect.value;
                    const currentSem = semesterSelect.value;

                    yearSelect.innerHTML = '<option value="">-- Year --</option>';
                    for (let i = 1; i <= maxYear; i++) {
                        let suffix = 'th';
                        if (i === 1) suffix = 'st';
                        else if (i === 2) suffix = 'nd';
                        else if (i === 3) suffix = 'rd';
                        yearSelect.innerHTML += `<option value="${i}" ${currentYear == i ? 'selected' : ''}>${i}${suffix} Year</option>`;
                    }

                    // Reset semester if it's not valid for the new year
                    semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                    updateSemestersForYear();
                };

                const updateSemestersForYear = function() {
                    const selectedYear = parseInt(yearSelect.value);
                    const currentSem = semesterSelect.value;
                    const selectedProg = progSelect.value;
                    
                    semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                    
                    if (selectedYear) {
                        for (let i = 1; i <= 2; i++) {
                            let suffix = i === 1 ? 'st' : 'nd';
                            semesterSelect.innerHTML += `<option value="${i}" ${currentSem == i ? 'selected' : ''}>${i}${suffix} Semester</option>`;
                        }
                    }
                };

                yearSelect.addEventListener('change', updateSemestersForYear);

                progSelect.addEventListener('change', function() {
                    const selectedProg = this.value;
                    let currentReg = regSelect.value;
                    let regStillValid = false;
                    
                    regSelect.innerHTML = '';
                    regSelect.appendChild(defRegOpt);
                    
                    allRegs.forEach(opt => {
                        if (!selectedProg || opt.getAttribute('data-program') === selectedProg) {
                            regSelect.appendChild(opt);
                            if (opt.value === currentReg) {
                                regStillValid = true;
                            }
                        }
                    });
                    
                    if (!regStillValid && currentReg !== '') {
                        regSelect.value = '';
                    }

                    updateYearSem();
                });

                // Run on initial load
                updateYearSem();
            }
        });

        // Dynamic generation of course fields
        document.addEventListener('DOMContentLoaded', function() {
            const numCoursesInput = document.getElementById('no_of_courses');
            const dynamicFieldsContainer = document.getElementById('dynamic_course_fields');
            
            if (numCoursesInput && dynamicFieldsContainer) {
                numCoursesInput.addEventListener('input', function() {
                    let num = parseInt(this.value);
                    if (isNaN(num) || num < 1) num = 1;
                    if (num > 9) {
                        num = 9;
                        this.value = 9;
                    }
                    
                    let html = '';
                    for (let i = 1; i <= num; i++) {
                        html += `
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-500 w-8 text-center">${i}</span>
                            <input type="text" name="code[]" placeholder="Code" class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                            <input type="text" name="name[]" placeholder="Name" class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm" required>
                        </div>`;
                    }
                    dynamicFieldsContainer.innerHTML = html;
                });
            }
        });

        // Cascade Delete Course Modal Global Functions
        window.openDeleteCourseModal = function(code, name, dept, reg, year, sem, staffCount, enrollmentsCount, materialsCount, assignmentsCount, deleteUrl) {
            const codeEl = document.getElementById('delModalCourseCode');
            const nameEl = document.getElementById('delModalCourseName');
            const deptEl = document.getElementById('delModalDeptBadge');
            const metaEl = document.getElementById('delModalMeta');
            
            const staffEl = document.getElementById('delModalStaffCount');
            const enrollEl = document.getElementById('delModalEnrollmentCount');
            const matEl = document.getElementById('delModalMaterialsCount');
            const assignEl = document.getElementById('delModalAssignmentsCount');
            const formEl = document.getElementById('deleteCourseForm');
            const modal = document.getElementById('deleteCourseModal');

            if (codeEl) codeEl.innerText = code || '';
            if (nameEl) nameEl.innerText = name || '';
            if (deptEl) deptEl.innerText = dept || '';
            if (metaEl) metaEl.innerText = `${reg || 'General'} • Year ${year || 1}, Semester ${sem || 1}`;
            
            if (staffEl) staffEl.innerText = `${staffCount || 0} Faculty`;
            if (enrollEl) enrollEl.innerText = `${enrollmentsCount || 0} Students`;
            if (matEl) matEl.innerText = `${materialsCount || 0} Materials`;
            if (assignEl) assignEl.innerText = `${assignmentsCount || 0} Assignments`;
            
            if (formEl) formEl.action = deleteUrl;
            if (modal) modal.classList.remove('hidden');
        };

        window.closeDeleteCourseModal = function() {
            const modal = document.getElementById('deleteCourseModal');
            if (modal) modal.classList.add('hidden');
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeDeleteCourseModal();
            }
        });
    </script>
    
    <!-- Cascade Delete Course Confirmation Modal -->
    <div id="deleteCourseModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-slate-200">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-rose-50 to-orange-50 border-b border-rose-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-xs border border-rose-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Delete Course & Linked Data</h3>
                        <p class="text-xs text-slate-500">Confirm permanent cascade deletion</p>
                    </div>
                </div>
                <button type="button" onclick="closeDeleteCourseModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-white/60 transition-colors focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">
                <!-- Course Target Preview -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <span id="delModalCourseCode" class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100"></span>
                        <span id="delModalDeptBadge" class="text-xs font-semibold text-slate-600 bg-white px-2.5 py-0.5 rounded-md border border-slate-200"></span>
                    </div>
                    <h4 id="delModalCourseName" class="text-base font-bold text-slate-900"></h4>
                    <p id="delModalMeta" class="text-xs text-slate-500 font-medium"></p>
                </div>

                <!-- Linked Child Nodes Section -->
                <div>
                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Linked Child Records That Will Be Removed:</span>
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Faculty Allocations -->
                        <div class="p-3 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalStaffCount" class="text-sm font-bold text-indigo-900">0 Faculty</div>
                                <div class="text-[11px] text-indigo-600 font-medium">Assigned Staff</div>
                            </div>
                        </div>

                        <!-- Enrolled Students -->
                        <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalEnrollmentCount" class="text-sm font-bold text-amber-900">0 Students</div>
                                <div class="text-[11px] text-amber-600 font-medium">Enrolled Students</div>
                            </div>
                        </div>

                        <!-- Course Materials -->
                        <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalMaterialsCount" class="text-sm font-bold text-blue-900">0 Materials</div>
                                <div class="text-[11px] text-blue-600 font-medium">Files & Notes</div>
                            </div>
                        </div>

                        <!-- Assignments & Tasks -->
                        <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <div id="delModalAssignmentsCount" class="text-sm font-bold text-purple-900">0 Assignments</div>
                                <div class="text-[11px] text-purple-600 font-medium">Tasks & Submissions</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Callout -->
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                    <p class="font-bold flex items-center gap-1.5 text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Are you sure you want to delete this course?</span>
                    </p>
                    <p class="text-rose-700 leading-relaxed">
                        This action will permanently delete this course and automatically clean up all associated faculty allocations, student enrollments, learning materials, and assignment submissions from the system.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeDeleteCourseModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer">
                    Cancel / Keep Course
                </button>
                <form id="deleteCourseForm" method="POST" action="" class="inline m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Yes, Delete Course & All Linked Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.reset_password_modal')

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof TomSelect !== "undefined") {
            ["regulation_id", "department", "year", "semester"].forEach(function(id) {
                const el = document.getElementById(id);
                if (el && !el.tomselect && !el.classList.contains("tomselected")) {
                    const ts = new TomSelect(el, {
                        create: false,
                        maxOptions: null,
                        allowEmptyOption: true,
                        controlInput: null
                    });
                    ts.on("change", function(val) {
                        if (el.form) el.form.submit();
                    });
                }
            });
        }
    });
    </script>
</body>
</html>


