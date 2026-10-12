<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'program_type',
        'code',
        'name',
        'curriculum',
        'status'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    protected static function booted()
    {
        static::saved(function () {
            \App\Services\CacheService::invalidateRegulations();
        });

        static::deleting(function ($regulation) {
            $coursesCount = $regulation->courses()->count();
            if ($coursesCount > 0) {
                throw new \Exception("Cannot delete regulation: You have not deleted its {$coursesCount} course(s) yet. Please un-enroll students and delete all associated courses first before removing this regulation.");
            }
        });

        static::deleted(function () {
            \App\Services\CacheService::invalidateRegulations();
        });
    }
}
