<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Events\AnnouncementDeleted;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $principal = auth()->guard('principal')->user();
        $announcements = Announcement::where('author_type', 'App\Models\Principal')
            ->where('author_id', $principal->id)
            ->latest()
            ->get();
        return view('Principal.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Principal.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,draft'
        ]);

        // Add author information
        $validated['author_type'] = 'App\Models\Principal';
        $validated['author_id'] = auth()->guard('principal')->id();

        // Set published status based on status
        $validated['is_published'] = $validated['status'] === 'active';
        $validated['published_at'] = $validated['status'] === 'active' ? now() : null;

        Announcement::create($validated);

        $message = $validated['status'] === 'draft' ? 'Announcement saved as draft.' : 'Announcement published successfully.';
        return redirect()->route('principal.announcements.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $principal = auth()->guard('principal')->user();
        $announcement = Announcement::where('author_type', 'App\Models\Principal')
            ->where('author_id', $principal->id)
            ->findOrFail($id);
        return view('Principal.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $principal = auth()->guard('principal')->user();
        $announcement = Announcement::where('author_type', 'App\Models\Principal')
            ->where('author_id', $principal->id)
            ->findOrFail($id);
        return view('Principal.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $principal = auth()->guard('principal')->user();
        
        // Verify the announcement belongs to this principal
        if ($announcement->author_type !== 'App\Models\Principal' || $announcement->author_id !== $principal->id) {
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
        return redirect()->route('principal.announcements.index')->with('success', $message);
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            $principal = auth()->guard('principal')->user();
            
            // Verify the announcement belongs to this principal
            if ($announcement->author_type !== 'App\Models\Principal' || $announcement->author_id !== $principal->id) {
                return redirect()->route('principal.announcements.index')
                    ->with('error', 'You do not have access to this announcement.');
            }

            // Check if the announcement exists
            if (!$announcement) {
                return redirect()->route('principal.announcements.index')
                    ->with('error', 'Announcement not found.');
            }

            // Store the ID before deletion
            $announcementId = $announcement->id;

            // Attempt to delete the announcement
            $announcement->delete();

            // Broadcast the deletion event
            event(new AnnouncementDeleted($announcementId));

            return redirect()->route('principal.announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error deleting announcement: ' . $e->getMessage());

            return redirect()->route('principal.announcements.index')
                ->with('error', 'Error deleting announcement. Please try again.');
        }
    }
}
