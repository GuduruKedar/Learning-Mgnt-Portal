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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center ml-auto">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                        @if(Auth::user()->photo)
                            <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shadow-sm">
                                {{ substr(Auth::user()->first_name ?? 'C', 0, 1) }}
                            </div>
                        @endif
                        <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Civil Admin' }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Edit Profile</a>
                        <a href="{{ route('password.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Change Password</a>
                        <form method="POST" action="{{ route('logout') }}" class="block border-t border-gray-100 mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6 w-full">

                <!-- Welcome Banner -->
                <div class="bg-gradient-to-br from-blue-900 via-indigo-900 to-blue-800 rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden border border-blue-700/40 shrink-0">
                    <!-- Abstract modern background elements -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-blue-500 opacity-20 blur-3xl pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-56 h-56 rounded-full bg-indigo-500 opacity-20 blur-2xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col gap-5">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider text-blue-200 border border-white/10 mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Civil Services Academy
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-tight">
                                Welcome, <span class="text-blue-200">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                            </h1>
                            <p class="text-blue-100/90 text-sm sm:text-base max-w-2xl font-normal leading-relaxed mt-2">
                                Manage university-wide UPSC & Civil Services training enrollments. Students from any academic department and year can participate while maintaining their core degree.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('civil.students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-blue-900 font-semibold text-sm rounded-xl shadow-md hover:bg-blue-50 hover:shadow-lg transition-all">
                                <svg class="w-4 h-4 mr-2 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Enroll / Register Student
                            </a>
                            <a href="{{ route('civil.students.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-semibold text-sm rounded-xl transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                View All Enrolled Roster
                            </a>
                        </div>
                    </div>
                </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Enrolled</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalEnrolled }}</p>
                        <p class="text-xs text-blue-600 mt-1 font-medium">All academic streams</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Students</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $activeEnrolled }}</p>
                        <p class="text-xs text-emerald-500 mt-1 font-medium">Currently in training</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Represented Depts</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $departmentBreakdown->count() }}</p>
                        <p class="text-xs text-indigo-500 mt-1 font-medium">Cross-department reach</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
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
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System - Civil Services Academy. All rights reserved.</p>
            </footer>

            </div>
        </main>
    </div>

</body>
</html>
