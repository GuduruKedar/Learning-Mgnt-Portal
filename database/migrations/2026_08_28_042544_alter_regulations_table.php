<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('regulations', 'program')) {
            Schema::table('regulations', function (Blueprint $table) {
                $table->dropColumn('program');
            });
        }
        if (Schema::hasColumn('regulations', 'curriculum')) {
            Schema::table('regulations', function (Blueprint $table) {
                $table->dropColumn('curriculum');
            });
        }
        if (Schema::hasColumn('regulations', 'name')) {
            Schema::table('regulations', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        Schema::table('regulations', function (Blueprint $table) {
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->default('Active');
            $table->unique(['program_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropUnique(['program_id', 'code']);
            $table->dropColumn(['program_id', 'name', 'status']);
            
            $table->renameColumn('code', 'name');
            $table->string('program')->nullable();
            $table->string('curriculum')->nullable();
        });
    }
};
