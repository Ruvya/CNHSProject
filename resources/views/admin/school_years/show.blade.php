@extends('layouts.admin')

@section('content')
<div class="container mt-4">
	<!-- Header (match User Management style) -->
	<div class="card mb-4" style="border-radius: 14px; box-shadow: 0 8px 25px rgba(30,58,138,0.12); border: none;">
		<div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
			<div class="d-flex align-items-center mb-2 mb-md-0">
				<span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
					<i class="fas fa-calendar"></i>
				</span>
				<div>
					<div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">School Year: {{ $schoolYear->name }}</div>
					<div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">View and manage records for this school year</div>
				</div>
			</div>
			<div class="mb-2 mb-md-0">
				<a href="{{ route('admin.school-years.index') }}" class="btn btn-outline-secondary me-2">Back</a>
				@if($schoolYear->status !== 'active')
				<form method="POST" action="{{ route('admin.school-years.activate', $schoolYear) }}" class="d-inline">
					@csrf
					<button class="btn btn-success">Activate</button>
				</form>
				@endif
				<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
				@if($schoolYear->studentYearlyRecords()->count() == 0 && $schoolYear->teacherYearlyRecords()->count() == 0)
				<form method="POST" action="{{ route('admin.school-years.destroy', $schoolYear) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this school year?')">
					@csrf
					@method('DELETE')
					<button class="btn btn-danger">Delete</button>
				</form>
				@endif
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">Summary</div>
				<div class="card-body">
					<div class="row text-center">
						<div class="col">
							<strong>Students</strong>
							<div class="h4 text-primary">{{ $summary['total_students'] }}</div>
						</div>
						<div class="col">
							<strong>Teachers</strong>
							<div class="h4 text-success">{{ $summary['total_teachers'] }}</div>
						</div>
						<div class="col">
							<strong>Sections</strong>
							<div class="h4 text-info">{{ $summary['total_sections'] }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Sections</div>
				<div class="card-body">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Grade</th>
								<th>Section</th>
								<th>Track/Cluster</th>
								<th>Adviser</th>
								<th>Enrollment</th>
							</tr>
						</thead>
						<tbody>
							@forelse($sections as $sec)
							<tr>
								<td>{{ $sec->grade_level }}</td>
								<td>{{ $sec->name }}</td>
								<td>{{ $sec->track }} {{ $sec->strand ? ' / '.$sec->strand : '' }}</td>
								<td>{{ optional($sec->adviser)->name ?? '—' }}</td>
								<td>{{ $sec->current_enrollment }}/{{ $sec->max_capacity }}</td>
							</tr>
							@empty
							<tr><td colspan="5" class="text-center">No sections.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Students (Yearly Records)</div>
				<div class="card-body">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Student</th>
								<th>Grade Level</th>
								<th>Section</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse($studentYearlyRecords as $syr)
							<tr>
								<td>{{ optional($syr->student)->name ?? 'Unknown' }}</td>
								<td>{{ $syr->grade_level }}</td>
								<td>{{ $syr->section }}</td>
								<td>{{ ucfirst($syr->status ?? 'active') }}</td>
							</tr>
							@empty
							<tr><td colspan="4" class="text-center">No student yearly records.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Teachers (Yearly Records)</div>
				<div class="card-body">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Teacher</th>
								<th>Department</th>
								<th>Position</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse($teacherYearlyRecords as $tyr)
							<tr>
								<td>{{ optional($tyr->teacher)->name ?? 'Unknown' }}</td>
								<td>{{ $tyr->department }}</td>
								<td>{{ $tyr->position }}</td>
								<td>{{ ucfirst($tyr->status ?? 'active') }}</td>
							</tr>
							@empty
							<tr><td colspan="4" class="text-center">No teacher yearly records.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit School Year</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form method="POST" action="{{ route('admin.school-years.update', $schoolYear) }}">
				@csrf
				@method('PUT')
				<div class="modal-body">
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" name="name" value="{{ $schoolYear->name }}" required>
					</div>
					<div class="mb-3">
						<label for="start_year" class="form-label">Start Year</label>
						<input type="number" class="form-control" id="start_year" name="start_year" value="{{ $schoolYear->start_year }}" min="2000" max="3000" required>
					</div>
					<div class="mb-3">
						<label for="end_year" class="form-label">End Year</label>
						<input type="number" class="form-control" id="end_year" name="end_year" value="{{ $schoolYear->end_year }}" min="2000" max="3000" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection


