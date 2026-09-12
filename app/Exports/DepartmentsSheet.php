<?php

namespace App\Exports;

use App\Models\Department;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection(): Enumerable
    {
        return Department::with('school')->get();
    }

    public function headings(): array
    {
        return [
            'Department Name',
            'Belongs to School'
        ];
    }

    public function map($department): array
    {
        return [
            $department->name,
            $department->school->name ?? 'N/A'
        ];
    }

    public function title(): string
    {
        return 'Available Departments';
    }
}
