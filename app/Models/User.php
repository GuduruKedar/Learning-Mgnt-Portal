<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'profile_id',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_staff', 'staff_id', 'course_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'staff_id');
    }

    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterial::class, 'staff_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id');
    }

    public function civilServiceEnrollment()
    {
        return $this->hasOne(CivilServiceEnrollment::class, 'user_id');
    }

    public function isCivilServicesEnrolled()
    {
        return $this->civilServiceEnrollment()->where('status', 'active')->exists();
    }

    // Fast session-cached accessors to eliminate redundant database queries on every request
    public function getRoleAttribute()
    {
        // 1. If checking the currently authenticated user and role is stored in session, return instantly (0 SQL queries)
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && session()->has('auth_user_role')) {
            return session('auth_user_role');
        }

        // 2. Read role directly from profile (roles_id holds the role name, avoiding a secondary join to roles table)
        $role = $this->profile->roles_id ?? ($this->profile->role->name ?? null);

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && $role) {
            session(['auth_user_role' => $role]);
        }

        return $role;
    }

    public function getFirstNameAttribute()
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && session()->has('auth_user_first_name')) {
            return session('auth_user_first_name');
        }

        $fname = $this->profile->first_name ?? null;
        $formatted = $fname ? ucwords(strtolower($fname)) : null;

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && $formatted) {
            session(['auth_user_first_name' => $formatted]);
        }

        return $formatted;
    }

    public function getLastNameAttribute()
    {
        return $this->profile->last_name ? ucwords(strtolower($this->profile->last_name)) : null;
    }

    public function getFullNameAttribute()
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && session()->has('auth_user_name')) {
            return session('auth_user_name');
        }

        if ($this->role === 'sa') {
            $name = 'Super Admin';
        } else {
            $full = trim(($this->profile->first_name ?? '') . ' ' . ($this->profile->last_name ?? ''));
            $name = !empty($full) ? ucwords(strtolower($full)) : ($this->username ?? 'User');
        }

        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::id() === $this->id && $name) {
            session(['auth_user_name' => $name]);
        }

        return $name;
    }

    public function getEmailAttribute()
    {
        return $this->profile->email ?? null;
    }

    public function getPhotoAttribute()
    {
        return $this->profile->photo ?? null;
    }

    public function getDesignationAttribute()
    {
        return $this->profile->designation ?? null;
    }

    public function getSchoolAttribute()
    {
        // For backwards compatibility in views if they do $user->school
        return $this->profile->school ?? null;
    }

    public function getDepartmentAttribute()
    {
        // For backwards compatibility in views if they do $user->department
        return $this->profile->department ?? null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeRole($query, $roleName)
    {
        return $query->whereHas('profile.role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    protected static function booted()
    {
        static::saved(function ($user) {
            \App\Services\CacheService::invalidateSuperAdminStats();
            \App\Services\CacheService::invalidateSshAdminStats();
            if ($user->profile?->departments_id) {
                \App\Services\CacheService::invalidateCoordinatorStats($user->profile->departments_id);
            }
        });

        static::deleted(function ($user) {
            \App\Services\CacheService::invalidateSuperAdminStats();
            \App\Services\CacheService::invalidateSshAdminStats();
            if ($user->profile?->departments_id) {
                \App\Services\CacheService::invalidateCoordinatorStats($user->profile->departments_id);
            }
        });
    }
}
