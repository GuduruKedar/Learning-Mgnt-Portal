<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\CourseMaterial;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $materials = CourseMaterial::whereNull('division')->orWhere('division', '')->get();
        foreach ($materials as $material) {
            $material->division = $material->effective_division;
            $material->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for data backfill
    }
};
