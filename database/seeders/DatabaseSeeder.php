<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CivilServiceEnrollment;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Schools, Departments, and Programs
        $this->call([
            SchoolAndDepartmentSeeder::class,
        ]);

        // 2. Ensure all Roles exist
        $roles = [
            'sa',
            'admin',
            'staff',
            'student',
            'sta',
            'stu',
            'civil_admin',
        ];

        foreach ($roles as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        // 3. Helper to seed/update Profile and User
        $seedUser = function ($username, $password, $roleId, $firstName, $lastName, $email, $schoolId = null, $deptId = null, $designation = null) {
            $profile = DB::table('profiles')->where('username', $username)->first();

            $profileData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'roles_id' => $roleId,
                'schools_id' => $schoolId,
                'departments_id' => $deptId,
                'designation' => $designation,
                'updated_at' => now(),
            ];

            if ($profile) {
                DB::table('profiles')->where('id', $profile->id)->update($profileData);
                $profileId = $profile->id;
            } else {
                $profileData['created_at'] = now();
                $profileId = DB::table('profiles')->insertGetId($profileData);
            }

            $user = DB::table('users')->where('username', $username)->first();
            $userData = [
                'username' => $username,
                'password' => Hash::make($password),
                'profile_id' => $profileId,
                'failed_login_attempts' => 0,
                'requires_password_reset' => 0,
                'updated_at' => now(),
            ];

            if ($user) {
                DB::table('users')->where('id', $user->id)->update($userData);
                $userId = $user->id;
            } else {
                $userData['created_at'] = now();
                $userId = DB::table('users')->insertGetId($userData);
            }

            return $userId;
        };

        // Super Admin
        $saUserId = $seedUser('superadmin', 'Vu_Super@123', 'sa', 'Super', 'Admin', 'superadmin@example.com', null, null, 'Super Administrator');

        // Coordinator / Admin
        $adminUserId = $seedUser('10001', 'Admin!741', 'admin', 'Admin', 'CSE', 'admincse@example.com', 'sc_ci', 'dep_cse', 'Coordinator');

        // Staff / Faculty
        $staffUserId = $seedUser('10002', 'Staff@852', 'sta', 'Staff', 'Member', 'staff@example.com', 'sc_ci', 'dep_cse', 'Assistant Professor');

        // Student
        $studentUserId = $seedUser('student1', 'Student#963', 'stu', 'Student', 'One', 'student@example.com', 'sc_ci', 'dep_cse', 'Student');

        // Civil Services Admin
        $civilAdminUserId = $seedUser('civiladmin', 'CivilAdmin@741', 'civil_admin', 'Civil', 'Admin', 'civiladmin@example.com', 'sc_cs', 'dep_cs', 'Civil Services Coordinator');

        // 4. Seed sample Civil Services courses & modules
        $courses = [
            [
                'code' => 'UPSC-GS1',
                'name' => 'General Studies I - Indian Heritage, Culture, History & Geography',
                'semester' => 'General Studies',
                'year' => '2026',
            ],
            [
                'code' => 'UPSC-GS2',
                'name' => 'General Studies II - Governance, Constitution, Polity & Social Justice',
                'semester' => 'General Studies',
                'year' => '2026',
            ],
            [
                'code' => 'UPSC-CSAT',
                'name' => 'Civil Services Aptitude Test (CSAT Paper II)',
                'semester' => 'Aptitude & Reasoning',
                'year' => '2026',
            ],
        ];

        foreach ($courses as $cData) {
            $course = Course::firstOrCreate(
                ['code' => $cData['code']],
                [
                    'department_id' => 'dep_cs',
                    'regulation_id' => null,
                    'name' => $cData['name'],
                    'year' => $cData['year'],
                    'semester' => $cData['semester'],
                ]
            );

            // Add sample reference module/material if none exists
            if ($course->materials()->count() === 0) {
                CourseMaterial::create([
                    'course_id' => $course->id,
                    'staff_id' => $civilAdminUserId,
                    'title' => 'Orientation Syllabus & High-Yield Topic Roadmap',
                    'type' => 'link',
                    'platform' => 'Reference Portal',
                    'url_or_path' => 'https://upsc.gov.in',
                ]);
            }
        }

        // 5. Enroll student1 in Civil Services for active demonstration
        CivilServiceEnrollment::firstOrCreate(
            ['user_id' => $studentUserId],
            [
                'batch_year' => '2026',
                'status' => 'active',
                'enrolled_at' => now(),
            ]
        );
    }
}
