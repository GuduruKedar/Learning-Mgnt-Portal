<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function getFirstNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : null;
    }

    public function getLastNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : null;
    }

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'schools_id',
        'departments_id',
        'email',
        'phone',
        'roles_id',
        'designation',
        'photo',
        'level',
        'programs_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id', 'name');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'schools_id', 'code');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'code');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'programs_id', 'code');
    }
}
