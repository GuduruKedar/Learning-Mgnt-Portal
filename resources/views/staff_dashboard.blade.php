<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - LMS Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="w-full space-y-6">
                
                <!-- Executive Welcome Banner -->
                <div class="theme-hero-card rounded-2xl p-6 lg:p-7 shadow-xl relative overflow-hidden text-white">
                    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full blur-3xl pointer-events-none opacity-40" style="background: var(--theme-glow);"></div>
                    <div class="absolute right-1/3 -bottom-10 h-48 w-48 rounded-full blur-2xl pointer-events-none opacity-30" style="background: var(--theme-primary-light);"></div>

                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        <!-- Left Side: User Greeting & Status -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full theme-hero-badge text-xs font-semibold uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full animate-pulse" style="background: var(--theme-primary);"></span>
                                    Faculty Academic Portal
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-200 border border-emerald-400/30">
                                    Active Faculty
                                </span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Welcome back, {{ $staff->profile->first_name ?? $staff->username }}
                            </h1>
                            <p class="text-sm text-white/80 max-w-2xl leading-relaxed">
                                Here is your academic teaching overview. Track assigned curriculum courses, manage uploaded study materials, and monitor student assessments.
                            </p>
                        </div>

                        <!-- Right Side: Clean Academic Affiliation Card Group -->
                        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-stretch sm:items-center gap-3 shrink-0">
                            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3.5 border border-white/15 flex flex-col gap-2 min-w-[280px]">
                                @if($school)
                                <div class="flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-md bg-white/15 text-white flex items-center justify-center shrink-0 border border-white/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] uppercase font-bold text-white/70 block leading-none">School</span>
                                            <span class="font-bold text-white text-xs truncate block" title="{{ $school->name }}">{{ ucwords(strtolower($school->name)) }}</span>
                                        </div>
                                    </div>
                                    <span class="font-mono text-[10px] font-bold bg-white/20 text-white border border-white/30 px-2 py-0.5 rounded shadow-2xs shrink-0">{{ $school->code }}</span>
                                </div>
                                @endif

                                @if($department)
                                <div class="flex items-center justify-between gap-3 text-xs pt-2 border-t border-white/15">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-md bg-white/15 text-white flex items-center justify-center shrink-0 border border-white/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] uppercase font-bold text-white/70 block leading-none">Department</span>
                                            <span class="font-bold text-white text-xs truncate block" title="{{ $department->name }}">{{ ucwords(strtolower($department->name)) }}</span>
                                        </div>
                                    </div>
                                    <span class="font-mono text-[10px] font-bold bg-white/20 text-white border border-white/30 px-2 py-0.5 rounded shadow-2xs shrink-0">{{ $department->code }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Executive Stat Cards: Single Horizontal Row (3 Core Teaching Metrics) -->
                <div class="grid grid-cols-3 gap-3 sm:gap-5 lg:gap-6">
                    
                    <!-- Card 1: Courses to Teach -->
                    <div class="bg-white dark:bg-[#151B23] rounded-xl sm:rounded-2xl shadow-xs border border-slate-200/80 dark:border-[#273244] p-3 sm:p-5 lg:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4 cursor-default select-none theme-card-hover transition-all">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl theme-icon-box flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[11px] font-extrabold uppercase tracking-wider text-theme-primary mb-0.5 truncate">Courses to Teach</p>
                            <div class="flex items-baseline gap-1 sm:gap-2">
                                <span class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] leading-tight">{{ $coursesToTeachCount ?? $assignedCourses->count() }}</span>
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-[#94A3B8] hidden xs:inline">{{ Str::plural('Subject', $coursesToTeachCount ?? $assignedCourses->count()) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Assessments Given -->
                    <div class="bg-white dark:bg-[#151B23] rounded-xl sm:rounded-2xl shadow-xs border border-slate-200/80 dark:border-[#273244] p-3 sm:p-5 lg:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4 cursor-default select-none theme-card-hover transition-all">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-800/40 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[11px] font-extrabold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-0.5 truncate">Assessments Given</p>
                            <div class="flex items-baseline gap-1 sm:gap-2">
                                <span class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] leading-tight">{{ $totalAssignmentsCount }}</span>
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-[#94A3B8] hidden xs:inline">{{ Str::plural('Assessment', $totalAssignmentsCount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Materials Uploaded -->
                    <div class="bg-white dark:bg-[#151B23] rounded-xl sm:rounded-2xl shadow-xs border border-slate-200/80 dark:border-[#273244] p-3 sm:p-5 lg:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4 cursor-default select-none theme-card-hover transition-all">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/40 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[11px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-0.5 truncate">Materials Uploaded</p>
                            <div class="flex items-baseline gap-1 sm:gap-2">
                                <span class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-[#F8FAFC] leading-tight">{{ $totalMaterialsCount }}</span>
                                <span class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-[#94A3B8] hidden xs:inline">{{ Str::plural('Resource', $totalMaterialsCount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses You Teach Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                                <span>Courses You Need to Teach</span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">{{ $coursesToTeachCount ?? $assignedCourses->count() }}</span>
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Assigned subjects with material uploads, assessments given, and enrolled students</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($assignedCourses as $course)
                        <div class="group bg-white dark:bg-[#151B23] rounded-xl border border-slate-200/90 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden">
                            <!-- Subtle Monochrome/Indigo Top Accent Line -->
                            <div class="h-0.5 bg-slate-200 dark:bg-slate-700 group-hover:bg-indigo-500 transition-colors duration-200"></div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Badges Header (Code, Regulation on Left | Year/Sem on Right) -->
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                            <a href="{{ route('staff.courses.materials', $course->id) }}" class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold font-mono tracking-wide bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors">
                                                {{ $course->code }}
                                            </a>
                                            @if($course->regulation)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                                    {{ $course->regulation->code ?: $course->regulation->name }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($course->year || $course->semester)
                                            <span class="inline-flex items-center text-[11px] font-medium text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-850 border border-slate-200/60 dark:border-slate-750 px-2 py-0.5 rounded-md shrink-0">
                                                @if($course->year)Year {{ $course->year }}@endif
                                                @if($course->year && $course->semester), @endif
                                                @if($course->semester)Sem {{ $course->semester }}@endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Course Name (Clickable link to course) -->
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white leading-snug tracking-tight mb-2">
                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center justify-between group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                            <span>{{ $course->name }}</span>
                                            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-all shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </h3>

                                    <!-- Course Metadata (Department & Curriculum) -->
                                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-normal flex-wrap mb-4">
                                        @if($course->department)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $course->department->name }}
                                        </span>
                                        @endif

                                        @if($course->regulation && !empty($course->regulation->curriculum))
                                        <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            Curriculum: {{ $course->regulation->curriculum }}
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Clean Minimalist Key Metrics Grid -->
                                    <div class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-slate-800 bg-slate-50/70 dark:bg-slate-800/40 rounded-lg p-2 border border-slate-100 dark:border-slate-800 text-center mb-4">
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#materials" class="p-1 rounded hover:bg-white dark:hover:bg-slate-800/80 transition-colors block group/metric" title="View materials uploaded">
                                            <span class="block text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover/metric:text-emerald-600 dark:group-hover/metric:text-emerald-400 transition-colors">{{ $course->materials_count ?? 0 }}</span>
                                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mt-0.5">Materials</span>
                                        </a>
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#assignments" class="p-1 rounded hover:bg-white dark:hover:bg-slate-800/80 transition-colors block group/metric" title="View assessments given">
                                            <span class="block text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover/metric:text-indigo-600 dark:group-hover/metric:text-indigo-400 transition-colors">{{ $course->assignments_count ?? 0 }}</span>
                                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mt-0.5">Assessments</span>
                                        </a>
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#overview" class="p-1 rounded hover:bg-white dark:hover:bg-slate-800/80 transition-colors block group/metric" title="View students enrolled">
                                            <span class="block text-base font-extrabold text-slate-800 dark:text-slate-200 group-hover/metric:text-slate-900 dark:group-hover/metric:text-white transition-colors">{{ $course->enrollments_count ?? 0 }}</span>
                                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mt-0.5">Students</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons: Subtle Monochrome Style -->
                                <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                                    <a href="{{ route('staff.courses.materials', $course->id) }}#materials" class="flex-1 text-center py-2 px-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        Materials
                                    </a>
                                    <a href="{{ route('staff.courses.materials', $course->id) }}#assignments" class="flex-1 text-center py-2 px-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        Assessments
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full bg-white dark:bg-[#151B23] rounded-xl shadow-2xs border border-slate-200 dark:border-slate-800 p-8 text-center">
                            <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800/60 rounded-xl flex items-center justify-center mx-auto text-slate-400 mb-2 border border-slate-200/60 dark:border-slate-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">No Assigned Courses</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">You have not been assigned to any courses yet. Contact your coordinator to allocate courses.</p>
                        </div>
                        @endforelse
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

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif
    </script>
</body>
</html>
