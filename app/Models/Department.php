<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'school_id', 'name'];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'code');
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'departments_id', 'code');
    }
}
