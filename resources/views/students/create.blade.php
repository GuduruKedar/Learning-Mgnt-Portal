<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student - LMS</title>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/60 pb-36">
            <div class="max-w-6xl mx-auto space-y-6">
                
                <!-- Page Breadcrumbs & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Add New Student</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Enroll and register a student account with academic division, department, and degree level.</p>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-100 text-orange-700 text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                            Student Enrollment Setup
                        </span>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80">
                    <div class="px-6 py-4 border-b border-slate-100 bg-white flex items-center justify-between rounded-t-2xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Student Profile & Academic Placement</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-400"><span class="text-red-500">*</span> Required fields</span>
                    </div>

                    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        
                        <!-- Personal Information Grid (3 Columns) -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" required>
                                    @error('first_name')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name (optional)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                    @error('middle_name')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" required>
                                    @error('last_name')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Credentials & Contact Grid (3 Columns) -->
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Credentials & Contact Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Registration Number <span class="text-red-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" maxlength="10" minlength="10" placeholder="e.g. 241FA04001" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all uppercase font-mono" required>
                                    @error('username')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. 241fa04001@vignan.ac.in" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit mobile" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-mono">
                                    @error('phone_number')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Placement & Level Grid (3 Columns) -->
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Academic Placement & Program</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                @if(Auth::user()->role === 'sa')
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School <span class="text-red-500">*</span></label>
                                    <select name="school_id" id="school_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" required>
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department <span class="text-red-500">*</span></label>
                                    <select name="department_id" id="department_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" data-school-id="{{ optional($dept->school)->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Degree Level</label>
                                    <select name="level" id="level_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                        <option value="">Select Level</option>
                                        <option value="UG" {{ old('level') == 'UG' ? 'selected' : '' }}>UG</option>
                                        <option value="PG" {{ old('level') == 'PG' ? 'selected' : '' }}>PG</option>
                                        <option value="Diploma" {{ old('level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="PhD" {{ old('level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                    @error('level')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                @else
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School</label>
                                    <input type="text" value="{{ Auth::user()->profile->school->name ?? 'N/A' }}" class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed" disabled>
                                    <input type="hidden" name="school_id" value="{{ Auth::user()->profile->school->id ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department</label>
                                    <input type="text" value="{{ Auth::user()->profile->department->name ?? 'N/A' }}" class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed" disabled>
                                    <input type="hidden" name="department_id" id="department_select" value="{{ Auth::user()->profile->department->id ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Degree Level</label>
                                    <select name="level" id="level_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                        <option value="">Select Level</option>
                                        <option value="UG" {{ old('level') == 'UG' ? 'selected' : '' }}>UG</option>
                                        <option value="PG" {{ old('level') == 'PG' ? 'selected' : '' }}>PG</option>
                                        <option value="Diploma" {{ old('level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="PhD" {{ old('level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                    @error('level')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Photo & Security (3 Columns) -->
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Photo & Account Security</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Profile Photo</label>
                                    <input type="file" name="photo" accept="image/*" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition-all">
                                    @error('photo')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Initial Password</label>
                                    <input type="password" name="password" placeholder="Defaults to Student#963 if blank" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                    <p class="text-xs text-slate-400 mt-1">Leave blank to use default initial password (Student#963).</p>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('students.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-all shadow-xs">Cancel</a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-md transition-all">Enroll Student</button>
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


    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
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
