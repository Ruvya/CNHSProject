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
            // Only add columns that don't exist yet
            // Check current columns: id, first_name, middle_name, last_name, email, student_id, password, grade_level, gender, created_at, updated_at

            // Add the missing columns that are needed by the Student model
            if (!Schema::hasColumn('students', 'year_level')) {
                $table->string('year_level')->nullable()->after('grade_level');
            }
            if (!Schema::hasColumn('students', 'section')) {
                $table->string('section')->nullable()->after('grade_level');
            }
            if (!Schema::hasColumn('students', 'lrn')) {
                $table->string('lrn')->nullable()->after('section');
            }
            if (!Schema::hasColumn('students', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('lrn');
            }
            if (!Schema::hasColumn('students', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('profile_picture');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('students', 'parent_name')) {
                $table->string('parent_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'parent_contact')) {
                $table->string('parent_contact')->nullable()->after('parent_name');
            }
            if (!Schema::hasColumn('students', 'advisor')) {
                $table->string('advisor')->nullable()->after('parent_contact');
            }
            if (!Schema::hasColumn('students', 'track')) {
                $table->string('track')->nullable()->after('advisor');
            }
            if (!Schema::hasColumn('students', 'strand')) {
                $table->string('strand')->nullable()->after('track');
            }
            if (!Schema::hasColumn('students', 'province')) {
                $table->string('province')->nullable()->after('strand');
            }
            if (!Schema::hasColumn('students', 'municipality')) {
                $table->string('municipality')->nullable()->after('province');
            }
            if (!Schema::hasColumn('students', 'barangay')) {
                $table->string('barangay')->nullable()->after('municipality');
            }
            if (!Schema::hasColumn('students', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('barangay');
            }
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('permanent_address');
            }
            if (!Schema::hasColumn('students', 'social_media')) {
                $table->string('social_media')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('students', 'emergency_name')) {
                $table->string('emergency_name')->nullable()->after('social_media');
            }
            if (!Schema::hasColumn('students', 'emergency_phone')) {
                $table->string('emergency_phone')->nullable()->after('emergency_name');
            }
            if (!Schema::hasColumn('students', 'emergency_relationship')) {
                $table->string('emergency_relationship')->nullable()->after('emergency_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop only the columns that were added by this migration
            $columnsToCheck = [
                'year_level', 'section', 'lrn', 'profile_picture', 'contact_number',
                'address', 'parent_name', 'parent_contact', 'advisor', 'track',
                'strand', 'province', 'municipality', 'barangay', 'permanent_address',
                'phone', 'social_media', 'emergency_name', 'emergency_phone', 'emergency_relationship'
            ];

            $columnsToRemove = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $columnsToRemove[] = $column;
                }
            }

            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
