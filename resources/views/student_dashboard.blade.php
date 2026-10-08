<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            <div class="w-full space-y-6">
                
                <!-- Student Hero Banner -->
                <div class="theme-hero-card rounded-2xl p-6 lg:p-7 shadow-xl relative overflow-hidden text-white">
                    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full blur-3xl pointer-events-none opacity-40" style="background: var(--theme-glow);"></div>
                    <div class="absolute right-1/3 -bottom-10 h-48 w-48 rounded-full blur-2xl pointer-events-none opacity-30" style="background: var(--theme-primary-light);"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full theme-hero-badge text-xs font-semibold uppercase tracking-wider mb-3">
                                <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--theme-primary);"></span>
                                Student Portal
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Welcome back, {{ Auth::user()->first_name ?? Auth::user()->username }}
                            </h1>
                            <p class="text-sm text-white/80 mt-1 max-w-2xl font-normal">
                                Access your enrolled course materials, download study resources, and submit academic assessments.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('student.enrollment.create') }}" class="btn-theme-primary inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>Enroll in Courses</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 items-stretch">
                    <!-- My Department -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div class="flex items-center justify-between gap-2">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4M9 11h4m-4-4h4"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-slate-100 dark:bg-[#1C2430] text-slate-600 dark:text-[#94A3B8]">My Department</span>
                        </div>
                        <div class="mt-3.5">
                            @php
                                $deptFormatted = $department ? preg_replace_callback('/\b(and|of|in|the|for|to|a|an|&)\b/i', fn($m) => strtolower($m[0]), ucwords(strtolower($department->name))) : 'N/A';
                            @endphp
                            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] line-clamp-2 leading-snug" title="{{ $department->name ?? 'N/A' }}">
                                {{ $deptFormatted }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-[#94A3B8] mt-1.5 flex items-center gap-1.5 font-medium truncate" title="{{ $profile->program->name ?? ($profile->programs_id ?? ($profile->level ?? 'Undergraduate Program')) }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                <span class="truncate">{{ $profile->program->name ?? ($profile->programs_id ?? ($profile->level ?? 'Undergraduate Program')) }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- My School -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div class="flex items-center justify-between gap-2">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300">My School</span>
                        </div>
                        <div class="mt-3.5">
                            @php
                                $schoolFormatted = $school ? preg_replace_callback('/\b(and|of|in|the|for|to|a|an|&)\b/i', fn($m) => strtolower($m[0]), ucwords(strtolower($school->name))) : 'N/A';
                            @endphp
                            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] line-clamp-2 leading-snug" title="{{ $school->name ?? 'N/A' }}">
                                {{ $schoolFormatted }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-[#94A3B8] mt-1.5 flex items-center gap-1.5 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="truncate">Academic Division</span>
                            </p>
                        </div>
                    </div>

                    <!-- Department Courses -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div class="flex items-center justify-between gap-2">
                            <div class="w-11 h-11 rounded-xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-800/50 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-violet-50 dark:bg-violet-950/40 text-violet-700 dark:text-violet-300">Curriculum</span>
                        </div>
                        <div class="mt-3.5 flex items-baseline justify-between">
                            <div>
                                <p class="text-2xl font-black text-slate-900 dark:text-[#F8FAFC] leading-none">{{ $departmentCoursesCount }}</p>
                                <p class="text-xs font-semibold text-slate-500 dark:text-[#94A3B8] mt-1.5">Department Courses</p>
                            </div>
                            <span class="text-[11px] font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/40 px-2 py-0.5 rounded-md">Catalog</span>
                        </div>
                    </div>

                    <!-- Enrolled Courses -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl p-5 border border-slate-200/80 dark:border-[#273244] shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div class="flex items-center justify-between gap-2">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/50 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300">Enrolled</span>
                        </div>
                        <div class="mt-3.5 flex items-baseline justify-between">
                            <div>
                                <p class="text-2xl font-black text-slate-900 dark:text-[#F8FAFC] leading-none">{{ $enrolledCoursesCount }}</p>
                                <p class="text-xs font-semibold text-slate-500 dark:text-[#94A3B8] mt-1.5">Active Courses</p>
                            </div>
                            <a href="{{ route('student.enrollment.create') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-0.5">
                                Manage &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Department Courses Table -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-[#273244] bg-white dark:bg-[#151B23] flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-900 dark:text-[#F8FAFC]">Department Courses</h3>
                            <span class="text-xs font-bold badge-theme px-2.5 py-0.5 rounded-full">{{ $departmentCoursesCount }} Total</span>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full divide-y divide-slate-100 dark:divide-[#273244] text-left">
                                <thead class="bg-slate-50/80 dark:bg-[#1C2430]">
                                    <tr>
                                        <th class="px-6 py-3.5 text-xs font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Course Name</th>
                                        <th class="px-6 py-3.5 text-xs font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider text-right sm:text-left">Code</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#151B23] divide-y divide-slate-100 dark:divide-[#273244]">
                                    @forelse($departmentCourses as $course)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] break-words hover-theme-text transition-colors">{{ $course->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-[#94A3B8] mt-0.5">{{ $course->year }} Year, {{ $course->semester }} Sem</div>
                                        </td>
                                        <td class="px-6 py-4 text-right sm:text-left whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-[#1C2430] text-slate-700 dark:text-[#94A3B8] text-xs font-mono font-semibold">{{ $course->code }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-8 text-center text-sm text-slate-400">No courses found in your department.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Enrolled Courses Table -->
                    <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-[#273244] bg-white dark:bg-[#151B23] flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-900 dark:text-[#F8FAFC]">Enrolled Courses</h3>
                            <a href="{{ route('student.enrollment.create') }}" class="btn-theme-ghost text-xs font-bold px-3 py-1.5 rounded-xl transition-all">+ Enroll More</a>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full divide-y divide-slate-100 dark:divide-[#273244] text-left">
                                <thead class="bg-slate-50/80 dark:bg-[#1C2430]">
                                    <tr>
                                        <th class="px-6 py-3.5 text-xs font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider">Course Name</th>
                                        <th class="px-6 py-3.5 text-xs font-bold text-slate-600 dark:text-[#94A3B8] uppercase tracking-wider text-right sm:text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-[#151B23] divide-y divide-slate-100 dark:divide-[#273244]">
                                    @forelse($enrolledCourses as $course)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-[#1C2430]/60 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC] break-words hover-theme-text transition-colors">{{ $course->name }}</div>
                                            @if($course->code)
                                            <div class="text-xs text-slate-500 dark:text-[#94A3B8] font-mono mt-0.5">{{ $course->code }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right sm:text-left whitespace-nowrap">
                                            <a href="{{ route('student.courses.materials', $course->id) }}" class="btn-theme-ghost inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-xl transition-all">
                                                View Materials &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-8 text-center text-sm text-slate-400">
                                            <p class="mb-3 text-sm">You are not enrolled in any courses yet.</p>
                                            <a href="{{ route('student.enrollment.create') }}" class="btn-theme-primary inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl shadow-sm">Enroll in Courses</a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



            </div>

            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">Technology Development (TD)</span>.</p>
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


