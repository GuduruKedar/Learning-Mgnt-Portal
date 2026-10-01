<!-- Bulk Upload Modal -->
<div id="bulkUploadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden transform transition-all border border-slate-100 flex flex-col max-h-[92vh]">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 via-indigo-50/40 to-blue-50/40 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-200 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900" id="bulkUploadModalTitle">Bulk Data Ingestion</h3>
                    <p class="text-xs text-slate-500 font-medium" id="bulkUploadModalSubtitle">Fast, automated multi-record verification</p>
                </div>
            </div>
            <button type="button" onclick="closeBulkUploadModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 relative overflow-y-auto space-y-5">
            <div id="bulkUploadFormContent" class="space-y-5">
                
                <!-- Template Download Card -->
                <div class="bg-gradient-to-r from-emerald-50/70 to-teal-50/40 border border-emerald-200/80 rounded-xl p-3.5 flex items-center justify-between gap-3 shadow-sm hover:border-emerald-300 transition-all">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M11.5 15.5l-1.5-2.5-1.5 2.5H7l2.25-3.5L7 8.5h1.5l1.5 2.5 1.5-2.5H13l-2.25 3.5L13 15.5h-1.5z"/></svg>
                        </div>
                        <div class="truncate">
                            <h4 class="text-xs font-bold text-slate-900">Official Excel Template</h4>
                            <p class="text-[11px] text-slate-500">Download formatted columns for error-free import</p>
                        </div>
                    </div>
                    <a href="{{ route('bulk-upload.template') }}" id="templateDownloadLink" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow transition-all shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download</span>
                    </a>
                </div>

                <form action="{{ route('bulk-upload.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleBulkUploadSubmit(event)" id="bulkUploadForm">
                    @csrf
                    <input type="hidden" name="target_role" id="bulkUploadRole" value="">
                    <input type="hidden" name="file_base64" id="bulkUploadFileBase64" value="">
                    <input type="hidden" name="file_name" id="bulkUploadFileName" value="">
                    
                    <!-- Drag & Drop Upload Container -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Select Spreadsheet File</label>
                        
                        <!-- Empty Dropzone State -->
                        <div id="dropZoneContainer" onclick="document.getElementById('bulkUploadFileInput').click()" 
                            ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleFileDrop(event)"
                            class="relative border-2 border-dashed border-indigo-200 hover:border-indigo-500 bg-indigo-50/20 hover:bg-indigo-50/50 rounded-2xl p-6 text-center cursor-pointer transition-all group">
                            
                            <input type="file" name="file" id="bulkUploadFileInput" onchange="handleBulkFileSelect(this)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="hidden" required>
                            
                            <div class="flex flex-col items-center justify-center space-y-2.5 pointer-events-none">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800"><span class="text-indigo-600 underline">Click to upload</span> or drag and drop</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Supports Microsoft Excel (.xlsx, .xls) and CSV (.csv)</p>
                                </div>
                                <div class="flex items-center gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase">.XLSX</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase">.XLS</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase">.CSV</span>
                                    <span class="text-[10px] text-slate-400 font-medium pl-1">Max 256MB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Selected File State -->
                        <div id="selectedFileContainer" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-inner">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M11.5 15.5l-1.5-2.5-1.5 2.5H7l2.25-3.5L7 8.5h1.5l1.5 2.5 1.5-2.5H13l-2.25 3.5L13 15.5h-1.5z"/></svg>
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-extrabold text-slate-900 truncate" id="selectedFileNameDisplay">filename.xlsx</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[11px] text-slate-500 font-medium" id="selectedFileSizeDisplay">0 KB</span>
                                        <span class="inline-flex items-center px-1.5 py-0.2 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded">Ready</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('bulkUploadFileInput').click()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-white border border-slate-200 hover:border-indigo-300 px-3 py-1.5 rounded-lg shadow-sm transition-all shrink-0">
                                Change
                            </button>
                        </div>

                        @error('file')
                            <p class="text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('target_role')
                            <p class="text-red-500 text-xs mt-1 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    <!-- Modal Action Buttons -->
                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" onclick="closeBulkUploadModal()" class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:border-slate-400 focus:outline-none transition-all shadow-sm">
                            Cancel
                        </button>
                        <button type="submit" id="bulkUploadSubmitBtn" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md hover:shadow-lg focus:outline-none transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            <span>Upload & Process</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Progress Bar Overlay -->
            <div id="bulkUploadProgress" class="hidden absolute inset-0 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center z-20 p-6 rounded-2xl">
                <div class="w-full max-w-xs text-center space-y-4">
                    <div class="relative w-16 h-16 mx-auto">
                        <div class="absolute inset-0 rounded-full border-4 border-indigo-100 border-t-indigo-600 animate-spin"></div>
                        <div class="absolute inset-2 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900" id="progressText">Processing Upload...</p>
                        <p class="text-xs text-slate-500 mt-0.5">Please wait while the system validates each row.</p>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200">
                        <div id="progressBarFill" class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                    </div>
                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[11px] font-semibold">Do not refresh or close</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Errors Modal -->
@if(session('import_errors'))
<div id="importErrorsModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all border border-red-100 flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-red-100 flex justify-between items-center bg-gradient-to-r from-red-50 to-orange-50 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-red-950">Bulk Upload Validation Issues</h3>
                    <p class="text-xs text-red-700 font-medium">Detailed row-by-row diagnostic report</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('importErrorsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-white/60 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto space-y-4">
            
            @if(session('import_summary'))
            <div class="grid grid-cols-3 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Total Processed</span>
                    <span class="text-xl font-black text-gray-900 mt-0.5 block">{{ session('import_summary')['total'] ?? 0 }}</span>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-center">
                    <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider block">Imported Successfully</span>
                    <span class="text-xl font-black text-emerald-700 mt-0.5 block">{{ session('import_summary')['success'] ?? 0 }}</span>
                </div>
                <div class="p-3 bg-red-50 rounded-xl border border-red-200 text-center">
                    <span class="text-xs font-semibold text-red-700 uppercase tracking-wider block">Failed Rows</span>
                    <span class="text-xl font-black text-red-700 mt-0.5 block">{{ session('import_summary')['failed'] ?? 0 }}</span>
                </div>
            </div>
            @endif

            <p class="text-xs text-gray-600 leading-relaxed">
                Some rows in your file did not pass verification. You can download the <strong>Excel Error Spreadsheet (.xlsx)</strong> to inspect and fix the exact row numbers, register numbers/IDs, and error descriptions.
            </p>
            
            @if(isset(session('import_errors')['__truncated']))
            <div class="bg-amber-50 border border-amber-300 text-amber-900 rounded-xl p-3 text-xs font-semibold flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('import_errors')['__truncated'] }}</span>
            </div>
            @endif

            <!-- Errors Scroll Area -->
            <div class="bg-slate-900 text-slate-100 rounded-xl p-4 max-h-60 overflow-y-auto font-mono text-xs space-y-1.5 shadow-inner">
                @foreach(session('import_errors') as $key => $error)
                    @if($key !== '__truncated')
                        <div class="flex items-start gap-2 border-b border-slate-800 pb-1.5 last:border-0 last:pb-0">
                            <span class="text-red-400 font-bold shrink-0">&bull;</span>
                            <span class="text-slate-200 leading-relaxed">{{ $error }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-2">
                <!-- Download Excel Error Spreadsheet (.xlsx) -->
                @if(session('import_summary.history_id'))
                    <a href="{{ route('bulk-upload.errors.excel', session('import_summary.history_id')) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M11.5 15.5l-1.5-2.5-1.5 2.5H7l2.25-3.5L7 8.5h1.5l1.5 2.5 1.5-2.5H13l-2.25 3.5L13 15.5h-1.5z"/></svg>
                        <span>Download Excel Errors (.xlsx)</span>
                    </a>
                @else
                    <button type="button" onclick="downloadErrorsAsExcel()" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm hover:shadow transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/><path d="M11.5 15.5l-1.5-2.5-1.5 2.5H7l2.25-3.5L7 8.5h1.5l1.5 2.5 1.5-2.5H13l-2.25 3.5L13 15.5h-1.5z"/></svg>
                        <span>Download Excel Errors (.xlsx)</span>
                    </button>
                @endif
            </div>

            <button type="button" onclick="document.getElementById('importErrorsModal').classList.add('hidden')" class="px-5 py-2.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 rounded-xl transition-colors">
                Close
            </button>
        </div>

    </div>
</div>
@endif

<script>
    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function handleBulkFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('bulkUploadFileName').value = file.name;
            
            // Update UI
            document.getElementById('selectedFileNameDisplay').innerText = file.name;
            document.getElementById('selectedFileSizeDisplay').innerText = formatFileSize(file.size);
            document.getElementById('dropZoneContainer').classList.add('hidden');
            document.getElementById('selectedFileContainer').classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('bulkUploadFileBase64').value = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropZoneContainer').classList.add('border-indigo-600', 'bg-indigo-50/70');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropZoneContainer').classList.remove('border-indigo-600', 'bg-indigo-50/70');
    }

    function handleFileDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropZoneContainer').classList.remove('border-indigo-600', 'bg-indigo-50/70');
        
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
            const fileInput = document.getElementById('bulkUploadFileInput');
            fileInput.files = e.dataTransfer.files;
            handleBulkFileSelect(fileInput);
        }
    }

    function openBulkUploadModal(role) {
        document.getElementById('bulkUploadRole').value = role;
        document.getElementById('bulkUploadModal').classList.remove('hidden');
        
        const titleEl = document.getElementById('bulkUploadModalTitle');
        const subtitleEl = document.getElementById('bulkUploadModalSubtitle');
        
        if (role === 'stu') {
            titleEl.innerText = 'Bulk Upload Students';
            subtitleEl.innerText = 'Import student admissions & enrollments';
        } else if (role === 'sta') {
            titleEl.innerText = 'Bulk Upload Faculty & Staff';
            subtitleEl.innerText = 'Import professors & academic staff records';
        } else if (role === 'admin') {
            titleEl.innerText = 'Bulk Upload Coordinators';
            subtitleEl.innerText = 'Import department coordinators & admins';
        } else {
            titleEl.innerText = 'Bulk Data Ingestion';
            subtitleEl.innerText = 'Fast, automated multi-record verification';
        }

        const templateLink = document.getElementById('templateDownloadLink');
        if (templateLink) {
            templateLink.href = "{{ route('bulk-upload.template') }}?role=" + role;
        }

        // Reset state
        document.getElementById('bulkUploadFileInput').value = '';
        document.getElementById('bulkUploadFileName').value = '';
        document.getElementById('bulkUploadFileBase64').value = '';
        document.getElementById('dropZoneContainer').classList.remove('hidden');
        document.getElementById('selectedFileContainer').classList.add('hidden');
        document.getElementById('bulkUploadFormContent').style.opacity = '1';
        document.getElementById('bulkUploadProgress').classList.add('hidden');
        document.getElementById('progressBarFill').style.width = '0%';
    }

    function closeBulkUploadModal() {
        document.getElementById('bulkUploadModal').classList.add('hidden');
    }

    function handleBulkUploadSubmit(e) {
        const fileInput = document.getElementById('bulkUploadFileInput');
        const base64Input = document.getElementById('bulkUploadFileBase64');
        
        if (fileInput && fileInput.files && fileInput.files[0] && !base64Input.value) {
            e.preventDefault();
            const file = fileInput.files[0];
            document.getElementById('bulkUploadFileName').value = file.name;
            const reader = new FileReader();
            reader.onload = function(evt) {
                base64Input.value = evt.target.result;
                document.getElementById('bulkUploadForm').submit();
            };
            reader.readAsDataURL(file);
            showBulkProgress();
            return false;
        }

        showBulkProgress();
    }

    function showBulkProgress() {
        document.getElementById('bulkUploadProgress').classList.remove('hidden');
        document.getElementById('bulkUploadFormContent').style.opacity = '0.3';
        
        let progress = 0;
        const bar = document.getElementById('progressBarFill');
        const text = document.getElementById('progressText');
        
        const interval = setInterval(() => {
            if (progress < 92) {
                progress += Math.random() * 6;
                if (progress > 92) progress = 92;
                bar.style.width = progress + '%';
                
                if (progress > 15 && progress < 45) text.innerText = 'Validating records...';
                if (progress >= 45 && progress < 75) text.innerText = 'Importing records into database...';
                if (progress >= 75) text.innerText = 'Finalizing diagnostics & summary...';
            }
        }, 400);
    }

    function downloadErrorsAsExcel() {
        const rawErrors = {!! json_encode(session('import_errors') ?? []) !!};
        
        let csvRows = [];
        // Header
        csvRows.push(['Row #', 'Register Number / Employee ID', 'Validation Error Details'].map(c => `"${c.replace(/"/g, '""')}"`).join(','));
        
        let idx = 1;
        for (const [key, errText] of Object.entries(rawErrors)) {
            if (key !== '__truncated') {
                let rowNum = idx;
                let idVal = 'N/A';
                let errMsg = errText;
                
                const matchWithLabel = errText.match(/^Row\s+(\d+)\s*\(([^:]+):\s*([^)]+)\):\s*(.+)$/i);
                const matchSimple = errText.match(/^Row\s+(\d+)\s*\(([^)]+)\):\s*(.+)$/i);
                
                if (matchWithLabel) {
                    rowNum = matchWithLabel[1];
                    idVal = matchWithLabel[3].trim();
                    errMsg = matchWithLabel[4].trim();
                } else if (matchSimple) {
                    rowNum = matchSimple[1];
                    idVal = matchSimple[2].trim();
                    errMsg = matchSimple[3].trim();
                }
                
                csvRows.push([rowNum, idVal, errMsg].map(c => `"${String(c).replace(/"/g, '""')}"`).join(','));
                idx++;
            }
        }
        
        const csvContent = '\ufeff' + csvRows.join('\r\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Bulk_Upload_Errors_${new Date().toISOString().slice(0,10)}.csv`;
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
