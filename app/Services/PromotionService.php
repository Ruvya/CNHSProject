<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentYearlyRecord;
use Illuminate\Support\Facades\DB;

class PromotionService
{
    /**
     * Promote or retain students based on general average for the given school year.
     * Returns a summary array with counts.
     */
    public function promoteForSchoolYear(string $fromSchoolYear, ?string $toSchoolYear = null, float $passingGrade = 75.0): array
    {
        $toSchoolYear = $toSchoolYear ?: $this->nextSchoolYear($fromSchoolYear);

        $promoted = 0;
        $retained = 0;
        $graduated = 0;

        // Verify school years exist
        $fromExists = SchoolYear::where('name', $fromSchoolYear)->exists();
        if (!$fromExists) {
            return [
                'promoted' => 0,
                'retained' => 0,
                'graduated' => 0,
                'message' => "Source school year {$fromSchoolYear} not found."
            ];
        }

        // Do not create the destination SchoolYear here. The controller will create/activate it
        // and also ensures naming format consistency across records.

        DB::transaction(function () use (
            $fromSchoolYear,
            $toSchoolYear,
            $passingGrade,
            &$promoted,
            &$retained,
            &$graduated
        ) {
            // Determine original cohorts from the FROM school year to avoid double-processing
            $grade11Ids = StudentYearlyRecord::where('school_year', $fromSchoolYear)
                ->where('grade_level', 'Grade 11')
                ->pluck('student_id');

            $grade12Ids = StudentYearlyRecord::where('school_year', $fromSchoolYear)
                ->where('grade_level', 'Grade 12')
                ->pluck('student_id');

            // Process Grade 12 FIRST (original cohort only)
            Student::query()
                ->whereIn('id', $grade12Ids)
                ->orderBy('id')
                ->chunk(500, function ($students) use ($fromSchoolYear, $toSchoolYear, $passingGrade, &$graduated, &$retained) {
                    foreach ($students as $student) {
                        $avg = $this->computeGeneralAverage($student->id, $fromSchoolYear);
                        $passed = !is_null($avg) && $avg >= $passingGrade;

                        if ($passed) {
                            // Mark graduated in the new school year record
                            StudentYearlyRecord::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'school_year' => $toSchoolYear,
                                ],
                                [
                                    'grade_level' => 'Grade 12',
                                    'section' => $student->section,
                                    'status' => 'graduated',
                                ]
                            );
                            StudentYearlyRecord::where('student_id', $student->id)
                                ->where('school_year', $fromSchoolYear)
                                ->update(['status' => 'graduated']);
                            $graduated++;
                        } else {
                            // Retain in Grade 12 if failed
                            StudentYearlyRecord::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'school_year' => $toSchoolYear,
                                ],
                                [
                                    'grade_level' => 'Grade 12',
                                    'section' => $student->section,
                                    'status' => 'retained',
                                ]
                            );
                            $retained++;
                        }
                    }
                });

            // Process Grade 11 (original cohort only)
            Student::query()
                ->whereIn('id', $grade11Ids)
                ->orderBy('id')
                ->chunk(500, function ($students) use ($fromSchoolYear, $toSchoolYear, $passingGrade, &$promoted, &$retained) {
                    foreach ($students as $student) {
                        $avg = $this->computeGeneralAverage($student->id, $fromSchoolYear);

                        $passed = !is_null($avg) && $avg >= $passingGrade;

                        if ($passed) {
                            // Promote to Grade 12
                            $student->grade_level = 'Grade 12';
                            $student->save();

                            // Record in next school year
                            StudentYearlyRecord::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'school_year' => $toSchoolYear,
                                ],
                                [
                                    'grade_level' => 'Grade 12',
                                    'section' => $student->section,
                                    'status' => 'promoted',
                                ]
                            );
                            // Mark prior year record as promoted
                            StudentYearlyRecord::where('student_id', $student->id)
                                ->where('school_year', $fromSchoolYear)
                                ->update(['status' => 'promoted']);
                            $promoted++;
                        } else {
                            // Retain in Grade 11
                            StudentYearlyRecord::updateOrCreate(
                                [
                                    'student_id' => $student->id,
                                    'school_year' => $toSchoolYear,
                                ],
                                [
                                    'grade_level' => 'Grade 11',
                                    'section' => $student->section,
                                    'status' => 'retained',
                                ]
                            );
                            $retained++;
                        }
                    }
                });
        });

        return [
            'promoted' => $promoted,
            'retained' => $retained,
            'graduated' => $graduated,
            'message' => 'Promotion process completed.'
        ];
    }

    private function computeGeneralAverage(int $studentId, string $schoolYear): ?float
    {
        $query = Grade::where('student_id', $studentId)
            ->where(function ($q) use ($schoolYear) {
                // Prefer exact match; fall back to rows with NULL school_year
                $q->where('school_year', $schoolYear)
                  ->orWhereNull('school_year');
            });

        // Only count submitted grades when status column exists
        if (\Schema::hasColumn('grades', 'status')) {
            $query->where('status', 'submitted');
        }

        $grades = $query->get();

        $finals = $grades->map(function ($g) {
            /** @var \App\Models\Grade $g */
            // Prefer stored final_grade; compute from quarters if missing
            return $g->final_grade ?? $g->calculateFinalGrade();
        })->filter(function ($v) { return !is_null($v); });

        // Fallback: use any recent submitted grades regardless of school_year
        if ($finals->isEmpty()) {
            $fallback = Grade::where('student_id', $studentId)
                ->when(\Schema::hasColumn('grades', 'status'), function ($q) {
                    $q->where('status', 'submitted');
                })
                ->orderByDesc('updated_at')
                ->limit(50)
                ->get();

            $finals = $fallback->map(function ($g) {
                return $g->final_grade ?? $g->calculateFinalGrade();
            })->filter(function ($v) { return !is_null($v); });
        }

        if ($finals->isEmpty()) {
            return null;
        }

        return round($finals->avg(), 2);
    }

    public function nextSchoolYear(string $schoolYear): string
    {
        // Expect format YYYY-YYYY
        $parts = explode('-', $schoolYear);
        if (count($parts) !== 2) {
            $y = (int)date('Y');
            return $y . '-' . ($y + 1);
        }
        $start = (int)$parts[0];
        $end = (int)$parts[1];
        return ($start + 1) . '-' . ($end + 1);
    }
}


