<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50">
            <h3 class="text-lg font-bold text-indigo-900">Bulk Upload</h3>
            <button type="button" onclick="closeBulkUploadModal()" class="text-indigo-400 hover:text-indigo-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 relative">
            <div id="bulkUploadFormContent">
                <div class="mb-4">
                    <a href="{{ route('bulk-upload.template') }}" id="templateDownloadLink" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Sample Template
                    </a>
                </div>
                <form action="{{ route('bulk-upload.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleBulkUploadSubmit(event)">
                    @csrf
                    <input type="hidden" name="target_role" id="bulkUploadRole" value="">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select File (.csv, .xls, .xlsx)</label>
                            <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('target_role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeBulkUploadModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">Upload & Process</button>
                    </div>
                </form>
            </div>
            
            <!-- Progress Bar Overlay -->
            <div id="bulkUploadProgress" class="hidden absolute inset-0 bg-white bg-opacity-90 flex flex-col items-center justify-center z-10 p-6">
                <div class="w-full max-w-xs text-center">
                    <p class="text-sm font-medium text-indigo-900 mb-3" id="progressText">Processing Upload...</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
                        <div id="progressBarFill" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Please wait, do not close this window.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Errors Modal -->
@if(session('import_errors'))
<div id="importErrorsModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-red-100 flex justify-between items-center bg-red-50">
            <h3 class="text-lg font-bold text-red-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Import Validation Errors
            </h3>
            <button type="button" onclick="document.getElementById('importErrorsModal').classList.add('hidden')" class="text-red-400 hover:text-red-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Some rows in your uploaded file failed validation and were skipped. Please review the errors below. You can copy this list to correct your file.</p>
            
            @if(isset(session('import_errors')['__truncated']))
            <div class="bg-amber-50 border border-amber-300 text-amber-800 rounded-lg p-3 mb-3 text-xs font-semibold flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ session('import_errors')['__truncated'] }}
            </div>
            @endif
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 max-h-64 overflow-y-auto mb-4 font-mono text-sm">
                @foreach(session('import_errors') as $key => $error)
                    @if($key !== '__truncated')
                        <div class="text-red-600 mb-1 border-b border-gray-200 pb-1 last:border-0">{{ $error }}</div>
                    @endif
                @endforeach
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="downloadErrorsAsText()" class="px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Errors
                </button>
                <button type="button" onclick="document.getElementById('importErrorsModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 shadow-sm">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function openBulkUploadModal(role) {
        document.getElementById('bulkUploadRole').value = role;
        document.getElementById('bulkUploadModal').classList.remove('hidden');
        
        const templateLink = document.getElementById('templateDownloadLink');
        if (templateLink) {
            templateLink.href = "{{ route('bulk-upload.template') }}?role=" + role;
        }

        // Reset state
        document.getElementById('bulkUploadFormContent').style.opacity = '1';
        document.getElementById('bulkUploadProgress').classList.add('hidden');
        document.getElementById('progressBarFill').style.width = '0%';
    }

    function closeBulkUploadModal() {
        document.getElementById('bulkUploadModal').classList.add('hidden');
    }

    function handleBulkUploadSubmit(e) {
        // Show progress overlay
        document.getElementById('bulkUploadProgress').classList.remove('hidden');
        document.getElementById('bulkUploadFormContent').style.opacity = '0.3';
        
        let progress = 0;
        const bar = document.getElementById('progressBarFill');
        const text = document.getElementById('progressText');
        
        // Simulate progress while the synchronous request happens
        const interval = setInterval(() => {
            if (progress < 90) {
                progress += Math.random() * 10;
                if (progress > 90) progress = 90;
                bar.style.width = progress + '%';
                
                if (progress > 30 && progress < 60) text.innerText = 'Validating data...';
                if (progress >= 60 && progress < 85) text.innerText = 'Importing users...';
                if (progress >= 85) text.innerText = 'Finishing up...';
            }
        }, 500);
        
        // Will be cleared when page reloads
    }

    function downloadErrorsAsText() {
        const rawErrors = {!! json_encode(session('import_errors') ?? []) !!};
        // Exclude the __truncated notice from the downloaded text
        const errorsText = Object.entries(rawErrors)
            .filter(([key, _]) => key !== '__truncated')
            .map(([_, val]) => val)
            .join('\n');
            
        const blob = new Blob([errorsText], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'import_errors.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->has('file') || $errors->has('target_role'))
            openBulkUploadModal('{{ old("target_role") ?? "sta" }}');
        @endif
    });
</script>
