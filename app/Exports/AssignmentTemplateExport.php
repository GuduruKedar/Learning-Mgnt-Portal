<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class AssignmentTemplateExport implements WithMultipleSheets, Export
{
    protected $staffUser;

    public function __construct($staffUser = null)
    {
        $this->staffUser = $staffUser ?: Auth::user();
    }

    public function sheets(): array
    {
        $sheets = [];

        $courses = collect();
        if ($this->staffUser) {
            $courses = Course::whereHas('staff', function ($q) {
                $q->where('users.id', $this->staffUser->id);
            })->with('department')->get();
        }

        $defaultCode = $courses->isNotEmpty() ? $courses->first()->code : '21CS101';

        $sheets[] = new AssignmentTemplateSheet($defaultCode);
        $sheets[] = new MyAssignedCoursesSheet($courses);

        return $sheets;
    }
}
