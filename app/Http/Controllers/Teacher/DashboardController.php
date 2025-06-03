<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get classes (subjects) assigned to the teacher through teacher assignments
        $assignedSubjects = $teacher->assignedSubjects()->get();

        // Also get subjects assigned directly (old way) for backward compatibility
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();

        // Combine both assignment methods
        $allSubjects = $assignedSubjects->merge($directSubjects)->unique('id');
        $totalClasses = $allSubjects->count();

        // Get total students across all assigned classes (both assignment methods)
        $assignedSubjectIds = $assignedSubjects->pluck('id')->toArray();
        $directSubjectIds = $directSubjects->pluck('id')->toArray();
        $allSubjectIds = array_unique(array_merge($assignedSubjectIds, $directSubjectIds));

        $totalStudents = Student::whereHas('subjects', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        })->count();

        // Get total subjects assigned to teacher (same as classes in this context)
        $totalSubjects = $totalClasses;

        // Get pending tasks (grades that need to be submitted)
        // Count grades where all quarters are null (no grades entered yet)
        $pendingTasks = 0;
        try {
            $pendingTasks = Grade::whereHas('subject', function($query) use ($allSubjectIds) {
                $query->whereIn('subjects.id', $allSubjectIds);
            })
            ->whereNull('quarter1')
            ->whereNull('quarter2')
            ->whereNull('quarter3')
            ->whereNull('quarter4')
            ->count();
        } catch (\Exception $e) {
            // If there's any error, default to 0
            $pendingTasks = 0;
        }

        // Get recent activities (grades posted)
        $recentActivities = collect(); // Initialize as empty collection

        try {
            $recentActivities = Grade::whereHas('subject', function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->with(['student', 'subject'])
            ->where(function($query) {
                $query->whereNotNull('quarter1')
                      ->orWhereNotNull('quarter2')
                      ->orWhereNotNull('quarter3')
                      ->orWhereNotNull('quarter4')
                      ->orWhereNotNull('final_grade');
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function($grade) {
                // Determine which grade to show (prioritize final_grade, then latest quarter)
                $gradeValue = $grade->final_grade ?? $grade->quarter4 ?? $grade->quarter3 ?? $grade->quarter2 ?? $grade->quarter1;
                $gradeType = $grade->final_grade ? 'Final Grade' : 'Quarter Grade';

                return (object)[
                    'title' => "Grade posted for {$grade->student->first_name} {$grade->student->last_name}",
                    'description' => "{$grade->subject->name}: {$gradeValue} ({$gradeType})",
                    'created_at' => $grade->created_at
                ];
            });
        } catch (\Exception $e) {
            // If there's any error with grades, just return empty collection
            $recentActivities = collect();
        }

        // Generate overview message
        $overviewMessage = "You are currently assigned to {$totalClasses} classes. You are teaching a total of {$totalStudents} students.";

        return view('teacher.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'totalClasses',
            'pendingTasks',
            'recentActivities',
            'overviewMessage'
        ));
    }
}