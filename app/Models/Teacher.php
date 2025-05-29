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
        'strand',
        'contact_number',
        'address',
        'status',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
}