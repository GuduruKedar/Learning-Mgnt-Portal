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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 191)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('department_id')->nullable()->index();
            $table->string('school_id')->nullable()->index();
            $table->string('user_name')->nullable()->index();
            $table->string('user_role')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at')->nullable()->index();
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamp('logout_at')->nullable()->index();
            $table->unsignedBigInteger('duration_seconds')->default(0)->index();
            $table->unsignedInteger('actions_count')->default(1);
            $table->enum('status', ['active', 'logged_out', 'timed_out'])->default('active')->index();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['department_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};

