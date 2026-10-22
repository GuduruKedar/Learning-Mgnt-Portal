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
        if (Schema::hasTable('assignment_questions')) {
            Schema::table('assignment_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('assignment_questions', 'type')) {
                    $table->string('type', 20)->default('mcq')->after('question_text')->index();
                }
                if (!Schema::hasColumn('assignment_questions', 'allowed_languages')) {
                    $table->string('allowed_languages', 255)->nullable()->after('type');
                }
                if (!Schema::hasColumn('assignment_questions', 'starter_code')) {
                    $table->longText('starter_code')->nullable()->after('allowed_languages');
                }
                if (!Schema::hasColumn('assignment_questions', 'test_cases')) {
                    $table->longText('test_cases')->nullable()->after('starter_code');
                }
            });

            // Make MCQ specific fields nullable in MySQL for coding questions
            try {
                DB::statement("ALTER TABLE `assignment_questions` MODIFY `option_a` TEXT NULL");
                DB::statement("ALTER TABLE `assignment_questions` MODIFY `option_b` TEXT NULL");
                DB::statement("ALTER TABLE `assignment_questions` MODIFY `option_c` TEXT NULL");
                DB::statement("ALTER TABLE `assignment_questions` MODIFY `option_d` TEXT NULL");
                DB::statement("ALTER TABLE `assignment_questions` MODIFY `correct_option` ENUM('A', 'B', 'C', 'D') NULL");
            } catch (\Exception $e) {
                // If already modified or different dialect, ignore
            }
        }

        if (Schema::hasTable('assignment_answers')) {
            Schema::table('assignment_answers', function (Blueprint $table) {
                if (!Schema::hasColumn('assignment_answers', 'code')) {
                    $table->longText('code')->nullable()->after('selected_option');
                }
                if (!Schema::hasColumn('assignment_answers', 'language')) {
                    $table->string('language', 50)->nullable()->after('code');
                }
                if (!Schema::hasColumn('assignment_answers', 'test_case_results')) {
                    $table->longText('test_case_results')->nullable()->after('language');
                }
                if (!Schema::hasColumn('assignment_answers', 'code_output')) {
                    $table->longText('code_output')->nullable()->after('test_case_results');
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
        if (Schema::hasTable('assignment_questions')) {
            Schema::table('assignment_questions', function (Blueprint $table) {
                $cols = ['type', 'allowed_languages', 'starter_code', 'test_cases'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('assignment_questions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('assignment_answers')) {
            Schema::table('assignment_answers', function (Blueprint $table) {
                $cols = ['code', 'language', 'test_case_results', 'code_output'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('assignment_answers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
