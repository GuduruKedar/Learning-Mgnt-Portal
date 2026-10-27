<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management Portal</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script>
        // Ensure login page is always in clean normal light mode
        document.documentElement.classList.remove('dark');
    </script>
</head>
<body class="h-screen w-screen overflow-hidden flex items-center justify-center p-3 sm:p-6 lg:p-8 bg-image relative selection:bg-indigo-500 selection:text-white">

    <!-- Clear, Subtle Ambient Overlay to keep background image vibrant and crisp -->
    <div class="absolute inset-0 bg-slate-900/35 bg-gradient-to-b from-slate-950/40 via-slate-900/20 to-slate-950/45 z-0"></div>

    <!-- Dynamic ambient glow lights for premium depth -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>

    <!-- Main Split Container -->
    <div class="relative z-10 w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">

        <!-- =============================================================== -->
        <!-- LEFT SIDE: Live Interactive Student Portal Showcase               -->
        <!-- =============================================================== -->
        <div class="hidden lg:flex lg:col-span-7 flex-col justify-center text-white space-y-4 lg:pr-4">
            
            <!-- Header -->
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2.5 bg-white/15 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/25 shadow-sm">
                    <img src="{{ asset('images/vignan_shield.svg') }}" alt="Vignan Logo" class="w-7 h-7 object-contain drop-shadow-sm">
                    <span class="text-xs uppercase font-bold tracking-wider text-white">VFSTR • Vignan University</span>
                </div>
                <h1 class="heading-font text-2xl lg:text-3xl xl:text-4xl font-black tracking-tight text-white leading-tight">
                    Learning Management <span class="animated-gradient-text">Portal</span>
                </h1>
                
                <p class="text-slate-100/90 text-xs sm:text-sm leading-relaxed max-w-lg font-normal">
                    A unified digital campus platform empowering students with streamlined course allocations, instant study materials, and direct faculty connectivity.
                </p>
            </div>

            <!-- Live Interactive Feature Showcase Grid (Clean Light Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 select-none">
                
                <!-- Feature 1: Course Enrollment -->
                <div class="feature-card p-3 rounded-xl flex items-start gap-3 cursor-default">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-xs font-bold text-slate-900">Course Enrollment</h3>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Online
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                            Register for semester courses, electives, and core subjects seamlessly.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Study Materials -->
                <div class="feature-card p-3 rounded-xl flex items-start gap-3 cursor-default">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-sky-500 to-cyan-400 flex items-center justify-center shrink-0 shadow-md shadow-sky-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-xs font-bold text-slate-900">Study Materials</h3>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                24/7 Access
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                            Instant access to presentations, syllabus PDFs, and lecture notes.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Faculty Mentorship -->
                <div class="feature-card p-3 rounded-xl flex items-start gap-3 cursor-default">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-xs font-bold text-slate-900">Assigned Faculty</h3>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Direct Sync
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                            View allocated professors, mentors, and departmental teachers.
                        </p>
                    </div>
                </div>

                <!-- Feature 4: Civil Services Coaching -->
                <div class="feature-card p-3 rounded-xl flex items-start gap-3 cursor-default">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-amber-500 to-orange-400 flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-xs font-bold text-slate-900">Civil Services</h3>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                UPSC Track
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                            Integrated coaching modules, GS prep, and practice materials.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Feature 5 & Live Stats Bar (Combined Banner) -->
            <div class="feature-card select-none p-3 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 cursor-default">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-purple-600 to-fuchsia-500 flex items-center justify-center shrink-0 shadow-md shadow-purple-500/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-bold text-slate-900">Academic Progress & Tracking</h3>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Real-Time
                            </span>
                        </div>
                        <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                            Check registered curriculum, regulation requirements, and overall academic status.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- RIGHT SIDE: Login Authentication Card      -->
        <!-- ========================================== -->
        <div class="lg:col-span-5 w-full flex justify-center lg:justify-end">
            
            <div class="glass-panel p-5 sm:p-7 rounded-2xl w-full max-w-sm sm:max-w-md relative shadow-2xl overflow-hidden">
                
                <!-- Institutional Shield Watermark in Card Background -->
                <div class="card-watermark" aria-hidden="true">
                    <img src="{{ asset('images/vignan_shield.svg') }}" alt="" class="pointer-events-none select-none">
                </div>

                <div class="relative z-10">
                    <!-- Logo & Card Header -->
                    <div class="text-center mb-4 sm:mb-5">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mx-auto mb-2">
                            <img src="{{ asset('images/vignan_shield.svg') }}" alt="Vignan University Logo" class="w-full h-full object-contain drop-shadow-md">
                        </div>

                        <!-- University Badge -->
                        <div class="mb-1">
                            <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs uppercase font-bold tracking-wider text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                VFSTR • Vignan University
                            </span>
                        </div>

                        <!-- Card Heading: Learning Management Portal -->
                        <h2 class="heading-font text-lg sm:text-2xl font-bold text-slate-900 tracking-tight">
                            Learning Management <span class="text-indigo-600">Portal</span>
                        </h2>
                        <p class="text-slate-500 mt-0.5 font-medium text-xs sm:text-sm">Welcome Back! Please enter your credentials to continue.</p>
                    </div>

                    @if(session('forgot_success'))
                        <div class="mb-3.5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/90 text-emerald-800 text-xs flex items-start gap-2.5 animate-fadeIn">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="flex-1">
                                <span class="font-bold block">Password Reset Confirmed</span>
                                <p class="text-[11px] leading-relaxed mt-0.5 text-emerald-700">{{ session('forgot_success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('forgot_error'))
                        <div class="mb-3.5 p-3.5 rounded-xl bg-rose-50 border border-rose-200/90 text-rose-800 text-xs flex items-start gap-2.5 animate-fadeIn">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="flex-1">
                                <span class="font-bold block">Password Reset Notice</span>
                                <p class="text-[11px] leading-relaxed mt-0.5 text-rose-700">{{ session('forgot_error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('show_forgot_prompt'))
                        <div class="mb-3.5 p-3.5 rounded-xl bg-amber-50 border border-amber-300 text-amber-950 text-xs flex items-start gap-2.5 shadow-sm animate-fadeIn">
                            <div class="w-6 h-6 rounded-lg bg-amber-200/80 text-amber-900 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="font-bold block text-amber-950 text-xs">2 Failed Login Attempts</span>
                                <p class="text-[11px] leading-relaxed mt-0.5 text-amber-900 font-medium">
                                    You have entered an incorrect password 2 times. Please click 
                                    <button type="button" onclick="openForgotPasswordModal()" class="font-bold underline text-indigo-700 hover:text-indigo-900 cursor-pointer">
                                        Forgot Password?
                                    </button> 
                                    to reset your credentials via SMS or Email.
                                </p>
                            </div>
                        </div>
                    @endif

                <!-- Authentication Form -->
                <form method="POST" action="{{ route('authenticate') }}" class="space-y-3.5 sm:space-y-4">
                    @csrf
                    
                    <!-- Username Field -->
                    <div>
                        <label for="emp_id" class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input 
                                type="text" 
                                id="emp_id" 
                                name="emp_id" 
                                class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border {{ $errors->has('emp_id') ? 'border-red-500 ring-1 ring-red-500 bg-red-50/40' : 'border-slate-200 bg-white' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-900 text-sm placeholder-slate-400" 
                                value="{{ old('emp_id', session('prefilled_username')) }}" 
                                required 
                                autofocus 
                                autocomplete="off"
                            >
                        </div>
                        @error('emp_id')
                            <p class="text-red-500 text-[11px] mt-1.5 font-medium flex items-start gap-1.5 leading-snug">
                                <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                            <button type="button" onclick="openForgotPasswordModal()" class="text-[11px] font-semibold {{ session('show_forgot_prompt') ? 'text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-300 shadow-xs' : 'text-indigo-600 hover:text-indigo-800 hover:underline' }} cursor-pointer transition-all focus:outline-none flex items-center gap-1">
                                @if(session('show_forgot_prompt'))
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                @endif
                                <span>Forgot Password?</span>
                            </button>
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full pl-9 pr-10 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-500 ring-1 ring-red-500 bg-red-50/40' : 'border-slate-200 bg-white' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-900 text-sm placeholder-slate-400" 
                                placeholder="••••••••" 
                                required 
                                autocomplete="off"
                            >
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors" title="Toggle password visibility">
                                <svg id="eyeOpen" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" class="h-4 w-4 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[11px] mt-1 font-medium flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1.5">
                        <button 
                            type="submit" 
                            class="btn-animated w-full bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-2.5 sm:py-3 px-5 rounded-xl shadow-lg shadow-indigo-600/25 transition-all text-sm tracking-wide flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <span>Sign In</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>

                <!-- Footer note in card -->
                <div class="mt-4 sm:mt-5 pt-3 border-t border-slate-100 text-center space-y-1">
                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium">
                        &copy; {{ date('Y') }} Learning Management Portal
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-500">
                        Powered by <span class="font-bold text-slate-800">Technology Development (TD)</span>
                    </p>
                </div>

                </div><!-- End relative z-10 -->

            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- Forgot Password Modal: Security Warning & Verification Dialog   -->
    <!-- ============================================================== -->
    <div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/75 backdrop-blur-sm p-4 transition-all duration-200" onclick="if(event.target===this) closeForgotPasswordModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 animate-scale-up" onclick="event.stopPropagation()">
            <!-- Modal Header: Official Security Warning Theme -->
            <div class="px-6 py-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border-b border-indigo-900/40 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-amber-400 shrink-0 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white tracking-tight">Security Alert & Password Reset</h3>
                            <span class="text-[9px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30">Official</span>
                        </div>
                        <p class="text-[11px] text-slate-300 mt-0.5">Automated SMS & Institutional Email Dispatch System</p>
                    </div>
                </div>
                <button type="button" onclick="closeForgotPasswordModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors focus:outline-none" title="Close dialog">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('password.forgot') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Security Warning Banner -->
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200/90 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center shrink-0 mt-0.5 text-amber-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-amber-900 leading-relaxed">
                        <span class="font-bold text-amber-950 block mb-0.5">⚠️ Security Notice: Password Overwrite Warning</span>
                        Confirming will <span class="font-bold underline text-amber-950">immediately reset your current password</span> and dispatch a secure temporary password via SMS and Email to your verified contact details on file.
                    </div>
                </div>

                <!-- Username / Email Input Field -->
                <div>
                    <label for="forgot_username" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Username / Reg No / Staff ID or Registered Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input 
                            type="text" 
                            id="forgot_username" 
                            name="username" 
                            required 
                            placeholder="e.g. 211FA04001, 10001, or name@vignan.ac.in" 
                            value="{{ old('username', session('prefilled_username')) }}"
                            class="w-full text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-white hover:border-slate-300 transition-all text-slate-900 placeholder-slate-400"
                            autocomplete="off"
                        >
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Credentials will be securely delivered to your registered mobile phone (SMS) and official email address.</span>
                    </p>
                </div>

                <!-- Footer Actions -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeForgotPasswordModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition-colors cursor-pointer">
                        Cancel / Go Back
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 via-indigo-600 to-indigo-700 hover:from-amber-700 hover:to-indigo-800 text-white font-bold text-xs shadow-md shadow-indigo-200 transition-all flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span>Confirm & Send Password via SMS & Email</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openForgotPasswordModal() {
            const modal = document.getElementById('forgotPasswordModal');
            const input = document.getElementById('forgot_username');
            const loginEmpId = document.getElementById('emp_id');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                if (input) {
                    if (loginEmpId && loginEmpId.value.trim() !== '') {
                        input.value = loginEmpId.value.trim();
                    }
                    setTimeout(() => input.focus(), 50);
                }
            }
        }

        function closeForgotPasswordModal() {
            const modal = document.getElementById('forgotPasswordModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeForgotPasswordModal();
            }
        });
    </script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
