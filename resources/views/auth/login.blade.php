<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learning Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-image relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Clear, Subtle Ambient Overlay to keep background image vibrant and crisp -->
    <div class="absolute inset-0 bg-slate-950/35 bg-gradient-to-b from-black/30 via-black/20 to-black/40 z-0"></div>

    <!-- Dynamic ambient glow lights for premium depth -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>

    <!-- Main Split Container -->
    <div class="relative z-10 w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center py-6">

        <!-- =============================================================== -->
        <!-- LEFT SIDE: Live Interactive Student Portal Showcase               -->
        <!-- =============================================================== -->
        <div class="hidden lg:flex lg:col-span-7 flex-col justify-center text-white space-y-6 lg:pr-6">
            
            <!-- Header -->
            <div class="space-y-3">
                <h1 class="heading-font text-3xl lg:text-[2.65rem] font-black tracking-tight text-white leading-tight">
                    Learning Management <span class="animated-gradient-text">System</span>
                </h1>
                
                <p class="text-slate-200/85 text-sm leading-relaxed max-w-lg">
                    A unified digital campus platform empowering students with streamlined course allocations, instant study materials, and direct faculty connectivity.
                </p>
            </div>

            <!-- Live Interactive Feature Showcase Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                
                <!-- Feature 1: Course Enrollment -->
                <div class="feature-card card-anim-1 group p-4 rounded-2xl flex items-start gap-3.5 cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center shrink-0 shadow-lg shadow-indigo-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">Course Enrollment</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                Online
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mt-1 leading-snug">
                            Register for semester courses, electives, and core subjects seamlessly.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Study Materials -->
                <div class="feature-card card-anim-2 group p-4 rounded-2xl flex items-start gap-3.5 cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-sky-500 to-cyan-400 flex items-center justify-center shrink-0 shadow-lg shadow-sky-500/25 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-sky-600 transition-colors">Study Materials</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                24/7 Access
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mt-1 leading-snug">
                            Instant access to presentations, syllabus PDFs, and lecture notes.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Faculty Mentorship -->
                <div class="feature-card card-anim-3 group p-4 rounded-2xl flex items-start gap-3.5 cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">Assigned Faculty</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Direct Sync
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mt-1 leading-snug">
                            View allocated professors, mentors, and departmental teachers.
                        </p>
                    </div>
                </div>

                <!-- Feature 4: Civil Services Coaching -->
                <div class="feature-card card-anim-4 group p-4 rounded-2xl flex items-start gap-3.5 cursor-default">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-400 flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/25 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-amber-600 transition-colors">Civil Services</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                UPSC Track
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mt-1 leading-snug">
                            Integrated coaching modules, GS prep, and practice materials.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Feature 5 & Live Stats Bar (Combined Banner) -->
            <div class="feature-card card-anim-5 group p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 cursor-default">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-purple-600 to-fuchsia-500 flex items-center justify-center shrink-0 shadow-lg shadow-purple-500/25 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Academic Progress & Tracking</h3>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                Real-Time
                            </span>
                        </div>
                        <p class="text-gray-600 text-xs mt-0.5 leading-snug">
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
            
            <div class="glass-panel p-6 sm:p-10 rounded-3xl w-full max-w-md relative shadow-2xl">
                
                <!-- Logo & Card Header -->
                <div class="text-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30 text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                    <h2 class="heading-font text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Welcome Back</h2>
                    <p class="text-gray-500 mt-2 font-medium text-sm">Please enter your credentials to continue.</p>
                </div>

                <!-- Authentication Form -->
                <form method="POST" action="{{ route('authenticate') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Username Field (No placeholder) -->
                    <div>
                        <label for="emp_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input 
                                type="text" 
                                id="emp_id" 
                                name="emp_id" 
                                class="input-animated w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('emp_id') ? 'border-red-500 ring-1 ring-red-500 bg-red-50/40' : 'border-gray-200 bg-white/90' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-gray-900 text-sm" 
                                value="{{ old('emp_id') }}" 
                                required 
                                autofocus 
                                autocomplete="off"
                            >
                        </div>
                        @error('emp_id')
                            <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input-animated w-full pl-11 pr-12 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-500 ring-1 ring-red-500 bg-red-50/40' : 'border-gray-200 bg-white/90' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-gray-900 text-sm placeholder-gray-400" 
                                placeholder="••••••••" 
                                required 
                                autocomplete="off"
                            >
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none transition-colors" title="Toggle password visibility">
                                <svg id="eyeOpen" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" class="h-5 w-5 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button 
                            type="submit" 
                            class="btn-animated w-full bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-600/25 transition-all text-sm tracking-wide flex items-center justify-center gap-2"
                        >
                            <span>Sign In</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>

                <!-- Footer note in card -->
                <div class="mt-8 pt-5 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500 font-medium">
                        &copy; {{ date('Y') }} Learning Management System
                    </p>
                </div>

            </div>

        </div>

    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
