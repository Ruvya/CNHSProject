<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SchoolYear extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'start_year',
		'end_year',
		'status', // active, closed, archived
	];

	public const STATUS_ACTIVE = 'active';
	public const STATUS_CLOSED = 'closed';
	public const STATUS_ARCHIVED = 'archived';

	/**
	 * Link to RegistrarYear container record
	 */
	public function registrarYear(): HasOne
	{
		return $this->hasOne(RegistrarYear::class, 'school_year', 'name');
	}

	/**
	 * Get all student yearly records for this school year
	 */
	public function studentYearlyRecords(): HasMany
	{
		return $this->hasMany(StudentYearlyRecord::class, 'school_year', 'name');
	}

	/**
	 * Get all teacher yearly records for this school year
	 */
	public function teacherYearlyRecords(): HasMany
	{
		return $this->hasMany(TeacherYearlyRecord::class, 'school_year', 'name');
	}

	/**
	 * Get all sections for this school year
	 */
	public function sections(): HasMany
	{
		return $this->hasMany(Section::class, 'school_year', 'name');
	}

	/**
	 * Get all teacher assignments for this school year
	 */
	public function teacherAssignments(): HasMany
	{
		return $this->hasMany(TeacherAssignment::class, 'school_year', 'name');
	}

	/**
	 * Get all schedules for this school year
	 */
	public function schedules(): HasMany
	{
		return $this->hasMany(Schedule::class, 'school_year', 'name');
	}

	/**
	 * Get all subjects that have students enrolled in this school year
	 */
	public function subjects()
	{
		return Subject::whereHas('students', function($query) {
			$query->where('student_subject.school_year', $this->name);
		});
	}

	/**
	 * Get all students enrolled in this school year
	 */
	public function students()
	{
		return Student::whereHas('yearlyRecords', function($query) {
			$query->where('school_year', $this->name);
		});
	}

	/**
	 * Get all teachers assigned in this school year
	 */
	public function teachers()
	{
		return Teacher::whereHas('yearlyRecords', function($query) {
			$query->where('school_year', $this->name);
		});
	}

	/**
	 * Get summary statistics for this school year
	 */
	public function getSummaryAttribute()
	{
		return [
			'total_students' => $this->studentYearlyRecords()->count(),
			'total_teachers' => $this->teacherYearlyRecords()->count(),
			'total_sections' => $this->sections()->count(),
			'total_assignments' => $this->teacherAssignments()->count(),
			'total_schedules' => $this->schedules()->count(),
		];
	}

	/**
	 * Check if this school year is the current active one
	 */
	public function isActive(): bool
	{
		return $this->status === self::STATUS_ACTIVE;
	}

	/**
	 * Check if this school year is closed
	 */
	public function isClosed(): bool
	{
		return $this->status === self::STATUS_CLOSED;
	}

	/**
	 * Check if this school year is archived
	 */
	public function isArchived(): bool
	{
		return $this->status === self::STATUS_ARCHIVED;
	}

	/**
	 * Get formatted year range
	 */
	public function getFormattedYearsAttribute(): string
	{
		return $this->start_year . ' - ' . $this->end_year;
	}

	/**
	 * Scope for active school year
	 */
	public function scopeActive($query)
	{
		return $query->where('status', self::STATUS_ACTIVE);
	}

	/**
	 * Scope for closed school years
	 */
	public function scopeClosed($query)
	{
		return $query->where('status', self::STATUS_CLOSED);
	}

	/**
	 * Scope for archived school years
	 */
	public function scopeArchived($query)
	{
		return $query->where('status', self::STATUS_ARCHIVED);
	}

	/**
	 * Scope for non-archived school years
	 */
	public function scopeNotArchived($query)
	{
		return $query->where('status', '!=', self::STATUS_ARCHIVED);
	}

	protected static function booted(): void
	{
		static::created(function (SchoolYear $schoolYear) {
			// Ensure a RegistrarYear record exists for this school year
			if (!RegistrarYear::where('school_year', $schoolYear->name)->exists()) {
				RegistrarYear::create([
					'school_year' => $schoolYear->name,
					'metadata' => [
						'start_year' => $schoolYear->start_year,
						'end_year' => $schoolYear->end_year,
						'status' => $schoolYear->status,
					],
				]);
			}
		});
	}
}


