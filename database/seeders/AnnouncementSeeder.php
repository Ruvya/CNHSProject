<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample announcements
        $announcements = [
            [
                'title' => 'Welcome to the New School Year!',
                'content' => 'We are excited to welcome all students back for another amazing school year. Please check your schedules and prepare for classes. Don\'t forget to bring all required materials and maintain proper school uniform.',
                'status' => 'active',
                'author_type' => 'App\Models\Principal',
                'author_id' => 1,
                'is_published' => true,
                'published_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Important: Class Schedule Changes',
                'content' => 'Due to facility maintenance, some classes will be moved to different rooms. Please check with your advisers for updated room assignments. The changes will be effective starting next week.',
                'status' => 'active',
                'author_type' => 'App\Models\Principal',
                'author_id' => 1,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Upcoming School Events',
                'content' => 'Mark your calendars! We have several exciting events coming up including the Science Fair (March 15), Sports Festival (March 22), and Academic Awards Ceremony (March 30). More details will follow.',
                'status' => 'active',
                'author_type' => 'App\Models\Principal',
                'author_id' => 1,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Library Hours Extended',
                'content' => 'Good news! The school library will now be open until 6:00 PM on weekdays to give students more time for research and study. Weekend hours remain the same.',
                'status' => 'active',
                'author_type' => 'App\Models\Principal',
                'author_id' => 1,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Student Health and Safety Protocols',
                'content' => 'Please remember to follow all health and safety protocols while on campus. This includes proper hygiene, following emergency procedures, and reporting any safety concerns to school staff immediately.',
                'status' => 'active',
                'author_type' => 'App\Models\Principal',
                'author_id' => 1,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
