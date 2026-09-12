<!-- Sidebar -->
<!-- Mobile Hamburger Button (Floating over Header) -->
<button id="mobile-toggle" class="mobile-menu-button hidden sm:hidden fixed top-3 left-4 z-[60] p-2 rounded-md text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition-all duration-200">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
<aside id="sidebar" class="sidebar sidebar-expanded bg-blue-900 text-blue-100 shadow-2xl border-r border-blue-800 flex flex-col transition-all duration-300 relative z-20 shrink-0">
    <!-- Logo Area -->
    <div class="h-16 flex items-center justify-between border-b border-blue-800 px-4" id="logo-container">
        <span id="logo-text" class="text-xl font-bold tracking-wider text-expanded whitespace-nowrap text-white">{{ Auth::check() ? (['sa' => 'LMS Super Admin', 'admin' => 'LMS Admin', 'sta' => 'LMS Staff', 'stu' => 'LMS Student'][Auth::user()->role] ?? 'LMS') : 'LMS' }}</span>
        <button id="toggle-sidebar" class="p-1 rounded-md text-blue-300 hover:text-white hover:bg-blue-800 focus:outline-none transition-colors shrink-0">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <button type="button" id="mobile-close-sidebar" class="hidden sm:hidden p-1.5 rounded-lg text-blue-300 hover:text-white hover:bg-blue-800 focus:outline-none transition-colors shrink-0" aria-label="Close sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto no-scrollbar py-6 space-y-2 px-3">
        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('dashboard') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'sta')
        <!-- Staff Specific Links -->
        <a href="{{ route('staff.courses.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('staff.courses.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">My Courses</span>
        </a>
        @endif

        @if(Auth::user()->role === 'stu')
        <!-- Student Specific Links -->
        <a href="{{ route('student.enrollment.create') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('student.enrollment.create') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Enroll in Courses</span>
        </a>
        <a href="{{ route('student.courses.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('student.courses.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">My Courses</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['sa', 'admin']))
        
        <!-- Manage Section -->
        <div class="pt-2">
            <button type="button" onclick="document.getElementById('manage-menu').classList.toggle('hidden')" class="w-full flex justify-between items-center px-3 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider hover:text-white transition-colors focus:outline-none">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-expanded nav-text">Manage</span>
                </div>
                <svg class="w-4 h-4 text-expanded nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="manage-menu" class="space-y-1 mt-1 {{ (request()->routeIs('coordinators.departments_list') || request()->routeIs('coordinators.index') || request()->routeIs('staff.index') || request()->routeIs('students.index')) ? '' : 'hidden' }}">
                @if(Auth::user()->role === 'sa')
                <a href="{{ route('coordinators.departments_list') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('coordinators.departments_list') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Dept. Coordinators</span>
                </a>
                

                @endif
                
                <a href="{{ route('staff.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('staff.index') && !request()->has('action') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Staff</span>
                </a>
                
                <a href="{{ route('students.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('students.index') && !request()->has('action') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Student</span>
                </a>

                <!-- Reset Password Sub-Dropdown inside Manage -->
                <div class="pt-1">
                    <button type="button" onclick="document.getElementById('reset-password-menu').classList.toggle('hidden')" class="w-full flex justify-between items-center px-3 py-3 {{ request('action') == 'reset_password' ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors focus:outline-none group">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Reset Password</span>
                        </div>
                        <svg class="w-4 h-4 text-expanded nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="reset-password-menu" class="space-y-1 mt-1 pl-4 {{ request('action') == 'reset_password' ? '' : 'hidden' }}">
                        @if(Auth::user()->role === 'sa')
                        <a href="{{ route('coordinators.index', ['action' => 'reset_password']) }}" class="flex items-center px-3 py-2.5 {{ request('action') == 'reset_password' && request()->routeIs('coordinators.index') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            <span class="ml-3 nav-text font-medium whitespace-nowrap text-expanded">Coordinator</span>
                        </a>
                        @endif

                        <a href="{{ route('staff.index', ['action' => 'reset_password']) }}" class="flex items-center px-3 py-2.5 {{ request('action') == 'reset_password' && request()->routeIs('staff.index') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            <span class="ml-3 nav-text font-medium whitespace-nowrap text-expanded">Staff</span>
                        </a>

                        <a href="{{ route('students.index', ['action' => 'reset_password']) }}" class="flex items-center px-3 py-2.5 {{ request('action') == 'reset_password' && request()->routeIs('students.index') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            <span class="ml-3 nav-text font-medium whitespace-nowrap text-expanded">Student</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['sa', 'admin']))
        <!-- Academics Section -->
        <div class="pt-2 border-t border-blue-800 mt-2">
            <button type="button" onclick="document.getElementById('academics-menu').classList.toggle('hidden')" class="w-full flex justify-between items-center px-3 py-2 text-xs font-semibold text-blue-300 uppercase tracking-wider hover:text-white transition-colors focus:outline-none mt-2">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    <span class="text-expanded nav-text">Academics</span>
                </div>
                <svg class="w-4 h-4 text-expanded nav-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="academics-menu" class="space-y-1 mt-1 {{ request()->routeIs('academic.*') ? '' : 'hidden' }}">
                <a href="{{ route('academic.regulations') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('academic.regulations') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Regulations</span>
                </a>

                <a href="{{ route('academic.courses') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('academic.courses') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Courses</span>
                </a>

                <a href="{{ route('academic.courses.allocations') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('academic.courses.allocations') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Course Allocations</span>
                </a>
                
                @if(Auth::user()->role === 'sa')
                <a href="{{ route('academic.enrollment_insights') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('academic.enrollment_insights') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap text-expanded">Enrollment Insights</span>
                </a>
                @endif
            </div>
        </div>
        @endif



    </nav>
</aside>

<!-- Mobile Sidebar Overlay (Premium Glassmorphism Fade) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[90] opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out"></div>

