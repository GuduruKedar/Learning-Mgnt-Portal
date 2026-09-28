<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S&H Faculty Directory - SSH Department</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800 font-sans">

    @include('partials.sidebar')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('ssh.dashboard') }}" class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition-colors" title="Back to Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">S&H Faculty & Staff Directory</h1>
                    <p class="text-[11px] text-slate-400 font-medium hidden sm:block">Manage Sciences & Humanities teaching faculty and staff allocations</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.staff.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Add S&H Faculty</span>
                </a>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-lg leading-none">&times;</button>
                </div>
                @endif

                <!-- Filter Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">
                    <form id="facultyFilterForm" method="GET" action="{{ route('ssh.staff.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                        <div class="sm:col-span-6 lg:col-span-5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Search Faculty</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Emp ID, Name, Email, or Phone..." class="w-full text-sm rounded-xl border border-slate-200 pl-10 pr-4 py-2.5 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 shadow-xs transition-all">
                            </div>
                        </div>

                        <div class="sm:col-span-6 lg:col-span-4">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Department / Discipline</label>
                            <div class="relative">
                                <select name="department" id="departmentSelect" class="w-full text-sm font-medium rounded-xl border border-slate-200 py-2.5 px-3.5 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 shadow-xs transition-all cursor-pointer">
                                    <option value="">All S&H Disciplines</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-12 lg:col-span-3 flex items-center gap-2 pt-1">
                            <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                <span>Filter</span>
                            </button>
                            <a href="{{ route('ssh.staff.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Faculty Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 shadow-xs"></span>
                            <h2 class="text-sm font-bold text-slate-900">Registered Faculty Members</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Showing {{ $staff->total() }} S&H Faculty
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Emp ID</th>
                                    <th class="px-6 py-3.5 text-left">Faculty Name</th>
                                    <th class="px-6 py-3.5 text-left">Department</th>
                                    <th class="px-6 py-3.5 text-left">Contact Info</th>
                                    <th class="px-6 py-3.5 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($staff as $member)
                                @php
                                    $firstName = $member->first_name ?? '';
                                    $lastName = $member->last_name ?? '';
                                    $fullName = trim("{$firstName} {$lastName}");
                                    // Remove title prefix for avatar letter calculation (e.g. "Dr. Ramesh" -> "R")
                                    $cleanName = preg_replace('/^(Dr\.|Prof\.|Mr\.|Mrs\.|Ms\.)\s+/i', '', $fullName);
                                    $initial = strtoupper(substr(trim($cleanName), 0, 1)) ?: 'F';
                                    
                                    $colors = [
                                        'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        'bg-blue-100 text-blue-700 border-blue-200',
                                        'bg-violet-100 text-violet-700 border-violet-200',
                                        'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'bg-amber-100 text-amber-700 border-amber-200',
                                        'bg-rose-100 text-rose-700 border-rose-200'
                                    ];
                                    $colorClass = $colors[abs(crc32($member->username)) % count($colors)];
                                    $photo = $member->photo ?? $member->profile?->photo;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- EMP ID -->
                                    <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-700">
                                        <span class="px-2 py-1 rounded-md bg-indigo-50 border border-indigo-100">
                                            {{ $member->username }}
                                        </span>
                                    </td>

                                    <!-- Faculty Name -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($photo)
                                                <img class="w-9 h-9 rounded-full object-cover shadow-xs border border-slate-200 shrink-0" src="{{ asset('storage/' . $photo) }}" alt="{{ $fullName }}">
                                            @else
                                                <div class="w-9 h-9 rounded-full {{ $colorClass }} border font-bold flex items-center justify-center text-xs shadow-xs shrink-0">
                                                    {{ $initial }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-slate-900 text-sm leading-tight">{{ $fullName }}</div>
                                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $member->username }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Department -->
                                    <td class="px-6 py-4 text-xs">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium border border-slate-200">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            <span>{{ $member->profile->department->name ?? $member->profile->departments_id }}</span>
                                        </div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-6 py-4 text-xs">
                                        <div class="space-y-1">
                                            @if(!empty($member->profile?->email))
                                                <div class="flex items-center gap-1.5 text-slate-600">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    <a href="mailto:{{ $member->profile->email }}" class="hover:text-indigo-600 transition-colors truncate max-w-[220px]">
                                                        {{ $member->profile->email }}
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic text-[11px]">No email provided</span>
                                            @endif

                                            @if(!empty($member->profile?->phone))
                                                <div class="flex items-center gap-1.5 text-slate-500 font-mono text-[11px]">
                                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                    <a href="tel:{{ $member->profile->phone }}" class="hover:text-indigo-600 transition-colors">
                                                        {{ $member->profile->phone }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('ssh.staff.edit', $member->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/90 text-indigo-600 border border-indigo-200/70 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Faculty Profile">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button type="button" onclick="openManualResetPasswordModal('{{ route('users.reset-password', $member->id) }}', '{{ $member->username }}', '{{ addslashes($member->profile->first_name ?? $member->username) }} {{ addslashes($member->profile->last_name ?? '') }}', 'Staff@852')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50/90 text-amber-600 border border-amber-200/70 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Reset Password for {{ $member->username }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>
                                            <form method="POST" action="{{ route('ssh.staff.destroy', $member->id) }}" onsubmit="return confirm('Are you sure you want to remove faculty member {{ $member->username }}?')" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Faculty">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <p class="text-sm font-semibold text-slate-500">No S&H faculty members found.</p>
                                            <p class="text-xs text-slate-400">Try adjusting your search criteria or discipline filter.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($staff->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $staff->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('facultyFilterForm');
            const searchInput = document.getElementById('searchInput');
            const departmentSelect = document.getElementById('departmentSelect');

            // Auto submit on department change
            if (departmentSelect) {
                departmentSelect.addEventListener('change', function() {
                    form.submit();
                });
            }

            // Debounced auto submit on search input typing
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }
        });
    </script>
    @include('partials.reset_password_modal')
</body>
</html>
