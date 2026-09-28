<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit S&H Faculty - SSH Department</title>
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
                <h1 class="text-base sm:text-lg font-bold text-slate-800">Edit S&H Faculty Member</h1>
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
                    <p class="font-bold mb-1">Please correct the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('ssh.staff.update', $staff->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Faculty Academic Profile</h2>
                            <p class="text-xs text-slate-500">Employee ID: <span class="font-mono font-bold text-indigo-700">{{ $staff->username }}</span></p>
                        </div>
                        @if($staff->profile && $staff->profile->photo)
                        <img src="{{ asset('storage/' . $staff->profile->photo) }}" alt="Profile" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 shadow-sm">
                        @endif
                    </div>

                    <!-- Row 1: Names -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $staff->profile->first_name ?? '') }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $staff->profile->middle_name ?? '') }}" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $staff->profile->last_name ?? '') }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>
                    </div>

                    <!-- Row 2: Department & Designation -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">S&H Department Discipline *</label>
                            <select name="department_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="">Select S&H Department</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $staff->profile->department->id ?? '') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Designation *</label>
                            <select name="designation" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                <option value="Assistant Professor" {{ old('designation', $staff->profile->designation ?? '') == 'Assistant Professor' ? 'selected' : '' }}>Assistant Professor</option>
                                <option value="Associate Professor" {{ old('designation', $staff->profile->designation ?? '') == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                <option value="Professor" {{ old('designation', $staff->profile->designation ?? '') == 'Professor' ? 'selected' : '' }}>Professor</option>
                                <option value="Head of Department" {{ old('designation', $staff->profile->designation ?? '') == 'Head of Department' ? 'selected' : '' }}>Head of Department</option>
                                <option value="Dean - S&H" {{ old('designation', $staff->profile->designation ?? '') == 'Dean - S&H' ? 'selected' : '' }}>Dean - S&H</option>
                                <option value="Lecturer" {{ old('designation', $staff->profile->designation ?? '') == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                                <option value="Teaching Assistant" {{ old('designation', $staff->profile->designation ?? '') == 'Teaching Assistant' ? 'selected' : '' }}>Teaching Assistant</option>
                                <option value="Lab Assistant" {{ old('designation', $staff->profile->designation ?? '') == 'Lab Assistant' ? 'selected' : '' }}>Lab Assistant</option>
                                <option value="Visiting Faculty" {{ old('designation', $staff->profile->designation ?? '') == 'Visiting Faculty' ? 'selected' : '' }}>Visiting Faculty</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Contact Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Email</label>
                            <input type="email" name="email" value="{{ old('email', $staff->profile->email ?? '') }}" placeholder="name@vignan.ac.in" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $staff->profile->phone ?? '') }}" placeholder="10 digit mobile" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>
                    </div>

                    <!-- Row 4: Change Password & Photo -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Change Password (Optional)</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Update Photo</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('ssh.staff.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 font-semibold text-xs text-slate-700 hover:bg-slate-100 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                            Update Faculty
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

</body>
</html>
