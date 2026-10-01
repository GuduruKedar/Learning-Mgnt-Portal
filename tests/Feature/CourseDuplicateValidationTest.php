<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Regulation;
use App\Models\Department;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseDuplicateValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cannot_create_batch_courses_with_duplicate_code_in_same_form()
    {
        $admin = User::role('sa')->first() ?? User::first();
        $reg = Regulation::first();
        $dept = Department::first();

        $response = $this->actingAs($admin)->post(route('academic.courses.store'), [
            'regulation_id' => $reg->id,
            'department_id' => $dept->code,
            'semester' => 1,
            'year' => 1,
            'no_of_courses' => 2,
            'code' => ['TESTDUP101', 'TESTDUP101'],
            'name' => ['Subject One', 'Subject Two'],
        ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertEquals(0, Course::where('code', 'TESTDUP101')->count());
    }

    public function test_cannot_create_batch_courses_with_preexisting_code()
    {
        $admin = User::role('sa')->first() ?? User::first();
        $reg = Regulation::first();
        $dept = Department::first();

        // Existing course
        $existing = Course::first();

        $response = $this->actingAs($admin)->post(route('academic.courses.store'), [
            'regulation_id' => $reg->id,
            'department_id' => $dept->code,
            'semester' => 1,
            'year' => 1,
            'no_of_courses' => 1,
            'code' => [strtolower($existing->code)],
            'name' => ['Another Subject'],
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_can_create_batch_courses_with_unique_codes()
    {
        $admin = User::role('sa')->first() ?? User::first();
        $reg = Regulation::first();
        $dept = Department::first();

        $code1 = 'TESTUNIQ_' . rand(1000, 9999);
        $code2 = 'TESTUNIQ_' . rand(1000, 9999);

        $response = $this->actingAs($admin)->post(route('academic.courses.store'), [
            'regulation_id' => $reg->id,
            'department_id' => $dept->code,
            'semester' => 1,
            'year' => 1,
            'no_of_courses' => 2,
            'code' => [$code1, $code2],
            'name' => ['Subject 1', 'Subject 2'],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('courses', ['code' => $code1]);
        $this->assertDatabaseHas('courses', ['code' => $code2]);
    }

    public function test_ssh_course_creation_blocks_duplicate_code()
    {
        $admin = User::role('sa')->first() ?? User::first();
        $reg = Regulation::first();
        $dept = Department::first();

        // 1. Create initial SSH course
        $code = 'SSH_UNIQ_' . rand(1000, 9999);
        $this->actingAs($admin)->post(route('ssh.courses.store'), [
            'regulation_id' => $reg->id,
            'department_id' => $dept->code,
            'semester' => 1,
            'code' => $code,
            'name' => 'Foundational Physics',
        ])->assertSessionHasNoErrors();

        // 2. Attempt creating duplicate SSH course with same code
        $resp = $this->actingAs($admin)->post(route('ssh.courses.store'), [
            'regulation_id' => $reg->id,
            'department_id' => $dept->code,
            'semester' => 2,
            'code' => strtolower($code),
            'name' => 'Foundational Physics 2',
        ]);

        $resp->assertSessionHasErrors(['code']);
    }

    public function test_civil_course_creation_blocks_duplicate_code()
    {
        $admin = User::role('sa')->first() ?? User::first();

        $code = 'CIVIL_UNIQ_' . rand(1000, 9999);
        $this->actingAs($admin)->post(route('civil.courses.store'), [
            'code' => $code,
            'name' => 'General Studies 1',
            'category' => 'Prelims GS Paper 1',
        ])->assertSessionHasNoErrors();

        // Attempt creating with duplicate code
        $resp = $this->actingAs($admin)->post(route('civil.courses.store'), [
            'code' => strtolower($code),
            'name' => 'General Studies Duplicate',
            'category' => 'Prelims GS Paper 2',
        ]);

        $resp->assertSessionHasErrors(['code']);
    }
}
