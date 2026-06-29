<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Netflix Update Payment Method</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; background-color: #141414; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 4px; overflow: hidden; }
        .header { background-color: #000000; padding: 25px; text-align: left; }
        .header h1 { color: #e50914; margin: 0; font-size: 32px; font-weight: bold; letter-spacing: -1px; }
        .content { padding: 40px; }
        .title { font-size: 22px; font-weight: bold; color: #222222; margin-bottom: 20px; }
        .body-text { font-size: 15px; color: #555555; line-height: 1.6; margin-bottom: 30px; }
        .btn { display: inline-block; background-color: #e50914; color: #ffffff !important; text-decoration: none; padding: 14px 28px; font-weight: bold; font-size: 15px; border-radius: 4px; }
        .footer { background-color: #f3f3f3; padding: 20px; font-size: 11px; color: #8c8c8c; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NETFLIX</h1>
        </div>
        <div class="content">
            <div class="title">Please update your payment details</div>
            <div class="body-text">
                Hi {{ $user->name }},<br><br>
                We were unable to process your subscription renewal payment for this month. 
                Please update your credit card or billing details within 48 hours to prevent the immediate suspension of your streaming services.
            </div>
            <div>
                <a href="{{ route('phishing.track-click', $campaign) }}" class="btn">Update Payment Method</a>
            </div>
        </div>
        <div class="footer">
            Netflix Services, Inc., 100 Winchester Circle, Los Gatos, CA 95032, USA
        </div>
    </div>
</body>
</html>
