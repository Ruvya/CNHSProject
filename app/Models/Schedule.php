<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section_id',
        'room_id',
        'day',
        'start_time',
        'end_time',
        'school_year',
        'grading_period',
        'status',
        'notes',
        'created_by'
    ];

    // Important: keep times as raw strings (DB columns are TIME)
    // Casting to datetime would turn them into DateTime objects and
    // break comparisons that rely on string times with strtotime().
    protected $casts = [];

    /**
     * Get the teacher for this schedule
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject for this schedule
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the section for this schedule
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the room for this schedule
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the admin who created this schedule
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Check for conflicts with other schedules
     */
    public function hasConflicts($excludeId = null)
    {
        $conflicts = [];

        // Check teacher conflicts
        $teacherConflicts = $this->getTeacherConflicts($excludeId);
        if (!empty($teacherConflicts)) {
            $conflicts['teacher'] = $teacherConflicts;
        }

        // Check section conflicts
        $sectionConflicts = $this->getSectionConflicts($excludeId);
        if (!empty($sectionConflicts)) {
            $conflicts['section'] = $sectionConflicts;
        }

        // Check room conflicts
        if (!empty($this->room_id)) {
            $roomConflicts = $this->getRoomConflicts($excludeId);
            if (!empty($roomConflicts)) {
                $conflicts['room'] = $roomConflicts;
            }
        }

        return $conflicts;
    }

    /**
     * Get teacher conflicts
     */
    private function getTeacherConflicts($excludeId = null)
    {
        $query = self::where('teacher_id', $this->teacher_id)
            ->where('day', $this->day)
            ->where('status', 'active')
            ->where('school_year', $this->school_year)
            ->where('grading_period', $this->grading_period);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $schedules = $query->get();
        $conflicts = [];

        foreach ($schedules as $schedule) {
            if ($this->timeSlotsOverlap($this->start_time, $this->end_time, $schedule->start_time, $schedule->end_time)) {
                $roomPart = $schedule->room ? ' in ' . $schedule->room->name : '';
                $conflicts[] = [
                    'schedule' => $schedule,
                    'conflict_type' => 'teacher_double_booking',
                    'message' => "Teacher is already scheduled for {$schedule->subject->name}{$roomPart} at the same time"
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Get section conflicts
     */
    private function getSectionConflicts($excludeId = null)
    {
        $query = self::where('section_id', $this->section_id)
            ->where('day', $this->day)
            ->where('status', 'active')
            ->where('school_year', $this->school_year)
            ->where('grading_period', $this->grading_period);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $schedules = $query->get();
        $conflicts = [];

        foreach ($schedules as $schedule) {
            if ($this->timeSlotsOverlap($this->start_time, $this->end_time, $schedule->start_time, $schedule->end_time)) {
                $conflicts[] = [
                    'schedule' => $schedule,
                    'conflict_type' => 'section_double_booking',
                    'message' => "Section is already scheduled for {$schedule->subject->name} with {$schedule->teacher->name} at the same time"
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Get room conflicts
     */
    private function getRoomConflicts($excludeId = null)
    {
        $query = self::where('room_id', $this->room_id)
            ->where('day', $this->day)
            ->where('status', 'active')
            ->where('school_year', $this->school_year)
            ->where('grading_period', $this->grading_period);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $schedules = $query->get();
        $conflicts = [];

        foreach ($schedules as $schedule) {
            if ($this->timeSlotsOverlap($this->start_time, $this->end_time, $schedule->start_time, $schedule->end_time)) {
                $conflicts[] = [
                    'schedule' => $schedule,
                    'conflict_type' => 'room_double_booking',
                    'message' => "Room is already occupied by {$schedule->section->name} for {$schedule->subject->name} at the same time"
                ];
            }
        }

        return $conflicts;
    }

    /**
     * Check if two time slots overlap
     */
    private function timeSlotsOverlap($start1, $end1, $start2, $end2)
    {
        $start1 = strtotime($start1);
        $end1 = strtotime($end1);
        $start2 = strtotime($start2);
        $end2 = strtotime($end2);

        return !($end1 <= $start2 || $start1 >= $end2);
    }

    /**
     * Check if teacher is qualified for the subject
     */
    public function isTeacherQualified()
    {
        return TeacherAssignment::isTeacherQualified($this->teacher_id, $this->subject_id);
    }

    /**
     * Check if teacher has exceeded maximum teaching load
     */
    public function checkTeacherLoad()
    {
        $weeklyHours = self::where('teacher_id', $this->teacher_id)
            ->where('status', 'active')
            ->where('school_year', $this->school_year)
            ->where('grading_period', $this->grading_period)
            ->get()
            ->sum(function($schedule) {
                $start = strtotime($schedule->start_time);
                $end = strtotime($schedule->end_time);
                return ($end - $start) / 3600; // Convert to hours
            });

        // Add current schedule hours if updating
        if ($this->id) {
            $currentStart = strtotime($this->start_time);
            $currentEnd = strtotime($this->end_time);
            $weeklyHours += ($currentEnd - $currentStart) / 3600;
        }

        $maxHours = 40; // Default maximum teaching hours per week
        return $weeklyHours <= $maxHours;
    }

    /**
     * Get formatted schedule display
     */
    public function getFormattedScheduleAttribute()
    {
        $startTime = date('g:i A', strtotime($this->start_time));
        $endTime = date('g:i A', strtotime($this->end_time));
        
        return "{$this->day} {$startTime} - {$endTime}";
    }

    /**
     * Scope for active schedules
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
