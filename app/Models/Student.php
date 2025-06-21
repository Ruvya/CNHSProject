<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'name', // Add the name field to fillable
        'email',
        'password',
        'grade_level',
        'year_level',
        'section',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'religion',
        'civil_status',
        'lrn',
        'profile_picture',
        'contact_number',
        'address',
        'parent_name',
        'parent_contact',
        'advisor',
        'track',
        'strand',
        'province',
        'municipality',
        'barangay',
        'permanent_address',
        'phone',
        'social_media',
        'emergency_name',
        'emergency_phone',
        'emergency_relationship',
        'is_temporary_account',
        'profile_completed',
        'registrar_data_uploaded',
        'registrar_upload_date',
        'allow_profile_edit'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'is_temporary_account' => 'boolean',
        'profile_completed' => 'boolean',
        'registrar_data_uploaded' => 'boolean',
        'registrar_upload_date' => 'datetime',
        'allow_profile_edit' => 'boolean',
    ];

    /**
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically populate the 'name' field when creating or updating
        static::saving(function ($student) {
            $student->name = $student->getFullNameAttribute();
        });

        // Automatically assign subjects when student is created or track/strand changes
        static::saved(function ($student) {
            // Check if this is a new student or if track/strand/grade_level changed
            if ($student->wasRecentlyCreated ||
                $student->wasChanged(['track', 'strand', 'grade_level'])) {

                // Only auto-assign if all required fields are present
                if ($student->track && $student->strand && $student->grade_level) {
                    $assignmentService = app(\App\Services\AutomaticSubjectAssignmentService::class);
                    $assignmentService->assignSubjectsToStudent($student);
                }
            }
        });
    }

    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
            ->withPivot('grade', 'quarter', 'school_year', 'remarks')
            ->withTimestamps();
    }

    /**
     * Get subjects for current school year
     */
    public function currentSubjects($schoolYear = null)
    {
        $schoolYear = $schoolYear ?? $this->getCurrentSchoolYear();

        return $this->subjects()->wherePivot('school_year', $schoolYear);
    }

    /**
     * Get core subjects assigned to this student
     */
    public function coreSubjects()
    {
        return $this->subjects()->where('is_core_subject', true);
    }

    /**
     * Get track-specific subjects assigned to this student
     */
    public function trackSubjects()
    {
        return $this->subjects()
            ->where('track', $this->track)
            ->where('is_core_subject', false);
    }

    /**
     * Get strand-specific subjects assigned to this student
     */
    public function strandSubjects()
    {
        return $this->subjects()
            ->where('track', $this->track)
            ->where('strand', $this->strand)
            ->where('is_core_subject', false);
    }

    /**
     * Check if student has all required subjects assigned
     */
    public function hasCompleteSubjectAssignment()
    {
        if (!$this->track || !$this->strand || !$this->grade_level) {
            return false;
        }

        $assignmentService = app(\App\Services\AutomaticSubjectAssignmentService::class);
        return !$assignmentService->needsReassignment($this);
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
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

    /**
     * Get formatted track and strand display
     */
    public function getTrackStrandDisplayAttribute()
    {
        if (!$this->track || !$this->strand) {
            return 'Not Set';
        }

        return $this->track . ' - ' . $this->strand;
    }

    /**
     * Check if student is ready for automatic subject assignment
     */
    public function isReadyForSubjectAssignment()
    {
        return !empty($this->track) && !empty($this->strand) && !empty($this->grade_level);
    }

    public function yearlyRecords()
    {
        return $this->hasMany(StudentYearlyRecord::class);
    }
}