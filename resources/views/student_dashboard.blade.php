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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
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
        <main class="flex-1 overflow-y-auto p-3.5 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-5 sm:space-y-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Student Dashboard Overview</h2>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-6">
                    <!-- My Department -->
                    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2.5 sm:p-3 rounded-xl bg-blue-50 text-blue-600 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4M9 11h4m-4-4h4"></path></svg>
                            </div>
                            <div class="ml-3.5 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">My Department</p>
                                <p class="text-sm sm:text-base font-bold text-gray-900 leading-snug break-words" title="{{ $department->name ?? 'N/A' }}">{{ $department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- My School -->
                    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2.5 sm:p-3 rounded-xl bg-emerald-50 text-emerald-600 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div class="ml-3.5 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">My School</p>
                                <p class="text-sm sm:text-base font-bold text-gray-900 leading-snug break-words" title="{{ $school->name ?? 'N/A' }}">{{ $school->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Department Courses -->
                    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2.5 sm:p-3 rounded-xl bg-indigo-50 text-indigo-600 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div class="ml-3.5 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Department Courses</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">{{ $departmentCoursesCount }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Enrolled Courses -->
                    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2.5 sm:p-3 rounded-xl bg-amber-50 text-amber-600 shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-3.5 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Enrolled Courses</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">{{ $enrolledCoursesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Department Courses Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 bg-gray-50/75 flex justify-between items-center">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Department Courses</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ $departmentCoursesCount }} Total</span>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full divide-y divide-gray-100 text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-4 py-3 sm:px-6 sm:py-3.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Name</th>
                                        <th class="px-4 py-3 sm:px-6 sm:py-3.5 text-xs font-bold text-gray-700 uppercase tracking-wider text-right sm:text-left">Code</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($departmentCourses as $course)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 sm:px-6 sm:py-4">
                                            <div class="text-sm font-semibold text-gray-900 break-words">{{ $course->name }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $course->year }} Year, {{ $course->semester }} Sem</div>
                                        </td>
                                        <td class="px-4 py-3 sm:px-6 sm:py-4 text-right sm:text-left whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded bg-gray-100 text-gray-700 text-xs font-mono font-semibold">{{ $course->code }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500">No courses found in your department.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Enrolled Courses Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 bg-gray-50/75 flex justify-between items-center">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Enrolled Courses</h3>
                            <a href="{{ route('student.enrollment.create') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">+ Enroll More</a>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full divide-y divide-gray-100 text-left">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-4 py-3 sm:px-6 sm:py-3.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Course Name</th>
                                        <th class="px-4 py-3 sm:px-6 sm:py-3.5 text-xs font-bold text-gray-700 uppercase tracking-wider text-right sm:text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($enrolledCourses as $course)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 sm:px-6 sm:py-4">
                                            <div class="text-sm font-semibold text-gray-900 break-words">{{ $course->name }}</div>
                                            @if($course->code)
                                            <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $course->code }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:px-6 sm:py-4 text-right sm:text-left whitespace-nowrap">
                                            <a href="{{ route('student.courses.materials', $course->id) }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                View Materials &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500">
                                            <p class="mb-3 text-sm">You are not enrolled in any courses yet.</p>
                                            <a href="{{ route('student.enrollment.create') }}" class="inline-flex items-center px-3.5 py-2 border border-transparent text-xs sm:text-sm font-semibold rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">Enroll in Courses</a>
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
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
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


