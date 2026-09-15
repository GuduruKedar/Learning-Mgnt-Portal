<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssignmentManagementTest extends TestCase
{
    protected $staffUser;
    protected $studentUser;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // Fetch seeded Staff
        $this->staffUser = User::where('username', '10002')->first() 
            ?? User::whereHas('profile.role', function ($q) { $q->where('name', 'sta'); })->first();

        // Fetch seeded Student
        $this->studentUser = User::where('username', 'student1')->first()
            ?? User::whereHas('profile.role', function ($q) { $q->where('name', 'stu'); })->first();

        // Get or Create Course
        $this->course = Course::first();
        if (!$this->course) {
            $this->course = Course::create([
                'code' => 'CS_MCQ_TEST',
                'name' => 'Computer Architecture & Systems',
                'department_id' => 'dep_cs',
                'year' => 3,
                'semester' => 1,
            ]);
        }

        // Assign Staff to Course
        if ($this->staffUser && !$this->course->staff()->where('users.id', $this->staffUser->id)->exists()) {
            $this->course->staff()->attach($this->staffUser->id);
        }

        // Enroll Student in Course
        if ($this->studentUser && !$this->course->enrollments()->where('users.id', $this->studentUser->id)->exists()) {
            $this->course->enrollments()->attach($this->studentUser->id);
        }
    }

    public function test_staff_can_view_assignments_page_and_create_page()
    {
        $response = $this->actingAs($this->staffUser)->get(route('staff.assignments.index'));
        $response->assertStatus(200);
        $response->assertSee('Course Assignments');

        $createResponse = $this->actingAs($this->staffUser)->get(route('staff.assignments.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Bulk Excel Upload');
        $createResponse->assertSee('Manual MCQ Form');
    }

    public function test_staff_can_create_mcq_assignment_manually()
    {
        Assignment::where('title', 'Operating Systems Process Synchronization MCQ')->delete();

        $payload = [
            'course_id' => $this->course->id,
            'title' => 'Operating Systems Process Synchronization MCQ',
            'description' => 'Answer all MCQ questions carefully.',
            'due_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'status' => 'published',
            'questions' => [
                [
                    'question_text' => 'What is a Semaphore in Operating Systems?',
                    'option_a' => 'A hardware register',
                    'option_b' => 'An integer variable used for synchronization',
                    'option_c' => 'A memory allocation algorithm',
                    'option_d' => 'A network protocol',
                    'correct_option' => 'B',
                    'marks' => 2,
                    'explanation' => 'A semaphore is an integer variable accessed only through atomic operations wait() and signal().',
                ],
                [
                    'question_text' => 'Which scheduling algorithm is non-preemptive?',
                    'option_a' => 'Round Robin',
                    'option_b' => 'Shortest Remaining Time First',
                    'option_c' => 'First-Come, First-Served (FCFS)',
                    'option_d' => 'Priority Preemptive',
                    'correct_option' => 'C',
                    'marks' => 1,
                    'explanation' => 'FCFS is strictly non-preemptive.',
                ],
            ],
        ];

        $response = $this->actingAs($this->staffUser)->post(route('staff.assignments.store'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $assignment = Assignment::where('title', 'Operating Systems Process Synchronization MCQ')->first();
        $this->assertNotNull($assignment);
        $this->assertEquals(3, $assignment->max_marks); // 2 + 1
        $this->assertEquals(2, $assignment->questions()->count());
    }

    public function test_staff_can_bulk_import_mcqs_via_excel_or_csv()
    {
        Assignment::where('title', 'Database Systems MCQ Quiz')->delete();

        $csvContent = "course_code,assignment_title,due_date,question,option_a,option_b,option_c,option_d,correct_option,marks,explanation\n"
            . "{$this->course->code},Database Systems MCQ Quiz,2026-11-20 23:59:00,Which key uniquely identifies a record in a relational table?,Foreign Key,Primary Key,Composite Key,Candidate Key,B,2,Primary key uniquely identifies each tuple.\n"
            . "{$this->course->code},Database Systems MCQ Quiz,2026-11-20 23:59:00,What does ACID stand for in DBMS?,Atomicity Consistency Isolation Durability,Accuracy Completeness Integrity Dependency,Access Control Identity Domain,Array Cache Index Database,A,1,ACID properties ensure reliable database transactions.\n";

        $file = UploadedFile::fake()->createWithContent('mcq_assignments.csv', $csvContent);

        $response = $this->actingAs($this->staffUser)->post(route('staff.assignments.bulk'), [
            'excel_file' => $file,
        ]);

        $response->assertRedirect(route('staff.assignments.index'));
        $response->assertSessionHas('success');

        $assignment = Assignment::where('title', 'Database Systems MCQ Quiz')->first();
        $this->assertNotNull($assignment);
        $this->assertEquals(2, $assignment->questions()->count());
        $this->assertEquals(3, $assignment->max_marks); // 2 + 1
    }

    public function test_staff_can_bulk_import_mcqs_via_base64_payload()
    {
        Assignment::where('title', 'Base64 MCQ Quiz')->delete();

        $csvContent = "course_code,assignment_title,due_date,question,option_a,option_b,option_c,option_d,correct_option,marks,explanation\n"
            . "{$this->course->code},Base64 MCQ Quiz,2026-11-20 23:59:00,Which protocol is secure?,HTTP,FTP,HTTPS,Telnet,C,1,HTTPS uses TLS/SSL.\n";

        $base64 = 'data:text/csv;base64,' . base64_encode($csvContent);

        $response = $this->actingAs($this->staffUser)->post(route('staff.assignments.bulk'), [
            'file_base64' => $base64,
            'file_name' => 'test_base64.csv',
        ]);

        $response->assertRedirect(route('staff.assignments.index'));
        $response->assertSessionHas('success');

        $assignment = Assignment::where('title', 'Base64 MCQ Quiz')->first();
        $this->assertNotNull($assignment);
        $this->assertEquals(1, $assignment->questions()->count());
    }

    public function test_staff_can_download_mcq_excel_template()
    {
        $response = $this->actingAs($this->staffUser)->get(route('staff.assignments.template'));
        $response->assertStatus(200);
        $this->assertTrue(
            $response->headers->get('content-disposition') !== null ||
            $response->headers->get('Content-Type') === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }

    public function test_student_takes_mcq_quiz_and_receives_instant_auto_grading()
    {
        // 1. Create MCQ Assignment
        $assignment = Assignment::create([
            'course_id' => $this->course->id,
            'staff_id' => $this->staffUser->id,
            'title' => 'Python Basics MCQ Test',
            'description' => 'Test on basic Python syntax and types.',
            'max_marks' => 3,
            'due_date' => now()->addDays(3),
            'status' => 'published',
        ]);

        $q1 = AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question_text' => 'What is the output of type(10.5)?',
            'option_a' => 'int',
            'option_b' => 'float',
            'option_c' => 'str',
            'option_d' => 'double',
            'correct_option' => 'B',
            'marks' => 1,
            'explanation' => '10.5 is a floating-point number in Python.',
            'order' => 1,
        ]);

        $q2 = AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question_text' => 'Which keyword is used to define a function in Python?',
            'option_a' => 'func',
            'option_b' => 'function',
            'option_c' => 'def',
            'option_d' => 'define',
            'correct_option' => 'C',
            'marks' => 2,
            'explanation' => 'The def keyword begins a function definition.',
            'order' => 2,
        ]);

        $assignment->recalculateMaxMarks();

        // 2. Student views the test page
        $studentView = $this->actingAs($this->studentUser)->get(route('student.assignments.show', $assignment->id));
        $studentView->assertStatus(200);
        $studentView->assertSee('What is the output of type(10.5)?');
        $studentView->assertSee('Which keyword is used to define a function in Python?');

        // 3. Student submits: Q1 correct ('B'), Q2 incorrect ('A')
        $submitPayload = [
            'answers' => [
                $q1->id => 'B', // Correct (+1)
                $q2->id => 'A', // Incorrect (0)
            ],
        ];

        $submitResponse = $this->actingAs($this->studentUser)->post(
            route('student.assignments.submit', $assignment->id),
            $submitPayload
        );

        $submitResponse->assertRedirect(route('student.assignments.show', $assignment->id));
        $submitResponse->assertSessionHas('success');

        // 4. Verify Database & Auto-Graded Score
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $this->studentUser->id)
            ->first();

        $this->assertNotNull($submission);
        $this->assertEquals('graded', $submission->status);
        $this->assertEquals(1, $submission->marks_awarded); // 1 out of 3

        $this->assertDatabaseHas('assignment_answers', [
            'submission_id' => $submission->id,
            'question_id' => $q1->id,
            'selected_option' => 'B',
            'is_correct' => 1,
            'marks_awarded' => 1,
        ]);

        $this->assertDatabaseHas('assignment_answers', [
            'submission_id' => $submission->id,
            'question_id' => $q2->id,
            'selected_option' => 'A',
            'is_correct' => 0,
            'marks_awarded' => 0,
        ]);

        // 5. Student reviews scorecard
        $resultView = $this->actingAs($this->studentUser)->get(route('student.assignments.show', $assignment->id));
        $resultView->assertStatus(200);
        $resultView->assertSee('10.5 is a floating-point number in Python.');
        $resultView->assertSee('Correct Answers');

        // 6. Staff views student results
        $staffView = $this->actingAs($this->staffUser)->get(route('staff.assignments.show', $assignment->id));
        $staffView->assertStatus(200);
        $staffView->assertSee($this->studentUser->first_name);
    }
}
