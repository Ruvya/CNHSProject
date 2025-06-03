<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Carbon\Carbon;

class SeniorHighSchoolSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing subjects to avoid duplicates (use delete instead of truncate due to foreign keys)
        Subject::query()->delete();

        $now = Carbon::now();

        // 📘 1. CORE SUBJECTS (Taken by all SHS students)
        $coreSubjects = [
            // Grade 11 Core Subjects
            [
                'name' => 'Oral Communication',
                'code' => 'ORAL-COMM-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Language and Literature',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Develops students\' oral communication skills through various speaking activities and presentations.'
            ],
            [
                'name' => 'Reading and Writing',
                'code' => 'READ-WRITE-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Language and Literature',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Enhances reading comprehension and writing skills across different text types and genres.'
            ],
            [
                'name' => 'Komunikasyon at Pananaliksik',
                'code' => 'KOM-PAN-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Language and Literature',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Develops Filipino communication and research skills.'
            ],
            [
                'name' => 'Pagbasa at Pagsusuri ng Iba\'t Ibang Teksto',
                'code' => 'PAG-SUR-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Language and Literature',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Critical reading and analysis of various Filipino texts.'
            ],
            [
                'name' => '21st Century Literature',
                'code' => '21ST-LIT-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Language and Literature',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Study of contemporary literature from the Philippines and the world.'
            ],
            [
                'name' => 'Contemporary Philippine Arts from the Regions',
                'code' => 'CPAR-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Arts and Humanities',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Appreciation and understanding of Philippine regional arts and culture.'
            ],
            [
                'name' => 'Media and Information Literacy',
                'code' => 'MIL-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Technology and Media',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Critical evaluation and responsible use of media and information.'
            ],
            [
                'name' => 'General Mathematics',
                'code' => 'GEN-MATH-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Mathematics',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Basic mathematical concepts and applications for everyday life.'
            ],
            [
                'name' => 'Statistics and Probability',
                'code' => 'STAT-PROB-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Mathematics',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Introduction to statistical concepts and probability theory.'
            ],
            [
                'name' => 'Earth and Life Science',
                'code' => 'ELS-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Science',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Study of Earth systems and biological processes.'
            ],
            [
                'name' => 'Physical Education and Health 1',
                'code' => 'PE-HEALTH-1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Physical Education and Health',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Physical fitness, health education, and wellness promotion.'
            ],
            [
                'name' => 'Personal Development',
                'code' => 'PER-DEV-11',
                'grade_level' => 'Grade 11',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Personal Development',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Self-awareness, goal setting, and personal growth strategies.'
            ],

            // Grade 12 Core Subjects
            [
                'name' => 'Understanding Culture, Society, and Politics',
                'code' => 'UCSP-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Social Sciences',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Analysis of cultural, social, and political phenomena.'
            ],
            [
                'name' => 'Introduction to the Philosophy of the Human Person',
                'code' => 'PHIL-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Philosophy and Ethics',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Philosophical inquiry into human nature and existence.'
            ],
            [
                'name' => 'Physical Science',
                'code' => 'PHYS-SCI-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Science',
                'grading' => 'All Gradings',
                'is_core_subject' => true,
                'description' => 'Integration of physics and chemistry concepts.'
            ],
            [
                'name' => 'Physical Education and Health 2',
                'code' => 'PE-HEALTH-2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Physical Education and Health',
                'grading' => 'First Grading',
                'is_core_subject' => true,
                'description' => 'Advanced physical fitness and health concepts.'
            ],
            [
                'name' => 'Physical Education and Health 3',
                'code' => 'PE-HEALTH-3-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Physical Education and Health',
                'grading' => 'Second Grading',
                'is_core_subject' => true,
                'description' => 'Sports and recreational activities.'
            ],
            [
                'name' => 'Physical Education and Health 4',
                'code' => 'PE-HEALTH-4-12',
                'grade_level' => 'Grade 12',
                'track' => 'Core Subject',
                'strand' => 'All Strands',
                'cluster' => 'Physical Education and Health',
                'grading' => 'Third Grading',
                'is_core_subject' => true,
                'description' => 'Community health and wellness programs.'
            ],
        ];

        // Insert core subjects
        foreach ($coreSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('Core subjects created successfully!');

        // 📚 2. ACADEMIC TRACK SUBJECTS

        // Applied Subjects (Common to all Academic Track strands)
        $appliedSubjects = [
            [
                'name' => 'English for Academic and Professional Purposes',
                'code' => 'EAPP-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'All Academic Strands',
                'cluster' => 'Applied Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Advanced English skills for academic and professional contexts.'
            ],
            [
                'name' => 'Practical Research 1',
                'code' => 'PR1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'All Academic Strands',
                'cluster' => 'Applied Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Introduction to quantitative research methods and design.'
            ],
            [
                'name' => 'Practical Research 2',
                'code' => 'PR2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'All Academic Strands',
                'cluster' => 'Applied Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Advanced research methods and thesis writing.'
            ],
            [
                'name' => 'Empowerment Technologies',
                'code' => 'EMPTECH-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'All Academic Strands',
                'cluster' => 'Applied Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'ICT skills for productivity and digital citizenship.'
            ],
        ];

        // Insert applied subjects
        foreach ($appliedSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 📐 STEM (Science, Technology, Engineering, Mathematics) Specialized Subjects
        $stemSubjects = [
            [
                'name' => 'Pre-Calculus',
                'code' => 'PRE-CAL-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Advanced mathematical concepts preparing for calculus.'
            ],
            [
                'name' => 'Basic Calculus',
                'code' => 'BASIC-CAL-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Introduction to differential and integral calculus.'
            ],
            [
                'name' => 'General Biology 1',
                'code' => 'GEN-BIO-1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Molecular and cellular biology concepts.'
            ],
            [
                'name' => 'General Biology 2',
                'code' => 'GEN-BIO-2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Genetics, evolution, and ecology.'
            ],
            [
                'name' => 'General Physics 1',
                'code' => 'GEN-PHYS-1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Mechanics, waves, and thermodynamics.'
            ],
            [
                'name' => 'General Physics 2',
                'code' => 'GEN-PHYS-2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Electricity, magnetism, and modern physics.'
            ],
            [
                'name' => 'General Chemistry 1',
                'code' => 'GEN-CHEM-1-11',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Atomic structure, chemical bonding, and stoichiometry.'
            ],
            [
                'name' => 'General Chemistry 2',
                'code' => 'GEN-CHEM-2-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Organic chemistry and biochemistry fundamentals.'
            ],
            [
                'name' => 'Research Projects',
                'code' => 'RES-PROJ-12',
                'grade_level' => 'Grade 12',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'cluster' => 'Specialized Subject',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Independent research project in STEM field.'
            ],
        ];

        // Insert STEM subjects
        foreach ($stemSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('Academic Track subjects (Applied and STEM) created successfully!');

        // Run other subject seeders
        $this->call([
            AcademicTrackSubjectsSeeder::class,
            TVLTrackSubjectsSeeder::class,
        ]);

        $this->command->info('🎉 ALL SENIOR HIGH SCHOOL SUBJECTS CREATED SUCCESSFULLY!');
        $this->command->info('📊 Total subjects created: ' . Subject::count());
    }
}
