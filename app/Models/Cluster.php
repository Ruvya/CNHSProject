<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
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
     * Get the track that owns this cluster
     */
    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    /**
     * Get students with this cluster
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'cluster', 'name');
    }

    /**
     * Get subjects with this cluster
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'cluster', 'name');
    }

    /**
     * Get teachers with this cluster
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'cluster', 'name');
    }

    /**
     * Scope to get only active clusters
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if cluster can be deleted (no students/subjects assigned)
     */
    public function canBeDeleted()
    {
        return $this->students()->count() === 0 &&
               $this->subjects()->count() === 0 &&
               $this->teachers()->count() === 0;
    }
}

