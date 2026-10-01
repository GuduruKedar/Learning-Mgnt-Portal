<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\AssignmentQuestion;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Regulation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegulationCourseProtectedDeletionTest extends TestCase
{
    protected $adminUser;
    protected $staffUser;
    protected $studentUser;
    protected $department;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // Super Admin / Admin User
        $this->adminUser = User::whereHas('profile', function ($q) { $q->where('roles_id', 'sa'); })->first() 
            ?? User::whereHas('profile', function ($q) { $q->where('roles_id', 'admin'); })->first();

        // Staff User
        $this->staffUser = User::whereHas('profile', function ($q) { $q->where('roles_id', 'sta'); })->first();

        // Student User
        $this->studentUser = User::whereHas('profile', function ($q) { $q->where('roles_id', 'stu'); })->first();

        $this->department = Department::first();
    }

    public function test_cannot_delete_regulation_with_active_courses_via_controller()
    {
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'R_TEST_BLOCK_' . time(),
            'name' => 'R_TEST_BLOCK_' . time(),
            'curriculum' => 'C24',
            'status' => 'Active',
        ]);

        $course = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $this->department ? $this->department->code : 'dep_cse',
            'code' => 'CS_TEST_' . time(),
            'name' => 'Test Course For Protection',
            'year' => 2,
            'semester' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('academic.regulations.destroy', $regulation->id));

        $response->assertSessionHas('error', "Cannot delete regulation: You have not deleted its 1 course(s) yet. Please un-enroll students and delete all associated courses first before removing this regulation.");
        $this->assertDatabaseHas('regulations', ['id' => $regulation->id]);

        // Clean up
        $course->delete();
        $regulation->delete();
    }

    public function test_cannot_delete_regulation_with_active_courses_via_eloquent_model_hook()
    {
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'R_HOOK_TEST_' . time(),
            'name' => 'R_HOOK_TEST_' . time(),
            'curriculum' => 'C24',
            'status' => 'Active',
        ]);

        $course = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $this->department ? $this->department->code : 'dep_cse',
            'code' => 'CS_HOOK_' . time(),
            'name' => 'Test Course For Model Hook',
            'year' => 2,
            'semester' => 1,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cannot delete regulation: You have not deleted its 1 course(s) yet. Please un-enroll students and delete all associated courses first before removing this regulation.");

        $regulation->delete();
    }

    public function test_course_deletion_cascades_and_cleans_up_all_child_entities()
    {
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'R_CASCADE_' . time(),
            'name' => 'R_CASCADE_' . time(),
            'curriculum' => 'C24',
            'status' => 'Active',
        ]);

        $course = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $this->department ? $this->department->code : 'dep_cse',
            'code' => 'CS_CASCADE_' . time(),
            'name' => 'Cascade Course Test',
            'year' => 2,
            'semester' => 1,
        ]);

        // 1. Enroll student
        if ($this->studentUser) {
            $course->enrollments()->attach($this->studentUser->id);
        }

        // 2. Allocate staff
        if ($this->staffUser) {
            $course->staff()->attach($this->staffUser->id);
        }

        // 3. Upload Course Material with physical file
        $materialFilePath = 'course_materials/test_file_' . time() . '.pdf';
        Storage::disk('public')->put($materialFilePath, 'Dummy PDF content');
        
        $material = CourseMaterial::create([
            'course_id' => $course->id,
            'staff_id' => $this->staffUser ? $this->staffUser->id : $this->adminUser->id,
            'type' => 'file',
            'platform' => 'pdf',
            'title' => 'Lecture Note 1',
            'url_or_path' => $materialFilePath,
        ]);

        // 4. Create Assignment with attachment, questions, submissions, answers
        $assignmentAttachment = 'assignments/test_attachment_' . time() . '.pdf';
        Storage::disk('public')->put($assignmentAttachment, 'Dummy Assignment Attachment');

        $assignment = Assignment::create([
            'course_id' => $course->id,
            'staff_id' => $this->staffUser ? $this->staffUser->id : $this->adminUser->id,
            'title' => 'Midterm MCQ Test',
            'description' => 'Test MCQ assignment',
            'max_marks' => 10,
            'due_date' => now()->addDays(5),
            'attachment_path' => $assignmentAttachment,
            'status' => 'published',
        ]);

        $question = AssignmentQuestion::create([
            'assignment_id' => $assignment->id,
            'question_text' => 'What is 2 + 2?',
            'option_a' => '1',
            'option_b' => '2',
            'option_c' => '3',
            'option_d' => '4',
            'correct_option' => 'D',
            'marks' => 2,
            'explanation' => 'Arithmetic',
            'order' => 1,
        ]);

        $submissionFile = 'submissions/test_submission_' . time() . '.pdf';
        Storage::disk('public')->put($submissionFile, 'Student Answer PDF');

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $this->studentUser ? $this->studentUser->id : $this->adminUser->id,
            'submission_text' => 'My submission text',
            'file_path' => $submissionFile,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        $answer = AssignmentAnswer::create([
            'submission_id' => $submission->id,
            'question_id' => $question->id,
            'selected_option' => 'D',
            'is_correct' => true,
            'marks_awarded' => 2,
        ]);

        // Perform deletion via AcademicController destroyCourse route
        $response = $this->actingAs($this->adminUser)
            ->delete(route('academic.courses.destroy', $course->id));

        $response->assertSessionHas('success');

        // Verify Course and all child records are removed
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('course_staff', ['course_id' => $course->id]);
        $this->assertDatabaseMissing('course_materials', ['id' => $material->id]);
        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
        $this->assertDatabaseMissing('assignment_questions', ['id' => $question->id]);
        $this->assertDatabaseMissing('assignment_submissions', ['id' => $submission->id]);
        $this->assertDatabaseMissing('assignment_answers', ['id' => $answer->id]);

        // Verify physical files were removed from disk
        Storage::disk('public')->assertMissing($materialFilePath);
        Storage::disk('public')->assertMissing($assignmentAttachment);
        Storage::disk('public')->assertMissing($submissionFile);

        // Verify Activity Log recorded with severity 'info'
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'course_deleted',
            'severity' => 'info',
            'entity_id' => $course->id,
        ]);

        // Clean up regulation
        $regulation->delete();
    }

    public function test_can_delete_regulation_once_all_courses_are_deleted()
    {
        $regulation = Regulation::create([
            'program_type' => 'B.Tech',
            'code' => 'R_CLEARED_' . time(),
            'name' => 'R_CLEARED_' . time(),
            'curriculum' => 'C24',
            'status' => 'Active',
        ]);

        $course = Course::create([
            'regulation_id' => $regulation->id,
            'department_id' => $this->department ? $this->department->code : 'dep_cse',
            'code' => 'CS_CLEAR_' . time(),
            'name' => 'Clearable Course',
            'year' => 2,
            'semester' => 1,
        ]);

        // Delete the course first
        $course->delete();

        // Now delete the regulation via controller
        $response = $this->actingAs($this->adminUser)
            ->delete(route('academic.regulations.destroy', $regulation->id));

        $response->assertSessionHas('success', 'Regulation deleted successfully.');
        $this->assertDatabaseMissing('regulations', ['id' => $regulation->id]);

        // Verify Activity Log recorded with severity 'info'
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'regulation_deleted',
            'severity' => 'info',
            'entity_id' => $regulation->id,
        ]);
    }

    public function test_student_deletion_records_activity_log_with_info_severity()
    {
        $stuUsername = '241FA07999';
        $profile = Profile::create([
            'first_name' => 'Temp',
            'last_name' => 'Student',
            'username' => $stuUsername,
            'email' => 'temp_stu_delete@vignan.ac.in',
            'roles_id' => 'stu',
            'departments_id' => $this->department ? $this->department->code : 'dep_cse',
        ]);
        $tempStudent = User::create([
            'username' => $stuUsername,
            'password' => bcrypt('password'),
            'profile_id' => $profile->id,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('students.destroy', $tempStudent->id));

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('users', ['id' => $tempStudent->id]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'student_deleted',
            'severity' => 'info',
            'entity_name' => 'Temp Student',
        ]);
    }

    public function test_regulations_view_renders_protected_modal_and_manage_button()
    {
        $response = $this->actingAs($this->adminUser)->get(route('academic.regulations'));

        $response->assertStatus(200);
        $response->assertSee('id="protectedRegulationModal"', false);
        $response->assertSee('openProtectedRegulationModal', false);
        $response->assertSee('Manage Courses', false);
    }

    public function test_student_with_active_enrollments_or_civil_services_cannot_be_deleted()
    {
        $stuUsername = '241FA' . rand(10000, 99999);
        $profile = Profile::create([
            'first_name' => 'Enrolled',
            'last_name' => 'Student',
            'username' => $stuUsername,
            'email' => 'enrolled_' . uniqid() . '@vignan.ac.in',
            'roles_id' => 'stu',
            'departments_id' => $this->department ? $this->department->code : 'dep_cse',
        ]);
        $enrolledStudent = User::create([
            'username' => $stuUsername,
            'password' => bcrypt('password'),
            'profile_id' => $profile->id,
        ]);

        $course = Course::first();
        if (!$course) {
            $reg = Regulation::first() ?? Regulation::create([
                'program_type' => 'B.Tech',
                'code' => 'R_TMP_' . rand(100, 999),
                'status' => 'Active',
            ]);
            $course = Course::create([
                'regulation_id' => $reg->id,
                'department_id' => $this->department ? $this->department->code : 'dep_cse',
                'code' => 'CS_ENR_' . rand(100, 999),
                'name' => 'Enrollment Test Course',
                'year' => 1,
                'semester' => 1,
            ]);
        }

        // 1. Enroll into a course
        \Illuminate\Support\Facades\DB::table('enrollments')->insert([
            'user_id' => $enrolledStudent->id,
            'course_id' => $course->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Attempt deletion while enrolled in course
        $response = $this->actingAs($this->adminUser)
            ->delete(route('students.destroy', $enrolledStudent->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $enrolledStudent->id]);

        // 2. Unenroll from course and enroll in Civil Services
        \Illuminate\Support\Facades\DB::table('enrollments')->where('user_id', $enrolledStudent->id)->delete();
        \App\Models\CivilServiceEnrollment::create([
            'user_id' => $enrolledStudent->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        // Attempt deletion while enrolled in Civil Services
        $responseCivil = $this->actingAs($this->adminUser)
            ->delete(route('students.destroy', $enrolledStudent->id));

        $responseCivil->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $enrolledStudent->id]);

        // 3. Unenroll from Civil Services and verify deletion succeeds
        \App\Models\CivilServiceEnrollment::where('user_id', $enrolledStudent->id)->delete();
        $responseSuccess = $this->actingAs($this->adminUser)
            ->delete(route('students.destroy', $enrolledStudent->id));

        $responseSuccess->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('users', ['id' => $enrolledStudent->id]);
    }

    public function test_students_view_renders_protected_student_modal()
    {
        $response = $this->actingAs($this->adminUser)->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertSee('id="protectedStudentModal"', false);
        $response->assertSee('openProtectedStudentModal', false);
    }
}
