<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use App\Services\SemesterService;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();
        $selectedSemester = $request->get('semester');

        // Get all subjects assigned to this student
        // Note: We don't need additional filtering here because subjects should only be assigned
        // to students if they match their grade, track, and strand
        $assignedSubjects = $student->subjects()
            ->with('teacher')
            ->get();

        // Optional semester filter based on subject's semester
        if (!empty($selectedSemester)) {
            $assignedSubjects = $assignedSubjects->filter(function ($subject) use ($selectedSemester) {
                $subjectSemester = $subject->semester ?? null;
                return empty($subjectSemester) || $subjectSemester === $selectedSemester;
            })->values();
        }

        // Categorize subjects according to DepEd curriculum structure
        $coreSubjects = $assignedSubjects->where('is_core_subject', true);

        // Applied subjects (track-specific but not strand-specific)
        $appliedSubjects = $assignedSubjects->where('track', $student->track)
            ->where('is_core_subject', false)
            ->filter(function($subject) {
                return empty($subject->cluster) || $subject->cluster === null;
            });

        // Specialized subjects (strand-specific)
        $specializedSubjects = $assignedSubjects->where('cluster', $student->cluster)
            ->where('is_core_subject', false)
            ->where('cluster', '!=', null);

        // Legacy categorization for backward compatibility
        $trackSubjects = $assignedSubjects->where('track', $student->track)->where('is_core_subject', false)->where('cluster', '!=', $student->cluster);
        $clusterSubjects = $assignedSubjects->where('cluster', $student->cluster)->where('is_core_subject', false);

        // Calculate statistics
        $totalAssigned = $assignedSubjects->count();
        $subjectsWithTeachers = $assignedSubjects->whereNotNull('teacher_id')->count();
        $subjectsWithoutTeachers = $totalAssigned - $subjectsWithTeachers;

        // Check if student has complete assignment
        $hasCompleteAssignment = $student->isReadyForSubjectAssignment() && $student->hasCompleteSubjectAssignment();

        // Get assignment status
        $assignmentStatus = $this->getAssignmentStatus($student);

        // Add a simple $subjects variable for the view template compatibility
        $subjects = $assignedSubjects;

        $semesterOptions = SemesterService::getSemesterOptions();

        return view('student.subjects', compact(
            'student',
            'subjects',
            'assignedSubjects',
            'coreSubjects',
            'appliedSubjects',
            'specializedSubjects',
            'trackSubjects',
            'clusterSubjects',
            'totalAssigned',
            'subjectsWithTeachers',
            'subjectsWithoutTeachers',
            'hasCompleteAssignment',
            'assignmentStatus',
            'semesterOptions',
            'selectedSemester'
        ));
    }

    /**
     * Get assignment status for the student
     */
    private function getAssignmentStatus($student)
    {
        if (!$student->track || !$student->cluster || !$student->grade_level) {
            return [
                'status' => 'incomplete_data',
                'message' => 'Your track, cluster, or grade level information is incomplete. Please contact the registrar to update your information.',
                'color' => 'warning'
            ];
        }

        if ($student->subjects->count() == 0) {
            return [
                'status' => 'no_subjects',
                'message' => 'No subjects have been assigned yet. Subjects will be automatically assigned based on your track and cluster.',
                'color' => 'info'
            ];
        }

        if (!$student->hasCompleteSubjectAssignment()) {
            return [
                'status' => 'needs_update',
                'message' => 'Your subject assignment may need updating based on your current track and cluster.',
                'color' => 'warning'
            ];
        }

        return [
            'status' => 'complete',
            'message' => 'Your subjects have been automatically assigned based on your track and cluster.',
            'color' => 'success'
        ];
    }

    public function show($id)
    {
        $student = Auth::guard('student')->user();

        // Get the specific subject with teacher information
        $subject = Subject::with('teacher')->findOrFail($id);

        // Check if this subject is assigned to the student
        $isAssigned = $student->subjects()->where('subject_id', $id)->exists();

        // Get the student's grade for this subject if it exists
        $studentGrade = $student->grades()->where('subject_id', $id)->first();

        // Get other students in this subject (for class size info)
        $classSize = $subject->students()->count();

        return view('student.subject-details', compact(
            'student',
            'subject',
            'isAssigned',
            'studentGrade',
            'classSize'
        ));
    }
}