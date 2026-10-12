<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'staff_id',
        'type',
        'platform',
        'division',
        'title',
        'url_or_path',
    ];

    public const DIVISIONS = [
        'Course objective and outcomes',
        'Syllabus document',
        'PPTs',
        'Lecture notes',
        'Lab manual',
        'Assigment Questions',
        'Question Bank',
        'Gate Questions',
        'Previous Question Papers',
        'Videos',
        'Additional material',
    ];

    public function getEffectiveDivisionAttribute(): string
    {
        if (!empty($this->division)) {
            // Normalize case / trimmed matching
            foreach (self::DIVISIONS as $div) {
                if (strcasecmp(trim($this->division), trim($div)) === 0) {
                    return $div;
                }
            }
            return $this->division;
        }

        $p = strtolower(trim($this->platform ?? ''));
        $t = strtolower(trim($this->title ?? ''));

        if (str_contains($t, 'objective') || str_contains($t, 'outcome') || str_contains($t, 'co-po')) {
            return 'Course objective and outcomes';
        }
        if (str_contains($t, 'syllabus') || str_contains($t, 'curriculum')) {
            return 'Syllabus document';
        }
        if (in_array($p, ['ppt', 'pptx', 'pps', 'ppsx', 'presentation', 'slides']) || str_contains($t, 'ppt') || str_contains($t, 'slide')) {
            return 'PPTs';
        }
        if (str_contains($t, 'lab') || str_contains($t, 'manual') || str_contains($t, 'experiment')) {
            return 'Lab manual';
        }
        if (str_contains($t, 'assignment') || str_contains($t, 'assigment')) {
            return 'Assigment Questions';
        }
        if (str_contains($t, 'question bank') || str_contains($t, 'qbank') || str_contains($t, 'bank')) {
            return 'Question Bank';
        }
        if (str_contains($t, 'gate')) {
            return 'Gate Questions';
        }
        if (str_contains($t, 'previous') || str_contains($t, 'qp') || str_contains($t, 'past paper') || str_contains($t, 'old paper')) {
            return 'Previous Question Papers';
        }
        if (in_array($p, ['youtube', 'zoom', 'video', 'vimeo']) || str_contains($t, 'video') || str_contains($t, 'lecture video')) {
            return 'Videos';
        }
        if (str_contains($t, 'note') || in_array($p, ['pdf', 'word', 'doc', 'docx'])) {
            return 'Lecture notes';
        }

        return 'Additional material';
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    protected static function booted()
    {
        static::saved(function ($material) {
            \App\Services\CacheService::invalidateCourseMaterials($material->course_id);
        });
        static::deleted(function ($material) {
            \App\Services\CacheService::invalidateCourseMaterials($material->course_id);
        });
    }
}
