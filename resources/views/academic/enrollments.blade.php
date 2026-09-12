<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Insights - LMS</title>
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
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::check() && Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Admin' }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50 relative">
            <div class="p-4 sm:p-6">
                
                <!-- Header -->
                <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <div class="pt-8 sm:pt-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Enrollment Insights</h1>
                        <p class="text-gray-500 mt-1 sm:mt-2 text-xs sm:text-sm">SaaS analytics for course allocations and student enrollments</p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-3 gap-3 sm:gap-6 mb-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-6 flex flex-col items-center sm:items-start text-center sm:text-left hover:shadow-md transition-shadow">
                        <div class="p-2 sm:p-3 bg-blue-50 text-blue-600 rounded-lg sm:rounded-xl mb-2 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[10px] sm:text-sm font-semibold text-gray-500 uppercase tracking-wider leading-tight h-8 sm:h-auto overflow-hidden">Total<br class="block sm:hidden"> Enrollments</h3>
                        <p class="text-lg sm:text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalEnrollments) }}</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-6 flex flex-col items-center sm:items-start text-center sm:text-left hover:shadow-md transition-shadow">
                        <div class="p-2 sm:p-3 bg-indigo-50 text-indigo-600 rounded-lg sm:rounded-xl mb-2 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-[10px] sm:text-sm font-semibold text-gray-500 uppercase tracking-wider leading-tight h-8 sm:h-auto overflow-hidden">Courses<br class="block sm:hidden"> Offered</h3>
                        <p class="text-lg sm:text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalCoursesOffered) }}</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-6 flex flex-col items-center sm:items-start text-center sm:text-left hover:shadow-md transition-shadow">
                        <div class="p-2 sm:p-3 {{ $overallAllocationRate >= 90 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} rounded-lg sm:rounded-xl mb-2 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[10px] sm:text-sm font-semibold text-gray-500 uppercase tracking-wider leading-tight h-8 sm:h-auto overflow-hidden">Alloc.<br class="block sm:hidden"> Rate</h3>
                        <p class="text-lg sm:text-3xl font-bold text-gray-900 mt-1">{{ $overallAllocationRate }}%</p>
                    </div>
                </div>

                <!-- Analytics Grids -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                    
                    <!-- Department Allocations -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                        <div class="mb-5 sm:mb-6">
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Department Allocations</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Click a department to view its courses</p>
                        </div>
                        
                        <div class="space-y-4 sm:space-y-5 max-h-[500px] overflow-y-auto pr-2" style="scrollbar-width: thin;">
                            @forelse($departmentInsights as $insight)
                            <div
                                class="dept-row cursor-pointer rounded-lg p-2 -mx-2 hover:bg-indigo-50 transition-colors"
                                data-dept-code="{{ $insight['code'] }}"
                                data-dept-name="{{ $insight['department'] }}"
                                onclick="selectDepartment('{{ $insight['code'] }}', '{{ addslashes($insight['department']) }}')"
                            >
                                <div class="flex flex-row justify-between items-center mb-1.5">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700 truncate pr-2 flex-1 min-w-0">{{ $insight['department'] ?? 'General' }}</span>
                                    <span class="text-xs sm:text-sm font-bold text-gray-900 shrink-0 ml-2">{{ $insight['total_courses'] }} <span class="text-gray-500 font-normal text-[10px] sm:text-xs">Courses</span> <span class="text-gray-300 mx-1">|</span> {{ $insight['total_enrollments'] }} <span class="text-gray-500 font-normal text-[10px] sm:text-xs">Enrolled</span></span>
                                </div>
                            </div>
                            @empty
                            <div class="py-6 sm:py-8 text-center text-xs sm:text-sm text-gray-500">No department data available.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Courses & Regulations (filterable) -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 overflow-hidden">
                        <div class="mb-5 sm:mb-6 flex flex-row justify-between items-start gap-2">
                            <div>
                                <h2 id="subject-panel-title" class="text-base sm:text-lg font-bold text-gray-900">Top Enrolled Subjects</h2>
                                <p id="subject-panel-sub" class="text-xs sm:text-sm text-gray-500">Highest student engagement</p>
                            </div>
                            <button id="clear-dept-btn" onclick="clearDepartmentFilter()" class="hidden shrink-0 text-xs px-2.5 py-1 rounded-full bg-gray-100 hover:bg-indigo-100 text-gray-600 hover:text-indigo-700 font-medium transition-colors">
                                ✕ All
                            </button>
                        </div>
                        
                        <div class="overflow-x-auto -mx-5 sm:-mx-6 px-5 sm:px-6">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="border-b border-gray-200">
                                    <tr>
                                        <th class="pb-2 pt-1 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Subject</th>
                                        <th class="pb-2 pt-1 px-2 sm:px-3 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Reg.</th>
                                        <th class="pb-2 pt-1 px-2 sm:px-3 text-right text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody id="subject-table-body" class="divide-y divide-gray-50">
                                    @forelse($topCourses as $course)
                                    <tr class="hover:bg-gray-50 transition-colors subject-row cursor-pointer" onclick="openStudentsModal(this)" data-students="{{ json_encode($course->enrollments->map(function($e) { return ['roll_no' => $e->profile->username ?? 'N/A', 'name' => trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? '')), 'email' => $e->email ?? 'N/A']; })->values()->toArray()) }}" data-code="{{ $course->code }}">
                                        <td class="py-2.5 sm:py-3 text-sm text-gray-900" style="vertical-align:top">
                                            <div class="font-semibold text-xs sm:text-sm text-gray-800">{{ $course->code }}</div>
                                            <div class="text-[10px] sm:text-xs text-gray-400 truncate max-w-[130px] sm:max-w-none">{{ $course->name }}</div>
                                        </td>
                                        <td class="px-2 sm:px-3 py-2.5 sm:py-3 whitespace-nowrap" style="vertical-align:top">
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[9px] sm:text-xs font-semibold">
                                                {{ $course->regulation ? $course->regulation->code : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-2 sm:px-3 py-2.5 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-bold text-gray-900 text-right" style="vertical-align:top">{{ number_format($course->enrollments_count) }}</td>
                                    </tr>
                                    @empty
                                    <tr id="empty-row-default">
                                        <td colspan="3" class="px-3 py-8 text-center text-xs sm:text-sm text-gray-500">No enrollments data available.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="dept-empty-msg" class="hidden py-8 text-center text-xs sm:text-sm text-gray-500">No courses found for this department.</div>
                        </div>
                    </div>
                </div>

            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

    @include('partials.reset_password_modal')

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
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Students Modal -->
    <div id="studentsModal" class="fixed inset-0 z-[110] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all max-h-[80vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50 shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-indigo-900" id="studentsModalTitle">Enrolled Students</h3>
                    <p class="text-xs text-indigo-600 mt-1" id="studentsModalSub">Subject: </p>
                </div>
                <button type="button" onclick="closeStudentsModal()" class="text-indigo-400 hover:text-indigo-600 focus:outline-none shrink-0 ml-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-0 overflow-y-auto flex-1 bg-white">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Roll No</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body" class="bg-white divide-y divide-gray-50">
                        <!-- Student rows injected via JS -->
                    </tbody>
                </table>
                <div id="no-students-msg" class="hidden py-8 text-center text-sm text-gray-500">
                    No students enrolled in this course.
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end">
                <button type="button" onclick="closeStudentsModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Close</button>
            </div>
        </div>
    </div>

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }

        // ── Department filter (existing) ────────────────────────────────────
        const allCoursesByDept = @json($allCoursesByDept);

        function selectDepartment(deptCode, deptName) {
            document.querySelectorAll('.dept-row').forEach(r => r.classList.remove('bg-indigo-50', 'ring-2', 'ring-indigo-200'));
            const row = document.querySelector(`.dept-row[data-dept-code="${deptCode}"]`);
            if (row) row.classList.add('bg-indigo-50', 'ring-2', 'ring-indigo-200');

            document.getElementById('subject-panel-title').textContent = deptName;
            document.getElementById('subject-panel-sub').textContent   = 'Subjects offered by this department';
            document.getElementById('clear-dept-btn').classList.remove('hidden');

            const courses  = allCoursesByDept[deptCode] || [];
            const tbody    = document.getElementById('subject-table-body');
            const emptyMsg = document.getElementById('dept-empty-msg');

            if (courses.length === 0) {
                tbody.innerHTML = '';
                emptyMsg.classList.remove('hidden');
                return;
            }
            emptyMsg.classList.add('hidden');
            tbody.innerHTML = courses.map(c => `
                <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="openStudentsModal(this)" data-students='${escHtml(JSON.stringify(c.students || []))}' data-code="${escHtml(c.code)}">
                    <td class="py-2.5 text-sm text-gray-900" style="vertical-align:top">
                        <div class="font-semibold text-xs sm:text-sm text-gray-800">${escHtml(c.code)}</div>
                        <div class="text-[10px] sm:text-xs text-gray-400 truncate max-w-[130px] sm:max-w-none">${escHtml(c.name)}</div>
                    </td>
                    <td class="px-2 sm:px-3 py-2.5 whitespace-nowrap" style="vertical-align:top">
                        <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[9px] sm:text-xs font-semibold">${escHtml(c.regulation_code)}</span>
                    </td>
                    <td class="px-2 sm:px-3 py-2.5 whitespace-nowrap text-xs sm:text-sm font-bold text-gray-900 text-right" style="vertical-align:top">${c.enrollments_count}</td>
                </tr>
            `).join('');
        }

        function clearDepartmentFilter() {
            document.querySelectorAll('.dept-row').forEach(r => r.classList.remove('bg-indigo-50', 'ring-2', 'ring-indigo-200'));
            document.getElementById('subject-panel-title').textContent = 'Top Enrolled Subjects';
            document.getElementById('subject-panel-sub').textContent   = 'Highest student engagement';
            document.getElementById('clear-dept-btn').classList.add('hidden');
            document.getElementById('dept-empty-msg').classList.add('hidden');
            location.reload();
        }

        function openStudentsModal(row) {
            const studentsData = row.getAttribute('data-students');
            const code = row.getAttribute('data-code');
            const students = JSON.parse(studentsData || '[]');
            
            document.getElementById('studentsModalSub').textContent = 'Subject: ' + code;
            
            const tbody = document.getElementById('students-table-body');
            const noMsg = document.getElementById('no-students-msg');
            
            if (students.length === 0) {
                tbody.innerHTML = '';
                noMsg.classList.remove('hidden');
            } else {
                noMsg.classList.add('hidden');
                tbody.innerHTML = students.map(s => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-900">${escHtml(s.roll_no || 'N/A')}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">${escHtml(s.name)}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">${escHtml(s.email)}</td>
                    </tr>
                `).join('');
            }
            
            document.getElementById('studentsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeStudentsModal() {
            document.getElementById('studentsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
    </script>
</body>
</html>

