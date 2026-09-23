<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'school_id',
        'user_name',
        'user_role',
        'action',
        'action_title',
        'module',
        'severity',
        'description',
        'entity_type',
        'entity_id',
        'entity_name',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'code');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'code');
    }

    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'success' => 'emerald',
            'warning' => 'amber',
            'danger'  => 'rose',
            default   => 'indigo',
        };
    }

    public function getModuleBadgeAttribute(): string
    {
        return match (strtolower($this->module)) {
            'authentication', 'auth', 'security' => 'bg-purple-100 text-purple-700 border-purple-200',
            'academics', 'courses', 'regulations' => 'bg-blue-100 text-blue-700 border-blue-200',
            'assignments' => 'bg-amber-100 text-amber-700 border-amber-200',
            'materials'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'students', 'enrollment' => 'bg-sky-100 text-sky-700 border-sky-200',
            'faculty', 'staff' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'civil services' => 'bg-orange-100 text-orange-700 border-orange-200',
            default       => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
