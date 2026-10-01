<!-- Top Header -->
<header class="h-16 bg-white dark:bg-[#111827] shadow-xs flex items-center justify-between px-4 sm:px-6 z-40 relative shrink-0 w-full border-b border-slate-200 dark:border-[#273244] transition-colors duration-200">
    <div class="flex items-center gap-3">
        <span class="text-base sm:text-lg font-bold text-slate-800 dark:text-[#F8FAFC] tracking-tight">
            {{ Auth::check() ? (['sa' => 'LMS Super Admin', 'ssh_admin' => 'LMS SSH', 'admin' => 'LMS Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'LMS Faculty', 'stu' => 'LMS Student'][Auth::user()->role] ?? 'Learning Management System') : 'Learning Management System' }}
        </span>
    </div>
    <div class="flex items-center gap-3 ml-auto">
        @include('partials.theme_toggle')
        @include('partials.profile_dropdown')
    </div>
</header>

@include('partials.flash_messages')
