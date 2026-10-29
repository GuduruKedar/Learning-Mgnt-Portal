<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coordinator - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>

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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="mb-6 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Coordinator: {{ $coordinator->first_name }} {{ $coordinator->last_name }}</h1>
                    <a href="{{ route('coordinators.departments_list') }}" class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg shadow-sm transition-all duration-200 ease-in-out text-sm">
                        &larr; Back
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form id="coordinator_edit_form" action="{{ route('coordinators.update', $coordinator->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $coordinator->first_name) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $coordinator->last_name) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee ID / Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" value="{{ old('username', $coordinator->username) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono" required>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                    <span>Email Address</span>
                                    <span id="email_status_pill" class="hidden text-[10px] font-semibold px-2 py-0.5 rounded-full transition-all"></span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="coordinator_email" name="email" value="{{ old('email', $coordinator->email) }}" placeholder="name@vignan.ac.in" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium">
                                    <input type="hidden" name="confirm_duplicate_email" id="confirm_duplicate_email" value="{{ old('confirm_duplicate_email', '0') }}">
                                    <div id="email_spinner" class="hidden absolute right-3 top-3 text-slate-400">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div id="duplicate_email_alert" class="hidden mt-2 p-3 bg-amber-50/90 border border-amber-200 rounded-xl text-xs text-amber-900 shadow-2xs">
                                    <div class="flex items-start gap-2.5">
                                        <div class="p-1 bg-amber-100 rounded-lg text-amber-700 shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-amber-900 flex items-center justify-between">
                                                <span>Email in use by another user</span>
                                                <span id="dup_confirmed_tag" class="hidden text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full border border-emerald-200">✓ Confirmed to reuse</span>
                                            </div>
                                            <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                                Registered to <strong id="dup_existing_name" class="font-semibold text-slate-900"></strong> 
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

                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $coordinator->phone_number) }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit phone number" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Password <span class="text-xs text-slate-400 font-normal lowercase">(leave blank to keep current)</span></label>
                                <div class="relative">
                                    <input type="password" id="coordinator_password" name="password" autocomplete="new-password" class="w-full pl-3.5 password-input-with-actions py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Enter new password">
                                    <div class="password-actions-group">
                                        <button type="button" onclick="setCoordinatorDefaultPassword()" class="password-default-btn text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800/60 shadow-2xs" title="Auto-fill default password Admin!741">
                                            Use Default
                                        </button>
                                        <button type="button" onclick="toggleCoordinatorPassword()" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-200/50 dark:hover:bg-slate-700/50 transition-colors focus:outline-none cursor-pointer" aria-label="Toggle password visibility">
                                            <svg id="coordPwdEyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg id="coordPwdEyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5 flex-wrap">
                                    <span>Default password is <code class="font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded border border-indigo-200 dark:border-indigo-800/60">Admin!741</code>. If changing, must be at least 8 characters with a symbol (e.g. @, #, !).</span>
                                </p>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Academic Placement Card (Full-width, single grid layout) -->
                            <div class="md:col-span-2 bg-slate-50/80 dark:bg-[#1C2430]/60 border border-slate-200/90 dark:border-[#273244] rounded-2xl p-4 sm:p-5 space-y-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-bold text-xs border border-indigo-200/60 dark:border-indigo-800/60">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Academic Affiliation & Placement</h3>
                                </div>

                                <div class="grid grid-cols-1 gap-3.5">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">School / Faculty</label>
                                        <div class="w-full px-3.5 py-2.5 bg-white dark:bg-[#151B23] border border-slate-200 dark:border-[#273244] rounded-xl text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug break-words shadow-2xs">
                                            {{ optional($coordinator->profile->school)->name ?? 'N/A' }}
                                        </div>
                                        <input type="hidden" name="school_id" value="{{ optional($coordinator->profile->school)->id }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Department</label>
                                        <div class="w-full px-3.5 py-2.5 bg-white dark:bg-[#151B23] border border-slate-200 dark:border-[#273244] rounded-xl text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug break-words shadow-2xs">
                                            {{ optional($coordinator->profile->department)->name ?? 'N/A' }}
                                        </div>
                                        <input type="hidden" name="department_id" value="{{ optional($coordinator->profile->department)->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow transition-colors">Update Coordinator</button>
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

        function toggleCoordinatorPassword() {
            const input = document.getElementById('coordinator_password');
            const openIcon = document.getElementById('coordPwdEyeOpen');
            const closedIcon = document.getElementById('coordPwdEyeClosed');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                if (openIcon) openIcon.classList.add('hidden');
                if (closedIcon) closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                if (openIcon) openIcon.classList.remove('hidden');
                if (closedIcon) closedIcon.classList.add('hidden');
            }
        }

        function setCoordinatorDefaultPassword() {
            const input = document.getElementById('coordinator_password');
            if (!input) return;
            input.value = 'Admin!741';
            input.type = 'text';
            const openIcon = document.getElementById('coordPwdEyeOpen');
            const closedIcon = document.getElementById('coordPwdEyeClosed');
            if (openIcon) openIcon.classList.add('hidden');
            if (closedIcon) closedIcon.classList.remove('hidden');
            input.focus();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

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
                    The email address <span id="modal_dup_email" class="font-mono font-semibold text-indigo-600"></span> is already registered in the LMS for:
                </p>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Name:</span>
                        <span id="modal_dup_name" class="font-bold text-slate-900"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Role:</span>
                        <span id="modal_dup_role" class="px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100"></span>
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

                <div class="p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl">
                    <p class="text-xs text-indigo-950 font-medium leading-relaxed">
                        Would you like to assign this <strong>same email address</strong> to this coordinator, or enter a different email?
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeDuplicateModalAndChangeEmail()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors cursor-pointer">
                    Enter Different Email
                </button>
                <button type="button" onclick="confirmDuplicateModalAndSubmit()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                    Yes, Use Same Email
                </button>
            </div>
        </div>
    </div>

    <script>
        const ignoreProfileId = "{{ $coordinator->profile_id }}";
        let currentEmailCheck = { email: '', exists: false, userData: null, confirmed: false };
        const emailInput = document.getElementById('coordinator_email');
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
        const editForm = document.getElementById('coordinator_edit_form');

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

            fetch('/coordinators/check-email?email=' + encodeURIComponent(emailVal) + '&ignore_id=' + encodeURIComponent(ignoreProfileId))
                .then(res => res.json())
                .then(data => {
                    if (emailSpinner) emailSpinner.classList.add('hidden');
                    if (data && data.exists) {
                        currentEmailCheck = { email: emailVal, exists: true, userData: data, confirmed: confirmDupInput.value === '1' };
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
            editForm.submit();
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

        if (editForm) {
            editForm.addEventListener('submit', function(e) {
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
                        editForm.submit();
                    }
                });
            });
        }
    </script>

</body>
</html>

