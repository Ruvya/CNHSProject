<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementsController extends Controller
{
    public function index()
    {
        try {
            $student = Auth::guard('student')->user();

            // Get all announcements that the student should see
            $announcements = collect();

            // 1. Get principal announcements (visible to all students)
            $principalAnnouncements = Announcement::with('author')
                ->where('status', 'active')
                ->where('is_published', true)
                ->where('author_type', 'App\Models\Principal')
                ->get();

            // 2. Get teacher announcements from teachers of subjects the student is enrolled in
            if ($student) {
                // Get all teacher IDs from subjects the student is enrolled in
                $teacherIds = $student->subjects()
                    ->whereNotNull('teacher_id')
                    ->pluck('subjects.teacher_id')
                    ->unique();

                $teacherAnnouncements = Announcement::with('author')
                    ->where('status', 'active')
                    ->where('is_published', true)
                    ->where('author_type', 'App\Models\Teacher')
                    ->whereIn('author_id', $teacherIds)
                    ->get();

                // Combine all announcements
                $announcements = $principalAnnouncements->merge($teacherAnnouncements);
            } else {
                // If no student is logged in, only show principal announcements
                $announcements = $principalAnnouncements;
            }

            // Sort by creation date (newest first)
            $announcements = $announcements->sortByDesc('created_at');

        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), return empty collection
            $announcements = collect();
        }

        return view('student.announcements', compact('announcements'));
    }
}