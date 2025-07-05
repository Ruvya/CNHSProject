<?php
// Fix missing tables for admin dashboard

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Fix Missing Tables</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🔧 Fix Missing Database Tables</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Check Missing Tables</h2>";
    
    // Check for student_yearly_records table
    $studentYearlyExists = \Illuminate\Support\Facades\Schema::hasTable('student_yearly_records');
    echo "<p>student_yearly_records table: " . ($studentYearlyExists ? "<span class='success'>✅ EXISTS</span>" : "<span class='error'>❌ MISSING</span>") . "</p>";
    
    // Check for teacher_yearly_records table
    $teacherYearlyExists = \Illuminate\Support\Facades\Schema::hasTable('teacher_yearly_records');
    echo "<p>teacher_yearly_records table: " . ($teacherYearlyExists ? "<span class='success'>✅ EXISTS</span>" : "<span class='error'>❌ MISSING</span>") . "</p>";
    
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Create Missing Tables</h2>";
    
    // Create student_yearly_records table if missing
    if (!$studentYearlyExists) {
        echo "<p class='warning'>⚠️ Creating student_yearly_records table...</p>";
        
        \Illuminate\Support\Facades\Schema::create('student_yearly_records', function ($table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('school_year');
            $table->string('grade_level');
            $table->string('section')->nullable();
            $table->string('status')->default('enrolled'); // enrolled, promoted, retained, graduated
            $table->timestamps();
        });
        
        echo "<p class='success'>✅ student_yearly_records table created</p>";
    }
    
    // Create teacher_yearly_records table if missing
    if (!$teacherYearlyExists) {
        echo "<p class='warning'>⚠️ Creating teacher_yearly_records table...</p>";
        
        \Illuminate\Support\Facades\Schema::create('teacher_yearly_records', function ($table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('school_year'); // e.g., 2023-2024
            $table->string('department')->nullable();
            $table->string('position')->nullable(); // e.g., Head Teacher, Subject Teacher, etc.
            $table->json('subjects_taught')->nullable(); // Array of subjects taught
            $table->json('grade_levels_handled')->nullable(); // Array of grade levels
            $table->string('advisory_section')->nullable(); // If teacher is an adviser
            $table->integer('total_students')->default(0); // Total students handled
            $table->decimal('teaching_load', 5, 2)->default(0.00); // Teaching load in hours
            $table->string('employment_status')->default('regular'); // regular, substitute, part-time
            $table->string('status')->default('active'); // active, inactive, transferred, resigned
            $table->text('notes')->nullable(); // Additional notes for the year
            $table->date('start_date')->nullable(); // Start date for the school year
            $table->date('end_date')->nullable(); // End date for the school year
            $table->timestamps();
            
            // Ensure unique record per teacher per school year
            $table->unique(['teacher_id', 'school_year']);
        });
        
        echo "<p class='success'>✅ teacher_yearly_records table created</p>";
    }
    
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Add Sample Data</h2>";
    
    // Add sample school years to student_yearly_records
    $currentYear = date('Y');
    $schoolYears = [
        ($currentYear - 1) . '-' . $currentYear,
        $currentYear . '-' . ($currentYear + 1)
    ];
    
    foreach ($schoolYears as $schoolYear) {
        // Check if we have any students to create records for
        $students = \App\Models\Student::take(5)->get();
        
        foreach ($students as $student) {
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
            }
        }
    }
    
    $recordCount = \Illuminate\Support\Facades\DB::table('student_yearly_records')->count();
    echo "<p class='success'>✅ Added sample data. Total student yearly records: {$recordCount}</p>";
    
    // Add sample data for teachers
    $teachers = \App\Models\Teacher::take(3)->get();
    
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
    }
    
    $teacherRecordCount = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->count();
    echo "<p class='success'>✅ Added teacher sample data. Total teacher yearly records: {$teacherRecordCount}</p>";
    
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Verify Tables</h2>";
    
    // Verify student_yearly_records
    $studentYearlyCount = \Illuminate\Support\Facades\DB::table('student_yearly_records')->count();
    echo "<p>student_yearly_records count: <span class='success'>{$studentYearlyCount}</span></p>";
    
    // Get distinct school years
    $schoolYearsInDb = \Illuminate\Support\Facades\DB::table('student_yearly_records')
        ->distinct()
        ->pluck('school_year')
        ->sort()
        ->toArray();
    echo "<p>Available school years: " . implode(', ', $schoolYearsInDb) . "</p>";
    
    // Verify teacher_yearly_records
    $teacherYearlyCount = \Illuminate\Support\Facades\DB::table('teacher_yearly_records')->count();
    echo "<p>teacher_yearly_records count: <span class='success'>{$teacherYearlyCount}</span></p>";
    
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🎉 Tables Fixed Successfully!</h2>";
    echo "<p class='success'>All missing tables have been created and populated with sample data.</p>";
    echo "<p><strong>You can now access the admin dashboard without errors!</strong></p>";
    echo "<p><a href='/admin-bypass' style='background:#dc3545;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Test Admin Dashboard</a></p>";
    echo "<p><a href='/all-login-solutions' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>All Login Solutions</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
