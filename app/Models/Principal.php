<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Principal extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'contact_number',
        'address',
        'position',
        'bio',
        'education',
        'years_of_experience',
        'date_of_birth',
        'gender',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'facebook',
        'linkedin',
        'twitter',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'years_of_experience' => 'integer',
    ];

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the profile picture URL.
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        // Return default avatar
        return asset('images/default-avatar.png');
    }

    /**
     * Get the age from date of birth.
     */
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        return null;
    }

    /**
     * Get formatted years of experience.
     */
    public function getFormattedExperienceAttribute()
    {
        if ($this->years_of_experience) {
            $years = $this->years_of_experience;
            return $years . ' ' . ($years == 1 ? 'year' : 'years') . ' of experience';
        }
        return 'Experience not specified';
    }
} 