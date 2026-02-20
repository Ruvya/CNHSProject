@extends('layouts.admin')

@section('content')
<div class="container mt-4">
	<h1>Statistics: {{ $schoolYear->name }}</h1>

	<div class="mb-3">
		<a href="{{ route('admin.school-years.show', $schoolYear) }}" class="btn btn-secondary">Back to School Year</a>
		<a href="{{ route('admin.school-years.index') }}" class="btn btn-outline-secondary">All School Years</a>
	</div>

	<div class="row g-3">
		<!-- Student Statistics -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Student Distribution by Grade Level</h5>
				</div>
				<div class="card-body">
					@if($studentStats->count() > 0)
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Grade Level</th>
										<th>Count</th>
										<th>Percentage</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalStudents = $studentStats->sum('count');
									@endphp
									@foreach($studentStats as $stat)
									<tr>
										<td>Grade {{ $stat->grade_level }}</td>
										<td>{{ $stat->count }}</td>
										<td>{{ $totalStudents > 0 ? round(($stat->count / $totalStudents) * 100, 1) : 0 }}%</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<p class="text-muted">No student data available.</p>
					@endif
				</div>
			</div>
		</div>

		<!-- Teacher Statistics -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Teacher Distribution by Department</h5>
				</div>
				<div class="card-body">
					@if($teacherStats->count() > 0)
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Department</th>
										<th>Count</th>
										<th>Percentage</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalTeachers = $teacherStats->sum('count');
									@endphp
									@foreach($teacherStats as $stat)
									<tr>
										<td>{{ $stat->department }}</td>
										<td>{{ $stat->count }}</td>
										<td>{{ $totalTeachers > 0 ? round(($stat->count / $totalTeachers) * 100, 1) : 0 }}%</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<p class="text-muted">No teacher data available.</p>
					@endif
				</div>
			</div>
		</div>

		<!-- Section Statistics -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Section Distribution by Grade and Track</h5>
				</div>
				<div class="card-body">
					@if($sectionStats->count() > 0)
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Grade Level</th>
										<th>Track</th>
										<th>Count</th>
									</tr>
								</thead>
								<tbody>
									@foreach($sectionStats as $stat)
									<tr>
										<td>Grade {{ $stat->grade_level }}</td>
										<td>{{ $stat->track }}</td>
										<td>{{ $stat->count }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<p class="text-muted">No section data available.</p>
					@endif
				</div>
			</div>
		</div>

		<!-- Assignment Statistics -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Teacher Assignments by Grading Period</h5>
				</div>
				<div class="card-body">
					@if($assignmentStats->count() > 0)
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Grading Period</th>
										<th>Count</th>
										<th>Percentage</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalAssignments = $assignmentStats->sum('count');
									@endphp
									@foreach($assignmentStats as $stat)
									<tr>
										<td>{{ $stat->grading_period }}</td>
										<td>{{ $stat->count }}</td>
										<td>{{ $totalAssignments > 0 ? round(($stat->count / $totalAssignments) * 100, 1) : 0 }}%</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<p class="text-muted">No assignment data available.</p>
					@endif
				</div>
			</div>
		</div>

		<!-- Summary Cards -->
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Summary Overview</h5>
				</div>
				<div class="card-body">
					<div class="row text-center">
						<div class="col-md-2">
							<div class="card bg-primary text-white">
								<div class="card-body">
									<h4>{{ $studentStats->sum('count') }}</h4>
									<p class="mb-0">Total Students</p>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card bg-success text-white">
								<div class="card-body">
									<h4>{{ $teacherStats->sum('count') }}</h4>
									<p class="mb-0">Total Teachers</p>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card bg-info text-white">
								<div class="card-body">
									<h4>{{ $sectionStats->sum('count') }}</h4>
									<p class="mb-0">Total Sections</p>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card bg-warning text-white">
								<div class="card-body">
									<h4>{{ $assignmentStats->sum('count') }}</h4>
									<p class="mb-0">Total Assignments</p>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card bg-secondary text-white">
								<div class="card-body">
									<h4>{{ $studentStats->count() }}</h4>
									<p class="mb-0">Grade Levels</p>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="card bg-dark text-white">
								<div class="card-body">
									<h4>{{ $teacherStats->count() }}</h4>
									<p class="mb-0">Departments</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
