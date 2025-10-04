@extends('layouts.registrar')

@section('title', 'School Year ' . $schoolYear . ' Records')

@section('content')
<div class="container-fluid">
    <!-- Header (match Admin User Management style) -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">School Year {{ $schoolYear }}</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Student and teacher records for academic year {{ $schoolYear }}</div>
                </div>
            </div>
            <div class="mb-2 mb-md-0">
                <a href="{{ route('registrar.yearly-records.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Overview
                </a>
            </div>
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
                                <span class="ms-2 text-muted">{{ $section->grade_level }} • {{ $section->track }} {{ $section->strand ? ' / '.$section->strand : '' }}</span>
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
