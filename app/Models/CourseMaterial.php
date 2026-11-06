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
        $fileType = $this->detected_file_type;

        if (str_contains($t, 'objective') || str_contains($t, 'outcome') || str_contains($t, 'co-po')) {
            return 'Course objective and outcomes';
        }
        if (str_contains($t, 'syllabus') || str_contains($t, 'curriculum')) {
            return 'Syllabus document';
        }
        if (in_array($p, ['ppt', 'pptx', 'pps', 'ppsx', 'presentation', 'slides']) || $fileType === 'pptx' || str_contains($t, 'ppt') || str_contains($t, 'slide')) {
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
        if (in_array($p, ['youtube', 'zoom', 'video', 'vimeo']) || $fileType === 'video' || str_contains($t, 'video') || str_contains($t, 'lecture video')) {
            return 'Videos';
        }
        if (str_contains($t, 'note') || in_array($p, ['pdf', 'word', 'doc', 'docx']) || in_array($fileType, ['docx', 'pdf'])) {
            return 'Lecture notes';
        }

        return 'Additional material';
    }

    public function getDetectedFileTypeAttribute(): ?string
    {
        if ($this->type === 'file' && !empty($this->url_or_path)) {
            $ext = strtolower(pathinfo($this->url_or_path, PATHINFO_EXTENSION));
            return $ext ?: null;
        }

        if ($this->type === 'link' && !empty($this->url_or_path)) {
            $url = urldecode($this->url_or_path);
            if (preg_match('/(?::[pP]:|\/p\/|\.pptx?(?:$|[?&#]))/i', $url)) {
                return 'pptx';
            }
            if (preg_match('/(?::[wW]:|\/w\/|\.docx?(?:$|[?&#]))/i', $url)) {
                return 'docx';
            }
            if (preg_match('/(?::[xX]:|\/x\/|\.xlsx?(?:$|[?&#]))/i', $url)) {
                return 'xlsx';
            }
            if (preg_match('/(?::[bB]:|\/b\/|\.pdf(?:$|[?&#]))/i', $url)) {
                return 'pdf';
            }
            if (preg_match('/(?::[vV]:|\/v\/|\.mp4(?:$|[?&#]))/i', $url) || str_contains($url, 'stream.office.com')) {
                return 'video';
            }
        }

        return null;
    }

    public function getIsOfficeLinkAttribute(): bool
    {
        if ($this->type !== 'link' || empty($this->url_or_path)) {
            return false;
        }
        $host = strtolower(parse_url($this->url_or_path, PHP_URL_HOST) ?? '');
        return str_contains($host, 'sharepoint.com') || str_contains($host, 'onedrive.live.com') || str_contains($host, '1drv.ms') || in_array(strtolower($this->platform ?? ''), ['onedrive', 'sharepoint']);
    }

    public function getOfficePlatformAttribute(): ?string
    {
        if (!$this->is_office_link) {
            return null;
        }
        $host = strtolower(parse_url($this->url_or_path, PHP_URL_HOST) ?? '');
        if (str_contains($host, 'onedrive.live.com') || str_contains($host, '1drv.ms') || str_contains($host, '-my.sharepoint.com') || strtolower($this->platform ?? '') === 'onedrive') {
            return 'onedrive';
        }
        if (str_contains($host, 'sharepoint.com') || strtolower($this->platform ?? '') === 'sharepoint') {
            return 'sharepoint';
        }
        return 'sharepoint';
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
