<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'target_role',
        'status',
        'total_rows',
        'processed_rows',
        'uploaded_by',
        'batch_id',
        'error_message'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
