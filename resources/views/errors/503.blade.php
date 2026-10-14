<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - System Under Maintenance | LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 text-gray-800 font-['Inter',sans-serif]">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center sm:p-12">
        
        <!-- Icon Badge -->
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 text-amber-600 mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

        <!-- Headings -->
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">503</h1>
        <h2 class="mt-2 text-xl font-semibold text-gray-800">System Under Maintenance</h2>
        
        <!-- Maintenance Status Pill -->
        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            <span>Scheduled Routine Maintenance</span>
        </div>

        <!-- Description -->
        <p class="mt-4 text-sm text-gray-500 leading-relaxed">
            The Learning Management Portal is temporarily offline for essential system maintenance and optimization. All courses, records, and student submissions remain completely safe.
        </p>

        <!-- Action Controls -->
        <div class="mt-8 flex items-center justify-center">
            <button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                Reload Page
            </button>
        </div>

        <!-- Helpdesk Footer Note -->
        <div class="mt-6 pt-5 border-t border-gray-100 text-xs text-gray-400">
            Need urgent academic help? <a href="mailto:web@vignan.ac.in" class="text-blue-600 hover:underline font-medium">web@vignan.ac.in</a>
        </div>
    </div>
</body>
</html>
