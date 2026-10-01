<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload History - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            <div class="w-full space-y-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Bulk Upload History</h1>
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Back to Dashboard</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200" id="historyTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">File Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">Uploaded By</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase">Date</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-900 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($histories as $history)
                            @php
                                $hasErrors = !empty($history->error_message) && $history->error_message !== '[]';
                            @endphp
                            <tr data-id="{{ $history->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $history->file_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $history->target_role }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $history->status === 'completed' && !$hasErrors ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $history->status === 'completed' && $hasErrors ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $history->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $history->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $history->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $history->status === 'completed' && $hasErrors ? 'Completed w/ Errors' : ucfirst($history->status) }}
                                    </span>
                                    @if($history->status === 'failed')
                                    <p class="text-xs text-red-500 mt-1 truncate max-w-xs" title="{{ $history->error_message }}">{{ $history->error_message }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        @php $pct = $history->total_rows > 0 ? min(100, round(($history->processed_rows / $history->total_rows) * 100)) : 0; @endphp
                                        <div class="progress-bar bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1 inline-block progress-text">{{ $history->processed_rows }} / {{ $history->total_rows }} Rows</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $history->uploader->username ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $history->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($hasErrors)
                                        <a href="{{ route('bulk-upload.errors.excel', $history->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition-colors shadow-sm" title="Download Excel Error Spreadsheet">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M11.5 15.5l-1.5-2.5-1.5 2.5H7l2.25-3.5L7 8.5h1.5l1.5 2.5 1.5-2.5H13l-2.25 3.5L13 15.5h-1.5z"/></svg>
                                            <span>Excel (.xlsx)</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">No errors</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">No upload history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        
            <footer class="mt-8 border-t border-gray-200 dark:border-slate-800 pt-4 pb-2">
                <p class="text-center text-xs text-gray-500 dark:text-slate-400 font-medium">&copy; {{ date('Y') }} Learning Management System. Powered by <span class="font-semibold text-gray-700 dark:text-gray-300">TD</span>.</p>
            </footer>
        </main>
    </div>

    <script>
        // Simple polling for progress
        setInterval(() => {
            fetch('{{ route("bulk-upload.progress") }}')
                .then(res => res.json())
                .then(data => {
                    if(data && data.length > 0) {
                        data.forEach(item => {
                            const row = document.querySelector(`tr[data-id="${item.id}"]`);
                            if(row) {
                                const pct = item.total_rows > 0 ? Math.min(100, Math.round((item.processed_rows / item.total_rows) * 100)) : 0;
                                row.querySelector('.progress-bar').style.width = pct + '%';
                                row.querySelector('.progress-text').innerText = `${item.processed_rows} / ${item.total_rows} Rows`;
                                
                                const badge = row.querySelector('.status-badge');
                                badge.innerText = item.status.charAt(0).toUpperCase() + item.status.slice(1);
                                if(item.status === 'completed') {
                                    badge.className = 'status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                                }
                            }
                        });
                    }
                });
        }, 3000); // Check every 3 seconds
    </script>
</body>
</html>