<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherYearlyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'school_year',
        'department',
        'position',
        'subjects_taught',
        'grade_levels_handled',
        'advisory_section',
        'total_students',
        'teaching_load',
        'employment_status',
        'status',
        'notes',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'subjects_taught' => 'array',
        'grade_levels_handled' => 'array',
        'total_students' => 'integer',
        'teaching_load' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the teacher that owns this yearly record
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get formatted subjects taught
     */
    public function getFormattedSubjectsAttribute()
    {
        if (!$this->subjects_taught) {
            return 'No subjects assigned';
        }
        
        return is_array($this->subjects_taught) 
            ? implode(', ', $this->subjects_taught)
            : $this->subjects_taught;
    }

    /**
     * Get formatted grade levels
     */
    public function getFormattedGradeLevelsAttribute()
    {
        if (!$this->grade_levels_handled) {
            return 'No grade levels assigned';
        }
        
        return is_array($this->grade_levels_handled) 
            ? implode(', ', $this->grade_levels_handled)
            : $this->grade_levels_handled;
    }

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for specific school year
     */
    public function scopeForSchoolYear($query, $schoolYear)
    {
        return $query->where('school_year', $schoolYear);
    }
}
