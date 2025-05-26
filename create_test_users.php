<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Registrar;

echo "Creating test users for Teacher, Student, and Registrar...\n\n";

// Create test teacher
$teacher = Teacher::where('email', 'teacher@example.com')->first();
if (!$teacher) {
    Teacher::create([
        'name' => 'Test Teacher',
        'email' => 'teacher@example.com',
        'password' => Hash::make('teacher123'),
    ]);
    echo "✅ Created test teacher:\n";
    echo "Email: teacher@example.com\n";
    echo "Password: teacher123\n\n";
} else {
    // Reset password
    $teacher->password = Hash::make('teacher123');
    $teacher->save();
    echo "✅ Reset teacher password:\n";
    echo "Email: teacher@example.com\n";
    echo "Password: teacher123\n\n";
}

// Create test student using raw SQL to avoid model issues
$studentExists = DB::table('students')->where('student_id', '123456789')->exists();
if (!$studentExists) {
    DB::table('students')->insert([
        'first_name' => 'Test',
        'last_name' => 'Student',
        'email' => 'student@example.com',
        'password' => Hash::make('student123'),
        'student_id' => '123456789',
        'grade_level' => '11',
        'gender' => 'Male',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Created test student:\n";
    echo "Student ID: 123456789\n";
    echo "Password: student123\n\n";
} else {
    // Reset password
    DB::table('students')->where('student_id', '123456789')->update([
        'password' => Hash::make('student123'),
        'updated_at' => now(),
    ]);
    echo "✅ Reset student password:\n";
    echo "Student ID: 123456789\n";
    echo "Password: student123\n\n";
}

// Create test registrar
$registrar = Registrar::where('email', 'registrar@example.com')->first();
if (!$registrar) {
    Registrar::create([
        'first_name' => 'Test',
        'last_name' => 'Registrar',
        'email' => 'registrar@example.com',
        'password' => Hash::make('registrar123'),
    ]);
    echo "✅ Created test registrar:\n";
    echo "Email: registrar@example.com\n";
    echo "Password: registrar123\n";
    echo "Registrar Code: letmein\n\n";
} else {
    // Reset password
    $registrar->password = Hash::make('registrar123');
    $registrar->save();
    echo "✅ Reset registrar password:\n";
    echo "Email: registrar@example.com\n";
    echo "Password: registrar123\n";
    echo "Registrar Code: letmein\n\n";
}

echo "All test users created/updated successfully!\n";
echo "\nYou can now test login with:\n";
echo "Teacher - Email: teacher@example.com, Password: teacher123\n";
echo "Student - Student ID: 123456789, Password: student123\n";
echo "Registrar - Email: registrar@example.com, Password: registrar123, Code: letmein\n";
