<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function departments()
    {
        return $this->hasMany(Department::class, 'school_id', 'code');
    }
}
