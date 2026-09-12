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

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id');
    }

    // Accessors to ensure old views still work
    public function getRoleAttribute()
    {
        return $this->profile->role->name ?? null;
    }

    public function getFirstNameAttribute()
    {
        return $this->profile->first_name ? ucwords(strtolower($this->profile->first_name)) : null;
    }

    public function getLastNameAttribute()
    {
        return $this->profile->last_name ? ucwords(strtolower($this->profile->last_name)) : null;
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
}
