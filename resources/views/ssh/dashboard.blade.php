<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSH Department (First Year Directorate) - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800 font-sans">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                    <svg class="w-3.5 h-3.5 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Sciences & Humanities Division
                </span>
                <span class="text-xs text-slate-500 font-medium hidden md:inline">University First Year Academic Directorate</span>
            </div>

            <div class="flex items-center ml-auto gap-4">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6 w-full">

                <!-- Welcome Hero Banner -->
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden border border-indigo-800/50">
                    <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-2 max-w-2xl">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider text-indigo-200 border border-white/15">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                First Year Academic Administration
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                                Sciences & Humanities (SSH) Department
                            </h1>
                            <p class="text-slate-200 text-sm sm:text-base leading-relaxed">
                                Complete central management for all first-year undergraduate students, foundational STEM curriculum, faculty allocations, notes, and assessments across all engineering and science branches.
                            </p>
                        </div>

                        <div class="flex flex-wrap md:flex-col gap-2.5 shrink-0">
                            <a href="{{ route('ssh.students.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Register 1st Year Student
                            </a>
                            <a href="{{ route('ssh.courses.allocations') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-semibold text-sm rounded-xl transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Allocate S&H Faculty
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KPI Metric Cards Grid: 3 Core Metrics (Students, Faculty, Courses) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Card 1: Total 1st Year Students -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 hover:shadow-md transition-all group relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-blue-50/70 group-hover:scale-110 transition-transform pointer-events-none"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">1st Year Students</span>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-4xl font-black text-slate-900 tracking-tight">{{ $totalFirstYears }}</p>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Enrolled across all engineering & science branches</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active Freshers
                            </span>
                            <a href="{{ route('ssh.students.index') }}" class="font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                View Students &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: S&H Faculty -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 hover:shadow-md transition-all group relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-indigo-50/70 group-hover:scale-110 transition-transform pointer-events-none"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">S&H Faculty</span>
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-4xl font-black text-indigo-600 tracking-tight">{{ $totalShFaculty }}</p>
                            <p class="text-xs text-slate-500 mt-1 font-medium">Mathematics, Physics, Chemistry & Humanities</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1 font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                                Teaching Staff
                            </span>
                            <a href="{{ route('ssh.staff.index') }}" class="font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                View Faculty &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Card 3: Foundational Courses -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 hover:shadow-md transition-all group relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-amber-50/70 group-hover:scale-110 transition-transform pointer-events-none"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">1st Year Courses</span>
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-4xl font-black text-amber-600 tracking-tight">{{ $totalFirstYearCourses }}</p>
                            <p class="text-xs text-slate-500 mt-1 font-medium">{{ $allocatedCoursesCount }} courses allocated with faculty</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">
                                Sem 1 & Sem 2
                            </span>
                            <a href="{{ route('ssh.courses.index') }}" class="font-bold text-amber-600 hover:text-amber-800 flex items-center gap-1">
                                Manage Courses &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content Row: Branch Breakdown & Recent Freshers -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left: 1st Year Branch Distribution (1 col) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    1st Year Branch Distribution
                                </h2>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">Freshers</span>
                            </div>

                            <div class="mt-4 space-y-4">
                                @forelse($branchBreakdown as $b)
                                <div>
                                    <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                                        <span class="truncate max-w-[200px]">{{ $b->department_name ?? $b->departments_id }}</span>
                                        <span class="font-bold text-slate-900">{{ $b->student_count }} students</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $totalFirstYears > 0 ? min(100, round(($b->student_count / $totalFirstYears) * 100)) : 0 }}%"></div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8 text-slate-400 text-sm">
                                    No branch breakdown data available.
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('ssh.students.index') }}" class="w-full py-2 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs flex items-center justify-center transition-colors">
                                View Full Student Directory &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Right: Recent 1st Year Enrolled Students (2 cols) -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Recent First Year Registrations
                            </h2>
                            <a href="{{ route('ssh.students.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All ({{ $totalFirstYears }}) &rarr;</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Reg No</th>
                                        <th class="px-6 py-3 text-left">Student Name</th>
                                        <th class="px-6 py-3 text-left">Parent Branch</th>
                                        <th class="px-6 py-3 text-center">Section</th>
                                        <th class="px-6 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentFreshers as $fresher)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-3.5 font-bold text-indigo-700">
                                            <span class="font-mono text-xs bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">
                                                {{ $fresher->username }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <div class="font-semibold text-slate-900">{{ $fresher->first_name }} {{ $fresher->last_name }}</div>
                                            <div class="text-xs text-slate-400">{{ $fresher->profile->email ?? 'No email' }}</div>
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-600 text-xs font-medium">
                                            {{ $fresher->profile->department->name ?? 'General' }}
                                        </td>
                                        <td class="px-6 py-3.5 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Sec {{ strtoupper($fresher->profile->section ?? 'A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <a href="{{ route('ssh.students.edit', $fresher->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                            No 1st year students registered yet. Click "Register 1st Year Student" to begin.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Foundational Courses & S&H Faculty Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- 1st Year Foundational Courses Overview -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                1st Year Courses (1-1 & 1-2)
                            </h2>
                            <a href="{{ route('ssh.courses.allocations') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Allocations &rarr;</a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($firstYearCourses->take(5) as $course)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $course->code }}</span>
                                        <span class="text-xs font-semibold text-slate-500">Sem {{ $course->semester }}</span>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $course->name }}</p>
                                </div>
                                <div class="text-right">
                                    @if($course->staff->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            {{ $course->staff->count() }} Faculty Assigned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                            Unallocated
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-slate-400 py-6 text-center">No Year 1 courses configured yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- S&H Faculty Overview -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                S&H Faculty Members
                            </h2>
                            <a href="{{ route('ssh.staff.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All ({{ $totalShFaculty }}) &rarr;</a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($shFaculty->take(5) as $faculty)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ substr($faculty->first_name ?? 'F', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $faculty->first_name }} {{ $faculty->last_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $faculty->profile->designation ?? 'Faculty' }} • {{ $faculty->profile->department->name ?? 'Sciences & Humanities' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-400">{{ $faculty->username }}</span>
                            </div>
                            @empty
                            <p class="text-sm text-slate-400 py-6 text-center">No S&H faculty listed yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="mt-8 border-t border-slate-200 pt-4 pb-2">
                    <p class="text-center text-xs text-slate-500">&copy; {{ date('Y') }} Learning Management System - School of Applied Sciences & Humanities (First Year Directorate). All rights reserved.</p>
                </footer>

            </div>
        </main>
    </div>

</body>
</html>
