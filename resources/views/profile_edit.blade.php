<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof window.initLMSUI === 'function') {
                window.initLMSUI();
            }
            @if($errors->has('password'))
                if (window.openPwdModal) window.openPwdModal();
            @endif
        });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 bg-slate-50/60 dark:bg-[#0D1117] flex flex-col">
            <div class="max-w-4xl mx-auto w-full flex-1">
                
                <div class="mb-5 flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-[#1C2430]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Back to Dashboard</span>
                    </a>
                </div>

                <!-- Form Section -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] p-6 sm:p-8">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-6 border-b border-slate-100 dark:border-[#273244] gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-[#F8FAFC] tracking-tight">Personal Information</h2>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-[#94A3B8]">Update your details and how others see you on the platform.</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 self-start sm:self-center">
                            <span class="text-red-500 font-bold">*</span> Required fields
                        </span>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                            
                            <!-- First Name -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="e.g. Rajesh" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Middle Name <span class="text-[11px] font-normal text-slate-400 lowercase">(optional)</span>
                                </label>
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->profile->middle_name ?? '') }}" placeholder="Optional" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="e.g. Sharma" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Email Address
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="name@vignan.ac.in" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Phone Number
                                </label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile->phone ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit number" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl text-sm font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-[#151B23] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all font-mono">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Photo Upload -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    Profile Photo <span class="text-[11px] font-normal text-slate-400 dark:text-slate-400 lowercase">(only .webp, max 2MB)</span>
                                </label>
                                <div class="flex items-center gap-3 px-3.5 py-1.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl">
                                    <div class="relative shrink-0">
                                        @if(!empty($user->photo))
                                            <img id="avatarPreview" src="{{ asset('storage/' . $user->photo) }}" alt="Avatar" class="w-8 h-8 rounded-lg object-cover border border-slate-200 dark:border-[#273244]">
                                        @else
                                            <div id="avatarFallback" class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-xs" style="background: var(--theme-gradient, linear-gradient(135deg, #4F46E5, #7C3AED));">
                                                {{ substr($user->first_name ?: $user->username, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="photo" id="photoInput" accept=".webp,image/webp" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950/60 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/60 cursor-pointer">
                                </div>
                                @error('photo')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- System Information Section -->
                            <div class="col-span-1 md:col-span-2 mt-2 pt-6 border-t border-slate-100 dark:border-[#273244]">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        <span>System Information</span>
                                    </h3>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/50 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                        {{ ['sa' => 'Super Admin', 'ssh_admin' => 'SSH Admin', 'admin' => 'Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'Faculty', 'stu' => 'Student'][$user->role] ?? ucfirst($user->role) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Username Card -->
                                    <div class="p-4 bg-slate-50 dark:bg-[#1C2430] rounded-xl border border-slate-200/80 dark:border-[#273244] shadow-xs flex items-center gap-3.5 transition-all hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Username</p>
                                            <p class="text-sm font-bold text-slate-800 dark:text-[#F8FAFC] font-mono truncate mt-0.5">{{ $user->username }}</p>
                                        </div>
                                    </div>

                                    <!-- School Card -->
                                    <div class="p-4 bg-slate-50 dark:bg-[#1C2430] rounded-xl border border-slate-200/80 dark:border-[#273244] shadow-xs flex items-center gap-3.5 transition-all hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-900/50 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">School</p>
                                            <p class="text-sm font-bold text-slate-800 dark:text-[#F8FAFC] truncate mt-0.5">{{ $user->school->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <!-- Department Card -->
                                    <div class="p-4 bg-slate-50 dark:bg-[#1C2430] rounded-xl border border-slate-200/80 dark:border-[#273244] shadow-xs flex items-center gap-3.5 transition-all hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 flex items-center justify-center shrink-0 shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Department</p>
                                            <p class="text-sm font-bold text-slate-800 dark:text-[#F8FAFC] truncate mt-0.5">{{ $user->department->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-[#273244] flex items-center justify-end gap-3">
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all text-center">
                                Cancel
                            </a>
                            <button type="submit" class="btn-theme-primary font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-indigo-600/20 active:scale-95 flex items-center gap-2 cursor-pointer text-xs sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Save Profile Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            
            </div>
            
            <footer class="mt-12 pt-4 pb-2">
                <p class="text-center text-xs text-slate-400 dark:text-slate-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-slate-600 dark:text-slate-400">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-slate-200 dark:border-[#273244]">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-[#273244] flex justify-between items-center bg-indigo-50/50 dark:bg-[#1C2430]">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Change Password</h3>
                <button type="button" id="closePasswordModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="modal_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">New Password</label>
                            <div class="relative">
                                <input type="password" id="modal_password" name="password" placeholder="Enter new password" class="w-full pl-4 pr-12 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-slate-100 text-sm transition-all" required>
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
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="modal_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="modal_password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="w-full pl-4 pr-12 py-2.5 bg-slate-50 dark:bg-[#1C2430] border border-slate-200 dark:border-[#273244] rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 dark:text-slate-100 text-sm transition-all" required>
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
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none transition-colors w-full sm:w-auto">Cancel</button>
                        <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 focus:outline-none shadow-md shadow-indigo-600/20 w-full sm:w-auto">Save Changes</button>
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
