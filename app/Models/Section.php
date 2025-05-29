<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'name',
        'grade_level',
        'track',
        'strand',
        'max_capacity',
        'current_enrollment',
        'school_year',
        'grading_period',
        'adviser_id',
        'room',
        'status',
        'notes'
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'current_enrollment' => 'integer',
    ];

    /**
     * Get the adviser (teacher) for this section
     */
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'adviser_id');
    }

    /**
     * Get all student assignments for this section
     */
    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get all teacher assignments for this section
     */
    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * Get students currently assigned to this section
     */
    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            StudentAssignment::class,
            'section_id',
            'id',
            'id',
            'student_id'
        )->where('student_assignments.status', 'active');
    }

    /**
     * Check if section is full
     */
    public function isFull(): bool
    {
        return $this->current_enrollment >= $this->max_capacity;
    }

    /**
     * Get available slots
     */
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->max_capacity - $this->current_enrollment);
    }

    /**
     * Update enrollment count
     */
    public function updateEnrollmentCount(): void
    {
        $this->current_enrollment = $this->studentAssignments()
            ->where('status', 'active')
            ->count();

        // Update status based on capacity
        if ($this->current_enrollment >= $this->max_capacity) {
            $this->status = 'full';
        } elseif ($this->status === 'full' && $this->current_enrollment < $this->max_capacity) {
            $this->status = 'active';
        }

        $this->save();
    }

    /**
     * Scope for active sections
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
     * Scope for specific grade level
     */
    public function scopeForGrade($query, $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    /**
     * Scope for specific track
     */
    public function scopeForTrack($query, $track)
    {
        return $query->where('track', $track);
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
