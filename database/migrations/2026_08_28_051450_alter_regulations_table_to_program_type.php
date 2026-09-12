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
            // Drop foreign key and column first
            if (Schema::hasColumn('regulations', 'program_id')) {
                // Ignore error if foreign key doesn't exist
                try { $table->dropForeign(['program_id']); } catch (\Exception $e) {}
                try { $table->dropUnique(['program_id', 'code']); } catch (\Exception $e) {}
                $table->dropColumn('program_id');
            }

            // Add program_type
            if (!Schema::hasColumn('regulations', 'program_type')) {
                $table->string('program_type')->after('id');
            }
            
            // Add new unique constraint
            try { $table->unique(['program_type', 'code']); } catch (\Exception $e) {}
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
            $table->dropUnique(['program_type', 'code']);
            $table->dropColumn('program_type');

            $table->foreignId('program_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['program_id', 'code']);
        });
    }
};
