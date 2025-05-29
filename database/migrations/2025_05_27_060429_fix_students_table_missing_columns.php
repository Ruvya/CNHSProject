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
            // Add is_temporary_account column if it doesn't exist
            if (!Schema::hasColumn('students', 'is_temporary_account')) {
                $table->boolean('is_temporary_account')->default(false)->after('password');
            }

            // Add profile_completed column if it doesn't exist
            if (!Schema::hasColumn('students', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false)->after('is_temporary_account');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('students', 'profile_completed')) {
                $table->dropColumn('profile_completed');
            }
            if (Schema::hasColumn('students', 'is_temporary_account')) {
                $table->dropColumn('is_temporary_account');
            }
        });
    }
};
