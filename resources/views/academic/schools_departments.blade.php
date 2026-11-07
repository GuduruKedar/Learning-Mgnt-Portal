<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Schools & Academic Departments - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>

    <style>
        /* Smooth sliding animations */
        @keyframes slideInFromRight {
            0% { transform: translateX(36px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInFromLeft {
            0% { transform: translateX(-36px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInFromTop {
            0% { transform: translateY(-24px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-in-right {
            animation: slideInFromRight 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-slide-in-left {
            animation: slideInFromLeft 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-slide-in-top {
            animation: slideInFromTop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Hide scrollbars for reel carousel */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Interactive card styling */
        .school-reel-card {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .school-reel-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -8px rgba(99, 102, 241, 0.2);
        }
        .school-reel-card.active-school {
            border-color: #6366f1;
            background: linear-gradient(to bottom right, #ffffff, #eef2ff);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.4), 0 8px 16px -6px rgba(99, 102, 241, 0.25);
        }

        .dept-reel-card {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dept-reel-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -8px rgba(99, 102, 241, 0.2);
        }
        .dept-reel-card.active-dept {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.3);
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800 font-sans">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70 custom-scrollbar">
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Flash Messages -->
                @include('partials.flash_messages')

                <!-- Page Header Card -->
                <div class="relative overflow-hidden bg-gradient-to-r from-white via-indigo-50/30 to-white p-6 sm:p-7 rounded-3xl border border-indigo-100/80 shadow-xs">
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-48 h-48 bg-gradient-to-br from-indigo-200/40 to-blue-200/0 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative flex flex-col lg:flex-row lg:justify-between lg:items-center gap-5">
                        <div class="flex items-center gap-4">
                            <span class="w-14 h-14 bg-gradient-to-tr from-indigo-600 via-indigo-700 to-blue-600 text-white rounded-2xl shadow-lg shadow-indigo-200 flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Schools & Academic Departments</h1>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">Hierarchical</span>
                                </div>
                                <p class="text-slate-500 text-xs sm:text-sm mt-1">Collegiate structure, instructional units, and interactive sliding navigation between schools and their departments.</p>
                            </div>
                        </div>

                        <!-- Top Action Buttons -->
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" 
                                    onclick="openInspectorAt(0, 0)" 
                                    class="inline-flex items-center justify-center gap-2 bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold text-xs sm:text-sm py-2.5 px-4 rounded-xl shadow-2xs hover:shadow-xs transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span>Launch Sliding Explorer</span>
                            </button>

                            <button type="button" 
                                    onclick="openCreateSchoolModal()" 
                                    class="inline-flex items-center justify-center gap-2 bg-indigo-50/80 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/80 font-bold text-xs sm:text-sm py-2.5 px-4 rounded-xl shadow-2xs hover:shadow-xs transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Add School</span>
                            </button>

                            <button type="button" 
                                    onclick="openCreateDepartmentModal()" 
                                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-xs sm:text-sm py-2.5 px-5 rounded-xl shadow-md shadow-indigo-600/25 hover:shadow-lg transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Add Department</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4 KPI Summary Metric Cards -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Stat 1: Total Schools -->
                    <div class="group bg-white rounded-2xl shadow-xs border border-slate-200/80 p-4 sm:p-5 hover:border-indigo-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Collegiate Schools</span>
                            <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $totalSchoolsCount }}</div>
                        <div class="mt-1 text-[11px] text-slate-500 font-medium">Academic divisions registered</div>
                    </div>

                    <!-- Stat 2: Total Departments -->
                    <div class="group bg-white rounded-2xl shadow-xs border border-slate-200/80 p-4 sm:p-5 hover:border-blue-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Departments</span>
                            <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ $totalDepartmentsCount }}</div>
                        <div class="mt-1 text-[11px] text-slate-500 font-medium">Active instructional units</div>
                    </div>

                    <!-- Stat 3: Total Faculty -->
                    <div class="group bg-white rounded-2xl shadow-xs border border-slate-200/80 p-4 sm:p-5 hover:border-cyan-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Allocated Faculty</span>
                            <span class="p-2.5 bg-cyan-50 text-cyan-600 rounded-xl group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($totalFacultyCount) }}</div>
                        <div class="mt-1 text-[11px] text-slate-500 font-medium">Teaching staff allocated</div>
                    </div>

                    <!-- Stat 4: Total Students -->
                    <div class="group bg-white rounded-2xl shadow-xs border border-slate-200/80 p-4 sm:p-5 hover:border-emerald-300 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Enrolled Students</span>
                            <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($totalStudentsCount) }}</div>
                        <div class="mt-1 text-[11px] text-slate-500 font-medium">Active learners enrolled</div>
                    </div>
                </div>

                <!-- ==================================================================== -->
                <!-- DUAL-LEVEL SLIDING CONCEPT: SCHOOLS & DEPARTMENTS SLIDER HUB -->
                <!-- ==================================================================== -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-6">
                    
                    <!-- LEVEL 1: COLLEGIATE SCHOOLS SLIDER REEL -->
                    <div class="space-y-3 pb-5 border-b border-slate-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </span>
                                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">1. Slide Between Schools</h2>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $schoolsWithDepts->count() }} Schools
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Select or slide to any school to reveal its departments starting with its 1st created department.</p>
                            </div>

                            <!-- School Slider Controls -->
                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <button type="button" 
                                        onclick="slideSchoolsReel('left')" 
                                        class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 flex items-center justify-center transition-all shadow-2xs hover:shadow-xs active:scale-90 cursor-pointer"
                                        title="Slide Schools Left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <span class="text-xs font-mono font-bold text-slate-400 px-1" id="schoolReelCounter">1 / {{ $schoolsWithDepts->count() }}</span>
                                <button type="button" 
                                        onclick="slideSchoolsReel('right')" 
                                        class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 flex items-center justify-center transition-all shadow-2xs hover:shadow-xs active:scale-90 cursor-pointer"
                                        title="Slide Schools Right">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Horizontal Track of Schools -->
                        <div id="schoolsReelContainer" class="flex gap-3.5 overflow-x-auto scroll-smooth no-scrollbar py-1.5 px-0.5">
                            @foreach($schoolsWithDepts as $sIdx => $sch)
                                <div id="schoolCard-{{ $sIdx }}" 
                                     onclick="selectActiveSchool({{ $sIdx }}, true)" 
                                     class="school-reel-card {{ $sIdx === 0 ? 'active-school' : '' }} w-64 sm:w-72 shrink-0 bg-white rounded-2xl border border-slate-200 p-3.5 flex flex-col justify-between cursor-pointer select-none">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <span class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                                                {{ $sch->code }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                                {{ $sch->departments_count }} {{ Str::plural('Dept', $sch->departments_count) }}
                                            </span>
                                        </div>
                                        <h3 class="font-extrabold text-slate-900 text-xs sm:text-sm line-clamp-2" title="{{ $sch->name }}">{{ $sch->name }}</h3>
                                    </div>

                                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>👨‍🏫 {{ $sch->faculty_count }} Faculty</span>
                                        <span>🎓 {{ $sch->student_count }} Students</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- LEVEL 2: DEPARTMENTS UNDER ACTIVE SCHOOL SLIDER REEL -->
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </span>
                                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight" id="activeSchoolTitle">
                                        2. Departments Under School
                                    </h2>
                                    <span id="activeSchoolDeptBadge" class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        4 Departments
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5" id="activeSchoolDesc">
                                    Displaying departments ordered by creation date — starting with the 1st created department.
                                </p>
                            </div>

                            <!-- Department Slider Controls -->
                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <button type="button" 
                                        onclick="slideDepartmentsReel('left')" 
                                        class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 flex items-center justify-center transition-all shadow-2xs hover:shadow-xs active:scale-90 cursor-pointer"
                                        title="Slide Departments Left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <button type="button" 
                                        onclick="slideDepartmentsReel('right')" 
                                        class="w-8 h-8 rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 border border-slate-200 hover:border-indigo-200 flex items-center justify-center transition-all shadow-2xs hover:shadow-xs active:scale-90 cursor-pointer"
                                        title="Slide Departments Right">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Horizontal Track of Departments for Active School -->
                        <div id="departmentsReelContainer" class="flex gap-4 overflow-x-auto scroll-smooth no-scrollbar py-2 px-0.5">
                            <!-- Populated dynamically via renderActiveSchoolDepartments() -->
                        </div>
                    </div>

                </div>

                <!-- ==================================================================== -->
                <!-- MAIN DIRECTORY TABS: DEPARTMENTS & SCHOOLS TABLES -->
                <!-- ==================================================================== -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
                    
                    <!-- Tab Switcher Navigation -->
                    <div class="px-6 pt-5 pb-0 border-b border-slate-200/80 bg-slate-50/60 flex items-center justify-between gap-4">
                        <div class="flex items-center space-x-2">
                            <button type="button" 
                                    id="tabBtn-departments" 
                                    onclick="switchDirectoryTab('departments')" 
                                    class="px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'departments' ? 'bg-white text-indigo-700 shadow-xs border-t border-x border-slate-200/80 -mb-[1px]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100/80' }}">
                                <span>🏛️ Academic Departments</span>
                                <span class="px-2 py-0.5 text-[10px] rounded-full {{ $activeTab === 'departments' ? 'bg-indigo-100 text-indigo-800 font-extrabold' : 'bg-slate-200 text-slate-600' }}">{{ $totalDepartmentsCount }}</span>
                            </button>

                            <button type="button" 
                                    id="tabBtn-schools" 
                                    onclick="switchDirectoryTab('schools')" 
                                    class="px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'schools' ? 'bg-white text-indigo-700 shadow-xs border-t border-x border-slate-200/80 -mb-[1px]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100/80' }}">
                                <span>🏫 Collegiate Schools</span>
                                <span class="px-2 py-0.5 text-[10px] rounded-full {{ $activeTab === 'schools' ? 'bg-indigo-100 text-indigo-800 font-extrabold' : 'bg-slate-200 text-slate-600' }}">{{ $totalSchoolsCount }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter & Search Toolbar -->
                    <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/30">
                        <form method="GET" action="{{ route('academic.departments.index') }}" id="filterForm" class="flex flex-col sm:flex-row items-center gap-3">
                            <input type="hidden" name="tab" id="formActiveTab" value="{{ $activeTab }}">

                            <!-- Search Input -->
                            <div class="relative flex-1 w-full">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ $searchTerm }}" 
                                       placeholder="Search by department name, code, or school..." 
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder-slate-400 shadow-2xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>

                            <!-- School Dropdown Filter (Visible for Departments) -->
                            <div id="schoolFilterContainer" class="{{ $activeTab === 'departments' ? '' : 'hidden' }} w-full sm:w-72">
                                <select name="school" 
                                        onchange="document.getElementById('filterForm').submit()" 
                                        class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 shadow-2xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Collegiate Schools</option>
                                    @foreach($schools as $sch)
                                        <option value="{{ $sch->code }}" {{ $selectedSchool === $sch->code ? 'selected' : '' }}>
                                            {{ $sch->name }} ({{ $sch->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit / Reset Buttons -->
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-all cursor-pointer">
                                    Filter
                                </button>
                                @if(!empty($searchTerm) || $selectedSchool !== 'all')
                                    <a href="{{ route('academic.departments.index', ['tab' => $activeTab]) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- TAB 1: DEPARTMENTS DIRECTORY TABLE -->
                    <div id="section-departments" class="{{ $activeTab === 'departments' ? '' : 'hidden' }}">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left">
                                <thead class="bg-slate-50/80">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">Department Details</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">School / Faculty</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">Teaching Staff</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">Students Enrolled</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($departments as $dept)
                                        <tr class="hover:bg-indigo-50/40 transition-colors">
                                            <!-- Department Details -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                <div class="flex items-center space-x-3.5">
                                                    <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 font-mono">
                                                        {{ strtoupper(substr(str_replace('dep_', '', $dept->code), 0, 2)) }}
                                                    </span>
                                                    <div>
                                                        <div class="font-extrabold text-slate-900 text-sm">{{ $dept->name }}</div>
                                                        <div class="text-xs text-indigo-600 font-mono font-semibold">Code: {{ $dept->code }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Governing School -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                @if($dept->school)
                                                    <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                        {{ $dept->school->name }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 font-mono pl-3.5">Code: {{ $dept->school->code }}</div>
                                                @else
                                                    <span class="text-slate-400 font-mono italic">Central / System</span>
                                                @endif
                                            </td>

                                            <!-- Faculty Staffed -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                                    👨‍🏫 {{ $dept->faculty_count }} Faculty
                                                </span>
                                            </td>

                                            <!-- Enrolled Students -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    🎓 {{ $dept->student_count }} Students
                                                </span>
                                            </td>

                                            <!-- Actions: View (Sky Blue) | Edit (Indigo) | Delete (Rose) per standards -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Slide Inspect Button (View - Sky Blue) -->
                                                    <button type="button" 
                                                            onclick="openInspectorByDeptCode('{{ $dept->code }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-sky-50/80 text-sky-600 border border-sky-200/80 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" 
                                                            title="Slide Inspect Department">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Edit Department Button (Indigo) -->
                                                    <button type="button" 
                                                            onclick="openEditDepartmentModal({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ $dept->code }}', '{{ $dept->school_id }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50/80 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" 
                                                            title="Edit Department">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Department Button (Rose) -->
                                                    <button type="button" 
                                                            onclick="confirmDelete('department', {{ $dept->id }}, '{{ addslashes($dept->name) }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" 
                                                            title="Delete Department">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                No academic departments found matching your search.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="text-xs text-slate-500 font-medium">
                                Showing {{ $departments->firstItem() ?? 0 }} to {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }} departments
                            </div>
                            <div>
                                {{ $departments->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: SCHOOLS DIRECTORY TABLE -->
                    <div id="section-schools" class="{{ $activeTab === 'schools' ? '' : 'hidden' }}">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left">
                                <thead class="bg-slate-50/80">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">School Details</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">Collegiate Code</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider">Departments Included</th>
                                        <th class="px-6 py-4 text-xs font-extrabold text-slate-600 uppercase tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($schoolsList as $sch)
                                        <tr class="hover:bg-indigo-50/40 transition-colors">
                                            <!-- School Details -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                <div class="flex items-center space-x-3.5">
                                                    <span class="p-2.5 bg-indigo-50 text-indigo-700 rounded-xl border border-indigo-200 shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    </span>
                                                    <div>
                                                        <div class="font-extrabold text-slate-900 text-sm">{{ $sch->name }}</div>
                                                        <div class="text-[11px] text-slate-400 mt-0.5">Faculty Unit #{{ $sch->id }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Code -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                                    {{ $sch->code }}
                                                </span>
                                            </td>

                                            <!-- Departments List & Count -->
                                            <td class="px-6 py-4 text-xs max-w-md">
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                        {{ $sch->departments_count }} Departments
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($sch->departments->take(4) as $d)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[150px]" title="{{ $d->name }}">
                                                            {{ $d->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($sch->departments->count() > 4)
                                                        <span class="text-[10px] text-slate-400 font-bold self-center">+{{ $sch->departments->count() - 4 }} more</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Actions: Edit (Indigo) | Delete (Rose) -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Edit School -->
                                                    <button type="button" 
                                                            onclick="openEditSchoolModal({{ $sch->id }}, '{{ addslashes($sch->name) }}', '{{ $sch->code }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50/80 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" 
                                                            title="Edit School">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete School -->
                                                    <button type="button" 
                                                            onclick="confirmDelete('school', {{ $sch->id }}, '{{ addslashes($sch->name) }}')" 
                                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" 
                                                            title="Delete School">
                                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                No collegiate schools found matching your search.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="text-xs text-slate-500 font-medium">
                                Showing {{ $schoolsList->firstItem() ?? 0 }} to {{ $schoolsList->lastItem() ?? 0 }} of {{ $schoolsList->total() }} schools
                            </div>
                            <div>
                                {{ $schoolsList->links() }}
                            </div>
                        </div>
                    </div>

                </div>

                <footer class="mt-8 border-t border-slate-200 pt-5 pb-3">
                    <p class="text-center text-xs text-slate-400">&copy; {{ date('Y') }} Learning Management System. Dynamic School & Academic Department Engine with Dual-Level Sliding Navigation.</p>
                </footer>

            </div>
        </main>
    </div>

    <!-- ==================================================================== -->
    <!-- DUAL-LEVEL SLIDING INSPECTOR DRAWER (SCHOOLS & DEPARTMENTS) -->
    <!-- ==================================================================== -->
    <div id="inspectorBackdrop" 
         onclick="closeDepartmentInspector()" 
         class="fixed inset-0 z-[120] hidden bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 opacity-0"></div>

    <aside id="inspectorDrawer" 
           class="fixed inset-y-0 right-0 z-[130] w-full max-w-lg bg-white shadow-2xl border-l border-slate-200 flex flex-col transform transition-transform duration-300 ease-out translate-x-full">
        
        <!-- Drawer Header with School & Department Position -->
        <div class="p-5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between border-b border-indigo-900/40">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-widest text-indigo-300 font-bold block">Dual-Level Sliding Explorer</span>
                <div class="flex items-center gap-2 mt-0.5">
                    <span id="drawerSchoolCounter" class="text-sm font-extrabold text-white">School 1 of 10</span>
                    <span class="text-slate-400 text-xs">/</span>
                    <span id="drawerDeptCounter" class="text-xs font-semibold text-indigo-300">Dept 1 of 4</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="closeDepartmentInspector()" 
                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer"
                        title="Close Drawer (Esc)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- LEVEL 1 SLIDER: SCHOOL SLIDE CONTROLS (‹ Prev School | Next School ›) -->
        <div class="px-5 py-2.5 bg-indigo-50/80 border-b border-indigo-100 flex items-center justify-between gap-2">
            <button type="button" 
                    onclick="slideSchool('prev')" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-[11px] font-bold shadow-2xs active:scale-95 transition-all cursor-pointer"
                    title="Slide to Previous School (1st Dept)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <span>‹ Prev School</span>
            </button>

            <div class="text-center truncate px-2">
                <span class="text-[10px] font-bold uppercase text-indigo-500 tracking-wider block">Active School</span>
                <span id="drawerSchoolNameBadge" class="text-xs font-extrabold text-slate-900 truncate block">School of Computing</span>
            </div>

            <button type="button" 
                    onclick="slideSchool('next')" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-[11px] font-bold shadow-2xs active:scale-95 transition-all cursor-pointer"
                    title="Slide to Next School (1st Dept)">
                <span>Next School ›</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- LEVEL 2 SLIDER: DEPARTMENT SLIDE CONTROLS (‹ Prev Dept | Next Dept ›) -->
        <div class="px-5 py-2.5 bg-slate-100 border-b border-slate-200 flex items-center justify-between gap-3">
            <button type="button" 
                    id="prevDeptSlideBtn"
                    onclick="slideDepartmentWithinSchool('prev')" 
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white hover:bg-blue-50 text-blue-700 border border-slate-200 text-xs font-bold shadow-2xs active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                <span id="prevDeptLabel">‹ Prev Dept</span>
            </button>

            <!-- Department Chips / Dropdown for this school -->
            <select id="drawerDeptJumpSelect" 
                    onchange="jumpToDepartmentInCurrentSchool(parseInt(this.value))" 
                    class="py-1 px-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 shadow-2xs focus:ring-2 focus:ring-indigo-500 cursor-pointer max-w-[170px] truncate">
                <!-- Populated dynamically via JS -->
            </select>

            <button type="button" 
                    id="nextDeptSlideBtn"
                    onclick="slideDepartmentWithinSchool('next')" 
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white hover:bg-blue-50 text-blue-700 border border-slate-200 text-xs font-bold shadow-2xs active:scale-95 transition-all cursor-pointer">
                <span id="nextDeptLabel">Next Dept ›</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Department Mini Chips Track for Current School -->
        <div id="drawerDeptChipsContainer" class="px-5 py-2 bg-slate-50 border-b border-slate-200/80 flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <!-- Populated dynamically -->
        </div>

        <!-- Animated Slide Content Body -->
        <div id="inspectorContentBody" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
            <!-- Will be dynamically populated with .animate-slide-in-right or left -->
        </div>

        <!-- Drawer Footer Actions -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3">
            <button type="button" 
                    onclick="filterTableToCurrentDept()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold border border-indigo-200 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter Table</span>
            </button>

            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="editCurrentInspectorDept()" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Edit Department</span>
                </button>
            </div>
        </div>
    </aside>

    <!-- ==================================================================== -->
    <!-- MODAL 1: CREATE SCHOOL -->
    <!-- ==================================================================== -->
    <div id="createSchoolModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </span>
                    <h3 class="text-base font-bold">Add Collegiate School</h3>
                </div>
                <button type="button" onclick="closeModal('createSchoolModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('academic.schools.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="schoolNameInput" oninput="autoSuggestSchoolCode(this.value)" required placeholder="e.g. School of Artificial Intelligence & Computing" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="schoolCodeInput" required placeholder="e.g. sc_ai" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Unique system identifier (e.g. <span class="font-mono">sc_ai</span>, <span class="font-mono">sc_ceng</span>)</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('createSchoolModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Save School
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL 2: EDIT SCHOOL -->
    <!-- ==================================================================== -->
    <div id="editSchoolModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </span>
                    <h3 class="text-base font-bold">Edit School Details</h3>
                </div>
                <button type="button" onclick="closeModal('editSchoolModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="editSchoolForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="editSchoolName" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="editSchoolCode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editSchoolModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Update School
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL 3: CREATE DEPARTMENT -->
    <!-- ==================================================================== -->
    <div id="createDepartmentModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <h3 class="text-base font-bold">Add Academic Department</h3>
                </div>
                <button type="button" onclick="closeModal('createDepartmentModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('academic.departments.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Governing School <span class="text-rose-500">*</span></label>
                    <select name="school_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">-- Select Parent Collegiate School --</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch->code }}">
                                {{ $sch->name }} ({{ $sch->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="deptNameInput" oninput="autoSuggestDeptCode(this.value)" required placeholder="e.g. Artificial Intelligence & Machine Learning" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="deptCodeInput" required placeholder="e.g. dep_aiml" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Unique system code (e.g. <span class="font-mono">dep_cse</span>, <span class="font-mono">dep_it</span>, <span class="font-mono">dep_aiml</span>)</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('createDepartmentModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Save Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL 4: EDIT DEPARTMENT -->
    <!-- ==================================================================== -->
    <div id="editDepartmentModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 transform transition-all">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </span>
                    <h3 class="text-base font-bold">Edit Department Details</h3>
                </div>
                <button type="button" onclick="closeModal('editDepartmentModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="editDepartmentForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Governing School <span class="text-rose-500">*</span></label>
                    <select name="school_id" id="editDeptSchool" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        @foreach($schools as $sch)
                            <option value="{{ $sch->code }}">
                                {{ $sch->name }} ({{ $sch->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="editDeptName" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="editDeptCode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editDepartmentModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL 5: DELETE CONFIRMATION -->
    <!-- ==================================================================== -->
    <div id="deleteConfirmModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden border border-slate-100 transform transition-all text-center p-6 space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-xs">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900" id="deleteTargetTitle">Delete Item</h3>
                <p class="text-xs text-slate-500 mt-1" id="deleteTargetDesc">Are you sure you want to permanently delete this item?</p>
            </div>

            <form id="deleteForm" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('deleteConfirmModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25 cursor-pointer">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- CLIENT-SIDE HIERARCHICAL DATASET & DUAL SLIDING JAVASCRIPT ENGINE -->
    <!-- ==================================================================== -->
    @php
        $schoolsTreeData = $schoolsWithDepts->map(function($sch) {
            return [
                'id' => $sch->id,
                'name' => $sch->name,
                'code' => $sch->code,
                'departments_count' => (int) $sch->departments_count,
                'faculty_count' => (int) $sch->faculty_count,
                'student_count' => (int) $sch->student_count,
                'courses_count' => (int) $sch->courses_count,
                'departments' => $sch->departments->map(function($d, $dIdx) use ($sch) {
                    return [
                        'id' => $d->id,
                        'name' => $d->name,
                        'code' => $d->code,
                        'school_id' => $d->school_id,
                        'school_name' => $sch->name,
                        'school_code' => $sch->code,
                        'faculty_count' => (int) $d->faculty_count,
                        'student_count' => (int) $d->student_count,
                        'courses_count' => (int) ($d->courses_count ?? 0),
                        'order_in_school' => $dIdx + 1,
                        'is_first_in_school' => $dIdx === 0,
                    ];
                })->values(),
            ];
        })->values();
    @endphp

    <script>
        // Full hierarchical tree of Schools and their ordered Departments (1st created is index 0)
        const SCHOOLS_TREE = @json($schoolsTreeData);
        let currentSchoolIdx = 0;
        let currentDeptIdx = 0;
        let isDrawerOpen = false;

        // Initialize Dual-Level Sliders on Load
        document.addEventListener('DOMContentLoaded', function() {
            if (SCHOOLS_TREE.length > 0) {
                selectActiveSchool(0, false);
            }
        });

        // -------------------------------------------------------------
        // LEVEL 1: SCHOOL SELECTION & REEL SCROLLING
        // -------------------------------------------------------------
        function slideSchoolsReel(direction) {
            const container = document.getElementById('schoolsReelContainer');
            if (!container) return;
            const scrollAmount = 280;
            container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' });
        }

        function selectActiveSchool(schoolIndex, scrollIntoView = true) {
            if (schoolIndex < 0 || schoolIndex >= SCHOOLS_TREE.length) return;
            currentSchoolIdx = schoolIndex;
            currentDeptIdx = 0; // Automatically reset to 1st created department!

            // Update School Card Active States
            SCHOOLS_TREE.forEach((_, idx) => {
                const card = document.getElementById(`schoolCard-${idx}`);
                if (card) {
                    if (idx === schoolIndex) {
                        card.classList.add('active-school');
                        if (scrollIntoView) {
                            card.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                        }
                    } else {
                        card.classList.remove('active-school');
                    }
                }
            });

            // Update Reel Counter
            const counter = document.getElementById('schoolReelCounter');
            if (counter) counter.innerText = `${schoolIndex + 1} / ${SCHOOLS_TREE.length}`;

            // Render Departments under this selected school
            renderActiveSchoolDepartments();
        }

        // -------------------------------------------------------------
        // LEVEL 2: DEPARTMENTS UNDER ACTIVE SCHOOL
        // -------------------------------------------------------------
        function slideDepartmentsReel(direction) {
            const container = document.getElementById('departmentsReelContainer');
            if (!container) return;
            const scrollAmount = 320;
            container.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' });
        }

        function renderActiveSchoolDepartments() {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school) return;

            // Update Header labels
            document.getElementById('activeSchoolTitle').innerText = `2. Departments under ${school.name}`;
            document.getElementById('activeSchoolDeptBadge').innerText = `${school.departments.length} ${school.departments.length === 1 ? 'Department' : 'Departments'}`;
            document.getElementById('activeSchoolDesc').innerText = `Displaying departments under ${school.name} in creation order — 1st created department is #1 (${school.departments[0] ? school.departments[0].name : 'None'}).`;

            const container = document.getElementById('departmentsReelContainer');
            if (!container) return;

            if (school.departments.length === 0) {
                container.innerHTML = `
                    <div class="w-full py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400 text-xs">
                        No departments registered under this school yet. Click "+ Add Department" to create the 1st one.
                    </div>
                `;
                return;
            }

            container.innerHTML = school.departments.map((dept, dIdx) => {
                const initial = dept.code.replace('dep_', '').substring(0, 2).toUpperCase();
                const isFirstBadge = dept.is_first_in_school ? 
                    `<span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">⭐ 1st Created Dept</span>` : 
                    `<span class="font-mono text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">#${dept.order_in_school}</span>`;

                return `
                    <div class="dept-reel-card w-72 sm:w-80 shrink-0 bg-gradient-to-b from-white to-slate-50/70 rounded-2xl border border-slate-200 p-4 flex flex-col justify-between cursor-pointer"
                         onclick="openInspectorAt(${currentSchoolIdx}, ${dIdx})">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2.5">
                                ${isFirstBadge}
                                <span class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                    ${dept.code}
                                </span>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0 font-mono">
                                    ${initial}
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-extrabold text-slate-900 text-sm truncate" title="${dept.name}">${dept.name}</h3>
                                    <span class="text-xs text-slate-400 font-medium">${school.name}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                                <div class="p-1.5 rounded-lg bg-cyan-50/70 border border-cyan-100">
                                    <div class="text-[10px] text-cyan-600 font-bold uppercase">Faculty</div>
                                    <div class="text-xs font-black text-slate-800 mt-0.5">${dept.faculty_count}</div>
                                </div>
                                <div class="p-1.5 rounded-lg bg-emerald-50/70 border border-emerald-100">
                                    <div class="text-[10px] text-emerald-600 font-bold uppercase">Students</div>
                                    <div class="text-xs font-black text-slate-800 mt-0.5">${dept.student_count}</div>
                                </div>
                                <div class="p-1.5 rounded-lg bg-purple-50/70 border border-purple-100">
                                    <div class="text-[10px] text-purple-600 font-bold uppercase">Courses</div>
                                    <div class="text-xs font-black text-slate-800 mt-0.5">${dept.courses_count}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-indigo-600 font-bold flex items-center gap-1 hover:underline">
                                Slide to inspect
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono">School #${currentSchoolIdx + 1}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // -------------------------------------------------------------
        // DUAL-LEVEL SLIDING INSPECTOR DRAWER
        // -------------------------------------------------------------
        function openInspectorAt(schoolIndex, deptIndex) {
            if (schoolIndex < 0 || schoolIndex >= SCHOOLS_TREE.length) return;
            currentSchoolIdx = schoolIndex;
            const school = SCHOOLS_TREE[schoolIndex];
            if (!school || school.departments.length === 0) return;

            currentDeptIdx = Math.max(0, Math.min(deptIndex, school.departments.length - 1));

            const drawer = document.getElementById('inspectorDrawer');
            const backdrop = document.getElementById('inspectorBackdrop');

            renderInspectorDrawer('animate-slide-in-right');

            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                drawer.classList.remove('translate-x-full');
            }, 10);

            isDrawerOpen = true;
        }

        function openInspectorByDeptCode(deptCode) {
            // Find school and dept indexes by code
            for (let s = 0; s < SCHOOLS_TREE.length; s++) {
                const dIdx = SCHOOLS_TREE[s].departments.findIndex(d => d.code === deptCode);
                if (dIdx !== -1) {
                    openInspectorAt(s, dIdx);
                    return;
                }
            }
            openInspectorAt(0, 0);
        }

        function closeDepartmentInspector() {
            const drawer = document.getElementById('inspectorDrawer');
            const backdrop = document.getElementById('inspectorBackdrop');

            drawer.classList.add('translate-x-full');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');

            setTimeout(() => {
                backdrop.classList.add('hidden');
                isDrawerOpen = false;
            }, 300);
        }

        // Slide between Schools: Takes the new school and lands on its 1st created department!
        function slideSchool(direction) {
            if (SCHOOLS_TREE.length === 0) return;
            let nextSchoolIdx = currentSchoolIdx;
            let animClass = 'animate-slide-in-right';

            if (direction === 'next') {
                nextSchoolIdx = (currentSchoolIdx + 1) % SCHOOLS_TREE.length;
                animClass = 'animate-slide-in-right';
            } else if (direction === 'prev') {
                nextSchoolIdx = (currentSchoolIdx - 1 + SCHOOLS_TREE.length) % SCHOOLS_TREE.length;
                animClass = 'animate-slide-in-left';
            }

            currentSchoolIdx = nextSchoolIdx;
            currentDeptIdx = 0; // Reset to 1st created department of new school!

            // Sync page school reel
            selectActiveSchool(currentSchoolIdx, true);

            renderInspectorDrawer(animClass);
        }

        // Slide between Departments within School & Continuous Cross-School Sliding
        function slideDepartmentWithinSchool(direction) {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school || school.departments.length === 0) return;

            let animClass = 'animate-slide-in-right';

            if (direction === 'next') {
                if (currentDeptIdx + 1 < school.departments.length) {
                    // Slide to next department in current school
                    currentDeptIdx++;
                    animClass = 'animate-slide-in-right';
                } else {
                    // Last department reached! Slide into the NEW SCHOOL and land on its 1st created department!
                    currentSchoolIdx = (currentSchoolIdx + 1) % SCHOOLS_TREE.length;
                    currentDeptIdx = 0;
                    animClass = 'animate-slide-in-right';
                    selectActiveSchool(currentSchoolIdx, true);
                }
            } else if (direction === 'prev') {
                if (currentDeptIdx > 0) {
                    // Slide to previous department in current school
                    currentDeptIdx--;
                    animClass = 'animate-slide-in-left';
                } else {
                    // At 1st department! Slide back into the PREVIOUS SCHOOL and land on its last department!
                    currentSchoolIdx = (currentSchoolIdx - 1 + SCHOOLS_TREE.length) % SCHOOLS_TREE.length;
                    const prevSchool = SCHOOLS_TREE[currentSchoolIdx];
                    currentDeptIdx = Math.max(0, prevSchool.departments.length - 1);
                    animClass = 'animate-slide-in-left';
                    selectActiveSchool(currentSchoolIdx, true);
                }
            }

            renderInspectorDrawer(animClass);
        }

        function jumpToDepartmentInCurrentSchool(deptIndex) {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school || !school.departments[deptIndex]) return;
            const animClass = deptIndex > currentDeptIdx ? 'animate-slide-in-right' : 'animate-slide-in-left';
            currentDeptIdx = deptIndex;
            renderInspectorDrawer(animClass);
        }

        function renderInspectorDrawer(animClass = 'animate-slide-in-right') {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school) return;
            const dept = school.departments[currentDeptIdx];
            if (!dept) return;

            // Update top counters
            document.getElementById('drawerSchoolCounter').innerText = `School ${currentSchoolIdx + 1} of ${SCHOOLS_TREE.length}`;
            document.getElementById('drawerDeptCounter').innerText = `Dept ${currentDeptIdx + 1} of ${school.departments.length}`;
            document.getElementById('drawerSchoolNameBadge').innerText = school.name;

            // Next / Prev Labels with Transition Hints
            const prevLabel = document.getElementById('prevDeptLabel');
            const nextLabel = document.getElementById('nextDeptLabel');
            if (currentDeptIdx === 0) {
                const prevSchool = SCHOOLS_TREE[(currentSchoolIdx - 1 + SCHOOLS_TREE.length) % SCHOOLS_TREE.length];
                prevLabel.innerText = `‹ ${prevSchool.code.toUpperCase()}`;
            } else {
                prevLabel.innerText = `‹ Prev Dept`;
            }

            if (currentDeptIdx + 1 === school.departments.length) {
                const nextSchool = SCHOOLS_TREE[(currentSchoolIdx + 1) % SCHOOLS_TREE.length];
                nextLabel.innerText = `${nextSchool.code.toUpperCase()} (1st Dept) ›`;
            } else {
                nextLabel.innerText = `Next Dept ›`;
            }

            // Populate Jump Select
            const jumpSelect = document.getElementById('drawerDeptJumpSelect');
            if (jumpSelect) {
                jumpSelect.innerHTML = school.departments.map((d, i) => 
                    `<option value="${i}" ${i === currentDeptIdx ? 'selected' : ''}>#${i + 1} - ${d.name} (${d.code})</option>`
                ).join('');
            }

            // Populate Mini Department Chips Bar
            const chipsBar = document.getElementById('drawerDeptChipsContainer');
            if (chipsBar) {
                chipsBar.innerHTML = school.departments.map((d, i) => {
                    const isActive = i === currentDeptIdx;
                    return `
                        <button type="button" 
                                onclick="jumpToDepartmentInCurrentSchool(${i})" 
                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold shrink-0 transition-all cursor-pointer ${isActive ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'}">
                            ${i === 0 ? '⭐ 1st: ' : `#${i + 1} `}${d.code.replace('dep_', '').toUpperCase()}
                        </button>
                    `;
                }).join('');
            }

            // Construct Animated Card Body
            const body = document.getElementById('inspectorContentBody');
            const initialCode = dept.code.replace('dep_', '').substring(0, 2).toUpperCase();

            body.innerHTML = `
                <div class="${animClass} space-y-5">
                    <!-- Department Hero Header Card -->
                    <div class="relative overflow-hidden bg-gradient-to-tr from-indigo-50 via-white to-blue-50/50 p-5 rounded-2xl border border-indigo-100 shadow-xs">
                        <div class="flex items-start gap-4">
                            <span class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-purple-600 text-white flex items-center justify-center font-black text-lg shadow-md shadow-indigo-200 shrink-0 font-mono">
                                ${initialCode}
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                        Instructional Unit #${dept.order_in_school}
                                    </span>
                                    ${dept.is_first_in_school ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">⭐ 1st Created Department</span>' : ''}
                                </div>
                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1 leading-snug break-words">${dept.name}</h2>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-indigo-600 bg-white px-2 py-0.5 rounded-md border border-indigo-100">Code: ${dept.code}</span>
                                    <span class="text-xs text-slate-400">ID #${dept.id}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Governing School Banner -->
                    <div class="p-4 bg-slate-50/90 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Governing Collegiate School</span>
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    ${school.name}
                                </div>
                                <div class="text-xs font-mono text-slate-500 mt-0.5">School Code: ${school.code} (School ${currentSchoolIdx + 1} of ${SCHOOLS_TREE.length})</div>
                            </div>
                            <a href="/academic/departments-schools?tab=departments&school=${school.code}" class="px-2.5 py-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all">
                                Filter Table →
                            </a>
                        </div>
                    </div>

                    <!-- 3 Metric Cards for this Department -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3.5 bg-cyan-50/60 rounded-2xl border border-cyan-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-600">Faculty</span>
                            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1">${dept.faculty_count}</div>
                            <span class="text-[10px] text-slate-400 font-medium">Teachers</span>
                        </div>

                        <div class="p-3.5 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Students</span>
                            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1">${dept.student_count}</div>
                            <span class="text-[10px] text-slate-400 font-medium">Enrolled</span>
                        </div>

                        <div class="p-3.5 bg-purple-50/60 rounded-2xl border border-purple-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Courses</span>
                            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1">${dept.courses_count}</div>
                            <span class="text-[10px] text-slate-400 font-medium">Curricula</span>
                        </div>
                    </div>

                    <!-- Dual Sliding Quick Preview Track -->
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                            <span>Department Sliding Track</span>
                            <span>${currentDeptIdx + 1} / ${school.departments.length} in this School</span>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" 
                                    onclick="slideDepartmentWithinSchool('prev')" 
                                    class="flex-1 p-2.5 bg-white hover:bg-indigo-50 border border-slate-200 rounded-xl text-left transition-all cursor-pointer">
                                <span class="text-[10px] text-slate-400 font-bold block">‹ PREVIOUS</span>
                                <span class="text-xs font-bold text-slate-800 truncate block">
                                    ${currentDeptIdx > 0 ? school.departments[currentDeptIdx - 1].name : '‹ Prev School Last Dept'}
                                </span>
                            </button>

                            <button type="button" 
                                    onclick="slideDepartmentWithinSchool('next')" 
                                    class="flex-1 p-2.5 bg-white hover:bg-indigo-50 border border-slate-200 rounded-xl text-right transition-all cursor-pointer">
                                <span class="text-[10px] text-slate-400 font-bold block">NEXT ›</span>
                                <span class="text-xs font-bold text-slate-800 truncate block">
                                    ${currentDeptIdx + 1 < school.departments.length ? school.departments[currentDeptIdx + 1].name : 'Next School (1st Dept) ›'}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Quick Links for This Department -->
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Department Directory Shortcuts</span>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="/academic/courses?department=${dept.code}" class="p-3 bg-white hover:bg-indigo-50 border border-slate-200 rounded-xl flex items-center gap-2 text-xs font-bold text-slate-800 hover:text-indigo-700 transition-all">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <span>Browse Courses</span>
                            </a>
                            <a href="/academic/enrollment-insights?department=${dept.code}" class="p-3 bg-white hover:bg-emerald-50 border border-slate-200 rounded-xl flex items-center gap-2 text-xs font-bold text-slate-800 hover:text-emerald-700 transition-all">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span>Enrollment Insights</span>
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Action from Inspector: Edit
        function editCurrentInspectorDept() {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school) return;
            const dept = school.departments[currentDeptIdx];
            if (!dept) return;
            closeDepartmentInspector();
            openEditDepartmentModal(dept.id, dept.name, dept.code, dept.school_id);
        }

        // Action from Inspector: Filter main table
        function filterTableToCurrentDept() {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school) return;
            const dept = school.departments[currentDeptIdx];
            if (!dept) return;
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'departments');
            url.searchParams.set('search', dept.name);
            window.location.href = url.toString();
        }

        // Keyboard navigation for dual sliding:
        // Left/Right: Slide between departments
        // Up/Down: Slide between schools (jumping to 1st created department)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isDrawerOpen) {
                closeDepartmentInspector();
            } else if (isDrawerOpen) {
                if (e.key === 'ArrowLeft') {
                    slideDepartmentWithinSchool('prev');
                } else if (e.key === 'ArrowRight') {
                    slideDepartmentWithinSchool('next');
                } else if (e.key === 'ArrowUp') {
                    slideSchool('prev');
                } else if (e.key === 'ArrowDown') {
                    slideSchool('next');
                }
            }
        });

        // Touch swiping support on mobile / trackpad for the drawer
        let touchStartX = 0;
        let touchEndX = 0;
        const drawerElement = document.getElementById('inspectorDrawer');
        if (drawerElement) {
            drawerElement.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            drawerElement.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipeGesture();
            }, { passive: true });
        }

        function handleSwipeGesture() {
            const diffX = touchEndX - touchStartX;
            if (Math.abs(diffX) > 60) {
                if (diffX < 0) {
                    slideDepartmentWithinSchool('next');
                } else {
                    slideDepartmentWithinSchool('prev');
                }
            }
        }

        // Tab Switcher
        function switchDirectoryTab(tab) {
            document.getElementById('formActiveTab').value = tab;
            
            const btnDepts = document.getElementById('tabBtn-departments');
            const btnSchools = document.getElementById('tabBtn-schools');
            const secDepts = document.getElementById('section-departments');
            const secSchools = document.getElementById('section-schools');
            const schoolFilterContainer = document.getElementById('schoolFilterContainer');

            if (tab === 'departments') {
                btnDepts.className = 'px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer bg-white text-indigo-700 shadow-xs border-t border-x border-slate-200/80 -mb-[1px]';
                btnSchools.className = 'px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer text-slate-500 hover:text-slate-800 hover:bg-slate-100/80';
                secDepts.classList.remove('hidden');
                secSchools.classList.add('hidden');
                if (schoolFilterContainer) schoolFilterContainer.classList.remove('hidden');
            } else {
                btnSchools.className = 'px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer bg-white text-indigo-700 shadow-xs border-t border-x border-slate-200/80 -mb-[1px]';
                btnDepts.className = 'px-5 py-3 rounded-t-2xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer text-slate-500 hover:text-slate-800 hover:bg-slate-100/80';
                secSchools.classList.remove('hidden');
                secDepts.classList.add('hidden');
                if (schoolFilterContainer) schoolFilterContainer.classList.add('hidden');
            }

            // Sync URL query param
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            window.history.replaceState({ path: url.toString() }, '', url.toString());
        }

        // Modal Helpers
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openCreateSchoolModal() {
            document.getElementById('createSchoolModal').classList.remove('hidden');
        }

        function openEditSchoolModal(id, name, code) {
            document.getElementById('editSchoolName').value = name;
            document.getElementById('editSchoolCode').value = code;
            document.getElementById('editSchoolForm').action = `/academic/schools/${id}`;
            document.getElementById('editSchoolModal').classList.remove('hidden');
        }

        function openCreateDepartmentModal() {
            document.getElementById('createDepartmentModal').classList.remove('hidden');
        }

        function openEditDepartmentModal(id, name, code, schoolId) {
            document.getElementById('editDeptName').value = name;
            document.getElementById('editDeptCode').value = code;
            document.getElementById('editDeptSchool').value = schoolId;
            document.getElementById('editDepartmentForm').action = `/academic/departments/${id}`;
            document.getElementById('editDepartmentModal').classList.remove('hidden');
        }

        function confirmDelete(type, id, name) {
            const form = document.getElementById('deleteForm');
            const title = document.getElementById('deleteTargetTitle');
            const desc = document.getElementById('deleteTargetDesc');

            if (type === 'school') {
                form.action = `/academic/schools/${id}`;
                title.innerText = `Delete School: ${name}`;
                desc.innerText = `Are you sure you want to delete this school? It must have no remaining active departments or allocated users.`;
            } else {
                form.action = `/academic/departments/${id}`;
                title.innerText = `Delete Department: ${name}`;
                desc.innerText = `Are you sure you want to delete this department? Any courses or users assigned to it must be reassigned first.`;
            }

            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        }

        // Auto-suggestions for codes
        function autoSuggestSchoolCode(name) {
            const codeInput = document.getElementById('schoolCodeInput');
            if (codeInput && (!codeInput.value || codeInput.dataset.manual !== 'true')) {
                let code = name.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '');
                if (!code.startsWith('sc_')) {
                    code = 'sc_' + code;
                }
                codeInput.value = code.substring(0, 30);
            }
        }

        function autoSuggestDeptCode(name) {
            const codeInput = document.getElementById('deptCodeInput');
            if (codeInput && (!codeInput.value || codeInput.dataset.manual !== 'true')) {
                let code = name.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '');
                if (!code.startsWith('dep_')) {
                    code = 'dep_' + code;
                }
                codeInput.value = code.substring(0, 30);
            }
        }

        document.getElementById('schoolCodeInput')?.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
        document.getElementById('deptCodeInput')?.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    </script>
</body>
</html>
