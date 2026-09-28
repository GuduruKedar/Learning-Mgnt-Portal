<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses & Assignments - LMS Faculty</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            <div class="flex items-center">
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
            <div class="max-w-7xl mx-auto space-y-6">
                
                <!-- Page Header with Summary Stats -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Courses & Academic Hub</h1>
                        <p class="mt-1 text-sm text-gray-600">Seamlessly access your assigned courses, learning materials, and student assignments.</p>
                    </div>
                    
                    <!-- Quick Stats Badges -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Courses to Teach</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $courses->count() }}</p>
                            </div>
                        </div>

                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Materials Uploaded</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $totalMaterialsCount }}</p>
                            </div>
                        </div>

                        <div class="inline-flex items-center px-3.5 py-2 rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-500 leading-none">Assessments Given</p>
                                <p class="text-base font-black text-gray-900 leading-tight mt-0.5">{{ $totalAssignmentsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Navigation Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-6 sm:space-x-8" role="tablist" aria-label="Course Sections">
                        <button type="button" id="tab-btn-materials" onclick="switchSectionTab('materials')" role="tab" aria-selected="true" aria-controls="section-materials" class="group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-indigo-600 border-indigo-600 transition-all focus:outline-none">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Course Learning Materials</span>
                            <span class="ml-2.5 py-0.5 px-2.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-700">{{ $courses->count() }}</span>
                        </button>

                        <button type="button" id="tab-btn-assignments" onclick="switchSectionTab('assignments')" role="tab" aria-selected="false" aria-controls="section-assignments" class="group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span>Course Assignments & Assessments</span>
                            <span class="ml-2.5 py-0.5 px-2.5 rounded-full text-xs font-extrabold bg-gray-100 text-gray-600 group-hover:bg-purple-100 group-hover:text-purple-700">{{ $totalAssignmentsCount }}</span>
                        </button>
                    </nav>
                </div>

                <!-- SECTION 1: COURSE LEARNING MATERIALS -->
                <div id="section-materials" role="tabpanel" aria-labelledby="tab-btn-materials" class="space-y-6">
                    
                    <!-- Search & Filter bar for Courses -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="relative w-full sm:max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="courseMaterialSearch" oninput="filterCourseCards(this.value)" placeholder="Search courses by name or code..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <div class="text-xs text-gray-500 font-medium">
                            Showing <span id="visibleCoursesCount" class="font-bold text-gray-800">{{ $courses->count() }}</span> of {{ $courses->count() }} Courses
                        </div>
                    </div>

                    <!-- Recent Uploads Section -->
                    @if($recentUploads->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <h2 class="text-base font-bold text-gray-900 mb-3.5 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Your Recent Material Uploads
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($recentUploads as $upload)
                            <div class="flex items-center justify-between p-3.5 bg-gray-50/80 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-white transition-all shadow-2xs">
                                <div class="min-w-0 pr-3">
                                    <h4 class="font-bold text-gray-800 text-sm truncate">{{ $upload->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">
                                        <span class="font-semibold text-indigo-600">{{ $upload->course->name ?? 'Course' }}</span> &bull; {{ $upload->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('staff.courses.materials', $upload->course_id) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs hover:bg-indigo-50 transition-colors">
                                        Manage
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($groupedCourses->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto text-gray-400 mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No Assigned Courses</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">You do not have any courses assigned to your staff profile yet. Contact your coordinator to allocate courses.</p>
                        </div>
                    @else
                        <div class="space-y-10" id="coursesGridContainer">
                            @foreach($groupedCourses as $regId => $coursesList)
                                @php
                                    $firstCourse = $coursesList->first();
                                    $regulation = $firstCourse ? $firstCourse->regulation : null;
                                    $regCode = $regulation ? ($regulation->code ?: $regulation->name) : 'Standard';
                                    $curriculum = $regulation ? $regulation->curriculum : null;
                                    $programType = $regulation ? $regulation->program_type : null;
                                @endphp
                                <div class="regulation-group" data-reg="{{ strtolower($regCode . ' ' . $curriculum . ' ' . $programType) }}">
                                    <div class="flex items-center justify-between pb-3.5 mb-5 border-b border-gray-200">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h2 class="text-lg font-bold text-gray-900">Regulation {{ $regCode }}</h2>
                                                    @if(!empty($curriculum))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                            Curriculum: {{ $curriculum }}
                                                        </span>
                                                    @endif
                                                    @if(!empty($programType))
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-600">
                                                            {{ $programType }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    {{ $coursesList->count() }} {{ Str::plural('Course', $coursesList->count()) }} assigned
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full">
                                            {{ $coursesList->count() }} {{ Str::plural('Subject', $coursesList->count()) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($coursesList as $course)
                                            <div class="course-card group bg-white rounded-2xl border border-gray-200/90 shadow-2xs hover:shadow-lg hover:border-indigo-300 transition-all duration-200 flex flex-col justify-between overflow-hidden cursor-pointer transform hover:-translate-y-0.5"
                                                 onclick="window.location.href='{{ route('staff.courses.materials', $course->id) }}'"
                                                 data-search="{{ strtolower($course->name . ' ' . $course->code . ' ' . ($course->department->name ?? '')) }}">
                                                <!-- Top Gradient Accent Line -->
                                                <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600"></div>

                                                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                                    <div>
                                                        <!-- Badges Header (Code, Regulation on Left | Year/Sem on Right) -->
                                                        <div class="flex items-center justify-between gap-2 mb-3">
                                                            <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200/80 shadow-2xs">
                                                                    {{ $course->code }}
                                                                </span>
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

                                                        <!-- Course Name -->
                                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors leading-snug mb-1.5">
                                                            <a href="{{ route('staff.courses.materials', $course->id) }}" class="hover:underline focus:outline-none">
                                                                {{ $course->name }}
                                                            </a>
                                                        </h3>

                                                        <!-- Course Metadata (Department & Curriculum) -->
                                                        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium flex-wrap">
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
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- SECTION 2: COURSE ASSIGNMENTS -->
                <div id="section-assignments" role="tabpanel" aria-labelledby="tab-btn-assignments" class="hidden space-y-6">
                    
                    <!-- Assignments Filter & Actions Header -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-center gap-3 flex-1">
                            <!-- Live Search -->
                            <div class="relative w-full sm:w-72">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="assignmentSearchInput" oninput="filterAssignmentCards(this.value)" placeholder="Search assignments..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>

                            <!-- Course Filter Dropdown -->
                            <div class="w-full sm:w-60">
                                <select id="assignmentCourseSelect" onchange="filterAssignmentByCourse(this.value)" class="w-full py-2 px-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                    <option value="">All Assigned Courses</option>
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs text-sm font-bold transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                + Create New Assignment
                            </a>
                        </div>
                    </div>

                    <!-- Assignments Cards Grid -->
                    @if($assignments->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto text-purple-600 mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No Assignments Created Yet</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">You haven't created any assignments for your assigned courses yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create Your First Assignment
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="assignmentsGrid">
                            @foreach($assignments as $assignment)
                                <div class="assignment-card bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-purple-300 transition-all flex flex-col justify-between overflow-hidden"
                                     data-course-id="{{ $assignment->course_id }}"
                                     data-search="{{ strtolower($assignment->title . ' ' . ($assignment->course->name ?? '') . ' ' . ($assignment->course->code ?? '')) }}">
                                    <!-- Top Accent Line -->
                                    <div class="h-1.5 bg-gradient-to-r from-purple-500 to-indigo-600"></div>

                                    <div class="p-6 flex-1 flex flex-col justify-between">
                                        <div>
                                            <!-- Course Code & Due Date Badge -->
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs">
                                                    {{ $assignment->course->code ?? 'Course' }}
                                                </span>
                                                @if($assignment->due_date)
                                                    <span class="inline-flex items-center text-xs font-medium {{ $assignment->isPastDue() ? 'text-red-700 bg-red-50 border border-red-200' : 'text-emerald-700 bg-emerald-50 border border-emerald-200' }} px-2.5 py-0.5 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Due: {{ $assignment->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Assignment Title -->
                                            <h3 class="text-base font-bold text-gray-900 group-hover:text-purple-600 transition-colors leading-snug mb-1">
                                                {{ $assignment->title }}
                                            </h3>

                                            <!-- Course Name -->
                                            <p class="text-xs text-gray-500 font-semibold mb-3">
                                                {{ $assignment->course->name ?? 'Course' }}
                                            </p>

                                            @if($assignment->description)
                                                <p class="text-xs text-gray-600 line-clamp-2 mb-4 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                                    {{ $assignment->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Metrics Row -->
                                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 text-center">
                                            <div class="p-2 rounded-xl bg-gray-50">
                                                <p class="text-sm font-black text-gray-900">{{ $assignment->max_marks }}</p>
                                                <p class="text-[10px] uppercase font-bold text-gray-400">Max Marks</p>
                                            </div>
                                            <div class="p-2 rounded-xl bg-gray-50">
                                                <p class="text-sm font-black text-gray-900">{{ $assignment->questions->count() }}</p>
                                                <p class="text-[10px] uppercase font-bold text-gray-400">Questions</p>
                                            </div>
                                            <div class="p-2 rounded-xl bg-purple-50 border border-purple-100">
                                                <p class="text-sm font-black text-purple-700">{{ $assignment->submissions_count }}</p>
                                                <p class="text-[10px] uppercase font-bold text-purple-500">Submitted</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Actions -->
                                    <div class="bg-gray-50/80 px-5 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                        <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-xs text-xs font-bold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View / Grade
                                        </a>
                                        <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="inline-flex justify-center items-center px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-semibold transition-colors" title="Edit Assignment">
                                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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

        // Accessible Tab Switcher
        window.switchSectionTab = function(tabKey) {
            const materialsSection = document.getElementById('section-materials');
            const assignmentsSection = document.getElementById('section-assignments');
            const materialsTabBtn = document.getElementById('tab-btn-materials');
            const assignmentsTabBtn = document.getElementById('tab-btn-assignments');

            if (tabKey === 'materials') {
                materialsSection.classList.remove('hidden');
                assignmentsSection.classList.add('hidden');

                materialsTabBtn.setAttribute('aria-selected', 'true');
                materialsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-indigo-600 border-indigo-600 transition-all focus:outline-none';
                
                assignmentsTabBtn.setAttribute('aria-selected', 'false');
                assignmentsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none';
                
                window.history.replaceState(null, null, '#materials');
            } else {
                materialsSection.classList.add('hidden');
                assignmentsSection.classList.remove('hidden');

                assignmentsTabBtn.setAttribute('aria-selected', 'true');
                assignmentsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-bold text-sm text-purple-600 border-purple-600 transition-all focus:outline-none';
                
                materialsTabBtn.setAttribute('aria-selected', 'false');
                materialsTabBtn.className = 'group inline-flex items-center py-3.5 px-1 border-b-2 font-semibold text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 transition-all focus:outline-none';
                
                window.history.replaceState(null, null, '#assignments');
            }
        };

        // Live Search for Course Learning Materials
        window.filterCourseCards = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.course-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                if (cleanQuery === '' || searchData.includes(cleanQuery)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Handle regulation group headers visibility
            document.querySelectorAll('.regulation-group').forEach(group => {
                const visibleInGroup = group.querySelectorAll('.course-card:not([style*="display: none"])').length;
                group.style.display = visibleInGroup > 0 ? '' : 'none';
            });

            const countEl = document.getElementById('visibleCoursesCount');
            if (countEl) countEl.textContent = visibleCount;
        };

        // Live Search for Assignments
        window.filterAssignmentCards = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const courseFilter = (document.getElementById('assignmentCourseSelect')?.value || '').trim();
            const cards = document.querySelectorAll('.assignment-card');

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const cardCourseId = card.getAttribute('data-course-id') || '';

                const matchesQuery = cleanQuery === '' || searchData.includes(cleanQuery);
                const matchesCourse = courseFilter === '' || cardCourseId === courseFilter;

                if (matchesQuery && matchesCourse) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        };

        // Course filter for Assignments
        window.filterAssignmentByCourse = function(courseId) {
            const searchQuery = document.getElementById('assignmentSearchInput')?.value || '';
            window.filterAssignmentCards(searchQuery);
        };

        // Check URL hash on load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#assignments') {
                window.switchSectionTab('assignments');
            }
        });
    </script>
</body>
</html>
