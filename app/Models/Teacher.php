<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'subject',
        'track',
        'cluster',
        'contact_number',
        'address',
        'status',
        'profile_picture',
        'password_change_required',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_change_required' => 'boolean',
        'password_changed_at' => 'datetime',
    ];

    /**
     * Get the subjects assigned to this teacher
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get all students enrolled in this teacher's subjects
     */
    public function students()
    {
        return Student::whereHas('subjects', function($query) {
            $query->where('teacher_id', $this->id);
        });
    }

    /**
     * Get the yearly records for this teacher
     */
    public function yearlyRecords()
    {
        return $this->hasMany(TeacherYearlyRecord::class);
    }

    /**
     * Get the current year record for this teacher
     */
    public function currentYearRecord()
    {
        $currentSchoolYear = $this->getCurrentSchoolYear();
        return $this->yearlyRecords()->where('school_year', $currentSchoolYear)->first();
    }

    /**
     * Get the current school year (e.g., 2023-2024)
     */
    public function getCurrentSchoolYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        // School year typically starts in June/July
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }



    /**
     * Get grades for students in this teacher's subjects
     */
    public function grades()
    {
        return Grade::whereHas('subject', function($query) {
            $query->where('teacher_id', $this->id);
        });
    }

    /**
     * Get teacher assignments
     */
    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    /**
     * Get current active assignments
     */
    public function currentAssignments()
    {
        return $this->hasMany(TeacherAssignment::class)
            ->where('status', 'active');
    }

    /**
     * Get sections this teacher is adviser for
     */
    public function advisedSections()
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }

    /**
     * Get subjects assigned to this teacher through teacher assignments
     */
    public function assignedSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_assignments', 'teacher_id', 'subject_id')
            ->wherePivot('status', 'active')
            ->withPivot('school_year', 'grading_period', 'schedule', 'assignment_date', 'notes');
    }

    /**
     * Get current active assigned subjects for the current school year
     */
    public function currentAssignedSubjects($schoolYear = null, $gradingPeriod = null)
    {
        $query = $this->belongsToMany(Subject::class, 'teacher_assignments', 'teacher_id', 'subject_id')
            ->wherePivot('status', 'active');

        if ($schoolYear) {
            $query->wherePivot('school_year', $schoolYear);
        }

        if ($gradingPeriod) {
            $query->wherePivot('grading_period', $gradingPeriod);
        }

        return $query->withPivot('school_year', 'grading_period', 'schedule', 'assignment_date', 'notes');
    }

    /**
     * Get all subjects (both direct assignment and through teacher assignments)
     */
    public function allSubjects()
    {
        // Get subjects from direct assignment (old way)
        $directSubjects = $this->subjects();

        // Get subjects from teacher assignments (new way)
        $assignedSubjects = $this->assignedSubjects();

        // Combine both and return unique subjects
        $directIds = $directSubjects->pluck('id')->toArray();
        $assignedIds = $assignedSubjects->pluck('id')->toArray();
        $allIds = array_unique(array_merge($directIds, $assignedIds));

        return Subject::whereIn('id', $allIds);
    }
}