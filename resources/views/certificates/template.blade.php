<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 landscape; margin: 0; }

        body {
            font-family: 'DejaVu Serif', Georgia, serif;
            background: #0f172a;
            padding: 28px;
            color: #1e293b;
        }

        .cert {
            background: #ffffff;
            max-width: 970px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        /* Decorative top bar */
        .cert-topbar {
            height: 8px;
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 40%, #0ea5e9 100%);
        }

        /* Side accent */
        .cert-body {
            display: flex;
            min-height: 560px;
        }

        .cert-sidebar {
            width: 8px;
            background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 50%, #0ea5e9 100%);
            flex-shrink: 0;
        }

        .cert-main {
            flex: 1;
            padding: 40px 48px 36px 40px;
        }

        /* Header row */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 16px;
        }
        .brand-name { font-size: 18px; font-weight: bold; color: #1e293b; letter-spacing: 0.5px; }
        .brand-sub  { font-size: 10px; color: #64748b; letter-spacing: 1px; text-transform: uppercase; }

        .cert-type-badge {
            text-align: right;
        }
        .cert-type-badge .label {
            font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #6366f1; font-weight: bold;
        }
        .cert-type-badge .type {
            font-size: 22px; font-weight: bold; color: #1e293b; line-height: 1.2;
        }

        /* Divider */
        .divider { height: 1px; background: #e2e8f0; margin: 0 0 24px 0; }

        /* Recipient */
        .recipient-section { margin-bottom: 20px; }
        .recipient-label { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #64748b; margin-bottom: 4px; }
        .recipient-name  { font-size: 32px; font-weight: bold; color: #0f172a; line-height: 1.1; }
        .recipient-role  { font-size: 13px; color: #6366f1; font-weight: 600; margin-top: 4px; }

        /* Details grid */
        .details-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .details-row { display: table-row; }
        .detail-cell {
            display: table-cell;
            vertical-align: top;
            padding: 4px 16px 4px 0;
            width: 33%;
        }
        .detail-key   { font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: #94a3b8; margin-bottom: 2px; }
        .detail-value { font-size: 12px; color: #1e293b; font-weight: 600; }

        /* Contribution summary */
        .summary-box {
            background: #f8fafc;
            border-left: 3px solid #6366f1;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 0 6px 6px 0;
        }
        .summary-box .label { font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .summary-box p { font-size: 11.5px; color: #334155; line-height: 1.6; }

        /* Technologies */
        .tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 20px; }
        .tag {
            background: #ede9fe; color: #5b21b6;
            font-size: 10px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
        }

        /* Footer: signatures + QR */
        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sig-block { text-align: center; min-width: 130px; }
        .sig-line  { border-bottom: 1px solid #1e293b; height: 28px; margin-bottom: 4px; }
        .sig-name  { font-size: 11px; font-weight: 700; color: #0f172a; }
        .sig-title { font-size: 9px; color: #64748b; letter-spacing: 0.5px; }

        .qr-block { text-align: center; }
        .qr-block img { width: 72px; height: 72px; display: block; margin: 0 auto 4px; }
        .qr-label { font-size: 8px; color: #94a3b8; letter-spacing: 0.5px; }

        /* Expert seal */
        .expert-seal {
            position: absolute;
            top: 36px; right: 40px;
            width: 80px; height: 80px;
            border: 3px solid #10b981;
            border-radius: 50%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 8px;
        }
        .expert-seal .seal-text { font-size: 7.5px; font-weight: bold; color: #10b981; letter-spacing: 0.5px; text-transform: uppercase; line-height: 1.3; }

        /* Cert number footer */
        .cert-footer-bar {
            background: #f1f5f9;
            padding: 8px 48px 8px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cert-footer-bar .cert-num  { font-size: 10px; font-family: 'DejaVu Sans Mono', monospace; color: #475569; }
        .cert-footer-bar .cert-hash { font-size: 8px; font-family: 'DejaVu Sans Mono', monospace; color: #94a3b8; }

        /* Bottom bar */
        .cert-bottombar {
            height: 6px;
            background: linear-gradient(90deg, #0ea5e9 0%, #8b5cf6 60%, #6366f1 100%);
        }
    </style>
</head>
<body>
<div class="cert">
    <div class="cert-topbar"></div>
    <div class="cert-body">
        <div class="cert-sidebar"></div>
        <div class="cert-main">

            {{-- Header --}}
            <div class="header-row">
                <div class="brand">
                    <div class="brand-icon">M</div>
                    <div>
                        <div class="brand-name">Mindova</div>
                        <div class="brand-sub">Verified Professional Credential</div>
                    </div>
                </div>
                <div class="cert-type-badge">
                    <div class="label">Certificate of</div>
                    <div class="type">{{ ucfirst($certificate->certificate_type) }}</div>
                </div>
            </div>

            {{-- Expert seal (only when expert-approved) --}}
            @if($certificate->isExpertApproved())
            <div class="expert-seal" style="position: absolute; top: 44px; left: 50%; transform: translateX(-50%);">
                <div class="seal-text">Expert<br>Approved<br>✓</div>
            </div>
            @endif

            <div class="divider"></div>

            {{-- Recipient --}}
            <div class="recipient-section">
                <div class="recipient-label">This certifies that</div>
                <div class="recipient-name">{{ $certificate->user->name ?? 'Contributor' }}</div>
                <div class="recipient-role">{{ $certificate->role }}</div>
            </div>

            {{-- Challenge + Company --}}
            <div class="details-grid">
                <div class="details-row">
                    <div class="detail-cell">
                        <div class="detail-key">Challenge</div>
                        <div class="detail-value">{{ Str::limit($certificate->challenge->title ?? '—', 45) }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-key">
                            @if($certificate->show_company_name) Company @else Industry @endif
                        </div>
                        <div class="detail-value">
                            @if($certificate->show_company_name)
                                {{ $certificate->company?->company?->company_name ?? $certificate->company?->name ?? '—' }}
                            @else
                                {{ $certificate->industry ?? $certificate->challenge?->field ?? '—' }}
                            @endif
                        </div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-key">Industry Sector</div>
                        <div class="detail-value">{{ $certificate->industry ?? $certificate->challenge?->field ?? '—' }}</div>
                    </div>
                </div>
                <div class="details-row">
                    <div class="detail-cell">
                        <div class="detail-key">Verified Hours</div>
                        <div class="detail-value">{{ number_format($certificate->total_hours, 1) }} hours</div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-key">Project Duration</div>
                        <div class="detail-value">{{ $certificate->project_duration ?? '—' }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-key">Completion Date</div>
                        <div class="detail-value">{{ $certificate->issued_at?->format('d M Y') ?? now()->format('d M Y') }}</div>
                    </div>
                </div>
                @if($certificate->isExpertApproved())
                <div class="details-row">
                    <div class="detail-cell">
                        <div class="detail-key">Expert Approval</div>
                        <div class="detail-value" style="color:#10b981;">{{ $certificate->expertVolunteer?->user?->name ?? 'Verified Expert' }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-key">Approved On</div>
                        <div class="detail-value">{{ $certificate->expert_approved_at?->format('d M Y') ?? '—' }}</div>
                    </div>
                    <div class="detail-cell"></div>
                </div>
                @endif
            </div>

            {{-- Contribution Summary --}}
            <div class="summary-box">
                <div class="label">Nature of Contribution</div>
                <p>{{ $certificate->contribution_summary }}</p>
            </div>

            {{-- Technologies / Disciplines --}}
            @if(!empty($certificate->technologies) || !empty($certificate->contribution_types))
            <div class="tags">
                @foreach(array_merge($certificate->technologies ?? [], $certificate->contribution_types ?? []) as $tag)
                <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            {{-- Footer: Signatures + QR --}}
            <div class="footer-row">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">
                        @if($certificate->show_company_name)
                            {{ $certificate->company?->company?->company_name ?? 'Company Representative' }}
                        @else
                            Company Representative
                        @endif
                    </div>
                    <div class="sig-title">Company Digital Stamp</div>
                </div>

                @if($certificate->isExpertApproved())
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $certificate->expertVolunteer?->user?->name ?? 'Expert' }}</div>
                    <div class="sig-title">Verified Expert</div>
                </div>
                @endif

                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Mindova Platform</div>
                    <div class="sig-title">Platform Authority</div>
                </div>

                {{-- QR Code --}}
                <div class="qr-block">
                    @php
                        try {
                            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(72),
                                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
                            );
                            $writer = new \BaconQrCode\Writer($renderer);
                            $qrSvg = $writer->writeString(url('/certificates/verify?id=' . $certificate->certificate_number));
                            $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
                        } catch (\Exception $e) {
                            $qrBase64 = null;
                        }
                    @endphp
                    @if($qrBase64)
                    <img src="{{ $qrBase64 }}" alt="Verify Certificate">
                    @endif
                    <div class="qr-label">SCAN TO VERIFY</div>
                </div>
            </div>

        </div>
    </div>

    {{-- Certificate Number + Hash Bar --}}
    <div class="cert-footer-bar">
        <span class="cert-num">
            {{ $certificate->certificate_number }}
            @if($certificate->is_revoked)
                — <strong style="color:#ef4444;">REVOKED</strong>
            @endif
        </span>
        <span class="cert-hash">
            SHA-256: {{ $certificate->verification_hash ? substr($certificate->verification_hash, 0, 32) . '…' : 'PENDING' }}
        </span>
    </div>
    <div class="cert-bottombar"></div>
</div>
</body>
</html>
