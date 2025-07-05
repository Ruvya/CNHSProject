<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $appName }} - Teacher Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 25px;
            margin-bottom: 35px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 30px 25px 25px 25px;
        }
        .header h1 {
            color: white;
            margin: 10px 0 0 0;
            font-size: 28px;
            font-weight: 700;
        }
        .school-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px auto;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .welcome-badge {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .content {
            margin-bottom: 35px;
        }
        .credentials-box {
            background: linear-gradient(135deg, #dbeafe, #f8f9fa);
            border: 2px solid #1e3a8a;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }
        .credentials-box h3 {
            color: #1e3a8a;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .credential-item {
            background-color: #ffffff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #1e3a8a;
            text-align: left;
        }
        .credential-label {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 16px;
            color: #212529;
            font-weight: 500;
            margin-top: 5px;
            word-break: break-all;
        }
        .password-value {
            font-family: 'Courier New', monospace;
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            display: inline-block;
            margin-top: 5px;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 25px 0;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .login-button:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .security-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #ffc107;
        }
        .security-notice h4 {
            color: #856404;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .security-notice p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }
        .instructions {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #17a2b8;
        }
        .instructions h4 {
            color: #0c5460;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .instructions ol {
            color: #0c5460;
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 25px;
            margin-top: 35px;
            font-size: 13px;
            color: #6c757d;
        }
        .support-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .support-info h4 {
            color: #495057;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .support-info p {
            color: #6c757d;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="school-logo">
                CNHS
            </div>
            <h1>Calingcaging National High School</h1>
            <div class="welcome-badge">Welcome to Our Team!</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $teacherName }}!</h2>
            
            <p>Welcome to Calingcaging National High School! We're excited to have you join our educational team. Your teacher account has been successfully created by our administration.</p>
            
            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                
                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $teacherEmail }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">
                        <span class="password-value">{{ $password }}</span>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="login-button">
                    🚀 Login to Your Account
                </a>
            </div>

            <div class="security-notice">
                <h4>🔒 Important Security Information</h4>
                <p><strong>For your security, please:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Change your password after your first login</li>
                    <li>Do not share your login credentials with anyone</li>
                    <li>Always log out when using shared computers</li>
                    <li>Contact support immediately if you suspect unauthorized access</li>
                </ul>
            </div>

            <div class="instructions">
                <h4>📋 First Login Instructions</h4>
                <ol>
                    <li>Click the "Login to Your Account" button above</li>
                    <li>Enter your email address and the provided password</li>
                    <li><strong>You will be automatically redirected to change your password</strong></li>
                    <li>Create a new secure password that you can remember</li>
                    <li>After changing your password, you'll access the teacher dashboard</li>
                    <li>Complete your teacher profile information</li>
                    <li>Explore the teacher dashboard and available features</li>
                </ol>
            </div>

            <div class="support-info">
                <h4>📞 Need Help?</h4>
                <p>If you have any questions or need assistance, please contact our support team:</p>
                <p><strong>Email:</strong> {{ $supportEmail }}</p>
                <p><strong>Response Time:</strong> Within 24 hours</p>
            </div>
            
            <p>We look forward to working with you and supporting your teaching journey at Calingcaging National High School!</p>

            <p>Best regards,<br>
            <strong>CNHS Administration Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This email was sent from Calingcaging National High School system.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p><small>Email sent on {{ date('F j, Y \a\t g:i A') }}</small></p>
        </div>
    </div>
</body>
</html>
