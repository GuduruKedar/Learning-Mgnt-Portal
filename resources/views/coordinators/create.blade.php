<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Coordinator - LMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => {
                    profileMenu.classList.add('hidden');
                });
                profileMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
            const pwdModal = document.getElementById('passwordModal');
            const openPwdBtn = document.getElementById('openPasswordModalBtn');
            const closePwdBtn = document.getElementById('closePasswordModal');
            const cancelPwdBtn = document.getElementById('cancelPasswordModal');
            
            if (pwdModal && openPwdBtn) {
                const openModal = () => {
                    pwdModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                };
                const closeModal = () => {
                    pwdModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                };
                
                openPwdBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal();
                    if(typeof profileMenu !== 'undefined' && profileMenu) profileMenu.classList.add('hidden');
                });
                closePwdBtn.addEventListener('click', closeModal);
                cancelPwdBtn.addEventListener('click', closeModal);
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif
        });
    </script>

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
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 focus:outline-none">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-3xl mx-auto space-y-4 sm:space-y-6">
                <div class="mb-4 sm:mb-6">
                    <a href="{{ route('coordinators.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2 inline-flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>Back to Coordinators</a>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Add New Coordinator <br class="sm:hidden"> <span class="text-lg sm:text-2xl font-medium text-gray-600">(Department Coordinator)</span></h1>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <form action="{{ route('coordinators.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID / Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit phone number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">School <span class="text-red-500">*</span></label>
                                <input type="hidden" name="school_id" id="school_id_input" value="{{ old('school_id', $preselectedSchoolId ?? '') }}" required>
                                <button type="button" id="school_custom_btn" class="w-full flex justify-between items-center px-4 py-2 border border-gray-300 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" onclick="toggleDropdown('school_dropdown_list')">
                                    <span id="school_display_text" class="truncate">Select School</span>
                                    <svg class="w-4 h-4 ml-2 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="school_dropdown_list" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <div class="p-1">
                                        <div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" onclick="selectSchool('', 'Select School')">Select School</div>
                                        @foreach($schools as $school)
                                            <div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" title="{{ $school->name }}" onclick="selectSchool('{{ $school->id }}', '{{ addslashes(Str::limit(ucwords(strtolower($school->name)), 40)) }}')">
                                                {{ Str::limit(ucwords(strtolower($school->name)), 40) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('school_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                <input type="hidden" name="department_id" id="department_id_input" value="{{ old('department_id', $preselectedDepartmentId ?? '') }}" required>
                                <button type="button" id="department_custom_btn" class="w-full flex justify-between items-center px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors cursor-not-allowed" onclick="toggleDropdown('department_dropdown_list')" disabled>
                                    <span id="department_display_text" class="truncate">Select Department</span>
                                    <svg class="w-4 h-4 ml-2 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="department_dropdown_list" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    <div class="p-1" id="department_dropdown_list_inner">
                                        <div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" onclick="selectDepartment('', 'Select Department')">Select Department</div>
                                    </div>
                                </div>
                                @error('department_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" placeholder="Defaults to Admin!741 if blank" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow w-full sm:w-auto">Create Coordinator</button>
                        </div>
                    </form>
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


    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const navTexts = document.querySelectorAll('.nav-text');
        const logoText = document.getElementById('logo-text');
        const logoContainer = document.getElementById('logo-container');

        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        // Initialize state on load
        if (isCollapsed && sidebar) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            if (logoText) {
                logoText.classList.add('hidden');
                logoText.classList.remove('text-expanded');
            }
            if (logoContainer) {
                logoContainer.classList.remove('justify-between');
                logoContainer.classList.add('justify-center');
            }
            navTexts.forEach(el => {
                el.classList.add('text-collapsed');
                el.classList.remove('text-expanded');
            });
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                isCollapsed = !isCollapsed;
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                if (isCollapsed) {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    
                    if (logoText) {
                        logoText.classList.add('hidden');
                        logoText.classList.remove('text-expanded');
                    }
                    if (logoContainer) {
                        logoContainer.classList.remove('justify-between');
                        logoContainer.classList.add('justify-center');
                    }

                    navTexts.forEach(el => {
                        el.classList.add('text-collapsed');
                        el.classList.remove('text-expanded');
                    });
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    
                    if (logoContainer) {
                        logoContainer.classList.remove('justify-center');
                        logoContainer.classList.add('justify-between');
                    }
                    if (logoText) {
                        logoText.classList.remove('hidden');
                    }

                    setTimeout(() => {
                        if (!isCollapsed) {
                            if (logoText) logoText.classList.add('text-expanded');
                            navTexts.forEach(el => {
                                el.classList.remove('text-collapsed');
                                el.classList.add('text-expanded');
                            });
                        }
                    }, 150);
                }
            });
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if(dropdown.classList.contains('hidden')) {
                // Close others first
                document.querySelectorAll('.absolute.z-50').forEach(el => el.classList.add('hidden'));
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#school_custom_btn') && !e.target.closest('#school_dropdown_list')) {
                document.getElementById('school_dropdown_list')?.classList.add('hidden');
            }
            if (!e.target.closest('#department_custom_btn') && !e.target.closest('#department_dropdown_list')) {
                document.getElementById('department_dropdown_list')?.classList.add('hidden');
            }
        });

        function selectSchool(id, text) {
            document.getElementById('school_id_input').value = id;
            document.getElementById('school_display_text').textContent = text;
            document.getElementById('school_dropdown_list').classList.add('hidden');
            updateDepartments();
        }

        function selectDepartment(id, text) {
            document.getElementById('department_id_input').value = id;
            document.getElementById('department_display_text').textContent = text;
            document.getElementById('department_dropdown_list').classList.add('hidden');
        }

        function updateDepartments() {
            let schoolId = document.getElementById('school_id_input').value;
            let departmentDisplay = document.getElementById('department_display_text');
            let departmentListInner = document.getElementById('department_dropdown_list_inner');
            let departmentInput = document.getElementById('department_id_input');
            let departmentBtn = document.getElementById('department_custom_btn');
            let selectedDeptId = "{{ old('department_id', $preselectedDepartmentId ?? null) }}";
            
            if(schoolId) {
                departmentDisplay.textContent = 'Loading departments...';
                departmentBtn.classList.add('bg-gray-50', 'cursor-not-allowed');
                departmentBtn.disabled = true;
                
                fetch('/schools/' + schoolId + '/departments')
                    .then(response => response.json())
                    .then(data => {
                        departmentDisplay.textContent = 'Select Department';
                        departmentBtn.classList.remove('bg-gray-50', 'cursor-not-allowed');
                        departmentBtn.disabled = false;
                        
                        let html = `<div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" onclick="selectDepartment('', 'Select Department')">Select Department</div>`;
                        
                        data.forEach(dept => {
                            let titleCaseName = dept.name.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                            let truncatedName = titleCaseName.length > 35 ? titleCaseName.substring(0, 32) + '...' : titleCaseName;
                            
                            if (dept.id == selectedDeptId) {
                                departmentInput.value = dept.id;
                                departmentDisplay.textContent = truncatedName;
                            }
                            
                            html += `<div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" title="${dept.name}" onclick="selectDepartment('${dept.id}', '${truncatedName.replace(/'/g, "\\'")}')">${truncatedName}</div>`;
                        });
                        
                        departmentListInner.innerHTML = html;
                    });
            } else {
                departmentDisplay.textContent = 'Select Department';
                departmentInput.value = '';
                departmentBtn.classList.add('bg-gray-50', 'cursor-not-allowed');
                departmentBtn.disabled = true;
                departmentListInner.innerHTML = `<div class="px-3 py-2 cursor-pointer hover:bg-indigo-50 rounded text-sm truncate" onclick="selectDepartment('', 'Select Department')">Select Department</div>`;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            // Pre-select school if it has an initial value
            const initialSchoolId = document.getElementById('school_id_input').value;
            if (initialSchoolId) {
                // Find the display text from the dropdown list
                const option = document.querySelector(`#school_dropdown_list div[onclick*="'${initialSchoolId}'"]`);
                if (option) {
                    document.getElementById('school_display_text').textContent = option.textContent.trim();
                }
                updateDepartments();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>


