<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects for principal view
     */
    public function index()
    {
        // Get all subjects with their teacher assignments and student counts
        $subjects = Subject::with(['teacherAssignments' => function($query) {
            $query->where('status', 'active')
                  ->with('teacher')
                  ->latest();
        }])
        ->withCount('students')
        ->orderBy('name')
        ->paginate(20);

        // Calculate statistics
        $totalSubjects = Subject::count();
        $assignedSubjects = Subject::whereHas('teacherAssignments', function($query) {
            $query->where('status', 'active');
        })->count();
        $coreSubjects = Subject::where('is_core_subject', true)->count();
        $masterSubjects = Subject::where('is_master_subject', true)->count();

        // Get subjects by grade level for overview
        $subjectsByGrade = Subject::selectRaw('grade_level, COUNT(*) as count')
            ->groupBy('grade_level')
            ->pluck('count', 'grade_level');

        // Get subjects by track for overview
        $subjectsByTrack = Subject::selectRaw('track, COUNT(*) as count')
            ->whereNotNull('track')
            ->groupBy('track')
            ->pluck('count', 'track');

        return view('Principal.subjects.index', compact(
            'subjects',
            'totalSubjects',
            'assignedSubjects',
            'coreSubjects',
            'masterSubjects',
            'subjectsByGrade',
            'subjectsByTrack'
        ));
    }

    /**
     * Display the specified subject
     */
    public function show(Subject $subject)
    {
        // Load relationships
        $subject->load([
            'teacherAssignments' => function($query) {
                $query->where('status', 'active')->with('teacher');
            },
            'students' => function($query) {
                $query->orderBy('last_name')->orderBy('first_name');
            }
        ]);

        // Get subject statistics
        $totalStudents = $subject->students->count();
        $gradeDistribution = $subject->students->groupBy('grade_level')->map->count();
        $trackDistribution = $subject->students->groupBy('track')->map->count();

        return view('Principal.subjects.show', compact(
            'subject',
            'totalStudents',
            'gradeDistribution',
            'trackDistribution'
        ));
    }
}
