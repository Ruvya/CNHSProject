<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the teacher's announcements.
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Get announcements created by this teacher
        $announcements = Announcement::where('author_type', 'App\Models\Teacher')
            ->where('author_id', $teacher->id)
            ->latest()
            ->get();

        return view('teacher.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('teacher.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,draft'
        ]);

        // Add author information
        $validated['author_type'] = 'App\Models\Teacher';
        $validated['author_id'] = $teacher->id;

        // Set published status based on status
        $validated['is_published'] = $validated['status'] === 'active';
        $validated['published_at'] = $validated['status'] === 'active' ? now() : null;

        Announcement::create($validated);

        $message = $validated['status'] === 'draft' ? 'Announcement saved as draft.' : 'Announcement published successfully.';
        return redirect()->route('teacher.announcements.index')->with('success', $message);
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the announcement belongs to this teacher
        if ($announcement->author_type !== 'App\Models\Teacher' || $announcement->author_id !== $teacher->id) {
            abort(403, 'You do not have access to this announcement.');
        }

        return view('teacher.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the announcement belongs to this teacher
        if ($announcement->author_type !== 'App\Models\Teacher' || $announcement->author_id !== $teacher->id) {
            abort(403, 'You do not have access to this announcement.');
        }

        return view('teacher.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the announcement belongs to this teacher
        if ($announcement->author_type !== 'App\Models\Teacher' || $announcement->author_id !== $teacher->id) {
            abort(403, 'You do not have access to this announcement.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,draft'
        ]);

        // Set published status based on status
        $validated['is_published'] = $validated['status'] === 'active';
        $validated['published_at'] = $validated['status'] === 'active' ? now() : null;

        $announcement->update($validated);

        $message = $validated['status'] === 'draft' ? 'Announcement saved as draft.' : 'Announcement updated and published successfully.';
        return redirect()->route('teacher.announcements.index')->with('success', $message);
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the announcement belongs to this teacher
        if ($announcement->author_type !== 'App\Models\Teacher' || $announcement->author_id !== $teacher->id) {
            abort(403, 'You do not have access to this announcement.');
        }

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Get students that can see this teacher's announcements
     */
    public function getTargetStudents()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get all students enrolled in this teacher's subjects
        $students = $teacher->students()->get();

        return $students;
    }

    /**
     * Get principal announcements for notification bell
     */
    public function getPrincipalAnnouncements()
    {
        $announcements = Announcement::where('author_type', 'App\Models\Principal')
            ->where('status', 'active')
            ->where('is_published', true)
            ->latest()
            ->take(10) // Limit to 10 most recent
            ->get();

        return response()->json([
            'announcements' => $announcements,
            'count' => $announcements->count()
        ]);
    }
}
