<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix students table
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                // Check and add columns that might be missing
                if (!Schema::hasColumn('students', 'first_name')) {
                    $table->string('first_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'middle_name')) {
                    $table->string('middle_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'last_name')) {
                    $table->string('last_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'email')) {
                    $table->string('email')->nullable()->unique();
                }
                if (!Schema::hasColumn('students', 'student_id')) {
                    $table->string('student_id')->nullable()->unique();
                }
                if (!Schema::hasColumn('students', 'password')) {
                    $table->string('password')->nullable();
                }
                if (!Schema::hasColumn('students', 'grade_level')) {
                    $table->string('grade_level')->nullable();
                }
                if (!Schema::hasColumn('students', 'gender')) {
                    $table->string('gender')->nullable();
                }
                if (!Schema::hasColumn('students', 'emergency_name')) {
                    $table->string('emergency_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'emergency_phone')) {
                    $table->string('emergency_phone')->nullable();
                }
                if (!Schema::hasColumn('students', 'emergency_relationship')) {
                    $table->string('emergency_relationship')->nullable();
                }
                if (!Schema::hasColumn('students', 'section')) {
                    $table->string('section')->nullable();
                }
                if (!Schema::hasColumn('students', 'advisor')) {
                    $table->string('advisor')->nullable();
                }
                if (!Schema::hasColumn('students', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('students', 'address')) {
                    $table->string('address')->nullable();
                }
            });
        } else {
            // Create students table if it doesn't exist
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable()->unique();
                $table->string('student_id')->nullable()->unique();
                $table->string('password')->nullable();
                $table->string('grade_level')->nullable();
                $table->string('gender')->nullable();
                // $table->string('emergency_name')->nullable();
                // $table->string('emergency_phone')->nullable();
                // $table->string('emergency_relationship')->nullable();
                // $table->string('section')->nullable();
                // $table->string('advisor')->nullable();
                // $table->string('phone')->nullable();
                // $table->string('address')->nullable();
                $table->timestamps();
            });
        }

        // Add other tables as needed
        // For example, fix admins table
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Fix registrars table
        if (!Schema::hasTable('registrars')) {
            Schema::create('registrars', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('profile_picture')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Fix announcements table
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->foreignId('user_id')->constrained();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // No down method needed
    }
};