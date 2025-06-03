<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDatabase extends Command
{
    protected $signature = 'db:fix';
    protected $description = 'Fix database structure issues';

    public function handle()
    {
        $this->info('Starting database fixes...');

        try {
            // 1. Create registrars table
            $this->info('1. Creating registrars table...');
            if (!Schema::hasTable('registrars')) {
                DB::statement("
                    CREATE TABLE registrars (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        first_name VARCHAR(255) NOT NULL,
                        last_name VARCHAR(255) NOT NULL,
                        email VARCHAR(255) UNIQUE NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        profile_picture VARCHAR(255) NULL,
                        remember_token VARCHAR(100) NULL,
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL
                    )
                ");

                // Insert default registrar
                DB::table('registrars')->insert([
                    'first_name' => 'Default',
                    'last_name' => 'Registrar',
                    'email' => 'registrar@school.com',
                    'password' => bcrypt('password123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info('   ✓ Registrars table created successfully!');
            } else {
                $this->info('   ✓ Registrars table already exists.');
            }

            // 2. Fix students table - rename student_id to id if needed
            $this->info('2. Fixing students table...');
            if (!Schema::hasColumn('students', 'id') && Schema::hasColumn('students', 'student_id')) {
                // First, check if student_id is the primary key
                $primaryKey = DB::select("SHOW KEYS FROM students WHERE Key_name = 'PRIMARY'");
                if (!empty($primaryKey) && $primaryKey[0]->Column_name === 'student_id') {
                    // Rename student_id to id
                    DB::statement("ALTER TABLE students CHANGE student_id id BIGINT UNSIGNED AUTO_INCREMENT");
                    $this->info('   ✓ Renamed student_id to id column');
                } else {
                    // Add id column and make it primary
                    DB::statement("ALTER TABLE students ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST");
                    $this->info('   ✓ Added id column to students table');
                }
            } else if (!Schema::hasColumn('students', 'id')) {
                DB::statement("ALTER TABLE students ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST");
                $this->info('   ✓ Added id column to students table');
            }

            // Add missing columns to students table
            $studentsColumns = [
                'student_id' => "VARCHAR(255) NULL UNIQUE",
                'first_name' => "VARCHAR(255) NULL",
                'middle_name' => "VARCHAR(255) NULL",
                'last_name' => "VARCHAR(255) NULL",
                'password' => "VARCHAR(255) NULL",
                'grade_level' => "VARCHAR(255) NULL",
                'gender' => "VARCHAR(255) NULL",
                'track' => "VARCHAR(255) NULL",
                'strand' => "VARCHAR(255) NULL",
                'section' => "VARCHAR(255) NULL",
                'contact_number' => "VARCHAR(255) NULL",
                'address' => "TEXT NULL",
                'parent_name' => "VARCHAR(255) NULL",
                'parent_contact' => "VARCHAR(255) NULL",
                'lrn' => "VARCHAR(255) NULL",
                'is_temporary_account' => "BOOLEAN DEFAULT FALSE"
            ];

            foreach ($studentsColumns as $column => $definition) {
                if (!Schema::hasColumn('students', $column)) {
                    DB::statement("ALTER TABLE students ADD COLUMN {$column} {$definition}");
                    $this->info("   ✓ Added {$column} column");
                }
            }

            // Make name column nullable if it exists
            if (Schema::hasColumn('students', 'name')) {
                DB::statement("ALTER TABLE students MODIFY COLUMN name VARCHAR(255) NULL");
            }

            $this->info('   ✓ Students table fixed successfully!');

            // 3. Create subjects table if it doesn't exist
            $this->info('3. Creating/fixing subjects table...');
            if (!Schema::hasTable('subjects')) {
                DB::statement("
                    CREATE TABLE subjects (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        code VARCHAR(255) UNIQUE NOT NULL,
                        grade_level VARCHAR(255) NOT NULL,
                        track VARCHAR(255) NOT NULL,
                        strand VARCHAR(255) NULL,
                        cluster VARCHAR(255) NULL,
                        specialization VARCHAR(255) NULL,
                        description TEXT NULL,
                        teacher_id BIGINT UNSIGNED NULL,
                        registrar_id BIGINT UNSIGNED NULL,
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL,
                        FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
                        FOREIGN KEY (registrar_id) REFERENCES registrars(id) ON DELETE SET NULL
                    )
                ");
                $this->info('   ✓ Subjects table created successfully!');
            } else {
                $this->info('   ✓ Subjects table already exists.');
            }

            // 4. Create sections table
            $this->info('4. Creating sections table...');
            if (!Schema::hasTable('sections')) {
                DB::statement("
                    CREATE TABLE sections (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        grade_level VARCHAR(255) NOT NULL,
                        track VARCHAR(255) NOT NULL,
                        strand VARCHAR(255) NULL,
                        max_capacity INT DEFAULT 40,
                        current_enrollment INT DEFAULT 0,
                        school_year VARCHAR(255) NOT NULL,
                        grading_period VARCHAR(255) NOT NULL,
                        adviser_id BIGINT UNSIGNED NULL,
                        room VARCHAR(255) NULL,
                        status ENUM('active', 'inactive', 'full') DEFAULT 'active',
                        notes TEXT NULL,
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL,
                        FOREIGN KEY (adviser_id) REFERENCES teachers(id) ON DELETE SET NULL,
                        UNIQUE KEY unique_section_per_term (name, school_year, grading_period)
                    )
                ");
                $this->info('   ✓ Sections table created successfully!');
            } else {
                $this->info('   ✓ Sections table already exists.');
            }

            // 5. Student assignments functionality has been removed
            $this->info('5. Student assignments functionality has been removed - skipping...');

            // 6. Create teacher_assignments table
            $this->info('6. Creating teacher_assignments table...');
            if (!Schema::hasTable('teacher_assignments')) {
                DB::statement("
                    CREATE TABLE teacher_assignments (
                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        teacher_id BIGINT UNSIGNED NOT NULL,
                        subject_id BIGINT UNSIGNED NOT NULL,
                        section_id BIGINT UNSIGNED NOT NULL,
                        school_year VARCHAR(255) NOT NULL,
                        grading_period VARCHAR(255) NOT NULL,
                        schedule JSON NULL,
                        assignment_date DATE NOT NULL,
                        status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
                        assigned_by BIGINT UNSIGNED NOT NULL,
                        notes TEXT NULL,
                        created_at TIMESTAMP NULL,
                        updated_at TIMESTAMP NULL,
                        FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
                        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
                        FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
                        FOREIGN KEY (assigned_by) REFERENCES registrars(id) ON DELETE CASCADE
                    )
                ");
                $this->info('   ✓ Teacher assignments table created successfully!');
            } else {
                $this->info('   ✓ Teacher assignments table already exists.');
            }

            $this->info('');
            $this->info('🎉 All database fixes completed successfully!');
            $this->info('');
            $this->info('Default registrar credentials:');
            $this->info('Email: registrar@school.com');
            $this->info('Password: password123');

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
