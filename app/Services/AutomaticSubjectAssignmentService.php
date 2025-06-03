<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutomaticSubjectAssignmentService
{
    /**
     * Automatically assign subjects to a student based on their track and strand
     */
    public function assignSubjectsToStudent(Student $student, $schoolYear = null)
    {
        if (!$student->track || !$student->strand || !$student->grade_level) {
            Log::info("Student {$student->id} missing required fields for automatic assignment", [
                'track' => $student->track,
                'strand' => $student->strand,
                'grade_level' => $student->grade_level
            ]);
            return false;
        }

        $schoolYear = $schoolYear ?? $this->getCurrentSchoolYear();

        try {
            DB::transaction(function () use ($student, $schoolYear) {
                // Get subjects that should be assigned to this student
                $subjectsToAssign = $this->getSubjectsForStudent($student);

                if ($subjectsToAssign->isEmpty()) {
                    Log::warning("No subjects found for student {$student->id} with track {$student->track} and strand {$student->strand}");
                    return;
                }

                // Prepare pivot data for assignment
                $pivotData = [];
                foreach ($subjectsToAssign as $subject) {
                    $pivotData[$subject->id] = [
                        'school_year' => $schoolYear,
                        'remarks' => 'Automatically assigned based on track and strand',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Sync subjects (this will replace existing assignments)
                $student->subjects()->sync($pivotData);

                Log::info("Successfully assigned {$subjectsToAssign->count()} subjects to student {$student->id}");
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to assign subjects to student {$student->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get subjects that should be assigned to a student based on curriculum rules
     */
    public function getSubjectsForStudent(Student $student)
    {
        $query = Subject::where('grade_level', $student->grade_level);

        // Core subjects (required for all students in the grade level)
        $coreSubjects = (clone $query)->where('is_core_subject', true)->get();

        // Track-specific subjects
        $trackSubjects = (clone $query)
            ->where('track', $student->track)
            ->where('is_core_subject', false)
            ->get();

        // Strand-specific subjects
        $strandSubjects = (clone $query)
            ->where('track', $student->track)
            ->where('strand', $student->strand)
            ->where('is_core_subject', false)
            ->get();

        // Combine all subjects and remove duplicates
        $allSubjects = $coreSubjects
            ->merge($trackSubjects)
            ->merge($strandSubjects)
            ->unique('id');

        return $allSubjects;
    }

    /**
     * Get preview of subjects that would be assigned to a student
     */
    public function previewSubjectsForStudent(Student $student)
    {
        $subjects = $this->getSubjectsForStudent($student);

        // Core subjects (required for all students)
        $coreSubjects = $subjects->where('is_core_subject', true);

        // Applied subjects (track-specific but not strand-specific)
        $appliedSubjects = $subjects->where('track', $student->track)
            ->where('is_core_subject', false)
            ->where(function($subject) use ($student) {
                return empty($subject->strand) || $subject->strand === null;
            });

        // Specialized subjects (strand-specific)
        $specializedSubjects = $subjects->where('strand', $student->strand)
            ->where('is_core_subject', false)
            ->where('strand', '!=', null);

        return [
            'core_subjects' => $coreSubjects,
            'applied_subjects' => $appliedSubjects,
            'specialized_subjects' => $specializedSubjects,
            'track_subjects' => $subjects->where('track', $student->track)->where('is_core_subject', false)->where('strand', '!=', $student->strand),
            'strand_subjects' => $subjects->where('strand', $student->strand)->where('is_core_subject', false),
            'total_count' => $subjects->count()
        ];
    }

    /**
     * Bulk assign subjects to multiple students
     */
    public function bulkAssignSubjects($students, $schoolYear = null)
    {
        $schoolYear = $schoolYear ?? $this->getCurrentSchoolYear();
        $successCount = 0;
        $failureCount = 0;

        foreach ($students as $student) {
            if ($this->assignSubjectsToStudent($student, $schoolYear)) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'total_processed' => count($students)
        ];
    }

    /**
     * Re-assign subjects to a student (useful when track/strand changes)
     */
    public function reassignSubjectsToStudent(Student $student, $schoolYear = null)
    {
        // First, remove existing assignments for this school year
        $schoolYear = $schoolYear ?? $this->getCurrentSchoolYear();

        $student->subjects()->wherePivot('school_year', $schoolYear)->detach();

        // Then assign new subjects
        return $this->assignSubjectsToStudent($student, $schoolYear);
    }

    /**
     * Check if a student needs subject reassignment
     */
    public function needsReassignment(Student $student)
    {
        $currentSubjects = $student->subjects;
        $expectedSubjects = $this->getSubjectsForStudent($student);

        // Check if current subjects match expected subjects
        $currentSubjectIds = $currentSubjects->pluck('id')->sort()->values()->toArray();
        $expectedSubjectIds = $expectedSubjects->pluck('id')->sort()->values()->toArray();

        // Compare arrays instead of using equals() method
        return $currentSubjectIds !== $expectedSubjectIds;
    }

    /**
     * Get curriculum statistics
     */
    public function getCurriculumStatistics()
    {
        $stats = [];

        // Get all tracks and strands
        $tracks = Subject::distinct()->pluck('track')->filter();

        foreach ($tracks as $track) {
            $strands = Subject::where('track', $track)->distinct()->pluck('strand')->filter();

            $stats[$track] = [];
            foreach ($strands as $strand) {
                $subjectCount = Subject::where('track', $track)
                    ->where('strand', $strand)
                    ->count();

                $stats[$track][$strand] = $subjectCount;
            }
        }

        return $stats;
    }

    /**
     * Get students who need subject assignment
     */
    public function getStudentsNeedingAssignment()
    {
        return Student::whereNotNull('track')
            ->whereNotNull('strand')
            ->whereNotNull('grade_level')
            ->whereDoesntHave('subjects')
            ->get();
    }

    /**
     * Get students who need subject reassignment
     */
    public function getStudentsNeedingReassignment()
    {
        $studentsNeedingReassignment = collect();

        $students = Student::whereNotNull('track')
            ->whereNotNull('strand')
            ->whereNotNull('grade_level')
            ->whereHas('subjects')
            ->with('subjects')
            ->get();

        foreach ($students as $student) {
            if ($this->needsReassignment($student)) {
                $studentsNeedingReassignment->push($student);
            }
        }

        return $studentsNeedingReassignment;
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        // School year starts in June (month 6)
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * Validate student data for automatic assignment
     */
    public function validateStudentForAssignment(Student $student)
    {
        $errors = [];

        if (!$student->track) {
            $errors[] = 'Track is required for automatic subject assignment';
        }

        if (!$student->strand) {
            $errors[] = 'Strand is required for automatic subject assignment';
        }

        if (!$student->grade_level) {
            $errors[] = 'Grade level is required for automatic subject assignment';
        }

        // Check if subjects exist for this combination
        if ($student->track && $student->strand && $student->grade_level) {
            $availableSubjects = $this->getSubjectsForStudent($student);
            if ($availableSubjects->isEmpty()) {
                $errors[] = "No subjects available for {$student->track} - {$student->strand} in {$student->grade_level}";
            }
        }

        return $errors;
    }
}
