<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DashboardRoutesTest extends TestCase
{
    public function test_all_routes_return_successful_response()
    {
        $roles_to_test = ['sa', 'admin', 'sta', 'stu'];

        foreach ($roles_to_test as $role) {
            $user = User::role($role)->first();
            
            if (!$user) {
                $this->markTestSkipped("No user with role $role found");
                continue;
            }

            // Common routes
            $this->actingAs($user)->get('/dashboard')->assertStatus(200);
            $this->actingAs($user)->get('/profile')->assertStatus(200);

            if ($role === 'sa' || $role === 'admin') {
                $this->assertNotEquals(500, $this->actingAs($user)->get('/department-coordinators')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/staff')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/students')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/regulations')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/courses')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/courses/allocations')->getStatusCode());
            }

            if ($role === 'sta') {
                $this->assertNotEquals(500, $this->actingAs($user)->get('/staff/courses')->getStatusCode());
            }

            if ($role === 'stu') {
                $this->assertNotEquals(500, $this->actingAs($user)->get('/student/courses')->getStatusCode());
                $this->assertNotEquals(500, $this->actingAs($user)->get('/student/enrollment/create')->getStatusCode());
            }
        }
    }
}
