<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'question_id',
        'selected_option',
        'is_correct',
        'marks_awarded',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'marks_awarded' => 'integer',
    ];

    public function submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    public function question()
    {
        return $this->belongsTo(AssignmentQuestion::class, 'question_id');
    }
}
