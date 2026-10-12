<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\School;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoordinatorUsernameGenerationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_coordinator_suggested_username_formats_with_padded_index()
    {
        Role::firstOrCreate(['name' => 'sa']);
        Role::firstOrCreate(['name' => 'admin']);

        $school = School::firstOrCreate(
            ['code' => 'sc_che_test'],
            ['name' => 'School of Chemical Engineering Test']
        );

        $dept = Department::firstOrCreate(
            ['code' => 'dep_che'],
            ['name' => 'Department of Chemical Engineering', 'school_id' => 'sc_che_test']
        );

        $saUid = rand(10000, 99999);
        $superadminProfile = Profile::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'sa_test_' . $saUid,
            'email' => 'sa_test_' . $saUid . '@vignan.ac.in',
            'roles_id' => 'sa',
        ]);

        $superadmin = User::create([
            'username' => $superadminProfile->username,
            'password' => bcrypt('Admin!741'),
            'profile_id' => $superadminProfile->id,
        ]);

        // First coordinator suggestion should match dep_che_01 (or next padded)
        $response = $this->actingAs($superadmin)->getJson("/departments/{$dept->id}/suggested-username");
        $response->assertStatus(200);
        $suggested1 = $response->json('username');
        $this->assertMatchesRegularExpression('/^dep_che_\d{2}$/', $suggested1);

        // If a coordinator user is created with suggested1, the next suggestion becomes dep_che_02
        $coordProfile = Profile::create([
            'first_name' => 'Coord',
            'last_name' => 'One',
            'username' => $suggested1,
            'email' => 'coord_' . uniqid() . '@vignan.ac.in',
            'roles_id' => 'admin',
            'departments_id' => $dept->code,
            'schools_id' => $school->code,
        ]);

        User::create([
            'username' => $suggested1,
            'password' => bcrypt('Admin!741'),
            'profile_id' => $coordProfile->id,
        ]);

        $response2 = $this->actingAs($superadmin)->getJson("/departments/{$dept->id}/suggested-username");
        $response2->assertStatus(200);
        $suggested2 = $response2->json('username');
        $this->assertMatchesRegularExpression('/^dep_che_\d{2}$/', $suggested2);
        $this->assertNotEquals($suggested1, $suggested2);
    }
}
