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
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-3">
                <a href="{{ route('ssh.courses.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-base sm:text-lg font-bold text-slate-800">Create 1st Year Foundational Course</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.courses.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all">
                    Cancel
                </a>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

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
                                <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. 22MA101" class="w-full text-sm font-mono uppercase rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
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
                                    <option value="{{ $dept->code }}" {{ old('department_id') == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
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
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md transition-all">
                                Create Course
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
