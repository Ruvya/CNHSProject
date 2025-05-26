@extends('layouts.registrar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Subjects Management - FIXED</h5>
                    <span class="badge bg-success">All Admin-Created Subjects Visible</span>
                </div>
                <div class="card-body">
                    
                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $totalSubjects }}</h4>
                                    <small>Total Subjects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $grade11Count }}</h4>
                                    <small>Grade 11 Subjects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $grade12Count }}</h4>
                                    <small>Grade 12 Subjects</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $availableStrands->count() }}</h4>
                                    <small>Available Strands</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Level Filter -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="gradeLevel" class="form-label">Filter by Grade Level</label>
                            <select id="gradeLevel" class="form-select">
                                <option value="">All Grade Levels</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="strandFilter" class="form-label">Filter by Strand</label>
                            <select id="strandFilter" class="form-select">
                                <option value="">All Strands</option>
                                @foreach($availableStrands as $strand)
                                    <option value="{{ $strand }}">{{ $strand }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchSubject" class="form-label">Search Subjects</label>
                            <input type="text" id="searchSubject" class="form-control" placeholder="Search by name or code...">
                        </div>
                    </div>

                    <!-- Grade 11 Section -->
                    <div class="grade-section mb-4" data-grade="Grade 11">
                        <h4 class="mb-3 d-flex align-items-center">
                            <i class="fas fa-graduation-cap me-2 text-success"></i>Grade 11 Subjects
                            <span class="badge bg-success ms-2">{{ $grade11Count }} subjects</span>
                        </h4>
                        
                        @foreach($availableStrands as $strand)
                            @php
                                $subjects11 = $subjectsByGradeAndStrand['Grade 11'][$strand] ?? collect();
                            @endphp
                            <div class="strand-folder mb-3" data-strand="{{ $strand }}">
                                <div class="folder-header bg-light p-3 rounded cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ strtolower(str_replace('-', '', $strand)) }}11" role="button">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-folder me-2 text-primary"></i>
                                            <strong>{{ $strand }}</strong>
                                        </div>
                                        <span class="badge bg-primary">{{ $subjects11->count() }} subjects</span>
                                    </div>
                                </div>
                                <div class="collapse" id="{{ strtolower(str_replace('-', '', $strand)) }}11">
                                    <div class="card card-body">
                                        @if($subjects11->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Subject Code</th>
                                                            <th>Subject Name</th>
                                                            <th>Description</th>
                                                            <th>Units</th>
                                                            <th>Teacher</th>
                                                            <th>Track</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($subjects11 as $subject)
                                                            <tr class="subject-row" data-name="{{ strtolower($subject->name ?? $subject->subject_name ?? '') }}" data-code="{{ strtolower($subject->code ?? $subject->subject_code ?? '') }}">
                                                                <td><strong>{{ $subject->code ?? $subject->subject_code ?? 'N/A' }}</strong></td>
                                                                <td>{{ $subject->name ?? $subject->subject_name ?? 'N/A' }}</td>
                                                                <td>{{ Str::limit($subject->description ?? 'No description', 50) }}</td>
                                                                <td><span class="badge bg-info">{{ $subject->units ?? 'N/A' }}</span></td>
                                                                <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                                                <td><span class="badge bg-secondary">{{ $subject->track ?? 'N/A' }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>No subjects found for {{ $strand }} in Grade 11</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Grade 12 Section -->
                    <div class="grade-section mb-4" data-grade="Grade 12">
                        <h4 class="mb-3 d-flex align-items-center">
                            <i class="fas fa-graduation-cap me-2 text-info"></i>Grade 12 Subjects
                            <span class="badge bg-info ms-2">{{ $grade12Count }} subjects</span>
                        </h4>
                        
                        @foreach($availableStrands as $strand)
                            @php
                                $subjects12 = $subjectsByGradeAndStrand['Grade 12'][$strand] ?? collect();
                            @endphp
                            <div class="strand-folder mb-3" data-strand="{{ $strand }}">
                                <div class="folder-header bg-light p-3 rounded cursor-pointer" data-bs-toggle="collapse" data-bs-target="#{{ strtolower(str_replace('-', '', $strand)) }}12" role="button">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-folder me-2 text-primary"></i>
                                            <strong>{{ $strand }}</strong>
                                        </div>
                                        <span class="badge bg-primary">{{ $subjects12->count() }} subjects</span>
                                    </div>
                                </div>
                                <div class="collapse" id="{{ strtolower(str_replace('-', '', $strand)) }}12">
                                    <div class="card card-body">
                                        @if($subjects12->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Subject Code</th>
                                                            <th>Subject Name</th>
                                                            <th>Description</th>
                                                            <th>Units</th>
                                                            <th>Teacher</th>
                                                            <th>Track</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($subjects12 as $subject)
                                                            <tr class="subject-row" data-name="{{ strtolower($subject->name ?? $subject->subject_name ?? '') }}" data-code="{{ strtolower($subject->code ?? $subject->subject_code ?? '') }}">
                                                                <td><strong>{{ $subject->code ?? $subject->subject_code ?? 'N/A' }}</strong></td>
                                                                <td>{{ $subject->name ?? $subject->subject_name ?? 'N/A' }}</td>
                                                                <td>{{ Str::limit($subject->description ?? 'No description', 50) }}</td>
                                                                <td><span class="badge bg-info">{{ $subject->units ?? 'N/A' }}</span></td>
                                                                <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                                                <td><span class="badge bg-secondary">{{ $subject->track ?? 'N/A' }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>No subjects found for {{ $strand }} in Grade 12</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- All Subjects Section (Fallback) -->
                    <div class="all-subjects-section mb-4">
                        <h4 class="mb-3 d-flex align-items-center">
                            <i class="fas fa-list me-2 text-warning"></i>All Subjects (Unfiltered View)
                            <span class="badge bg-warning ms-2">{{ $allSubjects->count() }} subjects</span>
                        </h4>
                        
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Complete Subject List (Including Admin-Created Subjects)</h6>
                            </div>
                            <div class="card-body">
                                @if($allSubjects->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Grade Level</th>
                                                    <th>Strand</th>
                                                    <th>Track</th>
                                                    <th>Units</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allSubjects as $subject)
                                                    <tr class="subject-row" data-name="{{ strtolower($subject->name ?? $subject->subject_name ?? '') }}" data-code="{{ strtolower($subject->code ?? $subject->subject_code ?? '') }}">
                                                        <td>{{ $subject->id }}</td>
                                                        <td><strong>{{ $subject->code ?? $subject->subject_code ?? 'N/A' }}</strong></td>
                                                        <td>{{ $subject->name ?? $subject->subject_name ?? 'N/A' }}</td>
                                                        <td><span class="badge bg-primary">{{ $subject->grade_level ?? 'N/A' }}</span></td>
                                                        <td><span class="badge bg-secondary">{{ $subject->strand ?? 'N/A' }}</span></td>
                                                        <td><span class="badge bg-info">{{ $subject->track ?? 'N/A' }}</span></td>
                                                        <td><span class="badge bg-success">{{ $subject->units ?? 'N/A' }}</span></td>
                                                        <td>{{ $subject->teacher->name ?? 'Not Assigned' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                        <h5>No Subjects Found</h5>
                                        <p>There are no subjects in the database. Please contact the administrator to add subjects.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Quick Actions</h6>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('registrar.dashboard') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                        </a>
                                        <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-success">
                                            <i class="fas fa-users me-1"></i>Manage Students
                                        </a>
                                        <a href="/registrar-subjects-diagnostic" class="btn btn-outline-info">
                                            <i class="fas fa-stethoscope me-1"></i>Diagnostic Tool
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}
.folder-header:hover {
    background-color: #e9ecef !important;
}
.subject-row {
    transition: background-color 0.2s;
}
.subject-row:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
