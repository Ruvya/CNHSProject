<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectSchedule extends Model
{
    protected $fillable = [
        'subject_offering_id',
        'day_of_week',
        'start_time',
        'end_time',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the subject offering that this schedule belongs to
     */
    public function subjectOffering(): BelongsTo
    {
        return $this->belongsTo(SubjectOffering::class);
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time)) . ' - ' .
               date('g:i A', strtotime($this->end_time));
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute(): int
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        return ($end - $start) / 60;
    }

    /**
     * Check if schedule conflicts with another schedule
     */
    public function conflictsWith(SubjectSchedule $other): bool
    {
        if ($this->day_of_week !== $other->day_of_week) {
            return false;
        }

        $thisStart = strtotime($this->start_time);
        $thisEnd = strtotime($this->end_time);
        $otherStart = strtotime($other->start_time);
        $otherEnd = strtotime($other->end_time);

        return !($thisEnd <= $otherStart || $thisStart >= $otherEnd);
    }

    /**
     * Scope for specific day
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Scope for specific room
     */
    public function scopeForRoom($query, $room)
    {
        return $query->where('room', $room);
    }

    /**
     * Scope for time range
     */
    public function scopeInTimeRange($query, $startTime, $endTime)
    {
        return $query->where(function ($q) use ($startTime, $endTime) {
            $q->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function ($q2) use ($startTime, $endTime) {
                  $q2->where('start_time', '<=', $startTime)
                     ->where('end_time', '>=', $endTime);
              });
        });
    }
}
