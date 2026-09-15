<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MyAssignedCoursesSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $courses;

    public function __construct($courses = [])
    {
        $this->courses = $courses;
    }

    public function headings(): array
    {
        return [
            'course_code',
            'course_name',
            'department',
            'year',
            'semester',
        ];
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->courses as $course) {
            $data[] = [
                $course->code,
                $course->name,
                $course->department ? $course->department->name : ($course->department_id ?? 'N/A'),
                $course->year ? 'Year ' . $course->year : 'N/A',
                $course->semester ? 'Sem ' . $course->semester : 'N/A',
            ];
        }

        if (empty($data)) {
            $data[] = ['No Assigned Courses Found', '', '', '', ''];
        }

        return $data;
    }

    public function title(): string
    {
        return 'My_Assigned_Courses';
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '047857'],
                ],
            ],
        ];
    }
}
