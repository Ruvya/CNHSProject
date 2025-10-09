<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;
use App\Services\SemesterService;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');
        
        // Get actual sections from database for the current school year
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $sections = \App\Models\Section::where('school_year', $currentSchoolYear)
            ->where('status', 'active')
            ->orderBy('name')
            ->pluck('name')
            ->unique()
            ->values();

        $query = Student::query()
            ->with(['grades' => function($query) use ($request) {
                if ($request->subject) {
                    $query->where('subject_id', $request->subject);
                }
            }]);

        if ($request->subject) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }

        if ($request->grade_level) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->section) {
            $query->where('section', $request->section);
        }

        $students = $query->get();

        return view('teacher.grades', compact('subjects', 'sections', 'students'));
    }

    public function gradeManagement(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $selectedSemester = $request->get('semester');

        // Get subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');

        // Optionally filter subjects by selected semester (respect subject semester and assignment pivot semester)
        if (!empty($selectedSemester)) {
            $subjects = $subjects->filter(function ($subject) use ($selectedSemester) {
                // Accept if subject explicitly matches semester or is for both semesters
                $subjectSemester = $subject->semester ?? null;
                if ($subjectSemester === 'Both Semesters' || $subjectSemester === $selectedSemester) {
                    return true;
                }

                // If coming via teacher_assignments pivot, check pivot semester when available
                if (isset($subject->pivot) && !empty($subject->pivot->semester)) {
                    return $subject->pivot->semester === 'Both Semesters' || $subject->pivot->semester === $selectedSemester;
                }

                // If no explicit semester, include by default
                return empty($subjectSemester);
            })->values();
        }

        // Get grade statistics
        $gradeStats = [];
        foreach ($subjects as $subject) {
            $totalStudents = Student::whereHas('subjects', function($q) use ($subject) {
                $q->where('subjects.id', $subject->id);
            })->count();

            $gradedQuery = Grade::where('subject_id', $subject->id)
                ->whereNotNull('final_grade');
            if (!empty($selectedSemester) && \Illuminate\Support\Facades\Schema::hasColumn('grades', 'semester')) {
                $gradedQuery->where(function ($q) use ($selectedSemester) {
                    $q->where('semester', $selectedSemester)
                      ->orWhereNull('semester');
                });
            }
            $gradedStudents = $gradedQuery->count();

            $avgQuery = Grade::where('subject_id', $subject->id)
                ->whereNotNull('final_grade');
            if (!empty($selectedSemester) && \Illuminate\Support\Facades\Schema::hasColumn('grades', 'semester')) {
                $avgQuery->where(function ($q) use ($selectedSemester) {
                    $q->where('semester', $selectedSemester)
                      ->orWhereNull('semester');
                });
            }
            $averageGrade = $avgQuery->avg('final_grade');

            $gradeStats[] = [
                'subject' => $subject,
                'semester' => (isset($subject->pivot) && !empty($subject->pivot->semester)) ? $subject->pivot->semester : ($subject->semester ?? null),
                'grade_level' => $subject->grade_level ?? null,
                'total_students' => $totalStudents,
                'graded_students' => $gradedStudents,
                'pending_grades' => $totalStudents - $gradedStudents,
                'average_grade' => $averageGrade ? round($averageGrade, 2) : 0,
                'completion_percentage' => $totalStudents > 0 ? round(($gradedStudents / $totalStudents) * 100, 1) : 0
            ];
        }

        $semesterOptions = SemesterService::getSemesterOptions();

        return view('teacher.grade-management', compact('gradeStats', 'subjects', 'semesterOptions', 'selectedSemester'));
    }

    public function saveGrade(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'quarter1' => 'nullable|numeric|min:0|max:100',
            'quarter2' => 'nullable|numeric|min:0|max:100',
            'quarter3' => 'nullable|numeric|min:0|max:100',
            'quarter4' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        $student = Student::findOrFail($request->student_id);
        $subject = Subject::findOrFail($request->subject_id);

        // Verify teacher has access to this subject (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to grade this subject.'
            ], 403);
        }

        // Check if there's an existing grade and if it can be edited
        $existingGrade = Grade::where('student_id', $request->student_id)
                             ->where('subject_id', $request->subject_id)
                             ->first();
        
        if ($existingGrade && !$existingGrade->canBeEdited()) {
            return response()->json([
                'success' => false,
                'message' => 'This grade has been submitted for approval and cannot be modified. Status: ' . $existingGrade->approval_status_badge
            ], 422);
        }

        // Calculate final grade (average of quarters)
        $quarters = array_filter([
            $request->quarter1,
            $request->quarter2,
            $request->quarter3,
            $request->quarter4
        ]);

        $finalGrade = !empty($quarters) ? round(array_sum($quarters) / count($quarters), 2) : null;

        // Update or create grade record
        $grade = Grade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
            ],
            [
                'quarter1' => $request->quarter1,
                'quarter2' => $request->quarter2,
                'quarter3' => $request->quarter3,
                'quarter4' => $request->quarter4,
                'final_grade' => $finalGrade,
                'remarks' => $request->remarks,
                'status' => 'draft' // Always set to draft when teacher saves
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Grade saved successfully',
            'data' => [
                'final_grade' => $finalGrade,
                'status' => $grade->status,
                'status_color' => $grade->status_color,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'subject_name' => $subject->name
            ]
        ]);
    }

    /**
     * Save individual quarter grade via AJAX
     */
    public function saveQuarterGrade(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'quarter' => 'required|in:quarter1,quarter2,quarter3,quarter4',
            'grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $student = Student::findOrFail($request->student_id);
        $subject = Subject::findOrFail($request->subject_id);

        // Verify teacher has access to this subject (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to grade this subject.'
            ], 403);
        }

        // Check if trying to input second semester grades without first semester
        if (in_array($request->quarter, ['quarter3', 'quarter4'])) {
            if (Grade::shouldLockSecondSemesterFor($request->student_id, $request->subject_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Second semester grades are locked. Please enter First Semester grades first.'
                ], 422);
            }
        }

        // Get or create grade record
        $grade = Grade::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
            ],
            [
                'quarter1' => null,
                'quarter2' => null,
                'quarter3' => null,
                'quarter4' => null,
                'final_grade' => null,
                'remarks' => null,
                'status' => 'draft'
            ]
        );

        // Update the specific quarter
        $grade->{$request->quarter} = $request->grade;

        // Recalculate final grade
        $grade->final_grade = $grade->calculateFinalGrade();
        $grade->save();

        return response()->json([
            'success' => true,
            'message' => 'Quarter grade saved successfully',
            'data' => [
                'quarter' => $request->quarter,
                'grade' => $request->grade,
                'final_grade' => $grade->final_grade,
                'status' => $grade->status,
                'status_color' => $grade->status_color
            ]
        ]);
    }

    public function editGrade($studentId, $subjectId)
    {
        $teacher = Auth::guard('teacher')->user();
        $subject = Subject::findOrFail($subjectId);

        // Verify teacher has access to this subject (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have permission to access this grade.');
        }

        $student = Student::findOrFail($studentId);
        $grade = Grade::where('student_id', $studentId)
                     ->where('subject_id', $subjectId)
                     ->first();

        return view('teacher.edit-grade', compact('teacher', 'student', 'subject', 'grade'));
    }

    public function show(Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();
        $students = $subject->students()->where('teacher_id', $teacher->id)->with('grades')->get();

        $totalStudents = $students->count();
        $studentsWithGrades = $students->filter(function($student) {
            return $student->grades->isNotEmpty();
        })->count();

        $totalGrades = Grade::where('subject_id', $subject->id)->whereIn('student_id', $students->pluck('id'))->avg('final_grade');
        $totalSubjects = TeacherAssignment::where('teacher_id', $teacher->id)->distinct('subject_id')->count();

        return view('teacher.subjects.grades', [
            'subject' => $subject,
            'students' => $students,
            'totalStudents' => $totalStudents,
            'studentsWithGrades' => $studentsWithGrades,
            'averageGrade' => $totalGrades,
            'totalSubjects' => $totalSubjects,
        ]);
    }

    /**
     * Submit grades for approval
     */
    public function submitGrades(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $request->validate([
            'grade_ids' => 'required|array',
            'grade_ids.*' => 'exists:grades,id'
        ]);

        $submittedCount = 0;
        $errors = [];

        foreach ($request->grade_ids as $gradeId) {
            $grade = Grade::findOrFail($gradeId);
            
            // Verify teacher has access to this grade
            $hasDirectAccess = $grade->subject->teacher_id === $teacher->id;
            $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $grade->subject_id)->exists();
            
            if (!$hasDirectAccess && !$hasAssignmentAccess) {
                $errors[] = "You don't have permission to submit grade for {$grade->student->first_name} {$grade->student->last_name}";
                continue;
            }

            // Check if grade can be submitted
            if (!$grade->canBeEdited()) {
                $errors[] = "Grade for {$grade->student->first_name} {$grade->student->last_name} cannot be submitted (Status: {$grade->approval_status_badge})";
                continue;
            }

            // Check if grade has sufficient data
            if (!$grade->final_grade) {
                $errors[] = "Grade for {$grade->student->first_name} {$grade->student->last_name} is incomplete";
                continue;
            }

            $grade->submitForApproval();
            $submittedCount++;
        }

        if ($submittedCount > 0) {
            $message = "Successfully submitted {$submittedCount} grade(s) for approval.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " grade(s) could not be submitted.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'submitted_count' => $submittedCount,
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No grades were submitted.',
                'errors' => $errors
            ], 422);
        }
    }

    /**
     * Submit all grades for a subject
     */
    public function submitAllGradesForSubject(Request $request, Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify teacher has access to this subject
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to submit grades for this subject.'
            ], 403);
        }

        // Get all draft grades for this subject
        $grades = Grade::where('subject_id', $subject->id)
                      ->where('status', 'draft')
                      ->get();

        $submittedCount = 0;
        $errors = [];

        foreach ($grades as $grade) {
            // Check if grade has sufficient data
            if (!$grade->final_grade) {
                $errors[] = "Grade for {$grade->student->first_name} {$grade->student->last_name} is incomplete";
                continue;
            }

            $grade->submitForApproval();
            $submittedCount++;
        }

        if ($submittedCount > 0) {
            $message = "Successfully submitted {$submittedCount} grade(s) for approval.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " grade(s) could not be submitted.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'submitted_count' => $submittedCount,
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No grades were submitted. All grades may already be submitted or incomplete.',
                'errors' => $errors
            ], 422);
        }
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        // School year starts in June (month 6)
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
}