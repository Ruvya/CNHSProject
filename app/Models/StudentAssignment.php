<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssignment extends Model
{
    protected $fillable = [
        'student_id',
        'school_year',
        'grading_period',
        'grade_level',
        'track',
        'strand',
        'subjects',
        'assignment_date',
        'status',
        'assigned_by',
        'notes'
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'subjects' => 'array',
    ];

    /**
     * Get the student for this assignment
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }



    /**
     * Get the registrar who made this assignment
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Registrar::class, 'assigned_by');
    }

    /**
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Note: Section-based enrollment tracking removed since this is now academic-based assignment
        // If section functionality is needed in the future, add section_id to fillable and create section relationship
    }

    /**
     * Scope for active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for current school year
     */
    public function scopeCurrentSchoolYear($query, $schoolYear = null)
    {
        $schoolYear = $schoolYear ?? $this->getCurrentSchoolYear();
        return $query->where('school_year', $schoolYear);
    }

    /**
     * Scope for specific grading period
     */
    public function scopeForGradingPeriod($query, $gradingPeriod)
    {
        return $query->where('grading_period', $gradingPeriod);
    }

    /**
     * Check if student has existing assignment for the same term
     */
    public static function hasExistingAssignment($studentId, $schoolYear, $gradingPeriod)
    {
        return self::where('student_id', $studentId)
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear(): string
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
}
