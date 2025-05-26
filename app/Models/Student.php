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

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
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
        'emergency_relationship'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

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
        return $this->hasMany(Grade::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject')
            ->withPivot('grade')
            ->withTimestamps();
    }
}