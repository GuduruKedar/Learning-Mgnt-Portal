<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
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
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="mb-6">
                    <a href="{{ route('staff.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2 inline-flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>Back to Staff</a>
                    <h1 class="text-2xl font-bold text-gray-900">Add New Staff</h1>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="Enter first name" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="Enter middle name">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="Enter last name" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Employee ID / Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="Enter employee ID" required>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="Enter email address">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="10-digit phone number">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if(Auth::user()->role === 'sa')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">School <span class="text-red-500">*</span></label>
                                <select name="school_id" id="school_select" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-500">*</span></label>
                                <select name="department_id" id="department_select" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" data-school-id="{{ optional($dept->school)->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            @elseif(Auth::user()->role === 'admin')
                            <!-- Show School and Department Fixed (Hidden inputs removed to prevent validation errors) -->
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">School <span class="text-red-500">*</span></label>
                                <select id="school_select_fixed" class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed" disabled>
                                    @php
                                        $adminSchoolCode = Auth::user()->profile->schools_id ?? '';
                                        $adminSchool = $schools->where('code', $adminSchoolCode)->first() ?? $schools->where('id', $adminSchoolCode)->first();
                                    @endphp
                                    <option value="{{ $adminSchool->id ?? '' }}" selected>{{ $adminSchool->name ?? 'Unknown School' }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-500">*</span></label>
                                <select id="department_select_fixed" class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed" disabled>
                                    @php
                                        $adminDeptCode = Auth::user()->profile->departments_id ?? '';
                                        $adminDept = $departments->where('code', $adminDeptCode)->first() ?? $departments->where('id', $adminDeptCode)->first();
                                    @endphp
                                    <option value="{{ $adminDept->id ?? '' }}" selected>{{ $adminDept->name ?? 'Unknown Department' }}</option>
                                </select>
                            </div>
                            @else
                            <!-- Coordinator: Both Hidden, taken from auth user -->
                            <input type="hidden" name="school_id" value="{{ Auth::user()->profile->school_id }}">
                            <input type="hidden" name="department_id" value="{{ Auth::user()->profile->department_id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900 placeholder-gray-400" placeholder="e.g. Senior Lecturer">
                                @error('designation')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all duration-300 text-gray-900" placeholder="Defaults to Staff@852 if blank">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('staff.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 shadow-sm">Cancel</a>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 border border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-md shadow-teal-500/20 hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-0.5">Save Staff Member</button>
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
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelectEl = document.getElementById('school_select');
            const departmentSelectEl = document.getElementById('department_select');

            // 1. Capture all original department options if department_select is a SELECT element
            let allDepartments = [];
            const isDeptSelect = departmentSelectEl && departmentSelectEl.tagName === 'SELECT';
            if (isDeptSelect) {
                allDepartments = Array.from(departmentSelectEl.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => ({
                        value: opt.value,
                        text: opt.text,
                        schoolId: opt.getAttribute('data-school-id')
                    }));
            }

            // 2. Initialize TomSelect on all selects
            document.querySelectorAll('select').forEach(function(el) {
                if (!el.classList.contains('tomselected')) {
                    new TomSelect(el, {
                        create: false,
                        maxOptions: null,
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                }
            });

            // 3. Dynamic Department Filtering based on School Selection
            if (isDeptSelect && schoolSelectEl) {
                const schoolTs = schoolSelectEl.tomselect;
                const deptTs = departmentSelectEl.tomselect;

                function updateDepartmentOptions(selectedSchoolId, keepCurrentVal) {
                    const currentVal = keepCurrentVal 
                        ? (deptTs ? deptTs.getValue() : departmentSelectEl.value)
                        : '';

                    // Filter departments for selected school
                    const matchingDepts = selectedSchoolId 
                        ? allDepartments.filter(d => String(d.schoolId) === String(selectedSchoolId))
                        : [];

                    // Update native select DOM
                    departmentSelectEl.innerHTML = '<option value="">Select Department</option>';
                    matchingDepts.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.value;
                        opt.text = d.text;
                        opt.setAttribute('data-school-id', d.schoolId);
                        if (String(d.value) === String(currentVal)) {
                            opt.selected = true;
                        }
                        departmentSelectEl.appendChild(opt);
                    });

                    // Update TomSelect instance
                    if (deptTs) {
                        deptTs.clear(true);
                        deptTs.clearOptions();
                        matchingDepts.forEach(d => {
                            deptTs.addOption({
                                value: d.value,
                                text: d.text
                            });
                        });
                        if (currentVal && matchingDepts.some(d => String(d.value) === String(currentVal))) {
                            deptTs.setValue(currentVal, true);
                        } else {
                            deptTs.setValue('', true);
                        }
                        deptTs.refreshOptions(false);
                    }
                }

                // Listen to School changes
                if (schoolTs) {
                    schoolTs.on('change', function(val) {
                        updateDepartmentOptions(val, false);
                    });
                    const initialSchool = schoolTs.getValue();
                    if (initialSchool) {
                        updateDepartmentOptions(initialSchool, true);
                    } else {
                        updateDepartmentOptions('', false);
                    }
                } else {
                    schoolSelectEl.addEventListener('change', function() {
                        updateDepartmentOptions(this.value, false);
                    });
                    if (schoolSelectEl.value) {
                        updateDepartmentOptions(schoolSelectEl.value, true);
                    } else {
                        updateDepartmentOptions('', false);
                    }
                }
            }
        });
    </script>
</body>
</html>
