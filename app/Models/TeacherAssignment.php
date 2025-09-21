<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section_id',
        'school_year',
        'grading_period',
        'schedule',
        'assignment_date',
        'status',
        'assigned_by',
        'notes'
    ];

    protected $casts = [
        'schedule' => 'array',
        'assignment_date' => 'date',
    ];

    /**
     * Get the teacher for this assignment
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject for this assignment
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the section for this assignment
     */
     public function section(): BelongsTo
     {
         return $this->belongsTo(Section::class);
     }

    /**
     * Get the registrar who made this assignment
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(Registrar::class, 'assigned_by');
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
     * Check for schedule conflicts with other teacher assignments
     */
    public function hasScheduleConflict($teacherId, $schedule, $excludeId = null)
    {
        if (!$schedule || !is_array($schedule)) {
            return false;
        }

        $query = self::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->where('school_year', $this->school_year)
            ->where('grading_period', $this->grading_period);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingAssignments = $query->get();

        foreach ($existingAssignments as $assignment) {
            if ($this->schedulesConflict($schedule, $assignment->schedule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if two schedules conflict
     */
    private function schedulesConflict($schedule1, $schedule2)
    {
        if (!$schedule1 || !$schedule2) {
            return false;
        }

        foreach ($schedule1 as $slot1) {
            foreach ($schedule2 as $slot2) {
                if ($this->timeSlotsConflict($slot1, $slot2)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if two time slots conflict
     */
    private function timeSlotsConflict($slot1, $slot2)
    {
        // Check if same day
        if ($slot1['day'] !== $slot2['day']) {
            return false;
        }

        $start1 = strtotime($slot1['start_time']);
        $end1 = strtotime($slot1['end_time']);
        $start2 = strtotime($slot2['start_time']);
        $end2 = strtotime($slot2['end_time']);

        // Check for time overlap
        return !($end1 <= $start2 || $start1 >= $end2);
    }

    /**
     * Get formatted schedule string
     */
    public function getFormattedScheduleAttribute(): string
    {
        if (!$this->schedule) {
            return 'No schedule set';
        }

        $scheduleStrings = [];
        foreach ($this->schedule as $slot) {
            $scheduleStrings[] = $slot['day'] . ' ' .
                date('g:i A', strtotime($slot['start_time'])) . '-' .
                date('g:i A', strtotime($slot['end_time']));
        }

        return implode(', ', $scheduleStrings);
    }

    /**
     * Check if teacher is qualified for the subject
     */
    public static function isTeacherQualified($teacherId, $subjectId)
    {
        $teacher = Teacher::find($teacherId);
        $subject = Subject::find($subjectId);

        if (!$teacher || !$subject) {
            return false;
        }

        // Check if teacher's subject expertise matches
        // This is a basic check - you can enhance this logic
        return stripos($teacher->subject, $subject->name) !== false ||
               stripos($teacher->strand, $subject->strand) !== false ||
               stripos($teacher->subject, $subject->track) !== false;
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
