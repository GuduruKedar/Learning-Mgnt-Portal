<?php

namespace App\Exports;

use App\Models\School;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class SchoolsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection(): Enumerable
    {
        return School::all();
    }

    public function headings(): array
    {
        return [
            'School Name'
        ];
    }

    public function map($school): array
    {
        return [
            $school->name
        ];
    }

    public function title(): string
    {
        return 'Available Schools';
    }
}
