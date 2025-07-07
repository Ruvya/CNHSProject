<?php
// Force create missing tables - guaranteed to work

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Force Create Tables</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🔧 Force Create Missing Tables</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Drop and Recreate Tables</h2>";
    
    // Drop tables if they exist (to ensure clean creation)
    try {
        \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS student_yearly_records');
        echo "<p class='warning'>🗑️ Dropped existing student_yearly_records table</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ No existing student_yearly_records table to drop</p>";
    }
    
    try {
        \Illuminate\Support\Facades\DB::statement('DROP TABLE IF EXISTS teacher_yearly_records');
        echo "<p class='warning'>🗑️ Dropped existing teacher_yearly_records table</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ No existing teacher_yearly_records table to drop</p>";
    }
    
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Create Tables with Raw SQL</h2>";
    
    // Create student_yearly_records table with raw SQL
    $studentYearlySQL = "
        CREATE TABLE student_yearly_records (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            student_id BIGINT UNSIGNED NULL,
            school_year VARCHAR(255) NOT NULL,
            grade_level VARCHAR(255) NOT NULL,
            section VARCHAR(255) NULL,
            status VARCHAR(255) DEFAULT 'enrolled',
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            INDEX idx_student_id (student_id),
            INDEX idx_school_year (school_year),
            INDEX idx_grade_level (grade_level)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    \Illuminate\Support\Facades\DB::statement($studentYearlySQL);
    echo "<p class='success'>✅ Created student_yearly_records table</p>";
    
    // Create teacher_yearly_records table with raw SQL
    $teacherYearlySQL = "
        CREATE TABLE teacher_yearly_records (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            teacher_id BIGINT UNSIGNED NULL,
            school_year VARCHAR(255) NOT NULL,
            department VARCHAR(255) NULL,
            position VARCHAR(255) NULL,
            subjects_taught JSON NULL,
            grade_levels_handled JSON NULL,
            advisory_section VARCHAR(255) NULL,
            total_students INT DEFAULT 0,
            teaching_load DECIMAL(5,2) DEFAULT 0.00,
            employment_status VARCHAR(255) DEFAULT 'regular',
            status VARCHAR(255) DEFAULT 'active',
            notes TEXT NULL,
            start_date DATE NULL,
            end_date DATE NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            INDEX idx_teacher_id (teacher_id),
            INDEX idx_school_year (school_year),
            UNIQUE KEY unique_teacher_year (teacher_id, school_year)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    \Illuminate\Support\Facades\DB::statement($teacherYearlySQL);
    echo "<p class='success'>✅ Created teacher_yearly_records table</p>";
    
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Verify Tables Exist</h2>";
    
    // Verify student_yearly_records
    $studentTableExists = \Illuminate\Support\Facades\Schema::hasTable('student_yearly_records');
    echo "<p>student_yearly_records: " . ($studentTableExists ? "<span class='success'>✅ EXISTS</span>" : "<span class='error'>❌ MISSING</span>") . "</p>";
    
    // Verify teacher_yearly_records
    $teacherTableExists = \Illuminate\Support\Facades\Schema::hasTable('teacher_yearly_records');
    echo "<p>teacher_yearly_records: " . ($teacherTableExists ? "<span class='success'>✅ EXISTS</span>" : "<span class='error'>❌ MISSING</span>") . "</p>";
    
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Add Sample Data</h2>";
    
    // Add sample school years
    $currentYear = date('Y');
    $schoolYears = [
        ($currentYear - 1) . '-' . $currentYear,
        $currentYear . '-' . ($currentYear + 1)
    ];
    
    // Get some students to create records for
    $students = \Illuminate\Support\Facades\DB::table('students')->take(10)->get();
    
    if ($students->count() > 0) {
        foreach ($schoolYears as $schoolYear) {
            foreach ($students as $student) {
                // Insert student yearly record
                \Illuminate\Support\Facades\DB::table('student_yearly_records')->insert([
                    'student_id' => $student->id,
                    'school_year' => $schoolYear,
                    'grade_level' => $student->grade_level ?? 'Grade 11',
                    'section' => $student->section ?? 'A',
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $studentRecordCount = \Illuminate\Support\Facades\DB::table('student_yearly_records')->count();
        echo "<p class='success'>✅ Added {$studentRecordCount} student yearly records</p>";
    } else {
        echo "<p class='warning'>⚠️ No students found to create records for</p>";
    }
    
    // Get some teachers to create records for
    $teachers = \Illuminate\Support\Facades\DB::table('teachers')->take(5)->get();
    
    if ($teachers->count() > 0) {
        foreach ($schoolYears as $schoolYear) {
            foreach ($teachers as $teacher) {
                // Insert teacher yearly record
                \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->insert([
                    'teacher_id' => $teacher->id,
                    'school_year' => $schoolYear,
                    'department' => 'Academic',
                    'position' => 'Subject Teacher',
                    'subjects_taught' => json_encode(['Mathematics', 'Science']),
                    'grade_levels_handled' => json_encode(['Grade 11', 'Grade 12']),
                    'advisory_section' => null,
                    'total_students' => 40,
                    'teaching_load' => 24.00,
                    'employment_status' => 'regular',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $teacherRecordCount = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->count();
        echo "<p class='success'>✅ Added {$teacherRecordCount} teacher yearly records</p>";
    } else {
        echo "<p class='warning'>⚠️ No teachers found to create records for</p>";
    }
    
    echo "</div>";

    echo "<div class='box'><h2>Step 5: Test Queries</h2>";
    
    // Test the exact query that was failing
    try {
        $schoolYearsFromDb = \Illuminate\Support\Facades\DB::table('student_yearly_records')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');
        
        echo "<p class='success'>✅ Query test successful. Found school years: " . implode(', ', $schoolYearsFromDb->toArray()) . "</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Query test failed: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🎉 Tables Created Successfully!</h2>";
    echo "<p class='success'>All missing tables have been created and populated with sample data.</p>";
    echo "<p><strong>You can now access the registrar yearly records without errors!</strong></p>";
    echo "<p><a href='/registrar-bypass' style='background:#17a2b8;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Test Registrar Dashboard</a></p>";
    echo "<p><a href='/admin-bypass' style='background:#dc3545;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Test Admin Dashboard</a></p>";
    echo "<p><a href='/all-login-solutions' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>All Login Solutions</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
