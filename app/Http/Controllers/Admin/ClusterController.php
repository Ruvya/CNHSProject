<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ClusterController extends Controller
{
    /**
     * Show the form for creating a new cluster
     */
    public function create(Request $request): View
    {
        $trackId = $request->get('track_id');
        $tracks = Track::active()->orderBy('name')->get();

        return view('admin.clusters.create', compact('tracks', 'trackId'));
    }

    /**
     * Store a newly created cluster
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'track_id' => 'required|exists:tracks,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        // Check for unique name within the track
        $existing = Cluster::where('track_id', $validated['track_id'])
            ->where('name', $validated['name'])
            ->exists();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'A cluster with this name already exists for this track.']);
        }

        Cluster::create($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Cluster created successfully.');
    }

    /**
     * Display the specified cluster
     */
    public function show(Cluster $cluster): View
    {
        $cluster->load('track');
        return view('admin.clusters.show', compact('cluster'));
    }

    /**
     * Show the form for editing the specified cluster
     */
    public function edit(Cluster $cluster): View
    {
        $tracks = Track::active()->orderBy('name')->get();
        return view('admin.clusters.edit', compact('cluster', 'tracks'));
    }

    /**
     * Update the specified cluster
     */
    public function update(Request $request, Cluster $cluster): RedirectResponse
    {
        $validated = $request->validate([
            'track_id' => 'required|exists:tracks,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        // Check for unique name within the track (excluding current cluster)
        $existing = Cluster::where('track_id', $validated['track_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $cluster->id)
            ->exists();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'A cluster with this name already exists for this track.']);
        }

        $cluster->update($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Cluster updated successfully.');
    }

    /**
     * Remove the specified cluster
     */
    public function destroy(Cluster $cluster): RedirectResponse
    {
        if (!$cluster->canBeDeleted()) {
            return redirect()->route('admin.tracks.index')
                ->with('error', 'Cannot delete cluster. It is assigned to students/subjects/teachers.');
        }

        $cluster->delete();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Cluster deleted successfully.');
    }

    /**
     * Toggle cluster status
     */
    public function toggleStatus(Cluster $cluster): RedirectResponse
    {
        $cluster->status = $cluster->status === 'active' ? 'inactive' : 'active';
        $cluster->save();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Cluster status updated successfully.');
    }

    /**
     * Get clusters by track ID (AJAX endpoint)
     */
    public function getByTrack($trackId): JsonResponse
    {
        $track = Track::findOrFail($trackId);
        $clusters = $track->activeClusters()
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'description']);

        return response()->json([
            'success' => true,
            'clusters' => $clusters
        ]);
    }
}

