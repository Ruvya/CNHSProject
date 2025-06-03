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
            // Remove units column since senior high school doesn't use units
            if (Schema::hasColumn('subjects', 'units')) {
                $table->dropColumn('units');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Add back units column if needed for rollback
            $table->integer('units')->default(3)->nullable()->after('grade_level');
        });
    }
};
