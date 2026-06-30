@extends('layouts.app')

@section('title', $volunteer->user->name . ' — Talent Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back --}}
    <a href="{{ route('talent.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Talent Marketplace
    </a>

    @php
        $tierColor = $volunteer->tier_color ?? '#6366f1';
        $tierName  = $volunteer->tier_name  ?? 'Explorer';
        $stars     = $volunteer->stars ?? $volunteer->reputation_score;
    @endphp

    {{-- ===== PROFILE HEADER ===== --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-6">
        <div class="h-3" style="background: linear-gradient(90deg, {{ $tierColor }}, {{ $tierColor }}80)"></div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold text-white shrink-0"
                     style="background: linear-gradient(135deg, {{ $tierColor }}, {{ $tierColor }}aa)">
                    {{ strtoupper(substr($volunteer->user->name ?? 'U', 0, 1)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full border"
                              style="background: {{ $tierColor }}15; color: {{ $tierColor }}; border-color: {{ $tierColor }}40;">
                            {{ $tierName }}
                        </span>
                        @if($volunteer->isCertifiedExpert())
                        <span class="text-xs font-bold px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-500/30">
                            Certified Expert
                        </span>
                        @elseif($volunteer->isExpertCandidate())
                        <span class="text-xs font-bold px-2.5 py-1 bg-amber-50 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300 rounded-full border border-amber-200 dark:border-amber-500/30">
                            Expert Candidate
                        </span>
                        @endif
                        @if($score['expert_approvals'] > 0)
                        <span class="text-xs font-semibold px-2.5 py-1 bg-violet-50 dark:bg-violet-500/15 text-violet-700 dark:text-violet-300 rounded-full">
                            {{ $score['expert_approvals'] }} Expert-Approved
                        </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $volunteer->user->name ?? 'Professional' }}</h1>
                    @if($volunteer->bio)
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-3 max-w-xl">{{ Str::limit($volunteer->bio, 250) }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ $volunteer->availability_hours_per_week }}h/week available</span>
                        <span>·</span>
                        <span>Member since {{ $volunteer->created_at->format('M Y') }}</span>
                        @if($volunteer->user->linkedin_profile_url)
                        <span>·</span>
                        <a href="{{ $volunteer->user->linkedin_profile_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">LinkedIn</a>
                        @endif
                    </div>
                </div>

                {{-- Hire CTA --}}
                <div class="shrink-0 flex flex-col items-end gap-3">
                    @if($canHire)
                        @if($alreadySent)
                        <span class="px-5 py-2.5 text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl">
                            Request Sent
                        </span>
                        @else
                        <a href="{{ route('talent.hire', $volunteer) }}"
                           class="px-5 py-2.5 text-sm font-bold text-white rounded-xl shadow-sm hover:opacity-90 transition-opacity"
                           style="background: {{ $tierColor }}">
                            Send Hire Request
                        </a>
                        @endif
                    @endif
                    {{-- Marketplace score --}}
                    <div class="text-center px-4 py-3 rounded-xl border" style="background: {{ $tierColor }}0d; border-color: {{ $tierColor }}30;">
                        <div class="text-2xl font-bold" style="color: {{ $tierColor }}">{{ $score['total'] }}</div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Talent Score</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== KPI ROW ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        @foreach([
            ['label' => 'Stars',            'value' => $stars,                          'color' => $tierColor,  'hex' => true],
            ['label' => 'Trust Score',      'value' => number_format($volunteer->trust_score ?? 100, 0) . '/100', 'color' => '#10b981', 'hex' => false],
            ['label' => 'Verified Projects','value' => $score['verified_projects'],      'color' => '#6366f1',  'hex' => false],
            ['label' => 'Expert Approved',  'value' => $score['expert_approvals'],       'color' => '#8b5cf6',  'hex' => false],
            ['label' => 'Success Rate',     'value' => $score['success_rate'] . '%',     'color' => '#f59e0b',  'hex' => false],
        ] as $kpi)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 text-center">
            <div class="text-xl font-bold" style="color: {{ $kpi['color'] }}">{{ $kpi['value'] }}</div>
            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $kpi['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- MAIN --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- VERIFIED PORTFOLIO --}}
            @if($certificates->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Verified Project Portfolio</h2>
                    <span class="ml-auto text-xs text-gray-400">{{ $certificates->count() }} verified</span>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($certificates as $cert)
                    <div class="px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $cert->challenge->title ?? 'Project' }}</span>
                                    @if($cert->isExpertApproved())
                                    <span class="text-[10px] px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded font-bold">Expert Approved</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    {{ $cert->role }}
                                    @if($cert->industry) · {{ $cert->industry }}@endif
                                    · {{ number_format($cert->total_hours, 0) }}h
                                    @if($cert->project_duration) · {{ $cert->project_duration }}@endif
                                    · {{ $cert->issued_at?->format('M Y') ?? $cert->created_at->format('M Y') }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ Str::limit($cert->contribution_summary, 160) }}</p>
                                @if(!empty($cert->technologies))
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @foreach(array_slice($cert->technologies, 0, 4) as $tech)
                                    <span class="text-[10px] px-1.5 py-0.5 bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300 rounded">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @if($cert->pdf_path)
                            <a href="{{ $cert->pdf_url }}" target="_blank"
                               class="shrink-0 text-xs px-3 py-1.5 border border-violet-200 dark:border-violet-500/30 text-violet-600 dark:text-violet-400 rounded-lg hover:bg-violet-50 dark:hover:bg-violet-500/10 font-semibold transition-colors">
                               PDF
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- SKILLS --}}
            @if($volunteer->skills->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Skills</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($volunteer->skills as $skill)
                        <div class="flex justify-between items-center px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="text-sm text-gray-800 dark:text-gray-200 font-medium">{{ $skill->skill_name }}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-semibold
                                {{ $skill->proficiency_level === 'expert' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $skill->proficiency_level === 'advanced' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $skill->proficiency_level === 'intermediate' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $skill->proficiency_level === 'beginner' ? 'bg-gray-200 text-gray-700' : '' }}">
                                {{ ucfirst($skill->proficiency_level) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-4">

            {{-- Talent Score Breakdown --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Talent Score Breakdown</h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach([
                        ['label' => 'Reputation Stars',    'raw' => min($stars / 1200, 1),          'weight' => 25, 'display' => $stars . ' stars'],
                        ['label' => 'Trust Score',         'raw' => ($volunteer->trust_score ?? 100) / 100, 'weight' => 20, 'display' => number_format($volunteer->trust_score ?? 100, 0) . '/100'],
                        ['label' => 'Verified Projects',   'raw' => min($score['verified_projects'] / 20, 1), 'weight' => 15, 'display' => $score['verified_projects'] . ' projects'],
                        ['label' => 'Success Rate',        'raw' => $score['success_rate'] / 100,   'weight' => 15, 'display' => $score['success_rate'] . '%'],
                        ['label' => 'Expert Approvals',    'raw' => min($score['expert_approvals'] / 5, 1), 'weight' => 10, 'display' => $score['expert_approvals'] . ' certs'],
                    ] as $factor)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ $factor['label'] }}</span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $factor['display'] }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                            <div class="h-full rounded-full" style="width: {{ min(100, $factor['raw'] * 100) }}%; background: {{ $tierColor }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Active Hiring Records --}}
            @if($hiringRecords->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Verified Employment</h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($hiringRecords as $record)
                    <div class="text-xs">
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $record->position_title }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ $record->engagementLabel() }} · since {{ $record->hired_at->format('M Y') }}</div>
                        <div class="font-mono text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $record->hiring_verification_id }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Hire CTA (mobile) --}}
            @if($canHire && !$alreadySent)
            <a href="{{ route('talent.hire', $volunteer) }}"
               class="block w-full text-center px-5 py-3 text-white font-bold rounded-xl shadow-sm hover:opacity-90 transition-opacity"
               style="background: {{ $tierColor }}">
                Send Hire Request
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
