<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('department_id')->nullable()->index();
            $table->string('school_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action'); // e.g. user_login, course_created, assignment_submitted
            $table->string('action_title'); // Short readable title
            $table->string('module')->index(); // Academics, Assignments, Materials, Students, Auth, etc.
            $table->enum('severity', ['info', 'success', 'warning', 'danger'])->default('info')->index();
            $table->text('description');
            $table->string('entity_type')->nullable(); // Course, Assignment, User, etc.
            $table->string('entity_id')->nullable();
            $table->string('entity_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method', 10)->nullable();
            $table->text('url')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
