<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create MCQ Assignment - LMS Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center space-x-2">
                <a href="{{ route('staff.assignments.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">Assignments</a>
                <span class="text-gray-300">/</span>
                <span class="text-sm font-semibold text-indigo-700">Create MCQ Assignment</span>
            </div>
            <div class="flex items-center">
                <a href="{{ route('staff.assignments.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Assignments
                </a>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-5xl mx-auto space-y-6">

                <!-- Alert Messages -->
                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-start justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <h4 class="text-sm font-bold text-red-800 mb-1">Please fix the following validation errors:</h4>
                    <ul class="text-xs text-red-700 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Page Header -->
                <div class="border-b border-gray-200 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create MCQ Assignment</h1>
                        <p class="mt-1 text-sm text-gray-600">Build interactive multiple choice questions or upload full question banks in bulk using Excel.</p>
                    </div>
                </div>

                <!-- Mode Tabs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 bg-gray-50/50 flex">
                        <button type="button" id="tab-bulk-btn" onclick="switchTab('bulk')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Bulk Excel Upload
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">Recommended</span>
                        </button>
                        <button type="button" id="tab-single-btn" onclick="switchTab('single')" class="px-6 py-3.5 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Manual MCQ Form
                        </button>
                    </div>

                    <!-- Bulk Excel Upload Panel -->
                    <div id="tab-bulk-content" class="p-6 sm:p-8 space-y-6">
                        
                        <!-- Step 1: Download Template -->
                        <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-bold text-indigo-950 flex items-center">
                                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center mr-2">1</span>
                                        Download Official MCQ Excel Template
                                    </h3>
                                    <p class="text-xs text-indigo-800 mt-1 max-w-xl">
                                        The Excel spreadsheet includes question text, Option A, Option B, Option C, Option D, correct option (A/B/C/D), marks, and a sheet with your course codes.
                                    </p>
                                </div>
                                <a href="{{ route('staff.assignments.template') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors shrink-0">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Download MCQ .xlsx Template
                                </a>
                            </div>
                        </div>

                        <!-- Expected Excel Fields Guide -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-3">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Required Excel Columns for MCQ Bulk Upload:</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left text-gray-600">
                                    <thead class="bg-gray-200/70 text-gray-800 font-bold">
                                        <tr>
                                            <th class="p-2.5 rounded-l">Column Header</th>
                                            <th class="p-2.5">Required?</th>
                                            <th class="p-2.5">Example Value</th>
                                            <th class="p-2.5 rounded-r">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 font-sans">
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">course_code</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5 font-mono">21CS101</td>
                                            <td class="p-2.5">Your assigned course code</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">assignment_title</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Unit 1 MCQ Quiz: Data Structures</td>
                                            <td class="p-2.5">Questions with same title & course are grouped together</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">question</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Which data structure follows the LIFO principle?</td>
                                            <td class="p-2.5">The question text</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">option_a</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Queue</td>
                                            <td class="p-2.5">First choice</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">option_b</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Stack</td>
                                            <td class="p-2.5">Second choice</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">option_c</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Array</td>
                                            <td class="p-2.5">Third choice</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">option_d</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5">Tree</td>
                                            <td class="p-2.5">Fourth choice</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">correct_option</td>
                                            <td class="p-2.5 text-red-600 font-bold">Yes</td>
                                            <td class="p-2.5 font-mono font-bold text-emerald-700">B</td>
                                            <td class="p-2.5">Must be <code>A</code>, <code>B</code>, <code>C</code>, or <code>D</code></td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">marks</td>
                                            <td class="p-2.5 text-gray-500">Optional</td>
                                            <td class="p-2.5 font-mono">1</td>
                                            <td class="p-2.5">Marks for this question (default 1)</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">explanation</td>
                                            <td class="p-2.5 text-gray-500">Optional</td>
                                            <td class="p-2.5">Stack operates on Last In First Out (LIFO).</td>
                                            <td class="p-2.5">Explanations shown to students in review</td>
                                        </tr>
                                        <tr>
                                            <td class="p-2.5 font-mono font-bold text-indigo-700">due_date</td>
                                            <td class="p-2.5 text-gray-500">Optional</td>
                                            <td class="p-2.5 font-mono">2026-11-30 23:59:00</td>
                                            <td class="p-2.5">Deadline date and time</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 2: Upload Excel File -->
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                                <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center mr-2">2</span>
                                Upload Completed Excel File
                            </h3>
                            <form action="{{ route('staff.assignments.bulk') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="bulk-upload-form">
                                @csrf
                                <input type="hidden" name="file_base64" id="file_base64">
                                <input type="hidden" name="file_name" id="file_name">
                                
                                <label for="excel_file" id="drop-zone" class="block relative border-2 border-dashed border-indigo-200 hover:border-indigo-500 rounded-xl p-8 text-center bg-indigo-50/20 hover:bg-indigo-50/50 transition-all cursor-pointer">
                                    <input id="excel_file" name="excel_file" type="file" accept=".xlsx,.xls,.csv" class="sr-only" onchange="handleFileSelect(this)">
                                    
                                    <!-- Default State (When no file selected) -->
                                    <div id="upload-prompt-state">
                                        <div class="mx-auto w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-3 shadow-inner">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="font-semibold text-sm text-gray-800">
                                            <span class="text-indigo-600 underline hover:text-indigo-700">Click to browse</span> or drag and drop your Excel / CSV file
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">Supports Microsoft Excel (.xlsx, .xls) and CSV (.csv) up to 10MB</p>
                                    </div>

                                    <!-- Selected State (When file is chosen) -->
                                    <div id="upload-selected-state" class="hidden">
                                        <div class="mx-auto w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-3 shadow-inner">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm font-bold shadow-sm">
                                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                            <span id="selected-file-name"></span>
                                        </div>
                                        <p class="text-xs text-indigo-600 font-semibold mt-3 hover:underline">Click here if you want to change to another file</p>
                                    </div>
                                </label>

                                <div class="flex justify-end">
                                    <button type="submit" id="submit-import-btn" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Import MCQ Questions & Create Assignment
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Single Assignment Creation Panel -->
                    <div id="tab-single-content" class="p-6 sm:p-8 hidden">
                        <form action="{{ route('staff.assignments.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Course -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Assigned Course <span class="text-red-500">*</span></label>
                                    <select name="course_id" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                        <option value="">-- Choose Course --</option>
                                        @foreach($assignedCourses as $course)
                                            <option value="{{ $course->id }}" {{ (old('course_id', $selectedCourseId) == $course->id) ? 'selected' : '' }}>
                                                {{ $course->code }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">MCQ Assignment Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Unit 1 MCQ Test: Database Normalization" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Due Date -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Submission Deadline Date & Time <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d\TH:i')) }}" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Publish Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Active for Students)</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description / Test Guidelines</label>
                                    <textarea name="description" rows="2" placeholder="e.g. Please choose the single best answer for each question. Each question carries 1 mark." class="w-full text-sm border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <!-- Initial Question 1 -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center mr-2">Q1</span>
                                    Add Question 1 (Optional - you can also add more questions on the next screen)
                                </h3>

                                <div class="bg-gray-50/70 p-5 rounded-xl border border-gray-200 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Question Statement</label>
                                        <textarea name="questions[0][question_text]" rows="2" placeholder="Type the question here..." class="w-full text-sm border border-gray-300 rounded-lg p-2.5 bg-white"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Option A</label>
                                            <input type="text" name="questions[0][option_a]" placeholder="Choice A text" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Option B</label>
                                            <input type="text" name="questions[0][option_b]" placeholder="Choice B text" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Option C</label>
                                            <input type="text" name="questions[0][option_c]" placeholder="Choice C text" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Option D</label>
                                            <input type="text" name="questions[0][option_d]" placeholder="Choice D text" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-emerald-700 mb-1">Correct Option</label>
                                            <select name="questions[0][correct_option]" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white font-bold">
                                                <option value="A">Option A</option>
                                                <option value="B">Option B</option>
                                                <option value="C">Option C</option>
                                                <option value="D">Option D</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Marks</label>
                                            <input type="number" name="questions[0][marks]" value="1" min="1" max="100" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Explanation (Optional)</label>
                                            <input type="text" name="questions[0][explanation]" placeholder="Why this option is correct" class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <a href="{{ route('staff.assignments.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Create MCQ Assignment
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <script>
        function switchTab(tab) {
            const singleBtn = document.getElementById('tab-single-btn');
            const bulkBtn = document.getElementById('tab-bulk-btn');
            const singleContent = document.getElementById('tab-single-content');
            const bulkContent = document.getElementById('tab-bulk-content');

            if (tab === 'single') {
                singleBtn.classList.add('border-indigo-600', 'text-indigo-600');
                singleBtn.classList.remove('border-transparent', 'text-gray-500');
                bulkBtn.classList.remove('border-indigo-600', 'text-indigo-600');
                bulkBtn.classList.add('border-transparent', 'text-gray-500');
                singleContent.classList.remove('hidden');
                bulkContent.classList.add('hidden');
            } else {
                bulkBtn.classList.add('border-indigo-600', 'text-indigo-600');
                bulkBtn.classList.remove('border-transparent', 'text-gray-500');
                singleBtn.classList.remove('border-indigo-600', 'text-indigo-600');
                singleBtn.classList.add('border-transparent', 'text-gray-500');
                bulkContent.classList.remove('hidden');
                singleContent.classList.add('hidden');
            }
        }

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
                    dropZone.classList.add('border-indigo-600', 'bg-indigo-100/60');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('border-indigo-600', 'bg-indigo-100/60');
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
</body>
</html>
