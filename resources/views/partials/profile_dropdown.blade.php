@php
    $currentUser = Auth::user();
@endphp
<div class="relative">
    <button id="profileDropdownBtn" onclick="event.stopPropagation(); const m = document.getElementById('profileDropdownMenu'); if(m) m.classList.toggle('hidden');" class="flex items-center gap-2 sm:gap-3 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-[#1C2430] transition-all focus:outline-none shrink-0" aria-label="Open profile menu">
        @if(Auth::check() && $currentUser && !empty($currentUser->photo))
            <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-slate-200 dark:border-[#273244]" src="{{ asset('storage/' . $currentUser->photo) }}" alt="">
        @else
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold shadow-sm text-xs" style="background: var(--theme-gradient);">
                {{ substr($currentUser?->first_name ?? ($currentUser?->username ?? 'U'), 0, 1) }}
            </div>
        @endif
        <div class="text-left hidden sm:block">
            <span class="block text-sm font-semibold text-slate-800 dark:text-[#F8FAFC] leading-tight truncate max-w-[130px]">{{ $currentUser?->first_name ?? ($currentUser?->username ?? 'User') }}</span>
            <span class="block text-[11px] text-slate-500 dark:text-[#94A3B8] font-medium leading-none mt-0.5">
                {{ ['sa' => 'Super Admin', 'ssh_admin' => 'SSH Admin', 'admin' => 'Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'Faculty', 'stu' => 'Student'][$currentUser?->role ?? ''] ?? 'User' }}
            </span>
        </div>
        <svg class="w-4 h-4 text-slate-400 dark:text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    
    <div id="profileDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#151B23] rounded-2xl shadow-2xl border border-slate-200 dark:border-[#273244] py-1.5 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right transition-all">
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-[#273244] bg-slate-50 dark:bg-[#1C2430] rounded-t-2xl">
            <p class="text-[10px] font-bold text-slate-400 dark:text-[#64748B] uppercase tracking-wider">Signed in as</p>
            <p class="text-sm font-bold text-slate-800 dark:text-[#F8FAFC] truncate">{{ $currentUser?->username ?? 'Guest' }}</p>
            @if($currentUser?->email)
                <p class="text-xs text-slate-500 dark:text-[#94A3B8] truncate">{{ $currentUser->email }}</p>
            @endif
        </div>
        <div class="p-1 space-y-0.5">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 dark:text-[#94A3B8] hover:bg-slate-50 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-[#F8FAFC] transition-colors">
                <svg class="w-4 h-4 text-slate-400 dark:text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Edit Profile
            </a>
            <a href="{{ route('password.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 dark:text-[#94A3B8] hover:bg-slate-50 dark:hover:bg-[#1C2430] hover:text-slate-900 dark:hover:text-[#F8FAFC] transition-colors">
                <svg class="w-4 h-4 text-slate-400 dark:text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                Change Password
            </a>
        </div>
        <div class="border-t border-slate-100 dark:border-[#273244] p-1 mt-1">
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-red-600 dark:text-[#EF4444] hover:bg-red-50 dark:hover:bg-[#EF4444]/10 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 text-red-600 dark:text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
