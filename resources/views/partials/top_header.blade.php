<!-- Top Header -->
<header class="h-16 bg-white dark:bg-[#111827] shadow-xs flex items-center justify-between px-3 sm:px-6 z-40 relative shrink-0 w-full border-b border-slate-200 dark:border-[#273244] transition-colors duration-200">
    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1 mr-2">
        <!-- Mobile Navigation Menu Toggle Button -->
        <button type="button" id="mobile-toggle" class="md:hidden p-2 -ml-1 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shrink-0" aria-label="Open Navigation Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Logo displayed in top header when sidebar is collapsed (or on mobile) -->
        <a href="{{ route('dashboard') }}" class="header-collapsed-logo hidden items-center shrink-0 transition-transform duration-200 hover:scale-105" title="LMS Portal">
            @include('partials.shield_logo', ['class' => 'w-8 h-8 sm:w-9 sm:h-9 shrink-0 drop-shadow-xs'])
        </a>

        <span class="text-base sm:text-lg md:text-xl font-bold text-slate-800 dark:text-[#F8FAFC] tracking-tight whitespace-nowrap truncate min-w-0" title="{{ Auth::check() ? (['sa' => 'LMS Super Admin', 'ssh_admin' => 'LMS SSH', 'admin' => 'LMS Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'LMS Faculty', 'stu' => 'LMS Student'][Auth::user()->role] ?? 'Learning Management System') : 'Learning Management System' }}">
            {{ Auth::check() ? (['sa' => 'LMS Super Admin', 'ssh_admin' => 'LMS SSH', 'admin' => 'LMS Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'LMS Faculty', 'stu' => 'LMS Student'][Auth::user()->role] ?? 'Learning Management System') : 'Learning Management System' }}
        </span>
    </div>
    <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
        @include('partials.theme_toggle')
        @include('partials.profile_dropdown')
    </div>
</header>

@include('partials.flash_messages')
