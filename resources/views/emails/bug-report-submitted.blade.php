<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .critical-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
        }
        .content {
            background: #f9fafb;
            padding: 24px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 20px;
            background: white;
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .field-value {
            font-size: 15px;
            color: #111827;
        }
        .description {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 6px;
            margin-top: 8px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .meta-info {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            padding: 16px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐞 New Bug Report</h1>
        @if($bugReport->blocked_user)
            <span class="critical-badge">⚠️ CRITICAL - BLOCKED USER</span>
        @endif
    </div>

    <div class="content">
        {{-- Issue Type --}}
        <div class="field">
            <div class="field-label">Issue Type</div>
            <div class="field-value">{{ $bugReport->getIssueTypeLabel() }}</div>
        </div>

        {{-- Description --}}
        <div class="field">
            <div class="field-label">Description</div>
            <div class="description">{{ $bugReport->description }}</div>
        </div>

        {{-- Current Page --}}
        <div class="field">
            <div class="field-label">Page Where Issue Occurred</div>
            <div class="field-value">{{ $bugReport->current_page }}</div>
        </div>

        {{-- Reporter Info --}}
        <div class="field">
            <div class="field-label">Reported By</div>
            <div class="field-value">
                @if($bugReport->user)
                    {{ $bugReport->user->name }} ({{ $bugReport->user->email }})
                    <br>
                    <small style="color: #6b7280;">
                        User Type: {{ ucfirst($bugReport->user->user_type) }}
                    </small>
                @else
                    Anonymous User
                @endif
            </div>
        </div>

        {{-- Screenshot Notice --}}
        @if($bugReport->screenshot)
        <div class="field">
            <div class="field-label">Screenshot</div>
            <div class="field-value">📎 Screenshot attached to this email</div>
        </div>
        @endif

        {{-- Meta Information --}}
        <div class="meta-info">
            <strong>Additional Information:</strong><br>
            <small>
                <strong>Report ID:</strong> #{{ $bugReport->id }}<br>
                <strong>Submitted:</strong> {{ $bugReport->created_at->format('M d, Y H:i:s') }} ({{ $bugReport->created_at->diffForHumans() }})<br>
                <strong>Browser:</strong> {{ $bugReport->user_agent ?? 'Unknown' }}<br>
                <strong>Status:</strong> {{ ucfirst($bugReport->status) }}
            </small>
        </div>

        {{-- Critical Warning --}}
        @if($bugReport->blocked_user)
        <div style="background: #fee2e2; border: 2px solid #dc2626; padding: 16px; border-radius: 6px; margin-top: 20px;">
            <strong style="color: #dc2626;">⚠️ HIGH PRIORITY</strong><br>
            <small style="color: #7f1d1d;">
                The user indicated this issue <strong>blocked them from continuing their task</strong>.
                This should be reviewed and prioritized for immediate resolution.
            </small>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>
            This is an automated notification from the Mindova bug reporting system.<br>
            Bug reports are collected and reviewed in batches for pattern analysis.
        </p>
        <p style="margin-top: 12px; color: #9ca3af;">
            <strong>Mindova Platform</strong> - MVP Bug Tracking<br>
            <em>Strictly for friction capture - Not a support system</em>
        </p>
    </div>
</body>
</html>
