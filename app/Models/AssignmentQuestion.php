<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'question_text',
        'image',
        'type',
        'allowed_languages',
        'starter_code',
        'test_cases',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'marks',
        'explanation',
        'order',
    ];

    protected $casts = [
        'marks' => 'integer',
        'order' => 'integer',
        'starter_code' => 'array',
        'test_cases' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function answers()
    {
        return $this->hasMany(AssignmentAnswer::class, 'question_id');
    }

    public function isCoding(): bool
    {
        return ($this->type ?? 'mcq') === 'code';
    }

    public function isMcq(): bool
    {
        return ($this->type ?? 'mcq') === 'mcq';
    }

    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return \Illuminate\Support\Facades\Storage::url($this->image);
    }

    public function getAllowedLanguagesArray(): array
    {
        if (empty($this->allowed_languages)) {
            return ['c', 'java', 'python'];
        }
        if (is_array($this->allowed_languages)) {
            return $this->allowed_languages;
        }
        return array_filter(array_map('trim', explode(',', strtolower($this->allowed_languages))));
    }
}

