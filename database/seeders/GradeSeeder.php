<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run()
    {
        // Clear existing grades
        DB::table('grades')->truncate();

        // Get students and subjects
        $students = Student::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            echo "No students or subjects found. Please seed students and subjects first.\n";
            return;
        }

        $gradeRanges = [
            // Outstanding (90-100) - 25%
            ['min' => 90, 'max' => 100, 'weight' => 25],
            // Very Satisfactory (85-89) - 30%
            ['min' => 85, 'max' => 89, 'weight' => 30],
            // Satisfactory (80-84) - 25%
            ['min' => 80, 'max' => 84, 'weight' => 25],
            // Fairly Satisfactory (75-79) - 15%
            ['min' => 75, 'max' => 79, 'weight' => 15],
            // Failed (Below 75) - 5%
            ['min' => 60, 'max' => 74, 'weight' => 5],
        ];

        $totalGrades = 0;

        foreach ($students as $student) {
            // Assign 3-5 subjects per student
            $studentSubjects = $subjects->random(rand(3, min(5, $subjects->count())));
            
            foreach ($studentSubjects as $subject) {
                // Determine grade range based on weights
                $rand = rand(1, 100);
                $cumulativeWeight = 0;
                $selectedRange = $gradeRanges[0]; // Default
                
                foreach ($gradeRanges as $range) {
                    $cumulativeWeight += $range['weight'];
                    if ($rand <= $cumulativeWeight) {
                        $selectedRange = $range;
                        break;
                    }
                }

                // Generate quarterly grades within the selected range
                $quarter1 = rand($selectedRange['min'], $selectedRange['max']);
                $quarter2 = rand($selectedRange['min'], $selectedRange['max']);
                $quarter3 = rand($selectedRange['min'], $selectedRange['max']);
                $quarter4 = rand($selectedRange['min'], $selectedRange['max']);
                
                // Calculate final grade (average)
                $finalGrade = round(($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4, 2);

                // Determine remarks based on final grade
                $remarks = '';
                if ($finalGrade >= 90) {
                    $remarks = 'Outstanding performance';
                } elseif ($finalGrade >= 85) {
                    $remarks = 'Very satisfactory work';
                } elseif ($finalGrade >= 80) {
                    $remarks = 'Satisfactory performance';
                } elseif ($finalGrade >= 75) {
                    $remarks = 'Fairly satisfactory';
                } else {
                    $remarks = 'Needs improvement';
                }

                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'quarter1' => $quarter1,
                    'quarter2' => $quarter2,
                    'quarter3' => $quarter3,
                    'quarter4' => $quarter4,
                    'final_grade' => $finalGrade,
                    'remarks' => $remarks,
                ]);

                $totalGrades++;
            }
        }

        echo "Created {$totalGrades} grade records for {$students->count()} students across {$subjects->count()} subjects.\n";
        
        // Display grade distribution
        $outstanding = Grade::where('final_grade', '>=', 90)->where('final_grade', '<=', 100)->count();
        $verySatisfactory = Grade::where('final_grade', '>=', 85)->where('final_grade', '<=', 89)->count();
        $satisfactory = Grade::where('final_grade', '>=', 80)->where('final_grade', '<=', 84)->count();
        $fairlySatisfactory = Grade::where('final_grade', '>=', 75)->where('final_grade', '<=', 79)->count();
        $failed = Grade::where('final_grade', '<', 75)->count();

        echo "\nGrade Distribution:\n";
        echo "Outstanding (90-100): {$outstanding}\n";
        echo "Very Satisfactory (85-89): {$verySatisfactory}\n";
        echo "Satisfactory (80-84): {$satisfactory}\n";
        echo "Fairly Satisfactory (75-79): {$fairlySatisfactory}\n";
        echo "Did Not Meet Expectations (<75): {$failed}\n";
    }
}
