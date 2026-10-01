<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>
        /* Force dropdown to always open downwards below the input */
        .ts-wrapper {
            position: relative !important;
            width: 100% !important;
        }
        .ts-wrapper .ts-dropdown {
            position: absolute !important;
            top: 100% !important;
            bottom: auto !important;
            left: 0 !important;
            min-width: 100% !important;
            width: max-content !important;
            max-width: 420px !important;
            margin-top: 4px !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            z-index: 9999 !important;
            max-height: 220px !important;
            background: #ffffff !important;
            overflow-y: auto !important;
        }
        .ts-wrapper .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.625rem 0.875rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            border-color: #e2e8f0 !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }
        .ts-wrapper .ts-dropdown .optgroup-header {
            padding: 0.5rem 0.75rem 0.25rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #64748b !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .ts-wrapper .ts-dropdown .option {
            padding: 0.55rem 0.75rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            cursor: pointer !important;
        }
        .ts-wrapper .ts-dropdown .option:hover,
        .ts-wrapper .ts-dropdown .option.active {
            background-color: #eef2ff !important;
            color: #4338ca !important;
            font-weight: 600 !important;
        }
        .ts-wrapper .ts-dropdown .option.selected {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        /* Multi-select tag styles */
        .ts-wrapper.multi .ts-control {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 4px !important;
            padding: 4px 6px !important;
            min-height: 38px !important;
            font-size: 0.75rem !important;
            border-radius: 0.5rem !important;
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
        }
        .ts-wrapper.multi .ts-control .item {
            background-color: #eef2ff !important;
            color: #3730a3 !important;
            border: 1px solid #c7d2fe !important;
            border-radius: 0.375rem !important;
            padding: 2px 4px 2px 8px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 4px !important;
            line-height: 1.3 !important;
            max-width: calc(100% - 6px) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            overflow: hidden !important;
        }
        .ts-wrapper.multi .ts-control .item span.item-label,
        .ts-wrapper.multi .ts-control .item span {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            flex: 1 1 auto !important;
            min-width: 0 !important;
            display: inline-block !important;
        }
        .ts-wrapper.multi .ts-control .item .remove,
        .ts-wrapper.multi .ts-control .item a.remove {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            border-radius: 9999px !important;
            background-color: #c7d2fe !important;
            color: #1e1b4b !important;
            font-size: 13px !important;
            font-weight: 900 !important;
            text-decoration: none !important;
            cursor: pointer !important;
            line-height: 1 !important;
            transition: all 0.15s ease !important;
            margin-left: 4px !important;
            padding: 0 !important;
            flex: 0 0 16px !important;
        }
        .ts-wrapper.multi .ts-control .item .remove:hover,
        .ts-wrapper.multi .ts-control .item a.remove:hover {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            transform: scale(1.15) !important;
        }
        .ts-wrapper.multi .ts-control input {
            font-size: 0.75rem !important;
            min-width: 80px !important;
            flex: 1 1 auto !important;
        }
        /* Remove number input spinner arrows */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type="number"] {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }
    </style>
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-4 sm:space-y-5 w-full">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <a href="{{ route('academic.courses') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase tracking-wider mb-1 inline-flex items-center gap-1.5 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Courses Catalog
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Add New Course(s)</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Configure curriculum specifications, department allocations, and batch-create subjects.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3.5 rounded-2xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-semibold">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-2xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-semibold">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-lg leading-none">&times;</button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-2 font-bold mb-1.5 text-rose-900">
                            <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm">Please resolve the following {{ $errors->count() }} error(s):</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 pl-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Create Form Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-indigo-600 shadow-sm"></span>
                            <h2 class="text-base font-bold text-slate-900">Curriculum & Course Details</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Central Academic Portal
                        </span>
                    </div>

                    <form action="{{ route('academic.courses.store') }}" method="POST" class="p-5 sm:p-6">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                            
                            <!-- Left Column: Academic Placement (Program, Regulation, Semester, Total Subjects) -->
                            <div class="lg:col-span-4 bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-4">
                                <div class="pb-2.5 border-b border-slate-200/80 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                            1
                                        </div>
                                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                            Academic Placement
                                        </h3>
                                    </div>
                                    <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">Step 1 of 2</span>
                                </div>

                                <!-- 1. Program Type -->
                                <div class="space-y-1.5">
                                    <label for="program_type_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Program Type <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="program_type_select" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                            <option value="">Select Program Type</option>
                                            @foreach($availableProgramTypes as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- 2. Academic Regulation -->
                                <div class="space-y-1.5">
                                    <label for="regulation_id_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Academic Regulation <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="regulation_id_select" name="regulation_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                            <option value="">Select Regulation</option>
                                            @foreach($regulations as $reg)
                                                <option value="{{ $reg->id }}" data-program="{{ $reg->program_type }}">
                                                    {{ $reg->code }}{{ !empty($reg->curriculum) ? ' - ' . $reg->curriculum : ($reg->name && $reg->name !== $reg->code ? ' - ' . $reg->name : '') }} ({{ $reg->program_type }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if(Auth::user()->role === 'sa')
                                <!-- Department (For Super Admin) -->
                                <div class="space-y-1.5">
                                    <label for="department_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Department <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="department_id" name="department_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->code }}" {{ old('department_id') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @elseif(Auth::user()->role === 'ssh_admin')
                                <!-- Department (Fixed for SSH Admin - 1st Year Central Directorate) -->
                                <input type="hidden" name="department_id" value="dep_ssh">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Directorate / Department
                                    </label>
                                    <div class="px-3.5 py-2.5 bg-indigo-50/80 border border-indigo-200 rounded-xl text-xs font-bold text-indigo-900 flex items-center gap-2 shadow-xs">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <div class="truncate">
                                            <span>Social Sciences & Humanities</span>
                                            <span class="block text-[10px] font-normal text-indigo-600">1st Year Directorate (Common to all Branches)</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <!-- Department (Fixed for Department Coordinator) -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Department
                                    </label>
                                    <div class="px-3.5 py-2.5 bg-slate-100/90 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span class="truncate">{{ Auth::user()->profile->department->name ?? Auth::user()->profile->departments_id }}</span>
                                    </div>
                                </div>
                                @endif

                                <!-- 3. Year & Semester Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <!-- Year -->
                                    <div class="space-y-1.5">
                                        <label for="year_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Year <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="year_select" name="year" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                                <option value="">Select Year</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Semester -->
                                    <div class="space-y-1.5">
                                        <label for="semester_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Semester <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="semester_select" name="semester" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                                <option value="">Select Semester</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Total Subjects To Create -->
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between">
                                        <label for="no_of_courses" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Total Subjects To Create <span class="text-rose-500">*</span>
                                        </label>
                                        <span id="course_count_badge" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">1 Subject</span>
                                    </div>
                                    <div class="relative">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" name="no_of_courses" id="no_of_courses" value="1" placeholder="e.g. 5" oninput="handleCourseCountChange(this.value)" onchange="handleCourseCountChange(this.value)" onkeyup="handleCourseCountChange(this.value)" onwheel="event.preventDefault()" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold text-slate-800 shadow-xs transition-all" required>
                                    </div>
                                    <p class="text-[11px] text-slate-400">Enter count (1-20) to generate input rows dynamically on the right.</p>
                                </div>
                            </div>

                            <!-- Right Column: Dynamic Course Rows & Submission (8 cols) -->
                            <div class="lg:col-span-8 flex flex-col justify-between space-y-6">
                                <div>
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-100">
                                                2
                                            </div>
                                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                                Subject Codes & Names
                                            </h3>
                                        </div>
                                        <span id="rows_summary_text" class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">1 row configured</span>
                                    </div>

                                    <div class="grid grid-cols-12 gap-2 mb-2.5 px-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        <div class="col-span-1 text-center">#</div>
                                        <div class="col-span-3 sm:col-span-3">Subject Code *</div>
                                        <div class="col-span-4 sm:col-span-4">Subject Name / Title *</div>
                                        <div class="col-span-4 sm:col-span-4">Assign Faculty (Multiple Allowed)</div>
                                    </div>

                                    <div id="dynamic_course_fields" class="space-y-3">
                                        <div class="course-row grid grid-cols-12 gap-2 items-center p-2.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:bg-slate-50 transition-colors" data-row="1">
                                            <div class="col-span-1 text-center font-bold text-xs text-slate-400 row-num">1</div>
                                            <div class="col-span-3 sm:col-span-3">
                                                <input type="text" name="code[]" placeholder="e.g. 22CS101" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-mono uppercase font-bold text-indigo-700 bg-white shadow-2xs" required>
                                                <div class="code-error-msg hidden text-[10px] sm:text-[11px] text-rose-600 font-bold mt-1 leading-tight"></div>
                                            </div>
                                            <div class="col-span-4 sm:col-span-4">
                                                <input type="text" name="name[]" placeholder="e.g. Programming in C" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-semibold text-slate-800 bg-white shadow-2xs" required>
                                            </div>
                                            <div class="col-span-4 sm:col-span-4">
                                                <select name="staff_id[0][]" multiple class="faculty-tomselect no-tomselect w-full" placeholder="Type Emp Code or Name (Multi)...">
                                                    @if(isset($availableStaff))
                                                        @foreach($availableStaff as $st)
                                                            @php
                                                                $fullName = $st->profile ? trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')) : $st->username;
                                                                $deptName = $st->profile?->department?->name ?? '';
                                                            @endphp
                                                            <option value="{{ $st->id }}">{{ $st->username }} - {{ $fullName }}{{ $deptName ? ' (' . $deptName . ')' : '' }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                                    <p class="text-xs text-slate-400 text-center sm:text-left">
                                        All courses created will be available for student enrollment and faculty allocations.
                                    </p>
                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                        <a href="{{ route('academic.courses') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs text-center transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit" class="w-full sm:w-auto px-7 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span>Create Course(s)</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Hidden Template for Faculty Options -->
    <template id="faculty_options_template">
        @if(isset($availableStaff))
            @foreach($availableStaff as $st)
                @php
                    $fullName = $st->profile ? trim(($st->profile->first_name ?? '') . ' ' . ($st->profile->last_name ?? '')) : $st->username;
                    $deptName = $st->profile?->department?->name ?? '';
                @endphp
                <option value="{{ $st->id }}">{{ $st->username }} - {{ $fullName }}{{ $deptName ? ' (' . $deptName . ')' : '' }}</option>
            @endforeach
        @endif
    </template>

    <!-- Modals -->
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
        // Global helper for TomSelect on faculty dropdowns
        function initFacultyTomSelect(el) {
            if (!el) return;
            if (el.tomselect || el.classList.contains('tomselected')) return;
            el.classList.add('no-tomselect');

            if (typeof TomSelect !== 'undefined') {
                try {
                    const ts = new TomSelect(el, {
                        plugins: {
                            remove_button: {
                                title: 'Remove faculty',
                                label: '×',
                                className: 'remove'
                            }
                        },
                        create: false,
                        maxOptions: null,
                        placeholder: 'Type Emp Code or Name (Multi)...',
                        sortField: { field: "text", direction: "asc" },
                        render: {
                            item: function(data, escape) {
                                return '<div class="item" data-value="' + escape(data.value) + '">' +
                                    '<span class="item-label" title="' + escape(data.text) + '">' + escape(data.text) + '</span>' +
                                '</div>';
                            }
                        }
                    });

                    if (ts && ts.control) {
                        ts.control.addEventListener('click', function(e) {
                            const removeBtn = e.target.closest('.remove');
                            if (removeBtn) {
                                const itemEl = removeBtn.closest('.item');
                                if (itemEl && itemEl.dataset.value) {
                                    ts.removeItem(itemEl.dataset.value);
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        });
                    }
                } catch (e) {
                    try {
                        const ts = new TomSelect(el, {
                            plugins: ['remove_button'],
                            create: false,
                            maxOptions: null,
                            placeholder: 'Type Emp Code or Name (Multi)...',
                            sortField: { field: "text", direction: "asc" },
                            render: {
                                item: function(data, escape) {
                                    return '<div class="item" data-value="' + escape(data.value) + '">' +
                                        '<span class="item-label" title="' + escape(data.text) + '">' + escape(data.text) + '</span>' +
                                    '</div>';
                                }
                            }
                        });
                        if (ts && ts.control) {
                            ts.control.addEventListener('click', function(e) {
                                const removeBtn = e.target.closest('.remove');
                                if (removeBtn) {
                                    const itemEl = removeBtn.closest('.item');
                                    if (itemEl && itemEl.dataset.value) {
                                        ts.removeItem(itemEl.dataset.value);
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                }
                            });
                        }
                    } catch (err) {
                        console.error('TomSelect init error:', err);
                    }
                }
            }
        }

        // Live Row Generator
        function syncRowCount(desiredCount) {
            const container = document.getElementById('dynamic_course_fields');
            if (!container) return;

            let count = parseInt(desiredCount);
            if (isNaN(count) || count < 1) count = 1;
            if (count > 20) count = 20;

            const badge = document.getElementById('course_count_badge');
            if (badge) badge.textContent = count === 1 ? '1 Subject' : `${count} Subjects`;

            const summaryText = document.getElementById('rows_summary_text');
            if (summaryText) summaryText.textContent = count === 1 ? '1 row configured' : `${count} rows configured`;

            const tpl = document.getElementById('faculty_options_template');
            const facultyOptionsHtml = tpl ? tpl.innerHTML : '';

            const currentRows = container.querySelectorAll('.course-row');
            const currentCount = currentRows.length;

            if (count > currentCount) {
                for (let i = currentCount + 1; i <= count; i++) {
                    const rowIndex = i - 1;
                    const newRow = document.createElement('div');
                    newRow.className = 'course-row grid grid-cols-12 gap-2 items-center p-2.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:bg-slate-50 transition-colors animate-fade-in';
                    newRow.setAttribute('data-row', i);
                    newRow.innerHTML = `
                        <div class="col-span-1 text-center font-bold text-xs text-slate-400 row-num">${i}</div>
                        <div class="col-span-3 sm:col-span-3">
                            <input type="text" name="code[]" placeholder="e.g. Code" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-mono uppercase font-bold text-indigo-700 bg-white shadow-2xs" required>
                            <div class="code-error-msg hidden text-[10px] sm:text-[11px] text-rose-600 font-bold mt-1 leading-tight"></div>
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <input type="text" name="name[]" placeholder="e.g. Subject Name" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-semibold text-slate-800 bg-white shadow-2xs" required>
                        </div>
                        <div class="col-span-4 sm:col-span-4">
                            <select name="staff_id[${rowIndex}][]" multiple class="faculty-tomselect no-tomselect w-full" placeholder="Type Emp Code or Name (Multi)...">
                                ${facultyOptionsHtml}
                            </select>
                        </div>
                    `;
                    container.appendChild(newRow);

                    const sel = newRow.querySelector('.faculty-tomselect');
                    if (sel) {
                        setTimeout(() => initFacultyTomSelect(sel), 10);
                    }
                }
            } else if (count < currentCount) {
                for (let i = currentCount - 1; i >= count; i--) {
                    if (currentRows[i]) {
                        const sel = currentRows[i].querySelector('.faculty-tomselect');
                        if (sel && sel.tomselect) {
                            try { sel.tomselect.destroy(); } catch(e) {}
                        }
                        currentRows[i].remove();
                    }
                }
            }
        }

        window.handleCourseCountChange = function(val) {
            const clean = String(val).replace(/[^0-9]/g, '');
            const input = document.getElementById('no_of_courses');
            if (input && input.value !== clean) {
                input.value = clean;
            }
            if (clean === '') return;
            let num = parseInt(clean, 10);
            if (!isNaN(num)) {
                if (num > 20) num = 20;
                if (num < 1) num = 1;
                syncRowCount(num);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initLMSUI === 'function') {
                window.initLMSUI();
            }

            @if($errors->has('password'))
                if (window.openPwdModal) window.openPwdModal();
            @endif

            // 1. Program Type -> Regulation -> Semester Dropdown Logic
            const programTypeSelect = document.getElementById('program_type_select');
            const regulationSelect = document.getElementById('regulation_id_select');
            const yearSelect = document.getElementById('year_select');
            const semesterSelect = document.getElementById('semester_select');
            const allRegulations = Array.from(regulationSelect.options).filter(opt => opt.value !== '');

            function syncTS(el) {
                if (el && el.tomselect) {
                    const ts = el.tomselect;
                    const val = el.value;
                    ts.clear();
                    ts.clearOptions();
                    ts.sync();
                    if (val) {
                        ts.setValue(val, true);
                    }
                }
            }

            function matchProgramType(selected, candidate) {
                if (!selected || !candidate) return false;
                const s = selected.trim().toLowerCase();
                const c = candidate.trim().toLowerCase();
                if (s === c) return true;
                const sClean = s.replace(/[^a-z0-9]/g, '');
                const cClean = c.replace(/[^a-z0-9]/g, '');
                return sClean !== '' && sClean === cClean;
            }

            function populateYears(programType) {
                const currentVal = yearSelect.value;
                yearSelect.innerHTML = '<option value="">Select Year</option>';
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                
                const isSshAdmin = {{ Auth::user()->role === 'ssh_admin' ? 'true' : 'false' }};
                if (isSshAdmin) {
                    const opt = document.createElement('option');
                    opt.value = 1;
                    opt.textContent = '1st Year';
                    opt.selected = true;
                    yearSelect.appendChild(opt);
                    syncTS(yearSelect);
                    populateSemesters(1);
                    return;
                }

                let maxYears = 4; // Default B.Tech
                if (programType) {
                    const typeLower = programType.toLowerCase();
                    if (typeLower.includes('b.tech') || typeLower.includes('b.pharm')) maxYears = 4;
                    else if (typeLower.includes('m.tech') || typeLower.includes('m.pharm') || typeLower.includes('m.b.a') || typeLower.includes('mba') || typeLower.includes('m.c.a') || typeLower.includes('mca') || typeLower.includes('m.sc')) maxYears = 2;
                    else if (typeLower.includes('b.sc') || typeLower.includes('b.com') || typeLower.includes('b.b.a') || typeLower.includes('bba')) maxYears = 3;
                    else if (typeLower.includes('ph.d')) maxYears = 5;
                }

                const ordinals = ["1st", "2nd", "3rd", "4th", "5th", "6th"];
                for (let i = 1; i <= maxYears; i++) {
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.textContent = `${ordinals[i - 1] || i + 'th'} Year`;
                    if (i == currentVal) {
                        opt.selected = true;
                    }
                    yearSelect.appendChild(opt);
                }

                syncTS(yearSelect);

                if (yearSelect.value) {
                    populateSemesters(yearSelect.value);
                } else if (yearSelect.options.length === 2) {
                    yearSelect.selectedIndex = 1;
                    if (yearSelect.tomselect) yearSelect.tomselect.setValue(yearSelect.value, true);
                    populateSemesters(yearSelect.value);
                } else {
                    syncTS(semesterSelect);
                }
            }

            function populateSemesters(selectedYear) {
                const currentVal = semesterSelect.value;
                semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                if (!selectedYear) {
                    syncTS(semesterSelect);
                    return;
                }

                const y = parseInt(selectedYear);
                const opt1 = document.createElement('option');
                opt1.value = 1;
                opt1.textContent = `1st Semester (${y}-1)`;
                if (currentVal == '1') opt1.selected = true;
                semesterSelect.appendChild(opt1);

                const opt2 = document.createElement('option');
                opt2.value = 2;
                opt2.textContent = `2nd Semester (${y}-2)`;
                if (currentVal == '2') opt2.selected = true;
                semesterSelect.appendChild(opt2);

                syncTS(semesterSelect);
            }

            // Initial load of years & semesters
            populateYears(programTypeSelect.value);

            programTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                regulationSelect.innerHTML = '<option value="">Select Regulation</option>';

                if (selectedType) {
                    allRegulations.forEach(opt => {
                        const progType = opt.getAttribute('data-program') || '';
                        if (matchProgramType(selectedType, progType)) {
                            regulationSelect.appendChild(opt.cloneNode(true));
                        }
                    });
                } else {
                    allRegulations.forEach(opt => regulationSelect.appendChild(opt.cloneNode(true)));
                }

                syncTS(regulationSelect);
                populateYears(selectedType);

                if (regulationSelect.options.length === 2) {
                    regulationSelect.selectedIndex = 1;
                    if (regulationSelect.tomselect) regulationSelect.tomselect.setValue(regulationSelect.value);
                    regulationSelect.dispatchEvent(new Event('change'));
                }
            });

            regulationSelect.addEventListener('change', function() {
                const selectedReg = this.options[this.selectedIndex];
                const programType = selectedReg ? selectedReg.getAttribute('data-program') : programTypeSelect.value;
                if (programType && programType !== programTypeSelect.value) {
                    programTypeSelect.value = programType;
                    if (programTypeSelect.tomselect) programTypeSelect.tomselect.setValue(programType, true);
                }
                populateYears(programType || programTypeSelect.value);
            });

            yearSelect.addEventListener('change', function() {
                populateSemesters(this.value);
            });

            // Initialize first row's faculty dropdown
            document.querySelectorAll('.faculty-tomselect').forEach(initFacultyTomSelect);

            const noOfCoursesInput = document.getElementById('no_of_courses');
            if (noOfCoursesInput) {
                noOfCoursesInput.addEventListener('blur', function() {
                    let count = parseInt(this.value) || 1;
                    if (count < 1) count = 1;
                    if (count > 20) count = 20;
                    this.value = count;
                    syncRowCount(count);
                });

                if (parseInt(noOfCoursesInput.value) > 1) {
                    syncRowCount(noOfCoursesInput.value);
                }
            }
            
            // Real-time Course Code Duplicate & Existence Validation
            const existingDatabaseCodes = @json($existingCourseCodes ?? []);

            function validateAllCourseCodes() {
                const codeInputs = Array.from(document.querySelectorAll('input[name="code[]"]'));
                const seenCodes = {};
                let hasDuplicateError = false;
                let hasExistingDbError = false;

                codeInputs.forEach(input => {
                    const val = input.value.trim().toUpperCase();
                    if (val !== '') {
                        seenCodes[val] = (seenCodes[val] || 0) + 1;
                    }
                });

                codeInputs.forEach(input => {
                    const val = input.value.trim().toUpperCase();
                    const container = input.closest('div');
                    let errorDiv = container.querySelector('.code-error-msg');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'code-error-msg text-[10px] sm:text-[11px] text-rose-600 font-bold mt-1 leading-tight';
                        container.appendChild(errorDiv);
                    }

                    if (val === '') {
                        errorDiv.classList.add('hidden');
                        errorDiv.innerHTML = '';
                        input.classList.remove('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                        return;
                    }

                    if (seenCodes[val] > 1) {
                        hasDuplicateError = true;
                        errorDiv.classList.remove('hidden');
                        errorDiv.innerHTML = `<span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Same course code '${val}' entered multiple times. Duplicate code is NOT allowed!</span>`;
                        input.classList.add('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                    } else if (existingDatabaseCodes.includes(val)) {
                        hasExistingDbError = true;
                        errorDiv.classList.remove('hidden');
                        errorDiv.innerHTML = `<span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Course code '${val}' already exists in the system!</span>`;
                        input.classList.add('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                    } else {
                        errorDiv.classList.add('hidden');
                        errorDiv.innerHTML = '';
                        input.classList.remove('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                    }
                });

                return !(hasDuplicateError || hasExistingDbError);
            }

            // Real-time event delegation on course code inputs
            const dynamicContainer = document.getElementById('dynamic_course_fields');
            if (dynamicContainer) {
                dynamicContainer.addEventListener('input', function(e) {
                    if (e.target && e.target.name === 'code[]') {
                        validateAllCourseCodes();
                    }
                });
                dynamicContainer.addEventListener('change', function(e) {
                    if (e.target && e.target.name === 'code[]') {
                        validateAllCourseCodes();
                    }
                });
                dynamicContainer.addEventListener('blur', function(e) {
                    if (e.target && e.target.name === 'code[]') {
                        validateAllCourseCodes();
                    }
                }, true);
            }

            const courseForm = document.querySelector('form[action="{{ route('academic.courses.store') }}"]');
            if (courseForm) {
                courseForm.addEventListener('submit', function(e) {
                    if (!validateAllCourseCodes()) {
                        e.preventDefault();
                        const firstError = document.querySelector('input[name="code[]"].\\!border-rose-500');
                        if (firstError) {
                            firstError.focus();
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        alert('Duplicate or already existing course code detected! Two courses cannot have the same code. Please enter unique course codes.');
                        return false;
                    }
                });
            }

            if (programTypeSelect.value) {
                programTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>

