<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make schedules.room_id nullable to allow schedules without rooms
        try {
            DB::statement('ALTER TABLE `schedules` MODIFY `room_id` BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if column already nullable or table/column not present
        }
    }

    public function down(): void
    {
        // Best-effort revert: set room_id back to NOT NULL if desired
        try {
            DB::statement('ALTER TABLE `schedules` MODIFY `room_id` BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
            // Ignore if cannot revert safely
        }
    }
};


