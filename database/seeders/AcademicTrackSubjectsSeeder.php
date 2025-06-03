<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Carbon\Carbon;

class AcademicTrackSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 💼 ABM (Accountancy, Business, and Management) Specialized Subjects
        $abmSubjects = [
            [
                'name' => 'Applied Economics',
                'code' => 'APP-ECON-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Economic principles and their real-world applications.'
            ],
            [
                'name' => 'Business Ethics and Social Responsibility',
                'code' => 'BUS-ETH-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Ethical decision-making in business contexts.'
            ],
            [
                'name' => 'Fundamentals of Accountancy, Business and Management 1',
                'code' => 'FABM-1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Basic accounting principles and business fundamentals.'
            ],
            [
                'name' => 'Fundamentals of Accountancy, Business and Management 2',
                'code' => 'FABM-2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Advanced accounting and business management concepts.'
            ],
            [
                'name' => 'Business Math',
                'code' => 'BUS-MATH-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Mathematical applications in business and finance.'
            ],
            [
                'name' => 'Business Finance',
                'code' => 'BUS-FIN-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Financial management and investment principles.'
            ],
            [
                'name' => 'Principles of Marketing',
                'code' => 'PRIN-MKT-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Marketing strategies and consumer behavior analysis.'
            ],
            [
                'name' => 'Organization and Management',
                'code' => 'ORG-MGT-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'ABM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Organizational behavior and management principles.'
            ],
        ];

        // Insert ABM subjects
        foreach ($abmSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 🧠 HUMSS (Humanities and Social Sciences) Specialized Subjects
        $humssSubjects = [
            [
                'name' => 'Creative Writing',
                'code' => 'CW-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Development of creative writing skills across genres.'
            ],
            [
                'name' => 'Malikhaing Pagsulat',
                'code' => 'MP-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Creative writing in Filipino language and literature.'
            ],
            [
                'name' => 'Introduction to World Religions and Belief Systems',
                'code' => 'WRBS-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Comparative study of world religions and belief systems.'
            ],
            [
                'name' => 'Philippine Politics and Governance',
                'code' => 'PPG-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Analysis of Philippine political system and governance.'
            ],
            [
                'name' => 'Community Engagement, Solidarity, and Citizenship',
                'code' => 'CESC-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Civic engagement and community service learning.'
            ],
            [
                'name' => 'Disciplines and Ideas in the Social Sciences',
                'code' => 'DISS-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Overview of social science disciplines and methodologies.'
            ],
            [
                'name' => 'Disciplines and Ideas in the Applied Social Sciences',
                'code' => 'DIASS-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Applied social sciences and their practical applications.'
            ],
        ];

        // Insert HUMSS subjects
        foreach ($humssSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 🧭 GAS (General Academic Strand) Specialized Subjects
        $gasSubjects = [
            [
                'name' => 'Humanities Elective - Creative Writing',
                'code' => 'HUM-CW-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'GAS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Creative writing elective for GAS students.'
            ],
            [
                'name' => 'Social Science Elective - Philippine Politics',
                'code' => 'SS-PPG-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'GAS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Philippine politics elective for GAS students.'
            ],
            [
                'name' => 'Business Elective - Principles of Marketing',
                'code' => 'BUS-MKT-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'GAS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Marketing principles elective for GAS students.'
            ],
            [
                'name' => 'STEM Elective - Applied Economics',
                'code' => 'STEM-ECON-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'GAS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Economics elective for GAS students.'
            ],
            [
                'name' => 'STEM Elective - Pre-Calculus',
                'code' => 'STEM-PRECAL-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'GAS',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Pre-calculus elective for GAS students.'
            ],
        ];

        // Insert GAS subjects
        foreach ($gasSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('ABM, HUMSS, and GAS subjects created successfully!');
    }
}
