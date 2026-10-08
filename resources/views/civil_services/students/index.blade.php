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
        @include('partials.top_header')

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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3.5">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Civil Services - Enrolled Students</h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Cross-department roster of students currently enrolled in Civil Services training.</p>
                    </div>
                    <div class="grid grid-cols-2 sm:flex sm:items-center gap-2.5 w-full sm:w-auto">
                        <button type="button" onclick="document.getElementById('quickEnrollModal').classList.remove('hidden')" class="w-full sm:w-auto inline-flex items-center justify-center px-3.5 py-2.5 bg-blue-50 border border-blue-200 hover:border-blue-300 text-blue-700 hover:bg-blue-100 font-semibold text-xs sm:text-sm rounded-xl transition-all shadow-xs cursor-pointer text-center">
                            <svg class="w-4 h-4 mr-1.5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="truncate">Quick Enroll</span>
                        </button>
                        <a href="{{ route('civil.students.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3.5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm hover:shadow-md transition-all text-center">
                            <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="truncate">Add Student</span>
                        </a>
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    @php
                        $orderedCodes = [
                            'sc_ceng',
                            'sc_eeceng',
                            'sc_ci',
                            'sc_bps',
                            'sc_lm',
                            'sc_aft',
                            'sc_ash',
                            'dip',
                            'sc_edu',
                            'sc_cs',
                        ];
                        $orderedSchools = collect($orderedCodes)->map(function($code) use ($schools) {
                            return $schools->where('code', $code)->first();
                        })->filter()->values();
                        $remainingSchools = $schools->whereNotIn('code', $orderedCodes)->values();
                        $allDisplaySchools = $orderedSchools->concat($remainingSchools);

                        $currSchoolName = '-- All Schools --';
                        if(request('school')) {
                            foreach($allDisplaySchools as $idx => $s) {
                                if($s->code == request('school')) {
                                    $currSchoolName = sprintf('%02d. %s', $idx + 1, $s->name);
                                    break;
                                }
                            }
                        }

                        $currDeptName = '-- All Departments --';
                        if(request('department')) {
                            $dObj = $departments->firstWhere('code', request('department'));
                            if($dObj) {
                                $currDeptName = $dObj->name;
                            }
                        }

                        $statusMap = [
                            'active' => 'Active',
                            'completed' => 'Completed',
                            'dropped' => 'Dropped',
                        ];
                        $currStatusName = $statusMap[request('status')] ?? '-- All Status --';
                    @endphp
                    <form method="GET" action="{{ route('civil.students.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center w-full" id="filterForm">
                        <div class="md:col-span-3 w-full min-w-0">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by reg no, name, email..." class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-2xs">
                            </div>
                        </div>

                        <!-- School Custom Dropdown (Constrained & No Outer Overflow) -->
                        <div class="relative w-full min-w-0 md:col-span-3 custom-dropdown" id="schoolDropdownContainer">
                            <label for="filter_school" class="sr-only">Parent School</label>
                            <select name="school" id="filter_school" class="hidden">
                                <option value="">-- All Schools --</option>
                                @foreach($allDisplaySchools as $idx => $school)
                                    <option value="{{ $school->code }}" {{ request('school') == $school->code ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $idx + 1) }}. {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" id="schoolDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-left shadow-2xs cursor-pointer">
                                <span id="schoolDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currSchoolName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="schoolChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="schoolDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-[280px] sm:min-w-[380px] max-w-[calc(100vw-2.5rem)] md:max-w-md bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-72 overflow-y-auto py-1.5 space-y-0.5">
                                <div class="custom-school-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium" data-value="">
                                    <span>-- All Schools --</span>
                                </div>
                                @foreach($allDisplaySchools as $idx => $school)
                                    <div class="custom-school-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between {{ request('school') == $school->code ? 'bg-blue-50 text-blue-800 font-semibold' : '' }}" data-value="{{ $school->code }}">
                                        <span class="whitespace-normal leading-normal text-xs sm:text-sm">{{ sprintf('%02d', $idx + 1) }}. {{ $school->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Department Custom Dropdown (Constrained & Cascading) -->
                        <div class="relative w-full min-w-0 md:col-span-2 custom-dropdown" id="deptDropdownContainer">
                            <label for="filter_department" class="sr-only">Parent Department</label>
                            <select name="department" id="filter_department" class="hidden">
                                <option value="">-- All Departments --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <button type="button" id="deptDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-left shadow-2xs cursor-pointer">
                                <span id="deptDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currDeptName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="deptChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="deptDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-[260px] sm:min-w-[320px] max-w-[calc(100vw-2.5rem)] md:max-w-md bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-72 overflow-y-auto py-1.5 space-y-0.5">
                                <!-- Populated dynamically based on School -->
                            </div>
                        </div>

                        <!-- Status Custom Dropdown (Constrained & Bounded) -->
                        <div class="relative w-full min-w-0 md:col-span-2 custom-dropdown" id="statusDropdownContainer">
                            <label for="status" class="sr-only">Status</label>
                            <select name="status" id="status" class="hidden">
                                <option value="">-- All Status --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                            </select>

                            <button type="button" id="statusDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-left shadow-2xs cursor-pointer">
                                <span id="statusDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currStatusName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="statusChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="statusDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-full bg-white border border-gray-200 rounded-xl shadow-2xl z-50 py-1.5 space-y-0.5">
                                <div class="custom-status-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium {{ !request('status') ? 'bg-blue-50 text-blue-800 font-semibold' : '' }}" data-value="">
                                    <span>-- All Status --</span>
                                </div>
                                <div class="custom-status-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between {{ request('status') == 'active' ? 'bg-blue-50 text-blue-800 font-semibold' : '' }}" data-value="active">
                                    <span>Active</span>
                                </div>
                                <div class="custom-status-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between {{ request('status') == 'completed' ? 'bg-blue-50 text-blue-800 font-semibold' : '' }}" data-value="completed">
                                    <span>Completed</span>
                                </div>
                                <div class="custom-status-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between {{ request('status') == 'dropped' ? 'bg-blue-50 text-blue-800 font-semibold' : '' }}" data-value="dropped">
                                    <span>Dropped</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:col-span-2 w-full min-w-0">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-3.5 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors cursor-pointer">
                                Apply
                            </button>
                            <a href="{{ route('civil.students.index') }}" class="inline-flex items-center justify-center gap-1 px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-2xs" title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
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
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $student->username }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($student->photo)
                                                <img class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover mr-3 border border-gray-200" src="{{ asset('storage/' . $student->photo) }}" alt="">
                                            @else
                                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center mr-3 text-xs border border-indigo-100">
                                                    {{ substr($student->first_name ?? 'S', 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-gray-900 text-sm">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                <div class="text-xs text-gray-400">{{ $student->email ?? 'No email' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5">
                                        <div class="font-medium text-gray-800 text-sm">{{ $student->profile->department->name ?? 'N/A' }}</div>
                                        <div class="text-[10px] uppercase tracking-wider text-gray-400">{{ $student->profile->school->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ $student->profile->level ?? 'UG' }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-1">{{ $student->profile->program->name ?? '' }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap text-gray-600 font-medium text-xs">
                                        {{ $student->civilServiceEnrollment->batch_year ?? '2026' }}
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            {{ ucfirst($student->civilServiceEnrollment->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 sm:px-6 sm:py-2.5 whitespace-nowrap text-right">
                                        @php
                                            $stName = addslashes(trim(($student->profile->first_name ?? '') . ' ' . ($student->profile->last_name ?? '')));
                                            $unenrollUrl = route('civil.students.unenroll', $student->id);
                                        @endphp
                                        <button type="button" 
                                            onclick="openUniversalDeleteModal({
                                                title: 'Unenroll Student from Civil Services',
                                                subtitle: 'Confirm track unenrollment',
                                                itemName: '{{ $stName }}',
                                                itemCode: 'Reg: {{ $student->username }}',
                                                itemBadge: 'Civil Services Coaching',
                                                itemMeta: 'Batch: {{ $student->civilServiceEnrollment->batch_year ?? '2026' }} • Department: {{ addslashes($student->profile->department->name ?? 'N/A') }}',
                                                cascadeItems: [
                                                    { title: 'Civil Services Track', count: 'Active Enrollment Removed', icon: 'check' },
                                                    { title: 'Degree Academic Record', count: '100% Intact & Preserved', icon: 'student' }
                                                ],
                                                warningTitle: 'Unenroll {{ $student->username }} from Civil Services?',
                                                warningBody: 'Are you sure you want to remove {{ $student->username }} from the Civil Services coaching track? Their main academic record and degree credentials will remain safe and untouched.',
                                                deleteUrl: '{{ $unenrollUrl }}',
                                                submitBtnText: 'Yes, Unenroll Student'
                                            })"
                                            class="text-xs font-semibold text-red-600 hover:text-red-800 px-3 py-1 rounded-lg bg-red-50 hover:bg-red-100 transition-colors cursor-pointer">
                                            Unenroll
                                        </button>
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
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
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
                    <input type="text" name="reg_number" required maxlength="10" minlength="10" placeholder="e.g. 241FA04001 (10 chars)" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')" class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase font-mono">
                    <p class="text-xs text-gray-400 mt-1">Enter the 10-character student university registration number.</p>
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
            const schoolSelectEl = document.getElementById('filter_school');
            const departmentSelectEl = document.getElementById('filter_department');
            const statusSelectEl = document.getElementById('status');

            const schoolBtn = document.getElementById('schoolDropdownBtn');
            const schoolMenu = document.getElementById('schoolDropdownMenu');
            const schoolChevron = document.getElementById('schoolChevron');
            const schoolSelectedText = document.getElementById('schoolDropdownSelected');

            const deptBtn = document.getElementById('deptDropdownBtn');
            const deptMenu = document.getElementById('deptDropdownMenu');
            const deptChevron = document.getElementById('deptChevron');
            const deptSelectedText = document.getElementById('deptDropdownSelected');

            const statusBtn = document.getElementById('statusDropdownBtn');
            const statusMenu = document.getElementById('statusDropdownMenu');
            const statusChevron = document.getElementById('statusChevron');
            const statusSelectedText = document.getElementById('statusDropdownSelected');

            const allDepartments = [
                @foreach($departments as $dept)
                {
                    value: "{{ $dept->code }}",
                    label: "{{ addslashes($dept->name) }}",
                    schoolId: "{{ $dept->school_id }}"
                },
                @endforeach
            ];

            function closeAllCustomDropdowns() {
                if (schoolMenu) schoolMenu.classList.add('hidden');
                if (schoolChevron) schoolChevron.classList.remove('rotate-180');
                if (deptMenu) deptMenu.classList.add('hidden');
                if (deptChevron) deptChevron.classList.remove('rotate-180');
                if (statusMenu) statusMenu.classList.add('hidden');
                if (statusChevron) statusChevron.classList.remove('rotate-180');
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown')) {
                    closeAllCustomDropdowns();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeAllCustomDropdowns();
            });

            function syncLabels() {
                if (schoolSelectEl && schoolSelectedText) {
                    const selectedOpt = schoolSelectEl.options[schoolSelectEl.selectedIndex];
                    schoolSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Schools --';
                }
                if (departmentSelectEl && deptSelectedText) {
                    const selectedOpt = departmentSelectEl.options[departmentSelectEl.selectedIndex];
                    deptSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Departments --';
                }
                if (statusSelectEl && statusSelectedText) {
                    const selectedOpt = statusSelectEl.options[statusSelectEl.selectedIndex];
                    statusSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Status --';
                }
            }

            if (schoolBtn && schoolMenu) {
                schoolBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = schoolMenu.classList.contains('hidden');
                    closeAllCustomDropdowns();
                    if (isHidden) {
                        schoolMenu.classList.remove('hidden');
                        if (schoolChevron) schoolChevron.classList.add('rotate-180');
                    }
                });
            }

            if (schoolMenu) {
                schoolMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-school-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (schoolSelectEl) {
                        schoolSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                        renderDepartments();
                    }
                });
            }

            if (deptBtn && deptMenu) {
                deptBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = deptMenu.classList.contains('hidden');
                    closeAllCustomDropdowns();
                    if (isHidden) {
                        deptMenu.classList.remove('hidden');
                        if (deptChevron) deptChevron.classList.add('rotate-180');
                    }
                });
            }

            if (deptMenu) {
                deptMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-dept-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (departmentSelectEl) {
                        departmentSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                    }
                });
            }

            if (statusBtn && statusMenu) {
                statusBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = statusMenu.classList.contains('hidden');
                    closeAllCustomDropdowns();
                    if (isHidden) {
                        statusMenu.classList.remove('hidden');
                        if (statusChevron) statusChevron.classList.add('rotate-180');
                    }
                });
            }

            if (statusMenu) {
                statusMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-status-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (statusSelectEl) {
                        statusSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                    }
                });
            }

            function renderDepartments() {
                if (!departmentSelectEl || !deptMenu) return;
                const selSchool = schoolSelectEl ? schoolSelectEl.value : '';
                const currDept = departmentSelectEl.value;

                departmentSelectEl.innerHTML = '<option value="">-- All Departments --</option>';
                deptMenu.innerHTML = `
                    <div class="custom-dept-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium" data-value="">
                        <span>-- All Departments --</span>
                    </div>
                `;

                const filtered = allDepartments.filter(d => !selSchool || d.schoolId === selSchool);
                let foundCurrent = false;

                filtered.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.value;
                    opt.textContent = d.label;
                    if (d.value === currDept) {
                        opt.selected = true;
                        foundCurrent = true;
                    }
                    departmentSelectEl.appendChild(opt);

                    const div = document.createElement('div');
                    div.className = `custom-dept-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between ${d.value === currDept ? 'bg-blue-50 text-blue-800 font-semibold' : ''}`;
                    div.setAttribute('data-value', d.value);
                    div.innerHTML = `<span class="whitespace-normal leading-normal text-xs sm:text-sm">${d.label}</span>`;
                    deptMenu.appendChild(div);
                });

                if (!foundCurrent && currDept !== '') {
                    departmentSelectEl.value = '';
                }
                syncLabels();
            }

            renderDepartments();
            const initDept = "{{ request('department') }}";
            if (initDept && departmentSelectEl) {
                departmentSelectEl.value = initDept;
                renderDepartments();
            }
        });
    </script>
</body>
</html>
