<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit 1st Year Course - SSH Department</title>
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
                <h1 class="text-base sm:text-lg font-bold text-slate-800">Edit 1st Year Course / Subject</h1>
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

                <form method="POST" action="{{ route('ssh.courses.update', $course->id) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-base font-bold text-slate-900">Foundational Course Details</h2>
                        <p class="text-xs text-slate-500">Update curriculum subject information for 1st Year students</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Course Name *</label>
                            <input type="text" name="name" value="{{ old('name', $course->name) }}" required placeholder="e.g., Engineering Physics" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Course Code *</label>
                            <input type="text" name="code" value="{{ old('code', $course->code) }}" required placeholder="e.g., 22PH101" class="w-full text-sm uppercase rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Semester *</label>
                            <select name="semester" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="1" {{ old('semester', $course->semester) == 1 ? 'selected' : '' }}>Semester 1 (Autumn)</option>
                                <option value="2" {{ old('semester', $course->semester) == 2 ? 'selected' : '' }}>Semester 2 (Spring)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Credits *</label>
                            <input type="number" step="0.5" min="0" max="10" name="credits" value="{{ old('credits', $course->credits) }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Course Type</label>
                            <select name="type" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="Theory" {{ old('type', $course->type) == 'Theory' ? 'selected' : '' }}>Theory</option>
                                <option value="Practical" {{ old('type', $course->type) == 'Practical' ? 'selected' : '' }}>Practical / Lab</option>
                                <option value="Integrated" {{ old('type', $course->type) == 'Integrated' ? 'selected' : '' }}>Integrated (Theory + Lab)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Regulation *</label>
                            <select name="regulation_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="">Select Regulation</option>
                                @foreach($regulations as $reg)
                                <option value="{{ $reg->id }}" {{ old('regulation_id', $course->regulation_id) == $reg->id ? 'selected' : '' }}>
                                    {{ $reg->code }} - {{ $reg->program_type }} ({{ $reg->curriculum ?? 'Default' }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Department Discipline *</label>
                            <select name="department_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->code }}" {{ old('department_id', $course->department_id) == $dept->code ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('ssh.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 font-semibold text-xs text-slate-700 hover:bg-slate-100 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                            Update Course
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

</body>
</html>
