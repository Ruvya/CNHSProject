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
        'remarks'
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
    public function getStatusAttribute()
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
        switch ($this->status) {
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
}