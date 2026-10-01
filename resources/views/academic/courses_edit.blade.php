<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
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
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 gap-4">
                    <div>
                        <a href="{{ route('academic.courses') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mb-2 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Courses
                        </a>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Edit Course</h1>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Create Form Container -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
                    <form action="{{ route('academic.courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Left Column: Settings -->
                            <div class="md:col-span-5 lg:col-span-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Program Type</label>
                                    <select id="program_type_select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 no-tomselect bg-white" required>
                                        <option value="">Select Program Type</option>
                                        @foreach($availableProgramTypes as $type)
                                            <option value="{{ $type }}" {{ ($course->regulation->program_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Regulation</label>
                                    <select id="regulation_id_select" name="regulation_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 no-tomselect bg-white" required>
                                        <option value="">Select Regulation</option>
                                        @foreach($regulations as $reg)
                                            <option value="{{ $reg->id }}" data-program="{{ $reg->program_type }}" {{ $course->regulation_id == $reg->id ? 'selected' : '' }}>
                                                {{ $reg->code }}{{ !empty($reg->curriculum) ? ' - ' . $reg->curriculum : ($reg->name && $reg->name !== $reg->code ? ' - ' . $reg->name : '') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(in_array(Auth::user()->role, ['sa', 'ssh_admin']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <select name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->code }}" {{ $course->department_id == $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <div class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-sm font-semibold text-gray-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span>{{ $course->department->name ?? $course->department_id }}</span>
                                    </div>
                                </div>
                                @endif
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                        <select id="year_select" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 no-tomselect bg-white" required data-selected="{{ $course->year }}">
                                            <option value="">-- Year --</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                                        <select id="semester_select" name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 no-tomselect bg-white" required data-selected="{{ $course->semester }}">
                                            <option value="">-- Sem --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Courses List -->
                            <div class="md:col-span-7 lg:col-span-8">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course Details</label>
                                <div class="flex items-center gap-2 mb-2 px-1">
                                    <span class="text-xs font-semibold text-gray-500 w-24 sm:w-32">Code</span>
                                    <span class="text-xs font-semibold text-gray-500 flex-1">Subject Name</span>
                                </div>
                                <div class="space-y-2 mb-4 pr-2">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <input type="text" name="code" id="academic_edit_code" placeholder="Code" class="w-24 sm:w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm font-mono uppercase font-bold" value="{{ old('code', $course->code) }}" required>
                                            <input type="text" name="name" placeholder="Name" class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 text-sm font-semibold" value="{{ old('name', $course->name) }}" required>
                                        </div>
                                        <div id="academic_edit_code_error" class="hidden text-[11px] text-rose-600 font-bold mt-1 flex items-center gap-1 leading-tight"></div>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-100 mt-4">
                                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors">
                                        Update Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

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

    <script>        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown Filtering Logic
            const programTypeSelect = document.getElementById('program_type_select');
            const regulationSelect = document.getElementById('regulation_id_select');
            const yearSelect = document.getElementById('year_select');
            const semesterSelect = document.getElementById('semester_select');
            const allRegulations = Array.from(regulationSelect.options).filter(opt => opt.value !== '');

            function matchProgramType(selected, candidate) {
                if (!selected || !candidate) return false;
                const s = selected.trim().toLowerCase();
                const c = candidate.trim().toLowerCase();
                if (s === c) return true;
                const sClean = s.replace(/[^a-z0-9]/g, '');
                const cClean = c.replace(/[^a-z0-9]/g, '');
                return sClean !== '' && sClean === cClean;
            }

            programTypeSelect.addEventListener('change', function(e) {
                const selectedType = this.value;
                const currentReg = regulationSelect.value;
                regulationSelect.innerHTML = '<option value="">Select Regulation</option>';
                
                if (!e.isTrusted) {
                    // Do not reset year and sem if triggered programatically on load
                } else {
                    yearSelect.innerHTML = '<option value="">-- Year --</option>';
                    semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                }

                if (selectedType) {
                    allRegulations.forEach(opt => {
                        const progType = opt.getAttribute('data-program') || '';
                        if (matchProgramType(selectedType, progType)) {
                            const newOpt = opt.cloneNode(true);
                            if (newOpt.value === currentReg) {
                                newOpt.selected = true;
                            }
                            regulationSelect.appendChild(newOpt);
                        }
                    });
                } else {
                    allRegulations.forEach(opt => regulationSelect.appendChild(opt.cloneNode(true)));
                }

                // Auto-select if only one option
                if (regulationSelect.options.length === 2 && !regulationSelect.value) {
                    regulationSelect.selectedIndex = 1;
                    regulationSelect.dispatchEvent(new Event('change'));
                }
                
                if (!e.isTrusted) {
                    regulationSelect.dispatchEvent(new Event('change'));
                }
            });

            regulationSelect.addEventListener('change', function(e) {
                const selectedReg = this.options[this.selectedIndex];
                const programType = selectedReg ? selectedReg.getAttribute('data-program') : '';
                
                const selectedYear = yearSelect.getAttribute('data-selected') || yearSelect.value;
                const selectedSem = semesterSelect.getAttribute('data-selected') || semesterSelect.value;
                
                yearSelect.innerHTML = '<option value="">-- Year --</option>';
                semesterSelect.innerHTML = '<option value="">-- Sem --</option>';

                if (programType) {
                    const isSshAdmin = {{ Auth::user()->role === 'ssh_admin' ? 'true' : 'false' }};
                    if (isSshAdmin) {
                        let opt = document.createElement('option');
                        opt.value = 1;
                        opt.textContent = '1 (First Year)';
                        opt.selected = true;
                        yearSelect.appendChild(opt);
                    } else {
                        const typeLower = programType.toLowerCase();
                        let maxYears = 4;
                        if (typeLower.includes('b.tech') || typeLower.includes('b.pharm')) maxYears = 4;
                        else if (typeLower.includes('m.tech') || typeLower.includes('m.pharm') || typeLower.includes('m.b.a') || typeLower.includes('mba') || typeLower.includes('m.c.a') || typeLower.includes('mca') || typeLower.includes('m.sc')) maxYears = 2;
                        else if (typeLower.includes('b.sc') || typeLower.includes('b.com') || typeLower.includes('b.b.a') || typeLower.includes('bba')) maxYears = 3;
                        else if (typeLower.includes('ph.d')) maxYears = 5;

                        for (let i = 1; i <= maxYears; i++) {
                            let opt = document.createElement('option');
                            opt.value = i;
                            opt.textContent = i;
                            if (i == selectedYear) {
                                opt.selected = true;
                            }
                            yearSelect.appendChild(opt);
                        }
                    }
                }
                
                if (!e.isTrusted || selectedYear) {
                    yearSelect.dispatchEvent(new Event('change'));
                }
            });

            yearSelect.addEventListener('change', function() {
                const selectedSem = semesterSelect.getAttribute('data-selected') || semesterSelect.value;
                semesterSelect.innerHTML = '<option value="">-- Sem --</option>';
                if (this.value) {
                    [1, 2].forEach(sem => {
                        let opt = document.createElement('option');
                        opt.value = sem;
                        opt.textContent = `Sem ${sem} (${this.value}-${sem})`;
                        if (sem == selectedSem) {
                            opt.selected = true;
                        }
                        semesterSelect.appendChild(opt);
                    });
                }
            });
            
            // Real-time Course Code Duplicate Validation
            const existingDatabaseCodes = @json($existingCourseCodes ?? []);
            const codeInput = document.getElementById('academic_edit_code');
            const errorDiv = document.getElementById('academic_edit_code_error');
            const form = document.querySelector('form[action="{{ route('academic.courses.update', $course->id) }}"]');

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

            // Apply initial filtering based on selection
            if(programTypeSelect.value) {
                programTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>

