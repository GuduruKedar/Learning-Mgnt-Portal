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
<script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full">
            <div class="flex items-center">
                <div class="relative">
                    <button id="profileDropdownBtn" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none shrink-0">
                    @if(Auth::user()->photo)
                        <img class="w-8 h-8 rounded-full object-cover shadow-sm border border-indigo-200" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="">
                    @else
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                            {{ substr(Auth::user()->first_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-gray-700 hidden sm:block">Hello, {{ Auth::user()->first_name ?? 'Admin' }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div id="profileDropdownMenu" class="absolute -right-2 sm:right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-100 py-1 z-50 hidden max-w-[calc(100vw-2rem)] origin-top-right">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Edit Profile</a>
                        <button type="button" id="openPasswordModalBtn" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Change Password</button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6 w-full">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <a href="{{ route('academic.courses') }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase tracking-wider mb-2 inline-flex items-center gap-1.5 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Courses Catalog
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Add New Course(s)</h1>
                        <p class="text-xs text-slate-500 mt-1">Configure curriculum specifications, department allocations, and batch-create subjects.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-semibold">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-sm flex items-center justify-between animate-fade-in">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-semibold">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-lg leading-none">&times;</button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-sm">
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
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-indigo-600 shadow-sm"></span>
                            <h2 class="text-base font-bold text-slate-900">Curriculum & Course Details</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                            Central Academic Portal
                        </span>
                    </div>

                    <form action="{{ route('academic.courses.store') }}" method="POST" class="p-6 sm:p-8">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            
                            <!-- Left Column: Academic Placement (Program, Regulation, Semester, Total Subjects) -->
                            <div class="lg:col-span-5 bg-slate-50/70 p-5 sm:p-6 rounded-2xl border border-slate-200/80 space-y-5">
                                <div class="pb-3 border-b border-slate-200/80 flex items-center justify-between">
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

                                <!-- 3. Semester -->
                                <div class="space-y-1.5">
                                    <label for="semester_select" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Semester <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="semester_select" name="semester" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium text-slate-800 cursor-pointer shadow-xs transition-all" required>
                                            <option value="">-- Select Semester --</option>
                                            <option value="1">1st Semester (1-1)</option>
                                            <option value="2">2nd Semester (1-2)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 4. Total Subjects To Create -->
                                <div class="space-y-1.5 pt-1">
                                    <label for="no_of_courses" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Total Subjects To Create <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="no_of_courses" id="no_of_courses" min="1" max="20" value="1" placeholder="e.g. 5" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold text-slate-800 shadow-xs transition-all" required>
                                    </div>
                                    <p class="text-[11px] text-slate-400">Enter count (1-20) to generate input rows dynamically on the right.</p>
                                </div>
                            </div>

                            <!-- Right Column: Dynamic Course Rows & Submission (7 cols) -->
                            <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
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
                                        <span class="text-xs text-slate-400 font-medium">Auto-generated rows</span>
                                    </div>

                                    <div class="grid grid-cols-12 gap-2 mb-2.5 px-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        <div class="col-span-1 text-center">#</div>
                                        <div class="col-span-4 sm:col-span-3">Subject Code *</div>
                                        <div class="col-span-7 sm:col-span-8">Subject Name / Title *</div>
                                    </div>

                                    <div id="dynamic_course_fields" class="space-y-3 max-h-[520px] overflow-y-auto pr-2">
                                        <div class="grid grid-cols-12 gap-2 items-center p-2.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:bg-slate-50 transition-colors">
                                            <div class="col-span-1 text-center font-bold text-xs text-slate-400">1</div>
                                            <div class="col-span-4 sm:col-span-3">
                                                <input type="text" name="code[]" placeholder="e.g. 22CS101" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-mono uppercase font-bold text-indigo-700 bg-white shadow-2xs" required>
                                            </div>
                                            <div class="col-span-7 sm:col-span-8">
                                                <input type="text" name="name[]" placeholder="e.g. Programming in C" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-semibold text-slate-800 bg-white shadow-2xs" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
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
        if (typeof window.initLMSUI === 'function') {
            window.initLMSUI();
        }
        @if($errors->has('password'))
            if (window.openPwdModal) window.openPwdModal();
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Program Type -> Regulation -> Semester Dropdown Logic
            const programTypeSelect = document.getElementById('program_type_select');
            const regulationSelect = document.getElementById('regulation_id_select');
            const semesterSelect = document.getElementById('semester_select');
            const allRegulations = Array.from(regulationSelect.options).filter(opt => opt.value !== '');

            function getOrdinal(n) {
                const s = ["th", "st", "nd", "rd"];
                const v = n % 100;
                return n + (s[(v - 20) % 10] || s[v] || s[0]);
            }

            function populateSemesters(programType) {
                const currentVal = semesterSelect.value;
                semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>';
                const isSshAdmin = {{ Auth::user()->role === 'ssh_admin' ? 'true' : 'false' }};
                
                if (isSshAdmin) {
                    const opt1 = document.createElement('option');
                    opt1.value = 1;
                    opt1.textContent = '1st Semester (1-1)';
                    semesterSelect.appendChild(opt1);

                    const opt2 = document.createElement('option');
                    opt2.value = 2;
                    opt2.textContent = '2nd Semester (1-2)';
                    semesterSelect.appendChild(opt2);

                    if (currentVal == '1' || currentVal == '2') {
                        semesterSelect.value = currentVal;
                    }
                    return;
                }

                let maxSem = 8;
                if (programType) {
                    const typeLower = programType.toLowerCase();
                    if (typeLower.includes('m.tech') || typeLower.includes('m.pharm') || typeLower.includes('m.b.a') || typeLower.includes('mba') || typeLower.includes('m.c.a') || typeLower.includes('mca') || typeLower.includes('m.sc')) {
                        maxSem = 4;
                    } else if (typeLower.includes('b.sc') || typeLower.includes('b.com') || typeLower.includes('b.b.a') || typeLower.includes('bba') || typeLower.includes('degree') || typeLower.includes('diploma')) {
                        maxSem = 6;
                    } else if (typeLower.includes('ph.d')) {
                        maxSem = 10;
                    } else if (typeLower.includes('b.tech') || typeLower.includes('b.pharm')) {
                        maxSem = 8;
                    }
                }

                for (let sem = 1; sem <= maxSem; sem++) {
                    const yearNum = Math.ceil(sem / 2);
                    const semInYear = (sem % 2 === 1) ? 1 : 2;
                    const opt = document.createElement('option');
                    opt.value = sem;
                    opt.textContent = `${getOrdinal(sem)} Semester (${yearNum}-${semInYear})`;
                    semesterSelect.appendChild(opt);
                }

                if (currentVal && semesterSelect.querySelector(`option[value="${currentVal}"]`)) {
                    semesterSelect.value = currentVal;
                }
            }

            // Initial load of semesters
            populateSemesters(programTypeSelect.value);

            programTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                regulationSelect.innerHTML = '<option value="">Select Regulation</option>';

                if (selectedType) {
                    allRegulations.forEach(opt => {
                        const progType = opt.getAttribute('data-program') || '';
                        if (progType.includes(selectedType) || selectedType.includes(progType) || 
                            (selectedType === 'B.Tech' && progType === 'B.Tech') ||
                            (selectedType === 'M.Tech' && progType === 'M.Tech')) {
                            regulationSelect.appendChild(opt.cloneNode(true));
                        }
                    });
                } else {
                    allRegulations.forEach(opt => regulationSelect.appendChild(opt.cloneNode(true)));
                }

                // Populate semesters based on the selected program
                populateSemesters(selectedType);

                // Auto-select if only one option
                if (regulationSelect.options.length === 2) {
                    regulationSelect.selectedIndex = 1;
                    regulationSelect.dispatchEvent(new Event('change'));
                }
            });

            regulationSelect.addEventListener('change', function() {
                const selectedReg = this.options[this.selectedIndex];
                const programType = selectedReg ? selectedReg.getAttribute('data-program') : programTypeSelect.value;
                populateSemesters(programType);
            });

            // 2. Dynamic Course Fields Generator
            const noOfCoursesInput = document.getElementById('no_of_courses');
            const dynamicFieldsContainer = document.getElementById('dynamic_course_fields');

            noOfCoursesInput.addEventListener('input', function() {
                const count = parseInt(this.value) || 0;
                const safeCount = Math.min(Math.max(count, 1), 20); // Cap at 20 rows
                
                const currentRows = dynamicFieldsContainer.children.length;

                if (safeCount > currentRows) {
                    for (let i = currentRows + 1; i <= safeCount; i++) {
                        const newRow = document.createElement('div');
                        newRow.className = 'grid grid-cols-12 gap-2 items-center p-2.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:bg-slate-50 transition-colors animate-fade-in';
                        newRow.innerHTML = `
                            <div class="col-span-1 text-center font-bold text-xs text-slate-400">${i}</div>
                            <div class="col-span-4 sm:col-span-3">
                                <input type="text" name="code[]" placeholder="e.g. Code" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-mono uppercase font-bold text-indigo-700 bg-white shadow-2xs" required>
                            </div>
                            <div class="col-span-7 sm:col-span-8">
                                <input type="text" name="name[]" placeholder="e.g. Subject Name" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs font-semibold text-slate-800 bg-white shadow-2xs" required>
                            </div>
                        `;
                        dynamicFieldsContainer.appendChild(newRow);
                    }
                } else if (safeCount < currentRows) {
                    for (let i = currentRows; i > safeCount; i--) {
                        dynamicFieldsContainer.removeChild(dynamicFieldsContainer.lastChild);
                    }
                }
                
                if (this.value !== '' && this.value != safeCount) {
                    this.value = safeCount;
                }
            });
            
            // Apply initial filtering based on selection
            if(programTypeSelect.value) {
                programTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>

