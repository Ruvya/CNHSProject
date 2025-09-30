<?php

namespace App\Services;

class SemesterService
{
    /**
     * Get current semester based on current date
     */
    public static function getCurrentSemester(): string
    {
        $currentMonth = date('n');

        // 1st Semester: June to December (months 6-12)
        // 2nd Semester: January to May (months 1-5)
        if ($currentMonth >= 6 && $currentMonth <= 12) {
            return '1st Semester';
        } else {
            return '2nd Semester';
        }
    }

    /**
     * Get current school year
     */
    public static function getCurrentSchoolYear(): string
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
     * Get all available semesters
     */
    public static function getAvailableSemesters(): array
    {
        return ['1st Semester', '2nd Semester'];
    }

    /**
     * Get semester options for forms
     */
    public static function getSemesterOptions(): array
    {
        return [
            '' => 'Select Semester',
            '1st Semester' => '1st Semester',
            '2nd Semester' => '2nd Semester',
            'Both Semesters' => 'Both Semesters'
        ];
    }

    /**
     * Check if a date falls within a specific semester
     */
    public static function isDateInSemester(string $date, string $semester): bool
    {
        $month = date('n', strtotime($date));
        
        if ($semester === '1st Semester') {
            return $month >= 6 && $month <= 12;
        } elseif ($semester === '2nd Semester') {
            return $month >= 1 && $month <= 5;
        }
        
        return false;
    }

    /**
     * Get semester start and end dates for a given school year
     */
    public static function getSemesterDates(string $schoolYear, string $semester): array
    {
        $startYear = (int) explode('-', $schoolYear)[0];
        $endYear = (int) explode('-', $schoolYear)[1];

        if ($semester === '1st Semester') {
            return [
                'start' => $startYear . '-06-01',
                'end' => $startYear . '-12-31'
            ];
        } elseif ($semester === '2nd Semester') {
            return [
                'start' => $endYear . '-01-01',
                'end' => $endYear . '-05-31'
            ];
        }

        return [];
    }

    /**
     * Convert old grading period to semester
     */
    public static function convertGradingPeriodToSemester(string $gradingPeriod): string
    {
        switch ($gradingPeriod) {
            case 'First Grading':
            case 'Second Grading':
                return '1st Semester';
            case 'Third Grading':
            case 'Fourth Grading':
                return '2nd Semester';
            default:
                return '1st Semester';
        }
    }

    /**
     * Get quarter mapping for semester
     */
    public static function getQuarterMapping(): array
    {
        return [
            '1st Semester' => [
                'quarter1' => '1st Quarter',
                'quarter2' => '2nd Quarter'
            ],
            '2nd Semester' => [
                'quarter3' => '3rd Quarter',
                'quarter4' => '4th Quarter'
            ]
        ];
    }
}
