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
        Schema::table('subjects', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('subjects', 'semester')) {
                $table->string('semester')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'department')) {
                $table->string('department')->nullable();
            }
            if (!Schema::hasColumn('subjects', 'is_master_subject')) {
                $table->boolean('is_master_subject')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the new columns if they exist
            if (Schema::hasColumn('subjects', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('subjects', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('subjects', 'is_master_subject')) {
                $table->dropColumn('is_master_subject');
            }
        });
    }
};
