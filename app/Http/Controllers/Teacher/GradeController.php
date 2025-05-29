<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $subjects = Subject::where('teacher_id', $teacher->id)->get();
        $sections = ['A', 'B', 'C', 'D', 'E']; // Add more sections as needed

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

        // Verify teacher has access to this subject
        if ($subject->teacher_id !== $teacher->id) {
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

        // Verify teacher has access to this subject
        if ($subject->teacher_id !== $teacher->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to grade this subject.'
            ], 403);
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

        // Verify teacher has access to this subject
        if ($subject->teacher_id !== $teacher->id) {
            abort(403, 'You do not have permission to access this grade.');
        }

        $student = Student::findOrFail($studentId);
        $grade = Grade::where('student_id', $studentId)
                     ->where('subject_id', $subjectId)
                     ->first();

        return view('teacher.edit-grade', compact('teacher', 'student', 'subject', 'grade'));
    }
}