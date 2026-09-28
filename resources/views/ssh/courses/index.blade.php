<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1st Year Foundational Courses - SSH Department</title>
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
                <a href="{{ route('ssh.dashboard') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-base sm:text-lg font-bold text-slate-800">1st Year Foundational Courses (Sem 1 & Sem 2)</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.courses.allocations') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Faculty Allocations
                </a>
                <a href="{{ route('ssh.courses.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Foundational Course
                </a>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">

                @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
                </div>
                @endif

                <!-- Filters Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <form method="GET" action="{{ route('ssh.courses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Search Course</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Course Code or Name..." class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Regulation</label>
                            <select name="regulation_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                                <option value="">All Regulations</option>
                                @foreach($regulations as $reg)
                                    <option value="{{ $reg->id }}" {{ request('regulation_id') == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->code }} - {{ $reg->program_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Department</label>
                            <select name="department" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ request('department') == $dept->code ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Semester</label>
                            <select name="semester" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3">
                                <option value="">All Semesters (1 & 2)</option>
                                <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors">
                                Filter
                            </button>
                            <a href="{{ route('ssh.courses.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Courses Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Code</th>
                                    <th class="px-6 py-3.5 text-left">Course Name</th>
                                    <th class="px-6 py-3.5 text-left">Department / Regulation</th>
                                    <th class="px-6 py-3.5 text-left">Semester</th>
                                    <th class="px-6 py-3.5 text-left">Faculty Assigned</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($courses as $course)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-6 py-4 font-bold text-indigo-700">
                                        {{ $course->code }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $course->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $course->materials_count }} materials • {{ $course->assignments_count }} assignments</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-semibold text-slate-700">{{ $course->department->name ?? $course->department_id }}</div>
                                        <div class="text-slate-400">{{ $course->regulation->code ?? 'General' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Sem {{ $course->semester }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-2 min-w-[220px]">
                                            @if($course->staff->count() > 0)
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach($course->staff as $fac)
                                                    @php
                                                        $facFullName = trim(($fac->first_name ?? '') . ' ' . ($fac->last_name ?? ''));
                                                        $facCode = $fac->username ?? 'N/A';
                                                        $facEmail = $fac->profile->email ?? ($fac->email ?? '');
                                                        $facPhone = $fac->profile->phone ?? '';
                                                        $facDesig = $fac->profile->designation ?? 'Faculty';
                                                        $facDept = $fac->profile->department->name ?? ($fac->profile->departments_id ?? 'Social Sciences & Humanities');
                                                        $facSchool = $fac->profile->school->name ?? ($fac->profile->schools_id ?? 'School of Applied Sciences & Humanities');
                                                        $facPhoto = $fac->profile->photo ? asset('storage/' . $fac->profile->photo) : '';
                                                        $unallocUrl = route('academic.courses.unallocate', [$course->id, $fac->id]);
                                                        $courseContext = $course->code . ' - ' . $course->name . ' (Sem ' . $course->semester . ')';
                                                        $facDeptShort = $fac->profile->department->code ?? (strlen($facDept) > 12 ? substr($facDept, 0, 10).'..' : $facDept);
                                                    @endphp
                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50/80 border border-indigo-100 text-indigo-800 text-xs font-medium group hover:bg-indigo-100/90 transition-all">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                                        <button type="button" 
                                                            onclick="showFacultyDetails('{{ addslashes($facFullName) }}', '{{ addslashes($facCode) }}', '{{ addslashes($facEmail) }}', '{{ addslashes($facPhone) }}', '{{ addslashes($facDesig) }}', '{{ addslashes($facDept) }}', '{{ addslashes($facSchool) }}', '{{ $facPhoto }}', '{{ $unallocUrl }}', '{{ addslashes($courseContext) }}')"
                                                            class="hover:underline focus:outline-none text-left cursor-pointer font-semibold text-indigo-900"
                                                            title="Click to view details of {{ $facFullName }}">
                                                            {{ $facFullName }} <span class="text-[10px] text-indigo-500 font-normal">({{ $facDeptShort }})</span>
                                                        </button>
                                                        <form action="{{ $unallocUrl }}" method="POST" class="inline" onsubmit="return confirm('Remove faculty {{ addslashes($facFullName) }} from this course?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors p-0.5 rounded focus:outline-none" title="Remove Faculty">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-[11px] text-slate-400 font-medium italic">
                                                    No faculty allocated yet
                                                </div>
                                            @endif

                                            <!-- Quick Inline Assign Dropdown -->
                                            <form action="{{ route('academic.courses.allocate', $course->id) }}" method="POST" class="flex items-center gap-1.5 w-full">
                                                @csrf
                                                <div class="relative flex-1">
                                                    <select name="staff_id" required class="w-full text-xs py-1.5 px-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-medium hover:bg-white hover:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none cursor-pointer transition-all">
                                                        <option value="">+ Assign Faculty...</option>
                                                        @if(isset($availableStaff))
                                                            @php
                                                                $groupedStaff = $availableStaff->groupBy(function($st) {
                                                                    return $st->profile->department->name ?? ($st->profile->school->name ?? 'General / S&H');
                                                                });
                                                            @endphp
                                                            @foreach($groupedStaff as $deptName => $facultyList)
                                                                <optgroup label="{{ $deptName }}">
                                                                    @foreach($facultyList as $st)
                                                                        @if(!$course->staff->contains('id', $st->id))
                                                                            <option value="{{ $st->id }}">
                                                                                {{ $st->username }} - {{ $st->first_name }} {{ $st->last_name }} ({{ $st->profile->designation ?? 'Faculty' }})
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <button type="submit" class="py-1.5 px-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-xs transition-colors shrink-0" title="Assign selected faculty">
                                                    Assign
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('ssh.courses.edit', $course->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50/90 text-indigo-600 border border-indigo-200/70 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Edit Course">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('ssh.courses.destroy', $course->id) }}" onsubmit="return confirm('Delete course {{ $course->code }}?')" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Course">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        No 1st year foundational courses found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($courses->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $courses->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>
</html>
