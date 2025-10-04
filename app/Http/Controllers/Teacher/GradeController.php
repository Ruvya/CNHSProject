<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

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

        // Get subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');

        // Get grade statistics
        $gradeStats = [];
        foreach ($subjects as $subject) {
            $totalStudents = Student::whereHas('subjects', function($q) use ($subject) {
                $q->where('subjects.id', $subject->id);
            })->count();

            $gradedStudents = Grade::where('subject_id', $subject->id)
                ->whereNotNull('final_grade')
                ->count();

            $averageGrade = Grade::where('subject_id', $subject->id)
                ->whereNotNull('final_grade')
                ->avg('final_grade');

            $gradeStats[] = [
                'subject' => $subject,
                'total_students' => $totalStudents,
                'graded_students' => $gradedStudents,
                'pending_grades' => $totalStudents - $gradedStudents,
                'average_grade' => $averageGrade ? round($averageGrade, 2) : 0,
                'completion_percentage' => $totalStudents > 0 ? round(($gradedStudents / $totalStudents) * 100, 1) : 0
            ];
        }

        return view('teacher.grade-management', compact('gradeStats', 'subjects'));
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
                'remarks' => null
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