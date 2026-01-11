<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Mindova Team Invitation') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #3B82F6;
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #3B82F6, #8B5CF6);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .credentials-box {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            margin: 0 0 15px 0;
            color: #1f2937;
            font-size: 16px;
        }
        .credential-item {
            margin: 10px 0;
        }
        .credential-label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background: #1f2937;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 15px;
            word-break: break-all;
        }
        .warning-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #92400e;
        }
        .warning-box strong {
            display: block;
            margin-bottom: 5px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .steps {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .steps h3 {
            color: #166534;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        .steps ol {
            margin: 0;
            padding-left: 20px;
            color: #166534;
        }
        .steps li {
            margin: 8px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        .footer a {
            color: #3B82F6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Mindova</div>
        </div>

        <h1>{{ __('Welcome to the Mindova Team!') }}</h1>

        <p>{{ __('Hello') }} <strong>{{ $teamMember->name }}</strong>,</p>

        <p>{{ __('You have been invited to join the Mindova internal team. Your account has been created with the following role:') }}</p>

        <div style="text-align: center;">
            <span class="role-badge">{{ $teamMember->role->name }}</span>
        </div>

        <p>{{ $teamMember->role->description }}</p>

        <div class="credentials-box">
            <h3>{{ __('Your Login Credentials') }}</h3>
            <div class="credential-item">
                <div class="credential-label">{{ __('Email') }}</div>
                <div class="credential-value">{{ $teamMember->email }}</div>
            </div>
            <div class="credential-item">
                <div class="credential-label">{{ __('Temporary Password') }}</div>
                <div class="credential-value">{{ $temporaryPassword }}</div>
            </div>
        </div>

        <div class="warning-box">
            <strong>{{ __('Important Security Notice') }}</strong>
            {{ __('This temporary password can only be used once. You will be required to change your password upon your first login. Never share your credentials with anyone.') }}
        </div>

        <div class="steps">
            <h3>{{ __('Getting Started') }}</h3>
            <ol>
                <li>{{ __('Click the button below to access the admin portal') }}</li>
                <li>{{ __('Enter your email and temporary password') }}</li>
                <li>{{ __('Create a new secure password when prompted') }}</li>
                <li>{{ __('Start managing the Mindova platform') }}</li>
            </ol>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/mindova-admin/login') }}" class="cta-button">
                {{ __('Access Admin Portal') }}
            </a>
        </div>

        <div class="footer">
            <p>{{ __('This is an automated message from Mindova. Please do not reply to this email.') }}</p>
            <p>{{ __('If you did not expect this invitation, please contact') }} <a href="mailto:mindova.ai@gmail.com">mindova.ai@gmail.com</a></p>
            <p>&copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</body>
</html>
