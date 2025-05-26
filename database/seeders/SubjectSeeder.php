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
            [
                'name' => 'Reading and Writing Skills',
                'code' => 'ENG11-2',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course develops students\' reading and writing skills through various texts and writing activities.'
            ],
            [
                'name' => 'General Mathematics',
                'code' => 'MATH11-1',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course covers basic mathematical concepts and their applications in real-life situations.'
            ],
            [
                'name' => 'Statistics and Probability',
                'code' => 'MATH11-2',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course introduces students to statistical concepts and probability theory.'
            ],
            [
                'name' => 'Earth and Life Science',
                'code' => 'SCI11-1',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course covers the basic concepts of earth science and life science.'
            ],
            [
                'name' => 'Physical Science',
                'code' => 'SCI11-2',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course introduces students to the fundamental concepts of physics and chemistry.'
            ],
            [
                'name' => 'Introduction to Philosophy',
                'code' => 'PHIL11-1',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course introduces students to philosophical thinking and major philosophical questions.'
            ],

            // Grade 12 Subjects
            [
                'name' => 'Contemporary Philippine Arts',
                'code' => 'ARTS12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course explores contemporary Philippine arts and their cultural significance.'
            ],
            [
                'name' => 'Media and Information Literacy',
                'code' => 'MIL12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course develops students\' ability to access, analyze, and create media content.'
            ],
            [
                'name' => 'Personal Development',
                'code' => 'PD12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course helps students understand themselves and their relationships with others.'
            ],
            [
                'name' => 'Understanding Culture, Society and Politics',
                'code' => 'UCSP12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course examines human cultural variation, social differences, and political identities.'
            ],
            [
                'name' => 'Philippine Politics and Governance',
                'code' => 'PPG12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course analyzes the Philippine political system and governance structures.'
            ],
            [
                'name' => 'Community Engagement, Solidarity and Citizenship',
                'code' => 'CESC12-1',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'description' => 'This course develops students\' civic consciousness and social responsibility.'
            ],

            // STEM Subjects for Grade 11
            [
                'name' => 'Pre-Calculus',
                'code' => 'MATH11-STEM',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course prepares students for calculus by covering advanced algebraic and trigonometric concepts.'
            ],
            [
                'name' => 'General Biology 1',
                'code' => 'BIO11-STEM',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course covers the fundamental concepts of biology including cell structure and function.'
            ],
            [
                'name' => 'General Chemistry 1',
                'code' => 'CHEM11-STEM',
                'grade_level' => 'Grade 11',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course introduces students to the basic principles of chemistry.'
            ],

            // STEM Subjects for Grade 12
            [
                'name' => 'Calculus',
                'code' => 'MATH12-STEM',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course covers differential and integral calculus and their applications.'
            ],
            [
                'name' => 'General Biology 2',
                'code' => 'BIO12-STEM',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course continues the study of biology with focus on genetics and evolution.'
            ],
            [
                'name' => 'General Physics 1',
                'code' => 'PHYS12-STEM',
                'grade_level' => 'Grade 12',
                'units' => 1,
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'description' => 'This course covers mechanics, thermodynamics, and wave motion.'
            ]
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
