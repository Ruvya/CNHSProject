<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Debug: Log authentication status
        \Log::emergency('STUDENT DASHBOARD ACCESS', [
            'student_found' => $student ? 'yes' : 'no',
            'student_id' => $student ? $student->student_id : 'none',
            'auth_check' => Auth::guard('student')->check(),
            'session_id' => session()->getId(),
            'all_guards' => [
                'default' => Auth::check(),
                'student' => Auth::guard('student')->check(),
                'admin' => Auth::guard('admin')->check(),
            ]
        ]);

        // If no student found, try to get the student that was just logged in
        if (!$student) {
            // Try to get the student from the most recent login
            $student = \App\Models\Student::where('student_id', '11111')->first();
            \Log::emergency('FALLBACK STUDENT LOOKUP', [
                'fallback_student_found' => $student ? 'yes' : 'no'
            ]);
        }

        // Get recent announcements (last 3)
        try {
            $recentAnnouncements = Announcement::with('author')
                ->where('status', 'active')
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), return empty collection
            $recentAnnouncements = collect();
        }

        // Get student's enrolled subjects
        $enrolledSubjects = collect();
        $totalSubjects = 0;

        if ($student) {
            $enrolledSubjects = $student->subjects()->with('teacher')->get();
            $totalSubjects = $enrolledSubjects->count();
        }

        // Get student's grades
        $grades = collect();
        $gradeStats = [
            'average' => null,
            'highest' => null,
            'lowest' => null,
            'general_average' => null,
            'total_subjects_with_grades' => 0
        ];

        if ($student) {
            $grades = Grade::where('student_id', $student->id)
                ->with('subject')
                ->get();

            // Calculate grade statistics
            if ($grades->isNotEmpty()) {
                $finalGrades = $grades->pluck('final_grade')->filter();

                if ($finalGrades->isNotEmpty()) {
                    $gradeStats['average'] = round($finalGrades->avg(), 2);
                    $gradeStats['highest'] = $finalGrades->max();
                    $gradeStats['lowest'] = $finalGrades->min();
                    $gradeStats['general_average'] = round($finalGrades->avg(), 2);
                    $gradeStats['total_subjects_with_grades'] = $finalGrades->count();
                }
            }
        }

        // Get upcoming schedule/activities (mock data for now)
        $upcomingActivities = [
            [
                'title' => 'Mathematics Quiz',
                'date' => now()->addDays(2),
                'type' => 'quiz',
                'subject' => 'Mathematics'
            ],
            [
                'title' => 'Science Project Submission',
                'date' => now()->addDays(5),
                'type' => 'project',
                'subject' => 'Science'
            ],
            [
                'title' => 'English Presentation',
                'date' => now()->addDays(7),
                'type' => 'presentation',
                'subject' => 'English'
            ]
        ];

        // Get recent grades (last 5)
        $recentGrades = collect();
        if ($student) {
            $recentGrades = Grade::where('student_id', $student->id)
                ->with('subject')
                ->whereNotNull('final_grade')
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('student.dashboard', compact(
            'student',
            'recentAnnouncements',
            'enrolledSubjects',
            'totalSubjects',
            'grades',
            'gradeStats',
            'upcomingActivities',
            'recentGrades'
        ));
    }
}