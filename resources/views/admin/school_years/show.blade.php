@extends('layouts.admin')

@section('content')
<div class="container mt-4">
	<h1>School Year: {{ $schoolYear->name }}</h1>

	<div class="mb-3">
		<a href="{{ route('admin.school-years.index') }}" class="btn btn-secondary">Back</a>
		@if($schoolYear->status !== 'active')
			<form method="POST" action="{{ route('admin.school-years.activate', $schoolYear) }}" class="d-inline">
				@csrf
				<button class="btn btn-success">Activate</button>
			</form>
		@endif
		<a href="{{ route('admin.school-years.statistics', $schoolYear) }}" class="btn btn-info">Statistics</a>
		<a href="{{ route('admin.school-years.export', $schoolYear) }}" class="btn btn-outline-primary">Export Data</a>
		<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
		@if($schoolYear->studentYearlyRecords()->count() == 0 && $schoolYear->teacherYearlyRecords()->count() == 0)
			<form method="POST" action="{{ route('admin.school-years.destroy', $schoolYear) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this school year?')">
				@csrf
				@method('DELETE')
				<button class="btn btn-danger">Delete</button>
			</form>
		@endif
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
						<div class="col">
							<strong>Assignments</strong>
							<div class="h4 text-warning">{{ $summary['total_assignments'] }}</div>
						</div>
						<div class="col">
							<strong>Subjects</strong>
							<div class="h4 text-secondary">{{ $summary['total_subjects'] }}</div>
						</div>
						@if(isset($summary['total_schedules']))
						<div class="col">
							<strong>Schedules</strong>
							<div class="h4 text-dark">{{ $summary['total_schedules'] }}</div>
						</div>
						@endif
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
								<th>Track/Strand</th>
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

		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Teacher Assignments</div>
				<div class="card-body">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Teacher</th>
								<th>Subject</th>
								<th>Grading Period</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							@forelse($assignments as $asg)
							<tr>
								<td>{{ optional($asg->teacher)->name ?? 'Unknown' }}</td>
								<td>{{ optional($asg->subject)->display_name ?? 'Unknown' }}</td>
								<td>{{ $asg->grading_period }}</td>
								<td>{{ ucfirst($asg->status ?? 'active') }}</td>
							</tr>
							@empty
							<tr><td colspan="4" class="text-center">No assignments.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Subjects (with student count)</div>
				<div class="card-body">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Subject</th>
								<th>Code</th>
								<th>Grade Level</th>
								<th>Students</th>
							</tr>
						</thead>
						<tbody>
							@forelse($subjects as $sub)
							<tr>
								<td>{{ $sub->name }}</td>
								<td>{{ $sub->code }}</td>
								<td>{{ $sub->grade_level }}</td>
								<td>{{ $sub->students_count }}</td>
							</tr>
							@empty
							<tr><td colspan="4" class="text-center">No subjects.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		@if(isset($gradeDistribution) && $gradeDistribution->count() > 0)
		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Grade Level Distribution</div>
				<div class="card-body">
					<div class="row">
						@foreach($gradeDistribution as $grade => $count)
						<div class="col-md-2 text-center">
							<div class="card bg-light">
								<div class="card-body">
									<h5 class="card-title">Grade {{ $grade }}</h5>
									<p class="card-text h4">{{ $count }} students</p>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		@endif

		@if(isset($sectionAnalysis) && $sectionAnalysis->count() > 0)
		<div class="col-md-12">
			<div class="card mt-3">
				<div class="card-header">Section Capacity Analysis</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>Section</th>
									<th>Grade</th>
									<th>Enrollment</th>
									<th>Capacity</th>
									<th>Utilization</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($sectionAnalysis as $section)
								<tr>
									<td>{{ $section['name'] }}</td>
									<td>{{ $section['grade_level'] }}</td>
									<td>{{ $section['current_enrollment'] }}</td>
									<td>{{ $section['max_capacity'] }}</td>
									<td>
										<div class="progress" style="height: 20px;">
											<div class="progress-bar {{ $section['utilization'] > 90 ? 'bg-danger' : ($section['utilization'] > 70 ? 'bg-warning' : 'bg-success') }}" 
												 style="width: {{ $section['utilization'] }}%">
												{{ $section['utilization'] }}%
											</div>
										</div>
									</td>
									<td>
										<span class="badge bg-{{ $section['status'] === 'active' ? 'success' : ($section['status'] === 'full' ? 'danger' : 'secondary') }}">
											{{ ucfirst($section['status']) }}
										</span>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif
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


