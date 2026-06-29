<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Microsoft Account Verification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 40px; border: 1px solid #e1e1e1; border-radius: 4px; }
        .logo { color: #737373; font-size: 22px; font-weight: 600; margin-bottom: 20px; }
        .logo span { color: #f25022; }
        .logo span.blue { color: #00a4ef; }
        .logo span.green { color: #7fba00; }
        .logo span.yellow { color: #ffb900; }
        .title { font-size: 24px; font-weight: 500; color: #262626; margin-bottom: 20px; }
        .body-text { font-size: 16px; color: #505050; line-height: 1.5; margin-bottom: 30px; }
        .btn { display: inline-block; background-color: #0067b8; color: #ffffff !important; text-decoration: none; padding: 12px 24px; font-weight: 600; font-size: 16px; border-radius: 2px; }
        .footer { font-size: 12px; color: #a6a6a6; margin-top: 40px; border-top: 1px solid #e1e1e1; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span>M</span><span>i</span><span class="blue">c</span><span class="green">r</span><span class="yellow">o</span><span>s</span><span>o</span><span>f</span><span>t</span>
        </div>
        <div class="title">Verify your account login information</div>
        <div class="body-text">
            Dear {{ $user->name }},<br><br>
            We detected a login attempt to your Microsoft Account from an unrecognized device or location. To ensure your account security and prevent suspension, please verify your identity and account details immediately.
        </div>
        <div>
            <a href="{{ route('phishing.track-click', $campaign) }}" class="btn">Verify Account Info</a>
        </div>
        <div class="footer">
            This is a mandatory system security notification. Microsoft Corporation, One Microsoft Way, Redmond, WA 98052
        </div>
    </div>
</body>
</html>
