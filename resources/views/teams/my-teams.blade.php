@extends('layouts.app')

@section('title', __('My Teams'))

@push('styles')
<style>
    /* Premium Animation Keyframes */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.8; }
        50% { transform: scale(1.2); opacity: 0.3; }
        100% { transform: scale(0.8); opacity: 0.8; }
    }

    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce-in {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes rotate-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes connect-pulse {
        0%, 100% { opacity: 0.3; stroke-width: 2; }
        50% { opacity: 1; stroke-width: 4; }
    }

    @keyframes avatar-pop {
        0% { transform: scale(0) rotate(-180deg); }
        100% { transform: scale(1) rotate(0deg); }
    }

    /* Hero Section */
    .teams-hero {
        background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 25%, #8b5cf6 50%, #a855f7 75%, #ec4899 100%);
        background-size: 400% 400%;
        animation: gradient-shift 15s ease infinite;
        position: relative;
        overflow: hidden;
    }

    .teams-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20v40c11.046 0 20-8.954 20-20zm0 0c0 11.046 8.954 20 20 20V10c-11.046 0-20 8.954-20 20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .floating-icon {
        animation: float 6s ease-in-out infinite;
    }

    /* Team Network Animation */
    .team-network {
        position: absolute;
        width: 200px;
        height: 200px;
        right: 5%;
        top: 50%;
        transform: translateY(-50%);
    }

    .network-node {
        animation: pulse-ring 3s ease-in-out infinite;
    }

    .network-line {
        animation: connect-pulse 2s ease-in-out infinite;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 2px solid transparent;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-card.active {
        border-color: var(--card-border);
        box-shadow: 0 20px 40px -12px var(--card-shadow);
    }

    .stat-card.active::before {
        transform: scaleX(1);
    }

    /* Team Cards */
    .team-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 2px solid #e5e7eb;
    }

    .team-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.2);
        border-color: transparent;
    }

    .team-card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--header-from) 0%, var(--header-to) 100%);
        position: relative;
    }

    .team-card-body {
        padding: 1.5rem;
    }

    /* Member Avatars */
    .member-avatars {
        display: flex;
        align-items: center;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid white;
        margin-left: -12px;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        z-index: var(--z-index);
    }

    .member-avatar:first-child {
        margin-left: 0;
    }

    .member-avatar:hover {
        transform: scale(1.2) translateY(-5px);
        z-index: 100 !important;
    }

    .member-avatar.leader {
        background: var(--gradient-warning);
        box-shadow: 0 0 0 3px var(--shadow-color-warning-light);
    }

    .member-avatar.pending {
        background: var(--gradient-slate);
        opacity: 0.7;
    }

    /* Progress Ring */
    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-circle {
        transition: stroke-dashoffset 0.5s ease;
    }

    /* Match Score Gauge */
    .match-gauge {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .match-gauge-bg {
        fill: none;
        stroke: #e5e7eb;
        stroke-width: 8;
    }

    .match-gauge-progress {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        transition: stroke-dashoffset 1s ease;
    }

    /* Skills Coverage Bar */
    .skills-coverage-bar {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .skills-coverage-fill {
        height: 100%;
        border-radius: 4px;
        background: var(--gradient-success-horizontal);
        transition: width 1s ease;
    }

    /* Invitation Card */
    .invitation-card {
        background: var(--gradient-gold);
        border: 2px solid var(--gradient-gold-border);
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        animation: slide-up 0.6s ease forwards;
    }

    .invitation-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
        animation: shimmer 3s infinite;
    }

    .invitation-pulse {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 12px;
        height: 12px;
    }

    .invitation-pulse-dot {
        width: 12px;
        height: 12px;
        background: #f59e0b;
        border-radius: 50%;
        position: absolute;
    }

    .invitation-pulse-ring {
        position: absolute;
        inset: -4px;
        border: 2px solid #f59e0b;
        border-radius: 50%;
        animation: pulse-ring 1.5s ease infinite;
    }

    /* Action Buttons */
    .btn-accept {
        background: var(--gradient-success);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-accept::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .btn-accept:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.4);
    }

    .btn-accept:hover::before {
        transform: translateX(100%);
    }

    .btn-decline {
        background: white;
        color: #6b7280;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .btn-decline:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .btn-view {
        background: var(--gradient-primary);
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.4);
    }

    /* Role Badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .role-badge.leader {
        background: var(--gradient-gold);
        color: var(--color-warning-800);
        border: 1px solid var(--color-warning-400);
    }

    .role-badge.specialist {
        background: linear-gradient(135deg, var(--color-secondary-100), var(--color-secondary-200));
        color: var(--color-secondary-800);
        border: 1px solid var(--color-secondary-400);
    }

    .role-badge.member {
        background: linear-gradient(135deg, var(--color-info-100), var(--color-info-200));
        color: var(--color-blue-dark);
        border: 1px solid var(--color-info-400);
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-badge.forming {
        background: var(--gradient-gold);
        color: var(--color-warning-800);
    }

    .status-badge.active {
        background: linear-gradient(135deg, var(--color-success-100), var(--color-success-200));
        color: var(--color-success-800);
    }

    .status-badge.completed {
        background: linear-gradient(135deg, var(--color-info-100), var(--color-info-200));
        color: var(--color-blue-dark);
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 32px;
        padding: 4rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.03'%3E%3Cpath d='M20 20c0-5.523-4.477-10-10-10v20c5.523 0 10-4.477 10-10zm0 0c0 5.523 4.477 10 10 10V10c-5.523 0-10 4.477-10 10z'/%3E%3C/g%3E%3C/svg%3E");
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 4s ease-in-out infinite;
        box-shadow: 0 20px 40px -10px var(--shadow-color-primary);
    }

    /* Declined Section */
    .declined-section {
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .declined-section:hover {
        opacity: 1;
    }

    /* Tab Navigation */
    .tab-nav {
        display: flex;
        gap: 0.5rem;
        background: #f1f5f9;
        padding: 0.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
    }

    .tab-btn {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .tab-btn:hover {
        color: #1e293b;
        background: white;
    }

    .tab-btn.active {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 15px -3px var(--shadow-color-primary);
    }

    .tab-count {
        background: rgba(255,255,255,0.2);
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
    }

    .tab-btn:not(.active) .tab-count {
        background: #e2e8f0;
        color: #64748b;
    }

    /* Animations */
    .animate-slide-up {
        animation: slide-up 0.6s ease forwards;
    }

    .animate-bounce-in {
        animation: bounce-in 0.6s ease forwards;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .team-network {
            display: none;
        }

        .tab-nav {
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1 1 45%;
        }
    }
</style>
@endpush

@section('content')
@php
    $pendingTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'invited';
    });

    $activeTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'accepted';
    });

    $declinedTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'declined';
    });

    $hasActiveTask = \App\Models\TaskAssignment::where('volunteer_id', auth()->user()->volunteer->id)
        ->whereIn('invitation_status', ['accepted', 'in_progress', 'submitted'])
        ->first();
@endphp

<!-- Premium Hero Section -->
<div class="teams-hero py-12 mb-8 rounded-3xl shadow-2xl mx-4 lg:mx-8 relative">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-icon absolute top-8 left-[10%] w-16 h-16 bg-white/10 rounded-2xl backdrop-blur-sm flex items-center justify-center" style="animation-delay: 0s;">
            <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="floating-icon absolute bottom-12 left-[20%] w-12 h-12 bg-white/10 rounded-xl backdrop-blur-sm flex items-center justify-center" style="animation-delay: 1s;">
            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div class="floating-icon absolute top-16 left-[35%] w-10 h-10 bg-white/10 rounded-lg backdrop-blur-sm flex items-center justify-center" style="animation-delay: 2s;">
            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
        </div>
    </div>

    <!-- Team Network Illustration -->
    <div class="team-network hidden lg:block">
        <svg viewBox="0 0 200 200" class="w-full h-full">
            <!-- Connection Lines -->
            <line x1="100" y1="50" x2="50" y2="100" class="network-line" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
            <line x1="100" y1="50" x2="150" y2="100" class="network-line" stroke="rgba(255,255,255,0.3)" stroke-width="2" style="animation-delay: 0.5s;"/>
            <line x1="50" y1="100" x2="75" y2="160" class="network-line" stroke="rgba(255,255,255,0.3)" stroke-width="2" style="animation-delay: 1s;"/>
            <line x1="150" y1="100" x2="125" y2="160" class="network-line" stroke="rgba(255,255,255,0.3)" stroke-width="2" style="animation-delay: 1.5s;"/>

            <!-- Central Node (Leader) -->
            <circle cx="100" cy="50" r="20" class="network-node" fill="rgba(255,255,255,0.2)" stroke="white" stroke-width="2"/>
            <text x="100" y="55" text-anchor="middle" fill="white" font-size="14" font-weight="bold">L</text>

            <!-- Member Nodes -->
            <circle cx="50" cy="100" r="16" class="network-node" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.5)" stroke-width="2" style="animation-delay: 0.3s;"/>
            <circle cx="150" cy="100" r="16" class="network-node" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.5)" stroke-width="2" style="animation-delay: 0.6s;"/>
            <circle cx="75" cy="160" r="14" class="network-node" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.4)" stroke-width="2" style="animation-delay: 0.9s;"/>
            <circle cx="125" cy="160" r="14" class="network-node" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.4)" stroke-width="2" style="animation-delay: 1.2s;"/>
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center lg:text-left lg:max-w-2xl">
            <!-- Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-4 py-2 mb-4">
                <div class="relative">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <div class="absolute inset-0 w-2 h-2 bg-green-400 rounded-full animate-ping"></div>
                </div>
                <span class="text-sm font-semibold text-white">{{ __('Team Collaboration Hub') }}</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">
                {{ __('My') }}
                <span class="bg-gradient-to-r from-yellow-200 via-pink-200 to-yellow-200 bg-clip-text text-transparent">{{ __('Teams') }}</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg text-white/90 font-medium mb-6 max-w-xl">
                {{ __('Collaborate, innovate, and achieve together. Manage your team invitations and track your collaborative journey.') }}
            </p>

            <!-- Quick Stats in Hero -->
            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/20">
                    <div class="text-2xl font-black text-white">{{ $teams->count() }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Total Teams') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/20">
                    <div class="text-2xl font-black text-yellow-300">{{ $pendingTeams->count() }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Pending') }}</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/20">
                    <div class="text-2xl font-black text-green-300">{{ $activeTeams->count() }}</div>
                    <div class="text-xs text-white/80 font-medium">{{ __('Active') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    @if($teams->count() > 0)

    <!-- Tab Navigation -->
    <div x-data="{ activeTab: '{{ $pendingTeams->count() > 0 ? 'pending' : 'active' }}' }">
        <div class="tab-nav">
            <button @click="activeTab = 'pending'"
                    :class="{ 'active': activeTab === 'pending' }"
                    class="tab-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Invitations') }}</span>
                <span class="tab-count">{{ $pendingTeams->count() }}</span>
            </button>
            <button @click="activeTab = 'active'"
                    :class="{ 'active': activeTab === 'active' }"
                    class="tab-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Active') }}</span>
                <span class="tab-count">{{ $activeTeams->count() }}</span>
            </button>
            <button @click="activeTab = 'declined'"
                    :class="{ 'active': activeTab === 'declined' }"
                    class="tab-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Declined') }}</span>
                <span class="tab-count">{{ $declinedTeams->count() }}</span>
            </button>
        </div>

        <!-- Pending Invitations Tab -->
        <div x-show="activeTab === 'pending'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
            @if($pendingTeams->count() > 0)
                <!-- Active Task Warning -->
                @if($hasActiveTask)
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-2 border-orange-300 rounded-2xl p-6 mb-6 animate-slide-up">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-orange-900 mb-1">{{ __('Active Task in Progress') }}</h3>
                            <p class="text-orange-800 mb-2">
                                {{ __('You currently have an active task:') }}
                                <a href="{{ route('tasks.show', $hasActiveTask->task_id) }}" class="underline font-bold hover:text-orange-900">
                                    {{ $hasActiveTask->task->title }}
                                </a>
                            </p>
                            <p class="text-sm text-orange-700">
                                {{ __('Complete your current task before joining new teams. You can only work on one task at a time.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-6">
                    @foreach($pendingTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                    @endphp
                    <div class="invitation-card" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="invitation-pulse">
                            <div class="invitation-pulse-dot"></div>
                            <div class="invitation-pulse-ring"></div>
                        </div>

                        <div class="p-6 relative z-10">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                                <!-- Team Info -->
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <h3 class="text-2xl font-black text-gray-900">{{ $team->name }}</h3>
                                        @if($myMembership->role === 'leader')
                                        <span class="role-badge leader">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            {{ __('Team Leader') }}
                                        </span>
                                        @elseif($myMembership->role === 'specialist')
                                        <span class="role-badge specialist">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            {{ __('Specialist') }}
                                        </span>
                                        @else
                                        <span class="role-badge member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ __('Team Member') }}
                                        </span>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 mb-4 leading-relaxed">{{ $team->description }}</p>

                                    <!-- Challenge Link -->
                                    <a href="{{ route('challenges.show', $team->challenge) }}" class="inline-flex items-center gap-2 text-amber-800 hover:text-amber-900 font-semibold mb-4 group">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span class="group-hover:underline">{{ $team->challenge->title }}</span>
                                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>

                                    <!-- Team Stats -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
                                            <div class="text-2xl font-black text-gray-900">{{ $team->members->count() }}</div>
                                            <div class="text-xs text-gray-600 font-medium">{{ __('Members') }}</div>
                                        </div>
                                        @if($team->team_match_score)
                                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
                                            <div class="text-2xl font-black text-indigo-600">{{ number_format($team->team_match_score) }}%</div>
                                            <div class="text-xs text-gray-600 font-medium">{{ __('Match Score') }}</div>
                                        </div>
                                        @endif
                                        @if($team->estimated_total_hours)
                                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
                                            <div class="text-2xl font-black text-purple-600">{{ $team->estimated_total_hours }}h</div>
                                            <div class="text-xs text-gray-600 font-medium">{{ __('Est. Hours') }}</div>
                                        </div>
                                        @endif
                                        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-3 text-center">
                                            <div class="text-2xl font-black text-green-600">{{ $team->acceptedMembers()->count() }}</div>
                                            <div class="text-xs text-gray-600 font-medium">{{ __('Accepted') }}</div>
                                        </div>
                                    </div>

                                    <!-- Your Role Description -->
                                    @if($myMembership->role_description)
                                    <div class="bg-white/80 backdrop-blur-sm border-2 border-amber-200 rounded-xl p-4">
                                        <p class="text-sm font-bold text-amber-900 mb-1">{{ __('Your Role:') }}</p>
                                        <p class="text-sm text-amber-800">{{ $myMembership->role_description }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Right Side: Members & Actions -->
                                <div class="lg:w-64 space-y-4">
                                    <!-- Team Members Preview -->
                                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-4">
                                        <p class="text-xs font-bold text-gray-700 mb-3">{{ __('Team Members') }}</p>
                                        <div class="member-avatars mb-3">
                                            @foreach($team->members->take(5) as $idx => $member)
                                            <div class="member-avatar {{ $member->role === 'leader' ? 'leader' : ($member->status === 'invited' ? 'pending' : '') }}"
                                                 style="--z-index: {{ 10 - $idx }};"
                                                 title="{{ $member->volunteer->user->name ?? 'Unknown' }} ({{ ucfirst($member->role) }})">
                                                {{ strtoupper(substr($member->volunteer->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            @endforeach
                                            @if($team->members->count() > 5)
                                            <div class="member-avatar" style="--z-index: 1; background: linear-gradient(135deg, #64748b, #475569);">
                                                +{{ $team->members->count() - 5 }}
                                            </div>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ $team->acceptedMembers()->count() }}/{{ $team->members->count() }} {{ __('members joined') }}
                                        </p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="space-y-2">
                                        @if($hasActiveTask)
                                        <button type="button" disabled class="w-full btn-decline opacity-50 cursor-not-allowed flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            {{ __('Blocked') }}
                                        </button>
                                        @else
                                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full btn-accept flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ __('Accept Invitation') }}
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full btn-decline flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Decline') }}
                                            </button>
                                        </form>

                                        <a href="{{ route('teams.show', $team) }}" class="w-full btn-view flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Pending State -->
                <div class="empty-state">
                    <div class="relative z-10">
                        <div class="empty-icon">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-3">{{ __('No Pending Invitations') }}</h2>
                        <p class="text-gray-600 max-w-md mx-auto">
                            {{ __("You don't have any pending team invitations. Keep your profile updated to get matched with new opportunities!") }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Active Teams Tab -->
        <div x-show="activeTab === 'active'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
            @if($activeTeams->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($activeTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                        $isLeader = $myMembership->role === 'leader';
                        $acceptedCount = $team->acceptedMembers()->count();
                        $totalCount = $team->members->count();
                        $acceptanceRate = $totalCount > 0 ? ($acceptedCount / $totalCount) * 100 : 0;
                    @endphp
                    <div class="team-card animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s; --header-from: {{ $team->status === 'active' ? '#10b981' : ($team->status === 'forming' ? '#f59e0b' : '#6366f1') }}; --header-to: {{ $team->status === 'active' ? '#059669' : ($team->status === 'forming' ? '#d97706' : '#4f46e5') }};">
                        <!-- Card Header -->
                        <div class="team-card-header">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-white mb-1">{{ $team->name }}</h3>
                                    <span class="status-badge {{ $team->status }}">
                                        @if($team->status === 'active')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        @elseif($team->status === 'forming')
                                        <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        @else
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endif
                                        {{ ucfirst($team->status) }}
                                    </span>
                                </div>
                                @if($isLeader)
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center" title="{{ __('Team Leader') }}">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Member Avatars -->
                            <div class="flex items-center justify-between">
                                <div class="member-avatars">
                                    @foreach($team->members->take(4) as $idx => $member)
                                    <div class="member-avatar {{ $member->role === 'leader' ? 'leader' : ($member->status !== 'accepted' ? 'pending' : '') }}"
                                         style="--z-index: {{ 10 - $idx }}; width: 36px; height: 36px; font-size: 12px;"
                                         title="{{ $member->volunteer->user->name ?? 'Unknown' }}">
                                        {{ strtoupper(substr($member->volunteer->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    @endforeach
                                    @if($team->members->count() > 4)
                                    <div class="member-avatar" style="--z-index: 1; width: 36px; height: 36px; font-size: 11px; background: rgba(255,255,255,0.3);">
                                        +{{ $team->members->count() - 4 }}
                                    </div>
                                    @endif
                                </div>
                                <span class="text-white/90 text-sm font-medium">{{ $acceptedCount }}/{{ $totalCount }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="team-card-body">
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($team->description, 100) }}</p>

                            <!-- Challenge Link -->
                            <a href="{{ route('challenges.show', $team->challenge) }}" class="flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-semibold text-sm mb-4 group">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="truncate group-hover:underline">{{ Str::limit($team->challenge->title, 35) }}</span>
                            </a>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                @if($team->team_match_score)
                                <div class="bg-indigo-50 rounded-xl p-3 text-center">
                                    <div class="text-xl font-black text-indigo-600">{{ number_format($team->team_match_score) }}%</div>
                                    <div class="text-xs text-indigo-600/70 font-medium">{{ __('Match') }}</div>
                                </div>
                                @endif
                                @if($team->estimated_total_hours)
                                <div class="bg-purple-50 rounded-xl p-3 text-center">
                                    <div class="text-xl font-black text-purple-600">{{ $team->estimated_total_hours }}h</div>
                                    <div class="text-xs text-purple-600/70 font-medium">{{ __('Est. Hours') }}</div>
                                </div>
                                @endif
                            </div>

                            <!-- Team Progress -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-gray-600 font-medium">{{ __('Team Formation') }}</span>
                                    <span class="text-gray-900 font-bold">{{ number_format($acceptanceRate) }}%</span>
                                </div>
                                <div class="skills-coverage-bar">
                                    <div class="skills-coverage-fill" style="width: {{ $acceptanceRate }}%;"></div>
                                </div>
                            </div>

                            <!-- Your Skills -->
                            @if($myMembership->assigned_skills && count($myMembership->assigned_skills) > 0)
                            <div class="mb-4">
                                <p class="text-xs font-bold text-gray-700 mb-2">{{ __('Your Skills:') }}</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($myMembership->assigned_skills, 0, 3) as $skill)
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold">{{ $skill }}</span>
                                    @endforeach
                                    @if(count($myMembership->assigned_skills) > 3)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">+{{ count($myMembership->assigned_skills) - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- View Team Button -->
                            <a href="{{ route('teams.show', $team) }}" class="w-full btn-view flex items-center justify-center gap-2">
                                {{ __('View Team') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Active State -->
                <div class="empty-state">
                    <div class="relative z-10">
                        <div class="empty-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-3">{{ __('No Active Teams') }}</h2>
                        <p class="text-gray-600 max-w-md mx-auto mb-6">
                            {{ __("You haven't joined any teams yet. Accept a pending invitation to start collaborating!") }}
                        </p>
                        @if($pendingTeams->count() > 0)
                        <button @click="activeTab = 'pending'" class="btn-view inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19"/>
                            </svg>
                            {{ __('View Invitations') }} ({{ $pendingTeams->count() }})
                        </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Declined Teams Tab -->
        <div x-show="activeTab === 'declined'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
            @if($declinedTeams->count() > 0)
                <div class="declined-section space-y-4">
                    @foreach($declinedTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                    @endphp
                    <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-5 animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-700">{{ $team->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $team->challenge->title }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                @if($myMembership->declined_at)
                                <span class="text-sm text-gray-500">
                                    {{ __('Declined') }} {{ $myMembership->declined_at->diffForHumans() }}
                                </span>
                                @endif
                                <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm font-semibold">
                                    {{ __('Declined') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Declined State -->
                <div class="empty-state">
                    <div class="relative z-10">
                        <div class="empty-icon" style="background: linear-gradient(135deg, #9ca3af, #6b7280);">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-3">{{ __('No Declined Invitations') }}</h2>
                        <p class="text-gray-600 max-w-md mx-auto">
                            {{ __("You haven't declined any team invitations. All past invitations were either accepted or are still pending.") }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @else
    <!-- No Teams Empty State -->
    <div class="empty-state">
        <div class="relative z-10">
            <div class="empty-icon">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">{{ __('No Teams Yet') }}</h2>
            <p class="text-gray-600 mb-8 max-w-lg mx-auto text-lg">
                {{ __("You haven't been invited to any teams yet. Complete your profile and upload your CV to get matched with exciting team opportunities!") }}
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('profile.edit') }}" class="btn-view inline-flex items-center justify-center gap-2 px-8 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Update Profile') }}
                </a>
                <a href="{{ route('assignments.my') }}" class="btn-decline inline-flex items-center justify-center gap-2 px-8 py-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    {{ __('View My Tasks') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
