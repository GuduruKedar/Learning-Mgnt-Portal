<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Department;
use App\Models\Program;
use App\Helpers\RegisterNumberParser;

class ValidationAuditTest extends TestCase
{
    protected $superadmin;
    protected $school;
    protected $dept;
    protected $otherDept;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superadmin = User::where('username', 'superadmin')->first();
        $this->school = School::first();
        $this->dept = Department::where('school_id', $this->school->code)->first();
        $this->otherDept = Department::where('school_id', '!=', $this->school->code)->first();
    }

    /**
     * Test RegisterNumberParser validation
     */
    public function test_register_number_parser_validation()
    {
        // Valid B.Tech CSE: 241FA04001
        $valid = RegisterNumberParser::parse('241FA04001');
        $this->assertNotNull($valid);
        $this->assertEquals('B.Tech', $valid['course_name']);
        $this->assertTrue(in_array($valid['department_name'], ['Computer Science & Engineering', 'Computer Science and Engineering']));

        // Invalid: format error
        $this->assertNull(RegisterNumberParser::parse('INVALID123'));
        $this->assertNull(RegisterNumberParser::parse('12345'));
        // Invalid: non-existent dept code
        $this->assertNull(RegisterNumberParser::parse('241FA99999'));
    }

    /**
     * Test Student Creation & Mismatched School/Department Validation
     */
    public function test_student_school_department_validation()
    {
        // 1. Mismatched school and department
        if ($this->otherDept) {
            $resp = $this->actingAs($this->superadmin)->post('/students', [
                'first_name' => 'Test',
                'last_name' => 'Validation',
                'username' => '241FA04991',
                'email' => 'testval991@gmail.com',
                'school_id' => $this->school->id,
                'department_id' => $this->otherDept->id, // Does not belong to this school!
            ]);
            $resp->assertSessionHasErrors(['department_id']);
        }

        // 2. Invalid register number format
        $respInvalidReg = $this->actingAs($this->superadmin)->post('/students', [
            'first_name' => 'Test',
            'last_name' => 'Validation',
            'username' => 'NOT_A_REG',
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $respInvalidReg->assertSessionHasErrors(['username']);
    }

    /**
     * Test Student Creation and Subsequent Update without unique error
     */
    public function test_student_create_and_update_lifecycle()
    {
        // Clean up if already exists
        \App\Models\Profile::where('email', 'johndoe992@gmail.com')->orWhere('username', '241FA04992')->forceDelete();
        User::where('username', '241FA04992')->delete();

        // 1. Create valid student
        $resp = $this->actingAs($this->superadmin)->post('/students', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => '241FA04992',
            'email' => 'johndoe992@gmail.com',
            'phone_number' => '9876543210',
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $resp->assertRedirect('/students');
        $resp->assertSessionHas('success');

        $student = User::where('username', '241FA04992')->first();
        $this->assertNotNull($student);

        // 2. Edit student page loads without error
        $editResp = $this->actingAs($this->superadmin)->get("/students/{$student->id}/edit");
        $editResp->assertStatus(200);
        $editResp->assertSee('John');
        $editResp->assertSee('Doe');

        // 3. Update student keeping same username & email (no unique error!)
        $updateResp = $this->actingAs($this->superadmin)->put("/students/{$student->id}", [
            'first_name' => 'Johnny',
            'last_name' => 'Doer',
            'username' => '241FA04992', // same username
            'email' => 'johndoe992@gmail.com', // same email
            'phone_number' => '9876543211',
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $updateResp->assertRedirect('/students');
        $updateResp->assertSessionHas('success');

        // Verify update persisted
        $student->refresh();
        $this->assertEquals('Johnny', $student->profile->first_name);
        $this->assertEquals('Doer', $student->profile->last_name);

        // Clean up
        $student->profile()->delete();
        $student->delete();
    }

    /**
     * Test Staff Creation and Update validation
     */
    public function test_staff_create_and_update_lifecycle()
    {
        // Clean up if already exists
        User::where('username', '99881')->delete();

        // 1. Staff username must be exactly 5 digits
        $respInvalidStaff = $this->actingAs($this->superadmin)->post('/staff', [
            'first_name' => 'Staff',
            'last_name' => 'Member',
            'username' => '123', // not 5 digits
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $respInvalidStaff->assertSessionHasErrors(['username']);

        // 2. Create valid staff member (5 digits: 99881)
        $resp = $this->actingAs($this->superadmin)->post('/staff', [
            'first_name' => 'Robert',
            'last_name' => 'Smith',
            'username' => '99881',
            'email' => 'robert99881@gmail.com',
            'phone_number' => '9123456780',
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $resp->assertRedirect('/staff');
        $resp->assertSessionHas('success');

        $staff = User::where('username', '99881')->first();
        $this->assertNotNull($staff);

        // 3. Edit staff page loads cleanly
        $editResp = $this->actingAs($this->superadmin)->get("/staff/{$staff->id}/edit");
        $editResp->assertStatus(200);

        // 4. Update staff member keeping same username
        $updateResp = $this->actingAs($this->superadmin)->put("/staff/{$staff->id}", [
            'first_name' => 'Robbie',
            'last_name' => 'Smith',
            'username' => '99881',
            'email' => 'robert99881@gmail.com',
            'phone_number' => '9123456789',
            'school_id' => $this->school->id,
            'department_id' => $this->dept->id,
        ]);
        $updateResp->assertRedirect('/staff');
        $updateResp->assertSessionHas('success');

        $staff->refresh();
        $this->assertEquals('Robbie', $staff->profile->first_name);

        // Clean up
        $staff->profile()->delete();
        $staff->delete();
    }
}
