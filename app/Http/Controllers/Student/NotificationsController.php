<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
	public function unreadCount()
	{
		$student = Auth::guard('student')->user();
		if (!$student) {
            return response()->json(['count' => 0])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
		}

        try {
            if (\Schema::hasTable('announcement_reads')) {
                $count = Announcement::where('status', 'active')
                    ->where('is_published', true)
                    ->whereNotIn('id', function ($q) use ($student) {
                        $q->select('announcement_id')
                            ->from('announcement_reads')
                            ->where('student_id', $student->id);
                    })
                    ->count();
            } else {
                $count = Announcement::where('status', 'active')
                    ->where('is_published', true)
                    ->count();
            }
        } catch (\Throwable $e) {
            // Fallback: if the reads table is missing or any error occurs,
            // just show total published announcements so the badge is not blank
            try {
                $count = Announcement::where('status', 'active')
                    ->where('is_published', true)
                    ->count();
            } catch (\Throwable $e2) {
                $count = 0;
            }
        }

        return response()->json(['count' => $count])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
	}

	public function latest()
	{
		$student = Auth::guard('student')->user();
        if (!$student) {
            return response()->json(['announcements' => []])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        }

		$announcements = Announcement::with('author')
			->where('status', 'active')
			->where('is_published', true)
			->orderBy('created_at', 'desc')
			->take(10)
			->get()
			->map(function ($a) use ($student) {
				$read = AnnouncementRead::where('announcement_id', $a->id)
					->where('student_id', $student->id)
					->exists();
				return [
					'id' => $a->id,
					'title' => $a->title,
					'created_at' => $a->created_at->diffForHumans(),
					'author' => optional($a->author)->name ?? 'Teacher',
					'read' => $read,
				];
			});

        return response()->json(['announcements' => $announcements])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
	}

	public function markRead(Announcement $announcement)
	{
		$student = Auth::guard('student')->user();
		if (!$student) {
			return response()->json(['ok' => false], 401);
		}

		AnnouncementRead::firstOrCreate(
			['announcement_id' => $announcement->id, 'student_id' => $student->id],
			['read_at' => now()]
		);

		return response()->json(['ok' => true]);
	}
}


