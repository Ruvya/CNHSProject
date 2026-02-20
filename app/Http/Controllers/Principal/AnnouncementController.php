<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Events\AnnouncementDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

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
        try {
            // Clear announcement cache
            cache()->forget('announcements_list');

            // Check if announcements table exists
            if (!\Schema::hasTable('announcements')) {
                \Log::error('Announcements table does not exist');
                $announcements = collect();
                return response()
                    ->view('Principal.announcements.index', compact('announcements'))
                    ->with('error', 'Announcements table not found. Please run migrations.')
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
            }

            // Get fresh announcements from database with no caching
            $announcements = Announcement::latest()->get();

            // Log for debugging
            \Log::info('Announcements loaded successfully', [
                'count' => $announcements->count(),
                'ids' => $announcements->pluck('id')->toArray(),
                'table_exists' => true
            ]);

            return response()
                ->view('Principal.announcements.index', compact('announcements'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');

        } catch (\Exception $e) {
            \Log::error('Error loading announcements: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Return empty collection if there's an error
            $announcements = collect();
            return response()
                ->view('Principal.announcements.index', compact('announcements'))
                ->with('error', 'Error loading announcements: ' . $e->getMessage())
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

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
        try {
            // Check if announcements table exists
            if (!\Schema::hasTable('announcements')) {
                \Log::error('Cannot create announcement: announcements table does not exist');
                return redirect()->back()
                    ->with('error', 'Announcements table not found. Please run migrations.')
                    ->withInput();
            }

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

            $announcement = Announcement::create($validated);

            // Clear dashboard cache to ensure new announcement appears immediately
            cache()->forget('dashboard_announcements');
            cache()->forget('announcements_list');

            // Clear all caches to ensure immediate visibility
            try {
                \Artisan::call('cache:clear');
            } catch (\Exception $e) {
                \Log::warning('Cache clearing failed after announcement creation: ' . $e->getMessage());
            }

            \Log::info('Announcement created successfully', [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'status' => $announcement->status,
                'created_at' => $announcement->created_at,
                'cache_cleared' => true
            ]);

            $message = $validated['status'] === 'draft' ? 'Announcement saved as draft.' : 'Announcement published successfully.';

            // Add a parameter to indicate we should refresh the dashboard
            return redirect()->route('principal.announcements.index')
                ->with('success', $message)
                ->with('announcement_created', true);

        } catch (\Exception $e) {
            \Log::error('Error creating announcement: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating announcement: ' . $e->getMessage())
                ->withInput();
        }
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
     * Toggle the status of an announcement between active and draft.
     */
    public function toggleStatus(Announcement $announcement)
    {
        try {
            // Toggle the status
            $newStatus = $announcement->status === 'active' ? 'draft' : 'active';

            // Update the announcement
            $announcement->update([
                'status' => $newStatus,
                'is_published' => $newStatus === 'active',
                'published_at' => $newStatus === 'active' ? now() : null,
            ]);

            $message = $newStatus === 'active'
                ? 'Announcement published successfully.'
                : 'Announcement moved to draft.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $newStatus,
                'badge_class' => $newStatus === 'active' ? 'success' : 'warning',
                'badge_text' => ucfirst($newStatus)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling announcement status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating announcement status. Please try again.'
            ], 500);
        }
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
            
            // Log the deletion attempt
            \Log::info('Attempting to delete announcement', [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'user_id' => auth()->guard('principal')->id(),
                'request_method' => request()->method(),
                'is_ajax' => request()->ajax()
            ]);

            // Begin transaction
            DB::beginTransaction();

            // Get announcement details before deletion
            $announcementId = $announcement->id;
            $announcementTitle = $announcement->title;

            // Force delete the announcement (permanently removes from database)
            $deleted = $announcement->forceDelete();

            if (!$deleted) {
                throw new \Exception('Failed to force delete announcement - forceDelete() returned false');

            }

            // Clear specific announcement cache (with error handling)
            try {
                cache()->tags(['announcements'])->forget('announcement_' . $announcementId);
            } catch (\Exception $e) {
                // Cache tags might not be supported, try without tags
                cache()->forget('announcement_' . $announcementId);
                \Log::debug('Cache tags not supported, using simple cache: ' . $e->getMessage());
            }

            cache()->forget('announcements_list');
            cache()->flush(); // Clear all cache to be safe

            // Clear view cache for announcements
            try {
                Artisan::call('view:clear');
                Artisan::call('cache:clear');
            } catch (\Exception $e) {
                \Log::warning('Cache clearing failed: ' . $e->getMessage());
            }

            // Verify the announcement is completely gone
            $verifyGone = Announcement::withTrashed()->find($announcementId);
            if ($verifyGone) {
                throw new \Exception('Announcement still exists after force delete');
            }

            // Double check with raw query
            $rawVerify = DB::table('announcements')->where('id', $announcementId)->first();
            if ($rawVerify) {
                throw new \Exception('Announcement still exists in database after force delete');
            }

            // Commit transaction
            DB::commit();

            // Log successful deletion
            \Log::info('Announcement deleted successfully', [
                'id' => $announcementId,
                'title' => $announcementTitle,
                'method' => 'forceDelete',
                'verification_passed' => true
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Announcement deleted successfully',
                    'announcement_id' => $announcementId,
                    'announcement_title' => $announcementTitle
                ]);
            }

            return redirect()->route('principal.announcements.index')
                ->with('success', 'Announcement deleted successfully');

        } catch (\Exception $e) {
            // Roll back transaction on error
            DB::rollBack();

            \Log::error('Error deleting announcement', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'announcement_id' => $announcement->id ?? 'unknown',
                'user_id' => auth()->guard('principal')->id(),
                'request_method' => request()->method(),
                'is_ajax' => request()->ajax()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting announcement: ' . $e->getMessage(),
                    'error_details' => [
                        'file' => basename($e->getFile()),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }

            return redirect()->route('principal.announcements.index')
                ->with('error', 'Error deleting announcement: ' . $e->getMessage());
        }
    }

    /**
     * Verify announcement deletion by checking if it exists in database
     */
    public function verifyDeletion($id)
    {
        // Check in multiple ways to be absolutely sure
        $eloquentCheck = Announcement::find($id); // This excludes soft-deleted by default
        $eloquentWithTrashedCheck = Announcement::withTrashed()->find($id); // This includes soft-deleted
        $rawCheck = DB::table('announcements')->where('id', $id)->whereNull('deleted_at')->first();
        $rawWithDeletedCheck = DB::table('announcements')->where('id', $id)->first();

        // Also check the old table name if it exists
        $oldTableCheck = null;
        try {
            $oldTableCheck = DB::table('announcement')->where('id', $id)->first();
        } catch (\Exception $e) {
            // Table doesn't exist, which is fine
        }

        // An announcement is considered "deleted" if:
        // 1. It doesn't exist in normal queries (eloquentCheck is null)
        // 2. It doesn't exist in raw queries without deleted_at (rawCheck is null)
        // 3. If it exists in withTrashed or raw with deleted_at, it means it's soft deleted
        $isActivelyExists = $eloquentCheck !== null || $rawCheck !== null || $oldTableCheck !== null;
        $isSoftDeleted = $eloquentWithTrashedCheck !== null && $eloquentCheck === null;
        $isCompletelyDeleted = $eloquentWithTrashedCheck === null && $rawWithDeletedCheck === null;

        return response()->json([
            'exists' => $isActivelyExists,
            'is_soft_deleted' => $isSoftDeleted,
            'is_completely_deleted' => $isCompletelyDeleted,
            'eloquent_check' => $eloquentCheck !== null,
            'eloquent_with_trashed_check' => $eloquentWithTrashedCheck !== null,
            'raw_check' => $rawCheck !== null,
            'raw_with_deleted_check' => $rawWithDeletedCheck !== null,
            'old_table_check' => $oldTableCheck !== null,
            'announcement' => $eloquentCheck ? [
                'id' => $eloquentCheck->id,
                'title' => $eloquentCheck->title,
                'status' => $eloquentCheck->status
            ] : ($eloquentWithTrashedCheck ? [
                'id' => $eloquentWithTrashedCheck->id,
                'title' => $eloquentWithTrashedCheck->title,
                'status' => $eloquentWithTrashedCheck->status,
                'deleted_at' => $eloquentWithTrashedCheck->deleted_at
            ] : null),
            'message' => $isActivelyExists ? 'Announcement still exists and is visible' :
                        ($isSoftDeleted ? 'Announcement is soft deleted (hidden but recoverable)' :
                        'Announcement is completely deleted from database')
        ]);
    }

    /**
     * Force clear all caches and refresh data
     */
    public function forceClearCache()
    {
        try {
            // Clear all possible caches
            cache()->flush();
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            \Log::info('All caches cleared manually by principal');

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error clearing caches: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error clearing caches: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up any soft-deleted announcements (force delete them permanently)
     */
    public function cleanupSoftDeleted()
    {
        try {
            // Get all soft-deleted announcements
            $softDeleted = Announcement::onlyTrashed()->get();
            $count = $softDeleted->count();

            if ($count > 0) {
                // Force delete all soft-deleted announcements
                foreach ($softDeleted as $announcement) {
                    $announcement->forceDelete();
                }

                // Clear caches
                cache()->flush();

                \Log::info("Cleaned up {$count} soft-deleted announcements");

                return response()->json([
                    'success' => true,
                    'message' => "Successfully cleaned up {$count} soft-deleted announcements",
                    'count' => $count
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'No soft-deleted announcements to clean up',
                    'count' => 0
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error cleaning up soft-deleted announcements: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up soft-deleted announcements: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint to verify routes are working
     */
    public function testEndpoint()
    {
        return response()->json([
            'success' => true,
            'message' => 'Test endpoint is working!',
            'timestamp' => now(),
            'user' => auth()->guard('principal')->user()->name ?? 'Unknown'
        ]);
    }
}
