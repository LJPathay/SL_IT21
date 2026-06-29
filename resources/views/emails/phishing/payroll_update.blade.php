<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Urgent: Update Payroll Information</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-top: 5px solid #4f46e5; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .content { padding: 40px; }
        .title { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 20px; }
        .body-text { font-size: 15px; color: #4b5563; line-height: 1.6; margin-bottom: 30px; }
        .btn { display: inline-block; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 12px 24px; font-weight: 600; font-size: 15px; border-radius: 4px; }
        .footer { background-color: #f9fafb; padding: 25px; font-size: 11px; color: #9ca3af; text-align: center; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="title">Action Required: Verify Direct Deposit Info</div>
            <div class="body-text">
                Dear {{ $user->name }},<br><br>
                This email is to notify you that the HR & Payroll portal has been updated. To prevent delays in your next direct deposit payment schedule, you are required to verify your bank account and routing details within 24 hours.
            </div>
            <div>
                <a href="{{ route('phishing.track-click', $campaign) }}" class="btn">Update Direct Deposit Details</a>
            </div>
        </div>
        <div class="footer">
            Internal HR & Payroll System. Confidential Notification.
        </div>
    </div>
</body>
</html>
