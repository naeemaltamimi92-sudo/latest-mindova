@extends('layouts.app')

@section('title', 'Verified Talent Marketplace — Mindova')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ===== HERO ===== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-violet-600 dark:text-violet-400">Verified Talent</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Find Proven Professionals</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Every profile is backed by verified challenges, company feedback, and expert validation — not just a CV.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ $total }}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">verified professionals</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ===== FILTER SIDEBAR ===== --}}
        <form method="GET" action="{{ route('talent.index') }}" id="filterForm" class="lg:w-64 shrink-0 space-y-4">

            {{-- Search --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Name or skill…"
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 space-y-4">
                <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Filters</h3>

                {{-- Professional Level --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Professional Level</label>
                    <select name="tier" onchange="document.getElementById('filterForm').submit()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All Levels</option>
                        <option value="explorer"         {{ request('tier') === 'explorer'         ? 'selected' : '' }}>Explorer (0–49 ⭐)</option>
                        <option value="contributor"      {{ request('tier') === 'contributor'      ? 'selected' : '' }}>Contributor (50–199 ⭐)</option>
                        <option value="trusted_member"   {{ request('tier') === 'trusted_member'   ? 'selected' : '' }}>Trusted Member (200–499 ⭐)</option>
                        <option value="expert_candidate" {{ request('tier') === 'expert_candidate' ? 'selected' : '' }}>Expert Candidate (500–1199 ⭐)</option>
                        <option value="certified_expert" {{ request('tier') === 'certified_expert' ? 'selected' : '' }}>Certified Expert (1200+ ⭐)</option>
                    </select>
                </div>

                {{-- Industry --}}
                @if($industries->count() > 0)
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Industry</label>
                    <select name="industry" onchange="document.getElementById('filterForm').submit()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">All Industries</option>
                        @foreach($industries as $ind)
                        <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Min Stars --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Minimum Stars</label>
                    <input type="number" name="min_stars" value="{{ request('min_stars') }}" min="0" max="9999" placeholder="0"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                {{-- Min Trust --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Minimum Trust Score</label>
                    <input type="number" name="min_trust" value="{{ request('min_trust') }}" min="0" max="100" placeholder="0"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                {{-- Min Availability --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Min Hours/Week Available</label>
                    <input type="number" name="min_hours" value="{{ request('min_hours') }}" min="0" max="80" placeholder="0"
                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                {{-- Expert approved toggle --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="expert_only" value="1"
                           {{ request('expert_only') ? 'checked' : '' }}
                           onchange="document.getElementById('filterForm').submit()"
                           class="w-4 h-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Expert Approved Only</span>
                </label>

                <button type="submit"
                        class="w-full px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Apply Filters
                </button>

                @if(request()->hasAny(['q','tier','min_stars','min_trust','min_hours','expert_only','industry']))
                <a href="{{ route('talent.index') }}" class="block text-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 mt-1">Clear all</a>
                @endif
            </div>

            {{-- Sort --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                @foreach([
                    'ranking'  => 'Marketplace Ranking',
                    'stars'    => 'Most Stars',
                    'trust'    => 'Highest Trust',
                    'projects' => 'Most Projects',
                    'recent'   => 'Recently Active',
                ] as $val => $label)
                <a href="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                   class="flex items-center gap-2 py-1.5 text-sm {{ $sort === $val ? 'text-violet-600 dark:text-violet-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    @if($sort === $val)
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @else
                    <span class="w-3.5 h-3.5"></span>
                    @endif
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </form>

        {{-- ===== RESULTS GRID ===== --}}
        <div class="flex-1 min-w-0">

            @if($paginated->isEmpty())
            <div class="text-center py-20">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No professionals match your filters</p>
                <a href="{{ route('talent.index') }}" class="mt-3 inline-block text-sm text-violet-600 dark:text-violet-400 hover:underline">Clear filters</a>
            </div>
            @else
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                @foreach($paginated as $i => $volunteer)
                @php
                    $stars    = $volunteer->stars ?? $volunteer->reputation_score;
                    $tierColor = $volunteer->tier_color ?? '#6b7280';
                    $tierName  = $volunteer->tier_name  ?? 'Explorer';
                    $verified  = \App\Models\Certificate::where('user_id', $volunteer->user_id)
                                    ->where('company_confirmed', true)->where('is_revoked', false)->count();
                    $expertApp = \App\Models\Certificate::where('user_id', $volunteer->user_id)
                                    ->whereNotNull('expert_approved_at')->count();
                @endphp
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-lg dark:hover:shadow-gray-900/50 hover:-translate-y-0.5 transition-all">
                    {{-- Tier colour strip --}}
                    <div class="h-1.5" style="background: {{ $tierColor }}"></div>

                    <div class="p-5">
                        {{-- Rank badge + avatar --}}
                        <div class="flex items-start gap-3 mb-4">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white"
                                     style="background: linear-gradient(135deg, {{ $tierColor }}, {{ $tierColor }}99)">
                                    {{ strtoupper(substr($volunteer->user->name ?? 'U', 0, 1)) }}
                                </div>
                                @if($i < 3)
                                <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-amber-400 flex items-center justify-center text-[9px] font-bold text-white">
                                    {{ $i + 1 }}
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-tight mb-0.5 truncate">
                                    {{ $volunteer->user->name ?? 'Professional' }}
                                </h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-semibold px-1.5 py-0.5 rounded"
                                          style="background: {{ $tierColor }}18; color: {{ $tierColor }}">
                                        {{ $tierName }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="text-center">
                                <div class="text-base font-bold" style="color: {{ $tierColor }}">{{ $stars }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500">Stars</div>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($volunteer->trust_score ?? 100, 0) }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500">Trust</div>
                            </div>
                            <div class="text-center">
                                <div class="text-base font-bold text-blue-600 dark:text-blue-400">{{ $verified }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500">Projects</div>
                            </div>
                        </div>

                        {{-- Expert approval badge --}}
                        @if($expertApp > 0)
                        <div class="flex items-center gap-1.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mb-3">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.946.806 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $expertApp }} Expert-Approved {{ Str::plural('Project', $expertApp) }}
                        </div>
                        @endif

                        {{-- Top skills --}}
                        @if($volunteer->skills->count() > 0)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach($volunteer->skills->take(4) as $skill)
                            <span class="text-[10px] px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full font-medium">
                                {{ $skill->skill_name }}
                            </span>
                            @endforeach
                            @if($volunteer->skills->count() > 4)
                            <span class="text-[10px] px-2 py-0.5 text-gray-400 dark:text-gray-500">+{{ $volunteer->skills->count() - 4 }}</span>
                            @endif
                        </div>
                        @endif

                        {{-- Availability --}}
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $volunteer->availability_hours_per_week }}h/week available
                        </div>

                        {{-- CTAs --}}
                        <div class="flex gap-2">
                            <a href="{{ route('talent.profile', $volunteer) }}"
                               class="flex-1 text-center px-3 py-2 border border-gray-200 dark:border-gray-600 hover:border-violet-400 dark:hover:border-violet-500 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg transition-colors">
                                View Profile
                            </a>
                            @auth
                            @if(auth()->user()->company)
                            <a href="{{ route('talent.hire', $volunteer) }}"
                               class="flex-1 text-center px-3 py-2 text-white text-xs font-bold rounded-lg transition-colors"
                               style="background: {{ $tierColor }}">
                                Hire
                            </a>
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($total > $perPage)
            <div class="flex justify-center items-center gap-2">
                @if($page > 1)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}"
                   class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Previous
                </a>
                @endif
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ ($page - 1) * $perPage + 1 }}–{{ min($page * $perPage, $total) }} of {{ $total }}
                </span>
                @if($page * $perPage < $total)
                <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}"
                   class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Next
                </a>
                @endif
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
