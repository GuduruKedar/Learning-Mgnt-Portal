<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/60 pb-36">
            <div class="max-w-6xl mx-auto space-y-6">
                
                <!-- Page Breadcrumbs & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Add New Faculty</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Register a faculty member to manage subject curriculum, materials, and course assignments.</p>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                            Faculty Registration Setup
                        </span>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80">
                    <div class="px-6 py-4 border-b border-slate-100 bg-white flex items-center justify-between rounded-t-2xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Faculty Account & Departmental Assignment</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-400"><span class="text-red-500">*</span> Required fields</span>
                    </div>

                    <form id="staff_create_form" action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        
                        <!-- Personal Info Grid (3 Columns) -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" required>
                                    @error('first_name')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name (optional)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                                    @error('middle_name')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" required>
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
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee ID / Username <span class="text-red-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" placeholder="e.g. 10001" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono" required>
                                    <p class="text-[11px] text-slate-400 mt-1">Must be exactly 5 digits (e.g. <span class="font-mono text-teal-600 font-semibold">10001</span>). Only 5 digits are accepted.</p>
                                    @error('username')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                        <span>Email Address</span>
                                        <span id="email_status_pill" class="hidden text-[10px] font-semibold px-2 py-0.5 rounded-full transition-all"></span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" id="staff_email" name="email" value="{{ old('email') }}" placeholder="name@vignan.ac.in" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-medium">
                                        <input type="hidden" name="confirm_duplicate_email" id="confirm_duplicate_email" value="{{ old('confirm_duplicate_email', '0') }}">
                                        <div id="email_spinner" class="hidden absolute right-3 top-3 text-slate-400">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Live Duplicate Email Warning / Confirmation -->
                                    <div id="duplicate_email_alert" class="hidden mt-2 p-3 bg-amber-50/90 border border-amber-200 rounded-xl text-xs text-amber-900 shadow-2xs">
                                        <div class="flex items-start gap-2.5">
                                            <div class="p-1 bg-amber-100 rounded-lg text-amber-700 shrink-0 mt-0.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-amber-900 flex items-center justify-between">
                                                    <span>Email already registered</span>
                                                    <span id="dup_confirmed_tag" class="hidden text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full border border-emerald-200">✓ Confirmed to reuse</span>
                                                </div>
                                                <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                                    Belongs to <strong id="dup_existing_name" class="font-semibold text-slate-900"></strong> 
                                                    (<span id="dup_existing_role"></span><span id="dup_existing_dept_wrapper"> • <span id="dup_existing_dept"></span></span>).
                                                </p>
                                                <div class="mt-2 flex items-center gap-2" id="dup_action_buttons">
                                                    <button type="button" onclick="confirmDuplicateEmailUse()" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-[11px] transition-colors shadow-2xs cursor-pointer">
                                                        Use same email
                                                    </button>
                                                    <button type="button" onclick="promptChangeEmail()" class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-lg font-semibold text-[11px] transition-colors cursor-pointer">
                                                        Enter another email
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(session('duplicate_email_warning'))
                                    <div class="mt-2 p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900">
                                        <p class="font-bold mb-1">⚠️ Email Already In Use</p>
                                        <p class="text-[11px] mb-2 leading-relaxed">The email <strong>{{ session('duplicate_email_warning')['email'] }}</strong> is currently used by <strong>{{ session('duplicate_email_warning')['name'] }}</strong> ({{ session('duplicate_email_warning')['role'] }}). Would you like to use this same email or change it?</p>
                                        <div class="flex items-center gap-2">
                                            <button type="button" onclick="confirmAndSubmitDuplicateEmail()" class="px-3 py-1 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg text-xs cursor-pointer">
                                                Yes, Use This Email
                                            </button>
                                            <button type="button" onclick="promptChangeEmail()" class="px-3 py-1 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold rounded-lg text-xs cursor-pointer">
                                                Enter Another Email
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit mobile" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono">
                                    @error('phone_number')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Placement & Designation (3 Columns) -->
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Academic Placement & Role</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                @if(Auth::user()->role === 'sa')
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School <span class="text-red-500">*</span></label>
                                    <select name="school_id" id="school_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" required>
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
                                    <select name="department_id" id="department_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" data-school-id="{{ optional($dept->school)->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                @elseif(Auth::user()->role === 'admin')
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">School <span class="text-red-500">*</span></label>
                                    <select id="school_select_fixed" class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed" disabled>
                                        @php
                                            $adminSchoolCode = Auth::user()->profile->schools_id ?? '';
                                            $adminSchool = $schools->where('code', $adminSchoolCode)->first() ?? $schools->where('id', $adminSchoolCode)->first();
                                        @endphp
                                        <option value="{{ $adminSchool->id ?? '' }}" selected>{{ $adminSchool->name ?? 'Unknown School' }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department <span class="text-red-500">*</span></label>
                                    <select id="department_select_fixed" class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 text-sm cursor-not-allowed" disabled>
                                        @php
                                            $adminDeptCode = Auth::user()->profile->departments_id ?? '';
                                            $adminDept = $departments->where('code', $adminDeptCode)->first() ?? $departments->where('id', $adminDeptCode)->first();
                                        @endphp
                                        <option value="{{ $adminDept->id ?? '' }}" selected>{{ $adminDept->name ?? 'Unknown Department' }}</option>
                                    </select>
                                </div>
                                @else
                                <input type="hidden" name="school_id" value="{{ Auth::user()->profile->school_id }}">
                                <input type="hidden" name="department_id" value="{{ Auth::user()->profile->department_id }}">
                                @endif

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Designation</label>
                                    <input type="text" name="designation" value="{{ old('designation') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" placeholder="e.g. Assistant Professor, Lecturer">
                                    @error('designation')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Account Security</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Initial Password</label>
                                    <input type="password" name="password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all" placeholder="Defaults to Staff@852 if left blank">
                                    <p class="text-xs text-slate-400 mt-1">Leave blank to use default secure initial password (Staff@852).</p>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-t border-slate-100 dark:border-slate-800 pt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('staff.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white transition-all shadow-xs">Cancel</a>
                            <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 transition-all cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Save Faculty Member</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">Technology Development (TD)</span>.</p>
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

    <!-- Duplicate Email Confirmation Modal -->
    <div id="duplicateEmailModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all duration-200" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all p-6">
            <div class="flex items-center gap-3.5 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900" id="modal-title">Email Already In Use</h3>
                    <p class="text-xs text-slate-500">Duplicate email address detected</p>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <p class="text-xs text-slate-600 leading-relaxed">
                    The email address <span id="modal_dup_email" class="font-mono font-semibold text-teal-600"></span> is already registered in the LMS for:
                </p>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Name:</span>
                        <span id="modal_dup_name" class="font-bold text-slate-900"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Role:</span>
                        <span id="modal_dup_role" class="px-2 py-0.5 rounded text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-100"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Username / ID:</span>
                        <span id="modal_dup_username" class="font-mono text-slate-800 font-semibold"></span>
                    </div>
                    <div class="flex items-center justify-between" id="modal_dup_dept_row">
                        <span class="text-slate-500 font-medium">Department:</span>
                        <span id="modal_dup_dept" class="text-slate-800 font-medium"></span>
                    </div>
                </div>

                <div class="p-3 bg-teal-50/70 border border-teal-100 rounded-xl">
                    <p class="text-xs text-teal-950 font-medium leading-relaxed">
                        Would you like to assign this <strong>same email address</strong> to this faculty member, or enter a different email?
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeDuplicateModalAndChangeEmail()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors cursor-pointer">
                    Enter Different Email
                </button>
                <button type="button" onclick="confirmDuplicateModalAndSubmit()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-600/20 transition-all cursor-pointer">
                    Yes, Use Same Email
                </button>
            </div>
        </div>
    </div>

    <script>
        // Duplicate Email Verification Logic for Staff
        let currentEmailCheck = { email: '', exists: false, userData: null, confirmed: false };
        const emailInput = document.getElementById('staff_email');
        const confirmDupInput = document.getElementById('confirm_duplicate_email');
        const dupAlert = document.getElementById('duplicate_email_alert');
        const dupConfirmedTag = document.getElementById('dup_confirmed_tag');
        const dupActionButtons = document.getElementById('dup_action_buttons');
        const dupNameEl = document.getElementById('dup_existing_name');
        const dupRoleEl = document.getElementById('dup_existing_role');
        const dupDeptEl = document.getElementById('dup_existing_dept');
        const dupDeptWrapper = document.getElementById('dup_existing_dept_wrapper');
        const emailSpinner = document.getElementById('email_spinner');
        const emailStatusPill = document.getElementById('email_status_pill');
        const dupModal = document.getElementById('duplicateEmailModal');
        const createForm = document.getElementById('staff_create_form');

        let emailCheckTimeout = null;

        function checkEmailAvailability(emailVal, callback) {
            emailVal = (emailVal || '').trim();
            if (!emailVal || !emailVal.includes('@')) {
                hideDuplicateAlert();
                currentEmailCheck = { email: emailVal, exists: false, userData: null, confirmed: false };
                confirmDupInput.value = '0';
                if (callback) callback(false);
                return;
            }

            if (emailSpinner) emailSpinner.classList.remove('hidden');

            fetch('/users/check-email?email=' + encodeURIComponent(emailVal))
                .then(res => res.json())
                .then(data => {
                    if (emailSpinner) emailSpinner.classList.add('hidden');
                    if (data && data.exists) {
                        currentEmailCheck = {
                            email: emailVal,
                            exists: true,
                            userData: data,
                            confirmed: confirmDupInput.value === '1'
                        };
                        showDuplicateAlert(data);
                        if (callback) callback(true, data);
                    } else {
                        currentEmailCheck = { email: emailVal, exists: false, userData: null, confirmed: false };
                        confirmDupInput.value = '0';
                        hideDuplicateAlert();
                        if (callback) callback(false);
                    }
                })
                .catch(err => {
                    if (emailSpinner) emailSpinner.classList.add('hidden');
                    console.error('Email check error:', err);
                    if (callback) callback(false);
                });
        }

        function showDuplicateAlert(userData) {
            if (!dupAlert) return;
            dupNameEl.textContent = userData.name || userData.username;
            dupRoleEl.textContent = userData.role || 'User';
            if (userData.department) {
                dupDeptEl.textContent = userData.department;
                dupDeptWrapper.classList.remove('hidden');
            } else {
                dupDeptWrapper.classList.add('hidden');
            }

            if (confirmDupInput.value === '1') {
                dupConfirmedTag.classList.remove('hidden');
                dupActionButtons.classList.add('hidden');
                if (emailStatusPill) {
                    emailStatusPill.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                    emailStatusPill.textContent = 'Shared Email Confirmed';
                    emailStatusPill.classList.remove('hidden');
                }
            } else {
                dupConfirmedTag.classList.add('hidden');
                dupActionButtons.classList.remove('hidden');
                if (emailStatusPill) {
                    emailStatusPill.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200';
                    emailStatusPill.textContent = 'Email in use';
                    emailStatusPill.classList.remove('hidden');
                }
            }
            dupAlert.classList.remove('hidden');
        }

        function hideDuplicateAlert() {
            if (dupAlert) dupAlert.classList.add('hidden');
            if (emailStatusPill) emailStatusPill.classList.add('hidden');
        }

        function confirmDuplicateEmailUse() {
            confirmDupInput.value = '1';
            currentEmailCheck.confirmed = true;
            if (currentEmailCheck.userData) {
                showDuplicateAlert(currentEmailCheck.userData);
            }
        }

        function promptChangeEmail() {
            confirmDupInput.value = '0';
            currentEmailCheck.confirmed = false;
            hideDuplicateAlert();
            if (emailInput) {
                emailInput.focus();
                emailInput.select();
            }
        }

        function openDuplicateModal(data) {
            document.getElementById('modal_dup_email').textContent = emailInput.value;
            document.getElementById('modal_dup_name').textContent = data.name || data.username;
            document.getElementById('modal_dup_role').textContent = data.role || 'User';
            document.getElementById('modal_dup_username').textContent = data.username || 'N/A';
            
            const deptRow = document.getElementById('modal_dup_dept_row');
            if (data.department) {
                document.getElementById('modal_dup_dept').textContent = data.department;
                deptRow.classList.remove('hidden');
            } else {
                deptRow.classList.add('hidden');
            }

            dupModal.classList.remove('hidden');
        }

        function closeDuplicateModalAndChangeEmail() {
            dupModal.classList.add('hidden');
            promptChangeEmail();
        }

        function confirmDuplicateModalAndSubmit() {
            confirmDupInput.value = '1';
            currentEmailCheck.confirmed = true;
            dupModal.classList.add('hidden');
            createForm.submit();
        }

        function confirmAndSubmitDuplicateEmail() {
            confirmDupInput.value = '1';
            createForm.submit();
        }

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                confirmDupInput.value = '0';
                currentEmailCheck.confirmed = false;
                clearTimeout(emailCheckTimeout);
                emailCheckTimeout = setTimeout(() => {
                    checkEmailAvailability(this.value);
                }, 500);
            });

            emailInput.addEventListener('blur', function() {
                clearTimeout(emailCheckTimeout);
                checkEmailAvailability(this.value);
            });
        }

        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                const emailVal = (emailInput?.value || '').trim();
                if (confirmDupInput.value === '1' || !emailVal) {
                    return true;
                }

                if (currentEmailCheck.exists && currentEmailCheck.email === emailVal) {
                    e.preventDefault();
                    openDuplicateModal(currentEmailCheck.userData);
                    return false;
                }

                e.preventDefault();
                checkEmailAvailability(emailVal, function(exists, userData) {
                    if (exists) {
                        openDuplicateModal(userData);
                    } else {
                        confirmDupInput.value = '0';
                        createForm.submit();
                    }
                });
            });
        }

        if (emailInput && emailInput.value) {
            checkEmailAvailability(emailInput.value);
        }
    </script>

</body>
</html>
