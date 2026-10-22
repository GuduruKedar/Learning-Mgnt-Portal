<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->name }} ({{ $course->code }}) - LMS Faculty Portal</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v={{ filemtime(public_path('css/main.css')) }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
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
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-50">
            <div class="w-full space-y-6">

                <!-- Breadcrumbs & Quick Navigation -->
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'sa')
                            <a href="{{ route('academic.courses') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs hover:shadow transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                <span>Back to Course List</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs hover:shadow transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                <span>Back to Dashboard</span>
                            </a>
                        @endif
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-700 font-bold">{{ $course->code }}</span>
                        <span class="text-gray-300">&bull;</span>
                        <span class="text-gray-600 truncate max-w-xs">{{ $course->name }}</span>
                    </nav>

                    <div class="flex items-center gap-2">
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'sa')
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Coordinator Course Hub (All Faculty Materials)
                            </span>
                        @else
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Assigned Course Hub
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Course Details Banner -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200/90 dark:border-[#273244] shadow-2xs p-6 sm:p-7 relative overflow-hidden">
                    <div class="relative space-y-3">
                        <!-- Course Metadata Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold font-mono bg-indigo-600 text-white tracking-wider shadow-2xs">
                                {{ $course->code }}
                            </span>

                            @if($course->regulation)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    {{ $course->regulation->name }}
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Year {{ $course->year ?? 1 }} &bull; Semester {{ $course->semester ?? 1 }}
                            </span>
                        </div>

                        <!-- Course Title & Department -->
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                                {{ $course->name }}
                            </h1>
                            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs sm:text-sm text-gray-600 dark:text-slate-400 font-medium">
                                @if($course->department)
                                    <span class="inline-flex items-center gap-1.5 text-gray-700 dark:text-slate-300 font-semibold">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $course->department->name }}
                                    </span>
                                @endif
                                @if($course->department && $course->department->school)
                                    <span class="text-gray-300 dark:text-slate-600">&bull;</span>
                                    <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-slate-400">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        {{ $course->department->school->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Quick Interactive Metric Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Course Materials Card -->
                    <button type="button" onclick="switchCourseTab('materials')" id="metric-card-materials" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow course-metric-card">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 shadow-2xs transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Course Materials</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-none stat-number">{{ $totalMaterialsCount }}</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-slate-400 stat-subtext">({{ $filesCount }} files &bull; {{ $linksCount }} links)</span>
                            </div>
                        </div>
                    </button>

                    <!-- Assessments Metric Card -->
                    <button type="button" onclick="switchCourseTab('assignments')" id="metric-card-assignments" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow course-metric-card">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-800/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 shadow-2xs transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-purple-600 dark:text-purple-400">Assessments</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-none stat-number">{{ $totalAssignmentsCount }}</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-slate-400 stat-subtext">({{ $activeAssignmentsCount }} active)</span>
                            </div>
                        </div>
                    </button>

                    <!-- Students Metric Card -->
                    <button type="button" onclick="switchCourseTab('overview')" id="metric-card-overview" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow course-metric-card">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 shadow-2xs transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Enrolled Students</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-none stat-number">{{ $enrolledStudentsCount }}</span>
                                <span class="text-xs font-medium text-gray-500 dark:text-slate-400 stat-subtext">Active learners</span>
                            </div>
                        </div>
                    </button>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-xs">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-xs">
                        <ul class="list-disc pl-5 text-sm space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ========================================================= -->
                <!-- TAB PANEL 1: LEARNING MATERIALS                           -->
                <!-- ========================================================= -->
                <div id="course-panel-materials" role="tabpanel" aria-labelledby="course-tab-btn-materials" class="space-y-6">
                    
                    <!-- Materials Directory (Full Width & Professional) -->
                    <div class="course-materials-directory bg-white dark:bg-[#151B23] rounded-2xl shadow-2xs border border-gray-200/90 dark:border-[#273244] overflow-hidden flex flex-col">
                        <div class="course-materials-header px-5 sm:px-6 py-4 border-b border-gray-100 dark:border-[#273244] bg-gray-50/80 dark:bg-[#151B23] flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white tracking-tight">Learning Materials Directory</h3>
                                    <span id="materialsCountBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/60 transition-all">
                                        {{ $totalMaterialsCount }} Total
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Direct learning access for faculty and enrolled students</p>
                            </div>
                            
                            <!-- Live Search, Filter & Manage Materials Action Button -->
                            <div class="flex items-center flex-wrap sm:flex-nowrap gap-2 w-full lg:w-auto justify-start lg:justify-end">
                                <!-- Live Search Input -->
                                <div class="relative flex-1 sm:w-60 min-w-[160px]">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" 
                                           id="materialsSearchInput" 
                                           oninput="filterMaterialsLive()" 
                                           placeholder="Search title, format, division..." 
                                           class="h-10 block w-full pl-9 pr-8 border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 rounded-xl text-xs bg-white dark:bg-[#1C2430] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-2xs">
                                    <button type="button" 
                                            id="materialsSearchClearBtn" 
                                            onclick="clearMaterialsSearch()" 
                                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-slate-200 hidden cursor-pointer" 
                                            title="Clear Search">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <!-- Live Type Filter Dropdown (Custom styled pill) -->
                                <div class="relative shrink-0">
                                    <select id="materialsTypeSelect" 
                                            onchange="filterMaterialsLive()" 
                                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                                            class="h-10 appearance-none pl-3 pr-8 border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 bg-white dark:bg-[#1C2430] rounded-xl text-xs font-semibold text-gray-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-2xs cursor-pointer">
                                        <option value="">All Types</option>
                                        <option value="file">📄 Files</option>
                                        <option value="link">🔗 Links</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 dark:text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>

                                <!-- Reset Filters Button -->
                                <button type="button" 
                                        id="materialsResetFilterBtn" 
                                        onclick="resetAllMaterialFilters()" 
                                        class="hidden h-10 shrink-0 inline-flex items-center gap-1.5 px-3 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200/80 dark:border-rose-800/60 rounded-xl text-xs font-bold transition-all shadow-2xs cursor-pointer" 
                                        title="Reset Filters">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span>Reset</span>
                                </button>

                                <!-- Manage Materials Trigger Button -->
                                <button type="button" onclick="openManageMaterialsModal()" class="manage-materials-btn h-10 shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    <span class="whitespace-nowrap font-bold">Manage Materials</span>
                                </button>
                            </div>
                        </div>

                        @php
                            $subfolderCategories = [
                                'Course objective and outcomes' => [
                                    'name' => 'Course Objectives & Outcomes (CO / PO)',
                                    'badge' => 'Course Objectives',
                                    'short_name' => 'Course Objectives & Outcomes',
                                    'description' => 'Course educational goals, course outcomes (COs), program outcomes (POs), and learning taxonomies',
                                    'color' => 'purple',
                                    'border_accent' => 'border-purple-200/90',
                                    'header_gradient' => 'from-purple-50/90 via-purple-50/40 to-white',
                                    'icon_bg' => 'bg-purple-100 text-purple-700 border border-purple-200',
                                    'badge_class' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'icon' => '🎯',
                                ],
                                'Syllabus document' => [
                                    'name' => 'Syllabus Document',
                                    'badge' => 'Syllabus',
                                    'short_name' => 'Syllabus Document',
                                    'description' => 'Official curriculum breakdown, lecture schedule, evaluation schemes, and textbook references',
                                    'color' => 'blue',
                                    'border_accent' => 'border-blue-200/90',
                                    'header_gradient' => 'from-blue-50/90 via-blue-50/40 to-white',
                                    'icon_bg' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    'badge_class' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'icon' => '📜',
                                ],
                                'PPTs' => [
                                    'name' => 'PowerPoint Presentations (PPTs)',
                                    'badge' => 'PPTs / Slides',
                                    'short_name' => 'PPTs',
                                    'description' => 'Lecture slide decks, interactive visual presentations, and module slides',
                                    'color' => 'amber',
                                    'border_accent' => 'border-amber-200/90',
                                    'header_gradient' => 'from-amber-50/90 via-amber-50/40 to-white',
                                    'icon_bg' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    'badge_class' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'icon' => '📊',
                                ],
                                'Lecture notes' => [
                                    'name' => 'Lecture Notes & Study Material',
                                    'badge' => 'Lecture Notes',
                                    'short_name' => 'Lecture Notes',
                                    'description' => 'Comprehensive class reading notes, module summaries, and study handouts',
                                    'color' => 'emerald',
                                    'border_accent' => 'border-emerald-200/90',
                                    'header_gradient' => 'from-emerald-50/90 via-emerald-50/40 to-white',
                                    'icon_bg' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                    'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'icon' => '📝',
                                ],
                                'Lab manual' => [
                                    'name' => 'Lab Manual & Practical Guides',
                                    'badge' => 'Lab Manual',
                                    'short_name' => 'Lab Manual',
                                    'description' => 'Laboratory instructions, experiment procedures, code guides, and viva reference sheets',
                                    'color' => 'teal',
                                    'border_accent' => 'border-teal-200/90',
                                    'header_gradient' => 'from-teal-50/90 via-teal-50/40 to-white',
                                    'icon_bg' => 'bg-teal-100 text-teal-700 border border-teal-200',
                                    'badge_class' => 'bg-teal-100 text-teal-800 border-teal-200',
                                    'icon' => '🧪',
                                ],
                                'Assigment Questions' => [
                                    'name' => 'Assignment Questions',
                                    'badge' => 'Assignment Questions',
                                    'short_name' => 'Assigment Questions',
                                    'description' => 'Homework tasks, practice assignments, problem sets, and case study briefs',
                                    'color' => 'indigo',
                                    'border_accent' => 'border-indigo-200/90',
                                    'header_gradient' => 'from-indigo-50/90 via-indigo-50/40 to-white',
                                    'icon_bg' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
                                    'badge_class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'icon' => '📋',
                                ],
                                'Question Bank' => [
                                    'name' => 'Question Bank & Solved Problems',
                                    'badge' => 'Question Bank',
                                    'short_name' => 'Question Bank',
                                    'description' => 'Unit-wise question pools, model answers, 2-mark & 16-mark compilations',
                                    'color' => 'violet',
                                    'border_accent' => 'border-violet-200/90',
                                    'header_gradient' => 'from-violet-50/90 via-violet-50/40 to-white',
                                    'icon_bg' => 'bg-violet-100 text-violet-700 border border-violet-200',
                                    'badge_class' => 'bg-violet-100 text-violet-800 border-violet-200',
                                    'icon' => '🗂️',
                                ],
                                'Gate Questions' => [
                                    'name' => 'GATE Questions & Competitive Prep',
                                    'badge' => 'GATE Questions',
                                    'short_name' => 'Gate Questions',
                                    'description' => 'GATE examination previous year questions, solutions, and competitive mock drills',
                                    'color' => 'orange',
                                    'border_accent' => 'border-orange-200/90',
                                    'header_gradient' => 'from-orange-50/90 via-orange-50/40 to-white',
                                    'icon_bg' => 'bg-orange-100 text-orange-700 border border-orange-200',
                                    'badge_class' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'icon' => '🎓',
                                ],
                                'Previous Question Papers' => [
                                    'name' => 'Previous Question Papers (QP)',
                                    'badge' => 'Previous Question Papers',
                                    'short_name' => 'Previous Question Papers',
                                    'description' => 'University semester end examination papers, mid-term tests, and archival papers',
                                    'color' => 'cyan',
                                    'border_accent' => 'border-cyan-200/90',
                                    'header_gradient' => 'from-cyan-50/90 via-cyan-50/40 to-white',
                                    'icon_bg' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',
                                    'badge_class' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    'icon' => '📑',
                                ],
                                'Videos' => [
                                    'name' => 'Recorded Lectures & Videos',
                                    'badge' => 'Videos',
                                    'short_name' => 'Videos',
                                    'description' => 'Video recordings, YouTube playlist links, webinar clips, and visual demonstrations',
                                    'color' => 'red',
                                    'border_accent' => 'border-red-200/90',
                                    'header_gradient' => 'from-red-50/90 via-red-50/40 to-white',
                                    'icon_bg' => 'bg-red-100 text-red-700 border border-red-200',
                                    'badge_class' => 'bg-red-100 text-red-800 border-red-200',
                                    'icon' => '🎥',
                                ],
                                'Additional material' => [
                                    'name' => 'Additional Material & References',
                                    'badge' => 'Additional Material',
                                    'short_name' => 'Additional material',
                                    'description' => 'Supplementary reference documents, spreadsheets, external web links, and extra materials',
                                    'color' => 'slate',
                                    'border_accent' => 'border-slate-200/90',
                                    'header_gradient' => 'from-slate-50/90 via-slate-50/40 to-white',
                                    'icon_bg' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                    'badge_class' => 'bg-slate-100 text-slate-800 border-slate-200',
                                    'icon' => '📁',
                                ],
                            ];

                            $groupedMaterials = [];
                            foreach ($subfolderCategories as $k => $c) {
                                $groupedMaterials[$k] = collect();
                            }
                            foreach ($materials as $mat) {
                                $cat = $mat->effective_division;
                                if (!isset($groupedMaterials[$cat])) {
                                    $groupedMaterials['Additional material']->push($mat);
                                } else {
                                    $groupedMaterials[$cat]->push($mat);
                                }
                            }
                        @endphp

                        <!-- Sub-Folder Navigation Bar (Segregation Tabs) -->
                        <div class="px-5 sm:px-6 py-3 bg-slate-50/80 dark:bg-[#1C2430]/70 border-b border-gray-200/80 dark:border-[#273244] flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
                            <div class="flex items-center gap-2 shrink-0" id="subfolderTabsContainer">
                                <button type="button" 
                                    onclick="selectSubfolder('all')" 
                                    id="subfolder-tab-all"
                                    data-subfolder-target="all"
                                    class="subfolder-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-2xs cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                    <span>All Divisions</span>
                                    <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white">{{ $totalMaterialsCount }}</span>
                                </button>

                                @foreach($subfolderCategories as $catKey => $catMeta)
                                    @php 
                                        $catCount = $groupedMaterials[$catKey]->count(); 
                                        $catSlug = Str::slug($catKey);
                                    @endphp
                                    @if($catCount > 0)
                                    <button type="button" 
                                        onclick="selectSubfolder('{{ $catSlug }}')" 
                                        id="subfolder-tab-{{ $catSlug }}"
                                        data-subfolder-target="{{ $catSlug }}"
                                        class="subfolder-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-[#151B23] border border-gray-200 dark:border-[#273244] hover:border-gray-300 dark:hover:border-slate-600 transition-all shadow-2xs cursor-pointer">
                                        <span class="text-xs">{{ $catMeta['icon'] }}</span>
                                        <span>{{ $catMeta['short_name'] }}</span>
                                        <span class="tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-[#1C2430] text-gray-700 dark:text-slate-300 border border-transparent dark:border-[#273244]">{{ $catCount }}</span>
                                    </button>
                                    @endif
                                @endforeach
                            </div>

                            <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                                <!-- Cards vs Table Layout Switcher -->
                                <div class="inline-flex p-1 bg-white dark:bg-[#151B23] rounded-xl border border-gray-200 dark:border-[#273244] shadow-2xs">
                                    <button type="button" onclick="setCourseMaterialsLayout('cards')" id="course-mat-btn-cards" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 cursor-pointer flex items-center gap-1.5" title="Cards View">
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        <span>Cards</span>
                                    </button>
                                    <button type="button" onclick="setCourseMaterialsLayout('table')" id="course-mat-btn-table" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all cursor-pointer flex items-center gap-1.5" title="Table View">
                                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        <span>Table</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 bg-gray-50/50 dark:bg-[#0D1117]/60 flex-1 space-y-6" id="materialsCardsContainer">
                            @if($totalMaterialsCount === 0)
                                <div class="py-16 px-6 text-center bg-white dark:bg-[#151B23] border border-gray-100 dark:border-[#273244] rounded-2xl shadow-2xs">
                                    <div class="mx-auto w-16 h-16 bg-gradient-to-tr from-indigo-100 to-indigo-50 dark:from-indigo-950/60 dark:to-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-4 ring-8 ring-indigo-50/60 dark:ring-indigo-950/40 shadow-xs">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white mb-1">No Materials Uploaded Yet</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                                        Publish course objectives, syllabus documents, PPTs, lecture notes, lab manuals, question banks, or study videos for your students.
                                    </p>
                                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                                        <button type="button" onclick="openManageMaterialsModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span>Manage / Add Material</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Layout 1: Segregated Academic Division Sections (Cards View) -->
                                <div id="courseMaterialsCardsWrap" class="space-y-6">
                                    {{-- Segregated Academic Division Sections --}}
                                    @foreach($subfolderCategories as $catKey => $catMeta)
                                    @php 
                                        $catItems = $groupedMaterials[$catKey]; 
                                        $catSlug = Str::slug($catKey);
                                    @endphp
                                    @if($catItems->count() > 0)
                                    <div class="subfolder-section bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs overflow-hidden transition-all duration-200" 
                                         id="subfolder-section-{{ $catSlug }}"
                                         data-folder-key="{{ $catSlug }}">
                                        
                                        <!-- Subfolder Header Banner -->
                                        <div onclick="toggleSubfolder('{{ $catSlug }}')" 
                                             class="px-5 py-4 bg-gray-50/70 dark:bg-[#1C2128] border-b border-gray-100 dark:border-[#273244] flex items-center justify-between cursor-pointer select-none group transition-colors">
                                            <div class="flex items-center gap-3.5 min-w-0">
                                                <div class="w-10 h-10 rounded-xl {{ $catMeta['icon_bg'] }} dark:bg-[#273244] dark:border-[#384457] flex items-center justify-center text-lg shadow-2xs shrink-0 group-hover:scale-105 transition-transform">
                                                    <span>{{ $catMeta['icon'] }}</span>
                                                </div>

                                                <div class="min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <h4 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white tracking-tight truncate">{{ $catMeta['name'] }}</h4>
                                                        <span class="subfolder-count-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $catMeta['badge_class'] }} transition-all">
                                                            {{ $catItems->count() }} {{ $catItems->count() === 1 ? 'Material' : 'Materials' }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 truncate">{{ $catMeta['description'] }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2.5 shrink-0 ml-3">
                                                <span class="subfolder-toggle-text text-xs font-semibold text-gray-400 dark:text-slate-400 hidden md:inline">Collapse</span>
                                                <div class="subfolder-chevron w-7 h-7 rounded-lg bg-white dark:bg-[#273244] border border-gray-200 dark:border-[#384457] flex items-center justify-center text-gray-500 dark:text-slate-300 group-hover:border-indigo-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-all duration-200 shadow-2xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Subfolder Grid Content -->
                                        <div class="subfolder-content-body p-5 sm:p-6 bg-slate-50/30 dark:bg-[#151B23]">
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                                @foreach($catItems as $material)
                                                <div class="material-card bg-white dark:bg-[#1C2128] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/50 transition-all duration-200 flex flex-col group h-full justify-between overflow-hidden"
                                                     data-material-title="{{ strtolower($material->title) }}"
                                                     data-material-type="{{ strtolower($material->type) }}"
                                                     data-material-platform="{{ strtolower($material->platform) }}"
                                                     data-material-division="{{ strtolower($material->effective_division) }}"
                                                     data-material-category="{{ strtolower(($catMeta['name'] ?? '') . ' ' . ($catMeta['short_name'] ?? '') . ' ' . $catSlug . ' ' . $catKey) }}"
                                                     data-material-ext="{{ strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION)) }}"
                                                     data-material-staff="{{ strtolower($material->staff->profile->first_name ?? '') }}"
                                                     data-subfolder="{{ $catSlug }}">
                                                    <div class="p-5 flex-1 relative flex flex-col justify-between">
                                                        <div>
                                                            {{-- Top Row: Format Icon + Platform Badge + Action/Delete --}}
                                                            @php
                                                                $canDeleteMaterial = Auth::user()->role === 'sa'
                                                                     || (Auth::user()->role === 'admin' && (!Auth::user()->profile || Auth::user()->profile->departments_id === $course->department_id))
                                                                     || ($course->staff && $course->staff->contains(Auth::id()))
                                                                     || $material->staff_id === Auth::id();
                                                                $matDelUrl = route('staff.courses.materials.destroy', $material->id);
                                                                $matOpenUrl = $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id);
                                                                $allocData = [
                                                                    'id' => $material->id,
                                                                    'title' => $material->title,
                                                                    'division' => $material->effective_division,
                                                                    'platform' => $material->platform,
                                                                    'type' => $material->type,
                                                                    'updateUrl' => route('staff.courses.materials.update', $material->id)
                                                                ];
                                                            @endphp

                                                            <div class="flex items-center justify-between gap-3 mb-3.5">
                                                                <div class="flex items-center gap-2.5 min-w-0">
                                                                    <a href="{{ $matOpenUrl }}" target="_blank" class="shrink-0 group/icon block" title="Open {{ $material->title }}">
                                                                        @if($material->platform === 'youtube')
                                                                            <div class="h-10 w-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'drive' || $material->platform === 'onedrive')
                                                                            <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'pdf')
                                                                            <div class="h-10 w-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'word')
                                                                            <div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'excel')
                                                                            <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'ppt')
                                                                            <div class="h-10 w-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                            </div>
                                                                        @elseif($material->platform === 'txt')
                                                                            <div class="h-10 w-10 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                            </div>
                                                                        @else
                                                                            <div class="h-10 w-10 rounded-xl bg-slate-100 border border-slate-200/70 flex items-center justify-center text-slate-500 shadow-2xs group-hover/icon:scale-105 transition-transform">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                            </div>
                                                                        @endif
                                                                    </a>
                                                                    
                                                                    @php
                                                                        $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                                                    @endphp
                                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase font-mono tracking-wider {{ in_array($rawExt, ['ppt','pptx','pps']) ? 'bg-amber-100 text-amber-800 border border-amber-200' : (in_array($rawExt, ['xls','xlsx','csv']) ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($rawExt === 'pdf' ? 'bg-rose-100 text-rose-800 border border-rose-200' : (in_array($rawExt, ['doc','docx']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200'))) }}">
                                                                            {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                        </span>
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-[#1C2128] border border-slate-200 dark:border-[#273244] capitalize">
                                                                            {{ $material->platform ?: $material->type }}
                                                                        </span>
                                                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500 dark:text-slate-400" title="Uploaded by {{ trim(($material->staff->profile->first_name ?? '') . ' ' . ($material->staff->profile->last_name ?? '')) ?: ($material->staff->username ?? 'Faculty') }}">
                                                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                            <span class="truncate max-w-[130px]">{{ trim(($material->staff->profile->first_name ?? '') . ' ' . ($material->staff->profile->last_name ?? '')) ?: ($material->staff->username ?? 'Faculty') }}</span>
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                @if($canDeleteMaterial)
                                                                <div class="shrink-0 flex items-center gap-1.5 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                                                    <button type="button" 
                                                                        data-material='@json($allocData)'
                                                                        onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-xs cursor-pointer" 
                                                                        title="Allocate / Change Division">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                    </button>

                                                                    <button type="button" 
                                                                        onclick="openUniversalDeleteModal({
                                                                            title: 'Delete Course Material',
                                                                            subtitle: 'Confirm learning material removal',
                                                                            itemName: '{{ addslashes($material->title) }}',
                                                                            itemCode: '{{ strtoupper($material->type) }}',
                                                                            itemBadge: '{{ ucfirst($material->platform ?: 'Material') }}',
                                                                            itemMeta: 'Course: {{ addslashes($course->code) }} - {{ addslashes($course->name) }}',
                                                                            cascadeItems: [
                                                                                { title: 'Material Type', count: '{{ ucfirst($material->type) }} Resource', icon: 'file' },
                                                                                { title: 'Storage Status', count: '{{ $material->type === 'file' ? 'Local File Deleted' : 'URL Removed' }}', icon: 'material' }
                                                                            ],
                                                                            warningTitle: 'Delete {{ addslashes($material->title) }}?',
                                                                            warningBody: 'Are you sure you want to permanently delete this learning resource from {{ addslashes($course->code) }}? If it is a file, it will be deleted from the server storage.',
                                                                            deleteUrl: '{{ $matDelUrl }}',
                                                                            submitBtnText: 'Yes, Delete Material'
                                                                        })"
                                                                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-xs cursor-pointer" 
                                                                        title="Delete Material">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            {{-- Full-width Title: Directly clickable to open material --}}
                                                            <a href="{{ $matOpenUrl }}" target="_blank" class="text-[15px] font-bold text-gray-900 dark:text-white leading-snug break-words tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors hover:underline block" title="Open {{ $material->title }}">
                                                                {{ $material->title }}
                                                            </a>

                                                            {{-- Division Tag / Quick Allocate Badge & Created By --}}
                                                            <div class="mt-2.5 flex items-center justify-between gap-2 flex-wrap">
                                                                @if($canDeleteMaterial)
                                                                    <button type="button"
                                                                        data-material='@json($allocData)'
                                                                        onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $catMeta['badge_class'] }} border hover:opacity-90 hover:scale-[1.02] transition-all cursor-pointer shadow-2xs"
                                                                        title="Click to allocate / relocate division">
                                                                        <span>{{ $catMeta['icon'] }}</span>
                                                                        <span>{{ $material->effective_division }}</span>
                                                                        <svg class="w-3 h-3 opacity-60 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                                    </button>
                                                                @else
                                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $catMeta['badge_class'] }} border shadow-2xs">
                                                                        <span>{{ $catMeta['icon'] }}</span>
                                                                        <span>{{ $material->effective_division }}</span>
                                                                    </span>
                                                                @endif

                                                                <div class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-600 dark:text-slate-300 bg-gray-50 dark:bg-[#1C2128] px-2.5 py-1 rounded-lg border border-gray-200/80 dark:border-[#273244]" title="Created By">
                                                                    <svg class="w-3 h-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                    <span class="text-gray-400 dark:text-slate-400 font-normal">Created by:</span>
                                                                    <span class="font-bold text-gray-800 dark:text-slate-200 truncate max-w-[130px]">{{ trim(($material->staff->profile->first_name ?? '') . ' ' . ($material->staff->profile->last_name ?? '')) ?: ($material->staff->username ?? 'Faculty') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="bg-gray-50/80 dark:bg-[#161B22] px-4 py-2.5 border-t border-gray-100 dark:border-[#273244] flex items-center justify-between gap-2">
                                                        @if($material->type === 'link')
                                                             <a href="{{ $material->url_or_path }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 py-1.5 rounded-lg transition-colors gap-1.5">
                                                                Open Link <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 py-1.5 rounded-lg transition-colors gap-1">
                                                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            </a>
                                                            <div class="w-px h-3.5 bg-gray-200 dark:bg-[#273244]"></div>
                                                            <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 py-1.5 rounded-lg transition-colors gap-1">
                                                                Download <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>

                                <!-- Layout 2: Structured Tabular Directory (Table View) -->
                                <div id="courseMaterialsTableWrap" class="hidden overflow-hidden bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200 dark:border-[#273244] shadow-2xs">
                                    <div class="overflow-x-auto">
                                        <table class="w-full divide-y divide-gray-200 dark:divide-[#273244] text-left">
                                            <thead class="bg-gray-50/90 dark:bg-[#1C2128] text-[11px] font-bold text-gray-600 dark:text-slate-300 uppercase tracking-wider">
                                                <tr>
                                                    <th class="px-5 py-3.5">Material Title & Format</th>
                                                    <th class="px-5 py-3.5">Division / Section</th>
                                                    <th class="px-5 py-3.5">Type</th>
                                                    <th class="px-5 py-3.5">Created By</th>
                                                    <th class="px-5 py-3.5 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 dark:divide-[#273244] bg-white dark:bg-[#151B23] text-xs">
                                                @foreach($materials as $material)
                                                    @php
                                                        $rawExt = strtolower(pathinfo($material->url_or_path, PATHINFO_EXTENSION));
                                                        $matCatKey = $material->effective_division;
                                                        $matMeta = $subfolderCategories[$matCatKey] ?? $subfolderCategories['Additional material'];
                                                        $matSlug = Str::slug($matCatKey);
                                                        $canDeleteMaterial = Auth::user()->role === 'sa'
                                                             || (Auth::user()->role === 'admin' && (!Auth::user()->profile || Auth::user()->profile->departments_id === $course->department_id))
                                                             || ($course->staff && $course->staff->contains(Auth::id()))
                                                             || $material->staff_id === Auth::id();
                                                        $matDelUrl = route('staff.courses.materials.destroy', $material->id);
                                                        $allocData = [
                                                            'id' => $material->id,
                                                            'title' => $material->title,
                                                            'division' => $material->effective_division,
                                                            'platform' => $material->platform,
                                                            'type' => $material->type,
                                                            'updateUrl' => route('staff.courses.materials.update', $material->id)
                                                        ];
                                                    @endphp
                                                    <tr class="hover:bg-indigo-50/40 dark:hover:bg-[#1C2128]/60 transition-colors material-table-row"
                                                        data-material-title="{{ strtolower($material->title) }}"
                                                        data-material-type="{{ strtolower($material->type) }}"
                                                        data-material-platform="{{ strtolower($material->platform) }}"
                                                        data-material-division="{{ strtolower($material->effective_division) }}"
                                                        data-material-category="{{ strtolower(($matMeta['name'] ?? '') . ' ' . ($matMeta['short_name'] ?? '') . ' ' . $matSlug . ' ' . $matCatKey) }}"
                                                        data-material-ext="{{ $rawExt }}"
                                                        data-material-staff="{{ strtolower($material->staff->profile->first_name ?? '') }}"
                                                        data-subfolder="{{ $matSlug }}">
                                                        <td class="px-5 py-3.5">
                                                            <div class="flex items-center gap-3">
                                                                <div class="shrink-0">
                                                                    @if($material->platform === 'youtube')
                                                                        <div class="h-8 w-8 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/50 flex items-center justify-center text-red-600 dark:text-red-400">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        </div>
                                                                    @elseif($material->platform === 'pdf')
                                                                        <div class="h-8 w-8 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-900/50 flex items-center justify-center text-rose-600 dark:text-rose-400">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                    @elseif(in_array($rawExt, ['ppt','pptx']))
                                                                        <div class="h-8 w-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                                                        </div>
                                                                    @else
                                                                        <div class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="min-w-0">
                                                                    <a href="{{ $material->type === 'link' ? $material->url_or_path : route('materials.view', $material->id) }}" target="_blank" class="font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors break-words block" title="Open {{ $material->title }}">
                                                                        {{ $material->title }}
                                                                    </a>
                                                                    <div class="flex items-center gap-1.5 mt-0.5">
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-100 dark:bg-[#1C2128] text-slate-700 dark:text-slate-300 border border-transparent dark:border-[#273244]">
                                                                            {{ $rawExt ? '.' . $rawExt : ($material->platform ?: $material->type) }}
                                                                        </span>
                                                                        <span class="text-[10px] text-gray-400 dark:text-slate-400 capitalize">{{ $material->platform ?: $material->type }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                                            @if($canDeleteMaterial)
                                                                <button type="button"
                                                                    data-material='@json($allocData)'
                                                                    onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-extrabold {{ $matMeta['badge_class'] }} border hover:opacity-85 hover:scale-[1.02] transition-all cursor-pointer shadow-2xs"
                                                                    title="Click to allocate / change division">
                                                                    <span>{{ $matMeta['icon'] }}</span>
                                                                    <span>{{ $matMeta['short_name'] }}</span>
                                                                    <svg class="w-3 h-3 opacity-50 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                                </button>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-extrabold {{ $matMeta['badge_class'] }} border">
                                                                    <span>{{ $matMeta['icon'] }}</span>
                                                                    <span>{{ $matMeta['short_name'] }}</span>
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                                            @if($material->type === 'link')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/40">
                                                                    🔗 Web Link
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40">
                                                                    📄 File Resource
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-5 py-3.5 whitespace-nowrap text-gray-700 dark:text-slate-300">
                                                            <div class="flex items-center gap-2">
                                                                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold border border-indigo-200 dark:border-indigo-800/60 shrink-0">
                                                                    {{ strtoupper(substr($material->staff->profile->first_name ?? ($material->staff->username ?? 'F'), 0, 1)) }}
                                                                </div>
                                                                <span class="font-semibold text-gray-800 dark:text-slate-200">
                                                                    {{ trim(($material->staff->profile->first_name ?? '') . ' ' . ($material->staff->profile->last_name ?? '')) ?: ($material->staff->username ?? 'Faculty') }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                                            <div class="flex items-center justify-center gap-1.5">
                                                                @if($material->type === 'link')
                                                                    <a href="{{ $material->url_or_path }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                        <span>Open</span>
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-2xs">
                                                                        <span>View</span>
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                                    </a>
                                                                    <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-[#273244] dark:hover:bg-[#384457] text-gray-700 dark:text-slate-200 border border-transparent dark:border-[#384457] rounded-lg text-xs font-bold transition-colors">
                                                                        <span>Download</span>
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                    </a>
                                                                @endif

                                                                @if($canDeleteMaterial)
                                                                    <button type="button"
                                                                        data-material='@json($allocData)'
                                                                        onclick="openAllocateDivisionModal(JSON.parse(this.getAttribute('data-material')))"
                                                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer shadow-2xs"
                                                                        title="Allocate / Edit Division">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                    </button>

                                                                    <button type="button"
                                                                        onclick="openUniversalDeleteModal({
                                                                            title: 'Delete Course Material',
                                                                            subtitle: 'Confirm learning material removal',
                                                                            itemName: '{{ addslashes($material->title) }}',
                                                                            itemCode: '{{ strtoupper($material->type) }}',
                                                                            itemBadge: '{{ ucfirst($material->platform ?: 'Material') }}',
                                                                            itemMeta: 'Course: {{ addslashes($course->code) }} - {{ addslashes($course->name) }}',
                                                                            cascadeItems: [
                                                                                { title: 'Material Type', count: '{{ ucfirst($material->type) }} Resource', icon: 'file' },
                                                                                { title: 'Storage Status', count: '{{ $material->type === 'file' ? 'Local File Deleted' : 'URL Removed' }}', icon: 'material' }
                                                                            ],
                                                                            warningTitle: 'Delete {{ addslashes($material->title) }}?',
                                                                            warningBody: 'Are you sure you want to permanently delete this learning resource from {{ addslashes($course->code) }}? If it is a file, it will be deleted from the server storage.',
                                                                            deleteUrl: '{{ $matDelUrl }}',
                                                                            submitBtnText: 'Yes, Delete Material'
                                                                        })"
                                                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer"
                                                                        title="Delete">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <!-- Dynamic No Matches Empty State for Live Search/Filter -->
                            <div id="materialsNoMatchState" class="py-14 px-6 text-center bg-white border border-dashed border-gray-200 rounded-2xl hidden">
                                <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">No Matching Learning Materials</h3>
                                <p class="text-xs text-gray-500 mt-1">No learning materials match your current search, type filter, or selected sub-folder.</p>
                                <button type="button" onclick="resetAllMaterialFilters()" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span>Reset Filters</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- TAB PANEL 2: ASSIGNMENTS & ASSESSMENTS                    -->
                <!-- ========================================================= -->
                <!-- TAB PANEL 2: COURSE ASSIGNMENTS & QUIZZES                  -->
                <!-- ========================================================= -->
                <div id="course-panel-assignments" role="tabpanel" aria-labelledby="course-tab-btn-assignments" class="hidden space-y-6">
                    <div class="bg-white dark:bg-[#151B23] p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-[#273244] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Course Assignments & Quizzes</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Manage, evaluate submissions, and publish assessments for {{ $course->name }}</p>
                        </div>
                        <a href="{{ route('staff.assignments.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs text-xs font-bold transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create New Assignment
                        </a>
                    </div>

                    @if($assignments->isEmpty())
                        <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-gray-100 dark:border-[#273244] p-12 text-center">
                            <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/60 rounded-2xl flex items-center justify-center mx-auto text-purple-600 dark:text-purple-400 mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">No Assignments For This Course</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400 max-w-sm mx-auto">Create MCQ assessments, questions, or assignments to evaluate student progress.</p>
                            <div class="mt-6">
                                <a href="{{ route('staff.assignments.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create First Assignment
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($assignments as $assignment)
                                <div class="bg-white dark:bg-[#151B23] rounded-2xl border border-gray-200/80 dark:border-[#273244] shadow-sm hover:shadow-md hover:border-purple-300 dark:hover:border-purple-600/60 transition-all flex flex-col justify-between overflow-hidden">
                                    <div class="h-1.5 bg-gradient-to-r from-purple-500 to-indigo-600"></div>

                                    <div class="p-6 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800/60 shadow-2xs">
                                                    {{ $course->code }}
                                                </span>
                                                @if($assignment->due_date)
                                                    <span class="inline-flex items-center text-xs font-medium {{ $assignment->isPastDue() ? 'text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/60 border border-red-200 dark:border-red-900/50' : 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60' }} px-2.5 py-0.5 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Due: {{ $assignment->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug mb-1">
                                                {{ $assignment->title }}
                                            </h3>

                                            @if($assignment->description)
                                                <p class="text-xs text-gray-600 dark:text-slate-300 line-clamp-2 mt-2 mb-4 bg-gray-50 dark:bg-[#1C2128] p-2.5 rounded-lg border border-gray-100 dark:border-[#273244]">
                                                    {{ $assignment->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Metrics Row -->
                                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 dark:border-[#273244] text-center mt-3">
                                            <div class="p-2 rounded-xl bg-gray-50 dark:bg-[#1C2128]">
                                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $assignment->max_marks }}</p>
                                                <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-slate-400">Max Marks</p>
                                            </div>
                                            <div class="p-2 rounded-xl bg-gray-50 dark:bg-[#1C2128]">
                                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $assignment->questions->count() }}</p>
                                                <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-slate-400">Questions</p>
                                            </div>
                                            <div class="p-2 rounded-xl bg-purple-50 dark:bg-purple-950/50 border border-purple-100 dark:border-purple-900/50">
                                                <p class="text-sm font-black text-purple-700 dark:text-purple-300">{{ $assignment->submissions_count }}</p>
                                                <p class="text-[10px] uppercase font-bold text-purple-500 dark:text-purple-400">Submitted</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Actions -->
                                    <div class="bg-gray-50/80 dark:bg-[#1C2128] px-5 py-3 border-t border-gray-100 dark:border-[#273244] flex items-center justify-between gap-2">
                                        <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-2xs text-xs font-bold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View / Grade
                                        </a>
                                        <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="inline-flex justify-center items-center px-3 py-2 bg-white dark:bg-[#151B23] border border-gray-300 dark:border-[#273244] hover:bg-gray-50 dark:hover:bg-[#273244] text-gray-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-colors" title="Edit Assignment">
                                            <svg class="w-3.5 h-3.5 text-gray-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ========================================================= -->
                <!-- TAB PANEL 3: COURSE OVERVIEW & ENROLLED STUDENTS           -->
                <!-- ========================================================= -->
                <div id="course-panel-overview" role="tabpanel" aria-labelledby="course-tab-btn-overview" class="hidden space-y-6">
                    
                    <!-- Course Academic Info Banner -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-gray-100 dark:border-[#273244] p-5 sm:p-6">
                        <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-gray-100 dark:border-[#273244]">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Academic Course Information</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400">Official curriculum, department, and semester specifications</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-gray-50/70 dark:bg-[#1C2128] p-3.5 rounded-xl border border-gray-100 dark:border-[#273244]">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400">Course Code & Name</p>
                                <p class="font-bold text-gray-900 dark:text-white text-sm mt-1 flex items-center gap-1.5 flex-wrap">
                                    <span class="font-mono text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded text-xs border border-indigo-100 dark:border-indigo-800/60">{{ $course->code }}</span>
                                    <span>{{ $course->name }}</span>
                                </p>
                            </div>

                            <div class="bg-gray-50/70 dark:bg-[#1C2128] p-3.5 rounded-xl border border-gray-100 dark:border-[#273244]">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400">Department</p>
                                <p class="font-bold text-gray-900 dark:text-white text-sm mt-1 truncate" title="{{ $course->department->name ?? 'N/A' }}">
                                    {{ $course->department->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50/70 dark:bg-[#1C2128] p-3.5 rounded-xl border border-gray-100 dark:border-[#273244]">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400">Regulation & Curriculum</p>
                                <p class="font-bold text-gray-900 dark:text-white text-sm mt-1">
                                    Regulation {{ $course->regulation->code ?? 'Standard' }}
                                    @if($course->regulation && $course->regulation->curriculum)
                                        <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">({{ $course->regulation->curriculum }})</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50/70 dark:bg-[#1C2128] p-3.5 rounded-xl border border-gray-100 dark:border-[#273244]">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400">Semester & Year</p>
                                <p class="font-bold text-gray-900 dark:text-white text-sm mt-1">
                                    Year {{ $course->year ?? 1 }}, Semester {{ $course->semester ?? 1 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Students List Card (Full Width) -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-gray-100 dark:border-[#273244] overflow-hidden">
                        <!-- Table Card Header -->
                        <div class="p-5 border-b border-gray-100 dark:border-[#273244] bg-gray-50/70 dark:bg-[#1C2128] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Enrolled Students</h3>
                                        <span id="enrolledCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                            {{ $course->enrollments->count() }} {{ Str::plural('Student', $course->enrollments->count()) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Students actively registered and participating in this course</p>
                                </div>
                            </div>
                            <div class="relative w-full sm:w-72">
                                <input type="text" id="studentSearchInput" oninput="filterEnrolledStudents(this.value)" placeholder="Search by name, roll no, email..." class="w-full pl-9 pr-3 py-2 text-xs bg-white dark:bg-[#151B23] border border-gray-300 dark:border-[#273244] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition-shadow shadow-xs font-medium">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80 dark:bg-[#1C2128] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-slate-400 border-b border-gray-200 dark:border-[#273244]">
                                        <th class="py-3.5 px-4 text-center w-12">#</th>
                                        <th class="py-3.5 px-4">Student Name</th>
                                        <th class="py-3.5 px-4">Roll Number / ID</th>
                                        <th class="py-3.5 px-4">Department / Program</th>
                                        <th class="py-3.5 px-4">Email Address</th>
                                        <th class="py-3.5 px-4">Contact</th>
                                        <th class="py-3.5 px-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-[#273244]" id="studentsTableBody">
                                    @forelse($course->enrollments as $index => $student)
                                    @php
                                        $firstName = $student->profile->first_name ?? '';
                                        $lastName = $student->profile->last_name ?? '';
                                        $fullName = trim($firstName . ' ' . $lastName);
                                        if (empty($fullName)) {
                                            $fullName = $student->username;
                                        }
                                        $email = $student->email ?: ($student->profile->email ?? null);
                                        $phone = $student->profile->phone ?? null;
                                        $deptName = $student->profile->department->name ?? ($student->profile->departments_id ?? ($course->department->name ?? '—'));
                                        $programName = $student->profile->program->name ?? ($student->profile->programs_id ?? null);
                                    @endphp
                                    <tr class="student-row hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-colors" data-student-search="{{ strtolower($fullName . ' ' . $student->username . ' ' . ($email ?? '') . ' ' . ($deptName ?? '') . ' ' . ($phone ?? '')) }}">
                                        <td class="py-3.5 px-4 text-center font-mono text-xs text-gray-400 dark:text-slate-500 font-semibold student-index">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                                    {{ substr($firstName ?: ($student->username ?: 'S'), 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-gray-900 dark:text-white block leading-tight">{{ $fullName }}</span>
                                                    <span class="text-[11px] text-gray-400 dark:text-slate-500 sm:hidden font-mono">{{ $student->username }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                                                {{ $student->username }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            <p class="font-medium text-gray-800 dark:text-slate-200">{{ $deptName }}</p>
                                            @if($programName)
                                                <p class="text-[11px] text-gray-400 dark:text-slate-400">{{ $programName }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            @if($email)
                                                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    {{ $email }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 dark:text-slate-500 italic">Not specified</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-gray-600 dark:text-slate-300">
                                            @if($phone)
                                                <span class="font-mono">{{ $phone }}</span>
                                            @else
                                                <span class="text-gray-400 dark:text-slate-500">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 shadow-2xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-gray-400 dark:text-slate-500">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-[#1C2128] flex items-center justify-center mx-auto mb-3 text-gray-400 dark:text-slate-500">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-600 dark:text-slate-300">No Students Enrolled</p>
                                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">There are currently no students registered for this course.</p>
                                        </td>
                                    </tr>
                                    @endforelse

                                    <!-- Filter No-Match State -->
                                    <tr id="no-students-matched-row" style="display: none;">
                                        <td colspan="7" class="py-10 text-center text-gray-500 dark:text-slate-400">
                                            <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mx-auto mb-2 font-bold text-sm">
                                                !
                                            </div>
                                            <p class="text-xs font-semibold text-gray-700 dark:text-slate-200">No matching students found</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-0.5">Try searching with a different name or roll number.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-gray-200 dark:border-[#273244] pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-slate-200">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Manage Materials Modal -->
    <div id="manageMaterialsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/70 backdrop-blur-sm p-4 overflow-y-auto transition-opacity" aria-labelledby="manageMaterialsModalTitle" aria-modal="true">
        <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden border border-gray-100 dark:border-[#273244] transform transition-all my-8" role="dialog">
            <!-- Modal Header -->
            <div class="modal-header px-6 py-5 sm:py-6 border-b border-gray-100 dark:border-[#273244] bg-slate-50/80 dark:bg-[#1C2128] flex justify-between items-center">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 id="manageMaterialsModalTitle" class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-tight">Manage Course Materials</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">{{ $course->code }} &bull; {{ $course->name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeManageMaterialsModal()" class="w-9 h-9 rounded-xl text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#273244] flex items-center justify-center transition-colors focus:outline-none cursor-pointer" aria-label="Close modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Segmented Switcher Controls (Link vs File) -->
            <div class="p-4 bg-gray-50/50 dark:bg-[#151B23] border-b border-gray-100 dark:border-[#273244]">
                <div class="modal-tab-track grid grid-cols-2 gap-2 p-1.5 bg-gray-200/70 dark:bg-[#0D1117] rounded-xl border border-transparent dark:border-[#273244]">
                    <button type="button" id="tab-link-btn" class="modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-bold rounded-lg transition-all shadow-xs bg-white dark:bg-[#273244] text-indigo-700 dark:text-indigo-300 border border-gray-200/50 dark:border-[#384457] cursor-pointer">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        <span>Web Link</span>
                    </button>
                    <button type="button" id="tab-file-btn" class="modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-[#161B22] border border-transparent cursor-pointer">
                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <span>Upload File</span>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Add Link Form -->
                <div id="tab-link-content" class="block">
                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="link">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Material Title <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                    <input type="text" name="title" value="{{ old('type') === 'link' ? old('title') : '' }}" placeholder="e.g., Lecture 1 Video / Unit 1 Notes" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror transition-all font-medium" required>
                                </div>
                                @if(old('type') === 'link')
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Material Division / Section <span class="text-red-500">*</span></label>
                                <select name="division" id="link-division-select" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('division') border-red-500 @enderror transition-all font-medium" required>
                                    <option value="">Select Division</option>
                                    <option value="Course objective and outcomes" {{ old('type') === 'link' && old('division') == 'Course objective and outcomes' ? 'selected' : '' }}>🎯 Course objective and outcomes</option>
                                    <option value="Syllabus document" {{ old('type') === 'link' && old('division') == 'Syllabus document' ? 'selected' : '' }}>📜 Syllabus document</option>
                                    <option value="PPTs" {{ old('type') === 'link' && old('division') == 'PPTs' ? 'selected' : '' }}>📊 PPTs</option>
                                    <option value="Lecture notes" {{ old('type') === 'link' && old('division') == 'Lecture notes' ? 'selected' : '' }}>📝 Lecture notes</option>
                                    <option value="Lab manual" {{ old('type') === 'link' && old('division') == 'Lab manual' ? 'selected' : '' }}>🧪 Lab manual</option>
                                    <option value="Assigment Questions" {{ old('type') === 'link' && old('division') == 'Assigment Questions' ? 'selected' : '' }}>📋 Assigment Questions</option>
                                    <option value="Question Bank" {{ old('type') === 'link' && old('division') == 'Question Bank' ? 'selected' : '' }}>🗂️ Question Bank</option>
                                    <option value="Gate Questions" {{ old('type') === 'link' && old('division') == 'Gate Questions' ? 'selected' : '' }}>🎓 Gate Questions</option>
                                    <option value="Previous Question Papers" {{ old('type') === 'link' && old('division') == 'Previous Question Papers' ? 'selected' : '' }}>📑 Previous Question Papers</option>
                                    <option value="Videos" {{ old('type') === 'link' && old('division') == 'Videos' ? 'selected' : '' }}>🎥 Videos</option>
                                    <option value="Additional material" {{ old('type') === 'link' && (old('division') == 'Additional material' || !old('division')) ? 'selected' : '' }}>📁 Additional material</option>
                                </select>
                                @if(old('type') === 'link')
                                    @error('division') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Platform / Source <span class="text-red-500">*</span></label>
                                <select name="platform" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('platform') border-red-500 @enderror transition-all font-medium" required>
                                    <option value="">Select Platform</option>
                                    <option value="Reference Portal" {{ old('type') === 'link' && old('platform') == 'Reference Portal' ? 'selected' : '' }}>🌐 Reference Portal / Academic Roadmap</option>
                                    <option value="youtube" {{ old('type') === 'link' && old('platform') == 'youtube' ? 'selected' : '' }}>▶️ YouTube Video</option>
                                    <option value="drive" {{ old('type') === 'link' && old('platform') == 'drive' ? 'selected' : '' }}>📁 Google Drive Folder / File</option>
                                    <option value="onedrive" {{ old('type') === 'link' && old('platform') == 'onedrive' ? 'selected' : '' }}>☁️ Microsoft OneDrive</option>
                                    <option value="website" {{ old('type') === 'link' && old('platform') == 'website' ? 'selected' : '' }}>🔗 External Website / Documentation</option>
                                </select>
                                @if(old('type') === 'link')
                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Direct URL <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    </div>
                                    <input type="url" name="url" value="{{ old('type') === 'link' ? old('url') : '' }}" placeholder="https://youtube.com/... or https://drive.google.com/..." class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('url') border-red-500 @enderror transition-all font-medium" required>
                                </div>
                                @if(old('type') === 'link')
                                    @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div class="pt-4 border-t border-gray-100 dark:border-[#273244] flex items-center justify-end gap-2.5">
                                <button type="button" onclick="closeManageMaterialsModal()" class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 dark:text-slate-300 bg-white dark:bg-[#1C2128] border border-gray-300 dark:border-[#273244] rounded-xl hover:bg-gray-50 dark:hover:bg-[#273244] transition-colors focus:outline-none cursor-pointer">Cancel</button>
                                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Save Link Material</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Upload File Form -->
                <div id="tab-file-content" class="hidden">
                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data" id="course-material-upload-form">
                        @csrf
                        <input type="hidden" name="type" value="file">
                        <input type="hidden" name="file_base64" id="file_base64">
                        <input type="hidden" name="file_name" id="file_name">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Document Title <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <input type="text" name="title" value="{{ old('type') === 'file' ? old('title') : '' }}" placeholder="e.g., Unit 1 Lecture Presentation" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror transition-all font-medium" required>
                                </div>
                                @if(old('type') === 'file')
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Material Division / Section <span class="text-red-500">*</span></label>
                                <select name="division" id="file-division-select" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('division') border-red-500 @enderror transition-all font-medium" required>
                                    <option value="">Select Division</option>
                                    <option value="Course objective and outcomes" {{ old('type') === 'file' && old('division') == 'Course objective and outcomes' ? 'selected' : '' }}>🎯 Course objective and outcomes</option>
                                    <option value="Syllabus document" {{ old('type') === 'file' && old('division') == 'Syllabus document' ? 'selected' : '' }}>📜 Syllabus document</option>
                                    <option value="PPTs" {{ old('type') === 'file' && old('division') == 'PPTs' ? 'selected' : '' }}>📊 PPTs</option>
                                    <option value="Lecture notes" {{ old('type') === 'file' && old('division') == 'Lecture notes' ? 'selected' : '' }}>📝 Lecture notes</option>
                                    <option value="Lab manual" {{ old('type') === 'file' && old('division') == 'Lab manual' ? 'selected' : '' }}>🧪 Lab manual</option>
                                    <option value="Assigment Questions" {{ old('type') === 'file' && old('division') == 'Assigment Questions' ? 'selected' : '' }}>📋 Assigment Questions</option>
                                    <option value="Question Bank" {{ old('type') === 'file' && old('division') == 'Question Bank' ? 'selected' : '' }}>🗂️ Question Bank</option>
                                    <option value="Gate Questions" {{ old('type') === 'file' && old('division') == 'Gate Questions' ? 'selected' : '' }}>🎓 Gate Questions</option>
                                    <option value="Previous Question Papers" {{ old('type') === 'file' && old('division') == 'Previous Question Papers' ? 'selected' : '' }}>📑 Previous Question Papers</option>
                                    <option value="Videos" {{ old('type') === 'file' && old('division') == 'Videos' ? 'selected' : '' }}>🎥 Videos</option>
                                    <option value="Additional material" {{ old('type') === 'file' && (old('division') == 'Additional material' || !old('division')) ? 'selected' : '' }}>📁 Additional material</option>
                                </select>
                                @if(old('type') === 'file')
                                    @error('division') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Document Format <span class="text-red-500">*</span></label>
                                <select name="platform" id="file-type-select" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('platform') border-red-500 @enderror transition-all font-medium" required>
                                    <option value="">Select Document Format</option>
                                    <option value="pdf" {{ old('type') === 'file' && old('platform') == 'pdf' ? 'selected' : '' }}>📕 PDF Document (.pdf)</option>
                                    <option value="word" {{ old('type') === 'file' && old('platform') == 'word' ? 'selected' : '' }}>📘 Word Document (.doc, .docx)</option>
                                    <option value="excel" {{ old('type') === 'file' && old('platform') == 'excel' ? 'selected' : '' }}>📊 Excel Spreadsheet (.xls, .xlsx, .csv)</option>
                                    <option value="ppt" {{ old('type') === 'file' && old('platform') == 'ppt' ? 'selected' : '' }}>📙 PowerPoint Presentation (.ppt, .pptx)</option>
                                    <option value="txt" {{ old('type') === 'file' && old('platform') == 'txt' ? 'selected' : '' }}>📄 Text Document (.txt)</option>
                                </select>
                                @if(old('type') === 'file')
                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300">Choose File <span class="text-red-500">*</span></label>
                                    <span class="text-[11px] font-semibold text-gray-400 dark:text-slate-500">Max 25MB</span>
                                </div>
                                
                                <div id="file-dropzone-container" class="border-2 border-dashed border-gray-300 dark:border-[#273244] hover:border-indigo-400 dark:hover:border-indigo-500/70 rounded-2xl p-5 text-center bg-gray-50/50 dark:bg-[#1C2128]/50 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 transition-all cursor-pointer relative group">
                                    <div class="flex flex-col items-center justify-center">
                                        <div id="file-icon-preview" class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-2 shadow-2xs border border-indigo-100 dark:border-indigo-800/50 transition-transform">
                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p id="file-select-title" class="text-xs font-bold text-gray-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Click to select document</p>
                                        <p id="file-select-subtitle" class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5">Supports PDF, DOCX, PPTX, XLSX, TXT, CSV, ZIP (Max 25MB)</p>
                                    </div>
                                    <input type="file" name="file" id="file-upload-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.pps,.ppsx,.xls,.xlsx,.csv,.txt,.zip,.rar,.png,.jpg,.jpeg,.webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>

                                <!-- Dynamic In-Modal File Size Error Alert Banner -->
                                <div id="file-size-error-banner" class="hidden mt-3 p-3.5 bg-red-50 dark:bg-red-950/40 border-2 border-red-200 dark:border-red-900/50 rounded-xl flex items-start gap-3 text-red-800 dark:text-red-300 transition-all shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0 mt-0.5 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-red-900 dark:text-red-200" id="file-size-error-title">File Size Exceeds 25MB Limit</p>
                                        <p class="text-[11px] font-medium text-red-700 dark:text-red-300 mt-0.5 leading-relaxed" id="file-size-error-msg">The selected file exceeds the maximum allowed limit of 25MB. Please choose a smaller file.</p>
                                    </div>
                                    <button type="button" onclick="dismissFileSizeError()" class="text-red-400 hover:text-red-700 dark:hover:text-red-200 p-1 rounded-md transition-colors cursor-pointer" title="Dismiss">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                @if(old('type') === 'file')
                                    @error('file')
                                        <div class="mt-3 p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 rounded-xl flex items-center gap-2 text-xs text-red-700 dark:text-red-300 font-semibold">
                                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                @endif
                            </div>
                            <div class="pt-4 border-t border-gray-100 dark:border-[#273244] flex items-center justify-end gap-2.5">
                                <button type="button" onclick="closeManageMaterialsModal()" class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 dark:text-slate-300 bg-white dark:bg-[#1C2128] border border-gray-300 dark:border-[#273244] rounded-xl hover:bg-gray-50 dark:hover:bg-[#273244] transition-colors focus:outline-none cursor-pointer">Cancel</button>
                                <button type="submit" id="submit-upload-btn" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span>Upload Document</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Change Password</h3>
                <button type="button" id="closePasswordModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none" aria-label="Close Password Modal">
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

    <!-- Allocate / Edit Division Modal -->
    <div id="allocateDivisionModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900/70 backdrop-blur-sm p-4 overflow-y-auto transition-opacity" aria-labelledby="allocateDivisionModalTitle" aria-modal="true">
        <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 dark:border-[#273244] transform transition-all my-8" role="dialog">
            <!-- Modal Header -->
            <div class="modal-header px-6 py-5 sm:py-6 border-b border-gray-100 dark:border-[#273244] bg-slate-50/80 dark:bg-[#1C2128] flex justify-between items-center">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 id="allocateDivisionModalTitle" class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-tight">Allocate / Reallocate Material</h3>
                        <p id="allocate-material-subtitle" class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Assign or update the division section for this material</p>
                    </div>
                </div>
                <button type="button" onclick="closeAllocateDivisionModal()" class="w-9 h-9 rounded-xl text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#273244] flex items-center justify-center transition-colors focus:outline-none cursor-pointer" aria-label="Close modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form id="allocateDivisionForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="allocate-material-id" name="id" value="">

                <div class="p-6 space-y-4">
                    <!-- Material Title Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Material Title <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <input type="text" id="allocate-title-input" name="title" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium" required>
                        </div>
                    </div>

                    <!-- Material Division Select -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Material Division / Section <span class="text-red-500">*</span></label>
                        <select id="allocate-division-select" name="division" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-white dark:bg-[#1C2128] text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium shadow-2xs" required>
                            <option value="">-- Select Division --</option>
                            <option value="Course objective and outcomes">🎯 Course objective and outcomes</option>
                            <option value="Syllabus document">📜 Syllabus document</option>
                            <option value="PPTs">📊 PPTs</option>
                            <option value="Lecture notes">📝 Lecture notes</option>
                            <option value="Lab manual">🧪 Lab manual</option>
                            <option value="Assigment Questions">📋 Assigment Questions</option>
                            <option value="Question Bank">🗂️ Question Bank</option>
                            <option value="Gate Questions">🎓 Gate Questions</option>
                            <option value="Previous Question Papers">📑 Previous Question Papers</option>
                            <option value="Videos">🎥 Videos</option>
                            <option value="Additional material">📁 Additional material</option>
                        </select>
                        <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1">Select which section this resource belongs to so students and faculty can find it organized properly.</p>
                    </div>

                    <!-- Platform / Source Selection (Optional) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 mb-1.5">Platform / Format</label>
                        <input type="text" id="allocate-platform-select" name="platform" placeholder="e.g. pdf, ppt, youtube, website" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 dark:border-[#273244] bg-gray-50/50 dark:bg-[#1C2128] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium">
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-[#273244] flex items-center justify-end gap-2.5">
                        <button type="button" onclick="closeAllocateDivisionModal()" class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-gray-700 dark:text-slate-300 bg-white dark:bg-[#1C2128] border border-gray-300 dark:border-[#273244] rounded-xl hover:bg-gray-50 dark:hover:bg-[#273244] focus:outline-none transition-colors cursor-pointer">Cancel</button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Save Allocation</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Allocate Division Modal Open / Close Handlers
        window.openAllocateDivisionModal = function(data) {
            const modal = document.getElementById('allocateDivisionModal');
            const form = document.getElementById('allocateDivisionForm');
            const titleInput = document.getElementById('allocate-title-input');
            const divisionSelect = document.getElementById('allocate-division-select');
            const platformInput = document.getElementById('allocate-platform-select');
            const idInput = document.getElementById('allocate-material-id');
            const subtitleEl = document.getElementById('allocate-material-subtitle');

            if (!modal || !form) return;

            if (data.updateUrl) {
                form.action = data.updateUrl;
            }
            if (idInput && data.id) idInput.value = data.id;
            if (titleInput && data.title) titleInput.value = data.title;
            if (divisionSelect && data.division) divisionSelect.value = data.division;
            if (platformInput) platformInput.value = data.platform || '';
            if (subtitleEl) {
                subtitleEl.textContent = `Type: ${(data.type || 'Material').toUpperCase()} • Format: ${data.platform || 'Standard'}`;
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                if (divisionSelect) divisionSelect.focus();
            }, 50);
        };

        window.closeAllocateDivisionModal = function() {
            const modal = document.getElementById('allocateDivisionModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        // Close Allocate Modal on backdrop click
        document.getElementById('allocateDivisionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeAllocateDivisionModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeAllocateDivisionModal();
                window.closeManageMaterialsModal();
            }
        });

        // Manage Materials Modal Open / Close Handlers
        window.openManageMaterialsModal = function() {
            const modal = document.getElementById('manageMaterialsModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        };

        window.closeManageMaterialsModal = function() {
            const modal = document.getElementById('manageMaterialsModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        // Close on backdrop click
        document.getElementById('manageMaterialsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeManageMaterialsModal();
            }
        });

        @if($errors->has('title') || $errors->has('platform') || $errors->has('url') || $errors->has('file') || old('type') || session('error'))
            window.addEventListener('DOMContentLoaded', function() {
                window.openManageMaterialsModal();
                @if($errors->has('file') || old('type') === 'file' || (session('error') && (str_contains(session('error'), 'file') || str_contains(session('error'), '25MB'))))
                    const fileBtn = document.getElementById('tab-file-btn');
                    if (fileBtn) fileBtn.click();
                @endif
            });
        @endif

        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Cooperative Tab Switcher (Materials, Assignments, Overview)
        window.switchCourseTab = function(tabKey) {
            const panels = {
                materials: document.getElementById('course-panel-materials'),
                assignments: document.getElementById('course-panel-assignments'),
                overview: document.getElementById('course-panel-overview')
            };

            const metricCards = {
                materials: document.getElementById('metric-card-materials'),
                assignments: document.getElementById('metric-card-assignments'),
                overview: document.getElementById('metric-card-overview')
            };

            Object.keys(panels).forEach(key => {
                if (key === tabKey) {
                    if (panels[key]) panels[key].classList.remove('hidden');
                    if (metricCards[key]) {
                        metricCards[key].classList.add('is-active-tab');
                    }
                    window.history.replaceState(null, null, '#' + key);
                } else {
                    if (panels[key]) panels[key].classList.add('hidden');
                    if (metricCards[key]) {
                        metricCards[key].classList.remove('is-active-tab');
                    }
                }
            });
        };

        // Sub-Folder State & Quick Selection
        let activeSubfolderFilter = 'all';

        window.selectSubfolder = function(catKey) {
            activeSubfolderFilter = catKey;
            
            // Update Tab Active Styles
            document.querySelectorAll('.subfolder-tab-btn').forEach(btn => {
                const targetCat = btn.getAttribute('data-subfolder-target');
                const badge = btn.querySelector('.tab-badge');
                if (targetCat === catKey) {
                    btn.className = 'subfolder-tab-btn inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-indigo-600 text-white shadow-xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/20 text-white';
                } else {
                    btn.className = 'subfolder-tab-btn inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 hover:text-gray-900 bg-white border border-gray-200 hover:border-gray-300 transition-all shadow-2xs cursor-pointer';
                    if (badge) badge.className = 'tab-badge px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700';
                }
            });

            // Automatically open / expand the selected section directly!
            if (catKey !== 'all') {
                const section = document.getElementById('subfolder-section-' + catKey);
                if (section) {
                    const body = section.querySelector('.subfolder-content-body');
                    const chevron = section.querySelector('.subfolder-chevron');
                    const toggleText = section.querySelector('.subfolder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                }
                allFoldersExpanded = true;
                const btnText = document.getElementById('toggleAllSubfoldersText');
                const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
            } else {
                allFoldersExpanded = true;
                const btnText = document.getElementById('toggleAllSubfoldersText');
                const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
                if (btnText) btnText.textContent = 'Collapse All';
                if (btnIcon) btnIcon.classList.remove('rotate-180');
                document.querySelectorAll('.subfolder-section').forEach(section => {
                    const body = section.querySelector('.subfolder-content-body');
                    const chevron = section.querySelector('.subfolder-chevron');
                    const toggleText = section.querySelector('.subfolder-toggle-text');
                    if (body) body.classList.remove('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                    if (toggleText) toggleText.textContent = 'Collapse';
                });
            }

            window.filterMaterialsLive();
        };

        window.toggleSubfolder = function(catKey) {
            const section = document.getElementById('subfolder-section-' + catKey);
            if (!section) return;
            const body = section.querySelector('.subfolder-content-body');
            const chevron = section.querySelector('.subfolder-chevron');
            const toggleText = section.querySelector('.subfolder-toggle-text');
            
            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
                if (toggleText) toggleText.textContent = 'Collapse';
            } else {
                body.classList.add('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                if (toggleText) toggleText.textContent = 'Expand';
            }
        };

        let allFoldersExpanded = true;
        window.toggleAllSubfolders = function() {
            allFoldersExpanded = !allFoldersExpanded;
            const btnText = document.getElementById('toggleAllSubfoldersText');
            const btnIcon = document.getElementById('toggleAllSubfoldersIcon');
            if (btnText) btnText.textContent = allFoldersExpanded ? 'Collapse All' : 'Expand All';
            if (btnIcon) {
                if (allFoldersExpanded) btnIcon.classList.remove('rotate-180');
                else btnIcon.classList.add('rotate-180');
            }

            document.querySelectorAll('.subfolder-section').forEach(section => {
                const body = section.querySelector('.subfolder-content-body');
                const chevron = section.querySelector('.subfolder-chevron');
                const toggleText = section.querySelector('.subfolder-toggle-text');
                if (body && chevron && toggleText) {
                    if (allFoldersExpanded) {
                        body.classList.remove('hidden');
                        chevron.classList.remove('rotate-180');
                        toggleText.textContent = 'Collapse';
                    } else {
                        body.classList.add('hidden');
                        chevron.classList.add('rotate-180');
                        toggleText.textContent = 'Expand';
                    }
                }
            });
        };

        // Course Materials Layout State (Cards vs Table)
        let currentCourseMaterialsLayout = 'cards';

        window.setCourseMaterialsLayout = function(layout) {
            currentCourseMaterialsLayout = layout;
            const btnCards = document.getElementById('course-mat-btn-cards');
            const btnTable = document.getElementById('course-mat-btn-table');
            const cardsWrap = document.getElementById('courseMaterialsCardsWrap');
            const tableWrap = document.getElementById('courseMaterialsTableWrap');
            const collapseBtn = document.getElementById('toggleAllSubfoldersBtn');
            const iconCards = btnCards?.querySelector('svg');
            const iconTable = btnTable?.querySelector('svg');

            if (layout === 'cards') {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');

                if (cardsWrap) cardsWrap.classList.remove('hidden');
                if (tableWrap) tableWrap.classList.add('hidden');
                if (collapseBtn) collapseBtn.classList.remove('hidden');
            } else {
                if (btnCards) btnCards.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition-all cursor-pointer flex items-center gap-1.5';
                if (btnTable) btnTable.className = 'px-2.5 py-1 rounded-lg text-xs font-bold transition-all bg-indigo-50 text-indigo-700 border border-indigo-200 cursor-pointer flex items-center gap-1.5';
                if (iconCards) iconCards.setAttribute('class', 'w-3.5 h-3.5 text-gray-400');
                if (iconTable) iconTable.setAttribute('class', 'w-3.5 h-3.5 text-indigo-600');

                if (cardsWrap) cardsWrap.classList.add('hidden');
                if (tableWrap) tableWrap.classList.remove('hidden');
                if (collapseBtn) collapseBtn.classList.add('hidden');
            }

            window.filterMaterialsLive();
        };

        // Live Real-Time Course Materials Filter
        window.filterMaterialsLive = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            const clearBtn = document.getElementById('materialsSearchClearBtn');
            const resetBtn = document.getElementById('materialsResetFilterBtn');
            const noMatchBox = document.getElementById('materialsNoMatchState');
            const countBadge = document.getElementById('materialsCountBadge');

            const searchQuery = (searchInput?.value || '').toLowerCase().trim();
            const selectedType = (typeSelect?.value || '').toLowerCase().trim();

            if (clearBtn) {
                if (searchQuery.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            if (resetBtn) {
                if (searchQuery.length > 0 || selectedType !== '' || activeSubfolderFilter !== 'all') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }

            const cards = document.querySelectorAll('.material-card');
            let overallVisibleCount = 0;
            const totalCount = cards.length;

            // 1. Filter each individual card
            cards.forEach(card => {
                const title = card.getAttribute('data-material-title') || '';
                const type = card.getAttribute('data-material-type') || '';
                const platform = card.getAttribute('data-material-platform') || '';
                const staff = card.getAttribute('data-material-staff') || '';
                const cardFolder = card.getAttribute('data-subfolder') || '';
                const division = card.getAttribute('data-material-division') || '';
                const category = card.getAttribute('data-material-category') || '';
                const ext = card.getAttribute('data-material-ext') || '';

                const matchesSearch = searchQuery === '' || 
                                      title.includes(searchQuery) || 
                                      platform.includes(searchQuery) || 
                                      division.includes(searchQuery) || 
                                      category.includes(searchQuery) || 
                                      ext.includes(searchQuery) || 
                                      staff.includes(searchQuery);

                const matchesType = selectedType === '' || type === selectedType;
                const matchesFolder = (activeSubfolderFilter === 'all') || (cardFolder === activeSubfolderFilter);

                if (matchesSearch && matchesType && matchesFolder) {
                    card.style.display = '';
                    overallVisibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Filter each table row
            document.querySelectorAll('.material-table-row').forEach(row => {
                const title = row.getAttribute('data-material-title') || '';
                const type = row.getAttribute('data-material-type') || '';
                const platform = row.getAttribute('data-material-platform') || '';
                const staff = row.getAttribute('data-material-staff') || '';
                const cardFolder = row.getAttribute('data-subfolder') || '';
                const division = row.getAttribute('data-material-division') || '';
                const category = row.getAttribute('data-material-category') || '';
                const ext = row.getAttribute('data-material-ext') || '';

                const matchesSearch = searchQuery === '' || 
                                      title.includes(searchQuery) || 
                                      platform.includes(searchQuery) || 
                                      division.includes(searchQuery) || 
                                      category.includes(searchQuery) || 
                                      ext.includes(searchQuery) || 
                                      staff.includes(searchQuery);

                const matchesType = selectedType === '' || type === selectedType;
                const matchesFolder = (activeSubfolderFilter === 'all') || (cardFolder === activeSubfolderFilter);

                if (matchesSearch && matchesType && matchesFolder) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // 2. Update each subfolder section visibility and badge
            document.querySelectorAll('.subfolder-section').forEach(section => {
                const sectionFolderKey = section.getAttribute('data-folder-key');
                if (activeSubfolderFilter !== 'all' && activeSubfolderFilter !== sectionFolderKey) {
                    section.style.display = 'none';
                    return;
                }

                const sectionCards = section.querySelectorAll('.material-card');
                let sectionVisibleCount = 0;
                sectionCards.forEach(c => {
                    if (c.style.display !== 'none') sectionVisibleCount++;
                });

                const badgeEl = section.querySelector('.subfolder-count-badge');
                if (badgeEl) {
                    if (searchQuery !== '' || selectedType !== '') {
                        badgeEl.textContent = `${sectionVisibleCount} of ${sectionCards.length} Shown`;
                    } else {
                        badgeEl.textContent = `${sectionCards.length} ${sectionCards.length === 1 ? 'Material' : 'Materials'}`;
                    }
                }

                if (sectionVisibleCount > 0) {
                    section.style.display = '';
                } else {
                    section.style.display = 'none';
                }
            });

            if (noMatchBox) {
                if (overallVisibleCount === 0 && totalCount > 0) {
                    noMatchBox.classList.remove('hidden');
                } else {
                    noMatchBox.classList.add('hidden');
                }
            }

            if (countBadge) {
                if (searchQuery !== '' || selectedType !== '' || activeSubfolderFilter !== 'all') {
                    countBadge.textContent = `${overallVisibleCount} of ${totalCount} Shown`;
                } else {
                    countBadge.textContent = `${totalCount} Total`;
                }
            }
        };

        window.clearMaterialsSearch = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            window.filterMaterialsLive();
        };

        window.resetAllMaterialFilters = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            if (searchInput) searchInput.value = '';
            if (typeSelect) typeSelect.value = '';
            window.selectSubfolder('all');
        };

        // Enrolled Students Filter
        window.filterEnrolledStudents = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.student-row');
            const noMatchRow = document.getElementById('no-students-matched-row');
            const badge = document.getElementById('enrolledCountBadge');
            let visibleCount = 0;

            rows.forEach((row) => {
                const searchData = (row.getAttribute('data-student-search') || '').toLowerCase();
                const isMatch = cleanQuery === '' || searchData.includes(cleanQuery);
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) {
                    visibleCount++;
                    const indexEl = row.querySelector('.student-index');
                    if (indexEl) indexEl.textContent = visibleCount;
                }
            });

            if (noMatchRow) {
                noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }

            if (badge) {
                if (cleanQuery !== '') {
                    badge.textContent = `${visibleCount} of ${rows.length} Shown`;
                } else {
                    badge.textContent = `${rows.length} ${rows.length === 1 ? 'Student' : 'Students'}`;
                }
            }
        };

        // Tab switching logic for material upload forms (Add Link vs Upload Document)
        const tabLinkBtn = document.getElementById('tab-link-btn');
        const tabFileBtn = document.getElementById('tab-file-btn');
        const tabLinkContent = document.getElementById('tab-link-content');
        const tabFileContent = document.getElementById('tab-file-content');

        if(tabLinkBtn && tabFileBtn) {
            const setUploadModalTab = (activeType) => {
                const isLink = activeType === 'link';
                
                // Link Tab
                tabLinkBtn.className = isLink 
                    ? 'modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-bold rounded-lg transition-all shadow-xs bg-white dark:bg-[#273244] text-indigo-700 dark:text-indigo-300 border border-gray-200/50 dark:border-[#384457] cursor-pointer'
                    : 'modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-[#161B22] border border-transparent cursor-pointer';
                const linkSvg = tabLinkBtn.querySelector('svg');
                if (linkSvg) linkSvg.setAttribute('class', isLink ? 'w-4 h-4 text-indigo-600 dark:text-indigo-400' : 'w-4 h-4 text-gray-500 dark:text-slate-400');

                // File Tab
                tabFileBtn.className = !isLink 
                    ? 'modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-bold rounded-lg transition-all shadow-xs bg-white dark:bg-[#273244] text-indigo-700 dark:text-indigo-300 border border-gray-200/50 dark:border-[#384457] cursor-pointer'
                    : 'modal-tab-btn flex items-center justify-center gap-2 py-2.5 px-4 text-xs sm:text-sm font-semibold rounded-lg transition-all text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-[#161B22] border border-transparent cursor-pointer';
                const fileSvg = tabFileBtn.querySelector('svg');
                if (fileSvg) fileSvg.setAttribute('class', !isLink ? 'w-4 h-4 text-indigo-600 dark:text-indigo-400' : 'w-4 h-4 text-gray-500 dark:text-slate-400');

                if (isLink) {
                    tabFileContent?.classList.add('hidden');
                    tabFileContent?.classList.remove('block');
                    tabLinkContent?.classList.add('block');
                    tabLinkContent?.classList.remove('hidden');
                } else {
                    tabLinkContent?.classList.add('hidden');
                    tabLinkContent?.classList.remove('block');
                    tabFileContent?.classList.add('block');
                    tabFileContent?.classList.remove('hidden');
                }
            };

            tabLinkBtn.addEventListener('click', () => setUploadModalTab('link'));
            tabFileBtn.addEventListener('click', () => setUploadModalTab('file'));
            
            @if(old('type') === 'file')
                setUploadModalTab('file');
            @else
                setUploadModalTab('link');
            @endif
        }

        // File type detection, size validation, and icon preview
        const fileInput = document.getElementById('file-upload-input');
        const fileTypeSelect = document.getElementById('file-type-select');
        const fileIconPreview = document.getElementById('file-icon-preview');
        const fileBase64Input = document.getElementById('file_base64');
        const fileNameInput = document.getElementById('file_name');
        const fileDropzone = document.getElementById('file-dropzone-container');
        const fileSelectTitle = document.getElementById('file-select-title');
        const fileSelectSubtitle = document.getElementById('file-select-subtitle');
        const fileSizeErrorBanner = document.getElementById('file-size-error-banner');
        const fileSizeErrorTitle = document.getElementById('file-size-error-title');
        const fileSizeErrorMsg = document.getElementById('file-size-error-msg');
        const uploadForm = document.getElementById('course-material-upload-form');

        window.dismissFileSizeError = function() {
            if (fileSizeErrorBanner) fileSizeErrorBanner.classList.add('hidden');
            if (fileDropzone) {
                fileDropzone.classList.remove('border-red-400', 'bg-red-50/40', 'ring-2', 'ring-red-300');
                fileDropzone.classList.add('border-gray-300', 'bg-gray-50/50');
            }
        };

        function resetFileSelectionState() {
            if (fileInput) fileInput.value = '';
            if (fileBase64Input) fileBase64Input.value = '';
            if (fileNameInput) fileNameInput.value = '';
            if (fileSelectTitle) {
                fileSelectTitle.textContent = 'Click to select document';
                fileSelectTitle.className = 'text-xs font-bold text-gray-700 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors';
            }
            if (fileSelectSubtitle) {
                fileSelectSubtitle.textContent = 'Supports PDF, DOCX, PPTX, XLSX, CSV, ZIP (Max 25MB)';
                fileSelectSubtitle.className = 'text-[10px] text-gray-400 dark:text-slate-500 mt-0.5';
            }
            if (fileIconPreview) {
                fileIconPreview.innerHTML = '<svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>';
                fileIconPreview.className = 'w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-2 shadow-2xs border border-indigo-100 dark:border-indigo-800/50 transition-transform';
            }
        }

        let isFileReaderBusy = false;

        function processSelectedFile(file) {
            if (!file) {
                resetFileSelectionState();
                window.dismissFileSizeError();
                return;
            }

            // Strict 25MB Check (26,214,400 bytes)
            const maxBytes = 25 * 1024 * 1024;
            if (file.size > maxBytes) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                resetFileSelectionState();

                if (fileSizeErrorTitle) {
                    fileSizeErrorTitle.textContent = `File Size (${fileSizeMB} MB) Exceeds 25MB Limit`;
                }
                if (fileSizeErrorMsg) {
                    fileSizeErrorMsg.innerHTML = `The selected file <span class="font-bold text-red-900 dark:text-red-300 break-all">"${file.name}"</span> is <span class="font-bold text-red-900 dark:text-red-300">${fileSizeMB} MB</span>. Maximum allowed file size is <span class="font-bold text-red-900 dark:text-red-300">25 MB</span>. Please choose a smaller file.`;
                }
                if (fileSizeErrorBanner) {
                    fileSizeErrorBanner.classList.remove('hidden');
                }
                if (fileDropzone) {
                    fileDropzone.classList.remove('border-gray-300', 'bg-gray-50/50', 'hover:border-indigo-400');
                    fileDropzone.classList.add('border-red-400', 'bg-red-50/40', 'ring-2', 'ring-red-300');
                }
                return;
            }

            // File size valid (<= 25MB)
            window.dismissFileSizeError();
            if (fileNameInput) fileNameInput.value = file.name;

            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            if (fileSelectTitle) {
                fileSelectTitle.textContent = file.name;
                fileSelectTitle.className = 'text-xs font-bold text-indigo-700 dark:text-indigo-300 truncate max-w-[280px] sm:max-w-md';
            }
            if (fileSelectSubtitle) {
                fileSelectSubtitle.textContent = `File Size: ${fileSizeMB} MB • Reading file into buffer...`;
                fileSelectSubtitle.className = 'text-[11px] font-semibold text-amber-600 dark:text-amber-400 mt-0.5 animate-pulse';
            }

            const filename = file.name.toLowerCase();
            let type = '';
            let iconSvg = '';

            if (filename.endsWith('.pdf')) {
                type = 'pdf';
                iconSvg = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
            } else if (filename.endsWith('.doc') || filename.endsWith('.docx')) {
                type = 'word';
                iconSvg = '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
            } else if (filename.endsWith('.xls') || filename.endsWith('.xlsx') || filename.endsWith('.csv')) {
                type = 'excel';
                iconSvg = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
            } else if (filename.endsWith('.ppt') || filename.endsWith('.pptx') || filename.endsWith('.pps') || filename.endsWith('.ppsx')) {
                type = 'ppt';
                iconSvg = '<svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>';
            } else if (filename.endsWith('.txt')) {
                type = 'txt';
                iconSvg = '<svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
            }

            if (type && fileTypeSelect) {
                fileTypeSelect.value = type;
            }
            
            if (fileIconPreview) {
                if (iconSvg) {
                    fileIconPreview.innerHTML = iconSvg;
                } else {
                    fileIconPreview.innerHTML = '<svg class="w-6 h-6 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                }
                fileIconPreview.classList.remove('hidden');
                fileIconPreview.classList.add('flex');
            }

            // Immediately convert selected file to Base64 to bypass server temp folder locks
            isFileReaderBusy = true;
            const reader = new FileReader();
            reader.onload = function(evt) {
                isFileReaderBusy = false;
                if (fileBase64Input) {
                    fileBase64Input.value = evt.target.result;
                }
                if (fileSelectSubtitle) {
                    fileSelectSubtitle.textContent = `File Size: ${fileSizeMB} MB • Ready to upload ✓`;
                    fileSelectSubtitle.className = 'text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-0.5';
                }
            };
            reader.onerror = function() {
                isFileReaderBusy = false;
                if (fileBase64Input) fileBase64Input.value = '';
                if (fileSelectSubtitle) {
                    fileSelectSubtitle.textContent = `File Size: ${fileSizeMB} MB • Ready (multipart mode)`;
                    fileSelectSubtitle.className = 'text-[11px] font-semibold text-indigo-600 mt-0.5';
                }
            };
            reader.readAsDataURL(file);
        }

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
                processSelectedFile(file);
            });
        }

        // Drag & Drop handlers on dropzone
        if (fileDropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                fileDropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileDropzone.classList.remove('border-gray-300', 'bg-gray-50/50');
                    fileDropzone.classList.add('border-indigo-500', 'bg-indigo-50/70', 'ring-2', 'ring-indigo-300');
                });
            });

            ['dragleave', 'dragend'].forEach(eventName => {
                fileDropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/70', 'ring-2', 'ring-indigo-300');
                    fileDropzone.classList.add('border-gray-300', 'bg-gray-50/50');
                });
            });

            fileDropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileDropzone.classList.remove('border-indigo-500', 'bg-indigo-50/70', 'ring-2', 'ring-indigo-300');
                fileDropzone.classList.add('border-gray-300', 'bg-gray-50/50');

                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                    const droppedFile = e.dataTransfer.files[0];
                    if (fileInput) {
                        fileInput.files = e.dataTransfer.files;
                    }
                    processSelectedFile(droppedFile);
                }
            });
        }

        // Intercept form submit: handle base64 encoding, size limits, and loading state
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                const file = fileInput && fileInput.files ? fileInput.files[0] : null;
                const hasBase64 = fileBase64Input && fileBase64Input.value && fileBase64Input.value.length > 50;

                // Validate that a file is picked
                if (!file && !hasBase64) {
                    e.preventDefault();
                    if (fileSizeErrorTitle) fileSizeErrorTitle.textContent = 'Please Select a File';
                    if (fileSizeErrorMsg) fileSizeErrorMsg.textContent = 'Choose or drag & drop a learning material document before uploading.';
                    if (fileSizeErrorBanner) fileSizeErrorBanner.classList.remove('hidden');
                    return false;
                }

                const maxBytes = 25 * 1024 * 1024;
                if (file && file.size > maxBytes) {
                    e.preventDefault();
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    if (fileSizeErrorTitle) {
                        fileSizeErrorTitle.textContent = `File Size (${fileSizeMB} MB) Exceeds 25MB Limit`;
                    }
                    if (fileSizeErrorMsg) {
                        fileSizeErrorMsg.innerHTML = `The selected file <span class="font-bold text-red-900 break-all">"${file.name}"</span> is <span class="font-bold text-red-900">${fileSizeMB} MB</span>. Maximum allowed file size is <span class="font-bold text-red-900">25 MB</span>. Please select a smaller file.`;
                    }
                    if (fileSizeErrorBanner) {
                        fileSizeErrorBanner.classList.remove('hidden');
                    }
                    return false;
                }

                const submitBtn = document.getElementById('submit-upload-btn');

                // If FileReader is still reading the file in the background
                if (isFileReaderBusy && file) {
                    e.preventDefault();
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Preparing Document...</span>';
                    }

                    const fallbackReader = new FileReader();
                    fallbackReader.onload = function(evt) {
                        if (fileBase64Input) fileBase64Input.value = evt.target.result;
                        if (fileNameInput) fileNameInput.value = file.name;
                        if (fileInput) fileInput.removeAttribute('name'); // Prevent duplicate binary upload
                        uploadForm.submit();
                    };
                    fallbackReader.onerror = function() {
                        uploadForm.submit(); // Submit as standard multipart fallback
                    };
                    fallbackReader.readAsDataURL(file);
                    return false;
                }

                // If Base64 data is ready, remove multipart name to prevent server temp folder bottlenecks
                if (hasBase64 && fileInput) {
                    fileInput.removeAttribute('name');
                }

                // Show active animated loading spinner on button
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
                    submitBtn.innerHTML = '<svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Uploading Document...</span>';
                }

                return true;
            });
        }

        // On Load Hash / Query Routing (#materials, #assignments, #overview or ?tab=...)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash.replace('#', '');
            const targetTab = tabParam || hash;

            if (targetTab === 'assignments') {
                window.switchCourseTab('assignments');
            } else if (targetTab === 'overview' || targetTab === 'students') {
                window.switchCourseTab('overview');
            } else {
                window.switchCourseTab('materials');
            }
        });

        window.addEventListener('hashchange', function() {
            const hash = window.location.hash.replace('#', '');
            if (hash === 'assignments' || hash === 'overview' || hash === 'materials') {
                window.switchCourseTab(hash);
            }
        });
    </script>
</body>
</html>
