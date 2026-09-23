<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Coordinator - LMS</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => {
                    profileMenu.classList.add('hidden');
                });
                profileMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
            const pwdModal = document.getElementById('passwordModal');
            const openPwdBtn = document.getElementById('openPasswordModalBtn');
            const closePwdBtn = document.getElementById('closePasswordModal');
            const cancelPwdBtn = document.getElementById('cancelPasswordModal');
            
            if (pwdModal && openPwdBtn) {
                const openModal = () => {
                    pwdModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                };
                const closeModal = () => {
                    pwdModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                };
                
                openPwdBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal();
                    if(typeof profileMenu !== 'undefined' && profileMenu) profileMenu.classList.add('hidden');
                });
                closePwdBtn.addEventListener('click', closeModal);
                cancelPwdBtn.addEventListener('click', closeModal);
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif
        });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 9999px;
            margin: 6px 0;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
    </style>
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center ml-auto">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->role === 'sa' ? 'Super Admin' : (Auth::user()->first_name ?? 'Admin') }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 focus:outline-none">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/60 pb-36">
            <div class="max-w-4xl mx-auto space-y-6">
                
                <!-- Page Breadcrumbs & Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Add New Coordinator</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Register a department coordinator to manage curriculum, faculty allocations, and students.</p>
                    </div>
                    <div class="shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            Department Coordinator Setup
                        </span>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80">
                    <div class="px-6 py-4 border-b border-slate-100 bg-white flex items-center justify-between rounded-t-2xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Coordinator Account & Academic Assignment</h2>
                        </div>
                        <span class="text-xs font-semibold text-slate-400"><span class="text-red-500">*</span> Required fields</span>
                    </div>

                    <form action="{{ route('coordinators.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        
                        <!-- Personal Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Rajesh" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Sharma" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Credentials & Contact Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee ID / Username <span class="text-red-500">*</span></label>
                                <input type="text" name="username" value="{{ old('username') }}" placeholder="5-digit ID (e.g. 10001)" maxlength="5" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono" required>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="name@vignan.ac.in" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="10-digit mobile" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-5">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Academic Placement</h3>

                            <!-- School & Department Full Row Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                
                                <!-- School Field -->
                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                        <span>School / Academic Division <span class="text-red-500">*</span></span>
                                        <span id="school_selected_badge" class="hidden text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">Selected</span>
                                    </label>
                                    <input type="hidden" name="school_id" id="school_id_input" value="{{ old('school_id', $preselectedSchoolId ?? '') }}" required>
                                    
                                    <button type="button" id="school_custom_btn" 
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 hover:border-indigo-300 rounded-xl text-sm text-left focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-150 shadow-sm"
                                        onclick="toggleDropdown('school_dropdown_list')" aria-haspopup="listbox">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-100/70 text-indigo-700 flex items-center justify-center shrink-0 font-bold text-xs border border-indigo-200/60">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                            <span id="school_display_text" class="font-semibold text-slate-800 text-sm leading-snug break-words">Select School</span>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-150" id="school_chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <!-- Full-Width School Dropdown Popover -->
                                    <div id="school_dropdown_list" class="hidden absolute z-50 left-0 right-0 sm:right-auto sm:min-w-[480px] max-w-[calc(100vw-2rem)] mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden flex flex-col transition-all ring-1 ring-black/5">
                                        <div class="p-2.5 border-b border-slate-100 bg-slate-50/90">
                                            <div class="relative">
                                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                <input type="text" id="school_search_input" placeholder="Search school..." oninput="filterSchools(this.value)" class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                        <div class="p-2 overflow-y-auto max-h-[460px] custom-scrollbar overscroll-contain space-y-1 pb-4" id="school_options_container">
                                            <div class="px-3 py-2 cursor-pointer hover:bg-slate-100/80 rounded-xl text-xs text-slate-500 font-semibold transition-colors mb-1" onclick="selectSchool('', 'Select School', '')">
                                                -- Select School --
                                            </div>
                                            @php
                                                $orderedCodes = [
                                                    'sc_ceng',
                                                    'sc_eeceng',
                                                    'sc_ci',
                                                    'sc_bps',
                                                    'sc_lm',
                                                    'sc_aft',
                                                    'sc_ash',
                                                    'dip',
                                                    'sc_edu',
                                                    'sc_cs',
                                                ];
                                                $orderedSchools = collect($orderedCodes)->map(function($code) use ($schools) {
                                                    return $schools->where('code', $code)->first();
                                                })->filter()->values();
                                            @endphp
                                            @foreach($orderedSchools as $idx => $school)
                                                @php
                                                    $numStr = sprintf('%02d', $idx + 1);
                                                    $schoolDisplayName = $numStr . ' ' . $school->name;
                                                @endphp
                                                <div class="school-option p-2.5 cursor-pointer hover:bg-indigo-50/80 rounded-xl text-sm transition-all group flex items-center justify-between gap-3 border border-transparent hover:border-indigo-100" 
                                                     data-name="{{ strtolower($school->name) }}"
                                                     data-id="{{ $school->id }}"
                                                     onclick="selectSchool('{{ $school->id }}', '{{ addslashes($schoolDisplayName) }}', '{{ $school->code }}')">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 font-bold text-xs border border-indigo-200">
                                                            {{ $numStr }}
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="font-bold text-slate-900 group-hover:text-indigo-600 text-sm leading-snug whitespace-normal">
                                                                {{ $school->name }}
                                                            </p>
                                                            <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-mono mt-0.5">
                                                                {{ $school->code }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <svg class="school-check-icon hidden w-4 h-4 text-indigo-600 shrink-0 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            @endforeach
                                            <div class="h-2"></div>
                                        </div>
                                    </div>
                                    @error('school_id')
                                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Department Field -->
                                <div class="relative">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                        <span>Department <span class="text-red-500">*</span></span>
                                        <span id="dept_selected_badge" class="hidden text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Selected</span>
                                    </label>
                                    <input type="hidden" name="department_id" id="department_id_input" value="{{ old('department_id', $preselectedDepartmentId ?? '') }}" required>
                                    
                                    <button type="button" id="department_custom_btn" 
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-slate-100/80 border border-slate-200 rounded-xl text-sm text-left transition-all duration-150 cursor-not-allowed shadow-sm" 
                                        onclick="toggleDropdown('department_dropdown_list')" disabled aria-haspopup="listbox">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                            <div class="w-7 h-7 rounded-lg bg-slate-200/80 text-slate-400 flex items-center justify-center shrink-0" id="dept_icon_container">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <span id="department_display_text" class="font-medium text-slate-400 text-sm leading-snug break-words">Select School first</span>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-150" id="dept_chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <!-- Full-Width Department Dropdown Popover -->
                                    <div id="department_dropdown_list" class="hidden absolute z-50 left-0 right-0 sm:right-auto sm:min-w-[480px] max-w-[calc(100vw-2rem)] mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden flex flex-col transition-all ring-1 ring-black/5">
                                        <div class="p-2.5 border-b border-slate-100 bg-slate-50/90" id="dept_search_wrapper">
                                            <div class="relative">
                                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                <input type="text" id="dept_search_input" placeholder="Search department..." oninput="filterDepartments(this.value)" class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                        </div>
                                        <div class="p-2 overflow-y-auto max-h-[460px] custom-scrollbar overscroll-contain space-y-1 pb-4" id="department_dropdown_list_inner">
                                            <div class="px-3.5 py-4 text-xs text-slate-400 font-medium text-center">
                                                Please select a School first
                                            </div>
                                        </div>
                                    </div>
                                    @error('department_id')
                                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Security & Password -->
                        <div class="border-t border-slate-100 pt-5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Coordinator Initial Password</label>
                            <input type="password" name="password" placeholder="Leave blank to use default password (Admin!741)" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                            <p class="text-[11px] text-slate-400 mt-1">If left blank, the system automatically assigns default initial credentials: <code class="text-indigo-600 font-bold font-mono">Admin!741</code></p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                            <a href="{{ route('coordinators.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-bold text-center hover:bg-slate-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 hover:-translate-y-0.5 transition-all duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>Create Coordinator</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <footer class="mt-8 border-t border-slate-200/80 pt-4 pb-2">
                <p class="text-center text-xs text-slate-500 font-medium">&copy; {{ date('Y') }} Learning Management System. All rights reserved.</p>
            </footer>
        </main>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
                <h3 class="text-lg font-bold text-indigo-900">Change Password</h3>
                <button type="button" id="closePasswordModal" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" placeholder="Enter new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" id="cancelPasswordModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const navTexts = document.querySelectorAll('.nav-text');
        const logoText = document.getElementById('logo-text');
        const logoContainer = document.getElementById('logo-container');

        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        // Initialize state on load
        if (isCollapsed && sidebar) {
            sidebar.classList.remove('sidebar-expanded');
            sidebar.classList.add('sidebar-collapsed');
            if (logoText) {
                logoText.classList.add('hidden');
                logoText.classList.remove('text-expanded');
            }
            if (logoContainer) {
                logoContainer.classList.remove('justify-between');
                logoContainer.classList.add('justify-center');
            }
            navTexts.forEach(el => {
                el.classList.add('text-collapsed');
                el.classList.remove('text-expanded');
            });
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                isCollapsed = !isCollapsed;
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                if (isCollapsed) {
                    sidebar.classList.remove('sidebar-expanded');
                    sidebar.classList.add('sidebar-collapsed');
                    
                    if (logoText) {
                        logoText.classList.add('hidden');
                        logoText.classList.remove('text-expanded');
                    }
                    if (logoContainer) {
                        logoContainer.classList.remove('justify-between');
                        logoContainer.classList.add('justify-center');
                    }

                    navTexts.forEach(el => {
                        el.classList.add('text-collapsed');
                        el.classList.remove('text-expanded');
                    });
                } else {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.classList.add('sidebar-expanded');
                    
                    if (logoContainer) {
                        logoContainer.classList.remove('justify-center');
                        logoContainer.classList.add('justify-between');
                    }
                    if (logoText) {
                        logoText.classList.remove('hidden');
                    }

                    setTimeout(() => {
                        if (!isCollapsed) {
                            if (logoText) logoText.classList.add('text-expanded');
                            navTexts.forEach(el => {
                                el.classList.remove('text-collapsed');
                                el.classList.add('text-expanded');
                            });
                        }
                    }, 150);
                }
            });
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (!dropdown) return;
            const isHidden = dropdown.classList.contains('hidden');
            
            // Close all custom dropdowns first
            document.querySelectorAll('#school_dropdown_list, #department_dropdown_list').forEach(el => el.classList.add('hidden'));
            
            if (isHidden) {
                dropdown.classList.remove('hidden');
                // Focus search input if present
                if (id === 'school_dropdown_list') {
                    const sInput = document.getElementById('school_search_input');
                    if (sInput) { sInput.value = ''; filterSchools(''); sInput.focus(); }
                } else if (id === 'department_dropdown_list') {
                    const dInput = document.getElementById('dept_search_input');
                    if (dInput) { dInput.value = ''; filterDepartments(''); dInput.focus(); }
                }
            }
        }

        // Filter Schools
        function filterSchools(query) {
            const term = (query || '').toLowerCase().trim();
            const options = document.querySelectorAll('#school_options_container .school-option');
            options.forEach(opt => {
                const name = opt.getAttribute('data-name') || '';
                if (name.includes(term)) {
                    opt.classList.remove('hidden');
                } else {
                    opt.classList.add('hidden');
                }
            });
        }

        // Filter Departments
        function filterDepartments(query) {
            const term = (query || '').toLowerCase().trim();
            const options = document.querySelectorAll('#department_dropdown_list_inner .dept-option');
            options.forEach(opt => {
                const name = opt.getAttribute('data-name') || '';
                if (name.includes(term)) {
                    opt.classList.remove('hidden');
                } else {
                    opt.classList.add('hidden');
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#school_custom_btn') && !e.target.closest('#school_dropdown_list')) {
                document.getElementById('school_dropdown_list')?.classList.add('hidden');
            }
            if (!e.target.closest('#department_custom_btn') && !e.target.closest('#department_dropdown_list')) {
                document.getElementById('department_dropdown_list')?.classList.add('hidden');
            }
        });

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('school_dropdown_list')?.classList.add('hidden');
                document.getElementById('department_dropdown_list')?.classList.add('hidden');
            }
        });

        function selectSchool(id, text, code) {
            document.getElementById('school_id_input').value = id;
            const displayText = document.getElementById('school_display_text');
            const selectedBadge = document.getElementById('school_selected_badge');
            
            displayText.textContent = text || 'Select School';
            if (id) {
                displayText.classList.add('font-bold', 'text-slate-900');
                if (selectedBadge) selectedBadge.classList.remove('hidden');
            } else {
                displayText.classList.remove('font-bold', 'text-slate-900');
                if (selectedBadge) selectedBadge.classList.add('hidden');
            }

            // Highlight active in dropdown
            document.querySelectorAll('#school_options_container .school-option').forEach(opt => {
                const check = opt.querySelector('.school-check-icon');
                if (opt.getAttribute('data-id') === String(id)) {
                    opt.classList.add('bg-indigo-50/90', 'text-indigo-900');
                    if (check) check.classList.remove('hidden');
                } else {
                    opt.classList.remove('bg-indigo-50/90', 'text-indigo-900');
                    if (check) check.classList.add('hidden');
                }
            });

            document.getElementById('school_dropdown_list')?.classList.add('hidden');
            updateDepartments();
        }

        function selectDepartment(id, text, code) {
            document.getElementById('department_id_input').value = id;
            const displayText = document.getElementById('department_display_text');
            const selectedBadge = document.getElementById('dept_selected_badge');
            
            displayText.textContent = text || 'Select Department';
            if (id) {
                displayText.classList.add('font-bold', 'text-slate-900');
                if (selectedBadge) selectedBadge.classList.remove('hidden');
            } else {
                displayText.classList.remove('font-bold', 'text-slate-900');
                if (selectedBadge) selectedBadge.classList.add('hidden');
            }

            // Highlight active in dropdown
            document.querySelectorAll('#department_dropdown_list_inner .dept-option').forEach(opt => {
                const check = opt.querySelector('.dept-check-icon');
                if (opt.getAttribute('data-id') === String(id)) {
                    opt.classList.add('bg-emerald-50/90', 'text-emerald-900');
                    if (check) check.classList.remove('hidden');
                } else {
                    opt.classList.remove('bg-emerald-50/90', 'text-emerald-900');
                    if (check) check.classList.add('hidden');
                }
            });

            document.getElementById('department_dropdown_list')?.classList.add('hidden');
        }

        function updateDepartments() {
            let schoolId = document.getElementById('school_id_input').value;
            let departmentDisplay = document.getElementById('department_display_text');
            let departmentListInner = document.getElementById('department_dropdown_list_inner');
            let departmentInput = document.getElementById('department_id_input');
            let departmentBtn = document.getElementById('department_custom_btn');
            let deptIconContainer = document.getElementById('dept_icon_container');
            let selectedBadge = document.getElementById('dept_selected_badge');
            let selectedDeptId = "{{ old('department_id', $preselectedDepartmentId ?? null) }}";
            
            if(schoolId) {
                departmentDisplay.textContent = 'Loading departments...';
                departmentBtn.classList.remove('bg-white', 'hover:border-emerald-300');
                departmentBtn.classList.add('bg-slate-50', 'cursor-wait');
                departmentBtn.disabled = true;
                
                fetch('/schools/' + schoolId + '/departments')
                    .then(response => response.json())
                    .then(data => {
                        departmentBtn.classList.remove('bg-slate-100/80', 'bg-slate-50', 'cursor-not-allowed', 'cursor-wait');
                        departmentBtn.classList.add('bg-white', 'hover:border-emerald-400');
                        departmentBtn.disabled = false;
                        if (deptIconContainer) {
                            deptIconContainer.className = 'w-7 h-7 rounded-lg bg-emerald-100/70 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs border border-emerald-200/60';
                        }
                        
                        let html = `
                            <div class="px-3 py-2 cursor-pointer hover:bg-slate-100/80 rounded-xl text-xs text-slate-500 font-semibold transition-colors mb-1" onclick="selectDepartment('', 'Select Department', '')">
                                -- Select Department --
                            </div>
                        `;
                        
                        let matchedSelected = false;
                        
                        if (data && data.length > 0) {
                            data.forEach((dept, index) => {
                                let deptNum = index + 1;
                                let formattedDeptName = dept.name;
                                let isCurrent = (String(dept.id) === String(selectedDeptId));
                                
                                if (isCurrent) {
                                    departmentInput.value = dept.id;
                                    departmentDisplay.textContent = `${deptNum}. ${formattedDeptName}`;
                                    departmentDisplay.classList.add('font-bold', 'text-slate-900');
                                    if (selectedBadge) selectedBadge.classList.remove('hidden');
                                    matchedSelected = true;
                                }
                                
                                html += `
                                    <div class="dept-option p-2.5 cursor-pointer hover:bg-emerald-50/80 rounded-xl text-sm transition-all group flex items-center justify-between gap-3 border ${isCurrent ? 'bg-emerald-50/90 border-emerald-200 text-emerald-900' : 'bg-white border-transparent hover:border-emerald-100'}" 
                                         data-name="${dept.name.toLowerCase()}" 
                                         data-id="${dept.id}"
                                         onclick="selectDepartment('${dept.id}', '${deptNum}. ${formattedDeptName.replace(/'/g, "\\'")}', '${dept.code || ''}')">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs border border-emerald-200">
                                                ${deptNum}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 group-hover:text-emerald-700 text-sm leading-snug whitespace-normal">
                                                    ${deptNum}. ${formattedDeptName}
                                                </p>
                                                <span class="inline-block px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-mono mt-0.5">
                                                    ${dept.code || ''}
                                                </span>
                                            </div>
                                        </div>
                                        <svg class="dept-check-icon ${isCurrent ? '' : 'hidden'} w-4 h-4 text-emerald-600 shrink-0 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                `;
                            });
                            html += '<div class="h-2"></div>';
                        } else {
                            html += `
                                <div class="px-3.5 py-6 text-xs text-slate-400 text-center">
                                    No academic departments registered under this school.
                                </div>
                            `;
                        }
                        
                        departmentListInner.innerHTML = html;
                        if (!matchedSelected) {
                            departmentDisplay.textContent = 'Select Department';
                            departmentDisplay.classList.remove('font-bold', 'text-slate-900');
                            if (selectedBadge) selectedBadge.classList.add('hidden');
                        }
                    })
                    .catch(err => {
                        departmentDisplay.textContent = 'Error loading departments';
                    });
            } else {
                departmentDisplay.textContent = 'Select School first';
                departmentDisplay.classList.remove('font-bold', 'text-slate-900');
                departmentInput.value = '';
                if (selectedBadge) selectedBadge.classList.add('hidden');
                departmentBtn.classList.remove('bg-white', 'hover:border-emerald-400', 'cursor-wait');
                departmentBtn.classList.add('bg-slate-100/80', 'cursor-not-allowed');
                departmentBtn.disabled = true;
                if (deptIconContainer) {
                    deptIconContainer.className = 'w-7 h-7 rounded-lg bg-slate-200/80 text-slate-400 flex items-center justify-center shrink-0';
                }
                departmentListInner.innerHTML = `
                    <div class="px-3.5 py-3 text-xs text-slate-400 font-medium text-center">
                        Please select a School first
                    </div>
                `;
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const initialSchoolId = document.getElementById('school_id_input').value;
            if (initialSchoolId) {
                const option = document.querySelector(`#school_options_container .school-option[data-id="${initialSchoolId}"]`);
                if (option) {
                    const name = option.querySelector('p')?.textContent?.trim();
                    if (name) {
                        selectSchool(initialSchoolId, name, '');
                    }
                } else {
                    updateDepartments();
                }
            }
        });
    </script>
</body>
</html>


