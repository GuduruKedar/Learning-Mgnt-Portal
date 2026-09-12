<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Regulation;

class ComprehensiveSystemTest extends TestCase
{
    /**
     * Test authentication flows: valid and invalid credentials.
     */
    public function test_authentication_flows()
    {
        // 1. Visit Login Page
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Username');
        $response->assertSee('Password');

        // 2. Invalid Credentials
        $failResponse = $this->post('/authenticate', [
            'emp_id' => 'wrong_user',
            'password' => 'WrongPassword!123',
        ]);
        $failResponse->assertRedirect('/');
        $failResponse->assertSessionHasErrors('emp_id');

        // 3. Super Admin Login
        $saLogin = $this->post('/authenticate', [
            'emp_id' => 'superadmin',
            'password' => 'Vu_Super@123',
        ]);
        $saLogin->assertRedirect('/dashboard');
    }

    /**
     * Test Super Admin access to all core management modules.
     */
    public function test_super_admin_full_flow()
    {
        $superadmin = User::where('username', 'superadmin')->first();
        $this->assertNotNull($superadmin, 'Superadmin user must exist');

        // 1. Dashboard
        $resp = $this->actingAs($superadmin)->get('/dashboard');
        $resp->assertStatus(200);
        $resp->assertSee('Super Admin');

        // 2. Coordinators
        $this->actingAs($superadmin)->get('/coordinators')->assertStatus(200);
        $this->actingAs($superadmin)->get('/coordinators/create')->assertStatus(200);
        $this->actingAs($superadmin)->get('/department-coordinators')->assertStatus(200);
        $this->actingAs($superadmin)->get('/enrollment-insights')->assertStatus(200);

        // 3. Staff & Students Management
        $this->actingAs($superadmin)->get('/staff')->assertStatus(200);
        $this->actingAs($superadmin)->get('/staff/create')->assertStatus(200);
        $this->actingAs($superadmin)->get('/students')->assertStatus(200);
        $this->actingAs($superadmin)->get('/students/create')->assertStatus(200);

        // 4. Academic Management
        $this->actingAs($superadmin)->get('/regulations')->assertStatus(200);
        $this->actingAs($superadmin)->get('/courses')->assertStatus(200);
        $this->actingAs($superadmin)->get('/courses/create')->assertStatus(200);
        $this->actingAs($superadmin)->get('/courses/allocations')->assertStatus(200);

        // 5. Bulk Upload & Template
        $this->actingAs($superadmin)->get('/upload-history')->assertStatus(200);
        $this->actingAs($superadmin)->get('/bulk-upload/progress')->assertStatus(200);
        $this->actingAs($superadmin)->get('/bulk-upload/template?role=stu')->assertStatus(200);
        $this->actingAs($superadmin)->get('/bulk-upload/template?role=sta')->assertStatus(200);

        // 6. User Profile
        $this->actingAs($superadmin)->get('/profile')->assertStatus(200);
        $this->actingAs($superadmin)->get('/password')->assertStatus(200);
    }

    /**
     * Test Admin / Department Coordinator role flows.
     */
    public function test_admin_coordinator_flow()
    {
        $admin = User::where('username', '100001')->first();
        $this->assertNotNull($admin, 'Admin user 100001 must exist');

        // 1. Dashboard
        $resp = $this->actingAs($admin)->get('/dashboard');
        $resp->assertStatus(200);

        // 2. Department Staff & Students
        $this->actingAs($admin)->get('/staff')->assertStatus(200);
        $this->actingAs($admin)->get('/staff/create')->assertStatus(200);
        $this->actingAs($admin)->get('/students')->assertStatus(200);
        $this->actingAs($admin)->get('/students/create')->assertStatus(200);

        // 3. Department Academic
        $this->actingAs($admin)->get('/regulations')->assertStatus(200);
        $this->actingAs($admin)->get('/courses')->assertStatus(200);
        $this->actingAs($admin)->get('/courses/allocations')->assertStatus(200);

        // 4. Bulk Upload History & Templates
        $this->actingAs($admin)->get('/upload-history')->assertStatus(200);
        $this->actingAs($admin)->get('/bulk-upload/template?role=stu')->assertStatus(200);

        // 5. Restricted Routes (Admin should NOT access super-admin-only coordinators routes)
        $restrictedResp = $this->actingAs($admin)->get('/department-coordinators');
        // RoleMiddleware redirects back with error for non-sa
        $this->assertTrue(in_array($restrictedResp->getStatusCode(), [302, 403]));
    }

    /**
     * Test Staff / Faculty role flows.
     */
    public function test_staff_faculty_flow()
    {
        $staff = User::where('username', '100002')->first();
        $this->assertNotNull($staff, 'Staff user 100002 must exist');

        // 1. Dashboard
        $resp = $this->actingAs($staff)->get('/dashboard');
        $resp->assertStatus(200);

        // 2. My Courses
        $coursesResp = $this->actingAs($staff)->get('/staff/courses');
        $coursesResp->assertStatus(200);

        // 3. Students Directory (Read-only)
        $this->actingAs($staff)->get('/students')->assertStatus(200);

        // 4. Cannot create students or coordinators (Forbidden / Redirected)
        $deniedResp = $this->actingAs($staff)->get('/students/create');
        $this->assertTrue(in_array($deniedResp->getStatusCode(), [302, 403]));

        $deniedCoord = $this->actingAs($staff)->get('/coordinators');
        $this->assertTrue(in_array($deniedCoord->getStatusCode(), [302, 403]));

        // 5. Profile & Password
        $this->actingAs($staff)->get('/profile')->assertStatus(200);
        $this->actingAs($staff)->get('/password')->assertStatus(200);
    }

    /**
     * Test Student role flows.
     */
    public function test_student_flow()
    {
        $student = User::where('username', 'student1')->first();
        $this->assertNotNull($student, 'Student user student1 must exist');

        // 1. Dashboard
        $resp = $this->actingAs($student)->get('/dashboard');
        $resp->assertStatus(200);

        // 2. Course Enrollment Page
        $enrollPage = $this->actingAs($student)->get('/student/enroll');
        $enrollPage->assertStatus(200);
        $enrollPage->assertSee('Course Enrollment');
        $enrollPage->assertSee('Academic Selection');

        // 3. AJAX Course Fetching
        $activeReg = Regulation::where('status', 'Active')->first();
        if ($activeReg) {
            $ajaxResp = $this->actingAs($student)->getJson("/student/enroll/courses?regulation_id={$activeReg->id}&year=1&semester=1");
            $ajaxResp->assertStatus(200);
            $ajaxResp->assertJsonStructure(['courses', 'enrolled_course_ids']);
        }

        // 4. My Courses
        $myCourses = $this->actingAs($student)->get('/student/my-courses');
        $myCourses->assertStatus(200);

        // 5. Forbidden Routes (Student cannot access staff or admin routes)
        $deniedStaff = $this->actingAs($student)->get('/staff');
        $this->assertTrue(in_array($deniedStaff->getStatusCode(), [302, 403]));

        $deniedCoursesCreate = $this->actingAs($student)->get('/courses/create');
        $this->assertTrue(in_array($deniedCoursesCreate->getStatusCode(), [302, 403]));

        // 6. Profile & Password
        $this->actingAs($student)->get('/profile')->assertStatus(200);
        $this->actingAs($student)->get('/password')->assertStatus(200);
    }
}
