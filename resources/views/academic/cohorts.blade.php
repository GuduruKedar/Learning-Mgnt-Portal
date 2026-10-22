<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cohort Student Course Enrollment - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="w-full space-y-6 max-w-7xl mx-auto">
                
                <!-- Breadcrumbs & Navigation -->
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                        <a href="{{ route('academic.courses') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs hover:shadow transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Back to Courses</span>
                        </a>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-700 font-bold">Academics</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-600">Cohort Course Enrollment</span>
                    </nav>

                    <div class="flex items-center gap-2">
                        @if($selectedCourse)
                        <a href="{{ route('staff.courses.materials', $selectedCourse->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-all shadow-2xs" title="View Course Materials">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Course Materials</span>
                        </a>
                        @endif
                        <span class="text-xs font-bold px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            Coordinator Cohort Hub
                        </span>
                    </div>
                </div>

                <!-- Page Header Title -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-indigo-600 text-white shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            <span>Student Cohort Course Enrollment</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-2xl">
                            Assign subjects by course code to student cohorts in bulk. Filter by Year, Semester, or Program to enroll students with a single click.
                        </p>
                    </div>
                </div>

                <!-- Session Flash Alerts -->
                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="flex-1">
                        <span class="font-bold">Please check the errors below:</span>
                        <ul class="list-disc pl-5 mt-1 text-xs space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Section 1: Target Course Selection Banner -->
                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs p-5 sm:p-6 transition-all">
                    <form action="{{ route('academic.cohorts') }}" method="GET" id="courseSelectionForm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <label for="course_select" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span>Step 1 &bull; Select Target Subject / Course Code</span>
                                </label>
                                <select name="course_id" id="course_select" onchange="document.getElementById('courseSelectionForm').submit()" class="w-full h-11 px-3.5 bg-gray-50 hover:bg-white border border-gray-300 focus:border-indigo-500 rounded-xl text-xs sm:text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-2xs transition-all cursor-pointer">
                                    @forelse($courses as $c)
                                        <option value="{{ $c->id }}" {{ $selectedCourse && $selectedCourse->id === $c->id ? 'selected' : '' }}>
                                            {{ $c->code }} — {{ $c->name }} (Year {{ $c->year ?? 1 }}, Sem {{ $c->semester ?? 1 }}) &bull; {{ $c->enrollments_count ?? 0 }} enrolled
                                        </option>
                                    @empty
                                        <option value="">No courses available in this department</option>
                                    @endforelse
                                </select>
                            </div>

                            @if($selectedCourse)
                            <div class="md:text-right shrink-0">
                                <span class="text-xs text-gray-400 font-medium">Currently Selected Subject</span>
                                <div class="text-base sm:text-lg font-black text-indigo-700 font-mono tracking-tight">{{ $selectedCourse->code }}</div>
                                <div class="text-xs font-semibold text-gray-700 truncate max-w-xs md:max-w-sm">{{ $selectedCourse->name }}</div>
                            </div>
                            @endif
                        </div>
                    </form>

                    @if($selectedCourse)
                    <!-- Course Details Strip -->
                    <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Subject Code: <span class="font-mono ml-1 font-extrabold">{{ $selectedCourse->code }}</span>
                            </span>

                            @if($selectedCourse->regulation)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                {{ $selectedCourse->regulation->code }} {{ !empty($selectedCourse->regulation->curriculum) ? ' - ' . $selectedCourse->regulation->curriculum : '' }}
                            </span>
                            @endif

                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                Year {{ $selectedCourse->year ?? 1 }} &bull; Semester {{ $selectedCourse->semester ?? 1 }}
                            </span>

                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                Dept: {{ $selectedCourse->department->name ?? ($selectedCourse->department_id ?? 'IT') }}
                            </span>
                        </div>

                        <!-- Faculty Allocation summary -->
                        <div class="flex items-center gap-1.5 text-gray-600">
                            <span class="font-semibold text-gray-500">Allocated Faculty:</span>
                            @if($selectedCourse->staff && $selectedCourse->staff->count() > 0)
                                <div class="flex items-center -space-x-1">
                                    @foreach($selectedCourse->staff as $fac)
                                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold text-[10px] flex items-center justify-center ring-2 ring-white" title="{{ $fac->first_name }} {{ $fac->last_name }} ({{ $fac->username }})">
                                            {{ strtoupper(substr($fac->first_name ?? ($fac->username ?? 'F'), 0, 1)) }}
                                        </div>
                                    @endforeach
                                </div>
                                <span class="font-bold text-gray-800">
                                    {{ $selectedCourse->staff->pluck('first_name')->implode(', ') }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">No faculty allocated</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Section 2: Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Total Cohort Students -->
                    <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-2xs hover:shadow-xs transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Filtered Cohort Size</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $totalCohortCount }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-2 font-medium">Students matching Year & Semester filters</p>
                    </div>

                    <!-- Already Enrolled in this Subject -->
                    <div class="bg-white rounded-2xl border border-emerald-100 p-5 shadow-2xs hover:shadow-xs transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Already Enrolled</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1">{{ $enrolledCount }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[11px] text-emerald-600 mt-2 font-medium">Active in course {{ $selectedCourse?->code ?? '' }}</p>
                    </div>

                    <!-- Ready to Enroll -->
                    <div class="bg-white rounded-2xl border border-amber-100 p-5 shadow-2xs hover:shadow-xs transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Ready to Enroll</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-700 mt-1">{{ $pendingCount }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[11px] text-amber-600 mt-2 font-medium">Pending assignment to this course</p>
                    </div>
                </div>

                <!-- Section 3: Cohort Filtering Controls -->
                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs p-5 sm:p-6 transition-all">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">2</span>
                            <h3 class="text-sm sm:text-base font-bold text-gray-800">Filter Student Cohort</h3>
                        </div>
                        <a href="{{ route('academic.cohorts', ['course_id' => $selectedCourseId]) }}" class="text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
                            Reset Filters
                        </a>
                    </div>

                    <form action="{{ route('academic.cohorts') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5 items-end">
                        <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">

                        <!-- Year Filter -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Academic Year</label>
                            <select name="year" class="w-full h-10 px-3 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="all" {{ $yearFilter === 'all' ? 'selected' : '' }}>All Years</option>
                                <option value="1" {{ (string)$yearFilter === '1' ? 'selected' : '' }}>Year 1</option>
                                <option value="2" {{ (string)$yearFilter === '2' ? 'selected' : '' }}>Year 2</option>
                                <option value="3" {{ (string)$yearFilter === '3' ? 'selected' : '' }}>Year 3</option>
                                <option value="4" {{ (string)$yearFilter === '4' ? 'selected' : '' }}>Year 4</option>
                            </select>
                        </div>

                        <!-- Semester Filter -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Semester</label>
                            <select name="semester" class="w-full h-10 px-3 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="all" {{ $semFilter === 'all' ? 'selected' : '' }}>All Semesters</option>
                                <option value="1" {{ (string)$semFilter === '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ (string)$semFilter === '2' ? 'selected' : '' }}>Semester 2</option>
                            </select>
                        </div>

                        <!-- Enrollment Status Filter -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Enrollment Status</label>
                            <select name="status" class="w-full h-10 px-3 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Cohort Students</option>
                                <option value="not_enrolled" {{ $statusFilter === 'not_enrolled' ? 'selected' : '' }}>Only Not Enrolled ({{ $pendingCount }})</option>
                                <option value="enrolled" {{ $statusFilter === 'enrolled' ? 'selected' : '' }}>Already Enrolled ({{ $enrolledCount }})</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <div>
                            <button type="submit" class="w-full h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 shadow-xs transition-all cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                <span>Apply Filters</span>
                            </button>
                        </div>
                    </form>

                    <!-- Search Input Bar -->
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <form action="{{ route('academic.cohorts') }}" method="GET" class="flex gap-2">
                            <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                            <input type="hidden" name="year" value="{{ $yearFilter }}">
                            <input type="hidden" name="semester" value="{{ $semFilter }}">
                            <input type="hidden" name="status" value="{{ $statusFilter }}">

                            <div class="relative flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search }}" 
                                       placeholder="Search student by Roll Number, Name, or Email..." 
                                       class="w-full h-9.5 pl-9 pr-4 bg-gray-50 hover:bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <button type="submit" class="px-4 h-9.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer">
                                Search
                            </button>
                            @if($search)
                            <a href="{{ route('academic.cohorts', ['course_id' => $selectedCourseId, 'year' => $yearFilter, 'semester' => $semFilter, 'status' => $statusFilter]) }}" class="px-3 h-9.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold flex items-center transition-all shrink-0">
                                Clear
                            </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Section 4: Cohort Student List Table & Bulk Action Form -->
                @if(!$selectedCourse)
                    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-2xs">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-base font-extrabold text-gray-900">Please Select a Course</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Select a course above to view students and execute bulk cohort enrollment.</p>
                    </div>
                @else
                    <form action="{{ route('academic.cohorts.enroll') }}" method="POST" id="cohortActionForm">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">

                        <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs overflow-hidden">
                            <!-- Table Top Action Bar -->
                            <div class="p-4 sm:px-6 bg-slate-50/90 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" 
                                               id="selectAllCheckbox" 
                                               onchange="toggleSelectAll(this)" 
                                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 cursor-pointer">
                                        <label for="selectAllCheckbox" class="text-xs font-bold text-gray-700 select-none cursor-pointer">
                                            Select All
                                        </label>
                                    </div>

                                    <span class="text-gray-300">|</span>

                                    <!-- Quick selection helpers -->
                                    <button type="button" onclick="selectOnlyNotEnrolled()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline cursor-pointer">
                                        Select All Unenrolled
                                    </button>
                                    <span class="text-gray-300">&bull;</span>
                                    <button type="button" onclick="clearAllSelections()" class="text-xs font-semibold text-gray-500 hover:text-gray-700 hover:underline cursor-pointer">
                                        Deselect All
                                    </button>
                                </div>

                                <div class="flex items-center gap-2.5">
                                    <span class="text-xs font-semibold text-gray-600">
                                        <span id="selectedCountBadge" class="font-extrabold text-indigo-600">0</span> of {{ $students->count() }} selected
                                    </span>

                                    <!-- Bulk Enroll Button -->
                                    <button type="submit" 
                                            id="bulkEnrollBtn"
                                            disabled
                                            class="py-2 px-4 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-all flex items-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                            title="Enroll selected students into {{ $selectedCourse->code }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                        <span>Bulk Enroll Selected</span>
                                    </button>

                                    <!-- Bulk Unenroll Button -->
                                    <button type="button" 
                                            id="bulkUnenrollBtn"
                                            onclick="submitUnenrollForm()"
                                            disabled
                                            class="py-2 px-3 rounded-xl text-xs font-bold bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 transition-all flex items-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                            title="Unenroll selected students from {{ $selectedCourse->code }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                                        <span>Unenroll</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-gray-200 text-left">
                                    <thead class="bg-gray-50/80 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-4 py-3.5 text-center w-10">#</th>
                                            <th class="px-5 py-3.5">Roll No & Student Name</th>
                                            <th class="px-5 py-3.5">Program & Dept</th>
                                            <th class="px-5 py-3.5">Year / Sem</th>
                                            <th class="px-5 py-3.5 text-center">Enrollment Status</th>
                                            <th class="px-5 py-3.5 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white text-xs">
                                        @forelse($students as $stu)
                                        @php
                                            $profile = $stu->profile;
                                            $stuFullName = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')) ?: ($stu->name ?: 'Student');
                                            $avatarInitial = strtoupper(substr($profile->first_name ?? ($stu->username ?? 'S'), 0, 1));
                                            $regNo = $stu->username ?? 'N/A';
                                            $progName = $profile->program->name ?? ($profile->programs_id ?? 'B.Tech IT');
                                            $yearText = 'Yr ' . ($profile->academic_year ?? 1);
                                            $semText = $profile->semester ? 'Sem ' . $profile->semester : '—';
                                        @endphp
                                        <tr class="hover:bg-slate-50/80 transition-colors {{ $stu->is_enrolled ? 'bg-emerald-50/20' : '' }}">
                                            <!-- Checkbox Column -->
                                            <td class="px-4 py-3.5 text-center">
                                                <input type="checkbox" 
                                                       name="student_ids[]" 
                                                       value="{{ $stu->id }}" 
                                                       data-enrolled="{{ $stu->is_enrolled ? '1' : '0' }}"
                                                       onchange="updateSelectionCount()"
                                                       class="student-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 cursor-pointer">
                                            </td>

                                            <!-- Student Info -->
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-100 to-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center border border-indigo-200/80 shrink-0">
                                                        {{ $avatarInitial }}
                                                    </div>
                                                    <div>
                                                        <div class="font-extrabold text-indigo-700 font-mono text-xs">{{ $regNo }}</div>
                                                        <div class="font-semibold text-gray-900 text-xs">{{ $stuFullName }}</div>
                                                        <div class="text-[11px] text-gray-400">{{ $profile->email ?? ($stu->email ?? '') }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Program & Dept -->
                                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-600">
                                                <div class="font-semibold text-gray-800 text-xs truncate max-w-[160px]" title="{{ $progName }}">{{ $progName }}</div>
                                                <div class="text-[11px] text-gray-400 uppercase font-mono">{{ $profile->departments_id ?? 'IT' }}</div>
                                            </td>

                                            <!-- Year / Semester -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700">
                                                    {{ $yearText }} &bull; {{ $semText }}
                                                </span>
                                            </td>

                                            <!-- Enrollment Status Pill -->
                                            <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                                @if($stu->is_enrolled)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Enrolled
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600 border border-gray-200/80">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                        Not Enrolled
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Individual Action -->
                                            <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                                @if($stu->is_enrolled)
                                                    <button type="button" 
                                                            onclick="unenrollSingleStudent({{ $stu->id }}, '{{ addslashes($stuFullName) }}')" 
                                                            class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline cursor-pointer"
                                                            title="Unenroll student from this course">
                                                        Unenroll
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            onclick="enrollSingleStudent({{ $stu->id }})" 
                                                            class="text-xs font-bold text-emerald-600 hover:text-emerald-800 hover:underline cursor-pointer"
                                                            title="Enroll this student now">
                                                        + Enroll
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="py-12 text-center text-gray-500">
                                                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                </div>
                                                <p class="font-bold text-gray-700 text-xs">No students match the selected cohort criteria</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5">Try adjusting the Year, Semester, or Enrollment Status filters above.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>

                    <!-- Separate Unenroll Form for JS trigger -->
                    <form id="standaloneUnenrollForm" action="{{ route('academic.cohorts.unenroll') }}" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <div id="standaloneUnenrollInputs"></div>
                    </form>

                    <!-- Separate Single Enroll Form for JS trigger -->
                    <form id="standaloneEnrollForm" action="{{ route('academic.cohorts.enroll') }}" method="POST" class="hidden">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <input type="hidden" name="student_ids[]" id="singleEnrollStudentId" value="">
                    </form>
                @endif

            </div>
        </main>
    </div>

    <!-- Script for Checkbox Multi-selection and Bulk Operations -->
    <script>
        function updateSelectionCount() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const count = checkboxes.length;
            const badge = document.getElementById('selectedCountBadge');
            const enrollBtn = document.getElementById('bulkEnrollBtn');
            const unenrollBtn = document.getElementById('bulkUnenrollBtn');

            if (badge) badge.innerText = count;
            if (enrollBtn) {
                enrollBtn.disabled = count === 0;
                enrollBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Bulk Enroll Selected (${count})</span>
                `;
            }
            if (unenrollBtn) {
                unenrollBtn.disabled = count === 0;
                unenrollBtn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                    <span>Unenroll (${count})</span>
                `;
            }

            const allCheckboxes = document.querySelectorAll('.student-checkbox');
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll && allCheckboxes.length > 0) {
                selectAll.checked = count === allCheckboxes.length;
                selectAll.indeterminate = count > 0 && count < allCheckboxes.length;
            }
        }

        function toggleSelectAll(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
            updateSelectionCount();
        }

        function selectOnlyNotEnrolled() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = (cb.getAttribute('data-enrolled') === '0');
            });
            updateSelectionCount();
        }

        function clearAllSelections() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            updateSelectionCount();
        }

        function enrollSingleStudent(studentId) {
            document.getElementById('singleEnrollStudentId').value = studentId;
            document.getElementById('standaloneEnrollForm').submit();
        }

        function unenrollSingleStudent(studentId, studentName) {
            if (!confirm(`Are you sure you want to remove "${studentName}" from this course?`)) {
                return;
            }
            const container = document.getElementById('standaloneUnenrollInputs');
            container.innerHTML = `<input type="hidden" name="student_ids[]" value="${studentId}">`;
            document.getElementById('standaloneUnenrollForm').submit();
        }

        function submitUnenrollForm() {
            const checked = document.querySelectorAll('.student-checkbox:checked');
            if (checked.length === 0) return;

            if (!confirm(`Are you sure you want to unenroll the ${checked.length} selected students from this course?`)) {
                return;
            }

            const container = document.getElementById('standaloneUnenrollInputs');
            container.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'student_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });

            document.getElementById('standaloneUnenrollForm').submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateSelectionCount();
        });
    </script>
</body>
</html>
