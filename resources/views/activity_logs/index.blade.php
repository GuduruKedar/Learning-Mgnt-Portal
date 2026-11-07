<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Activity & Audit Log Monitoring - LMS</title>
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</head>
<body class="h-screen overflow-hidden flex bg-gray-50 text-gray-800">

    @include('partials.sidebar')

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Header -->
        @include('partials.top_header')

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50/70 relative custom-scrollbar">
            <div class="pt-6 pb-12 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
                
                <!-- Page Title & Header Section -->
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 bg-white p-6 sm:p-7 rounded-2xl border border-gray-200/80 shadow-xs">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="p-3 bg-gradient-to-tr from-indigo-600 to-blue-500 text-white rounded-xl shadow-md shadow-indigo-100 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Audit & Activity Log Monitoring</h1>
                                <p class="text-gray-500 text-xs sm:text-sm mt-1">Real-time SaaS monitoring dashboard tracking individual activities, user session usage durations, and security audit logs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Department Selector & Export Actions -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[220px] sm:min-w-[260px]">
                            <label for="header-department-select" class="sr-only">Filter by Department</label>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <select id="header-department-select" onchange="filterByDepartment(this.value)" class="w-full pl-9 pr-10 py-2.5 bg-gray-50 hover:bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all cursor-pointer">
                                <option value="all" {{ $selectedDepartment === 'all' ? 'selected' : '' }}>🏛️ All Departments (University-wide)</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->code }}" {{ $selectedDepartment === $dept->code ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Export Dropdown -->
                        <div class="relative inline-block text-left" id="exportDropdownContainer">
                            <button type="button" onclick="toggleExportMenu()" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs hover:shadow-sm transition-all cursor-pointer">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Export CSV</span>
                                <svg class="w-3.5 h-3.5 ml-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="exportMenu" class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 py-1 divide-y divide-gray-100">
                                <a id="exportLogsBtn" href="{{ route('activity_logs.export', array_merge(request()->query(), ['type' => 'logs'])) }}" class="group flex items-center px-4 py-2.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium">
                                    <span class="mr-2.5 text-base">📋</span>
                                    <span>Export Event Logs CSV</span>
                                </a>
                                <a id="exportSessionsBtn" href="{{ route('activity_logs.export', array_merge(request()->query(), ['type' => 'sessions'])) }}" class="group flex items-center px-4 py-2.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium">
                                    <span class="mr-2.5 text-base">⏱️</span>
                                    <span>Export Session Durations CSV</span>
                                </a>
                                <a id="exportUsersBtn" href="{{ route('activity_logs.export', array_merge(request()->query(), ['type' => 'users'])) }}" class="group flex items-center px-4 py-2.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-medium">
                                    <span class="mr-2.5 text-base">👥</span>
                                    <span>Export User Usage Summary CSV</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Incident & Security Alert Banner (If Warning / Danger Logs Exist) -->
                @if(isset($recentCriticalLogs) && $recentCriticalLogs->isNotEmpty())
                    <div class="bg-[#FFFDF5] border border-amber-300/70 border-l-4 border-l-amber-500 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xs relative">
                        <div class="flex items-start sm:items-center space-x-3.5 min-w-0">
                            <span class="p-2.5 bg-amber-500/15 text-amber-600 border border-amber-500/30 rounded-xl shrink-0 shadow-xs flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </span>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-800 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Security &amp; Audit Notice
                                    </span>
                                    <span class="text-xs sm:text-sm font-bold text-gray-900">
                                        {{ $warningCount + $dangerCount }} Alerts Detected
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                                    <span class="text-gray-500 font-medium">Latest:</span>
                                    <span class="font-semibold text-gray-900">{{ $recentCriticalLogs->first()->action_title }}</span>
                                    <span class="text-gray-400">—</span>
                                    <span class="text-gray-700 truncate max-w-xl">{{ $recentCriticalLogs->first()->description }}</span>
                                    <span class="inline-flex items-center text-[11px] font-mono text-amber-700 bg-amber-500/10 px-2 py-0.5 rounded-md border border-amber-500/25">
                                        {{ $recentCriticalLogs->first()->created_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 shrink-0 self-end md:self-auto">
                            <a href="{{ route('activity_logs.index', array_merge(request()->query(), ['severity' => 'warning'])) }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm">
                                View Warnings ({{ $warningCount }})
                            </a>
                            <a href="{{ route('activity_logs.index', array_merge(request()->query(), ['severity' => 'danger'])) }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-xs hover:shadow-sm">
                                View Critical ({{ $dangerCount }})
                            </a>
                        </div>
                    </div>
                @endif

                <!-- User Session & Duration Highlights Strip -->
                <div class="bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 text-white rounded-2xl p-5 sm:p-6 shadow-md border border-indigo-800/40">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-indigo-800/50">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <div>
                                <h3 class="text-base sm:text-lg font-extrabold tracking-tight flex items-center gap-2">
                                    <span>User Session & Usage Duration Analytics</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping mr-1"></span> Live Active
                                    </span>
                                </h3>
                                <p class="text-xs text-indigo-200/80 mt-0.5">Accurate per-session and per-user time spent monitoring with live heartbeat tracking.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="switchTab('sessions')" class="px-3.5 py-1.5 bg-white/10 hover:bg-white/20 active:scale-95 text-white text-xs font-bold rounded-xl border border-white/20 transition-all flex items-center gap-1.5">
                                <span>View Session Durations</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                            <button type="button" onclick="switchTab('users')" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                                <span>Per-User Summary</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- 4 Live Session Metrics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <span class="text-[11px] font-semibold text-indigo-300 uppercase tracking-wider block">Avg Session Length</span>
                            <div class="text-xl sm:text-2xl font-black text-white mt-1 font-mono flex items-center gap-1.5">
                                <span>⏱️</span>
                                <span>{{ $avgSessionDurationFormatted }}</span>
                            </div>
                            <span class="text-[10px] text-indigo-300/70 block mt-0.5">Average time per active login</span>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <span class="text-[11px] font-semibold text-indigo-300 uppercase tracking-wider block">Total Usage Time</span>
                            <div class="text-xl sm:text-2xl font-black text-emerald-400 mt-1 font-mono flex items-center gap-1.5">
                                <span>⏳</span>
                                <span>{{ $totalUsageTimeFormatted }}</span>
                            </div>
                            <span class="text-[10px] text-indigo-300/70 block mt-0.5">Total platform consumption</span>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <span class="text-[11px] font-semibold text-indigo-300 uppercase tracking-wider block">Active Online Now</span>
                            <div class="text-xl sm:text-2xl font-black text-cyan-300 mt-1 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>{{ $activeOnlineCount }}</span>
                                <span class="text-xs font-semibold text-indigo-200">Active</span>
                            </div>
                            <span class="text-[10px] text-indigo-300/70 block mt-0.5">Currently active in LMS</span>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <span class="text-[11px] font-semibold text-indigo-300 uppercase tracking-wider block">Total Sessions</span>
                            <div class="text-xl sm:text-2xl font-black text-white mt-1 font-mono flex items-center gap-1.5">
                                <span>🔑</span>
                                <span>{{ number_format($totalSessionsCount) }}</span>
                            </div>
                            <span class="text-[10px] text-indigo-300/70 block mt-0.5">Recorded user sessions</span>
                        </div>
                    </div>
                </div>

                <!-- 6 Standard KPI Metric Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                    <!-- Metric 1: Total Logs -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Logs</span>
                            <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">{{ number_format($totalLogsCount) }}</div>
                        <div class="mt-1 text-[11px] text-gray-500 font-medium">Individual actions recorded</div>
                    </div>

                    <!-- Metric 2: Active Departments -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Departments</span>
                            <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">{{ $totalActiveDepartments }}</div>
                        <div class="mt-1 text-[11px] text-gray-500 font-medium">Active units logged</div>
                    </div>

                    <!-- Metric 3: Active Users -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Active Actors</span>
                            <span class="p-1.5 bg-cyan-50 text-cyan-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">{{ $totalActiveUsers }}</div>
                        <div class="mt-1 text-[11px] text-gray-500 font-medium">Unique users & staff</div>
                    </div>

                    <!-- Metric 4: Today's Logs -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Today's Logs</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-gray-900">{{ $todayLogsCount }}</div>
                        <div class="mt-1 text-[11px] text-emerald-600 font-semibold flex items-center">
                            <span>{{ $todayTrend >= 0 ? '+' : '' }}{{ $todayTrend }}% vs yesterday</span>
                        </div>
                    </div>

                    <!-- Metric 5: Success Events -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Success Rate</span>
                            <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                        </div>
                        @php
                            $rate = $totalLogsCount > 0 ? round((($successCount + $infoCount) / $totalLogsCount) * 100, 1) : 100;
                        @endphp
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-emerald-600">{{ $rate }}%</div>
                        <div class="mt-1 text-[11px] text-gray-500 font-medium">{{ $successCount + $infoCount }} successful ops</div>
                    </div>

                    <!-- Metric 6: Warnings & Audits -->
                    <div class="bg-white rounded-2xl shadow-xs border border-gray-200/70 p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Alerts & Audits</span>
                            <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </span>
                        </div>
                        <div class="mt-2 text-2xl sm:text-3xl font-black text-amber-600">{{ $warningCount + $dangerCount }}</div>
                        <div class="mt-1 text-[11px] text-amber-700 font-medium">{{ $warningCount }} warn, {{ $dangerCount }} crit</div>
                    </div>
                </div>

                <!-- Graphical & Pie Chart Analytics Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Chart 1: Activity Volume Over Time -->
                    <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                                    Activity Volume Trend (Last {{ $selectedDateRange == 'all' ? '30' : $selectedDateRange }} Days)
                                </h2>
                                <p class="text-xs text-gray-500">Timeline breakdown of user events, submissions, allocations, and alerts</p>
                            </div>
                            
                            <!-- Date Range Quick Filter Pills -->
                            <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-xl text-xs font-semibold self-start sm:self-auto">
                                <button onclick="setTimeRange('7')" class="px-2.5 py-1 rounded-lg transition-all {{ $selectedDateRange === '7' ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">7D</button>
                                <button onclick="setTimeRange('14')" class="px-2.5 py-1 rounded-lg transition-all {{ $selectedDateRange === '14' || $selectedDateRange === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">14D</button>
                                <button onclick="setTimeRange('30')" class="px-2.5 py-1 rounded-lg transition-all {{ $selectedDateRange === '30' ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">30D</button>
                            </div>
                        </div>
                        <div class="mt-4 relative h-72">
                            <canvas id="activityTimelineChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Department Activity Breakdown -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                                    Department Share
                                </h2>
                                <p class="text-xs text-gray-500">Top active units & distribution</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-cyan-50 text-cyan-700 rounded-lg">Top Active</span>
                        </div>
                        <div class="mt-4 relative h-64 flex items-center justify-center">
                            <canvas id="departmentPieChart"></canvas>
                        </div>
                        <div class="mt-3 text-center text-xs text-gray-400">Hover over slices to see department logs & % share</div>
                    </div>

                </div>

                <!-- Secondary Charts Grid: Module Bar & Role Breakdown Donut -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Chart 3: Module Activity Distribution -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Module & Subsystem Distribution</h2>
                                <p class="text-xs text-gray-500">Events grouped by feature category (Academics, Materials, Assignments, etc.)</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg">Modules</span>
                        </div>
                        <div class="mt-4 relative h-60">
                            <canvas id="moduleBarChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 4: Role Breakdown -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-base font-bold text-gray-900">User Role Distribution</h2>
                                <p class="text-xs text-gray-500">Activity breakdown by user access level (Admin, Faculty, Student)</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg">Roles</span>
                        </div>
                        <div class="mt-4 relative h-60">
                            <canvas id="roleDonutChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Department Activity Quick Switcher Cards -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Department Quick Selector & Summary</h2>
                            <p class="text-xs text-gray-500">Click a card to immediately filter all monitoring logs and session usage for that department.</p>
                        </div>
                        @if($selectedDepartment !== 'all')
                            <button onclick="filterByDepartment('all')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
                                <span>Reset to All Departments</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach(array_slice($departmentSummaries, 0, 8) as $dSumm)
                            <div onclick="filterByDepartment('{{ $dSumm['code'] }}')" class="p-3.5 rounded-xl border {{ $selectedDepartment === $dSumm['code'] ? 'border-indigo-600 bg-indigo-50/50 shadow-xs ring-2 ring-indigo-500/40' : 'border-gray-200 bg-white hover:border-indigo-300 hover:bg-gray-50' }} transition-all cursor-pointer flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-900 truncate pr-2" title="{{ $dSumm['name'] }}">{{ $dSumm['name'] }}</span>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 shrink-0">{{ $dSumm['code'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-gray-500 truncate mt-0.5">{{ $dSumm['school_name'] }}</div>
                                </div>
                                <div class="mt-3 flex items-center justify-between border-t border-gray-100/80 pt-2 text-xs">
                                    <span class="font-bold text-indigo-600">{{ $dSumm['total_logs'] }} logs</span>
                                    <span class="text-gray-400 text-[10px]">{{ $dSumm['latest_activity'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Unified Monitoring Table Section with Interactive Tabs -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                    
                    <!-- Tab Navigation Header -->
                    <div class="px-5 pt-4 pb-0 border-b border-gray-200 bg-gray-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center space-x-2 overflow-x-auto custom-scrollbar pb-2 sm:pb-0">
                            <!-- Tab 1: Event Logs -->
                            <button type="button" 
                                    id="tabBtn-logs" 
                                    onclick="switchTab('logs')" 
                                    class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'logs' ? 'bg-white text-indigo-700 shadow-xs border border-gray-200/80' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100/80' }}">
                                <span>📋 Activity Event Logs</span>
                                <span id="tabCount-logs" class="px-2 py-0.5 text-[10px] rounded-full {{ $activeTab === 'logs' ? 'bg-indigo-100 text-indigo-800 font-extrabold' : 'bg-gray-200 text-gray-600' }}">{{ number_format($totalLogsCount) }}</span>
                            </button>

                            <!-- Tab 2: User Session Durations -->
                            <button type="button" 
                                    id="tabBtn-sessions" 
                                    onclick="switchTab('sessions')" 
                                    class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'sessions' ? 'bg-white text-indigo-700 shadow-xs border border-gray-200/80' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100/80' }}">
                                <span>⏱️ User Session Durations</span>
                                <span id="tabCount-sessions" class="px-2 py-0.5 text-[10px] rounded-full {{ $activeTab === 'sessions' ? 'bg-indigo-100 text-indigo-800 font-extrabold' : 'bg-gray-200 text-gray-600' }}">{{ number_format($totalSessionsCount) }}</span>
                            </button>

                            <!-- Tab 3: Per-User Usage Summary -->
                            <button type="button" 
                                    id="tabBtn-users" 
                                    onclick="switchTab('users')" 
                                    class="tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer {{ $activeTab === 'users' ? 'bg-white text-indigo-700 shadow-xs border border-gray-200/80' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100/80' }}">
                                <span>👥 Per-User Usage Summary</span>
                                <span id="tabCount-users" class="px-2 py-0.5 text-[10px] rounded-full {{ $activeTab === 'users' ? 'bg-indigo-100 text-indigo-800 font-extrabold' : 'bg-gray-200 text-gray-600' }}">{{ number_format($totalActiveUsers) }}</span>
                            </button>
                        </div>

                        <!-- Live Auto-Refresh Controls -->
                        <div class="flex items-center space-x-1.5 pb-2 text-xs text-gray-500 self-end sm:self-auto">
                            <span class="text-[11px] font-semibold text-gray-400">Auto-Refresh:</span>
                            <div class="flex items-center bg-gray-200/70 p-0.5 rounded-lg text-[11px]">
                                <button type="button" id="btn-refresh-off" onclick="setAutoRefresh(0)" class="px-2 py-0.5 rounded-md transition-all bg-white text-gray-900 shadow-xs font-bold">Off</button>
                                <button type="button" id="btn-refresh-10" onclick="setAutoRefresh(10)" class="px-2 py-0.5 rounded-md transition-all hover:text-gray-900 text-gray-600 font-semibold">10s</button>
                                <button type="button" id="btn-refresh-30" onclick="setAutoRefresh(30)" class="px-2 py-0.5 rounded-md transition-all hover:text-gray-900 text-gray-600 font-semibold">30s</button>
                            </div>
                            <span id="refresh-countdown" class="hidden text-[10px] text-indigo-600 font-mono font-bold animate-pulse"></span>
                        </div>
                    </div>

                    <!-- Table Search & Filter Toolbar -->
                    <div class="p-5 border-b border-gray-100 bg-gray-50/40">
                        <form id="liveFilterForm" onsubmit="event.preventDefault(); fetchLiveLogs(1);" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                            
                            <!-- Search Field with Live Debounce and Spinner -->
                            <div class="lg:col-span-2 relative">
                                <label for="table-search" class="sr-only">Search</label>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="table-search" name="search" value="{{ $searchTerm }}" oninput="debounceLiveFilter()" placeholder="Search user, role, IP, action..." class="w-full pl-9 pr-9 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 placeholder-gray-400 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                
                                <!-- Live Spinner Indicator -->
                                <div id="liveFilterSpinner" class="hidden absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-indigo-600">
                                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            <!-- Department Filter -->
                            <div>
                                <select id="filterDepartment" name="department" onchange="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->code }}" {{ $selectedDepartment === $dept->code ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Role Filter (Works on All Tabs) -->
                            <div>
                                <select id="filterRole" name="role" onchange="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Roles</option>
                                    <option value="sa" {{ $selectedRole === 'sa' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Dept Coordinator</option>
                                    <option value="sta" {{ $selectedRole === 'sta' ? 'selected' : '' }}>Faculty / Staff</option>
                                    <option value="stu" {{ $selectedRole === 'stu' ? 'selected' : '' }}>Student</option>
                                    <option value="civil_admin" {{ $selectedRole === 'civil_admin' ? 'selected' : '' }}>Civil Admin</option>
                                </select>
                            </div>

                            <!-- Module Filter (Logs Tab Only) -->
                            <div id="filterModuleWrapper">
                                <select id="filterModule" name="module" onchange="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Modules</option>
                                    @foreach($modulesList as $mod)
                                        <option value="{{ $mod }}" {{ $selectedModule === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter & Reset Buttons -->
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs transition-all text-center cursor-pointer flex items-center justify-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    Apply
                                </button>
                                <button type="button" onclick="clearLiveFilters()" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 rounded-xl text-xs sm:text-sm font-semibold transition-all cursor-pointer">
                                    Clear
                                </button>
                            </div>

                        </form>
                    </div>

                    <!-- Dynamic Table Wrapper -->
                    <div class="overflow-x-auto relative">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-gray-50/80" id="tableHeader">
                                @if($activeTab === 'sessions')
                                    <tr>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Session Started</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Last Activity / End</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Session Duration</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Activity Volume</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Inspect</th>
                                    </tr>
                                @elseif($activeTab === 'users')
                                    <tr>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Total Sessions</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Total Platform Time</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Avg Session Time</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Operations Done</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Last Activity</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">View Sessions</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Timestamp</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department & School</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Module & Action</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Description & Target</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Inspect</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody id="logsTableBody" class="divide-y divide-gray-100 bg-white transition-opacity duration-150">
                                @if($activeTab === 'sessions')
                                    @include('activity_logs.partials.session_rows', ['sessions' => $sessions])
                                @elseif($activeTab === 'users')
                                    @include('activity_logs.partials.user_summary_rows', ['userSummaries' => $userSummaries])
                                @else
                                    @include('activity_logs.partials.table_rows', ['logs' => $logs])
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div id="logsPaginationSummary" class="text-xs text-gray-500 font-medium">
                            @if($activeTab === 'sessions')
                                Showing {{ $sessions->firstItem() ?? 0 }} to {{ $sessions->lastItem() ?? 0 }} of {{ $sessions->total() }} user session records
                            @elseif($activeTab === 'users')
                                Showing {{ $userSummaries->firstItem() ?? 0 }} to {{ $userSummaries->lastItem() ?? 0 }} of {{ $userSummaries->total() }} per-user usage summaries
                            @else
                                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} individual log reports
                            @endif
                        </div>
                        <div id="logsPaginationLinks">
                            @if($activeTab === 'sessions')
                                {{ $sessions->links() }}
                            @elseif($activeTab === 'users')
                                {{ $userSummaries->links() }}
                            @else
                                {{ $logs->links() }}
                            @endif
                        </div>
                    </div>

                </div>

                <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                    <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} Learning Management System. Activity & Audit Monitoring Engine with User Session Tracking.</p>
                </footer>

            </div>
        </main>
    </div>

    <!-- Individual Log Details Modal (With Session Duration Integration) -->
    <div id="logDetailModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden border border-gray-100 transform transition-all">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-gray-900 to-indigo-950 text-white flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-white/10 rounded-lg text-indigo-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold" id="modalLogTitle">Individual Log Details</h3>
                        <p class="text-xs text-gray-300 font-mono" id="modalLogId">ID: #---</p>
                    </div>
                </div>
                <button type="button" onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Scrollable Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-5 text-xs text-gray-700">
                
                <!-- Quick Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Department</span>
                        <span class="font-bold text-gray-900 text-xs" id="modalDepartment">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Actor / Role</span>
                        <span class="font-bold text-indigo-700 text-xs" id="modalActor">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Module</span>
                        <span class="font-bold text-gray-900 text-xs" id="modalModule">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Severity</span>
                        <span class="font-bold text-xs" id="modalSeverity">---</span>
                    </div>
                </div>

                <!-- Session & Usage Duration Context -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-indigo-50/60 p-4 rounded-xl border border-indigo-100">
                    <div>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block flex items-center gap-1">
                            <span>⏱️ Session Duration (This Event)</span>
                        </span>
                        <span class="font-mono font-extrabold text-indigo-950 text-sm mt-0.5 block" id="modalSessionDuration">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block flex items-center gap-1">
                            <span>👥 User Total Platform Time</span>
                        </span>
                        <span class="font-mono font-extrabold text-indigo-950 text-sm mt-0.5 block" id="modalUserTotalUsage">---</span>
                    </div>
                </div>

                <!-- Description & Target -->
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Event Summary</label>
                    <p class="text-sm font-medium text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100" id="modalDescription">---</p>
                </div>

                <!-- Technical & Network Metadata -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50/70 p-4 rounded-xl border border-gray-200/60 font-mono-code text-[11px]">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-sans font-bold">IP Address</span>
                        <span class="text-gray-800" id="modalIp">---</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-sans font-bold">HTTP Method & URL</span>
                        <span class="text-gray-800 truncate block" id="modalMethodUrl">---</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-400 block text-[10px] uppercase font-sans font-bold">User Agent / Client</span>
                        <span class="text-gray-700 text-[10px] break-all block" id="modalUserAgent">---</span>
                    </div>
                </div>

                <!-- JSON Payload Viewer -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Payload & Execution Context</label>
                        <button onclick="copyPayload()" class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer">Copy JSON</button>
                    </div>
                    <pre class="bg-gray-900 text-emerald-400 p-4 rounded-xl font-mono-code text-[11px] overflow-x-auto custom-scrollbar max-h-48 border border-gray-800" id="modalPayload">{}</pre>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                <span id="modalTimestamp">Recorded: ---</span>
                <button type="button" onclick="closeLogDetailsModal()" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold transition-colors cursor-pointer">
                    Close
                </button>
            </div>

        </div>
    </div>

    <!-- Dedicated User Session Inspection Modal -->
    <div id="sessionDetailModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden border border-gray-100 transform transition-all">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 text-white flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-white/10 rounded-lg text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold" id="sessModalUser">User Session Details</h3>
                        <p class="text-xs text-indigo-300 font-mono" id="sessModalId">Session ID: #---</p>
                    </div>
                </div>
                <button type="button" onclick="closeSessionDetailsModal()" class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-4 text-xs text-gray-700">
                
                <!-- Duration Highlight Card -->
                <div class="bg-gradient-to-tr from-emerald-500/10 to-indigo-500/10 p-5 rounded-2xl border border-emerald-200/60 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Session Duration Spent</span>
                        <div class="text-2xl font-black text-gray-900 mt-1 font-mono flex items-center gap-2">
                            <span>⏱️</span>
                            <span id="sessModalDuration">---</span>
                        </div>
                    </div>
                    <span id="sessModalStatusBadge" class="px-3 py-1 rounded-xl text-xs font-bold border">---</span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">User Role</span>
                        <span class="font-bold text-indigo-700 text-xs" id="sessModalRole">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Department</span>
                        <span class="font-bold text-gray-900 text-xs" id="sessModalDept">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Login Started At</span>
                        <span class="font-bold text-gray-900 text-xs font-mono" id="sessModalLoginAt">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Last Activity At</span>
                        <span class="font-bold text-gray-900 text-xs font-mono" id="sessModalLastAt">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Session End / Logout</span>
                        <span class="font-bold text-gray-900 text-xs font-mono" id="sessModalLogoutAt">---</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Actions Recorded</span>
                        <span class="font-bold text-emerald-700 text-xs font-mono" id="sessModalActions">---</span>
                    </div>
                </div>

                <!-- Device / IP Metadata -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/60 font-mono-code text-[11px] space-y-2">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-sans font-bold">Client IP Address</span>
                        <span class="text-gray-800" id="sessModalIp">---</span>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
                <button type="button" onclick="closeSessionDetailsModal()" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold transition-colors cursor-pointer">
                    Done
                </button>
            </div>

        </div>
    </div>

    <!-- Live Initialization & Interaction Scripts -->
    <script>
        let currentTab = '{{ $activeTab }}';

        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Timeline Chart (Line / Area)
            const ctxTimeline = document.getElementById('activityTimelineChart').getContext('2d');
            const timelineGradient = ctxTimeline.createLinearGradient(0, 0, 0, 280);
            timelineGradient.addColorStop(0, 'rgba(79, 70, 229, 0.35)');
            timelineGradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

            new Chart(ctxTimeline, {
                type: 'line',
                data: {
                    labels: {!! json_encode($timelineLabels) !!},
                    datasets: [
                        {
                            label: 'Total Activity Logs',
                            data: {!! json_encode($timelineData) !!},
                            borderColor: '#4f46e5',
                            backgroundColor: timelineGradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Warnings & Criticals',
                            data: {!! json_encode($timelineWarningData) !!},
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.08)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.38,
                            pointBackgroundColor: '#f59e0b',
                            pointRadius: 2,
                            pointHoverRadius: 5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                font: { size: 11, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e1b4b',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#94a3b8' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(241, 245, 249, 0.9)' },
                            ticks: { precision: 0, font: { size: 11 }, color: '#94a3b8' }
                        }
                    }
                }
            });

            // 2. Department Breakdown Doughnut Chart
            const ctxDept = document.getElementById('departmentPieChart').getContext('2d');
            new Chart(ctxDept, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieLabels) !!},
                    datasets: [{
                        data: {!! json_encode($pieData) !!},
                        backgroundColor: {!! json_encode($pieColors) !!},
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                font: { size: 10, weight: '500' },
                                padding: 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw || 0;
                                    const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${value} logs (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // 3. Module Distribution Bar Chart
            const ctxModule = document.getElementById('moduleBarChart').getContext('2d');
            new Chart(ctxModule, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($moduleLabels) !!},
                    datasets: [{
                        label: 'Logs Count',
                        data: {!! json_encode($moduleData) !!},
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'
                        ],
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { cornerRadius: 8 }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(241, 245, 249, 0.9)' },
                            ticks: { precision: 0 }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // 4. Role Breakdown Donut Chart
            const ctxRole = document.getElementById('roleDonutChart').getContext('2d');
            new Chart(ctxRole, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($roleLabels) !!},
                    datasets: [{
                        data: {!! json_encode($roleData) !!},
                        backgroundColor: ['#6366f1', '#0ea5e9', '#14b8a6', '#f43f5e', '#8b5cf6'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });

            // Auto-Refresh restore from localStorage
            const savedInterval = parseInt(localStorage.getItem('activity_log_auto_refresh') || '0', 10);
            if (savedInterval > 0) {
                setAutoRefresh(savedInterval);
            }

            // Pagination Link Interception
            const paginationContainer = document.getElementById('logsPaginationLinks');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link && link.href) {
                        e.preventDefault();
                        try {
                            const parsedUrl = new URL(link.href);
                            const page = parsedUrl.searchParams.get('page') || 
                                         parsedUrl.searchParams.get('logs_page') || 
                                         parsedUrl.searchParams.get('sessions_page') || 
                                         parsedUrl.searchParams.get('users_page') || 1;
                            fetchLiveLogs(page);
                        } catch (err) {
                            window.location.href = link.href;
                        }
                    }
                });
            }

            // Close export dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const container = document.getElementById('exportDropdownContainer');
                const menu = document.getElementById('exportMenu');
                if (container && !container.contains(e.target) && menu) {
                    menu.classList.add('hidden');
                }
            });

            // Heartbeat to keep active session and duration accurately updated
            setInterval(() => {
                fetch('{{ route('session.heartbeat') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).catch(() => {});
            }, 60000);

        });

        // Toggle Export Menu
        function toggleExportMenu() {
            const menu = document.getElementById('exportMenu');
            if (menu) menu.classList.toggle('hidden');
        }

        // Tab Switching Engine
        function switchTab(tab) {
            currentTab = tab;

            // Update Tab Button Styles
            ['logs', 'sessions', 'users'].forEach(t => {
                const btn = document.getElementById(`tabBtn-${t}`);
                const countBadge = document.getElementById(`tabCount-${t}`);
                if (btn) {
                    if (t === tab) {
                        btn.className = 'tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer bg-white text-indigo-700 shadow-xs border border-gray-200/80';
                        if (countBadge) countBadge.className = 'px-2 py-0.5 text-[10px] rounded-full bg-indigo-100 text-indigo-800 font-extrabold';
                    } else {
                        btn.className = 'tab-btn px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer text-gray-500 hover:text-gray-800 hover:bg-gray-100/80';
                        if (countBadge) countBadge.className = 'px-2 py-0.5 text-[10px] rounded-full bg-gray-200 text-gray-600';
                    }
                }
            });

            // Update Table Header
            const header = document.getElementById('tableHeader');
            if (header) {
                if (tab === 'sessions') {
                    header.innerHTML = `
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Session Started</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Last Activity / End</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Session Duration</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Activity Volume</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Inspect</th>
                        </tr>
                    `;
                } else if (tab === 'users') {
                    header.innerHTML = `
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Total Sessions</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Total Platform Time</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Avg Session Time</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Operations Done</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Last Activity</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">View Sessions</th>
                        </tr>
                    `;
                } else {
                    header.innerHTML = `
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Timestamp</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department & School</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Module & Action</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Description & Target</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Inspect</th>
                        </tr>
                    `;
                }
            }

            // Hide/Show Module filter wrapper on session tabs
            const modWrapper = document.getElementById('filterModuleWrapper');
            if (modWrapper) {
                if (tab === 'logs') {
                    modWrapper.classList.remove('hidden');
                } else {
                    modWrapper.classList.add('hidden');
                }
            }

            fetchLiveLogs(1);
        }

        // Live Filters Engine
        let liveFilterDebounceTimer = null;
        let currentLiveAbortController = null;

        function debounceLiveFilter() {
            clearTimeout(liveFilterDebounceTimer);
            const spinner = document.getElementById('liveFilterSpinner');
            if (spinner) spinner.classList.remove('hidden');
            liveFilterDebounceTimer = setTimeout(() => {
                fetchLiveLogs(1);
            }, 260);
        }

        async function fetchLiveLogs(page = 1) {
            const searchInput = document.getElementById('table-search');
            const deptSelect = document.getElementById('filterDepartment');
            const roleSelect = document.getElementById('filterRole');
            const modSelect = document.getElementById('filterModule');
            const headerDeptSelect = document.getElementById('header-department-select');
            const spinner = document.getElementById('liveFilterSpinner');
            const tableBody = document.getElementById('logsTableBody');
            const paginationLinks = document.getElementById('logsPaginationLinks');
            const paginationSummary = document.getElementById('logsPaginationSummary');

            if (spinner) spinner.classList.remove('hidden');
            if (tableBody) tableBody.style.opacity = '0.45';

            if (currentLiveAbortController) {
                currentLiveAbortController.abort();
            }
            currentLiveAbortController = new AbortController();

            const search = searchInput ? searchInput.value.trim() : '';
            let dept = deptSelect ? deptSelect.value : 'all';
            const role = roleSelect ? roleSelect.value : 'all';
            const mod = modSelect ? modSelect.value : 'all';

            if (dept === 'all' && headerDeptSelect && headerDeptSelect.value !== 'all') {
                dept = headerDeptSelect.value;
            }

            const params = new URLSearchParams();
            params.set('tab', currentTab);
            if (search) params.set('search', search);
            if (dept && dept !== 'all') params.set('department', dept);
            if (role && role !== 'all') params.set('role', role);
            if (currentTab === 'logs' && mod && mod !== 'all') params.set('module', mod);
            if (page && page > 1) params.set('page', page);

            const requestParams = new URLSearchParams(params);
            requestParams.set('live', '1');

            try {
                const response = await fetch(`{{ route('activity_logs.index') }}?${requestParams.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Live-Filter': '1',
                        'Accept': 'application/json'
                    },
                    signal: currentLiveAbortController.signal
                });

                const data = await response.json();

                if (data.status === 'success') {
                    if (tableBody) {
                        tableBody.innerHTML = data.table_html;
                    }

                    if (paginationLinks) {
                        paginationLinks.innerHTML = data.pagination_html || '';
                    }

                    if (paginationSummary) {
                        let entityName = 'individual log reports';
                        if (data.tab === 'sessions') entityName = 'user session records';
                        else if (data.tab === 'users') entityName = 'per-user usage summaries';
                        paginationSummary.innerText = `Showing ${data.first_item} to ${data.last_item} of ${data.total} ${entityName} (10 per page)`;
                    }

                    // Update export URLs
                    const expLogs = document.getElementById('exportLogsBtn');
                    const expSessions = document.getElementById('exportSessionsBtn');
                    const expUsers = document.getElementById('exportUsersBtn');
                    if (expLogs) expLogs.href = `{{ route('activity_logs.export') }}?type=logs&${params.toString()}`;
                    if (expSessions) expSessions.href = `{{ route('activity_logs.export') }}?type=sessions&${params.toString()}`;
                    if (expUsers) expUsers.href = `{{ route('activity_logs.export') }}?type=users&${params.toString()}`;

                    // Update browser history URL
                    const cleanUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
                    window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Live filter fetch failed:', err);
                }
            } finally {
                if (spinner) spinner.classList.add('hidden');
                if (tableBody) tableBody.style.opacity = '1';
            }
        }

        function clearLiveFilters() {
            const searchInput = document.getElementById('table-search');
            const deptSelect = document.getElementById('filterDepartment');
            const roleSelect = document.getElementById('filterRole');
            const modSelect = document.getElementById('filterModule');

            if (searchInput) searchInput.value = '';
            if (deptSelect) deptSelect.value = 'all';
            if (roleSelect) roleSelect.value = 'all';
            if (modSelect) modSelect.value = 'all';

            fetchLiveLogs(1);
        }

        function filterByDepartment(deptCode) {
            const deptSelect = document.getElementById('filterDepartment');
            if (deptSelect) deptSelect.value = deptCode;
            fetchLiveLogs(1);
        }

        function filterBySpecificUser(userName) {
            switchTab('sessions');
            const searchInput = document.getElementById('table-search');
            if (searchInput) searchInput.value = userName;
            fetchLiveLogs(1);
        }

        function setTimeRange(days) {
            const url = new URL(window.location.href);
            url.searchParams.set('date_range', days);
            url.searchParams.set('page', 1);
            url.searchParams.set('tab', currentTab);
            window.location.href = url.toString();
        }

        // Auto Refresh Engine
        let autoRefreshTimer = null;
        let countdownTimer = null;
        let secondsLeft = 0;

        function setAutoRefresh(seconds) {
            clearInterval(autoRefreshTimer);
            clearInterval(countdownTimer);
            
            const btnOff = document.getElementById('btn-refresh-off');
            const btn10 = document.getElementById('btn-refresh-10');
            const btn30 = document.getElementById('btn-refresh-30');
            const countdownEl = document.getElementById('refresh-countdown');

            [btnOff, btn10, btn30].forEach(btn => {
                if (btn) btn.className = 'px-2 py-0.5 rounded-md transition-all hover:text-gray-900 text-gray-600 font-semibold';
            });

            if (seconds === 0) {
                localStorage.removeItem('activity_log_auto_refresh');
                if (btnOff) btnOff.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-gray-900 shadow-xs font-bold';
                if (countdownEl) countdownEl.classList.add('hidden');
                return;
            }

            localStorage.setItem('activity_log_auto_refresh', seconds);
            if (seconds === 10 && btn10) {
                btn10.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-indigo-700 shadow-xs font-bold';
            } else if (seconds === 30 && btn30) {
                btn30.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-indigo-700 shadow-xs font-bold';
            }

            secondsLeft = seconds;
            if (countdownEl) {
                countdownEl.classList.remove('hidden');
                countdownEl.innerText = `Refreshing in ${secondsLeft}s...`;
            }

            countdownTimer = setInterval(() => {
                secondsLeft--;
                if (secondsLeft > 0) {
                    if (countdownEl) countdownEl.innerText = `Refreshing in ${secondsLeft}s...`;
                } else {
                    if (countdownEl) countdownEl.innerText = `Refreshing now...`;
                }
            }, 1000);

            autoRefreshTimer = setInterval(() => {
                fetchLiveLogs();
            }, seconds * 1000);
        }

        // Modal inspector handlers for Event Logs
        async function openLogDetailsModal(logId) {
            const modal = document.getElementById('logDetailModal');
            modal.classList.remove('hidden');

            try {
                const res = await fetch(`/activity-logs/${logId}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    const log = data.log;
                    document.getElementById('modalLogTitle').innerText = log.action_title;
                    document.getElementById('modalLogId').innerText = `ID: #${log.id} • Action: ${log.action}`;
                    document.getElementById('modalDepartment').innerText = log.department_name;
                    document.getElementById('modalActor').innerText = `${log.user_name} (${log.user_role})`;
                    document.getElementById('modalModule').innerText = log.module;
                    document.getElementById('modalSeverity').innerText = log.severity.toUpperCase();
                    document.getElementById('modalDescription').innerText = log.description;
                    document.getElementById('modalIp').innerText = log.ip_address || '127.0.0.1';
                    document.getElementById('modalMethodUrl').innerText = `${log.method || 'POST'} ${log.url || '/'}`;
                    document.getElementById('modalUserAgent').innerText = log.user_agent || 'Mozilla/5.0';
                    document.getElementById('modalPayload').innerText = JSON.stringify(log.payload || {}, null, 2);
                    document.getElementById('modalTimestamp').innerText = `Recorded: ${log.created_at_formatted} (${log.relative_time})`;
                    
                    // Session duration metrics
                    document.getElementById('modalSessionDuration').innerText = log.session_duration || 'N/A';
                    document.getElementById('modalUserTotalUsage').innerText = log.user_total_usage || 'N/A';
                }
            } catch (err) {
                console.error(err);
            }
        }

        function closeLogDetailsModal() {
            document.getElementById('logDetailModal').classList.add('hidden');
        }

        // Dedicated Inspector for Session Rows
        function inspectUserSession(id, userName, role, dept, loginAt, lastAt, logoutAt, duration, actions, status, ip) {
            document.getElementById('sessModalId').innerText = `Session Record ID: #${id}`;
            document.getElementById('sessModalUser').innerText = `${userName} — Session Report`;
            document.getElementById('sessModalRole').innerText = role;
            document.getElementById('sessModalDept').innerText = dept;
            document.getElementById('sessModalLoginAt').innerText = loginAt;
            document.getElementById('sessModalLastAt').innerText = lastAt;
            document.getElementById('sessModalLogoutAt').innerText = logoutAt;
            document.getElementById('sessModalDuration').innerText = duration;
            document.getElementById('sessModalActions').innerText = `${actions} operations`;
            document.getElementById('sessModalIp').innerText = ip;

            const badge = document.getElementById('sessModalStatusBadge');
            badge.innerText = status;
            if (status.includes('Active')) {
                badge.className = 'px-3 py-1 rounded-xl text-xs font-bold border bg-emerald-100 text-emerald-800 border-emerald-300';
            } else if (status.includes('Logged Out')) {
                badge.className = 'px-3 py-1 rounded-xl text-xs font-bold border bg-gray-100 text-gray-700 border-gray-300';
            } else {
                badge.className = 'px-3 py-1 rounded-xl text-xs font-bold border bg-amber-100 text-amber-800 border-amber-300';
            }

            document.getElementById('sessionDetailModal').classList.remove('hidden');
        }

        function closeSessionDetailsModal() {
            document.getElementById('sessionDetailModal').classList.add('hidden');
        }

        function copyPayload() {
            const text = document.getElementById('modalPayload').innerText;
            navigator.clipboard.writeText(text);
            alert('Log payload copied to clipboard!');
        }
    </script>
</body>
</html>
