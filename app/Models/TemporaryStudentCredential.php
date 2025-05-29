<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryStudentCredential extends Model
{
    protected $fillable = [
        'student_id',
        'password',
        'is_used',
        'created_by_admin_id',
        'used_by_student_id',
        'used_at',
        'notes'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the admin who created this credential
     */
    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    /**
     * Get the student who used this credential
     */
    public function usedByStudent(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'used_by_student_id', 'student_id');
    }

    /**
     * Mark this credential as used
     */
    public function markAsUsed(Student $student): void
    {
        $this->update([
            'is_used' => true,
            'used_by_student_id' => $student->id,
            'used_at' => now()
        ]);
    }

    /**
     * Scope to get unused credentials
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope to get used credentials
     */
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    /**
     * Scope to get credentials created by a specific admin
     */
    public function scopeCreatedBy($query, $adminId)
    {
        return $query->where('created_by_admin_id', $adminId);
    }

    /**
     * Get the plain password (for display purposes only)
     * Note: This should only be used for unused credentials
     */
    public function getPlainPassword()
    {
        return $this->getOriginal('password');
    }
}
