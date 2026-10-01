<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add 1st Year Foundational Course - SSH Department</title>
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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="w-full space-y-6">

                @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <p class="font-bold mb-1">Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h2 class="text-base font-bold text-slate-900">Course Information</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Define a first-year foundational curriculum subject (Mathematics, Physics, Chemistry, English, Programming, etc.).</p>
                    </div>

                    <form method="POST" action="{{ route('ssh.courses.store') }}" class="space-y-6">
                        @csrf

                        <!-- Row 1: Course Code & Name -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Course Code <span class="text-rose-500">*</span></label>
                                <input type="text" name="code" id="ssh_course_code" value="{{ old('code') }}" required placeholder="e.g. 22MA101" class="w-full text-sm font-mono uppercase rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <div id="ssh_code_error" class="hidden text-[11px] text-rose-600 font-bold mt-1.5 flex items-center gap-1 leading-tight"></div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Course Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Linear Algebra and Calculus" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                        </div>

                        <!-- Row 2: Regulation & Department -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Academic Regulation <span class="text-rose-500">*</span></label>
                                <select name="regulation_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    <option value="">Select Regulation</option>
                                    @foreach($regulations as $reg)
                                    <option value="{{ $reg->id }}" {{ old('regulation_id') == $reg->id ? 'selected' : '' }}>{{ $reg->code }} - {{ $reg->program_type }} ({{ $reg->curriculum ?? 'Default' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Discipline / Department <span class="text-rose-500">*</span></label>
                                <select name="department_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ old('department_id', 'dep_ssh') == $dept->code ? 'selected' : '' }}>
                                        {{ $dept->name }} @if($dept->code === 'dep_ssh')(1st Year Directorate - Common to all Branches)@endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Centralized 1st Year Offering Notice -->
                        <div class="p-3.5 rounded-xl bg-indigo-50/80 border border-indigo-100 flex items-start gap-2.5 text-xs text-indigo-900">
                            <svg class="w-4 h-4 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="font-bold">Centralized 1st-Year Subject:</span> 1st Year courses created under <strong>Social Sciences & Humanities (SSH)</strong> are shared centrally and automatically available for enrollment by all 1st-year students across all engineering branches (<strong>CSE, IT, ACSE, ECE, MECH, CIVIL</strong>, etc.). Faculty from any department can be allocated to teach sections.
                            </div>
                        </div>

                        <!-- Row 3: Semester -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">First Year Semester <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-slate-50 border-slate-200">
                                    <input type="radio" name="semester" value="1" {{ old('semester', '1') == '1' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-slate-800">Semester 1</span>
                                        <span class="block text-xs text-slate-400">First term subjects</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-slate-50 border-slate-200">
                                    <input type="radio" name="semester" value="2" {{ old('semester') == '2' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-slate-800">Semester 2</span>
                                        <span class="block text-xs text-slate-400">Second term subjects</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('ssh.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-colors">
                                Cancel
                            </a>
                            <button type="submit" id="ssh_submit_btn" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md transition-all">
                                Create Course
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingDatabaseCodes = @json($existingCourseCodes ?? []);
            const codeInput = document.getElementById('ssh_course_code');
            const errorDiv = document.getElementById('ssh_code_error');
            const form = document.querySelector('form[action="{{ route('ssh.courses.store') }}"]');

            function validateSshCode() {
                if (!codeInput) return true;
                const val = codeInput.value.trim().toUpperCase();

                if (val !== '' && existingDatabaseCodes.includes(val)) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.innerHTML = `<svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Course code '${val}' already exists in system! Duplicate code is NOT allowed.`;
                    codeInput.classList.add('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                    return false;
                } else {
                    errorDiv.classList.add('hidden');
                    errorDiv.innerHTML = '';
                    codeInput.classList.remove('!border-rose-500', '!bg-rose-50/70', '!text-rose-700', 'ring-2', 'ring-rose-400');
                    return true;
                }
            }

            if (codeInput) {
                codeInput.addEventListener('input', validateSshCode);
                codeInput.addEventListener('change', validateSshCode);
                codeInput.addEventListener('blur', validateSshCode);
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateSshCode()) {
                        e.preventDefault();
                        codeInput.focus();
                        alert('Course code already exists! Duplicate course code is not allowed.');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>
