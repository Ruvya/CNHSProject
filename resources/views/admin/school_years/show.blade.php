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
	</div>

	<div class="row g-3">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">Summary</div>
				<div class="card-body">
					<div class="row text-center">
						<div class="col">
							<strong>Students</strong>
							<div>{{ $summary['total_students'] }}</div>
						</div>
						<div class="col">
							<strong>Teachers</strong>
							<div>{{ $summary['total_teachers'] }}</div>
						</div>
						<div class="col">
							<strong>Sections</strong>
							<div>{{ $summary['total_sections'] }}</div>
						</div>
						<div class="col">
							<strong>Assignments</strong>
							<div>{{ $summary['total_assignments'] }}</div>
						</div>
						<div class="col">
							<strong>Subjects</strong>
							<div>{{ $summary['total_subjects'] }}</div>
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
	</div>
</div>
@endsection


