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
        'profile_completed'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_temporary_account' => 'boolean',
        'profile_completed' => 'boolean',
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
     * Get student assignments (section assignments)
     */
    public function assignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get current active assignment
     */
    public function currentAssignment()
    {
        return $this->hasOne(StudentAssignment::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get current section
     */
    public function currentSection()
    {
        return $this->hasOneThrough(
            Section::class,
            StudentAssignment::class,
            'student_id',
            'id',
            'id',
            'section_id'
        )->where('student_assignments.status', 'active');
    }
}