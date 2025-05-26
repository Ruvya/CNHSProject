<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'author_type'
    ];

    public function author()
    {
        return $this->morphTo();
    }
} 