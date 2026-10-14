<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - LMS</title>
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
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-[#0d1117]">
            <div class="max-w-2xl mx-auto space-y-6">
                <div class="mb-2">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-semibold inline-flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </a>
                </div>
                
                <div class="flex items-center gap-4">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" class="h-16 w-16 object-cover rounded-2xl border-2 border-indigo-500 shadow-md">
                    @else
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-extrabold text-2xl shadow-md">
                            {{ substr($user->first_name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Change Password</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Update your account password securely.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm font-semibold shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center gap-3 text-rose-800 dark:text-rose-300 text-sm font-semibold shadow-xs">
                        <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <div class="pwd-card p-6 sm:p-8">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6 w-full">
                            <!-- Current Password -->
                            <div class="pwd-input-group">
                                <label for="current_password" class="block mb-1.5">Current Password</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" placeholder="Enter current password" class="pwd-input" required autocomplete="current-password">
                                    <button type="button" onclick="toggleFieldVisibility('current_password', this)" class="pwd-eye-btn" aria-label="Toggle current password visibility">
                                        <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="pwd-input-group">
                                <label for="password" class="block mb-1.5">New Password</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" placeholder="Enter new password" class="pwd-input" required autocomplete="new-password">
                                    <button type="button" onclick="toggleFieldVisibility('password', this)" class="pwd-eye-btn" aria-label="Toggle password visibility">
                                        <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2.5 flex items-center gap-4 flex-wrap">
                                    <span id="req-length" class="pwd-requirement-item">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        At least 8 characters
                                    </span>
                                    <span id="req-symbol" class="pwd-requirement-item">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        At least one symbol (e.g. @, #, !)
                                    </span>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div class="pwd-input-group">
                                <label for="password_confirmation" class="block mb-1.5">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="pwd-input" required autocomplete="new-password">
                                    <button type="button" onclick="toggleFieldVisibility('password_confirmation', this)" class="pwd-eye-btn" aria-label="Toggle confirm password visibility">
                                        <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p id="pwd-match-hint" class="text-xs mt-1.5 hidden"></p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-[#273244] flex items-center justify-between gap-4">
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="pwd-submit-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-gray-200 dark:border-gray-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <script>
        function toggleFieldVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const openIcon = btn.querySelector('.eye-open');
            const closedIcon = btn.querySelector('.eye-closed');
            if (openIcon && closedIcon) {
                if (isPassword) {
                    openIcon.classList.add('hidden');
                    closedIcon.classList.remove('hidden');
                    btn.setAttribute('aria-label', 'Hide password');
                } else {
                    openIcon.classList.remove('hidden');
                    closedIcon.classList.add('hidden');
                    btn.setAttribute('aria-label', 'Show password');
                }
            }
        }

        // Live validation indicators
        const pwdInput = document.getElementById('password');
        const confirmPwdInput = document.getElementById('password_confirmation');
        const reqLength = document.getElementById('req-length');
        const reqSymbol = document.getElementById('req-symbol');
        const matchHint = document.getElementById('pwd-match-hint');

        function updatePasswordRequirements() {
            if (!pwdInput) return;
            const val = pwdInput.value;
            
            if (reqLength) {
                if (val.length >= 8) {
                    reqLength.classList.add('valid');
                } else {
                    reqLength.classList.remove('valid');
                }
            }

            if (reqSymbol) {
                const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(val);
                if (hasSymbol) {
                    reqSymbol.classList.add('valid');
                } else {
                    reqSymbol.classList.remove('valid');
                }
            }

            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            if (!confirmPwdInput || !pwdInput || !matchHint) return;
            const p1 = pwdInput.value;
            const p2 = confirmPwdInput.value;

            if (p2.length === 0) {
                matchHint.classList.add('hidden');
                return;
            }

            matchHint.classList.remove('hidden');
            if (p1 === p2) {
                matchHint.textContent = 'Passwords match';
                matchHint.className = 'text-xs mt-1.5 font-semibold text-emerald-600 dark:text-emerald-400';
            } else {
                matchHint.textContent = 'Passwords do not match yet';
                matchHint.className = 'text-xs mt-1.5 font-medium text-amber-500 dark:text-amber-400';
            }
        }

        if (pwdInput) pwdInput.addEventListener('input', updatePasswordRequirements);
        if (confirmPwdInput) confirmPwdInput.addEventListener('input', checkPasswordMatch);

        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>



