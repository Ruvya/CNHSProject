<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;
use Illuminate\Http\Request;

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

            // Only show grades to students when submitted by teacher
            $isSubmitted = $gradeRecord && ($gradeRecord->status === 'submitted');

            // Create a subject grade object with enhanced data
            $subjectGrade = (object) [
                'subject' => $subject,
                'quarter1' => $isSubmitted ? ($gradeRecord->quarter1 ?? null) : null,
                'quarter2' => $isSubmitted ? ($gradeRecord->quarter2 ?? null) : null,
                'quarter3' => $isSubmitted ? ($gradeRecord->quarter3 ?? null) : null,
                'quarter4' => $isSubmitted ? ($gradeRecord->quarter4 ?? null) : null,
                'final_grade' => $isSubmitted ? ($gradeRecord->final_grade ?? null) : null,
                'remarks' => $gradeRecord->remarks ?? null,
                'is_enrolled' => $student->subjects()->where('subject_id', $subject->id)->exists(),
                'status' => $isSubmitted ? 'Submitted' : 'Pending',
                'status_color' => $isSubmitted ? 'success' : 'warning',
                'last_updated' => $gradeRecord ? $gradeRecord->updated_at : null,
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

    /**
     * Get updated grades via AJAX for real-time updates
     */
    public function getUpdatedGrades()
    {
        $student = Auth::guard('student')->user();

        // Get all subjects the student is enrolled in
        $enrolledSubjects = $student->subjects()->with('teacher')->get();

        $updatedGrades = collect();

        foreach ($enrolledSubjects as $subject) {
            // Get the grade record for this subject
            $gradeRecord = Grade::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->first();

            if ($gradeRecord) {
                $isSubmitted = ($gradeRecord->status === 'submitted');
                $updatedGrades->push([
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'quarter1' => $isSubmitted ? $gradeRecord->quarter1 : null,
                    'quarter2' => $isSubmitted ? $gradeRecord->quarter2 : null,
                    'quarter3' => $isSubmitted ? $gradeRecord->quarter3 : null,
                    'quarter4' => $isSubmitted ? $gradeRecord->quarter4 : null,
                    'final_grade' => $isSubmitted ? $gradeRecord->final_grade : null,
                    'status' => $isSubmitted ? 'Submitted' : 'Pending',
                    'status_color' => $isSubmitted ? 'success' : 'warning',
                    'last_updated' => $gradeRecord->updated_at->format('M d, Y h:i A'),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'grades' => $updatedGrades,
            'last_refresh' => now()->format('M d, Y h:i A')
        ]);
    }

    /**
     * Fetch grades based on grade level and school year filters via AJAX.
     */
    public function fetchGradesByFilters(Request $request)
    {
        $student = Auth::guard('student')->user();
        $gradeLevel = $request->input('grade_level');
        $schoolYear = $request->input('school_year');

        $query = Grade::where('student_id', $student->id);

        if ($gradeLevel) {
            $query->whereHas('subject', function ($q) use ($gradeLevel) {
                $q->where('grade_level', $gradeLevel);
            });
        }

        if ($schoolYear) {
            // Assuming 'school_year' might be a column in the grades table or subject table
            // If it's in the subjects table, you'd do: 
            $query->whereHas('subject', function ($q) use ($schoolYear) {
                $q->where('school_year', $schoolYear);
            });

            // If 'school_year' is directly in the grades table
            // $query->where('school_year', $schoolYear);
        }

        $filteredGrades = $query->with('subject.teacher')->get();

        // Re-calculate statistics for the filtered grades
        $filteredSubjectGrades = collect();
        foreach ($filteredGrades as $gradeRecord) {
            $subject = $gradeRecord->subject;
            $isSubmitted = ($gradeRecord && $gradeRecord->status === 'submitted');
            $filteredSubjectGrades->push((object) [
                'subject' => $subject,
                'quarter1' => $isSubmitted ? ($gradeRecord->quarter1 ?? null) : null,
                'quarter2' => $isSubmitted ? ($gradeRecord->quarter2 ?? null) : null,
                'quarter3' => $isSubmitted ? ($gradeRecord->quarter3 ?? null) : null,
                'quarter4' => $isSubmitted ? ($gradeRecord->quarter4 ?? null) : null,
                'final_grade' => $isSubmitted ? ($gradeRecord->final_grade ?? null) : null,
                'remarks' => $gradeRecord->remarks ?? null,
                'is_enrolled' => $student->subjects()->where('subject_id', $subject->id)->exists(),
                'status' => $isSubmitted ? 'Submitted' : 'Pending',
                'status_color' => $isSubmitted ? 'success' : 'warning',
                'last_updated' => $gradeRecord ? $gradeRecord->updated_at : null,
            ]);
        }

        $generalAverageFiltered = null;
        $highestGradeFiltered = null;
        $totalSubjectsFiltered = $filteredSubjectGrades->count();
        $enrolledSubjectsFiltered = $filteredSubjectGrades->where('is_enrolled', true)->count();
        $quarterAveragesFiltered = [];

        if ($filteredSubjectGrades->isNotEmpty()) {
            foreach ($filteredSubjectGrades as $grade) {
                $quarters = array_filter([
                    $grade->quarter1,
                    $grade->quarter2,
                    $grade->quarter3,
                    $grade->quarter4
                ]);
                if (!empty($quarters)) {
                    $quarterAveragesFiltered[] = array_sum($quarters) / count($quarters);
                }
            }
            if (!empty($quarterAveragesFiltered)) {
                $generalAverageFiltered = round(array_sum($quarterAveragesFiltered) / count($quarterAveragesFiltered), 2);
            }

            $finalGradesFiltered = $filteredSubjectGrades->pluck('final_grade')->filter();
            if ($finalGradesFiltered->isNotEmpty()) {
                $highestGradeFiltered = round($finalGradesFiltered->max(), 2);
            }
        }

        // Prepare quarter grades for chart
        $quarterGradesData = [
            'first' => round($filteredSubjectGrades->pluck('quarter1')->filter()->avg() ?? 0, 2),
            'second' => round($filteredSubjectGrades->pluck('quarter2')->filter()->avg() ?? 0, 2),
            'third' => round($filteredSubjectGrades->pluck('quarter3')->filter()->avg() ?? 0, 2),
            'fourth' => round($filteredSubjectGrades->pluck('quarter4')->filter()->avg() ?? 0, 2)
        ];

        return response()->json([
            'success' => true,
            'grades' => $filteredSubjectGrades->map(function($item) {
                return [
                    'subject_id' => $item->subject->id,
                    'subject_name' => $item->subject->name,
                    'subject_code' => $item->subject->code,
                    'teacher_name' => $item->subject->teacher->name ?? 'No teacher assigned',
                    'is_enrolled' => $item->is_enrolled,
                    'quarter1' => $item->quarter1,
                    'quarter2' => $item->quarter2,
                    'quarter3' => $item->quarter3,
                    'quarter4' => $item->quarter4,
                    'final_grade' => $item->final_grade,
                    'status' => $item->status,
                    'status_color' => $item->status_color,
                ];
            }),
            'total_subjects' => $totalSubjectsFiltered,
            'enrolled_subjects' => $enrolledSubjectsFiltered,
            'general_average' => $generalAverageFiltered,
            'highest_grade' => $highestGradeFiltered,
            'quarter_grades_data' => $quarterGradesData,
            'last_refresh' => now()->format('M d, Y h:i A')
        ]);
    }
}