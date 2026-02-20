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
            /* margin-left: 250px !important; */
        }

        /* Profile Header - Match Dashboard Banner */
        .profile-header {
            margin-bottom: 1.5rem;
            padding: 1.75rem 2rem;
            background: linear-gradient(135deg, #ffa62b 0%, #ff7b2c 100%);
            border-radius: 20px;
            box-shadow: 0 10px 24px rgba(16, 24, 40, 0.08);
            position: relative;
            overflow: hidden;
            color: #ffffff;
        }
        .profile-header::before{
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 65%;
            background: linear-gradient(135deg, #3961e7 0%, #2f50d1 100%);
            clip-path: polygon(22% 0, 100% 0, 100% 100%, 0 100%);
            opacity: 1;
            z-index: 1;
        }
        .profile-header .header-content{
            position: relative;
            z-index: 2;
        }

        .profile-header h1 {
            margin-top: 3%;
            font-size: 2.5rem;
            margin-bottom: 0.4rem;
            color: white;
            -webkit-background-clip: unset;
            -webkit-text-fill-color: unset;
            background-clip: unset;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .last-updated {
            color: white;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Profile Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        /* Profile Cards */
        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow:
                0 2px 12px rgba(37, 99, 235, 0.06),
                0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow:
                0 4px 20px rgba(37, 99, 235, 0.12),
                0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header i {
            font-size: 1.25rem;
        }

        .card-header h2 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 600;
        }

        .card-content {
            padding: 1.25rem;
        }

        /* Profile Image Container */
        .profile-image-container {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto 1.5rem;
        }

        .large-profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(37, 99, 235, 0.1);
        }

        .edit-photo-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 12px rgba(37, 99, 235, 0.25);
        }

        .edit-photo-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item label {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .info-item p {
            color: #1f2937;
            font-weight: 500;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Form Styles */
        .form-control {
            width: 100%;
            padding: 0.65rem;
            border: 1px solid rgba(37, 99, 235, 0.15);
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #fafbff;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
            background: #ffffff;
        }

        .btn {
            padding: 0.65rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.2);
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        /* Alert Styles */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-color: rgba(5, 150, 105, 0.2);
            color: #047857;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-color: rgba(37, 99, 235, 0.2);
            color: #1d4ed8;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .profile-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
            }

            .profile-header {
                padding: 1rem;
            }

            .profile-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .profile-grid {
                gap: 0.75rem;
            }

            .profile-card {
                border-radius: 12px;
            }

            .card-content {
                padding: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="profile-header">
    <div class="header-content">
        <h1>Student Profile</h1>
        @php
            $student = Auth::guard('student')->user();
        @endphp
        <p class="last-updated">Last updated: <span id="lastUpdated">{{ $student ? $student->updated_at->diffForHumans() : 'N/A' }}</span></p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<!-- Profile editing is restricted to registrars only -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Notice:</strong> Profile information can only be edited by the registrar's office.
    Please contact the registrar for any changes to your information.
</div>

<!-- Edit form removed - students cannot edit their profiles -->

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
                <!-- Profile picture editing removed - students cannot edit their profiles -->
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <p>{{ $student ? $student->first_name . ' ' . $student->last_name : 'N/A' }}</p>
                </div>
                @if($student && $student->gender)
                <div class="info-item">
                    <label>Gender</label>
                    <p>{{ $student->gender }}</p>
                </div>
                @endif
                <div class="info-item">
                    <label>Student ID</label>
                    <p>{{ $student ? $student->student_id : 'N/A' }}</p>
                </div>
                @if($student && $student->email)
                <div class="info-item">
                    <label>Email</label>
                    <p>{{ $student->email }}</p>
                </div>
                @endif
                @if($student && $student->date_of_birth)
                <div class="info-item">
                    <label>Date of Birth</label>
                    <p>{{ $student->date_of_birth->format('F j, Y') }}</p>
                </div>
                @endif
                @if($student && $student->place_of_birth)
                <div class="info-item">
                    <label>Place of Birth</label>
                    <p>{{ $student->place_of_birth }}</p>
                </div>
                @endif
                @if($student && $student->nationality)
                <div class="info-item">
                    <label>Nationality</label>
                    <p>{{ $student->nationality }}</p>
                </div>
                @endif
                @if($student && $student->religion)
                <div class="info-item">
                    <label>Religion</label>
                    <p>{{ $student->religion }}</p>
                </div>
                @endif
                @if($student && $student->civil_status)
                <div class="info-item">
                    <label>Civil Status</label>
                    <p>{{ $student->civil_status }}</p>
                </div>
                @endif
                @if($student && $student->contact_number)
                <div class="info-item">
                    <label>Contact Number</label>
                    <p>{{ $student->contact_number }}</p>
                </div>
                @endif
                @if($student && $student->lrn)
                <div class="info-item">
                    <label>LRN (Learner Reference Number)</label>
                    <p>{{ $student->lrn }}</p>
                </div>
                @endif
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
                @if($student && $student->grade_level)
                <div class="info-item">
                    <label>Grade Level</label>
                    <p>{{ $student->grade_level }}</p>
                </div>
                @endif
                @if($student && $student->section)
                <div class="info-item">
                    <label>Section</label>
                    <p>{{ $student->section }}</p>
                </div>
                @endif
                @if($student && $student->advisor)
                <div class="info-item">
                    <label>Adviser Name</label>
                    <p>{{ $student->advisor }}</p>
                </div>
                @endif
                @if($student && $student->track)
                <div class="info-item">
                    <label>Track</label>
                    <p>{{ $student->track }}</p>
                </div>
                @endif
                @if($student && $student->strand)
                <div class="info-item">
                    <label>Strand</label>
                    <p>{{ $student->strand }}</p>
                </div>
                @endif
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
                @if($student && $student->province)
                <div class="info-item"><label>Province</label><p>{{ $student->province }}</p></div>
                @endif
                @if($student && $student->municipality)
                <div class="info-item"><label>Municipality</label><p>{{ $student->municipality }}</p></div>
                @endif
                @if($student && $student->barangay)
                <div class="info-item"><label>Barangay</label><p>{{ $student->barangay }}</p></div>
                @endif
                @if($student && $student->permanent_address)
                <div class="info-item"><label>Permanent Address (ZIP code)</label><p>{{ $student->permanent_address }}</p></div>
                @endif
                @if($student && $student->phone)
                <div class="info-item"><label>Contact Number</label><p>{{ $student->phone }}</p></div>
                @endif
                @if($student && $student->email)
                <div class="info-item"><label>Email</label><p>{{ $student->email }}</p></div>
                @endif
                @if($student && $student->social_media)
                <div class="info-item full-width"><label>Social Media Accounts</label><p>{{ $student->social_media }}</p></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Parent/Guardian Information Card -->
    @if($student && ($student->parent_name || $student->parent_contact))
    <div class="profile-card parent-info">
        <div class="card-header">
            <i class="fas fa-users"></i>
            <h2>Parent/Guardian Information</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                @if($student && $student->parent_name)
                <div class="info-item">
                    <label>Parent/Guardian Name</label>
                    <p>{{ $student->parent_name }}</p>
                </div>
                @endif
                @if($student && $student->parent_contact)
                <div class="info-item">
                    <label>Parent/Guardian Contact</label>
                    <p>{{ $student->parent_contact }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Emergency Contact Card -->
    @if($student && ($student->emergency_name || $student->emergency_relationship || $student->emergency_phone))
    <div class="profile-card emergency-info">
        <div class="card-header">
            <i class="fas fa-phone-alt"></i>
            <h2>Emergency Contact</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                @if($student && $student->emergency_name)
                <div class="info-item">
                    <label>Contact Person</label>
                    <p>{{ $student->emergency_name }}</p>
                </div>
                @endif
                @if($student && $student->emergency_relationship)
                <div class="info-item">
                    <label>Relationship</label>
                    <p>{{ $student->emergency_relationship }}</p>
                </div>
                @endif
                @if($student && $student->emergency_phone)
                <div class="info-item">
                    <label>Contact Number</label>
                    <p>{{ $student->emergency_phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    </div>
@endsection

@push('scripts')
<script>
    // Profile editing functionality removed - students cannot edit their profiles
    console.log('Student profile is view-only. Contact registrar for changes.');
</script>
@endpush