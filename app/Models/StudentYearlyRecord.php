<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentYearlyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_year',
        'grade_level',
        'section',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 