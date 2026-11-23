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

                <!-- One-Line Header (Zero Wasted Space) -->
                <div class="flex items-center gap-3 py-1">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white flex items-center justify-center shadow-xs shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </span>
                    <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Schools & Academic Departments</h1>
                </div>

                <!-- Academic Hierarchy Selector Cards: Schools, Departments, Programs -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Card 1: Collegiate Schools -->
                    <div id="cardTab-schools" 
                         onclick="switchDirectoryTab('schools', true)" 
                         class="group rounded-2xl p-5 transition-all cursor-pointer select-none {{ $activeTab === 'schools' ? 'bg-gradient-to-br from-indigo-50/90 via-white to-white border-2 border-indigo-600 ring-4 ring-indigo-500/10 shadow-md -translate-y-1' : 'bg-white border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-indigo-600 transition-colors">Collegiate Schools</span>
                            <div class="flex items-center gap-1.5">
                                <span id="badge-card-schools" class="{{ $activeTab === 'schools' ? '' : 'hidden' }} inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-600 text-white shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Active View
                                </span>
                                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </span>
                            </div>
                        </div>

                        <!-- Count & In-Card Add Button -->
                        <div class="mt-2.5 flex items-center justify-between">
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalSchoolsCount }}</div>
                            <button type="button" 
                                    onclick="event.stopPropagation(); openCreateSchoolModal()" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer"
                                    title="Add new Collegiate School">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                <span>Add School</span>
                            </button>
                        </div>

                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>Higher academic divisions</span>
                            <span class="text-indigo-600 font-bold text-[11px] group-hover:underline">View Schools →</span>
                        </div>
                    </div>

                    <!-- Card 2: Academic Departments -->
                    <div id="cardTab-departments" 
                         onclick="switchDirectoryTab('departments', true)" 
                         class="group rounded-2xl p-5 transition-all cursor-pointer select-none {{ $activeTab === 'departments' ? 'bg-gradient-to-br from-blue-50/90 via-white to-white border-2 border-blue-600 ring-4 ring-blue-500/10 shadow-md -translate-y-1' : 'bg-white border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-blue-600 transition-colors">Academic Departments</span>
                            <div class="flex items-center gap-1.5">
                                <span id="badge-card-departments" class="{{ $activeTab === 'departments' ? '' : 'hidden' }} inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-600 text-white shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Active View
                                </span>
                                <span class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                </span>
                            </div>
                        </div>

                        <!-- Count & In-Card Add Button -->
                        <div class="mt-2.5 flex items-center justify-between">
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalDepartmentsCount }}</div>
                            <button type="button" 
                                    onclick="event.stopPropagation(); openCreateDepartmentModal()" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer"
                                    title="Add new Academic Department">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                <span>Add Department</span>
                            </button>
                        </div>

                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>Active instructional units</span>
                            <span class="text-blue-600 font-bold text-[11px] group-hover:underline">View Departments →</span>
                        </div>
                    </div>

                    <!-- Card 3: Degree Programs -->
                    <div id="cardTab-programs" 
                         onclick="switchDirectoryTab('programs', true)" 
                         class="group rounded-2xl p-5 transition-all cursor-pointer select-none {{ $activeTab === 'programs' ? 'bg-gradient-to-br from-purple-50/90 via-white to-white border-2 border-purple-600 ring-4 ring-purple-500/10 shadow-md -translate-y-1' : 'bg-white border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 group-hover:text-purple-600 transition-colors">Degree Programs</span>
                            <div class="flex items-center gap-1.5">
                                <span id="badge-card-programs" class="{{ $activeTab === 'programs' ? '' : 'hidden' }} inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-600 text-white shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Active View
                                </span>
                                <span class="p-2.5 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                </span>
                            </div>
                        </div>

                        <!-- Count & In-Card Add Button -->
                        <div class="mt-2.5 flex items-center justify-between">
                            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalProgramsCount }}</div>
                            <button type="button" 
                                    onclick="event.stopPropagation(); openCreateProgramModal()" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 hover:bg-purple-600 text-purple-700 hover:text-white border border-purple-200 transition-all shadow-2xs hover:shadow-xs active:scale-95 cursor-pointer"
                                    title="Add new Degree Program under Department">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                <span>Add Program</span>
                            </button>
                        </div>

                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span>UG, PG, PhD & Diploma curricula</span>
                            <span class="text-purple-600 font-bold text-[11px] group-hover:underline">View Programs →</span>
                        </div>
                    </div>
                </div>

                                <!-- ==================================================================== -->
                <!-- MAIN ACADEMIC DIRECTORY: SCHOOLS, DEPARTMENTS & PROGRAMS -->
                <!-- ==================================================================== -->
                <div id="directorySection" class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden scroll-mt-6">

                    <!-- TAB 1: DEPARTMENTS DIRECTORY TABLE -->
                    <div id="section-departments" class="{{ $activeTab === 'departments' ? '' : 'hidden' }}">
                        <!-- Departments Sub-Toolbar (with School Filter Dropdown, Counter & Add Button) -->
                        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 tracking-tight">Academic Departments Directory</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $totalDepartmentsCount }} Units
                                </span>
                            </div>

                            <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap w-full sm:w-auto">
                                <!-- Live School Filter Dropdown -->
                                <div class="relative w-full sm:w-64">
                                    <select id="deptSectionSchoolFilter" 
                                            onchange="handleDepartmentSchoolDropdownFilter(this.value)" 
                                            class="w-full py-1.5 pl-3 pr-8 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                        <option value="all">All Collegiate Schools ({{ $totalDepartmentsCount }})</option>
                                        @foreach($schools as $sch)
                                            <option value="{{ $sch->code }}" {{ $selectedSchool === $sch->code ? 'selected' : '' }}>
                                                {{ $sch->name }} ({{ $sch->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-xs font-medium text-slate-500 whitespace-nowrap" id="departmentsCountDisplay">
                                    Showing <span class="font-bold text-slate-800" id="departmentsVisibleCount">{{ $departments->count() }}</span> of {{ $totalDepartmentsCount }}
                                </div>

                                <!-- Add Department Button -->
                                <button type="button" 
                                        onclick="openCreateDepartmentModal()" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white shadow-xs transition-all cursor-pointer whitespace-nowrap shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Add Department</span>
                                </button>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-200/80 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                        <th class="px-6 py-3.5">Academic Department</th>
                                        <th class="px-6 py-3.5 w-44 whitespace-nowrap">Department Code</th>
                                        <th class="px-6 py-3.5 whitespace-nowrap">Collegiate School</th>
                                        <th class="px-6 py-3.5 w-32 whitespace-nowrap text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white" id="departmentsTableBody">
                                    @forelse($departments as $dept)
                                        <tr class="department-table-row hover:bg-indigo-50/40 transition-colors"
                                            data-name="{{ strtolower($dept->name) }}"
                                            data-code="{{ strtolower($dept->code) }}"
                                            data-school="{{ strtolower($dept->school->name ?? '') }}"
                                            data-school-code="{{ strtolower($dept->school_id ?? '') }}"
                                            data-keywords="{{ strtolower($dept->name . ' ' . $dept->code . ' ' . ($dept->school->name ?? '') . ' ' . ($dept->school_id ?? '')) }}">
                                            
                                            <!-- Academic Department -->
                                            <td class="px-6 py-3.5 text-xs">
                                                <div class="flex items-center space-x-3.5">
                                                    <span class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs shrink-0 font-mono">
                                                        {{ strtoupper(substr(str_replace('dep_', '', $dept->code), 0, 2)) }}
                                                    </span>
                                                    <div>
                                                        <div class="font-extrabold text-slate-900 text-sm leading-snug">{{ $dept->name }}</div>
                                                        <div class="text-[11px] text-indigo-600 font-mono font-semibold">Code: {{ $dept->code }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Department Code -->
                                            <td class="px-6 py-3.5 whitespace-nowrap text-xs">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                                    {{ $dept->code }}
                                                </span>
                                            </td>

                                            <!-- Collegiate School -->
                                            <td class="px-6 py-3.5 whitespace-nowrap text-xs">
                                                @if($dept->school)
                                                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                                                        <span>{{ $dept->school->name }}</span>
                                                    </div>
                                                    <div class="text-[10px] font-mono text-slate-400 pl-3.5">School Code: {{ $dept->school->code }}</div>
                                                @else
                                                    <span class="text-slate-400 font-mono italic">Central / System</span>
                                                @endif
                                            </td>

                                            <!-- Actions: Left-aligned with compact gap -->
                                            <td class="px-6 py-3.5 whitespace-nowrap text-xs text-left">
                                                <div class="flex items-center justify-start gap-1.5">
                                                    <!-- Inspect Button (View - Sky Blue) -->
                                                    <button type="button" 
                                                            onclick="openInspectorByDeptCode('{{ $dept->code }}')" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 border border-sky-200/90 hover:bg-sky-600 hover:text-white transition-all shadow-2xs hover:shadow-xs cursor-pointer" 
                                                            title="View Department Details">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Edit Department Button (Indigo) -->
                                                    <button type="button" 
                                                            onclick="openEditDepartmentModal({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ $dept->code }}', '{{ $dept->school_id }}')" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200/90 hover:bg-indigo-600 hover:text-white transition-all shadow-2xs hover:shadow-xs cursor-pointer" 
                                                            title="Edit Department">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Department Button (Cascading confirmation modal) -->
                                                    <button type="button" 
                                                            onclick="openDeleteDepartmentCascadeModal({{ $dept->id }})" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-200/90 hover:bg-rose-600 hover:text-white transition-all shadow-2xs hover:shadow-xs cursor-pointer" 
                                                            title="Delete Department & Linked Academic Data">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                No academic departments found matching your search.
                                            </td>
                                        </tr>
                                    @endforelse
                                    <!-- Live filter empty state row -->
                                    <tr id="departmentsNoMatchRow" class="hidden">
                                        <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            <div class="text-xs font-bold text-slate-700">No academic departments match your filter</div>
                                            <div class="text-[11px] text-slate-400 mt-1">Try another search keyword or select a different school</div>
                                            <button type="button" onclick="clearLiveFilter()" class="mt-3 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors cursor-pointer">Clear filter</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Departments Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="text-xs text-slate-500 font-medium">
                                Total {{ $totalDepartmentsCount }} academic departments registered across {{ $totalSchoolsCount }} collegiate schools
                            </div>
                            @if($departments->hasPages())
                                <div>
                                    {{ $departments->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- TAB 2: SCHOOLS DIRECTORY TABLE -->
                    <div id="section-schools" class="{{ $activeTab === 'schools' ? '' : 'hidden' }} p-4 sm:p-6 flex flex-col items-center">
                        <div class="w-full max-w-3xl bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                            <!-- Schools Sub-Toolbar (Compact Single Line) -->
                            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 tracking-tight">Collegiate Schools Directory</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $totalSchoolsCount }} Divisions
                                    </span>
                                </div>
                                <div class="text-xs font-medium text-slate-500" id="schoolsCountDisplay">
                                    Showing <span class="font-bold text-slate-800">{{ $schoolsList->count() }}</span> of {{ $totalSchoolsCount }} schools
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full divide-y divide-slate-200 text-left">
                                    <thead class="bg-slate-50/90 border-b border-slate-200">
                                        <tr>
                                            <th class="px-5 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wider text-left">Collegiate School</th>
                                            <th class="w-32 px-3 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wider text-left">School Code</th>
                                            <th class="w-24 px-3 py-3 text-[11px] font-black text-slate-500 uppercase tracking-wider text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white" id="schoolsTableBody">
                                        @forelse($schoolsList as $sch)
                                            <tr class="school-table-row hover:bg-indigo-50/30 transition-colors"
                                                data-name="{{ strtolower($sch->name) }}"
                                                data-code="{{ strtolower($sch->code) }}"
                                                data-keywords="{{ strtolower($sch->name . ' ' . $sch->code) }}">
                                                <!-- School Name -->
                                                <td class="px-5 py-2.5 text-xs text-left">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-8 h-8 bg-gradient-to-tr from-indigo-600 via-indigo-700 to-blue-600 text-white rounded-lg shadow-2xs flex items-center justify-center shrink-0">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                        </span>
                                                        <div class="font-extrabold text-slate-900 text-xs sm:text-sm tracking-tight leading-snug">{{ $sch->name }}</div>
                                                    </div>
                                                </td>

                                                <!-- School Code -->
                                                <td class="w-32 px-3 py-2.5 whitespace-nowrap text-xs text-left">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200 shadow-2xs">
                                                        {{ $sch->code }}
                                                    </span>
                                                </td>

                                                <!-- Actions: Edit (Indigo) | Delete (Rose) - Left-aligned -->
                                                <td class="w-24 px-3 py-2.5 whitespace-nowrap text-xs text-left">
                                                    <div class="flex items-center justify-start gap-1.5">
                                                        <!-- Edit School -->
                                                        <button type="button" 
                                                                onclick="openEditSchoolModal({{ $sch->id }}, '{{ addslashes($sch->name) }}', '{{ $sch->code }}')" 
                                                                class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200/90 hover:bg-indigo-600 hover:text-white transition-all shadow-2xs hover:shadow-xs cursor-pointer" 
                                                                title="Edit School">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                            </svg>
                                                        </button>

                                                        <!-- Delete School (Cascading confirmation modal) -->
                                                        <button type="button" 
                                                                onclick="openDeleteSchoolCascadeModal({{ $sch->id }})" 
                                                                class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-200/90 hover:bg-rose-600 hover:text-white transition-all shadow-2xs hover:shadow-xs cursor-pointer" 
                                                                title="Delete School & Linked Academic Data">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    No collegiate schools found matching your search.
                                                </td>
                                            </tr>
                                        @endforelse
                                        <!-- Live filter empty state row -->
                                        <tr id="schoolsNoMatchRow" class="hidden">
                                            <td colspan="3" class="px-6 py-10 text-center text-slate-400">
                                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                <div class="text-xs font-bold text-slate-700">No collegiate schools match your live search</div>
                                                <div class="text-[11px] text-slate-400 mt-1">Try another school name or code, or clear your search</div>
                                                <button type="button" onclick="clearLiveFilter()" class="mt-3 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors cursor-pointer">Clear search</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Footer -->
                            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="text-xs text-slate-500 font-medium">
                                    Showing {{ $schoolsList->firstItem() ?? 0 }} to {{ $schoolsList->lastItem() ?? 0 }} of {{ $schoolsList->total() }} schools
                                </div>
                                <div>
                                    {{ $schoolsList->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================================================================== -->
                    <!-- TAB 3: DEGREE PROGRAMS UNDER DEPARTMENTS -->
                    <!-- ==================================================================== -->
                    <div id="section-programs" class="{{ $activeTab === 'programs' ? '' : 'hidden' }}">
                        <!-- Programs Header / Sub-toolbar (Strictly Two Fixed Lines - Never Wraps to Three) -->
                        <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/70 space-y-3">
                            <!-- Line 1: Collegiate School & Department Filters + Add Program Button -->
                            <div class="flex items-center justify-between gap-3 flex-nowrap w-full">
                                <div class="flex items-center gap-2.5 flex-nowrap flex-1 min-w-0">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-black text-indigo-700 bg-indigo-50 border border-indigo-200/80 uppercase tracking-wider shrink-0">
                                        <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                        <span>Filter Programs:</span>
                                    </span>
                                    
                                    <!-- 1. Collegiate School Filter -->
                                    <select id="progSchoolFilter" 
                                            onchange="handleProgramSchoolFilterChange()" 
                                            title="Filter by Collegiate School"
                                            class="text-xs py-2 pl-3 pr-8 bg-white border border-slate-300 rounded-xl text-slate-800 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-2xs transition-all flex-1 min-w-0 truncate">
                                        <option value="all">All Collegiate Schools ({{ $schools->count() }})</option>
                                        @foreach($schools as $sch)
                                            <option value="{{ $sch->code }}" title="{{ $sch->name }}">
                                                {{ ucwords(strtolower($sch->name)) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- 2. Governing Department Filter (Cascaded by School) -->
                                    <select id="progDeptFilter" 
                                            onchange="handleProgramFilterChange()" 
                                            title="Filter by Governing Department"
                                            class="text-xs py-2 pl-3 pr-8 bg-white border border-slate-300 rounded-xl text-slate-800 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-2xs transition-all flex-1 min-w-0 truncate">
                                        <option value="all" data-school="all">All Departments ({{ $allDepartmentsForDropdown->count() }})</option>
                                        @foreach($allDepartmentsForDropdown as $deptOpt)
                                            <option value="{{ $deptOpt->id }}" data-school="{{ $deptOpt->school_id }}" title="{{ $deptOpt->name }}">
                                                {{ $deptOpt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Add Degree Program Button -->
                                <button type="button" 
                                        onclick="openCreateProgramModal()" 
                                        class="inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold text-xs py-2 px-3.5 rounded-xl shadow-xs hover:shadow-indigo-600/25 transition-all cursor-pointer whitespace-nowrap shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Add Degree Program</span>
                                </button>
                            </div>

                            <!-- Line 2: Academic Level Filter + Search Input + Reset Button + Count -->
                            <div class="flex items-center justify-between gap-3 flex-nowrap w-full pt-2.5 border-t border-slate-200/60">
                                <div class="flex items-center gap-2.5 flex-nowrap flex-1 min-w-0">
                                    <!-- 3. Academic Level Filter -->
                                    <select id="progLevelFilter" 
                                            onchange="handleProgramFilterChange()" 
                                            title="Filter by Academic Level"
                                            class="text-xs py-2 pl-3 pr-8 bg-white border border-slate-300 rounded-xl text-slate-800 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-2xs transition-all w-[220px] shrink-0">
                                        <option value="all">All Academic Levels</option>
                                        <option value="UG">Undergraduate (UG)</option>
                                        <option value="PG">Postgraduate (PG)</option>
                                        <option value="PhD">Doctoral (Ph.D)</option>
                                        <option value="Diploma">Diploma / Polytechnic</option>
                                    </select>

                                    <!-- 4. Quick Live Search Input -->
                                    <div class="relative flex-1 max-w-sm min-w-0">
                                        <input type="text" 
                                               id="programsSearchInput" 
                                               oninput="handleProgramFilterChange()" 
                                               placeholder="Search programs by name or code..." 
                                               class="w-full text-xs py-2 pl-8 pr-3 bg-white border border-slate-300 rounded-xl text-slate-900 font-semibold placeholder:text-slate-400 placeholder:font-normal focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs transition-all">
                                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>

                                    <!-- 5. Reset Filter Button -->
                                    <button type="button" 
                                            id="progResetFiltersBtn" 
                                            onclick="clearLiveFilter()" 
                                            title="Reset all filters to default" 
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 bg-white hover:bg-rose-50 border border-slate-300 hover:border-rose-300 transition-all cursor-pointer whitespace-nowrap shadow-2xs active:scale-95 shrink-0 opacity-40 pointer-events-none">
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        <span>Reset</span>
                                    </button>
                                </div>

                                <!-- Count Display -->
                                <div id="programsCountDisplay" class="text-xs font-semibold text-slate-500 whitespace-nowrap shrink-0">
                                    Showing <span class="font-bold text-slate-800">{{ $programsList->count() }}</span> of {{ $totalProgramsCount }} degree programs
                                </div>
                            </div>
                        </div>

                        <!-- Programs Table -->
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-left border-collapse table-fixed">
                                <thead>
                                    <tr class="border-b border-slate-200/80 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                                        <th class="px-6 py-3.5 w-[66%]">Degree Program</th>
                                        <th class="px-4 py-3.5 w-[17%] text-center">Level</th>
                                        <th class="px-6 py-3.5 w-[17%] text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white" id="programsTableBody">
                                    @forelse($programsList as $prog)
                                        <tr class="program-table-row hover:bg-indigo-50/40 transition-colors"
                                            data-id="{{ $prog->id }}"
                                            data-name="{{ strtolower($prog->name) }}"
                                            data-code="{{ strtolower($prog->code) }}"
                                            data-level="{{ strtolower($prog->level) }}"
                                            data-dept-id="{{ $prog->department_id }}"
                                            data-dept="{{ strtolower($prog->department->name ?? '') }}"
                                            data-dept-code="{{ strtolower($prog->department->code ?? '') }}"
                                            data-school="{{ strtolower($prog->department->school->name ?? '') }}"
                                            data-school-code="{{ strtolower($prog->department->school_id ?? ($prog->department->school->code ?? '')) }}"
                                            data-school-id="{{ $prog->department->school->id ?? '' }}"
                                            data-duration="{{ $prog->duration_years }}"
                                            data-students="{{ $prog->student_count ?? 0 }}">
                                            <!-- 1. Degree Program (Fully visible - no truncation) -->
                                            <td class="px-6 py-4 text-xs">
                                                <div class="flex items-start sm:items-center space-x-3">
                                                    <span class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs shrink-0 mt-0.5 sm:mt-0">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                                    </span>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="font-extrabold text-slate-900 text-sm leading-snug break-words" title="{{ $prog->name }}">{{ $prog->name }}</div>
                                                        <div class="text-[11px] text-purple-600 font-mono font-semibold mt-0.5">Code: {{ $prog->code }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- 2. Academic Level (Centered) -->
                                            <td class="px-4 py-4 whitespace-nowrap text-xs text-center">
                                                @php
                                                    $lvlBadge = match($prog->level) {
                                                        'UG' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'PG' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                        'PhD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'Diploma' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $lvlBadge }}">
                                                    {{ $prog->level }}
                                                </span>
                                            </td>

                                            <!-- 3. Actions (Centered) -->
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <!-- View Details (Eye) -->
                                                    <button type="button" 
                                                            onclick="openProgramDetailsModal({{ $prog->id }})" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white transition-colors cursor-pointer" 
                                                            title="View Program Details">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Edit Program (Pencil) -->
                                                    <button type="button" 
                                                            onclick="openEditProgramModal({{ $prog->id }}, '{{ addslashes($prog->name) }}', '{{ $prog->code }}', {{ $prog->department_id }}, '{{ $prog->level }}', {{ $prog->duration_years }})" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors cursor-pointer" 
                                                            title="Edit Program">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Program (Trash) -->
                                                    <button type="button" 
                                                            onclick="openDeleteProgramCascadeModal({{ $prog->id }})" 
                                                            class="w-7 h-7 p-1.5 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer" 
                                                            title="Delete Program">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 mx-auto flex items-center justify-center text-slate-400 mb-3">
                                                    🎓
                                                </div>
                                                <h4 class="font-bold text-slate-700 text-sm">No Degree Programs Found</h4>
                                                <p class="text-xs text-slate-400 mt-1">Create degree programs under academic departments for student enrollment.</p>
                                                <button type="button" onclick="openCreateProgramModal()" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                                                    + Add 1st Degree Program
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <!-- Live filter empty state row -->
                                    <tr id="programsNoMatchRow" class="hidden">
                                        <td colspan="3" class="px-6 py-10 text-center text-slate-400">
                                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            <div class="text-xs font-bold text-slate-700">No degree programs match your filter</div>
                                            <div class="text-[11px] text-slate-400 mt-1">Try another search keyword or clear your filter</div>
                                            <button type="button" onclick="clearLiveFilter()" class="mt-3 px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors cursor-pointer">Clear filter</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="text-xs text-slate-500 font-medium" id="programsCountDisplay">
                                Showing <span class="font-bold text-slate-800">{{ $programsList->count() }}</span> of {{ $totalProgramsCount }} degree programs
                            </div>
                            @if($programsList->hasPages())
                                <div>
                                    {{ $programsList->links() }}
                                </div>
                            @endif
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
    <!-- DUAL-LEVEL INSPECTOR MODAL (CENTERED POPUP DIALOG) -->
    <!-- ==================================================================== -->
    <!-- DEPARTMENT DETAILS MODAL (CENTERED POPUP DIALOG) -->
    <div id="inspectorBackdrop" 
         onclick="closeDepartmentInspector()" 
         class="fixed inset-0 z-[140] hidden bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 opacity-0 flex items-center justify-center p-3 sm:p-5">
        
        <div id="inspectorDrawer" 
             onclick="event.stopPropagation()" 
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden max-h-[85vh] transform transition-all duration-300 ease-out scale-95 opacity-0 text-left">
        
            <!-- Clean Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between border-b border-indigo-900/40 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 id="inspectorModalDeptName" class="text-base font-extrabold text-white leading-tight truncate">Department Details</h3>
                            <span id="inspectorModalDeptCodeBadge" class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30"></span>
                        </div>
                        <p id="inspectorModalSchoolSubtitle" class="text-xs text-indigo-300 mt-0.5 truncate">Collegiate School Overview</p>
                    </div>
                </div>

                <button type="button" 
                        onclick="closeDepartmentInspector()" 
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all cursor-pointer shrink-0 ml-3"
                        title="Close (Esc)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Content Body: Displays ONLY Programs, Faculty, Students, Courses -->
            <div id="inspectorContentBody" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                <!-- Dynamically populated via renderInspectorDrawer -->
            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3 shrink-0">
                <button type="button" 
                        onclick="closeDepartmentInspector()" 
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold border border-slate-200 transition-all cursor-pointer shadow-2xs">
                    Close
                </button>

                <button type="button" 
                        onclick="editCurrentInspectorDept()" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Edit Department</span>
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- ==================================================================== -->
    <!-- MODAL 1: CREATE SCHOOL -->
    <!-- ==================================================================== -->
    <div id="createSchoolModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" onclick="if(event.target === this) closeModal('createSchoolModal')">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 transform transition-all flex flex-col">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold">Add Collegiate School</h3>
                        <p class="text-[11px] text-indigo-300">Register a new collegiate academic school</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('createSchoolModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('academic.schools.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="schoolNameInput" oninput="autoSuggestSchoolCode(this.value)" required placeholder="e.g. SCHOOL OF ARTIFICIAL INTELLIGENCE & COMPUTING" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all uppercase">
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
                    <input type="text" name="name" id="editSchoolName" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all uppercase">
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
    <!-- MODAL 3: CREATE DEPARTMENT (WITH RESPECTIVE SCHOOL & PARSER LINK) -->
    <!-- ==================================================================== -->
    <div id="createDepartmentModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all max-h-[90vh] flex flex-col">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold">Add Academic Department</h3>
                        <p class="text-[11px] text-slate-400">Assign new academic department to governing school</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('createDepartmentModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('academic.departments.store') }}" method="POST" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Governing School <span class="text-rose-500">*</span></label>
                    <select name="school_id" id="createDeptSchoolSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
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
    <!-- MODAL 6: CASCADING DELETE SCHOOL CONFIRMATION -->
    <!-- ==================================================================== -->
    <div id="deleteSchoolCascadeModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-100 transform transition-all max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-rose-700 via-rose-800 to-rose-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-2xl bg-white/10 text-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-black tracking-tight">Delete Collegiate School</h3>
                        <p class="text-[11px] text-rose-200/90 font-medium">Permanently deletes school and all linked academic records</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('deleteSchoolCascadeModal')" class="text-rose-200 hover:text-white p-1 rounded-xl hover:bg-white/10 focus:outline-none transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar text-left">
                <!-- Target School Details Banner -->
                <div class="p-3.5 bg-rose-50/70 border border-rose-200/80 rounded-2xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </span>
                        <div class="truncate">
                            <div class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Target School to Delete</div>
                            <div id="cascadeModalSchoolName" class="text-sm font-extrabold text-slate-900 truncate">School Name</div>
                        </div>
                    </div>
                    <span id="cascadeModalSchoolCode" class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-white text-rose-700 border border-rose-200 shadow-2xs">sc_code</span>
                </div>

                <!-- Warning Notice -->
                <div class="p-3 bg-amber-50 border border-amber-200/90 rounded-2xl flex items-start gap-2.5 text-xs text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <span class="font-extrabold">Cascading Deletion Notice:</span>
                        Deleting this collegiate school will permanently purge all linked departments, degree programs, and curriculum data. Affiliated faculty and student user accounts will be safely unbound.
                    </div>
                </div>

                <!-- Metric Impact Cards (4 items) -->
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Impact Summary</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Departments</div>
                            <div id="cascadeModalDeptCount" class="text-lg font-black text-rose-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Delete</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Degree Programs</div>
                            <div id="cascadeModalProgCount" class="text-lg font-black text-rose-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Delete</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Faculty</div>
                            <div id="cascadeModalFacultyCount" class="text-lg font-black text-amber-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Unbind</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Students</div>
                            <div id="cascadeModalStudentCount" class="text-lg font-black text-amber-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Unbind</div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Breakdown of Linked Departments & Programs -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Linked Academic Divisions & Curricula</div>
                        <span id="cascadeModalListSummary" class="text-[11px] font-bold text-slate-400"></span>
                    </div>

                    <div id="cascadeModalLinkedDataContainer" class="max-h-56 overflow-y-auto space-y-2.5 p-2.5 bg-slate-50/80 rounded-2xl border border-slate-200/90 custom-scrollbar">
                        <!-- Populated dynamically by JavaScript -->
                    </div>
                </div>

                <!-- Safety Confirmation Checkbox -->
                <div class="p-3 bg-rose-50/50 border border-rose-200/70 rounded-2xl">
                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="cascadeDeleteConfirmCheckbox" onchange="toggleCascadeDeleteSubmit(this.checked)" class="mt-0.5 w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500 cursor-pointer">
                        <span class="text-xs font-semibold text-slate-700 leading-snug">
                            I acknowledge and confirm that deleting <span id="cascadeModalSchoolNameInCheck" class="font-bold text-slate-900">this school</span> will permanently delete all associated departments, degree programs, and curriculum data.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer Form -->
            <form id="cascadeDeleteSchoolForm" method="POST" class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('deleteSchoolCascadeModal')" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all shadow-2xs cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="cascadeDeleteSubmitBtn" disabled class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-rose-600 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Permanently Delete School & Linked Data</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL 7: CASCADING DELETE DEPARTMENT CONFIRMATION -->
    <!-- ==================================================================== -->
    <div id="deleteDepartmentCascadeModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-100 transform transition-all max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-rose-700 via-rose-800 to-rose-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-2xl bg-white/10 text-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-black tracking-tight">Delete Academic Department</h3>
                        <p class="text-[11px] text-rose-200/90 font-medium">Permanently deletes department and all linked degree programs & records</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('deleteDepartmentCascadeModal')" class="text-rose-200 hover:text-white p-1 rounded-xl hover:bg-white/10 focus:outline-none transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar text-left">
                <!-- Target Department Details Banner -->
                <div class="p-3.5 bg-rose-50/70 border border-rose-200/80 rounded-2xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-2xs font-mono font-bold text-xs" id="cascadeModalDeptAvatar">
                            DP
                        </span>
                        <div class="truncate">
                            <div class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Target Department to Delete</div>
                            <div id="cascadeModalDeptName" class="text-sm font-extrabold text-slate-900 truncate">Department Name</div>
                            <div id="cascadeModalDeptSchool" class="text-[11px] text-slate-500 truncate mt-0.5">Collegiate School</div>
                        </div>
                    </div>
                    <span id="cascadeModalDeptCode" class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-white text-rose-700 border border-rose-200 shadow-2xs">dep_code</span>
                </div>

                <!-- Warning Notice -->
                <div class="p-3 bg-amber-50 border border-amber-200/90 rounded-2xl flex items-start gap-2.5 text-xs text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <span class="font-extrabold">Cascading Deletion Notice:</span>
                        Deleting this academic department will permanently purge all linked degree programs, courses, syllabus materials, and assignments. Affiliated faculty and enrolled students will be safely unbound.
                    </div>
                </div>

                <!-- Metric Impact Cards (4 items) -->
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Impact Summary</div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Degree Programs</div>
                            <div id="cascadeModalDeptProgCount" class="text-lg font-black text-rose-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Delete</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Courses</div>
                            <div id="cascadeModalDeptCourseCount" class="text-lg font-black text-rose-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Delete</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Faculty</div>
                            <div id="cascadeModalDeptFacultyCount" class="text-lg font-black text-amber-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Unbind</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Students</div>
                            <div id="cascadeModalDeptStudentCount" class="text-lg font-black text-amber-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Unbind</div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Breakdown of Linked Programs -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Linked Degree Programs to be Removed</div>
                        <span id="cascadeModalDeptListSummary" class="text-[11px] font-bold text-slate-400"></span>
                    </div>

                    <div id="cascadeModalDeptLinkedDataContainer" class="max-h-56 overflow-y-auto space-y-2 p-2.5 bg-slate-50/80 rounded-2xl border border-slate-200/90 custom-scrollbar">
                        <!-- Populated dynamically by JavaScript -->
                    </div>
                </div>

                <!-- Safety Confirmation Checkbox -->
                <div class="p-3 bg-rose-50/50 border border-rose-200/70 rounded-2xl">
                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="cascadeDeleteDeptConfirmCheckbox" onchange="toggleCascadeDeleteDeptSubmit(this.checked)" class="mt-0.5 w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500 cursor-pointer">
                        <span class="text-xs font-semibold text-slate-700 leading-snug">
                            I acknowledge and confirm that deleting <span id="cascadeModalDeptNameInCheck" class="font-bold text-slate-900">this department</span> will permanently delete all associated degree programs, courses, and curriculum data.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer Form -->
            <form id="cascadeDeleteDeptForm" method="POST" class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('deleteDepartmentCascadeModal')" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all shadow-2xs cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="cascadeDeleteDeptSubmitBtn" disabled class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-rose-600 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Permanently Delete Department & Linked Data</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL: CREATE DEGREE PROGRAM -->
    <!-- ==================================================================== -->
    <div id="createProgramModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all max-h-[90vh] flex flex-col">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold">Add Degree Program</h3>
                        <p class="text-[11px] text-slate-400">Create degree offering under an Academic Department</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('createProgramModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('academic.programs.store') }}" method="POST" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                @csrf
                <!-- 1. Collegiate School Dropdown -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Collegiate School <span class="text-rose-500">*</span></label>
                    <select id="createProgSchoolSelect" onchange="handleCreateModalSchoolChange(this.value)" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">-- Select Collegiate School --</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch->code }}">
                                {{ $sch->name }} ({{ $sch->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Parent Department Dropdown (Filtered based on School) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent Department <span class="text-rose-500">*</span></label>
                    <select name="department_id" id="createProgDeptSelect" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">-- Select Academic Department --</option>
                        @foreach($allDepartmentsForDropdown as $deptOpt)
                            <option value="{{ $deptOpt->id }}" data-school="{{ $deptOpt->school_id }}">
                                {{ $deptOpt->name }} ({{ $deptOpt->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Program Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="progNameInput" oninput="autoSuggestProgramCode(this.value)" required placeholder="e.g. B.Tech in Artificial Intelligence & Machine Learning" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Program Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="progCodeInput" required placeholder="e.g. btech_aiml" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Unique identifier (e.g. <span class="font-mono">btech_cse</span>, <span class="font-mono">mtech_vlsi</span>, <span class="font-mono">phd_ai</span>)</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Degree Level <span class="text-rose-500">*</span></label>
                        <select name="level" id="createProgLevel" onchange="autoAdjustProgramDuration(this.value, 'createProgDuration')" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="UG">UG (Undergraduate)</option>
                            <option value="PG">PG (Postgraduate)</option>
                            <option value="PhD">PhD (Doctoral)</option>
                            <option value="Diploma">Diploma / Polytechnic</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Duration (Years) <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_years" id="createProgDuration" value="4" min="1" max="10" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('createProgramModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Save Degree Program
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL: EDIT DEGREE PROGRAM -->
    <!-- ==================================================================== -->
    <div id="editProgramModal" class="fixed inset-0 z-[140] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 transform transition-all max-h-[90vh] flex flex-col">
            <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-2.5">
                    <span class="p-1.5 bg-white/10 rounded-xl text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </span>
                    <h3 class="text-base font-bold">Edit Degree Program</h3>
                </div>
                <button type="button" onclick="closeModal('editProgramModal')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="editProgramForm" method="POST" class="p-6 space-y-4 overflow-y-auto custom-scrollbar">
                @csrf
                @method('PUT')
                <!-- 1. Collegiate School Dropdown -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Collegiate School <span class="text-rose-500">*</span></label>
                    <select id="editProgSchool" onchange="handleEditModalSchoolChange(this.value)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">-- Select Collegiate School --</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch->code }}">
                                {{ $sch->name }} ({{ $sch->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Parent Department Dropdown (Filtered by School) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent Department <span class="text-rose-500">*</span></label>
                    <select name="department_id" id="editProgDept" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                        <option value="">-- Select Academic Department --</option>
                        @foreach($allDepartmentsForDropdown as $deptOpt)
                            <option value="{{ $deptOpt->id }}" data-school="{{ $deptOpt->school_id }}">
                                {{ $deptOpt->name }} ({{ $deptOpt->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Program Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="editProgName" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Program Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" id="editProgCode" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Degree Level <span class="text-rose-500">*</span></label>
                        <select name="level" id="editProgLevel" onchange="autoAdjustProgramDuration(this.value, 'editProgDuration')" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                            <option value="UG">UG (Undergraduate)</option>
                            <option value="PG">PG (Postgraduate)</option>
                            <option value="PhD">PhD (Doctoral)</option>
                            <option value="Diploma">Diploma / Polytechnic</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Duration (Years) <span class="text-rose-500">*</span></label>
                        <input type="number" name="duration_years" id="editProgDuration" min="1" max="10" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editProgramModal')" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/25 cursor-pointer">
                        Update Degree Program
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL: PROGRAM DETAILS INSPECTOR (PERFECTLY CENTERED POPUP DIALOG) -->
    <!-- ==================================================================== -->
    <div id="programDetailsModal" 
         onclick="if(event.target === this) closeModal('programDetailsModal')" 
         class="fixed inset-0 z-[150] hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-4 sm:p-6 flex items-center justify-center transition-all duration-200">
        
        <div id="programDetailsCard" 
             onclick="event.stopPropagation()" 
             class="relative w-full max-w-xl my-auto bg-white rounded-3xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden max-h-[90vh] text-left transform transition-all duration-200">
        
            <!-- Radiant Royal Gradient Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 text-white flex items-center justify-between border-b border-indigo-500/30 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-10 h-10 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-white shrink-0 shadow-2xs backdrop-blur-xs">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 id="progModalName" class="text-base sm:text-lg font-black text-white leading-tight truncate">Degree Program Details</h3>
                            <span id="progModalCodeBadge" class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono font-bold bg-white/20 text-white border border-white/30 backdrop-blur-xs">btech_cse</span>
                            <span id="progModalLevelBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white text-indigo-700 shadow-2xs">UG</span>
                        </div>
                        <p id="progModalSubtitle" class="text-xs text-indigo-100 font-medium mt-0.5 truncate">Academic Offering Overview</p>
                    </div>
                </div>

                <button type="button" 
                        onclick="closeModal('programDetailsModal')" 
                        class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/25 text-white flex items-center justify-center transition-all cursor-pointer shrink-0 ml-3"
                        title="Close (Esc)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Content Body: Centered details displaying key academic metrics -->
            <div id="progModalContentBody" class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-5 custom-scrollbar text-left">
                <!-- Academic Hierarchy Card -->
                <div class="p-4 bg-slate-50/90 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Institutional Governance</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 bg-white rounded-xl border border-slate-200/80 flex items-center gap-3 shadow-2xs">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Governing Department</div>
                                <div id="progModalDeptName" class="text-xs font-bold text-slate-800 truncate">Department Name</div>
                                <div id="progModalDeptCode" class="text-[10px] font-mono text-indigo-600 font-semibold">dep_code</div>
                            </div>
                        </div>

                        <div class="p-3 bg-white rounded-xl border border-slate-200/80 flex items-center gap-3 shadow-2xs">
                            <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                            </span>
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Collegiate School</div>
                                <div id="progModalSchoolName" class="text-xs font-bold text-slate-800 truncate">School Name</div>
                                <div id="progModalSchoolCode" class="text-[10px] font-mono text-purple-600 font-semibold">sc_code</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Metrics: Level, Duration, Students, Status -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <!-- Academic Level Card -->
                    <div class="p-3.5 bg-blue-50/70 rounded-2xl border border-blue-100/90 text-center flex flex-col justify-between shadow-2xs">
                        <div class="flex items-center justify-center gap-1.5 text-blue-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Level</span>
                        </div>
                        <div id="progModalLevelVal" class="text-xl sm:text-2xl font-black text-slate-900 my-1">UG</div>
                        <span id="progModalLevelLabel" class="text-[10px] text-slate-500 font-semibold truncate">Undergraduate</span>
                    </div>

                    <!-- Duration Card -->
                    <div class="p-3.5 bg-purple-50/70 rounded-2xl border border-purple-100/90 text-center flex flex-col justify-between shadow-2xs">
                        <div class="flex items-center justify-center gap-1.5 text-purple-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Duration</span>
                        </div>
                        <div id="progModalDurationVal" class="text-xl sm:text-2xl font-black text-slate-900 my-1">4 Yrs</div>
                        <span id="progModalSemestersLabel" class="text-[10px] text-slate-500 font-semibold truncate">8 Semesters</span>
                    </div>

                    <!-- Enrolled Students Card -->
                    <div class="p-3.5 bg-emerald-50/70 rounded-2xl border border-emerald-100/90 text-center flex flex-col justify-between shadow-2xs">
                        <div class="flex items-center justify-center gap-1.5 text-emerald-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Students</span>
                        </div>
                        <div id="progModalStudentsVal" class="text-xl sm:text-2xl font-black text-slate-900 my-1">0</div>
                        <span class="text-[10px] text-slate-500 font-semibold truncate">Enrolled Profiles</span>
                    </div>

                    <!-- Program Status Card -->
                    <div class="p-3.5 bg-indigo-50/70 rounded-2xl border border-indigo-100/90 text-center flex flex-col justify-between shadow-2xs">
                        <div class="flex items-center justify-center gap-1.5 text-indigo-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-wider">Status</span>
                        </div>
                        <div class="text-base sm:text-lg font-black text-emerald-600 my-1 flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold truncate">Curriculum Ready</span>
                    </div>
                </div>

                <!-- Program Description / Summary Box -->
                <div class="p-4 bg-slate-50/90 rounded-2xl border border-slate-200/80 text-xs text-slate-600 space-y-1.5 shadow-2xs">
                    <div class="font-extrabold text-slate-800 text-xs">Curriculum Specification</div>
                    <p id="progModalSummaryText" class="text-slate-600 leading-relaxed">
                        This degree program is officially recognized under university academic regulations. Students enrolled in this program are assigned courses, faculty advisors, and evaluation criteria in alignment with collegiate department standards.
                    </p>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-3 shrink-0">
                <button type="button" 
                        onclick="closeModal('programDetailsModal')" 
                        class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold border border-slate-200 transition-all cursor-pointer shadow-2xs active:scale-95">
                    Close
                </button>

                <button type="button" 
                        id="progModalEditBtn" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow-indigo-600/25 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <span>Edit Program</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ==================================================================== -->
    <!-- MODAL: DELETE DEGREE PROGRAM CASCADE -->
    <!-- ==================================================================== -->
    <div id="deleteProgramCascadeModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-100 transform transition-all max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-rose-700 via-rose-800 to-rose-900 text-white flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <span class="w-10 h-10 rounded-2xl bg-white/10 text-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-black tracking-tight">Delete Degree Program</h3>
                        <p class="text-[11px] text-rose-200/90 font-medium">Permanently removes program from academic catalog</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('deleteProgramCascadeModal')" class="text-rose-200 hover:text-white p-1 rounded-xl hover:bg-white/10 focus:outline-none transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto space-y-4 custom-scrollbar text-left">
                <!-- Target Program Details Banner -->
                <div class="p-3.5 bg-rose-50/70 border border-rose-200/80 rounded-2xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-2xs font-mono font-bold text-xs" id="cascadeModalProgAvatar">
                            PR
                        </span>
                        <div class="truncate">
                            <div class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Target Program to Delete</div>
                            <div id="cascadeModalProgName" class="text-sm font-extrabold text-slate-900 truncate">Degree Program Name</div>
                            <div id="cascadeModalProgDeptSchool" class="text-[11px] text-slate-500 truncate mt-0.5">Department & School</div>
                        </div>
                    </div>
                    <span id="cascadeModalProgCode" class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-white text-rose-700 border border-rose-200 shadow-2xs">btech_cse</span>
                </div>

                <!-- Warning Notice -->
                <div class="p-3 bg-amber-50 border border-amber-200/90 rounded-2xl flex items-start gap-2.5 text-xs text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <span class="font-extrabold">Deletion Safety Notice:</span>
                        Deleting this degree program will permanently remove it from the academic curriculum catalog. Any enrolled student profiles will be safely unbound without data loss.
                    </div>
                </div>

                <!-- Metric Impact Cards (3 items) -->
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Impact Summary</div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Enrolled Students</div>
                            <div id="cascadeModalProgStudentCount" class="text-lg font-black text-amber-600 mt-0.5">0</div>
                            <div class="text-[10px] text-slate-500 font-medium">To Unbind</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Duration</div>
                            <div id="cascadeModalProgDuration" class="text-lg font-black text-slate-800 mt-0.5">4 Yrs</div>
                            <div id="cascadeModalProgSemesters" class="text-[10px] text-slate-500 font-medium">8 Semesters</div>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                            <div class="text-[10px] font-bold uppercase text-slate-400">Degree Level</div>
                            <div id="cascadeModalProgLevel" class="text-lg font-black text-indigo-600 mt-0.5">UG</div>
                            <div class="text-[10px] text-slate-500 font-medium">Curriculum</div>
                        </div>
                    </div>
                </div>

                <!-- Safety Confirmation Checkbox -->
                <div class="p-3 bg-rose-50/50 border border-rose-200/70 rounded-2xl">
                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="cascadeDeleteProgConfirmCheckbox" onchange="toggleCascadeDeleteProgSubmit(this.checked)" class="mt-0.5 w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500 cursor-pointer">
                        <span class="text-xs font-semibold text-slate-700 leading-snug">
                            I acknowledge and confirm that deleting <span id="cascadeModalProgNameInCheck" class="font-bold text-slate-900">this degree program</span> will permanently remove it from the university catalog and safely unbind all enrolled student profiles.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer Form -->
            <form id="cascadeDeleteProgForm" method="POST" class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('deleteProgramCascadeModal')" class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all shadow-2xs cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="cascadeDeleteProgSubmitBtn" disabled class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-rose-600 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/25 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Permanently Delete Degree Program</span>
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
                'departments_count' => (int) ($sch->departments_count ?? 0),
                'faculty_count' => (int) ($sch->faculty_count ?? 0),
                'student_count' => (int) ($sch->student_count ?? 0),
                'courses_count' => (int) ($sch->courses_count ?? 0),
                'departments' => $sch->departments->map(function($d, $dIdx) use ($sch) {
                    return [
                        'id' => $d->id,
                        'name' => $d->name,
                        'code' => $d->code,
                        'school_id' => $d->school_id,
                        'school_name' => $sch->name,
                        'school_code' => $sch->code,
                        'faculty_count' => (int) ($d->faculty_count ?? 0),
                        'student_count' => (int) ($d->student_count ?? 0),
                        'courses_count' => (int) ($d->courses_count ?? 0),
                        'programs_count' => !empty($d->programs) ? $d->programs->count() : (int) ($d->programs_count ?? 0),
                        'programs' => !empty($d->programs) ? $d->programs->map(function($p) {
                            return [
                                'id' => $p->id,
                                'name' => $p->name,
                                'code' => $p->code,
                                'level' => $p->level ?? 'UG',
                                'duration_years' => $p->duration_years ?? null,
                            ];
                        })->values() : [],
                        'order_in_school' => $dIdx + 1,
                        'is_first_in_school' => $dIdx === 0,
                    ];
                })->values(),
            ];
        })->values();

        // Complete Cascading Payload for School Deletions (Eager-loaded with departments & degree programs)
        $cascadeSchoolsCollection = $schoolsList->getCollection()->merge($schoolsWithDepts)->unique('id');
        $schoolsCascadeMap = $cascadeSchoolsCollection->mapWithKeys(function($sch) {
            return [
                $sch->id => [
                    'id' => $sch->id,
                    'name' => $sch->name,
                    'code' => $sch->code,
                    'faculty_count' => (int) ($sch->faculty_count ?? 0),
                    'student_count' => (int) ($sch->student_count ?? 0),
                    'departments_count' => !empty($sch->departments) ? $sch->departments->count() : 0,
                    'programs_count' => !empty($sch->departments) ? $sch->departments->sum(fn($d) => !empty($d->programs) ? $d->programs->count() : 0) : 0,
                    'departments' => !empty($sch->departments) ? $sch->departments->map(function($d) {
                        return [
                            'id' => $d->id,
                            'name' => $d->name,
                            'code' => $d->code,
                            'faculty_count' => (int) ($d->faculty_count ?? 0),
                            'student_count' => (int) ($d->student_count ?? 0),
                            'programs_count' => !empty($d->programs) ? $d->programs->count() : 0,
                            'programs' => !empty($d->programs) ? $d->programs->map(function($p) {
                                return [
                                    'id' => $p->id,
                                    'name' => $p->name,
                                    'code' => $p->code,
                                    'level' => $p->level ?? 'UG',
                                ];
                            })->values() : []
                        ];
                    })->values() : []
                ]
            ];
        });

        // Complete Cascading Payload for Department Deletions (Eager-loaded with degree programs, school, and counts)
        $cascadeDeptsCollection = $departments->getCollection()->merge($allDepartments ?? $allDepartmentsForDropdown ?? collect())->unique('id');
        $departmentsCascadeMap = $cascadeDeptsCollection->mapWithKeys(function($d) {
            return [
                $d->id => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'code' => $d->code,
                    'school_id' => $d->school_id,
                    'school_name' => $d->school ? $d->school->name : 'Unassigned Collegiate School',
                    'school_code' => $d->school ? $d->school->code : 'None',
                    'faculty_count' => (int) ($d->faculty_count ?? 0),
                    'student_count' => (int) ($d->student_count ?? 0),
                    'courses_count' => (int) ($d->courses_count ?? 0),
                    'programs_count' => !empty($d->programs) ? $d->programs->count() : (int) ($d->programs_count ?? 0),
                    'programs' => !empty($d->programs) ? $d->programs->map(function($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'code' => $p->code,
                            'level' => $p->level ?? 'UG',
                            'duration_years' => $p->duration_years ?? null,
                        ];
                    })->values() : []
                ]
            ];
        });

        // Complete Cascading & Inspector Payload for Degree Programs
        $programsCascadeMap = $programsList->mapWithKeys(function($p) {
            return [
                $p->id => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'code' => $p->code,
                    'level' => $p->level ?? 'UG',
                    'duration_years' => (int) ($p->duration_years ?? 4),
                    'semesters' => (int) (($p->duration_years ?? 4) * 2),
                    'department_id' => $p->department_id,
                    'department_name' => $p->department ? $p->department->name : 'Unassigned Academic Department',
                    'department_code' => $p->department ? $p->department->code : 'None',
                    'school_name' => ($p->department && $p->department->school) ? $p->department->school->name : 'Unassigned Collegiate School',
                    'school_code' => ($p->department && $p->department->school) ? $p->department->school->code : 'None',
                    'student_count' => (int) ($p->student_count ?? 0),
                ]
            ];
        });
    @endphp

    <script>
        // Full hierarchical tree of Schools and their ordered Departments (1st created is index 0)
        const SCHOOLS_TREE = @json($schoolsTreeData);
        // Cascading deletion metadata mapping
        const SCHOOLS_CASCADE_MAP = @json($schoolsCascadeMap);
        const DEPARTMENTS_CASCADE_MAP = @json($departmentsCascadeMap);
        const PROGRAMS_CASCADE_MAP = @json($programsCascadeMap);
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
                drawer.classList.remove('scale-95', 'opacity-0');
                drawer.classList.add('scale-100', 'opacity-100');
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

            drawer.classList.remove('scale-100', 'opacity-100');
            drawer.classList.add('scale-95', 'opacity-0');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');

            setTimeout(() => {
                backdrop.classList.add('hidden');
                isDrawerOpen = false;
            }, 250);
        }

        function renderInspectorDrawer() {
            const school = SCHOOLS_TREE[currentSchoolIdx];
            if (!school) return;
            const dept = school.departments[currentDeptIdx];
            if (!dept) return;

            // Update Header titles
            const modalDeptName = document.getElementById('inspectorModalDeptName');
            if (modalDeptName) modalDeptName.innerText = dept.name;

            const modalDeptCodeBadge = document.getElementById('inspectorModalDeptCodeBadge');
            if (modalDeptCodeBadge) modalDeptCodeBadge.innerText = dept.code;

            const modalSchoolSubtitle = document.getElementById('inspectorModalSchoolSubtitle');
            if (modalSchoolSubtitle) modalSchoolSubtitle.innerText = `${school.name} (${school.code})`;

            // Construct Content Body with ONLY: Programs, Faculty, Students, Courses
            const body = document.getElementById('inspectorContentBody');
            if (!body) return;

            const programs = dept.programs || [];
            const programsCount = programs.length;

            body.innerHTML = `
                <div class="space-y-6">
                    <!-- Key Academic Metrics: Programs, Faculty, Students, Courses -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <!-- Programs Card -->
                        <div class="p-4 bg-indigo-50/70 rounded-2xl border border-indigo-100/90 text-center flex flex-col justify-between shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-indigo-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider">Programs</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 my-1">${programsCount}</div>
                            <span class="text-[10px] text-slate-500 font-semibold">Degree Curricula</span>
                        </div>

                        <!-- Faculty Card -->
                        <div class="p-4 bg-cyan-50/70 rounded-2xl border border-cyan-100/90 text-center flex flex-col justify-between shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-cyan-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider">Faculty</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 my-1">${dept.faculty_count}</div>
                            <span class="text-[10px] text-slate-500 font-semibold">Teaching Staff</span>
                        </div>

                        <!-- Students Card -->
                        <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-100/90 text-center flex flex-col justify-between shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider">Students</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 my-1">${dept.student_count}</div>
                            <span class="text-[10px] text-slate-500 font-semibold">Enrolled</span>
                        </div>

                        <!-- Courses Card -->
                        <div class="p-4 bg-purple-50/70 rounded-2xl border border-purple-100/90 text-center flex flex-col justify-between shadow-2xs">
                            <div class="flex items-center justify-center gap-1.5 text-purple-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-wider">Courses</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-900 my-1">${dept.courses_count}</div>
                            <span class="text-[10px] text-slate-500 font-semibold">Subjects</span>
                        </div>
                    </div>

                    <!-- Programs List Section -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-600">Degree Programs Offered</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700">${programsCount} Programs</span>
                            </div>
                        </div>

                        ${programs.length > 0 ? `
                            <div class="divide-y divide-slate-100 rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                                ${programs.map(p => {
                                    const levelBadgeClasses = p.level === 'PG' ? 'bg-purple-100 text-purple-700 border-purple-200' :
                                        p.level === 'PhD' ? 'bg-amber-100 text-amber-800 border-amber-200' :
                                        p.level === 'Diploma' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' :
                                        'bg-blue-100 text-blue-700 border-blue-200';
                                    return `
                                        <div class="p-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/80 transition-colors">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border ${levelBadgeClasses} shrink-0">
                                                    ${p.level || 'UG'}
                                                </span>
                                                <div class="min-w-0">
                                                    <h4 class="text-xs font-bold text-slate-900 truncate" title="${p.name}">${p.name}</h4>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="font-mono text-[10px] text-slate-500 font-semibold">${p.code}</span>
                                                        ${p.duration_years ? `<span class="text-[10px] text-slate-400">• ${p.duration_years} Years</span>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        ` : `
                            <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                <p class="text-xs font-medium text-slate-500">No degree programs currently registered under this department.</p>
                            </div>
                        `}
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

        // Keyboard navigation: Escape to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isDrawerOpen) {
                closeDepartmentInspector();
            }
        });

        // Directory View Switcher (Driven by Top Metric Cards)
        function switchDirectoryTab(tab, shouldScroll = false) {
            const secDepts = document.getElementById('section-departments');
            const secSchools = document.getElementById('section-schools');
            const secPrograms = document.getElementById('section-programs');
            const formTab = document.getElementById('formActiveTab');

            // Header metric cards
            const cardSchools = document.getElementById('cardTab-schools');
            const cardDepts = document.getElementById('cardTab-departments');
            const cardPrograms = document.getElementById('cardTab-programs');
            const badgeSchools = document.getElementById('badge-card-schools');
            const badgeDepts = document.getElementById('badge-card-departments');
            const badgePrograms = document.getElementById('badge-card-programs');

            if (formTab) formTab.value = tab;

            const cardInactiveBase = 'group rounded-2xl p-5 transition-all cursor-pointer select-none bg-white border border-slate-200/80 shadow-xs hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5';

            // Update top cards visual state
            if (cardSchools) {
                cardSchools.className = (tab === 'schools') 
                    ? 'group rounded-2xl p-5 transition-all cursor-pointer select-none bg-gradient-to-br from-indigo-50/90 via-white to-white border-2 border-indigo-600 ring-4 ring-indigo-500/10 shadow-md -translate-y-1'
                    : cardInactiveBase;
            }
            if (badgeSchools) {
                badgeSchools.classList.toggle('hidden', tab !== 'schools');
            }

            if (cardDepts) {
                cardDepts.className = (tab === 'departments') 
                    ? 'group rounded-2xl p-5 transition-all cursor-pointer select-none bg-gradient-to-br from-blue-50/90 via-white to-white border-2 border-blue-600 ring-4 ring-blue-500/10 shadow-md -translate-y-1'
                    : cardInactiveBase;
            }
            if (badgeDepts) {
                badgeDepts.classList.toggle('hidden', tab !== 'departments');
            }

            if (cardPrograms) {
                cardPrograms.className = (tab === 'programs') 
                    ? 'group rounded-2xl p-5 transition-all cursor-pointer select-none bg-gradient-to-br from-purple-50/90 via-white to-white border-2 border-purple-600 ring-4 ring-purple-500/10 shadow-md -translate-y-1'
                    : cardInactiveBase;
            }
            if (badgePrograms) {
                badgePrograms.classList.toggle('hidden', tab !== 'programs');
            }

            // Show / hide directory sections
            if (tab === 'schools') {
                secSchools?.classList.remove('hidden');
                secDepts?.classList.add('hidden');
                secPrograms?.classList.add('hidden');
            } else if (tab === 'departments') {
                secDepts?.classList.remove('hidden');
                secSchools?.classList.add('hidden');
                secPrograms?.classList.add('hidden');
            } else if (tab === 'programs') {
                secPrograms?.classList.remove('hidden');
                secDepts?.classList.add('hidden');
                secSchools?.classList.add('hidden');
            }

            // Smooth scroll if triggered by user clicking the top card
            if (shouldScroll) {
                const target = document.getElementById('directorySection');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            // Sync URL query param cleanly without carrying over parameters from previous tabs
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            if (tab === 'programs') {
                url.searchParams.delete('school');
                url.searchParams.delete('dept_page');
                url.searchParams.delete('school_page');
                url.searchParams.delete('search');
            } else if (tab === 'schools') {
                url.searchParams.delete('school');
                url.searchParams.delete('dept_page');
                url.searchParams.delete('prog_page');
                url.searchParams.delete('search');
            } else if (tab === 'departments') {
                url.searchParams.delete('school_page');
                url.searchParams.delete('prog_page');
            }
            window.history.replaceState({ path: url.toString() }, '', url.toString());

            // Re-apply live filtering on tab switch
            if (tab === 'programs') {
                handleProgramFilterChange();
            } else if (tab === 'departments') {
                const selectedSchool = document.getElementById('deptSectionSchoolFilter')?.value || 'all';
                filterDepartmentsTableLive([], '', selectedSchool);
            }
        }

        // Modal Helpers
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
                el.style.display = 'none';
            }
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden');
                el.style.display = 'flex';
            }
        }

        function openCreateSchoolModal() {
            openModal('createSchoolModal');
        }

        function openEditSchoolModal(id, name, code) {
            document.getElementById('editSchoolName').value = name;
            document.getElementById('editSchoolCode').value = code;
            document.getElementById('editSchoolForm').action = `/academic/schools/${id}`;
            openModal('editSchoolModal');
        }

        function openCreateDepartmentModal(preselectedSchool = null) {
            if (preselectedSchool) {
                const sel = document.getElementById('createDeptSchoolSelect');
                if (sel) sel.value = preselectedSchool;
            }
            openModal('createDepartmentModal');
        }

        function openEditDepartmentModal(id, name, code, schoolId) {
            document.getElementById('editDeptName').value = name;
            document.getElementById('editDeptCode').value = code;
            document.getElementById('editDeptSchool').value = schoolId;
            document.getElementById('editDepartmentForm').action = `/academic/departments/${id}`;
            openModal('editDepartmentModal');
        }

        function confirmDelete(type, id, name) {
            if (type === 'school') {
                openDeleteSchoolCascadeModal(id);
                return;
            }
            if (type === 'department') {
                openDeleteDepartmentCascadeModal(id);
                return;
            }
            if (type === 'program') {
                openDeleteProgramCascadeModal(id);
                return;
            }

            const form = document.getElementById('deleteForm');
            const title = document.getElementById('deleteTargetTitle');
            const desc = document.getElementById('deleteTargetDesc');

            form.action = `/academic/departments/${id}`;
            title.innerText = `Delete Department: ${name}`;
            desc.innerText = `Are you sure you want to delete this department? Any courses or users assigned to it must be reassigned first.`;

            openModal('deleteConfirmModal');
        }

        // ====================================================================
        // CASCADE SCHOOL DELETION HANDLER (Shows all linked departments & programs)
        // ====================================================================
        function openDeleteSchoolCascadeModal(schoolId) {
            const data = SCHOOLS_CASCADE_MAP[schoolId];
            if (!data) {
                console.error('School data not found for ID:', schoolId);
                return;
            }

            document.getElementById('cascadeDeleteSchoolForm').action = `/academic/schools/${data.id}`;
            document.getElementById('cascadeModalSchoolName').innerText = data.name;
            document.getElementById('cascadeModalSchoolCode').innerText = data.code;
            document.getElementById('cascadeModalSchoolNameInCheck').innerText = data.name;

            document.getElementById('cascadeModalDeptCount').innerText = data.departments_count ?? 0;
            document.getElementById('cascadeModalProgCount').innerText = data.programs_count ?? 0;
            document.getElementById('cascadeModalFacultyCount').innerText = data.faculty_count ?? 0;
            document.getElementById('cascadeModalStudentCount').innerText = data.student_count ?? 0;

            // Reset confirmation checkbox & submit button
            const checkbox = document.getElementById('cascadeDeleteConfirmCheckbox');
            if (checkbox) checkbox.checked = false;
            toggleCascadeDeleteSubmit(false);

            // Render linked divisions (Departments & their degree programs)
            const container = document.getElementById('cascadeModalLinkedDataContainer');
            const summaryLabel = document.getElementById('cascadeModalListSummary');

            if (data.departments && data.departments.length > 0) {
                if (summaryLabel) {
                    summaryLabel.innerText = `${data.departments.length} Department(s) • ${data.programs_count ?? 0} Degree Program(s)`;
                }

                let html = '';
                data.departments.forEach((dept) => {
                    const progCount = dept.programs ? dept.programs.length : 0;
                    html += `
                        <div class="bg-white rounded-xl border border-slate-200/90 p-3 shadow-2xs space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </span>
                                    <div class="text-xs font-black text-slate-800 truncate">${dept.name}</div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">${dept.code}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">${progCount} ${progCount === 1 ? 'Program' : 'Programs'}</span>
                                </div>
                            </div>
                    `;

                    if (dept.programs && dept.programs.length > 0) {
                        html += `<div class="pl-3 border-l-2 border-indigo-200/60 space-y-1.5 mt-2">`;
                        dept.programs.forEach(prog => {
                            let levelStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                            if (prog.level === 'PG') levelStyle = 'bg-purple-50 text-purple-700 border-purple-200';
                            else if (prog.level === 'PhD') levelStyle = 'bg-amber-50 text-amber-700 border-amber-200';
                            else if (prog.level === 'Diploma') levelStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';

                            html += `
                                <div class="flex items-center justify-between gap-2 text-xs py-0.5">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black border ${levelStyle} uppercase leading-none">${prog.level || 'UG'}</span>
                                        <span class="text-slate-700 font-semibold truncate">${prog.name}</span>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400 shrink-0 font-medium">${prog.code}</span>
                                </div>
                            `;
                        });
                        html += `</div>`;
                    } else {
                        html += `
                            <div class="pl-3 border-l-2 border-slate-200 text-[11px] text-slate-400 italic">
                                No degree programs assigned under this department.
                            </div>
                        `;
                    }

                    html += `</div>`;
                });
                container.innerHTML = html;
            } else {
                if (summaryLabel) summaryLabel.innerText = '0 Divisions';
                container.innerHTML = `
                    <div class="p-6 text-center text-slate-400">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="text-xs font-bold text-slate-600">No linked departments or degree programs</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">This collegiate school currently has no active departments or curriculum programs.</div>
                    </div>
                `;
            }

            openModal('deleteSchoolCascadeModal');
        }

        function toggleCascadeDeleteSubmit(isChecked) {
            const btn = document.getElementById('cascadeDeleteSubmitBtn');
            if (btn) {
                btn.disabled = !isChecked;
            }
        }

        // ====================================================================
        // CASCADE DEPARTMENT DELETION HANDLER (Shows all linked programs & records)
        // ====================================================================
        function openDeleteDepartmentCascadeModal(deptId) {
            const data = DEPARTMENTS_CASCADE_MAP[deptId];
            if (!data) {
                console.error('Department data not found for ID:', deptId);
                return;
            }

            document.getElementById('cascadeDeleteDeptForm').action = `/academic/departments/${data.id}`;
            document.getElementById('cascadeModalDeptName').innerText = data.name;
            document.getElementById('cascadeModalDeptCode').innerText = data.code;
            document.getElementById('cascadeModalDeptSchool').innerText = `Collegiate School: ${data.school_name} (${data.school_code})`;
            document.getElementById('cascadeModalDeptNameInCheck').innerText = data.name;

            const avatarInitial = data.code ? data.code.replace('dep_', '').substring(0, 2).toUpperCase() : 'DP';
            const avatarEl = document.getElementById('cascadeModalDeptAvatar');
            if (avatarEl) avatarEl.innerText = avatarInitial;

            document.getElementById('cascadeModalDeptProgCount').innerText = data.programs_count ?? (data.programs ? data.programs.length : 0);
            document.getElementById('cascadeModalDeptCourseCount').innerText = data.courses_count ?? 0;
            document.getElementById('cascadeModalDeptFacultyCount').innerText = data.faculty_count ?? 0;
            document.getElementById('cascadeModalDeptStudentCount').innerText = data.student_count ?? 0;

            // Reset confirmation checkbox & submit button
            const checkbox = document.getElementById('cascadeDeleteDeptConfirmCheckbox');
            if (checkbox) checkbox.checked = false;
            toggleCascadeDeleteDeptSubmit(false);

            // Render linked degree programs
            const container = document.getElementById('cascadeModalDeptLinkedDataContainer');
            const summaryLabel = document.getElementById('cascadeModalDeptListSummary');

            if (data.programs && data.programs.length > 0) {
                if (summaryLabel) {
                    summaryLabel.innerText = `${data.programs.length} Degree Program(s)`;
                }

                let html = '';
                data.programs.forEach(prog => {
                    let levelStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                    if (prog.level === 'PG') levelStyle = 'bg-purple-50 text-purple-700 border-purple-200';
                    else if (prog.level === 'PhD') levelStyle = 'bg-amber-50 text-amber-700 border-amber-200';
                    else if (prog.level === 'Diploma') levelStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';

                    html += `
                        <div class="flex items-center justify-between gap-2 p-2.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border ${levelStyle} shrink-0">
                                    ${prog.level || 'UG'}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-slate-800 truncate" title="${prog.name}">${prog.name}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">${prog.code}${prog.duration_years ? ` • ${prog.duration_years} Years` : ''}</div>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full shrink-0">Will Delete</span>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                if (summaryLabel) summaryLabel.innerText = '0 Programs';
                container.innerHTML = `
                    <div class="p-4 text-center text-xs text-slate-400 font-medium">
                        No degree programs currently registered under this department.
                    </div>
                `;
            }

            openModal('deleteDepartmentCascadeModal');
        }

        function toggleCascadeDeleteDeptSubmit(isChecked) {
            const btn = document.getElementById('cascadeDeleteDeptSubmitBtn');
            if (btn) {
                btn.disabled = !isChecked;
            }
        }

        function handleCreateModalSchoolChange(schoolCode) {
            const deptSelect = document.getElementById('createProgDeptSelect');
            if (!deptSelect) return;
            deptSelect.value = '';
            const options = deptSelect.querySelectorAll('option');
            let matchCount = 0;
            options.forEach(opt => {
                if (!opt.value) {
                    opt.hidden = false;
                    opt.style.display = '';
                } else if (!schoolCode || opt.dataset.school === schoolCode) {
                    opt.hidden = false;
                    opt.style.display = '';
                    matchCount++;
                } else {
                    opt.hidden = true;
                    opt.style.display = 'none';
                }
            });
            if (schoolCode && matchCount === 0) {
                deptSelect.options[0].text = '-- No departments registered in this school --';
            } else {
                deptSelect.options[0].text = '-- Select Academic Department --';
            }
        }

        function handleEditModalSchoolChange(schoolCode) {
            const deptSelect = document.getElementById('editProgDept');
            if (!deptSelect) return;
            const options = deptSelect.querySelectorAll('option');
            let currentValid = false;
            options.forEach(opt => {
                if (!opt.value) {
                    opt.hidden = false;
                    opt.style.display = '';
                } else if (!schoolCode || opt.dataset.school === schoolCode) {
                    opt.hidden = false;
                    opt.style.display = '';
                    if (opt.value == deptSelect.value) currentValid = true;
                } else {
                    opt.hidden = true;
                    opt.style.display = 'none';
                }
            });
            if (!currentValid && deptSelect.value) {
                deptSelect.value = '';
            }
        }

        // Program Management Handlers
        function openCreateProgramModal(preselectedDept = null) {
            const schoolFilter = document.getElementById('progSchoolFilter')?.value;
            const schoolSelect = document.getElementById('createProgSchoolSelect');
            
            if (preselectedDept) {
                const deptOpt = document.querySelector(`#createProgDeptSelect option[value="${preselectedDept}"]`);
                const schCode = deptOpt ? deptOpt.dataset.school : '';
                if (schoolSelect && schCode) {
                    schoolSelect.value = schCode;
                    handleCreateModalSchoolChange(schCode);
                }
                const sel = document.getElementById('createProgDeptSelect');
                if (sel) sel.value = preselectedDept;
            } else if (schoolSelect && schoolFilter && schoolFilter !== 'all') {
                schoolSelect.value = schoolFilter;
                handleCreateModalSchoolChange(schoolFilter);
            } else if (schoolSelect) {
                schoolSelect.value = '';
                handleCreateModalSchoolChange('');
            }
            openModal('createProgramModal');
        }

        function openEditProgramModal(id, name, code, deptId, level, duration) {
            document.getElementById('editProgName').value = name;
            document.getElementById('editProgCode').value = code;
            document.getElementById('editProgLevel').value = level;
            document.getElementById('editProgDuration').value = duration;
            document.getElementById('editProgramForm').action = `/academic/programs/${id}`;

            const deptOption = document.querySelector(`#editProgDept option[value="${deptId}"]`);
            const schoolCode = deptOption ? deptOption.dataset.school : '';
            const schoolSelect = document.getElementById('editProgSchool');
            if (schoolSelect && schoolCode) {
                schoolSelect.value = schoolCode;
                handleEditModalSchoolChange(schoolCode);
            }
            document.getElementById('editProgDept').value = deptId;

            openModal('editProgramModal');
        }

        // ====================================================================
        // PROGRAM DETAILS INSPECTOR (CENTERED DIALOG)
        // ====================================================================
        function openProgramDetailsModal(progId) {
            let data = (typeof PROGRAMS_CASCADE_MAP !== 'undefined') ? PROGRAMS_CASCADE_MAP[progId] : null;
            if (!data) {
                const row = document.querySelector(`tr[data-id="${progId}"]`);
                if (row) {
                    data = {
                        id: progId,
                        name: row.querySelector('.text-slate-900.text-sm')?.innerText.trim() || 'Degree Program',
                        code: row.dataset.code || '',
                        level: (row.dataset.level || 'UG').toUpperCase(),
                        duration_years: parseInt(row.dataset.duration || '4'),
                        semesters: parseInt(row.dataset.duration || '4') * 2,
                        department_id: row.dataset.deptId,
                        department_name: row.dataset.dept || 'Academic Department',
                        department_code: row.dataset.deptCode || '',
                        school_name: row.dataset.school || 'Collegiate School',
                        school_code: row.dataset.schoolCode || '',
                        student_count: parseInt(row.dataset.students || '0')
                    };
                }
            }
            if (!data) {
                console.error('Program data not found for ID:', progId);
                return;
            }

            document.getElementById('progModalName').innerText = data.name;
            document.getElementById('progModalCodeBadge').innerText = data.code;
            
            const lvlBadge = document.getElementById('progModalLevelBadge');
            if (lvlBadge) {
                lvlBadge.innerText = data.level;
                lvlBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white text-indigo-700 shadow-2xs';
            }

            document.getElementById('progModalSubtitle').innerText = `${data.department_name} • ${data.school_name}`;
            document.getElementById('progModalDeptName').innerText = data.department_name;
            document.getElementById('progModalDeptCode').innerText = data.department_code ? `Code: ${data.department_code}` : '';
            document.getElementById('progModalSchoolName').innerText = data.school_name;
            document.getElementById('progModalSchoolCode').innerText = data.school_code ? `Code: ${data.school_code}` : '';

            document.getElementById('progModalLevelVal').innerText = data.level;
            const lvlLabels = { 'UG': 'Undergraduate', 'PG': 'Postgraduate', 'PhD': 'Doctoral', 'Diploma': 'Diploma' };
            document.getElementById('progModalLevelLabel').innerText = lvlLabels[data.level] || 'Curriculum';

            document.getElementById('progModalDurationVal').innerText = `${data.duration_years} Yrs`;
            document.getElementById('progModalSemestersLabel').innerText = `${data.semesters || (data.duration_years * 2)} Semesters`;

            document.getElementById('progModalStudentsVal').innerText = Number(data.student_count || 0).toLocaleString();

            const editBtn = document.getElementById('progModalEditBtn');
            if (editBtn) {
                editBtn.onclick = function() {
                    closeModal('programDetailsModal');
                    openEditProgramModal(data.id, data.name, data.code, data.department_id, data.level, data.duration_years);
                };
            }

            openModal('programDetailsModal');
        }

        // ====================================================================
        // CASCADE PROGRAM DELETION HANDLER (Shows linked info & unbind metrics)
        // ====================================================================
        function openDeleteProgramCascadeModal(progId) {
            let data = (typeof PROGRAMS_CASCADE_MAP !== 'undefined') ? PROGRAMS_CASCADE_MAP[progId] : null;
            if (!data) {
                const row = document.querySelector(`tr[data-id="${progId}"]`);
                if (row) {
                    data = {
                        id: progId,
                        name: row.querySelector('.text-slate-900.text-sm')?.innerText.trim() || 'Degree Program',
                        code: row.dataset.code || '',
                        level: (row.dataset.level || 'UG').toUpperCase(),
                        duration_years: parseInt(row.dataset.duration || '4'),
                        semesters: parseInt(row.dataset.duration || '4') * 2,
                        department_name: row.dataset.dept || 'Academic Department',
                        school_name: row.dataset.school || 'Collegiate School',
                        student_count: parseInt(row.dataset.students || '0')
                    };
                }
            }
            if (!data) {
                console.error('Program data not found for ID:', progId);
                return;
            }

            document.getElementById('cascadeDeleteProgForm').action = `/academic/programs/${data.id}`;
            document.getElementById('cascadeModalProgName').innerText = data.name;
            document.getElementById('cascadeModalProgCode').innerText = data.code;
            document.getElementById('cascadeModalProgDeptSchool').innerText = `${data.department_name} • ${data.school_name}`;
            document.getElementById('cascadeModalProgNameInCheck').innerText = data.name;

            const avatarInitial = data.code ? data.code.substring(0, 2).toUpperCase() : 'PR';
            const avatarEl = document.getElementById('cascadeModalProgAvatar');
            if (avatarEl) avatarEl.innerText = avatarInitial;

            document.getElementById('cascadeModalProgStudentCount').innerText = Number(data.student_count || 0).toLocaleString();
            document.getElementById('cascadeModalProgDuration').innerText = `${data.duration_years} Yrs`;
            document.getElementById('cascadeModalProgSemesters').innerText = `${data.semesters || (data.duration_years * 2)} Semesters`;
            document.getElementById('cascadeModalProgLevel').innerText = data.level;

            // Reset confirmation checkbox & submit button
            const checkbox = document.getElementById('cascadeDeleteProgConfirmCheckbox');
            if (checkbox) checkbox.checked = false;
            toggleCascadeDeleteProgSubmit(false);

            openModal('deleteProgramCascadeModal');
        }

        function toggleCascadeDeleteProgSubmit(isChecked) {
            const btn = document.getElementById('cascadeDeleteProgSubmitBtn');
            if (btn) {
                btn.disabled = !isChecked;
            }
        }

        function autoSuggestProgramCode(name) {
            const codeInput = document.getElementById('progCodeInput');
            if (codeInput && (!codeInput.value || codeInput.dataset.manual !== 'true')) {
                let code = name.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '');
                codeInput.value = code.substring(0, 30);
            }
        }

        function autoAdjustProgramDuration(level, durationInputId) {
            const input = document.getElementById(durationInputId);
            if (!input) return;
            if (level === 'UG') input.value = 4;
            else if (level === 'PG') input.value = 2;
            else if (level === 'PhD') input.value = 3;
            else if (level === 'Diploma') input.value = 3;
        }

        function filterProgramsTab() {
            const level = document.getElementById('progLevelFilter')?.value || 'all';
            const dept = document.getElementById('progDeptFilter')?.value || 'all';
            const url = new URL(window.location.href);
            url.searchParams.set('tab', 'programs');
            if (level !== 'all') url.searchParams.set('level', level);
            else url.searchParams.delete('level');
            if (dept !== 'all') url.searchParams.set('dept', dept);
            else url.searchParams.delete('dept');
            window.location.href = url.toString();
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

        // ====================================================================
        // REAL-TIME LIVE DIRECTORY FILTERING ENGINE
        // ====================================================================
        function handleDirectoryLiveFilter(rawQuery) {
            const query = (rawQuery || '').trim().toLowerCase();
            const clearBtn = document.getElementById('clearLiveSearchBtn');
            const resetBtn = document.getElementById('liveResetBtn');
            const selectedSchool = document.getElementById('deptSectionSchoolFilter')?.value || 'all';

            if (clearBtn) {
                clearBtn.classList.toggle('hidden', query.length === 0);
            }
            if (resetBtn) {
                resetBtn.classList.toggle('hidden', query.length === 0 && selectedSchool === 'all');
            }

            const tokens = query.split(/\s+/).filter(t => t.length > 0);

            // Filter tables in real time
            filterSchoolsTableLive(tokens, query);
            filterDepartmentsTableLive(tokens, query, selectedSchool);
            filterProgramsTableLive(tokens, query);
        }

        function filterSchoolsTableLive(tokens, rawQuery) {
            const rows = document.querySelectorAll('.school-table-row');
            const noMatchRow = document.getElementById('schoolsNoMatchRow');
            const countDisplay = document.getElementById('schoolsCountDisplay');
            const totalCount = {{ $totalSchoolsCount }};
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const code = row.dataset.code || '';
                const keywords = row.dataset.keywords || `${name} ${code}`;

                const isMatch = tokens.length === 0 || tokens.every(token => keywords.includes(token));
                if (isMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noMatchRow) {
                noMatchRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            if (countDisplay) {
                if (rawQuery.length > 0) {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-indigo-600">${visibleCount}</span> of ${totalCount} schools (live filtered)`;
                } else {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-slate-800">${rows.length}</span> of ${totalCount} schools`;
                }
            }
        }

        function filterDepartmentsTableLive(tokens, rawQuery, selectedSchool) {
            const rows = document.querySelectorAll('.department-table-row');
            const noMatchRow = document.getElementById('departmentsNoMatchRow');
            const countDisplay = document.getElementById('departmentsCountDisplay');
            const totalCount = {{ $totalDepartmentsCount }};
            let visibleCount = 0;

            const schoolFilter = (selectedSchool || 'all').toLowerCase();

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const code = row.dataset.code || '';
                const schoolName = row.dataset.school || '';
                const schoolCode = (row.dataset.schoolCode || '').toLowerCase();
                const keywords = row.dataset.keywords || `${name} ${code} ${schoolName} ${schoolCode}`;

                const schoolMatches = (schoolFilter === 'all' || schoolCode === schoolFilter);
                const tokenMatches = tokens.length === 0 || tokens.every(token => keywords.includes(token));

                if (schoolMatches && tokenMatches) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noMatchRow) {
                noMatchRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            if (countDisplay) {
                if (rawQuery.length > 0 || schoolFilter !== 'all') {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-indigo-600">${visibleCount}</span> of ${totalCount} (filtered)`;
                } else {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-slate-800">${rows.length}</span> of ${totalCount}`;
                }
            }
        }

        function handleProgramSchoolFilterChange() {
            const schoolVal = (document.getElementById('progSchoolFilter')?.value || 'all').toLowerCase();
            const deptSelect = document.getElementById('progDeptFilter');
            
            if (deptSelect) {
                let currentDeptStillValid = false;
                const options = deptSelect.querySelectorAll('option');
                options.forEach(opt => {
                    const optSchool = (opt.dataset.school || '').toLowerCase();
                    if (opt.value === 'all') {
                        opt.style.display = '';
                    } else if (schoolVal === 'all' || optSchool === schoolVal) {
                        opt.style.display = '';
                        if (opt.value === deptSelect.value) {
                            currentDeptStillValid = true;
                        }
                    } else {
                        opt.style.display = 'none';
                    }
                });
                
                // If currently selected department doesn't belong to the new school, reset to 'all'
                if (!currentDeptStillValid && deptSelect.value !== 'all') {
                    deptSelect.value = 'all';
                }
            }
            
            filterProgramsTableLive();
        }

        function filterProgramsTableLive(tokens = [], rawQuery = '') {
            const rows = document.querySelectorAll('.program-table-row');
            const noMatchRow = document.getElementById('programsNoMatchRow');
            const countDisplay = document.getElementById('programsCountDisplay');
            const resetBtn = document.getElementById('progResetFiltersBtn');
            const schoolFilter = (document.getElementById('progSchoolFilter')?.value || 'all').toLowerCase();
            const deptFilter = document.getElementById('progDeptFilter')?.value || 'all';
            const levelFilter = (document.getElementById('progLevelFilter')?.value || 'all').toLowerCase();
            const searchInput = (document.getElementById('programsSearchInput')?.value || '').trim().toLowerCase();
            const totalCount = {{ $totalProgramsCount }};
            let visibleCount = 0;

            const searchTokens = searchInput ? searchInput.split(/\s+/).filter(t => t.length > 0) : (tokens || []);
            const isFilterActive = (schoolFilter !== 'all' || deptFilter !== 'all' || levelFilter !== 'all' || searchInput.length > 0);

            // Update Reset button visual state
            if (resetBtn) {
                if (isFilterActive) {
                    resetBtn.classList.remove('opacity-40', 'pointer-events-none');
                    resetBtn.classList.add('text-rose-600', 'bg-rose-50', 'border-rose-300', 'shadow-xs');
                    resetBtn.classList.remove('text-slate-500', 'bg-white', 'border-slate-300');
                } else {
                    resetBtn.classList.add('opacity-40', 'pointer-events-none');
                    resetBtn.classList.remove('text-rose-600', 'bg-rose-50', 'border-rose-300', 'shadow-xs');
                    resetBtn.classList.add('text-slate-500', 'bg-white', 'border-slate-300');
                }
            }

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const code = row.dataset.code || '';
                const level = (row.dataset.level || '').toLowerCase();
                const deptId = row.dataset.deptId || '';
                const deptName = row.dataset.dept || '';
                const school = row.dataset.school || '';
                const schoolCode = (row.dataset.schoolCode || '').toLowerCase();
                const schoolId = (row.dataset.schoolId || '').toLowerCase();
                const combined = `${name} ${code} ${level} ${deptName} ${school}`;

                const schoolMatches = (schoolFilter === 'all' || schoolCode === schoolFilter || schoolId === schoolFilter);
                const levelMatches = (levelFilter === 'all' || level === levelFilter);
                const deptMatches = (deptFilter === 'all' || deptId === deptFilter);
                const tokenMatches = searchTokens.length === 0 || searchTokens.every(token => combined.includes(token));

                if (schoolMatches && levelMatches && deptMatches && tokenMatches) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noMatchRow) {
                noMatchRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            if (countDisplay) {
                if (isFilterActive || (rawQuery && rawQuery.length > 0)) {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-purple-600">${visibleCount}</span> of ${totalCount} degree programs (filtered)`;
                } else {
                    countDisplay.innerHTML = `Showing <span class="font-bold text-slate-800">${visibleCount}</span> of ${totalCount} degree programs`;
                }
            }
        }

        function handleDepartmentSchoolDropdownFilter(val) {
            const subSelect = document.getElementById('deptSectionSchoolFilter');
            if (subSelect && subSelect.value !== val) subSelect.value = val;

            filterDepartmentsTableLive([], '', val);
        }

        function handleProgramFilterChange() {
            filterProgramsTableLive();
        }

        function handleFilterFormSubmit(e) {
            e.preventDefault();
            return false;
        }

        function clearLiveFilter() {
            const progSchool = document.getElementById('progSchoolFilter');
            if (progSchool) progSchool.value = 'all';
            const progDept = document.getElementById('progDeptFilter');
            if (progDept) {
                progDept.value = 'all';
                progDept.querySelectorAll('option').forEach(opt => opt.style.display = '');
            }
            const progLevel = document.getElementById('progLevelFilter');
            if (progLevel) progLevel.value = 'all';
            const searchInput = document.getElementById('programsSearchInput');
            if (searchInput) searchInput.value = '';

            const deptSchool = document.getElementById('deptSectionSchoolFilter');
            if (deptSchool) deptSchool.value = 'all';

            handleProgramFilterChange();
            filterDepartmentsTableLive([], '', 'all');
        }

        function resetAllFilters() {
            clearLiveFilter();
            const url = new URL(window.location.href);
            if (url.searchParams.has('search') || url.searchParams.has('school') || url.searchParams.has('level') || url.searchParams.has('dept')) {
                const activeTab = document.getElementById('formActiveTab')?.value || 'programs';
                window.location.href = `/academic/departments-schools?tab=${activeTab}`;
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            // Clean up any stale query parameters from browser URL bar
            try {
                const url = new URL(window.location.href);
                let urlChanged = false;
                if (url.searchParams.has('dept_page')) {
                    url.searchParams.delete('dept_page');
                    urlChanged = true;
                }
                if (url.searchParams.has('prog_page')) {
                    url.searchParams.delete('prog_page');
                    urlChanged = true;
                }
                if (url.searchParams.has('school_page')) {
                    url.searchParams.delete('school_page');
                    urlChanged = true;
                }
                if (url.searchParams.has('search')) {
                    url.searchParams.delete('search');
                    urlChanged = true;
                }
                if (urlChanged) {
                    window.history.replaceState({}, '', url.toString());
                }
            } catch (e) {}

            // Initial program count calculation
            handleProgramFilterChange();
        });
    </script>
</body>
</html>
