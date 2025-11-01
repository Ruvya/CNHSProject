<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrackController extends Controller
{
    /**
     * Display unified tracks and clusters management page
     */
    public function index(): View
    {
        $tracks = Track::with('clusters')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('admin.tracks.index', compact('tracks'));
    }

    /**
     * Show the form for creating a new track
     */
    public function create(): View
    {
        return view('admin.tracks.create');
    }

    /**
     * Store a newly created track
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tracks,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        Track::create($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track created successfully.');
    }

    /**
     * Display the specified track
     */
    public function show(Track $track): View
    {
        $track->load('clusters');
        return view('admin.tracks.show', compact('track'));
    }

    /**
     * Show the form for editing the specified track
     */
    public function edit(Track $track): View
    {
        return view('admin.tracks.edit', compact('track'));
    }

    /**
     * Update the specified track
     */
    public function update(Request $request, Track $track): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tracks,name,' . $track->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        $track->update($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track updated successfully.');
    }

    /**
     * Remove the specified track
     */
    public function destroy(Track $track): RedirectResponse
    {
        if (!$track->canBeDeleted()) {
            return redirect()->route('admin.tracks.index')
                ->with('error', 'Cannot delete track. It has clusters or is assigned to students/subjects/teachers.');
        }

        $track->delete();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track deleted successfully.');
    }

    /**
     * Toggle track status
     */
    public function toggleStatus(Track $track): RedirectResponse
    {
        $track->status = $track->status === 'active' ? 'inactive' : 'active';
        $track->save();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track status updated successfully.');
    }
}

