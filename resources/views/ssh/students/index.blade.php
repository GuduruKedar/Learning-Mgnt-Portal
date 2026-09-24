<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Year Students Directory - SSH Department</title>
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
                    <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">1st Year Students Directory</h1>
                    <p class="text-[11px] text-slate-500 font-medium hidden sm:block">Sciences & Humanities • Freshers & 1st Year Management</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <button type="button" onclick="document.getElementById('bulk-upload-modal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 rounded-xl border border-indigo-200 bg-indigo-50/80 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Bulk Upload
                </button>
                <a href="{{ route('ssh.students.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Register Fresher
                </a>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                </div>
                @endif

                <!-- Live Search & Comprehensive Filters Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <form id="filter-form" method="GET" action="{{ route('ssh.students.index') }}" onsubmit="return false;" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
                        
                        <!-- 1. Search Input -->
                        <div class="lg:col-span-6">
                            <label for="search-input" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>Search Student</span>
                                <span class="text-[11px] font-normal text-slate-400 lowercase">live search</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Roll No, Name, Email, Phone..." autocomplete="off" class="w-full text-sm pl-10 pr-9 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-slate-50/50 hover:bg-white transition-all text-slate-800 placeholder-slate-400">
                                <button type="button" id="clear-search-btn" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Parent Branch / Department Select -->
                        <div class="lg:col-span-5">
                            <label for="branch-filter" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Parent Branch / Dept
                            </label>
                            <select id="branch-filter" name="department" class="w-full text-sm py-2 px-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-slate-50/50 hover:bg-white transition-all text-slate-800 cursor-pointer">
                                <option value="">All Branches</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Reset Filter Button -->
                        <div class="lg:col-span-1">
                            <button type="button" id="reset-filter-btn" title="Reset all filters" class="w-full py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-all flex items-center justify-center gap-1.5 border border-slate-200">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span class="hidden sm:inline">Reset</span>
                            </button>
                        </div>
                    </form>

                    <!-- Active Filter Chips Container -->
                    <div id="active-filter-chips" class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-slate-100 {{ (request('search') || request('department') || request('batch')) ? '' : 'hidden' }}">
                        <span class="text-xs font-semibold text-slate-400">Active Filters:</span>
                        <div id="chips-list" class="flex flex-wrap items-center gap-2"></div>
                    </div>
                </div>

                <!-- Students Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" id="students-card">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
                        <div class="flex items-center gap-2.5">
                            <h2 class="text-sm font-bold text-slate-900">First Year Student Roster</h2>
                            <span id="student-count-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <span id="student-count">{{ $students->total() }}</span>&nbsp;Total
                            </span>
                        </div>
                        <div id="live-indicator" class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium opacity-0 transition-opacity duration-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Live Filtered</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto" id="students-table-container">
                        <table class="min-w-full divide-y divide-slate-100 text-sm" id="students-table">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Reg No</th>
                                    <th class="px-6 py-3.5 text-left">Student</th>
                                    <th class="px-6 py-3.5 text-left">Parent Branch / Dept</th>
                                    <th class="px-6 py-3.5 text-left">Program / Batch</th>
                                    <th class="px-6 py-3.5 text-left">Contact Info</th>
                                    <th class="px-6 py-3.5 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="students-tbody">
                                @forelse($students as $student)
                                @php
                                    $firstName = $student->profile?->first_name ?? $student->first_name;
                                    $middleName = $student->profile?->middle_name ?? '';
                                    $lastName = $student->profile?->last_name ?? $student->last_name;
                                    $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);
                                    $deptCode = $student->profile?->departments_id ?? '';
                                    $deptName = $student->profile?->department?->name ?? $deptCode;
                                    $schoolName = $student->profile?->school?->name ?? '';
                                    $academicYear = $student->profile?->academic_year ?? 1;
                                    $semester = $student->profile?->semester ?? 1;
                                    $batchCode = substr($student->username, 0, 2);
                                    $searchString = strtolower($student->username . ' ' . $fullName . ' ' . ($student->profile?->email ?? '') . ' ' . ($student->profile?->phone ?? '') . ' ' . $deptName . ' ' . $schoolName);
                                @endphp
                                <tr class="student-row hover:bg-indigo-50/30 transition-colors"
                                    data-regno="{{ $student->username }}"
                                    data-name="{{ strtolower($fullName) }}"
                                    data-branch="{{ $deptCode }}"
                                    data-branch-name="{{ strtolower($deptName) }}"
                                    data-year="{{ $academicYear }}"
                                    data-batch="{{ $batchCode }}"
                                    data-search="{{ $searchString }}">
                                    
                                    <!-- Reg No -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 text-xs tracking-wider">
                                             {{ $student->username }}
                                        </span>
                                    </td>

                                    <!-- Student Profile & Name -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($student->profile?->photo)
                                                <img class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-sm shrink-0" src="{{ asset('storage/' . $student->profile->photo) }}" alt="{{ $fullName }}">
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                                                    {{ strtoupper(substr($firstName, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-slate-900 text-sm leading-snug">{{ $fullName }}</div>
                                                <div class="inline-flex items-center gap-1.5 text-xs text-slate-500 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span class="font-semibold text-emerald-700">{{ $academicYear == 1 ? '1st Year' : ($academicYear == 2 ? '2nd Year' : ($academicYear == 3 ? '3rd Year' : '4th Year')) }}</span>
                                                    <span class="text-slate-300">•</span>
                                                    <span>Level: {{ $student->profile?->level ?? 'UG' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Parent Branch / Dept -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800 text-sm">
                                            {{ $deptName ?: 'General Studies' }}
                                        </div>
                                        @if($schoolName)
                                        <div class="text-[11px] uppercase tracking-wider text-slate-400 font-medium mt-0.5">
                                            {{ $schoolName }}
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Program / Batch -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $student->profile?->program?->name ?? 'General First Year' }}
                                        </span>
                                        <div class="text-[11px] text-slate-400 mt-0.5">Batch 20{{ $batchCode }}</div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-6 py-4 text-xs text-slate-600">
                                        @if($student->profile?->email)
                                        <div class="flex items-center gap-1.5 text-slate-700 hover:text-indigo-600">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <a href="mailto:{{ $student->profile->email }}" class="truncate max-w-[180px]">{{ $student->profile->email }}</a>
                                        </div>
                                        @else
                                        <div class="text-slate-400 italic">No email</div>
                                        @endif

                                        @if($student->profile?->phone)
                                        <div class="flex items-center gap-1.5 text-slate-500 mt-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            <span>{{ $student->profile->phone }}</span>
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('ssh.students.edit', $student->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/90 text-indigo-600 border border-indigo-200/70 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Student">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button type="button" onclick="openManualResetPasswordModal('{{ route('users.reset-password', $student->id) }}', '{{ $student->username }}', '{{ addslashes($fullName) }}', 'Student#963')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50/90 text-amber-600 border border-amber-200/70 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Reset Password for {{ $student->username }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            </button>
                                            <form method="POST" action="{{ route('ssh.students.destroy', $student->id) }}" onsubmit="return confirm('Are you sure you want to delete student {{ $student->username }} ({{ $fullName }})?')" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Student">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr id="no-students-server">
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                        <p class="font-semibold text-slate-600">No first year students found</p>
                                        <p class="text-xs text-slate-400 mt-1">Try resetting your filters or register a new fresher above.</p>
                                    </td>
                                </tr>
                                @endforelse

                                <!-- Dynamic Client-Side Empty State Row (Hidden by default) -->
                                <tr id="client-empty-state" class="hidden">
                                    <td colspan="6" class="px-6 py-14 text-center">
                                        <div class="max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-800">No matching students found</h3>
                                            <p class="text-xs text-slate-500 mt-1">No 1st year students match your current search, branch, or year filters.</p>
                                            <button type="button" onclick="resetAllFilters()" class="mt-4 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs rounded-xl transition-all inline-flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                Clear Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination & Controls Footer Bar -->
                    <div id="pagination-container" class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Left: Record summary and rows-per-page selector -->
                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-medium w-full sm:w-auto justify-between sm:justify-start">
                            <div>
                                Showing 
                                <span class="font-bold text-slate-800">{{ $students->firstItem() ?? ($students->count() > 0 ? 1 : 0) }}</span> 
                                to 
                                <span class="font-bold text-slate-800">{{ $students->lastItem() ?? $students->count() }}</span> 
                                of 
                                <span class="font-bold text-slate-800">{{ $students->total() }}</span> 
                                students
                            </div>
                            
                            <div class="flex items-center gap-1.5 pl-3 border-l border-slate-200">
                                <span class="text-slate-500">Per page:</span>
                                <select id="per-page-select" onchange="changePerPage(this.value)" class="text-xs py-1 px-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-bold focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer shadow-2xs">
                                    <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right: Page navigation links -->
                        <div class="flex items-center justify-end w-full sm:w-auto">
                            @if($students->hasPages())
                                {{ $students->appends(request()->query())->links() }}
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-500 shadow-2xs">
                                    Page 1 of 1
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Bulk Upload Freshers Modal -->
    <div id="bulk-upload-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 space-y-5 animate-scale-up">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Bulk Onboard 1st Year Freshers</h3>
                    <p class="text-xs text-slate-500">Upload CSV / Excel sheet containing freshers list</p>
                </div>
                <button type="button" onclick="document.getElementById('bulk-upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-xl font-bold leading-none">&times;</button>
            </div>

            <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 flex items-center justify-between text-xs">
                <div>
                    <div class="font-bold text-indigo-900">Need the standard spreadsheet format?</div>
                    <div class="text-indigo-700 text-[11px]">Download our pre-formatted fresher template with branch fields.</div>
                </div>
                <a href="{{ route('ssh.students.template') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shrink-0 shadow-sm transition-colors">
                    Download Template
                </a>
            </div>

            <form method="POST" action="{{ route('ssh.students.bulk') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Select CSV or Excel File *</label>
                    <input type="file" name="file" required accept=".csv,.xlsx,.xls,.txt" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Accepts CSV, XLSX files up to 10MB.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('bulk-upload-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 font-semibold text-xs text-slate-700 hover:bg-slate-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-colors">
                        Start Bulk Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Filtering Engine Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const clearSearchBtn = document.getElementById('clear-search-btn');
            const branchFilter = document.getElementById('branch-filter');
            const resetFilterBtn = document.getElementById('reset-filter-btn');
            const studentCountSpan = document.getElementById('student-count');
            const liveIndicator = document.getElementById('live-indicator');
            const clientEmptyState = document.getElementById('client-empty-state');
            const chipsContainer = document.getElementById('active-filter-chips');
            const chipsList = document.getElementById('chips-list');
            
            let debounceTimer = null;

            function updateClearButton() {
                if (searchInput.value.trim().length > 0) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
            }

            function renderChips() {
                const searchVal = searchInput.value.trim();
                const branchVal = branchFilter.value;
                const branchText = branchFilter.options[branchFilter.selectedIndex]?.text || '';

                let chips = [];

                if (searchVal) {
                    chips.push({ label: `Search: "${searchVal}"`, type: 'search' });
                }
                if (branchVal) {
                    chips.push({ label: `Branch: ${branchText}`, type: 'branch' });
                }

                if (chips.length > 0) {
                    chipsContainer.classList.remove('hidden');
                    chipsList.innerHTML = chips.map(c => `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100 animate-fade-in">
                            <span>${c.label}</span>
                            <button type="button" onclick="removeFilter('${c.type}')" class="hover:text-indigo-900 text-sm leading-none font-bold ml-0.5">&times;</button>
                        </span>
                    `).join('');
                } else {
                    chipsContainer.classList.add('hidden');
                    chipsList.innerHTML = '';
                }
            }

            window.removeFilter = function(type) {
                if (type === 'search') {
                    searchInput.value = '';
                    updateClearButton();
                } else if (type === 'branch') {
                    branchFilter.value = '';
                }
                applyLiveFilter();
            };

            window.resetAllFilters = function() {
                searchInput.value = '';
                branchFilter.value = '';
                updateClearButton();
                applyLiveFilter();
            };

            function applyClientFilter() {
                const searchVal = searchInput.value.trim().toLowerCase();
                const branchVal = branchFilter.value.trim().toLowerCase();

                const rows = document.querySelectorAll('.student-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowSearch = row.getAttribute('data-search') || '';
                    const rowBranch = (row.getAttribute('data-branch') || '').toLowerCase();

                    let matchesSearch = !searchVal || rowSearch.includes(searchVal);
                    let matchesBranch = !branchVal || rowBranch === branchVal;

                    if (matchesSearch && matchesBranch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (studentCountSpan) {
                    studentCountSpan.textContent = visibleCount;
                }

                if (visibleCount === 0 && rows.length > 0) {
                    clientEmptyState.classList.remove('hidden');
                } else {
                    clientEmptyState.classList.add('hidden');
                }

                // Flash live indicator
                if (searchVal || branchVal) {
                    liveIndicator.classList.remove('opacity-0');
                    liveIndicator.classList.add('opacity-100');
                } else {
                    liveIndicator.classList.remove('opacity-100');
                    liveIndicator.classList.add('opacity-0');
                }

                renderChips();
            }

            window.changePerPage = function(val) {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', val);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            };

            function syncWithServer() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const params = new URLSearchParams(window.location.search);
                    const s = searchInput.value.trim();
                    const d = branchFilter.value.trim();
                    const perPageEl = document.getElementById('per-page-select');
                    const perPage = perPageEl ? perPageEl.value : '15';

                    if (s) params.set('search', s); else params.delete('search');
                    if (d) params.set('department', d); else params.delete('department');
                    params.delete('section');
                    params.set('per_page', perPage);
                    params.delete('page');

                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.history.replaceState({}, '', newUrl.endsWith('?') ? window.location.pathname : newUrl);

                    fetch(newUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newTbody = doc.getElementById('students-tbody');
                        const newCountBadge = doc.getElementById('student-count-badge');
                        const newPagination = doc.getElementById('pagination-container');

                        if (newTbody) {
                            document.getElementById('students-tbody').innerHTML = newTbody.innerHTML;
                        }
                        if (newCountBadge && studentCountSpan) {
                            const newCount = doc.getElementById('student-count');
                            if (newCount) studentCountSpan.textContent = newCount.textContent;
                        }
                        
                        const currentPagination = document.getElementById('pagination-container');
                        if (newPagination && currentPagination) {
                            currentPagination.innerHTML = newPagination.innerHTML;
                        }
                    })
                    .catch(err => {
                        console.error('Server sync failed:', err);
                    });
                }, 250);
            }

            function applyLiveFilter() {
                updateClearButton();
                applyClientFilter();
                syncWithServer();
            }

            // Event Listeners
            searchInput.addEventListener('input', applyLiveFilter);
            branchFilter.addEventListener('change', applyLiveFilter);
            
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
                applyLiveFilter();
            });

            resetFilterBtn.addEventListener('click', function() {
                resetAllFilters();
            });

            updateClearButton();
            renderChips();
        });
    </script>
    @include('partials.reset_password_modal')
</body>
</html>
