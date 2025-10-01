<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherAssignment;

class SchedulingValidationService
{
    /**
     * Validate a schedule entry for conflicts and constraints
     */
    public function validateSchedule($scheduleData, $excludeScheduleId = null)
    {
        $errors = [];
        $warnings = [];

        // Check teacher qualification
        if (!$this->isTeacherQualified($scheduleData['teacher_id'], $scheduleData['subject_id'])) {
            $errors[] = "Teacher is not qualified to teach this subject.";
        }

        // Check teacher availability
        $teacherAvailability = $this->checkTeacherAvailability($scheduleData, $excludeScheduleId);
        if (!$teacherAvailability['available']) {
            $errors[] = $teacherAvailability['message'];
        }

        // Check teacher load
        $teacherLoad = $this->checkTeacherLoad($scheduleData, $excludeScheduleId);
        if (!$teacherLoad['within_limit']) {
            $warnings[] = $teacherLoad['message'];
        }

        // Check section conflicts
        $sectionConflicts = $this->checkSectionConflicts($scheduleData, $excludeScheduleId);
        if (!empty($sectionConflicts)) {
            $errors[] = "Section conflict: " . implode(', ', $sectionConflicts);
        }

        // Room is optional: only validate room-related constraints if provided
        if (!empty($scheduleData['room_id'])) {
            $roomAvailability = $this->checkRoomAvailability($scheduleData, $excludeScheduleId);
            if (!$roomAvailability['available']) {
                $errors[] = $roomAvailability['message'];
            }

            $roomSuitability = $this->checkRoomSuitability($scheduleData['room_id'], $scheduleData['subject_id']);
            if (!$roomSuitability['suitable']) {
                $warnings[] = $roomSuitability['message'];
            }

            $roomCapacity = $this->checkRoomCapacity($scheduleData['room_id'], $scheduleData['section_id']);
            if (!$roomCapacity['sufficient']) {
                $warnings[] = $roomCapacity['message'];
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings
        ];
    }

    /**
     * Check if teacher is qualified for the subject
     */
    public function isTeacherQualified($teacherId, $subjectId)
    {
        return TeacherAssignment::isTeacherQualified($teacherId, $subjectId);
    }

    /**
     * Check teacher availability (no double booking)
     */
    public function checkTeacherAvailability($scheduleData, $excludeScheduleId = null)
    {
        $query = Schedule::where('teacher_id', $scheduleData['teacher_id'])
            ->where('day', $scheduleData['day'])
            ->where('status', 'active')
            ->where('school_year', $scheduleData['school_year'])
            ->where('grading_period', $scheduleData['grading_period']);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $conflictingSchedules = $query->get();

        foreach ($conflictingSchedules as $schedule) {
            if ($this->timeSlotsOverlap($scheduleData['start_time'], $scheduleData['end_time'], $schedule->start_time, $schedule->end_time)) {
                $roomPart = $schedule->room ? ' in ' . $schedule->room->name : '';
                return [
                    'available' => false,
                    'message' => "Teacher is already scheduled for {$schedule->subject->name}{$roomPart} at the same time."
                ];
            }
        }

        return ['available' => true];
    }

    /**
     * Check teacher's weekly teaching load
     */
    public function checkTeacherLoad($scheduleData, $excludeScheduleId = null)
    {
        $teacher = Teacher::find($scheduleData['teacher_id']);
        $maxHours = 40; // Default maximum teaching hours per week

        $weeklyHours = Schedule::where('teacher_id', $scheduleData['teacher_id'])
            ->where('status', 'active')
            ->where('school_year', $scheduleData['school_year'])
            ->where('grading_period', $scheduleData['grading_period']);

        if ($excludeScheduleId) {
            $weeklyHours->where('id', '!=', $excludeScheduleId);
        }

        $schedules = $weeklyHours->get();
        $totalHours = $schedules->sum(function($schedule) {
            $start = strtotime($schedule->start_time);
            $end = strtotime($schedule->end_time);
            return ($end - $start) / 3600;
        });

        // Add current schedule hours
        $currentStart = strtotime($scheduleData['start_time']);
        $currentEnd = strtotime($scheduleData['end_time']);
        $totalHours += ($currentEnd - $currentStart) / 3600;

        if ($totalHours > $maxHours) {
            return [
                'within_limit' => false,
                'message' => "Teacher will exceed maximum teaching load ({$maxHours} hours/week). Current: {$totalHours} hours."
            ];
        }

        return ['within_limit' => true];
    }

    /**
     * Check section conflicts (no double booking)
     */
    public function checkSectionConflicts($scheduleData, $excludeScheduleId = null)
    {
        $query = Schedule::where('section_id', $scheduleData['section_id'])
            ->where('day', $scheduleData['day'])
            ->where('status', 'active')
            ->where('school_year', $scheduleData['school_year'])
            ->where('grading_period', $scheduleData['grading_period']);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $conflictingSchedules = $query->get();
        $conflicts = [];

        foreach ($conflictingSchedules as $schedule) {
            if ($this->timeSlotsOverlap($scheduleData['start_time'], $scheduleData['end_time'], $schedule->start_time, $schedule->end_time)) {
                $conflicts[] = "Section is already scheduled for {$schedule->subject->name} with {$schedule->teacher->name}";
            }
        }

        return $conflicts;
    }

    /**
     * Check room availability
     */
    public function checkRoomAvailability($scheduleData, $excludeScheduleId = null)
    {
        $room = Room::find($scheduleData['room_id']);
        
        if (!$room || !$room->is_available) {
            return [
                'available' => false,
                'message' => "Room is not available for scheduling."
            ];
        }

        $query = Schedule::where('room_id', $scheduleData['room_id'])
            ->where('day', $scheduleData['day'])
            ->where('status', 'active')
            ->where('school_year', $scheduleData['school_year'])
            ->where('grading_period', $scheduleData['grading_period']);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $conflictingSchedules = $query->get();

        foreach ($conflictingSchedules as $schedule) {
            if ($this->timeSlotsOverlap($scheduleData['start_time'], $scheduleData['end_time'], $schedule->start_time, $schedule->end_time)) {
                return [
                    'available' => false,
                    'message' => "Room is already occupied by {$schedule->section->name} for {$schedule->subject->name} at the same time."
                ];
            }
        }

        return ['available' => true];
    }

    /**
     * Check if room is suitable for the subject
     */
    public function checkRoomSuitability($roomId, $subjectId)
    {
        $room = Room::find($roomId);
        $subject = Subject::find($subjectId);

        if (!$room || !$subject) {
            return [
                'suitable' => false,
                'message' => "Invalid room or subject."
            ];
        }

        // Check if room type matches subject requirements
        $subjectType = $this->getSubjectType($subject);
        
        if ($room->type === 'general' || $room->type === $subjectType) {
            return ['suitable' => true];
        }

        // Check special requirements
        if ($room->special_requirements && in_array($subjectType, $room->special_requirements)) {
            return ['suitable' => true];
        }

        return [
            'suitable' => false,
            'message' => "Room type ({$room->type}) may not be suitable for {$subjectType} subjects."
        ];
    }

    /**
     * Check if room has sufficient capacity
     */
    public function checkRoomCapacity($roomId, $sectionId)
    {
        $room = Room::find($roomId);
        $section = Section::find($sectionId);

        if (!$room || !$section) {
            return [
                'sufficient' => false,
                'message' => "Invalid room or section."
            ];
        }

        if ($room->capacity < $section->current_enrollment) {
            return [
                'sufficient' => false,
                'message' => "Room capacity ({$room->capacity}) is less than section enrollment ({$section->current_enrollment})."
            ];
        }

        return ['sufficient' => true];
    }

    /**
     * Get subject type for room suitability checking
     */
    private function getSubjectType($subject)
    {
        $subjectName = strtolower($subject->name);
        
        if (strpos($subjectName, 'computer') !== false || strpos($subjectName, 'ict') !== false) {
            return 'computer_lab';
        }
        
        if (strpos($subjectName, 'science') !== false || strpos($subjectName, 'chemistry') !== false || 
            strpos($subjectName, 'physics') !== false || strpos($subjectName, 'biology') !== false) {
            return 'science_lab';
        }
        
        if (strpos($subjectName, 'pe') !== false || strpos($subjectName, 'physical education') !== false) {
            return 'gymnasium';
        }
        
        return 'general';
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
     * Get available time slots for a teacher on a specific day
     */
    public function getAvailableTimeSlots($teacherId, $day, $schoolYear, $gradingPeriod, $excludeScheduleId = null)
    {
        $existingSchedules = Schedule::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where('status', 'active')
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod);

        if ($excludeScheduleId) {
            $existingSchedules->where('id', '!=', $excludeScheduleId);
        }

        $schedules = $existingSchedules->get();

        // Define available time slots (8 AM to 5 PM, 1-hour slots)
        $availableSlots = [];
        $startHour = 8;
        $endHour = 17;

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slotStart = sprintf('%02d:00:00', $hour);
            $slotEnd = sprintf('%02d:00:00', $hour + 1);
            
            $isAvailable = true;
            foreach ($schedules as $schedule) {
                if ($this->timeSlotsOverlap($slotStart, $slotEnd, $schedule->start_time, $schedule->end_time)) {
                    $isAvailable = false;
                    break;
                }
            }
            
            if ($isAvailable) {
                $availableSlots[] = [
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'display' => date('g:i A', strtotime($slotStart)) . ' - ' . date('g:i A', strtotime($slotEnd))
                ];
            }
        }

        return $availableSlots;
    }

    /**
     * Get available rooms for a specific time slot
     */
    public function getAvailableRooms($day, $startTime, $endTime, $schoolYear, $gradingPeriod, $excludeScheduleId = null)
    {
        $conflictingSchedules = Schedule::where('day', $day)
            ->where('status', 'active')
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod);

        if ($excludeScheduleId) {
            $conflictingSchedules->where('id', '!=', $excludeScheduleId);
        }

        $schedules = $conflictingSchedules->get();
        $occupiedRoomIds = [];

        foreach ($schedules as $schedule) {
            if ($this->timeSlotsOverlap($startTime, $endTime, $schedule->start_time, $schedule->end_time)) {
                $occupiedRoomIds[] = $schedule->room_id;
            }
        }

        return Room::where('is_available', true)
            ->whereNotIn('id', $occupiedRoomIds)
            ->orderBy('name')
            ->get();
    }
}
