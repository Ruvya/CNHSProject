<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'quarter1',
        'quarter2',
        'quarter3',
        'quarter4',
        'final_grade',
        'remarks',
        'school_year',
        'semester',
        'status'
    ];

    protected $casts = [
        'quarter1' => 'decimal:2',
        'quarter2' => 'decimal:2',
        'quarter3' => 'decimal:2',
        'quarter4' => 'decimal:2',
        'final_grade' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }


    /**
     * Calculate final grade from quarters
     */
    public function calculateFinalGrade()
    {
        $quarters = array_filter([
            $this->quarter1,
            $this->quarter2,
            $this->quarter3,
            $this->quarter4
        ]);

        if (empty($quarters)) {
            return null;
        }

        return round(array_sum($quarters) / count($quarters), 2);
    }

    /**
     * Get grade status (Passed/Failed/Incomplete)
     */
    public function getGradeStatusAttribute()
    {
        if ($this->final_grade === null) {
            return 'Incomplete';
        }

        return $this->final_grade >= 75 ? 'Passed' : 'Failed';
    }

    /**
     * Get status color class
     */
    public function getStatusColorAttribute()
    {
        switch ($this->grade_status) {
            case 'Passed':
                return 'success';
            case 'Failed':
                return 'danger';
            default:
                return 'warning';
        }
    }


    /**
     * Scope to get grades for a specific teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->whereHas('subject', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        });
    }

    /**
     * Scope to get grades for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Check if First Semester (Q1 and Q2) has any grades
     */
    public function hasFirstSemesterGrades()
    {
        return !is_null($this->quarter1) || !is_null($this->quarter2);
    }

    /**
     * Check if First Semester (Q1 and Q2) is complete (both quarters have grades)
     */
    public function isFirstSemesterComplete()
    {
        return !is_null($this->quarter1) && !is_null($this->quarter2);
    }

    /**
     * Check if Second Semester inputs should be locked
     * Locks when there are NO inputs in First Semester
     */
    public function shouldLockSecondSemester()
    {
        return !$this->hasFirstSemesterGrades();
    }

    /**
     * Static method to check if second semester should be locked for a student/subject
     */
    public static function shouldLockSecondSemesterFor($studentId, $subjectId)
    {
        $grade = static::where('student_id', $studentId)
                      ->where('subject_id', $subjectId)
                      ->first();
        
        // If no grade record exists, lock second semester
        if (!$grade) {
            return true;
        }
        
        return $grade->shouldLockSecondSemester();
    }

}