<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - System Under Maintenance | LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        <!-- Live Server Back Online Notification (auto-shows when server is back) -->
        <div id="liveAlert" class="hidden mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-medium flex items-center justify-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>Server is back online! Redirecting now...</span>
        </div>

        <!-- Action Controls -->
        <div class="mt-7 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button type="button" id="statusBtn" onclick="checkServer()" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                <svg id="btnIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span id="btnText">Check Server Status</span>
            </button>
            <a href="/" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Reload Page
            </a>
        </div>

        <!-- Auto Check Countdown -->
        <p class="mt-4 text-xs text-gray-400">
            Auto-checking every <span id="countdown" class="font-semibold text-gray-600">10s</span> • Resume with <code class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded text-[11px] font-mono">php artisan up</code>
        </p>

        <!-- Helpdesk Footer Note -->
        <div class="mt-6 pt-5 border-t border-gray-100 text-xs text-gray-400">
            Need urgent academic help? <a href="mailto:support@vignan.ac.in" class="text-blue-600 hover:underline font-medium">support@vignan.ac.in</a>
        </div>
    </div>

    <!-- Lightweight Auto-Reload Script -->
    <script>
        let timer = 10;
        const countdownEl = document.getElementById('countdown');
        const statusBtn = document.getElementById('statusBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const liveAlert = document.getElementById('liveAlert');

        async function checkServer() {
            if (btnText) btnText.textContent = 'Checking...';
            if (btnIcon) btnIcon.classList.add('animate-spin');

            try {
                const res = await fetch('/?_ping=' + Date.now(), { method: 'GET', cache: 'no-store' });
                if (res.status === 200) {
                    if (liveAlert) liveAlert.classList.remove('hidden');
                    if (statusBtn) statusBtn.classList.replace('bg-blue-600', 'bg-emerald-600');
                    if (btnText) btnText.textContent = 'Server Online!';
                    setTimeout(() => { window.location.href = '/'; }, 1200);
                    return;
                }
            } catch (e) {
                // Still in maintenance
            }

            setTimeout(() => {
                if (btnText) btnText.textContent = 'Check Server Status';
                if (btnIcon) btnIcon.classList.remove('animate-spin');
            }, 500);
            timer = 10;
        }

        setInterval(() => {
            timer--;
            if (timer <= 0) {
                timer = 10;
                checkServer();
            }
            if (countdownEl) countdownEl.textContent = timer + 's';
        }, 1000);
    </script>
</body>
</html>
