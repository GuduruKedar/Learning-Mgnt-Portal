<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll New Student - Civil Services LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-slate-50 text-slate-800 font-sans">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-end px-4 sm:px-6 z-50 relative shrink-0 w-full border-b border-slate-200">
            <div class="flex items-center gap-2.5">
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="w-full space-y-6">

                <!-- Page Header (In-Page) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200">
                    <div class="flex items-center gap-3.5">
                        <a href="{{ route('civil.students.index') }}" class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-sm hover:bg-slate-50 text-slate-600 transition-all hover:scale-105 shrink-0" title="Back to Enrolled Students">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight">Create & Enroll Student in Civil Services</h1>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Civil Services Academy • Student Registration & Academic Affiliation</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('civil.students.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm font-semibold text-xs transition-all">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Validation Errors Banner -->
                @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-1.5 text-rose-900">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Please fix the following validation errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 pl-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Main Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/40">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            Student Identity & Academic Placement
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Register student into their parent academic department and immediately enroll them into Civil Services.</p>
                    </div>

                    <form action="{{ route('civil.students.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                        @csrf

                        <!-- SECTION 1: Personal Information -->
                        <div>
                            <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[11px] font-bold">1</span>
                                Personal Information
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Rahul" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                    @error('first_name')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Sharma" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                    @error('last_name')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="student@vignan.ac.in" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                    @error('email')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Phone Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="10-digit number" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                    @error('phone_number')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Academic Affiliation & Civil Services Placement -->
                        <div class="pt-2 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[11px] font-bold">2</span>
                                Parent Academic Affiliation & Batch Placement
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">School <span class="text-rose-500">*</span></label>
                                    <select name="school_id" id="school_select" required class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                        <option value="">Select School</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Department <span class="text-rose-500">*</span></label>
                                    <select name="department_id" id="department_select" required class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" data-school-id="{{ $schools->where('code', $dept->school_id)->first()->id ?? '' }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Academic Level</label>
                                    <select name="level" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                        <option value="UG" {{ old('level') == 'UG' ? 'selected' : '' }}>UG (Undergraduate)</option>
                                        <option value="PG" {{ old('level') == 'PG' ? 'selected' : '' }}>PG (Postgraduate)</option>
                                        <option value="Diploma" {{ old('level') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="PhD" {{ old('level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Civil Services Batch Year</label>
                                    <input type="text" name="batch_year" value="{{ old('batch_year', date('Y')) }}" placeholder="e.g. {{ date('Y') }}" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Identifiers & Credentials -->
                        <div class="pt-2 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[11px] font-bold">3</span>
                                Registration Number & Account Security
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Registration Number (10 Chars) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" required maxlength="10" minlength="10" placeholder="e.g. 241FA04001" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')" class="w-full text-sm font-mono uppercase rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all tracking-wider font-semibold">
                                    <span class="text-[11px] text-slate-400 mt-1 block">10-character alphanumeric registration ID.</span>
                                    @error('username')
                                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Account Password</label>
                                    <input type="password" name="password" placeholder="Leave empty for Student#963" class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2.5 px-3.5 bg-slate-50/40 hover:bg-white transition-all">
                                    <span class="text-[11px] text-slate-400 mt-1 block">Default initial password is <code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Student#963</code>.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('civil.students.index') }}" class="px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Create & Enroll Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelectEl = document.getElementById('school_select');
            const departmentSelectEl = document.getElementById('department_select');

            let allDepartments = Array.from(departmentSelectEl.options)
                .filter(opt => opt.value !== '')
                .map(opt => ({
                    value: opt.value,
                    text: opt.text,
                    schoolId: opt.getAttribute('data-school-id')
                }));

            document.querySelectorAll('select').forEach(function(el) {
                if (!el.classList.contains('tomselected')) {
                    new TomSelect(el, {
                        create: false,
                        maxOptions: null,
                        sortField: { field: "text", direction: "asc" }
                    });
                }
            });

            if (schoolSelectEl && departmentSelectEl) {
                const schoolTs = schoolSelectEl.tomselect;
                const deptTs = departmentSelectEl.tomselect;

                function updateDepartments(selectedSchoolId, preserveValue = false) {
                    const currentVal = preserveValue ? deptTs.getValue() : '';
                    deptTs.clear();
                    deptTs.clearOptions();

                    const matchingDepts = selectedSchoolId 
                        ? allDepartments.filter(d => String(d.schoolId) === String(selectedSchoolId))
                        : allDepartments;

                    matchingDepts.forEach(d => {
                        deptTs.addOption({ value: d.value, text: d.text });
                    });
                    deptTs.refreshOptions(false);

                    if (currentVal && matchingDepts.some(d => String(d.value) === String(currentVal))) {
                        deptTs.setValue(currentVal, true);
                    }
                }

                schoolTs.on('change', function(selectedSchoolId) {
                    updateDepartments(selectedSchoolId, false);
                });

                // Run on initial load if school is already selected (e.g. on validation redirect)
                if (schoolTs.getValue()) {
                    updateDepartments(schoolTs.getValue(), true);
                }
            }
        });
    </script>
</body>
</html>
