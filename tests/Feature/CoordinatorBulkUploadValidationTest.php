<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Role;
use App\Models\School;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class CoordinatorBulkUploadValidationTest extends TestCase
{
    protected function getItCoordinator(): User
    {
        $profile = Profile::firstOrCreate(
            ['username' => 'coord_it_test'],
            [
                'first_name' => 'IT',
                'last_name' => 'Coordinator',
                'email' => 'coord_it_test@vignan.ac.in',
                'roles_id' => 'admin',
                'schools_id' => 'sc_ci',
                'departments_id' => 'dep_it',
                'designation' => 'Department Coordinator',
            ]
        );

        return User::firstOrCreate(
            ['username' => 'coord_it_test'],
            [
                'password' => Hash::make('Admin!741'),
                'profile_id' => $profile->id,
            ]
        );
    }

    /**
     * Test: IT Coordinator CANNOT upload students belonging to CSE or other departments.
     */
    public function test_it_coordinator_cannot_upload_cse_student()
    {
        $coordinator = $this->getItCoordinator();

        // 251FA04099 is a CSE student (04 = CSE)
        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Computing & Informatics\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA04099\",\"Rohan\",\"\",\"Verma\",\"\",\"rohan.cse99@vignan.ac.in\",\"9876511199\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('cse_students.csv', $csvContent);

        // Clean up before test
        Profile::where('username', '251FA04099')->orWhere('email', 'rohan.cse99@vignan.ac.in')->forceDelete();
        User::where('username', '251FA04099')->delete();

        $response = $this->actingAs($coordinator)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertEquals(0, $data['imported']);

        // Check error message in errors list
        $foundDeptError = false;
        foreach ($data['errors'] as $err) {
            if (str_contains($err, 'assigned department') || str_contains($err, 'does not match the department') || str_contains($err, 'Information Technology')) {
                $foundDeptError = true;
                break;
            }
        }
        $this->assertTrue($foundDeptError, 'Expected cross-department registration error for IT coordinator uploading CSE student');

        // Confirm student was NOT created
        $this->assertNull(User::where('username', '251FA04099')->first());
    }

    /**
     * Test: IT Coordinator CANNOT upload students with a different School in the spreadsheet.
     */
    public function test_it_coordinator_cannot_upload_different_school()
    {
        $coordinator = $this->getItCoordinator();

        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Core Engineering\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA07098\",\"Kiran\",\"\",\"Sharma\",\"\",\"kiran.it98@vignan.ac.in\",\"9876511198\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('diff_school.csv', $csvContent);

        Profile::where('username', '251FA07098')->orWhere('email', 'kiran.it98@vignan.ac.in')->forceDelete();
        User::where('username', '251FA07098')->delete();

        $response = $this->actingAs($coordinator)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertEquals(0, $data['imported']);

        $foundSchoolError = false;
        foreach ($data['errors'] as $err) {
            if (str_contains($err, 'unauthorized') || str_contains($err, 'assigned school')) {
                $foundSchoolError = true;
                break;
            }
        }
        $this->assertTrue($foundSchoolError, 'Expected unauthorized school error for IT coordinator uploading Core Engineering school');

        $this->assertNull(User::where('username', '251FA07098')->first());
    }

    /**
     * Test: IT Coordinator CANNOT upload students with a different Department name in the spreadsheet.
     */
    public function test_it_coordinator_cannot_upload_different_department()
    {
        $coordinator = $this->getItCoordinator();

        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Computing & Informatics\",\"Mechanical Engineering\",\"UG\",\"B.Tech in Mechanical Engineering\",\"251FA07097\",\"Pawan\",\"\",\"Kumar\",\"\",\"pawan.it97@vignan.ac.in\",\"9876511197\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('diff_dept.csv', $csvContent);

        Profile::where('username', '251FA07097')->orWhere('email', 'pawan.it97@vignan.ac.in')->forceDelete();
        User::where('username', '251FA07097')->delete();

        $response = $this->actingAs($coordinator)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertEquals(0, $data['imported']);

        $foundDeptError = false;
        foreach ($data['errors'] as $err) {
            if (str_contains($err, 'unauthorized') || str_contains($err, 'assigned department') || str_contains($err, 'could not be found') || str_contains($err, 'does not match the department')) {
                $foundDeptError = true;
                break;
            }
        }
        $this->assertTrue($foundDeptError, 'Expected unauthorized department error');

        $this->assertNull(User::where('username', '251FA07097')->first());
    }

    /**
     * Test: IT Coordinator CAN successfully upload valid IT students (07 = IT).
     */
    public function test_it_coordinator_can_upload_valid_it_student()
    {
        $coordinator = $this->getItCoordinator();

        // 251FA07096 is a valid B.Tech IT student (07 = IT)
        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Computing & Informatics\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA07096\",\"Sravani\",\"\",\"Reddy\",\"\",\"sravani.it96@vignan.ac.in\",\"9876511196\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('valid_it_student.csv', $csvContent);

        Profile::where('username', '251FA07096')->orWhere('email', 'sravani.it96@vignan.ac.in')->forceDelete();
        User::where('username', '251FA07096')->delete();

        $response = $this->actingAs($coordinator)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, $data['imported']);

        $createdStudent = User::where('username', '251FA07096')->first();
        $this->assertNotNull($createdStudent);
        $this->assertEquals('dep_it', $createdStudent->profile->departments_id);
        $this->assertEquals('sc_ci', $createdStudent->profile->schools_id);
        $this->assertEquals('Sravani', $createdStudent->profile->first_name);
        $this->assertEquals('Reddy', $createdStudent->profile->last_name);

        // Clean up
        $createdStudent->profile()->forceDelete();
        $createdStudent->delete();
    }

    /**
     * Test: Comprehensive field validation (First Name == Last Name, Invalid Email, Invalid Phone).
     */
    public function test_field_validations_in_bulk_upload()
    {
        $coordinator = $this->getItCoordinator();

        // Row 1: same first & last name
        // Row 2: invalid email domain (yahoo.com)
        // Row 3: invalid phone (only 5 digits)
        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Computing & Informatics\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA07091\",\"Rohan\",\"\",\"Rohan\",\"\",\"rohan.it91@vignan.ac.in\",\"9876511191\",\"Student#963\"\n" .
            "\"School of Computing & Informatics\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA07092\",\"Deepak\",\"\",\"Kumar\",\"\",\"deepak@yahoo.com\",\"9876511192\",\"Student#963\"\n" .
            "\"School of Computing & Informatics\",\"Information Technology\",\"UG\",\"B.Tech in Information Technology\",\"251FA07093\",\"Tarun\",\"\",\"Gupta\",\"\",\"tarun.it93@vignan.ac.in\",\"12345\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('invalid_fields.csv', $csvContent);

        $response = $this->actingAs($coordinator)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertEquals(0, $data['imported']);
        $this->assertCount(3, $data['errors']);

        $allErrors = implode(' | ', $data['errors']);
        $this->assertTrue(str_contains($allErrors, 'different from') || str_contains($allErrors, 'Last Name'));
        $this->assertTrue(str_contains($allErrors, '@gmail.com') || str_contains($allErrors, '@vignan.ac.in'));
        $this->assertTrue(str_contains($allErrors, '10 digits'));
    }

    protected function getSshAdmin(): User
    {
        $profile = Profile::firstOrCreate(
            ['username' => 'sshadmin_test'],
            [
                'first_name' => 'SSH',
                'last_name' => 'Administrator',
                'email' => 'sshadmin_test@vignan.ac.in',
                'roles_id' => 'ssh_admin',
                'schools_id' => 'sc_ash',
                'departments_id' => 'dep_ssh',
                'designation' => 'Director - Sciences & Humanities',
            ]
        );

        return User::firstOrCreate(
            ['username' => 'sshadmin_test'],
            [
                'password' => Hash::make('SshAdmin@741'),
                'profile_id' => $profile->id,
            ]
        );
    }

    /**
     * Test: SSH Admin can bulk upload students across MULTIPLE departments (CSE, ECE, Mech) like Super Admin.
     */
    public function test_ssh_admin_can_bulk_upload_students_across_multiple_departments()
    {
        $sshAdmin = $this->getSshAdmin();

        // 251FA04081 (CSE), 251FA05082 (ECE), 251FA08083 (Mech)
        $csvContent = "school,department,level,program,register_number,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Computing & Informatics\",\"Computer Science & Engineering\",\"UG\",\"B.Tech in Computer Science & Engineering\",\"251FA04081\",\"Aarav\",\"\",\"Patel\",\"\",\"aarav.cse81@vignan.ac.in\",\"9876543081\",\"Student#963\"\n" .
            "\"School of Electrical, Electronics & Communication Engineering\",\"Electronics and Communication Engineering\",\"UG\",\"B.Tech in Electronics and Communication Engineering\",\"251FA05082\",\"Diya\",\"\",\"Menon\",\"\",\"diya.ece82@vignan.ac.in\",\"9876543082\",\"Student#963\"\n" .
            "\"School of Core Engineering\",\"Mechanical Engineering\",\"UG\",\"B.Tech in Mechanical Engineering\",\"251FA08083\",\"Kabir\",\"\",\"Singh\",\"\",\"kabir.mech83@vignan.ac.in\",\"9876543083\",\"Student#963\"\n";

        $file = UploadedFile::fake()->createWithContent('ssh_multi_dept_students.csv', $csvContent);

        // Clean up before test
        Profile::whereIn('username', ['251FA04081', '251FA05082', '251FA08083'])->forceDelete();
        User::whereIn('username', ['251FA04081', '251FA05082', '251FA08083'])->delete();

        $response = $this->actingAs($sshAdmin)->postJson('/bulk-upload', [
            'target_role' => 'stu',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(3, $data['imported']);

        // Verify students were created in their respective departments
        $cseStudent = User::where('username', '251FA04081')->first();
        $this->assertNotNull($cseStudent);
        $this->assertEquals('dep_cse', $cseStudent->profile->departments_id);

        $eceStudent = User::where('username', '251FA05082')->first();
        $this->assertNotNull($eceStudent);
        $this->assertEquals('dep_ece', $eceStudent->profile->departments_id);

        $mechStudent = User::where('username', '251FA08083')->first();
        $this->assertNotNull($mechStudent);
        $this->assertEquals('dep_mech', $mechStudent->profile->departments_id);

        // Clean up
        Profile::whereIn('username', ['251FA04081', '251FA05082', '251FA08083'])->forceDelete();
        User::whereIn('username', ['251FA04081', '251FA05082', '251FA08083'])->delete();
    }

    /**
     * Test: SSH Admin can bulk upload faculty members across S&H and other departments.
     */
    public function test_ssh_admin_can_bulk_upload_faculty()
    {
        $sshAdmin = $this->getSshAdmin();

        $csvContent = "school,department,designation,employee_id,firstname,middlename,lastname,photo,email,mobile_number,password\n" .
            "\"School of Applied Sciences and Humanities\",\"Mathematics\",\"Assistant Professor\",\"88001\",\"Dr Rajesh\",\"\",\"Sharma\",\"\",\"rajesh.math88001@vignan.ac.in\",\"9876548801\",\"Staff@852\"\n" .
            "\"School of Applied Sciences and Humanities\",\"Physics\",\"Associate Professor\",\"88002\",\"Dr Priya\",\"\",\"Nair\",\"\",\"priya.phys88002@vignan.ac.in\",\"9876548802\",\"Staff@852\"\n" .
            "\"School of Computing & Informatics\",\"Computer Science & Engineering\",\"Assistant Professor\",\"88003\",\"Vikram\",\"\",\"Verma\",\"\",\"vikram.cse88003@vignan.ac.in\",\"9876548803\",\"Staff@852\"\n";

        $file = UploadedFile::fake()->createWithContent('ssh_faculty_upload.csv', $csvContent);

        // Clean up before test
        Profile::whereIn('username', ['88001', '88002', '88003'])->forceDelete();
        User::whereIn('username', ['88001', '88002', '88003'])->delete();

        $response = $this->actingAs($sshAdmin)->postJson('/bulk-upload', [
            'target_role' => 'sta',
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(3, $data['imported']);

        // Verify faculty were created in their respective departments with role 'sta'
        $mathFaculty = User::where('username', '88001')->first();
        $this->assertNotNull($mathFaculty);
        $this->assertEquals('sta', $mathFaculty->profile->roles_id);
        $this->assertEquals('dep_maths', $mathFaculty->profile->departments_id);

        $physFaculty = User::where('username', '88002')->first();
        $this->assertNotNull($physFaculty);
        $this->assertEquals('dep_phy', $physFaculty->profile->departments_id);

        $cseFaculty = User::where('username', '88003')->first();
        $this->assertNotNull($cseFaculty);
        $this->assertEquals('dep_cse', $cseFaculty->profile->departments_id);

        // Clean up
        Profile::whereIn('username', ['88001', '88002', '88003'])->forceDelete();
        User::whereIn('username', ['88001', '88002', '88003'])->delete();
    }

    /**
     * Test: SSH Admin routes for template work smoothly.
     */
    public function test_ssh_admin_can_download_templates_and_samples()
    {
        $sshAdmin = $this->getSshAdmin();

        $templateResponse = $this->actingAs($sshAdmin)->get('/bulk-upload/template?role=stu');
        $templateResponse->assertStatus(200);

        $templateStaffResponse = $this->actingAs($sshAdmin)->get('/bulk-upload/template?role=sta');
        $templateStaffResponse->assertStatus(200);
    }
}
