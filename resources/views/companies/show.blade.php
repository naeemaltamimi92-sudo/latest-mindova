@extends('layouts.app')

@section('title', $company->company_name . ' - ' . __('Company Profile'))

@section('content')
<!-- Premium Hero Header -->
<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-16 mb-12 rounded-3xl shadow-2xl">
    <!-- Animated Background Effects -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-400/20 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-violet-400/20 via-transparent to-transparent"></div>
    </div>
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl animate-float"></div>
        <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6 sm:px-8">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
            <!-- Company Logo -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500 rounded-3xl blur opacity-40 group-hover:opacity-60 transition duration-500"></div>
                @if($company->logo_path)
                <img src="{{ asset('storage/' . $company->logo_path) }}"
                     alt="{{ $company->company_name }}"
                     class="relative w-32 h-32 lg:w-40 lg:h-40 rounded-2xl object-cover shadow-2xl ring-4 ring-white/20">
                @else
                <div class="relative w-32 h-32 lg:w-40 lg:h-40 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-2xl ring-4 ring-white/20">
                    <span class="text-4xl lg:text-5xl font-black text-white">{{ substr($company->company_name, 0, 2) }}</span>
                </div>
                @endif
            </div>

            <!-- Company Info -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-4">
                    @if($company->industry)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-sm font-semibold text-white/90">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $company->industry }}
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 rounded-full text-sm font-semibold text-emerald-300">
                        <div class="relative">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <div class="absolute inset-0 w-2 h-2 bg-emerald-400 rounded-full animate-ping"></div>
                        </div>
                        {{ __('Verified Company') }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 tracking-tight">
                    {{ $company->company_name }}
                </h1>

                @if($company->description)
                <p class="text-lg text-white/80 max-w-2xl leading-relaxed mb-6">
                    {{ Str::limit($company->description, 200) }}
                </p>
                @endif

                <!-- Quick Actions -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    @if($company->website)
                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        {{ __('Visit Website') }}
                    </a>
                    @endif
                    <div class="inline-flex items-center gap-2 text-white/70 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('Member since :date', ['date' => $company->created_at->format('F Y')]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 sm:px-8">
    <!-- Premium Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12 -mt-8 relative z-10">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['total_challenges'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Total Challenges') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-emerald-600">{{ $stats['active_challenges'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Active Now') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-violet-600">{{ $stats['completed_challenges'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Completed') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-amber-600">{{ $stats['total_ideas'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Community Ideas') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Active Challenges -->
            @if($activeChallenges->count() > 0)
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-8 py-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Active Challenges') }}</h2>
                            <p class="text-sm text-slate-600">{{ __(':count challenges currently in progress', ['count' => $activeChallenges->count()]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($activeChallenges as $challenge)
                    <a href="{{ route('challenges.show', $challenge->id) }}"
                       class="block px-8 py-6 hover:bg-slate-50 transition-all group">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold
                                        @if($challenge->status === 'active') bg-emerald-100 text-emerald-700 border border-emerald-200
                                        @elseif($challenge->status === 'analyzing') bg-amber-100 text-amber-700 border border-amber-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        <div class="w-1.5 h-1.5 rounded-full
                                            @if($challenge->status === 'active') bg-emerald-500 animate-pulse
                                            @elseif($challenge->status === 'analyzing') bg-amber-500 animate-pulse
                                            @else bg-slate-500 @endif"></div>
                                        {{ ucfirst($challenge->status) }}
                                    </span>
                                    @if($challenge->challenge_type === 'team_execution')
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold">
                                        {{ __('Team Execution') }}
                                    </span>
                                    @else
                                    <span class="px-3 py-1 bg-violet-100 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold">
                                        {{ __('Community Ideas') }}
                                    </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">
                                    {{ $challenge->title }}
                                </h3>

                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-4">
                                    {{ Str::limit($challenge->refined_brief ?? $challenge->initial_brief, 180) }}
                                </p>

                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                        <span class="font-semibold">{{ __('Level') }} {{ $challenge->complexity_level }}/4</span>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $challenge->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-indigo-600 font-semibold text-sm group-hover:translate-x-1 transition-transform">
                                {{ __('View Details') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Completed Challenges -->
            @if($completedChallenges->count() > 0)
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 px-8 py-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Completed Challenges') }}</h2>
                            <p class="text-sm text-slate-600">{{ __(':count challenges successfully completed', ['count' => $completedChallenges->count()]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($completedChallenges as $challenge)
                    <a href="{{ route('challenges.show', $challenge->id) }}"
                       class="block px-8 py-6 hover:bg-slate-50 transition-all group">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Completed') }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">
                                    {{ $challenge->title }}
                                </h3>

                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-4">
                                    {{ Str::limit($challenge->refined_brief ?? $challenge->initial_brief, 180) }}
                                </p>

                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                    @if($challenge->challenge_type === 'team_execution')
                                    <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $challenge->workstreams->sum(function($ws) { return $ws->tasks->where('status', 'completed')->count(); }) }} {{ __('tasks completed') }}
                                    </span>
                                    @else
                                    <span class="flex items-center gap-1.5 text-violet-600 font-semibold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                        {{ $challenge->ideas->count() }} {{ __('ideas submitted') }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $challenge->updated_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-indigo-600 font-semibold text-sm group-hover:translate-x-1 transition-transform">
                                {{ __('View Details') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- No Challenges State -->
            @if($activeChallenges->count() === 0 && $completedChallenges->count() === 0)
            <div class="bg-gradient-to-br from-slate-50 to-indigo-50/50 rounded-3xl p-12 text-center border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-gradient-to-br from-slate-200 to-slate-300 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No Challenges Yet') }}</h3>
                <p class="text-slate-600">{{ __("This company hasn't submitted any challenges yet.") }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Challenge Types Distribution -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-violet-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        {{ __('Challenge Types') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">{{ __('Team Execution') }}</span>
                        </div>
                        <span class="text-2xl font-black text-indigo-600">{{ $stats['team_challenges'] }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-violet-50 rounded-xl border border-violet-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">{{ __('Community Ideas') }}</span>
                        </div>
                        <span class="text-2xl font-black text-violet-600">{{ $stats['community_challenges'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Complexity Distribution -->
            @if($stats['complexity_distribution']->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        {{ __('Complexity Levels') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($stats['complexity_distribution'] as $level => $count)
                    @php
                        $percentage = $stats['total_challenges'] > 0 ? ($count / $stats['total_challenges']) * 100 : 0;
                        $colors = [
                            1 => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-100'],
                            2 => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-100'],
                            3 => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-100'],
                            4 => ['bg' => 'bg-red-500', 'light' => 'bg-red-100'],
                        ];
                        $color = $colors[$level] ?? ['bg' => 'bg-slate-500', 'light' => 'bg-slate-100'];
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-slate-700">{{ __('Level') }} {{ $level }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ $count }} <span class="text-slate-400 font-normal">({{ round($percentage) }}%)</span></span>
                        </div>
                        <div class="h-3 {{ $color['light'] }} rounded-full overflow-hidden">
                            <div class="h-full {{ $color['bg'] }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tasks Overview -->
            @if($stats['total_tasks'] > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        {{ __('Tasks Overview') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-4xl font-black text-teal-600 mb-1">{{ $stats['total_tasks'] }}</div>
                            <div class="text-sm text-slate-500 font-medium">{{ __('Total Tasks Created') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Member Info -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold">{{ __('Member Since') }}</h3>
                        <p class="text-slate-300 text-sm">{{ $company->created_at->format('F Y') }}</p>
                    </div>
                </div>
                <div class="text-sm text-slate-400">
                    {{ __('Active for :time', ['time' => $company->created_at->diffForHumans(['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW])]) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
