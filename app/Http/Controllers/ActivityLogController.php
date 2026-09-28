<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    /**
     * Display the Activity & Audit Monitoring Dashboard.
     */
    public function index(Request $request)
    {
        $selectedDepartment = $request->query('department', 'all');
        $selectedModule = $request->query('module', 'all');
        $selectedSeverity = $request->query('severity', 'all');
        $selectedRole = $request->query('role', 'all');
        $selectedDateRange = $request->query('date_range', '30');
        $searchTerm = $request->query('search', '');
        $perPage = (int)$request->query('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) $perPage = 10;

        // Base query for individual logs table
        $query = ActivityLog::with(['user.profile', 'department.school'])
            ->orderBy('created_at', 'desc');

        if ($selectedDepartment !== 'all' && !empty($selectedDepartment)) {
            $query->where('department_id', $selectedDepartment);
        }

        if ($selectedModule !== 'all' && !empty($selectedModule)) {
            $query->where('module', $selectedModule);
        }

        if ($selectedSeverity !== 'all' && !empty($selectedSeverity)) {
            $query->where('severity', $selectedSeverity);
        }

        if ($selectedRole !== 'all' && !empty($selectedRole)) {
            $query->where('user_role', $selectedRole);
        }

        if ($selectedDateRange !== 'all') {
            $days = (int)$selectedDateRange;
            if ($days > 0) {
                $query->where('created_at', '>=', Carbon::now()->subDays($days)->startOfDay());
            }
        }

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('action_title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('entity_name', 'like', "%{$searchTerm}%")
                  ->orWhere('ip_address', 'like', "%{$searchTerm}%")
                  ->orWhere('action', 'like', "%{$searchTerm}%");
            });
        }

        // Paginate individual logs (10 per page by default)
        $logs = $query->paginate($perPage)->withQueryString();

        // All departments for dropdown & cards
        $departments = Department::with('school')->get();
        $deptMap = $departments->keyBy('code');

        // Global KPI metrics
        $kpiQuery = ActivityLog::query();
        if ($selectedDepartment !== 'all') {
            $kpiQuery->where('department_id', $selectedDepartment);
        }
        $totalLogsCount = (clone $kpiQuery)->count();
        $totalActiveDepartments = ActivityLog::distinct('department_id')->whereNotNull('department_id')->count('department_id');
        $totalActiveUsers = (clone $kpiQuery)->distinct('user_name')->count('user_name');
        
        $todayLogsCount = (clone $kpiQuery)->where('created_at', '>=', Carbon::today())->count();
        $yesterdayLogsCount = (clone $kpiQuery)->whereBetween('created_at', [Carbon::yesterday(), Carbon::today()])->count();
        $todayTrend = $yesterdayLogsCount > 0 
            ? round((($todayLogsCount - $yesterdayLogsCount) / $yesterdayLogsCount) * 100, 1) 
            : ($todayLogsCount > 0 ? 100 : 0);

        $severityCounts = (clone $kpiQuery)
            ->select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        $successCount = $severityCounts['success'] ?? 0;
        $infoCount = $severityCounts['info'] ?? 0;
        $warningCount = $severityCounts['warning'] ?? 0;
        $dangerCount = $severityCounts['danger'] ?? 0;

        // --- 1. Timeline Chart (Dynamic based on selectedDateRange) ---
        $timelineDays = in_array($selectedDateRange, ['7', '14', '30']) ? (int)$selectedDateRange : 14;
        $timelineLabels = [];
        $timelineData = [];
        $timelineWarningData = [];

        for ($i = $timelineDays - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $timelineLabels[] = $date->format('M d');

            $dayLogQuery = ActivityLog::whereDate('created_at', $dateStr);
            if ($selectedDepartment !== 'all') {
                $dayLogQuery->where('department_id', $selectedDepartment);
            }

            $dayLogs = (clone $dayLogQuery)->get();
            $timelineData[] = $dayLogs->count();
            $timelineWarningData[] = $dayLogs->whereIn('severity', ['warning', 'danger'])->count();
        }

        // --- 2. Department Breakdown Pie / Doughnut Chart (Top 7 + Others for Clean Legibility) ---
        $deptCountsRaw = ActivityLog::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->orderBy('count', 'desc')
            ->get();

        $pieLabels = [];
        $pieData = [];
        $curatedColors = [
            '#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', 
            '#8b5cf6', '#3b82f6', '#94a3b8'
        ];
        $pieColors = [];

        $topThreshold = 7;
        $otherCount = 0;
        $otherDeptsTotal = 0;

        foreach ($deptCountsRaw as $index => $item) {
            $dCode = $item->department_id;
            $dName = isset($deptMap[$dCode]) ? $deptMap[$dCode]->name : ($dCode ? strtoupper(str_replace('_', ' ', $dCode)) : 'General / Central');

            if ($index < $topThreshold) {
                $pieLabels[] = $dName;
                $pieData[] = $item->count;
                $pieColors[] = $curatedColors[$index % count($curatedColors)];
            } else {
                $otherCount++;
                $otherDeptsTotal += $item->count;
            }
        }

        if ($otherDeptsTotal > 0) {
            $pieLabels[] = "Other Departments ({$otherCount})";
            $pieData[] = $otherDeptsTotal;
            $pieColors[] = '#94a3b8';
        }

        // If empty
        if (empty($pieLabels)) {
            $pieLabels = ['No Data'];
            $pieData = [0];
            $pieColors = ['#e2e8f0'];
        }

        // Fetch recent critical alerts for incident banner
        $recentCriticalLogs = ActivityLog::with('department')
            ->whereIn('severity', ['warning', 'danger'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // --- 3. Module Distribution Bar Chart ---
        $moduleQuery = ActivityLog::select('module', DB::raw('count(*) as count'));
        if ($selectedDepartment !== 'all') {
            $moduleQuery->where('department_id', $selectedDepartment);
        }
        $moduleStats = $moduleQuery->groupBy('module')
            ->orderBy('count', 'desc')
            ->pluck('count', 'module')
            ->toArray();

        $moduleLabels = array_keys($moduleStats);
        $moduleData = array_values($moduleStats);

        // --- 4. Role Breakdown Donut Chart ---
        $roleQuery = ActivityLog::select('user_role', DB::raw('count(*) as count'));
        if ($selectedDepartment !== 'all') {
            $roleQuery->where('department_id', $selectedDepartment);
        }
        $roleStats = $roleQuery->groupBy('user_role')
            ->pluck('count', 'user_role')
            ->toArray();

        $roleLabelsMap = [
            'sa' => 'Super Admin',
            'admin' => 'Dept Coordinator',
            'sta' => 'Faculty / Staff',
            'staff' => 'Faculty / Staff',
            'stu' => 'Student',
            'student' => 'Student',
            'civil_admin' => 'Civil Admin',
        ];

        $roleLabels = [];
        $roleData = [];
        foreach ($roleStats as $rKey => $rCount) {
            $roleLabels[] = $roleLabelsMap[$rKey] ?? ucfirst($rKey ?? 'Unknown');
            $roleData[] = $rCount;
        }

        // --- 5. Hourly Activity Distribution (24h) ---
        $hourlyData = array_fill(0, 24, 0);
        $hourlyLogs = ActivityLog::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->when($selectedDepartment !== 'all', fn($q) => $q->where('department_id', $selectedDepartment))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->pluck('count', 'hour')
            ->toArray();

        foreach ($hourlyLogs as $hr => $cnt) {
            $hourlyData[(int)$hr] = $cnt;
        }

        // Department Summary Cards (for quick switching and stats)
        $departmentSummaries = [];
        foreach ($departments as $dept) {
            $dLogs = ActivityLog::where('department_id', $dept->code)->get();
            $dCount = $dLogs->count();
            $dUniqueUsers = $dLogs->pluck('user_name')->unique()->filter()->count();
            $dLatest = $dLogs->first();
            $dWarnings = $dLogs->whereIn('severity', ['warning', 'danger'])->count();

            $departmentSummaries[] = [
                'code' => $dept->code,
                'name' => $dept->name,
                'school_name' => $dept->school->name ?? 'Core School',
                'total_logs' => $dCount,
                'unique_users' => $dUniqueUsers,
                'warnings_count' => $dWarnings,
                'latest_activity' => $dLatest ? $dLatest->created_at->diffForHumans() : 'No activity',
            ];
        }

        // Sort departments by total logs descending
        usort($departmentSummaries, fn($a, $b) => $b['total_logs'] <=> $a['total_logs']);

        $modulesList = ['Academics', 'Assignments', 'Materials', 'Students', 'Authentication', 'Civil Services', 'Security'];

        return view('activity_logs.index', compact(
            'logs',
            'departments',
            'departmentSummaries',
            'selectedDepartment',
            'selectedModule',
            'selectedSeverity',
            'selectedRole',
            'selectedDateRange',
            'searchTerm',
            'perPage',
            'totalLogsCount',
            'totalActiveDepartments',
            'totalActiveUsers',
            'todayLogsCount',
            'todayTrend',
            'successCount',
            'infoCount',
            'warningCount',
            'dangerCount',
            'timelineLabels',
            'timelineData',
            'timelineWarningData',
            'pieLabels',
            'pieData',
            'pieColors',
            'moduleLabels',
            'moduleData',
            'roleLabels',
            'roleData',
            'hourlyData',
            'modulesList',
            'recentCriticalLogs'
        ));
    }

    /**
     * Show detailed individual log report (AJAX or modal payload).
     */
    public function show($id)
    {
        $log = ActivityLog::with(['user.profile', 'department.school'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'log' => [
                'id' => $log->id,
                'action' => $log->action,
                'action_title' => $log->action_title,
                'module' => $log->module,
                'severity' => $log->severity,
                'severity_color' => $log->severity_color,
                'description' => $log->description,
                'user_name' => $log->user_name,
                'user_role' => $log->user_role,
                'user_email' => $log->user?->profile?->email ?? ($log->user?->email ?? 'N/A'),
                'department_name' => $log->department->name ?? ($log->department_id ? strtoupper(str_replace('_', ' ', $log->department_id)) : 'Central / System'),
                'school_name' => $log->department->school->name ?? ($log->school->name ?? 'N/A'),
                'entity_type' => $log->entity_type,
                'entity_id' => $log->entity_id,
                'entity_name' => $log->entity_name,
                'ip_address' => $log->ip_address,
                'user_agent' => $log->user_agent,
                'method' => $log->method,
                'url' => $log->url,
                'payload' => $log->payload,
                'created_at_formatted' => $log->created_at->format('M d, Y - h:i:s A'),
                'relative_time' => $log->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Export logs to CSV file.
     */
    public function export(Request $request): StreamedResponse
    {
        $selectedDepartment = $request->query('department', 'all');
        $selectedModule = $request->query('module', 'all');
        $selectedSeverity = $request->query('severity', 'all');
        $searchTerm = $request->query('search', '');

        $query = ActivityLog::with(['user.profile', 'department.school'])
            ->orderBy('created_at', 'desc');

        if ($selectedDepartment !== 'all' && !empty($selectedDepartment)) {
            $query->where('department_id', $selectedDepartment);
        }
        if ($selectedModule !== 'all' && !empty($selectedModule)) {
            $query->where('module', $selectedModule);
        }
        if ($selectedSeverity !== 'all' && !empty($selectedSeverity)) {
            $query->where('severity', $selectedSeverity);
        }
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('action_title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $logs = $query->limit(5000)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="activity_audit_logs_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return response()->stream(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($handle, [
                'Log ID',
                'Timestamp',
                'Department',
                'School',
                'User / Actor',
                'Role',
                'Module',
                'Action Title',
                'Action Code',
                'Severity',
                'Description',
                'Entity Type',
                'Entity Name',
                'IP Address',
                'Method',
                'URL'
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->department->name ?? ($log->department_id ?? 'Central/Global'),
                    $log->department->school->name ?? ($log->school->name ?? 'N/A'),
                    $log->user_name,
                    $log->user_role,
                    $log->module,
                    $log->action_title,
                    $log->action,
                    strtoupper($log->severity),
                    $log->description,
                    $log->entity_type,
                    $log->entity_name,
                    $log->ip_address,
                    $log->method,
                    $log->url
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
