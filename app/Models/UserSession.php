<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $table = 'user_sessions';

    protected $fillable = [
        'session_id',
        'user_id',
        'department_id',
        'school_id',
        'user_name',
        'user_role',
        'ip_address',
        'user_agent',
        'login_at',
        'last_activity_at',
        'logout_at',
        'duration_seconds',
        'actions_count',
        'status',
    ];

    protected $casts = [
        'login_at'         => 'datetime',
        'last_activity_at' => 'datetime',
        'logout_at'        => 'datetime',
        'duration_seconds' => 'integer',
        'actions_count'    => 'integer',
    ];

    /**
     * User relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Department relationship (mapped by department code e.g. 'dep_it', 'dep_cse').
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'code');
    }

    /**
     * School relationship.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'code');
    }

    /**
     * Scope for currently online sessions (active within last 15 minutes).
     */
    public function scopeOnline($query)
    {
        return $query->where('status', 'active')
            ->where('last_activity_at', '>=', Carbon::now()->subMinutes(15));
    }

    /**
     * Check if session is currently active and online.
     */
    public function getIsOnlineAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return $this->last_activity_at && $this->last_activity_at->gt(Carbon::now()->subMinutes(15));
    }

    /**
     * Real-time computed duration in seconds (if active, calculates from login until now).
     */
    public function getActiveDurationSecondsAttribute(): int
    {
        if ($this->is_online && $this->login_at) {
            return max(0, (int)$this->login_at->diffInSeconds(Carbon::now()));
        }

        return (int)$this->duration_seconds;
    }

    /**
     * Human-readable formatted session duration string.
     */
    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->active_duration_seconds;
        return self::formatDuration($seconds);
    }

    /**
     * Reusable static formatter for duration in seconds to human-readable string.
     */
    public static function formatDuration(int|float|null $seconds): string
    {
        $seconds = max(0, (int)$seconds);

        if ($seconds < 60) {
            return $seconds . 's';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }

        return $remainingSeconds > 0 ? "{$minutes}m {$remainingSeconds}s" : "{$minutes}m";
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_online) {
            return 'Active Now';
        }

        if ($this->status === 'logged_out') {
            return 'Logged Out';
        }

        return 'Timed Out / Inactive';
    }

    /**
     * Status badge styling.
     */
    public function getStatusBadgeAttribute(): array
    {
        if ($this->is_online) {
            return [
                'bg'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot'    => 'bg-emerald-500 animate-pulse',
                'text'   => 'Active Now',
                'is_live'=> true,
            ];
        }

        if ($this->status === 'logged_out') {
            return [
                'bg'     => 'bg-gray-100 text-gray-700 border-gray-200',
                'dot'    => 'bg-gray-400',
                'text'   => 'Logged Out',
                'is_live'=> false,
            ];
        }

        return [
            'bg'     => 'bg-amber-50 text-amber-700 border-amber-200',
            'dot'    => 'bg-amber-400',
            'text'   => 'Inactive / Expired',
            'is_live'=> false,
        ];
    }

    /**
     * Formatted role label and badge.
     */
    public function getRoleLabelAttribute(): string
    {
        $roleMap = [
            'sa'          => 'Super Admin',
            'admin'       => 'Dept Coordinator',
            'sta'         => 'Faculty / Staff',
            'staff'       => 'Faculty / Staff',
            'stu'         => 'Student',
            'student'     => 'Student',
            'civil_admin' => 'Civil Admin',
        ];

        return $roleMap[$this->user_role] ?? ucfirst($this->user_role ?? 'User');
    }
}

