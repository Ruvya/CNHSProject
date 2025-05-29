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
        Schema::table('students', function (Blueprint $table) {
            // Make the 'name' column nullable to fix the registration issue
            if (Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert the 'name' column back to NOT NULL (if needed)
            if (Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
        });
    }
};
