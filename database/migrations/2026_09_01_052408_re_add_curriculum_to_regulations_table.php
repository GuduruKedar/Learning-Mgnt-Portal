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
        Schema::table('regulations', function (Blueprint $table) {
            // Add curriculum column
            if (!Schema::hasColumn('regulations', 'curriculum')) {
                $table->string('curriculum')->nullable()->after('program_type');
            }

            // Drop old unique constraint and add new one
            try { $table->dropUnique(['program_type', 'code']); } catch (\Exception $e) {}
            try { $table->unique(['program_type', 'code', 'curriculum']); } catch (\Exception $e) {}
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
            try { $table->dropUnique(['program_type', 'code', 'curriculum']); } catch (\Exception $e) {}
            try { $table->unique(['program_type', 'code']); } catch (\Exception $e) {}
            
            if (Schema::hasColumn('regulations', 'curriculum')) {
                $table->dropColumn('curriculum');
            }
        });
    }
};
