<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'staff_id',
        'title',
        'description',
        'max_marks',
        'due_date',
        'attachment_path',
        'status',
        'type',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'max_marks' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function questions()
    {
        return $this->hasMany(AssignmentQuestion::class)->orderBy('order', 'asc');
    }

    public function assignedStudents()
    {
        return $this->belongsToMany(User::class, 'assignment_students', 'assignment_id', 'student_id')
                    ->withTimestamps();
    }

    public function isPublic(): bool
    {
        return ($this->type ?? 'public') === 'public';
    }

    public function isPrivate(): bool
    {
        return $this->type === 'private';
    }

    public function isAccessibleBy(User $user): bool
    {
        if ($this->isPublic()) {
            return true;
        }
        return $this->assignedStudents()->where('users.id', $user->id)->exists();
    }

    public function recalculateMaxMarks(): int
    {
        $total = $this->questions()->sum('marks');
        if ($total > 0 && $total !== $this->max_marks) {
            $this->update(['max_marks' => $total]);
        }
        return $total > 0 ? $total : $this->max_marks;
    }

    public function isPastDue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }
}
