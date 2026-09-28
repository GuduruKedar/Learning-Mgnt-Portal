<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register 1st Year Student - SSH Department</title>
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
                <a href="{{ route('ssh.students.index') }}" class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 transition-colors" title="Back to Students Directory">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Register New First Year Student</h1>
                    <p class="text-[11px] text-slate-500 font-medium hidden sm:block">Sciences & Humanities • Freshers & 1st Year Onboarding</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all">
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

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/40">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            Student Identity & Academic Placement
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Provide the student's personal details, parent branch, academic year, semester, and credentials.</p>
                    </div>

                    <form method="POST" action="{{ route('ssh.students.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-7">
                        @csrf

                        <!-- SECTION 1: Personal Identity -->
                        <div>
                            <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3">1. Personal Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Aarav" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Middle Name</label>
                                    <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Patel" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Credentials & Identifiers -->
                        <div>
                            <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3">2. Register Number & Password</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Register Number (10 Chars) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="username" value="{{ old('username') }}" required maxlength="10" placeholder="e.g. 231FA07009" class="w-full text-sm font-mono uppercase rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all tracking-wider font-semibold">
                                    <span class="text-[11px] text-slate-400 mt-1 block">First two digits represent the year (e.g., <span class="font-semibold text-slate-600">23</span>1FA07009 belongs to Year 2023).</span>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Default Password</label>
                                    <input type="password" name="password" placeholder="Leave empty for Student#963" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                    <span class="text-[11px] text-slate-400 mt-1 block">Default initial password is <code class="text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded">Student#963</code>.</span>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Academic Placement -->
                        <div>
                            <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3">3. Academic Placement & Department</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Parent School <span class="text-rose-500">*</span></label>
                                    <select name="school_id" id="school_select" required class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all cursor-pointer">
                                        <option value="">Select Parent School</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Parent Department / Branch <span class="text-rose-500">*</span></label>
                                    <select name="department_id" id="department_select" required class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all cursor-pointer">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" data-school="{{ $dept->school_id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Contact Information -->
                        <div>
                            <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3">4. Contact & Photo</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="student@vignan.ac.in or @gmail.com" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Mobile Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" placeholder="10 digit mobile number" class="w-full text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 py-2.5 px-3 bg-slate-50/40 hover:bg-white transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Profile Photo (Optional)</label>
                                <input type="file" name="photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('ssh.students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-7 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-200 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Register Student
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Dynamic cascading department dropdown based on school selection
        const schoolSelect = document.getElementById('school_select');
        const deptSelect = document.getElementById('department_select');

        schoolSelect.addEventListener('change', function() {
            const selectedSchoolVal = this.value;
            if (!selectedSchoolVal) return;

            fetch(`/schools/${selectedSchoolVal}/departments`)
                .then(res => res.json())
                .then(data => {
                    deptSelect.innerHTML = '<option value="">Select Department</option>';
                    data.forEach(dept => {
                        const opt = document.createElement('option');
                        opt.value = dept.id;
                        opt.textContent = dept.name;
                        deptSelect.appendChild(opt);
                    });
                })
                .catch(() => {});
        });
    </script>
</body>
</html>
