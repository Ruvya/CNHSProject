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
            // Add missing columns that are required for student registration

            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable()->after('student_id');
            }

            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }

            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }

            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable()->after('email');
            }

            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')->nullable()->after('password');
            }

            if (!Schema::hasColumn('students', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('gender');
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

            if (!Schema::hasColumn('students', 'lrn')) {
                $table->string('lrn')->nullable();
            }

            if (!Schema::hasColumn('students', 'grade_level')) {
                $table->string('grade_level')->nullable();
            }

            if (!Schema::hasColumn('students', 'section')) {
                $table->string('section')->nullable();
            }

            if (!Schema::hasColumn('students', 'track')) {
                $table->string('track')->nullable();
            }

            if (!Schema::hasColumn('students', 'strand')) {
                $table->string('strand')->nullable();
            }

            // Add the name column if it doesn't exist
            if (!Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable();
            }

            // Fix the old 'name' column to be nullable or drop it
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
            $columnsToCheck = [
                'first_name', 'middle_name', 'last_name', 'password', 'gender',
                'contact_number', 'address', 'parent_name', 'parent_contact', 'lrn'
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
