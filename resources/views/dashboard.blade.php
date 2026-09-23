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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center ml-auto">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->role === 'sa' ? 'Super Admin' : (Auth::user()->first_name ?? 'Admin') }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/60">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Executive Command Center Hero Banner -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 p-6 sm:p-8 text-white shadow-xl border border-indigo-800/40">
                    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
                    <div class="absolute right-1/3 -bottom-10 h-48 w-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Super Admin Command Center
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Welcome back, {{ Auth::user()->role === 'sa' ? 'Super Admin' : (Auth::user()->first_name ?? 'Super Admin') }}! 👋
                            </h1>
                            <p class="text-sm sm:text-base text-indigo-200/90 mt-1 max-w-2xl font-normal">
                                System-wide overview of academic departments, coordinator leadership, student enrollments, and institutional modules.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('coordinators.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>Add Coordinator</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Unified Executive KPI Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- KPI 1: Coordinators -->
                    <a href="{{ route('coordinators.departments_list') }}" class="relative bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Coordinators</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalCoordinators ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Department Leadership</span>
                        </div>
                    </a>

                    <!-- KPI 2: Faculty -->
                    <a href="{{ route('staff.index') }}" class="relative bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Faculty</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalStaff ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-blue-700">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Active Academic Staff</span>
                        </div>
                    </a>

                    <!-- KPI 3: Students -->
                    <a href="{{ route('students.index') }}" class="relative bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-violet-300 hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Students</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalStudents ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-violet-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-violet-700">
                            <span class="inline-block w-2 h-2 rounded-full bg-violet-500"></span>
                            <span>Total Registered</span>
                        </div>
                    </a>

                    <!-- KPI 4: Civil Services -->
                    <a href="{{ route('civil.students.index') }}" class="relative bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-300 hover:-translate-y-1 transition-all duration-200 group overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Civil Services</p>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ $totalCivil ?? 0 }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-amber-700">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Academy Aspirants</span>
                        </div>
                    </a>
                </div>

                <!-- Recent Coordinators - Executive High-Impact Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Recent Coordinators</h3>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ count($coordinators ?? []) }} Recent</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Department heads and coordinators actively leading curriculum administration</p>
                            </div>
                        </div>
                        <a href="{{ route('coordinators.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50/70 hover:bg-indigo-100/70 border border-indigo-200/60 transition-all duration-150 shrink-0 self-start sm:self-auto group">
                            <span>View All Coordinators</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto w-full">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Coordinator</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">School & Department</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Role & Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Onboarded</th>
                                    <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
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
                                <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                                    <!-- Coordinator Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3.5">
                                            @if($photoUrl)
                                                <img class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-100 shadow-sm" src="{{ $photoUrl }}" alt="{{ $fullName }}">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold text-sm flex items-center justify-center shadow-sm shadow-indigo-200">
                                                    {{ $initial }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                    {{ $fullName }}
                                                </div>
                                                <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5 font-medium">
                                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    <span>{{ $email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- School & Department -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $schoolName }}
                                            </span>
                                            <div class="text-xs font-semibold text-slate-600 flex items-center gap-1">
                                                <span class="text-indigo-500 font-bold">&bull;</span>
                                                <span>{{ $deptName }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role & Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active Lead
                                        </span>
                                    </td>

                                    <!-- Onboarded Date -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>{{ $coordinator->created_at ? $coordinator->created_at->diffForHumans() : 'Recently' }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-0.5 pl-5">
                                            {{ $coordinator->created_at ? $coordinator->created_at->format('M d, Y') : '' }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-2">
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
                                                class="w-9 h-9 p-2 flex items-center justify-center rounded-xl bg-sky-50/80 text-sky-600 border border-sky-200/80 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Quick View">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <a href="{{ route('coordinators.edit', $coordinator->id) }}" class="w-9 h-9 p-2 flex items-center justify-center rounded-xl bg-indigo-50/80 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Edit Coordinator">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <a href="{{ route('coordinators.index', ['action' => 'reset_password', 'search' => $coordinator->username]) }}" class="w-9 h-9 p-2 flex items-center justify-center rounded-xl bg-amber-50/80 text-amber-600 border border-amber-200/80 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Reset Password">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 mx-auto flex items-center justify-center text-slate-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-600">No Department Coordinators Registered</p>
                                        <p class="text-xs text-slate-400 mt-1">Click "Add Coordinator" above to register an academic coordinator.</p>
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
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-6 py-4 border-b border-slate-100 bg-white flex justify-between items-center">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Recent Faculty</h3>
                                </div>
                                <a href="{{ route('staff.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group">
                                    <span>View All</span>
                                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @forelse($recentStaff ?? [] as $staff)
                                @php
                                    $sFirstName = $staff->profile->first_name ?? '';
                                    $sLastName = $staff->profile->last_name ?? '';
                                    $sFullName = trim($sFirstName . ' ' . $sLastName);
                                    if (empty($sFullName)) $sFullName = $staff->username ?? 'Staff Member';
                                    $sInitial = strtoupper(substr($sFirstName ?: 'S', 0, 1));
                                @endphp
                                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center border border-blue-100">
                                            {{ $sInitial }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $sFullName }}</p>
                                            <p class="text-xs text-slate-500">{{ $staff->profile->email ?? ($staff->username ?? '') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                            {{ $staff->profile->department->name ?? 'General' }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No faculty recorded yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Students -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-6 py-4 border-b border-slate-100 bg-white flex justify-between items-center">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Recent Students</h3>
                                </div>
                                <a href="{{ route('students.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group">
                                    <span>View All</span>
                                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @forelse($recentStudents ?? [] as $student)
                                @php
                                    $stFirstName = $student->profile->first_name ?? '';
                                    $stLastName = $student->profile->last_name ?? '';
                                    $stFullName = trim($stFirstName . ' ' . $stLastName);
                                    if (empty($stFullName)) $stFullName = $student->username ?? 'Student';
                                    $stInitial = strtoupper(substr($stFirstName ?: 'U', 0, 1));
                                @endphp
                                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-700 font-bold text-xs flex items-center justify-center border border-violet-100">
                                            {{ $stInitial }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $stFullName }}</p>
                                            <p class="text-xs text-slate-500 font-mono">{{ $student->username ?? ($student->profile->username ?? '') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                            {{ $student->profile->department->name ?? 'General' }}
                                        </span>
                                    </div>
                                </div>
                                @empty
                                <div class="px-6 py-8 text-center text-xs text-slate-400 font-medium">No students registered yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-slate-200/80 pt-4 pb-2">
                <p class="text-center text-xs text-slate-500 font-medium">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
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


