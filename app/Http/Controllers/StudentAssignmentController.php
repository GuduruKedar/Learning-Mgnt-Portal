<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get enrolled course IDs
        $enrolledCourseIds = Course::whereHas('enrollments', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->pluck('id');

        $query = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->whereIn('status', ['published', 'closed'])
            ->where(function($q) use ($user) {
                $q->where('type', 'public')
                  ->orWhereNull('type')
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('type', 'private')
                          ->whereHas('assignedStudents', function($sq) use ($user) {
                              $sq->where('users.id', $user->id);
                          });
                  });
            })
            ->with(['course', 'staff', 'questions', 'submissions' => function ($q) use ($user) {
                $q->where('student_id', $user->id);
            }]);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('course', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $allAssignments = $query->orderBy('due_date', 'asc')->get();

        $tab = $request->get('tab', 'all');
        $filteredAssignments = $allAssignments->filter(function ($assignment) use ($tab) {
            $submission = $assignment->submissions->first();
            $isSubmitted = !is_null($submission);
            $isOverdue = $assignment->isPastDue() && !$isSubmitted;

            if ($tab === 'pending') {
                return !$isSubmitted && !$assignment->isPastDue();
            } elseif ($tab === 'completed' || $tab === 'submitted' || $tab === 'graded') {
                return $isSubmitted;
            } elseif ($tab === 'overdue') {
                return $isOverdue;
            }
            return true;
        });

        // Summary metrics
        $totalCount = $allAssignments->count();
        $submittedCount = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
        $completedCount = $submittedCount;
        $pendingCount = $allAssignments->filter(fn($a) => $a->submissions->isEmpty() && !$a->isPastDue())->count();
        $overdueCount = $allAssignments->filter(fn($a) => $a->submissions->isEmpty() && $a->isPastDue())->count();

        // Calculate average score percentage for completed assignments
        $completedAssignments = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty());
        $avgScorePercent = 0;
        if ($completedAssignments->isNotEmpty()) {
            $totalMarksScored = $completedAssignments->sum(fn($a) => $a->submissions->first()->marks_awarded ?? 0);
            $totalMaxMarks = $completedAssignments->sum(fn($a) => $a->max_marks > 0 ? $a->max_marks : 1);
            $avgScorePercent = round(($totalMarksScored / max(1, $totalMaxMarks)) * 100);
        }

        $enrolledCourses = Course::whereIn('id', $enrolledCourseIds)->orderBy('code')->get();

        return view('student.assignments.index', compact(
            'filteredAssignments',
            'tab',
            'totalCount',
            'submittedCount',
            'completedCount',
            'pendingCount',
            'overdueCount',
            'avgScorePercent',
            'enrolledCourses'
        ));
    }

    public function show(Assignment $assignment)
    {
        $user = Auth::user();

        // Check student enrollment
        $isEnrolled = Course::where('id', $assignment->course_id)
            ->whereHas('enrollments', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in the course for this assignment.');
        }

        if (!$assignment->isAccessibleBy($user)) {
            abort(403, 'This is a private assignment and your account has not been granted access.');
        }

        $assignment->load(['course.department', 'staff', 'questions']);
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->with(['answers.question'])
            ->first();

        $userAnswersByQuestionId = [];
        $correctCount = 0;
        $incorrectCount = 0;
        $unattemptedCount = 0;

        if ($submission) {
            $userAnswersByQuestionId = $submission->answers->keyBy('question_id');
            foreach ($assignment->questions as $question) {
                $ans = $userAnswersByQuestionId->get($question->id);
                $isAttempted = false;
                if ($ans) {
                    if ($question->isCoding()) {
                        $isAttempted = !empty(trim((string)$ans->code));
                    } else {
                        $isAttempted = !empty($ans->selected_option);
                    }
                }

                if ($isAttempted) {
                    if ($ans->is_correct || $ans->marks_awarded > 0) {
                        $correctCount++;
                    } else {
                        $incorrectCount++;
                    }
                } else {
                    $unattemptedCount++;
                }
            }
        }

        return view('student.assignments.show', compact(
            'assignment',
            'submission',
            'userAnswersByQuestionId',
            'correctCount',
            'incorrectCount',
            'unattemptedCount'
        ));
    }

    public function submit(Request $request, Assignment $assignment, \App\Services\CodeExecutionService $codeExecutor)
    {
        $user = Auth::user();

        // Verify enrollment
        $isEnrolled = Course::where('id', $assignment->course_id)
            ->whereHas('enrollments', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        if (!$assignment->isAccessibleBy($user)) {
            abort(403, 'This is a private assignment and your account has not been granted access.');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();

        if ($submission) {
            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('error', 'You have already submitted this assignment.');
        }

        $answersInput = $request->input('answers', []);
        $codeAnswersInput = $request->input('code_answers', []);
        $questions = $assignment->questions;

        $totalScore = 0;
        $maxPossible = $assignment->max_marks ?: $assignment->recalculateMaxMarks();

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'submitted_at' => now(),
            'status' => 'graded',
            'marks_awarded' => 0,
            'graded_at' => now(),
            'feedback' => 'Auto-evaluated upon submission.',
        ]);

        foreach ($questions as $question) {
            if ($question->isCoding()) {
                $qCodeData = $codeAnswersInput[$question->id] ?? [];
                $submittedCode = is_array($qCodeData) ? ($qCodeData['code'] ?? '') : (string)$qCodeData;
                $submittedLang = is_array($qCodeData) ? ($qCodeData['language'] ?? 'python') : 'python';

                $isCorrect = false;
                $marksEarned = 0;
                $testCaseResults = [];
                $execOutput = '';

                if (!empty(trim($submittedCode))) {
                    $testCases = is_array($question->test_cases) ? $question->test_cases : [];
                    $eval = $codeExecutor->runTestCases($submittedLang, $submittedCode, $testCases, $question->marks);

                    $isCorrect = !empty($eval['passed']);
                    $marksEarned = (int)($eval['score'] ?? 0);
                    $testCaseResults = $eval['results'] ?? [];
                    $execOutput = "Passed {$eval['passed_count']} / {$eval['total_test_cases']} Test Cases.";
                    $totalScore += $marksEarned;
                }

                AssignmentAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                    'selected_option' => null,
                    'code' => $submittedCode,
                    'language' => $submittedLang,
                    'test_case_results' => $testCaseResults,
                    'code_output' => $execOutput,
                    'is_correct' => $isCorrect,
                    'marks_awarded' => $marksEarned,
                ]);

            } else {
                // MCQ evaluation
                $selected = isset($answersInput[$question->id]) ? strtoupper(trim((string)$answersInput[$question->id])) : null;
                $isCorrect = false;
                $marksEarned = 0;

                if ($selected && in_array($selected, ['A', 'B', 'C', 'D'])) {
                    if ($selected === $question->correct_option) {
                        $isCorrect = true;
                        $marksEarned = $question->marks;
                        $totalScore += $marksEarned;
                    }
                } else {
                    $selected = null;
                }

                AssignmentAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                    'selected_option' => $selected,
                    'is_correct' => $isCorrect,
                    'marks_awarded' => $marksEarned,
                ]);
            }
        }

        $submission->update([
            'marks_awarded' => $totalScore,
        ]);

        return redirect()->route('student.assignments.show', $assignment->id)
            ->with('success', "Assignment submitted! You scored {$totalScore} / {$maxPossible} Marks.");
    }

    public function runCode(Request $request, \App\Services\CodeExecutionService $codeExecutor)
    {
        $request->validate([
            'language' => 'required|string|in:c,java,python',
            'code' => 'required|string',
            'stdin' => 'nullable|string',
            'test_cases' => 'nullable|array',
        ]);

        $language = strtolower($request->language);
        $code = $request->code;
        $stdin = (string)$request->input('stdin', '');
        $testCases = $request->input('test_cases', null);

        if (is_array($testCases) && count($testCases) > 0) {
            // For interactive runs in student view, evaluate against public test cases only
            $publicTestCases = array_values(array_filter($testCases, function ($tc) {
                return empty($tc['is_hidden']);
            }));
            $result = $codeExecutor->runTestCases($language, $code, $publicTestCases);
        } else {
            $result = $codeExecutor->execute($language, $code, $stdin);
        }

        return response()->json($result);
    }

    public function downloadReport(Assignment $assignment)
    {
        $user = Auth::user();

        $isEnrolled = Course::where('id', $assignment->course_id)
            ->whereHas('enrollments', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in the course for this assignment.');
        }

        $assignment->load(['course.department', 'staff', 'questions']);
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->with(['answers.question'])
            ->firstOrFail();

        $userAnswersByQuestionId = $submission->answers->keyBy('question_id');
        $correctCount = 0;
        $incorrectCount = 0;
        $unattemptedCount = 0;

        foreach ($assignment->questions as $question) {
            $ans = $userAnswersByQuestionId->get($question->id);
            if ($ans && $ans->selected_option) {
                if ($ans->is_correct) {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                }
            } else {
                $unattemptedCount++;
            }
        }

        $percentage = ($assignment->max_marks > 0) ? round(($submission->marks_awarded / $assignment->max_marks) * 100) : 0;

        return view('student.assignments.report', compact(
            'assignment',
            'submission',
            'user',
            'userAnswersByQuestionId',
            'correctCount',
            'incorrectCount',
            'unattemptedCount',
            'percentage'
        ));
    }
}

