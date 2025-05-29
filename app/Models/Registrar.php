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
        'email',
        'password',
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
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the subjects created by this registrar.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }


}