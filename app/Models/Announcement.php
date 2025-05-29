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
}