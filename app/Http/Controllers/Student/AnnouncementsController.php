<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use App\Models\AnnouncementRead;

class AnnouncementsController extends Controller
{
    public function index()
    {
        try {
            // Use the same simple query that works on the dashboard
            $announcements = Announcement::with('author')
                ->where('status', 'active')
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->get();

        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), return empty collection
            $announcements = collect();
        }

        return view('student.announcements', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        // Ensure only active/published announcements are viewable by students
        if (!$announcement->is_published || $announcement->status !== 'active') {
            abort(404);
        }

        $student = Auth::guard('student')->user();
        if ($student) {
            AnnouncementRead::firstOrCreate(
                ['announcement_id' => $announcement->id, 'student_id' => $student->id],
                ['read_at' => now()]
            );
        }

        return view('student.announcement-show', [
            'announcement' => $announcement->load('author')
        ]);
    }
}