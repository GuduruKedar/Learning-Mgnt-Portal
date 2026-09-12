<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = ['regulation_id', 'department_id', 'code', 'name', 'year', 'semester'];

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'code');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'course_staff', 'course_id', 'staff_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function enrollments()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id');
    }
}
