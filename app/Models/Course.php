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

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    protected static function booted()
    {
        static::saved(function ($course) {
            \App\Services\CacheService::invalidateCourses($course->department_id);
            \App\Services\CacheService::invalidateCourseAllocations($course->id);
            \App\Services\CacheService::invalidateCourseSyllabus($course->id);
        });

        static::deleting(function ($course) {
            // Delete materials and their physical files
            foreach ($course->materials as $material) {
                if ($material->type === 'file' && !empty($material->url_or_path)) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($material->url_or_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($material->url_or_path);
                    }
                }
                $material->delete();
            }

            // Delete assignments, questions, submissions, answers, and attachments
            foreach ($course->assignments as $assignment) {
                if (!empty($assignment->attachment_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($assignment->attachment_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->attachment_path);
                }

                foreach ($assignment->submissions as $submission) {
                    if (!empty($submission->file_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($submission->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->file_path);
                    }
                    \App\Models\AssignmentAnswer::where('submission_id', $submission->id)->delete();
                    $submission->delete();
                }

                $assignment->questions()->delete();
                $assignment->delete();
            }

            // Delete enrollments and detach staff
            \Illuminate\Support\Facades\DB::table('enrollments')->where('course_id', $course->id)->delete();
            $course->staff()->detach();
        });

        static::deleted(function ($course) {
            \App\Services\CacheService::invalidateCourses($course->department_id);
            \App\Services\CacheService::invalidateCourseAllocations($course->id);
            \App\Services\CacheService::invalidateCourseSyllabus($course->id);
        });
    }
}
