<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity & Audit Log Monitoring - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </span>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Audit & Activity Log Monitoring</h1>
                                <p class="text-gray-500 text-xs sm:text-sm mt-1">Real-time SaaS monitoring dashboard tracking individual activities, department allocations, and security audit logs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Department Selector & Export Actions -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[240px] sm:min-w-[280px]">
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

                        <a id="exportCsvBtn" href="{{ route('activity_logs.export', request()->query()) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-xs hover:shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Log CSV
                        </a>
                    </div>
                </div>

                <!-- Incident & Security Alert Banner (If Warning / Danger Logs Exist) -->
                @if(isset($recentCriticalLogs) && $recentCriticalLogs->isNotEmpty())
                    <div class="bg-amber-50/90 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-start sm:items-center space-x-3">
                            <span class="p-2.5 bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/25 rounded-xl shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </span>
                            <div>
                                <h2 class="text-xs font-bold text-amber-900 dark:text-amber-200 uppercase tracking-wide">Security & Audit Notice: {{ $warningCount + $dangerCount }} Alerts Detected</h2>
                                <p class="text-xs text-amber-800 dark:text-amber-300/90 mt-0.5">
                                    Latest: <span class="font-semibold text-amber-900 dark:text-white">{{ $recentCriticalLogs->first()->action_title }}</span> — {{ $recentCriticalLogs->first()->description }} 
                                    <span class="text-[11px] text-amber-600 dark:text-amber-400 font-mono">({{ $recentCriticalLogs->first()->created_at->diffForHumans() }})</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0 self-end md:self-auto">
                            <a href="{{ route('activity_logs.index', array_merge(request()->query(), ['severity' => 'warning'])) }}" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shadow-xs hover:shadow-sm">
                                View Warnings ({{ $warningCount }})
                            </a>
                            <a href="{{ route('activity_logs.index', array_merge(request()->query(), ['severity' => 'danger'])) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shadow-xs hover:shadow-sm">
                                View Critical ({{ $dangerCount }})
                            </a>
                        </div>
                    </div>
                @endif

                <!-- 6 Dynamic KPI Metric Cards -->
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
                    
                    <!-- Chart 1: Activity Volume Over Time (Line Chart with Date Switcher) -->
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

                    <!-- Chart 2: Department Activity Breakdown (Clean Doughnut Chart Top 7 + Others) -->
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
                    
                    <!-- Chart 3: Module Activity Distribution (Horizontal Bar Chart) -->
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

                    <!-- Chart 4: User Role & Actor Breakdown (Donut Chart) -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h2 class="text-base font-bold text-gray-900">Actor Role Activity Share</h2>
                                <p class="text-xs text-gray-500">Activity volume generated by Super Admin, Coordinators, Faculty, & Students</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg">Roles</span>
                        </div>
                        <div class="mt-4 relative h-60 flex items-center justify-center">
                            <canvas id="roleDonutChart"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Department Quick-Cards Hub -->
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-200/80 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Department Activity Quick-Hub</h2>
                            <p class="text-xs text-gray-500">Click any department card to filter individual log reports specifically for that unit.</p>
                        </div>
                        @if($selectedDepartment !== 'all')
                            <button onclick="filterByDepartment('all')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 self-start">
                                <span>Reset to All Departments</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach(array_slice($departmentSummaries, 0, 8) as $dSumm)
                            <div onclick="filterByDepartment('{{ $dSumm['code'] }}')" class="p-3.5 rounded-xl border {{ $selectedDepartment === $dSumm['code'] ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/40 shadow-xs ring-2 ring-indigo-500/40' : 'border-gray-200 dark:border-slate-800 bg-white dark:bg-[#151B23] hover:border-indigo-300 dark:hover:border-indigo-500/50 hover:bg-gray-50 dark:hover:bg-[#1C2430]' }} transition-all cursor-pointer flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-900 dark:text-white truncate pr-2" title="{{ $dSumm['name'] }}">{{ $dSumm['name'] }}</span>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 border border-transparent dark:border-slate-700/60 shrink-0">{{ $dSumm['code'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-gray-500 dark:text-slate-400 truncate mt-0.5">{{ $dSumm['school_name'] }}</div>
                                </div>
                                <div class="mt-3 flex items-center justify-between border-t border-gray-100/80 dark:border-slate-800 pt-2 text-xs">
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $dSumm['total_logs'] }} logs</span>
                                    <span class="text-gray-400 dark:text-slate-500 text-[10px]">{{ $dSumm['latest_activity'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Individual Log Reports Table Section -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                    
                    <!-- Table Search & Filter Toolbar -->
                    <div class="p-5 border-b border-gray-100 bg-gray-50/40">
                        <form id="liveFilterForm" onsubmit="event.preventDefault(); fetchLiveLogs(1);" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                            
                            <!-- Search Field with Live Debounce and Spinner -->
                            <div class="lg:col-span-2 relative">
                                <label for="table-search" class="sr-only">Search logs</label>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="table-search" name="search" value="{{ $searchTerm }}" oninput="debounceLiveFilter()" placeholder="Search actor, action, IP, entity..." class="w-full pl-9 pr-9 py-2 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 placeholder-gray-400 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                
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

                            <!-- Module Filter -->
                            <div>
                                <select id="filterModule" name="module" onchange="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Modules</option>
                                    @foreach($modulesList as $mod)
                                        <option value="{{ $mod }}" {{ $selectedModule === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Severity Filter -->
                            <div>
                                <select id="filterSeverity" name="severity" onchange="fetchLiveLogs(1)" class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 shadow-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer transition-all">
                                    <option value="all">All Severities</option>
                                    <option value="success" {{ $selectedSeverity === 'success' ? 'selected' : '' }}>✅ Success</option>
                                    <option value="info" {{ $selectedSeverity === 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                                    <option value="warning" {{ $selectedSeverity === 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                                    <option value="danger" {{ $selectedSeverity === 'danger' ? 'selected' : '' }}>🚨 Danger / Critical</option>
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

                    <!-- Individual Logs Table -->
                    <div class="overflow-x-auto relative">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Timestamp</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Department & School</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">User / Actor</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Module & Action</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Description & Target</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider text-right">Inspect</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody" class="divide-y divide-gray-100 bg-white transition-opacity duration-150">
                                @include('activity_logs.partials.table_rows', ['logs' => $logs])
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer (10 items per page) -->
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div id="logsPaginationSummary" class="text-xs text-gray-500 font-medium">
                            Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} individual log reports (10 per page)
                        </div>
                        <div id="logsPaginationLinks">
                            {{ $logs->links() }}
                        </div>
                    </div>

                </div>

                <footer class="mt-8 border-t border-gray-200 pt-4 pb-2">
                    <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} Learning Management System. Activity & Audit Monitoring Engine.</p>
                </footer>

            </div>
        </main>
    </div>

    <!-- Individual Log Details Slide-Over / Modal -->
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
                <button type="button" onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-white p-1 rounded-lg hover:bg-white/10 focus:outline-none">
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
                        <button onclick="copyPayload()" class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-800">Copy JSON</button>
                    </div>
                    <pre class="bg-gray-900 text-emerald-400 p-4 rounded-xl font-mono-code text-[11px] overflow-x-auto custom-scrollbar max-h-48 border border-gray-800" id="modalPayload">{}</pre>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400">
                <span id="modalTimestamp">Recorded: ---</span>
                <button type="button" onclick="closeLogDetailsModal()" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold transition-colors">
                    Close
                </button>
            </div>

        </div>
    </div>

    <!-- Chart.js Live Initialization Scripts -->
    <script>
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
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Security & Warnings',
                            data: {!! json_encode($timelineWarningData) !!},
                            borderColor: '#f59e0b',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.38,
                            pointBackgroundColor: '#f59e0b',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { family: 'Inter', size: 12, weight: '600' } }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(241, 245, 249, 0.9)' },
                            ticks: { font: { family: 'Inter', size: 11 }, precision: 0 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 11 } }
                        }
                    }
                }
            });

            // 2. Department Breakdown (Pie / Doughnut Chart Top 7 + Others)
            const ctxPie = document.getElementById('departmentPieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieLabels) !!},
                    datasets: [{
                        data: {!! json_encode($pieData) !!},
                        backgroundColor: {!! json_encode($pieColors) !!},
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw || 0;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${value} logs (${percentage}%)`;
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

            // Check if user had auto-refresh enabled in localStorage
            const savedInterval = parseInt(localStorage.getItem('activity_log_auto_refresh') || '0', 10);
            if (savedInterval > 0) {
                setAutoRefresh(savedInterval);
            }

            // Bind live pagination link interception
            const paginationContainer = document.getElementById('logsPaginationLinks');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link && link.href) {
                        e.preventDefault();
                        try {
                            const parsedUrl = new URL(link.href);
                            const page = parsedUrl.searchParams.get('page') || 1;
                            fetchLiveLogs(page);
                        } catch (err) {
                            window.location.href = link.href;
                        }
                    }
                });
            }

        });

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
            const modSelect = document.getElementById('filterModule');
            const sevSelect = document.getElementById('filterSeverity');
            const headerDeptSelect = document.getElementById('header-department-select');
            const spinner = document.getElementById('liveFilterSpinner');
            const tableBody = document.getElementById('logsTableBody');
            const paginationLinks = document.getElementById('logsPaginationLinks');
            const paginationSummary = document.getElementById('logsPaginationSummary');
            const exportBtn = document.getElementById('exportCsvBtn');

            if (spinner) spinner.classList.remove('hidden');
            if (tableBody) tableBody.style.opacity = '0.45';

            // Abort previous in-flight request if still running
            if (currentLiveAbortController) {
                currentLiveAbortController.abort();
            }
            currentLiveAbortController = new AbortController();

            const search = searchInput ? searchInput.value.trim() : '';
            let dept = deptSelect ? deptSelect.value : 'all';
            const mod = modSelect ? modSelect.value : 'all';
            const sev = sevSelect ? sevSelect.value : 'all';

            // If header department selector is used and table dept is 'all', sync them
            if (dept === 'all' && headerDeptSelect && headerDeptSelect.value !== 'all') {
                dept = headerDeptSelect.value;
            }

            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (dept && dept !== 'all') params.set('department', dept);
            if (mod && mod !== 'all') params.set('module', mod);
            if (sev && sev !== 'all') params.set('severity', sev);
            if (page && page > 1) params.set('page', page);

            // Fetch live data via AJAX
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
                    // Update table rows
                    if (tableBody) {
                        tableBody.innerHTML = data.table_html;
                    }

                    // Update pagination controls
                    if (paginationLinks) {
                        paginationLinks.innerHTML = data.pagination_html || '';
                    }

                    // Update pagination summary counter
                    if (paginationSummary) {
                        paginationSummary.innerText = `Showing ${data.first_item} to ${data.last_item} of ${data.total} individual log reports (10 per page)`;
                    }

                    // Sync Export CSV link URL
                    if (exportBtn) {
                        exportBtn.href = `{{ route('activity_logs.export') }}?${params.toString()}`;
                    }

                    // Update browser history URL without page reload
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
            const modSelect = document.getElementById('filterModule');
            const sevSelect = document.getElementById('filterSeverity');

            if (searchInput) searchInput.value = '';
            if (deptSelect) deptSelect.value = 'all';
            if (modSelect) modSelect.value = 'all';
            if (sevSelect) sevSelect.value = 'all';

            fetchLiveLogs(1);
        }

        // Department filter redirection
        function filterByDepartment(deptCode) {
            const deptSelect = document.getElementById('filterDepartment');
            if (deptSelect) {
                deptSelect.value = deptCode;
            }
            fetchLiveLogs(1);
        }

        // Time Range filter redirection
        function setTimeRange(days) {
            const url = new URL(window.location.href);
            url.searchParams.set('date_range', days);
            url.searchParams.set('page', 1);
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
                btn.className = 'px-2 py-0.5 rounded-md transition-all hover:text-gray-900 text-gray-600 font-semibold';
            });

            if (seconds === 0) {
                localStorage.removeItem('activity_log_auto_refresh');
                btnOff.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-gray-900 shadow-xs font-bold';
                countdownEl.classList.add('hidden');
                return;
            }

            localStorage.setItem('activity_log_auto_refresh', seconds);
            if (seconds === 10) {
                btn10.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-indigo-700 shadow-xs font-bold';
            } else if (seconds === 30) {
                btn30.className = 'px-2 py-0.5 rounded-md transition-all bg-white text-indigo-700 shadow-xs font-bold';
            }

            secondsLeft = seconds;
            countdownEl.classList.remove('hidden');
            countdownEl.innerText = `Refreshing in ${secondsLeft}s...`;

            countdownTimer = setInterval(() => {
                secondsLeft--;
                if (secondsLeft > 0) {
                    countdownEl.innerText = `Refreshing in ${secondsLeft}s...`;
                } else {
                    countdownEl.innerText = `Refreshing now...`;
                }
            }, 1000);

            autoRefreshTimer = setInterval(() => {
                fetchLiveLogs();
            }, seconds * 1000);
        }

        // Modal inspector handlers
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
                }
            } catch (err) {
                console.error(err);
            }
        }

        function closeLogDetailsModal() {
            document.getElementById('logDetailModal').classList.add('hidden');
        }

        function copyPayload() {
            const text = document.getElementById('modalPayload').innerText;
            navigator.clipboard.writeText(text);
            alert('Log payload copied to clipboard!');
        }
    </script>
</body>
</html>
