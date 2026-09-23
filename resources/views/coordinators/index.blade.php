<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coordinators - LMS</title>
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
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Admin' }}</span>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50 flex flex-col">
            <div class="max-w-7xl mx-auto space-y-6 w-full flex-1">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <div class="flex items-center gap-3">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
                            @if(request('action') == 'reset_password')
                                Reset Password - Coordinators
                            @else
                                Manage Coordinators
                            @endif
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                            {{ $coordinators->total() }} Total
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        @if(request('action') != 'reset_password')

                        <a href="{{ route('coordinators.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition-all duration-200 ease-in-out focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Coordinator
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('coordinators.index') }}" method="GET" class="flex flex-row flex-wrap items-center gap-3 w-full" id="filterForm">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" placeholder="Search by name, username or email...">
                            </div>
                        </div>

                        <div class="w-full sm:w-auto min-w-[320px] sm:min-w-[460px] lg:min-w-[540px] shrink-0">
                            <label for="school" class="sr-only">School</label>
                            <select name="school" id="school" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none shadow-xs">
                                <option value="">All Schools</option>
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

                        <div class="w-full sm:w-auto min-w-[280px] sm:min-w-[360px] lg:min-w-[420px] shrink-0">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none shadow-xs">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-3 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                Apply Filters
                            </button>
                            <a href="{{ route('coordinators.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-xs" title="Reset all filters">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('search');
                        const filterForm = document.getElementById('filterForm');
                        const schoolSelect = document.getElementById('school');
                        const deptSelect = document.getElementById('department');

                        if (searchInput && filterForm) {
                            let debounceTimer;
                            searchInput.addEventListener('input', function() {
                                clearTimeout(debounceTimer);
                                debounceTimer = setTimeout(() => {
                                    filterForm.submit();
                                }, 500);
                            });
                        }

                        if (schoolSelect && deptSelect) {
                            const allDeptOptions = Array.from(deptSelect.options).map(opt => ({
                                value: opt.value,
                                text: opt.text,
                                schoolId: opt.getAttribute('data-school-id')
                            }));

                            function filterDeptDropdown(selectedSchool) {
                                const currentDept = deptSelect.value;
                                deptSelect.innerHTML = '<option value="">All Departments</option>';
                                allDeptOptions.forEach(opt => {
                                    if (opt.value === '') return;
                                    if (!selectedSchool || opt.schoolId === selectedSchool) {
                                        const optionEl = document.createElement('option');
                                        optionEl.value = opt.value;
                                        optionEl.text = opt.text;
                                        optionEl.setAttribute('data-school-id', opt.schoolId);
                                        if (opt.value === currentDept) {
                                            optionEl.selected = true;
                                        }
                                        deptSelect.appendChild(optionEl);
                                    }
                                });
                            }

                            schoolSelect.addEventListener('change', function() {
                                filterDeptDropdown(this.value);
                                if (filterForm) filterForm.submit();
                            });

                            deptSelect.addEventListener('change', function() {
                                if (filterForm) filterForm.submit();
                            });

                            if (schoolSelect.value) {
                                filterDeptDropdown(schoolSelect.value);
                            }
                        }
                    });
                </script>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">School/Department</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($coordinators as $coordinator)
                            @php
                                $firstName = $coordinator->profile->first_name ?? $coordinator->first_name ?? '';
                                $lastName = $coordinator->profile->last_name ?? $coordinator->last_name ?? '';
                                $fullName = trim($firstName . ' ' . $lastName);
                                if (empty($fullName)) $fullName = $coordinator->username ?? 'Coordinator';
                                $email = $coordinator->profile->email ?? $coordinator->email ?? 'N/A';
                                $phone = $coordinator->profile->phone ?? 'N/A';
                                $designation = $coordinator->profile->designation ?? 'Department Coordinator';
                                $schoolName = $coordinator->profile->school->name ?? 'N/A';
                                $deptName = $coordinator->profile->department->name ?? 'N/A';
                                $deptCode = $coordinator->profile->department->code ?? '';
                                $photoUrl = ($coordinator->profile && $coordinator->profile->photo) ? asset('storage/' . $coordinator->profile->photo) : '';
                                $initial = strtoupper(substr($firstName ?: ($coordinator->username ?: 'C'), 0, 1));
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($photoUrl)
                                            <img class="w-9 h-9 rounded-xl object-cover ring-2 ring-slate-100" src="{{ $photoUrl }}" alt="{{ $fullName }}">
                                        @else
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                                {{ $initial }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $fullName }}</div>
                                            <div class="text-xs text-gray-500">{{ $email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono font-bold text-gray-900">{{ $coordinator->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $designation }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="font-semibold text-gray-800">{{ $schoolName }}</div>
                                    <div class="text-xs text-gray-500">{{ $deptName }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" 
                                                onclick="openCoordinatorDetailsModal({
                                                    id: '{{ $coordinator->id }}',
                                                    name: '{{ addslashes($fullName) }}',
                                                    username: '{{ addslashes($coordinator->username) }}',
                                                    email: '{{ addslashes($coordinator->email ?? 'N/A') }}',
                                                    phone: '{{ addslashes($coordinator->phone ?? 'N/A') }}',
                                                    designation: '{{ addslashes($coordinator->designation ?? 'Department Coordinator') }}',
                                                    school: '{{ addslashes($coordinator->school->name ?? 'N/A') }}',
                                                    department: '{{ addslashes($coordinator->department->name ?? 'N/A') }}',
                                                    deptCode: '{{ addslashes($coordinator->department->code ?? '') }}',
                                                    photo: '{{ $coordinator->photo ? asset('storage/' . $coordinator->photo) : '' }}',
                                                    initial: '{{ strtoupper(substr($coordinator->first_name ?: $coordinator->username, 0, 1)) }}',
                                                    editUrl: '{{ route('coordinators.edit', $coordinator->id) }}',
                                                    resetUrl: '{{ route('coordinators.index', ['action' => 'reset_password', 'search' => $coordinator->username]) }}'
                                                })"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-sky-50/80 text-sky-600 border border-sky-200/80 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="View Coordinator Details">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>

                                            <a href="{{ route('coordinators.edit', $coordinator->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50/80 text-indigo-600 border border-indigo-200/80 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Edit Coordinator">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            @if(Auth::user()->role === 'sa')
                                            <button type="button" onclick="openResetUserPasswordModal('{{ route('users.reset-password', $coordinator->id) }}', '{{ $coordinator->username }}', '{{ addslashes($fullName) }}', 'Admin!741')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50/80 text-amber-600 border border-amber-200/80 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Reset Password for {{ $coordinator->username }}">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>

                                            <form action="{{ route('coordinators.destroy', $coordinator->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Coordinator?');" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50/80 text-rose-600 border border-rose-200/80 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5" title="Delete Coordinator">
                                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">No Coordinators found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $coordinators->links() }}
                    </div>
                </div>
            </div>
            
            <footer class="mt-auto border-t border-gray-200 pt-4 pb-2 w-full">
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

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Dynamic Department Filtering based on School Selection
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.getElementById('school');
            const departmentSelect = document.getElementById('department');
            
            if (schoolSelect && departmentSelect) {
                // Store all original department options (excluding the default "All Departments" option)
                const allDepartments = Array.from(departmentSelect.options).filter(opt => opt.value !== '');
                const defaultOption = departmentSelect.options[0];
                
                function filterDepartments() {
                    const selectedSchoolCode = schoolSelect.value;
                    let currentDeptValue = departmentSelect.value;
                    let deptStillValid = false;

                    // Clear current options except the default one
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

                    // If the previously selected department is no longer in the list, reset to default
                    if (!deptStillValid && currentDeptValue !== '') {
                        departmentSelect.value = '';
                    }
                }
                
                // Listen for school changes
                schoolSelect.addEventListener('change', filterDepartments);
                
                // Run on initial load
                filterDepartments();
                
                // Ensure the selected department from the request is maintained if it's valid
                const requestedDept = "{{ request('department') }}";
                if(requestedDept) {
                     departmentSelect.value = requestedDept;
                }
            }
        });
    </script>

    @include('partials.coordinator_details_modal')
    @include('partials.reset_password_modal')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>

