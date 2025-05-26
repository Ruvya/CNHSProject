<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjectOffering extends Model
{
    protected $fillable = [
        'subject_id',
        'registrar_id',
        'teacher_id',
        'school_year',
        'grading',
        'grade_level',
        'track',
        'max_students',
        'enrolled_students',
        'status',
        'notes'
    ];

    protected $casts = [
        'max_students' => 'integer',
        'enrolled_students' => 'integer',
    ];

    /**
     * Get the subject that this offering belongs to
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the registrar who created this offering
     */
    public function registrar(): BelongsTo
    {
        return $this->belongsTo(Registrar::class);
    }

    /**
     * Get the teacher assigned to this offering
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the schedules for this offering
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(SubjectSchedule::class);
    }

    /**
     * Get the students enrolled in this offering
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_subject_offering')
            ->withPivot('enrollment_date', 'status')
            ->withTimestamps();
    }

    /**
     * Check if the offering is full
     */
    public function isFull(): bool
    {
        return $this->enrolled_students >= $this->max_students;
    }

    /**
     * Get available slots
     */
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->max_students - $this->enrolled_students);
    }

    /**
     * Get formatted schedule string
     */
    public function getFormattedScheduleAttribute(): string
    {
        $schedules = $this->schedules->map(function ($schedule) {
            return $schedule->day_of_week . ' ' .
                   date('g:i A', strtotime($schedule->start_time)) . '-' .
                   date('g:i A', strtotime($schedule->end_time));
        });

        return $schedules->join(', ');
    }

    /**
     * Scope for active offerings
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
