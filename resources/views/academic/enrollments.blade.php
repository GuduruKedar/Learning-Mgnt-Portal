<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Insights - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-btn.active {
            color: #4f46e5;
            border-bottom: 2px solid #4f46e5;
            background-color: #eef2ff;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::check() && Auth::user()->profile && Auth::user()->profile->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->profile->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->profile->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->profile->first_name ?? 'Super Admin' }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50/60 relative">
            <div class="p-4 sm:p-6 lg:p-8 w-full space-y-6">
                
                <!-- Page Title & Header -->
                <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="p-2 bg-indigo-100 text-indigo-700 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Enrollment Insights</h1>
                        </div>
                        <p class="text-gray-500 mt-1 text-xs sm:text-sm">Comprehensive SaaS analytics, department allocations, faculty teaching loads, and student enrollment rosters.</p>
                    </div>

                    <!-- Department Selector & Actions -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="relative min-w-[260px] sm:min-w-[320px]">
                            <label for="department-select" class="sr-only">Select Department</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <select id="department-select" onchange="onDepartmentSelectChange(this.value)" class="w-full pl-9 pr-10 py-2.5 bg-white border border-indigo-200 rounded-xl text-sm font-semibold text-gray-800 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                <option value="all">🏢 All Departments (University Overview)</option>
                                @foreach($departmentsList as $dept)
                                    <option value="{{ $dept['code'] }}" {{ $selectedDepartment === $dept['code'] ? 'selected' : '' }}>
                                        {{ $dept['name'] }} ({{ $dept['total_courses'] }} Courses, {{ $dept['total_enrollments'] }} Enrolled)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button onclick="exportCurrentViewToExcel()" class="inline-flex items-center px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export to Excel
                        </button>
                    </div>
                </div>

                <!-- Dynamic KPI Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
                    <!-- Metric 1: Enrollments -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Enrollments</span>
                            <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-enrollments" class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($totalEnrollments) }}</p>
                        <p id="kpi-enrollments-sub" class="text-[10px] text-gray-400 mt-0.5">Across all subjects</p>
                    </div>

                    <!-- Metric 2: Courses Offered -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Courses Offered</span>
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-courses" class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($totalCoursesOffered) }}</p>
                        <p id="kpi-courses-sub" class="text-[10px] text-gray-400 mt-0.5">Active curriculum</p>
                    </div>

                    <!-- Metric 3: Allocation Rate -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Alloc. Rate</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-allocation" class="text-xl sm:text-2xl font-black text-gray-900">{{ $overallAllocationRate }}%</p>
                        <p id="kpi-allocation-sub" class="text-[10px] text-emerald-600 font-medium mt-0.5">{{ $totalAllocatedCourses }} of {{ $totalCoursesOffered }} Assigned</p>
                    </div>

                    <!-- Metric 4: Faculty Members -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Faculty / Staff</span>
                            <span class="p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-faculty" class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($totalFaculty) }}</p>
                        <p id="kpi-faculty-sub" class="text-[10px] text-gray-400 mt-0.5">Teaching faculty</p>
                    </div>

                    <!-- Metric 5: Unique Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Unique Students</span>
                            <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-students" class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($totalStudents) }}</p>
                        <p id="kpi-students-sub" class="text-[10px] text-gray-400 mt-0.5">Registered learners</p>
                    </div>

                    <!-- Metric 6: Materials & Content -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Materials & Asgns</span>
                            <span class="p-1.5 bg-rose-50 text-rose-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </span>
                        </div>
                        <p id="kpi-materials" class="text-xl sm:text-2xl font-black text-gray-900">{{ number_format($totalMaterials + $totalAssignments) }}</p>
                        <p id="kpi-materials-sub" class="text-[10px] text-gray-400 mt-0.5">{{ $totalMaterials }} Files | {{ $totalAssignments }} Asgns</p>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 1. DEPARTMENT SPECIFIC DEEP-DIVE VIEW (Shows when a department is chosen) -->
                <!-- ========================================================================= -->
                <div id="department-deep-dive-panel" class="hidden space-y-6">
                    
                    <!-- Department Banner Card -->
                    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-950 text-white rounded-2xl p-5 sm:p-6 shadow-md relative overflow-hidden">
                        <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-indigo-700/20 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 relative z-10">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span id="dept-banner-badge" class="px-2.5 py-0.5 bg-indigo-500/40 text-indigo-100 rounded-full text-xs font-bold uppercase tracking-wider">DEPARTMENT</span>
                                    <span id="dept-banner-school" class="text-xs text-indigo-200">School</span>
                                </div>
                                <h2 id="dept-banner-name" class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Information Technology</h2>
                                <p id="dept-banner-code" class="text-xs sm:text-sm text-indigo-200 mt-1">Code: dep_it</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <button onclick="selectDepartment('all')" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-semibold rounded-xl backdrop-blur-sm transition-all flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    View All Departments
                                </button>
                                <button onclick="exportDepartmentRoster()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Export Dept Report (.CSV)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Department Navigation Tabs -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1 flex overflow-x-auto gap-1">
                        <button id="tab-btn-courses" onclick="switchDeptTab('courses')" class="tab-btn active px-4 py-2 text-xs sm:text-sm font-bold rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Courses & Subjects (<span id="tab-count-courses">0</span>)
                        </button>
                        <button id="tab-btn-faculty" onclick="switchDeptTab('faculty')" class="tab-btn px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Faculty Teaching Roster (<span id="tab-count-faculty">0</span>)
                        </button>
                        <button id="tab-btn-students" onclick="switchDeptTab('students')" class="tab-btn px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Enrolled Students Directory (<span id="tab-count-students">0</span>)
                        </button>
                        <button id="tab-btn-curriculum" onclick="switchDeptTab('curriculum')" class="tab-btn px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Regulations & Curricula
                        </button>
                    </div>

                    <!-- TAB 1: COURSES & SUBJECTS -->
                    <div id="dept-tab-courses" class="dept-tab-content bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Courses & Subject Allocations</h3>
                                <p class="text-xs text-gray-500">Subjects offered by this department with faculty assignments & enrollment metrics.</p>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="text" id="dept-course-search" oninput="filterDeptCourses(this.value)" placeholder="Search subject code or name..." class="w-full px-3.5 py-2 text-xs sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="overflow-x-auto -mx-5 sm:-mx-6">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th class="px-5 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subject</th>
                                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Reg / Curric</th>
                                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Year & Sem</th>
                                        <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Allocated Faculty</th>
                                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Enrolled Students</th>
                                        <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Files / Asgns</th>
                                        <th class="px-5 sm:px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="dept-courses-tbody" class="divide-y divide-gray-100">
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                            <div id="dept-no-courses-msg" class="hidden py-12 text-center text-sm text-gray-500">
                                No courses found matching your search.
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: FACULTY TEACHING ROSTER -->
                    <div id="dept-tab-faculty" class="dept-tab-content hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Faculty Teaching Roster</h3>
                                <p class="text-xs text-gray-500">Staff members allocated to teaching subjects in this department.</p>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="text" id="dept-faculty-search" oninput="filterDeptFaculty(this.value)" placeholder="Search faculty name or ID..." class="w-full px-3.5 py-2 text-xs sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div id="dept-faculty-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Populated via JS -->
                        </div>
                        <div id="dept-no-faculty-msg" class="hidden py-12 text-center text-sm text-gray-500">
                            No faculty members currently allocated to this department.
                        </div>
                    </div>

                    <!-- TAB 3: ENROLLED STUDENTS DIRECTORY -->
                    <div id="dept-tab-students" class="dept-tab-content hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Enrolled Students Roster</h3>
                                <p class="text-xs text-gray-500">All registered students currently enrolled in courses of this department.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="dept-students-search" oninput="filterDeptStudents(this.value)" placeholder="Search student name or roll..." class="w-full sm:w-64 px-3.5 py-2 text-xs sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <button onclick="exportStudentRosterToCSV()" class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded-lg text-xs flex items-center shrink-0">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Export CSV
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto -mx-5 sm:-mx-6">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th class="px-5 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Register / Roll No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Enrolled Course</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Year / Sem</th>
                                    </tr>
                                </thead>
                                <tbody id="dept-students-tbody" class="divide-y divide-gray-100">
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                            <div id="dept-no-students-msg" class="hidden py-12 text-center text-sm text-gray-500">
                                No enrolled students found for this department.
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: REGULATIONS BREAKDOWN -->
                    <div id="dept-tab-curriculum" class="dept-tab-content hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="mb-5">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900">Curriculum & Regulation Distribution</h3>
                            <p class="text-xs text-gray-500">Distribution of courses and student enrollments across active regulations.</p>
                        </div>

                        <div id="dept-regulations-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Populated via JS -->
                        </div>
                    </div>

                </div>

                <!-- ========================================================================= -->
                <!-- 2. ALL DEPARTMENTS COMPARATIVE OVERVIEW (Shows when 'all' is selected)      -->
                <!-- ========================================================================= -->
                <div id="all-departments-panel" class="space-y-6">
                    
                    <!-- Quick Department Select Chips -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Quick Department Selection</h3>
                                <p class="text-xs text-gray-500">Click any department below to inspect its complete faculty, subjects, and student enrollments.</p>
                            </div>
                            <div class="w-full sm:w-64">
                                <input type="text" id="dept-quick-search" oninput="filterDepartmentCards(this.value)" placeholder="Search department name..." class="w-full px-3.5 py-2 text-xs sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div id="department-cards-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[600px] overflow-y-auto pr-1">
                            @foreach($departmentsList as $dept)
                            <div 
                                class="dept-overview-card bg-white border border-gray-200 hover:border-indigo-500 hover:shadow-md rounded-xl p-4 cursor-pointer transition-all flex flex-col justify-between group"
                                data-name="{{ strtolower($dept['name']) }}"
                                data-code="{{ strtolower($dept['code']) }}"
                                onclick="selectDepartment('{{ $dept['code'] }}')"
                            >
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $dept['allocation_rate'] >= 90 ? 'bg-emerald-50 text-emerald-700' : ($dept['allocation_rate'] > 0 ? 'bg-amber-50 text-amber-700' : 'bg-gray-100 text-gray-500') }}">
                                            {{ $dept['allocation_rate'] }}% Alloc
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1" title="{{ $dept['name'] }}">{{ $dept['name'] }}</h4>
                                    <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $dept['school_name'] }}</p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <span class="font-semibold text-gray-700">{{ $dept['total_courses'] }} <span class="font-normal text-gray-400">Courses</span></span>
                                    <span class="text-gray-300">•</span>
                                    <span class="font-semibold text-gray-700">{{ $dept['total_enrollments'] }} <span class="font-normal text-gray-400">Enrolled</span></span>
                                    <span class="text-gray-300">•</span>
                                    <span class="font-semibold text-indigo-600 group-hover:underline flex items-center">
                                        View <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Top Enrolled Subjects Leaderboard -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900">Top Enrolled Subjects Across Institution</h3>
                                <p class="text-xs text-gray-500">Highest student engagement and enrollment volume.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto -mx-5 sm:-mx-6">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th class="px-5 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subject Code & Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Regulation</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Department</th>
                                        <th class="px-5 sm:px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($topCourses as $course)
                                    <tr class="hover:bg-indigo-50/40 transition-colors cursor-pointer" onclick="openStudentsModalFromCourse({{ json_encode($course->enrollments->map(function($e) { return ['roll_no' => $e->profile->username ?? $e->username, 'name' => trim(($e->profile->first_name ?? '') . ' ' . ($e->profile->last_name ?? '')), 'email' => $e->email ?? 'N/A']; })->values()->toArray()) }}, '{{ $course->code }} - {{ addslashes($course->name) }}')">
                                        <td class="px-5 sm:px-6 py-3">
                                            <div class="font-bold text-sm text-gray-900">{{ $course->code }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-sm">{{ $course->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700">
                                                {{ $course->regulation ? $course->regulation->code : 'Direct' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs font-medium text-gray-600">
                                            {{ $course->department->name ?? $course->department_id }}
                                        </td>
                                        <td class="px-5 sm:px-6 py-3 whitespace-nowrap text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700">
                                                {{ number_format($course->enrollments_count) }} Students
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No course enrollments found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
            
            <footer class="mt-12 border-t border-gray-200 pt-6 pb-4">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

    @include('partials.reset_password_modal')

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

    <!-- Enrolled Students Detail Modal -->
    <div id="studentsModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden transform transition-all max-h-[85vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-900 text-white shrink-0">
                <div>
                    <h3 class="text-lg font-bold" id="studentsModalTitle">Enrolled Students Roster</h3>
                    <p class="text-xs text-indigo-200 mt-0.5" id="studentsModalSub">Subject: </p>
                </div>
                <button type="button" onclick="closeStudentsModal()" class="text-indigo-200 hover:text-white focus:outline-none shrink-0 ml-4 p-1">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between gap-3 shrink-0">
                <input type="text" id="modal-student-search" oninput="filterModalStudents(this.value)" placeholder="Search roll no, student name or email..." class="w-full sm:w-80 px-3.5 py-1.5 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button onclick="exportModalStudentsToCSV()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg flex items-center gap-1 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export
                </button>
            </div>

            <div class="p-0 overflow-y-auto flex-1 bg-white">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/90 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Register / Roll No</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email Address</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body" class="bg-white divide-y divide-gray-100">
                        <!-- Student rows injected via JS -->
                    </tbody>
                </table>
                <div id="no-students-msg" class="hidden py-12 text-center text-sm text-gray-500">
                    No students currently enrolled in this subject.
                </div>
            </div>
            
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end">
                <button type="button" onclick="closeStudentsModal()" class="px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none">Close</button>
            </div>
        </div>
    </div>

    <!-- Embedded Application State & JavaScript -->
    <script>
        // Global Datasets
        const departmentsData = @json($departmentsData);
        const globalMetrics = {
            totalEnrollments: {{ $totalEnrollments }},
            totalCourses: {{ $totalCoursesOffered }},
            totalAllocated: {{ $totalAllocatedCourses }},
            allocationRate: {{ $overallAllocationRate }},
            totalFaculty: {{ $totalFaculty }},
            totalStudents: {{ $totalStudents }},
            totalMaterials: {{ $totalMaterials }},
            totalAssignments: {{ $totalAssignments }}
        };

        let currentActiveDepartment = '{{ $selectedDepartment }}';
        let currentModalStudents = [];
        let currentModalSubjectTitle = '';

        // Initialize UI on load
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof window.initLMSUI === 'function') {
                window.initLMSUI();
            }

            if (currentActiveDepartment && currentActiveDepartment !== 'all' && departmentsData[currentActiveDepartment]) {
                selectDepartment(currentActiveDepartment);
            } else {
                selectDepartment('all');
            }
        });

        function onDepartmentSelectChange(deptCode) {
            selectDepartment(deptCode);
        }

        function selectDepartment(deptCode) {
            currentActiveDepartment = deptCode;
            const selectEl = document.getElementById('department-select');
            if (selectEl) selectEl.value = deptCode;

            const allPanel = document.getElementById('all-departments-panel');
            const deepDivePanel = document.getElementById('department-deep-dive-panel');

            if (deptCode === 'all' || !departmentsData[deptCode]) {
                // Show All Departments Panel
                allPanel.classList.remove('hidden');
                deepDivePanel.classList.add('hidden');
                updateGlobalKPIs();
            } else {
                // Show Department Specific Deep Dive Panel
                allPanel.classList.add('hidden');
                deepDivePanel.classList.remove('hidden');

                const data = departmentsData[deptCode];
                updateDepartmentKPIs(data);
                populateDepartmentDeepDive(data);
                switchDeptTab('courses');
            }

            // Update URL without full refresh so links can be shared
            const url = new URL(window.location);
            if (deptCode === 'all') {
                url.searchParams.delete('department');
            } else {
                url.searchParams.set('department', deptCode);
            }
            window.history.replaceState({}, '', url);
        }

        function updateGlobalKPIs() {
            document.getElementById('kpi-enrollments').textContent = Number(globalMetrics.totalEnrollments).toLocaleString();
            document.getElementById('kpi-enrollments-sub').textContent = 'Across all subjects';

            document.getElementById('kpi-courses').textContent = Number(globalMetrics.totalCourses).toLocaleString();
            document.getElementById('kpi-courses-sub').textContent = 'Active curriculum';

            document.getElementById('kpi-allocation').textContent = globalMetrics.allocationRate + '%';
            document.getElementById('kpi-allocation-sub').textContent = `${globalMetrics.totalAllocated} of ${globalMetrics.totalCourses} Assigned`;

            document.getElementById('kpi-faculty').textContent = Number(globalMetrics.totalFaculty).toLocaleString();
            document.getElementById('kpi-faculty-sub').textContent = 'Teaching faculty';

            document.getElementById('kpi-students').textContent = Number(globalMetrics.totalStudents).toLocaleString();
            document.getElementById('kpi-students-sub').textContent = 'Registered learners';

            document.getElementById('kpi-materials').textContent = Number(globalMetrics.totalMaterials + globalMetrics.totalAssignments).toLocaleString();
            document.getElementById('kpi-materials-sub').textContent = `${globalMetrics.totalMaterials} Files | ${globalMetrics.totalAssignments} Asgns`;
        }

        function updateDepartmentKPIs(data) {
            document.getElementById('kpi-enrollments').textContent = Number(data.total_enrollments).toLocaleString();
            document.getElementById('kpi-enrollments-sub').textContent = `In ${data.name}`;

            document.getElementById('kpi-courses').textContent = Number(data.total_courses).toLocaleString();
            document.getElementById('kpi-courses-sub').textContent = 'Department subjects';

            document.getElementById('kpi-allocation').textContent = data.allocation_rate + '%';
            document.getElementById('kpi-allocation-sub').textContent = `${data.allocated_courses} of ${data.total_courses} Allocated`;

            document.getElementById('kpi-faculty').textContent = Number(data.faculty_count).toLocaleString();
            document.getElementById('kpi-faculty-sub').textContent = 'Staff in department';

            document.getElementById('kpi-students').textContent = Number(data.unique_students_count).toLocaleString();
            document.getElementById('kpi-students-sub').textContent = 'Enrolled students';

            document.getElementById('kpi-materials').textContent = Number(data.materials_count + data.assignments_count).toLocaleString();
            document.getElementById('kpi-materials-sub').textContent = `${data.materials_count} Files | ${data.assignments_count} Asgns`;
        }

        function populateDepartmentDeepDive(data) {
            // Header Banner
            document.getElementById('dept-banner-name').textContent = data.name;
            document.getElementById('dept-banner-code').textContent = `Code: ${data.code} • School: ${data.school_name}`;
            document.getElementById('dept-banner-school').textContent = data.school_name;

            // Tab Counts
            document.getElementById('tab-count-courses').textContent = data.courses ? data.courses.length : 0;
            document.getElementById('tab-count-faculty').textContent = data.faculty ? data.faculty.length : 0;
            document.getElementById('tab-count-students').textContent = data.students ? data.students.length : 0;

            // Render Courses Table
            renderDeptCourses(data.courses || []);

            // Render Faculty Grid
            renderDeptFaculty(data.faculty || []);

            // Render Students Directory
            renderDeptStudents(data.students || []);

            // Render Regulations Breakdown
            renderDeptRegulations(data.regulations_breakdown || []);
        }

        function renderDeptCourses(courses) {
            const tbody = document.getElementById('dept-courses-tbody');
            const noMsg = document.getElementById('dept-no-courses-msg');

            if (!courses || courses.length === 0) {
                tbody.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }

            noMsg.classList.add('hidden');
            tbody.innerHTML = courses.map(c => {
                const staffAvatars = c.staff && c.staff.length > 0 ? c.staff.map(s => `
                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-indigo-50 text-indigo-800 rounded-lg text-xs font-semibold" title="Staff ID: ${esc(s.staff_id)}">
                        <span class="w-5 h-5 rounded-full bg-indigo-200 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                            ${esc((s.name || 'S').charAt(0))}
                        </span>
                        ${esc(s.name)}
                    </span>
                `).join(' ') : `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700">
                        Pending Allocation
                    </span>
                `;

                return `
                    <tr class="hover:bg-indigo-50/30 transition-colors dept-course-row" data-search="${esc(c.code.toLowerCase())} ${esc(c.name.toLowerCase())}">
                        <td class="px-5 sm:px-6 py-3.5">
                            <div class="font-extrabold text-sm text-gray-900">${esc(c.code)}</div>
                            <div class="text-xs text-gray-500 max-w-xs truncate" title="${esc(c.name)}">${esc(c.name)}</div>
                        </td>
                        <td class="px-3 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                ${esc(c.regulation_code)} ${c.curriculum && c.curriculum !== 'N/A' ? '(' + esc(c.curriculum) + ')' : ''}
                            </span>
                        </td>
                        <td class="px-3 py-3.5 whitespace-nowrap text-xs text-gray-600">
                            ${c.year ? `Year ${esc(c.year)}, Sem ${esc(c.semester)}` : 'All Batches'}
                        </td>
                        <td class="px-3 py-3.5">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                ${staffAvatars}
                            </div>
                        </td>
                        <td class="px-3 py-3.5 whitespace-nowrap text-center">
                            <button onclick="openStudentsModalFromCourse(${JSON.stringify(c.students).replace(/"/g, '&quot;')}, '${esc(c.code)} - ${esc(c.name)}')" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                ${c.enrollments_count || 0} Students
                            </button>
                        </td>
                        <td class="px-3 py-3.5 whitespace-nowrap text-center text-xs font-medium text-gray-500">
                            <span class="px-1.5 py-0.5 bg-gray-100 rounded">${c.materials_count || 0} Files</span>
                            <span class="mx-1">•</span>
                            <span class="px-1.5 py-0.5 bg-gray-100 rounded">${c.assignments_count || 0} Asgns</span>
                        </td>
                        <td class="px-5 sm:px-6 py-3.5 whitespace-nowrap text-right text-xs">
                            <button onclick="openStudentsModalFromCourse(${JSON.stringify(c.students).replace(/"/g, '&quot;')}, '${esc(c.code)} - ${esc(c.name)}')" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg transition-colors">
                                View Roster
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderDeptFaculty(faculty) {
            const container = document.getElementById('dept-faculty-grid');
            const noMsg = document.getElementById('dept-no-faculty-msg');

            if (!faculty || faculty.length === 0) {
                container.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }

            noMsg.classList.add('hidden');
            container.innerHTML = faculty.map(f => {
                const assignedCoursesHtml = f.courses && f.courses.length > 0 ? f.courses.map(ac => `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700" title="${esc(ac.name)}">
                        ${esc(ac.code)}
                    </span>
                `).join(' ') : '<span class="text-xs text-gray-400">No active course assigned</span>';

                return `
                    <div class="dept-faculty-card bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all flex flex-col justify-between" data-search="${esc(f.name.toLowerCase())} ${esc(f.staff_id.toLowerCase())}">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-800 text-white flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                    ${esc((f.name || 'F').charAt(0))}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">${esc(f.name)}</h4>
                                    <p class="text-xs text-indigo-600 font-medium">ID: ${esc(f.staff_id)}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-1 text-xs text-gray-500 mb-3">
                                <p class="truncate"><span class="font-medium text-gray-700">Email:</span> ${esc(f.email || 'N/A')}</p>
                                <p><span class="font-medium text-gray-700">Phone:</span> ${esc(f.phone || 'N/A')}</p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Assigned Subjects (${f.courses_count || 0})</p>
                            <div class="flex flex-wrap gap-1 mb-2">
                                ${assignedCoursesHtml}
                            </div>
                            <div class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg text-center">
                                Total Students Taught: ${f.students_taught || 0}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderDeptStudents(students) {
            const tbody = document.getElementById('dept-students-tbody');
            const noMsg = document.getElementById('dept-no-students-msg');

            if (!students || students.length === 0) {
                tbody.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }

            noMsg.classList.add('hidden');
            tbody.innerHTML = students.map(s => `
                <tr class="hover:bg-indigo-50/30 transition-colors dept-student-row" data-search="${esc((s.roll_no || '').toLowerCase())} ${esc((s.name || '').toLowerCase())} ${esc((s.email || '').toLowerCase())}">
                    <td class="px-5 sm:px-6 py-3 font-mono font-bold text-xs text-indigo-900">${esc(s.roll_no || 'N/A')}</td>
                    <td class="px-4 py-3 font-semibold text-xs sm:text-sm text-gray-900">${esc(s.name)}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">${esc(s.email || 'N/A')}</td>
                    <td class="px-4 py-3 text-xs font-medium text-gray-800">
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-800 rounded font-bold mr-1">${esc(s.course_code || 'Course')}</span>
                        ${esc(s.course_name || '')}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center text-xs text-gray-600">
                        ${s.year ? `Y${esc(s.year)} - S${esc(s.semester)}` : 'Standard'}
                    </td>
                </tr>
            `).join('');
        }

        function renderDeptRegulations(regulations) {
            const container = document.getElementById('dept-regulations-grid');

            if (!regulations || regulations.length === 0) {
                container.innerHTML = '<div class="col-span-full py-8 text-center text-sm text-gray-500">No regulation data found for this department.</div>';
                return;
            }

            container.innerHTML = regulations.map(r => `
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800">${esc(r.regulation)}</span>
                        <span class="text-xs font-bold text-gray-900">${r.courses_count} Courses</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-600">
                        <span>Enrolled Students</span>
                        <span class="font-black text-indigo-600 text-sm">${r.enrollments_count}</span>
                    </div>
                </div>
            `).join('');
        }

        function switchDeptTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active');
                b.classList.add('text-gray-600');
            });
            document.querySelectorAll('.dept-tab-content').forEach(c => c.classList.add('hidden'));

            const activeBtn = document.getElementById('tab-btn-' + tabName);
            const activeContent = document.getElementById('dept-tab-' + tabName);

            if (activeBtn) {
                activeBtn.classList.add('active');
                activeBtn.classList.remove('text-gray-600');
            }
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }

        // Search within courses
        function filterDeptCourses(query) {
            const q = query.toLowerCase().trim();
            let count = 0;
            document.querySelectorAll('.dept-course-row').forEach(row => {
                const text = row.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            const noMsg = document.getElementById('dept-no-courses-msg');
            if (noMsg) {
                if (count === 0) noMsg.classList.remove('hidden');
                else noMsg.classList.add('hidden');
            }
        }

        // Search within faculty
        function filterDeptFaculty(query) {
            const q = query.toLowerCase().trim();
            let count = 0;
            document.querySelectorAll('.dept-faculty-card').forEach(card => {
                const text = card.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    card.style.display = '';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });
            const noMsg = document.getElementById('dept-no-faculty-msg');
            if (noMsg) {
                if (count === 0) noMsg.classList.remove('hidden');
                else noMsg.classList.add('hidden');
            }
        }

        // Search within students
        function filterDeptStudents(query) {
            const q = query.toLowerCase().trim();
            let count = 0;
            document.querySelectorAll('.dept-student-row').forEach(row => {
                const text = row.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            const noMsg = document.getElementById('dept-no-students-msg');
            if (noMsg) {
                if (count === 0) noMsg.classList.remove('hidden');
                else noMsg.classList.add('hidden');
            }
        }

        // Search department cards in overview
        function filterDepartmentCards(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.dept-overview-card').forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const code = card.getAttribute('data-code') || '';
                if (!q || name.includes(q) || code.includes(q)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Students Modal functions
        function openStudentsModalFromCourse(students, courseTitle) {
            currentModalStudents = students || [];
            currentModalSubjectTitle = courseTitle;

            document.getElementById('studentsModalTitle').textContent = 'Enrolled Students Roster';
            document.getElementById('studentsModalSub').textContent = 'Subject: ' + courseTitle;
            document.getElementById('modal-student-search').value = '';

            renderModalStudents(currentModalStudents);

            document.getElementById('studentsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function renderModalStudents(students) {
            const tbody = document.getElementById('students-table-body');
            const noMsg = document.getElementById('no-students-msg');

            if (!students || students.length === 0) {
                tbody.innerHTML = '';
                noMsg.classList.remove('hidden');
                return;
            }

            noMsg.classList.add('hidden');
            tbody.innerHTML = students.map(s => `
                <tr class="hover:bg-indigo-50/40 modal-student-row" data-search="${esc((s.roll_no || '').toLowerCase())} ${esc((s.name || '').toLowerCase())} ${esc((s.email || '').toLowerCase())}">
                    <td class="px-6 py-3 text-xs font-mono font-bold text-indigo-900">${esc(s.roll_no || 'N/A')}</td>
                    <td class="px-6 py-3 text-sm font-semibold text-gray-900">${esc(s.name)}</td>
                    <td class="px-6 py-3 text-xs text-gray-500">${esc(s.email || 'N/A')}</td>
                </tr>
            `).join('');
        }

        function filterModalStudents(query) {
            const q = query.toLowerCase().trim();
            let count = 0;
            document.querySelectorAll('.modal-student-row').forEach(row => {
                const text = row.getAttribute('data-search') || '';
                if (!q || text.includes(q)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            const noMsg = document.getElementById('no-students-msg');
            if (noMsg) {
                if (count === 0) noMsg.classList.remove('hidden');
                else noMsg.classList.add('hidden');
            }
        }

        function closeStudentsModal() {
            document.getElementById('studentsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Export Utilities (CSV/Excel)
        function exportCurrentViewToExcel() {
            if (currentActiveDepartment && currentActiveDepartment !== 'all' && departmentsData[currentActiveDepartment]) {
                exportDepartmentRoster();
            } else {
                exportAllDepartmentsSummary();
            }
        }

        function exportAllDepartmentsSummary() {
            const rows = [
                ['Department Code', 'Department Name', 'School', 'Total Courses', 'Allocated Courses', 'Allocation Rate (%)', 'Total Enrollments', 'Unique Students', 'Faculty Count']
            ];

            Object.values(departmentsData).forEach(d => {
                rows.push([
                    d.code,
                    d.name,
                    d.school_name,
                    d.total_courses,
                    d.allocated_courses,
                    d.allocation_rate + '%',
                    d.total_enrollments,
                    d.unique_students_count,
                    d.faculty_count
                ]);
            });

            downloadCSV(rows, 'All_Departments_Enrollment_Insights.csv');
        }

        function exportDepartmentRoster() {
            const data = departmentsData[currentActiveDepartment];
            if (!data) return;

            const rows = [
                ['DEPARTMENT ENROLLMENT & ALLOCATION REPORT'],
                ['Department:', data.name, 'Code:', data.code],
                ['School:', data.school_name],
                ['Total Courses:', data.total_courses, 'Allocation Rate:', data.allocation_rate + '%'],
                ['Total Enrollments:', data.total_enrollments, 'Unique Students:', data.unique_students_count],
                [],
                ['COURSE & ALLOCATION BREAKDOWN'],
                ['Course Code', 'Course Name', 'Regulation', 'Curriculum', 'Year', 'Semester', 'Allocated Faculty', 'Enrolled Students', 'Materials Uploaded', 'Assignments']
            ];

            (data.courses || []).forEach(c => {
                const staffNames = (c.staff || []).map(s => s.name).join('; ') || 'Unallocated';
                rows.push([
                    c.code,
                    c.name,
                    c.regulation_code,
                    c.curriculum,
                    c.year || 'N/A',
                    c.semester || 'N/A',
                    staffNames,
                    c.enrollments_count,
                    c.materials_count,
                    c.assignments_count
                ]);
            });

            rows.push([]);
            rows.push(['ENROLLED STUDENTS LIST']);
            rows.push(['Register/Roll No', 'Student Name', 'Email', 'Enrolled Course Code', 'Course Name', 'Year', 'Semester']);

            (data.students || []).forEach(s => {
                rows.push([
                    s.roll_no || 'N/A',
                    s.name,
                    s.email || 'N/A',
                    s.course_code || '',
                    s.course_name || '',
                    s.year || '',
                    s.semester || ''
                ]);
            });

            downloadCSV(rows, `${data.code}_Department_Enrollment_Report.csv`);
        }

        function exportStudentRosterToCSV() {
            const data = departmentsData[currentActiveDepartment];
            if (!data) return;

            const rows = [
                ['Register/Roll No', 'Student Name', 'Email Address', 'Course Code', 'Course Name', 'Department']
            ];

            (data.students || []).forEach(s => {
                rows.push([
                    s.roll_no || 'N/A',
                    s.name,
                    s.email || 'N/A',
                    s.course_code || '',
                    s.course_name || '',
                    data.name
                ]);
            });

            downloadCSV(rows, `${data.code}_Enrolled_Students.csv`);
        }

        function exportModalStudentsToCSV() {
            const rows = [
                ['Register/Roll No', 'Student Name', 'Email Address', 'Subject']
            ];

            currentModalStudents.forEach(s => {
                rows.push([
                    s.roll_no || 'N/A',
                    s.name,
                    s.email || 'N/A',
                    currentModalSubjectTitle
                ]);
            });

            const cleanSubject = currentModalSubjectTitle.replace(/[^a-zA-Z0-9_-]/g, '_');
            downloadCSV(rows, `${cleanSubject}_Students_Roster.csv`);
        }

        function downloadCSV(rows, filename) {
            const csvContent = '\uFEFF' + rows.map(r => r.map(field => {
                const str = String(field !== null && field !== undefined ? field : '');
                return '"' + str.replace(/"/g, '""') + '"';
            }).join(',')).join('\r\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function esc(str) {
            return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g, '&#039;');
        }
    </script>
</body>
</html>
