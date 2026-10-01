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
        <!-- Top Header -->
        @include('partials.top_header')

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
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">TD</span>.</p>
            </footer>
        </main>
    </div>

</body>
</html>
