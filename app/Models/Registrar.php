<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Registrar extends Authenticatable
{
    use Notifiable;

    // Use default table name 'registrars' to match auth configuration
    protected $guard = 'registrar';

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_picture',
        'registrar_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name ?? 'Registrar';
    }

    /**
     * Get the display name attribute.
     */
    public function getDisplayNameAttribute()
    {
        return $this->first_name ?? $this->name ?? 'Registrar';
    }

    /**
     * Get the subjects created by this registrar.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}