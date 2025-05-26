<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Get all subjects the student is enrolled in (primary source)
        $enrolledSubjects = $student->subjects()->with('teacher')->get();

        // If no enrolled subjects, get subjects based on student's grade level, track, and strand
        if ($enrolledSubjects->isEmpty()) {
            $enrolledSubjects = \App\Models\Subject::where('grade_level', $student->grade_level)
                ->where(function($query) use ($student) {
                    $query->where('track', $student->track)
                          ->orWhereNull('track')
                          ->orWhere('track', '');
                })
                ->where(function($query) use ($student) {
                    $query->where('strand', $student->strand)
                          ->orWhereNull('strand')
                          ->orWhere('strand', '');
                })
                ->with('teacher')
                ->get();
        }

        // Create a collection to hold all subject data with grades
        $subjectGrades = collect();

        foreach ($enrolledSubjects as $subject) {
            // Get the grade record for this subject
            $gradeRecord = Grade::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->first();

            // Create a subject grade object
            $subjectGrade = (object) [
                'subject' => $subject,
                'quarter1' => $gradeRecord->quarter1 ?? null,
                'quarter2' => $gradeRecord->quarter2 ?? null,
                'quarter3' => $gradeRecord->quarter3 ?? null,
                'quarter4' => $gradeRecord->quarter4 ?? null,
                'final_grade' => $gradeRecord->final_grade ?? null,
                'remarks' => $gradeRecord->remarks ?? null,
                'is_enrolled' => $student->subjects()->where('subject_id', $subject->id)->exists(),
            ];

            $subjectGrades->push($subjectGrade);
        }

        // Use the subject grades collection for calculations
        $grades = $subjectGrades;

        // Initialize variables
        $gpa = null;
        $highestGrade = null;
        $rank = null;
        $generalAverage = null;
        $quarterGrades = [
            'first' => 0,
            'second' => 0,
            'third' => 0,
            'fourth' => 0
        ];

        if ($grades->isNotEmpty()) {
            // Calculate general average from all quarters
            $quarterAverages = [];
            foreach ($grades as $grade) {
                $quarters = array_filter([
                    $grade->quarter1,
                    $grade->quarter2,
                    $grade->quarter3,
                    $grade->quarter4
                ]);
                if (!empty($quarters)) {
                    $quarterAverages[] = array_sum($quarters) / count($quarters);
                }
            }

            if (!empty($quarterAverages)) {
                $generalAverage = round(array_sum($quarterAverages) / count($quarterAverages), 2);
            }

            // Get highest grade from final grades
            $finalGrades = $grades->pluck('final_grade')->filter();
            if ($finalGrades->isNotEmpty()) {
                $highestGrade = round($finalGrades->max(), 2);
            }

            // Calculate quarter averages
            $quarterGrades = [
                'first' => round($grades->pluck('quarter1')->filter()->avg() ?? 0, 2),
                'second' => round($grades->pluck('quarter2')->filter()->avg() ?? 0, 2),
                'third' => round($grades->pluck('quarter3')->filter()->avg() ?? 0, 2),
                'fourth' => round($grades->pluck('quarter4')->filter()->avg() ?? 0, 2)
            ];
        }

        // Calculate enrollment statistics
        $totalSubjects = $grades->count();
        $enrolledSubjects = $grades->where('is_enrolled', true)->count();
        $subjectsWithGrades = $grades->where('final_grade', '!=', null)->count();

        return view('student.grades', compact(
            'grades',
            'gpa',
            'highestGrade',
            'rank',
            'generalAverage',
            'quarterGrades',
            'totalSubjects',
            'enrolledSubjects',
            'subjectsWithGrades'
        ));
    }
}