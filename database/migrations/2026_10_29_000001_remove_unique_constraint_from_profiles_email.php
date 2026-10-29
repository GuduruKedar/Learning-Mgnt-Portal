<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Check existing indexes on profiles table
        $indexes = DB::select("SHOW INDEXES FROM profiles WHERE Key_name = 'profiles_email_unique'");
        if (!empty($indexes)) {
            DB::statement("ALTER TABLE `profiles` DROP INDEX `profiles_email_unique`");
        }

        // 2. Add normal index on email if not already indexed
        $emailIndexes = DB::select("SHOW INDEXES FROM profiles WHERE Column_name = 'email'");
        if (empty($emailIndexes)) {
            DB::statement("ALTER TABLE `profiles` ADD INDEX `profiles_email_index` (`email`)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $emailIndexes = DB::select("SHOW INDEXES FROM profiles WHERE Key_name = 'profiles_email_index'");
        if (!empty($emailIndexes)) {
            DB::statement("ALTER TABLE `profiles` DROP INDEX `profiles_email_index`");
        }

        $uniqueIndexes = DB::select("SHOW INDEXES FROM profiles WHERE Key_name = 'profiles_email_unique'");
        if (empty($uniqueIndexes)) {
            DB::statement("ALTER TABLE `profiles` ADD UNIQUE `profiles_email_unique` (`email`)");
        }
    }
};
