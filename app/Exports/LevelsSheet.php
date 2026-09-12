<?php

namespace App\Exports;

use App\Models\Program;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class LevelsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection(): Enumerable
    {
        // Get unique levels per department
        return Program::with(['department.school'])
            ->select('department_id', 'level')
            ->distinct()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Belongs to School',
            'Department Name',
            'Available Level (Use this in template)'
        ];
    }

    public function map($program): array
    {
        return [
            $program->department->school->name ?? 'N/A',
            $program->department->name ?? 'N/A',
            $program->level
        ];
    }

    public function title(): string
    {
        return 'Available Levels';
    }
}
