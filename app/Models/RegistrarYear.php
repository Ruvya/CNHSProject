<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrarYear extends Model
{
    use HasFactory;

    protected $table = 'registrar_years';

    protected $fillable = [
        'school_year',
        'created_by',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * The registrar user who created this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Registrar::class, 'created_by');
    }
}


