<?php

namespace App\Services;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentYearlyRecord;
use App\Models\TeacherYearlyRecord;

class YearlyRecordService
{
    /**
     * Ensure StudentYearlyRecord and TeacherYearlyRecord exist for the given school year.
     */
    public function ensureForSchoolYear(string $schoolYearName): array
    {
        $createdStudents = 0;
        $createdTeachers = 0;

        // Ensure the SchoolYear exists
        $schoolYear = SchoolYear::where('name', $schoolYearName)->first();
        if (!$schoolYear) {
            return ['students_created' => 0, 'teachers_created' => 0];
        }

        // Create missing student yearly records
        Student::query()->select('id', 'grade_level', 'section')
            ->orderBy('id')
            ->chunk(500, function ($students) use (&$createdStudents, $schoolYearName) {
                foreach ($students as $student) {
                    $exists = StudentYearlyRecord::where('student_id', $student->id)
                        ->where('school_year', $schoolYearName)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    StudentYearlyRecord::create([
                        'student_id' => $student->id,
                        'school_year' => $schoolYearName,
                        'grade_level' => $student->grade_level ?? 'Grade 11',
                        'section' => $student->section,
                        'status' => 'enrolled',
                    ]);
                    $createdStudents++;
                }
            });

        // Create missing teacher yearly records (only for active teachers if status column exists)
        $teacherQuery = Teacher::query()->select('id');
        if (\Schema::hasColumn('teachers', 'status')) {
            $teacherQuery->where('status', 'active');
        }

        $teacherQuery->orderBy('id')
            ->chunk(500, function ($teachers) use (&$createdTeachers, $schoolYearName) {
                foreach ($teachers as $teacher) {
                    $exists = TeacherYearlyRecord::where('teacher_id', $teacher->id)
                        ->where('school_year', $schoolYearName)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    TeacherYearlyRecord::create([
                        'teacher_id' => $teacher->id,
                        'school_year' => $schoolYearName,
                        'department' => null,
                        'position' => 'Teacher',
                        'employment_status' => 'regular',
                        'status' => 'active',
                    ]);
                    $createdTeachers++;
                }
            });

        return [
            'students_created' => $createdStudents,
            'teachers_created' => $createdTeachers,
        ];
    }
}


