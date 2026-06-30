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
    <div class="adm-stat adm-reveal" style="transition-delay: {{ $i * 60 }}ms">
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
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-gray-100 dark:border-slate-700 hover:shadow-md transition adm-reveal" style="transition-delay: {{ ($i + 4) * 60 }}ms">
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
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700/40">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400">{{ __('Live') }}</span>
            </div>
            <div>
                <h3 class="text-base font-black text-gray-900 dark:text-white leading-tight">{{ __('Activity Center') }}</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ __('Engineering command center — real-time platform pulse') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span id="ac-last-updated" class="text-xs text-gray-400 dark:text-gray-500">{{ __('Updated just now') }}</span>
            <button onclick="AC.refresh()" id="ac-refresh-btn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300 text-xs font-semibold hover:border-violet-300 hover:text-violet-600 dark:hover:text-violet-400 transition">
                <svg id="ac-refresh-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('Refresh') }}
            </button>
        </div>
    </div>

    {{-- Live pulse stats --}}
    <div class="ac-stats-grid mb-5 adm-reveal">
        <div class="ac-pulse-stat" style="--ac-color:#10b981">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="24" id="ac-stat-0">0</p>
                <p class="ac-pulse-label">{{ __('Experts Online') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#10b981"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#3b82f6">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#3b82f6,#0ea5e9)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="8" id="ac-stat-1">0</p>
                <p class="ac-pulse-label">{{ __('Companies Active') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#3b82f6"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#8b5cf6">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#5a3deb)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="31" id="ac-stat-2">0</p>
                <p class="ac-pulse-label">{{ __('In Progress') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#8b5cf6"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#f59e0b">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="12" id="ac-stat-3">0</p>
                <p class="ac-pulse-label">{{ __('Under Review') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#f59e0b"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#14b8a6">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#14b8a6,#0d9488)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="5" id="ac-stat-4">0</p>
                <p class="ac-pulse-label">{{ __('Certs Today') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#14b8a6"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#a855f7">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#a855f7,#7c3aed)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="47" id="ac-stat-5">0</p>
                <p class="ac-pulse-label">{{ __('AI Analyses') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#a855f7"></span>
        </div>
        <div class="ac-pulse-stat" style="--ac-color:#06b6d4">
            <div class="ac-pulse-stat-icon" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="ac-pulse-num" data-target="183" id="ac-stat-6">0</p>
                <p class="ac-pulse-label">{{ __('Community Online') }}</p>
            </div>
            <span class="ac-live-dot" style="background:#06b6d4"></span>
        </div>
    </div>

    {{-- Feed container --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden adm-reveal">

        {{-- Filter bar --}}
        <div class="px-5 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2 overflow-x-auto" style="scrollbar-width:none;-webkit-overflow-scrolling:touch">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 flex-shrink-0 pr-2">{{ __('Filter') }}</span>
            <div class="flex items-center gap-1.5 flex-nowrap">
                <button onclick="AC.setFilter('all')"         data-filter="all"         class="ac-filter-btn ac-active">{{ __('All') }}</button>
                <button onclick="AC.setFilter('company')"     data-filter="company"     class="ac-filter-btn">{{ __('Companies') }}</button>
                <button onclick="AC.setFilter('expert')"      data-filter="expert"      class="ac-filter-btn">{{ __('Experts') }}</button>
                <button onclick="AC.setFilter('community')"   data-filter="community"   class="ac-filter-btn">{{ __('Community') }}</button>
                <button onclick="AC.setFilter('ai')"          data-filter="ai"          class="ac-filter-btn">{{ __('AI') }}</button>
                <button onclick="AC.setFilter('certificate')" data-filter="certificate" class="ac-filter-btn">{{ __('Certificates') }}</button>
                <button onclick="AC.setFilter('challenge')"   data-filter="challenge"   class="ac-filter-btn">{{ __('Challenges') }}</button>
            </div>
        </div>

        {{-- Skeleton loader --}}
        <div id="ac-skeleton" class="divide-y divide-gray-50 dark:divide-slate-700/60">
            @for ($sk = 0; $sk < 4; $sk++)
            <div class="flex items-center gap-4 px-5 py-4">
                <div class="ac-skel w-10 h-10 rounded-full flex-shrink-0"></div>
                <div class="flex-1 space-y-2">
                    <div class="ac-skel h-3 rounded-lg" style="width:{{ 120 + $sk * 30 }}px"></div>
                    <div class="ac-skel h-3 rounded-lg" style="width:{{ 220 + $sk * 20 }}px"></div>
                    <div class="ac-skel h-3 rounded-lg" style="width:{{ 90 + $sk * 10 }}px"></div>
                </div>
                <div class="ac-skel h-6 w-20 rounded-lg flex-shrink-0"></div>
            </div>
            @endfor
        </div>

        {{-- Activity feed --}}
        <div id="ac-feed" class="hidden divide-y divide-gray-50 dark:divide-slate-700/60"></div>

        {{-- Empty state --}}
        <div id="ac-empty" class="hidden py-14 text-center">
            <div class="h-12 w-12 rounded-2xl bg-gray-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('No activities found') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('Try a different filter') }}</p>
        </div>

        {{-- Load more --}}
        <div id="ac-loadmore" class="hidden px-5 py-4 border-t border-gray-100 dark:border-slate-700 text-center">
            <button onclick="AC.loadMore()"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gray-50 dark:bg-slate-700/60 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold hover:bg-violet-50 dark:hover:bg-violet-900/20 hover:border-violet-200 hover:text-violet-600 dark:hover:text-violet-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                {{ __('Load More Activities') }}
            </button>
        </div>
    </div>
</div>

{{-- Live-activity toast (bottom-right) --}}
<div id="ac-toast" class="fixed bottom-6 right-6 z-[60] w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-violet-100 dark:border-violet-900/40 p-4 pointer-events-none"
     style="transform:translateY(120px);opacity:0;transition:transform 0.45s cubic-bezier(.4,0,.2,1),opacity 0.45s ease">
    <div class="flex items-start gap-3">
        <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 relative">
            <span class="absolute h-8 w-8 rounded-xl bg-violet-500 opacity-30 animate-ping"></span>
            <span class="h-2 w-2 rounded-full bg-white relative"></span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[10px] font-black text-violet-600 dark:text-violet-400 uppercase tracking-widest mb-0.5">{{ __('New Activity') }}</p>
            <p id="ac-toast-msg" class="text-sm text-gray-700 dark:text-gray-300 leading-snug"></p>
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
const AC = (() => {
    const PAGE = 8;
    let filter = 'all', visible = PAGE;

    const ICONS = {
        ai:          '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>',
        company:     '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>',
        expert:      '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',
        community:   '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2V10a2 2 0 012-2h6z"/></svg>',
        certificate: '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        challenge:   '<svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
    };
    const ICON_BG = {
        ai:'from-purple-500 to-violet-600', company:'from-blue-500 to-cyan-600',
        expert:'from-emerald-500 to-teal-600', community:'from-teal-500 to-cyan-600',
        certificate:'from-amber-500 to-yellow-500', challenge:'from-violet-600 to-indigo-600',
    };
    const BADGE = {
        live:    'text-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400',
        active:  'text-blue-700 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400',
        review:  'text-amber-700 bg-amber-50 dark:bg-amber-900/30 dark:text-amber-400',
        ai:      'text-purple-700 bg-purple-50 dark:bg-purple-900/30 dark:text-purple-400',
        done:    'text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-400',
        cert:    'text-teal-700 bg-teal-50 dark:bg-teal-900/30 dark:text-teal-400',
        submit:  'text-sky-700 bg-sky-50 dark:bg-sky-900/30 dark:text-sky-400',
    };

    let DATA = [
        // AI
        {id:1,  type:'ai',          actor:{name:'Mindova AI',      role:'AI Engine',          initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'completed analysis of',           meta:'Confidence: 94% · 1,247 tokens processed',            challenge:'Predictive Maintenance System',  badge:'ai',     status:'AI Processing', min:2  },
        {id:2,  type:'ai',          actor:{name:'Mindova AI',      role:'AI Engine',          initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'generated insights for',           meta:'Score: 8.7/10 · 12 recommendations produced',         challenge:'Smart Grid Optimization',        badge:'ai',     status:'AI Processing', min:18 },
        {id:3,  type:'ai',          actor:{name:'Mindova AI',      role:'AI Engine',          initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'started analyzing',                meta:'Processing submission #47',                            challenge:'Carbon Capture Analytics',       badge:'ai',     status:'AI Processing', min:34 },
        {id:4,  type:'ai',          actor:{name:'Mindova AI',      role:'AI Engine',          initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'matched volunteers for',           meta:'23 experts evaluated · Top match: 96% fit',           challenge:'Industrial IoT Platform',        badge:'done',   status:'Completed',     min:51 },
        {id:5,  type:'ai',          actor:{name:'Mindova AI',      role:'AI Engine',          initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'decomposed tasks for',             meta:'7 tasks created · Estimated 42 hours total',          challenge:'Digital Twin Architecture',      badge:'done',   status:'Completed',     min:89 },
        // Company
        {id:6,  type:'company',     actor:{name:'Siemens Energy',  role:'Enterprise Company', initials:'SE', grad:'from-blue-500 to-cyan-600'},    verb:'published a new challenge',        meta:'Budget: $15,000 · Deadline: 45 days',                 challenge:'Smart Grid Optimization',        badge:'live',   status:'Live',          min:5  },
        {id:7,  type:'company',     actor:{name:'SABIC',           role:'Enterprise Company', initials:'SA', grad:'from-blue-600 to-indigo-600'},  verb:'accepted the final solution for',  meta:'Rating: 9.4/10 · Full budget released',               challenge:'Carbon Capture Analytics',       badge:'done',   status:'Completed',     min:23 },
        {id:8,  type:'company',     actor:{name:'ABB Technologies', role:'Enterprise Company', initials:'AB', grad:'from-sky-500 to-blue-600'},    verb:'published a new challenge',        meta:'Budget: $8,500 · Deadline: 30 days',                  challenge:'Autonomous Quality Control',     badge:'live',   status:'Live',          min:47 },
        {id:9,  type:'company',     actor:{name:'General Electric', role:'Enterprise Company', initials:'GE', grad:'from-blue-700 to-violet-600'}, verb:'requested a revision for',         meta:'Feedback: 3 points to address',                       challenge:'Predictive Maintenance System',  badge:'review', status:'Reviewing',     min:72 },
        {id:10, type:'company',     actor:{name:'Honeywell',       role:'Enterprise Company', initials:'HW', grad:'from-indigo-500 to-blue-600'},  verb:'published a new challenge',        meta:'Budget: $22,000 · Deadline: 60 days',                 challenge:'Renewable Energy Forecasting',   badge:'live',   status:'Live',          min:105},
        {id:11, type:'company',     actor:{name:'Bosch Industries', role:'Enterprise Company', initials:'BI', grad:'from-cyan-500 to-blue-500'},   verb:'approved milestone 2 for',         meta:'$4,200 paid out · Milestone 3 unlocked',              challenge:'Digital Twin Architecture',      badge:'active', status:'Active Now',    min:148},
        {id:12, type:'company',     actor:{name:'Samsung SDI',     role:'Enterprise Company', initials:'SS', grad:'from-blue-500 to-teal-600'},    verb:'published a new challenge',        meta:'Budget: $11,000 · Deadline: 35 days',                 challenge:'Supply Chain Resilience',        badge:'live',   status:'Live',          min:193},
        // Expert
        {id:13, type:'expert',      actor:{name:'Ahmed Al-Rashidi', role:'ML Engineer',       initials:'AA', grad:'from-emerald-500 to-teal-600'}, verb:'submitted a solution to',          meta:'Score estimate: 89% · 340 hours tracked',             challenge:'Predictive Maintenance System',  badge:'submit', status:'Just Submitted', min:8  },
        {id:14, type:'expert',      actor:{name:'Sarah Johnson',   role:'Systems Architect',  initials:'SJ', grad:'from-teal-500 to-emerald-600'}, verb:'approved Solution #14 for',        meta:'Final review complete · Delivery recommended',        challenge:'Industrial IoT Platform',        badge:'review', status:'Reviewing',     min:15 },
        {id:15, type:'expert',      actor:{name:'Omar Al-Hassan',  role:'Data Scientist',     initials:'OH', grad:'from-violet-500 to-purple-600'}, verb:'earned +25 reputation stars on',  meta:'Milestone: 1,250 total points',                       challenge:'Carbon Capture Analytics',       badge:'active', status:'Active Now',    min:29 },
        {id:16, type:'expert',      actor:{name:'Fatima Al-Zahraa','role':'IoT Specialist',   initials:'FZ', grad:'from-rose-500 to-pink-600'},    verb:'joined the challenge team for',    meta:'Match score: 96% · 3rd team member added',           challenge:'Smart Grid Optimization',        badge:'active', status:'Active Now',    min:42 },
        {id:17, type:'expert',      actor:{name:'Michael Chen',    role:'DevOps Engineer',    initials:'MC', grad:'from-amber-500 to-orange-500'}, verb:'submitted a solution to',          meta:'Score estimate: 91% · Full architecture delivered',   challenge:'Digital Twin Architecture',      badge:'submit', status:'Just Submitted', min:63 },
        {id:18, type:'expert',      actor:{name:'Elena Petrova',   role:'Embedded Systems',   initials:'EP', grad:'from-cyan-500 to-teal-600'},    verb:'started working on',               meta:'Assignment accepted · SLA: 14 days',                  challenge:'Autonomous Quality Control',     badge:'active', status:'Active Now',    min:91 },
        {id:19, type:'expert',      actor:{name:'Yusuf Ibrahim',   role:'Energy Consultant',  initials:'YI', grad:'from-emerald-600 to-green-500'}, verb:'completed all tasks for',         meta:'7/7 tasks done · On-time delivery',                   challenge:'Renewable Energy Forecasting',   badge:'done',   status:'Completed',     min:127},
        {id:20, type:'expert',      actor:{name:'Layla Mansouri',  role:'AI Research Lead',   initials:'LM', grad:'from-violet-600 to-indigo-500'}, verb:'submitted a solution to',         meta:'Score estimate: 94% · Novel ML approach used',        challenge:'Supply Chain Resilience',        badge:'submit', status:'Just Submitted', min:162},
        // Community
        {id:21, type:'community',   actor:{name:'Community Hub',   role:'Community',          initials:'CH', grad:'from-teal-500 to-cyan-600'},    verb:'published a success story on',     meta:'4.8★ rating · 1,200+ views this week',               challenge:'Carbon Capture Analytics',       badge:'done',   status:'Published',     min:38 },
        {id:22, type:'community',   actor:{name:'Khalid Al-Otaibi', role:'Member',            initials:'KA', grad:'from-sky-500 to-blue-600'},     verb:'started a discussion on',          meta:'12 replies · Technical Q&A thread',                   challenge:'Predictive Maintenance System',  badge:'active', status:'Active Now',    min:55 },
        {id:23, type:'community',   actor:{name:'Nour Hassan',     role:'Member',             initials:'NH', grad:'from-pink-500 to-rose-600'},    verb:'shared an expert insight on',      meta:'Upvoted by 18 community members',                     challenge:'Smart Grid Optimization',        badge:'review', status:'Recently Updated', min:80},
        {id:24, type:'community',   actor:{name:'Community Hub',   role:'Community',          initials:'CH', grad:'from-teal-500 to-cyan-600'},    verb:'featured a success story on',      meta:'4.9★ rating · Pinned by Mindova team',               challenge:'Industrial IoT Platform',        badge:'done',   status:'Published',     min:140},
        // Certificates
        {id:25, type:'certificate', actor:{name:'Mindova Platform', role:'Certification',     initials:'MP', grad:'from-amber-500 to-yellow-500'}, verb:'issued a certificate to Omar Al-Hassan for', meta:'Level: Advanced Engineering · Verified', challenge:'Carbon Capture Analytics',       badge:'cert',   status:'Issued',        min:12 },
        {id:26, type:'certificate', actor:{name:'Mindova Platform', role:'Certification',     initials:'MP', grad:'from-amber-500 to-yellow-500'}, verb:'issued a certificate to Sarah Johnson for',  meta:'Level: Systems Architecture · Verified', challenge:'Industrial IoT Platform',        badge:'cert',   status:'Issued',        min:68 },
        {id:27, type:'certificate', actor:{name:'Mindova Platform', role:'Certification',     initials:'MP', grad:'from-amber-500 to-yellow-500'}, verb:'issued a certificate to Yusuf Ibrahim for',  meta:'Level: Energy & Sustainability · Verified', challenge:'Renewable Energy Forecasting', badge:'cert',   status:'Issued',        min:132},
        // Challenge events
        {id:28, type:'challenge',   actor:{name:'Challenge System', role:'Platform',          initials:'CS', grad:'from-violet-600 to-indigo-600'}, verb:'marked as completed:',            meta:'Final delivery accepted · Success story pending',     challenge:'Carbon Capture Analytics',       badge:'done',   status:'Completed',     min:25 },
        {id:29, type:'challenge',   actor:{name:'Challenge System', role:'Platform',          initials:'CS', grad:'from-violet-600 to-indigo-600'}, verb:'entered review phase:',           meta:'3 solutions submitted · Voting now open',             challenge:'Predictive Maintenance System',  badge:'review', status:'Reviewing',     min:60 },
        {id:30, type:'challenge',   actor:{name:'Challenge System', role:'Platform',          initials:'CS', grad:'from-violet-600 to-indigo-600'}, verb:'deadline extended for',           meta:'Extended by 7 days · Company requested',              challenge:'Digital Twin Architecture',      badge:'active', status:'Active Now',    min:115},
        {id:31, type:'challenge',   actor:{name:'Challenge System', role:'Platform',          initials:'CS', grad:'from-violet-600 to-indigo-600'}, verb:'entered delivery phase:',         meta:'Deployment checklist activated',                      challenge:'Smart Grid Optimization',        badge:'active', status:'Active Now',    min:178},
        {id:32, type:'challenge',   actor:{name:'Challenge System', role:'Platform',          initials:'CS', grad:'from-violet-600 to-indigo-600'}, verb:'reached milestone 2:',            meta:'66% complete · On schedule',                          challenge:'Autonomous Quality Control',     badge:'active', status:'Active Now',    min:225},
    ];

    DATA.sort((a,b) => a.min - b.min);

    function fmtTime(m) {
        if (m < 1)  return 'Just now';
        if (m < 60) return m + 'm ago';
        const h = Math.floor(m / 60);
        if (h < 24) return h + 'h ago';
        return Math.floor(h / 24) + 'd ago';
    }

    function card(a) {
        const pulse = a.min < 6 ? '<span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>' : '';
        const pulseBadge = ['Live','Active Now','Just Submitted','AI Processing'].includes(a.status)
            ? '<span class="h-1.5 w-1.5 rounded-full bg-current opacity-70 mr-1 inline-block" style="animation:ac-blink 2s ease-in-out infinite"></span>' : '';
        return `<div class="ac-card flex items-start gap-4 px-5 py-4 group" data-type="${a.type}">
            <div class="relative flex-shrink-0">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br ${a.actor.grad} flex items-center justify-center shadow-sm">
                    <span class="text-white font-black text-sm">${a.actor.initials}</span>
                </div>
                <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-lg bg-gradient-to-br ${ICON_BG[a.type]} flex items-center justify-center border-2 border-white dark:border-slate-800 shadow-sm">
                    ${ICONS[a.type]}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 flex-wrap">
                    <div>
                        <span class="font-bold text-gray-900 dark:text-white text-sm">${a.actor.name}</span>
                        <span class="text-gray-300 dark:text-gray-600 mx-1.5">·</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">${a.actor.role}</span>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        ${pulse}
                        <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">${fmtTime(a.min)}</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 leading-snug">
                    ${a.verb} <span class="font-semibold text-gray-800 dark:text-white">&ldquo;${a.challenge}&rdquo;</span>
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">${a.meta}</p>
                <div class="flex items-center gap-2 mt-2.5">
                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-lg ${BADGE[a.badge]}">
                        ${pulseBadge}${a.status}
                    </span>
                    <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline inline-flex items-center gap-1">
                        View <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>`;
    }

    function getFiltered() {
        return filter === 'all' ? DATA : DATA.filter(a => a.type === filter);
    }

    function renderFeed() {
        const items = getFiltered();
        const feed  = document.getElementById('ac-feed');
        const empty = document.getElementById('ac-empty');
        const more  = document.getElementById('ac-loadmore');
        if (!feed) return;
        if (items.length === 0) {
            feed.classList.add('hidden'); empty.classList.remove('hidden'); more.classList.add('hidden'); return;
        }
        empty.classList.add('hidden'); feed.classList.remove('hidden');
        const slice = items.slice(0, visible);
        feed.innerHTML = slice.map(card).join('');
        feed.querySelectorAll('.ac-card').forEach((el, i) => {
            el.style.cssText = 'opacity:0;transform:translateY(8px)';
            setTimeout(() => {
                el.style.cssText = 'transition:opacity .3s ease,transform .3s ease;opacity:1;transform:translateY(0)';
            }, i * 45);
        });
        visible < items.length ? more.classList.remove('hidden') : more.classList.add('hidden');
    }

    function animateCounter(el) {
        const target = parseInt(el.dataset.target, 10);
        const dur = 1100, start = performance.now();
        const step = now => {
            const p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target);
            if (p < 1) requestAnimationFrame(step);
        };
        setTimeout(() => requestAnimationFrame(step), 400);
    }

    function showToast(msg) {
        const t = document.getElementById('ac-toast');
        document.getElementById('ac-toast-msg').textContent = msg;
        t.style.transform = 'translateY(0)'; t.style.opacity = '1';
        clearTimeout(t._timer);
        t._timer = setTimeout(() => { t.style.transform = 'translateY(120px)'; t.style.opacity = '0'; }, 4500);
    }

    let liveIdx = 0;
    const LIVE = [
        {type:'ai',          actor:{name:'Mindova AI',       role:'AI Engine',         initials:'AI', grad:'from-purple-500 to-violet-600'}, verb:'started analyzing',               meta:'New submission queued for processing',       challenge:'Smart Grid Optimization',       badge:'ai',     status:'AI Processing'},
        {type:'expert',      actor:{name:'Ahmed Al-Rashidi', role:'ML Engineer',        initials:'AA', grad:'from-emerald-500 to-teal-600'}, verb:'submitted a solution to',         meta:'Score estimate: 87% · Peer review pending',  challenge:'Renewable Energy Forecasting',  badge:'submit', status:'Just Submitted'},
        {type:'company',     actor:{name:'Siemens Energy',   role:'Enterprise Company', initials:'SE', grad:'from-blue-500 to-cyan-600'},    verb:'reviewed a submission for',       meta:'Detailed technical feedback provided',        challenge:'Predictive Maintenance System', badge:'review', status:'Reviewing'},
        {type:'certificate', actor:{name:'Mindova Platform', role:'Certification',      initials:'MP', grad:'from-amber-500 to-yellow-500'}, verb:'issued a certificate to Michael Chen for', meta:'Level: Full-Stack Engineering · Verified', challenge:'Digital Twin Architecture', badge:'cert', status:'Issued'},
    ];

    function startLive() {
        setInterval(() => {
            const tpl = LIVE[liveIdx++ % LIVE.length];
            const a = Object.assign({}, tpl, {id: Date.now(), min: 0});
            DATA.unshift(a);
            showToast(a.actor.name + ' ' + a.verb + ' "' + a.challenge + '"');
            if (filter === 'all' || filter === a.type) {
                const feed = document.getElementById('ac-feed');
                if (feed && !feed.classList.contains('hidden')) {
                    const el = document.createElement('div');
                    el.innerHTML = card(a);
                    const newCard = el.firstElementChild;
                    newCard.style.cssText = 'opacity:0;transform:translateY(-8px)';
                    feed.insertBefore(newCard, feed.firstChild);
                    requestAnimationFrame(() => {
                        newCard.style.cssText = 'transition:opacity .4s ease,transform .4s ease;opacity:1;transform:translateY(0)';
                    });
                }
            }
        }, 22000);
    }

    function startTicker() {
        setInterval(() => {
            document.querySelectorAll('.ac-pulse-num').forEach(el => {
                if (Math.random() > 0.55) return;
                const d = Math.random() > 0.45 ? 1 : -1;
                const v = Math.max(1, parseInt(el.textContent) + d);
                el.textContent = v;
                el.style.color = d > 0 ? '#10b981' : '#ef4444';
                setTimeout(() => el.style.color = '', 700);
            });
        }, 5500);
    }

    function init() {
        document.querySelectorAll('.ac-pulse-num').forEach(animateCounter);
        setTimeout(() => {
            const sk = document.getElementById('ac-skeleton');
            if (sk) sk.style.display = 'none';
            renderFeed();
            document.getElementById('ac-feed').classList.remove('hidden');
        }, 750);
        startLive();
        startTicker();
    }

    function setFilter(f) {
        filter = f; visible = PAGE;
        document.querySelectorAll('.ac-filter-btn').forEach(b => b.classList.toggle('ac-active', b.dataset.filter === f));
        renderFeed();
    }

    function loadMore() { visible += PAGE; renderFeed(); }

    function refresh() {
        const icon = document.getElementById('ac-refresh-icon');
        const btn  = document.getElementById('ac-refresh-btn');
        btn.disabled = true;
        icon.style.cssText = 'transform:rotate(360deg);transition:transform .6s ease';
        const sk = document.getElementById('ac-skeleton');
        const fd = document.getElementById('ac-feed');
        sk.style.display = 'block'; fd.classList.add('hidden');
        document.getElementById('ac-loadmore').classList.add('hidden');
        setTimeout(() => {
            sk.style.display = 'none'; fd.classList.remove('hidden');
            renderFeed(); btn.disabled = false;
            icon.style.cssText = '';
            const ts = document.getElementById('ac-last-updated');
            if (ts) ts.textContent = 'Updated just now';
        }, 800);
    }

    return { init, setFilter, loadMore, refresh };
})();

document.addEventListener('DOMContentLoaded', () => AC.init());
</script>
@endpush
