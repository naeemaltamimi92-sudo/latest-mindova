@extends('layouts.app')

@section('title', __('Challenges Analytics'))

@push('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
        50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.8); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes progressBar {
        from { width: 0; }
    }

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
    .animate-shimmer {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    .animate-slide-in-up {
        animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .animate-scale-in {
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .animate-progress {
        animation: progressBar 1.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    /* Glassmorphism Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 1.5rem;
        overflow: hidden;
    }
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #a855f7);
    }

    /* Metric Card Hover */
    .metric-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .metric-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    /* Circular Progress */
    .circular-progress {
        position: relative;
        width: 120px;
        height: 120px;
    }
    .circular-progress svg {
        transform: rotate(-90deg);
    }
    .circular-progress .progress-ring {
        stroke-dasharray: 339.292;
        stroke-dashoffset: 339.292;
        transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Company Rank Badge */
    .rank-badge {
        position: relative;
        overflow: hidden;
    }
    .rank-badge::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(transparent, rgba(255,255,255,0.3), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <!-- Ultra Premium Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-12 mb-10 rounded-b-[3rem] shadow-2xl mx-4 sm:mx-6 lg:mx-8">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Gradient Orbs -->
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-500/30 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-violet-500/30 via-transparent to-transparent"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-purple-500/10 via-transparent to-transparent"></div>

            <!-- Floating Elements -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/4 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl animate-float" style="animation-delay: 4s;"></div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8">
            <!-- Header Content -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex items-center gap-6">
                    <!-- Animated Icon -->
                    <div class="relative group">
                        <div class="absolute -inset-2 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 rounded-3xl blur-lg opacity-50 group-hover:opacity-75 transition duration-500 animate-pulse-glow"></div>
                        <div class="relative h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600 flex items-center justify-center shadow-2xl">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-3">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-sm font-semibold text-white/90">{{ __('Real-time Analytics') }}</span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                            {{ __('Analytics') }}
                            <span class="bg-gradient-to-r from-indigo-200 via-purple-200 to-pink-200 bg-clip-text text-transparent">{{ __('Dashboard') }}</span>
                        </h1>
                        <p class="text-lg text-indigo-200/80 mt-2 max-w-xl">{{ __('Deep insights and performance metrics for platform challenges') }}</p>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Period Selector -->
                    <form method="GET" class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl blur opacity-30"></div>
                        <select name="period" onchange="this.form.submit()" class="relative rounded-xl bg-white/15 backdrop-blur-md border border-white/30 text-white font-semibold px-5 py-3 focus:border-indigo-400 focus:ring-indigo-400 text-sm cursor-pointer hover:bg-white/25 transition-all">
                            <option value="7" {{ $period == '7' ? 'selected' : '' }} class="text-slate-900">{{ __('Last 7 Days') }}</option>
                            <option value="30" {{ $period == '30' ? 'selected' : '' }} class="text-slate-900">{{ __('Last 30 Days') }}</option>
                            <option value="90" {{ $period == '90' ? 'selected' : '' }} class="text-slate-900">{{ __('Last 90 Days') }}</option>
                            <option value="365" {{ $period == '365' ? 'selected' : '' }} class="text-slate-900">{{ __('Last Year') }}</option>
                        </select>
                    </form>

                    <a href="{{ route('admin.challenges.index') }}" class="group inline-flex items-center gap-2 bg-white/15 backdrop-blur-md border border-white/30 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/25 transition-all">
                        <svg class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back') }}
                    </a>
                </div>
            </div>

            <!-- Key Metrics Cards - Floating -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
                <!-- Avg Analysis Time -->
                <div class="metric-card bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 animate-slide-in-up" style="animation-delay: 0.1s">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-white/60 font-medium mb-1">{{ __('Avg Analysis') }}</p>
                            <p class="text-3xl font-black text-white">{{ $metrics['avg_analysis_time'] }}<span class="text-lg text-white/60">h</span></p>
                            <div class="flex items-center gap-1 mt-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-emerald-400 font-semibold">12% {{ __('faster') }}</span>
                            </div>
                        </div>
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Avg Completion -->
                <div class="metric-card bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 animate-slide-in-up" style="animation-delay: 0.2s">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-white/60 font-medium mb-1">{{ __('Avg Completion') }}</p>
                            <p class="text-3xl font-black text-white">{{ $metrics['avg_completion_time'] }}<span class="text-lg text-white/60">d</span></p>
                            <div class="flex items-center gap-1 mt-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-emerald-400 font-semibold">{{ __('On track') }}</span>
                            </div>
                        </div>
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Engagement -->
                <div class="metric-card bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 animate-slide-in-up" style="animation-delay: 0.3s">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-white/60 font-medium mb-1">{{ __('Engagement') }}</p>
                            <p class="text-3xl font-black text-white">{{ $metrics['volunteer_engagement'] }}<span class="text-lg text-white/60">%</span></p>
                            <div class="w-full bg-white/20 rounded-full h-1.5 mt-3">
                                <div class="bg-gradient-to-r from-purple-400 to-pink-400 h-1.5 rounded-full animate-progress" style="width: {{ $metrics['volunteer_engagement'] }}%"></div>
                            </div>
                        </div>
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="metric-card bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 animate-slide-in-up" style="animation-delay: 0.4s">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-white/60 font-medium mb-1">{{ __('Success Rate') }}</p>
                            <p class="text-3xl font-black text-white">{{ $metrics['success_rate'] }}<span class="text-lg text-white/60">%</span></p>
                            <div class="flex items-center gap-1 mt-2">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs text-amber-400 font-semibold">{{ __('Excellent') }}</span>
                            </div>
                        </div>
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Challenges Over Time Chart -->
            <div class="chart-container glass-card shadow-xl p-6 animate-scale-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ __('Challenges Over Time') }}</h3>
                            <p class="text-sm text-slate-500">{{ __('Daily submission trends') }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold">{{ __('LIVE') }}</span>
                </div>
                <div class="h-72">
                    <canvas id="challengesOverTimeChart"></canvas>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="chart-container glass-card shadow-xl p-6 animate-scale-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ __('Status Distribution') }}</h3>
                            <p class="text-sm text-slate-500">{{ __('Current challenge states') }}</p>
                        </div>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Type Distribution & Top Companies -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Challenge Types with Premium Progress Bars -->
            <div class="chart-container glass-card shadow-xl p-6 animate-scale-in" style="animation-delay: 0.4s">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ __('Challenge Types') }}</h3>
                        <p class="text-sm text-slate-500">{{ __('Distribution by category') }}</p>
                    </div>
                </div>
                <div class="space-y-5">
                    @php $colors = ['from-indigo-500 to-purple-500', 'from-emerald-500 to-teal-500', 'from-amber-500 to-orange-500', 'from-pink-500 to-rose-500', 'from-blue-500 to-cyan-500']; @endphp
                    @foreach($typeDistribution as $index => $type)
                    @php
                        $total = $typeDistribution->sum('count');
                        $percentage = $total > 0 ? round(($type->count / $total) * 100, 1) : 0;
                        $colorClass = $colors[$index % count($colors)];
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="h-3 w-3 rounded-full bg-gradient-to-r {{ $colorClass }}"></div>
                                <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition-colors">
                                    {{ __(ucfirst(str_replace('_', ' ', $type->challenge_type ?? 'Unknown'))) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-900">{{ $type->count }}</span>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-semibold">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r {{ $colorClass }} h-2.5 rounded-full animate-progress transition-all group-hover:shadow-lg" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Companies with Ranking -->
            <div class="chart-container glass-card shadow-xl p-6 animate-scale-in" style="animation-delay: 0.5s">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ __('Top Companies') }}</h3>
                        <p class="text-sm text-slate-500">{{ __('Most active contributors') }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topCompanies as $index => $company)
                    <div class="group flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-white rounded-2xl border border-slate-100 hover:border-amber-200 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <!-- Rank Badge -->
                            <div class="rank-badge relative h-10 w-10 rounded-xl flex items-center justify-center font-black text-lg shadow-lg
                                @if($index === 0) bg-gradient-to-br from-yellow-400 to-amber-500 text-yellow-900
                                @elseif($index === 1) bg-gradient-to-br from-slate-300 to-slate-400 text-slate-700
                                @elseif($index === 2) bg-gradient-to-br from-amber-600 to-orange-600 text-white
                                @else bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600
                                @endif">
                                {{ $index + 1 }}
                            </div>
                            <!-- Company Avatar -->
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <span class="text-white text-sm font-bold">{{ strtoupper(substr($company->company_name ?? 'CO', 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $company->company_name ?? $company->user->name ?? __('Unknown') }}</p>
                                <p class="text-xs text-slate-500">{{ $company->challenges_count }} {{ __('challenges submitted') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30">
                                {{ $company->challenges_count }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                        <p class="text-slate-500">{{ __('No data available') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Completion Trends Chart -->
        <div class="chart-container glass-card shadow-xl p-6 mb-8 animate-scale-in" style="animation-delay: 0.6s">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ __('Completion Trends') }}</h3>
                        <p class="text-sm text-slate-500">{{ __('Monthly progress overview') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-indigo-500"></div>
                        <span class="text-xs font-medium text-slate-600">{{ __('Total') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                        <span class="text-xs font-medium text-slate-600">{{ __('Completed') }}</span>
                    </div>
                </div>
            </div>
            <div class="h-72">
                <canvas id="completionTrendsChart"></canvas>
            </div>
        </div>

        <!-- Scores by Field with Circular Progress -->
        @if($scoresByField->count() > 0)
        <div class="chart-container glass-card shadow-xl p-6 animate-scale-in" style="animation-delay: 0.7s">
            <div class="flex items-center gap-3 mb-8">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ __('Average Scores by Field') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('Performance metrics per category') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php $fieldColors = ['from-indigo-500 to-purple-600', 'from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-pink-500 to-rose-600', 'from-cyan-500 to-blue-600']; @endphp
                @foreach($scoresByField as $index => $field)
                <div class="group text-center p-6 bg-gradient-to-br from-slate-50 to-white rounded-2xl border border-slate-100 hover:border-indigo-200 hover:shadow-xl transition-all duration-300">
                    <!-- Circular Progress -->
                    <div class="circular-progress mx-auto mb-4 relative">
                        <svg class="w-full h-full" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="54" stroke="#e2e8f0" stroke-width="8" fill="none"/>
                            <circle cx="60" cy="60" r="54" stroke="url(#gradient{{ $index }})" stroke-width="8" fill="none" class="progress-ring" style="stroke-dashoffset: {{ 339.292 - (339.292 * $field->avg_score / 10) }}"/>
                            <defs>
                                <linearGradient id="gradient{{ $index }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color: {{ $index % 2 == 0 ? '#6366f1' : '#10b981' }}"/>
                                    <stop offset="100%" style="stop-color: {{ $index % 2 == 0 ? '#a855f7' : '#14b8a6' }}"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-2xl font-black text-slate-900">{{ number_format($field->avg_score, 1) }}</span>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-900 mb-1">{{ $field->field }}</p>
                    <p class="text-xs text-slate-500">{{ $field->count }} {{ __('challenges') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Global Config
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // Challenges Over Time - Area Chart
    const ctx1 = document.getElementById('challengesOverTimeChart').getContext('2d');
    const gradient1 = ctx1.createLinearGradient(0, 0, 0, 300);
    gradient1.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
    gradient1.addColorStop(1, 'rgba(99, 102, 241, 0.01)');

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($challengesOverTime->pluck('date')) !!},
            datasets: [{
                label: '{{ __("Challenges") }}',
                data: {!! json_encode($challengesOverTime->pluck('count')) !!},
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: gradient1,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(99, 102, 241)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { weight: 'bold' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Status Distribution - Doughnut Chart
    new Chart(document.getElementById('statusDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusDistribution->pluck('status')->map(fn($s) => __(ucfirst($s)))) !!},
            datasets: [{
                data: {!! json_encode($statusDistribution->pluck('count')) !!},
                backgroundColor: [
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(99, 102, 241)',
                    'rgb(16, 185, 129)',
                    'rgb(20, 184, 166)',
                    'rgb(107, 114, 128)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Completion Trends - Bar Chart
    const ctx3 = document.getElementById('completionTrendsChart').getContext('2d');

    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: {!! json_encode($completionStats->pluck('month')) !!},
            datasets: [{
                label: '{{ __("Total") }}',
                data: {!! json_encode($completionStats->pluck('total')) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 20
            }, {
                label: '{{ __("Completed") }}',
                data: {!! json_encode($completionStats->pluck('completed')) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Animate circular progress rings
    setTimeout(() => {
        document.querySelectorAll('.progress-ring').forEach(ring => {
            const offset = ring.style.strokeDashoffset;
            ring.style.strokeDashoffset = '339.292';
            setTimeout(() => {
                ring.style.strokeDashoffset = offset;
            }, 100);
        });
    }, 500);
});
</script>
@endpush
@endsection
