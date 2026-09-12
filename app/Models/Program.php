<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = ['department_id', 'code', 'name', 'level', 'duration_years'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
