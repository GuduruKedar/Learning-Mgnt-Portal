<?php

namespace App\Exports;

use App\Models\Program;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProgramsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection(): Enumerable
    {
        return Program::with(['department.school'])->get();
    }

    public function headings(): array
    {
        return [
            'Program Name (Use in template)',
            'Program Code',
            'Level (UG/PG/Diploma/PhD)',
            'Department Name',
            'School Name'
        ];
    }

    public function map($program): array
    {
        return [
            $program->name,
            $program->code,
            $program->level,
            $program->department->name ?? 'N/A',
            $program->department->school->name ?? 'N/A'
        ];
    }

    public function title(): string
    {
        return 'Available Programs';
    }
}
