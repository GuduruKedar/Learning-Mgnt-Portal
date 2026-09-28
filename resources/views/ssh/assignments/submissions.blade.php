<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions: {{ $assignment->title }} - SSH Department</title>
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
                <a href="{{ route('ssh.assignments.index') }}" class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-800">{{ $assignment->title }}</h1>
                    <p class="text-xs text-slate-500">{{ $assignment->course->name }} ({{ $assignment->course->code }}) • Due: {{ $assignment->due_date ? date('M d, Y h:i A', strtotime($assignment->due_date)) : 'No deadline' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $assignment->submissions->count() }} Submissions
                </span>
                <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                @include('partials.profile_dropdown')
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <div class="max-w-7xl mx-auto space-y-6">

                <!-- Submissions Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900">Student Response Records</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">Reg No</th>
                                    <th class="px-6 py-3.5 text-left">Student Name</th>
                                    <th class="px-6 py-3.5 text-left">Submitted At</th>
                                    <th class="px-6 py-3.5 text-left">Status</th>
                                    <th class="px-6 py-3.5 text-left">Marks / Score</th>
                                    <th class="px-6 py-3.5 text-right">File / Response</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($assignment->submissions as $sub)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-indigo-700">
                                        {{ $sub->user->username ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">
                                        {{ $sub->user->profile->first_name ?? '' }} {{ $sub->user->profile->last_name ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        {{ $sub->created_at ? $sub->created_at->format('M d, Y h:i A') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $sub->status === 'graded' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            {{ ucfirst($sub->status ?? 'submitted') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-900 text-xs">
                                        {{ $sub->marks !== null ? $sub->marks . ' / ' . $assignment->total_marks : 'Pending Evaluation' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($sub->file_path)
                                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-xs transition-colors">
                                            View Solution &rarr;
                                        </a>
                                        @else
                                        <span class="text-xs text-slate-400">Online text</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        No submissions recorded yet for this assignment.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
