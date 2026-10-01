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
                            <input type="text" name="code" id="ssh_edit_course_code" value="{{ old('code', $course->code) }}" required placeholder="e.g., 22PH101" class="w-full text-sm uppercase rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            <div id="ssh_edit_code_error" class="hidden text-[11px] text-rose-600 font-bold mt-1.5 flex items-center gap-1 leading-tight"></div>
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
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">Technology Development (TD)</span>.</p>
            </footer>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingDatabaseCodes = @json($existingCourseCodes ?? []);
            const codeInput = document.getElementById('ssh_edit_course_code');
            const errorDiv = document.getElementById('ssh_edit_code_error');
            const form = document.querySelector('form[action="{{ route('ssh.courses.update', $course->id) }}"]');

            function validateEditCode() {
                if (!codeInput) return true;
                const val = codeInput.value.trim().toUpperCase();

                if (val !== '' && existingDatabaseCodes.includes(val)) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.innerHTML = `<svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Course code '${val}' already exists for another course! Duplicate code is NOT allowed.`;
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
                codeInput.addEventListener('input', validateEditCode);
                codeInput.addEventListener('change', validateEditCode);
                codeInput.addEventListener('blur', validateEditCode);
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateEditCode()) {
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
