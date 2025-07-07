<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $app_name }}</title>
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        .welcome-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            margin-bottom: 30px;
        }
        .user-info {
            background-color: #e8f5e8;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .user-type {
            color: #28a745;
            font-weight: bold;
            text-transform: capitalize;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .features {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .features ul {
            margin: 0;
            padding-left: 20px;
        }
        .features li {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $app_name }}</h1>
            <div class="welcome-badge">Welcome!</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user_name }}!</h2>
            
            <p>We're excited to welcome you to {{ $app_name }}! Your account has been successfully created.</p>
            
            <div class="user-info">
                <p><strong>Account Type:</strong> <span class="user-type">{{ $user_type }}</span></p>
                <p><strong>Name:</strong> {{ $user_name }}</p>
            </div>
            
            <div class="features">
                <h3>What you can do with your {{ $user_type }} account:</h3>
                @if($user_type === 'student')
                <ul>
                    <li>View your grades and academic progress</li>
                    <li>Access your class schedule</li>
                    <li>View announcements and updates</li>
                    <li>Manage your profile information</li>
                </ul>
                @elseif($user_type === 'teacher')
                <ul>
                    <li>Manage your classes and students</li>
                    <li>Input and update student grades</li>
                    <li>View class lists and student information</li>
                    <li>Access teaching resources</li>
                </ul>
                @elseif($user_type === 'admin')
                <ul>
                    <li>Manage user accounts</li>
                    <li>Generate student credentials</li>
                    <li>Oversee system operations</li>
                    <li>Access administrative tools</li>
                </ul>
                @elseif($user_type === 'registrar')
                <ul>
                    <li>Manage student records</li>
                    <li>Handle subject assignments</li>
                    <li>Process enrollments</li>
                    <li>Generate academic reports</li>
                </ul>
                @elseif($user_type === 'principal')
                <ul>
                    <li>Manage school announcements</li>
                    <li>Oversee teacher assignments</li>
                    <li>Access school-wide reports</li>
                    <li>Manage school events</li>
                </ul>
                @endif
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
            
            <p>Welcome aboard!</p>
        </div>
        
        <div class="footer">
            <p>This email was sent from {{ $app_name }} system.</p>
            <p>If you received this email by mistake, please ignore it.</p>
        </div>
    </div>
</body>
</html>
