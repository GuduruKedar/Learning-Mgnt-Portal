<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Regulation;
use App\Models\Department;
use App\Models\CourseMaterial;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CascadeDeletionTest extends TestCase
{
    protected $superadmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::where('username', 'superadmin')->first();
    }

    /**
     * Test that a regulation CANNOT be deleted if it still has associated courses.
     * The system must protect child courses and warn the user to un-enroll and delete courses first.
     */
    public function test_regulation_cannot_be_deleted_while_courses_exist()
    {
        $this->assertNotNull($this->superadmin);

        // 1. Create a test regulation
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'TEST_REG_GUARD_' . time(),
            'name' => 'Test Guard Regulation',
            'curriculum' => 'TEST_CURR',
            'status' => 'Active',
        ]);

        $dept = Department::first();

        // 2. Create courses under this regulation
        $course1 = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $dept ? $dept->code : 'dep_cse',
            'code' => 'CRS_GUARD_1_' . time(),
            'name' => 'Guard Course 1',
            'year' => 2,
            'semester' => 1,
        ]);

        // 3. Attach student enrollment
        $student = User::role('stu')->first();
        if ($student) {
            $course1->enrollments()->attach($student->id);
        }

        // 4. Attempt to delete Regulation while courses still exist
        $response = $this->actingAs($this->superadmin)
            ->delete(route('academic.regulations.destroy', $regulation->id));

        // 5. Must fail with error warning to un-enroll and delete courses first
        $response->assertSessionHas('error');
        $this->assertStringContainsString('You have not deleted its 1 course(s) yet', session('error'));

        // 6. Verify regulation and courses still exist untouched
        $this->assertDatabaseHas('regulations', ['id' => $regulation->id]);
        $this->assertDatabaseHas('courses', ['id' => $course1->id]);
        if ($student) {
            $this->assertDatabaseHas('enrollments', [
                'user_id' => $student->id,
                'course_id' => $course1->id,
            ]);
        }
    }

    /**
     * Test that deleting an individual course un-enrolls all students and cleans up materials/assignments.
     */
    public function test_course_deletion_cascades_to_enrollments_materials_and_assignments()
    {
        $this->assertNotNull($this->superadmin);

        $dept = Department::first();
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'TEST_REG_CRS_' . time(),
            'name' => 'Test Course Regulation',
            'status' => 'Active',
        ]);

        // 1. Create a course
        $course = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $dept ? $dept->code : 'dep_cse',
            'code' => 'TEST_CRS_ALONE_' . time(),
            'name' => 'Cascade Course Alone',
            'year' => 3,
            'semester' => 1,
        ]);

        // 2. Attach student enrollment
        $student = User::role('stu')->first();
        if ($student) {
            $course->enrollments()->attach($student->id);
            $this->assertDatabaseHas('enrollments', [
                'user_id' => $student->id,
                'course_id' => $course->id,
            ]);
        }

        // 3. Create dummy material and assignment
        Storage::fake('public');
        $dummyPath = 'materials/test_file_' . time() . '.pdf';
        Storage::disk('public')->put($dummyPath, 'Dummy content');

        $staff = User::role('sta')->first() ?? $this->superadmin;
        $material = CourseMaterial::create([
            'course_id' => $course->id,
            'staff_id' => $staff->id,
            'type' => 'file',
            'platform' => 'pdf',
            'title' => 'Lecture Note',
            'url_or_path' => $dummyPath,
        ]);

        $assignment = Assignment::create([
            'course_id' => $course->id,
            'staff_id' => $staff->id,
            'title' => 'Course Assignment',
            'max_marks' => 20,
            'due_date' => now()->addDays(3),
            'status' => 'published',
        ]);

        // 4. Delete Course via HTTP DELETE route
        $response = $this->actingAs($this->superadmin)
            ->delete(route('academic.courses.destroy', $course->id));

        $response->assertSessionHas('success');

        // 5. Verify course and its children were deleted
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('course_materials', ['id' => $material->id]);
        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
        $this->assertFalse(Storage::disk('public')->exists($dummyPath));

        // 6. Now that the course is deleted, the regulation can be deleted!
        $regResponse = $this->actingAs($this->superadmin)
            ->delete(route('academic.regulations.destroy', $regulation->id));

        $regResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('regulations', ['id' => $regulation->id]);
    }
}
