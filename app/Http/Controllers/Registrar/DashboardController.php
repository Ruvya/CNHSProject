<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $registrar = auth()->guard('registrar')->user();

        // Basic Statistics
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $mySubjects = Subject::where('registrar_id', $registrar->id)->count();
        $totalTeachers = Teacher::count();
        $assignedSubjects = Subject::whereNotNull('teacher_id')->count();
        $unassignedSubjects = Subject::whereNull('teacher_id')->count();

        // Student Analytics
        $studentsByGrade = Student::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        $studentsByTrack = Student::select('track', DB::raw('count(*) as count'))
            ->whereNotNull('track')
            ->groupBy('track')
            ->get();

        $studentsByStrand = Student::select('strand', DB::raw('count(*) as count'))
            ->whereNotNull('strand')
            ->where('strand', '!=', '')
            ->groupBy('strand')
            ->orderBy('count', 'desc')
            ->get();

        // Subject Analytics
        $subjectsByGrade = Subject::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        $subjectsByTrack = Subject::select('track', DB::raw('count(*) as count'))
            ->whereNotNull('track')
            ->groupBy('track')
            ->get();

        // Recent Activities
        $recentSubjects = Subject::with(['teacher', 'registrar'])
            ->latest()
            ->take(5)
            ->get();

        $recentStudents = Student::latest()
            ->take(5)
            ->get();

        // My Recent Subjects
        $myRecentSubjects = Subject::where('registrar_id', $registrar->id)
            ->with('teacher')
            ->latest()
            ->take(5)
            ->get();

        // Growth Statistics (last 30 days)
        $recentStudentsCount = Student::where('created_at', '>=', now()->subDays(30))->count();
        $recentSubjectsCount = Subject::where('created_at', '>=', now()->subDays(30))->count();

        // Subject Assignment Statistics
        $assignmentStats = [
            'total' => $totalSubjects,
            'assigned' => $assignedSubjects,
            'unassigned' => $unassignedSubjects,
            'assignment_rate' => $totalSubjects > 0 ? round(($assignedSubjects / $totalSubjects) * 100, 1) : 0
        ];

        return view('registrar.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'mySubjects',
            'totalTeachers',
            'assignedSubjects',
            'unassignedSubjects',
            'studentsByGrade',
            'studentsByTrack',
            'studentsByStrand',
            'subjectsByGrade',
            'subjectsByTrack',
            'recentSubjects',
            'recentStudents',
            'myRecentSubjects',
            'recentStudentsCount',
            'recentSubjectsCount',
            'assignmentStats',
            'registrar'
        ));
    }

    public function profile()
    {
        return view('registrar.profile');
    }

    public function updateProfile(Request $request)
    {
        $registrar = auth()->guard('registrar')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $registrar->first_name = $request->name;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $registrar->profile_picture = $path;
        }
        $registrar->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}