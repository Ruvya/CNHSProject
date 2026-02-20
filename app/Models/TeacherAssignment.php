<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'school_year',
        'semester',
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

    // Note: section_id column was removed from teacher_assignments table
    // Section information is now handled through subject assignments

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
     * Scope for specific semester
     */
    public function scopeForSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope for specific grading period (deprecated - use scopeForSemester)
     */
    public function scopeForGradingPeriod($query, $gradingPeriod)
    {
        return $query->where('semester', $gradingPeriod);
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
            ->where('semester', $this->semester);

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
        // 1) Prefer embedded JSON schedule on the assignment (newer flow)
        if ($this->schedule && is_array($this->schedule) && count($this->schedule) > 0) {
            $scheduleStrings = [];
            foreach ($this->schedule as $slot) {
                if (!isset($slot['day'], $slot['start_time'], $slot['end_time'])) {
                    continue;
                }
                $scheduleStrings[] = $slot['day'] . ' ' .
                    date('g:i A', strtotime($slot['start_time'])) . '-' .
                    date('g:i A', strtotime($slot['end_time']));
            }
            if (!empty($scheduleStrings)) {
                return implode(', ', $scheduleStrings);
            }
        }

        // 2) Fallback: look up active schedules table for this teacher+subject, same school year and semester
        try {
            $query = \App\Models\Schedule::with(['section'])
                ->where('teacher_id', $this->teacher_id)
                ->where('subject_id', $this->subject_id)
                ->where('status', 'active');

            if (!empty($this->school_year)) {
                $query->where('school_year', $this->school_year);
            }

            // Respect semester/grading_period column
            if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
                if (!empty($this->semester)) {
                    $query->where('semester', $this->semester);
                }
            } else {
                if (!empty($this->semester)) {
                    $query->where('grading_period', $this->semester);
                }
            }

            $schedules = $query->orderBy('day')->orderBy('start_time')->get();
            if ($schedules->isEmpty()) {
                // Relax filters if nothing found: ignore school_year/semester and take most recent
                $relaxed = \App\Models\Schedule::where('teacher_id', $this->teacher_id)
                    ->where('subject_id', $this->subject_id)
                    ->where('status', 'active')
                    ->orderByDesc('school_year')
                    ->orderByDesc('id')
                    ->get();
                if ($relaxed->isNotEmpty()) {
                    $schedules = $relaxed;
                }
            }

            if ($schedules->isNotEmpty()) {
                return $schedules->map(function ($sch) {
                    return $sch->formatted_schedule;
                })->implode(', ');
            }
        } catch (\Throwable $e) {}

        return 'No schedule set';
    }

    /**
     * Display semester with graceful fallback to schedule/subject semester
     */
    public function getDisplaySemesterAttribute(): ?string
    {
        if (!empty($this->semester)) {
            return $this->semester;
        }

        // Fallback: try schedules table
        try {
            $query = \App\Models\Schedule::where('teacher_id', $this->teacher_id)
                ->where('subject_id', $this->subject_id)
                ->where('status', 'active');

            if (!empty($this->school_year)) {
                $query->where('school_year', $this->school_year);
            }

            $period = null;
            if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
                $period = (string) $query->value('semester');
            } else {
                $period = (string) $query->value('grading_period');
            }

            if (!empty($period)) {
                return $period;
            }

            // Relax: any most-recent schedule for this teacher+subject
            $recent = \App\Models\Schedule::where('teacher_id', $this->teacher_id)
                ->where('subject_id', $this->subject_id)
                ->where('status', 'active')
                ->orderByDesc('school_year')
                ->orderByDesc('id')
                ->first();
            if ($recent) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
                    return $recent->semester;
                }
                return $recent->grading_period;
            }
        } catch (\Throwable $e) {}

        // Final fallback: use subject's configured semester if any
        return $this->subject->semester ?? null;
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
