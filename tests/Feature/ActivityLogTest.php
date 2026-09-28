<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Services\ActivityLogger;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    protected $superadmin;
    protected $student;
    protected $dept;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'sa']);
        Role::firstOrCreate(['name' => 'stu']);

        $school = School::firstOrCreate(['code' => 'sc_ci_test'], ['name' => 'Test School']);
        $this->dept = Department::firstOrCreate(['code' => 'dep_it_test'], [
            'name' => 'Information Technology Test',
            'school_id' => $school->code
        ]);

        $saUid = rand(10000, 99999);
        $saProfile = Profile::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'sa_' . $saUid,
            'email' => 'satest_' . $saUid . '@example.com',
            'roles_id' => 'sa',
        ]);
        $this->superadmin = User::create([
            'username' => $saProfile->username,
            'password' => bcrypt('password'),
            'profile_id' => $saProfile->id
        ]);

        $stuRoll = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stuReg = '241FA07' . $stuRoll; // Exactly 10 chars
        $stuProfile = Profile::create([
            'first_name' => 'Student',
            'last_name' => 'User',
            'username' => $stuReg,
            'email' => 'stutest_' . $stuReg . '@example.com',
            'roles_id' => 'stu',
            'departments_id' => $this->dept->code,
        ]);
        $this->student = User::create([
            'username' => $stuProfile->username,
            'password' => bcrypt('password'),
            'profile_id' => $stuProfile->id
        ]);
    }

    protected function tearDown(): void
    {
        if ($this->student) {
            $this->student->delete();
            Profile::where('username', $this->student->username)->forceDelete();
        }
        if ($this->superadmin) {
            $this->superadmin->delete();
            Profile::where('username', $this->superadmin->username)->forceDelete();
        }
        ActivityLog::where('action', 'like', 'test_%')->orWhere('action', 'like', 'dash_view%')->orWhere('action', 'inspect_test')->delete();
        parent::tearDown();
    }

    public function test_activity_logger_service_records_log()
    {
        $log = ActivityLogger::log(
            'test_action',
            'Test Action Title',
            'Academics',
            'This is a test description.',
            'success',
            [
                'user' => $this->student,
                'department_id' => $this->dept->code,
                'entity_type' => 'Course',
                'entity_name' => 'Data Structures',
                'payload' => ['sample_key' => 'sample_value']
            ]
        );

        $this->assertNotNull($log);
        $this->assertDatabaseHas('activity_logs', [
            'id' => $log->id,
            'action' => 'test_action',
            'department_id' => $this->dept->code,
            'severity' => 'success',
        ]);
    }

    public function test_super_admin_can_access_activity_logs_dashboard()
    {
        // Generate test log
        ActivityLogger::log('dash_view_test', 'View Test', 'System', 'Dashboard testing', 'info');

        $response = $this->actingAs($this->superadmin)->get(route('activity_logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Activity Log Monitoring');
        $response->assertSee('Department Share');
        $response->assertSee('Activity Volume Trend');
    }

    public function test_super_admin_can_filter_by_department()
    {
        ActivityLogger::log('dept_filter_test', 'Dept Filter Test', 'Academics', 'Dept specific log', 'info', [
            'department_id' => $this->dept->code
        ]);

        $response = $this->actingAs($this->superadmin)->get(route('activity_logs.index', ['department' => $this->dept->code]));

        $response->assertStatus(200);
        $response->assertSee('Dept specific log');
    }

    public function test_super_admin_can_inspect_individual_log()
    {
        $log = ActivityLogger::log('inspect_test', 'Inspect Title', 'Security', 'Secret operation recorded', 'warning', [
            'department_id' => $this->dept->code
        ]);

        $response = $this->actingAs($this->superadmin)->get(route('activity_logs.show', $log->id));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'log' => [
                'id' => $log->id,
                'action' => 'inspect_test',
                'action_title' => 'Inspect Title',
                'severity' => 'warning',
            ]
        ]);
    }

    public function test_super_admin_can_export_csv()
    {
        $response = $this->actingAs($this->superadmin)->get(route('activity_logs.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_non_super_admin_cannot_access_logs()
    {
        $response = $this->actingAs($this->student)->get(route('activity_logs.index'));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
