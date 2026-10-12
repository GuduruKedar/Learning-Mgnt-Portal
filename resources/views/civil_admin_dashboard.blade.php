<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civil Services Admin Dashboard - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        @include('partials.top_header')

        <!-- Main Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6 w-full">

                <!-- Welcome Banner -->
                <div class="theme-hero-card rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden text-white shrink-0">
                    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full blur-3xl pointer-events-none opacity-40" style="background: var(--theme-glow);"></div>
                    <div class="absolute right-1/3 -bottom-10 h-48 w-48 rounded-full blur-2xl pointer-events-none opacity-30" style="background: var(--theme-primary-light);"></div>

                    <div class="relative z-10 flex flex-col gap-5">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full theme-hero-badge text-xs font-semibold uppercase tracking-wider mb-3">
                                <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--theme-primary);"></span>
                                Civil Services Academy
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                                Welcome, <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                            </h1>
                            <p class="text-white/80 text-sm sm:text-base max-w-2xl font-normal leading-relaxed mt-2">
                                Manage university-wide UPSC & Civil Services training enrollments. Students from any academic department and year can participate while maintaining their core degree.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('civil.students.create') }}" class="btn-theme-primary inline-flex items-center px-4 py-2.5 font-semibold text-sm rounded-xl shadow-md transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Enroll / Register Student
                            </a>
                            <a href="{{ route('civil.students.index') }}" class="btn-theme-ghost inline-flex items-center px-4 py-2.5 font-semibold text-sm rounded-xl transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                View All Enrolled Roster
                            </a>
                        </div>
                    </div>
                </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] p-6 flex items-center justify-between theme-card-hover">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-[#94A3B8] uppercase tracking-wider">Total Enrolled</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $totalEnrolled }}</p>
                        <p class="text-xs text-theme-primary mt-1 font-semibold">All academic streams</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl theme-icon-box flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] p-6 flex items-center justify-between theme-card-hover">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-[#94A3B8] uppercase tracking-wider">Active Students</p>
                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeEnrolled }}</p>
                        <p class="text-xs text-emerald-500 mt-1 font-semibold">Currently in training</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] p-6 flex items-center justify-between theme-card-hover">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-[#94A3B8] uppercase tracking-wider">Represented Depts</p>
                        <p class="text-3xl font-black text-violet-600 dark:text-violet-400 mt-1">{{ $departmentBreakdown->count() }}</p>
                        <p class="text-xs text-violet-500 mt-1 font-semibold">Cross-department reach</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-800/40 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Enrollments Table (2 cols) -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-base font-bold text-gray-900">Recent Civil Services Enrollments</h2>
                        <a href="{{ route('civil.students.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">View All &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3 text-left">Reg No</th>
                                    <th class="px-6 py-3 text-left">Student Name</th>
                                    <th class="px-6 py-3 text-left">Parent Department</th>
                                    <th class="px-6 py-3 text-left">Enrolled Date</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($recentEnrollments as $student)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-blue-700">
                                        {{ $student->username }}
                                    </td>
                                    <td class="px-6 py-3 font-medium text-gray-900">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ $student->profile->department->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-500 text-xs">
                                        {{ $student->civilServiceEnrollment->enrolled_at ? $student->civilServiceEnrollment->enrolled_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($student->civilServiceEnrollment->status ?? 'active') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        No students enrolled yet. Click "Enroll / Register Student" to get started.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Department Breakdown (1 col) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Enrollment by Parent Department</h2>
                    @if($departmentBreakdown->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-8">No department data yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($departmentBreakdown as $dept)
                            <div>
                                <div class="flex justify-between text-xs font-medium text-gray-600 mb-1">
                                    <span>{{ $dept->department->name ?? $dept->departments_id }}</span>
                                    <span class="font-bold text-gray-900">{{ $dept->count }} student{{ $dept->count > 1 ? 's' : '' }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalEnrolled > 0 ? ($dept->count / $totalEnrolled * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System - Civil Services Academy. Powered by <span class="font-semibold text-gray-700">Technology Development (TD)</span>.</p>
            </footer>

            </div>
        </main>
    </div>

</body>
</html>
