<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->name }} ({{ $course->code }}) - LMS Faculty Portal</title>
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
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-50">
            <div class="w-full space-y-6">

                <!-- Breadcrumbs & Quick Navigation -->
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs hover:shadow transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Back to Dashboard</span>
                        </a>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-700 font-bold">{{ $course->code }}</span>
                        <span class="text-gray-300">&bull;</span>
                        <span class="text-gray-600 truncate max-w-xs">{{ $course->name }}</span>
                    </nav>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Assigned Course Hub
                        </span>
                    </div>
                </div>

                <!-- Course Details Banner -->
                <div class="bg-white rounded-2xl border border-gray-200/90 shadow-2xs p-6 sm:p-7 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-72 h-72 bg-gradient-to-br from-indigo-100/50 via-purple-50/30 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative space-y-3">
                        <!-- Course Metadata Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold font-mono bg-indigo-600 text-white tracking-wider shadow-2xs">
                                {{ $course->code }}
                            </span>

                            @if($course->regulation)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    {{ $course->regulation->name }}
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Year {{ $course->year ?? 1 }} &bull; Semester {{ $course->semester ?? 1 }}
                            </span>
                        </div>

                        <!-- Course Title & Department -->
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                                {{ $course->name }}
                            </h1>
                            <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs sm:text-sm text-gray-600 font-medium">
                                @if($course->department)
                                    <span class="inline-flex items-center gap-1.5 text-gray-700 font-semibold">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $course->department->name }}
                                    </span>
                                @endif
                                @if($course->department && $course->department->school)
                                    <span class="text-gray-300">&bull;</span>
                                    <span class="inline-flex items-center gap-1.5 text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                        {{ $course->department->school->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3 Quick Interactive Metric Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Course Materials Card -->
                    <button type="button" onclick="switchCourseTab('materials')" id="metric-card-materials" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white hover:bg-indigo-50/40 border border-gray-200 hover:border-indigo-300 transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 group-hover:bg-indigo-700 text-white flex items-center justify-center shrink-0 shadow-sm transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-700">Course Materials</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $totalMaterialsCount }}</span>
                                <span class="text-xs font-medium text-gray-500">({{ $filesCount }} files &bull; {{ $linksCount }} links)</span>
                            </div>
                        </div>
                    </button>

                    <!-- Assessments Metric Card -->
                    <button type="button" onclick="switchCourseTab('assignments')" id="metric-card-assignments" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white hover:bg-purple-50/40 border border-gray-200 hover:border-purple-300 transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow">
                        <div class="w-12 h-12 rounded-xl bg-purple-600 group-hover:bg-purple-700 text-white flex items-center justify-center shrink-0 shadow-sm transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-purple-700">Assessments</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $totalAssignmentsCount }}</span>
                                <span class="text-xs font-medium text-gray-500">({{ $activeAssignmentsCount }} active)</span>
                            </div>
                        </div>
                    </button>

                    <!-- Students Metric Card -->
                    <button type="button" onclick="switchCourseTab('overview')" id="metric-card-overview" class="flex items-center gap-4 p-4 sm:p-5 rounded-2xl bg-white hover:bg-emerald-50/40 border border-gray-200 hover:border-emerald-300 transition-all duration-200 text-left cursor-pointer group shadow-2xs hover:shadow">
                        <div class="w-12 h-12 rounded-xl bg-emerald-600 group-hover:bg-emerald-700 text-white flex items-center justify-center shrink-0 shadow-sm transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-700">Enrolled Students</p>
                            <div class="mt-1 flex items-baseline gap-1.5 flex-wrap">
                                <span class="text-2xl sm:text-3xl font-black text-gray-900 leading-none">{{ $enrolledStudentsCount }}</span>
                                <span class="text-xs font-medium text-gray-500">Active learners</span>
                            </div>
                        </div>
                    </button>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center shadow-xs">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-semibold">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-xs">
                        <ul class="list-disc pl-5 text-sm space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ========================================================= -->
                <!-- TAB PANEL 1: LEARNING MATERIALS                           -->
                <!-- ========================================================= -->
                <div id="course-panel-materials" role="tabpanel" aria-labelledby="course-tab-btn-materials" class="space-y-6">
                    
                    <!-- Materials Directory (Full Width & Professional) -->
                    <div class="bg-white rounded-2xl shadow-2xs border border-gray-200/90 overflow-hidden flex flex-col">
                        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-extrabold text-gray-900 tracking-tight">Learning Materials Directory</h3>
                                    <span id="materialsCountBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100 transition-all">
                                        {{ $totalMaterialsCount }} Total
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Direct learning access for faculty and enrolled students</p>
                            </div>
                            
                            <!-- Live Search, Filter & Manage Materials Action Button -->
                            <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                                <!-- Live Search Input -->
                                <div class="relative w-full sm:w-56">
                                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" id="materialsSearchInput" oninput="filterMaterialsLive()" placeholder="Search title or format..." class="block w-full pl-8 pr-7 py-2 border border-gray-300 rounded-xl text-xs bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    <button type="button" id="materialsSearchClearBtn" onclick="clearMaterialsSearch()" class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600 hidden cursor-pointer" title="Clear Search">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <!-- Live Type Filter Dropdown -->
                                <select id="materialsTypeSelect" onchange="filterMaterialsLive()" class="py-2 px-2.5 border border-gray-300 bg-white rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                    <option value="">All Types</option>
                                    <option value="link">🔗 Links</option>
                                    <option value="file">📄 Files</option>
                                </select>

                                <!-- Reset Filters Button -->
                                <button type="button" id="materialsResetFilterBtn" onclick="resetAllMaterialFilters()" class="hidden inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-2 rounded-xl text-xs font-semibold transition-colors cursor-pointer" title="Reset Filters">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    <span>Reset</span>
                                </button>

                                <!-- Manage Materials Trigger Button -->
                                <button type="button" onclick="openManageMaterialsModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs hover:shadow transition-all transform hover:-translate-y-0.5 cursor-pointer shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Manage Materials</span>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-gray-50/40 flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="materialsCardsContainer">
                                @forelse($materials as $material)
                                <div class="material-card bg-white rounded-2xl border border-gray-200 shadow-2xs hover:shadow-md hover:border-indigo-200 transition-all duration-200 flex flex-col group h-full justify-between overflow-hidden"
                                     data-material-title="{{ strtolower($material->title) }}"
                                     data-material-type="{{ strtolower($material->type) }}"
                                     data-material-platform="{{ strtolower($material->platform) }}"
                                     data-material-staff="{{ strtolower($material->staff->profile->first_name ?? '') }}">
                                    <div class="p-5 flex-1 relative">
                                        {{-- Department-wide faculty deletion permission --}}
                                        @php
                                            $canDeleteMaterial = Auth::user()->role === 'sa'
                                                 || (Auth::user()->profile && Auth::user()->profile->departments_id === $course->department_id)
                                                 || ($course->staff && $course->staff->contains(Auth::id()))
                                                 || $material->staff_id === Auth::id();
                                        @endphp
                                        @if($canDeleteMaterial)
                                        <div class="absolute top-4 right-4 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                            <form action="{{ route('staff.courses.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8.5 h-8.5 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 cursor-pointer" title="Delete Material">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                        
                                        <div class="flex items-start space-x-3.5">
                                            <div class="flex-shrink-0">
                                                @if($material->platform === 'youtube')
                                                    <div class="h-11 w-11 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                @elseif($material->platform === 'drive' || $material->platform === 'onedrive')
                                                    <div class="h-11 w-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                                    </div>
                                                @elseif($material->platform === 'pdf')
                                                    <div class="h-11 w-11 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @elseif($material->platform === 'word')
                                                    <div class="h-11 w-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                @elseif($material->platform === 'excel')
                                                    <div class="h-11 w-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @elseif($material->platform === 'ppt')
                                                    <div class="h-11 w-11 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                    </div>
                                                @else
                                                    <div class="h-11 w-11 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shadow-2xs">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0 pr-6">
                                                <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $material->title }}">{{ $material->title }}</h4>
                                                <div class="flex items-center text-[11px] text-gray-500 mt-1 capitalize gap-1.5 flex-wrap">
                                                    <span class="bg-gray-100 px-2 py-0.5 rounded-md font-semibold text-gray-700">{{ $material->platform }}</span>
                                                    <span>&bull;</span>
                                                    <span>{{ $material->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                            @if($material->staff && $material->staff->profile && $material->staff->profile->photo)
                                                <img src="{{ asset('storage/' . $material->staff->profile->photo) }}" class="w-5 h-5 rounded-full object-cover">
                                            @else
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                                                    {{ substr($material->staff->profile->first_name ?? 'S', 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="text-xs text-gray-500">Added by <span class="font-semibold text-gray-700">{{ $material->staff->profile->first_name ?? 'Staff' }}</span></span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50/80 px-4 py-2.5 border-t border-gray-100">
                                        @if($material->type === 'link')
                                            <a href="{{ $material->url_or_path }}" target="_blank" class="flex items-center justify-center w-full text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors">
                                                Open Link <svg class="ml-1.5 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <div class="flex items-center justify-between w-full gap-2">
                                                <a href="{{ route('materials.view', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors">
                                                    View <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <div class="w-px h-3 bg-gray-200"></div>
                                                <a href="{{ route('materials.download', $material->id) }}" target="_blank" class="flex-1 flex items-center justify-center text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-1.5 rounded-lg transition-colors">
                                                    Download <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-full py-16 px-6 text-center bg-white border border-gray-100 rounded-2xl shadow-2xs">
                                    <div class="mx-auto w-16 h-16 bg-gradient-to-tr from-indigo-100 to-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 ring-8 ring-indigo-50/60 shadow-xs">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <h3 class="text-base font-extrabold text-gray-900 mb-1">No Materials Uploaded Yet</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                                        Publish lecture notes, PDF documents, Google Drive folders, or video links for your students.
                                    </p>
                                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                                        <button type="button" onclick="openManageMaterialsModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span>Manage / Add Material</span>
                                        </button>
                                    </div>
                                </div>
                                @endforelse

                                <!-- Dynamic No Matches Empty State for Live Search/Filter -->
                                <div id="materialsNoMatchState" class="col-span-full py-14 px-6 text-center bg-white border border-dashed border-gray-200 rounded-2xl hidden">
                                    <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">No Matching Learning Materials</h3>
                                    <p class="text-xs text-gray-500 mt-1">No learning materials match your current search or type filter.</p>
                                    <button type="button" onclick="resetAllMaterialFilters()" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        <span>Reset Filters</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- TAB PANEL 2: ASSIGNMENTS & ASSESSMENTS                    -->
                <!-- ========================================================= -->
                <div id="course-panel-assignments" role="tabpanel" aria-labelledby="course-tab-btn-assignments" class="hidden space-y-6">
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Course Assignments & Quizzes</h3>
                            <p class="text-xs text-gray-500">Manage, evaluate submissions, and publish assessments for {{ $course->name }}</p>
                        </div>
                        <a href="{{ route('staff.assignments.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-xs text-xs font-bold transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            + Create New Assignment
                        </a>
                    </div>

                    @if($assignments->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto text-purple-600 mb-4">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">No Assignments For This Course</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Create MCQ assessments, questions, or assignments to evaluate student progress.</p>
                            <div class="mt-6">
                                <a href="{{ route('staff.assignments.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create First Assignment
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($assignments as $assignment)
                                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-purple-300 transition-all flex flex-col justify-between overflow-hidden">
                                    <div class="h-1.5 bg-gradient-to-r from-purple-500 to-indigo-600"></div>

                                    <div class="p-6 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs">
                                                    {{ $course->code }}
                                                </span>
                                                @if($assignment->due_date)
                                                    <span class="inline-flex items-center text-xs font-medium {{ $assignment->isPastDue() ? 'text-red-700 bg-red-50 border border-red-200' : 'text-emerald-700 bg-emerald-50 border border-emerald-200' }} px-2.5 py-0.5 rounded-full">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Due: {{ $assignment->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h3 class="text-base font-bold text-gray-900 leading-snug mb-1">
                                                {{ $assignment->title }}
                                            </h3>

                                            @if($assignment->description)
                                                <p class="text-xs text-gray-600 line-clamp-2 mt-2 mb-4 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                                    {{ $assignment->description }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Metrics Row -->
                                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 text-center mt-3">
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
                                        <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-2xs text-xs font-bold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View / Grade
                                        </a>
                                        <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="inline-flex justify-center items-center px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors" title="Edit Assignment">
                                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ========================================================= -->
                <!-- TAB PANEL 3: COURSE OVERVIEW & ENROLLED STUDENTS           -->
                <!-- ========================================================= -->
                <div id="course-panel-overview" role="tabpanel" aria-labelledby="course-tab-btn-overview" class="hidden space-y-6">
                    
                    <!-- Course Academic Info Banner -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="flex items-center gap-2.5 pb-4 mb-4 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Academic Course Information</h3>
                                <p class="text-xs text-gray-500">Official curriculum, department, and semester specifications</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Course Code & Name</p>
                                <p class="font-bold text-gray-900 text-sm mt-1 flex items-center gap-1.5 flex-wrap">
                                    <span class="font-mono text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded text-xs border border-indigo-100">{{ $course->code }}</span>
                                    <span>{{ $course->name }}</span>
                                </p>
                            </div>

                            <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Department</p>
                                <p class="font-bold text-gray-900 text-sm mt-1 truncate" title="{{ $course->department->name ?? 'N/A' }}">
                                    {{ $course->department->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Regulation & Curriculum</p>
                                <p class="font-bold text-gray-900 text-sm mt-1">
                                    Regulation {{ $course->regulation->code ?? 'Standard' }}
                                    @if($course->regulation && $course->regulation->curriculum)
                                        <span class="text-xs text-gray-500 font-medium">({{ $course->regulation->curriculum }})</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Semester & Year</p>
                                <p class="font-bold text-gray-900 text-sm mt-1">
                                    Year {{ $course->year ?? 1 }}, Semester {{ $course->semester ?? 1 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Students List Card (Full Width) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Table Card Header -->
                        <div class="p-5 border-b border-gray-100 bg-gray-50/70 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-gray-900">Enrolled Students</h3>
                                        <span id="enrolledCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            {{ $course->enrollments->count() }} {{ Str::plural('Student', $course->enrollments->count()) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">Students actively registered and participating in this course</p>
                                </div>
                            </div>
                            <div class="relative w-full sm:w-72">
                                <input type="text" id="studentSearchInput" oninput="filterEnrolledStudents(this.value)" placeholder="Search by name, roll no, email..." class="w-full pl-9 pr-3 py-2 text-xs bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition-shadow shadow-xs">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80 text-[11px] uppercase tracking-wider font-bold text-gray-500 border-b border-gray-200">
                                        <th class="py-3.5 px-4 text-center w-12">#</th>
                                        <th class="py-3.5 px-4">Student Name</th>
                                        <th class="py-3.5 px-4">Roll Number / ID</th>
                                        <th class="py-3.5 px-4">Department / Program</th>
                                        <th class="py-3.5 px-4">Email Address</th>
                                        <th class="py-3.5 px-4">Contact</th>
                                        <th class="py-3.5 px-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100" id="studentsTableBody">
                                    @forelse($course->enrollments as $index => $student)
                                    @php
                                        $firstName = $student->profile->first_name ?? '';
                                        $lastName = $student->profile->last_name ?? '';
                                        $fullName = trim($firstName . ' ' . $lastName);
                                        if (empty($fullName)) {
                                            $fullName = $student->username;
                                        }
                                        $email = $student->email ?: ($student->profile->email ?? null);
                                        $phone = $student->profile->phone ?? null;
                                        $deptName = $student->profile->department->name ?? ($student->profile->departments_id ?? ($course->department->name ?? '—'));
                                        $programName = $student->profile->program->name ?? ($student->profile->programs_id ?? null);
                                    @endphp
                                    <tr class="student-row hover:bg-indigo-50/40 transition-colors" data-student-search="{{ strtolower($fullName . ' ' . $student->username . ' ' . ($email ?? '') . ' ' . ($deptName ?? '') . ' ' . ($phone ?? '')) }}">
                                        <td class="py-3.5 px-4 text-center font-mono text-xs text-gray-400 font-semibold student-index">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                                    {{ substr($firstName ?: ($student->username ?: 'S'), 0, 1) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-gray-900 block leading-tight">{{ $fullName }}</span>
                                                    <span class="text-[11px] text-gray-400 sm:hidden font-mono">{{ $student->username }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $student->username }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            <p class="font-medium text-gray-800">{{ $deptName }}</p>
                                            @if($programName)
                                                <p class="text-[11px] text-gray-400">{{ $programName }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            @if($email)
                                                <a href="mailto:{{ $email }}" class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 hover:underline">
                                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    {{ $email }}
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic">Not specified</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-gray-600">
                                            @if($phone)
                                                <span class="font-mono">{{ $phone }}</span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-gray-400">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-600">No Students Enrolled</p>
                                            <p class="text-xs text-gray-400 mt-1">There are currently no students registered for this course.</p>
                                        </td>
                                    </tr>
                                    @endforelse

                                    <!-- Filter No-Match State -->
                                    <tr id="no-students-matched-row" style="display: none;">
                                        <td colspan="7" class="py-10 text-center text-gray-500">
                                            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2 font-bold text-sm">
                                                !
                                            </div>
                                            <p class="text-xs font-semibold text-gray-700">No matching students found</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">Try searching with a different name or roll number.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

    <!-- Manage Materials Modal -->
    <div id="manageMaterialsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 overflow-y-auto transition-opacity" role="dialog" aria-labelledby="manageMaterialsModalTitle" aria-modal="true">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden border border-gray-100 transform transition-all my-8">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 id="manageMaterialsModalTitle" class="text-base font-extrabold text-gray-900">Manage Course Materials</h3>
                        <p class="text-xs text-gray-500 font-medium">{{ $course->code }} &bull; {{ $course->name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeManageMaterialsModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-colors focus:outline-none" aria-label="Close modal">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Segmented Switcher Controls (Link vs File) -->
            <div class="p-3.5 bg-gray-50/80 border-b border-gray-100">
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-gray-200/70 rounded-xl">
                    <button type="button" id="tab-link-btn" class="flex items-center justify-center gap-2 py-2 px-3 text-xs font-bold rounded-lg transition-all shadow-xs bg-white text-indigo-700 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        <span>Web Link</span>
                    </button>
                    <button type="button" id="tab-file-btn" class="flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <span>Upload File</span>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Add Link Form -->
                <div id="tab-link-content" class="block">
                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="link">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Material Title <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                    <input type="text" name="title" value="{{ old('type') === 'link' ? old('title') : '' }}" placeholder="e.g., Lecture 1 Video / Unit 1 Notes" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror transition-all" required>
                                </div>
                                @if(old('type') === 'link')
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Platform / Source <span class="text-red-500">*</span></label>
                                <select name="platform" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 bg-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('platform') border-red-500 @enderror transition-all" required>
                                    <option value="">Select Platform</option>
                                    <option value="youtube" {{ old('type') === 'link' && old('platform') == 'youtube' ? 'selected' : '' }}>🎥 YouTube Video</option>
                                    <option value="drive" {{ old('type') === 'link' && old('platform') == 'drive' ? 'selected' : '' }}>📁 Google Drive Folder / File</option>
                                    <option value="onedrive" {{ old('type') === 'link' && old('platform') == 'onedrive' ? 'selected' : '' }}>☁️ Microsoft OneDrive</option>
                                </select>
                                @if(old('type') === 'link')
                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Direct URL <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    </div>
                                    <input type="url" name="url" value="{{ old('type') === 'link' ? old('url') : '' }}" placeholder="https://youtube.com/... or https://drive.google.com/..." class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('url') border-red-500 @enderror transition-all" required>
                                </div>
                                @if(old('type') === 'link')
                                    @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                                <button type="button" onclick="closeManageMaterialsModal()" class="px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none">Cancel</button>
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Save Link Material</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Upload File Form -->
                <div id="tab-file-content" class="hidden">
                    <form action="{{ route('staff.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data" id="course-material-upload-form">
                        @csrf
                        <input type="hidden" name="type" value="file">
                        <input type="hidden" name="file_base64" id="file_base64">
                        <input type="hidden" name="file_name" id="file_name">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Document Title <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <input type="text" name="title" value="{{ old('type') === 'file' ? old('title') : '' }}" placeholder="e.g., Unit 1 Lecture Presentation" class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror transition-all" required>
                                </div>
                                @if(old('type') === 'file')
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Document Format <span class="text-red-500">*</span></label>
                                <select name="platform" id="file-type-select" class="w-full px-3 py-2.5 text-xs sm:text-sm border border-gray-300 bg-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('platform') border-red-500 @enderror transition-all" required>
                                    <option value="">Select Document Format</option>
                                    <option value="pdf" {{ old('type') === 'file' && old('platform') == 'pdf' ? 'selected' : '' }}>📄 PDF Document (.pdf)</option>
                                    <option value="word" {{ old('type') === 'file' && old('platform') == 'word' ? 'selected' : '' }}>📝 Word Document (.doc, .docx)</option>
                                    <option value="excel" {{ old('type') === 'file' && old('platform') == 'excel' ? 'selected' : '' }}>📊 Excel Spreadsheet (.xls, .xlsx, .csv)</option>
                                    <option value="ppt" {{ old('type') === 'file' && old('platform') == 'ppt' ? 'selected' : '' }}>📽️ PowerPoint Presentation (.ppt, .pptx)</option>
                                </select>
                                @if(old('type') === 'file')
                                    @error('platform') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600">Choose File <span class="text-red-500">*</span></label>
                                    <span class="text-[11px] font-semibold text-gray-400">Max 25MB</span>
                                </div>
                                
                                <div class="border-2 border-dashed border-gray-300 hover:border-indigo-400 rounded-2xl p-4 text-center bg-gray-50/50 hover:bg-indigo-50/30 transition-all cursor-pointer relative group">
                                    <div class="flex flex-col items-center justify-center">
                                        <div id="file-icon-preview" class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-2xs border border-indigo-100">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Click to select document</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Supports PDF, DOCX, PPTX, XLSX, ZIP</p>
                                    </div>
                                    <input type="file" name="file" id="file-upload-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.pps,.ppsx,.xls,.xlsx,.csv,.txt,.zip,.rar,.png,.jpg,.jpeg,.webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                </div>
                                @if(old('type') === 'file')
                                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @endif
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                                <button type="button" onclick="closeManageMaterialsModal()" class="px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none">Cancel</button>
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs sm:text-sm font-bold py-2.5 px-5 rounded-xl shadow-xs hover:shadow transition-all cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span>Upload Document</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        // Manage Materials Modal Open / Close Handlers
        window.openManageMaterialsModal = function() {
            const modal = document.getElementById('manageMaterialsModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        };

        window.closeManageMaterialsModal = function() {
            const modal = document.getElementById('manageMaterialsModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        // Close on backdrop click
        document.getElementById('manageMaterialsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                window.closeManageMaterialsModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeManageMaterialsModal();
            }
        });

        @if($errors->has('title') || $errors->has('platform') || $errors->has('url') || $errors->has('file') || old('type'))
            window.addEventListener('DOMContentLoaded', function() {
                window.openManageMaterialsModal();
            });
        @endif

        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Cooperative Tab Switcher (Materials, Assignments, Overview)
        window.switchCourseTab = function(tabKey) {
            const panels = {
                materials: document.getElementById('course-panel-materials'),
                assignments: document.getElementById('course-panel-assignments'),
                overview: document.getElementById('course-panel-overview')
            };

            const metricCards = {
                materials: document.getElementById('metric-card-materials'),
                assignments: document.getElementById('metric-card-assignments'),
                overview: document.getElementById('metric-card-overview')
            };

            Object.keys(panels).forEach(key => {
                if (key === tabKey) {
                    if (panels[key]) panels[key].classList.remove('hidden');
                    if (metricCards[key]) {
                        metricCards[key].classList.remove('border-gray-200', 'bg-white', 'shadow-2xs');
                        metricCards[key].classList.add('shadow-md', 'ring-2');
                        if (key === 'materials') metricCards[key].classList.add('ring-indigo-400', 'border-indigo-300', 'bg-indigo-50/70');
                        if (key === 'assignments') metricCards[key].classList.add('ring-purple-400', 'border-purple-300', 'bg-purple-50/70');
                        if (key === 'overview') metricCards[key].classList.add('ring-emerald-400', 'border-emerald-300', 'bg-emerald-50/70');
                    }
                    window.history.replaceState(null, null, '#' + key);
                } else {
                    if (panels[key]) panels[key].classList.add('hidden');
                    if (metricCards[key]) {
                        metricCards[key].classList.remove(
                            'shadow-md', 'ring-2', 
                            'ring-indigo-400', 'border-indigo-300', 'bg-indigo-50/70', 'bg-indigo-100/60',
                            'ring-purple-400', 'border-purple-300', 'bg-purple-50/70', 'bg-purple-100/60',
                            'ring-emerald-400', 'border-emerald-300', 'bg-emerald-50/70', 'bg-emerald-100/60'
                        );
                        metricCards[key].classList.add('shadow-2xs', 'border-gray-200', 'bg-white');
                    }
                }
            });
        };

        // Live Real-Time Course Materials Filter
        window.filterMaterialsLive = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            const clearBtn = document.getElementById('materialsSearchClearBtn');
            const resetBtn = document.getElementById('materialsResetFilterBtn');
            const noMatchBox = document.getElementById('materialsNoMatchState');
            const countBadge = document.getElementById('materialsCountBadge');

            const searchQuery = (searchInput?.value || '').toLowerCase().trim();
            const selectedType = (typeSelect?.value || '').toLowerCase().trim();

            if (clearBtn) {
                if (searchQuery.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            if (resetBtn) {
                if (searchQuery.length > 0 || selectedType !== '') {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
            }

            const cards = document.querySelectorAll('.material-card');
            let visibleCount = 0;
            const totalCount = cards.length;

            cards.forEach(card => {
                const title = card.getAttribute('data-material-title') || '';
                const type = card.getAttribute('data-material-type') || '';
                const platform = card.getAttribute('data-material-platform') || '';
                const staff = card.getAttribute('data-material-staff') || '';

                const matchesSearch = searchQuery === '' || 
                                      title.includes(searchQuery) || 
                                      platform.includes(searchQuery) || 
                                      staff.includes(searchQuery);

                const matchesType = selectedType === '' || type === selectedType;

                if (matchesSearch && matchesType) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noMatchBox) {
                if (visibleCount === 0 && totalCount > 0) {
                    noMatchBox.classList.remove('hidden');
                } else {
                    noMatchBox.classList.add('hidden');
                }
            }

            if (countBadge) {
                if (searchQuery !== '' || selectedType !== '') {
                    countBadge.textContent = `${visibleCount} of ${totalCount} Shown`;
                } else {
                    countBadge.textContent = `${totalCount} Total`;
                }
            }
        };

        window.clearMaterialsSearch = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            window.filterMaterialsLive();
        };

        window.resetAllMaterialFilters = function() {
            const searchInput = document.getElementById('materialsSearchInput');
            const typeSelect = document.getElementById('materialsTypeSelect');
            if (searchInput) searchInput.value = '';
            if (typeSelect) typeSelect.value = '';
            window.filterMaterialsLive();
        };

        // Enrolled Students Filter
        window.filterEnrolledStudents = function(query) {
            const cleanQuery = (query || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.student-row');
            const noMatchRow = document.getElementById('no-students-matched-row');
            const badge = document.getElementById('enrolledCountBadge');
            let visibleCount = 0;

            rows.forEach((row) => {
                const searchData = (row.getAttribute('data-student-search') || '').toLowerCase();
                const isMatch = cleanQuery === '' || searchData.includes(cleanQuery);
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) {
                    visibleCount++;
                    const indexEl = row.querySelector('.student-index');
                    if (indexEl) indexEl.textContent = visibleCount;
                }
            });

            if (noMatchRow) {
                noMatchRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }

            if (badge) {
                if (cleanQuery !== '') {
                    badge.textContent = `${visibleCount} of ${rows.length} Shown`;
                } else {
                    badge.textContent = `${rows.length} ${rows.length === 1 ? 'Student' : 'Students'}`;
                }
            }
        };

        // Tab switching logic for material upload forms (Add Link vs Upload Document)
        const tabLinkBtn = document.getElementById('tab-link-btn');
        const tabFileBtn = document.getElementById('tab-file-btn');
        const tabLinkContent = document.getElementById('tab-link-content');
        const tabFileContent = document.getElementById('tab-file-content');

        if(tabLinkBtn && tabFileBtn) {
            tabLinkBtn.addEventListener('click', () => {
                tabLinkBtn.className = 'flex items-center justify-center gap-2 py-2 px-3 text-xs font-bold rounded-lg transition-all shadow-xs bg-white text-indigo-700 cursor-pointer';
                tabFileBtn.className = 'flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900 cursor-pointer';

                tabFileContent.classList.add('hidden');
                tabFileContent.classList.remove('block');
                
                tabLinkContent.classList.add('block');
                tabLinkContent.classList.remove('hidden');
            });

            tabFileBtn.addEventListener('click', () => {
                tabFileBtn.className = 'flex items-center justify-center gap-2 py-2 px-3 text-xs font-bold rounded-lg transition-all shadow-xs bg-white text-indigo-700 cursor-pointer';
                tabLinkBtn.className = 'flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold rounded-lg transition-all text-gray-600 hover:text-gray-900 cursor-pointer';

                tabLinkContent.classList.add('hidden');
                tabLinkContent.classList.remove('block');
                
                tabFileContent.classList.add('block');
                tabFileContent.classList.remove('hidden');
            });
            
            @if(old('type') === 'file')
                tabFileBtn.click();
            @endif
        }

        // File type detection, base64 encoding and icon preview
        const fileInput = document.getElementById('file-upload-input');
        const fileTypeSelect = document.getElementById('file-type-select');
        const fileIconPreview = document.getElementById('file-icon-preview');
        const fileBase64Input = document.getElementById('file_base64');
        const fileNameInput = document.getElementById('file_name');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    if (fileIconPreview) {
                        fileIconPreview.innerHTML = '';
                        fileIconPreview.classList.add('hidden');
                        fileIconPreview.classList.remove('flex');
                    }
                    if (fileBase64Input) fileBase64Input.value = '';
                    if (fileNameInput) fileNameInput.value = '';
                    return;
                }

                if (fileNameInput) fileNameInput.value = file.name;

                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (fileBase64Input) {
                        fileBase64Input.value = evt.target.result;
                    }
                };
                reader.readAsDataURL(file);

                const filename = file.name.toLowerCase();
                let type = '';
                let iconSvg = '';

                if (filename.endsWith('.pdf')) {
                    type = 'pdf';
                    iconSvg = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                } else if (filename.endsWith('.doc') || filename.endsWith('.docx')) {
                    type = 'word';
                    iconSvg = '<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                } else if (filename.endsWith('.xls') || filename.endsWith('.xlsx') || filename.endsWith('.csv')) {
                    type = 'excel';
                    iconSvg = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
                } else if (filename.endsWith('.ppt') || filename.endsWith('.pptx') || filename.endsWith('.pps') || filename.endsWith('.ppsx')) {
                    type = 'ppt';
                    iconSvg = '<svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>';
                }

                if (type && fileTypeSelect) {
                    fileTypeSelect.value = type;
                }
                
                if (fileIconPreview) {
                    if (iconSvg) {
                        fileIconPreview.innerHTML = iconSvg;
                    } else {
                        fileIconPreview.innerHTML = '<svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                    }
                    fileIconPreview.classList.remove('hidden');
                    fileIconPreview.classList.add('flex');
                }
            });
        }

        // On Load Hash / Query Routing (#materials, #assignments, #overview or ?tab=...)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash.replace('#', '');
            const targetTab = tabParam || hash;

            if (targetTab === 'assignments') {
                window.switchCourseTab('assignments');
            } else if (targetTab === 'overview' || targetTab === 'students') {
                window.switchCourseTab('overview');
            } else {
                window.switchCourseTab('materials');
            }
        });

        window.addEventListener('hashchange', function() {
            const hash = window.location.hash.replace('#', '');
            if (hash === 'assignments' || hash === 'overview' || hash === 'materials') {
                window.switchCourseTab(hash);
            }
        });
    </script>
</body>
</html>
