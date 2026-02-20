<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Announcement;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('Principal Dashboard Access', [
            'user' => Auth::guard('principal')->user(),
            'authenticated' => Auth::guard('principal')->check()
        ]);

        // Clear any potential caches to ensure fresh data
        if (function_exists('cache')) {
            cache()->forget('dashboard_announcements');
            cache()->forget('dashboard_stats');
        }

        try {
            // Get real statistics from database with error handling
            $totalStudents = 0;
            $totalTeachers = 0;
            $totalSubjects = 0;
            $activeClasses = 0;
            $recentAnnouncements = collect();
            $upcomingEvents = collect();

            // Check if tables exist and get counts
            try {
                $totalStudents = Student::whereNotNull('last_login_at')
                    ->orWhereHas('subjects')
                    ->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting students: ' . $e->getMessage());
                $totalStudents = 0;
            }

            try {
                $totalTeachers = Teacher::count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting teachers: ' . $e->getMessage());
                $totalTeachers = 0;
            }

            try {
                $totalSubjects = Subject::count() ?? 0;
                $activeClasses = Subject::whereNotNull('teacher_id')->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting subjects: ' . $e->getMessage());
                $totalSubjects = 0;
                $activeClasses = 0;
            }

            // Get recent announcements (fresh from database, no caching)
            try {
                if (class_exists('\App\Models\Announcement')) {
                    // Clear any potential query cache and get fresh data
                    cache()->forget('dashboard_announcements');

                    // Only show announcements authored by the Principal (exclude teacher announcements)
                    $recentAnnouncements = Announcement::where('status', 'active')
                        ->where('author_type', 'App\\Models\\Principal')
                        ->whereNull('deleted_at') // Exclude soft-deleted records
                        ->latest()
                        ->take(5)
                        ->get();

                    // Log the announcements for debugging
                    Log::info('Dashboard announcements loaded', [
                        'count' => $recentAnnouncements->count(),
                        'ids' => $recentAnnouncements->pluck('id')->toArray(),
                        'statuses' => $recentAnnouncements->pluck('status')->toArray(),
                        'titles' => $recentAnnouncements->pluck('title')->toArray(),
                        'query_includes_deleted' => false,
                        'timestamp' => now()
                    ]);

                    // Fetch upcoming events from the Event model
                    $upcomingEvents = Event::where('start', '>=', now())
                        ->orderBy('start', 'asc')
                        ->take(3) // Limit to 3 upcoming events, similar to the screenshot
                        ->get();
                } else {
                    $recentAnnouncements = collect();
                }
            } catch (\Exception $e) {
                Log::warning('Error getting announcements: ' . $e->getMessage());
                $recentAnnouncements = collect();
            }

            // Calculate capacity percentages (assuming school capacity)
            $schoolCapacity = 3500; // Adjust this based on actual school capacity
            $studentCapacityPercentage = $totalStudents > 0 ? min(round(($totalStudents / $schoolCapacity) * 100), 100) : 0;

            // Calculate teacher utilization (teachers with assigned subjects)
            $teachersWithSubjects = 0;
            try {
                $teachersWithSubjects = Teacher::whereHas('subjects')->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting teachers with subjects: ' . $e->getMessage());
                $teachersWithSubjects = 0;
            }
            $teacherUtilizationPercentage = $totalTeachers > 0 ? round(($teachersWithSubjects / $totalTeachers) * 100) : 0;

            // Calculate class room utilization (assuming 100 total rooms)
            $totalRooms = 100; // Adjust based on actual school rooms
            $roomUtilizationPercentage = $activeClasses > 0 ? min(round(($activeClasses / $totalRooms) * 100), 100) : 0;

            // Get student distribution by grade level
            $studentsByGrade = collect();
            try {
                $studentsByGrade = Student::select('grade_level', DB::raw('count(*) as count'))
                    ->groupBy('grade_level')
                    ->orderBy('grade_level')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Error getting students by grade: ' . $e->getMessage());
                $studentsByGrade = collect();
            }

            // Get student distribution by track
            $studentsByTrack = collect();
            try {
                $studentsByTrack = Student::select('track', DB::raw('count(*) as count'))
                    ->whereNotNull('track')
                    ->groupBy('track')
                    ->get();
            } catch (\Exception $e) {
                Log::warning('Error getting students by track: ' . $e->getMessage());
                $studentsByTrack = collect();
            }

            // Get recent activities (last 30 days)
            $recentStudentsCount = 0;
            $recentTeachersCount = 0;
            $recentSubjectsCount = 0;

            try {
                $recentStudentsCount = Student::where('created_at', '>=', now()->subDays(30))->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting recent students: ' . $e->getMessage());
            }

            try {
                $recentTeachersCount = Teacher::where('created_at', '>=', now()->subDays(30))->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting recent teachers: ' . $e->getMessage());
            }

            try {
                $recentSubjectsCount = Subject::where('created_at', '>=', now()->subDays(30))->count() ?? 0;
            } catch (\Exception $e) {
                Log::warning('Error counting recent subjects: ' . $e->getMessage());
            }

            // Create response with no-cache headers to prevent browser caching
            $response = response()->view('Principal.dashboard', compact(
                'totalStudents',
                'totalTeachers',
                'totalSubjects',
                'activeClasses',
                'recentAnnouncements',
                'upcomingEvents',
                'studentCapacityPercentage',
                'teacherUtilizationPercentage',
                'roomUtilizationPercentage',
                'studentsByGrade',
                'studentsByTrack',
                'recentStudentsCount',
                'recentTeachersCount',
                'recentSubjectsCount'
            ));

            // Add headers to prevent caching
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;

        } catch (\Exception $e) {
            Log::error('Principal Dashboard Error: ' . $e->getMessage());

            // Return dashboard with default values if there's an error
            return view('Principal.dashboard', [
                'totalStudents' => 0,
                'totalTeachers' => 0,
                'totalSubjects' => 0,
                'activeClasses' => 0,
                'recentAnnouncements' => collect(),
                'upcomingEvents' => collect(),
                'studentCapacityPercentage' => 0,
                'teacherUtilizationPercentage' => 0,
                'roomUtilizationPercentage' => 0,
                'studentsByGrade' => collect(),
                'studentsByTrack' => collect(),
                'recentStudentsCount' => 0,
                'recentTeachersCount' => 0,
                'recentSubjectsCount' => 0
            ]);
        }
    }

    // New method to fetch and return the upcoming events list HTML
    public function upcomingEventsHtml()
    {
        $upcomingEvents = Event::where('start', '>=', now())
            ->orderBy('start', 'asc')
            ->take(3)
            ->get();

        // Render just the partial view for upcoming events
        return view('Principal._upcoming_events_list', compact('upcomingEvents'))->render();
    }
}