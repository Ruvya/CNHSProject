<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'quarter1',
        'quarter2',
        'quarter3',
        'quarter4',
        'final_grade',
        'remarks',
        'school_year',
        'semester',
        'status',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'quarter1' => 'decimal:2',
        'quarter2' => 'decimal:2',
        'quarter3' => 'decimal:2',
        'quarter4' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Principal::class, 'reviewed_by');
    }

    public function approvalLogs()
    {
        return $this->hasMany(GradeApprovalLog::class);
    }

    /**
     * Calculate final grade from quarters
     */
    public function calculateFinalGrade()
    {
        $quarters = array_filter([
            $this->quarter1,
            $this->quarter2,
            $this->quarter3,
            $this->quarter4
        ]);

        if (empty($quarters)) {
            return null;
        }

        return round(array_sum($quarters) / count($quarters), 2);
    }

    /**
     * Get grade status (Passed/Failed/Incomplete)
     */
    public function getGradeStatusAttribute()
    {
        if ($this->final_grade === null) {
            return 'Incomplete';
        }

        return $this->final_grade >= 75 ? 'Passed' : 'Failed';
    }

    /**
     * Get status color class
     */
    public function getStatusColorAttribute()
    {
        switch ($this->grade_status) {
            case 'Passed':
                return 'success';
            case 'Failed':
                return 'danger';
            default:
                return 'warning';
        }
    }

    /**
     * Get approval status color class
     */
    public function getApprovalStatusColorAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return 'secondary';
            case 'submitted':
                return 'info';
            case 'under_review':
                return 'warning';
            case 'approved':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Get approval status badge text
     */
    public function getApprovalStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return 'Draft';
            case 'submitted':
                return 'Submitted';
            case 'under_review':
                return 'Under Review';
            case 'approved':
                return 'Approved';
            case 'rejected':
                return 'Rejected';
            default:
                return 'Unknown';
        }
    }

    /**
     * Scope to get grades for a specific teacher
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->whereHas('subject', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        });
    }

    /**
     * Scope to get grades for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Check if First Semester (Q1 and Q2) has any grades
     */
    public function hasFirstSemesterGrades()
    {
        return !is_null($this->quarter1) || !is_null($this->quarter2);
    }

    /**
     * Check if First Semester (Q1 and Q2) is complete (both quarters have grades)
     */
    public function isFirstSemesterComplete()
    {
        return !is_null($this->quarter1) && !is_null($this->quarter2);
    }

    /**
     * Check if Second Semester inputs should be locked
     * Locks when there are NO inputs in First Semester
     */
    public function shouldLockSecondSemester()
    {
        return !$this->hasFirstSemesterGrades();
    }

    /**
     * Static method to check if second semester should be locked for a student/subject
     */
    public static function shouldLockSecondSemesterFor($studentId, $subjectId)
    {
        $grade = static::where('student_id', $studentId)
                      ->where('subject_id', $subjectId)
                      ->first();
        
        // If no grade record exists, lock second semester
        if (!$grade) {
            return true;
        }
        
        return $grade->shouldLockSecondSemester();
    }

    /**
     * Submit grade for approval
     */
    public function submitForApproval()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        // Log the submission
        $this->approvalLogs()->create([
            'action' => 'submitted',
            'performed_by' => null, // Teacher submitted, not principal
            'notes' => 'Grade submitted for approval',
            'grade_data_snapshot' => $this->toArray()
        ]);
    }

    /**
     * Approve grade
     */
    public function approve($principalId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $principalId,
            'reviewed_at' => now(),
            'approval_notes' => $notes
        ]);

        // Log the approval
        $this->approvalLogs()->create([
            'action' => 'approved',
            'performed_by' => $principalId,
            'notes' => $notes,
            'grade_data_snapshot' => $this->toArray()
        ]);
    }

    /**
     * Reject grade
     */
    public function reject($principalId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $principalId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason
        ]);

        // Log the rejection
        $this->approvalLogs()->create([
            'action' => 'rejected',
            'performed_by' => $principalId,
            'notes' => $reason,
            'grade_data_snapshot' => $this->toArray()
        ]);
    }

    /**
     * Return grade for revision
     */
    public function returnForRevision($principalId, $notes)
    {
        $this->update([
            'status' => 'draft',
            'reviewed_by' => $principalId,
            'reviewed_at' => now(),
            'approval_notes' => $notes
        ]);

        // Log the return
        $this->approvalLogs()->create([
            'action' => 'returned_for_revision',
            'performed_by' => $principalId,
            'notes' => $notes,
            'grade_data_snapshot' => $this->toArray()
        ]);
    }

    /**
     * Check if grade can be edited
     */
    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if grade is pending approval
     */
    public function isPendingApproval()
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    /**
     * Check if grade is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Scope to get grades by approval status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending approval grades
     */
    public function scopePendingApproval($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }
}