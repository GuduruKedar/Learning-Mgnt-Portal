<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrivateAssignmentTest extends TestCase
{
    protected $staffUser;
    protected $targetStudent;
    protected $otherStudent;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // Fetch staff user
        $this->staffUser = User::where('username', '10002')->first() 
            ?? User::whereHas('profile.role', function ($q) { $q->where('name', 'sta'); })->first();

        // Target student mentioned by user
        $this->targetStudent = User::where('username', '231FA07047')->first();

        // Other student
        $this->otherStudent = User::role('stu')->where('id', '!=', $this->targetStudent?->id)->first();

        // Target Course
        $this->course = Course::where('department_id', 'dep_it')->first() ?? Course::first();

        // Ensure staff has access to course
        if ($this->staffUser && $this->course && !$this->course->staff()->where('users.id', $this->staffUser->id)->exists()) {
            $this->course->staff()->attach($this->staffUser->id);
        }
    }

    public function test_match_students_ajax_endpoint_resolves_register_number()
    {
        $response = $this->actingAs($this->staffUser)
            ->postJson(route('staff.assignments.match-students'), [
                'register_numbers' => '231FA07047',
                'course_id' => $this->course->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);
        $response->assertJsonFragment([
            'username' => '231FA07047',
            'id' => $this->targetStudent->id,
        ]);
    }

    public function test_cohort_students_endpoint_returns_department_cohort_students()
    {
        $response = $this->actingAs($this->staffUser)
            ->getJson(route('staff.courses.cohort-students', [
                'course' => $this->course->id,
                'academic_year' => 'all',
                'semester' => 'all',
            ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'students',
            'count',
            'course_code',
        ]);
        $this->assertGreaterThan(0, $response->json('count'));
    }

    public function test_staff_can_create_private_assignment_with_pasted_register_number()
    {
        Assignment::where('title', 'Network Security Private Quiz')->delete();

        $payload = [
            'course_id' => $this->course->id,
            'title' => 'Network Security Private Quiz',
            'description' => 'Targeted quiz for designated students.',
            'due_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'status' => 'published',
            'type' => 'private',
            'student_register_numbers' => '231FA07047',
            'questions' => [
                [
                    'question_text' => 'What is an asymmetric encryption key pair?',
                    'option_a' => 'Public and Private keys',
                    'option_b' => 'Two identical secret keys',
                    'option_c' => 'Hash digest only',
                    'option_d' => 'Session token only',
                    'correct_option' => 'A',
                    'marks' => 5,
                ],
            ],
        ];

        $response = $this->actingAs($this->staffUser)
            ->post(route('staff.assignments.store'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $assignment = Assignment::where('title', 'Network Security Private Quiz')->first();
        $this->assertNotNull($assignment);
        $this->assertEquals('private', $assignment->type);

        // Verify target student is attached to assignedStudents
        $this->assertTrue($assignment->assignedStudents()->where('users.id', $this->targetStudent->id)->exists());

        // Verify student is auto-enrolled in the course
        $this->assertTrue($this->course->enrollments()->where('users.id', $this->targetStudent->id)->exists());

        // Target student can view the private assignment
        $studentResponse = $this->actingAs($this->targetStudent)
            ->get(route('student.assignments.show', $assignment->id));
        $studentResponse->assertStatus(200);
        $studentResponse->assertSee('Network Security Private Quiz');

        // Other non-assigned student is forbidden
        if ($this->otherStudent && !$this->course->enrollments()->where('users.id', $this->otherStudent->id)->exists()) {
            $otherResponse = $this->actingAs($this->otherStudent)
                ->get(route('student.assignments.show', $assignment->id));
            $otherResponse->assertStatus(403);
        }
    }
}
