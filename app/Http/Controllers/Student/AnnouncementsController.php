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
}