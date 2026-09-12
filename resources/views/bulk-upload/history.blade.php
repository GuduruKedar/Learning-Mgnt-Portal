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
<body class="p-8">
    <div class="max-w-7xl mx-auto space-y-6">
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
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($histories as $history)
                    <tr data-id="{{ $history->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $history->file_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $history->target_role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="status-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $history->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $history->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $history->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $history->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($history->status) }}
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No upload history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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