<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DHL Shipment Notification</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f6f6f6; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-top: 6px solid #ffcc00; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { background-color: #d40511; padding: 20px; text-align: left; }
        .header h1 { color: #ffcc00; margin: 0; font-size: 28px; font-weight: bold; }
        .content { padding: 40px; }
        .title { font-size: 20px; font-weight: bold; color: #d40511; margin-bottom: 20px; }
        .body-text { font-size: 15px; color: #444444; line-height: 1.6; margin-bottom: 30px; }
        .btn { display: inline-block; background-color: #d40511; color: #ffcc00 !important; text-decoration: none; padding: 12px 25px; font-weight: bold; font-size: 15px; border-radius: 3px; }
        .footer { background-color: #f1f1f1; padding: 20px; font-size: 11px; color: #777777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DHL Express</h1>
        </div>
        <div class="content">
            <div class="title">Your package delivery is on hold</div>
            <div class="body-text">
                Hello {{ $user->name }},<br><br>
                Your package with tracking number <strong>DHL-{{ rand(100000, 999999) }}</strong> could not be delivered on {{ date('Y-m-d') }} due to an incorrect shipping address.
                Please update your delivery address details and pay the outstanding processing fee ($1.99 USD) to schedule a new delivery.
            </div>
            <div>
                <a href="{{ route('phishing.track-click', $campaign) }}" class="btn">Update Address & Track Package</a>
            </div>
        </div>
        <div class="footer">
            Deutsche Post DHL Group. All rights reserved. Registered office: Bonn, Germany.
        </div>
    </div>
</body>
</html>
