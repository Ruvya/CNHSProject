@extends('layouts.student')

@section('title', 'Profile')

@section('styles')
    <style>
        /* Override main-content from layout to fix positioning */
        .main-content {
            padding: 2rem !important;
            background: #ffffff !important;
            min-height: calc(100vh - 80px) !important;
            position: relative;
            overflow-x: hidden;
            margin-left: 250px !important;
        }

        /* Profile Header */
        .profile-header {
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            color: #2563eb;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .last-updated {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Profile Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        /* Profile Cards */
        .profile-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow:
                0 4px 20px rgba(37, 99, 235, 0.08),
                0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 8px 30px rgba(37, 99, 235, 0.15),
                0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-header i {
            font-size: 1.5rem;
        }

        .card-header h2 {
            font-size: 1.2rem;
            margin: 0;
        }

        .card-content {
            padding: 1.5rem;
        }

        /* Profile Image Container */
        .profile-image-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 2rem;
        }

        .large-profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(37, 99, 235, 0.1);
        }

        .edit-photo-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .edit-photo-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .info-item p {
            color: #1f2937;
            font-weight: 500;
            font-size: 1rem;
        }

        /* Form Styles */
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
            }
        }

        @media (max-width: 768px) {
            .profile-grid {
                gap: 1rem;
            }

            .profile-card {
                border-radius: 15px;
            }

            .card-content {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="profile-header">
    <h1>Student Profile</h1>
    @php
        $student = Auth::guard('student')->user();
    @endphp
    <p class="last-updated">Last updated: <span id="lastUpdated">{{ $student ? $student->updated_at->diffForHumans() : 'N/A' }}</span></p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<button id="editProfileBtn" class="btn btn-primary mb-3">Edit Profile</button>

<form id="editProfileForm" method="POST" action="{{ route('student.profile.update') }}" style="display:none; max-width: 700px; margin-bottom: 2rem;">
    @csrf
    @method('PUT')
    <div class="profile-grid">
        <!-- Academic Information -->
        <div class="profile-card academic-info">
            <div class="card-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Academic Information</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label for="advisor">Adviser Name</label>
                        <input type="text" class="form-control" name="advisor" id="advisor" value="{{ old('advisor', $student->advisor) }}">
                    </div>
                    <div class="info-item">
                        <label for="track">Track</label>
                        <input type="text" class="form-control" name="track" id="track" value="{{ old('track', $student->track) }}">
                    </div>
                    <div class="info-item">
                        <label for="strand">Strand</label>
                        <input type="text" class="form-control" name="strand" id="strand" value="{{ old('strand', $student->strand) }}">
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact Information -->
        <div class="profile-card contact-info">
            <div class="card-header">
                <i class="fas fa-address-card"></i>
                <h2>Contact Information</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label for="province">Province</label>
                        <input type="text" class="form-control" name="province" id="province" value="{{ old('province', $student->province) }}">
                    </div>
                    <div class="info-item">
                        <label for="municipality">Municipality</label>
                        <input type="text" class="form-control" name="municipality" id="municipality" value="{{ old('municipality', $student->municipality) }}">
                    </div>
                    <div class="info-item">
                        <label for="barangay">Barangay</label>
                        <input type="text" class="form-control" name="barangay" id="barangay" value="{{ old('barangay', $student->barangay) }}">
                    </div>
                    <div class="info-item">
                        <label for="permanent_address">Permanent Address (ZIP code)</label>
                        <input type="text" class="form-control" name="permanent_address" id="permanent_address" value="{{ old('permanent_address', $student->permanent_address) }}">
                    </div>
                    <div class="info-item">
                        <label for="phone">Contact Number</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', $student->phone) }}">
                    </div>
                    <div class="info-item">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $student->email) }}">
                    </div>
                    <div class="info-item full-width">
                        <label for="social_media">Social Media Accounts (optional)</label>
                        <input type="text" class="form-control" name="social_media" id="social_media" value="{{ old('social_media', $student->social_media) }}" placeholder="Facebook, Twitter, etc.">
                    </div>
                </div>
            </div>
        </div>
        <!-- Emergency Contact -->
        <div class="profile-card emergency-info">
            <div class="card-header">
                <i class="fas fa-phone-alt"></i>
                <h2>Emergency Contact</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label for="emergency_name">Contact Person</label>
                        <input type="text" class="form-control" name="emergency_name" id="emergency_name" value="{{ old('emergency_name', $student->emergency_name) }}">
                    </div>
                    <div class="info-item">
                        <label for="emergency_relationship">Relationship</label>
                        <input type="text" class="form-control" name="emergency_relationship" id="emergency_relationship" value="{{ old('emergency_relationship', $student->emergency_relationship) }}">
                    </div>
                    <div class="info-item">
                        <label for="emergency_phone">Contact Number</label>
                        <input type="text" class="form-control" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone', $student->emergency_phone) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align:right; margin-top: 1rem;">
        <button type="submit" class="btn btn-success">Save Changes</button>
    </div>
</form>

<div class="profile-grid">
    <!-- Personal Information Card -->
    <div class="profile-card personal-info">
        <div class="card-header">
            <i class="fas fa-user-circle"></i>
            <h2>Personal Information</h2>
        </div>
        <div class="card-content">
            <div class="profile-image-container">
                <img src="{{ $student && $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/photo.jpg') }}" alt="Profile Picture" class="large-profile-pic">
                <form action="{{ route('student.profile.upload') }}" method="POST" enctype="multipart/form-data" id="profilePictureForm" style="display: none;">
                    @csrf
                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" onchange="this.form.submit()">
                </form>
                <button class="edit-photo-btn" onclick="document.getElementById('profilePictureInput').click()">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <p>{{ $student ? $student->first_name . ' ' . $student->last_name : 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Gender</label>
                    <p>{{ $student ? ($student->gender ?? 'Not set') : 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Student ID</label>
                    <p>{{ $student ? $student->student_id : 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p>{{ $student ? $student->email : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Information Card -->
    <div class="profile-card academic-info">
        <div class="card-header">
            <i class="fas fa-graduation-cap"></i>
            <h2>Academic Information</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item"><label>Adviser Name</label><p>{{ $student->advisor ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Track</label><p>{{ $student->track ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Strand</label><p>{{ $student->strand ?? 'Not set' }}</p></div>
            </div>
        </div>
    </div>

    <!-- Contact Information Card -->
    <div class="profile-card contact-info">
        <div class="card-header">
            <i class="fas fa-address-card"></i>
            <h2>Contact Information</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item"><label>Province</label><p>{{ $student->province ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Municipality</label><p>{{ $student->municipality ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Barangay</label><p>{{ $student->barangay ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Permanent Address (ZIP code)</label><p>{{ $student->permanent_address ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Contact Number</label><p>{{ $student->phone ?? 'Not set' }}</p></div>
                <div class="info-item"><label>Email</label><p>{{ $student->email ?? 'Not set' }}</p></div>
                <div class="info-item full-width"><label>Social Media Accounts</label><p>{{ $student->social_media ?? 'Not set' }}</p></div>
            </div>
        </div>
    </div>

    <!-- Emergency Contact Card -->
    <div class="profile-card emergency-info">
        <div class="card-header">
            <i class="fas fa-phone-alt"></i>
            <h2>Emergency Contact</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Contact Person</label>
                    <p>{{ $student->emergency_name ?? 'Not set' }}</p>
                </div>
                <div class="info-item">
                    <label>Relationship</label>
                    <p>{{ $student->emergency_relationship ?? 'Not set' }}</p>
                </div>
                <div class="info-item">
                    <label>Contact Number</label>
                    <p>{{ $student->emergency_phone ?? 'Not set' }}</p>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
<script>
    // Profile picture preview
    document.getElementById('profilePictureInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.large-profile-pic').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Toggle edit form
    document.getElementById('editProfileBtn').onclick = function() {
        var form = document.getElementById('editProfileForm');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        this.textContent = (form.style.display === 'none') ? 'Edit Profile' : 'Cancel Edit';
    };
</script>
@endsection