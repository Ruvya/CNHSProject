<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop existing foreign key if present
            try {
                $table->dropForeign(['room_id']);
            } catch (\Throwable $e) {
                // ignore if FK name differs or doesn't exist
            }

            // Make room_id nullable
            $table->foreignId('room_id')->nullable()->change();

            // Recreate FK with SET NULL on delete
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            try {
                $table->dropForeign(['room_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            // Revert to NOT NULL (this will fail if data contains NULLs; handle accordingly before rollback)
            $table->foreignId('room_id')->nullable(false)->change();

            // Restore cascade behavior
            $table->foreign('room_id')->references('id')->on('rooms')->cascadeOnDelete();
        });
    }
};


