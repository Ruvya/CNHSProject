<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

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
        'status',
        'author_type',
        'author_id',
        'is_published',
        'published_at'
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is saved or deleted
        static::saved(function ($model) {
            cache()->forget('announcements_list');
            // Try cache tags, but fall back to simple cache if not supported
            try {
                cache()->tags(['announcements'])->forget('announcement_' . $model->id);
            } catch (\Exception $e) {
                cache()->forget('announcement_' . $model->id);
            }
        });

        static::deleted(function ($model) {
            cache()->forget('announcements_list');
            // Try cache tags, but fall back to simple cache if not supported
            try {
                cache()->tags(['announcements'])->forget('announcement_' . $model->id);
            } catch (\Exception $e) {
                cache()->forget('announcement_' . $model->id);
            }
        });
    }

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