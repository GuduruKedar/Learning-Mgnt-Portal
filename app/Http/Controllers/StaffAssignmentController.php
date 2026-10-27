<?php

namespace App\Http\Controllers;

use App\Exports\AssignmentResponsesExport;
use App\Exports\AssignmentTemplateExport;
use App\Exports\PrivateAssignmentStudentTemplateExport;
use App\Imports\AssignmentsImport;
use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
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

        $assignedCourses = $this->getStaffEligibleCourses($user);

        $query = Assignment::with(['course.enrollments', 'questions', 'submissions.student'])->withCount('assignedStudents');

        if (!$isGlobalAdmin) {
            $query->where('staff_id', $user->id);
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
            $baseStaffQuery->where('staff_id', $user->id);
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

        $assignedCourses = $this->getStaffEligibleCourses($user);

        $selectedCourseId = $request->get('course_id');
        if (!$selectedCourseId && $assignedCourses->isNotEmpty()) {
            $selectedCourseId = $assignedCourses->first()->id;
        }

        $programs = $this->getStaffEligiblePrograms($user, $assignedCourses);

        return view('staff.assignments.create', compact('assignedCourses', 'selectedCourseId', 'programs'));
    }

    public function downloadStudentTemplate()
    {
        return Excel::download(new PrivateAssignmentStudentTemplateExport(), 'Private_Assignment_Students_Template.xlsx');
    }

    public function getCourseCohortStudents(Request $request, $course_id)
    {
        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $course = Course::with(['department'])->findOrFail($course_id);

        if (!$this->staffHasCourseAccess($user, $course)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Strictly only students enrolled in this course
        $query = $course->enrollments()->with(['profile.program']);

        if ($request->filled('academic_year') && $request->academic_year !== 'all') {
            $query->whereHas('profile', fn($q) => $q->where('academic_year', $request->academic_year));
        }

        if ($request->filled('semester') && $request->semester !== 'all') {
            $semVal = $request->semester;
            $yearVal = $request->input('academic_year');

            $acceptableSems = [(string)$semVal];
            if ($yearVal && $yearVal !== 'all') {
                $y = (int)$yearVal;
                if ((int)$semVal === ($y * 2 - 1)) {
                    $acceptableSems[] = '1';
                } elseif ((int)$semVal === ($y * 2)) {
                    $acceptableSems[] = '2';
                } elseif ((int)$semVal === 1) {
                    $acceptableSems[] = (string)($y * 2 - 1);
                } elseif ((int)$semVal === 2) {
                    $acceptableSems[] = (string)($y * 2);
                }
            }
            $acceptableSems = array_values(array_unique($acceptableSems));

            $query->whereHas('profile', function ($q) use ($acceptableSems) {
                $q->where(function ($sq) use ($acceptableSems) {
                    $sq->whereIn('semester', $acceptableSems)
                       ->orWhereNull('semester')
                       ->orWhere('semester', '');
                });
            });
        }

        if ($request->filled('program') && $request->program !== 'all') {
            $query->whereHas('profile', fn($q) => $q->where('programs_id', $request->program));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->get()->map(function ($stu) {
            $profile = $stu->profile;
            $name = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
            return [
                'id' => $stu->id,
                'username' => $stu->username,
                'name' => $name ?: $stu->username,
                'email' => $profile->email ?? '',
                'academic_year' => $profile->academic_year ?? 'N/A',
                'semester' => $profile->semester ?? 'N/A',
                'program' => $profile->program->name ?? ($profile->programs_id ?? 'N/A'),
                'section' => $profile->section ?? 'N/A',
            ];
        });

        $courseDept = $course->department;
        $targetDeptCode = $course->department_id ?: ($user->profile?->departments_id);
        $programsQuery = Program::query();

        if ($targetDeptCode) {
            $dept = Department::where('code', $targetDeptCode)->first();
            if ($dept) {
                $programsQuery->where('department_id', $dept->id);
            }
        } elseif ($courseDept?->school_id) {
            $deptIds = Department::where('school_id', $courseDept->school_id)->pluck('id');
            if ($deptIds->isNotEmpty()) {
                $programsQuery->whereIn('department_id', $deptIds);
            }
        }
        $availablePrograms = $programsQuery->orderBy('name')->get(['id', 'name', 'code']);

        return response()->json([
            'students' => $students,
            'count' => $students->count(),
            'course_code' => $course->code,
            'course_name' => $course->name,
            'course_year' => $course->year,
            'course_semester' => $course->semester,
            'programs' => $availablePrograms,
        ]);
    }

    public function parseStudentsExcel(Request $request)
    {
        $file = $request->file('file') ?: $request->file('student_excel');
        $base64 = $request->input('file_base64');

        $tempPath = null;
        if ($base64) {
            if (preg_match('/^data:.*?;base64,/', $base64, $match)) {
                $base64 = substr($base64, strlen($match[0]));
            }
            $content = base64_decode($base64);
            $ext = strtolower(pathinfo($request->input('file_name', 'students.xlsx'), PATHINFO_EXTENSION)) ?: 'xlsx';
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
            $tempPath = $tempDir . '/' . uniqid('students_', true) . '.' . $ext;
            file_put_contents($tempPath, $content);
        } elseif ($file && $file->isValid()) {
            $ext = strtolower($file->getClientOriginalExtension());
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
            $tempPath = $tempDir . '/' . uniqid('students_', true) . '.' . $ext;
            $file->move($tempDir, basename($tempPath));
        }

        if (!$tempPath || !file_exists($tempPath)) {
            return response()->json(['error' => 'No valid file uploaded.'], 422);
        }

        try {
            $extractedRegs = $this->extractRegisterNumbersFromSpreadsheet($tempPath);
            if (file_exists($tempPath)) @unlink($tempPath);

            if (empty($extractedRegs)) {
                return response()->json([
                    'error' => 'No student register numbers found in the uploaded file. Please make sure there is a column with register/roll numbers.',
                    'students' => [],
                    'count' => 0,
                ], 422);
            }

            $matchedUsers = User::role('stu')
                ->with(['profile.program'])
                ->whereIn('username', $extractedRegs)
                ->get();

            $courseId = $request->input('course_id');
            $course = $courseId ? Course::find($courseId) : null;
            $enrolledUserIds = $course ? $course->enrollments()->pluck('users.id')->toArray() : null;

            if ($enrolledUserIds !== null) {
                $matchedUsers = $matchedUsers->filter(fn($u) => in_array($u->id, $enrolledUserIds))->values();
            }

            $matchedRegs = $matchedUsers->pluck('username')->map(fn($u) => strtolower(trim($u)))->toArray();
            $unmatched = array_values(array_filter($extractedRegs, fn($r) => !in_array(strtolower(trim($r)), $matchedRegs)));

            $students = $matchedUsers->map(function ($stu) {
                $p = $stu->profile;
                $name = trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? ''));
                return [
                    'id' => $stu->id,
                    'username' => $stu->username,
                    'name' => $name ?: $stu->username,
                    'email' => $p->email ?? '',
                    'academic_year' => $p->academic_year ?? 'N/A',
                    'semester' => $p->semester ?? 'N/A',
                    'program' => $p->program->name ?? ($p->programs_id ?? 'N/A'),
                    'section' => $p->section ?? 'N/A',
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $students->count(),
                'students' => $students,
                'unmatched' => $unmatched,
                'message' => "Successfully matched {$students->count()} enrolled student(s) from Excel file." . (!empty($unmatched) ? " (" . count($unmatched) . " not enrolled in this course)" : ""),
            ]);
        } catch (\Throwable $e) {
            if ($tempPath && file_exists($tempPath)) @unlink($tempPath);
            return response()->json(['error' => 'Failed to parse spreadsheet: ' . $e->getMessage()], 422);
        }
    }

    protected function resolvePrivateStudentIds(Request $request): array
    {
        $studentIds = (array) $request->input('student_ids', []);

        if ($request->hasFile('student_excel')) {
            $file = $request->file('student_excel');
            if ($file->isValid()) {
                $excelRegs = $this->extractRegisterNumbersFromSpreadsheet($file->getRealPath());
                if (!empty($excelRegs)) {
                    $matched = User::role('stu')->whereIn('username', $excelRegs)->pluck('id')->toArray();
                    $studentIds = array_merge($studentIds, $matched);
                }
            }
        } elseif ($request->filled('student_file_base64')) {
            $base64 = $request->input('student_file_base64');
            if (preg_match('/^data:.*?;base64,/', $base64, $match)) {
                $base64 = substr($base64, strlen($match[0]));
            }
            $content = base64_decode($base64);
            $ext = strtolower(pathinfo($request->input('student_file_name', 'students.xlsx'), PATHINFO_EXTENSION)) ?: 'xlsx';
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
            $tempPath = $tempDir . '/' . uniqid('stus_', true) . '.' . $ext;
            file_put_contents($tempPath, $content);
            $excelRegs = $this->extractRegisterNumbersFromSpreadsheet($tempPath);
            if (file_exists($tempPath)) @unlink($tempPath);
            if (!empty($excelRegs)) {
                $matched = User::role('stu')->whereIn('username', $excelRegs)->pluck('id')->toArray();
                $studentIds = array_merge($studentIds, $matched);
            }
        }

        if ($request->filled('student_register_numbers')) {
            $raw = $request->input('student_register_numbers');
            $regs = preg_split('/[\s,;]+/', trim($raw));
            $regs = array_filter(array_map('trim', $regs));
            if (!empty($regs)) {
                $matched = User::role('stu')->whereIn('username', $regs)->pluck('id')->toArray();
                $studentIds = array_merge($studentIds, $matched);
            }
        }

        $allIds = array_values(array_unique(array_filter(array_map('intval', $studentIds))));

        $courseId = $request->input('course_id');
        if ($courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $enrolledIds = $course->enrollments()->pluck('users.id')->toArray();
                $allIds = array_values(array_intersect($allIds, $enrolledIds));
            }
        }

        return $allIds;
    }

    protected function extractRegisterNumbersFromSpreadsheet(string $filePath): array
    {
        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (empty($rows)) {
                return [];
            }

            $targetColIndex = 0;
            $headerRowIndex = null;
            $headerAliases = ['register_number', 'reg_no', 'roll_no', 'rollno', 'regno', 'username', 'student_id', 'student_username', 'register_no'];

            for ($r = 0; $r < min(3, count($rows)); $r++) {
                if (!is_array($rows[$r])) continue;
                foreach ($rows[$r] as $colIdx => $val) {
                    $norm = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', (string)$val)));
                    if (in_array($norm, $headerAliases)) {
                        $targetColIndex = $colIdx;
                        $headerRowIndex = $r;
                        break 2;
                    }
                }
            }

            $extracted = [];
            $startRow = $headerRowIndex !== null ? $headerRowIndex + 1 : 0;

            for ($i = $startRow; $i < count($rows); $i++) {
                if (!is_array($rows[$i])) continue;
                $val = trim((string)($rows[$i][$targetColIndex] ?? ''));
                if ($val === '') {
                    foreach ($rows[$i] as $cVal) {
                        $trimmed = trim((string)$cVal);
                        if ($trimmed !== '') {
                            $val = $trimmed;
                            break;
                        }
                    }
                }
                if (preg_match('/^[0-9]+(\.[0-9]+)?[eE]\+[0-9]+$/', $val)) {
                    $val = sprintf('%.0f', (float)$val);
                }
                if ($val !== '' && !in_array(strtolower($val), $headerAliases)) {
                    $extracted[] = $val;
                }
            }

            return array_values(array_unique($extracted));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Error parsing spreadsheet for students: ' . $e->getMessage());
            return [];
        }
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
            'type' => 'nullable|in:public,private',
            'questions' => 'nullable|array',
            'questions.*.type' => 'nullable|in:mcq,code',
            'questions.*.question_text' => 'required_with:questions|string',
            'questions.*.option_a' => 'nullable|string',
            'questions.*.option_b' => 'nullable|string',
            'questions.*.option_c' => 'nullable|string',
            'questions.*.option_d' => 'nullable|string',
            'questions.*.correct_option' => 'nullable|in:A,B,C,D',
            'questions.*.marks' => 'nullable|integer|min:1|max:100',
            'questions.*.explanation' => 'nullable|string',
        ]);

        // Verify staff access to course (including 1st year faculty policy)
        if (!$this->staffHasCourseAccess($user, $request->course_id)) {
            return redirect()->back()->withInput()->with('error', 'You are not assigned to teach this course.');
        }

        $type = $request->input('type', 'public');
        if (!in_array($type, ['public', 'private'])) {
            $type = 'public';
        }

        $privateStudentIds = [];
        if ($type === 'private') {
            $privateStudentIds = $this->resolvePrivateStudentIds($request);
            if (empty($privateStudentIds)) {
                return redirect()->back()->withInput()->with('error', 'For a Private Assignment, please select cohort students, upload an Excel file of students, or enter register numbers.');
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
            'type' => $type,
        ]);

        if ($type === 'private' && !empty($privateStudentIds)) {
            $assignment->assignedStudents()->sync($privateStudentIds);
        }

        if ($request->has('questions') && is_array($request->questions)) {
            $order = 1;
            foreach ($request->questions as $qData) {
                if (!empty($qData['question_text'])) {
                    $qType = $qData['type'] ?? 'mcq';
                    if ($qType === 'code') {
                        $allowedLangs = $qData['allowed_languages'] ?? ['c', 'java', 'python'];
                        if (is_array($allowedLangs)) {
                            $allowedLangs = implode(',', $allowedLangs);
                        }
                        $starterCodes = [
                            'c' => $qData['starter_code_c'] ?? \App\Services\CodeExecutionService::getDefaultStarterCode('c'),
                            'java' => $qData['starter_code_java'] ?? \App\Services\CodeExecutionService::getDefaultStarterCode('java'),
                            'python' => $qData['starter_code_python'] ?? \App\Services\CodeExecutionService::getDefaultStarterCode('python'),
                        ];
                        AssignmentQuestion::create([
                            'assignment_id' => $assignment->id,
                            'question_text' => $qData['question_text'],
                            'type' => 'code',
                            'allowed_languages' => $allowedLangs,
                            'starter_code' => $starterCodes,
                            'test_cases' => $qData['test_cases'] ?? [],
                            'marks' => $qData['marks'] ?? 10,
                            'order' => $order++,
                        ]);
                    } else {
                        AssignmentQuestion::create([
                            'assignment_id' => $assignment->id,
                            'question_text' => $qData['question_text'],
                            'type' => 'mcq',
                            'option_a' => $qData['option_a'] ?? '',
                            'option_b' => $qData['option_b'] ?? '',
                            'option_c' => $qData['option_c'] ?? '',
                            'option_d' => $qData['option_d'] ?? '',
                            'correct_option' => $qData['correct_option'] ?? 'A',
                            'marks' => $qData['marks'] ?? 1,
                            'explanation' => $qData['explanation'] ?? null,
                            'order' => $order++,
                        ]);
                    }
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
        $type = $request->input('type', 'public');
        if (!in_array($type, ['public', 'private'])) {
            $type = 'public';
        }

        $privateStudentIds = [];
        if ($type === 'private') {
            $privateStudentIds = $this->resolvePrivateStudentIds($request);
            if (empty($privateStudentIds)) {
                return redirect()->back()->withInput()->with('error', 'For a Private Assignment, please select cohort students, upload an Excel file of students, or enter register numbers.');
            }
        }

        $targetAssignmentId = null;

        if ($formCourseId && $formTitle) {
            // Verify access (including 1st year faculty policy)
            if (!$this->staffHasCourseAccess($user, $formCourseId)) {
                return redirect()->back()->withInput()->with('error', 'You are not assigned to teach this course.');
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
                    'type' => $type,
                ]
            );

            if ($type === 'private' && !empty($privateStudentIds)) {
                $targetAssignment->update(['type' => 'private']);
                $targetAssignment->assignedStudents()->sync($privateStudentIds);
            }

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

        $assignment->load(['course.department', 'course.enrollments.profile.program', 'questions', 'submissions.student.profile', 'submissions.answers.question', 'assignedStudents.profile.program']);

        if ($assignment->isPrivate()) {
            $enrolledStudents = $assignment->assignedStudents;
        } else {
            $enrolledStudents = $assignment->course->enrollments;
        }
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

        $type = $request->input('type', 'mcq');

        if ($type === 'code') {
            $request->validate([
                'question_text' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
                'marks' => 'required|integer|min:1|max:100',
                'allowed_languages' => 'nullable|array',
                'starter_code_c' => 'nullable|string',
                'starter_code_java' => 'nullable|string',
                'starter_code_python' => 'nullable|string',
                'test_cases' => 'nullable|array',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('assignment_questions', 'public');
            }

            $allowedLangs = $request->input('allowed_languages', ['c', 'java', 'python']);
            if (is_array($allowedLangs)) {
                $allowedLangs = implode(',', $allowedLangs);
            }

            $starterCodes = [
                'c' => $request->input('starter_code_c') ?: \App\Services\CodeExecutionService::getDefaultStarterCode('c'),
                'java' => $request->input('starter_code_java') ?: \App\Services\CodeExecutionService::getDefaultStarterCode('java'),
                'python' => $request->input('starter_code_python') ?: \App\Services\CodeExecutionService::getDefaultStarterCode('python'),
            ];

            $rawTestCases = $request->input('test_cases', []);
            $cleanTestCases = [];
            if (is_array($rawTestCases)) {
                foreach ($rawTestCases as $tc) {
                    if (isset($tc['input']) || isset($tc['expected_output'])) {
                        $cleanTestCases[] = [
                            'input' => (string)($tc['input'] ?? ''),
                            'expected_output' => (string)($tc['expected_output'] ?? ''),
                            'is_hidden' => !empty($tc['is_hidden']),
                        ];
                    }
                }
            }

            $order = $assignment->questions()->count() + 1;

            AssignmentQuestion::create([
                'assignment_id' => $assignment->id,
                'question_text' => $request->question_text,
                'image' => $imagePath,
                'type' => 'code',
                'allowed_languages' => $allowedLangs,
                'starter_code' => $starterCodes,
                'test_cases' => $cleanTestCases,
                'marks' => $request->marks,
                'order' => $order,
            ]);

            $assignment->recalculateMaxMarks();

            return redirect()->route('staff.assignments.show', $assignment->id)->with('success', 'Coding question added successfully.');
        }

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'marks' => 'required|integer|min:1|max:100',
            'explanation' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('assignment_questions', 'public');
        }

        $order = $assignment->questions()->count() + 1;

        AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question_text' => $request->question_text,
            'image' => $imagePath,
            'type' => 'mcq',
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

    public function uploadQuestionImage(Request $request, AssignmentQuestion $question)
    {
        $this->authorizeStaffAccess($question->assignment);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
        }

        $path = $request->file('image')->store('assignment_questions', 'public');
        $question->update(['image' => $path]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Picture attached to question successfully.',
                'image_url' => $question->image_url,
            ]);
        }

        return redirect()->back()->with('success', 'Picture attached to question successfully.');
    }

    public function deleteQuestionImage(Request $request, AssignmentQuestion $question)
    {
        $this->authorizeStaffAccess($question->assignment);

        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
        }

        $question->update(['image' => null]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Question picture removed successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Question picture removed successfully.');
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
            $result = $codeExecutor->runTestCases($language, $code, $testCases);
        } else {
            $result = $codeExecutor->execute($language, $code, $stdin);
        }

        return response()->json($result);
    }

    public function destroyQuestion(AssignmentQuestion $question)
    {
        $assignment = $question->assignment;
        $this->authorizeStaffAccess($assignment);

        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
        }

        $qText = \Illuminate\Support\Str::limit(strip_tags($question->question_text ?? $question->question ?? ''), 60);
        $assignmentTitle = $assignment->title;
        $courseCode = $assignment->course->code ?? 'General';
        $deptId = $assignment->course->department_id ?? null;

        $question->delete();
        $assignment->recalculateMaxMarks();

        \App\Services\ActivityLogger::log(
            'cascade_question_deleted',
            'Assignment Question Deleted',
            'Assignments',
            "Deleted question \"{$qText}\" from assignment \"{$assignmentTitle}\" ({$courseCode}).",
            'warning',
            [
                'department_id' => $deptId,
                'entity_type'   => 'AssignmentQuestion',
                'entity_id'     => $question->id,
                'entity_name'   => $qText,
                'payload'       => [
                    'assignment_id'    => $assignment->id,
                    'assignment_title' => $assignmentTitle,
                    'course_code'      => $courseCode
                ]
            ]
        );

        return redirect()->back()->with('success', 'Question deleted.');
    }

    public function edit(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $user = Auth::user();
        $isGlobalAdmin = in_array($user->role, ['sa', 'admin']);

        $assignedCourses = $this->getStaffEligibleCourses($user);
        if ($assignment->course && !$assignedCourses->contains('id', $assignment->course_id)) {
            $assignedCourses->push($assignment->course);
        }
        $programs = $this->getStaffEligiblePrograms($user, $assignedCourses);
        $assignedStudents = $assignment->assignedStudents()->with('profile.program')->get();
        $assignedStudentIds = $assignedStudents->pluck('id')->toArray();

        return view('staff.assignments.edit', compact('assignment', 'assignedCourses', 'programs', 'assignedStudents', 'assignedStudentIds'));
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
            'type' => 'nullable|in:public,private',
        ]);

        $type = $request->input('type', 'public');
        if (!in_array($type, ['public', 'private'])) {
            $type = 'public';
        }

        $privateStudentIds = [];
        if ($type === 'private') {
            $privateStudentIds = $this->resolvePrivateStudentIds($request);
            if (empty($privateStudentIds) && $assignment->assignedStudents()->count() === 0) {
                return redirect()->back()->withInput()->with('error', 'For a Private Assignment, please select cohort students, upload an Excel file of students, or enter register numbers.');
            }
        }

        $assignment->update([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'type' => $type,
        ]);

        if ($type === 'private') {
            if (!empty($privateStudentIds)) {
                $assignment->assignedStudents()->sync($privateStudentIds);
            }
        } else {
            $assignment->assignedStudents()->detach();
        }

        $assignment->recalculateMaxMarks();

        return redirect()->route('staff.assignments.show', $assignment->id)->with('success', 'MCQ Assignment details updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorizeStaffAccess($assignment);

        $assignmentTitle = $assignment->title;
        $courseCode = $assignment->course->code ?? 'General';
        $deptId = $assignment->course->department_id ?? null;
        $assignmentId = $assignment->id;
        $submissionsCount = $assignment->submissions->count();
        $questionsCount = $assignment->questions->count();
        $answersCount = 0;
        foreach ($assignment->submissions as $sub) {
            $answersCount += $sub->answers->count();
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($assignment) {
            // 1. Delete assignment attachment
            if ($assignment->attachment_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($assignment->attachment_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->attachment_path);
            }

            // 2. Delete student submissions and their files/answers
            foreach ($assignment->submissions as $sub) {
                if ($sub->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($sub->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($sub->file_path);
                }
                \Illuminate\Support\Facades\DB::table('assignment_answers')->where('submission_id', $sub->id)->delete();
                $sub->delete();
            }

            // 3. Delete MCQ questions
            $assignment->questions()->delete();

            // 4. Delete assignment
            $assignment->delete();
        });

        \App\Services\ActivityLogger::log(
            'cascade_assignment_deleted',
            'Assignment & Submissions Deleted',
            'Assignments',
            "Permanently deleted assignment \"{$assignmentTitle}\" ({$courseCode}). Cascaded removals: {$submissionsCount} student submission(s), {$answersCount} answer response(s), and {$questionsCount} question(s).",
            'danger',
            [
                'department_id' => $deptId,
                'entity_type'   => 'Assignment',
                'entity_id'     => $assignmentId,
                'entity_name'   => $assignmentTitle,
                'payload'       => [
                    'assignment_title'    => $assignmentTitle,
                    'course_code'         => $courseCode,
                    'submissions_deleted' => $submissionsCount,
                    'answers_deleted'     => $answersCount,
                    'questions_deleted'   => $questionsCount,
                ]
            ]
        );

        return redirect()->route('staff.assignments.index')->with('success', 'Assignment and all associated questions/submissions deleted successfully.');
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

        if (!$this->staffHasCourseAccess($user, $assignment->course_id)) {
            abort(403, 'Unauthorized action. You are not assigned to this course.');
        }

        return true;
    }

    protected function staffHasCourseAccess(User $user, $course): bool
    {
        if (in_array($user->role, ['sa', 'admin'])) {
            return true;
        }

        if (is_numeric($course)) {
            $course = Course::find($course);
        }

        if (!$course) {
            return false;
        }

        // Direct assignment check
        $hasDirect = Course::where('id', $course->id)
            ->whereHas('staff', fn($q) => $q->where('users.id', $user->id))
            ->exists();

        if ($hasDirect) {
            return true;
        }

        // 1st Year Access: If faculty is assigned to any Year 1 course, they can access all Year 1 courses in their department
        $deptCode = $user->profile?->departments_id;
        if ((int)$course->year === 1 && $deptCode && $course->department_id === $deptCode) {
            $assignedToYear1 = Course::whereHas('staff', fn($q) => $q->where('users.id', $user->id))
                ->where('year', 1)
                ->exists();
            if ($assignedToYear1) {
                return true;
            }
        }

        return false;
    }

    protected function getStaffEligibleCourses(User $user)
    {
        $isSuperAdmin = $user->role === 'sa';
        $isAdmin = $user->role === 'admin';

        $query = Course::query();

        // Strictly only courses that have enrolled students
        $query->whereHas('enrollments');

        if (!$isSuperAdmin) {
            if ($isAdmin) {
                // If admin is assigned to courses, prioritize them; else courses in their department
                if ($user->courses()->exists()) {
                    $query->whereHas('staff', fn($q) => $q->where('users.id', $user->id));
                } elseif ($user->profile?->departments_id) {
                    $query->where('department_id', $user->profile->departments_id);
                }
            } else {
                // Faculty (sta): Courses directly assigned/allocated to this faculty
                // PLUS if faculty is assigned to any 1st Year course, grant access to all 1st Year subjects in their department
                $assignedToYear1 = Course::whereHas('staff', fn($q) => $q->where('users.id', $user->id))
                    ->where('year', 1)
                    ->exists();

                $deptCode = $user->profile?->departments_id;

                $query->where(function ($q) use ($user, $assignedToYear1, $deptCode) {
                    $q->whereHas('staff', fn($sq) => $sq->where('users.id', $user->id));
                    if ($assignedToYear1 && $deptCode) {
                        $q->orWhere(function ($yq) use ($deptCode) {
                            $yq->where('department_id', $deptCode)
                               ->where('year', 1);
                        });
                    }
                });
            }
        }

        return $query->with(['regulation', 'department'])->orderBy('code')->get();
    }

    protected function getStaffEligiblePrograms(User $user, $assignedCourses = null)
    {
        $deptCode = $user->profile?->departments_id;

        // Prioritize faculty's specific department (e.g. IT)
        if ($deptCode) {
            $dept = Department::where('code', $deptCode)->first();
            if ($dept) {
                return Program::where('department_id', $dept->id)->orderBy('name')->get();
            }
        }

        if ($assignedCourses && $assignedCourses->isNotEmpty()) {
            $deptCodes = $assignedCourses->pluck('department_id')->filter()->unique();
            if ($deptCodes->isNotEmpty()) {
                $deptIds = Department::whereIn('code', $deptCodes)->pluck('id');
                if ($deptIds->isNotEmpty()) {
                    return Program::whereIn('department_id', $deptIds)->orderBy('name')->get();
                }
            }
        }

        $schoolCode = $user->profile?->schools_id;
        if ($schoolCode) {
            $deptIds = Department::where('school_id', $schoolCode)->pluck('id');
            if ($deptIds->isNotEmpty()) {
                return Program::whereIn('department_id', $deptIds)->orderBy('name')->get();
            }
        }

        return Program::orderBy('name')->get();
    }
}
