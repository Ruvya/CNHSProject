<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    /**
     * Get all clusters for this track
     */
    public function clusters()
    {
        return $this->hasMany(Cluster::class)->orderBy('order')->orderBy('name');
    }

    /**
     * Get active clusters only
     */
    public function activeClusters()
    {
        return $this->clusters()->where('status', 'active');
    }

    /**
     * Get students with this track
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'track', 'name');
    }

    /**
     * Get subjects with this track
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'track', 'name');
    }

    /**
     * Get teachers with this track
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'track', 'name');
    }

    /**
     * Scope to get only active tracks
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if track can be deleted (no clusters or students/subjects assigned)
     */
    public function canBeDeleted()
    {
        return $this->clusters()->count() === 0 &&
               $this->students()->count() === 0 &&
               $this->subjects()->count() === 0 &&
               $this->teachers()->count() === 0;
    }
}

