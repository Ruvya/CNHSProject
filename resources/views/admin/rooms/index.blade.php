@extends('layouts.admin')
@section('title', 'Room Management')
@section('content')
<div class="container-fluid">
    <!-- Modern Angled Header Card -->
    <div class="angled-header-card mb-4">
        <div class="header-left-content">
            <span class="icon"><i class="fas fa-door-open"></i></span>
            <div>
                <span class="title">Room Management</span>
                <span class="subtitle">Manage classrooms, laboratories, and other facilities</span>
            </div>
        </div>
        <div class="header-right-content">
            <a href="{{ route('admin.rooms.create') }}" class="angled-header-btn">
                <i class="fas fa-plus me-2"></i> Add New Room
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Rooms</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rooms.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ $search }}" placeholder="Search by name, code, or location...">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Room Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type }}" {{ $type == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="1" {{ $status === '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ $status === '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Rooms</h6>
        </div>
        <div class="card-body p-0">
            @if($rooms->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Capacity</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Equipment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $room->code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $room->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $room->type)) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $room->capacity }} students</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $room->location ?? 'Not specified' }}</span>
                                    </td>
                                    <td>
                                        @if($room->is_available)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Unavailable</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($room->equipment && count($room->equipment) > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach(array_slice($room->equipment, 0, 2) as $equipment)
                                                    <span class="badge bg-light text-dark">{{ $equipment }}</span>
                                                @endforeach
                                                @if(count($room->equipment) > 2)
                                                    <span class="badge bg-secondary">+{{ count($room->equipment) - 2 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rooms.show', $room) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rooms.edit', $room) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.rooms.toggle-availability', $room) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $room->is_available ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                        title="{{ $room->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}">
                                                    <i class="fas {{ $room->is_available ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.rooms.destroy', $room) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this room?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer bg-light">
                    {{ $rooms->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-door-closed fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No rooms found</h5>
                    <p class="text-muted">Create your first room to get started.</p>
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Room
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
