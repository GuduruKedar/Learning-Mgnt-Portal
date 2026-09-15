<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssignmentTemplateSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $defaultCourseCode;

    public function __construct($defaultCourseCode = '21CS101')
    {
        $this->defaultCourseCode = $defaultCourseCode ?: '21CS101';
    }

    public function headings(): array
    {
        return [
            'course_code',
            'assignment_title',
            'due_date',
            'question',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option',
            'marks',
            'explanation',
        ];
    }

    public function array(): array
    {
        $dueDate = now()->addDays(7)->format('Y-m-d H:i:s');

        return [
            [
                $this->defaultCourseCode,
                'Unit 1 MCQ Quiz: Fundamentals & Core Concepts',
                $dueDate,
                'Which of the following data structures operates on a Last In First Out (LIFO) principle?',
                'Queue',
                'Stack',
                'Linked List',
                'Binary Tree',
                'B',
                1,
                'A Stack follows the Last In First Out (LIFO) principle where elements are inserted and removed from the same end.',
            ],
            [
                $this->defaultCourseCode,
                'Unit 1 MCQ Quiz: Fundamentals & Core Concepts',
                $dueDate,
                'What is the worst-case time complexity of searching an element in a balanced Binary Search Tree (AVL tree)?',
                'O(1)',
                'O(n)',
                'O(log n)',
                'O(n log n)',
                'C',
                1,
                'In an AVL tree, the height is strictly maintained as O(log n), so search is always O(log n).',
            ],
            [
                $this->defaultCourseCode,
                'Unit 1 MCQ Quiz: Fundamentals & Core Concepts',
                $dueDate,
                'Which normal form is based on the concept of full functional dependency and eliminates partial dependency?',
                'First Normal Form (1NF)',
                'Second Normal Form (2NF)',
                'Third Normal Form (3NF)',
                'Boyce-Codd Normal Form (BCNF)',
                'B',
                2,
                '2NF eliminates partial dependency of non-prime attributes on any candidate key.',
            ],
            [
                $this->defaultCourseCode,
                'Unit 1 MCQ Quiz: Fundamentals & Core Concepts',
                $dueDate,
                'Which OSI layer is responsible for end-to-end communication and error recovery?',
                'Network Layer',
                'Data Link Layer',
                'Transport Layer',
                'Session Layer',
                'C',
                1,
                'The Transport layer (Layer 4) provides transparent transfer of data between end users with error recovery.',
            ],
        ];
    }

    public function title(): string
    {
        return 'MCQ_Assignments_Template';
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '1E3A8A'],
                ],
            ],
        ];
    }
}
