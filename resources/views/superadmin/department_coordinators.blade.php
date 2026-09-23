<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department-wise Coordinators | Superadmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center ml-auto">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Superadmin' }}</span>
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
                
                <!-- Page Header with Action -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Department-wise Coordinators</h1>
                        <p class="text-sm text-gray-500 mt-1">Overview of coordinator distribution and assignments across all university departments</p>
                    </div>
                    <a href="{{ route('coordinators.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                        + Add Department Coordinator
                    </a>
                </div>

                <!-- Unified Stats Grid -->
                <div id="stats-container" class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
                    <div class="grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                        <!-- Total Departments -->
                        <a href="{{ route('coordinators.departments_list') }}" class="block p-4 sm:p-6 flex flex-col items-center justify-center text-center hover:bg-blue-50/30 transition-colors">
                            <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600 mb-2 sm:mb-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalDepartments }}</p>
                            <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide mt-1">Total Depts</p>
                        </a>

                        <!-- Total Active Coordinators -->
                        <a href="{{ route('coordinators.departments_list', ['status' => 'assigned']) }}" class="block p-4 sm:p-6 flex flex-col items-center justify-center text-center hover:bg-green-50/30 transition-colors">
                            <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 sm:mb-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalActiveCoordinators }}</p>
                            <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide mt-1">Coordinators</p>
                        </a>

                        <!-- Assigned Departments -->
                        <a href="{{ route('coordinators.departments_list', ['status' => 'assigned']) }}" class="block p-4 sm:p-6 flex flex-col items-center justify-center text-center hover:bg-indigo-50/30 transition-colors">
                            <div class="p-2 sm:p-3 rounded-full bg-indigo-100 text-indigo-600 mb-2 sm:mb-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $departmentsWithCoordinators }}</p>
                            <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide mt-1">Assigned Depts</p>
                        </a>

                        <!-- Unassigned Departments -->
                        <a href="{{ route('coordinators.departments_list', ['status' => 'unassigned']) }}" class="block p-4 sm:p-6 flex flex-col items-center justify-center text-center hover:bg-orange-50/30 transition-colors">
                            <div class="p-2 sm:p-3 rounded-full bg-orange-100 text-orange-600 mb-2 sm:mb-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $unassignedDepartments }}</p>
                            <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wide mt-1">Unassigned Depts</p>
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('coordinators.departments_list') }}" method="GET" class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <label for="school" class="text-sm font-semibold text-gray-700 whitespace-nowrap">School:</label>
                            <select name="school" id="school" class="no-tomselect block w-full sm:w-auto min-w-[320px] sm:min-w-[460px] lg:min-w-[540px] px-3.5 py-2.5 text-sm border border-gray-300 bg-white text-gray-800 focus:ring-blue-500 focus:border-blue-500 rounded-lg cursor-pointer transition-all shadow-xs">
                                <option value="">-- All Schools --</option>
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
                                @endphp
                                @foreach($orderedSchools as $idx => $school)
                                    <option value="{{ $school->code }}" {{ request('school') == $school->code ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $idx + 1) }}. {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <label for="department" class="text-sm font-semibold text-gray-700 whitespace-nowrap">Department:</label>
                            <select name="department" id="department" class="no-tomselect block w-full sm:w-auto min-w-[280px] sm:min-w-[360px] lg:min-w-[420px] px-3.5 py-2.5 text-sm border border-gray-300 bg-white text-gray-800 focus:ring-blue-500 focus:border-blue-500 rounded-lg cursor-pointer transition-all shadow-xs">
                                <option value="">-- All Departments --</option>
                                @foreach($allDepartments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <label for="status" class="text-sm font-semibold text-gray-700 whitespace-nowrap">Status:</label>
                            <select name="status" id="status" class="no-tomselect block w-full sm:w-44 px-3 py-2 text-sm border border-gray-300 bg-white text-gray-800 focus:ring-blue-500 focus:border-blue-500 rounded-lg cursor-pointer transition-all">
                                <option value="">-- All Status --</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="unassigned" {{ request('status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            </select>
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const schoolSelect = document.getElementById('school');
                        const filterForm = schoolSelect ? schoolSelect.closest('form') : null;
                        const deptSelect = document.getElementById('department');
                        const statusSelect = document.getElementById('status');
                        
                        if (filterForm && schoolSelect && deptSelect) {
                            // Extract all department options and their associated school
                            const allDeptOptions = Array.from(deptSelect.options).map(opt => ({
                                value: opt.value,
                                text: opt.text,
                                schoolId: opt.getAttribute('data-school-id')
                            }));

                            function filterDeptDropdown(selectedSchool, targetVal = null) {
                                const currentDept = targetVal !== null ? targetVal : deptSelect.value;
                                deptSelect.innerHTML = '<option value="">-- All Departments --</option>';
                                let matchFound = false;

                                allDeptOptions.forEach(opt => {
                                    if (!opt.value) return;
                                    if (!selectedSchool || opt.schoolId === selectedSchool) {
                                        const optionEl = document.createElement('option');
                                        optionEl.value = opt.value;
                                        optionEl.text = opt.text;
                                        optionEl.setAttribute('data-school-id', opt.schoolId);
                                        if (opt.value === currentDept) {
                                            optionEl.selected = true;
                                            matchFound = true;
                                        }
                                        deptSelect.appendChild(optionEl);
                                    }
                                });

                                if (!matchFound && currentDept) {
                                    deptSelect.value = '';
                                }
                            }

                            // Filter initially if a school is selected
                            if (schoolSelect.value) {
                                filterDeptDropdown(schoolSelect.value, '{{ request("department") }}');
                            }

                            // When School dropdown changes: immediately filter departments and submit form
                            schoolSelect.addEventListener('change', function() {
                                filterDeptDropdown(this.value, '');
                                filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                            });

                            // When Department or Status changes: submit form
                            deptSelect.addEventListener('change', function() {
                                filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                            });

                            if (statusSelect) {
                                statusSelect.addEventListener('change', function() {
                                    filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                                });
                            }
                            
                            // Handle form submission via AJAX
                            filterForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const url = new URL(filterForm.action);
                                const formData = new FormData(filterForm);
                                const searchParams = new URLSearchParams(formData);
                                url.search = searchParams.toString();
                                
                                window.history.pushState({}, '', url);
                                
                                const tableContainer = document.getElementById('table-container');
                                if (tableContainer) tableContainer.style.opacity = '0.5';
                                
                                fetch(url)
                                .then(res => res.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    
                                    const newStats = doc.getElementById('stats-container');
                                    const oldStats = document.getElementById('stats-container');
                                    if (newStats && oldStats) oldStats.innerHTML = newStats.innerHTML;
                                    
                                    const newTable = doc.getElementById('table-container');
                                    if (newTable && tableContainer) {
                                        tableContainer.innerHTML = newTable.innerHTML;
                                        tableContainer.style.opacity = '1';
                                    }
                                })
                                .catch(err => {
                                    console.error('AJAX filter failed:', err);
                                    filterForm.submit(); // Fallback
                                });
                            });
                            
                            // Handle stat cards clicks via AJAX & sync filters
                            document.addEventListener('click', function(e) {
                                const statLink = e.target.closest('#stats-container a');
                                if (statLink && filterForm) {
                                    e.preventDefault();
                                    const url = new URL(statLink.href, window.location.origin);
                                    const statusParam = url.searchParams.get('status') || '';
                                    
                                    if (statusSelect) {
                                        statusSelect.value = statusParam;
                                    }
                                    
                                    filterForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                                }
                            });

                            // Handle pagination clicks via AJAX
                            document.addEventListener('click', function(e) {
                                const navLink = e.target.closest('#table-container nav a');
                                if (navLink) {
                                    e.preventDefault();
                                    
                                    const url = new URL(navLink.href);
                                    window.history.pushState({}, '', url);
                                    
                                    const tableContainer = document.getElementById('table-container');
                                    if (tableContainer) tableContainer.style.opacity = '0.5';
                                    
                                    fetch(url)
                                    .then(res => res.text())
                                    .then(html => {
                                        const parser = new DOMParser();
                                        const doc = parser.parseFromString(html, 'text/html');
                                        
                                        const newTable = doc.getElementById('table-container');
                                        if (newTable && tableContainer) {
                                            tableContainer.innerHTML = newTable.innerHTML;
                                            tableContainer.style.opacity = '1';
                                        }
                                    });
                                }
                            });
                        }
                    });
                </script>

                <!-- Data Table -->
                <div id="table-container" class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-[#f8f9fc]">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        School
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Coordinator Count
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                        Assigned Coordinators & Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($departments as $dept)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-800">{{ $dept->name }}</span>
                                            <span class="text-xs font-mono text-gray-400 mt-1">{{ $dept->code }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <span class="text-[11px] font-medium text-gray-500 uppercase">{{ $dept->school->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-5 align-top text-center">
                                        @if($dept->profiles->count() > 0)
                                            <span class="inline-flex items-center font-bold text-gray-800 text-sm">
                                                {{ $dept->profiles->count() }} COORDINATOR{{ $dept->profiles->count() > 1 ? 'S' : '' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold">
                                                0 COORDINATORS
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        @if($dept->profiles->count() > 0)
                                            <div class="flex flex-col gap-2 items-start w-full">
                                                @foreach($dept->profiles as $profile)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full max-w-md p-2 bg-gray-50 border border-gray-200 rounded-lg hover:bg-white transition-colors group shadow-sm">
                                                    <div class="flex items-center gap-2 mb-2 sm:mb-0">
                                                        <span class="font-bold text-gray-800 text-sm">{{ $profile->user->username ?? $profile->username }}</span>
                                                        <span class="text-gray-400 text-xs">|</span>
                                                        <span class="text-gray-600 text-sm font-medium">{{ $profile->first_name }}</span>
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700 ml-1">Active</span>
                                                    </div>
                                                     <div class="flex items-center gap-1">
                                                        <button type="button" 
                                                            onclick="openCoordinatorDetailsModal({
                                                                id: '{{ $profile->user->id ?? $profile->user_id }}',
                                                                name: '{{ addslashes(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')) }}',
                                                                username: '{{ addslashes($profile->user->username ?? $profile->username) }}',
                                                                email: '{{ addslashes($profile->email ?? ($profile->user->email ?? 'N/A')) }}',
                                                                phone: '{{ addslashes($profile->phone ?? 'N/A') }}',
                                                                designation: '{{ addslashes($profile->designation ?? 'Department Coordinator') }}',
                                                                school: '{{ addslashes($profile->school->name ?? ($dept->school->name ?? 'N/A')) }}',
                                                                department: '{{ addslashes($profile->department->name ?? ($dept->name ?? 'N/A')) }}',
                                                                deptCode: '{{ addslashes($dept->code ?? '') }}',
                                                                photo: '{{ $profile->photo ? asset('storage/' . $profile->photo) : '' }}',
                                                                initial: '{{ strtoupper(substr($profile->first_name ?: 'C', 0, 1)) }}',
                                                                editUrl: '{{ route('coordinators.edit', $profile->user->id ?? $profile->user_id) }}',
                                                                resetUrl: '{{ route('coordinators.index', ['action' => 'reset_password', 'search' => $profile->user->username ?? $profile->username]) }}'
                                                            })"
                                                            class="w-8.5 h-8.5 p-2 flex items-center justify-center rounded-xl bg-sky-50/80 text-sky-600 border border-sky-200/80 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5"
                                                            title="View Details">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        </button>
                                                        <a href="{{ route('coordinators.edit', $profile->user->id ?? $profile->user_id) }}" class="w-8.5 h-8.5 p-2 flex items-center justify-center rounded-xl bg-indigo-50/80 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        </a>
                                                        <button type="button" onclick="openResetUserPasswordModal('{{ route('users.reset-password', $profile->user->id ?? $profile->user_id) }}', '{{ $profile->user->username ?? $profile->username }}', '{{ addslashes(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')) }}', 'Admin!741')" class="w-8.5 h-8.5 p-2 flex items-center justify-center rounded-xl bg-amber-50/80 text-amber-600 border border-amber-200/80 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Reset Password">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                                        </button>
                                                        @if(Auth::user()->role === 'sa')
                                                        <form action="{{ route('coordinators.destroy', $profile->user->id ?? $profile->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Coordinator?');" class="inline m-0 p-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-8.5 h-8.5 p-2 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Delete">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                <div class="mt-1">
                                                    <a href="{{ route('coordinators.create', ['department' => $dept->code]) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                        Add Another
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between gap-6">
                                                <span class="text-sm italic text-gray-400">No coordinator assigned</span>
                                                <a href="{{ route('coordinators.create', ['department' => $dept->code]) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-sm transition-colors">
                                                    + Assign Coordinator
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-900">No departments found</h3>
                                        <p class="text-sm text-gray-500 mt-1">Adjust your filters to see more results.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($departments->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $departments->links() }}
                        </div>
                    @endif
                </div>
            </div>
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>
</div>

@include('partials.coordinator_details_modal')

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

@include('partials.reset_password_modal')
<script>
    if (typeof window.initLMSUI === 'function') {
        window.initLMSUI();
    }
    @if($errors->has('password'))
        if (window.openPwdModal) window.openPwdModal();
    @endif
</script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>

