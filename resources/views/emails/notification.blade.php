<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification_type }} - {{ $app_name }}</title>
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
            border-bottom: 2px solid #ffc107;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ffc107;
            margin: 0;
            font-size: 24px;
        }
        .notification-badge {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin: 15px 0;
            font-weight: bold;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .notification-content {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .action-button:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .urgent {
            border-left-color: #dc3545 !important;
            background-color: #f8d7da !important;
            border-color: #f5c6cb !important;
        }
        .urgent .notification-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $app_name }}</h1>
            <div class="notification-badge {{ strpos(strtolower($notification_type), 'urgent') !== false ? 'urgent' : '' }}">
                📢 {{ $notification_type }}
            </div>
        </div>
        
        <div class="content">
            <h2>Notification</h2>
            
            <div class="notification-content {{ strpos(strtolower($notification_type), 'urgent') !== false ? 'urgent' : '' }}">
                <div class="icon">
                    @if(strpos(strtolower($notification_type), 'urgent') !== false)
                        ⚠️
                    @elseif(strpos(strtolower($notification_type), 'success') !== false)
                        ✅
                    @elseif(strpos(strtolower($notification_type), 'warning') !== false)
                        ⚠️
                    @elseif(strpos(strtolower($notification_type), 'info') !== false)
                        ℹ️
                    @else
                        📢
                    @endif
                </div>
                
                <p><strong>{{ $notification_type }}</strong></p>
                
                <div style="margin: 20px 0;">
                    {!! nl2br(e($notification_message)) !!}
                </div>
            </div>
            
            @if($action_url)
            <div style="text-align: center;">
                <a href="{{ $action_url }}" class="action-button">Take Action</a>
            </div>
            @endif
            
            <p>This notification was sent to keep you informed about important updates in the {{ $app_name }} system.</p>
        </div>
        
        <div class="footer">
            <p>This email was sent from {{ $app_name }} system.</p>
            <p>If you received this email by mistake, please ignore it.</p>
            <p><small>Notification sent on {{ date('F j, Y \a\t g:i A') }}</small></p>
        </div>
    </div>
</body>
</html>
