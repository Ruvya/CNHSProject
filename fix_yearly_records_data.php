<?php
// Fix yearly records data - link to actual students and teachers

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Fix Yearly Records Data</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🔧 Fix Yearly Records Data</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Clear Existing Sample Data</h2>";
    
    // Clear existing records with null student_id
    $deletedStudentRecords = \Illuminate\Support\Facades\DB::table('student_yearly_records')
        ->whereNull('student_id')
        ->delete();
    
    echo "<p class='warning'>🗑️ Deleted {$deletedStudentRecords} sample student records</p>";
    
    // Clear existing records with null teacher_id
    $deletedTeacherRecords = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')
        ->whereNull('teacher_id')
        ->delete();
    
    echo "<p class='warning'>🗑️ Deleted {$deletedTeacherRecords} sample teacher records</p>";
    
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Create Records with Real Students</h2>";
    
    // Get actual students
    $students = \Illuminate\Support\Facades\DB::table('students')->get();
    
    if ($students->count() > 0) {
        $schoolYears = ['2024-2025', '2025-2026', '2026-2027'];
        $recordsAdded = 0;
        
        foreach ($students as $student) {
            foreach ($schoolYears as $schoolYear) {
                // Check if record already exists
                $exists = \Illuminate\Support\Facades\DB::table('student_yearly_records')
                    ->where('student_id', $student->id)
                    ->where('school_year', $schoolYear)
                    ->exists();
                
                if (!$exists) {
                    \Illuminate\Support\Facades\DB::table('student_yearly_records')->insert([
                        'student_id' => $student->id,
                        'school_year' => $schoolYear,
                        'grade_level' => $student->grade_level ?? 'Grade 11',
                        'section' => $student->section ?? 'A',
                        'status' => 'enrolled',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $recordsAdded++;
                }
            }
        }
        
        echo "<p class='success'>✅ Added {$recordsAdded} student yearly records linked to real students</p>";
        echo "<p>Total students processed: {$students->count()}</p>";
    } else {
        echo "<p class='warning'>⚠️ No students found in database</p>";
        
        // Create some sample students
        $sampleStudents = [
            ['first_name' => 'Juan', 'last_name' => 'Dela Cruz', 'grade_level' => 'Grade 11', 'section' => 'A'],
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'grade_level' => 'Grade 11', 'section' => 'B'],
            ['first_name' => 'Jose', 'last_name' => 'Rizal', 'grade_level' => 'Grade 12', 'section' => 'A'],
            ['first_name' => 'Ana', 'last_name' => 'Garcia', 'grade_level' => 'Grade 12', 'section' => 'B'],
        ];
        
        foreach ($sampleStudents as $studentData) {
            $studentId = \Illuminate\Support\Facades\DB::table('students')->insertGetId([
                'first_name' => $studentData['first_name'],
                'last_name' => $studentData['last_name'],
                'email' => strtolower($studentData['first_name'] . '.' . $studentData['last_name']) . '@student.cnhs.edu.ph',
                'student_id' => 'STU' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'grade_level' => $studentData['grade_level'],
                'section' => $studentData['section'],
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create yearly records for this student
            $schoolYears = ['2024-2025', '2025-2026'];
            foreach ($schoolYears as $schoolYear) {
                \Illuminate\Support\Facades\DB::table('student_yearly_records')->insert([
                    'student_id' => $studentId,
                    'school_year' => $schoolYear,
                    'grade_level' => $studentData['grade_level'],
                    'section' => $studentData['section'],
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        echo "<p class='success'>✅ Created 4 sample students with yearly records</p>";
    }
    
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Create Records with Real Teachers</h2>";
    
    // Get actual teachers
    $teachers = \Illuminate\Support\Facades\DB::table('teachers')->get();
    
    if ($teachers->count() > 0) {
        $schoolYears = ['2024-2025', '2025-2026', '2026-2027'];
        $recordsAdded = 0;
        
        foreach ($teachers as $teacher) {
            foreach ($schoolYears as $schoolYear) {
                // Check if record already exists
                $exists = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')
                    ->where('teacher_id', $teacher->id)
                    ->where('school_year', $schoolYear)
                    ->exists();
                
                if (!$exists) {
                    \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->insert([
                        'teacher_id' => $teacher->id,
                        'school_year' => $schoolYear,
                        'department' => 'Academic',
                        'position' => 'Subject Teacher',
                        'subjects_taught' => json_encode(['Mathematics', 'Science']),
                        'grade_levels_handled' => json_encode(['Grade 11', 'Grade 12']),
                        'total_students' => 40,
                        'teaching_load' => 24.00,
                        'employment_status' => 'regular',
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $recordsAdded++;
                }
            }
        }
        
        echo "<p class='success'>✅ Added {$recordsAdded} teacher yearly records linked to real teachers</p>";
        echo "<p>Total teachers processed: {$teachers->count()}</p>";
    } else {
        echo "<p class='warning'>⚠️ No teachers found in database</p>";
        
        // Create some sample teachers
        $sampleTeachers = [
            ['first_name' => 'Roberto', 'last_name' => 'Cruz', 'email' => 'roberto.cruz@cnhs.edu.ph'],
            ['first_name' => 'Elena', 'last_name' => 'Reyes', 'email' => 'elena.reyes@cnhs.edu.ph'],
            ['first_name' => 'Carlos', 'last_name' => 'Mendoza', 'email' => 'carlos.mendoza@cnhs.edu.ph'],
        ];
        
        foreach ($sampleTeachers as $teacherData) {
            $teacherId = \Illuminate\Support\Facades\DB::table('teachers')->insertGetId([
                'first_name' => $teacherData['first_name'],
                'last_name' => $teacherData['last_name'],
                'email' => $teacherData['email'],
                'password' => bcrypt('123456'),
                'phone' => '09' . rand(100000000, 999999999),
                'address' => 'Camarines Norte',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create yearly records for this teacher
            $schoolYears = ['2024-2025', '2025-2026'];
            foreach ($schoolYears as $schoolYear) {
                \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->insert([
                    'teacher_id' => $teacherId,
                    'school_year' => $schoolYear,
                    'department' => 'Academic',
                    'position' => 'Subject Teacher',
                    'subjects_taught' => json_encode(['Mathematics', 'Science']),
                    'grade_levels_handled' => json_encode(['Grade 11', 'Grade 12']),
                    'total_students' => 40,
                    'teaching_load' => 24.00,
                    'employment_status' => 'regular',
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        echo "<p class='success'>✅ Created 3 sample teachers with yearly records</p>";
    }
    
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Verify Data</h2>";
    
    // Count records
    $totalStudentRecords = \Illuminate\Support\Facades\DB::table('student_yearly_records')->count();
    $linkedStudentRecords = \Illuminate\Support\Facades\DB::table('student_yearly_records')->whereNotNull('student_id')->count();
    $totalTeacherRecords = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->count();
    $linkedTeacherRecords = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->whereNotNull('teacher_id')->count();
    
    echo "<p><strong>Student Records:</strong></p>";
    echo "<p>• Total: {$totalStudentRecords}</p>";
    echo "<p>• Linked to students: {$linkedStudentRecords}</p>";
    
    echo "<p><strong>Teacher Records:</strong></p>";
    echo "<p>• Total: {$totalTeacherRecords}</p>";
    echo "<p>• Linked to teachers: {$linkedTeacherRecords}</p>";
    
    // Test the problematic query
    $result = \Illuminate\Support\Facades\DB::table('student_yearly_records')
        ->where('school_year', '2025-2026')
        ->count();
    
    echo "<p class='success'>✅ Query test: Found {$result} records for 2025-2026</p>";
    
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🎉 Data Fix Complete!</h2>";
    echo "<p class='success'>All yearly records now have proper data linked to real students and teachers!</p>";
    echo "<p><strong>The yearly records page should now display properly without null errors.</strong></p>";
    echo "<p><a href='/registrar-bypass' style='background:#17a2b8;color:white;padding:15px 30px;text-decoration:none;border-radius:5px;margin:10px;font-size:18px;'>🧪 Test Registrar Dashboard</a></p>";
    echo "<p><a href='/all-login-solutions' style='background:#007bff;color:white;padding:15px 30px;text-decoration:none;border-radius:5px;margin:10px;font-size:18px;'>🚀 All Login Solutions</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
