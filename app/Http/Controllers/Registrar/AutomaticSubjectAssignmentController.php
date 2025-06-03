<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Services\AutomaticSubjectAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutomaticSubjectAssignmentController extends Controller
{
    protected $assignmentService;

    public function __construct(AutomaticSubjectAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display the automatic assignment dashboard
     */
    public function index()
    {
        // Get students needing assignment
        $studentsNeedingAssignment = $this->assignmentService->getStudentsNeedingAssignment();
        $studentsNeedingReassignment = $this->assignmentService->getStudentsNeedingReassignment();

        // Get curriculum statistics
        $curriculumStats = $this->assignmentService->getCurriculumStatistics();

        // Get overall statistics
        $totalStudents = Student::count();
        $studentsWithSubjects = Student::whereHas('subjects')->count();
        $studentsWithoutSubjects = $totalStudents - $studentsWithSubjects;
        $studentsWithIncompleteData = Student::where(function($query) {
            $query->whereNull('track')
                  ->orWhereNull('strand')
                  ->orWhereNull('grade_level');
        })->count();

        return view('registrar.automatic-subject-assignment.index', compact(
            'studentsNeedingAssignment',
            'studentsNeedingReassignment',
            'curriculumStats',
            'totalStudents',
            'studentsWithSubjects',
            'studentsWithoutSubjects',
            'studentsWithIncompleteData'
        ));
    }

    /**
     * Preview subjects for a specific student
     */
    public function preview($studentId)
    {
        $student = Student::findOrFail($studentId);
        $preview = $this->assignmentService->previewSubjectsForStudent($student);
        $validationErrors = $this->assignmentService->validateStudentForAssignment($student);

        return view('registrar.automatic-subject-assignment.preview', compact(
            'student',
            'preview',
            'validationErrors'
        ));
    }

    /**
     * Assign subjects to a specific student
     */
    public function assignToStudent(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $schoolYear = $request->input('school_year', $this->getCurrentSchoolYear());

        $success = $this->assignmentService->assignSubjectsToStudent($student, $schoolYear);

        if ($success) {
            $assignedCount = $student->subjects()->count();
            return redirect()->route('registrar.automatic-subject-assignment.index')
                ->with('success', "✅ Successfully assigned {$assignedCount} subjects to {$student->first_name} {$student->last_name}!");
        } else {
            return redirect()->back()
                ->with('error', 'Failed to assign subjects. Please check the student\'s track and strand information.');
        }
    }

    /**
     * Bulk assign subjects to multiple students
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'school_year' => 'required|string'
        ]);

        $students = Student::whereIn('id', $request->student_ids)->get();
        $schoolYear = $request->school_year;

        $result = $this->assignmentService->bulkAssignSubjects($students, $schoolYear);

        $message = "✅ Bulk assignment completed! ";
        $message .= "Successfully assigned: {$result['success_count']}, ";
        $message .= "Failed: {$result['failure_count']}, ";
        $message .= "Total processed: {$result['total_processed']}";

        return redirect()->route('registrar.automatic-subject-assignment.index')
            ->with('success', $message);
    }

    /**
     * Reassign subjects to students who need it
     */
    public function bulkReassign(Request $request)
    {
        $request->validate([
            'school_year' => 'required|string'
        ]);

        $studentsNeedingReassignment = $this->assignmentService->getStudentsNeedingReassignment();
        $schoolYear = $request->school_year;

        $successCount = 0;
        $failureCount = 0;

        foreach ($studentsNeedingReassignment as $student) {
            if ($this->assignmentService->reassignSubjectsToStudent($student, $schoolYear)) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        $message = "✅ Bulk reassignment completed! ";
        $message .= "Successfully reassigned: {$successCount}, ";
        $message .= "Failed: {$failureCount}";

        return redirect()->route('registrar.automatic-subject-assignment.index')
            ->with('success', $message);
    }

    /**
     * Show curriculum mapping
     */
    public function curriculumMapping()
    {
        // Get all tracks and their subjects
        $tracks = Subject::select('track', 'strand', 'grade_level')
            ->distinct()
            ->orderBy('track')
            ->orderBy('strand')
            ->orderBy('grade_level')
            ->get()
            ->groupBy('track');

        // Get subject counts by category
        $subjectCounts = [
            'core' => Subject::where('is_core_subject', true)->count(),
            'academic_track' => Subject::where('track', 'Academic Track')->count(),
            'tvl_track' => Subject::where('track', 'TVL Track')->count(),
            'total' => Subject::count()
        ];

        return view('registrar.automatic-subject-assignment.curriculum-mapping', compact(
            'tracks',
            'subjectCounts'
        ));
    }

    /**
     * Get subjects for a specific track/strand combination (AJAX)
     */
    public function getSubjectsForTrackStrand(Request $request)
    {
        $request->validate([
            'track' => 'required|string',
            'strand' => 'required|string',
            'grade_level' => 'required|string'
        ]);

        $subjects = Subject::where('grade_level', $request->grade_level)
            ->where(function($query) use ($request) {
                $query->where('is_core_subject', true)
                      ->orWhere(function($subQuery) use ($request) {
                          $subQuery->where('track', $request->track)
                                   ->where('strand', $request->strand);
                      });
            })
            ->with('teacher')
            ->orderBy('is_core_subject', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'subjects' => $subjects,
            'count' => $subjects->count()
        ]);
    }

    /**
     * Test automatic assignment for a student (preview only)
     */
    public function testAssignment(Request $request)
    {
        $request->validate([
            'track' => 'required|string',
            'strand' => 'required|string',
            'grade_level' => 'required|string'
        ]);

        // Create a temporary student object for testing
        $testStudent = new Student([
            'track' => $request->track,
            'strand' => $request->strand,
            'grade_level' => $request->grade_level
        ]);

        $preview = $this->assignmentService->previewSubjectsForStudent($testStudent);
        $validationErrors = $this->assignmentService->validateStudentForAssignment($testStudent);

        return response()->json([
            'success' => true,
            'preview' => $preview,
            'validation_errors' => $validationErrors,
            'total_subjects' => $preview['total_count']
        ]);
    }

    /**
     * Fix students with incomplete data
     */
    public function fixIncompleteData()
    {
        $studentsWithIncompleteData = Student::where(function($query) {
            $query->whereNull('track')
                  ->orWhereNull('strand')
                  ->orWhereNull('grade_level');
        })->get();

        return view('registrar.automatic-subject-assignment.fix-incomplete-data', compact(
            'studentsWithIncompleteData'
        ));
    }

    /**
     * Update student data and trigger automatic assignment
     */
    public function updateStudentData(Request $request, $studentId)
    {
        $request->validate([
            'track' => 'required|string',
            'strand' => 'required|string',
            'grade_level' => 'required|string'
        ]);

        $student = Student::findOrFail($studentId);
        
        $student->update([
            'track' => $request->track,
            'strand' => $request->strand,
            'grade_level' => $request->grade_level
        ]);

        // The model event will automatically trigger subject assignment

        return redirect()->back()
            ->with('success', "✅ Updated {$student->first_name} {$student->last_name}'s information and assigned subjects automatically!");
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
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
