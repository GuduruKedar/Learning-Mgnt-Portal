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

    // Course Materials (Staff only) - Defined before Admin routes to avoid wildcard conflict with Route::resource('staff')
    Route::middleware('role:sta')->group(function () {
        Route::get('/staff/courses', [\App\Http\Controllers\CourseMaterialController::class, 'myCourses'])->name('staff.courses.index');
        Route::get('/staff/courses/{course}', [\App\Http\Controllers\CourseMaterialController::class, 'index'])->name('staff.courses.materials');
        Route::post('/staff/courses/{course}/materials', [\App\Http\Controllers\CourseMaterialController::class, 'store'])->name('staff.courses.materials.store');
        Route::delete('/staff/materials/{material}', [\App\Http\Controllers\CourseMaterialController::class, 'destroy'])->name('staff.courses.materials.destroy');
    });
    
    // ==========================================
    // 1. Super Admin Routes (Role: sa)
    // ==========================================
    Route::middleware('role:sa')->group(function () {
        Route::resource('coordinators', \App\Http\Controllers\CoordinatorController::class);
        Route::get('/department-coordinators', [\App\Http\Controllers\CoordinatorController::class, 'departmentCoordinators'])->name('coordinators.departments_list');
        
        Route::get('/enrollment-insights', [\App\Http\Controllers\AcademicController::class, 'enrollmentInsights'])->name('academic.enrollment_insights');
    });

    // ==========================================
    // 2. Admin Routes (Role: admin)
    // Includes inherited permissions from Super Admin
    // ==========================================
    Route::middleware('role:sa,admin')->group(function () {
        Route::get('/schools/{school}/departments', [\App\Http\Controllers\CoordinatorController::class, 'getDepartments'])->name('schools.departments');
        Route::get('/departments/{department}/programs', [\App\Http\Controllers\CoordinatorController::class, 'getPrograms'])->name('departments.programs');
        
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
        });
    });

    // ==========================================
    // Global Authenticated Routes (All Roles)
    // ==========================================
    Route::get('/profile', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [DashboardController::class, 'editPassword'])->name('password.edit');
    Route::put('/password', [DashboardController::class, 'updatePassword'])->name('password.update');
});
