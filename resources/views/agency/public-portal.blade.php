<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portal->agency_name }} — Verified Talent</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; color: #1e293b; }
        .brand-gradient { background: linear-gradient(135deg, {{ $portal->primary_color }}, {{ $portal->secondary_color }}); }
        .brand-text { color: {{ $portal->primary_color }}; }
        .brand-border { border-color: {{ $portal->primary_color }}; }
        .brand-btn { background: {{ $portal->primary_color }}; color: #fff; }
        .brand-btn:hover { opacity: 0.88; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .tag { background: color-mix(in srgb, {{ $portal->primary_color }} 12%, white); color: {{ $portal->primary_color }}; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav style="background:#fff; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 50;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px; display:flex; align-items:center; justify-content:space-between; height: 60px;">
        <div style="display:flex; align-items:center; gap:12px;">
            @if($portal->logo_path)
            <img src="{{ asset('storage/' . $portal->logo_path) }}" alt="{{ $portal->agency_name }}" style="height:36px; object-fit:contain;">
            @else
            <div class="brand-gradient" style="width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:bold; font-size:16px;">
                {{ strtoupper(substr($portal->agency_name, 0, 1)) }}
            </div>
            <span style="font-weight: 700; font-size: 18px;">{{ $portal->agency_name }}</span>
            @endif
        </div>
        <div style="display:flex; gap:12px; align-items:center;">
            @if($portal->contact_email)
            <a href="mailto:{{ $portal->contact_email }}" style="font-size:13px; color:#64748b; text-decoration:none;">Contact Us</a>
            @endif
            <a href="{{ route('talent.index') }}" class="brand-btn" style="padding: 8px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
                Browse All Talent
            </a>
        </div>
    </div>
</nav>

{{-- Hero --}}
<div class="brand-gradient" style="padding: 80px 24px; text-align: center;">
    <div style="max-width: 640px; margin: 0 auto;">
        <span style="display:inline-block; background:rgba(255,255,255,0.2); color:#fff; font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; padding:4px 14px; border-radius:20px; margin-bottom:16px;">
            Verified Recruitment
        </span>
        <h1 style="font-size: 40px; font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 16px;">
            Hire Proven Professionals
        </h1>
        <p style="font-size: 16px; color: rgba(255,255,255,0.85); line-height: 1.7; margin-bottom: 28px;">
            Every professional in our network has been verified through real business challenges, expert validation, and measurable project success — not just a CV.
        </p>
        <a href="#talent" class="brand-btn" style="display:inline-block; padding: 14px 32px; border-radius:10px; font-size:15px; font-weight:700; text-decoration:none; background:#fff; color: {{ $portal->primary_color }};">
            Explore Verified Talent
        </a>
    </div>
</div>

{{-- Trust signals --}}
<div style="background: #fff; border-bottom: 1px solid #e2e8f0; padding: 24px;">
    <div style="max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; text-align: center;">
        @foreach([
            ['Not a CV', 'Every hire is backed by verified project history and expert approval.'],
            ['Reputation Driven', 'Dynamic ranking based on stars, trust score, and real performance data.'],
            ['Permanent Records', 'Tamper-proof hiring verification IDs for every engagement.'],
        ] as [$title, $desc])
        <div>
            <div class="brand-text" style="font-size:14px; font-weight:700; margin-bottom:4px;">{{ $title }}</div>
            <div style="font-size:12px; color:#64748b; line-height:1.6;">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Talent Grid --}}
<div id="talent" style="max-width: 1100px; margin: 48px auto; padding: 0 24px;">
    <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 24px;">Top Verified Professionals</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
        @foreach($topTalent as $i => $vol)
        @php
            $tierColor = $vol->tier_color ?? '#6366f1';
            $verified  = \App\Models\Certificate::where('user_id', $vol->user_id)
                            ->where('company_confirmed', true)->where('is_revoked', false)->count();
        @endphp
        <div class="card" style="transition: transform 0.15s, box-shadow 0.15s;">
            <div style="height: 4px; background: {{ $tierColor }};"></div>
            <div style="padding: 20px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                    <div style="width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:700; color:#fff; background: {{ $tierColor }}; flex-shrink:0;">
                        {{ strtoupper(substr($vol->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:14px; font-weight:700; color:#0f172a;">{{ $vol->user->name ?? 'Professional' }}</div>
                        <span class="tag" style="display:inline-block; margin-top:2px;">{{ $vol->tier_name ?? 'Explorer' }}</span>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:12px; text-align:center;">
                    <div>
                        <div style="font-size:16px; font-weight:700; color:{{ $tierColor }};">{{ $vol->stars ?? $vol->reputation_score }}</div>
                        <div style="font-size:10px; color:#94a3b8;">Stars</div>
                    </div>
                    <div>
                        <div style="font-size:16px; font-weight:700; color:#10b981;">{{ number_format($vol->trust_score ?? 100, 0) }}</div>
                        <div style="font-size:10px; color:#94a3b8;">Trust</div>
                    </div>
                    <div>
                        <div style="font-size:16px; font-weight:700; color:#6366f1;">{{ $verified }}</div>
                        <div style="font-size:10px; color:#94a3b8;">Projects</div>
                    </div>
                </div>
                @if($vol->skills->count() > 0)
                <div style="display:flex; flex-wrap:wrap; gap:4px; margin-bottom:12px;">
                    @foreach($vol->skills->take(3) as $skill)
                    <span style="font-size:10px; padding:2px 8px; background:#f1f5f9; color:#475569; border-radius:20px; font-weight:500;">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
                @endif
                <div style="display:flex; gap:8px;">
                    <a href="{{ route('talent.profile', $vol) }}" style="flex:1; text-align:center; padding:8px; border:1px solid #e2e8f0; border-radius:8px; font-size:12px; font-weight:600; color:#475569; text-decoration:none;">Profile</a>
                    @auth
                    @if(auth()->user()->company)
                    <a href="{{ route('talent.hire', $vol) }}" class="brand-btn" style="flex:1; text-align:center; padding:8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none;">Hire</a>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="brand-btn" style="flex:1; text-align:center; padding:8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none;">Hire</a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="text-align:center; margin-top:36px;">
        <a href="{{ route('talent.index') }}" class="brand-btn" style="display:inline-block; padding:12px 28px; border-radius:10px; font-size:14px; font-weight:700; text-decoration:none;">
            View All Verified Professionals
        </a>
    </div>
</div>

{{-- Footer --}}
<div style="border-top: 1px solid #e2e8f0; margin-top: 48px; padding: 24px; text-align: center;">
    <p style="font-size: 12px; color: #94a3b8;">
        {{ $portal->agency_name }}
        @if($portal->contact_email) · <a href="mailto:{{ $portal->contact_email }}" style="color: {{ $portal->primary_color }}; text-decoration:none;">{{ $portal->contact_email }}</a>@endif
        @if($portal->website) · <a href="{{ $portal->website }}" target="_blank" style="color: {{ $portal->primary_color }}; text-decoration:none;">{{ parse_url($portal->website, PHP_URL_HOST) }}</a>@endif
    </p>
    <p style="font-size: 11px; color: #cbd5e1; margin-top: 6px;">Powered by <strong>Mindova</strong> — Verified Professional Reputation Platform</p>
</div>

</body>
</html>
