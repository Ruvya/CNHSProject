<?php

namespace App\Services;

use App\Models\Subject;

class SubjectPackageService
{
    /**
     * Get subject packages based on grade level, track, and strand
     */
    public function getSubjectPackage($gradeLevel, $track, $strand, $gradingPeriod)
    {
        $coreSubjects = $this->getCoreSubjects($gradeLevel, $gradingPeriod);
        $appliedSubjects = $this->getAppliedSubjects($gradeLevel, $track, $gradingPeriod);
        $specializedSubjects = $this->getSpecializedSubjects($gradeLevel, $track, $strand, $gradingPeriod);

        return [
            'core' => $coreSubjects,
            'applied' => $appliedSubjects,
            'specialized' => $specializedSubjects,
            'all' => $coreSubjects->merge($appliedSubjects)->merge($specializedSubjects)
        ];
    }

    /**
     * Get core subjects (common to all strands)
     */
    private function getCoreSubjects($gradeLevel, $gradingPeriod)
    {
        return Subject::where('grade_level', $gradeLevel)
            ->where('is_core_subject', true)
            ->where(function($query) use ($gradingPeriod) {
                $query->where('grading', $gradingPeriod)
                      ->orWhere('grading', 'All Gradings');
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get applied subjects (track-specific)
     */
    private function getAppliedSubjects($gradeLevel, $track, $gradingPeriod)
    {
        return Subject::where('grade_level', $gradeLevel)
            ->where('track', $track)
            ->where('is_core_subject', false)
            ->whereNull('strand') // Applied subjects don't have specific strands
            ->where(function($query) use ($gradingPeriod) {
                $query->where('grading', $gradingPeriod)
                      ->orWhere('grading', 'All Gradings');
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get specialized subjects (strand-specific)
     */
    private function getSpecializedSubjects($gradeLevel, $track, $strand, $gradingPeriod)
    {
        return Subject::where('grade_level', $gradeLevel)
            ->where('track', $track)
            ->where('strand', $strand)
            ->where('is_core_subject', false)
            ->where(function($query) use ($gradingPeriod) {
                $query->where('grading', $gradingPeriod)
                      ->orWhere('grading', 'All Gradings');
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get default subject package structure for a strand
     */
    public function getDefaultSubjectStructure($strand)
    {
        $structures = [
            'STEM' => [
                'core' => [
                    'Oral Communication',
                    'Reading and Writing',
                    'Komunikasyon at Pananaliksik',
                    'General Mathematics',
                    'Statistics and Probability',
                    'Earth and Life Science',
                    'Physical Science',
                    'Personal Development',
                    'Understanding Culture, Society and Politics',
                    'Introduction to Philosophy',
                    'Physical Education and Health',
                    'Empowerment Technologies'
                ],
                'applied' => [
                    'Practical Research 1',
                    'Practical Research 2'
                ],
                'specialized' => [
                    'Pre-Calculus',
                    'Basic Calculus',
                    'General Biology 1',
                    'General Biology 2',
                    'General Physics 1',
                    'General Physics 2',
                    'General Chemistry 1',
                    'General Chemistry 2'
                ]
            ],
            'ABM' => [
                'core' => [
                    'Oral Communication',
                    'Reading and Writing',
                    'Komunikasyon at Pananaliksik',
                    'General Mathematics',
                    'Statistics and Probability',
                    'Earth and Life Science',
                    'Physical Science',
                    'Personal Development',
                    'Understanding Culture, Society and Politics',
                    'Introduction to Philosophy',
                    'Physical Education and Health',
                    'Empowerment Technologies'
                ],
                'applied' => [
                    'Practical Research 1',
                    'Practical Research 2'
                ],
                'specialized' => [
                    'Fundamentals of Accountancy, Business and Management 1',
                    'Fundamentals of Accountancy, Business and Management 2',
                    'Business Ethics and Social Responsibility',
                    'Applied Economics',
                    'Business Finance',
                    'Business Marketing',
                    'Organization and Management',
                    'Principles of Marketing'
                ]
            ],
            'HUMSS' => [
                'core' => [
                    'Oral Communication',
                    'Reading and Writing',
                    'Komunikasyon at Pananaliksik',
                    'General Mathematics',
                    'Statistics and Probability',
                    'Earth and Life Science',
                    'Physical Science',
                    'Personal Development',
                    'Understanding Culture, Society and Politics',
                    'Introduction to Philosophy',
                    'Physical Education and Health',
                    'Empowerment Technologies'
                ],
                'applied' => [
                    'Practical Research 1',
                    'Practical Research 2'
                ],
                'specialized' => [
                    'Creative Writing',
                    'Creative Nonfiction',
                    'Humanities',
                    'Social Science Research',
                    'Philippine Politics and Governance',
                    'Community Engagement, Solidarity and Citizenship',
                    'Trends, Networks and Critical Thinking',
                    'World Religions and Belief Systems'
                ]
            ],
            'GAS' => [
                'core' => [
                    'Oral Communication',
                    'Reading and Writing',
                    'Komunikasyon at Pananaliksik',
                    'General Mathematics',
                    'Statistics and Probability',
                    'Earth and Life Science',
                    'Physical Science',
                    'Personal Development',
                    'Understanding Culture, Society and Politics',
                    'Introduction to Philosophy',
                    'Physical Education and Health',
                    'Empowerment Technologies'
                ],
                'applied' => [
                    'Practical Research 1',
                    'Practical Research 2'
                ],
                'specialized' => [
                    'Humanities',
                    'Social Science Research',
                    'Applied Economics',
                    'Organization and Management',
                    'Disaster Readiness and Risk Reduction',
                    'Elective Subject 1',
                    'Elective Subject 2',
                    'Elective Subject 3'
                ]
            ]
        ];

        return $structures[$strand] ?? $structures['GAS'];
    }

    /**
     * Create subject assignments for a student
     */
    public function createSubjectAssignments($studentId, $subjects, $schoolYear, $gradingPeriod)
    {
        $assignments = [];

        foreach ($subjects as $subjectId) {
            $assignments[] = [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'school_year' => $schoolYear,
                'quarter' => $gradingPeriod, // Use quarter field instead of grading_period
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert into student_subject pivot table
        \DB::table('student_subject')->insert($assignments);

        return count($assignments);
    }

    /**
     * Update subject assignments for a student
     */
    public function updateSubjectAssignments($studentId, $subjects, $schoolYear, $gradingPeriod)
    {
        // Remove existing assignments for this term
        \DB::table('student_subject')
            ->where('student_id', $studentId)
            ->where('school_year', $schoolYear)
            ->where('quarter', $gradingPeriod) // Use quarter field instead of grading_period
            ->delete();

        // Create new assignments
        return $this->createSubjectAssignments($studentId, $subjects, $schoolYear, $gradingPeriod);
    }
}
