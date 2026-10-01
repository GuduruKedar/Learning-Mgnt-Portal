<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Allocations (Faculty Assignment) - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        .stat-card-glow {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stat-card-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(79, 70, 229, 0.12);
        }
        .custom-select-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.65rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.2rem;
            -webkit-print-color-adjust: exact;
            appearance: none;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                            <span>Academics</span>
                            <span class="text-slate-300">•</span>
                            <span>Faculty Assignment Engine</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <span>Course Allocations</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ $courses->total() }} {{ Str::plural('Course', $courses->total()) }}
                            </span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Assign, reallocate, and audit faculty teaching assignments across all academic courses.</p>
                    </div>
                    
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('academic.courses') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold shadow-xs hover:border-slate-300 transition-all">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Manage Courses</span>
                        </a>
                        <a href="{{ route('academic.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold shadow-sm shadow-indigo-200 hover:shadow-indigo-300 transition-all focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Add New Course</span>
                        </a>
                    </div>
                </div>

                <!-- KPI Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Courses -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs stat-card-glow flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Courses</p>
                            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalCourses ?? $courses->total() }}</h3>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">Across all curricula</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                    </div>

                    <!-- Staffed Courses -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs stat-card-glow flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Allocated Courses</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-2xl font-black text-emerald-600">{{ $totalAllocatedCourses ?? 0 }}</h3>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    {{ $allocationRate ?? 0 }}%
                                </span>
                            </div>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">Faculty assigned & active</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Pending Allocation -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs stat-card-glow flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unassigned Courses</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <h3 class="text-2xl font-black {{ ($totalUnallocatedCourses ?? 0) > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ $totalUnallocatedCourses ?? 0 }}</h3>
                                @if(($totalUnallocatedCourses ?? 0) > 0)
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                    Action Required
                                </span>
                                @endif
                            </div>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">Needs faculty allocation</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>

                    <!-- Total Teaching Assignments -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs stat-card-glow flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Staff Deployments</p>
                            <h3 class="text-2xl font-black text-indigo-700 mt-1">{{ $totalStaffAssignments ?? 0 }}</h3>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">Total teaching links</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100/80">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Filters & Search Toolbar -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <form action="{{ route('academic.courses.allocations') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full" id="filterForm">
                        
                        <!-- Search Box -->
                        <div class="flex-1 min-w-[240px]">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium" placeholder="Search by course code or title...">
                            </div>
                        </div>
                        
                        <!-- Regulation -->
                        <div class="w-full sm:w-56 shrink-0">
                            <label for="regulation_id" class="sr-only">Regulation</label>
                            <select name="regulation_id" id="regulation_id" onchange="this.form.submit()" class="custom-select-control block w-full pl-3.5 pr-8 py-2.5 text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                <option value="">All Regulations</option>
                                @foreach($regulations as $reg)
                                    <option value="{{ $reg->id }}" {{ request('regulation_id') == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->code }}{{ !empty($reg->curriculum) ? ' - ' . $reg->curriculum : ($reg->name && $reg->name !== $reg->code ? ' - ' . $reg->name : '') }} ({{ $reg->program_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department (Privileged) -->
                        @if(in_array(Auth::user()->role, ['sa', 'ssh_admin']))
                        <div class="w-full sm:w-52 shrink-0">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" onchange="this.form.submit()" class="custom-select-control block w-full pl-3.5 pr-8 py-2.5 text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Year Filter -->
                        <div class="w-full sm:w-32 shrink-0">
                            <label for="year" class="sr-only">Year</label>
                            <select name="year" id="year" onchange="this.form.submit()" class="custom-select-control block w-full pl-3.5 pr-8 py-2.5 text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
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

                        <!-- Semester Filter -->
                        <div class="w-full sm:w-32 shrink-0">
                            <label for="semester" class="sr-only">Semester</label>
                            <select name="semester" id="semester" onchange="this.form.submit()" class="custom-select-control block w-full pl-3.5 pr-8 py-2.5 text-xs sm:text-sm font-medium border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all cursor-pointer">
                                <option value="">All Sems</option>
                                @for($i = 1; $i <= 2; $i++)
                                    <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Sem {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-xs transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                <span>Filter</span>
                            </button>
                            <a href="{{ route('academic.courses.allocations') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 border border-slate-200 text-xs sm:text-sm font-semibold rounded-xl text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all shadow-xs" title="Reset all filters">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center shadow-xs">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200/80 text-rose-800 px-4 py-3.5 rounded-2xl shadow-xs">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Action Blocked</span>
                        </div>
                        <ul class="list-disc pl-7 text-xs sm:text-sm font-medium space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Course Allocations Table -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-800">Allocated Courses Directory</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span class="text-xs font-medium text-slate-500">Showing page {{ $courses->currentPage() }} of {{ $courses->lastPage() }}</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-50 px-3 py-1 rounded-full border border-slate-200">
                            {{ $courses->total() }} total matching
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Course Details</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Curriculum & Regulation</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Year & Sem</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider min-w-[280px]">Allocated Faculty</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @forelse($courses as $course)
                                <tr class="hover:bg-slate-50/70 transition-colors group">
                                    
                                    <!-- Course Code & Title -->
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-start gap-2.5">
                                            <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100/80 text-indigo-700 flex items-center justify-center font-mono-code font-bold text-xs shrink-0 mt-0.5">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-mono-code font-bold text-slate-900 text-sm tracking-tight">{{ $course->code }}</div>
                                                <div class="text-xs font-semibold text-slate-600 mt-0.5 line-clamp-1" title="{{ $course->name }}">{{ $course->name }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Program & Regulation -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $course->regulation->program_type ?? 'Degree' }}
                                            </span>
                                            <div class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                                <span class="font-mono-code font-bold text-slate-900">{{ $course->regulation->code ?? 'N/A' }}</span>
                                                @if(!empty($course->regulation->curriculum))
                                                    <span class="text-slate-400">•</span>
                                                    <span class="text-slate-500">{{ $course->regulation->curriculum }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Year & Sem -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100/90 text-slate-800 text-xs font-bold border border-slate-200/60">
                                            <span>Yr {{ $course->year ?? '1' }}</span>
                                            <span class="text-slate-300">•</span>
                                            <span class="text-indigo-600 font-extrabold">Sem {{ $course->semester ?? '1' }}</span>
                                        </div>
                                    </td>

                                    <!-- Department -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            <span class="font-semibold text-slate-700 text-xs">{{ $course->department->name ?? $course->department_id }}</span>
                                        </div>
                                    </td>

                                    <!-- Allocated Faculty -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-1.5 min-w-[240px]">
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
                                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50/90 text-indigo-700 border border-indigo-100 text-xs font-semibold shadow-2xs group hover:bg-indigo-100/90 transition-all">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 shrink-0"></span>
                                                            <button type="button" 
                                                                onclick="showFacultyDetails('{{ addslashes($facFullName) }}', '{{ addslashes($facCode) }}', '{{ addslashes($facEmail) }}', '{{ addslashes($facPhone) }}', '{{ addslashes($facDesig) }}', '{{ addslashes($facDept) }}', '{{ addslashes($courseContext) }}', '{{ $facPhoto }}', '{{ $unallocUrl }}')"
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
                                                    <select name="staff_id" required class="w-full text-xs py-1.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-medium hover:bg-white hover:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none cursor-pointer transition-all shadow-2xs">
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
                                                <button type="submit" class="py-1.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow-blue-200 transition-all shrink-0 cursor-pointer" title="Assign selected faculty">
                                                    Assign
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="max-w-sm mx-auto flex flex-col items-center">
                                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 mb-3">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <h4 class="text-base font-bold text-slate-900">No Courses Found</h4>
                                            <p class="text-xs text-slate-500 mt-1">Try adjusting your search criteria, selected regulation, or department filter.</p>
                                            <a href="{{ route('academic.courses.allocations') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                                                <span>Clear Filters</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($courses->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-white">
                        {{ $courses->links() }}
                    </div>
                    @endif
                </div>

            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Enhanced Faculty Details Drawer/Modal -->
    <div id="facultyDetailsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-xs transition-opacity p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all animate-in fade-in zoom-in duration-200">
            
            <!-- Modal Header with Accent Background -->
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 text-white relative">
                <button type="button" onclick="closeFacultyModal()" class="absolute top-4 right-4 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-1.5 rounded-full transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div class="flex items-center gap-4">
                    <div id="facultyModalPhotoContainer" class="w-14 h-14 rounded-2xl bg-white text-indigo-700 font-extrabold text-xl flex items-center justify-center shadow-lg border-2 border-white overflow-hidden shrink-0">
                        <span id="facultyModalAvatarInitials">FA</span>
                        <img id="facultyModalPhoto" src="" alt="Faculty Photo" class="w-full h-full object-cover hidden">
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/20 text-white border border-white/30 uppercase tracking-wider mb-1" id="facultyModalDesignation">
                            Faculty Member
                        </span>
                        <h3 class="text-lg font-bold text-white leading-snug truncate" id="facultyModalName">Faculty Name</h3>
                        <p class="text-xs text-indigo-100 font-mono-code mt-0.5" id="facultyModalCode">EMP001</p>
                    </div>
                </div>
            </div>

            <!-- Modal Body Details -->
            <div class="p-6 space-y-3.5 bg-white">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-400 uppercase tracking-wider text-[10px]">Department</span>
                        <span class="font-bold text-slate-800 text-right" id="facultyModalDept">Computer Science & Engineering</span>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200/60">
                        <span class="font-semibold text-slate-400 uppercase tracking-wider text-[10px]">Official Email</span>
                        <span class="font-semibold text-indigo-600 break-all text-right" id="facultyModalEmail">email@college.edu</span>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200/60">
                        <span class="font-semibold text-slate-400 uppercase tracking-wider text-[10px]">Phone</span>
                        <span class="font-semibold text-slate-700 text-right" id="facultyModalPhone">+91 00000 00000</span>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-200/60">
                        <span class="font-semibold text-slate-400 uppercase tracking-wider text-[10px]">Assigned Course</span>
                        <span class="font-bold text-slate-900 text-right" id="facultyModalCourse">Course Name</span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" id="facultyModalRemoveBtn" onclick="triggerRemoveStaffFromModal()" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs hover:shadow-rose-300 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Unassign Faculty</span>
                </button>
                <button type="button" onclick="closeFacultyModal()" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-xs transition-all cursor-pointer">
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>

    @include('partials.universal_delete_modal')

    <script>
        let currentFacultyAllocation = null;

        function showFacultyDetails(name, code, email, phone, designation, dept, courseContext, photoUrl, deleteUrl) {
            document.getElementById('facultyModalName').textContent = name;
            document.getElementById('facultyModalCode').textContent = code;
            document.getElementById('facultyModalEmail').textContent = email;
            document.getElementById('facultyModalPhone').textContent = phone;
            document.getElementById('facultyModalDesignation').textContent = designation || 'Faculty';
            document.getElementById('facultyModalDept').textContent = dept;
            document.getElementById('facultyModalCourse').textContent = courseContext;
            
            const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() || 'FA';
            document.getElementById('facultyModalAvatarInitials').textContent = initials;
            
            const photoImg = document.getElementById('facultyModalPhoto');
            const initialsSpan = document.getElementById('facultyModalAvatarInitials');
            if (photoUrl && photoUrl !== '') {
                photoImg.src = photoUrl;
                photoImg.classList.remove('hidden');
                initialsSpan.classList.add('hidden');
            } else {
                photoImg.classList.add('hidden');
                initialsSpan.classList.remove('hidden');
            }

            currentFacultyAllocation = { name, code, email, phone, designation, dept, courseContext, deleteUrl };
            document.getElementById('facultyDetailsModal').classList.remove('hidden');
        }

        function triggerRemoveStaffFromModal() {
            if (!currentFacultyAllocation || !currentFacultyAllocation.deleteUrl) return;
            const data = currentFacultyAllocation;
            closeFacultyModal();

            if (window.openUniversalDeleteModal) {
                window.openUniversalDeleteModal({
                    title: 'Remove Faculty Allocation',
                    itemName: data.name + ' (' + data.code + ')',
                    itemType: 'Course Faculty Assignment: ' + data.courseContext,
                    deleteUrl: data.deleteUrl,
                    warningMessage: 'Removing this faculty assignment will unassign ' + data.name + ' from teaching ' + data.courseContext + ' and revoke their material & grade management permissions.',
                    cascadeItems: [
                        { label: 'Teaching Assignment Link', count: '1 Removed' },
                        { label: 'Course Section & Material Access', count: 'Revoked' }
                    ]
                });
            }
        }

        function closeFacultyModal() {
            document.getElementById('facultyDetailsModal').classList.add('hidden');
        }

        // Close modal when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFacultyModal();
            }
        });
    </script>
</body>
</html>
