<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments Management - LMS Faculty</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-500">Staff Portal</span>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700">Course Assignments</span>
            </div>
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                        @if(Auth::user()->photo)
                            <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
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
            <div class="w-full space-y-6">

                <!-- Flash Alerts -->
                @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
                @endif

                @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <h4 class="text-sm font-bold text-amber-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        Import Warnings / Skipped Rows:
                    </h4>
                    <ul class="text-xs text-amber-700 list-disc list-inside space-y-1">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Page Header & Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Course Assignments</h1>
                        <p class="mt-1 text-sm text-gray-600">Create, bulk import via Excel, evaluate and grade student assignments.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('staff.assignments.template') }}" class="inline-flex items-center px-3.5 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Excel Template
                        </a>
                        <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Create Assignment
                        </a>
                    </div>
                </div>

                <!-- Stats Cards (Clickable Quick Filters) -->
                @php
                    $currentStatus = request('status');
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Total Assignments -->
                    <a href="{{ route('staff.assignments.index', array_filter(array_merge(request()->except(['page', 'status']), ['status' => null]))) }}" 
                       class="group rounded-2xl shadow-sm border p-5 flex items-center justify-between transition-all duration-200 {{ empty($currentStatus) ? 'bg-indigo-50/50 border-indigo-300 ring-2 ring-indigo-500/20 shadow-md' : 'bg-white border-gray-100 hover:border-indigo-200 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-bold {{ empty($currentStatus) ? 'text-indigo-700' : 'text-gray-500 group-hover:text-indigo-600' }} uppercase tracking-wider transition-colors">Total Assignments</p>
                                @if(empty($currentStatus))
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                @endif
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalAssignments }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5 font-medium">{{ $assignedCoursesCount }} {{ Str::plural('Course', $assignedCoursesCount) }} allocated</p>
                        </div>
                        <div class="w-12 h-12 {{ empty($currentStatus) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white' }} rounded-2xl flex items-center justify-center shrink-0 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </a>

                    <!-- Active / Open -->
                    <a href="{{ route('staff.assignments.index', array_filter(array_merge(request()->except(['page']), ['status' => 'active']))) }}" 
                       class="group rounded-2xl shadow-sm border p-5 flex items-center justify-between transition-all duration-200 {{ $currentStatus === 'active' ? 'bg-emerald-50/50 border-emerald-300 ring-2 ring-emerald-500/20 shadow-md' : 'bg-white border-gray-100 hover:border-emerald-200 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Active & Open</p>
                                @if($currentStatus === 'active')
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                @endif
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $activeAssignmentsCount }}</p>
                            <p class="text-[11px] text-emerald-600 mt-0.5 font-medium flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Accepting responses
                            </p>
                        </div>
                        <div class="w-12 h-12 {{ $currentStatus === 'active' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white' }} rounded-2xl flex items-center justify-center shrink-0 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </a>

                    <!-- Past Due Deadline -->
                    <a href="{{ route('staff.assignments.index', array_filter(array_merge(request()->except(['page']), ['status' => 'past_due']))) }}" 
                       class="group rounded-2xl shadow-sm border p-5 flex items-center justify-between transition-all duration-200 {{ $currentStatus === 'past_due' ? 'bg-amber-50/50 border-amber-300 ring-2 ring-amber-500/20 shadow-md' : 'bg-white border-gray-100 hover:border-amber-200 hover:shadow-md hover:-translate-y-0.5' }}">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Past Due</p>
                                @if($currentStatus === 'past_due')
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                @endif
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $pastDueAssignmentsCount }}</p>
                            <p class="text-[11px] text-amber-600 mt-0.5 font-medium">Deadline passed</p>
                        </div>
                        <div class="w-12 h-12 {{ $currentStatus === 'past_due' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white' }} rounded-2xl flex items-center justify-center shrink-0 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </a>
                </div>

                <!-- Filter & Search Bar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <form method="GET" action="{{ route('staff.assignments.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Filter by Course</label>
                            <select name="course_id" onchange="this.form.submit()" class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 p-2">
                                <option value="">All Assigned Courses</option>
                                @foreach($assignedCourses as $c)
                                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->code }} - {{ $c->name }} @if($c->regulation)({{ $c->regulation->code ?: $c->regulation->name }}{{ !empty($c->regulation->curriculum) ? ' • ' . $c->regulation->curriculum : '' }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            <select name="status" onchange="this.form.submit()" class="w-full text-sm border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 p-2">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Open)</option>
                                <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due Deadline</option>
                            </select>
                        </div>

                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Search Assignments</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, topic, course..." class="w-full text-sm border-gray-200 rounded-lg pl-9 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        <div class="md:col-span-1 flex items-end">
                            <a href="{{ route('staff.assignments.index') }}" class="w-full text-center py-2 px-3 text-xs font-semibold text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Assignments Grid -->
                @if($assignments->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">No Assignments Found</h3>
                        <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Create a new assignment manually or upload an Excel sheet to give assignments in bulk.</p>
                        <div class="mt-6 flex justify-center gap-3">
                            <a href="{{ route('staff.assignments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Create Assignment Now
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($assignments as $assignment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col justify-between overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $assignment->course->code }}
                                    </span>
                                    @if($assignment->status === 'draft')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                                    @elseif($assignment->status === 'closed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Closed</span>
                                    @elseif($assignment->isPastDue())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Past Due</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                                    @endif
                                </div>

                                <h3 class="text-base font-bold text-gray-900 line-clamp-2 hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('staff.assignments.show', $assignment->id) }}">{{ $assignment->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 mb-3">{{ $assignment->course->name }}</p>

                                @if($assignment->description)
                                    <p class="text-xs text-gray-600 line-clamp-3 mb-4 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                        {{ Str::limit($assignment->description, 120) }}
                                    </p>
                                @endif

                                <div class="space-y-2 text-xs text-gray-600 border-t border-gray-100 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Deadline:
                                        </span>
                                        <span class="font-semibold {{ $assignment->isPastDue() ? 'text-red-600' : 'text-gray-800' }}">
                                            {{ $assignment->due_date->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Max Marks:</span>
                                        <span class="font-semibold text-indigo-700">{{ $assignment->max_marks }} pts</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Submissions:</span>
                                        <span class="font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded text-xs">
                                            {{ $assignment->submissions->count() }} of {{ $assignment->course->enrollments->count() }} enrolled
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                <a href="{{ route('staff.assignments.show', $assignment->id) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    Questions & Results &rarr;
                                </a>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('staff.assignments.edit', $assignment->id) }}" class="p-1 text-gray-400 hover:text-indigo-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('staff.assignments.destroy', $assignment->id) }}" onsubmit="return confirm('Are you sure you want to delete this assignment? All student submissions will also be deleted.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-gray-400 hover:text-red-600" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $assignments->links() }}
                    </div>
                @endif

            </div>
        </main>
    </div>

    <!-- Password Modal -->
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
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>
</body>
</html>
