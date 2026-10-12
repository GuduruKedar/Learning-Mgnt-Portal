<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Under Maintenance | Learning Management Portal</title>
    <meta name="description" content="Learning Management Portal is currently undergoing scheduled system maintenance and updates.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .ambient-glow-1 {
            position: absolute;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, rgba(99, 102, 241, 0) 70%);
            top: -120px;
            left: -120px;
            filter: blur(40px);
            pointer-events: none;
            z-index: 0;
        }
        .ambient-glow-2 {
            position: absolute;
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.16) 0%, rgba(245, 158, 11, 0) 70%);
            bottom: -140px;
            right: -140px;
            filter: blur(50px);
            pointer-events: none;
            z-index: 0;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .circuit-bg {
            background-color: #0b0f19;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
                radial-gradient(at 90% 90%, rgba(245, 158, 11, 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.4) 0px, transparent 80%),
                radial-gradient(#1e293b 0.75px, transparent 0.75px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 24px 24px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #ffffff 20%, #cbd5e1 60%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .text-gradient-amber {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="circuit-bg min-h-screen text-slate-100 flex flex-col justify-between relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Ambient Glow Orbs -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Top Branding Navbar -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-900/80 border border-slate-700/80 p-2 shadow-lg flex items-center justify-center backdrop-blur-md">
                <img src="/images/vignan_shield.svg" alt="Vignan Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; document.getElementById('logoFallback').style.display='block';">
                <span id="logoFallback" class="hidden font-heading font-black text-indigo-400 text-lg">V</span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-950/70 border border-indigo-800/60 px-2 py-0.5 rounded-md">
                        VFSTR
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Vignan University</span>
                </div>
                <h1 class="font-heading font-bold text-sm sm:text-base text-white tracking-tight">
                    Learning Management <span class="text-indigo-400">Portal</span>
                </h1>
            </div>
        </div>

        <!-- Live Server Status Indicator Badge -->
        <div class="flex items-center gap-2 bg-slate-900/90 border border-slate-700/80 px-3 py-1.5 rounded-full shadow-md backdrop-blur-md">
            <span class="relative flex h-2.5 w-2.5">
                <span id="pingHalo" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span id="pingDot" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
            </span>
            <span id="statusBadgeText" class="text-xs font-semibold text-slate-200">Maintenance Mode</span>
        </div>
    </header>

    <!-- Main Content Stage -->
    <main class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10 w-full flex-1 flex flex-col justify-center">
        
        <!-- Online Celebration Banner (Hidden by Default until server is up) -->
        <div id="serverOnlineBanner" class="hidden mb-6 p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-200 flex items-center justify-between shadow-xl backdrop-blur-md animate-bounce">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">✓</div>
                <div>
                    <h3 class="text-sm font-bold text-white">System is back online!</h3>
                    <p class="text-xs text-emerald-300">The maintenance window has ended. Redirecting you to the portal...</p>
                </div>
            </div>
            <a href="/" class="px-4 py-1.5 text-xs font-bold text-slate-950 bg-emerald-400 hover:bg-emerald-300 rounded-lg transition-colors">
                Enter Portal
            </a>
        </div>

        <div class="bg-slate-900/70 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl backdrop-blur-xl relative overflow-hidden">
            <!-- Top Card Accents -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-indigo-500 to-amber-500"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Details Section -->
                <div class="lg:col-span-7 space-y-5 text-left">
                    
                    <!-- Mode Pill -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 border border-amber-500/30 text-amber-300">
                        <svg class="w-3.5 h-3.5 text-amber-400 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Scheduled Routine Maintenance</span>
                    </div>

                    <!-- Main Headline -->
                    <div>
                        <h2 class="font-heading text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                            We're Upgrading the <br class="hidden sm:inline">
                            <span class="text-gradient-amber">Learning Experience</span>
                        </h2>
                        <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed">
                            The LMS server is temporarily in maintenance mode for performance tuning, database optimization, and system upgrades.
                        </p>
                    </div>

                    <!-- Safe Data Guarantee Banner -->
                    <div class="flex items-start gap-3 bg-slate-800/60 border border-slate-700/60 rounded-xl p-3.5">
                        <div class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-xs text-slate-300 leading-normal">
                            <strong class="text-white font-semibold">Your Data is Safe:</strong> All coursework, uploaded assignments, grading submissions, and attendance logs are securely preserved and will be available as soon as maintenance finishes.
                        </p>
                    </div>

                    <!-- Action Controls -->
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <button type="button" id="checkStatusBtn" onclick="checkServerStatus(true)" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-semibold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                            <svg id="refreshIcon" class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span id="checkStatusText">Check Server Status</span>
                        </button>

                        <div class="flex items-center justify-center sm:justify-start gap-2 text-xs text-slate-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            <span>Auto-checking in <strong id="countdownText" class="text-amber-400">15s</strong></span>
                        </div>
                    </div>

                </div>

                <!-- Right Diagnostic Progress Card -->
                <div class="lg:col-span-5 bg-slate-950/70 border border-slate-800/90 rounded-2xl p-5 space-y-4 shadow-inner">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Maintenance Tasks</span>
                        <span class="text-[11px] font-semibold text-indigo-400 bg-indigo-950/80 px-2 py-0.5 rounded border border-indigo-900">
                            v2.4 Patch
                        </span>
                    </div>

                    <!-- Checklist -->
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                                <span class="text-slate-300 font-medium">Database Optimization</span>
                            </div>
                            <span class="text-[10px] font-semibold text-emerald-400">Done</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                                <span class="text-slate-300 font-medium">Bulk Upload Pipeline</span>
                            </div>
                            <span class="text-[10px] font-semibold text-emerald-400">Done</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30">
                            <div class="flex items-center gap-2.5">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                                </span>
                                <span class="text-amber-200 font-semibold">Cache & Asset Syncing</span>
                            </div>
                            <span class="text-[10px] font-semibold text-amber-400 animate-pulse">Running</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/40 border border-slate-800/60 text-slate-500">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                                <span>Health Verification</span>
                            </div>
                            <span class="text-[10px]">Queued</span>
                        </div>
                    </div>

                    <!-- Administrator Command Hint Box -->
                    <div class="mt-4 pt-3 border-t border-slate-800/80">
                        <div class="bg-slate-900/90 rounded-xl p-3 border border-slate-800 text-[11px] font-mono text-slate-300 space-y-1">
                            <div class="flex items-center justify-between text-slate-400 text-[10px]">
                                <span>Server Admin Command</span>
                                <span class="text-indigo-400">CLI</span>
                            </div>
                            <div class="text-emerald-400 flex items-center gap-1.5 font-bold">
                                <span class="text-slate-500">$</span>
                                <span>php artisan up</span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-sans mt-1">
                                Brings portal online and restores user access immediately.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Support Info Strip -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 px-2 text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>Need immediate academic assistance? Contact: <a href="mailto:support@vignan.ac.in" class="text-indigo-400 hover:text-indigo-300 underline font-semibold">support@vignan.ac.in</a></span>
            </div>
            <div class="flex items-center gap-4">
                <span>IT Services Helpdesk: Ext. 404 / 405</span>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 border-t border-slate-800/80 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>&copy; {{ date('Y') }} Learning Management Portal • Vignan's Foundation for Science, Technology & Research</p>
        <p>Powered by <span class="font-semibold text-slate-400">Technology Development (TD)</span></p>
    </footer>

    <!-- Interactive Auto-Ping & Status Script -->
    <script>
        let countdown = 15;
        const countdownEl = document.getElementById('countdownText');
        const checkBtn = document.getElementById('checkStatusBtn');
        const checkText = document.getElementById('checkStatusText');
        const refreshIcon = document.getElementById('refreshIcon');
        const onlineBanner = document.getElementById('serverOnlineBanner');
        const pingHalo = document.getElementById('pingHalo');
        const pingDot = document.getElementById('pingDot');
        const statusBadgeText = document.getElementById('statusBadgeText');

        async function checkServerStatus(manual = false) {
            if (manual) {
                checkBtn.disabled = true;
                refreshIcon.classList.add('animate-spin');
                checkText.textContent = 'Checking server...';
            }

            try {
                // Perform a non-cached fetch to root URL
                const response = await fetch('/?t=' + Date.now(), {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    cache: 'no-store'
                });

                // If status is 200 OK (not 503), the server is back online!
                if (response.status === 200) {
                    onServerBackOnline();
                    return;
                }
            } catch (err) {
                // Still in maintenance or network error
            }

            if (manual) {
                setTimeout(() => {
                    checkBtn.disabled = false;
                    refreshIcon.classList.remove('animate-spin');
                    checkText.textContent = 'Check Server Status';
                }, 600);
            }
            countdown = 15;
        }

        function onServerBackOnline() {
            onlineBanner.classList.remove('hidden');
            pingHalo.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75';
            pingDot.className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500';
            statusBadgeText.textContent = 'Portal Live';
            statusBadgeText.className = 'text-xs font-semibold text-emerald-400';
            
            checkText.textContent = 'Server Online! Redirecting...';
            checkBtn.className = 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs sm:text-sm shadow-lg shadow-emerald-600/30 transition-all';
            
            // Auto reload to portal after 2 seconds
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        }

        // Live countdown timer that fires checkServerStatus every 15 seconds
        setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                countdown = 15;
                checkServerStatus(false);
            }
            if (countdownEl) {
                countdownEl.textContent = countdown + 's';
            }
        }, 1000);
    </script>
</body>
</html>
