<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Assignments Table Indexes
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                if (!$this->hasIndex('assignments', 'assignments_status_index')) {
                    $table->index('status', 'assignments_status_index');
                }
                if (!$this->hasIndex('assignments', 'assignments_due_date_index')) {
                    $table->index('due_date', 'assignments_due_date_index');
                }
                if (!$this->hasIndex('assignments', 'assignments_course_status_index')) {
                    $table->index(['course_id', 'status'], 'assignments_course_status_index');
                }
            });
        }

        // 2. Course Materials Table Indexes
        if (Schema::hasTable('course_materials')) {
            Schema::table('course_materials', function (Blueprint $table) {
                if (!$this->hasIndex('course_materials', 'course_materials_type_index')) {
                    $table->index('type', 'course_materials_type_index');
                }
                if (!$this->hasIndex('course_materials', 'course_materials_course_type_index')) {
                    $table->index(['course_id', 'type'], 'course_materials_course_type_index');
                }
            });
        }

        // 3. Profiles Table Indexes
        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                if (!$this->hasIndex('profiles', 'profiles_roles_dept_index')) {
                    $table->index(['roles_id', 'departments_id'], 'profiles_roles_dept_index');
                }
            });
        }

        // 4. Courses Table Indexes
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'year') && !$this->hasIndex('courses', 'courses_dept_year_index')) {
                    $table->index(['department_id', 'year'], 'courses_dept_year_index');
                }
            });
        }

        // 5. Civil Service Enrollments Table Indexes
        if (Schema::hasTable('civil_service_enrollments')) {
            Schema::table('civil_service_enrollments', function (Blueprint $table) {
                if (!$this->hasIndex('civil_service_enrollments', 'cse_user_status_index')) {
                    $table->index(['user_id', 'status'], 'cse_user_status_index');
                }
            });
        }

        // 6. Activity Logs Table Indexes
        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                if (!$this->hasIndex('activity_logs', 'activity_logs_created_at_index')) {
                    $table->index('created_at', 'activity_logs_created_at_index');
                }
                if (!$this->hasIndex('activity_logs', 'activity_logs_action_index')) {
                    $table->index('action', 'activity_logs_action_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropIndex('assignments_status_index');
                $table->dropIndex('assignments_due_date_index');
                $table->dropIndex('assignments_course_status_index');
            });
        }

        if (Schema::hasTable('course_materials')) {
            Schema::table('course_materials', function (Blueprint $table) {
                $table->dropIndex('course_materials_type_index');
                $table->dropIndex('course_materials_course_type_index');
            });
        }

        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                $table->dropIndex('profiles_roles_dept_index');
            });
        }

        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropIndex('courses_dept_year_index');
            });
        }

        if (Schema::hasTable('civil_service_enrollments')) {
            Schema::table('civil_service_enrollments', function (Blueprint $table) {
                $table->dropIndex('cse_user_status_index');
            });
        }

        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropIndex('activity_logs_created_at_index');
                $table->dropIndex('activity_logs_action_index');
            });
        }
    }

    /**
     * Helper to check if an index exists on a table.
     */
    protected function hasIndex(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEXES FROM `{$table}` WHERE Key_name = '{$indexName}'");
            return !empty($indexes);
        } catch (\Throwable $e) {
            return false;
        }
    }
};
