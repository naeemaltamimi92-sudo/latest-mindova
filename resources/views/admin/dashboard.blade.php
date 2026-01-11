@extends('layouts.app')

@section('title', __('Admin Dashboard'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">

    <!-- Hero Header Section -->
    <div class="hero-gradient-primary text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-black">{{ __('Welcome back,') }} {{ auth()->user()->name }}!</h1>
                        <p class="text-white/70 text-sm mt-1">{{ __('Mindova Platform Administrator') }} &bull; {{ now()->format('l, F j, Y') }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.challenges.analytics') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-medium rounded-xl transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Analytics') }}
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ __('Settings') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards - Floating Above -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <!-- Total Users -->
            <div class="dashboard-stat-card stat-purple hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="icon-container icon-gradient-purple">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="pulse-dot pulse-dot-green"></div>
                </div>
                <p class="text-3xl lg:text-4xl font-black text-slate-900">{{ number_format($stats['total_users']) }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Total Users') }}</p>
                <div class="mt-3 flex items-center gap-1 text-xs text-emerald-600">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ __('Active Platform') }}</span>
                </div>
            </div>

            <!-- Volunteers -->
            <div class="dashboard-stat-card stat-green hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="icon-container icon-gradient-green">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">{{ __('ACTIVE') }}</span>
                </div>
                <p class="text-3xl lg:text-4xl font-black text-slate-900">{{ number_format($stats['total_volunteers']) }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Volunteers') }}</p>
            </div>

            <!-- Companies -->
            <div class="dashboard-stat-card stat-blue hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="icon-container icon-gradient-blue">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">{{ __('VERIFIED') }}</span>
                </div>
                <p class="text-3xl lg:text-4xl font-black text-slate-900">{{ number_format($stats['total_companies']) }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Companies') }}</p>
            </div>

            <!-- Active Challenges -->
            <div class="dashboard-stat-card stat-orange hover-lift">
                <div class="flex items-center justify-between mb-4">
                    <div class="icon-container icon-gradient-orange">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="pulse-dot pulse-dot-yellow"></div>
                </div>
                <p class="text-3xl lg:text-4xl font-black text-slate-900">{{ number_format($stats['active_challenges']) }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ __('Active Challenges') }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('Quick Actions') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('admin.challenges.index') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-indigo">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Challenges') }}</span>
                </a>
                <a href="{{ route('admin.volunteers.index') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-green">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Volunteers') }}</span>
                </a>
                <a href="{{ route('admin.companies.index') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-blue">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Companies') }}</span>
                </a>
                <a href="{{ route('admin.challenges.analytics') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-purple">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Analytics') }}</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-teal">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Settings') }}</span>
                </a>
                <a href="{{ route('admin.challenges.export') }}" class="quick-action-card">
                    <div class="icon-container-sm icon-gradient-pink">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">{{ __('Export') }}</span>
                </a>
            </div>
        </div>

        <!-- Secondary Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover-lift-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_challenges']) }}</p>
                        <p class="text-xs text-slate-500">{{ __('Total Challenges') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover-lift-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_tasks'] ?? 0) }}</p>
                        <p class="text-xs text-slate-500">{{ __('Total Tasks') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover-lift-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_assignments']) }}</p>
                        <p class="text-xs text-slate-500">{{ __('Assignments') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover-lift-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_certificates']) }}</p>
                        <p class="text-xs text-slate-500">{{ __('Certificates') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Challenge Status Distribution -->
        @if(!empty($challengesByStatus))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden mb-8 hover-lift">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                        {{ __('Challenge Status Distribution') }}
                    </h2>
                    <a href="{{ route('admin.challenges.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">{{ __('View All') }}</a>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($challengesByStatus as $status => $count)
                    @php
                        $statusConfig = [
                            'submitted' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'active' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'text' => 'text-green-700', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'in_progress' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            'completed' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'M5 13l4 4L19 7'],
                            'delivered' => ['bg' => 'bg-teal-50', 'border' => 'border-teal-200', 'text' => 'text-teal-700', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            'archived' => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-700', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                        ];
                        $config = $statusConfig[$status] ?? ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-700', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'];
                    @endphp
                    <div class="text-center p-4 rounded-xl {{ $config['bg'] }} border {{ $config['border'] }} hover-lift-sm transition-all">
                        <div class="h-10 w-10 rounded-lg {{ $config['bg'] }} flex items-center justify-center mx-auto mb-2">
                            <svg class="h-5 w-5 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-black {{ $config['text'] }}">{{ $count }}</p>
                        <p class="text-sm font-medium capitalize {{ $config['text'] }}">{{ __(ucfirst(str_replace('_', ' ', $status))) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Challenges -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="icon-container-sm icon-gradient-indigo">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-900">{{ __('Recent Challenges') }}</h2>
                        </div>
                        <a href="{{ route('admin.challenges.index') }}" class="btn-gradient text-sm py-2 px-4">
                            {{ __('View All') }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentChallenges as $challenge)
                        <a href="{{ route('admin.challenges.show', $challenge) }}" class="block px-6 py-4 hover:bg-gradient-to-r hover:from-slate-50 hover:to-transparent transition-colors group">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">{{ $challenge->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm text-slate-500">
                                            @if($challenge->company)
                                                {{ $challenge->company->company_name ?? $challenge->company->user->name ?? __('Unknown Company') }}
                                            @elseif($challenge->volunteer)
                                                {{ $challenge->volunteer->user->name ?? __('Unknown Volunteer') }}
                                            @else
                                                {{ __('Community Submission') }}
                                            @endif
                                        </span>
                                        <span class="text-slate-300">&bull;</span>
                                        <span class="text-sm text-slate-400">{{ $challenge->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'submitted' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'active' => 'bg-green-100 text-green-700 border-green-200',
                                        'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'delivered' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        'archived' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                @endphp
                                <span class="ml-4 px-3 py-1 rounded-lg text-xs font-bold border {{ $statusColors[$challenge->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $challenge->status))) }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ __('No challenges yet') }}</h3>
                            <p class="text-slate-500">{{ __('Challenges will appear here when created') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Contributors -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="icon-container-sm icon-gradient-green">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-900">{{ __('Top Contributors') }}</h2>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($topVolunteers as $index => $volunteer)
                        <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="flex items-center px-6 py-4 hover:bg-gradient-to-r hover:from-slate-50 hover:to-transparent transition-colors group">
                            <div class="flex-shrink-0 mr-4">
                                <div class="relative">
                                    @if($volunteer->profile_picture)
                                    <img src="{{ asset('storage/' . $volunteer->profile_picture) }}" alt="{{ $volunteer->user->name }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-white shadow-lg group-hover:ring-emerald-200 transition">
                                    @else
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center ring-2 ring-white shadow-lg group-hover:ring-emerald-200 transition">
                                        <span class="text-white font-bold">{{ substr($volunteer->user->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                    @if($index < 3)
                                    @php
                                        $badgeColors = [
                                            0 => 'bg-gradient-to-br from-yellow-400 to-amber-500 text-white',
                                            1 => 'bg-gradient-to-br from-slate-300 to-slate-400 text-slate-800',
                                            2 => 'bg-gradient-to-br from-amber-600 to-orange-700 text-white',
                                        ];
                                    @endphp
                                    <div class="absolute -top-1 -right-1 h-6 w-6 rounded-full flex items-center justify-center text-xs font-bold shadow-lg {{ $badgeColors[$index] }}">
                                        {{ $index + 1 }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 truncate group-hover:text-emerald-600 transition">{{ $volunteer->user->name }}</p>
                                <p class="text-sm text-slate-500 truncate">{{ $volunteer->field ?? __('No field specified') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-emerald-600">{{ number_format($volunteer->reputation_score) }}</p>
                                <p class="text-xs text-slate-400">{{ __('points') }}</p>
                            </div>
                        </a>
                        @empty
                        <div class="empty-state py-8">
                            <div class="empty-state-icon" style="width: 4rem; height: 4rem;">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 text-sm">{{ __('No contributors yet') }}</p>
                        </div>
                        @endforelse
                    </div>
                    @if($topVolunteers->count() > 0)
                    <div class="p-4 bg-slate-50 border-t border-slate-100">
                        <a href="{{ route('admin.volunteers.index') }}" class="block text-center text-sm font-semibold text-emerald-600 hover:text-emerald-700">{{ __('View All Volunteers') }} &rarr;</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Companies Section -->
        <div class="mt-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-cyan-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="icon-container-sm icon-gradient-blue">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">{{ __('Active Companies') }}</h2>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">{{ $activeCompanies->count() }}</span>
                    </div>
                    <a href="{{ route('admin.companies.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        {{ __('View All') }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($activeCompanies as $company)
                        <a href="{{ route('admin.companies.show', $company) }}" class="group block bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-xl p-5 border border-slate-200/60 hover:border-blue-300 hover:shadow-lg transition-all hover-lift-sm">
                            <div class="flex items-center gap-4">
                                @if($company->logo_path)
                                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }}" class="h-12 w-12 rounded-xl object-cover shadow-lg ring-2 ring-white">
                                @else
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg ring-2 ring-white">
                                    <span class="text-white font-bold text-lg">{{ substr($company->company_name ?? $company->user->name, 0, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors truncate">
                                        {{ $company->company_name ?? $company->user->name }}
                                    </p>
                                    <p class="text-sm text-slate-500 truncate">{{ $company->industry ?? __('No industry') }}</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-200/60 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="pulse-dot pulse-dot-green" style="width: 0.5rem; height: 0.5rem;"></span>
                                    <span class="text-xs text-slate-500">{{ __('Active') }}</span>
                                </div>
                                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $company->challenges_count }} {{ __('challenges') }}
                                </span>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full empty-state">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ __('No companies yet') }}</h3>
                            <p class="text-slate-500">{{ __('Companies will appear here when they register') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Footer -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="pulse-dot pulse-dot-green"></div>
                    <span class="text-sm font-medium text-slate-600">{{ __('System Status: All services operational') }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                    <span>{{ __('Last updated:') }} {{ now()->diffForHumans() }}</span>
                    <span>&bull;</span>
                    <span>Laravel v{{ app()->version() }}</span>
                    <span>&bull;</span>
                    <span>PHP v{{ phpversion() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
