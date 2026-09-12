<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
                        @if(request('action') == 'reset_password')
                            Reset Password - Staff
                        @else
                            Manage Staff
                        @endif
                    </h1>
                    <div class="flex flex-wrap sm:flex-nowrap gap-3 w-full sm:w-auto">
                        @if(request('action') != 'reset_password')
                        <button type="button" onclick="openBulkUploadModal('sta')" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-white border border-gray-200 hover:border-teal-500 hover:bg-teal-50 text-gray-700 hover:text-teal-700 font-semibold text-sm py-2.5 px-5 rounded-xl shadow-sm hover:shadow transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            Bulk Upload
                        </button>
                        <a href="{{ route('staff.create') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white font-semibold text-sm py-2.5 px-5 rounded-xl shadow-md shadow-teal-500/20 hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-300 ease-in-out focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transform hover:-translate-y-0.5 whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Staff
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 mb-6 transition-all hover:shadow-md">
                    <form action="{{ route('staff.index') }}" method="GET" class="flex flex-row flex-wrap items-center gap-3 w-full" id="filterForm">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200" placeholder="Search by name, username or email...">
                            </div>
                        </div>
                        
                        @if(Auth::user()->role === 'sa')
                        <div class="w-full sm:w-48 shrink-0">
                            <label for="school" class="sr-only">School</label>
                            <select name="school" id="school" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Schools</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->code }}" {{ request('school') == $school->code ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-48 shrink-0">
                            <label for="department" class="sr-only">Department</label>
                            <select name="department" id="department" class="no-tomselect block w-full pl-3 pr-10 py-2.5 text-sm border-gray-200 bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 rounded-lg transition-all duration-200 cursor-pointer appearance-none">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" data-school-id="{{ $dept->school_id }}" {{ request('department') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="flex items-center space-x-2 shrink-0">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                Apply
                            </button>
                            @if(request()->hasAny(['search', 'school', 'department']) && (request('search') != '' || request('school') != '' || request('department') != ''))
                            <a href="{{ route('staff.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                                Clear
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center mb-6 shadow-sm">
                        <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <div id="staffTableContainer" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Photo</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Employee ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">School/Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staffMembers as $staff)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($staff->photo)
                                            <img class="h-10 w-10 rounded-full object-cover border" src="{{ asset('storage/' . $staff->photo) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold">
                                                {{ substr($staff->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $staff->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $staff->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $staff->designation ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $staff->school->name ?? 'N/A' }}</div>
                                    <div class="text-xs">{{ $staff->department->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-4 justify-end sm:justify-start">
                                        @if(request('action') == 'reset_password')
                                            @if(in_array(Auth::user()->role, ['sa', 'admin']))
                                            <button type="button" onclick="openResetUserPasswordModal('{{ route('users.reset-password', $staff->id) }}')" class="text-orange-600 hover:text-orange-900">Reset Password</button>
                                            @endif
                                        @else
                                            <a href="{{ route('staff.edit', $staff->id) }}" class="text-teal-600 hover:text-teal-900">Edit</a>
                                            @if(Auth::user()->role === 'sa')
                                            <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-white">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <p class="text-gray-500 font-medium">No staff found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $staffMembers->links() }}
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

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        // Dynamic Department Filtering based on School Selection
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelectEl = document.getElementById('school');
            const departmentSelectEl = document.getElementById('department');
            
            if (schoolSelectEl && departmentSelectEl) {
                // Store all original department options
                const allDepartments = Array.from(departmentSelectEl.options).filter(opt => opt.value !== '').map(opt => ({
                    value: opt.value,
                    label: opt.text,
                    schoolId: opt.getAttribute('data-school-id'),
                    selected: opt.selected
                }));
                
                function filterDepartments() {
                    const selectedSchoolId = schoolSelectEl.value;
                    const currentDeptValue = departmentSelectEl.value;
                    
                    // Clear current options
                    departmentSelectEl.innerHTML = '<option value="">All Departments</option>';
                    
                    let hasSelected = false;
                    allDepartments.forEach(dept => {
                        if (!selectedSchoolId || dept.schoolId === selectedSchoolId) {
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
                    });
                    
                    if (!hasSelected) {
                        departmentSelectEl.value = '';
                    }
                }
                
                schoolSelectEl.addEventListener('change', filterDepartments);
                
                // Initialize on load
                filterDepartments();
            }

            // Live Filtering Integration
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('search');
            const tableContainer = document.getElementById('staffTableContainer');

            let timeout = null;

            function fetchResults() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableContainer = doc.getElementById('staffTableContainer');
                    
                    if (newTableContainer && tableContainer) {
                        tableContainer.innerHTML = newTableContainer.innerHTML;
                    }
                    
                    window.history.pushState({}, '', url);
                })
                .catch(error => console.error('Error fetching live filter results:', error));
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
        });
    </script>
    @include('partials.reset_password_modal')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>

