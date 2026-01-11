<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates Generated - {{ $challenge->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #667eea;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .certificate-list {
            margin: 20px 0;
        }
        .certificate-item {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
        }
        .certificate-item h4 {
            margin: 0 0 8px 0;
            font-size: 15px;
            color: #333;
        }
        .certificate-item p {
            margin: 4px 0;
            font-size: 13px;
            color: #666;
        }
        .certificate-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #667eea;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .stat-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            display: block;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎓 Certificates Generated Successfully</h1>
            <p>Challenge: {{ $challenge->title }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hello MINDOVA Team,</p>

            <p>Certificates have been successfully generated for a completed challenge on the MINDOVA platform.</p>

            <!-- Challenge Information -->
            <div class="info-box">
                <h3>📋 Challenge Details</h3>
                <p><strong>Title:</strong> {{ $challenge->title }}</p>
                <p><strong>Company:</strong> {{ $companyName }}</p>
                <p><strong>Domain:</strong> {{ $challenge->domain ?? 'General' }}</p>
                <p><strong>Certificate Type:</strong> {{ ucfirst($certificateType) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($challenge->status) }}</p>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number">{{ $totalCertificates }}</span>
                    <span class="stat-label">Certificates Issued</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ ucfirst($certificateType) }}</span>
                    <span class="stat-label">Certificate Type</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Certificate List -->
            <h3 style="margin-bottom: 15px;">📜 Generated Certificates:</h3>
            <div class="certificate-list">
                @foreach($certificates as $cert)
                    <div class="certificate-item">
                        <h4>{{ is_array($cert) ? $cert['volunteer']['name'] : $cert->volunteer->name }}</h4>
                        <p><strong>Certificate Number:</strong> <span class="certificate-number">{{ is_array($cert) ? $cert['certificate_number'] : $cert->certificate_number }}</span></p>
                        <p><strong>Role:</strong> {{ is_array($cert) ? $cert['role'] : $cert->role }}</p>
                        <p><strong>Total Hours:</strong> {{ number_format(is_array($cert) ? $cert['total_hours'] : $cert->total_hours, 1) }} hours</p>
                        <p><strong>Email:</strong> {{ is_array($cert) ? $cert['volunteer']['email'] : $cert->volunteer->email }}</p>
                        @php
                            $contributionTypes = is_array($cert) ? ($cert['contribution_types'] ?? []) : $cert->contribution_types;
                        @endphp
                        @if(!empty($contributionTypes))
                            <p><strong>Contributions:</strong> {{ implode(', ', $contributionTypes) }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="divider"></div>

            <!-- Summary -->
            <div class="info-box">
                <h3>✅ Summary</h3>
                <p><strong>Challenge ID:</strong> {{ $challenge->id }}</p>
                <p><strong>Issued Date:</strong> {{ now()->format('F d, Y \a\t H:i A') }}</p>
                <p><strong>PDF Files:</strong> Generated and stored in <code>storage/app/public/certificates/</code></p>
                <p><strong>Database:</strong> All records saved successfully</p>
            </div>

            <!-- Action -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('admin.challenges.show', $challenge) }}" class="button">View Challenge Details (Admin)</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                <strong>What happens next:</strong><br>
                • Contributors can view and download their certificates from their profile<br>
                • Certificates can be publicly verified using the unique certificate number<br>
                • PDFs are available for download immediately
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>MINDOVA Platform</strong></p>
            <p>This is an automated notification email. Please do not reply to this message.</p>
            <p style="margin-top: 10px;">
                <a href="{{ url('/') }}">Visit MINDOVA</a> •
                <a href="{{ route('challenges.show', $challenge) }}">View Challenge</a>
            </p>
            <p style="margin-top: 15px; color: #999;">
                © {{ date('Y') }} MINDOVA. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
