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
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'section')) {
                $table->string('section')->nullable()->after('programs_id');
            }
            if (!Schema::hasColumn('profiles', 'academic_year')) {
                $table->unsignedTinyInteger('academic_year')->default(1)->after('section');
            }
            if (!Schema::hasColumn('profiles', 'semester')) {
                $table->unsignedTinyInteger('semester')->nullable()->after('academic_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['section', 'academic_year', 'semester']);
        });
    }
};
