<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Course Materials & Assignments (Staff only) - Defined before Admin routes to avoid wildcard conflict with Route::resource('staff')
    Route::middleware('role:sta')->group(function () {
        Route::get('/staff/courses', [\App\Http\Controllers\CourseMaterialController::class, 'myCourses'])->name('staff.courses.index');
        Route::get('/staff/courses/{course}', [\App\Http\Controllers\CourseMaterialController::class, 'index'])->name('staff.courses.materials');
        Route::post('/staff/courses/{course}/materials', [\App\Http\Controllers\CourseMaterialController::class, 'store'])->name('staff.courses.materials.store');
        Route::delete('/staff/materials/{material}', [\App\Http\Controllers\CourseMaterialController::class, 'destroy'])->name('staff.courses.materials.destroy');

        // Staff Assignments
        Route::get('/staff/assignments', [\App\Http\Controllers\StaffAssignmentController::class, 'index'])->name('staff.assignments.index');
        Route::get('/staff/assignments/template', [\App\Http\Controllers\StaffAssignmentController::class, 'downloadTemplate'])->name('staff.assignments.template');
        Route::get('/staff/assignments/create', [\App\Http\Controllers\StaffAssignmentController::class, 'create'])->name('staff.assignments.create');
        Route::post('/staff/assignments', [\App\Http\Controllers\StaffAssignmentController::class, 'store'])->name('staff.assignments.store');
        Route::post('/staff/assignments/bulk-upload', [\App\Http\Controllers\StaffAssignmentController::class, 'bulkUpload'])->name('staff.assignments.bulk');
        Route::get('/staff/assignments/{assignment}', [\App\Http\Controllers\StaffAssignmentController::class, 'show'])->name('staff.assignments.show');
        Route::get('/staff/assignments/{assignment}/export-responses', [\App\Http\Controllers\StaffAssignmentController::class, 'exportResponses'])->name('staff.assignments.export');
        Route::get('/staff/assignments/{assignment}/edit', [\App\Http\Controllers\StaffAssignmentController::class, 'edit'])->name('staff.assignments.edit');
        Route::put('/staff/assignments/{assignment}', [\App\Http\Controllers\StaffAssignmentController::class, 'update'])->name('staff.assignments.update');
        Route::delete('/staff/assignments/{assignment}', [\App\Http\Controllers\StaffAssignmentController::class, 'destroy'])->name('staff.assignments.destroy');
        Route::post('/staff/assignments/{assignment}/questions', [\App\Http\Controllers\StaffAssignmentController::class, 'storeQuestion'])->name('staff.assignments.questions.store');
        Route::delete('/staff/assignments/questions/{question}', [\App\Http\Controllers\StaffAssignmentController::class, 'destroyQuestion'])->name('staff.assignments.questions.destroy');
    });
    
    // ==========================================
    // 1. Super Admin & SSH Admin Analytics Routes (Role: sa, ssh_admin)
    // ==========================================
    Route::middleware('role:sa,ssh_admin')->group(function () {
        Route::resource('coordinators', \App\Http\Controllers\CoordinatorController::class);
        Route::get('/department-coordinators', [\App\Http\Controllers\CoordinatorController::class, 'departmentCoordinators'])->name('coordinators.departments_list');
        
        Route::get('/enrollment-insights', [\App\Http\Controllers\AcademicController::class, 'enrollmentInsights'])->name('academic.enrollment_insights');
        
        // Activity & Audit Log Monitoring Dashboard
        Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity_logs.index');
        Route::get('/activity-logs/export', [\App\Http\Controllers\ActivityLogController::class, 'export'])->name('activity_logs.export');
        Route::get('/activity-logs/{id}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity_logs.show');
    });

    // ==========================================
    // Civil Services Admin Routes (Role: civil_admin, sa)
    // ==========================================
    Route::middleware('role:sa,civil_admin')->group(function () {
        Route::get('/civil-services/students', [\App\Http\Controllers\CivilAdminController::class, 'index'])->name('civil.students.index');
        Route::get('/civil-services/students/create', [\App\Http\Controllers\CivilAdminController::class, 'create'])->name('civil.students.create');
        Route::post('/civil-services/students', [\App\Http\Controllers\CivilAdminController::class, 'store'])->name('civil.students.store');
        Route::post('/civil-services/students/enroll-existing', [\App\Http\Controllers\CivilAdminController::class, 'enrollExisting'])->name('civil.students.enroll_existing');
        Route::delete('/civil-services/students/{id}/unenroll', [\App\Http\Controllers\CivilAdminController::class, 'unenroll'])->name('civil.students.unenroll');

        // Civil Services Courses & Modules (No Regulations Needed)
        Route::get('/civil-services/courses', [\App\Http\Controllers\CivilCourseController::class, 'index'])->name('civil.courses.index');
        Route::post('/civil-services/courses', [\App\Http\Controllers\CivilCourseController::class, 'store'])->name('civil.courses.store');
        Route::put('/civil-services/courses/{course}', [\App\Http\Controllers\CivilCourseController::class, 'update'])->name('civil.courses.update');
        Route::delete('/civil-services/courses/{course}', [\App\Http\Controllers\CivilCourseController::class, 'destroy'])->name('civil.courses.destroy');
        Route::get('/civil-services/courses/{course}/modules', [\App\Http\Controllers\CivilCourseController::class, 'modules'])->name('civil.courses.modules');
        Route::post('/civil-services/courses/{course}/modules', [\App\Http\Controllers\CivilCourseController::class, 'storeModule'])->name('civil.courses.modules.store');
        Route::delete('/civil-services/courses/{course}/modules/{material}', [\App\Http\Controllers\CivilCourseController::class, 'destroyModule'])->name('civil.courses.modules.destroy');
    });

    // ==========================================
    // SSH Department (Sciences & Humanities / First Year) Routes (Role: ssh_admin, sa)
    // ==========================================
    Route::middleware('role:sa,ssh_admin')->group(function () {
        Route::get('/ssh/dashboard', [\App\Http\Controllers\SshAdminController::class, 'index'])->name('ssh.dashboard');
        
        // 1st Year Students Lifecycle & Bulk Upload
        Route::get('/ssh/students', [\App\Http\Controllers\SshAdminController::class, 'students'])->name('ssh.students.index');
        Route::get('/ssh/students/template', [\App\Http\Controllers\SshAdminController::class, 'downloadStudentTemplate'])->name('ssh.students.template');
        Route::post('/ssh/students/bulk-upload', [\App\Http\Controllers\SshAdminController::class, 'bulkUploadStudents'])->name('ssh.students.bulk');
        Route::get('/ssh/students/create', [\App\Http\Controllers\SshAdminController::class, 'createStudent'])->name('ssh.students.create');
        Route::post('/ssh/students', [\App\Http\Controllers\SshAdminController::class, 'storeStudent'])->name('ssh.students.store');
        Route::get('/ssh/students/{id}/edit', [\App\Http\Controllers\SshAdminController::class, 'editStudent'])->name('ssh.students.edit');
        Route::put('/ssh/students/{id}', [\App\Http\Controllers\SshAdminController::class, 'updateStudent'])->name('ssh.students.update');
        Route::delete('/ssh/students/{id}', [\App\Http\Controllers\SshAdminController::class, 'destroyStudent'])->name('ssh.students.destroy');
        Route::get('/ssh/students-export', [\App\Http\Controllers\SshAdminController::class, 'exportStudents'])->name('ssh.students.export');

        // 1st Year Foundational Courses
        Route::get('/ssh/courses', [\App\Http\Controllers\SshAdminController::class, 'courses'])->name('ssh.courses.index');
        Route::get('/ssh/courses/create', [\App\Http\Controllers\SshAdminController::class, 'createCourse'])->name('ssh.courses.create');
        Route::post('/ssh/courses', [\App\Http\Controllers\SshAdminController::class, 'storeCourse'])->name('ssh.courses.store');
        Route::get('/ssh/courses/{id}/edit', [\App\Http\Controllers\SshAdminController::class, 'editCourse'])->name('ssh.courses.edit');
        Route::put('/ssh/courses/{id}', [\App\Http\Controllers\SshAdminController::class, 'updateCourse'])->name('ssh.courses.update');
        Route::delete('/ssh/courses/{id}', [\App\Http\Controllers\SshAdminController::class, 'destroyCourse'])->name('ssh.courses.destroy');

        // 1st Year Course Allocations
        Route::get('/ssh/course-allocations', [\App\Http\Controllers\SshAdminController::class, 'courseAllocations'])->name('ssh.courses.allocations');
        Route::post('/ssh/courses/{course}/allocate', [\App\Http\Controllers\SshAdminController::class, 'allocateStaff'])->name('ssh.courses.allocate');
        Route::delete('/ssh/courses/{course}/allocate/{staff}', [\App\Http\Controllers\SshAdminController::class, 'unallocateStaff'])->name('ssh.courses.unallocate');

        // S&H Faculty & Staff Management
        Route::get('/ssh/staff', [\App\Http\Controllers\SshAdminController::class, 'staff'])->name('ssh.staff.index');
        Route::get('/ssh/staff/create', [\App\Http\Controllers\SshAdminController::class, 'createStaff'])->name('ssh.staff.create');
        Route::post('/ssh/staff', [\App\Http\Controllers\SshAdminController::class, 'storeStaff'])->name('ssh.staff.store');
        Route::get('/ssh/staff/{id}/edit', [\App\Http\Controllers\SshAdminController::class, 'editStaff'])->name('ssh.staff.edit');
        Route::put('/ssh/staff/{id}', [\App\Http\Controllers\SshAdminController::class, 'updateStaff'])->name('ssh.staff.update');
        Route::delete('/ssh/staff/{id}', [\App\Http\Controllers\SshAdminController::class, 'destroyStaff'])->name('ssh.staff.destroy');

        // 1st Year Learning Materials & Assessments Oversight
        Route::get('/ssh/materials', [\App\Http\Controllers\SshAdminController::class, 'materials'])->name('ssh.materials.index');
        Route::post('/ssh/materials', [\App\Http\Controllers\SshAdminController::class, 'storeMaterial'])->name('ssh.materials.store');
        Route::delete('/ssh/materials/{id}', [\App\Http\Controllers\SshAdminController::class, 'destroyMaterial'])->name('ssh.materials.destroy');

        Route::get('/ssh/assignments', [\App\Http\Controllers\SshAdminController::class, 'assignments'])->name('ssh.assignments.index');
        Route::get('/ssh/assignments/create', [\App\Http\Controllers\SshAdminController::class, 'createAssignment'])->name('ssh.assignments.create');
        Route::post('/ssh/assignments', [\App\Http\Controllers\SshAdminController::class, 'storeAssignment'])->name('ssh.assignments.store');
        Route::delete('/ssh/assignments/{id}', [\App\Http\Controllers\SshAdminController::class, 'destroyAssignment'])->name('ssh.assignments.destroy');
        Route::get('/ssh/assignments/{id}/submissions', [\App\Http\Controllers\SshAdminController::class, 'assignmentSubmissions'])->name('ssh.assignments.submissions');
    });

    // ==========================================
    // 2. Admin Routes (Role: admin, sa, ssh_admin for shared utilities)
    // Includes inherited permissions from Super Admin
    // ==========================================
    Route::middleware('role:sa,admin,ssh_admin')->group(function () {
        Route::get('/schools/{school}/departments', [\App\Http\Controllers\CoordinatorController::class, 'getDepartments'])->name('schools.departments');
        Route::get('/departments/{department}/programs', [\App\Http\Controllers\CoordinatorController::class, 'getPrograms'])->name('departments.programs');
        Route::get('/departments/{department}/suggested-username', [\App\Http\Controllers\CoordinatorController::class, 'getSuggestedUsername'])->name('departments.suggested_username');
        
        // Admins and SAs have full CRUD on Students (destroy handled in controller)
        Route::resource('students', \App\Http\Controllers\StudentController::class)->except(['index', 'show']);

        // Reset User Password
        Route::post('/users/{id}/reset-password', [\App\Http\Controllers\DashboardController::class, 'resetUserPassword'])->name('users.reset-password');

        // Bulk Upload Routes
        Route::post('/bulk-upload', [\App\Http\Controllers\BulkUploadController::class, 'store'])->name('bulk-upload.store');
        Route::get('/upload-history', [\App\Http\Controllers\BulkUploadController::class, 'history'])->name('bulk-upload.history');
        Route::get('/bulk-upload/progress', [\App\Http\Controllers\BulkUploadController::class, 'progress'])->name('bulk-upload.progress');
        Route::get('/bulk-upload/template', [\App\Http\Controllers\BulkUploadController::class, 'downloadTemplate'])->name('bulk-upload.template');
        
        // Academic Management
        Route::get('/regulations', [\App\Http\Controllers\AcademicController::class, 'regulations'])->name('academic.regulations');
        Route::post('/regulations', [\App\Http\Controllers\AcademicController::class, 'storeRegulation'])->name('academic.regulations.store');
        Route::put('/regulations/{id}', [\App\Http\Controllers\AcademicController::class, 'updateRegulation'])->name('academic.regulations.update');
        Route::delete('/regulations/{id}', [\App\Http\Controllers\AcademicController::class, 'destroyRegulation'])->name('academic.regulations.destroy');
        
        Route::get('/courses', [\App\Http\Controllers\AcademicController::class, 'courses'])->name('academic.courses');
        Route::get('/courses/create', [\App\Http\Controllers\AcademicController::class, 'createCourse'])->name('academic.courses.create');
        Route::post('/courses', [\App\Http\Controllers\AcademicController::class, 'storeCourse'])->name('academic.courses.store');
        Route::get('/courses/{id}/edit', [\App\Http\Controllers\AcademicController::class, 'editCourse'])->name('academic.courses.edit');
        Route::put('/courses/{id}', [\App\Http\Controllers\AcademicController::class, 'updateCourse'])->name('academic.courses.update');
        Route::delete('/courses/{id}', [\App\Http\Controllers\AcademicController::class, 'destroyCourse'])->name('academic.courses.destroy');
        
        Route::get('/courses/allocations', [\App\Http\Controllers\AcademicController::class, 'courseAllocations'])->name('academic.courses.allocations');
        Route::post('/courses/{course}/allocate', [\App\Http\Controllers\AcademicController::class, 'allocateStaff'])->name('academic.courses.allocate');
        Route::delete('/courses/{course}/allocate/{staff}', [\App\Http\Controllers\AcademicController::class, 'unallocateStaff'])->name('academic.courses.unallocate');
        
        Route::resource('staff', \App\Http\Controllers\StaffController::class);
    });

    // ==========================================
    // 3. Staff Routes (Role: sta)
    // Includes inherited permissions from Super Admin and Admin
    // ==========================================
    Route::middleware('role:sa,admin,sta')->group(function () {
        // Staff can only View Students
        // Note: Defined AFTER Admin routes to prevent wildcard conflicts on resource routes!
        Route::resource('students', \App\Http\Controllers\StudentController::class)->only(['index', 'show']);
    });

    // ==========================================
    // 4. Student Routes (Role: stu)
    // Includes inherited permissions from all above roles
    // ==========================================
    Route::middleware('role:sa,admin,sta,stu')->group(function () {
        // Student specific routes
        Route::middleware('role:stu')->group(function () {
            Route::get('/student/enroll', [\App\Http\Controllers\StudentEnrollmentController::class, 'create'])->name('student.enrollment.create');
            Route::post('/student/enroll', [\App\Http\Controllers\StudentEnrollmentController::class, 'store'])->name('student.enrollment.store');
            Route::get('/student/enroll/courses', [\App\Http\Controllers\StudentEnrollmentController::class, 'fetchCourses'])->name('student.enrollment.fetchCourses');
            
            Route::get('/student/my-courses', [\App\Http\Controllers\StudentEnrollmentController::class, 'myCourses'])->name('student.courses.index');
            Route::get('/student/my-courses/{course}/materials', [\App\Http\Controllers\StudentEnrollmentController::class, 'courseMaterials'])->name('student.courses.materials');

            // Student Assignments
            Route::get('/student/assignments', [\App\Http\Controllers\StudentAssignmentController::class, 'index'])->name('student.assignments.index');
            Route::get('/student/assignments/{assignment}', [\App\Http\Controllers\StudentAssignmentController::class, 'show'])->name('student.assignments.show');
            Route::post('/student/assignments/{assignment}/submit', [\App\Http\Controllers\StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');
        });
    });

    // ==========================================
    // Global Authenticated Routes (All Roles)
    // ==========================================
    Route::get('/profile', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [DashboardController::class, 'editPassword'])->name('password.edit');
    Route::put('/password', [DashboardController::class, 'updatePassword'])->name('password.update');

    // Material Access & Direct Streaming Routes
    Route::get('/materials/{material}/view', [\App\Http\Controllers\CourseMaterialController::class, 'viewFile'])->name('materials.view');
    Route::get('/materials/{material}/raw', [\App\Http\Controllers\CourseMaterialController::class, 'rawFile'])->name('materials.raw');
    Route::get('/materials/{material}/download', [\App\Http\Controllers\CourseMaterialController::class, 'downloadFile'])->name('materials.download');
    Route::get('/storage/{path}', [\App\Http\Controllers\CourseMaterialController::class, 'serveStorage'])->where('path', '.*')->name('storage.serve');
});

