<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Carbon\Carbon;

class TVLTrackSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 🛠️ TECHNICAL-VOCATIONAL-LIVELIHOOD (TVL) TRACK SUBJECTS
        
        // 🍳 TVL - Home Economics (HE) Subjects
        $homeEconomicsSubjects = [
            [
                'name' => 'Cookery',
                'code' => 'COOKERY-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Food and Beverage Services',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Basic cooking techniques and food preparation skills.'
            ],
            [
                'name' => 'Bread and Pastry Production',
                'code' => 'BPP-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Food and Beverage Services',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Baking and pastry production techniques.'
            ],
            [
                'name' => 'Food and Beverage Services',
                'code' => 'FBS-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Food and Beverage Services',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Restaurant service and beverage preparation.'
            ],
            [
                'name' => 'Housekeeping',
                'code' => 'HK-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Tourism and Hospitality',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Hotel and accommodation housekeeping services.'
            ],
            [
                'name' => 'Front Office Services',
                'code' => 'FOS-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Tourism and Hospitality',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Hotel front desk operations and guest services.'
            ],
            [
                'name' => 'Tourism Promotion Services',
                'code' => 'TPS-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Home Economics',
                'cluster' => 'Tourism and Hospitality',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Tourism marketing and destination promotion.'
            ],
        ];

        // Insert Home Economics subjects
        foreach ($homeEconomicsSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 🖥️ TVL - Information and Communication Technology (ICT) Subjects
        $ictSubjects = [
            [
                'name' => 'Computer Systems Servicing',
                'code' => 'CSS-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Computer Hardware Servicing',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Computer hardware installation, configuration, and troubleshooting.'
            ],
            [
                'name' => 'Programming (Java)',
                'code' => 'PROG-JAVA-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Computer Programming',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Java programming fundamentals and object-oriented programming.'
            ],
            [
                'name' => 'Programming (C#)',
                'code' => 'PROG-CS-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Computer Programming',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'C# programming and .NET framework development.'
            ],
            [
                'name' => 'Programming (VB.NET)',
                'code' => 'PROG-VB-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Computer Programming',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Visual Basic .NET programming and application development.'
            ],
            [
                'name' => 'Animation',
                'code' => 'ANIM-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Digital Arts and Animation',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => '2D and 3D animation techniques and software.'
            ],
            [
                'name' => 'Web Development',
                'code' => 'WEB-DEV-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'ICT',
                'cluster' => 'Web and Mobile Development',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'HTML, CSS, JavaScript, and web application development.'
            ],
        ];

        // Insert ICT subjects
        foreach ($ictSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 🔧 TVL - Industrial Arts Subjects
        $industrialArtsSubjects = [
            [
                'name' => 'Automotive Servicing',
                'code' => 'AUTO-SERV-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Industrial Arts',
                'cluster' => 'Automotive Technology',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Vehicle maintenance, repair, and diagnostic procedures.'
            ],
            [
                'name' => 'Electrical Installation and Maintenance',
                'code' => 'EIM-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Industrial Arts',
                'cluster' => 'Electrical Technology',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Electrical wiring, installation, and maintenance procedures.'
            ],
            [
                'name' => 'Shielded Metal Arc Welding',
                'code' => 'SMAW-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Industrial Arts',
                'cluster' => 'Welding and Fabrication',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Arc welding techniques and metal fabrication.'
            ],
            [
                'name' => 'Carpentry',
                'code' => 'CARP-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Industrial Arts',
                'cluster' => 'Construction Technology',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Woodworking, furniture making, and construction carpentry.'
            ],
            [
                'name' => 'Plumbing',
                'code' => 'PLUMB-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Industrial Arts',
                'cluster' => 'Construction Technology',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Pipe installation, water systems, and plumbing maintenance.'
            ],
        ];

        // Insert Industrial Arts subjects
        foreach ($industrialArtsSubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 🚜 TVL - Agri-Fishery Arts Subjects
        $agriFisherySubjects = [
            [
                'name' => 'Animal Production',
                'code' => 'ANIM-PROD-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Agri-Fishery Arts',
                'cluster' => 'Animal Production',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Livestock and poultry raising, breeding, and management.'
            ],
            [
                'name' => 'Horticulture',
                'code' => 'HORT-11',
                'grade_level' => 'Grade 11',
                'track' => 'TVL Track',
                'strand' => 'Agri-Fishery Arts',
                'cluster' => 'Crop Production',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Plant cultivation, gardening, and landscape management.'
            ],
            [
                'name' => 'Aquaculture',
                'code' => 'AQUA-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Agri-Fishery Arts',
                'cluster' => 'Fishery Production',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Fish farming, pond management, and aquatic production.'
            ],
            [
                'name' => 'Fish Processing',
                'code' => 'FISH-PROC-12',
                'grade_level' => 'Grade 12',
                'track' => 'TVL Track',
                'strand' => 'Agri-Fishery Arts',
                'cluster' => 'Food Processing',
                'grading' => 'All Gradings',
                'is_core_subject' => false,
                'description' => 'Fish preservation, processing, and value-adding techniques.'
            ],
        ];

        // Insert Agri-Fishery subjects
        foreach ($agriFisherySubjects as $subject) {
            Subject::create(array_merge($subject, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info('TVL Track subjects created successfully!');
    }
}
