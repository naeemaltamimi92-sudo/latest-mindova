@extends('layouts.app')

@section('title', $volunteer->user->name . ' — Professional Portfolio')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ===== PROFILE HEADER ===== --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-6">
        {{-- Tier banner --}}
        <div class="h-2" style="background: {{ $volunteer->tier_color ?? '#6366f1' }}"></div>

        <div class="px-6 py-6">
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">

                {{-- Avatar --}}
                <x-ui.avatar name="{{ $volunteer->user->name }}" size="xl" />

                {{-- Bio block --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        {{-- Tier badge --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border"
                              style="background: {{ $volunteer->tier_color ?? '#6366f1' }}18; color: {{ $volunteer->tier_color ?? '#6366f1' }}; border-color: {{ $volunteer->tier_color ?? '#6366f1' }}40;">
                            {{ $volunteer->tier_name ?? 'Explorer' }}
                        </span>

                        {{-- Expert badge --}}
                        @if($volunteer->isCertifiedExpert())
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 rounded-full text-xs font-bold">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Certified Expert
                        </span>
                        @elseif($volunteer->isExpertCandidate())
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 rounded-full text-xs font-bold">
                            Expert Candidate
                        </span>
                        @endif

                        @if($volunteer->availability_hours_per_week >= 20)
                        <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-500/30 rounded-full text-xs font-semibold">
                            High Availability
                        </span>
                        @endif
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $volunteer->user->name }}</h1>

                    @if(!empty($stats['industries_count']))
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        Active across {{ $stats['industries_count'] }} {{ Str::plural('industry', $stats['industries_count']) }}
                        · {{ $stats['verified_hours'] }} verified hours
                    </p>
                    @endif

                    @if($volunteer->bio)
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4 max-w-2xl">{{ Str::limit($volunteer->bio, 280) }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3">
                        @if($volunteer->user->linkedin_profile_url)
                        <x-ui.button as="a" href="{{ $volunteer->user->linkedin_profile_url }}" target="_blank" rel="noopener noreferrer" variant="ghost" size="sm">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            LinkedIn
                        </x-ui.button>
                        @endif
                        <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $volunteer->availability_hours_per_week }}h/week available
                        </span>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Member since {{ $volunteer->created_at->format('M Y') }}</span>
                    </div>
                </div>

                {{-- Stars & Trust --}}
                <div class="flex flex-col items-center gap-3 shrink-0">
                    <div class="text-center px-5 py-4 rounded-xl border" style="background: {{ $volunteer->tier_color ?? '#6366f1' }}0d; border-color: {{ $volunteer->tier_color ?? '#6366f1' }}30;">
                        <div class="text-3xl font-bold" style="color: {{ $volunteer->tier_color ?? '#6366f1' }}">{{ $volunteer->stars ?? $volunteer->reputation_score }}</div>
                        <div class="text-xs font-semibold mt-0.5" style="color: {{ $volunteer->tier_color ?? '#6366f1' }}">Stars</div>
                    </div>
                    <div class="text-center px-4 py-3 bg-white dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 w-full">
                        <div class="text-lg font-bold {{ ($volunteer->trust_score ?? 100) >= 80 ? 'text-emerald-600 dark:text-emerald-400' : (($volunteer->trust_score ?? 100) >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                            {{ $volunteer->trust_score ?? 100 }}<span class="text-xs font-normal text-gray-400">/100</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Trust Score</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== KPI STRIP ===== --}}
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['label' => 'Verified Projects', 'value' => $stats['verified_projects'],  'color' => 'violet',  'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label' => 'Expert Approved',   'value' => $stats['expert_approved'],    'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Verified Hours',    'value' => $stats['verified_hours'],     'color' => 'blue',    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Industries',        'value' => $stats['industries_count'],   'color' => 'sky',     'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Success Rate',      'value' => $stats['success_rate'] . '%', 'color' => 'amber',  'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
            ['label' => 'Tasks Done',        'value' => $stats['completed_tasks'],    'color' => 'gray',    'icon' => 'M5 13l4 4L19 7'],
        ] as $kpi)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 text-center">
            <div class="text-xl font-bold text-{{ $kpi['color'] }}-600 dark:text-{{ $kpi['color'] }}-400">{{ $kpi['value'] }}</div>
            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-tight">{{ $kpi['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ===== MAIN COLUMN ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- VERIFIED PORTFOLIO CERTIFICATES --}}
            @if($certificates->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Verified Portfolio</h2>
                    <span class="ml-auto text-xs text-gray-400 dark:text-gray-500">{{ $certificates->count() }} {{ Str::plural('credential', $certificates->count()) }}</span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($certificates as $cert)
                    <div class="px-5 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            {{-- Left: cert type icon --}}
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                                {{ $cert->isExpertApproved() ? 'bg-emerald-100 dark:bg-emerald-500/15' : 'bg-violet-100 dark:bg-violet-500/15' }}">
                                @if($cert->isExpertApproved())
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @else
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                {{-- Title row --}}
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $cert->challenge->title ?? 'Challenge' }}</h3>
                                    @if($cert->isExpertApproved())
                                    <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 rounded-full">Expert Approved</span>
                                    @endif
                                </div>

                                {{-- Meta row --}}
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $cert->role }}</span>
                                    @if($cert->industry)
                                    <span>· {{ $cert->industry }}</span>
                                    @endif
                                    @if($cert->show_company_name && $cert->company)
                                    <span>· {{ $cert->company->company?->company_name ?? $cert->company->name }}</span>
                                    @endif
                                    <span>· {{ number_format($cert->total_hours, 0) }}h</span>
                                    @if($cert->project_duration)
                                    <span>· {{ $cert->project_duration }}</span>
                                    @endif
                                    <span>· {{ $cert->issued_at?->format('M Y') ?? $cert->created_at->format('M Y') }}</span>
                                </div>

                                {{-- Summary --}}
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed mb-2">{{ Str::limit($cert->contribution_summary, 180) }}</p>

                                {{-- Technology tags --}}
                                @if(!empty($cert->technologies))
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach(array_slice($cert->technologies, 0, 5) as $tech)
                                    <span class="text-[10px] px-2 py-0.5 bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300 rounded-full font-medium">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                @endif

                                {{-- Expert approval attribution --}}
                                @if($cert->isExpertApproved() && $cert->expertVolunteer)
                                <p class="text-[11px] text-emerald-600 dark:text-emerald-400">
                                    Approved by {{ $cert->expertVolunteer->user->name ?? 'Expert' }} on {{ $cert->expert_approved_at->format('d M Y') }}
                                </p>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                @if($cert->pdf_path)
                                <a href="{{ $cert->pdf_url }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    PDF
                                </a>
                                @endif
                                <a href="{{ route('certificates.verify') }}?id={{ $cert->certificate_number }}"
                                   class="text-[10px] text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 font-mono">
                                    {{ substr($cert->certificate_number, 0, 16) }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- SKILLS --}}
            @if($volunteer->skills->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Skills & Expertise</h2>
                    <span class="text-xs text-gray-400 dark:text-gray-500">({{ $volunteer->skills->count() }})</span>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($volunteer->skills as $skill)
                        <div class="bg-gray-50 dark:bg-gray-900/40 rounded-lg p-3 border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $skill->skill_name }}</h3>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded border
                                    {{ $skill->proficiency_level === 'expert'       ? 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30' : '' }}
                                    {{ $skill->proficiency_level === 'advanced'     ? 'bg-blue-50 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-500/30' : '' }}
                                    {{ $skill->proficiency_level === 'intermediate' ? 'bg-amber-50 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-500/30' : '' }}
                                    {{ $skill->proficiency_level === 'beginner'     ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600' : '' }}">
                                    {{ ucfirst($skill->proficiency_level) }}
                                </span>
                            </div>
                            @if($skill->years_of_experience)
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $skill->years_of_experience }} yrs experience</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- COMMUNITY IDEAS --}}
            @if($ideas->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Community Ideas</h2>
                    <span class="text-xs text-gray-400 dark:text-gray-500">({{ $ideas->count() }})</span>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($ideas as $idea)
                    <a href="{{ route('ideas.show', $idea->id) }}" class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white hover:text-violet-600 dark:hover:text-violet-400 mb-0.5">{{ $idea->title }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $idea->challenge->title ?? '' }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($idea->description, 120) }}</p>
                        </div>
                        @if($idea->status === 'scored')
                        <div class="shrink-0 text-center px-3 py-2 bg-amber-50 dark:bg-amber-500/10 rounded-lg border border-amber-100 dark:border-amber-500/20">
                            <div class="text-sm font-bold text-amber-700 dark:text-amber-300">{{ round($idea->final_score) }}</div>
                            <div class="text-[10px] text-amber-600 dark:text-amber-400">score</div>
                        </div>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <div class="space-y-4">

            {{-- Reputation tier --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: {{ $volunteer->tier_color ?? '#6366f1' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Reputation</h3>
                </div>
                <div class="p-4">
                    @php
                        $tiers = [
                            ['name' => 'Explorer',        'min' => 0,    'max' => 49,   'color' => '#6b7280'],
                            ['name' => 'Contributor',     'min' => 50,   'max' => 199,  'color' => '#3b82f6'],
                            ['name' => 'Trusted Member',  'min' => 200,  'max' => 499,  'color' => '#8b5cf6'],
                            ['name' => 'Expert Candidate','min' => 500,  'max' => 1199, 'color' => '#f59e0b'],
                            ['name' => 'Certified Expert','min' => 1200, 'max' => null, 'color' => '#10b981'],
                        ];
                        $stars = $volunteer->stars ?? $volunteer->reputation_score;
                    @endphp
                    <div class="space-y-2 mb-4">
                        @foreach($tiers as $tier)
                        @php
                            $active  = $stars >= $tier['min'] && ($tier['max'] === null || $stars <= $tier['max']);
                            $passed  = $tier['max'] !== null && $stars > $tier['max'];
                        @endphp
                        <div class="flex items-center gap-2 text-xs {{ $active ? 'font-bold' : ($passed ? 'opacity-50' : 'opacity-30') }}">
                            <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $active || $passed ? '' : 'border border-gray-300 dark:border-gray-600' }}"
                                 style="{{ $active || $passed ? 'background:' . $tier['color'] : '' }}"></div>
                            <span class="{{ $active ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">{{ $tier['name'] }}</span>
                            <span class="ml-auto text-gray-400 dark:text-gray-500">{{ $tier['min'] }}{{ $tier['max'] ? '–'.$tier['max'] : '+' }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Progress to next tier --}}
                    @php
                        $nextTier = collect($tiers)->first(fn($t) => $t['min'] > $stars);
                    @endphp
                    @if($nextTier)
                    @php
                        $prevMin = collect($tiers)->last(fn($t) => $t['min'] <= $stars)['min'] ?? 0;
                        $pct     = $nextTier['min'] > $prevMin ? min(100, (($stars - $prevMin) / ($nextTier['min'] - $prevMin)) * 100) : 100;
                    @endphp
                    <div class="mt-2">
                        <div class="flex justify-between text-[11px] text-gray-500 dark:text-gray-400 mb-1">
                            <span>{{ $stars }} stars</span>
                            <span>{{ $nextTier['min'] }} for {{ $nextTier['name'] }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                            <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $nextTier['color'] }}"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Industry Breakdown --}}
            @if($industryBreakdown->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Industries</h3>
                </div>
                <div class="p-4 space-y-2">
                    @foreach($industryBreakdown as $industry => $count)
                    <div class="flex items-center gap-2">
                        <div class="flex-1 text-xs text-gray-700 dark:text-gray-300 truncate">{{ $industry }}</div>
                        <span class="text-xs font-semibold text-sky-600 dark:text-sky-400">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Verified Technology Stack --}}
            @if($allTechnologies->count() > 0)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Verified Disciplines</h3>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($allTechnologies as $tech => $count)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300 border border-violet-200 dark:border-violet-500/20 rounded-full text-xs font-medium">
                            {{ $tech }}
                            @if($count > 1)
                            <span class="text-[10px] text-violet-500 dark:text-violet-400">×{{ $count }}</span>
                            @endif
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- No certificates yet --}}
            @if($certificates->count() === 0)
            <div class="bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                <p class="text-xs text-gray-500 dark:text-gray-400">No verified certificates yet.<br>Complete a challenge to earn one.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
