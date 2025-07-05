<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Assignment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background-color: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .school-name {
            color: #007bff;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .school-subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        .greeting {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .assignment-details {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            min-width: 140px;
            margin-right: 10px;
        }
        .detail-value {
            color: #333;
            flex: 1;
        }
        .schedule-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .schedule-title {
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 10px;
        }
        .schedule-item {
            margin-bottom: 5px;
            padding: 5px 0;
            border-bottom: 1px solid #bbdefb;
        }
        .schedule-item:last-child {
            border-bottom: none;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .notes-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .notes-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 12px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">CNHS</div>
            <h1 class="school-name">Calingcaging National High School</h1>
            <p class="school-subtitle">Subject Assignment Notification</p>
        </div>

        <!-- Greeting -->
        <div class="greeting">
            Dear {{ $teacherName }},
        </div>

        <!-- Main Message -->
        <p>We are pleased to inform you that you have been assigned to teach a new subject for the current academic period. Please review the assignment details below:</p>

        <!-- Assignment Details -->
        <div class="assignment-details">
            <h3 style="margin-top: 0; color: #007bff;">Assignment Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Subject:</span>
                <span class="detail-value"><strong>{{ $subjectName }}</strong></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Subject Code:</span>
                <span class="detail-value">{{ $subjectCode }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Grade Level:</span>
                <span class="detail-value">{{ $gradeLevel }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Track:</span>
                <span class="detail-value">{{ $track }}</span>
            </div>
            
            @if($strand)
            <div class="detail-row">
                <span class="detail-label">Strand:</span>
                <span class="detail-value">{{ $strand }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">School Year:</span>
                <span class="detail-value">{{ $schoolYear }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Grading Period:</span>
                <span class="detail-value">{{ $gradingPeriod }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Assignment Date:</span>
                <span class="detail-value">{{ $assignmentDate->format('F d, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Assigned By:</span>
                <span class="detail-value">{{ $registrarName }}</span>
            </div>
        </div>

        <!-- Schedule Information -->
        @if($schedule && count($schedule) > 0)
        <div class="schedule-info">
            <div class="schedule-title">Teaching Schedule</div>
            @foreach($schedule as $slot)
                @if(isset($slot['day']) && isset($slot['start_time']) && isset($slot['end_time']))
                <div class="schedule-item">
                    <strong>{{ $slot['day'] }}</strong>: 
                    {{ date('g:i A', strtotime($slot['start_time'])) }} - 
                    {{ date('g:i A', strtotime($slot['end_time'])) }}
                </div>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Notes Section -->
        @if($notes)
        <div class="notes-section">
            <div class="notes-title">Additional Notes</div>
            <p>{{ $notes }}</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ $dashboardUrl }}" class="btn btn-primary">Access Teacher Dashboard</a>
            <a href="{{ $loginUrl }}" class="btn btn-secondary">Login Portal</a>
        </div>

        <!-- Instructions -->
        <p>To access your teaching materials and manage your classes, please log in to the teacher portal using your existing credentials. If you have any questions about this assignment, please contact the registrar's office.</p>

        <p><strong>Important:</strong> Please confirm receipt of this assignment by logging into your teacher dashboard within the next 3 days.</p>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated notification from the Calingcaging National High School Management System.</p>
            <p>If you have any questions, please contact the registrar's office.</p>
            <p>&copy; {{ date('Y') }} Calingcaging National High School. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
