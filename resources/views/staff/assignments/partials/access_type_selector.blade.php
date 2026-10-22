@php
    $prefix = $prefix ?? 'main';
    $currentType = old('type', $type ?? 'public');
    $assignedIds = old('student_ids', $assignedStudentIds ?? []);
    if (!is_array($assignedIds)) {
        $assignedIds = [];
    }
@endphp

<div class="space-y-4" id="{{ $prefix }}-access-type-wrapper">
    <!-- Header -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-[#273244]">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/40 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Assignment Visibility & Access Type</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Choose whether all course students or only a specific cohort / Excel student list can access this assignment</p>
            </div>
        </div>
    </div>

    <!-- Access Type Toggle Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        <!-- Option 1: Public Assignment -->
        <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 {{ $currentType === 'public' ? 'border-emerald-500 bg-emerald-50/40 dark:bg-emerald-950/20 shadow-xs' : 'border-slate-200 dark:border-[#273244] bg-white dark:bg-[#151B23] hover:border-slate-300 dark:hover:border-slate-700' }}" id="{{ $prefix }}-card-public">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Public Assignment</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">Common to All</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Every student enrolled in this course can view, take, and submit this assignment.</p>
                    </div>
                </div>
                <input type="radio" name="type" value="public" class="mt-1 text-emerald-600 focus:ring-emerald-500" {{ $currentType === 'public' ? 'checked' : '' }} onchange="toggleAccessType('{{ $prefix }}', 'public')">
            </div>
        </label>

        <!-- Option 2: Private Assignment -->
        <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 {{ $currentType === 'private' ? 'border-amber-500 bg-amber-50/40 dark:bg-amber-950/20 shadow-xs' : 'border-slate-200 dark:border-[#273244] bg-white dark:bg-[#151B23] hover:border-slate-300 dark:hover:border-slate-700' }}" id="{{ $prefix }}-card-private">
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Private Assignment</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300">Restricted Access</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Only target students chosen via Cohort Filters or uploaded from an Excel sheet can access.</p>
                    </div>
                </div>
                <input type="radio" name="type" value="private" class="mt-1 text-amber-600 focus:ring-amber-500" {{ $currentType === 'private' ? 'checked' : '' }} onchange="toggleAccessType('{{ $prefix }}', 'private')">
            </div>
        </label>
    </div>

    <!-- Private Access Configuration Panel (Shown only when type=private) -->
    <div id="{{ $prefix }}-private-config" class="{{ $currentType === 'private' ? '' : 'hidden' }} bg-white dark:bg-[#151B23] rounded-2xl border border-amber-200/80 dark:border-amber-900/40 p-5 sm:p-6 space-y-5 shadow-xs">
        
        <!-- Private Header Bar & Counter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Designate Private Allowed Students</span>
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Upload student register numbers from Excel or filter and pick cohort students directly below.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                    <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span id="{{ $prefix }}-selected-count-badge">{{ count($assignedIds) }} Selected</span>
                </span>
            </div>
        </div>

        <!-- Two Interactive Modes: 1. Excel File Upload | 2. Cohort Filter & Selection -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <!-- Left Panel: Excel / CSV Student List Upload (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-4 bg-slate-50 dark:bg-[#1C2430] p-4 sm:p-5 rounded-xl border border-slate-200/80 dark:border-[#273244]">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-[#273244]">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-md bg-emerald-600 text-white text-xs font-black flex items-center justify-center">1</span>
                        <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">Upload Student Excel</h5>
                    </div>
                    <a href="{{ route('staff.assignments.students.template') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline" title="Download Excel Format for Students">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Template (.xlsx)</span>
                    </a>
                </div>

                <!-- Dropzone / File Picker -->
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-xl p-4 text-center bg-white dark:bg-[#151B23] transition-colors relative">
                    <input type="file" name="student_excel" id="{{ $prefix }}-student-excel" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleStudentExcelUpload(this, '{{ $prefix }}')">
                    <div class="space-y-1.5 pointer-events-none">
                        <div class="w-9 h-9 mx-auto rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Choose or Drag Student Excel (.xlsx / .csv)</p>
                        <p class="text-[11px] text-slate-400">Single column with header: <strong class="font-mono text-indigo-600 dark:text-indigo-400">register_number</strong></p>
                    </div>
                </div>

                <!-- Status Feedback Box -->
                <div id="{{ $prefix }}-excel-status" class="hidden text-xs p-3 rounded-lg border font-medium"></div>

                <!-- Quick Register Number Paste Box -->
                <div class="space-y-1.5 pt-2 border-t border-slate-200/60 dark:border-[#273244]">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Or Paste Register Numbers</label>
                        <button type="button" onclick="applyPastedRegisterNumbers('{{ $prefix }}')" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">Match & Select</button>
                    </div>
                    <textarea name="student_register_numbers" id="{{ $prefix }}-student-reg-paste" rows="2" placeholder="e.g. 720721104001, 720721104002&#10;720721104003" class="w-full text-xs font-mono border border-slate-200 dark:border-[#273244] rounded-lg p-2.5 bg-white dark:bg-[#151B23] text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            </div>

            <!-- Right Panel: Cohort Filters & Interactive Student Roster (lg:col-span-7) -->
            <div class="lg:col-span-7 space-y-4 bg-slate-50 dark:bg-[#1C2430] p-4 sm:p-5 rounded-xl border border-slate-200/80 dark:border-[#273244] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-[#273244]">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-indigo-600 text-white text-xs font-black flex items-center justify-center">2</span>
                            <h5 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-slate-200">Cohort Student Selector</h5>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="selectAllCohortStudents('{{ $prefix }}', true)" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">Select All Filtered</button>
                            <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                            <button type="button" onclick="selectAllCohortStudents('{{ $prefix }}', false)" class="text-[11px] font-bold text-slate-500 dark:text-slate-400 hover:underline cursor-pointer">Clear</button>
                        </div>
                    </div>

                    <!-- Filter Controls (Year, Semester, Program) -->
                    <div class="grid grid-cols-3 gap-2 pt-3 mb-3">

                        <!-- Year Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Year</label>
                            <select id="{{ $prefix }}-cohort-year" onchange="handleCohortYearChange('{{ $prefix }}')" class="w-full text-xs border border-slate-200 dark:border-[#273244] rounded-lg p-1.5 bg-white dark:bg-[#151B23] text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500">
                                <option value="all">All Years</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                        </div>

                        <!-- Semester Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Semester</label>
                            <select id="{{ $prefix }}-cohort-sem" onchange="fetchCohortStudents('{{ $prefix }}')" class="w-full text-xs border border-slate-200 dark:border-[#273244] rounded-lg p-1.5 bg-white dark:bg-[#151B23] text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500">
                                <option value="all" selected>All Semesters</option>
                                <option value="1">Sem 1 (1-1)</option>
                                <option value="2">Sem 2 (1-2)</option>
                            </select>
                        </div>

                        <!-- Program Filter -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Program</label>
                            <select id="{{ $prefix }}-cohort-prog" onchange="fetchCohortStudents('{{ $prefix }}')" class="w-full text-xs border border-slate-200 dark:border-[#273244] rounded-lg p-1.5 bg-white dark:bg-[#151B23] text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-indigo-500">
                                <option value="all" selected>All Programs</option>
                                @if(isset($programs))
                                    @foreach($programs as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Year & Semester Validation Banner -->
                    <div id="{{ $prefix }}-year-warning" class="hidden mb-3 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-2"></div>

                    <!-- Search Box -->
                    <div class="relative mb-3">
                        <input type="text" id="{{ $prefix }}-cohort-search" oninput="filterCohortStudentList('{{ $prefix }}')" placeholder="Search student name, register number..." class="w-full text-xs border border-slate-200 dark:border-[#273244] rounded-lg pl-8 pr-3 py-1.5 bg-white dark:bg-[#151B23] text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:ring-1 focus:ring-indigo-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <!-- Student Roster Box (Scrollable) -->
                    <div class="border border-slate-200/80 dark:border-[#273244] rounded-xl overflow-hidden bg-white dark:bg-[#151B23]">
                        <div class="max-h-56 overflow-y-auto divide-y divide-slate-100 dark:divide-[#273244]" id="{{ $prefix }}-student-roster">
                            <!-- Populated dynamically via JS -->
                            <div class="p-4 text-center text-xs text-slate-400" id="{{ $prefix }}-roster-loading">
                                <span class="inline-block animate-spin mr-1.5">&#9696;</span> Loading cohort students for selected course...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2 text-[11px] text-slate-500 dark:text-slate-400 flex items-center justify-between">
                    <span id="{{ $prefix }}-roster-stats">Showing 0 students</span>
                    <span class="text-amber-600 dark:text-amber-400 font-semibold">* Only checked students will have private test access</span>
                </div>
            </div>

        </div>

        <!-- Hidden input sink for all selected student IDs -->
        <div id="{{ $prefix }}-hidden-selected-inputs"></div>

    </div>
</div>

<script>
    // Initialize student selection state for this prefix
    window.accessSelectorStates = window.accessSelectorStates || {};
    window.accessSelectorStates['{{ $prefix }}'] = {
        selectedIds: new Set({!! json_encode(array_map('intval', $assignedIds)) !!}),
        cachedStudents: []
    };

    function toggleAccessType(prefix, type) {
        const configPanel = document.getElementById(prefix + '-private-config');
        const cardPublic = document.getElementById(prefix + '-card-public');
        const cardPrivate = document.getElementById(prefix + '-card-private');

        if (type === 'private') {
            configPanel.classList.remove('hidden');
            cardPrivate.classList.add('border-amber-500', 'bg-amber-50/40', 'dark:bg-amber-950/20', 'shadow-xs');
            cardPrivate.classList.remove('border-slate-200', 'dark:border-[#273244]');
            cardPublic.classList.remove('border-emerald-500', 'bg-emerald-50/40', 'dark:bg-emerald-950/20', 'shadow-xs');
            cardPublic.classList.add('border-slate-200', 'dark:border-[#273244]');
            // Fetch cohort students if not already fetched
            fetchCohortStudents(prefix);
        } else {
            configPanel.classList.add('hidden');
            cardPublic.classList.add('border-emerald-500', 'bg-emerald-50/40', 'dark:bg-emerald-950/20', 'shadow-xs');
            cardPublic.classList.remove('border-slate-200', 'dark:border-[#273244]');
            cardPrivate.classList.remove('border-amber-500', 'bg-amber-50/40', 'dark:bg-amber-950/20', 'shadow-xs');
            cardPrivate.classList.add('border-slate-200', 'dark:border-[#273244]');
        }
    }

    function getSelectedCourseId(prefix) {
        const form = document.getElementById(prefix + '-access-type-wrapper').closest('form');
        if (!form) return null;
        const courseSelect = form.querySelector('select[name="course_id"]');
        return courseSelect ? courseSelect.value : null;
    }

    function handleCohortYearChange(prefix) {
        const yearSelect = document.getElementById(prefix + '-cohort-year');
        const semSelect = document.getElementById(prefix + '-cohort-sem');
        if (!yearSelect || !semSelect) return;

        const selectedYear = yearSelect.value;
        const currentSem = semSelect.value;

        let opts = '<option value="all">All Semesters</option>';
        let validSems = [];

        if (selectedYear === '1') {
            opts += '<option value="1">Sem 1 (1-1)</option>';
            opts += '<option value="2">Sem 2 (1-2)</option>';
            validSems = ['1', '2'];
        } else if (selectedYear === '2') {
            opts += '<option value="3">Sem 3 (2-1)</option>';
            opts += '<option value="4">Sem 4 (2-2)</option>';
            validSems = ['3', '4'];
        } else if (selectedYear === '3') {
            opts += '<option value="5">Sem 5 (3-1)</option>';
            opts += '<option value="6">Sem 6 (3-2)</option>';
            validSems = ['5', '6'];
        } else if (selectedYear === '4') {
            opts += '<option value="7">Sem 7 (4-1)</option>';
            opts += '<option value="8">Sem 8 (4-2)</option>';
            validSems = ['7', '8'];
        } else {
            for (let s = 1; s <= 8; s++) {
                const yr = Math.ceil(s / 2);
                const semInYr = (s % 2 === 1) ? 1 : 2;
                opts += `<option value="${s}">Sem ${s} (Yr ${yr}-${semInYr})</option>`;
                validSems.push(String(s));
            }
        }

        semSelect.innerHTML = opts;
        if (validSems.includes(currentSem)) {
            semSelect.value = currentSem;
        } else {
            semSelect.value = 'all';
        }

        fetchCohortStudents(prefix);
    }

    function fetchCohortStudents(prefix) {
        const courseId = getSelectedCourseId(prefix);
        const rosterContainer = document.getElementById(prefix + '-student-roster');
        const statsLabel = document.getElementById(prefix + '-roster-stats');

        if (!courseId) {
            rosterContainer.innerHTML = '<div class="p-4 text-center text-xs text-amber-600">Please choose an assigned course first to view cohort students.</div>';
            return;
        }

        const yearSelect = document.getElementById(prefix + '-cohort-year');
        const semSelect = document.getElementById(prefix + '-cohort-sem');
        const year = yearSelect ? yearSelect.value : 'all';
        const sem = semSelect ? semSelect.value : 'all';
        const prog = document.getElementById(prefix + '-cohort-prog').value;
        const search = document.getElementById(prefix + '-cohort-search').value;

        rosterContainer.innerHTML = '<div class="p-4 text-center text-xs text-slate-400"><span class="inline-block animate-spin mr-1.5">&#9696;</span> Fetching enrolled course students...</div>';

        const url = `/staff/courses/${courseId}/cohort-students?academic_year=${encodeURIComponent(year)}&semester=${encodeURIComponent(sem)}&program=${encodeURIComponent(prog)}&search=${encodeURIComponent(search)}`;

        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    rosterContainer.innerHTML = `<div class="p-4 text-center text-xs text-red-500">${data.error}</div>`;
                    return;
                }

                // Auto-sync Year dropdown with selected course on first fetch
                if (!window.accessSelectorStates[prefix].hasInitializedYear && data.course_year) {
                    window.accessSelectorStates[prefix].hasInitializedYear = true;
                    if (yearSelect && yearSelect.value === 'all') {
                        yearSelect.value = String(data.course_year);
                        // Update semester options for course's year
                        let autoOpts = '<option value="all">All Semesters</option>';
                        const cYr = parseInt(data.course_year);
                        if (cYr === 1) {
                            autoOpts += '<option value="1">Sem 1 (1-1)</option><option value="2">Sem 2 (1-2)</option>';
                        } else if (cYr === 2) {
                            autoOpts += '<option value="3">Sem 3 (2-1)</option><option value="4">Sem 4 (2-2)</option>';
                        } else if (cYr === 3) {
                            autoOpts += '<option value="5">Sem 5 (3-1)</option><option value="6">Sem 6 (3-2)</option>';
                        } else if (cYr === 4) {
                            autoOpts += '<option value="7">Sem 7 (4-1)</option><option value="8">Sem 8 (4-2)</option>';
                        }
                        semSelect.innerHTML = autoOpts;
                    }
                }

                // Year & Course Mismatch Warning validation
                const warnBox = document.getElementById(prefix + '-year-warning');
                if (warnBox) {
                    if (data.course_year && year !== 'all' && String(year) !== String(data.course_year)) {
                        warnBox.classList.remove('hidden');
                        warnBox.innerHTML = `
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span><strong>Year Filter Notice:</strong> <code>${data.course_code}</code> is a <strong>Year ${data.course_year}</strong> course. Filtering by <strong>Year ${year}</strong> may show 0 enrolled students for this subject.</span>
                        `;
                    } else {
                        warnBox.classList.add('hidden');
                        warnBox.innerHTML = '';
                    }
                }

                if (data.programs && Array.isArray(data.programs) && data.programs.length > 0) {
                    const progSelect = document.getElementById(prefix + '-cohort-prog');
                    if (progSelect) {
                        const cur = progSelect.value;
                        let opts = '<option value="all">All Programs</option>';
                        data.programs.forEach(p => {
                            const sel = (cur === String(p.id) || cur === p.name) ? 'selected' : '';
                            opts += `<option value="${p.id}" ${sel}>${p.name}</option>`;
                        });
                        progSelect.innerHTML = opts;
                    }
                }

                window.accessSelectorStates[prefix].cachedStudents = data.students || [];
                renderCohortStudentRoster(prefix);
            })
            .catch(err => {
                console.error(err);
                rosterContainer.innerHTML = '<div class="p-4 text-center text-xs text-red-500">Failed to load cohort students. Please check your network.</div>';
            });
    }

    function renderCohortStudentRoster(prefix) {
        const state = window.accessSelectorStates[prefix];
        const rosterContainer = document.getElementById(prefix + '-student-roster');
        const statsLabel = document.getElementById(prefix + '-roster-stats');
        const searchTerm = document.getElementById(prefix + '-cohort-search').value.toLowerCase().trim();

        const filtered = state.cachedStudents.filter(stu => {
            if (!searchTerm) return true;
            return (stu.name && stu.name.toLowerCase().includes(searchTerm)) ||
                   (stu.username && stu.username.toLowerCase().includes(searchTerm)) ||
                   (stu.email && stu.email.toLowerCase().includes(searchTerm));
        });

        if (filtered.length === 0) {
            rosterContainer.innerHTML = '<div class="p-6 text-center text-xs text-slate-400">No students matched the chosen cohort criteria.</div>';
            statsLabel.textContent = 'Showing 0 students';
            return;
        }

        statsLabel.textContent = `Showing ${filtered.length} student${filtered.length === 1 ? '' : 's'}`;

        let html = '';
        filtered.forEach(stu => {
            const isChecked = state.selectedIds.has(stu.id);
            html += `
                <label class="flex items-center justify-between p-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors text-xs select-none">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <input type="checkbox" value="${stu.id}" ${isChecked ? 'checked' : ''} onchange="toggleStudentSelection('${prefix}', ${stu.id}, this.checked)" class="rounded text-indigo-600 focus:ring-indigo-500 student-checkbox-${prefix}">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded text-[11px]">${stu.username}</span>
                                <span class="font-semibold text-slate-900 dark:text-white truncate max-w-[160px]">${stu.name}</span>
                            </div>
                            <div class="text-[10px] text-slate-400 truncate mt-0.5">${stu.program} ${stu.academic_year !== 'N/A' ? '• Yr ' + stu.academic_year : ''} ${stu.semester !== 'N/A' ? 'Sem ' + stu.semester : ''}</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded ${isChecked ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold' : 'text-slate-400'}">
                        ${isChecked ? 'Granted' : 'No Access'}
                    </span>
                </label>
            `;
        });

        rosterContainer.innerHTML = html;
        updateSelectedBadge(prefix);
    }

    function toggleStudentSelection(prefix, studentId, isChecked) {
        const state = window.accessSelectorStates[prefix];
        if (isChecked) {
            state.selectedIds.add(studentId);
        } else {
            state.selectedIds.delete(studentId);
        }
        updateSelectedBadge(prefix);
    }

    function selectAllCohortStudents(prefix, selectAll) {
        const state = window.accessSelectorStates[prefix];
        const checkboxes = document.querySelectorAll(`.student-checkbox-${prefix}`);
        
        checkboxes.forEach(cb => {
            cb.checked = selectAll;
            const stuId = parseInt(cb.value);
            if (selectAll) {
                state.selectedIds.add(stuId);
            } else {
                state.selectedIds.delete(stuId);
            }
        });

        renderCohortStudentRoster(prefix);
        updateSelectedBadge(prefix);
    }

    function updateSelectedBadge(prefix) {
        const state = window.accessSelectorStates[prefix];
        const badge = document.getElementById(prefix + '-selected-count-badge');
        if (badge) {
            badge.textContent = `${state.selectedIds.size} Selected`;
        }
    }

    function filterCohortStudentList(prefix) {
        renderCohortStudentRoster(prefix);
    }

    function handleStudentExcelUpload(input, prefix) {
        const file = input.files[0];
        if (!file) return;

        const statusBox = document.getElementById(prefix + '-excel-status');
        statusBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-red-50', 'text-red-800', 'border-red-200');
        statusBox.classList.add('bg-indigo-50', 'text-indigo-800', 'border-indigo-200');
        statusBox.innerHTML = '<span class="inline-block animate-spin mr-1.5">&#9696;</span> Reading spreadsheet & matching student register numbers...';

        const formData = new FormData();
        formData.append('student_excel', file);
        const courseId = getSelectedCourseId(prefix);
        if (courseId) {
            formData.append('course_id', courseId);
        }
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('staff.assignments.parse-students') }}', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                statusBox.classList.remove('bg-indigo-50', 'text-indigo-800', 'border-indigo-200');
                statusBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
                statusBox.textContent = data.error;
                return;
            }

            const state = window.accessSelectorStates[prefix];
            if (data.students && data.students.length > 0) {
                data.students.forEach(s => {
                    state.selectedIds.add(s.id);
                    // Add to cached students list if not present
                    if (!state.cachedStudents.some(cs => cs.id === s.id)) {
                        state.cachedStudents.unshift(s);
                    }
                });
            }

            statusBox.classList.remove('bg-indigo-50', 'text-indigo-800', 'border-indigo-200');
            statusBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
            statusBox.innerHTML = `<strong>✓ Excel Processed:</strong> ${data.message}`;

            renderCohortStudentRoster(prefix);
            updateSelectedBadge(prefix);
        })
        .catch(err => {
            console.error(err);
            statusBox.classList.remove('bg-indigo-50', 'text-indigo-800', 'border-indigo-200');
            statusBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
            statusBox.textContent = 'Failed to process Excel spreadsheet. Please ensure valid file format.';
        });
    }

    function applyPastedRegisterNumbers(prefix) {
        const raw = document.getElementById(prefix + '-student-reg-paste').value.trim();
        if (!raw) return;

        const regs = raw.split(/[\s,;]+/).map(r => r.trim()).filter(Boolean);
        if (regs.length === 0) return;

        const state = window.accessSelectorStates[prefix];
        const statusBox = document.getElementById(prefix + '-excel-status');

        // Check against currently cached students first
        let matchedCount = 0;
        state.cachedStudents.forEach(stu => {
            if (regs.includes(stu.username)) {
                state.selectedIds.add(stu.id);
                matchedCount++;
            }
        });

        statusBox.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200');
        statusBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
        statusBox.innerHTML = `<strong>✓ Matched:</strong> ${matchedCount} student(s) selected from pasted list. Remaining will be matched on submit.`;

        renderCohortStudentRoster(prefix);
        updateSelectedBadge(prefix);
    }

    // Bind course switcher to auto-reload cohort students if private and handle form validation
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('{{ $prefix }}-access-type-wrapper');
        if (wrapper) {
            const form = wrapper.closest('form');
            if (form) {
                const courseSelect = form.querySelector('select[name="course_id"]');
                if (courseSelect) {
                    courseSelect.addEventListener('change', () => {
                        const privateRadio = form.querySelector('input[name="type"][value="private"]');
                        if (privateRadio && privateRadio.checked) {
                            fetchCohortStudents('{{ $prefix }}');
                        }
                    });
                }

                form.addEventListener('submit', function(e) {
                    const privateRadio = form.querySelector('input[name="type"][value="private"]');
                    if (privateRadio && privateRadio.checked) {
                        const state = window.accessSelectorStates['{{ $prefix }}'];
                        const selectedCount = state ? state.selectedIds.size : 0;
                        const excelFile = document.getElementById('{{ $prefix }}-student-excel');
                        const hasExcel = excelFile && excelFile.files && excelFile.files.length > 0;
                        const regPaste = document.getElementById('{{ $prefix }}-student-reg-paste');
                        const hasPaste = regPaste && regPaste.value.trim().length > 0;

                        if (selectedCount === 0 && !hasExcel && !hasPaste) {
                            e.preventDefault();
                            e.stopPropagation();
                            const statusBox = document.getElementById('{{ $prefix }}-excel-status');
                            if (statusBox) {
                                statusBox.classList.remove('hidden', 'bg-indigo-50', 'text-indigo-800', 'border-indigo-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                                statusBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200', 'dark:bg-red-950/40', 'dark:text-red-300', 'dark:border-red-900/60');
                                statusBox.innerHTML = '<div class="flex items-center gap-2"><svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><span><strong>Validation Error:</strong> For a Private Assignment, you must select cohort students, upload an Excel file of students, or paste register numbers.</span></div>';
                                statusBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                alert('For a Private Assignment, please select cohort students, upload a student Excel file, or enter register numbers.');
                            }
                            return false;
                        }

                        // Synchronize state.selectedIds into hidden form inputs
                        const hiddenContainer = document.getElementById('{{ $prefix }}-hidden-selected-inputs');
                        if (hiddenContainer && state) {
                            hiddenContainer.innerHTML = '';
                            state.selectedIds.forEach(id => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'student_ids[]';
                                input.value = id;
                                hiddenContainer.appendChild(input);
                            });
                        }
                    }
                });
            }
        }
        @if($currentType === 'private')
            fetchCohortStudents('{{ $prefix }}');
        @endif
    });
</script>
