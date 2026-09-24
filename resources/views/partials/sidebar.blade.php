<!-- Sidebar State Initializer (Executes synchronously to eliminate layout flicker on page load) -->
<script>
    (function() {
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
            document.documentElement.classList.add('sidebar-is-collapsed');
        }
    })();
</script>

<!-- Mobile Hamburger Button (Floating over Header) -->
<button id="mobile-toggle" class="mobile-menu-button hidden sm:hidden fixed top-3 left-4 z-[60] p-2 rounded-md text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition-all duration-200">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<aside id="sidebar" class="sidebar sidebar-expanded bg-blue-900 text-blue-100 shadow-2xl border-r border-blue-800 flex flex-col relative z-20 shrink-0">
    <!-- Logo Area -->
    <div class="h-16 flex items-center justify-between border-b border-blue-800 px-4" id="logo-container">
        <span id="logo-text" class="text-xl font-bold tracking-wider whitespace-nowrap text-white">{{ Auth::check() ? (['sa' => 'LMS Super Admin', 'ssh_admin' => 'LMS SSH', 'admin' => 'LMS Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'LMS Faculty', 'stu' => 'LMS Student'][Auth::user()->role] ?? 'LMS') : 'LMS' }}</span>
        <button id="toggle-sidebar" class="p-1 rounded-md text-blue-300 hover:text-white hover:bg-blue-800 focus:outline-none transition-colors shrink-0" aria-label="Toggle sidebar">
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
    @php
        $isDashboardActive = request()->routeIs('dashboard') || request()->routeIs('*.dashboard');
        $isManageActive = request()->routeIs('coordinators.*') || request()->routeIs('staff.*') || request()->routeIs('ssh.staff.*') || request()->routeIs('students.*') || request()->routeIs('ssh.students.*');
        $isAcademicsActive = request()->routeIs('academic.*') || request()->routeIs('activity_logs.*') || request()->routeIs('ssh.courses.*');
        $isCivilActive = request()->routeIs('civil.*');
    @endphp

    <nav class="flex-1 overflow-y-auto no-scrollbar py-6 space-y-2 px-3">
        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 {{ $isDashboardActive ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Dashboard">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'sta')
        <!-- Staff Specific Links -->
        <a href="{{ route('staff.assignments.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('staff.assignments.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2" title="Assignments">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Assignments</span>
        </a>
        @endif

        @if(Auth::user()->role === 'stu')
        <!-- Student Specific Links -->
        <a href="{{ route('student.enrollment.create') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('student.enrollment.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2" title="Enroll in Courses">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Enroll in Courses</span>
        </a>
        <a href="{{ route('student.courses.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('student.courses.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2" title="My Courses">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">My Courses</span>
        </a>
        <a href="{{ route('student.assignments.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('student.assignments.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-2" title="Assignments">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Assignments</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['sa', 'admin']))
        <!-- Manage Section -->
        <div class="pt-2">
            <button type="button" onclick="toggleSidebarSection('manage-menu')" class="w-full flex items-center px-3 py-3 rounded-xl transition-colors focus:outline-none {{ $isManageActive ? 'bg-blue-800 text-white shadow-sm border border-blue-700 active' : 'text-blue-300 hover:text-white hover:bg-blue-800/40' }}" title="Manage">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Manage</span>
                <svg class="w-4 h-4 ml-auto section-toggle-icon shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="manage-menu" class="space-y-1 mt-1 {{ $isManageActive ? '' : 'hidden' }}">
                @if(Auth::user()->role === 'sa')
                <a href="{{ route('coordinators.departments_list') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('coordinators.*') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Dept. Coordinators">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Dept. Coordinators</span>
                </a>
                @endif
                
                <a href="{{ route('staff.index') }}" class="flex items-center px-3 py-2.5 {{ (request()->routeIs('staff.*') || request()->routeIs('ssh.staff.*')) ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Faculty">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Faculty</span>
                </a>

                <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2.5 {{ (request()->routeIs('students.*') || request()->routeIs('ssh.students.*')) ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Students">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Students</span>
                </a>
            </div>
        </div>

        <!-- Academics Section -->
        <div class="pt-2 border-t border-blue-800 mt-2">
            <button type="button" onclick="toggleSidebarSection('academics-menu')" class="w-full flex items-center px-3 py-3 rounded-xl transition-colors focus:outline-none {{ $isAcademicsActive ? 'bg-blue-800 text-white shadow-sm border border-blue-700 active' : 'text-blue-300 hover:text-white hover:bg-blue-800/40' }}" title="Academics">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Academics</span>
                <svg class="w-4 h-4 ml-auto section-toggle-icon shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="academics-menu" class="space-y-1 mt-1 {{ $isAcademicsActive ? '' : 'hidden' }}">
                <a href="{{ route('academic.regulations') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('academic.regulations*') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Regulations">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Regulations</span>
                </a>

                <a href="{{ route('academic.courses') }}" class="flex items-center px-3 py-2.5 {{ (request()->routeIs('academic.courses*') || request()->routeIs('ssh.courses.*')) ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Courses">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Courses</span>
                </a>

                <a href="{{ route('academic.courses.allocations') }}" class="flex items-center px-3 py-2.5 {{ (request()->routeIs('academic.courses.allocations') || request()->routeIs('ssh.courses.allocations')) ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1" title="Course Allocations">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Course Allocations</span>
                </a>
                
                @if(Auth::user()->role === 'sa')
                <a href="{{ route('academic.enrollment_insights') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('academic.enrollment_insights') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1" title="Enrollment Insights">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Enrollment Insights</span>
                </a>

                <a href="{{ route('activity_logs.index') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('activity_logs.*') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1" title="Activity & Audit Logs">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Activity & Audit Logs</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        @if(in_array(Auth::user()->role, ['civil_admin', 'sa']))
        <!-- Civil Services Section -->
        <div class="pt-2 border-t border-blue-800 mt-2">
            <button type="button" onclick="toggleSidebarSection('civil-services-menu')" class="w-full flex items-center px-3 py-3 rounded-xl transition-colors focus:outline-none {{ $isCivilActive ? 'bg-blue-800 text-white shadow-sm border border-blue-700 active' : 'text-blue-300 hover:text-white hover:bg-blue-800/40' }}" title="Civil Services">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Civil Services</span>
                <svg class="w-4 h-4 ml-auto section-toggle-icon shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="civil-services-menu" class="space-y-1 mt-1 {{ $isCivilActive ? '' : 'hidden' }}">
                <a href="{{ route('civil.students.index') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('civil.students.index') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Enrolled Students">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Enrolled Students</span>
                </a>
                <a href="{{ route('civil.students.create') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('civil.students.create') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1" title="Enroll / Add Student">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Enroll / Add Student</span>
                </a>
                <a href="{{ route('civil.courses.index') }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('civil.courses.*') ? 'bg-blue-700/80 text-white font-semibold border border-blue-600 shadow-inner active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group mt-1" title="Courses & Modules">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Courses & Modules</span>
                </a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'ssh_admin')
        <!-- SSH Admin Navigation: Strictly Students, Faculty, Regulations, Courses -->
        <div class="pt-2 border-t border-blue-800 mt-2 space-y-1">
            <a href="{{ route('ssh.students.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('ssh.students.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Students">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Students</span>
            </a>

            <a href="{{ route('ssh.staff.index') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('ssh.staff.*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Faculty">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Faculty</span>
            </a>

            <a href="{{ route('academic.regulations') }}" class="flex items-center px-3 py-3 {{ request()->routeIs('academic.regulations*') ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Regulations">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Regulations</span>
            </a>

            <a href="{{ route('academic.courses') }}" class="flex items-center px-3 py-3 {{ (request()->routeIs('academic.courses*') || request()->routeIs('ssh.courses.*')) ? 'bg-blue-800 shadow-sm border border-blue-700 text-white active' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }} rounded-lg transition-colors group" title="Courses">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="ml-4 nav-text font-medium text-sm whitespace-nowrap">Courses</span>
            </a>
        </div>
        @endif



    </nav>
</aside>

<!-- Mobile Sidebar Overlay (Premium Glassmorphism Fade) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[90] opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out"></div>

