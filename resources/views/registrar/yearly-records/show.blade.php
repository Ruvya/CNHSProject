@extends('layouts.registrar')

@section('title', 'School Year ' . $schoolYear . ' Records')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('registrar.yearly-records.index') }}">Yearly Records</a></li>
                    <li class="breadcrumb-item active">{{ $schoolYear }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">School Year {{ $schoolYear }}</h1>
            <p class="text-muted">Student and teacher records for academic year {{ $schoolYear }}</p>
        </div>
        <div>
            <a href="{{ route('registrar.yearly-records.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Overview
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('registrar.yearly-records.show', $schoolYear) }}" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Grade Level</label>
                <select name="grade_level" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($availableGradeLevels as $g)
                        <option value="{{ $g }}" {{ ($selectedGrade ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Section</label>
                <select name="section" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($availableSections as $s)
                        <option value="{{ $s }}" {{ ($selectedSection ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('registrar.yearly-records.show', $schoolYear) }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Year Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $yearStats['total_students'] }}</h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $yearStats['total_teachers'] }}</h3>
                    <p class="mb-0">Total Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $yearStats['students_by_grade']->count() }}</h3>
                    <p class="mb-0">Grade Levels</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $yearStats['teachers_by_department']->count() }}</h3>
                    <p class="mb-0">Departments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for Students and Teachers -->
    <ul class="nav nav-tabs" id="recordsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                <i class="fas fa-user-graduate me-2"></i>Students ({{ $yearStats['total_students'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">
                <i class="fas fa-chalkboard-teacher me-2"></i>Teachers ({{ $yearStats['total_teachers'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </button>
        </li>
    </ul>

    <div class="tab-content" id="recordsTabContent">
        <!-- Students Tab (by Section) -->
        <div class="tab-pane fade show active" id="students" role="tabpanel">
            @if($sections->count() > 0)
                @foreach($sections as $section)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Section:</strong> {{ $section->name }}
                                <span class="ms-2 text-muted">Grade {{ $section->grade_level }} • {{ $section->track }} {{ $section->strand ? ' / '.$section->strand : '' }}</span>
                            </div>
                            <div>
                                <small class="text-muted">Adviser: {{ optional($section->adviser)->name ?? '—' }}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            @php $records = $studentRecords->get($section->name) ?? collect(); @endphp
                            @if($records->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Subjects & Grades ({{ $schoolYear }})</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($records as $rec)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                                {{ substr(optional($rec->student)->name ?? 'U', 0, 1) }}
                                                            </div>
                                                            <div>
                                                                <strong>{{ optional($rec->student)->name ?? 'Unknown' }}</strong><br>
                                                                <small class="text-muted">{{ optional($rec->student)->student_id }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php $subs = optional($rec->student)->subjects ?? collect(); @endphp
                                                        @if($subs->count() > 0)
                                                            <div class="small">
                                                                @foreach($subs as $sub)
                                                                    <div>
                                                                        <strong>{{ $sub->code }}</strong> - {{ $sub->name }}
                                                                        @if($sub->pivot && $sub->pivot->grade)
                                                                            <span class="badge bg-{{ $sub->pivot->grade >= 75 ? 'success' : 'danger' }} ms-2">{{ $sub->pivot->grade }}</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No enrolled subjects for {{ $schoolYear }}.</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $rec->status === 'enrolled' ? 'success' : ($rec->status === 'graduated' ? 'primary' : 'secondary') }}">
                                                            {{ ucfirst($rec->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                <a href="{{ route('registrar.students.show', [$rec->student, 'school_year' => $schoolYear]) }}" class="btn btn-sm btn-outline-secondary">Profile</a>
                                                <a href="{{ route('registrar.students.yearly-records.index', [$rec->student, 'school_year' => $schoolYear]) }}" class="btn btn-sm btn-outline-primary">Yearly Records</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-muted">No students linked to this section for {{ $schoolYear }}.</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                @php $list = isset($allStudentRecords) ? $allStudentRecords : collect(); @endphp
                @if($list->count() > 0)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Students (no sections set)</h5>
                            <small class="text-muted">Displaying all students for {{ $schoolYear }}</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Grade Level</th>
                                            <th>Section</th>
                                            <th>Subjects & Grades ({{ $schoolYear }})</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($list as $rec)
                                            <tr>
                                                <td>
                                                    <strong>{{ optional($rec->student)->name ?? 'Unknown' }}</strong><br>
                                                    <small class="text-muted">{{ optional($rec->student)->student_id }}</small>
                                                </td>
                                                <td>{{ $rec->grade_level }}</td>
                                                <td>{{ $rec->section ?? '—' }}</td>
                                                <td>
                                                    @php $subs = optional($rec->student)->subjects ?? collect(); @endphp
                                                    @if($subs->count() > 0)
                                                        <div class="small">
                                                            @foreach($subs as $sub)
                                                                <div>
                                                                    <strong>{{ $sub->code }}</strong> - {{ $sub->name }}
                                                                    @if($sub->pivot && $sub->pivot->grade)
                                                                        <span class="badge bg-{{ $sub->pivot->grade >= 75 ? 'success' : 'danger' }} ms-2">{{ $sub->pivot->grade }}</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No enrolled subjects.</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $rec->status === 'enrolled' ? 'success' : ($rec->status === 'graduated' ? 'primary' : 'secondary') }}">{{ ucfirst($rec->status) }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('registrar.students.show', [$rec->student, 'school_year' => $schoolYear]) }}" class="btn btn-sm btn-outline-secondary">Profile</a>
                                                    <a href="{{ route('registrar.students.yearly-records.index', [$rec->student, 'school_year' => $schoolYear]) }}" class="btn btn-sm btn-outline-primary">Yearly Records</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No sections found for this school year, and there are no student records to display.</p>
                    </div>
                @endif
            @endif
        </div>

        <!-- Teachers Tab -->
        <div class="tab-pane fade" id="teachers" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Teacher Records</h5>
                </div>
                <div class="card-body">
                    @if($teacherRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Teacher Name</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Subjects</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacherRecords as $record)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        {{ substr($record->teacher->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $record->teacher->name }}</strong><br>
                                                        <small class="text-muted">{{ $record->teacher->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $record->department ?? 'Not Assigned' }}</td>
                                            <td>{{ $record->position ?? 'Teacher' }}</td>
                                            <td>
                                                <small>{{ $record->formatted_subjects }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $record->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('registrar.teachers.yearly-records.edit', [$record->teacher, $record]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $teacherRecords->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No teacher records found for this school year.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            <div class="row g-4">
                <!-- Students by Grade -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Students by Grade Level</h6>
                        </div>
                        <div class="card-body">
                            @foreach($yearStats['students_by_grade'] as $grade)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $grade->grade_level }}</span>
                                    <span class="badge bg-primary">{{ $grade->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Students by Status -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Students by Status</h6>
                        </div>
                        <div class="card-body">
                            @foreach($yearStats['students_by_status'] as $status)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ ucfirst($status->status) }}</span>
                                    <span class="badge bg-{{ $status->status === 'enrolled' ? 'success' : 'secondary' }}">{{ $status->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Teachers by Department -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Teachers by Department</h6>
                        </div>
                        <div class="card-body">
                            @foreach($yearStats['teachers_by_department'] as $dept)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $dept->department ?? 'Not Assigned' }}</span>
                                    <span class="badge bg-success">{{ $dept->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Teachers by Status -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Teachers by Status</h6>
                        </div>
                        <div class="card-body">
                            @foreach($yearStats['teachers_by_status'] as $status)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ ucfirst($status->status) }}</span>
                                    <span class="badge bg-{{ $status->status === 'active' ? 'success' : 'secondary' }}">{{ $status->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
