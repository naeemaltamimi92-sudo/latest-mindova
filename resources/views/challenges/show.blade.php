@extends('layouts.app')

@section('title', $challenge->title)

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .slide-in-right { animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .fade-in { animation: fadeIn 0.8s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .scale-in { animation: scaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .float { animation: float 3s ease-in-out infinite; }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .pulse-glow { animation: pulseGlow 2s ease-in-out infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
    }
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #6B8DD6 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
        animation: rotate 30s linear infinite;
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .stat-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    .workstream-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .workstream-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #6366f1, #8b5cf6);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .workstream-card:hover::before {
        transform: scaleY(1);
    }
    .workstream-card:hover {
        transform: translateX(4px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }
    .task-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .task-item:hover {
        transform: translateX(8px);
        background: linear-gradient(to right, rgba(99, 102, 241, 0.05), transparent);
    }
    .idea-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .idea-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.3);
    }
    .skill-tag {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .skill-tag:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .circular-progress {
        transform: rotate(-90deg);
    }
    .circular-progress circle {
        transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .team-avatar {
        transition: all 0.3s ease;
    }
    .team-avatar:hover {
        transform: scale(1.15) translateY(-4px);
        z-index: 10;
    }
    .countdown-digit {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    }
    .attachment-card {
        transition: all 0.3s ease;
    }
    .attachment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -10px rgba(0, 0, 0, 0.15);
    }
    .tab-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tab-btn:hover {
        background: rgba(99, 102, 241, 0.1);
    }
    .tab-btn.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }
    .prose pre { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #e2e8f0; padding: 1.25rem; border-radius: 1rem; overflow-x: auto; border: 1px solid rgba(255,255,255,0.1); }
    .prose code { background: #f1f5f9; padding: 0.125rem 0.5rem; border-radius: 0.375rem; font-size: 0.875rem; color: #6366f1; }
    .prose pre code { background: transparent; padding: 0; color: #e2e8f0; }
    .ribbon {
        position: absolute;
        top: 20px;
        right: -35px;
        transform: rotate(45deg);
        padding: 5px 40px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>
@endpush

@section('content')
<div x-data="{ activeTab: 'overview', showAnalysis: false }" class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <!-- Hero Section -->
    <div class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-12 relative z-10">
            <div class="slide-up" style="animation-delay: 0.1s">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-sm text-white/70 mb-6">
                    <a href="{{ route('challenges.index') }}" class="hover:text-white transition-colors">{{ __('Challenges') }}</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-white">{{ Str::limit($challenge->title, 40) }}</span>
                </nav>

                <!-- Title & Status -->
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            @php
                                $statusConfig = [
                                    'draft' => ['bg' => 'bg-slate-500', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                                    'submitted' => ['bg' => 'bg-blue-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'analyzing' => ['bg' => 'bg-amber-500', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                                    'active' => ['bg' => 'bg-emerald-500', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                    'in_progress' => ['bg' => 'bg-indigo-500', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                    'completed' => ['bg' => 'bg-emerald-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'delivered' => ['bg' => 'bg-green-600', 'icon' => 'M5 13l4 4L19 7'],
                                    'rejected' => ['bg' => 'bg-red-500', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ];
                                $config = $statusConfig[$challenge->status] ?? $statusConfig['draft'];
                            @endphp
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $config['bg'] }} text-white font-semibold text-sm shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                                {{ __(ucfirst(str_replace('_', ' ', $challenge->status))) }}
                            </span>
                            @if($challenge->challenge_type)
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur text-white font-medium text-sm">
                                @if($challenge->challenge_type === 'team_execution')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                {{ __(ucfirst(str_replace('_', ' ', $challenge->challenge_type))) }}
                            </span>
                            @endif
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black mb-4 leading-tight">{{ $challenge->title }}</h1>
                        <div class="flex items-center flex-wrap gap-4 text-white/80">
                            <a href="#" class="flex items-center gap-2 hover:text-white transition-colors">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $challenge->company->company_name }}</span>
                            </a>
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('Created') }} {{ $challenge->created_at->translatedFormat('M d, Y') }}
                            </span>
                            @if($challenge->field)
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                {{ $challenge->field }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Deadline Countdown (if applicable) -->
                    @if($challenge->deadline && $challenge->deadline->isFuture())
                    <div class="slide-in-right" style="animation-delay: 0.3s">
                        <div class="glass-card rounded-2xl p-6 text-center">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">{{ __('Deadline') }}</p>
                            @php
                                $diff = now()->diff($challenge->deadline);
                                $days = $diff->days;
                                $hours = $diff->h;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="countdown-digit px-3 py-2 rounded-lg">
                                    <span class="text-2xl font-black text-white">{{ $days }}</span>
                                    <p class="text-[10px] text-slate-400 uppercase">{{ __('Days') }}</p>
                                </div>
                                <span class="text-slate-400 font-bold">:</span>
                                <div class="countdown-digit px-3 py-2 rounded-lg">
                                    <span class="text-2xl font-black text-white">{{ $hours }}</span>
                                    <p class="text-[10px] text-slate-400 uppercase">{{ __('Hours') }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 mt-2">{{ $challenge->deadline->translatedFormat('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 -mt-8 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($challenge->challenge_type === 'team_execution')
            <!-- Progress -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="w-14 h-14">
                        @php $progress = $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0; @endphp
                        <svg class="circular-progress w-full h-full" viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="16" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                            <circle cx="18" cy="18" r="16" fill="none" stroke="url(#progress-gradient)" stroke-width="3" stroke-linecap="round" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $progress }}"/>
                            <defs>
                                <linearGradient id="progress-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#6366f1"/>
                                    <stop offset="100%" stop-color="#8b5cf6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $progress }}%</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Progress') }}</p>
            </div>

            <!-- Tasks -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['completed_tasks'] }}/{{ $stats['total_tasks'] }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Tasks Completed') }}</p>
            </div>

            <!-- Hours -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total_hours'] }}h</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Estimated Hours') }}</p>
            </div>

            <!-- Volunteers -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['active_volunteers'] }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Active Contributors') }}</p>
            </div>
            @else
            <!-- Ideas Count -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total_ideas'] }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Ideas Submitted') }}</p>
            </div>

            <!-- Top Score -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                @php $topScore = $challenge->ideas->max('final_score') ?? 0; @endphp
                <p class="text-2xl font-black text-slate-900">{{ round($topScore) }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Top Score') }}</p>
            </div>

            <!-- Total Votes -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $challenge->ideas->sum('community_votes') }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Total Votes') }}</p>
            </div>

            <!-- Contributors -->
            <div class="stat-card glass-card rounded-2xl p-5 slide-up" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">{{ $challenge->ideas->pluck('volunteer_id')->unique()->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ __('Contributors') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column (Main Content) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tab Navigation -->
                <div class="flex flex-wrap gap-2 p-1.5 bg-slate-100 rounded-2xl slide-up" style="animation-delay: 0.3s">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active' : ''" class="tab-btn flex-1 py-3 px-4 rounded-xl font-semibold text-sm text-slate-700 transition-all">
                        {{ __('Overview') }}
                    </button>
                    @if($challenge->challenge_type === 'team_execution')
                    <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'active' : ''" class="tab-btn flex-1 py-3 px-4 rounded-xl font-semibold text-sm text-slate-700 transition-all">
                        {{ __('Tasks') }}
                    </button>
                    @else
                    <button @click="activeTab = 'ideas'" :class="activeTab === 'ideas' ? 'active' : ''" class="tab-btn flex-1 py-3 px-4 rounded-xl font-semibold text-sm text-slate-700 transition-all">
                        {{ __('Ideas') }}
                    </button>
                    @endif
                    @if($challenge->teams->count() > 0)
                    <button @click="activeTab = 'teams'" :class="activeTab === 'teams' ? 'active' : ''" class="tab-btn flex-1 py-3 px-4 rounded-xl font-semibold text-sm text-slate-700 transition-all">
                        {{ __('Teams') }}
                    </button>
                    @endif
                    @if($latestAnalysis)
                    <button @click="activeTab = 'analysis'" :class="activeTab === 'analysis' ? 'active' : ''" class="tab-btn flex-1 py-3 px-4 rounded-xl font-semibold text-sm text-slate-700 transition-all">
                        {{ __('AI Analysis') }}
                    </button>
                    @endif
                </div>

                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <!-- Challenge Description -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm slide-up" style="animation-delay: 0.4s">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-slate-900">{{ __('Challenge Description') }}</h2>
                        </div>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $challenge->refined_brief ?? $challenge->original_description }}</p>
                        </div>
                    </div>

                    <!-- Required Skills -->
                    @if($requiredSkills->count() > 0)
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm slide-up" style="animation-delay: 0.5s">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-slate-900">{{ __('Required Skills') }}</h2>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($requiredSkills as $skill)
                            <span class="skill-tag px-4 py-2 bg-gradient-to-r from-indigo-50 to-violet-50 text-indigo-700 rounded-xl font-semibold text-sm border border-indigo-100 cursor-default">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Attachments -->
                    @if($challenge->attachments->count() > 0)
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm slide-up" style="animation-delay: 0.6s">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-slate-900">{{ __('Attachments') }}</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($challenge->attachments as $attachment)
                            <a href="{{ route('challenges.attachments.download', [$challenge, $attachment]) }}" class="attachment-card flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-indigo-300">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center">
                                    @php
                                        $ext = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                                        $iconColor = match($ext) {
                                            'pdf' => 'text-red-500',
                                            'doc', 'docx' => 'text-blue-500',
                                            'xls', 'xlsx' => 'text-green-500',
                                            'jpg', 'jpeg', 'png', 'gif' => 'text-purple-500',
                                            default => 'text-slate-500'
                                        };
                                    @endphp
                                    <svg class="w-6 h-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 truncate">{{ $attachment->filename }}</p>
                                    <p class="text-xs text-slate-500">{{ strtoupper($ext) }} {{ __('File') }}</p>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Tasks Tab (Team Execution) -->
                @if($challenge->challenge_type === 'team_execution')
                <div x-show="activeTab === 'tasks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    @forelse($challenge->workstreams as $index => $workstream)
                    <div class="workstream-card bg-white rounded-3xl border border-slate-200 p-8 shadow-sm mb-6">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    <h3 class="text-xl font-black text-slate-900">{{ $workstream->title }}</h3>
                                </div>
                                <p class="text-slate-600 text-sm ml-11">{{ $workstream->description }}</p>
                            </div>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                {{ $workstream->tasks->count() }} {{ __('Tasks') }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($workstream->tasks as $task)
                            <div class="task-item group flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-indigo-200">
                                @php
                                    $taskStatusConfig = [
                                        'open' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        'assigned' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                        'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                        'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ];
                                    $taskConfig = $taskStatusConfig[$task->status] ?? $taskStatusConfig['open'];
                                @endphp
                                <div class="w-10 h-10 {{ $taskConfig['bg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 {{ $taskConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $taskConfig['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $task->title }}</h4>
                                    <div class="flex items-center flex-wrap gap-3 mt-1 text-xs">
                                        <span class="flex items-center gap-1 text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $task->estimated_hours }}h
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="text-slate-500">{{ __('Complexity') }}:</span>
                                            <span class="font-bold {{ $task->complexity_score <= 3 ? 'text-emerald-600' : ($task->complexity_score <= 6 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ $task->complexity_score }}/10
                                            </span>
                                        </span>
                                        <span class="px-2 py-0.5 {{ $taskConfig['bg'] }} {{ $taskConfig['text'] }} rounded-md font-semibold capitalize">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('tasks.show', $task) }}" class="flex-shrink-0 w-10 h-10 bg-indigo-100 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-xl flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 font-medium">{{ __('No tasks have been created yet.') }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ __('Tasks will appear here once AI analysis is complete.') }}</p>
                    </div>
                    @endforelse
                </div>
                @endif

                <!-- Ideas Tab (Community Discussion) -->
                @if($challenge->challenge_type === 'community_discussion')
                <div x-show="activeTab === 'ideas'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-slate-900">{{ __('Community Ideas') }}</h2>
                        @if(auth()->user() && auth()->user()->isVolunteer())
                        <a href="{{ route('ideas.create', $challenge) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-500/30 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Submit Idea') }}
                        </a>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($challenge->ideas->sortByDesc('final_score') as $idea)
                        <div class="idea-card bg-white rounded-2xl border border-slate-200 p-6 hover:border-indigo-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $idea->title }}</h3>
                                    <p class="text-sm text-slate-600 mb-3">{{ Str::limit($idea->description, 150) }}</p>
                                    <div class="flex items-center flex-wrap gap-4 text-sm">
                                        <span class="flex items-center gap-1.5 text-indigo-600 font-semibold">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            AI: {{ round($idea->ai_score) }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-slate-600">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            {{ $idea->community_votes }} {{ __('votes') }}
                                        </span>
                                        <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ __('Final') }}: {{ round($idea->final_score) }}
                                        </span>
                                        @if($idea->volunteer && $idea->volunteer->user)
                                        <span class="flex items-center gap-1.5 text-slate-500 text-xs">
                                            <span class="w-5 h-5 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                {{ strtoupper(substr($idea->volunteer->user->name, 0, 1)) }}
                                            </span>
                                            {{ $idea->volunteer->user->name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('ideas.show', $idea) }}" class="flex-shrink-0 px-4 py-2 bg-indigo-100 hover:bg-indigo-600 text-indigo-600 hover:text-white rounded-xl font-semibold text-sm transition-all">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <p class="text-slate-600 font-medium">{{ __('No ideas submitted yet') }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ __('Be the first to share your idea!') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Teams Tab -->
                @if($challenge->teams->count() > 0)
                <div x-show="activeTab === 'teams'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($challenge->teams as $team)
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-2xl flex items-center justify-center text-white font-black text-xl">
                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-black text-slate-900">{{ $team->name }}</h3>
                                    @if($team->leader && $team->leader->user)
                                    <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-1">
                                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                        </svg>
                                        {{ __('Led by') }} {{ $team->leader->user->name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                    {{ $team->members->count() + 1 }} {{ __('members') }}
                                </span>
                            </div>

                            <!-- Team Members -->
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center -space-x-3">
                                    @if($team->leader && $team->leader->user)
                                    <div class="team-avatar relative w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-md" title="{{ $team->leader->user->name }} ({{ __('Leader') }})">
                                        {{ strtoupper(substr($team->leader->user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    @foreach($team->members->take(5) as $member)
                                    @if($member->volunteer && $member->volunteer->user)
                                    <div class="team-avatar relative w-10 h-10 bg-gradient-to-br from-slate-400 to-slate-500 rounded-full flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-md" title="{{ $member->volunteer->user->name }}">
                                        {{ strtoupper(substr($member->volunteer->user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    @endforeach
                                    @if($team->members->count() > 5)
                                    <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 font-bold text-xs border-2 border-white">
                                        +{{ $team->members->count() - 5 }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- AI Analysis Tab -->
                @if($latestAnalysis)
                <div x-show="activeTab === 'analysis'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="space-y-6">
                        <!-- Summary -->
                        @if($latestAnalysis->summary)
                        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900">{{ __('AI Summary') }}</h3>
                            </div>
                            <p class="text-slate-700 leading-relaxed">{{ $latestAnalysis->summary }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Objectives -->
                            @if($latestAnalysis->objectives && count($latestAnalysis->objectives) > 0)
                            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-900">{{ __('Objectives') }}</h4>
                                </div>
                                <ul class="space-y-2">
                                    @foreach($latestAnalysis->objectives as $objective)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $objective }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Constraints -->
                            @if($latestAnalysis->constraints && count($latestAnalysis->constraints) > 0)
                            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-900">{{ __('Constraints') }}</h4>
                                </div>
                                <ul class="space-y-2">
                                    @foreach($latestAnalysis->constraints as $constraint)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $constraint }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Success Criteria -->
                            @if($latestAnalysis->success_criteria && count($latestAnalysis->success_criteria) > 0)
                            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-900">{{ __('Success Criteria') }}</h4>
                                </div>
                                <ul class="space-y-2">
                                    @foreach($latestAnalysis->success_criteria as $criterion)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $criterion }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Risk Assessment -->
                            @if($latestAnalysis->risk_assessment)
                            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-900">{{ __('Risk Assessment') }}</h4>
                                </div>
                                <p class="text-sm text-slate-600">{{ $latestAnalysis->risk_assessment }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Recommended Approach -->
                        @if($latestAnalysis->recommended_approach)
                        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-3xl border border-indigo-200 p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900">{{ __('Recommended Approach') }}</h3>
                            </div>
                            <p class="text-slate-700 leading-relaxed">{{ $latestAnalysis->recommended_approach }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column (Sidebar) -->
            <div class="space-y-6">
                <!-- Challenge Info Card -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm slide-in-right sticky top-6" style="animation-delay: 0.4s">
                    <h3 class="text-lg font-black text-slate-900 mb-6 pb-4 border-b border-slate-200">{{ __('Challenge Info') }}</h3>

                    <dl class="space-y-5">
                        <!-- Complexity -->
                        @if($challenge->complexity_level)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Complexity') }}</dt>
                                <dd>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-2xl font-black text-slate-900">{{ $challenge->complexity_level }}</span>
                                        <span class="text-sm text-slate-500">/10</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500
                                            @if($challenge->complexity_level <= 3) bg-gradient-to-r from-emerald-400 to-emerald-500
                                            @elseif($challenge->complexity_level <= 6) bg-gradient-to-r from-amber-400 to-amber-500
                                            @elseif($challenge->complexity_level <= 8) bg-gradient-to-r from-orange-400 to-orange-500
                                            @else bg-gradient-to-r from-red-500 to-red-600
                                            @endif"
                                            style="width: {{ ($challenge->complexity_level / 10) * 100 }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold mt-1 inline-block
                                        @if($challenge->complexity_level <= 3) text-emerald-600
                                        @elseif($challenge->complexity_level <= 6) text-amber-600
                                        @elseif($challenge->complexity_level <= 8) text-orange-600
                                        @else text-red-600
                                        @endif">
                                        @if($challenge->complexity_level <= 3) {{ __('Simple') }}
                                        @elseif($challenge->complexity_level <= 6) {{ __('Moderate') }}
                                        @elseif($challenge->complexity_level <= 8) {{ __('Complex') }}
                                        @else {{ __('Advanced') }}
                                        @endif
                                    </span>
                                </dd>
                            </div>
                        </div>
                        @endif

                        <!-- Estimated Hours -->
                        @if($latestAnalysis && $latestAnalysis->estimated_effort_hours)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Est. Effort') }}</dt>
                                <dd class="text-lg font-bold text-slate-900">{{ round($latestAnalysis->estimated_effort_hours) }} {{ __('hours') }}</dd>
                            </div>
                        </div>
                        @endif

                        <!-- Confidence Score -->
                        @if($latestAnalysis && $latestAnalysis->confidence_score)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('AI Confidence') }}</dt>
                                <dd class="text-lg font-bold text-slate-900">{{ round($latestAnalysis->confidence_score * 100) }}%</dd>
                            </div>
                        </div>
                        @endif

                        <!-- Deadline -->
                        @if($challenge->deadline)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Deadline') }}</dt>
                                <dd class="text-lg font-bold text-slate-900">{{ $challenge->deadline->translatedFormat('M d, Y') }}</dd>
                            </div>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Company Actions: Issue Certificates -->
                @auth
                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id && $challenge->canIssueCertificates())
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl border border-blue-200 p-6 shadow-sm slide-in-right" style="animation-delay: 0.5s">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center pulse-glow">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ __('Issue Certificates') }}</h3>
                                <p class="text-xs text-slate-600">{{ __('Challenge ready for completion') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-700 mb-4 leading-relaxed">
                            {{ __('Confirm completion and generate professional certificates for all participating volunteers.') }}
                        </p>
                        <a href="{{ route('challenges.confirm', $challenge) }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                            {{ __('Confirm & Issue Certificates') }}
                        </a>
                    </div>
                    @endif

                    <!-- Analytics Link (Company Owner) -->
                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id)
                    <a href="{{ route('challenges.analytics', $challenge) }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all slide-in-right" style="animation-delay: 0.6s">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900">{{ __('View Analytics') }}</h4>
                            <p class="text-sm text-slate-500">{{ __('Track progress and performance') }}</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                @endauth

                <!-- Company Info Card -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm slide-in-right" style="animation-delay: 0.7s">
                    <h3 class="text-lg font-black text-slate-900 mb-4">{{ __('Posted By') }}</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-slate-700 to-slate-900 rounded-2xl flex items-center justify-center text-white font-black text-xl">
                            {{ strtoupper(substr($challenge->company->company_name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900">{{ $challenge->company->company_name }}</h4>
                            @if($challenge->company->industry)
                            <p class="text-sm text-slate-500">{{ $challenge->company->industry }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
