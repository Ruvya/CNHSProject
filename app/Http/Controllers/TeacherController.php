<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function dashboard()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $allSubjects = $assignedSubjects->merge($directSubjects)->unique('id');
        $allSubjectIds = $allSubjects->pluck('id')->toArray();

        // Get total students in teacher's subjects
        $totalStudents = Student::whereHas('subjects', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        })->count();

        // Get total subjects assigned to teacher
        $totalSubjects = $allSubjects->count();

        // Get total classes (subjects with students)
        $totalClasses = Subject::whereIn('id', $allSubjectIds)
            ->whereHas('students')
            ->count();

        // Get pending tasks (grades that need to be submitted)
        $pendingTasks = Grade::whereHas('subject', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        })
        ->whereNull('grade')
        ->count();

        // Get recent activities (grades posted)
        $recentActivities = Grade::whereHas('subject', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        })
        ->with(['student', 'subject'])
        ->latest()
        ->take(5)
        ->get()
        ->map(function($grade) {
            return (object)[
                'title' => "Grade posted for {$grade->student->name}",
                'description' => "{$grade->subject->name}: {$grade->grade}",
                'created_at' => $grade->created_at
            ];
        });

        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'totalClasses',
            'pendingTasks',
            'recentActivities'
        ));
    }
}