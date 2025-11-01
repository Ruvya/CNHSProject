<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\AnnouncementRead;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.student', function ($view) {
            $unreadCount = 0;
            $latest = collect();

            try {
                $student = Auth::guard('student')->user();
                if ($student) {
                    $baseQuery = Announcement::with('author')
                        ->where('status', 'active')
                        ->where('is_published', true)
                        ->orderBy('created_at', 'desc');

                    $latest = (clone $baseQuery)->take(10)->get();

                    $unreadCount = (clone $baseQuery)
                        ->whereNotIn('id', function ($q) use ($student) {
                            $q->select('announcement_id')
                                ->from('announcement_reads')
                                ->where('student_id', $student->id);
                        })
                        ->count();
                }
            } catch (\Throwable $e) {
                // Silent fallback; JS fetch will handle later
            }

            $view->with('studentNotificationUnreadCount', $unreadCount);
            $view->with('studentNotificationLatest', $latest);
        });
    }
}
