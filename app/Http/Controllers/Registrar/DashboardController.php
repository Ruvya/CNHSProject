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
    protected function getActingUser()
    {
        if (\Auth::guard('admin')->check()) {
            return ['user' => \Auth::guard('admin')->user(), 'type' => 'admin'];
        } elseif (\Auth::guard('registrar')->check()) {
            return ['user' => \Auth::guard('registrar')->user(), 'type' => 'registrar'];
        }
        return ['user' => null, 'type' => null];
    }

    public function index()
    {
        $acting = $this->getActingUser();
        $registrar = $acting['user'];

        // Basic Statistics
        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $mySubjects = $acting['type'] === 'registrar' ? Subject::where('registrar_id', $registrar->id)->count() : Subject::count();
        $totalTeachers = Teacher::count();
        $assignedSubjects = Subject::whereNotNull('teacher_id')->count();
        $unassignedSubjects = Subject::whereNull('teacher_id')->count();

        // Student Analytics
        $studentsByGrade = Student::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get()
            ->map(function($item) {
                return [
                    'grade_level' => $item->grade_level ? "Grade {$item->grade_level}" : 'Not Set',
                    'count' => (int)$item->count
                ];
            });

        $studentsByTrack = Student::select('track', DB::raw('count(*) as count'))
            ->whereNotNull('track')
            ->where('track', '!=', '')
            ->groupBy('track')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'track' => $item->track,
                    'count' => (int)$item->count
                ];
            });

        $studentsByStrand = Student::select('strand', DB::raw('count(*) as count'))
            ->whereNotNull('strand')
            ->where('strand', '!=', '')
            ->groupBy('strand')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'strand' => $item->strand,
                    'count' => (int)$item->count
                ];
            });

        // Subject Analytics
        $subjectsByGrade = Subject::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get()
            ->map(function($item) {
                return [
                    'grade_level' => $item->grade_level ? "Grade {$item->grade_level}" : 'Not Set',
                    'count' => (int)$item->count
                ];
            });

        $subjectsByTrack = Subject::select('track', DB::raw('count(*) as count'))
            ->whereNotNull('track')
            ->where('track', '!=', '')
            ->groupBy('track')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'track' => $item->track,
                    'count' => (int)$item->count
                ];
            });

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

        // New data for charts
        $studentsByGradeLevel = Student::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get()
            ->map(function($item) {
                return [
                    'grade_level' => $item->grade_level ? "Grade {$item->grade_level}" : 'Not Set',
                    'count' => (int)$item->count
                ];
            });

        $subjectsByTrack = Subject::whereNotNull('track')
            ->select('track', DB::raw('count(*) as total'))
            ->groupBy('track')
            ->pluck('total', 'track');

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
            'studentsByGradeLevel',
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