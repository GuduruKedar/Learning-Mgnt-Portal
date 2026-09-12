<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SchoolAndDepartmentSeeder::class,
        ]);

        $saRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
            'name' => 'sa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $staffRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
            'name' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $studentRoleId = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
            'name' => 'student',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('roles')->insertOrIgnore([
            ['name' => 'sta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'stu', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $superAdminProfileId = \Illuminate\Support\Facades\DB::table('profiles')->insertGetId([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'roles_id' => 'sa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'username' => 'superadmin',
            'password' => \Illuminate\Support\Facades\Hash::make('Vu_Super@123'),
            'profile_id' => $superAdminProfileId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default Admin (Coordinator) for testing
        $adminProfileId = \Illuminate\Support\Facades\DB::table('profiles')->insertGetId([
            'first_name' => 'Admin',
            'last_name' => 'CSE',
            'username' => '10001',
            'email' => 'admincse@example.com',
            'schools_id' => 'sc_ci',
            'departments_id' => 'dep_cse',
            'roles_id' => 'admin',
            'designation' => 'Coordinator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'username' => '10001',
            'password' => \Illuminate\Support\Facades\Hash::make('Admin!741'),
            'profile_id' => $adminProfileId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default Staff for testing
        $staffProfileId = \Illuminate\Support\Facades\DB::table('profiles')->insertGetId([
            'first_name' => 'Staff',
            'last_name' => 'Member',
            'username' => '10002',
            'email' => 'staff@example.com',
            'roles_id' => 'sta',
            'schools_id' => 'sc_ci',
            'departments_id' => 'dep_cse',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'username' => '10002',
            'password' => \Illuminate\Support\Facades\Hash::make('Staff@852'),
            'profile_id' => $staffProfileId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default Student for testing
        $studentProfileId = \Illuminate\Support\Facades\DB::table('profiles')->insertGetId([
            'first_name' => 'Student',
            'last_name' => 'One',
            'username' => 'student1',
            'email' => 'student@example.com',
            'roles_id' => 'stu',
            'schools_id' => 'sc_ci',
            'departments_id' => 'dep_cse',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'username' => 'student1',
            'password' => \Illuminate\Support\Facades\Hash::make('Student#963'),
            'profile_id' => $studentProfileId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
