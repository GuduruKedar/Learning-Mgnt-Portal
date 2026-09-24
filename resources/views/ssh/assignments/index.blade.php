<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1st Year Assignments - SSH Department</title>
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
                <h1 class="text-base sm:text-lg font-bold text-slate-800">1st Year Assignments & Assessments</h1>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('ssh.assignments.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Assignment
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

                <!-- Search Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <form method="GET" action="{{ route('ssh.assignments.index') }}" class="flex gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assignment by title or course code..." class="flex-1 text-sm rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-4">
                        <button type="submit" class="py-2.5 px-5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors">
                            Search
                        </button>
                        <a href="{{ route('ssh.assignments.index') }}" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors">
                            Reset
                        </a>
                    </form>
                </div>

                <!-- Assignments Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Assignment Title</th>
                                    <th class="px-6 py-3.5 text-left">Course</th>
                                    <th class="px-6 py-3.5 text-left">Faculty / Creator</th>
                                    <th class="px-6 py-3.5 text-left">Due Date</th>
                                    <th class="px-6 py-3.5 text-left">Submissions</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($assignments as $assignment)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $assignment->title }}</div>
                                        <div class="text-xs text-slate-400">Total Marks: {{ $assignment->total_marks }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-indigo-700 font-mono text-xs">{{ $assignment->course->code ?? '1st Year' }}</div>
                                        <div class="text-xs text-slate-500">{{ $assignment->course->name ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                        {{ $assignment->staff->first_name ?? 'SSH' }} {{ $assignment->staff->last_name ?? 'Admin' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y h:i A') : 'No deadline' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('ssh.assignments.submissions', $assignment->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                            {{ $assignment->submissions_count }} Submissions &rarr;
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('ssh.assignments.submissions', $assignment->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50/90 text-sky-600 border border-sky-200/70 hover:bg-sky-600 hover:text-white hover:border-sky-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="View Submissions">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('ssh.assignments.destroy', $assignment->id) }}" onsubmit="return confirm('Delete assignment {{ $assignment->title }}?')" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50/90 text-rose-600 border border-rose-200/70 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all duration-150 shadow-2xs hover:shadow-xs hover:-translate-y-0.5" title="Delete Assignment">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        No assignments created for 1st year foundational courses yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($assignments->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $assignments->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

</body>
</html>
