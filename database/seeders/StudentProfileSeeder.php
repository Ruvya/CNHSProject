<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample students with comprehensive data
        $students = [
            [
                'student_id' => 'CNHS-2024-001',
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Cruz',
                'email' => 'maria.cruz@cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 11',
                'section' => 'Einstein',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'gender' => 'Female',
                'contact_number' => '09123456789',
                'address' => '123 Main St, Quezon City',
                'parent_name' => 'Juan Cruz',
                'parent_contact' => '09987654321',
            ],
            [
                'student_id' => 'CNHS-2024-002',
                'first_name' => 'John',
                'middle_name' => 'Dela',
                'last_name' => 'Cruz',
                'email' => 'john.delacruz@cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 12',
                'section' => 'Newton',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'gender' => 'Male',
                'contact_number' => '09234567890',
                'address' => '456 Oak Ave, Manila',
                'parent_name' => 'Rosa Dela Cruz',
                'parent_contact' => '09876543210',
            ],
            [
                'student_id' => 'CNHS-2024-003',
                'first_name' => 'Anna',
                'middle_name' => 'Marie',
                'last_name' => 'Garcia',
                'email' => 'anna.garcia@cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 11',
                'section' => 'Darwin',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'gender' => 'Female',
                'contact_number' => '09345678901',
                'address' => '789 Pine St, Makati',
                'parent_name' => 'Carlos Garcia',
                'parent_contact' => '09765432109',
            ]
        ];

        foreach ($students as $studentData) {
            $student = \App\Models\Student::create($studentData);

            // Assign subjects based on grade level and strand
            $this->assignSubjectsToStudent($student);
        }
    }

    private function assignSubjectsToStudent($student)
    {
        // Get subjects for the student's grade level
        $subjects = \App\Models\Subject::where('grade_level', $student->grade_level)
            ->where(function($query) use ($student) {
                $query->where('strand', $student->strand)
                      ->orWhereNull('strand')
                      ->orWhere('strand', '');
            })
            ->take(6) // Limit to 6 subjects
            ->get();

        foreach ($subjects as $subject) {
            // Enroll student in subject
            $student->subjects()->attach($subject->id);

            // Create sample grades (random grades between 70-95)
            $grade = rand(70, 95);

            \App\Models\Grade::create([
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'grade' => $grade,
                'quarter' => 1,
                'school_year' => '2024-2025',
            ]);
        }
    }
}
