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
            // Add remember_token column if it doesn't exist
            if (!Schema::hasColumn('students', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove remember_token column if it exists
            if (Schema::hasColumn('students', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });
    }
};
