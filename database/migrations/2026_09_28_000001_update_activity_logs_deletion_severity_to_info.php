<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::table('activity_logs')
            ->where(function ($q) {
                $q->where('action', 'like', '%deleted%')
                  ->orWhere('action', 'like', '%delete%')
                  ->orWhere('action_title', 'like', '%deleted%')
                  ->orWhere('action_title', 'like', '%delete%');
            })
            ->where('severity', 'danger')
            ->update(['severity' => 'info']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('activity_logs')
            ->where(function ($q) {
                $q->where('action', 'like', '%deleted%')
                  ->orWhere('action', 'like', '%delete%');
            })
            ->where('severity', 'info')
            ->update(['severity' => 'danger']);
    }
};
