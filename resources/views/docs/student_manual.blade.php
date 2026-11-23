<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal User Manual - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
        }

        /* Typography styling for rendered markdown content */
        .markdown-body h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .markdown-body h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.35rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .markdown-body h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #334155;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .markdown-body h4 {
            font-size: 1.05rem;
            font-weight: 600;
            color: #475569;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .markdown-body p {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
            line-height: 1.75;
            color: #334155;
            font-size: 0.95rem;
        }
        .markdown-body ul, .markdown-body ol {
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
        }
        .markdown-body ul {
            list-style-type: disc;
        }
        .markdown-body ol {
            list-style-type: decimal;
        }
        .markdown-body li {
            margin-top: 0.35rem;
            margin-bottom: 0.35rem;
            line-height: 1.65;
            color: #334155;
            font-size: 0.95rem;
        }
        .markdown-body pre {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 1rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin-top: 1rem;
            margin-bottom: 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            line-height: 1.6;
        }
        .markdown-body code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85em;
            background-color: #f1f5f9;
            color: #4338ca;
            padding: 0.2rem 0.4rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
        }
        .markdown-body pre code {
            background-color: transparent;
            color: inherit;
            padding: 0;
            border: none;
        }
        .markdown-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
        .markdown-body th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
        }
        .markdown-body td {
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            color: #334155;
        }
        .markdown-body tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .markdown-body blockquote {
            border-left: 4px solid #6366f1;
            background-color: #eef2ff;
            padding: 0.75rem 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-top: 1rem;
            margin-bottom: 1rem;
            color: #3730a3;
        }
        .markdown-body hr {
            border: 0;
            height: 1px;
            background: #e2e8f0;
            margin: 2rem 0;
        }
        .markdown-body a {
            color: #4f46e5;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .markdown-body a:hover {
            color: #3730a3;
        }

        /* Print-specific layout optimizations */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
                color: #000000;
            }
            .printable-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .markdown-body pre {
                background-color: #f8fafc !important;
                color: #0f172a !important;
                border: 1px solid #cbd5e1 !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">

    <!-- Sticky Floating Action Toolbar -->
    <header class="no-print sticky top-4 z-50 max-w-5xl mx-auto mb-6 bg-white/90 backdrop-blur-md border border-slate-200/90 rounded-2xl shadow-lg p-3 sm:p-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to LMS</span>
            </a>
            <div class="hidden sm:block">
                <h1 class="text-sm font-extrabold text-slate-900 leading-tight">Student Portal User Manual</h1>
                <p class="text-[11px] text-slate-500">Official Step-by-Step Practical Guide</p>
            </div>
        </div>

        <!-- Download & Print Action Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            <!-- 1. Direct Download Compiled PDF -->
            <a href="{{ route('student.manual.download_pdf') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/20 active:scale-95 transition-all cursor-pointer"
               title="Directly download official compiled PDF manual">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Download PDF Manual</span>
            </a>

            <!-- 2. Print / Save as PDF Browser Dialog -->
            <button type="button" 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition-all cursor-pointer"
                    title="Open browser print dialog">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Print / Custom PDF</span>
            </button>

            <!-- 3. Direct Download Markdown (.md) -->
            <a href="{{ route('student.manual.download') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-2xs transition-all"
               title="Download raw Markdown file">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Markdown (.MD)</span>
            </a>

            <!-- 4. Direct Download Standalone HTML -->
            <a href="{{ route('student.manual.download_html') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-2xs transition-all"
               title="Download standalone HTML document">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Offline HTML</span>
            </a>
        </div>
    </header>

    <!-- Main Manual Container -->
    <main class="max-w-5xl mx-auto bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 lg:p-12 printable-card">
        
        <!-- University Header for Print & Official Record -->
        <div class="border-b-2 border-indigo-600 pb-5 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-2">
                    Official Student Technical Manual
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Learning Management System (LMS)</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Student Portal Step-by-Step Operational Manual & Practical Guide</p>
            </div>
            <div class="text-left sm:text-right font-mono text-xs text-slate-400">
                <div>Document Version: 2.4</div>
                <div>Academic Year: 2026–2027</div>
                <div class="text-indigo-600 font-bold mt-1">Approved for Distribution</div>
            </div>
        </div>

        <!-- Rendered Markdown Body -->
        <article class="markdown-body">
            {!! $htmlContent !!}
        </article>

        <!-- Document Footer -->
        <footer class="mt-12 pt-6 border-t border-slate-200 text-center text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} Learning Management System. Technology Development (TD) Directorate. All rights reserved.</p>
            <p class="mt-1">Designed for academic guides, faculty mentors, and university student cohorts.</p>
        </footer>

    </main>

</body>
</html>

