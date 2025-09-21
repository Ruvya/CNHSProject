<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

	public function scopeActive($query)
	{
		return $query->where('status', self::STATUS_ACTIVE);
	}
}


