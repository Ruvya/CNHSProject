<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    /**
     * Display a listing of subject assignments for principal view
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $selectedGradeLevel = $request->get('grade_level');
        $selectedTrack = $request->get('track');
        $schoolYear = $request->get('school_year', $this->getCurrentSchoolYear());
        $semester = $request->get('semester', $this->getCurrentSemester());

        // Get all teachers and subjects for dropdowns
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        // Build query for assignments
        $query = TeacherAssignment::with(['teacher', 'subject'])
            ->where('status', 'active')
            ->where('school_year', $schoolYear);

        // Apply filters
        if ($selectedTeacher) {
            $query->where('teacher_id', $selectedTeacher);
        }

        if ($selectedSubject) {
            $query->where('subject_id', $selectedSubject);
        }

        if ($selectedGradeLevel) {
            $query->whereHas('subject', function($q) use ($selectedGradeLevel) {
                $q->where('grade_level', $selectedGradeLevel);
            });
        }

        if ($selectedTrack) {
            $query->whereHas('subject', function($q) use ($selectedTrack) {
                $q->where('track', $selectedTrack);
            });
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        $assignments = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total_assignments' => TeacherAssignment::where('status', 'active')
                ->where('school_year', $schoolYear)
                ->count(),
            'unique_teachers' => TeacherAssignment::where('status', 'active')
                ->where('school_year', $schoolYear)
                ->distinct('teacher_id')
                ->count(),
            'unique_subjects' => TeacherAssignment::where('status', 'active')
                ->where('school_year', $schoolYear)
                ->distinct('subject_id')
                ->count(),
            'unassigned_subjects' => Subject::whereDoesntHave('teacherAssignments', function($q) use ($schoolYear) {
                $q->where('status', 'active')->where('school_year', $schoolYear);
            })->count()
        ];

        return view('Principal.subject-assignments.index', compact(
            'teachers', 'subjects', 'assignments', 'stats',
            'selectedTeacher', 'selectedSubject', 'selectedGradeLevel', 
            'selectedTrack', 'schoolYear', 'gradingPeriod'
        ));
    }

    /**
     * Display the specified assignment
     */
    public function show(TeacherAssignment $assignment)
    {
        $assignment->load(['teacher', 'subject', 'subject.students']);

        // Get assignment statistics
        $studentCount = $assignment->subject->students->count();
        $gradeDistribution = $assignment->subject->students->groupBy('grade_level')->map->count();
        $trackDistribution = $assignment->subject->students->groupBy('track')->map->count();

        return view('Principal.subject-assignments.show', compact(
            'assignment',
            'studentCount',
            'gradeDistribution',
            'trackDistribution'
        ));
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

    /**
     * Get current semester
     */
    private function getCurrentSemester()
    {
        $currentMonth = date('n');
        
        // Determine semester based on month
        // 1st Semester: June to December (months 6-12)
        // 2nd Semester: January to May (months 1-5)
        if ($currentMonth >= 6 && $currentMonth <= 12) {
            return '1st Semester';
        } else {
            return '2nd Semester';
        }
    }

    /**
     * Get current grading period (deprecated - use getCurrentSemester)
     */
    private function getCurrentGradingPeriod()
    {
        return $this->getCurrentSemester();
    }
}
