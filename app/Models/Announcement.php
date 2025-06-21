<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_type',
        'author_id',
        'is_published',
        'published_at',
        'status'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the formatted created date.
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at->format('F d, Y');
    }

    public function author()
    {
        return $this->morphTo();
    }

    /**
     * Get the teacher author if this announcement is from a teacher
     */
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'author_id')
            ->where('author_type', 'App\Models\Teacher');
    }

    /**
     * Get the principal author if this announcement is from a principal
     */
    public function principal()
    {
        return $this->belongsTo(\App\Models\Principal::class, 'author_id')
            ->where('author_type', 'App\Models\Principal');
    }

    /**
     * Check if this announcement is from a teacher
     */
    public function isFromTeacher()
    {
        return $this->author_type === 'App\Models\Teacher';
    }

    /**
     * Check if this announcement is from a principal
     */
    public function isFromPrincipal()
    {
        return $this->author_type === 'App\Models\Principal';
    }
}