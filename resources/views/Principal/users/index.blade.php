@extends('Principal.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        User Management
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_students'] }}</h3>
                                    <p>Total Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['total_teachers'] }}</h3>
                                    <p>Total Teachers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['total_admins'] }}</h3>
                                    <p>Total Admins</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['total_registrars'] }}</h3>
                                    <p>Total Registrars</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-cog"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="{{ route('principal.users') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>User Type</label>
                                            <select name="type" class="form-control">
                                                <option value="all" {{ $userType == 'all' ? 'selected' : '' }}>All Users</option>
                                                <option value="students" {{ $userType == 'students' ? 'selected' : '' }}>Students Only</option>
                                                <option value="teachers" {{ $userType == 'teachers' ? 'selected' : '' }}>Teachers Only</option>
                                                <option value="admins" {{ $userType == 'admins' ? 'selected' : '' }}>Admins Only</option>
                                                <option value="registrars" {{ $userType == 'registrars' ? 'selected' : '' }}>Registrars Only</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Search</label>
                                            <input type="text" name="search" class="form-control" 
                                                   placeholder="Search by name, email, or ID..." 
                                                   value="{{ $search }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Students Section -->
                    @if($userType == 'all' || $userType == 'students')
                    @if($students->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-user-graduate mr-2"></i>
                                Students ({{ $students->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Grade Level</th>
                                            <th>Track</th>
                                            <th>Section</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                        <tr>
                                            <td><strong>{{ $student->student_id }}</strong></td>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>
                                                <span class="badge badge-primary">{{ $student->grade_level }}</span>
                                            </td>
                                            <td>
                                                @if($student->track)
                                                    <span class="badge badge-info">{{ $student->track }}</span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->section ?? 'Not assigned' }}</td>
                                            <td>{{ $student->email ?? 'Not set' }}</td>
                                            <td>
                                                @if($student->last_login_at)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('principal.users.students.show', $student) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Teachers Section -->
                    @if($userType == 'all' || $userType == 'teachers')
                    @if($teachers->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Teachers ({{ $teachers->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Track</th>
                                            <th>Cluster</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teachers as $teacher)
                                        <tr>
                                            <td><strong>{{ $teacher->name }}</strong></td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>
                                                @if($teacher->track)
                                                    <span class="badge badge-info">{{ $teacher->track }}</span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $teacher->cluster ?? 'Not assigned' }}</td>
                                            <td>{{ $teacher->contact_number ?? 'Not set' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $teacher->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($teacher->status ?? 'active') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('principal.users.teachers.show', $teacher) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Admins Section -->
                    @if($userType == 'all' || $userType == 'admins')
                    @if($admins->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-user-shield mr-2"></i>
                                Administrators ({{ $admins->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Last Login</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($admins as $admin)
                                        <tr>
                                            <td><strong>{{ $admin->name }}</strong></td>
                                            <td>{{ $admin->email }}</td>
                                            <td><span class="badge badge-danger">Administrator</span></td>
                                            <td>{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Registrars Section -->
                    @if($userType == 'all' || $userType == 'registrars')
                    @if($registrars->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-user-cog mr-2"></i>
                                Registrars ({{ $registrars->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrars as $registrar)
                                        <tr>
                                            <td><strong>{{ $registrar->first_name }} {{ $registrar->last_name }}</strong></td>
                                            <td>{{ $registrar->email }}</td>
                                            <td>{{ $registrar->phone ?? 'Not set' }}</td>
                                            <td>{{ $registrar->address ?? 'Not set' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if($students->count() == 0 && $teachers->count() == 0 && $admins->count() == 0 && $registrars->count() == 0)
                    <div class="text-center">
                        <p class="text-muted">No users found matching your criteria.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
