<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create MCQ Assignment - LMS Faculty</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 bg-slate-50/60 dark:bg-[#0D1117]">
            <div class="w-full space-y-6">

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/40 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-xs flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 dark:bg-red-950/40 border-l-4 border-red-500 p-4 rounded-r-xl shadow-xs flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 dark:bg-red-950/40 border-l-4 border-red-500 p-4 rounded-r-xl shadow-xs">
                    <h4 class="text-sm font-bold text-red-800 dark:text-red-300 mb-1">Please fix the following validation errors:</h4>
                    <ul class="text-xs text-red-700 dark:text-red-400 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Page Header -->
                <div class="border-b border-slate-200 dark:border-[#273244] pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-[#F8FAFC] tracking-tight">Create Assignment</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-[#94A3B8]">Build interactive Multiple Choice Questions (MCQ) assessments.</p>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('staff.assignments.template') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs hover:shadow transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Download Excel Template</span>
                        </a>
                    </div>
                </div>

                <!-- Mode Tabs Card -->
                <div class="bg-white dark:bg-[#151B23] rounded-2xl shadow-sm border border-slate-200/80 dark:border-[#273244] overflow-hidden relative z-10">
                    <div class="border-b border-slate-200/80 dark:border-[#273244] bg-slate-50/50 dark:bg-[#1C2430] flex">
                        <button type="button" id="tab-bulk-btn" onclick="switchTab('bulk')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 flex items-center transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Bulk Excel Upload
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800/60">Recommended</span>
                        </button>
                        <button type="button" id="tab-single-btn" onclick="switchTab('single')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 flex items-center transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Manual MCQ Form / Interactive Builder
                        </button>
                    </div>

                    <!-- Bulk Excel Upload Panel -->
                    <div id="tab-bulk-content" class="p-6 sm:p-8 bg-white dark:bg-[#151B23] space-y-6">
                        <form action="{{ route('staff.assignments.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="bulk-upload-form">
                            @csrf
                            <input type="hidden" name="file_base64" id="file_base64">
                            <input type="hidden" name="file_name" id="file_name">

                            <!-- Step 1: Assignment Details -->
                            <div class="bg-slate-50/60 dark:bg-[#1C2430] rounded-2xl border border-slate-200/80 dark:border-[#273244] shadow-xs p-6 space-y-5">
                                <div class="flex items-center gap-3 pb-3.5 border-b border-slate-200/60 dark:border-[#273244]">
                                    <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white text-xs font-extrabold flex items-center justify-center shadow-xs">1</span>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-[#F8FAFC]">Assignment Details</h3>
                                        <p class="text-xs text-slate-500 dark:text-[#94A3B8]">Select course and set assignment parameters (no need to specify in Excel)</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- Course -->
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            Assigned Course <span class="text-red-500">*</span>
                                        </label>
                                        <select name="course_id" class="tomselect-course w-full text-sm" required>
                                            <option value="">-- Choose Assigned Course --</option>
                                            @foreach($assignedCourses as $course)
                                                <option value="{{ $course->id }}" {{ (old('course_id', $selectedCourseId) == $course->id) ? 'selected' : '' }}>
                                                    {{ $course->code }} - {{ $course->name }} @if($course->regulation)({{ $course->regulation->code ?: $course->regulation->name }}{{ !empty($course->regulation->curriculum) ? ' • ' . $course->regulation->curriculum : '' }})@endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Assignment Title -->
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            MCQ Assignment Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Unit 1 MCQ Quiz: Fundamentals & Core Concepts" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                    </div>

                                    <!-- Deadline / Due Date -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            Submission Deadline <span class="text-red-500">*</span>
                                        </label>
                                        <input type="datetime-local" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all font-mono">
                                    </div>

                                    <!-- Publish Status -->
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            Publish Status <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all cursor-pointer">
                                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Active for Students)</option>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                                        </select>
                                    </div>

                                    <!-- Description / Instructions -->
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            Description / Instructions <span class="text-slate-400 text-xs font-normal lowercase">(optional)</span>
                                        </label>
                                        <textarea name="description" rows="2" placeholder="Provide instructions or guidelines for students..." class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl p-3 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">{{ old('description') }}</textarea>
                                    </div>

                                    <!-- Assignment Access & Cohort Selection -->
                                    <div class="md:col-span-2 pt-2">
                                        @include('staff.assignments.partials.access_type_selector', [
                                            'prefix' => 'bulk',
                                            'type' => old('type', 'public'),
                                            'assignedStudentIds' => old('student_ids', []),
                                            'programs' => $programs ?? collect()
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Expected Excel Fields Guide -->
                            <div class="bg-slate-50 dark:bg-[#1C2430] border border-slate-200/80 dark:border-[#273244] rounded-2xl p-6 space-y-4 shadow-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-200/60 dark:border-[#273244]">
                                    <div class="flex items-center gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white text-xs font-extrabold flex items-center justify-center shadow-xs">2</span>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900 dark:text-[#F8FAFC]">Required Excel Columns for Questions</h4>
                                            <p class="text-xs text-slate-500 dark:text-[#94A3B8]">Your spreadsheet only needs the question bank columns below</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('staff.assignments.template') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-2xs hover:shadow transition-all shrink-0 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span>Download Sample Template (.xlsx)</span>
                                    </a>
                                </div>
                                <div class="overflow-x-auto rounded-xl border border-slate-200/80 dark:border-[#273244]">
                                    <table class="w-full text-xs text-left text-slate-600 dark:text-slate-300">
                                        <thead class="bg-slate-100 dark:bg-[#151B23] text-slate-800 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-[#273244]">
                                            <tr>
                                                <th class="p-3">Column Header</th>
                                                <th class="p-3">Required?</th>
                                                <th class="p-3">Example Value</th>
                                                <th class="p-3">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 dark:divide-[#273244] font-sans bg-white dark:bg-[#1C2430]">
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">question</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3">Which data structure follows the LIFO principle?</td>
                                                <td class="p-3">The question text</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">option_a</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3">Queue</td>
                                                <td class="p-3">First choice</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">option_b</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3">Stack</td>
                                                <td class="p-3">Second choice</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">option_c</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3">Array</td>
                                                <td class="p-3">Third choice</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">option_d</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3">Tree</td>
                                                <td class="p-3">Fourth choice</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">correct_option</td>
                                                <td class="p-3 text-red-500 font-bold">Yes</td>
                                                <td class="p-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">B</td>
                                                <td class="p-3">Must be <code>A</code>, <code>B</code>, <code>C</code>, or <code>D</code></td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">marks</td>
                                                <td class="p-3 text-slate-400">Optional</td>
                                                <td class="p-3 font-mono">1</td>
                                                <td class="p-3">Marks for this question (default 1)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">explanation</td>
                                                <td class="p-3 text-slate-400">Optional</td>
                                                <td class="p-3">Stack operates on Last In First Out (LIFO).</td>
                                                <td class="p-3">Explanations shown to students in review</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Step 3: Upload Excel File Section -->
                            <div class="bg-slate-50/60 dark:bg-[#1C2430] rounded-2xl border border-slate-200/80 dark:border-[#273244] shadow-xs p-6 space-y-4">
                                <div class="flex items-center gap-3 pb-3.5 border-b border-slate-200/60 dark:border-[#273244]">
                                    <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white text-xs font-extrabold flex items-center justify-center shadow-xs">3</span>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-[#F8FAFC]">Upload Questions Spreadsheet</h3>
                                        <p class="text-xs text-slate-500 dark:text-[#94A3B8]">Select or drop your Excel (.xlsx, .xls) or CSV (.csv) question file</p>
                                    </div>
                                </div>
                                
                                <label for="excel_file" id="drop-zone" class="block relative border-2 border-dashed border-indigo-200 dark:border-indigo-900/60 hover:border-indigo-500 rounded-2xl p-8 text-center bg-indigo-50/20 dark:bg-indigo-950/20 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/40 transition-all cursor-pointer">
                                    <input id="excel_file" name="excel_file" type="file" accept=".xlsx,.xls,.csv" class="sr-only" onchange="handleFileSelect(this)">
                                    
                                    <!-- Default State (When no file selected) -->
                                    <div id="upload-prompt-state">
                                        <div class="mx-auto w-14 h-14 rounded-2xl bg-indigo-100 dark:bg-indigo-950/60 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-3 shadow-inner border border-indigo-200/60 dark:border-indigo-900/50">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">
                                            <span class="text-indigo-600 dark:text-indigo-400 underline hover:text-indigo-700">Click to browse</span> or drag and drop your Excel / CSV file
                                        </p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Supports Microsoft Excel (.xlsx, .xls) and CSV (.csv) up to 10MB</p>
                                    </div>

                                    <!-- Selected State (When file selected) -->
                                    <div id="upload-selected-state" class="hidden">
                                        <div class="mx-auto w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-3 shadow-inner border border-emerald-200/60 dark:border-emerald-900/50">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="font-bold text-sm text-slate-900 dark:text-white" id="selected-file-name">filename.xlsx</p>
                                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-1">File ready for import</p>
                                        <button type="button" onclick="event.preventDefault(); document.getElementById('excel_file').value = ''; handleFileSelect(document.getElementById('excel_file'));" class="mt-3 text-xs text-red-500 hover:text-red-700 underline font-semibold cursor-pointer">
                                            Remove file & select another
                                        </button>
                                    </div>
                                </label>

                                <div class="pt-4 border-t border-slate-200/60 dark:border-[#273244] flex items-center justify-end gap-3">
                                    <a href="{{ route('staff.assignments.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all text-center">Cancel</a>
                                    <button type="submit" id="submit-import-btn" class="btn-theme-primary font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-indigo-600/20 active:scale-95 flex items-center gap-2 cursor-pointer text-xs sm:text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span>Import Questions & Create Assignment</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Single Assignment Creation Panel -->
                    <div id="tab-single-content" class="p-6 sm:p-8 bg-white dark:bg-[#151B23] hidden space-y-6">
                        <form action="{{ route('staff.assignments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                                <!-- Course -->
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        Assigned Course <span class="text-red-500">*</span>
                                    </label>
                                    <select name="course_id" class="tomselect-course w-full text-sm" required>
                                        <option value="">-- Choose Course --</option>
                                        @foreach($assignedCourses as $course)
                                            <option value="{{ $course->id }}" {{ (old('course_id', $selectedCourseId) == $course->id) ? 'selected' : '' }}>
                                                {{ $course->code }} - {{ $course->name }} @if($course->regulation)({{ $course->regulation->code ?: $course->regulation->name }}{{ !empty($course->regulation->curriculum) ? ' • ' . $course->regulation->curriculum : '' }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Title -->
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        MCQ Assignment Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Unit 1 MCQ Test: Database Normalization" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:bg-white dark:focus:bg-[#151B23] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                </div>

                                <!-- Due Date -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        Submission Deadline <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] text-slate-900 dark:text-slate-100 focus:outline-none focus:bg-white dark:focus:bg-[#151B23] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all font-mono">
                                </div>

                                <!-- Publish Status -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        Publish Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" required class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-4 py-2.5 bg-slate-50 dark:bg-[#1C2430] text-slate-900 dark:text-slate-100 focus:outline-none focus:bg-white dark:focus:bg-[#151B23] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all cursor-pointer">
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Active for Students)</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        Description / Test Guidelines <span class="text-slate-400 text-xs font-normal lowercase">(optional)</span>
                                    </label>
                                    <textarea name="description" rows="2" placeholder="e.g. Please choose the single best answer for each question. Each question carries 1 mark." class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl p-3 bg-slate-50 dark:bg-[#1C2430] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:bg-white dark:focus:bg-[#151B23] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">{{ old('description') }}</textarea>
                                </div>

                                <!-- Assignment Access & Cohort Selection -->
                                <div class="md:col-span-2 pt-2">
                                    @include('staff.assignments.partials.access_type_selector', [
                                        'prefix' => 'manual',
                                        'type' => old('type', 'public'),
                                        'assignedStudentIds' => old('student_ids', []),
                                        'programs' => $programs ?? collect()
                                    ])
                                </div>
                            </div>

                            <!-- Initial Question 1 -->
                            <div class="border-t border-slate-200 dark:border-[#273244] pt-6 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <h3 class="text-base font-bold text-slate-900 dark:text-[#F8FAFC] flex items-center gap-2.5">
                                        <span class="w-7 h-7 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/50 text-xs font-bold flex items-center justify-center shadow-2xs">Q1</span>
                                        <span>Add Question 1 <span class="text-xs font-normal text-slate-400 dark:text-slate-400">(Optional - more questions can be added later)</span></span>
                                    </h3>

                                    <!-- Question Type Toggle (Hidden in UI to focus on MCQ) -->
                                    <div class="hidden inline-flex items-center gap-1 p-1 bg-slate-100 dark:bg-[#1C2430] rounded-xl border border-slate-200/80 dark:border-[#273244]">
                                        <label class="px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-indigo-700 dark:text-indigo-400 bg-white dark:bg-[#151B23] shadow-xs" id="q1_type_mcq_label">
                                            <input type="radio" name="questions[0][type]" value="mcq" checked onchange="toggleQ1Type('mcq')" class="sr-only">
                                            <span>MCQ Question</span>
                                        </label>
                                        <label class="px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-700" id="q1_type_code_label">
                                            <input type="radio" name="questions[0][type]" value="code" onchange="toggleQ1Type('code')" class="sr-only">
                                            <span>Coding Problem (C, Java, Python)</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="bg-slate-50 dark:bg-[#1C2430] p-6 rounded-2xl border border-slate-200/80 dark:border-[#273244] shadow-xs space-y-5">
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Question Statement / Problem Requirements</label>
                                        <textarea name="questions[0][question_text]" rows="3" placeholder="Type the question or coding problem statement here..." class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl p-3 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all"></textarea>
                                    </div>

                                    <!-- MCQ Specific Options -->
                                    <div id="q1_mcq_fields" class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-md bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 text-[11px] font-bold flex items-center justify-center">A</span>
                                                    <span>Option A</span>
                                                </label>
                                                <input type="text" name="questions[0][option_a]" placeholder="Choice A text" class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-md bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 text-[11px] font-bold flex items-center justify-center">B</span>
                                                    <span>Option B</span>
                                                </label>
                                                <input type="text" name="questions[0][option_b]" placeholder="Choice B text" class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-md bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 text-[11px] font-bold flex items-center justify-center">C</span>
                                                    <span>Option C</span>
                                                </label>
                                                <input type="text" name="questions[0][option_c]" placeholder="Choice C text" class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-md bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 text-[11px] font-bold flex items-center justify-center">D</span>
                                                    <span>Option D</span>
                                                </label>
                                                <input type="text" name="questions[0][option_d]" placeholder="Choice D text" class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-200/60 dark:border-[#273244]/60">
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Correct Option <span class="text-red-500">*</span></label>
                                                <select name="questions[0][correct_option]" class="w-full text-sm font-semibold border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all cursor-pointer">
                                                    <option value="A">Option A</option>
                                                    <option value="B">Option B</option>
                                                    <option value="C">Option C</option>
                                                    <option value="D">Option D</option>
                                                </select>
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Marks</label>
                                                <input type="number" name="questions[0][marks]" value="1" min="1" max="100" class="w-full text-sm font-semibold border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Explanation <span class="text-slate-400 font-normal lowercase">(optional)</span></label>
                                                <input type="text" name="questions[0][explanation]" placeholder="Why this option is correct" class="w-full text-sm border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 dark:focus:border-indigo-500 transition-all">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Coding Specific Fields -->
                                    <div id="q1_code_fields" class="hidden space-y-4 pt-2">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Marks for Coding Problem</label>
                                                <input type="number" id="q1_code_marks" value="10" min="1" max="100" class="w-full text-sm font-semibold border border-slate-200 dark:border-[#273244] rounded-xl px-3.5 py-2.5 bg-white dark:bg-[#151B23] text-slate-900 dark:text-slate-100">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Permitted Compilers</label>
                                                <div class="flex items-center gap-3 pt-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                        <input type="checkbox" name="questions[0][allowed_languages][]" value="c" checked class="rounded text-indigo-600">
                                                        <span>C (GCC 13)</span>
                                                    </label>
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                        <input type="checkbox" name="questions[0][allowed_languages][]" value="java" checked class="rounded text-indigo-600">
                                                        <span>Java (OpenJDK 21)</span>
                                                    </label>
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                        <input type="checkbox" name="questions[0][allowed_languages][]" value="python" checked class="rounded text-indigo-600">
                                                        <span>Python (3.14)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-xs text-purple-700 dark:text-purple-400 font-medium bg-purple-50 dark:bg-purple-950/40 p-3 rounded-xl border border-purple-100 dark:border-purple-900/40">
                                            Tip: Full test case suite and starter code templates can also be configured or modified from the assignment management screen.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-200/80 dark:border-[#273244] flex items-center justify-end gap-3">
                                <a href="{{ route('staff.assignments.index') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all text-center">Cancel</a>
                                <button type="submit" class="btn-theme-primary font-bold py-2.5 px-6 rounded-xl transition-all shadow-md shadow-indigo-600/20 active:scale-95 flex items-center gap-2 cursor-pointer text-xs sm:text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Create MCQ Assignment</span>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        
            <footer class="mt-8 border-t border-slate-200 dark:border-[#273244] pt-4 pb-2">
                <p class="text-center text-xs text-slate-400 dark:text-slate-500 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-slate-600 dark:text-slate-400">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <script>
        function initCourseTomSelect(el) {
            if (!el || typeof TomSelect === 'undefined') return;
            if (el.tomselect || el.classList.contains('tomselected')) {
                if (el.tomselect) el.tomselect.sync();
                return;
            }
            new TomSelect(el, {
                create: false,
                maxOptions: null,
                placeholder: '-- Choose Course --',
                onChange: function(val) {
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        function switchTab(tab) {
            const singleBtn = document.getElementById('tab-single-btn');
            const bulkBtn = document.getElementById('tab-bulk-btn');
            const singleContent = document.getElementById('tab-single-content');
            const bulkContent = document.getElementById('tab-bulk-content');

            if (tab === 'single') {
                singleBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                singleBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400');
                bulkBtn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                bulkBtn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400');
                singleContent.classList.remove('hidden');
                bulkContent.classList.add('hidden');
                setTimeout(() => {
                    document.querySelectorAll('#tab-single-content select.tomselect-course').forEach(initCourseTomSelect);
                }, 50);
            } else {
                bulkBtn.classList.add('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                bulkBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400');
                singleBtn.classList.remove('border-indigo-600', 'text-indigo-600', 'dark:text-indigo-400');
                singleBtn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400');
                bulkContent.classList.remove('hidden');
                singleContent.classList.add('hidden');
                setTimeout(() => {
                    document.querySelectorAll('#tab-bulk-content select.tomselect-course').forEach(initCourseTomSelect);
                }, 50);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize on visible tab's course select
            document.querySelectorAll('#tab-bulk-content select.tomselect-course').forEach(initCourseTomSelect);
        });

        function handleFileSelect(input) {
            const promptState = document.getElementById('upload-prompt-state');
            const selectedState = document.getElementById('upload-selected-state');
            const nameSpan = document.getElementById('selected-file-name');
            const base64Input = document.getElementById('file_base64');
            const nameInput = document.getElementById('file_name');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeKb = (file.size / 1024).toFixed(1);
                nameSpan.textContent = `${file.name} (${sizeKb} KB)`;
                if (nameInput) nameInput.value = file.name;

                // Read file as Base64 data URL
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (base64Input) {
                        base64Input.value = e.target.result;
                    }
                };
                reader.readAsDataURL(file);

                if (promptState) promptState.classList.add('hidden');
                if (selectedState) selectedState.classList.remove('hidden');
            } else {
                if (base64Input) base64Input.value = '';
                if (nameInput) nameInput.value = '';
                if (promptState) promptState.classList.remove('hidden');
                if (selectedState) selectedState.classList.add('hidden');
            }
        }

        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('excel_file');
        if (dropZone && fileInput) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('border-indigo-600', 'bg-indigo-100/60', 'dark:bg-indigo-950/60');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('border-indigo-600', 'bg-indigo-100/60', 'dark:bg-indigo-950/60');
                }, false);
            });
            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length) {
                    fileInput.files = files;
                    handleFileSelect(fileInput);
                }
            }, false);
        }

        const form = document.getElementById('bulk-upload-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const base64Input = document.getElementById('file_base64');
                const hasFile = (fileInput && fileInput.files && fileInput.files.length > 0) || (base64Input && base64Input.value.length > 0);
                if (!hasFile) {
                    e.preventDefault();
                    alert('Please select an Excel or CSV file before clicking Import.');
                    return false;
                }
                const btn = document.getElementById('submit-import-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Importing Questions...`;
                }
            });
        }

        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
    </script>

    <script>
        function toggleQ1Type(type) {
            const mcqFields = document.getElementById('q1_mcq_fields');
            const codeFields = document.getElementById('q1_code_fields');
            const mcqLabel = document.getElementById('q1_type_mcq_label');
            const codeLabel = document.getElementById('q1_type_code_label');
            const marksInput = document.querySelector('input[name="questions[0][marks]"]');
            const codeMarks = document.getElementById('q1_code_marks');

            if (type === 'code') {
                if (mcqFields) mcqFields.classList.add('hidden');
                if (codeFields) codeFields.classList.remove('hidden');
                if (mcqLabel) mcqLabel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-700';
                if (codeLabel) codeLabel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-purple-700 dark:text-purple-400 bg-white dark:bg-[#151B23] shadow-xs';
                if (marksInput && codeMarks) marksInput.value = codeMarks.value;
            } else {
                if (mcqFields) mcqFields.classList.remove('hidden');
                if (codeFields) codeFields.classList.add('hidden');
                if (mcqLabel) mcqLabel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-indigo-700 dark:text-indigo-400 bg-white dark:bg-[#151B23] shadow-xs';
                if (codeLabel) codeLabel.className = 'px-3 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all flex items-center gap-1.5 text-slate-500 dark:text-slate-400 hover:text-slate-700';
                if (marksInput) marksInput.value = 1;
            }
        }
    </script>

</body>
</html>
