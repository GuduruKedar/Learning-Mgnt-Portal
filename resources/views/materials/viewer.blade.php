<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cleanTitle }} - LMS Document Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- JSZip & docx-preview for in-browser Word rendering -->
    <script src="https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview@0.1.15/dist/docx-preview.min.js"></script>

    <!-- SheetJS for Excel spreadsheets rendering -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .docx-wrapper {
            background-color: transparent !important;
            padding: 0 !important;
        }
        .docx-wrapper > section.docx {
            background: white !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            margin: 24px auto !important;
            padding: 48px !important;
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
            max-width: 860px !important;
            min-height: 1000px !important;
            box-sizing: border-box !important;
        }
        @media (max-width: 768px) {
            .docx-wrapper > section.docx {
                padding: 24px !important;
                margin: 12px auto !important;
            }
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-full bg-slate-100 flex flex-col overflow-hidden text-slate-800">

    <!-- Top Navigation & Control Bar -->
    <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-xs z-30">
        <!-- Left: Document Info -->
        <div class="flex items-center gap-3 min-w-0">
            <button type="button" onclick="window.close()" class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors shrink-0" title="Close Document Viewer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="h-6 w-px bg-slate-200 shrink-0"></div>

            <div class="flex items-center gap-2.5 min-w-0">
                <!-- Icon based on format -->
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 
                    {{ in_array($ext, ['doc', 'docx']) ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                    {{ $ext === 'pdf' ? 'bg-rose-50 text-rose-600 border border-rose-100' : '' }}
                    {{ in_array($ext, ['xls', 'xlsx', 'csv']) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                    {{ in_array($ext, ['ppt', 'pptx', 'pps']) ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                    {{ in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif']) ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                    @if(in_array($ext, ['doc', 'docx']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @elseif($ext === 'pdf')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    @elseif(in_array($ext, ['xls', 'xlsx', 'csv']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    @elseif(in_array($ext, ['ppt', 'pptx', 'pps']))
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    @endif
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-sm font-bold text-slate-900 truncate" title="{{ $cleanTitle }}">{{ $cleanTitle }}</h1>
                        <span class="hidden sm:inline-flex px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200 shrink-0">
                            {{ strtoupper($ext) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500 truncate mt-0.5">
                        @if($course)
                            <span class="font-semibold text-indigo-600">{{ $course->code }}</span>
                            <span>&bull;</span>
                            <span class="truncate">{{ $course->name }}</span>
                            <span>&bull;</span>
                        @endif
                        <span>{{ $fileSizeFormatted }}</span>
                        @if($material->staff && $material->staff->profile)
                            <span>&bull;</span>
                            <span>Added by {{ $material->staff->profile->first_name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Actions & Tools -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <!-- Zoom Controls (for Docx, Text, Images) -->
            <div id="zoomControls" class="hidden sm:flex items-center bg-slate-100 rounded-xl p-0.5 border border-slate-200">
                <button type="button" onclick="adjustZoom(-0.1)" class="p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-white transition-colors" title="Zoom Out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </button>
                <span id="zoomLevelText" class="px-2 text-xs font-semibold text-slate-700 select-none">100%</span>
                <button type="button" onclick="adjustZoom(0.1)" class="p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-white transition-colors" title="Zoom In">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
                <button type="button" onclick="resetZoom()" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-white transition-colors text-xs font-bold" title="Reset Zoom">
                    ↺
                </button>
            </div>

            <!-- Print Button (if applicable) -->
            <button type="button" onclick="window.print()" class="hidden md:inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print</span>
            </button>

            <!-- Download Original File Button -->
            <a href="{{ $downloadUrl }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Download</span>
            </a>
        </div>
    </header>

    <!-- Main Document Rendering Canvas -->
    <main class="flex-1 overflow-y-auto relative flex flex-col bg-slate-100" id="viewerMainArea">
        
        <!-- Loading State Spinner -->
        <div id="documentLoadingSpinner" class="absolute inset-0 bg-slate-100 flex flex-col items-center justify-center z-20 transition-opacity duration-300">
            <div class="w-12 h-12 rounded-2xl bg-white shadow-md flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-indigo-600 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p class="text-sm font-bold text-slate-700" id="loadingStatusText">Rendering Document Preview...</p>
            <p class="text-xs text-slate-400 mt-1">Please wait while the file is prepared in your browser</p>
        </div>

        <!-- 1. WORD DOCUMENT VIEWER (.docx / .doc) -->
        @if(in_array($ext, ['docx', 'doc']))
            <div class="w-full flex-1 p-4 sm:p-6 overflow-auto" id="docxScrollWrapper">
                <div id="docx-container" class="transition-transform origin-top"></div>
            </div>

        <!-- 2. PDF VIEWER (.pdf) -->
        @elseif($ext === 'pdf')
            <div class="w-full h-full flex-1">
                <iframe src="{{ $rawUrl }}#toolbar=1" class="w-full h-full border-0" id="pdfIframe"></iframe>
            </div>

        <!-- 3. EXCEL SPREADSHEET VIEWER (.xlsx / .xls / .csv) -->
        @elseif(in_array($ext, ['xlsx', 'xls', 'csv']))
            <div class="w-full flex-1 flex flex-col p-4 sm:p-6 overflow-hidden">
                <!-- Sheet Tabs Container -->
                <div id="sheetTabsContainer" class="flex items-center gap-1.5 pb-3 overflow-x-auto shrink-0"></div>
                <!-- Sheet Content Table -->
                <div class="flex-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-auto p-4" id="sheetContentContainer">
                    <div id="excel-table-container" class="text-xs text-slate-800"></div>
                </div>
            </div>

        <!-- 4. POWERPOINT VIEWER (.pptx / .ppt) -->
        @elseif(in_array($ext, ['pptx', 'ppt', 'pps', 'ppsx']))
            <div class="w-full flex-1 flex flex-col items-center justify-center p-6 text-center">
                <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-8 max-w-lg w-full">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $cleanTitle }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-mono uppercase">{{ $fileName }} ({{ $fileSizeFormatted }})</p>
                    
                    <div class="mt-6 space-y-3">
                        <a href="{{ $downloadUrl }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition-all shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Download Presentation File</span>
                        </a>
                    </div>
                </div>
            </div>

        <!-- 5. IMAGE VIEWER (.png / .jpg / .jpeg / .webp / .svg / .gif) -->
        @elseif(in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif']))
            <div class="w-full flex-1 flex items-center justify-center p-6 overflow-auto" id="imageScrollWrapper">
                <img src="{{ $rawUrl }}" alt="{{ $cleanTitle }}" id="previewImage" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-lg border border-slate-200 transition-transform origin-center">
            </div>

        <!-- 6. TEXT / CODE VIEWER (.txt, .log, .json, etc.) -->
        @else
            <div class="w-full flex-1 p-4 sm:p-6 overflow-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 max-w-4xl mx-auto p-6">
                    <pre id="rawTextContainer" class="font-mono text-xs text-slate-800 leading-relaxed whitespace-pre-wrap overflow-x-auto"></pre>
                </div>
            </div>
        @endif

        <!-- Error Fallback Banner -->
        <div id="viewerErrorMessage" class="hidden absolute inset-x-4 top-4 sm:inset-x-auto sm:right-6 sm:max-w-md bg-rose-50 border border-rose-200 p-4 rounded-2xl shadow-lg z-30 flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="flex-1 text-xs">
                <h4 class="font-bold text-rose-900" id="errorTitle">Preview Error</h4>
                <p class="text-rose-700 mt-0.5" id="errorDetail">Could not parse document. You can download the file directly.</p>
            </div>
            <button type="button" onclick="document.getElementById('viewerErrorMessage').classList.add('hidden')" class="text-rose-500 hover:text-rose-700 text-sm">&times;</button>
        </div>

    </main>

    <script>
        const fileUrl = @json($rawUrl);
        const fileExt = @json($ext);
        let currentZoom = 1.0;

        function hideSpinner() {
            const spinner = document.getElementById('documentLoadingSpinner');
            if (spinner) {
                spinner.style.opacity = '0';
                setTimeout(() => spinner.classList.add('hidden'), 300);
            }
        }

        function showError(title, detail) {
            hideSpinner();
            const errBox = document.getElementById('viewerErrorMessage');
            if (errBox) {
                document.getElementById('errorTitle').textContent = title;
                document.getElementById('errorDetail').textContent = detail;
                errBox.classList.remove('hidden');
            }
        }

        function adjustZoom(delta) {
            currentZoom = Math.min(Math.max(0.5, currentZoom + delta), 2.0);
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 1.0;
            applyZoom();
        }

        function applyZoom() {
            const zoomText = document.getElementById('zoomLevelText');
            if (zoomText) zoomText.textContent = Math.round(currentZoom * 100) + '%';

            const docxTarget = document.getElementById('docx-container');
            if (docxTarget) docxTarget.style.transform = `scale(${currentZoom})`;

            const imgTarget = document.getElementById('previewImage');
            if (imgTarget) imgTarget.style.transform = `scale(${currentZoom})`;

            const textTarget = document.getElementById('rawTextContainer');
            if (textTarget) textTarget.style.transform = `scale(${currentZoom})`;
        }

        // Document Initialization based on Extension
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                if (fileExt === 'docx') {
                    const response = await fetch(fileUrl);
                    if (!response.ok) throw new Error('Network error: ' + response.statusText);
                    const blob = await response.blob();
                    const container = document.getElementById('docx-container');
                    
                    if (window.docx && window.docx.renderAsync) {
                        await window.docx.renderAsync(blob, container, null, {
                            inWrapper: true,
                            ignoreWidth: false,
                            ignoreHeight: false,
                            className: 'docx-page'
                        });
                        hideSpinner();
                    } else {
                        throw new Error('docx-preview library not loaded');
                    }
                } 
                else if (fileExt === 'doc') {
                    // Legacy .doc format notification
                    hideSpinner();
                    const container = document.getElementById('docx-container');
                    if (container) {
                        container.innerHTML = `
                            <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-8 max-w-lg mx-auto my-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900">Legacy Word Document (.doc)</h3>
                                <p class="text-xs text-slate-500 mt-1">This is an older binary Word format. Please download it or open in Microsoft Word.</p>
                                <a href="{{ $downloadUrl }}" class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                                    Download .doc File
                                </a>
                            </div>
                        `;
                    }
                }
                else if (fileExt === 'pdf') {
                    const iframe = document.getElementById('pdfIframe');
                    if (iframe) {
                        iframe.onload = () => hideSpinner();
                        setTimeout(hideSpinner, 1000);
                    }
                }
                else if (['xlsx', 'xls', 'csv'].includes(fileExt)) {
                    const response = await fetch(fileUrl);
                    const arrayBuffer = await response.arrayBuffer();
                    const workbook = XLSX.read(arrayBuffer, { type: 'array' });
                    
                    const tabsContainer = document.getElementById('sheetTabsContainer');
                    const tableContainer = document.getElementById('excel-table-container');

                    if (workbook.SheetNames.length > 0) {
                        workbook.SheetNames.forEach((sheetName, index) => {
                            const btn = document.createElement('button');
                            btn.className = `px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all ${index === 0 ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'}`;
                            btn.textContent = sheetName;
                            btn.onclick = () => renderSheet(workbook, sheetName, btn);
                            tabsContainer.appendChild(btn);
                        });

                        renderSheet(workbook, workbook.SheetNames[0]);
                    }
                    hideSpinner();
                }
                else if (['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'].includes(fileExt)) {
                    const img = document.getElementById('previewImage');
                    if (img) {
                        if (img.complete) hideSpinner();
                        else {
                            img.onload = () => hideSpinner();
                            img.onerror = () => showError('Image Error', 'Failed to render image.');
                        }
                    }
                }
                else {
                    // Text or other code file
                    const response = await fetch(fileUrl);
                    const text = await response.text();
                    const textContainer = document.getElementById('rawTextContainer');
                    if (textContainer) {
                        textContainer.textContent = text;
                    }
                    hideSpinner();
                }
            } catch (err) {
                console.error('Document Viewer Error:', err);
                showError('Preview Notice', 'Unable to render inline preview: ' + err.message);
            }
        });

        function renderSheet(workbook, sheetName, activeBtn) {
            const tableContainer = document.getElementById('excel-table-container');
            const sheet = workbook.Sheets[sheetName];
            const html = XLSX.utils.sheet_to_html(sheet, {
                header: '<table class="w-full border-collapse border border-slate-200 text-left">',
                footer: '</table>'
            });

            // Style rendered HTML table with Tailwind classes
            tableContainer.innerHTML = html;
            const table = tableContainer.querySelector('table');
            if (table) {
                table.classList.add('w-full', 'border-collapse', 'text-xs');
                table.querySelectorAll('td, th').forEach(cell => {
                    cell.classList.add('border', 'border-slate-200', 'px-3', 'py-2');
                });
                table.querySelectorAll('tr:first-child td, tr:first-child th').forEach(cell => {
                    cell.classList.add('bg-slate-100', 'font-bold', 'text-slate-800');
                });
            }

            if (activeBtn) {
                const tabs = document.querySelectorAll('#sheetTabsContainer button');
                tabs.forEach(t => t.className = 'px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50');
                activeBtn.className = 'px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all bg-indigo-600 text-white shadow-xs';
            }
        }
    </script>
</body>
</html>
