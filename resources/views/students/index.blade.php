<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - LMS</title>
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
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-2.5 ml-auto">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            <div class="w-full space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
                        Manage Students
                    </h1>
                    @if(in_array(Auth::user()->role, ['sa', 'admin']))
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto">
                        <button type="button" onclick="openBulkUploadModal('stu')" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm py-2.5 px-5 rounded-2xl shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ea580c] whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            Bulk Upload
                        </button>
                        <a href="{{ route('students.create') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-[#ea580c] hover:bg-[#c2410c] text-white font-bold text-sm py-2.5 px-5 rounded-2xl shadow-md shadow-[#ea580c]/25 hover:shadow-lg transition-all duration-200 ease-in-out focus:ring-2 focus:ring-offset-2 focus:ring-[#ea580c] transform hover:-translate-y-0.5 whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2 -ml-0.5 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Student
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Total Students Stat Card -->
                <div id="stats-container" class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-4 sm:p-5 flex items-center justify-between transition-all hover:shadow-md">
                    <button type="button" @if(Auth::user()->role === 'sa') onclick="openDeptStudentsModal()" @endif class="flex items-center gap-4 text-left @if(Auth::user()->role === 'sa') cursor-pointer group @endif focus:outline-none">
                        <div class="p-3 sm:p-3.5 rounded-full bg-orange-100 text-orange-600 @if(Auth::user()->role === 'sa') group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white @endif transition-all shadow-sm shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl sm:text-3xl font-bold text-gray-900 @if(Auth::user()->role === 'sa') group-hover:text-orange-600 @endif transition-colors">{{ $totalStudents }}</span>
                                @if(Auth::user()->role === 'sa')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200 px-2.5 py-0.5 rounded-full group-hover:bg-orange-600 group-hover:text-white transition-all">
                                    Click for Department Breakdown
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                                @endif
                            </div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mt-0.5">Total Students</p>
                        </div>
                    </button>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('students.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center w-full" id="filterForm">
                        <div class="@if(Auth::user()->role === 'sa') md:col-span-3 @else md:col-span-6 @endif w-full">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-2xs" placeholder="Search by name, reg number or email...">
                            </div>
                        </div>
                        
                        @if(Auth::user()->role === 'sa')
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

                            $currSchoolName = '-- All Schools --';
                            if(request('school')) {
                                foreach($orderedSchools as $idx => $s) {
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
                        @endphp

                        <!-- School Custom Dropdown -->
                        <div class="relative w-full md:col-span-3 custom-dropdown" id="schoolDropdownContainer">
                            <label for="school" class="sr-only">School</label>
                            <select name="school" id="school" class="hidden">
                                <option value="">-- All Schools --</option>
                                @foreach($orderedSchools as $idx => $school)
                                    <option value="{{ $school->code }}" {{ request('school') == $school->code ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $idx + 1) }}. {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" id="schoolDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all text-left shadow-2xs">
                                <span id="schoolDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currSchoolName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="schoolChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="schoolDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-[320px] sm:min-w-[440px] bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-72 overflow-y-auto py-1.5 space-y-0.5">
                                <div class="custom-school-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium" data-value="">
                                    <span>-- All Schools --</span>
                                </div>
                                @foreach($orderedSchools as $idx => $school)
                                    <div class="custom-school-opt px-3.5 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between {{ request('school') == $school->code ? 'bg-orange-50 text-orange-800 font-semibold' : '' }}" data-value="{{ $school->code }}">
                                        <span class="whitespace-normal leading-normal">{{ sprintf('%02d', $idx + 1) }}. {{ $school->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Department Custom Dropdown -->
                        <div class="relative w-full md:col-span-2 custom-dropdown" id="deptDropdownContainer">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" class="hidden">
                                <option value="">-- All Departments --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school->code ?? $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <button type="button" id="deptDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all text-left shadow-2xs">
                                <span id="deptDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currDeptName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="deptChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="deptDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-[280px] sm:min-w-[360px] bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-72 overflow-y-auto py-1.5 space-y-0.5">
                                <!-- Populated dynamically based on School -->
                            </div>
                        </div>
                        @endif

                        <!-- Program Custom Dropdown -->
                        @php
                            $currProgName = '-- All Programs --';
                            if(request('program')) {
                                $pObj = $programs->firstWhere('code', request('program'));
                                if($pObj) {
                                    $currProgName = $pObj->name;
                                }
                            }
                        @endphp
                        <div class="relative w-full @if(Auth::user()->role === 'sa') md:col-span-2 @else md:col-span-4 @endif custom-dropdown" id="progDropdownContainer">
                            <label for="program" class="sr-only">Program</label>
                            <select name="program" id="program" class="hidden">
                                <option value="">-- All Programs --</option>
                                @foreach($programs as $prog)
                                    @php
                                        $progDeptCode = $prog->department->code ?? '';
                                        $isAdminAllowed = Auth::user()->role === 'sa' || (Auth::user()->role === 'admin' && Auth::user()->profile->departments_id === $progDeptCode);
                                    @endphp
                                    @if($isAdminAllowed)
                                        <option value="{{ $prog->code }}" data-department-id="{{ $progDeptCode }}" {{ request('program') == $prog->code ? 'selected' : '' }}>{{ $prog->name }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <button type="button" id="progDropdownBtn" class="w-full flex items-center justify-between px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 rounded-lg hover:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all text-left shadow-2xs">
                                <span id="progDropdownSelected" class="font-medium text-gray-700 truncate leading-snug">{{ $currProgName }}</span>
                                <svg class="w-4 h-4 text-gray-400 ml-1.5 shrink-0 transition-transform duration-200" id="progChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div id="progDropdownMenu" class="hidden absolute top-full left-0 mt-1 w-full min-w-[260px] sm:min-w-[340px] bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-72 overflow-y-auto py-1.5 space-y-0.5">
                                <!-- Populated dynamically based on Department -->
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 md:col-span-2 w-full">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-3.5 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                Apply
                            </button>
                            <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center gap-1 px-3 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors shadow-2xs" title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div id="studentsTableContainer" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Photo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Reg Number</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">School/Department</th>
                                @if(in_array(Auth::user()->role, ['sa', 'admin']))
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($student->photo)
                                             <img class="h-10 w-10 rounded-full object-cover border" src="{{ asset('storage/' . $student->photo) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                                {{ substr($student->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-sm text-gray-900">{{ $student->username }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs md:max-w-sm">
                                    <div class="text-xs font-bold text-slate-800 uppercase tracking-tight leading-snug break-words">
                                        {{ $student->school->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1 font-medium flex items-center gap-1.5 leading-tight">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                                        <span class="break-words">{{ $student->department->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                @if(in_array(Auth::user()->role, ['sa', 'admin']))
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('students.edit', $student->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/90 text-indigo-600 border border-indigo-200/70 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Student">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <button type="button" onclick="openResetUserPasswordModal('{{ route('users.reset-password', $student->id) }}', '{{ $student->username }}', '{{ addslashes($student->first_name ?? $student->username) }} {{ addslashes($student->last_name ?? '') }}', 'Student#963')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50/90 text-amber-600 border border-amber-200/70 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Reset Password for {{ $student->username }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                        </button>
                                        @if(Auth::user()->role === 'sa')
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete student {{ $student->username }}?');" class="inline m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Student">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No students found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $students->links() }}
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

    @include('partials.bulk_upload_modal')

    <!-- Department Students Breakdown Modal -->
    @if(Auth::user()->role === 'sa')
    <div id="deptStudentsModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden border border-gray-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-orange-100 text-orange-700 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Students by Department</h3>
                        <p class="text-xs text-gray-500">Departments where students are enrolled</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                        {{ $totalStudents }} Total Students
                    </span>
                    <button type="button" onclick="closeDeptStudentsModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Department Cards Container (Only Departments with Students) -->
            <div class="p-5 sm:p-6 overflow-y-auto max-h-[60vh] bg-gray-50/40 space-y-3">
                @forelse($departmentsWithStudents as $deptItem)
                <div onclick="selectAndFilterStudentDepartment('{{ $deptItem->school->code ?? $deptItem->school_id }}', '{{ $deptItem->code }}')" 
                     class="bg-white p-4 rounded-xl border border-orange-100 hover:border-orange-400 shadow-xs hover:shadow-md transition-all flex items-center justify-between cursor-pointer group">
                    <div class="pr-3">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                            {{ $deptItem->school->name ?? 'School' }}
                        </span>
                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-orange-700 transition-colors">{{ $deptItem->name }}</h4>
                    </div>

                    <div class="flex items-center gap-2.5 shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200 group-hover:bg-orange-600 group-hover:text-white transition-all">
                            <svg class="w-3.5 h-3.5 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $deptItem->student_count }} {{ Str::plural('Student', $deptItem->student_count) }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">
                    <p class="text-sm font-medium text-gray-900">No students are currently assigned to any department.</p>
                </div>
                @endforelse
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Click on any department to filter students in table</span>
                <button type="button" onclick="closeDeptStudentsModal()" class="px-3.5 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Modal Functions
        window.openDeptStudentsModal = function() {
            const modal = document.getElementById('deptStudentsModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        window.closeDeptStudentsModal = function() {
            const modal = document.getElementById('deptStudentsModal');
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        window.selectAndFilterStudentDepartment = function(schoolCode, deptCode) {
            closeDeptStudentsModal();
            const schoolSelectEl = document.getElementById('school');
            const departmentSelectEl = document.getElementById('department');

            if (schoolSelectEl) {
                schoolSelectEl.value = schoolCode;
                schoolSelectEl.dispatchEvent(new Event('change'));

                setTimeout(() => {
                    if (departmentSelectEl) {
                        departmentSelectEl.value = deptCode;
                        departmentSelectEl.dispatchEvent(new Event('change'));
                    }
                }, 80);
            }
        };

        // Close modal on backdrop click or Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeptStudentsModal();
                closeAllCustomDropdowns();
            }
        });
        const deptStudentsModalEl = document.getElementById('deptStudentsModal');
        if (deptStudentsModalEl) {
            deptStudentsModalEl.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeptStudentsModal();
                }
            });
        }

        // Custom Downward Dropdowns System
        function closeAllCustomDropdowns() {
            document.querySelectorAll('#schoolDropdownMenu, #deptDropdownMenu, #progDropdownMenu').forEach(el => el.classList.add('hidden'));
            const schoolChev = document.getElementById('schoolChevron');
            const deptChev = document.getElementById('deptChevron');
            const progChev = document.getElementById('progChevron');
            if (schoolChev) schoolChev.classList.remove('rotate-180');
            if (deptChev) deptChev.classList.remove('rotate-180');
            if (progChev) progChev.classList.remove('rotate-180');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                closeAllCustomDropdowns();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelectEl = document.getElementById('school');
            const departmentSelectEl = document.getElementById('department');
            const programSelectEl = document.getElementById('program');

            const schoolBtn = document.getElementById('schoolDropdownBtn');
            const schoolMenu = document.getElementById('schoolDropdownMenu');
            const schoolSelectedText = document.getElementById('schoolDropdownSelected');
            const schoolChevron = document.getElementById('schoolChevron');

            const deptBtn = document.getElementById('deptDropdownBtn');
            const deptMenu = document.getElementById('deptDropdownMenu');
            const deptSelectedText = document.getElementById('deptDropdownSelected');
            const deptChevron = document.getElementById('deptChevron');

            const progBtn = document.getElementById('progDropdownBtn');
            const progMenu = document.getElementById('progDropdownMenu');
            const progSelectedText = document.getElementById('progDropdownSelected');
            const progChevron = document.getElementById('progChevron');

            // Store full master data
            const allDepartments = [
                @foreach($departments as $dept)
                {
                    value: "{{ $dept->code }}",
                    label: "{{ addslashes($dept->name) }}",
                    schoolId: "{{ $dept->school->code ?? $dept->school_id }}"
                },
                @endforeach
            ];

            const allPrograms = [
                @foreach($programs as $prog)
                @php
                    $pDeptCode = $prog->department->code ?? '';
                    $isAdminAllowed = Auth::user()->role === 'sa' || (Auth::user()->role === 'admin' && Auth::user()->profile->departments_id === $pDeptCode);
                @endphp
                @if($isAdminAllowed)
                {
                    value: "{{ $prog->code }}",
                    label: "{{ addslashes($prog->name) }}",
                    departmentId: "{{ $pDeptCode }}"
                },
                @endif
                @endforeach
            ];

            // Sync button label with hidden select
            function syncLabels() {
                if (schoolSelectEl && schoolSelectedText) {
                    const selectedOpt = schoolSelectEl.options[schoolSelectEl.selectedIndex];
                    schoolSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Schools --';
                }
                if (departmentSelectEl && deptSelectedText) {
                    const selectedOpt = departmentSelectEl.options[departmentSelectEl.selectedIndex];
                    deptSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Departments --';
                }
                if (programSelectEl && progSelectedText) {
                    const selectedOpt = programSelectEl.options[programSelectEl.selectedIndex];
                    progSelectedText.textContent = selectedOpt ? selectedOpt.text : '-- All Programs --';
                }
            }

            // Toggle School Dropdown
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

            // Handle School Option Clicks
            if (schoolMenu) {
                schoolMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-school-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (schoolSelectEl) {
                        schoolSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                        schoolSelectEl.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Rebuild Department Dropdown Items
            function filterDepartments() {
                const selectedSchoolId = schoolSelectEl ? schoolSelectEl.value : '';
                const currentDeptValue = departmentSelectEl ? departmentSelectEl.value : '';

                if (departmentSelectEl) {
                    departmentSelectEl.innerHTML = '<option value="">-- All Departments --</option>';
                }
                if (deptMenu) {
                    deptMenu.innerHTML = '<div class="custom-dept-opt px-3.5 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium" data-value=""><span>-- All Departments --</span></div>';
                }

                let hasSelected = false;
                allDepartments.forEach(dept => {
                    if (!selectedSchoolId || dept.schoolId === selectedSchoolId) {
                        if (departmentSelectEl) {
                            const option = document.createElement('option');
                            option.value = dept.value;
                            option.text = dept.label;
                            option.setAttribute('data-school-id', dept.schoolId);
                            if (dept.value === currentDeptValue) {
                                option.selected = true;
                                hasSelected = true;
                            }
                            departmentSelectEl.appendChild(option);
                        }

                        if (deptMenu) {
                            const optDiv = document.createElement('div');
                            optDiv.className = 'custom-dept-opt px-3.5 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between' + (dept.value === currentDeptValue ? ' bg-orange-50 text-orange-800 font-semibold' : '');
                            optDiv.setAttribute('data-value', dept.value);
                            optDiv.innerHTML = `<span class="whitespace-normal leading-normal">${dept.label}</span>`;
                            deptMenu.appendChild(optDiv);
                        }
                    }
                });

                if (!hasSelected && departmentSelectEl) {
                    departmentSelectEl.value = '';
                }
                filterPrograms();
                syncLabels();
            }

            // Toggle Department Dropdown
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

            // Handle Department Option Clicks
            if (deptMenu) {
                deptMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-dept-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (departmentSelectEl) {
                        departmentSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                        departmentSelectEl.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Rebuild Program Dropdown Items
            function filterPrograms() {
                const selectedDeptId = departmentSelectEl ? departmentSelectEl.value : "{{ Auth::user()->role === 'admin' ? Auth::user()->profile->departments_id : '' }}";
                const currentProgValue = programSelectEl ? programSelectEl.value : '';

                if (programSelectEl) {
                    programSelectEl.innerHTML = '<option value="">-- All Programs --</option>';
                }
                if (progMenu) {
                    progMenu.innerHTML = '<div class="custom-prog-opt px-3.5 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between font-medium" data-value=""><span>-- All Programs --</span></div>';
                }

                let hasSelected = false;
                allPrograms.forEach(prog => {
                    if (!selectedDeptId || prog.departmentId === selectedDeptId) {
                        if (programSelectEl) {
                            const option = document.createElement('option');
                            option.value = prog.value;
                            option.text = prog.label;
                            option.setAttribute('data-department-id', prog.departmentId);
                            if (prog.value === currentProgValue) {
                                option.selected = true;
                                hasSelected = true;
                            }
                            programSelectEl.appendChild(option);
                        }

                        if (progMenu) {
                            const optDiv = document.createElement('div');
                            optDiv.className = 'custom-prog-opt px-3.5 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-800 cursor-pointer rounded-lg mx-1 transition-colors flex items-center justify-between' + (prog.value === currentProgValue ? ' bg-orange-50 text-orange-800 font-semibold' : '');
                            optDiv.setAttribute('data-value', prog.value);
                            optDiv.innerHTML = `<span class="whitespace-normal leading-normal">${prog.label}</span>`;
                            progMenu.appendChild(optDiv);
                        }
                    }
                });

                if (!hasSelected && programSelectEl) {
                    programSelectEl.value = '';
                }
                syncLabels();
            }

            // Toggle Program Dropdown
            if (progBtn && progMenu) {
                progBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = progMenu.classList.contains('hidden');
                    closeAllCustomDropdowns();
                    if (isHidden) {
                        progMenu.classList.remove('hidden');
                        if (progChevron) progChevron.classList.add('rotate-180');
                    }
                });
            }

            // Handle Program Option Clicks
            if (progMenu) {
                progMenu.addEventListener('click', function(e) {
                    const opt = e.target.closest('.custom-prog-opt');
                    if (!opt) return;
                    const val = opt.getAttribute('data-value') || '';
                    if (programSelectEl) {
                        programSelectEl.value = val;
                        syncLabels();
                        closeAllCustomDropdowns();
                        programSelectEl.dispatchEvent(new Event('change'));
                    }
                });
            }

            if (schoolSelectEl) {
                schoolSelectEl.addEventListener('change', function() {
                    filterDepartments();
                });
            }
            if (departmentSelectEl) {
                departmentSelectEl.addEventListener('change', function() {
                    filterPrograms();
                });
            }

            // Initialize dropdowns on load
            filterDepartments();
            syncLabels();

            // Live Filtering Integration
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('search');
            const tableContainer = document.getElementById('studentsTableContainer');

            let timeout = null;

            function fetchResults() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;

                if (tableContainer) {
                    tableContainer.style.opacity = '0.5';
                    tableContainer.style.pointerEvents = 'none';
                }

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableContainer = doc.getElementById('studentsTableContainer');

                    if (newTableContainer && tableContainer) {
                        tableContainer.innerHTML = newTableContainer.innerHTML;
                    }

                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching live filter results:', error))
                .finally(() => {
                    if (tableContainer) {
                        tableContainer.style.opacity = '1';
                        tableContainer.style.pointerEvents = 'auto';
                    }
                });
            }

            function handleFilterChange() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchResults, 300);
            }

            if (searchInput) {
                searchInput.addEventListener('input', handleFilterChange);
            }
            if (schoolSelectEl) {
                schoolSelectEl.addEventListener('change', () => {
                    setTimeout(handleFilterChange, 50);
                });
            }
            if (departmentSelectEl) {
                departmentSelectEl.addEventListener('change', handleFilterChange);
            }
            if (programSelectEl) {
                programSelectEl.addEventListener('change', handleFilterChange);
            }

            // Handle pagination clicks via AJAX
            document.addEventListener('click', function(e) {
                const navLink = e.target.closest('#studentsTableContainer nav a, #studentsTableContainer .pagination a');
                if (navLink && navLink.href) {
                    e.preventDefault();

                    const url = new URL(navLink.href);
                    window.history.pushState({}, '', url.toString());

                    if (tableContainer) {
                        tableContainer.style.opacity = '0.5';
                        tableContainer.style.pointerEvents = 'none';
                    }

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.getElementById('studentsTableContainer');
                        if (newTable && tableContainer) {
                            tableContainer.innerHTML = newTable.innerHTML;
                        }
                    })
                    .catch(err => {
                        console.error('AJAX pagination error:', err);
                        window.location.href = navLink.href;
                    })
                    .finally(() => {
                        if (tableContainer) {
                            tableContainer.style.opacity = '1';
                            tableContainer.style.pointerEvents = 'auto';
                        }
                    });
                }
            });
        });
    </script>
    @include('partials.reset_password_modal')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>
