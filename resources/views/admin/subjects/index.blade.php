@extends('layouts.admin')

@section('title', 'Subject Management')

@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="card mb-4" style="border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border: none;">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between" style="padding: 1.2rem 1.5rem;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span style="font-size: 2rem; color: #2563eb; background: #f1f5fb; border-radius: 12px; padding: 0.7rem; margin-right: 1rem; display: flex; align-items: center;">
                    <i class="fas fa-book"></i>
                </span>
                <div>
                    <div style="font-size: 1.3rem; font-weight: bold; font-family: 'Poppins', sans-serif; color: #222;">Subject Management</div>
                    <div style="font-size: 0.95rem; font-family: 'Poppins', sans-serif; color: #555; font-weight: 500;">Create, edit, and manage academic subjects with DepEd curriculum structure</div>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-sm" style="font-weight: 600; border-radius: 1.5rem; font-size: 1rem; padding: 0.5rem 1.2rem;">
                    <i class="fas fa-plus me-2"></i> Create New Subject
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @include('admin.partials.semester-notice')

    <!-- Subject Statistics Cards removed as per request -->

    <!-- Subjects Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
            <form action="{{ route('admin.subjects.index') }}" method="GET" class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or code..." value="{{ request('search') }}" style="min-width: 300px; width: 350px;">
                    <select name="grade_level" class="form-select" style="width: auto;">
                        <option value="">All Grades</option>
                        <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                        <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                </div>
                <h5 class="mb-0 text-muted">
                    {{-- Displaying {{ $subjects->firstItem() }}-{{ $subjects->lastItem() }} of {{ $subjects->total() }} subjects --}}
                </h5>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle" style="border-radius: 14px; overflow: hidden;">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle text-center" style="width: 28%;">Subject Details</th>
                            <th class="align-middle text-center" style="width: 14%;">Grade Level</th>
                            <th class="align-middle text-center" style="width: 14%;">Cluster</th>
                            <th class="align-middle text-center" style="width: 14%;">Track</th>
                            <th class="align-middle text-center" style="width: 24%;">Assigned Teacher</th>
                            <th class="align-middle text-center" style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td class="align-middle">
                                    <div>
                                        <h6 class="mb-1">{{ $subject->name }}</h6>
                                        <small class="text-muted">{{ $subject->code }}</small>
                                        @if($subject->is_core_subject)
                                            <span class="badge bg-primary ms-2">Core</span>
                                        @endif
                                        @if($subject->is_master_subject)
                                            <span class="badge bg-success ms-1">Master</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    {{ $subject->grade_level ?? 'N/A' }}
                                </td>
                                <td class="align-middle text-center">
                                    {{ $subject->cluster ?? 'N/A' }}
                                </td>
                                <td class="align-middle text-center">
                                    {{ $subject->track ?? 'N/A' }}
                                </td>
                                <td class="align-middle text-center">
                                    @php $activeAssignment = $subject->teacherAssignments->first(); @endphp
                                    @if($activeAssignment && $activeAssignment->teacher)
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="text-start">
                                                <h6 class="mb-0">{{ $activeAssignment->teacher->name }}</h6>
                                                <small class="text-muted">{{ $activeAssignment->school_year ?? 'Current' }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <a href="{{ route('admin.subject-assignments.create', ['subject_id' => $subject->id]) }}"
                                               class="btn btn-outline-primary btn-sm mt-1 px-3">
                                                <i class="fas fa-plus"></i> Assign
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.subjects.edit', $subject) }}"
                                           class="btn btn-primary btn-sm px-3"
                                           title="Edit Subject">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($activeAssignment)
                                            <a href="{{ route('admin.subject-assignments.edit', $activeAssignment->id) }}"
                                               class="btn btn-success btn-sm px-3"
                                               title="Edit Assignment">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.subject-assignments.create', ['subject_id' => $subject->id]) }}"
                                               class="btn btn-warning btn-sm px-3"
                                               title="Assign Teacher">
                                                <i class="fas fa-user-plus"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm px-3"
                                                    onclick="return confirm('Are you sure you want to delete this subject?')"
                                                    title="Delete Subject">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-book fa-3x mb-3"></i>
                                    <h5>No subjects found</h5>
                                    <p class="mb-3">Start by creating your first subject to manage teacher assignments.</p>
                                    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Subject
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $subjects->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.angled-header-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(115deg, #f97316 60%, #3b82f6 60%);
    color: white;
    padding: 2.2rem 2.5rem 2.2rem 2.5rem;
    border-radius: 16px;
    margin-bottom: 2.5rem;
    box-shadow: 0 10px 30px rgba(249, 115, 22, 0.18);
    position: relative;
    overflow: hidden;
    min-height: 120px;
}
.header-left-content {
    display: flex;
    align-items: center;
}
.header-left-content .icon {
    font-size: 2.8rem;
    margin-right: 1.5rem;
    opacity: 0.92;
}
.header-left-content .title {
    font-size: 2.2rem;
    font-weight: 800;
    display: block;
    margin-bottom: 0.2rem;
    line-height: 1.1;
}
.header-left-content .subtitle {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.95;
    display: block;
}
.header-right-content {
    display: flex;
    align-items: center;
}
.angled-header-btn {
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 2rem;
    padding: 0.7rem 1.7rem;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    box-shadow: 0 2px 8px rgba(56,135,250,0.10);
    border: 1px solid rgba(255,255,255,0.25);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.angled-header-btn:hover {
    background: rgba(255,255,255,0.28);
    color: #fff;
    text-decoration: none;
}
@media (max-width: 768px) {
    .angled-header-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.2rem 1rem;
        min-height: 100px;
    }
    .header-left-content {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-left-content .icon {
        margin-bottom: 0.7rem;
        margin-right: 0;
    }
    .header-right-content {
        margin-top: 1rem;
        width: 100%;
        justify-content: flex-start;
    }
}
/* Modern Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
    overflow: hidden;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
</style>
@endpush
