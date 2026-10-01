<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')


    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/60">
            <div class="w-full space-y-6">
                
                <!-- Executive Command Center Hero Banner -->
                <div class="relative overflow-hidden rounded-3xl theme-hero-card p-6 sm:p-8 lg:p-9 text-white shadow-2xl border border-white/10 ring-1 ring-white/10">
                    <!-- Ambient Glows & Mesh Accents -->
                    <div class="absolute -right-16 -top-16 h-80 w-80 rounded-full blur-3xl pointer-events-none opacity-40" style="background: var(--theme-glow);"></div>
                    <div class="absolute right-1/4 -bottom-20 h-64 w-64 rounded-full blur-3xl pointer-events-none opacity-30" style="background: var(--theme-primary-light);"></div>
                    <div class="absolute left-1/3 top-0 h-40 w-40 rounded-full blur-2xl pointer-events-none opacity-20 bg-blue-400"></div>

                    <!-- Subtle Geometric Grid Overlay -->
                    <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 24px 24px;"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <!-- Left Content: Badges, Title, Subtitle & Quick Stats -->
                        <div class="space-y-4 max-w-3xl">
                            <!-- Badge Row -->
                            <div class="flex items-center gap-2.5">
                                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-white shadow-sm">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    <span>Super Admin Command Center</span>
                                </div>
                            </div>

                            <!-- Main Heading -->
                            <div>
                                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-100 via-sky-200 to-white">{{ Auth::user()->role === 'sa' ? 'Super Admin' : (Auth::user()->first_name ?? 'Super Admin') }}</span>!
                                </h1>
                                <p class="text-sm sm:text-base text-slate-200/90 mt-1.5 font-normal leading-relaxed">
                                    System-wide overview of academic departments, coordinator leadership, student enrollments, and institutional modules.
                                </p>
                            </div>

                            <!-- Executive Quick Metrics Chips -->
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <!-- Departments Stat Chip -->
                                <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] backdrop-blur-md border border-white/15 text-xs text-white shadow-sm transition-all">
                                    <div class="w-5 h-5 rounded-lg bg-blue-500/25 border border-blue-400/30 flex items-center justify-center text-blue-300 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-extrabold text-white text-sm tracking-tight">{{ $totalDepartments ?? 0 }}</span>
                                        <span class="text-white/75 font-medium text-xs">Departments</span>
                                    </div>
                                </div>

                                <!-- Courses Stat Chip -->
                                <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-white/[0.08] hover:bg-white/[0.14] backdrop-blur-md border border-white/15 text-xs text-white shadow-sm transition-all">
                                    <div class="w-5 h-5 rounded-lg bg-indigo-500/25 border border-indigo-400/30 flex items-center justify-center text-indigo-300 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-extrabold text-white text-sm tracking-tight">{{ $totalCourses ?? 0 }}</span>
                                        <span class="text-white/75 font-medium text-xs">Courses</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Action CTA -->
                        <div class="flex items-center shrink-0">
                            <a href="{{ route('coordinators.create') }}" class="btn-theme-primary inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-xl text-sm font-bold text-white shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 group">
                                <div class="w-5 h-5 rounded-lg bg-white/20 flex items-center justify-center group-hover:rotate-90 transition-transform duration-300">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <span>Add Coordinator</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Unified Executive KPI Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- KPI 1: Coordinators -->
                    <a href="{{ route('coordinators.departments_list') }}" class="relative bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-sm hover:shadow-md hover:border-theme-primary hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#94A3B8]">Coordinators</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1 tracking-tight">{{ $totalCoordinators ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl theme-icon-box flex items-center justify-center group-hover:scale-110 group-hover:bg-theme-primary group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-theme-primary">
                            <span class="inline-block w-2 h-2 rounded-full bg-theme-primary"></span>
                            <span>Department Leadership</span>
                        </div>
                    </a>

                    <!-- KPI 2: Faculty -->
                    <a href="{{ route('staff.index') }}" class="relative bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-sm hover:shadow-md hover:border-theme-primary hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#94A3B8]">Faculty</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1 tracking-tight">{{ $totalStaff ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800/40 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Active Academic Staff</span>
                        </div>
                    </a>

                    <!-- KPI 3: Students -->
                    <a href="{{ route('students.index') }}" class="relative bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-sm hover:shadow-md hover:border-theme-primary hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#94A3B8]">Students</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1 tracking-tight">{{ $totalStudents ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 border border-violet-100 dark:border-violet-800/40 flex items-center justify-center group-hover:scale-110 group-hover:bg-violet-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-violet-700 dark:text-violet-400">
                            <span class="inline-block w-2 h-2 rounded-full bg-violet-500"></span>
                            <span>Total Registered</span>
                        </div>
                    </a>

                    <!-- KPI 4: Civil Services -->
                    <a href="{{ route('civil.students.index') }}" class="relative bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-sm hover:shadow-md hover:border-theme-primary hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-[#94A3B8]">Civil Services</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1 tracking-tight">{{ $totalCivil ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-100 dark:border-amber-800/40 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-amber-400">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Academy Aspirants</span>
                        </div>
                    </a>
                </div>

                <!-- Recent Coordinators - Executive High-Impact Table -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-[#273244] bg-white dark:bg-[#151B23] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl theme-icon-box flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-[#F8FAFC] tracking-tight">Recent Coordinators</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold theme-icon-box">{{ count($coordinators ?? []) }} Recent</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-[#94A3B8] mt-0.5">Department heads and coordinators actively leading curriculum administration</p>
                            </div>
                        </div>
                        <a href="{{ route('coordinators.index') }}" class="btn-theme-ghost inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-150 shrink-0 self-start sm:self-auto group">
                            <span>View All Coordinators</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-[#273244]">
                            <thead class="bg-slate-50/80 dark:bg-[#1C2430]">
                                <tr>
                                    <th scope="col" class="px-4 py-2.5 sm:px-5 sm:py-3 text-left text-[11px] font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Coordinator</th>
                                    <th scope="col" class="px-4 py-2.5 sm:px-5 sm:py-3 text-left text-[11px] font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">School & Department</th>
                                    <th scope="col" class="px-4 py-2.5 sm:px-5 sm:py-3 text-left text-[11px] font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Role & Status</th>
                                    <th scope="col" class="px-4 py-2.5 sm:px-5 sm:py-3 text-left text-[11px] font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Onboarded</th>
                                    <th scope="col" class="px-4 py-2.5 sm:px-5 sm:py-3 text-right text-[11px] font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-[#151B23] divide-y divide-slate-100 dark:divide-[#273244]">
                                @forelse($coordinators ?? [] as $coordinator)
                                @php
                                    $firstName = $coordinator->first_name ?? ($coordinator->profile->first_name ?? '');
                                    $lastName = $coordinator->last_name ?? ($coordinator->profile->last_name ?? '');
                                    $fullName = trim($firstName . ' ' . $lastName);
                                    if (empty($fullName)) $fullName = $coordinator->username ?? 'Coordinator';
                                    $email = $coordinator->email ?? ($coordinator->profile->email ?? 'No email provided');
                                    $phone = $coordinator->profile->phone ?? 'N/A';
                                    $designation = $coordinator->profile->designation ?? 'Department Coordinator';
                                    $initial = strtoupper(substr($firstName ?: ($coordinator->username ?: 'C'), 0, 1));
                                    $schoolName = $coordinator->profile->school->name ?? 'General School';
                                    $deptName = $coordinator->profile->department->name ?? 'General Department';
                                    $deptCode = $coordinator->profile->department->code ?? '';
                                    $photoUrl = ($coordinator->profile && $coordinator->profile->photo) ? asset('storage/' . $coordinator->profile->photo) : '';
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors duration-150 group">
                                    <!-- Coordinator Info -->
                                    <td class="px-4 py-2 sm:px-5 sm:py-2 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if($photoUrl)
                                                <img class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl object-cover ring-2 ring-slate-100 dark:ring-[#273244] shadow-xs" src="{{ $photoUrl }}" alt="{{ $fullName }}">
                                            @else
                                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl text-white font-bold text-xs sm:text-sm flex items-center justify-center shadow-xs" style="background: var(--theme-gradient); box-shadow: 0 2px 8px var(--theme-glow);">
                                                    {{ $initial }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-xs sm:text-sm font-bold text-slate-900 dark:text-[#F8FAFC] hover-theme-text transition-colors">
                                                    {{ $fullName }}
                                                </div>
                                                <div class="text-[11px] text-slate-500 dark:text-[#94A3B8] flex items-center gap-1 mt-0.5 font-medium">
                                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    <span>{{ $email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- School & Department -->
                                    <td class="px-4 py-2 sm:px-5 sm:py-2 max-w-xs">
                                        <div class="flex flex-col">
                                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1 leading-tight">
                                                <svg class="w-2.5 h-2.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                <span class="truncate">{{ $schoolName }}</span>
                                            </div>
                                            <div class="text-xs font-semibold text-slate-800 dark:text-[#E2E8F0] leading-snug">
                                                {{ $deptName }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role & Status -->
                                    <td class="px-4 py-2 sm:px-5 sm:py-2 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/40">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active Lead
                                        </span>
                                    </td>

                                    <!-- Onboarded Date -->
                                    <td class="px-4 py-2 sm:px-5 sm:py-2 whitespace-nowrap">
                                        <div class="text-[11px] font-semibold text-slate-700 dark:text-[#CBD5E1] flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $coordinator->created_at ? $coordinator->created_at->diffForHumans() : 'Recently' }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-[#64748B] pl-4">
                                            {{ $coordinator->created_at ? $coordinator->created_at->format('M d, Y') : '' }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-2 sm:px-5 sm:py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" 
                                                onclick="openCoordinatorDetails({
                                                     username: '{{ $coordinator->username }}',
                                                    name: '{{ addslashes($fullName) }}',
                                                    email: '{{ addslashes($coordinator->email ?? '') }}',
                                                    phone: '{{ addslashes($coordinator->phone_number ?? '') }}',
                                                    school: '{{ addslashes($schoolName) }}',
                                                    department: '{{ addslashes($deptName) }}',
                                                    deptCode: '{{ addslashes($deptCode) }}',
                                                    photo: '{{ $photoUrl }}',
                                                    initial: '{{ $initial }}',
                                                    editUrl: '{{ route('coordinators.edit', $coordinator->id) }}',
                                                    resetUrl: '{{ route('coordinators.index', ['action' => 'reset_password', 'search' => $coordinator->username]) }}'
                                                })"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50/80 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200/80 dark:border-sky-800/40 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Quick View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <a href="{{ route('coordinators.edit', $coordinator->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/80 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/40 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Coordinator">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <a href="{{ route('coordinators.index', ['action' => 'reset_password', 'search' => $coordinator->username]) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50/80 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200/80 dark:border-amber-800/40 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Reset Password">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-[#1C2430] mx-auto flex items-center justify-center text-slate-400 mb-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-[#94A3B8]">No Department Coordinators Registered</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Secondary Split Section: Recent Faculty & Recent Students -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Faculty / Staff -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-[#273244] bg-white dark:bg-[#151B23] flex justify-between items-center">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800/40 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] tracking-tight">Recent Faculty</h3>
                                </div>
                                <a href="{{ route('staff.index') }}" class="text-xs font-bold hover-theme-text text-theme-primary flex items-center gap-1 group">
                                    <span>View All</span>
                                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-[#273244]">
                                @forelse($recentStaff ?? [] as $staff)
                                @php
                                    $sFirstName = $staff->profile->first_name ?? '';
                                    $sLastName = $staff->profile->last_name ?? '';
                                    $sFullName = trim($sFirstName . ' ' . $sLastName);
                                    if (empty($sFullName)) $sFullName = $staff->username ?? 'Staff Member';
                                    $sInitial = strtoupper(substr($sFirstName ?: 'S', 0, 1));
                                @endphp
                                <div class="px-4 py-2.5 sm:px-5 sm:py-2.5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 font-bold text-xs flex items-center justify-center border border-blue-100 dark:border-blue-800/40">
                                            {{ $sInitial }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900 dark:text-[#F8FAFC]">{{ $sFullName }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-[#94A3B8]">{{ $staff->profile->email ?? ($staff->username ?? '') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10.5px] font-semibold bg-slate-100 dark:bg-[#1C2430] text-slate-700 dark:text-[#94A3B8]">
                                            {{ $staff->profile->department->name ?? 'General' }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="px-5 py-6 text-center text-xs text-slate-400 dark:text-[#64748B] font-medium">No faculty recorded yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Students -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-[#273244] bg-white dark:bg-[#151B23] flex justify-between items-center">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400 border border-violet-100 dark:border-violet-800/40 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] tracking-tight">Recent Students</h3>
                                </div>
                                <a href="{{ route('students.index') }}" class="text-xs font-bold hover-theme-text text-theme-primary flex items-center gap-1 group">
                                    <span>View All</span>
                                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-[#273244]">
                                @forelse($recentStudents ?? [] as $student)
                                @php
                                    $stFirstName = $student->profile->first_name ?? '';
                                    $stLastName = $student->profile->last_name ?? '';
                                    $stFullName = trim($stFirstName . ' ' . $stLastName);
                                    if (empty($stFullName)) $stFullName = $student->username ?? 'Student';
                                    $stInitial = strtoupper(substr($stFirstName ?: 'U', 0, 1));
                                @endphp
                                <div class="px-4 py-2.5 sm:px-5 sm:py-2.5 flex items-center justify-between hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 font-bold text-xs flex items-center justify-center border border-violet-100 dark:border-violet-800/40">
                                            {{ $stInitial }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900 dark:text-[#F8FAFC]">{{ $stFullName }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-[#94A3B8] font-mono">{{ $student->username ?? ($student->profile->username ?? '') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[10.5px] font-semibold bg-slate-100 dark:bg-[#1C2430] text-slate-700 dark:text-[#94A3B8]">
                                            {{ $student->profile->department->name ?? 'General' }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="px-5 py-6 text-center text-xs text-slate-400 dark:text-[#64748B] font-medium">No students registered yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-slate-200/80 pt-4 pb-2">
                <p class="text-center text-xs text-slate-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-slate-700">TD</span>.</p>
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


    @include('partials.coordinator_details_modal')

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>


