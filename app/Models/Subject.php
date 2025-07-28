<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'grade_level',
        'teacher_id',
        'registrar_id',
        'description',
        'track',
        'cluster',
        'specialization',
        'grading',
        'semester',
        'is_master_subject',
        'is_core_subject',
        'prerequisite_subjects'
    ];

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
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id')
            ->withPivot('grade', 'quarter', 'school_year', 'remarks')
            ->withTimestamps();
    }

    /**
     * Get all grades for this subject
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class);
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

    /**
     * Get the currently assigned teacher (from TeacherAssignment, fallback to teacher_id)
     */
    public function getCurrentTeacherAttribute()
    {
        // Try to get the active teacher assignment for the current school year
        $schoolYear = app()->bound('currentSchoolYear') ? app('currentSchoolYear') : (date('n') >= 6 ? date('Y').'-'.(date('Y')+1) : (date('Y')-1).'-'.date('Y'));
        $assignment = $this->teacherAssignments()
            ->where('status', 'active')
            ->where('school_year', $schoolYear)
            ->latest('assignment_date')
            ->first();
        if ($assignment && $assignment->teacher) {
            return $assignment->teacher;
        }
        // Fallback to direct teacher_id column
        return $this->teacher;
    }
}