<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - LMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar logic        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 bg-gray-50/50 flex flex-col">
            <div class="max-w-4xl mx-auto w-full flex-1">
                
                <div class="mb-6">
                    <a href="{{ route('dashboard') }}" class="text-theme-primary hover-theme-text text-sm font-semibold inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Form Section -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] p-6 sm:p-10">
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-[#F8FAFC]">Personal Information</h2>
                        <p class="text-sm text-slate-500 dark:text-[#94A3B8]">Update your details and how others see you on the platform.</p>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                            
                            <!-- First Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">First Name <span class="text-indigo-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->profile->middle_name ?? '') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Last Name <span class="text-indigo-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile->phone ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit number" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Photo Upload -->
                            <div class="space-y-1.5">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Profile Photo <span class="text-xs font-normal text-slate-500">(Only .webp, Max 2MB)</span></label>
                                <input type="file" name="photo" accept=".webp,image/webp" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#1C2430] border border-gray-200 dark:border-[#273244] rounded-xl focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                @error('photo')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 mt-4 pt-6 border-t border-gray-100">
                                <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">System Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Username</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->username }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">School</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->school->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                                        <p class="text-xs text-gray-500 font-semibold uppercase mb-1">Department</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $user->department->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-[#273244] flex justify-end">
                            <button type="submit" class="btn-theme-primary font-bold py-3 px-8 rounded-xl transition-all transform hover:-translate-y-0.5 w-full sm:w-auto">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            
            </div>
            
            <footer class="mt-12 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700">TD</span>.</p>
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
                            <label for="modal_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="modal_password" name="password" placeholder="Enter new password" class="w-full pl-4 pr-12 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-slate-100 text-sm transition-all" required>
                                <button type="button" onclick="toggleFieldVisibility('modal_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-200/50 dark:hover:bg-slate-700/50 transition-colors focus:outline-none" aria-label="Toggle password visibility">
                                    <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="modal_password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="modal_password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="w-full pl-4 pr-12 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-slate-100 text-sm transition-all" required>
                                <button type="button" onclick="toggleFieldVisibility('modal_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-200/50 dark:hover:bg-slate-700/50 transition-colors focus:outline-none" aria-label="Toggle confirm password visibility">
                                    <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-auto">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-md shadow-indigo-600/20 w-full sm:w-auto">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>
</html>




