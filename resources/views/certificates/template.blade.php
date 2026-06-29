<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .certificate-container {
            border: 15px double #2563eb;
            padding: 30px;
            text-align: center;
            background-color: #fafbfc;
            border-radius: 8px;
            position: relative;
            height: 90%;
        }
        .header {
            margin-top: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .subtitle-main {
            font-size: 10px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .presented-to {
            font-size: 14px;
            font-style: italic;
            color: #475569;
            margin-bottom: 10px;
        }
        .name {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            width: 70%;
            margin: 15px auto;
        }
        .description {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            max-width: 80%;
            margin: 15px auto;
        }
        .module-title {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
            margin: 10px 0 25px 0;
        }
        .footer-table {
            width: 90%;
            margin: 30px auto 10px auto;
            border-collapse: collapse;
        }
        .footer-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: middle;
        }
        .signature-line {
            border-bottom: 1px solid #cbd5e1;
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 14px;
            color: #1e293b;
            padding-bottom: 5px;
            width: 140px;
            margin: 0 auto;
        }
        .signature-title {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .seal {
            width: 70px;
            height: 70px;
            background-color: #f59e0b;
            border: 4px solid #ffffff;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: #ffffff;
        }
        .seal-text-top {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }
        .seal-check {
            font-size: 16px;
            font-weight: bold;
            margin-top: 2px;
        }
        .verification {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            margin-top: 30px;
        }
        .verification span {
            color: #64748b;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="header">
            <div class="logo">SecureLearn Platform</div>
            <div class="subtitle-main">Certificate of Completion</div>
        </div>

        <div class="presented-to">This is proudly presented to</div>
        <div class="name">{{ $user->name }}</div>

        <div class="description">
            For demonstrating outstanding knowledge and competency in database defenses by passing all criteria, lessons, and quizzes in the curriculum
        </div>
        <div class="module-title">{{ $module->title ?? 'Module Completion' }}</div>

        <table class="footer-table">
            <tr>
                <td>
                    <div class="signature-line">SecureLearn Board</div>
                    <div class="signature-title">Issuing Authority</div>
                </td>
                <td>
                    <div class="seal">
                        <div class="seal-text-top">Passed</div>
                        <div class="seal-check">&#10004;</div>
                    </div>
                </td>
                <td>
                    <div class="signature-line">{{ $user->name }}</div>
                    <div class="signature-title">Recipient Signature</div>
                </td>
            </tr>
        </table>

        <div class="verification">
            Verify at: <span>securelearn.org/verify/{{ $certificate->credential_id ?? $certificate->certificate_number }}</span>
        </div>
    </div>
</body>
</html>
