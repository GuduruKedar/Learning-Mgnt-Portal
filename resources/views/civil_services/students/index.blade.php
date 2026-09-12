<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civil Services - Enrolled Students - LMS</title>
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

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('warning'))
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>{{ session('warning') }}</span>
                </div>
                @endif

                <!-- Header & Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Civil Services - Enrolled Students</h1>
                        <p class="text-sm text-gray-500 mt-1">Cross-department roster of students currently enrolled in Civil Services training.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" onclick="document.getElementById('quickEnrollModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-blue-50 border border-blue-200 text-blue-700 font-semibold text-sm rounded-xl hover:bg-blue-100 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Quick Enroll by Reg No
                        </button>
                        <a href="{{ route('civil.students.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-xl shadow-md transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add New Student
                        </a>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <form method="GET" action="{{ route('civil.students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Reg No, Name, Email..." class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Parent School</label>
                            <select name="school" id="filter_school" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->code }}" {{ request('school') == $school->code ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Parent Department</label>
                            <select name="department" id="filter_department" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-700 text-white font-semibold text-sm rounded-lg hover:bg-blue-800 transition-colors">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'school', 'department', 'status']))
                            <a href="{{ route('civil.students.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 font-semibold text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Reg No</th>
                                    <th class="px-6 py-3.5 text-left">Student</th>
                                    <th class="px-6 py-3.5 text-left">Parent School & Department</th>
                                    <th class="px-6 py-3.5 text-left">Level / Program</th>
                                    <th class="px-6 py-3.5 text-left">Batch Year</th>
                                    <th class="px-6 py-3.5 text-left">Status</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $student->username }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($student->photo)
                                                <img class="w-9 h-9 rounded-full object-cover mr-3 border border-gray-200" src="{{ asset('storage/' . $student->photo) }}" alt="">
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center mr-3 text-xs border border-indigo-100">
                                                    {{ substr($student->first_name ?? 'S', 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                <div class="text-xs text-gray-400">{{ $student->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-800">{{ $student->profile->department->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $student->profile->school->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ $student->profile->level ?? 'UG' }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-1">{{ $student->profile->program->name ?? '' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium text-xs">
                                        {{ $student->civilServiceEnrollment->batch_year ?? '2026' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($student->civilServiceEnrollment->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form method="POST" action="{{ route('civil.students.unenroll', $student->id) }}" onsubmit="return confirm('Are you sure you want to remove {{ $student->username }} from Civil Services enrollment? (Their core degree and academic record will remain intact).');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-800 px-3 py-1 rounded-lg bg-red-50 hover:bg-red-100 transition-colors">
                                                Unenroll
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        No students found matching the selected criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $students->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- Quick Enroll Modal -->
    <div id="quickEnrollModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Enroll Existing Student</h3>
                <button type="button" onclick="document.getElementById('quickEnrollModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('civil.students.enroll_existing') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Registration Number <span class="text-red-500">*</span></label>
                    <input type="text" name="reg_number" required placeholder="e.g., 221FA04001 or student1" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Enter any student's university reg number from any department.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Batch Year</label>
                    <input type="text" name="batch_year" value="{{ date('Y') }}" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('quickEnrollModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg shadow transition-colors">
                        Confirm Enrollment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.getElementById('filter_school');
            const departmentSelect = document.getElementById('filter_department');

            if (schoolSelect && departmentSelect) {
                // Store all original department options
                const allDepartments = Array.from(departmentSelect.options).filter(opt => opt.value !== '');
                const defaultOption = departmentSelect.options[0];

                function filterDepartments() {
                    const selectedSchoolCode = schoolSelect.value;
                    const currentDeptValue = departmentSelect.value;
                    let deptStillValid = false;

                    // Clear options and re-add default option
                    departmentSelect.innerHTML = '';
                    departmentSelect.appendChild(defaultOption);

                    allDepartments.forEach(option => {
                        if (!selectedSchoolCode || option.getAttribute('data-school-id') === selectedSchoolCode) {
                            departmentSelect.appendChild(option);
                            if (option.value === currentDeptValue) {
                                deptStillValid = true;
                            }
                        }
                    });

                    // If previously selected department is not in the filtered list, reset to default
                    if (!deptStillValid) {
                        departmentSelect.value = '';
                    } else {
                        departmentSelect.value = currentDeptValue;
                    }
                }

                schoolSelect.addEventListener('change', filterDepartments);

                // Run on initial load
                filterDepartments();

                // Preserve existing selection if matching
                const requestedDept = "{{ request('department') }}";
                if (requestedDept) {
                    departmentSelect.value = requestedDept;
                }
            }
        });
    </script>
</body>
</html>
