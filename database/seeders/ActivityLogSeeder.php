<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        // Don't duplicate if already seeded heavily, or clear
        DB::table('activity_logs')->truncate();

        $departments = Department::with('school')->get();
        $users = User::with('profile.role')->get();

        $modules = [
            'Academics' => [
                ['action' => 'course_created', 'title' => 'New Course Created', 'severity' => 'success', 'desc' => 'Created course %s for curriculum regulation.'],
                ['action' => 'course_allocated', 'title' => 'Course Allocated to Faculty', 'severity' => 'info', 'desc' => 'Assigned faculty %s to teach course %s.'],
                ['action' => 'regulation_added', 'title' => 'Academic Regulation Added', 'severity' => 'success', 'desc' => 'Published new syllabus regulation %s.'],
                ['action' => 'course_updated', 'title' => 'Course Curriculum Modified', 'severity' => 'info', 'desc' => 'Updated syllabus and credits for %s.'],
            ],
            'Assignments' => [
                ['action' => 'assignment_created', 'title' => 'Assignment Published', 'severity' => 'success', 'desc' => 'Published assignment "%s" with due date in 7 days.'],
                ['action' => 'assignment_submitted', 'title' => 'Student Assignment Submitted', 'severity' => 'success', 'desc' => 'Student submitted response for assignment %s.'],
                ['action' => 'assignment_evaluated', 'title' => 'Submissions Graded', 'severity' => 'info', 'desc' => 'Evaluated and posted scores for %s submissions in %s.'],
                ['action' => 'late_submission_warning', 'title' => 'Late Submission Attempt', 'severity' => 'warning', 'desc' => 'Attempted submission after deadline for %s.'],
            ],
            'Materials' => [
                ['action' => 'material_uploaded', 'title' => 'Lecture Notes Uploaded', 'severity' => 'success', 'desc' => 'Uploaded lecture slide/PDF "%s" for students.'],
                ['action' => 'material_downloaded', 'title' => 'Study Material Downloaded', 'severity' => 'info', 'desc' => 'Downloaded reference document %s.'],
                ['action' => 'material_removed', 'title' => 'Course Resource Deleted', 'severity' => 'warning', 'desc' => 'Archived and removed outdated resource %s.'],
            ],
            'Students' => [
                ['action' => 'student_registered', 'title' => 'New Student Onboarded', 'severity' => 'success', 'desc' => 'Registered new student %s with Roll No %s.'],
                ['action' => 'bulk_upload_success', 'title' => 'Bulk Student Upload Completed', 'severity' => 'success', 'desc' => 'Successfully imported 45 students from Excel/CSV file.'],
                ['action' => 'student_course_enrolled', 'title' => 'Student Enrolled in Elective', 'severity' => 'info', 'desc' => 'Student registered for elective course %s.'],
                ['action' => 'student_profile_updated', 'title' => 'Student Details Updated', 'severity' => 'info', 'desc' => 'Updated contact information and address for %s.'],
            ],
            'Authentication' => [
                ['action' => 'user_login', 'title' => 'User Logged In', 'severity' => 'info', 'desc' => 'Authenticated successfully via web session.'],
                ['action' => 'password_reset_success', 'title' => 'Password Reset by Admin', 'severity' => 'warning', 'desc' => 'Password reset requested and updated for user %s.'],
                ['action' => 'failed_login_warning', 'title' => 'Failed Login Attempt', 'severity' => 'danger', 'desc' => 'Multiple invalid password attempts detected from IP %s.'],
                ['action' => 'user_logout', 'title' => 'User Session Terminated', 'severity' => 'info', 'desc' => 'User logged out cleanly from system.'],
            ],
            'Civil Services' => [
                ['action' => 'civil_student_enrolled', 'title' => 'Civil Service Batch Enrolled', 'severity' => 'success', 'desc' => 'Enrolled student %s into UPSC Prelims training batch.'],
                ['action' => 'civil_module_added', 'title' => 'Civil Services Module Created', 'severity' => 'info', 'desc' => 'Created study module "%s" for General Studies.'],
            ],
            'Security' => [
                ['action' => 'role_permission_checked', 'title' => 'Security Audit Check', 'severity' => 'info', 'desc' => 'Audit check passed for coordinator permissions.'],
                ['action' => 'unauthorized_access_prevented', 'title' => 'Unauthorized Access Blocked', 'severity' => 'danger', 'desc' => 'Restricted endpoint access attempt blocked for role %s.'],
            ]
        ];

        $sampleEntities = [
            'Data Structures & Algorithms',
            'Advanced Database Management',
            'Full Stack Web Development',
            'Machine Learning & Neural Networks',
            'Structural Analysis & Concrete Tech',
            'Thermodynamics & Heat Transfer',
            'VLSI Design & Embedded Systems',
            'Digital Signal Processing',
            'Cloud Computing & DevOps',
            'Cyber Security & Cryptography',
            'Object Oriented Programming in Java',
            'Linear Algebra & Calculus'
        ];

        $sampleStudents = [
            ['name' => 'Sai Krishna', 'roll' => '231SA07001'],
            ['name' => 'Nagababu G', 'roll' => '231FA08001'],
            ['name' => 'Student One', 'roll' => 'student1'],
            ['name' => 'Ananya Sharma', 'roll' => '231FA04012'],
            ['name' => 'Rahul Verma', 'roll' => '231FA05023'],
            ['name' => 'Pooja Reddy', 'roll' => '231FA06045'],
            ['name' => 'Kedar Guduru', 'roll' => '231FA07089'],
            ['name' => 'Venkata Rao', 'roll' => '231FA09015'],
        ];

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:129.0) Gecko/20100101 Firefox/129.0',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148',
        ];

        $logs = [];

        // Generate ~140 realistic logs spanning 30 days
        $now = Carbon::now();

        for ($i = 0; $i < 150; $i++) {
            $daysAgo = rand(0, 28);
            $hoursAgo = rand(0, 23);
            $minutesAgo = rand(0, 59);
            $logTime = $now->copy()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes($minutesAgo);

            // Select a department (or null for global SA/System logs)
            $dept = (rand(1, 100) > 15 && $departments->isNotEmpty()) ? $departments->random() : null;
            $schoolCode = $dept ? $dept->school_id : 'sc_ci';
            $deptCode = $dept ? $dept->code : 'dep_it';

            // Select module
            $moduleKeys = array_keys($modules);
            $selectedModule = $moduleKeys[array_rand($moduleKeys)];
            $actionTemplates = $modules[$selectedModule];
            $selectedActionTemplate = $actionTemplates[array_rand($actionTemplates)];

            $entityName = $sampleEntities[array_rand($sampleEntities)];
            $student = $sampleStudents[array_rand($sampleStudents)];
            $ip = '192.168.1.' . rand(10, 250);

            // Format description
            $desc = $selectedActionTemplate['desc'];
            if (str_contains($desc, '%s')) {
                $desc = sprintf(
                    str_replace(['%s', '%s', '%s'], ['%s', '%s', '%s'], $desc),
                    $entityName,
                    $student['roll'] ?? $student['name'],
                    $ip
                );
            }

            // Pick a user
            $user = $users->isNotEmpty() ? $users->random() : null;
            $userName = $user ? trim(($user->profile->first_name ?? '') . ' ' . ($user->profile->last_name ?? '')) : 'Super Admin';
            if (empty($userName)) $userName = 'Admin User';
            $userRole = $user?->role ?? 'admin';

            $logs[] = [
                'user_id'       => $user?->id,
                'department_id' => $deptCode,
                'school_id'     => $schoolCode,
                'user_name'     => $userName,
                'user_role'     => $userRole,
                'action'        => $selectedActionTemplate['action'],
                'action_title'  => $selectedActionTemplate['title'],
                'module'        => $selectedModule,
                'severity'      => $selectedActionTemplate['severity'],
                'description'   => $desc,
                'entity_type'   => $selectedModule,
                'entity_id'     => (string)rand(1, 50),
                'entity_name'   => $entityName,
                'ip_address'    => $ip,
                'user_agent'    => $userAgents[array_rand($userAgents)],
                'method'        => in_array($selectedActionTemplate['severity'], ['warning', 'danger']) ? 'POST' : (rand(0, 1) ? 'GET' : 'POST'),
                'url'           => 'http://127.0.0.1:8000/' . strtolower(str_replace(' ', '-', $selectedModule)),
                'payload'       => json_encode([
                    'status' => 200,
                    'action_code' => $selectedActionTemplate['action'],
                    'department' => $dept ? $dept->name : 'Information Technology',
                    'execution_time_ms' => rand(15, 140),
                    'affected_records' => rand(1, 5),
                    'metadata' => [
                        'browser' => 'Chrome 128 on Windows',
                        'location' => 'Campus Intranet / Secure Gateway'
                    ]
                ]),
                'created_at'    => $logTime,
                'updated_at'    => $logTime,
            ];
        }

        // Sort descending by created_at
        usort($logs, function($a, $b) {
            return $b['created_at']->timestamp <=> $a['created_at']->timestamp;
        });

        foreach (array_chunk($logs, 50) as $chunk) {
            DB::table('activity_logs')->insert($chunk);
        }
    }
}
