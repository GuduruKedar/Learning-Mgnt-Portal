<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assignment_questions') && !Schema::hasColumn('assignment_questions', 'image')) {
            Schema::table('assignment_questions', function (Blueprint $table) {
                $table->string('image')->nullable()->after('question_text');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('assignment_questions') && Schema::hasColumn('assignment_questions', 'image')) {
            Schema::table('assignment_questions', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
