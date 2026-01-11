<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate {{ $certificate->certificate_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 40px;
            color: #333;
        }

        .certificate {
            background: white;
            border: 20px solid #2c3e50;
            border-image: linear-gradient(45deg, #3498db, #2c3e50) 1;
            padding: 60px;
            max-width: 1000px;
            margin: 0 auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 2px solid #3498db;
            pointer-events: none;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            max-width: 120px;
            max-height: 80px;
        }

        .logo-placeholder {
            width: 120px;
            height: 80px;
            background: #ecf0f1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 12px;
            text-align: center;
        }

        .title {
            font-size: 42px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 20px 0;
            border-bottom: 3px solid #3498db;
            padding-bottom: 15px;
        }

        .subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        .volunteer-name {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
            margin: 30px 0;
            text-align: center;
        }

        .intro-text {
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .role {
            font-size: 24px;
            color: #2c3e50;
            text-align: center;
            margin: 20px 0;
            font-style: italic;
        }

        .challenge-info {
            background: #ecf0f1;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
        }

        .challenge-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .challenge-domain {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .section {
            margin: 25px 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3498db;
        }

        .contribution-summary {
            font-size: 15px;
            line-height: 1.8;
            color: #555;
            text-align: justify;
        }

        .time-info {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .time-box {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            min-width: 120px;
        }

        .time-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .time-value {
            font-size: 20px;
            font-weight: bold;
            color: #3498db;
        }

        .contribution-types {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }

        .contribution-tag {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-column {
            text-align: center;
            flex: 1;
        }

        .signature-line {
            border-top: 2px solid #2c3e50;
            margin: 40px 20px 10px 20px;
            padding-top: 10px;
            font-size: 14px;
            color: #555;
        }

        .company-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .certificate-meta {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #7f8c8d;
        }

        .certificate-number {
            font-weight: bold;
            color: #3498db;
            letter-spacing: 1px;
        }

        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 100px;
            height: 100px;
            border: 3px solid #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: white;
            transform: rotate(-15deg);
        }

        .seal-text {
            font-size: 10px;
            font-weight: bold;
            color: #3498db;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="logos">
                <div>
                    @if($certificate->company_logo_path)
                        <img src="{{ storage_path('app/public/' . $certificate->company_logo_path) }}" alt="Company Logo" class="logo">
                    @else
                        <div class="logo-placeholder">Company<br>Logo</div>
                    @endif
                </div>
                <div>
                    <div class="logo-placeholder">MINDOVA<br>Platform</div>
                </div>
            </div>

            <div class="title">Certificate of {{ ucfirst($certificate->certificate_type) }}</div>
            <div class="subtitle">This is to certify that</div>
        </div>

        <div class="volunteer-name">{{ $certificate->volunteer->name }}</div>

        <div class="intro-text">
            Has successfully participated in and contributed to the following challenge<br>
            through the MINDOVA Platform
        </div>

        <div class="role">{{ $certificate->role }}</div>

        <div class="challenge-info">
            <div class="challenge-title">{{ $certificate->challenge->title }}</div>
            <div class="challenge-domain">Domain: {{ $certificate->challenge->domain ?? 'General' }}</div>
        </div>

        <div class="section">
            <div class="section-title">Contribution Summary</div>
            <div class="contribution-summary">
                {{ $certificate->contribution_summary }}
            </div>
        </div>

        @if(!empty($certificate->contribution_types))
        <div class="section">
            <div class="section-title">Areas of Contribution</div>
            <div class="contribution-types">
                @foreach($certificate->contribution_types as $type)
                    <div class="contribution-tag">{{ $type }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Time Investment</div>
            <div class="time-info">
                <div class="time-box">
                    <div class="time-label">Total Hours</div>
                    <div class="time-value">{{ number_format($certificate->total_hours, 1) }}h</div>
                </div>
                @if($certificate->time_breakdown)
                    <div class="time-box">
                        <div class="time-label">Analysis</div>
                        <div class="time-value">{{ number_format($certificate->time_breakdown['analysis'], 1) }}h</div>
                    </div>
                    <div class="time-box">
                        <div class="time-label">Execution</div>
                        <div class="time-value">{{ number_format($certificate->time_breakdown['execution'], 1) }}h</div>
                    </div>
                    <div class="time-box">
                        <div class="time-label">Review</div>
                        <div class="time-value">{{ number_format($certificate->time_breakdown['review'], 1) }}h</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <div class="footer-column">
                <div class="signature-line">
                    <span class="company-name">{{ $certificate->company->name ?? 'Company Representative' }}</span><br>
                    Company Representative
                </div>
            </div>
            <div class="footer-column">
                <div class="signature-line">
                    MINDOVA Platform<br>
                    Platform Authority
                </div>
            </div>
        </div>

        <div class="certificate-meta">
            Certificate Number: <span class="certificate-number">{{ $certificate->certificate_number }}</span><br>
            Issued on: {{ $certificate->issued_at?->format('F d, Y') ?? $certificate->created_at->format('F d, Y') }}
            @if($certificate->is_revoked)
                <br><strong style="color: #e74c3c;">REVOKED</strong> on {{ $certificate->revoked_at?->format('F d, Y') ?? __('Unknown date') }}
                <br>Reason: {{ $certificate->revocation_reason }}
            @endif
        </div>

        @if(!$certificate->is_revoked)
        <div class="seal">
            <div class="seal-text">VERIFIED<br>MINDOVA</div>
        </div>
        @endif
    </div>
</body>
</html>
