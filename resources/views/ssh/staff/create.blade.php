<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register S&H Faculty - SSH Department</title>
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
                <a href="{{ route('ssh.staff.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-base sm:text-lg font-bold text-slate-800">Register S&H Faculty Member</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.staff.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition-all">
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
                    <p class="font-bold mb-1">Please correct the following issues:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Department Overview Alert -->
                <div class="bg-gradient-to-r from-indigo-900 to-blue-900 text-white rounded-2xl p-5 shadow-sm space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="p-1 rounded-md bg-indigo-500/30 text-indigo-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </span>
                        <h2 class="text-sm font-bold tracking-wide">Sciences & Humanities (S&H) Directorate</h2>
                    </div>
                    <p class="text-xs text-indigo-200 leading-relaxed">
                        Create faculty accounts across <strong class="text-white">Mathematics and Statistics</strong>, <strong class="text-white">Physics</strong>, <strong class="text-white">Chemistry</strong>, <strong class="text-white">Department of English and Other Indian & Foreign Languages</strong>, or any newly configured future disciplines.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                    <form method="POST" action="{{ route('ssh.staff.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-base font-bold text-slate-900">Faculty Academic Profile</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Please provide accurate contact and discipline details.</p>
                        </div>

                        <!-- Row 1: Names -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Ramesh" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Sharma" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                        </div>

                        <!-- Row 2: Employee Code & Department Selection -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Employee ID / Code <span class="text-rose-500">*</span></label>
                                <input type="text" name="username" value="{{ old('username') }}" required maxlength="10" placeholder="e.g. 10008" class="w-full text-sm font-mono uppercase rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <p class="text-[11px] text-slate-400 mt-1">This will serve as the faculty's login username.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">S&H Department Discipline <span class="text-rose-500">*</span></label>
                                <select name="department_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    <option value="">-- Select S&H Department --</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->code }})
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-slate-400 mt-1">Select discipline (Maths, Physics, Chemistry, English, etc.)</p>
                            </div>
                        </div>

                        <!-- Row 3: Designation & Email -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Designation <span class="text-rose-500">*</span></label>
                                <select name="designation" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    <option value="">Select Designation</option>
                                    <option value="Assistant Professor" {{ old('designation') == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                    <option value="Associate Professor" {{ old('designation') == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                    <option value="Professor" {{ old('designation') == 'Professor' ? 'selected' : '' }}>Professor</option>
                                    <option value="Head of Department" {{ old('designation') == 'Head of Department' ? 'selected' : '' }}>Head of Department</option>
                                    <option value="Dean - S&H" {{ old('designation') == 'Dean - S&H' ? 'selected' : '' }}>Dean - S&H</option>
                                    <option value="Lecturer" {{ old('designation') == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                                    <option value="Teaching Assistant" {{ old('designation') == 'Teaching Assistant' ? 'selected' : '' }}>Teaching Assistant</option>
                                    <option value="Lab Assistant" {{ old('designation') == 'Lab Assistant' ? 'selected' : '' }}>Lab Assistant</option>
                                    <option value="Visiting Faculty" {{ old('designation') == 'Visiting Faculty' ? 'selected' : '' }}>Visiting Faculty</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Official Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="faculty@vignan.ac.in" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                        </div>

                        <!-- Row 4: Phone & Initial Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" maxlength="10" placeholder="10-digit mobile" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Initial Password</label>
                                <input type="password" name="password" placeholder="Default: Staff@852" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <p class="text-[11px] text-slate-400 mt-1">Leave empty to use standard default password (Staff@852).</p>
                            </div>
                        </div>

                        <!-- Row 5: Profile Photo -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Profile Photo (Optional)</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('ssh.staff.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md transition-all">
                                Register Faculty Member
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
