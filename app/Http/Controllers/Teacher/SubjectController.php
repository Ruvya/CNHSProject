<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubjectController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get subjects assigned through teacher assignments (new way)
        $assignedSubjects = $teacher->assignedSubjects()
            ->withCount('students')
            ->get();

        // Get subjects assigned directly (old way) for backward compatibility
        $directSubjects = Subject::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->get();

        // Combine both assignment methods and remove duplicates
        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');

        return view('teacher.subjects', compact('subjects'));
    }

    /**
     * View all students enrolled in a specific subject
     */
    public function viewStudents(Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get all students enrolled in this subject with their grades
        $students = $subject->students()
            ->with(['grades' => function($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get all subjects for the teacher (for the dropdown)
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

        return view('teacher.subjects.students', compact('subject', 'students', 'teacher', 'subjects', 'sections'));
    }

    /**
     * Manage grades for all students in a specific subject
     */
    public function manageGrades(Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Get all students enrolled in this subject with their grades
        $students = $subject->students()
            ->with(['grades' => function($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Calculate statistics
        $totalStudents = $students->count();
        $studentsWithGrades = $students->filter(function($student) {
            return $student->grades->isNotEmpty() && $student->grades->first()->final_grade !== null;
        })->count();

        $averageGrade = null;
        if ($studentsWithGrades > 0) {
            $grades = $students->flatMap(function($student) {
                return $student->grades->where('final_grade', '!=', null)->pluck('final_grade');
            });
            $averageGrade = $grades->avg();
        }

        return view('teacher.subjects.grades', compact('subject', 'students', 'teacher', 'totalStudents', 'studentsWithGrades', 'averageGrade'));
    }

    /**
     * Edit grades for a specific student in a subject
     */
    public function editStudentGrade(Subject $subject, Student $student)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Verify the student is enrolled in this subject
        if (!$subject->students()->where('students.id', $student->id)->exists()) {
            abort(404, 'Student is not enrolled in this subject.');
        }

        // Get or create grade record
        $grade = Grade::firstOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $subject->id
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

        return view('teacher.subjects.edit-grade', compact('subject', 'student', 'grade', 'teacher'));
    }

    /**
     * Save grades for a specific student in a subject
     */
    public function saveStudentGrade(Request $request, Subject $subject, Student $student)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        // Verify the student is enrolled in this subject
        if (!$subject->students()->where('students.id', $student->id)->exists()) {
            abort(404, 'Student is not enrolled in this subject.');
        }

        // Validate the request
        $validated = $request->validate([
            'quarter1' => 'nullable|numeric|min:0|max:100',
            'quarter2' => 'nullable|numeric|min:0|max:100',
            'quarter3' => 'nullable|numeric|min:0|max:100',
            'quarter4' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:255'
        ]);

        // Calculate final grade if all quarters are provided
        $quarters = array_filter([
            $validated['quarter1'],
            $validated['quarter2'],
            $validated['quarter3'],
            $validated['quarter4']
        ]);

        $finalGrade = null;
        if (count($quarters) >= 2) { // At least 2 quarters to calculate final grade
            $finalGrade = round(array_sum($quarters) / count($quarters), 2);
        }

        // Block if already submitted
        $existing = Grade::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->first();
        if ($existing && $existing->isLocked()) {
            return redirect()
                ->back()
                ->with('error', 'This grade has been submitted and can no longer be edited.');
        }

        // Update or create grade record
        Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $subject->id
            ],
            array_merge($validated, [
                'final_grade' => $finalGrade,
                'school_year' => \App\Services\SemesterService::getCurrentSchoolYear(),
                'semester' => \App\Services\SemesterService::getCurrentSemester(),
                'status' => \Schema::hasColumn('grades', 'status') ? ($request->input('action') === 'submit' ? 'submitted' : 'draft') : null,
            ])
        );

        return redirect()
            ->route('teacher.subjects.grades', $subject)
            ->with('success', "Grades updated successfully for {$student->first_name} {$student->last_name}.");
    }

    /**
     * Bulk update grades for multiple students
     */
    public function updateGrades(Request $request, Subject $subject)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subject->id)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            abort(403, 'You do not have access to this subject.');
        }

        $grades = $request->input('grades', []);
        $action = $request->input('action'); // 'save' for draft, otherwise submit
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($grades as $studentId => $gradeData) {
                // Verify student is enrolled in this subject
                if (!$subject->students()->where('students.id', $studentId)->exists()) {
                    continue;
                }

                // Validate individual grade data
                $quarters = array_filter([
                    $gradeData['quarter1'] ?? null,
                    $gradeData['quarter2'] ?? null,
                    $gradeData['quarter3'] ?? null,
                    $gradeData['quarter4'] ?? null
                ]);

                // Calculate final grade
                $finalGrade = null;
                if (count($quarters) >= 2) {
                    $finalGrade = round(array_sum($quarters) / count($quarters), 2);
                }

                // Skip if existing grade is submitted/locked
                $existing = Grade::where('student_id', $studentId)
                    ->where('subject_id', $subject->id)
                    ->first();
                if ($existing && $existing->isLocked()) {
                    continue;
                }

                // Update or create grade record
                $updateData = [
                    'quarter1' => $gradeData['quarter1'] ?? null,
                    'quarter2' => $gradeData['quarter2'] ?? null,
                    'quarter3' => $gradeData['quarter3'] ?? null,
                    'quarter4' => $gradeData['quarter4'] ?? null,
                    'final_grade' => $finalGrade,
                    'remarks' => $gradeData['remarks'] ?? null,
                    'school_year' => \App\Services\SemesterService::getCurrentSchoolYear(),
                    'semester' => \App\Services\SemesterService::getCurrentSemester(),
                ];
                if (Schema::hasColumn('grades', 'status')) {
                    $updateData['status'] = $action === 'save' ? 'draft' : 'submitted';
                }

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subject->id
                    ],
                    $updateData
                );

                $updatedCount++;
            }

            DB::commit();

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Successfully updated grades for {$updatedCount} students.",
                    'updated_count' => $updatedCount
                ]);
            }

            return redirect()
                ->route('teacher.subjects.grades', $subject)
                ->with('success', "Successfully updated grades for {$updatedCount} students.");

        } catch (\Exception $e) {
            DB::rollback();

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating grades. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('teacher.subjects.grades', $subject)
                ->with('error', 'An error occurred while updating grades. Please try again.');
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