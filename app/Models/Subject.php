<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'subject_name',
        'subject_code',
        'grade_level',
        'teacher_id',
        'registrar_id',
        'description',
        'track',
        'strand',
        'grading',
        'is_master_subject'
    ];

    // Add mutators to map form fields to database columns
    public function setNameAttribute($value)
    {
        // Populate both 'name' and 'subject_name' columns for compatibility
        $this->attributes['name'] = $value;
        $this->attributes['subject_name'] = $value;
    }

    public function setCodeAttribute($value)
    {
        // Populate both 'code' and 'subject_code' columns for compatibility
        $this->attributes['code'] = $value;
        $this->attributes['subject_code'] = $value;
    }

    // Add accessors to get data using the expected field names
    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->attributes['subject_name'] ?? null;
    }

    public function getCodeAttribute()
    {
        return $this->attributes['code'] ?? $this->attributes['subject_code'] ?? null;
    }



    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function registrar()
    {
        return $this->belongsTo(Registrar::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subject')
            ->withPivot('grade')
            ->withTimestamps();
    }

    /**
     * Get all grades for this subject
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the subject offerings for this subject
     */
    public function offerings()
    {
        return $this->hasMany(SubjectOffering::class);
    }

    /**
     * Get active offerings for this subject
     */
    public function activeOfferings()
    {
        return $this->hasMany(SubjectOffering::class)->where('status', 'active');
    }

    /**
     * Scope for master subjects only
     */
    public function scopeMasterSubjects($query)
    {
        return $query->where('is_master_subject', true);
    }

    /**
     * Scope for specific grading
     */
    public function scopeForGrading($query, $grading)
    {
        return $query->where('grading', $grading);
    }

    /**
     * Get formatted subject display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }
}