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
        Schema::table('teachers', function (Blueprint $table) {
            // Add subject column if it doesn't exist
            if (!Schema::hasColumn('teachers', 'subject')) {
                $table->string('subject')->nullable()->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Remove subject column if it exists
            if (Schema::hasColumn('teachers', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }
};
