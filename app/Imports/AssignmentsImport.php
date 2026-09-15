<?php

namespace App\Imports;

use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AssignmentsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $staffId;
    protected $targetAssignmentId;
    public $importedCount = 0;
    public $createdAssignmentsCount = 0;
    public $errors = [];

    public function __construct($staffId = null, $targetAssignmentId = null)
    {
        $this->staffId = $staffId ?: Auth::id();
        $this->targetAssignmentId = $targetAssignmentId;
    }

    public function collection(Collection $rows): void
    {
        $staffCourses = Course::whereHas('staff', function ($q) {
            $q->where('users.id', $this->staffId);
        })->get()->keyBy(function ($item) {
            return strtoupper(trim($item->code));
        });

        $isGlobalAdmin = Auth::user() && in_array(Auth::user()->role, ['sa', 'admin']);
        $allCourses = null;
        if ($isGlobalAdmin) {
            $allCourses = Course::all()->keyBy(function ($item) {
                return strtoupper(trim($item->code));
            });
        }

        $rowNum = 1;
        $assignmentsMap = [];
        $lastCourseCode = '';
        $lastTitle = '';
        $lastDueDate = null;

        foreach ($rows as $row) {
            $rowNum++;

            $courseCode = isset($row['course_code']) ? trim((string)$row['course_code']) : '';
            $title = isset($row['assignment_title']) ? trim((string)$row['assignment_title']) : (isset($row['title']) ? trim((string)$row['title']) : '');
            $rawDueDate = $row['due_date'] ?? null;

            // Auto carry-forward / inherit from the previous row if left blank
            if (empty($courseCode) && !empty($lastCourseCode)) {
                $courseCode = $lastCourseCode;
            }
            if (empty($title) && !empty($lastTitle)) {
                $title = $lastTitle;
            }
            if (empty($rawDueDate) && !empty($lastDueDate)) {
                $rawDueDate = $lastDueDate;
            }

            // Update last seen values
            if (!empty($courseCode)) {
                $lastCourseCode = $courseCode;
            }
            if (!empty($title)) {
                $lastTitle = $title;
            }
            if (!empty($rawDueDate)) {
                $lastDueDate = $rawDueDate;
            }
            
            $questionText = isset($row['question']) ? trim((string)$row['question']) : (isset($row['question_text']) ? trim((string)$row['question_text']) : '');
            $optA = isset($row['option_a']) ? trim((string)$row['option_a']) : '';
            $optB = isset($row['option_b']) ? trim((string)$row['option_b']) : '';
            $optC = isset($row['option_c']) ? trim((string)$row['option_c']) : '';
            $optD = isset($row['option_d']) ? trim((string)$row['option_d']) : '';
            $rawCorrect = isset($row['correct_option']) ? trim((string)$row['correct_option']) : (isset($row['answer']) ? trim((string)$row['answer']) : '');
            $marks = isset($row['marks']) ? (int)$row['marks'] : (isset($row['points']) ? (int)$row['points'] : 1);
            $explanation = isset($row['explanation']) ? trim((string)$row['explanation']) : null;

            // Check required fields
            if ($questionText === '') {
                $this->errors[] = "Row #{$rowNum}: Question text is empty. Row skipped.";
                continue;
            }

            if ($optA === '' || $optB === '' || $optC === '' || $optD === '') {
                $this->errors[] = "Row #{$rowNum}: All 4 options (A, B, C, D) must be provided for '{$questionText}'.";
                continue;
            }

            $correctOption = $this->parseCorrectOption($rawCorrect);
            if (!$correctOption) {
                $this->errors[] = "Row #{$rowNum}: Invalid correct option '{$rawCorrect}' for question. Must be A, B, C, or D.";
                continue;
            }

            if ($marks <= 0) {
                $marks = 1;
            }

            // Resolve assignment
            $assignment = null;
            if ($this->targetAssignmentId) {
                $assignment = Assignment::find($this->targetAssignmentId);
            }

            if (!$assignment) {
                if (empty($courseCode)) {
                    $this->errors[] = "Row #{$rowNum}: Course Code is missing.";
                    continue;
                }

                if (empty($title)) {
                    $this->errors[] = "Row #{$rowNum}: Assignment Title is missing.";
                    continue;
                }

                $courseKey = strtoupper($courseCode);
                $course = $staffCourses->get($courseKey);
                if (!$course && $isGlobalAdmin) {
                    $course = $allCourses->get($courseKey);
                }

                if (!$course) {
                    $this->errors[] = "Row #{$rowNum}: Course '{$courseCode}' not found or not assigned to you.";
                    continue;
                }

                $mapKey = $course->id . '_' . strtolower($title);
                if (!isset($assignmentsMap[$mapKey])) {
                    $parsedDueDate = $this->parseDueDate($rawDueDate) ?? now()->addDays(7);
                    
                    $assignment = Assignment::firstOrCreate(
                        [
                            'course_id' => $course->id,
                            'title' => $title,
                            'staff_id' => $this->staffId,
                        ],
                        [
                            'description' => 'Multiple Choice Questions (MCQ) Assignment.',
                            'max_marks' => 0,
                            'due_date' => $parsedDueDate,
                            'status' => 'published',
                        ]
                    );

                    $assignmentsMap[$mapKey] = $assignment;
                    $this->createdAssignmentsCount++;
                } else {
                    $assignment = $assignmentsMap[$mapKey];
                }
            }

            // Next order number
            $currentOrder = $assignment->questions()->count() + 1;

            AssignmentQuestion::create([
                'assignment_id' => $assignment->id,
                'question_text' => $questionText,
                'option_a' => $optA,
                'option_b' => $optB,
                'option_c' => $optC,
                'option_d' => $optD,
                'correct_option' => $correctOption,
                'marks' => $marks,
                'explanation' => $explanation,
                'order' => $currentOrder,
            ]);

            $this->importedCount++;
        }

        // Recalculate max_marks for all affected assignments
        foreach ($assignmentsMap as $assignment) {
            $assignment->recalculateMaxMarks();
        }

        if ($this->targetAssignmentId) {
            $target = Assignment::find($this->targetAssignmentId);
            if ($target) {
                $target->recalculateMaxMarks();
            }
        }
    }

    protected function parseCorrectOption($raw): ?string
    {
        $cleaned = strtoupper(trim((string)$raw));
        $cleaned = str_replace(['OPTION', 'OPT', 'CHOICE', '.', ')', ' '], '', $cleaned);

        if (in_array($cleaned, ['A', 'B', 'C', 'D'])) {
            return $cleaned;
        }

        return null;
    }

    protected function parseDueDate($rawDate): ?Carbon
    {
        if (empty($rawDate)) {
            return null;
        }

        if (is_numeric($rawDate)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($rawDate));
            } catch (\Exception $e) {
                // fallback
            }
        }

        $dateStr = trim((string)$rawDate);
        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d',
            'd-m-Y H:i:s',
            'd-m-Y H:i',
            'd-m-Y',
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
            'm/d/Y',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $dateStr);
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($dateStr);
        } catch (\Exception $e) {
            return null;
        }
    }
}
