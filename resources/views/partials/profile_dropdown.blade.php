<div class="relative">
    <button id="profileDropdownBtn" onclick="event.stopPropagation(); const m = document.getElementById('profileDropdownMenu'); if(m) m.classList.toggle('hidden');" class="flex items-center gap-2 sm:gap-3 p-1 rounded-xl hover:bg-slate-100 transition-all focus:outline-none shrink-0" aria-label="Open profile menu">
        @if(Auth::check() && Auth::user()->photo)
            <img class="w-9 h-9 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
        @else
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-bold shadow-sm text-sm">
                {{ substr(Auth::user()->first_name ?? (Auth::user()->username ?? 'U'), 0, 1) }}
            </div>
        @endif
        <div class="text-left hidden sm:block">
            <span class="block text-sm font-bold text-slate-800 leading-tight truncate max-w-[130px]">{{ Auth::user()->first_name ?? Auth::user()->username }}</span>
            <span class="block text-[11px] text-slate-500 font-medium leading-none mt-0.5">
                {{ ['sa' => 'Super Admin', 'ssh_admin' => 'SSH Admin', 'admin' => 'Coordinator', 'civil_admin' => 'Civil Services', 'sta' => 'Faculty', 'stu' => 'Student'][Auth::user()->role] ?? 'User' }}
            </span>
        </div>
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    
    <div id="profileDropdownMenu" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-1.5 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right transition-all">
        <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Signed in as</p>
            <p class="text-sm font-bold text-indigo-700 truncate">{{ Auth::user()->username }}</p>
            @if(Auth::user()->email)
                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
            @endif
        </div>
        <div class="p-1 space-y-0.5">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Edit Profile
            </a>
            <a href="{{ route('password.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                Change Password
            </a>
        </div>
        <div class="border-t border-slate-100 p-1 mt-1">
            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
