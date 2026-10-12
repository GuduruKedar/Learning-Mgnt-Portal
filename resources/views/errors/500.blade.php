<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>500 - Server Error | LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 text-gray-800">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center sm:p-12 relative">
        <!-- 500 Icon -->
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 text-red-600 mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">500</h1>
        <h2 class="mt-2 text-xl font-semibold text-gray-800">Internal Server Error</h2>
        <p class="mt-3 text-sm text-gray-500 leading-relaxed">
            {{ isset($exception) && $exception->getMessage() ? $exception->getMessage() : 'Something unexpected happened on our server while processing your request. Our technical team can be notified directly below.' }}
        </p>

        <!-- Inline Status Notification (Initially Hidden) -->
        <div id="inlineSuccessBanner" class="mt-5 hidden bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl p-3.5 text-left flex items-start gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong class="font-semibold block text-emerald-900">Error Reported Successfully!</strong>
                <span>An alert email has been sent to the administrator (<span class="underline">web@vignan.ac.in</span>) and logged in the system activity logs.</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button type="button" id="openReportBtn" onclick="toggleReportModal(true)" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-sm font-semibold text-white transition-colors shadow-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span id="reportBtnText">Report Error</span>
            </button>

            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Go Back
            </a>

            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm">
                Return to Dashboard
            </a>
        </div>
    </div>

    <!-- Report Error Modal -->
    <div id="reportModal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-md w-full p-6 text-left relative transition-all">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Report Issue to Administrator</h3>
                </div>
                <button onclick="toggleReportModal(false)" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-3">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Clicking submit will notify administrators at <strong class="text-gray-700">web@vignan.ac.in</strong> and log the incident directly in the superadmin Activity Logs.
                </p>

                <div class="bg-gray-50 rounded-xl p-3 border border-gray-200/60 text-xs space-y-1">
                    <div class="text-gray-500"><strong class="text-gray-700">Status:</strong> HTTP 500 Internal Server Error</div>
                    <div class="text-gray-500 truncate"><strong class="text-gray-700">URL:</strong> {{ request()->fullUrl() }}</div>
                </div>

                <div>
                    <label for="reportNotes" class="block text-xs font-semibold text-gray-700 mb-1">
                        What were you doing when this happened? (Optional)
                    </label>
                    <textarea id="reportNotes" rows="3" placeholder="Briefly describe the action you performed or what page you were opening..." class="w-full text-xs rounded-xl border border-gray-200 p-3 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 text-gray-800 placeholder-gray-400 resize-none"></textarea>
                </div>

                <div id="reportStatusMessage" class="hidden text-xs rounded-xl p-3"></div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2.5">
                <button type="button" onclick="toggleReportModal(false)" id="cancelReportBtn" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitErrorReport()" id="submitReportBtn" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm disabled:opacity-50">
                    <span id="submitReportText">Send Report</span>
                    <svg id="submitReportSpinner" class="animate-spin w-3.5 h-3.5 hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleReportModal(show) {
            const modal = document.getElementById('reportModal');
            if (show) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }

        let isSubmitting = false;

        async function submitErrorReport() {
            if (isSubmitting) return;

            const submitBtn = document.getElementById('submitReportBtn');
            const submitText = document.getElementById('submitReportText');
            const spinner = document.getElementById('submitReportSpinner');
            const cancelBtn = document.getElementById('cancelReportBtn');
            const statusMsg = document.getElementById('reportStatusMessage');
            const notes = document.getElementById('reportNotes').value;

            isSubmitting = true;
            submitBtn.disabled = true;
            cancelBtn.disabled = true;
            submitText.innerText = 'Sending...';
            spinner.classList.remove('hidden');
            statusMsg.classList.add('hidden');

            try {
                const response = await fetch("{{ route('error.report') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status_code: 500,
                        error_message: "{{ isset($exception) && $exception->getMessage() ? addslashes($exception->getMessage()) : '500 Internal Server Error' }}",
                        url: window.location.href,
                        notes: notes
                    })
                });

                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    // Update modal state briefly
                    statusMsg.className = 'text-xs rounded-xl p-3 bg-emerald-50 text-emerald-800 border border-emerald-200 block';
                    statusMsg.innerText = '✓ Error reported successfully! Admin notified.';
                    
                    setTimeout(() => {
                        toggleReportModal(false);

                        // Show inline success banner on main page
                        const inlineBanner = document.getElementById('inlineSuccessBanner');
                        if (inlineBanner) inlineBanner.classList.remove('hidden');

                        // Disable the main button and show as reported
                        const openReportBtn = document.getElementById('openReportBtn');
                        const reportBtnText = document.getElementById('reportBtnText');
                        if (openReportBtn && reportBtnText) {
                            openReportBtn.className = 'w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-emerald-600 text-sm font-semibold text-white cursor-default shadow-sm gap-2';
                            openReportBtn.disabled = true;
                            reportBtnText.innerText = '✓ Error Reported';
                        }
                    }, 700);
                } else {
                    throw new Error(data.message || 'Failed to submit report');
                }
            } catch (err) {
                statusMsg.className = 'text-xs rounded-xl p-3 bg-red-50 text-red-800 border border-red-200 block';
                statusMsg.innerText = 'Could not submit report at this time. Please try again or contact web@vignan.ac.in directly.';
                submitBtn.disabled = false;
                cancelBtn.disabled = false;
                submitText.innerText = 'Send Report';
                spinner.classList.add('hidden');
                isSubmitting = false;
            }
        }
    </script>
</body>
</html>
