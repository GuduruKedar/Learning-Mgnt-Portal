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
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-image relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Sophisticated Ambient Dark Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/85 to-indigo-950/90 backdrop-blur-[3px] z-0"></div>

    <!-- Glowing Background Highlights -->
    <div class="absolute top-1/4 left-10 w-96 h-96 glow-orb-1 rounded-full pointer-events-none z-0"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 glow-orb-2 rounded-full pointer-events-none z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[36rem] h-[36rem] glow-orb-3 rounded-full pointer-events-none z-0"></div>

    <!-- Main Split Container -->
    <div class="relative z-10 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center py-6 lg:py-8">

        <!-- ========================================== -->
        <!-- LEFT SIDE: LMS Overview, Purpose & Pillars -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 flex flex-col justify-center text-white space-y-6 lg:pr-4">
            
            <!-- Institution & Portal Tag -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-indigo-200 text-xs sm:text-sm font-semibold w-fit tracking-wide shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>University Academic & Career Portal</span>
            </div>

            <!-- Main Heading -->
            <div class="space-y-3">
                <h1 class="heading-font text-3xl sm:text-4xl xl:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow-sm">
                    Unified <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-indigo-200 to-sky-300">Learning Management</span> System
                </h1>
                <p class="text-slate-300 text-sm sm:text-base xl:text-lg font-normal leading-relaxed max-w-2xl">
                    An integrated digital ecosystem empowering students, faculty coordinators, and administration across all university departments with modern curriculum delivery, flexible course enrollment, digital learning resources, and career coaching.
                </p>
            </div>

            <!-- Key Feature Pillars Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                
                <!-- Pillar 1: Academic & Curriculum -->
                <div class="glass-feature-card p-4 sm:p-5 rounded-2xl flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center shrink-0 text-indigo-300 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm sm:text-base">Academic Curriculum</h3>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-1 leading-normal">
                            Regulation tracking, semester syllabus allotment, and course registration across UG, PG, and PhD levels.
                        </p>
                    </div>
                </div>

                <!-- Pillar 2: Civil Services Academy -->
                <div class="glass-feature-card p-4 sm:p-5 rounded-2xl flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0 text-blue-300 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm sm:text-base">Civil Services Center</h3>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-1 leading-normal">
                            Integrated UPSC career preparation, specialized General Studies modules, and universal enrollment.
                        </p>
                    </div>
                </div>

                <!-- Pillar 3: Courseware & Digital Materials -->
                <div class="glass-feature-card p-4 sm:p-5 rounded-2xl flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center shrink-0 text-emerald-300 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm sm:text-base">Digital Learning Hub</h3>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-1 leading-normal">
                            24/7 direct access to lecture notes, reference PDFs, multimedia video tutorials, and study modules.
                        </p>
                    </div>
                </div>

                <!-- Pillar 4: Role-Based Analytics & Workflows -->
                <div class="glass-feature-card p-4 sm:p-5 rounded-2xl flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center shrink-0 text-amber-300 shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm sm:text-base">Secured Role Portals</h3>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-1 leading-normal">
                            Tailored workflows for Super Admins, Department Coordinators, Faculty Members, and Students.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Live Indicators & Badges -->
            <div class="flex flex-wrap items-center gap-3 pt-2 text-xs sm:text-sm text-slate-300">
                <span class="stat-pill px-3 py-1.5 rounded-xl font-medium inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a10.97 10.97 0 00-.25 2.5v4.5a1 1 0 001 1h8a1 1 0 001-1v-4.5c0-.85-.087-1.688-.25-2.5l2.644-1.131a1 1 0 000-1.84l-7-3zM10 4.236l4.116 1.764L10 7.764 5.884 6 10 4.236z"></path></svg>
                    10+ Academic Schools
                </span>
                <span class="stat-pill px-3 py-1.5 rounded-xl font-medium inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    50+ Degree Programs
                </span>
                <span class="stat-pill px-3 py-1.5 rounded-xl font-medium inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Enterprise Security
                </span>
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
                    
                    <!-- Username Field -->
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
                                class="input-animated w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('emp_id') ? 'border-red-500 ring-1 ring-red-500 bg-red-50/40' : 'border-gray-200 bg-white/90' }} focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-gray-900 text-sm placeholder-gray-400" 
                                placeholder="Employee ID / Reg No" 
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
                                <svg id="eyeClosed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
