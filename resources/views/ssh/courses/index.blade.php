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
        <!-- Top Header -->
        @include('partials.top_header')

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
                                            @php
                                                $cCode = addslashes($course->code);
                                                $cName = addslashes($course->name);
                                                $cDept = addslashes($course->department->name ?? $course->department_id);
                                                $cReg = addslashes(($course->regulation->code ?? 'N/A') . (!empty($course->regulation->curriculum) ? ' - ' . $course->regulation->curriculum : ''));
                                                $cYear = $course->year ?? 1;
                                                $cSem = $course->semester ?? 1;
                                                $cStaffCount = $course->staff_count ?? $course->staff->count();
                                                $cEnrollCount = $course->enrollments_count ?? 0;
                                                $cMatCount = $course->materials_count ?? 0;
                                                $cAssignCount = $course->assignments_count ?? 0;
                                                $cDelUrl = route('ssh.courses.destroy', $course->id);
                                            @endphp
                                            <button type="button" 
                                                onclick="openDeleteCourseModal('{{ $cCode }}', '{{ $cName }}', '{{ $cDept }}', '{{ $cReg }}', '{{ $cYear }}', '{{ $cSem }}', {{ $cStaffCount }}, {{ $cEnrollCount }}, {{ $cMatCount }}, {{ $cAssignCount }}, '{{ $cDelUrl }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5 cursor-pointer" 
                                                title="Delete Course & All Linked Records">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
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

    <!-- Cascade Delete Course Confirmation Modal -->
    <div id="deleteCourseModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-slate-200">
            <!-- Modal Header -->
            <div class="px-6 py-4.5 bg-gradient-to-r from-rose-50 to-orange-50 border-b border-rose-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 shadow-xs border border-rose-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Delete 1st Year Course</h3>
                        <p class="text-xs text-slate-500">Confirm permanent cascade deletion</p>
                    </div>
                </div>
                <button type="button" onclick="closeDeleteCourseModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-white/60 transition-colors focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">
                <!-- Course Target Preview -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <span id="delModalCourseCode" class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100"></span>
                        <span id="delModalDeptBadge" class="text-xs font-semibold text-slate-600 bg-white px-2.5 py-0.5 rounded-md border border-slate-200"></span>
                    </div>
                    <h4 id="delModalCourseName" class="text-base font-bold text-slate-900"></h4>
                    <p id="delModalMeta" class="text-xs text-slate-500 font-medium"></p>
                </div>

                <!-- Linked Child Nodes Section -->
                <div>
                    <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Linked Records That Will Be Cleaned Up:</span>
                    </h5>
                    
                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Faculty Allocations -->
                        <div class="p-3 rounded-xl bg-indigo-50/70 border border-indigo-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalStaffCount" class="text-sm font-bold text-indigo-900">0 Faculty</div>
                                <div class="text-[11px] text-indigo-600 font-medium">Assigned Faculty</div>
                            </div>
                        </div>

                        <!-- Enrolled Students -->
                        <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalEnrollmentCount" class="text-sm font-bold text-amber-900">0 Students</div>
                                <div class="text-[11px] text-amber-600 font-medium">Enrolled Students</div>
                            </div>
                        </div>

                        <!-- Course Materials -->
                        <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div id="delModalMaterialsCount" class="text-sm font-bold text-blue-900">0 Materials</div>
                                <div class="text-[11px] text-blue-600 font-medium">Files & Notes</div>
                            </div>
                        </div>

                        <!-- Assignments & Tasks -->
                        <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <div id="delModalAssignmentsCount" class="text-sm font-bold text-purple-900">0 Assignments</div>
                                <div class="text-[11px] text-purple-600 font-medium">Tasks & Submissions</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Callout -->
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 space-y-1">
                    <p class="font-bold flex items-center gap-1.5 text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Are you sure you want to delete this course?</span>
                    </p>
                    <p class="text-rose-700 leading-relaxed">
                        This action will permanently delete this 1st year foundational course and automatically clean up all associated faculty allocations, student enrollments, learning materials, and assignment submissions.
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeDeleteCourseModal()" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer">
                    Cancel / Keep Course
                </button>
                <form id="deleteCourseForm" method="POST" action="" class="inline m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Yes, Delete Course & All Linked Data</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteCourseModal(code, name, dept, reg, year, sem, staffCount, enrollmentsCount, materialsCount, assignmentsCount, deleteUrl) {
            document.getElementById('delModalCourseCode').innerText = code;
            document.getElementById('delModalCourseName').innerText = name;
            document.getElementById('delModalDeptBadge').innerText = dept;
            document.getElementById('delModalMeta').innerText = `${reg} • Year ${year}, Semester ${sem}`;
            
            document.getElementById('delModalStaffCount').innerText = `${staffCount} Faculty`;
            document.getElementById('delModalEnrollmentCount').innerText = `${enrollmentsCount} Students`;
            document.getElementById('delModalMaterialsCount').innerText = `${materialsCount} Materials`;
            document.getElementById('delModalAssignmentsCount').innerText = `${assignmentsCount} Assignments`;
            
            document.getElementById('deleteCourseForm').action = deleteUrl;
            
            const modal = document.getElementById('deleteCourseModal');
            modal.classList.remove('hidden');
        }

        function closeDeleteCourseModal() {
            const modal = document.getElementById('deleteCourseModal');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
