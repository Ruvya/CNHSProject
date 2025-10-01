<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'capacity',
        'location',
        'equipment',
        'is_available',
        'special_requirements',
        'notes'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_available' => 'boolean',
        'equipment' => 'array',
        'special_requirements' => 'array',
    ];

    /**
     * Get schedules that use this room
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Check if room is available for a specific timeslot
     */
    public function isAvailableForTimeslot($day, $startTime, $endTime, $excludeScheduleId = null)
    {
        $query = $this->schedules()
            ->where('day', $day)
            ->where('status', 'active');

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $conflictingSchedules = $query->get();

        foreach ($conflictingSchedules as $schedule) {
            if ($this->timeSlotsOverlap($startTime, $endTime, $schedule->start_time, $schedule->end_time)) {
                return false;
            }
        }

        return true;
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
     * Get rooms suitable for a specific subject type
     */
    public function scopeSuitableForSubject($query, $subjectType)
    {
        return $query->where(function($q) use ($subjectType) {
            $q->where('type', 'general')
              ->orWhere('type', $subjectType)
              ->orWhereJsonContains('special_requirements', $subjectType);
        });
    }

    /**
     * Scope for available rooms
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Get formatted room display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->code . ' - ' . $this->name . ' (' . $this->type . ')';
    }
}
