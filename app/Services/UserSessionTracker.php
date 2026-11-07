<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserSessionTracker
{
    /**
     * Track an incoming authenticated request and update or create session record.
     */
    public static function trackRequest(Request $request): ?UserSession
    {
        try {
            if (!Auth::check()) {
                return null;
            }

            $user = Auth::user();
            if (!$user) {
                return null;
            }

            $sessionId = $request->session()->getId();
            if (!$sessionId) {
                return null;
            }

            $userSession = UserSession::where('session_id', $sessionId)
                ->where('user_id', $user->id)
                ->first();

            $now = Carbon::now();

            if (!$userSession) {
                // Initialize profile details
                $profile = $user->profile;
                $userName = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
                if (empty($userName)) {
                    $userName = $user->username;
                }
                $userRole = $user->role ?? ($profile->roles_id ?? 'user');
                $departmentId = $profile->departments_id ?? null;
                $schoolId = $profile->schools_id ?? null;

                $userSession = UserSession::create([
                    'session_id'       => $sessionId,
                    'user_id'          => $user->id,
                    'user_name'        => $userName,
                    'user_role'        => $userRole,
                    'department_id'    => $departmentId,
                    'school_id'        => $schoolId,
                    'ip_address'       => $request->ip() ?: '127.0.0.1',
                    'user_agent'       => substr($request->userAgent() ?? '', 0, 500),
                    'login_at'         => $now,
                    'last_activity_at' => $now,
                    'logout_at'        => null,
                    'duration_seconds' => 0,
                    'actions_count'    => 1,
                    'status'           => 'active',
                ]);
            } else {
                $loginAt = $userSession->login_at ?? $userSession->created_at ?? $now;
                $duration = max(0, (int)$loginAt->diffInSeconds($now));

                $userSession->last_activity_at = $now;
                $userSession->duration_seconds = $duration;
                $userSession->actions_count    = (int)$userSession->actions_count + 1;
                if ($userSession->status === 'logged_out') {
                    $userSession->status = 'active';
                    $userSession->logout_at = null;
                }
                $userSession->ip_address = $request->ip() ?: $userSession->ip_address;
                $userSession->save();
            }

            return $userSession;
        } catch (\Throwable $e) {
            Log::warning('UserSessionTracker trackRequest error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mark a session as logged out and freeze duration.
     */
    public static function endSession(string $sessionId, ?int $userId = null): ?UserSession
    {
        try {
            if (empty($sessionId)) {
                return null;
            }

            $query = UserSession::where('session_id', $sessionId);
            if ($userId) {
                $query->where('user_id', $userId);
            }

            $session = $query->latest('id')->first();
            if ($session) {
                $now = Carbon::now();
                $loginAt = $session->login_at ?? $session->created_at ?? $now;
                $duration = max(0, (int)$loginAt->diffInSeconds($now));

                $session->logout_at = $now;
                $session->last_activity_at = $now;
                $session->duration_seconds = $duration;
                $session->status = 'logged_out';
                $session->save();
            }

            return $session;
        } catch (\Throwable $e) {
            Log::warning('UserSessionTracker endSession error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mark expired/idle sessions as timed out.
     */
    public static function cleanupTimedOutSessions(int $idleMinutes = 120): int
    {
        try {
            $threshold = Carbon::now()->subMinutes($idleMinutes);
            return UserSession::where('status', 'active')
                ->where('last_activity_at', '<', $threshold)
                ->update(['status' => 'timed_out']);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Backfill realistic historical sessions from existing activity logs.
     */
    public static function backfillFromActivityLogs(): int
    {
        try {
            if (UserSession::count() > 0) {
                return 0; // Already seeded / populated
            }

            // Find distinct users in activity logs
            $userIds = ActivityLog::whereNotNull('user_id')->distinct()->pluck('user_id');
            $createdCount = 0;

            foreach ($userIds as $uid) {
                $logs = ActivityLog::where('user_id', $uid)
                    ->orderBy('created_at', 'asc')
                    ->get();

                if ($logs->isEmpty()) {
                    continue;
                }

                // Group consecutive logs within 2 hours into one session
                $currentSession = null;

                foreach ($logs as $log) {
                    $logTime = $log->created_at;

                    if ($currentSession === null) {
                        $currentSession = [
                            'user_id'          => $log->user_id,
                            'user_name'        => $log->user_name,
                            'user_role'        => $log->user_role,
                            'department_id'    => $log->department_id,
                            'school_id'        => $log->school_id,
                            'ip_address'       => $log->ip_address,
                            'user_agent'       => $log->user_agent,
                            'login_at'         => $logTime,
                            'last_activity_at' => $logTime,
                            'logout_at'        => null,
                            'duration_seconds' => 60,
                            'actions_count'    => 1,
                            'status'           => 'timed_out',
                        ];
                    } else {
                        // Check if this log is within 2 hours of current session's last activity
                        $diffMinutes = $currentSession['last_activity_at']->diffInMinutes($logTime);

                        if ($diffMinutes <= 120 && $log->action !== 'user_login') {
                            // Part of the same session
                            $currentSession['last_activity_at'] = $logTime;
                            $currentSession['actions_count'] += 1;
                            $diffSec = max(60, (int)$currentSession['login_at']->diffInSeconds($logTime));
                            $currentSession['duration_seconds'] = $diffSec;

                            if ($log->action === 'user_logout') {
                                $currentSession['logout_at'] = $logTime;
                                $currentSession['status'] = 'logged_out';
                                // Commit session
                                UserSession::create($currentSession);
                                $createdCount++;
                                $currentSession = null;
                            }
                        } else {
                            // Previous session finished, save it
                            UserSession::create($currentSession);
                            $createdCount++;

                            // Start a new session
                            $currentSession = [
                                'user_id'          => $log->user_id,
                                'user_name'        => $log->user_name,
                                'user_role'        => $log->user_role,
                                'department_id'    => $log->department_id,
                                'school_id'        => $log->school_id,
                                'ip_address'       => $log->ip_address,
                                'user_agent'       => $log->user_agent,
                                'login_at'         => $logTime,
                                'last_activity_at' => $logTime,
                                'logout_at'        => $log->action === 'user_logout' ? $logTime : null,
                                'duration_seconds' => 60,
                                'actions_count'    => 1,
                                'status'           => $log->action === 'user_logout' ? 'logged_out' : 'timed_out',
                            ];

                            if ($log->action === 'user_logout') {
                                UserSession::create($currentSession);
                                $createdCount++;
                                $currentSession = null;
                            }
                        }
                    }
                }

                if ($currentSession !== null) {
                    UserSession::create($currentSession);
                    $createdCount++;
                }
            }

            return $createdCount;
        } catch (\Throwable $e) {
            Log::warning('Backfill error: ' . $e->getMessage());
            return 0;
        }
    }
}

