<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #ea4f2d 0%, #e51d00 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
            text-align: center;
        }

        .content h2 {
            color: #000167;
            margin: 0 0 15px;
            font-size: 20px;
        }

        .content p {
            color: #6c757d;
            margin: 0 0 25px;
        }

        .otp-box {
            background: linear-gradient(135deg, #000167 0%, #000050 100%);
            color: #ffffff;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 12px;
            padding: 20px 30px;
            border-radius: 12px;
            display: inline-block;
            margin: 20px 0;
        }

        .expiry-notice {
            background: #fff3cd;
            color: #856404;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            margin: 20px 0 0;
        }

        .expiry-notice i {
            margin-right: 8px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            margin: 5px 0;
        }

        .warning-text {
            color: #dc3545;
            font-size: 13px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>TABANG</h1>
            <p>Relief Donation Platform</p>
        </div>

        <div class="content">
            <h2>Email Verification Code</h2>
            <p>Use the following code to verify your email address:</p>

            <div class="otp-box">{{ $otp }}</div>

            <div class="expiry-notice">
                ⏱️ This code will expire in <strong>10 minutes</strong>.
            </div>

            <p class="warning-text">
                If you did not request this verification code, please ignore this email.
            </p>
        </div>

        <div class="footer">
            <p>This is an automated message from TABANG.</p>
            <p>Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} TABANG - Relief Donation Platform</p>
        </div>
    </div>
</body>

</html>
