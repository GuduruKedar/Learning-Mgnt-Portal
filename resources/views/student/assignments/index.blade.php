<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assignments - Student Portal</title>
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
                <span class="text-sm font-medium text-gray-500">Student Portal</span>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700">My Assignments</span>
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
                        <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Student' }}</span>
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

                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My Course Assignments</h1>
                        <p class="mt-1 text-sm text-gray-500">Take online MCQ assessments, submit your answers, and view instant evaluated scores.</p>
                    </div>
                </div>

                <!-- Clear, Prominent Stats Overview -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- 1. Total Assignments -->
                    <a href="{{ route('student.assignments.index', ['tab' => 'all']) }}" class="bg-white rounded-2xl p-5 border {{ $tab === 'all' ? 'border-indigo-600 ring-2 ring-indigo-200' : 'border-gray-100 hover:border-indigo-200' }} shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Assignments</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $totalCount }}</p>
                                <p class="text-xs text-gray-400 mt-1">Across all courses</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                    </a>

                    <!-- 2. Pending to Submit -->
                    <a href="{{ route('student.assignments.index', ['tab' => 'pending']) }}" class="bg-white rounded-2xl p-5 border {{ $tab === 'pending' ? 'border-amber-500 ring-2 ring-amber-200' : 'border-gray-100 hover:border-amber-200' }} shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending (To Submit)</p>
                                <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $pendingCount }}</p>
                                <p class="text-xs text-gray-400 mt-1">Awaiting your response</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </a>

                    <!-- 3. Completed / Submitted -->
                    <a href="{{ route('student.assignments.index', ['tab' => 'completed']) }}" class="bg-white rounded-2xl p-5 border {{ in_array($tab, ['completed', 'submitted', 'graded']) ? 'border-emerald-500 ring-2 ring-emerald-200' : 'border-gray-100 hover:border-emerald-200' }} shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Completed</p>
                                <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $completedCount }}</p>
                                <p class="text-xs text-gray-400 mt-1">Submitted & graded</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </a>

                    <!-- 4. Performance Rate -->
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Average Score</p>
                                <p class="text-3xl font-extrabold text-indigo-600 mt-2">{{ $avgScorePercent }}%</p>
                                <p class="text-xs text-gray-400 mt-1">Across completed tests</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clean Tabs & Filters -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-4">
                    <!-- Clean, Streamlined Tab buttons -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-3">
                        <a href="{{ route('student.assignments.index', ['tab' => 'all', 'course_id' => request('course_id'), 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            All Assignments ({{ $totalCount }})
                        </a>
                        <a href="{{ route('student.assignments.index', ['tab' => 'pending', 'course_id' => request('course_id'), 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            To Submit ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('student.assignments.index', ['tab' => 'completed', 'course_id' => request('course_id'), 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ in_array($tab, ['completed', 'submitted', 'graded']) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Completed ({{ $completedCount }})
                        </a>
                        @if($overdueCount > 0)
                        <a href="{{ route('student.assignments.index', ['tab' => 'overdue', 'course_id' => request('course_id'), 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'overdue' ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Overdue ({{ $overdueCount }})
                        </a>
                        @endif
                    </div>

                    <!-- Search & Course Filter Form -->
                    <form method="GET" action="{{ route('student.assignments.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        
                        <div class="md:col-span-5">
                            <select name="course_id" onchange="this.form.submit()" class="w-full text-xs border-gray-200 rounded-xl p-2.5 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Enrolled Courses</option>
                                @foreach($enrolledCourses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-6">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by assignment title..." class="w-full text-xs border-gray-200 rounded-xl pl-9 pr-3 py-2.5 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        <div class="md:col-span-1">
                            <a href="{{ route('student.assignments.index', ['tab' => $tab]) }}" class="w-full block text-center py-2.5 px-3 text-xs font-bold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Assignment Cards Grid -->
                @if($filteredAssignments->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">No Assignments in this section</h3>
                        <p class="mt-1 text-xs text-gray-500">There are no assignments matching your selected filter.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($filteredAssignments as $assignment)
                            @php
                                $submission = $assignment->submissions->first();
                                $isSubmitted = !is_null($submission);
                                $isGraded = $submission && $submission->status === 'graded';
                            @endphp
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                {{ $assignment->course->code }}
                                            </span>
                                            <span class="text-xs font-semibold text-gray-400">{{ $assignment->questions->count() }} Questions</span>
                                        </div>

                                        @if($isSubmitted)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Score: {{ $submission->marks_awarded }} / {{ $assignment->max_marks }}
                                            </span>
                                        @elseif($assignment->isPastDue())
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                Pending
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-base font-bold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}">{{ $assignment->title }}</a>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 mb-3">{{ $assignment->course->name }}</p>

                                    @if($assignment->description)
                                        <p class="text-xs text-gray-600 line-clamp-2 mb-4 bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                            {{ Str::limit($assignment->description, 95) }}
                                        </p>
                                    @endif

                                    <div class="space-y-2 text-xs text-gray-600 border-t border-gray-100 pt-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Deadline:
                                            </span>
                                            <span class="font-bold {{ $assignment->isPastDue() ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $assignment->due_date->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-500">Total Marks:</span>
                                            <span class="font-bold text-indigo-700">{{ $assignment->max_marks }} pts</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-100">
                                    @if($isSubmitted)
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="w-full block text-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                                            View Score & Review Answers &rarr;
                                        </a>
                                    @elseif($assignment->isPastDue())
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="w-full block text-center px-4 py-2.5 bg-gray-300 text-gray-600 text-xs font-bold rounded-xl cursor-not-allowed">
                                            Submission Closed
                                        </a>
                                    @else
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="w-full block text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                                            Start MCQ Test &rarr;
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
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
