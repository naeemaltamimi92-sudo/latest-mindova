@extends('layouts.admin')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))
@section('page-subtitle', now()->format('l, F j, Y'))

@section('content')

{{-- Hero welcome banner --}}
<div class="adm-reveal relative overflow-hidden rounded-2xl mb-6" style="background: linear-gradient(135deg, #775FEE 0%, #5A3DEB 50%, #4338CA 100%);">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    </div>
    <div class="relative px-6 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 mb-2">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-semibold text-white/90">{{ __('All systems operational') }}</span>
            </div>
            <h2 class="text-2xl font-black text-white">{{ __('Welcome back,') }} {{ auth()->user()->name }}!</h2>
            <p class="text-white/70 text-sm mt-1">{{ __('Here\'s what\'s happening on your platform today.') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.challenges.analytics') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-semibold transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                </svg>
                {{ __('Analytics') }}
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 text-sm font-bold hover:bg-white/90 transition shadow">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('Settings') }}
            </a>
        </div>
    </div>
</div>

{{-- Primary stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $primaryStats = [
        ['label' => __('Total Users'), 'value' => number_format($stats['total_users']), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'from-violet-500 to-indigo-600', 'badge' => __('Active Platform'), 'badge_class' => 'text-emerald-600 bg-emerald-50'],
        ['label' => __('Contributors'), 'value' => number_format($stats['total_volunteers']), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'from-emerald-500 to-teal-600', 'badge' => __('ACTIVE'), 'badge_class' => 'text-emerald-600 bg-emerald-50'],
        ['label' => __('Companies'), 'value' => number_format($stats['total_companies']), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'from-blue-500 to-cyan-600', 'badge' => __('VERIFIED'), 'badge_class' => 'text-blue-600 bg-blue-50'],
        ['label' => __('Active Challenges'), 'value' => number_format($stats['active_challenges']), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'from-orange-500 to-amber-500', 'badge' => __('LIVE'), 'badge_class' => 'text-orange-600 bg-orange-50'],
    ];
    @endphp
    @foreach($primaryStats as $i => $stat)
    <div class="adm-stat adm-reveal" style="animation-delay: {{ $i * 60 }}ms">
        <div class="flex items-start justify-between mb-4">
            <div class="h-11 w-11 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center shadow-lg">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <span class="text-xs font-bold {{ $stat['badge_class'] }} px-2 py-1 rounded-full">{{ $stat['badge'] }}</span>
        </div>
        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Secondary stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
    $secondaryStats = [
        ['label' => __('Total Challenges'), 'value' => number_format($stats['total_challenges']), 'icon_bg' => 'bg-indigo-100 dark:bg-indigo-900/40', 'icon_color' => 'text-indigo-600', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label' => __('Total Tasks'), 'value' => number_format($stats['total_tasks'] ?? 0), 'icon_bg' => 'bg-emerald-100 dark:bg-emerald-900/40', 'icon_color' => 'text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => __('Assignments'), 'value' => number_format($stats['total_assignments']), 'icon_bg' => 'bg-blue-100 dark:bg-blue-900/40', 'icon_color' => 'text-blue-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['label' => __('Certificates'), 'value' => number_format($stats['total_certificates']), 'icon_bg' => 'bg-amber-100 dark:bg-amber-900/40', 'icon_color' => 'text-amber-600', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
    ];
    @endphp
    @foreach($secondaryStats as $i => $s)
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-100 dark:border-slate-700 hover:shadow-md transition adm-reveal" style="animation-delay: {{ ($i + 4) * 60 }}ms">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl {{ $s['icon_bg'] }} flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 {{ $s['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $s['value'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Quick actions --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5 mb-6 adm-reveal">
    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">{{ __('Quick Actions') }}</h3>
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
        @php
        $quickActions = [
            ['label' => __('Challenges'), 'href' => route('admin.challenges.index'), 'color' => 'from-violet-500 to-indigo-600', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => __('Contributors'), 'href' => route('admin.volunteers.index'), 'color' => 'from-emerald-500 to-teal-600', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => __('Companies'), 'href' => route('admin.companies.index'), 'color' => 'from-blue-500 to-cyan-600', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label' => __('Analytics'), 'href' => route('admin.challenges.analytics'), 'color' => 'from-purple-500 to-pink-600', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10'],
            ['label' => __('Settings'), 'href' => route('admin.settings.index'), 'color' => 'from-teal-500 to-green-600', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ['label' => __('Export'), 'href' => route('admin.challenges.export'), 'color' => 'from-rose-500 to-pink-600', 'icon' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ];
        @endphp
        @foreach($quickActions as $action)
        <a href="{{ $action['href'] }}"
           class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition group">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $action['color'] }} flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 text-center">{{ $action['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>

{{-- Challenge status distribution --}}
@if(!empty($challengesByStatus))
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 p-5 mb-6 adm-reveal">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ __('Challenge Status Distribution') }}</h3>
        <a href="{{ route('admin.challenges.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">{{ __('View All') }}</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
        $statusMeta = [
            'submitted' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'border' => 'border-amber-200 dark:border-amber-700/30', 'text' => 'text-amber-700 dark:text-amber-400', 'dot' => 'bg-amber-500'],
            'active' => ['bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'border' => 'border-emerald-200 dark:border-emerald-700/30', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],
            'in_progress' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-200 dark:border-blue-700/30', 'text' => 'text-blue-700 dark:text-blue-400', 'dot' => 'bg-blue-500'],
            'completed' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'border' => 'border-indigo-200 dark:border-indigo-700/30', 'text' => 'text-indigo-700 dark:text-indigo-400', 'dot' => 'bg-indigo-500'],
            'delivered' => ['bg' => 'bg-teal-50 dark:bg-teal-900/20', 'border' => 'border-teal-200 dark:border-teal-700/30', 'text' => 'text-teal-700 dark:text-teal-400', 'dot' => 'bg-teal-500'],
            'archived' => ['bg' => 'bg-gray-50 dark:bg-gray-700/40', 'border' => 'border-gray-200 dark:border-gray-600', 'text' => 'text-gray-600 dark:text-gray-400', 'dot' => 'bg-gray-400'],
        ];
        @endphp
        @foreach($challengesByStatus as $status => $count)
        @php $m = $statusMeta[$status] ?? $statusMeta['archived']; @endphp
        <div class="flex flex-col items-center text-center p-3 rounded-xl {{ $m['bg'] }} border {{ $m['border'] }}">
            <span class="h-2 w-2 rounded-full {{ $m['dot'] }} mb-2"></span>
            <p class="text-2xl font-black {{ $m['text'] }}">{{ $count }}</p>
            <p class="text-xs font-semibold {{ $m['text'] }} mt-1 capitalize">{{ __(ucfirst(str_replace('_', ' ', $status))) }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Main grid: recent challenges + top contributors --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Recent Challenges --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden adm-reveal">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                    <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Recent Challenges') }}</h3>
            </div>
            <a href="{{ route('admin.challenges.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                {{ __('View All') }}
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-slate-700">
            @forelse($recentChallenges as $challenge)
            @php
            $sc = ['submitted'=>'bg-amber-100 text-amber-700','active'=>'bg-emerald-100 text-emerald-700','in_progress'=>'bg-blue-100 text-blue-700','completed'=>'bg-indigo-100 text-indigo-700','delivered'=>'bg-teal-100 text-teal-700','archived'=>'bg-gray-100 text-gray-600'];
            @endphp
            <a href="{{ route('admin.challenges.show', $challenge) }}"
               class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition group">
                <div class="h-9 w-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-100">
                    <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-indigo-600">{{ $challenge->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $challenge->company ? ($challenge->company->company_name ?? $challenge->company->user->name) : ($challenge->volunteer ? $challenge->volunteer->user->name : __('Community')) }}
                        &bull; {{ $challenge->created_at->diffForHumans() }}
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $sc[$challenge->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ __(ucfirst(str_replace('_', ' ', $challenge->status))) }}
                </span>
            </a>
            @empty
            <div class="py-12 text-center">
                <div class="h-12 w-12 rounded-xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No challenges yet') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Top Contributors --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden adm-reveal">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Top Contributors') }}</h3>
            </div>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-slate-700">
            @forelse($topVolunteers as $index => $volunteer)
            <a href="{{ route('admin.volunteers.show', $volunteer) }}"
               class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition group">
                <div class="relative flex-shrink-0">
                    @if($volunteer->profile_picture)
                    <img src="{{ asset('storage/' . $volunteer->profile_picture) }}" alt="{{ $volunteer->user->name }}"
                         class="h-9 w-9 rounded-full object-cover ring-2 ring-white dark:ring-slate-700">
                    @else
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center ring-2 ring-white dark:ring-slate-700">
                        <span class="text-white font-bold text-xs">{{ substr($volunteer->user->name, 0, 2) }}</span>
                    </div>
                    @endif
                    @if($index < 3)
                    <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full text-[10px] font-black flex items-center justify-center border border-white dark:border-slate-700
                        {{ $index === 0 ? 'bg-amber-400 text-white' : ($index === 1 ? 'bg-gray-300 text-gray-700' : 'bg-orange-400 text-white') }}">
                        {{ $index + 1 }}
                    </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-emerald-600">{{ $volunteer->user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $volunteer->field ?? __('No field') }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-black text-emerald-600">{{ number_format($volunteer->reputation_score) }}</p>
                    <p class="text-[10px] text-gray-400">{{ __('pts') }}</p>
                </div>
            </a>
            @empty
            <div class="py-12 text-center">
                <p class="text-sm text-gray-400">{{ __('No contributors yet') }}</p>
            </div>
            @endforelse
        </div>
        @if($topVolunteers->count() > 0)
        <div class="px-5 py-3 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-100 dark:border-slate-700">
            <a href="{{ route('admin.volunteers.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">{{ __('View All Contributors') }} &rarr;</a>
        </div>
        @endif
    </div>
</div>

{{-- Active Companies --}}
@if($activeCompanies->count() > 0)
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden mb-6 adm-reveal">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Active Companies') }}</h3>
            <span class="text-xs font-bold text-blue-700 bg-blue-100 dark:bg-blue-900/40 dark:text-blue-400 px-2 py-0.5 rounded-full">{{ $activeCompanies->count() }}</span>
        </div>
        <a href="{{ route('admin.companies.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">{{ __('View All') }} &rarr;</a>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($activeCompanies as $company)
        <a href="{{ route('admin.companies.show', $company) }}"
           class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 dark:bg-slate-700/40 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-gray-100 dark:border-slate-600 hover:border-blue-200 transition group">
            @if($company->logo_path)
            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }}" class="h-10 w-10 rounded-xl object-cover">
            @else
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-sm">{{ substr($company->company_name ?? $company->user->name, 0, 2) }}</span>
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600">{{ $company->company_name ?? $company->user->name }}</p>
                <p class="text-xs text-gray-400">{{ $company->challenges_count }} {{ __('challenges') }}</p>
            </div>
            <span class="h-2 w-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ═══ ACTIVITY CENTER ═══ --}}
<div id="activity-center" class="mb-2">

    {{-- Section header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 adm-reveal">
        <div>
            <h3 class="text-base font-black text-gray-900 dark:text-white leading-tight">{{ __('Activity Center') }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Recent platform activity') }}</p>
        </div>
        <button onclick="location.reload()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 text-xs font-semibold hover:border-violet-300 hover:text-violet-600 dark:hover:text-violet-400 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            {{ __('Refresh') }}
        </button>
    </div>

    {{-- Platform stats --}}
    <div class="ac-stats-grid mb-5 adm-reveal">
        @php
        $pulseStats = [
            ['label' => __('Contributors'), 'value' => $stats['total_volunteers'], 'color' => '#10b981', 'grad' => 'linear-gradient(135deg,#10b981,#059669)', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['label' => __('Companies'), 'value' => $stats['total_companies'], 'color' => '#3b82f6', 'grad' => 'linear-gradient(135deg,#3b82f6,#0ea5e9)', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label' => __('Active Challenges'), 'value' => $stats['active_challenges'], 'color' => '#8b5cf6', 'grad' => 'linear-gradient(135deg,#8b5cf6,#5a3deb)', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label' => __('Total Tasks'), 'value' => $stats['total_tasks'] ?? 0, 'color' => '#f59e0b', 'grad' => 'linear-gradient(135deg,#f59e0b,#d97706)', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
            ['label' => __('Certificates Issued'), 'value' => $stats['total_certificates'], 'color' => '#14b8a6', 'grad' => 'linear-gradient(135deg,#14b8a6,#0d9488)', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label' => __('Completed Tasks'), 'value' => $stats['completed_tasks'], 'color' => '#a855f7', 'grad' => 'linear-gradient(135deg,#a855f7,#7c3aed)', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => __('Total Users'), 'value' => $stats['total_users'], 'color' => '#06b6d4', 'grad' => 'linear-gradient(135deg,#06b6d4,#0891b2)', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ];
        @endphp
        @foreach($pulseStats as $i => $ps)
        <div class="ac-pulse-stat" style="--ac-color:{{ $ps['color'] }}">
            <div class="ac-pulse-stat-icon" style="background:{{ $ps['grad'] }}">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ps['icon'] }}"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="{{ $ps['value'] }}" id="ac-stat-{{ $i }}">0</p>
                <p class="ac-pulse-label">{{ $ps['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Feed container --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden adm-reveal">

        {{-- Filter bar --}}
        <div class="px-5 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2 overflow-x-auto" style="scrollbar-width:none;-webkit-overflow-scrolling:touch">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 flex-shrink-0 pr-2">{{ __('Filter') }}</span>
            <div class="flex items-center gap-1.5 flex-nowrap">
                <button onclick="acSetFilter('all')"         data-filter="all"         class="ac-filter-btn ac-active">{{ __('All') }}</button>
                <button onclick="acSetFilter('challenge')"   data-filter="challenge"   class="ac-filter-btn">{{ __('Challenges') }}</button>
                <button onclick="acSetFilter('certificate')" data-filter="certificate" class="ac-filter-btn">{{ __('Certificates') }}</button>
                <button onclick="acSetFilter('task')"        data-filter="task"        class="ac-filter-btn">{{ __('Tasks') }}</button>
            </div>
        </div>

        {{-- Activity feed (server-rendered, real data) --}}
        <div id="ac-feed" class="divide-y divide-gray-50 dark:divide-slate-700/60">
            @forelse($activityFeed as $item)
            @php
            $acIcons = [
                'challenge'   => ['bg' => 'from-violet-600 to-indigo-600', 'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                'certificate' => ['bg' => 'from-amber-500 to-yellow-500', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'task'        => ['bg' => 'from-emerald-500 to-teal-600', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            $acIcon = $acIcons[$item['type']] ?? $acIcons['challenge'];
            @endphp
            <div class="ac-card flex items-start gap-4 px-5 py-4" data-type="{{ $item['type'] }}">
                <div class="relative flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $acIcon['bg'] }} flex items-center justify-center shadow-sm">
                        <span class="text-white font-black text-sm">{{ strtoupper(substr($item['actor_name'], 0, 2)) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 flex-wrap">
                        <div>
                            <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $item['actor_name'] }}</span>
                            <span class="text-gray-300 dark:text-gray-600 mx-1.5">&bull;</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item['actor_role'] }}</span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">{{ $item['created_at']->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 leading-snug">
                        {{ $item['verb'] }} <span class="font-semibold text-gray-800 dark:text-white">&ldquo;{{ $item['title'] }}&rdquo;</span>
                    </p>
                    @if($item['url'])
                    <a href="{{ $item['url'] }}" class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline inline-flex items-center gap-1 mt-2">
                        {{ __('View') }} <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-14 text-center">
                <div class="h-12 w-12 rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('No activity yet') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ─── Activity Center ───────────────────────────────────── */
.ac-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
@media (min-width: 640px)  { .ac-stats-grid { grid-template-columns: repeat(4, 1fr); } }
@media (min-width: 1280px) { .ac-stats-grid { grid-template-columns: repeat(7, 1fr); } }

.ac-pulse-stat {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 14px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: transform .25s ease, box-shadow .25s ease;
    position: relative;
    overflow: hidden;
}
.ac-pulse-stat::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: var(--ac-color, #5a3deb);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s ease;
}
.ac-pulse-stat:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
.ac-pulse-stat:hover::after { transform: scaleX(1); }
html.dark .ac-pulse-stat { background: #1e293b; border-color: #334155; }

.ac-pulse-stat-icon {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,.18);
}
.ac-pulse-num {
    font-size: 1.2rem; font-weight: 900;
    color: #111827; line-height: 1;
    transition: color .35s;
}
html.dark .ac-pulse-num { color: #f1f5f9; }
.ac-pulse-label { font-size: .65rem; color: #9ca3af; font-weight: 600; margin-top: 3px; line-height: 1.2; }

.ac-live-dot {
    position: absolute; top: 8px; right: 8px;
    width: 6px; height: 6px; border-radius: 50%;
    animation: ac-blink 2.2s ease-in-out infinite;
}
@keyframes ac-blink { 0%,100%{opacity:1} 50%{opacity:.3} }

/* Filter buttons */
.ac-filter-btn {
    padding: 5px 13px; border-radius: 999px;
    font-size: .73rem; font-weight: 600;
    background: transparent; color: #6b7280;
    border: 1px solid transparent;
    cursor: pointer; transition: all .18s ease;
    white-space: nowrap;
}
.ac-filter-btn:hover { background: #f3f4f6; color: #374151; border-color: #e5e7eb; }
.ac-filter-btn.ac-active {
    background: linear-gradient(135deg,#775fee,#5a3deb,#4338ca);
    color: #fff; border-color: transparent;
    box-shadow: 0 2px 8px rgba(90,61,235,.3);
}
html.dark .ac-filter-btn { color: #9ca3af; }
html.dark .ac-filter-btn:hover { background: #334155; color: #e2e8f0; border-color: #475569; }

/* Activity cards */
.ac-card { transition: background .18s ease; }
.ac-card:hover { background: #f9fafb; }
html.dark .ac-card:hover { background: rgba(51,65,85,.4); }

/* Skeleton shimmer */
.ac-skel {
    background: linear-gradient(90deg,#f3f4f6 25%,#e9ebee 50%,#f3f4f6 75%);
    background-size: 200% 100%;
    animation: ac-shimmer 1.5s ease-in-out infinite;
}
html.dark .ac-skel {
    background: linear-gradient(90deg,#1e293b 25%,#2d3d52 50%,#1e293b 75%);
    background-size: 200% 100%;
}
@keyframes ac-shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Count up the real stat numbers on load (purely cosmetic, no fake data).
    document.querySelectorAll('.ac-pulse-num').forEach(el => {
        const target = parseInt(el.dataset.target, 10) || 0;
        const dur = 900, start = performance.now();
        const step = now => {
            const p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target);
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    });
});

function acSetFilter(type) {
    document.querySelectorAll('.ac-filter-btn').forEach(b => b.classList.toggle('ac-active', b.dataset.filter === type));
    document.querySelectorAll('#ac-feed .ac-card').forEach(card => {
        card.classList.toggle('hidden', type !== 'all' && card.dataset.type !== type);
    });
}
</script>
@endpush

