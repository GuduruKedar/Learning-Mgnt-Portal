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
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 hidden sm:inline-block">Faculty Portal</span>
                <span class="text-sm font-bold text-gray-700 hidden md:inline-block">&bull; {{ $school->name ?? 'University' }}</span>
            </div>

            <div class="flex items-center ml-auto">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0" aria-label="Open user profile menu">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile Photo">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'S', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Staff' }}</span>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Executive Welcome Banner -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 lg:p-7 transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        <!-- Left Side: User Greeting & Status -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                    Faculty Academic Portal
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Active Faculty
                                </span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                                Welcome back, {{ $staff->profile->first_name ?? $staff->username }}
                            </h1>
                            <p class="text-sm text-gray-500 max-w-2xl leading-relaxed">
                                Here is your academic teaching overview. Track assigned curriculum courses, manage uploaded study materials, and monitor student assessments.
                            </p>
                        </div>

                        <!-- Right Side: Clean Academic Affiliation Card Group -->
                        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-stretch sm:items-center gap-3 shrink-0">
                            <div class="bg-gray-50/90 rounded-xl p-3 border border-gray-200/80 flex flex-col gap-2 min-w-[280px]">
                                @if($school)
                                <div class="flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] uppercase font-bold text-gray-400 block leading-none">School</span>
                                            <span class="font-bold text-gray-800 text-xs truncate block" title="{{ $school->name }}">{{ ucwords(strtolower($school->name)) }}</span>
                                        </div>
                                    </div>
                                    <span class="font-mono text-[10px] font-bold bg-white text-purple-700 border border-purple-200 px-2 py-0.5 rounded shadow-2xs shrink-0">{{ $school->code }}</span>
                                </div>
                                @endif

                                @if($department)
                                <div class="flex items-center justify-between gap-3 text-xs pt-2 border-t border-gray-200/60">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-6 h-6 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] uppercase font-bold text-gray-400 block leading-none">Department</span>
                                            <span class="font-bold text-gray-800 text-xs truncate block" title="{{ $department->name }}">{{ ucwords(strtolower($department->name)) }}</span>
                                        </div>
                                    </div>
                                    <span class="font-mono text-[10px] font-bold bg-white text-blue-700 border border-blue-200 px-2 py-0.5 rounded shadow-2xs shrink-0">{{ $department->code }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Executive Professional Stat Cards Grid: 3 Core Teaching Metrics (Display Only) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Card 1: Courses to Teach -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200/80 p-6 flex items-center gap-4 cursor-default select-none">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 shadow-2xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 mb-0.5">Courses to Teach</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-gray-900 leading-tight">{{ $coursesToTeachCount ?? $assignedCourses->count() }}</span>
                                <span class="text-xs font-semibold text-gray-500">{{ Str::plural('Subject', $coursesToTeachCount ?? $assignedCourses->count()) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Assessments Given -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200/80 p-6 flex items-center gap-4 cursor-default select-none">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shrink-0 shadow-2xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-purple-600 mb-0.5">Assessments Given</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-gray-900 leading-tight">{{ $totalAssignmentsCount }}</span>
                                <span class="text-xs font-semibold text-gray-500">{{ Str::plural('Assessment', $totalAssignmentsCount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Materials Uploaded -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200/80 p-6 flex items-center gap-4 cursor-default select-none">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 shadow-2xs">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-600 mb-0.5">Materials Uploaded</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-black text-gray-900 leading-tight">{{ $totalMaterialsCount }}</span>
                                <span class="text-xs font-semibold text-gray-500">{{ Str::plural('Resource', $totalMaterialsCount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses You Teach Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                                <span>Courses You Need to Teach</span>
                                <span class="text-xs font-bold bg-indigo-100 text-indigo-800 px-2.5 py-0.5 rounded-full">{{ $coursesToTeachCount ?? $assignedCourses->count() }}</span>
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">Assigned subjects with material uploads, assessments given, and enrolled students</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($assignedCourses as $course)
                        <div class="group bg-white rounded-2xl border border-gray-200/90 shadow-2xs hover:shadow-lg hover:border-indigo-300 transition-all duration-200 flex flex-col justify-between overflow-hidden">
                            <!-- Top Gradient Accent Line -->
                            <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600"></div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Badges Header (Code, Regulation on Left | Year/Sem on Right) -->
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                            <a href="{{ route('staff.courses.materials', $course->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono tracking-wider bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200/80 shadow-2xs transition-colors">
                                                {{ $course->code }}
                                            </a>
                                            @if($course->regulation)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                                    {{ $course->regulation->code ?: $course->regulation->name }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($course->year || $course->semester)
                                            <span class="inline-flex items-center text-[11px] font-semibold text-gray-600 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-md shrink-0">
                                                @if($course->year)Year {{ $course->year }}@endif
                                                @if($course->year && $course->semester), @endif
                                                @if($course->semester)Sem {{ $course->semester }}@endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Course Name (Clickable link to course) -->
                                    <h3 class="text-lg font-bold text-gray-900 leading-snug mb-2">
                                        <a href="{{ route('staff.courses.materials', $course->id) }}" class="hover:text-indigo-600 transition-colors flex items-center justify-between group-hover:text-indigo-600">
                                            <span>{{ $course->name }}</span>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-transform transform group-hover:translate-x-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </h3>

                                    <!-- Course Metadata (Department & Curriculum) -->
                                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium flex-wrap mb-4">
                                        @if($course->department)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $course->department->name }}
                                        </span>
                                        @endif

                                        @if($course->regulation && !empty($course->regulation->curriculum))
                                        <span class="text-gray-300">&bull;</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Curriculum: {{ $course->regulation->curriculum }}
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Key Metrics in Course: Clickable into Course Subsections -->
                                    <div class="grid grid-cols-3 gap-2 bg-gray-50 rounded-xl p-2.5 border border-gray-100 text-center mb-4">
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#materials" class="p-1 rounded-lg hover:bg-emerald-50/80 transition-colors block group/metric" title="View materials uploaded">
                                            <span class="block text-base font-black text-emerald-600 group-hover/metric:scale-105 transition-transform">{{ $course->materials_count ?? 0 }}</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight group-hover/metric:text-emerald-700">Materials</span>
                                        </a>
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#assignments" class="p-1 rounded-lg hover:bg-purple-50/80 transition-colors border-x border-gray-200 block group/metric" title="View assessments given">
                                            <span class="block text-base font-black text-purple-600 group-hover/metric:scale-105 transition-transform">{{ $course->assignments_count ?? 0 }}</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight group-hover/metric:text-purple-700">Assessments</span>
                                        </a>
                                        <a href="{{ route('staff.courses.materials', $course->id) }}#overview" class="p-1 rounded-lg hover:bg-indigo-50/80 transition-colors block group/metric" title="View students enrolled">
                                            <span class="block text-base font-black text-indigo-600 group-hover/metric:scale-105 transition-transform">{{ $course->enrollments_count ?? 0 }}</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight group-hover/metric:text-indigo-700">Students</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <a href="{{ route('staff.courses.materials', $course->id) }}#materials" class="flex-1 text-center py-2 px-3 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        Materials
                                    </a>
                                    <a href="{{ route('staff.courses.materials', $course->id) }}#assignments" class="flex-1 text-center py-2 px-3 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        Assessments
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mx-auto text-gray-400 mb-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">No Assigned Courses</h3>
                            <p class="text-xs text-gray-500 mt-1">You have not been assigned to any courses yet. Contact your coordinator to allocate courses.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
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
