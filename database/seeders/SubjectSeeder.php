<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default subjects creation disabled - registrars will create subjects as needed
        // This ensures only registrar-created subjects exist in the system

        // Note: If you need to re-enable default subjects, uncomment the code below
        // and run: php artisan db:seed --class=SubjectSeeder

        /*
        $subjects = [
            // Grade 11 Subjects
            [
                'name' => 'Oral Communication',
                'code' => 'ENG11-1',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course introduces students to the nature of communication and the elements of the communication process.'
            ],
            // ... other subjects commented out for brevity
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
        */
    }
}
