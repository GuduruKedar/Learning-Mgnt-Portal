<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1st Year Faculty Allocations - SSH Department</title>
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
                @if(session('warning'))
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold flex items-center justify-between">
                    <span>{{ session('warning') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-900">&times;</button>
                </div>
                @endif

                <!-- Course Allocation Cards List -->
                <div class="space-y-4">
                    @forelse($courses as $course)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        
                        <!-- Left: Course Details -->
                        <div class="space-y-2 max-w-lg">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-indigo-100 text-indigo-800">{{ $course->code }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Sem {{ $course->semester }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $course->regulation->code ?? 'General' }}</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">{{ $course->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $course->department->name ?? $course->department_id }}</p>
                        </div>

                        <!-- Center: Allocated Faculty List -->
                        <div class="flex-1">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Allocated Faculty</h4>
                            @if($course->staff->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($course->staff as $staff)
                                    @php
                                        $staffDept = $staff->profile->department->name ?? ($staff->profile->departments_id ?? 'Dept');
                                        $staffDeptShort = $staff->profile->department->code ?? (strlen($staffDept) > 12 ? substr($staffDept, 0, 10).'..' : $staffDept);
                                    @endphp
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800">
                                        <span>{{ $staff->first_name }} {{ $staff->last_name }} <span class="text-[10px] text-slate-500 font-normal">({{ $staffDeptShort }})</span></span>
                                        <button type="button"
                                            onclick="window.openUniversalDeleteModal({
                                                title: 'Unallocate Faculty Member',
                                                itemName: '{{ addslashes($staff->first_name . ' ' . $staff->last_name) }}',
                                                itemType: 'Faculty Assignment for {{ addslashes($course->name) }}',
                                                deleteUrl: '{{ route('ssh.courses.unallocate', [$course->id, $staff->id]) }}',
                                                warningMessage: 'Removing this faculty allocation will unlink them from teaching this course section and revoke their direct grade & material management access for this course.',
                                                cascadeItems: [
                                                    { label: 'Course Section Access', count: 'Revoked' },
                                                    { label: 'Faculty Course Allocation', count: '1 Link Removed' }
                                                ]
                                            })"
                                            class="text-slate-400 hover:text-rose-600 transition-colors text-sm font-bold leading-none p-0.5"
                                            title="Remove Allocation">
                                            &times;
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs font-medium text-rose-500 bg-rose-50 px-2.5 py-1 rounded-lg">No faculty allocated</span>
                            @endif
                        </div>

                        <!-- Right: Allocate Faculty Dropdown Form -->
                        <div class="shrink-0 w-full md:w-64">
                            <form method="POST" action="{{ route('ssh.courses.allocate', $course->id) }}" class="flex items-center gap-2">
                                @csrf
                                <select name="staff_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 px-2.5">
                                    <option value="">Assign Faculty...</option>
                                    @php
                                        $groupedStaff = $availableStaff->groupBy(function($st) {
                                            return $st->profile->department->name ?? ($st->profile->school->name ?? 'General / S&H');
                                        });
                                    @endphp
                                    @foreach($groupedStaff as $deptName => $facultyList)
                                        <optgroup label="{{ $deptName }}">
                                            @foreach($facultyList as $faculty)
                                                @if(!$course->staff->contains('id', $faculty->id))
                                                    <option value="{{ $faculty->id }}">{{ $faculty->username }} - {{ $faculty->first_name }} {{ $faculty->last_name }} ({{ $faculty->profile->designation ?? 'Faculty' }})</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <button type="submit" class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow transition-colors shrink-0">
                                    Assign
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl p-12 text-center text-slate-400 border border-slate-200">
                        No 1st year foundational courses found.
                    </div>
                    @endforelse
                </div>

                @if($courses->hasPages())
                <div class="bg-white rounded-2xl p-4 border border-slate-200">
                    {{ $courses->links() }}
                </div>
                @endif

            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">TD</span>.</p>
            </footer>
        </main>
    </div>

</body>
</html>
