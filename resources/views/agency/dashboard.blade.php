@extends('layouts.app')

@section('title', $portal->agency_name . ' — Recruitment Dashboard')

@section('content')

{{-- Inject agency brand colors as CSS vars --}}
<style>
    :root {
        --agency-primary:   {{ $portal->primary_color }};
        --agency-secondary: {{ $portal->secondary_color }};
    }
    .agency-btn { background: var(--agency-primary); }
    .agency-btn:hover { opacity: 0.88; }
    .agency-accent { color: var(--agency-primary); }
    .agency-border { border-color: var(--agency-primary); }
    .agency-bg-soft { background: color-mix(in srgb, var(--agency-primary) 10%, transparent); }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ===== AGENCY HEADER ===== --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-8">
        <div class="h-2" style="background: linear-gradient(90deg, {{ $portal->primary_color }}, {{ $portal->secondary_color }})"></div>
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center gap-4">
            @if($portal->logo_path)
            <img src="{{ $portal->logo_url }}" alt="{{ $portal->agency_name }}" class="h-12 object-contain shrink-0">
            @else
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl font-bold shrink-0"
                 style="background: linear-gradient(135deg, {{ $portal->primary_color }}, {{ $portal->secondary_color }})">
                {{ strtoupper(substr($portal->agency_name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $portal->agency_name }}</h1>
                @if($portal->description)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($portal->description, 120) }}</p>
                @endif
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('talent.index') }}"
                   class="px-4 py-2 text-white text-sm font-semibold rounded-lg transition-opacity agency-btn">
                    Search Talent
                </a>
                <a href="{{ route('agency.setup') }}"
                   class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Settings
                </a>
            </div>
        </div>
    </div>

    {{-- ===== KPI STRIP ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Requests',  'value' => $stats['total_requests'], 'key' => 'primary'],
            ['label' => 'Pending',         'value' => $stats['pending'],        'key' => 'amber'],
            ['label' => 'Successfully Hired','value' => $stats['hired'],        'key' => 'emerald'],
            ['label' => 'Declined',        'value' => $stats['declined'],       'key' => 'gray'],
        ] as $kpi)
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 text-center">
            <div class="text-3xl font-bold
                {{ $kpi['key'] === 'primary'  ? 'agency-accent' : '' }}
                {{ $kpi['key'] === 'amber'    ? 'text-amber-600 dark:text-amber-400' : '' }}
                {{ $kpi['key'] === 'emerald'  ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                {{ $kpi['key'] === 'gray'     ? 'text-gray-400 dark:text-gray-500' : '' }}"
                 @if($kpi['key'] === 'primary') style="color: {{ $portal->primary_color }}" @endif>
                {{ $kpi['value'] }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $kpi['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ===== CANDIDATE PIPELINE ===== --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 dark:text-white">Candidate Pipeline</h2>
                    <a href="{{ route('talent.index') }}" class="text-xs font-semibold agency-accent" style="color:{{ $portal->primary_color }}">+ Add Candidate</a>
                </div>

                @if($hireRequests->isEmpty())
                <div class="text-center py-12 text-gray-400 dark:text-gray-500">
                    <p class="text-sm">No hire requests yet.</p>
                    <a href="{{ route('talent.index') }}" class="mt-2 inline-block text-xs font-semibold agency-accent" style="color: {{ $portal->primary_color }}">Browse verified talent →</a>
                </div>
                @else

                {{-- Kanban-style tabs --}}
                <div class="flex border-b border-gray-100 dark:border-gray-700" x-data="{ tab: 'all' }">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'converted' => 'Hired', 'declined' => 'Declined'] as $tab => $label)
                    <button @click="tab = '{{ $tab }}'"
                            :class="tab === '{{ $tab }}' ? 'border-b-2 font-semibold text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                            class="px-4 py-3 text-xs transition-colors"
                            :style="tab === '{{ $tab }}' ? 'border-color: {{ $portal->primary_color }}; color: {{ $portal->primary_color }}' : ''">
                        {{ $label }}
                        @php $count = $tab === 'all' ? $hireRequests->count() : $hireRequests->where('status', $tab)->count() @endphp
                        <span class="ml-1 px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-[10px]">{{ $count }}</span>
                    </button>
                    @endforeach
                </div>

                <div x-data="{ tab: 'all' }" class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($hireRequests as $req)
                    <div x-show="tab === 'all' || tab === '{{ $req->status }}'" class="px-5 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                                     style="background: linear-gradient(135deg, {{ $portal->primary_color }}, {{ $portal->secondary_color }})">
                                    {{ strtoupper(substr($req->volunteer->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $req->volunteer->user->name ?? 'Professional' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $req->position_title }} · {{ $req->typeLabel() }}
                                        · {{ $req->created_at->diffForHumans() }}
                                    </div>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded" style="background:{{ $req->volunteer->tier_color ?? '#6366f1' }}15; color:{{ $req->volunteer->tier_color ?? '#6366f1' }}">
                                            {{ $req->volunteer->tier_name ?? 'Explorer' }}
                                        </span>
                                        <span class="text-[10px] text-gray-400">{{ $req->volunteer->stars ?? $req->volunteer->reputation_score }} ⭐</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    {{ $req->status === 'pending'   ? 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300' : '' }}
                                    {{ $req->status === 'converted' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : '' }}
                                    {{ $req->status === 'declined'  ? 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : '' }}
                                    {{ $req->status === 'withdrawn' ? 'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400' : '' }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                                <a href="{{ route('talent.profile', $req->volunteer) }}"
                                   class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ===== SIDEBAR: TOP TALENT ===== --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Top Ranked Professionals</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($topTalent->take(8) as $i => $vol)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-400 w-4">{{ $i + 1 }}</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                             style="background: {{ $vol->tier_color ?? '#6366f1' }}">
                            {{ strtoupper(substr($vol->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $vol->user->name ?? 'Professional' }}</div>
                            <div class="text-[11px] text-gray-400">{{ $vol->stars ?? $vol->reputation_score }} ⭐ · Trust {{ number_format($vol->trust_score ?? 100, 0) }}</div>
                        </div>
                        <a href="{{ route('talent.hire', $vol) }}"
                           class="shrink-0 text-xs px-2.5 py-1 text-white font-semibold rounded agency-btn">
                           Hire
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('talent.index') }}" class="text-xs font-semibold agency-accent" style="color: {{ $portal->primary_color }}">
                        View all verified professionals →
                    </a>
                </div>
            </div>

            {{-- Portal URL --}}
            <div class="bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Your Public Portal URL</p>
                <p class="text-xs font-mono text-gray-800 dark:text-gray-200 break-all">
                    {{ route('agency.portal', $portal->slug) }}
                </p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-2">Share this with clients so they can browse verified professionals through your branded portal.</p>
            </div>
        </div>
    </div>
</div>
@endsection
