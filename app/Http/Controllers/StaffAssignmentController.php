<?php

namespace App\Http\Controllers;

use App\Exports\AssignmentResponsesExport;
use App\Exports\AssignmentTemplateExport;
use App\Imports\AssignmentsImport;
use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StaffAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $coursesQuery = Course::query();
        if (!$isGlobalAdmin) {
            $coursesQuery->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        $assignedCourses = $coursesQuery->with(['regulation', 'department'])->orderBy('code')->get();

        $query = Assignment::with(['course.enrollments', 'questions', 'submissions.student']);

        if (!$isGlobalAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('staff_id', $user->id)
                  ->orWhereIn('course_id', function ($sub) use ($user) {
                      $sub->select('course_id')->from('course_staff')->where('staff_id', $user->id);
                  });
            });
        }

        // Filters
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', 'published')->where('due_date', '>=', now());
            } elseif ($request->status === 'past_due') {
                $query->where('status', 'published')->where('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
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

        $assignments = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Metrics
        $baseStaffQuery = Assignment::query();
        if (!$isGlobalAdmin) {
            $baseStaffQuery->where(function ($q) use ($user) {
                $q->where('staff_id', $user->id)
                  ->orWhereIn('course_id', function ($sub) use ($user) {
                      $sub->select('course_id')->from('course_staff')->where('staff_id', $user->id);
                  });
            });
        }
        
        $totalAssignments = (clone $baseStaffQuery)->count();
        $activeAssignmentsCount = (clone $baseStaffQuery)->where('status', 'published')->where('due_date', '>=', now())->count();
        $pastDueAssignmentsCount = (clone $baseStaffQuery)->where('status', 'published')->where('due_date', '<', now())->count();
        $assignedCoursesCount = $assignedCourses->count();

        $allStaffAssignmentIds = (clone $baseStaffQuery)->pluck('id');
        $totalSubmissions = AssignmentSubmission::whereIn('assignment_id', $allStaffAssignmentIds)->count();
        $totalQuestions = AssignmentQuestion::whereIn('assignment_id', $allStaffAssignmentIds)->count();

        return view('staff.assignments.index', compact(
            'assignments',
            'assignedCourses',
            'totalAssignments',
            'activeAssignmentsCount',
            'pastDueAssignmentsCount',
            'assignedCoursesCount',
            'totalSubmissions',
            'totalQuestions'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $coursesQuery = Course::query();
        if (!$isGlobalAdmin) {
            $coursesQuery->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        $assignedCourses = $coursesQuery->with(['regulation', 'department'])->orderBy('code')->get();

        $selectedCourseId = $request->get('course_id');

        return view('staff.assignments.create', compact('assignedCourses', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:published,draft,closed',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required_with:questions|string',
            'questions.*.option_a' => 'required_with:questions|string',
            'questions.*.option_b' => 'required_with:questions|string',
            'questions.*.option_c' => 'required_with:questions|string',
            'questions.*.option_d' => 'required_with:questions|string',
            'questions.*.correct_option' => 'required_with:questions|in:A,B,C,D',
            'questions.*.marks' => 'nullable|integer|min:1|max:100',
            'questions.*.explanation' => 'nullable|string',
        ]);

        // Verify staff access to course
        if (!$isGlobalAdmin) {
            $hasAccess = Course::where('id', $request->course_id)
                ->whereHas('staff', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })->exists();

            if (!$hasAccess) {
                return redirect()->back()->withInput()->with('error', 'You are not assigned to teach this course.');
            }
        }

        $assignment = Assignment::create([
            'course_id' => $request->course_id,
            'staff_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description ?: 'Multiple Choice Questions (MCQ) Assignment.',
            'max_marks' => 0,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        if ($request->has('questions') && is_array($request->questions)) {
            $order = 1;
            foreach ($request->questions as $qData) {
                if (!empty($qData['question_text'])) {
                    AssignmentQuestion::create([
                        'assignment_id' => $assignment->id,
                        'question_text' => $qData['question_text'],
                        'option_a' => $qData['option_a'],
                        'option_b' => $qData['option_b'],
                        'option_c' => $qData['option_c'],
                        'option_d' => $qData['option_d'],
                        'correct_option' => $qData['correct_option'],
                        'marks' => $qData['marks'] ?? 1,
                        'explanation' => $qData['explanation'] ?? null,
                        'order' => $order++,
                    ]);
                }
            }
        }

        $assignment->recalculateMaxMarks();

        return redirect()->route('staff.assignments.show', $assignment->id)->with('success', 'MCQ Assignment created successfully.');
    }

    public function bulkUpload(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        // Check if top-level assignment details were provided in the form
        $formCourseId = $request->input('course_id');
        $formTitle = $request->input('title') ?: $request->input('assignment_title');
        $formDueDate = $request->input('due_date');
        $formDescription = $request->input('description');
        $formStatus = $request->input('status') ?: 'published';

        $targetAssignmentId = null;

        if ($formCourseId && $formTitle) {
            // Verify access
            if (!$isGlobalAdmin) {
                $hasAccess = Course::where('id', $formCourseId)
                    ->whereHas('staff', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    })->exists();

                if (!$hasAccess) {
                    return redirect()->back()->withInput()->with('error', 'You are not assigned to teach this course.');
                }
            }

            $parsedDueDate = $formDueDate ? \Carbon\Carbon::parse($formDueDate) : now()->addDays(7);

            // Create or get the target assignment
            $targetAssignment = Assignment::firstOrCreate(
                [
                    'course_id' => $formCourseId,
                    'title' => $formTitle,
                    'staff_id' => $user->id,
                ],
                [
                    'description' => $formDescription ?: 'Multiple Choice Questions (MCQ) Assignment.',
                    'max_marks' => 0,
                    'due_date' => $parsedDueDate,
                    'status' => $formStatus,
                ]
            );

            $targetAssignmentId = $targetAssignment->id;
        }

        $tempPath = null;

        // Check if file is provided via Base64 data from browser
        if ($request->filled('file_base64')) {
            $base64Data = $request->input('file_base64');
            if (preg_match('/^data:.*?;base64,/', $base64Data, $match)) {
                $base64Data = substr($base64Data, strlen($match[0]));
            }
            $fileContent = base64_decode($base64Data);
            if ($fileContent === false || empty($fileContent)) {
                return redirect()->back()->withInput()->with('error', 'Failed to read uploaded file data. Please try again.');
            }

            $fileName = $request->input('file_name', 'import.xlsx');
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) ?: 'xlsx';
            if (!in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
                return redirect()->back()->withInput()->with('error', 'Invalid file type (.'.$ext.'). Please upload an Excel (.xlsx, .xls) or CSV (.csv) file.');
            }

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $tempPath = $tempDir . '/' . uniqid('import_', true) . '.' . $ext;
            file_put_contents($tempPath, $fileContent);
        } else {
            // Standard $_FILES upload
            $file = $request->file('excel_file') ?: $request->file('file');
            if (!$file) {
                return redirect()->back()->withInput()->with('error', 'Please select an Excel or CSV file to upload.');
            }
            if (!$file->isValid()) {
                $errorCode = $file->getError();
                $errorMessage = match ($errorCode) {
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in PHP configuration.',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded. Please try uploading again.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk. Check server permissions.',
                    default => 'File upload failed with error code: ' . $errorCode,
                };
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['xlsx', 'xls', 'csv', 'txt'])) {
                return redirect()->back()->withInput()->with('error', 'Invalid file type (.'.$extension.'). Please upload an Excel (.xlsx, .xls) or CSV (.csv) file.');
            }

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $tempPath = $tempDir . '/' . uniqid('import_', true) . '.' . $extension;
            if (is_uploaded_file($file->getPathname())) {
                move_uploaded_file($file->getPathname(), $tempPath);
            } else {
                copy($file->getPathname(), $tempPath);
            }
        }

        try {
            $import = new AssignmentsImport(Auth::id(), $targetAssignmentId);
            Excel::import($import, $tempPath);

            // Clean up temporary file
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }

            if ($import->importedCount > 0) {
                $assignmentName = $formTitle ? "into assignment '{$formTitle}'" : "across {$import->createdAssignmentsCount} assignment(s)";
                $message = "Successfully imported {$import->importedCount} MCQ question(s) {$assignmentName}.";
                if (!empty($import->errors)) {
                    $message .= " (" . count($import->errors) . " warning(s)).";
                }
                if ($targetAssignmentId) {
                    return redirect()->route('staff.assignments.show', $targetAssignmentId)
                        ->with('success', $message)
                        ->with('import_errors', $import->errors);
                }
                return redirect()->route('staff.assignments.index')
                    ->with('success', $message)
                    ->with('import_errors', $import->errors);
            } else {
                $errorMessage = 'No MCQ questions could be imported. Please make sure the Excel file has valid columns (question, option_a, option_b, option_c, option_d, correct_option).';
                return redirect()->back()->withInput()
                    ->with('error', $errorMessage)
                    ->with('import_errors', $import->errors);
            }
        } catch (\Exception $e) {
            if ($tempPath && file_exists($tempPath)) {
                @unlink($tempPath);
            }
            return redirect()->back()->withInput()->with('error', 'Error reading Excel file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new AssignmentTemplateExport(Auth::user()), 'LMS_MCQ_Assignments_Template.xlsx');
    }

    public function show(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $assignment->load(['course.department', 'course.enrollments.profile', 'questions', 'submissions.student.profile', 'submissions.answers.question']);

        $enrolledStudents = $assignment->course->enrollments;
        $submissionsByStudent = $assignment->submissions->keyBy('student_id');
        $totalEnrolled = $enrolledStudents->count();
        $totalSubmitted = $assignment->submissions->count();
        $totalPending = max(0, $totalEnrolled - $totalSubmitted);
        $submissionRate = $totalEnrolled > 0 ? round(($totalSubmitted / $totalEnrolled) * 100) : 0;
        $averageScore = $totalSubmitted > 0 ? round($assignment->submissions->avg('marks_awarded'), 1) : 0;

        return view('staff.assignments.show', compact(
            'assignment',
            'enrolledStudents',
            'submissionsByStudent',
            'totalEnrolled',
            'totalSubmitted',
            'totalPending',
            'submissionRate',
            'averageScore'
        ));
    }

    public function exportResponses(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $assignment->load(['course.department', 'course.enrollments.profile', 'questions', 'submissions.student.profile', 'submissions.answers.question']);

        $filename = 'Assignment_Responses_' . Str::slug($assignment->course->code . '_' . $assignment->title) . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AssignmentResponsesExport($assignment), $filename);
    }

    public function storeQuestion(Request $request, Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'marks' => 'required|integer|min:1|max:100',
            'explanation' => 'nullable|string',
        ]);

        $order = $assignment->questions()->count() + 1;

        AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
            'marks' => $request->marks,
            'explanation' => $request->explanation,
            'order' => $order,
        ]);

        $assignment->recalculateMaxMarks();

        return redirect()->route('staff.assignments.show', $assignment->id)->with('success', 'MCQ question added successfully.');
    }

    public function destroyQuestion(AssignmentQuestion $question)
    {
        $assignment = $question->assignment;
        $this->authorizeStaffAccess($assignment);

        $question->delete();
        $assignment->recalculateMaxMarks();

        return redirect()->back()->with('success', 'Question deleted.');
    }

    public function edit(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $coursesQuery = Course::query();
        if (!$isGlobalAdmin) {
            $coursesQuery->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        $assignedCourses = $coursesQuery->with(['regulation', 'department'])->orderBy('code')->get();

        return view('staff.assignments.edit', compact('assignment', 'assignedCourses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:published,draft,closed',
        ]);

        $assignment->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        $assignment->recalculateMaxMarks();

        return redirect()->route('staff.assignments.show', $assignment->id)->with('success', 'MCQ Assignment details updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $assignment->delete();

        return redirect()->route('staff.assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    protected function authorizeStaffAccess(Assignment $assignment)
    {
        $user = Auth::user();
        if (in_array($user->role, ['sa', 'admin'])) {
            return true;
        }

        if ($assignment->staff_id === $user->id) {
            return true;
        }

        $isTeachingCourse = Course::where('id', $assignment->course_id)
            ->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->exists();

        if (!$isTeachingCourse) {
            abort(403, 'Unauthorized action. You are not assigned to this course.');
        }

        return true;
    }
}
