<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PrivateAssignmentStudentTemplateExport extends StringValueBinder implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting, WithCustomValueBinder
{
    protected $sampleStudents;

    public function __construct($sampleStudents = [])
    {
        $this->sampleStudents = $sampleStudents;
    }

    public function headings(): array
    {
        return [
            'register_number',
        ];
    }

    public function array(): array
    {
        if (!empty($this->sampleStudents)) {
            $formatted = [];
            foreach ($this->sampleStudents as $row) {
                if (is_array($row)) {
                    $formatted[] = [(string)($row['register_number'] ?? reset($row))];
                } else {
                    $formatted[] = [(string)$row];
                }
            }
            return $formatted;
        }

        return [
            ['720721104001'],
            ['720721104002'],
            ['720721104003'],
        ];
    }

    public function title(): string
    {
        return 'Allowed Students';
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        $sheet->getStyle('A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
            ],
        ];
    }
}
