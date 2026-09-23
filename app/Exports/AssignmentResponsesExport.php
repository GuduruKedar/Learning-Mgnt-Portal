<?php

namespace App\Exports;

use App\Models\Assignment;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssignmentResponsesExport implements WithMultipleSheets, Export
{
    use Exportable;

    protected $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function sheets(): array
    {
        return [
            new AssignmentStudentSummarySheet($this->assignment),
            new AssignmentDetailedResponsesSheet($this->assignment),
            new AssignmentQuestionsListSheet($this->assignment),
        ];
    }
}

/**
 * Sheet 1: Overall Student Results & Marks Summary
 */
class AssignmentStudentSummarySheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function title(): string
    {
        return 'Student Results Summary';
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Register Number',
            'Student Name',
            'Email Address',
            'Course Code',
            'Course Name',
            'Assignment Title',
            'Submission Status',
            'Marks Obtained',
            'Total Marks',
            'Percentage (%)',
            'Grade / Status',
            'Submitted At',
        ];
    }

    public function array(): array
    {
        $rows = [];
        $enrolledStudents = $this->assignment->course->enrollments;
        $submissionsByStudent = $this->assignment->submissions->keyBy('student_id');
        $maxMarks = $this->assignment->max_marks ?: 1;

        $index = 1;
        foreach ($enrolledStudents as $student) {
            $submission = $submissionsByStudent->get($student->id);
            $hasSubmitted = !is_null($submission);

            $marksObtained = $hasSubmitted ? $submission->marks_awarded : 0;
            $percentage = $hasSubmitted ? round(($marksObtained / $maxMarks) * 100, 1) : 0;

            $grade = 'Pending Submission';
            if ($hasSubmitted) {
                if ($percentage >= 80) {
                    $grade = 'Excellent (A)';
                } elseif ($percentage >= 60) {
                    $grade = 'Good (B)';
                } elseif ($percentage >= 40) {
                    $grade = 'Pass (C)';
                } else {
                    $grade = 'Needs Improvement (F)';
                }
            }

            $submittedAt = ($hasSubmitted && $submission->submitted_at)
                ? $submission->submitted_at->format('M d, Y h:i A')
                : 'Not Submitted';

            $rows[] = [
                $index++,
                $student->username,
                trim($student->first_name . ' ' . $student->last_name),
                $student->profile->email ?? ($student->email ?? 'N/A'),
                $this->assignment->course->code,
                $this->assignment->course->name,
                $this->assignment->title,
                $hasSubmitted ? 'Submitted & Graded' : 'Pending Submission',
                $hasSubmitted ? $marksObtained : '0',
                $this->assignment->max_marks,
                $hasSubmitted ? $percentage . '%' : '0%',
                $grade,
                $submittedAt,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $highestRow = max(1, $sheet->getHighestRow());
        $highestColumn = $sheet->getHighestColumn();

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '312E81']], // Indigo-900
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ],
        ];
    }
}

/**
 * Sheet 2: Question-Wise Responses Matrix
 */
class AssignmentDetailedResponsesSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function title(): string
    {
        return 'Question-Wise Responses';
    }

    public function headings(): array
    {
        $headings = [
            'S.No',
            'Register Number',
            'Student Name',
            'Status',
        ];

        foreach ($this->assignment->questions as $idx => $q) {
            $headings[] = "Q" . ($idx + 1) . " Picked";
            $headings[] = "Q" . ($idx + 1) . " Result";
            $headings[] = "Q" . ($idx + 1) . " Marks";
        }

        $headings[] = 'Total Score';
        $headings[] = 'Max Marks';
        $headings[] = 'Percentage (%)';

        return $headings;
    }

    public function array(): array
    {
        $rows = [];
        $enrolledStudents = $this->assignment->course->enrollments;
        $submissionsByStudent = $this->assignment->submissions->keyBy('student_id');
        $questions = $this->assignment->questions;
        $maxMarks = $this->assignment->max_marks ?: 1;

        $index = 1;
        foreach ($enrolledStudents as $student) {
            $submission = $submissionsByStudent->get($student->id);
            $hasSubmitted = !is_null($submission);
            $answers = $hasSubmitted ? $submission->answers->keyBy('question_id') : collect();

            $row = [
                $index++,
                $student->username,
                trim($student->first_name . ' ' . $student->last_name),
                $hasSubmitted ? 'Submitted' : 'Pending',
            ];

            foreach ($questions as $q) {
                if (!$hasSubmitted) {
                    $row[] = '-';
                    $row[] = '-';
                    $row[] = 0;
                } else {
                    $ans = $answers->get($q->id);
                    if ($ans) {
                        $row[] = $ans->selected_option ?: 'Unanswered';
                        $row[] = $ans->is_correct ? 'Correct' : 'Wrong';
                        $row[] = $ans->is_correct ? $q->marks : 0;
                    } else {
                        $row[] = 'Unanswered';
                        $row[] = 'Wrong';
                        $row[] = 0;
                    }
                }
            }

            $score = $hasSubmitted ? $submission->marks_awarded : 0;
            $row[] = $score;
            $row[] = $this->assignment->max_marks;
            $row[] = $hasSubmitted ? round(($score / $maxMarks) * 100, 1) . '%' : '0%';

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $highestRow = max(1, $sheet->getHighestRow());
        $highestColumn = $sheet->getHighestColumn();

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '065F46']], // Emerald-800
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ],
        ];
    }
}

/**
 * Sheet 3: Question Bank & Answer Key Reference
 */
class AssignmentQuestionsListSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function title(): string
    {
        return 'Assignment Questions';
    }

    public function headings(): array
    {
        return [
            'Q.No',
            'Question Statement',
            'Option A',
            'Option B',
            'Option C',
            'Option D',
            'Correct Option',
            'Marks',
            'Explanation',
        ];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->assignment->questions as $idx => $q) {
            $rows[] = [
                'Q' . ($idx + 1),
                $q->question_text,
                $q->option_a,
                $q->option_b,
                $q->option_c,
                $q->option_d,
                $q->correct_option,
                $q->marks,
                $q->explanation ?: 'N/A',
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $highestRow = max(1, $sheet->getHighestRow());
        $highestColumn = $sheet->getHighestColumn();

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']], // Slate-800
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ],
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ],
        ];
    }
}
