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
        'remarks'
    ];

    protected $casts = [
        'quarter1' => 'decimal:2',
        'quarter2' => 'decimal:2',
        'quarter3' => 'decimal:2',
        'quarter4' => 'decimal:2',
        'final_grade' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
} 