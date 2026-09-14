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
    <div class="relative z-10 w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center py-6">

        <!-- =============================================================== -->
        <!-- LEFT SIDE: Professional Point-Wise LMS Utility Guide             -->
        <!-- =============================================================== -->
        <div class="lg:col-span-7 flex flex-col justify-center text-white space-y-6 lg:pr-4">
            
            <!-- Heading (Single Line) & Professional Section Header -->
            <div class="space-y-4">
                <h1 class="heading-font text-2xl sm:text-3xl lg:text-[2.15rem] xl:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm sm:whitespace-nowrap">
                    Learning Management <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-indigo-200 to-sky-300">System</span>
                </h1>

                <!-- Professionally Styled Utility Header -->
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-500/20 via-blue-500/15 to-sky-500/10 border border-indigo-400/30 backdrop-blur-md shadow-lg shadow-indigo-950/40">
                    <div class="w-2.5 h-2.5 rounded-full bg-gradient-to-r from-sky-400 to-indigo-400 shadow-sm shadow-sky-400/80 animate-pulse"></div>
                    <span class="text-xs sm:text-sm font-bold tracking-wide uppercase text-transparent bg-clip-text bg-gradient-to-r from-sky-200 via-indigo-100 to-blue-200">
                        How this LMS is useful for students
                    </span>
                </div>
            </div>

            <!-- Clear, Concise Point-Wise List -->
            <div class="space-y-3">
                
                <!-- Point 1: Course Enrollment -->
                <div class="point-item p-3.5 sm:p-4 rounded-xl glass-points-container flex items-center gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center shrink-0 text-indigo-300 font-bold text-xs">
                        01
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white">Course Enrollment</h2>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-0.5 leading-normal">
                            Register for semester courses, core subjects, and electives online.
                        </p>
                    </div>
                </div>

                <!-- Point 2: Study Materials -->
                <div class="point-item p-3.5 sm:p-4 rounded-xl glass-points-container flex items-center gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/20 border border-sky-400/30 flex items-center justify-center shrink-0 text-sky-300 font-bold text-xs">
                        02
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white">Lecture Notes & Study Materials</h2>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-0.5 leading-normal">
                            Download classroom presentations, syllabus PDFs, and reference resources 24/7.
                        </p>
                    </div>
                </div>

                <!-- Point 3: Faculty Details -->
                <div class="point-item p-3.5 sm:p-4 rounded-xl glass-points-container flex items-center gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center shrink-0 text-emerald-300 font-bold text-xs">
                        03
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white">Assigned Faculty Details</h2>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-0.5 leading-normal">
                            View allocated teachers and professors for each of your enrolled courses.
                        </p>
                    </div>
                </div>

                <!-- Point 4: Civil Services Coaching -->
                <div class="point-item p-3.5 sm:p-4 rounded-xl glass-points-container flex items-center gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-400/30 flex items-center justify-center shrink-0 text-amber-300 font-bold text-xs">
                        04
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white">Civil Services Coaching</h2>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-0.5 leading-normal">
                            Access integrated UPSC and General Studies preparation modules.
                        </p>
                    </div>
                </div>

                <!-- Point 5: Academic Tracking -->
                <div class="point-item p-3.5 sm:p-4 rounded-xl glass-points-container flex items-center gap-3.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 border border-purple-400/30 flex items-center justify-center shrink-0 text-purple-300 font-bold text-xs">
                        05
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white">Academic Progress Tracking</h2>
                        <p class="text-slate-300/90 text-xs sm:text-sm mt-0.5 leading-normal">
                            Check registered courses, regulation curriculum, and academic requirements.
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
