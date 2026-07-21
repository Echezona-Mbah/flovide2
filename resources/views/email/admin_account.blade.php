<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account Details</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
        }

        .wrapper {
            max-width: 620px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            padding: 36px 40px 30px;
            text-align: center;
        }

        .header img {
            max-width: 140px;
            margin-bottom: 18px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header p.subtitle {
            color: #a0aec0;
            font-size: 13px;
            margin-top: 6px;
        }

        /* ── Body ── */
        .body {
            padding: 36px 40px;
        }

        .body p {
            font-size: 15px;
            line-height: 1.7;
            color: #4a5568;
            margin-bottom: 16px;
        }

        /* ── Credentials card ── */
        .credentials {
            background: #f7f8fc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 24px 28px;
            margin: 24px 0;
        }

        .credentials h2 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            margin-bottom: 18px;
        }

        .credential-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .credential-row:last-child {
            margin-bottom: 0;
        }

        .credential-label {
            width: 110px;
            flex-shrink: 0;
            font-size: 13px;
            font-weight: 600;
            color: #718096;
            padding-top: 2px;
        }

        .credential-value {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
            word-break: break-all;
        }

        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* ── Warning box ── */
        .warning {
            background: #fff8e1;
            border-left: 4px solid #f6c90e;
            border-radius: 6px;
            padding: 14px 18px;
            margin: 20px 0;
            font-size: 13px;
            color: #7d6608;
            line-height: 1.6;
        }

        /* ── CTA Button ── */
        .btn-wrap {
            text-align: center;
            margin: 28px 0 8px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ── Footer ── */
        .footer {
            background: #f7f8fc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 40px;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #a0aec0;
            line-height: 1.6;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .social-icons {
            margin-top: 14px;
        }

        .social-icons a {
            display: inline-block;
            margin: 0 6px;
        }

        .social-icons a img {
            width: 30px;
            height: 30px;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Header -->
        <div class="header">
            <img src="{{ asset('Logo.png') }}" alt="{{ config('app.name') }} Logo">
            <h1>Admin Account Created</h1>
            <p class="subtitle">Your login credentials are ready</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p>Hi <strong>{{ $name }}</strong>,</p>
            <p>
                A new admin account has been created for you on the
                <strong>{{ config('app.name') }}</strong> platform. Below are your login credentials.
                Please keep them safe and confidential.
            </p>

            <!-- Credentials card -->
            <div class="credentials">
                <h2>🔐 Account Details</h2>

                <div class="credential-row">
                    <span class="credential-label">Full Name</span>
                    <span class="credential-value">{{ $name }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Email</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Password</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Role</span>
                    <span class="credential-value">
                        <span class="badge">{{ $role }}</span>
                    </span>
                </div>
            </div>

            <!-- Warning -->
            <div class="warning">
                ⚠️ <strong>Important:</strong> For your security, please log in and change your password
                immediately. Never share your credentials with anyone.
            </div>

            <!-- CTA -->
            <div class="btn-wrap">
                <a href="{{ url('/admin/login') }}" class="btn">Log In to Admin Panel →</a>
            </div>

            <p style="margin-top: 24px;">
                If you have any questions or did not expect this email, please contact your system administrator.
            </p>

            <p>Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-icons">
                <a href="https://www.facebook.com/your-facebook-page">
                    <img src="{{ asset('asserts/SocialMedia/facebook.png') }}" alt="Facebook">
                </a>
                <a href="https://www.instagram.com/your-instagram-account">
                    <img src="{{ asset('asserts/SocialMedia/instagram.png') }}" alt="Instagram">
                </a>
                <a href="https://www.linkedin.com/in/your-linkedin-profile">
                    <img src="{{ asset('asserts/SocialMedia/linkedin.png') }}" alt="LinkedIn">
                </a>
                <a href="https://wa.me/your-whatsapp-number">
                    <img src="{{ asset('asserts/SocialMedia/whatsapp.png') }}" alt="WhatsApp">
                </a>
            </div>
            <p style="margin-top: 12px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email was sent because a new admin account was created for you.
            </p>
        </div>

    </div>
</body>
</html>
