@extends('layouts.app')

@section('title', __('Edit Profile'))

@push('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.8; }
        50% { transform: scale(1.2); opacity: 0.3; }
        100% { transform: scale(0.8); opacity: 0.8; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes glow-pulse {
        0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
        50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
    }

    @keyframes rotate-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes bounce-gentle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-pulse-ring {
        animation: pulse-ring 3s ease-in-out infinite;
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-shift 4s ease infinite;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-glow {
        animation: glow-pulse 2s ease-in-out infinite;
    }

    .animate-rotate-slow {
        animation: rotate-slow 20s linear infinite;
    }

    .animate-bounce-gentle {
        animation: bounce-gentle 2s ease-in-out infinite;
    }

    /* Stagger animations */
    .stagger-1 { animation-delay: 0.1s; opacity: 0; }
    .stagger-2 { animation-delay: 0.2s; opacity: 0; }
    .stagger-3 { animation-delay: 0.3s; opacity: 0; }
    .stagger-4 { animation-delay: 0.4s; opacity: 0; }
    .stagger-5 { animation-delay: 0.5s; opacity: 0; }

    /* Premium Card Effects */
    .premium-card {
        position: relative;
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-gradient-from, #10b981), var(--card-gradient-to, #06b6d4));
    }

    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    /* Shimmer Effect */
    .shimmer-btn {
        position: relative;
        overflow: hidden;
    }

    .shimmer-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    /* Glassmorphism */
    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Profile Avatar Glow */
    .avatar-glow {
        position: relative;
    }

    .avatar-glow::before {
        content: '';
        position: absolute;
        inset: -4px;
        background: linear-gradient(45deg, #10b981, #06b6d4, #8b5cf6, #ec4899);
        border-radius: 50%;
        z-index: -1;
        animation: rotate-slow 8s linear infinite;
    }

    /* Progress Ring */
    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-circle {
        transition: stroke-dashoffset 0.5s ease;
    }

    /* Input Focus Effects */
    .premium-input {
        transition: all 0.3s ease;
    }

    .premium-input:focus {
        transform: scale(1.01);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }

    /* Floating Labels */
    .floating-label-group {
        position: relative;
    }

    .floating-label-group input:focus + label,
    .floating-label-group input:not(:placeholder-shown) + label {
        transform: translateY(-1.5rem) scale(0.85);
        color: #8b5cf6;
    }

    /* Quick Stats Hover */
    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Section Divider */
    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.3), transparent);
    }

    /* Particle Effects */
    .particle {
        position: absolute;
        width: 6px;
        height: 6px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        animation: float 4s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-violet-50/30 to-emerald-50/30">
    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 py-16 mb-12">
        <!-- Animated Background Effects -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Gradient Overlays -->
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-emerald-400/30 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-cyan-400/30 via-transparent to-transparent"></div>

            <!-- Floating Orbs -->
            <div class="absolute top-10 left-[10%] w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 right-[10%] w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl animate-pulse-ring"></div>

            <!-- Decorative Grid -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <!-- Particles -->
            <div class="particle top-[20%] left-[15%]" style="animation-delay: 0s;"></div>
            <div class="particle top-[40%] left-[25%]" style="animation-delay: 1s;"></div>
            <div class="particle top-[60%] right-[20%]" style="animation-delay: 2s;"></div>
            <div class="particle top-[30%] right-[30%]" style="animation-delay: 3s;"></div>
            <div class="particle bottom-[25%] left-[40%]" style="animation-delay: 1.5s;"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 sm:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-10">
                <!-- Profile Avatar with Progress Ring -->
                <div class="relative">
                    <!-- Animated Ring -->
                    <div class="absolute -inset-4 animate-rotate-slow">
                        <svg class="w-full h-full" viewBox="0 0 200 200">
                            <defs>
                                <linearGradient id="avatarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#10b981"/>
                                    <stop offset="50%" style="stop-color:#06b6d4"/>
                                    <stop offset="100%" style="stop-color:#8b5cf6"/>
                                </linearGradient>
                            </defs>
                            <circle cx="100" cy="100" r="95" fill="none" stroke="url(#avatarGradient)" stroke-width="2" stroke-dasharray="10 5" opacity="0.5"/>
                        </svg>
                    </div>

                    <!-- Avatar Container -->
                    <div class="relative group">
                        <div class="absolute -inset-2 bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 rounded-full blur opacity-50 group-hover:opacity-70 transition duration-500"></div>
                        <div class="relative w-36 h-36 lg:w-44 lg:h-44 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-2xl ring-4 ring-white/30 overflow-hidden">
                            @if(auth()->user()->isVolunteer() && auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @elseif(auth()->user()->isCompany() && auth()->user()->company && auth()->user()->company->logo_path)
                                <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}" alt="{{ auth()->user()->company->company_name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl lg:text-6xl font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            @endif
                        </div>

                        <!-- Edit Avatar Button -->
                        <div class="absolute bottom-2 right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="text-center lg:text-left flex-1">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 glass rounded-full px-5 py-2.5 mb-5 animate-slide-up">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-bold text-white/90">{{ __('Profile Settings') }}</span>
                        <span class="px-2 py-0.5 bg-white/20 rounded-full text-xs font-bold text-white">
                            @if(auth()->user()->isVolunteer())
                                {{ __('Contributor') }}
                            @else
                                {{ __('Company') }}
                            @endif
                        </span>
                    </div>

                    <!-- Name -->
                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-3 animate-slide-up stagger-1">
                        {{ auth()->user()->name }}
                    </h1>

                    <!-- Email -->
                    <p class="text-lg text-white/70 mb-6 animate-slide-up stagger-2">{{ auth()->user()->email }}</p>

                    <!-- Quick Stats Row -->
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 animate-slide-up stagger-3">
                        @if(auth()->user()->isVolunteer() && auth()->user()->volunteer)
                            <div class="glass rounded-xl px-5 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/60 font-medium">{{ __('Reputation') }}</p>
                                    <p class="text-xl font-black text-white">{{ auth()->user()->volunteer->reputation_score ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="glass rounded-xl px-5 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/60 font-medium">{{ __('Hours/Week') }}</p>
                                    <p class="text-xl font-black text-white">{{ auth()->user()->volunteer->availability_hours_per_week ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="glass rounded-xl px-5 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/60 font-medium">{{ __('Skills') }}</p>
                                    <p class="text-xl font-black text-white">{{ auth()->user()->volunteer->skills->count() ?? 0 }}</p>
                                </div>
                            </div>
                        @else
                            <div class="glass rounded-xl px-5 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-white/60 font-medium">{{ __('Challenges') }}</p>
                                    <p class="text-xl font-black text-white">{{ auth()->user()->company->total_challenges_submitted ?? 0 }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Completion Card -->
                @php
                    $completionPercentage = 0;
                    $totalFields = 5;
                    $completedFields = 0;

                    if(auth()->user()->name) $completedFields++;
                    if(auth()->user()->email) $completedFields++;
                    if(auth()->user()->isVolunteer() && auth()->user()->volunteer) {
                        if(auth()->user()->volunteer->profile_picture) $completedFields++;
                        if(auth()->user()->volunteer->bio) $completedFields++;
                        if(auth()->user()->volunteer->skills->count() > 0) $completedFields++;
                    } elseif(auth()->user()->isCompany() && auth()->user()->company) {
                        if(auth()->user()->company->logo_path) $completedFields++;
                        if(auth()->user()->company->description) $completedFields++;
                        if(auth()->user()->company->industry) $completedFields++;
                    }
                    $completionPercentage = round(($completedFields / $totalFields) * 100);
                @endphp

                <div class="hidden xl:block">
                    <div class="glass rounded-2xl p-6 animate-slide-up stagger-4">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="relative">
                                <svg class="w-20 h-20 progress-ring" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="8"/>
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="url(#progressGradient)" stroke-width="8"
                                            stroke-linecap="round" class="progress-ring-circle"
                                            stroke-dasharray="251.2"
                                            stroke-dashoffset="{{ 251.2 - (251.2 * $completionPercentage / 100) }}"/>
                                    <defs>
                                        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                            <stop offset="0%" style="stop-color:#10b981"/>
                                            <stop offset="100%" style="stop-color:#06b6d4"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xl font-black text-white">{{ $completionPercentage }}%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-white/70 font-medium">{{ __('Profile') }}</p>
                                <p class="text-lg font-bold text-white">{{ __('Completion') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-white/60">
                            @if($completionPercentage < 100)
                                {{ __('Complete your profile to unlock all features') }}
                            @else
                                {{ __('Your profile is complete!') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <!-- Navigation Tabs -->
        <div x-data="{ activeTab: 'account', ...securitySettings() }" class="mb-10">
            <div class="flex flex-wrap gap-2 p-2 bg-white rounded-2xl shadow-lg border border-slate-100 mb-8">
                <button @click="activeTab = 'account'"
                        :class="activeTab === 'account' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-50'"
                        class="flex-1 sm:flex-none px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Account') }}
                </button>
                <button @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'bg-gradient-to-r from-violet-500 to-purple-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-50'"
                        class="flex-1 sm:flex-none px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    @if(auth()->user()->isVolunteer())
                        {{ __('Contributor') }}
                    @else
                        {{ __('Company') }}
                    @endif
                </button>
                <button @click="activeTab = 'security'"
                        :class="activeTab === 'security' ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-50'"
                        class="flex-1 sm:flex-none px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    {{ __('Security') }}
                </button>
            </div>

            <!-- Account Tab -->
            <div x-show="activeTab === 'account'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="premium-card shadow-xl border border-slate-100" style="--card-gradient-from: #10b981; --card-gradient-to: #06b6d4;">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-white">{{ __('Account Information') }}</h2>
                                <p class="text-emerald-100">{{ __('Your basic account details and settings') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name Card -->
                            <div class="stat-card group bg-gradient-to-br from-slate-50 to-emerald-50/50 rounded-2xl p-6 border-2 border-slate-100 hover:border-emerald-200">
                                <div class="flex items-start gap-4">
                                    <div class="stat-icon w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Full Name') }}</dt>
                                        <dd class="text-xl font-bold text-slate-900">{{ auth()->user()->name }}</dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Card -->
                            <div class="stat-card group bg-gradient-to-br from-slate-50 to-blue-50/50 rounded-2xl p-6 border-2 border-slate-100 hover:border-blue-200">
                                <div class="flex items-start gap-4">
                                    <div class="stat-icon w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Email Address') }}</dt>
                                        <dd class="text-xl font-bold text-slate-900 break-all">{{ auth()->user()->email }}</dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Type Card -->
                            <div class="stat-card group bg-gradient-to-br from-slate-50 to-violet-50/50 rounded-2xl p-6 border-2 border-slate-100 hover:border-violet-200">
                                <div class="flex items-start gap-4">
                                    <div class="stat-icon w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg transition-transform">
                                        @if(auth()->user()->isVolunteer())
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1h-3a1 1 0 01-1-1v-2H9v2a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Account Type') }}</dt>
                                        <dd>
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-violet-500 to-purple-500 text-white rounded-xl text-sm font-bold shadow-lg">
                                                {{ ucfirst(auth()->user()->user_type) }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- LinkedIn Card -->
                            <div class="stat-card group bg-gradient-to-br from-slate-50 to-sky-50/50 rounded-2xl p-6 border-2 border-slate-100 hover:border-sky-200">
                                <div class="flex items-start gap-4">
                                    <div class="stat-icon w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg transition-transform">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('LinkedIn') }}</dt>
                                        <dd>
                                            @if(auth()->user()->linkedin_id)
                                            <a href="{{ auth()->user()->linkedin_profile_url }}" target="_blank" class="inline-flex items-center gap-2 text-sky-600 hover:text-sky-800 font-bold transition-colors group">
                                                {{ __('View Profile') }}
                                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                            @else
                                            <span class="inline-flex items-center gap-2 text-slate-400 text-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                </svg>
                                                {{ __('Not connected') }}
                                            </span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Since -->
                        <div class="mt-8 pt-8 border-t-2 border-slate-100">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-medium">{{ __('Member Since') }}</p>
                                        <p class="text-lg font-bold text-slate-900">{{ auth()->user()->created_at->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-bold text-sm">{{ __('Active Account') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-1 transform translate-y-0">
                @if(auth()->user()->isVolunteer() && auth()->user()->volunteer)
                <!-- Contributor Profile Card -->
                <div class="premium-card shadow-xl border border-slate-100" style="--card-gradient-from: #8b5cf6; --card-gradient-to: #a855f7;">
                    <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 px-8 py-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-white">{{ __('Contributor Profile') }}</h2>
                                <p class="text-violet-100">{{ __('Manage your volunteer information and skills') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.volunteer.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                        @csrf

                        <!-- Profile Picture Upload -->
                        <div x-data="{ preview: null }" class="bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-50 border-2 border-violet-100 rounded-3xl p-8">
                            <label class="block text-lg font-black text-slate-900 mb-6">{{ __('Profile Picture') }}</label>
                            <div class="flex flex-col sm:flex-row items-center gap-10">
                                <!-- Current/Preview Picture -->
                                <div class="relative group cursor-pointer">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-violet-400 via-purple-400 to-pink-400 rounded-full blur opacity-40 group-hover:opacity-60 transition-all duration-500 animate-pulse-ring"></div>
                                    <div class="relative w-40 h-40 rounded-full border-4 border-white overflow-hidden bg-gradient-to-br from-violet-100 to-purple-100 shadow-2xl group-hover:scale-105 transition-transform duration-300">
                                        <template x-if="preview">
                                            <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!preview">
                                            @if(auth()->user()->volunteer->profile_picture)
                                                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}"
                                                     alt="{{ auth()->user()->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </template>
                                    </div>
                                </div>
                                <!-- Upload Button -->
                                <div class="flex-1 text-center sm:text-left">
                                    <label for="profile_picture" class="cursor-pointer inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 text-white font-bold rounded-2xl hover:from-violet-700 hover:via-purple-700 hover:to-fuchsia-700 transition-all shadow-xl hover:shadow-2xl hover:scale-105 shimmer-btn">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('Upload New Photo') }}
                                    </label>
                                    <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden"
                                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
                                    <p class="text-sm text-slate-500 mt-4">{{ __('JPG, PNG or GIF (Max 2MB)') }}</p>
                                    <p class="text-xs text-violet-500 mt-1">{{ __('Recommended: Square image, 400x400 pixels') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-100 rounded-2xl p-6 group hover:border-blue-300 transition-all">
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('Availability (hours/week)') }}
                                </label>
                                <input type="number" name="availability_hours_per_week" min="0" max="168"
                                       lang="en" inputmode="numeric" pattern="[0-9]*"
                                       class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all font-semibold text-lg bg-white"
                                       value="{{ auth()->user()->volunteer->availability_hours_per_week }}"
                                       placeholder="{{ __('Enter hours (0-168)') }}">
                                <p class="text-xs text-slate-500 mt-3 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('Hours you can contribute per week') }}
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-6 group hover:border-amber-300 transition-all">
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ __('Reputation Score') }}
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-xl animate-glow">
                                        <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-5xl font-black bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ auth()->user()->volunteer->reputation_score }}</span>
                                        <p class="text-sm text-slate-500 mt-1">{{ __('Points earned') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="bg-gradient-to-br from-slate-50 to-violet-50/50 border-2 border-slate-100 rounded-2xl p-6 hover:border-violet-200 transition-all">
                            <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                {{ __('Bio') }}
                            </label>
                            <textarea name="bio" rows="5" class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-violet-500 focus:ring-4 focus:ring-violet-500/20 transition-all font-medium resize-none bg-white" placeholder="{{ __('Tell us about yourself, your experience, and what you\'re passionate about...') }}">{{ auth()->user()->volunteer->bio }}</textarea>
                            <p class="text-xs text-slate-500 mt-2">{{ __('A great bio helps companies understand your background') }}</p>
                        </div>

                        <!-- CV Analysis Status -->
                        <div class="bg-gradient-to-br from-slate-50 to-blue-50/50 border-2 border-slate-100 rounded-2xl p-6">
                            <label class="block text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                {{ __('CV Analysis Status') }}
                            </label>
                            <div class="flex items-center flex-wrap gap-4">
                                <span class="px-6 py-3 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2
                                    {{ auth()->user()->volunteer->ai_analysis_status === 'completed' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white' : '' }}
                                    {{ auth()->user()->volunteer->ai_analysis_status === 'processing' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white' : '' }}
                                    {{ auth()->user()->volunteer->ai_analysis_status === 'pending' ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white' : '' }}
                                    {{ auth()->user()->volunteer->ai_analysis_status === 'failed' ? 'bg-gradient-to-r from-red-500 to-rose-500 text-white' : '' }}">
                                    @if(auth()->user()->volunteer->ai_analysis_status === 'completed')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif(auth()->user()->volunteer->ai_analysis_status === 'processing')
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    @elseif(auth()->user()->volunteer->ai_analysis_status === 'pending')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                    {{ __(ucfirst(auth()->user()->volunteer->ai_analysis_status)) }}
                                </span>
                                @if(auth()->user()->volunteer->ai_analysis_confidence)
                                <span class="px-6 py-3 bg-gradient-to-r from-blue-100 to-indigo-100 border-2 border-blue-200 rounded-xl text-sm font-bold text-blue-700 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('Confidence:') }} {{ round(auth()->user()->volunteer->ai_analysis_confidence) }}%
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Extracted Skills -->
                        @if(auth()->user()->volunteer->skills->count() > 0)
                        <div class="bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-50 border-2 border-violet-100 rounded-2xl p-6">
                            <label class="block text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                {{ __('Extracted Skills') }}
                                <span class="ml-2 px-2 py-1 bg-violet-200 text-violet-700 rounded-full text-xs font-bold">{{ auth()->user()->volunteer->skills->count() }}</span>
                            </label>
                            <div class="flex flex-wrap gap-3">
                                @foreach(auth()->user()->volunteer->skills as $skill)
                                <span class="px-4 py-2.5 rounded-xl text-sm font-bold shadow-md transition-all hover:scale-105 hover:shadow-lg cursor-default
                                    {{ $skill->proficiency_level === 'expert' ? 'bg-gradient-to-r from-violet-500 to-purple-500 text-white' : '' }}
                                    {{ $skill->proficiency_level === 'advanced' ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white' : '' }}
                                    {{ $skill->proficiency_level === 'intermediate' ? 'bg-gradient-to-r from-emerald-500 to-green-500 text-white' : '' }}
                                    {{ !in_array($skill->proficiency_level, ['expert', 'advanced', 'intermediate']) ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                    {{ $skill->skill_name }}
                                    @if($skill->proficiency_level)
                                    <span class="text-xs opacity-80 ml-1">({{ __(ucfirst($skill->proficiency_level)) }})</span>
                                    @endif
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Upload New CV -->
                        <div class="bg-gradient-to-br from-slate-50 to-violet-50/50 border-2 border-dashed border-violet-300 rounded-2xl p-8 hover:border-violet-400 transition-all group">
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto bg-gradient-to-br from-violet-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-xl mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <label class="block text-lg font-bold text-slate-900 mb-2">{{ __('Upload New CV') }}</label>
                                <p class="text-sm text-slate-500 mb-4">{{ __('Upload a new CV (PDF, DOC, or DOCX)') }}</p>
                                <label for="cv_upload" class="cursor-pointer inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-violet-300 text-violet-600 font-bold rounded-xl hover:bg-violet-50 hover:border-violet-400 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    {{ __('Choose File') }}
                                </label>
                                <input id="cv_upload" type="file" name="cv" accept=".pdf,.doc,.docx" class="hidden">
                                <p class="text-xs text-violet-500 mt-4">{{ __('AI will automatically analyze and extract your skills') }}</p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-3 bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 text-white font-bold text-lg px-10 py-5 rounded-2xl transition-all transform hover:scale-[1.02] shadow-xl hover:shadow-2xl shimmer-btn">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Save Changes') }}
                        </button>
                    </form>
                </div>
                @endif

                @if(auth()->user()->isCompany() && auth()->user()->company)
                <!-- Company Profile Card -->
                <div class="premium-card shadow-xl border border-slate-100" style="--card-gradient-from: #0284c7; --card-gradient-to: #06b6d4;">
                    <div class="bg-gradient-to-r from-sky-600 via-blue-600 to-cyan-600 px-8 py-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-white">{{ __('Company Profile') }}</h2>
                                <p class="text-sky-100">{{ __('Manage your company information') }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.company.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                        @csrf

                        <!-- Company Logo -->
                        <div x-data="{ logoPreview: null }" class="bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 border-2 border-sky-100 rounded-3xl p-8">
                            <label class="block text-lg font-black text-slate-900 mb-6">{{ __('Company Logo') }}</label>
                            <div class="flex flex-col sm:flex-row items-center gap-10">
                                <!-- Current/Preview Logo -->
                                <div class="relative group">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-sky-400 via-blue-400 to-cyan-400 rounded-2xl blur opacity-30 group-hover:opacity-50 transition-all duration-500"></div>
                                    <div class="relative w-36 h-36 rounded-2xl border-4 border-white overflow-hidden bg-gradient-to-br from-sky-100 to-blue-100 shadow-2xl group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                                        <template x-if="logoPreview">
                                            <img :src="logoPreview" alt="Preview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!logoPreview">
                                            @if(auth()->user()->company->logo_path)
                                                <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}"
                                                     alt="{{ auth()->user()->company->company_name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-16 h-16 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            @endif
                                        </template>
                                    </div>
                                </div>
                                <!-- Upload Button -->
                                <div class="flex-1 text-center sm:text-left">
                                    <label for="company_logo" class="cursor-pointer inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-sky-600 via-blue-600 to-cyan-600 text-white font-bold rounded-2xl hover:from-sky-700 hover:via-blue-700 hover:to-cyan-700 transition-all shadow-xl hover:shadow-2xl hover:scale-105 shimmer-btn">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('Upload New Logo') }}
                                    </label>
                                    <input id="company_logo" name="logo" type="file" accept="image/*" class="hidden"
                                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => logoPreview = e.target.result; reader.readAsDataURL(file); }">
                                    <p class="text-sm text-slate-500 mt-4">{{ __('JPG, PNG or SVG (Max 2MB)') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="bg-gradient-to-br from-sky-50 to-blue-50/50 border-2 border-sky-100 rounded-2xl p-6 hover:border-sky-200 transition-all">
                            <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ __('Company Name') }}
                            </label>
                            <input type="text" name="company_name" class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-sky-500 focus:ring-4 focus:ring-sky-500/20 transition-all font-semibold text-lg bg-white" value="{{ auth()->user()->company->company_name }}">
                        </div>

                        <!-- Industry & Website -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-slate-50 to-sky-50/50 border-2 border-slate-100 rounded-2xl p-6 hover:border-sky-200 transition-all">
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ __('Industry') }}
                                </label>
                                <input type="text" name="industry" class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-sky-500 focus:ring-4 focus:ring-sky-500/20 transition-all font-semibold bg-white" value="{{ auth()->user()->company->industry }}">
                            </div>
                            <div class="bg-gradient-to-br from-slate-50 to-sky-50/50 border-2 border-slate-100 rounded-2xl p-6 hover:border-sky-200 transition-all">
                                <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                    {{ __('Website') }}
                                </label>
                                <input type="url" name="website" class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-sky-500 focus:ring-4 focus:ring-sky-500/20 transition-all font-semibold bg-white" value="{{ auth()->user()->company->website }}" placeholder="https://example.com">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-gradient-to-br from-slate-50 to-sky-50/50 border-2 border-slate-100 rounded-2xl p-6 hover:border-sky-200 transition-all">
                            <label class="block text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                {{ __('Description') }}
                            </label>
                            <textarea name="description" rows="5" class="premium-input w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-sky-500 focus:ring-4 focus:ring-sky-500/20 transition-all font-medium resize-none bg-white" placeholder="{{ __('Tell volunteers about your company...') }}">{{ auth()->user()->company->description }}</textarea>
                        </div>

                        <!-- Statistics -->
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-2xl p-8">
                            <label class="block text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ __('Company Statistics') }}
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div class="stat-card bg-white rounded-2xl p-6 text-center shadow-lg border border-emerald-100">
                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl stat-icon transition-transform">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <dt class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">{{ __('Total Challenges') }}</dt>
                                    <dd class="text-4xl font-black bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">{{ auth()->user()->company->total_challenges_submitted }}</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-3 bg-gradient-to-r from-sky-600 via-blue-600 to-cyan-600 text-white font-bold text-lg px-10 py-5 rounded-2xl transition-all transform hover:scale-[1.02] shadow-xl hover:shadow-2xl shimmer-btn">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Save Changes') }}
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-1 transform translate-y-0">
                <div class="premium-card shadow-xl border border-slate-100" style="--card-gradient-from: #e11d48; --card-gradient-to: #ec4899;">
                    <div class="bg-gradient-to-r from-rose-600 via-pink-600 to-fuchsia-600 px-8 py-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-white">{{ __('Security Settings') }}</h2>
                                <p class="text-rose-100">{{ __('Manage your account security and privacy') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 space-y-8">
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-emerald-700">{{ session('success') }}</p>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-red-700">{{ __('There were errors with your request') }}</p>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-600 ml-13">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Password Section - Only show for users who have a password (not OAuth-only users) -->
                        @if(auth()->user()->hasPassword())
                        <div class="bg-gradient-to-br from-rose-50 to-pink-50 border-2 border-rose-100 rounded-2xl p-6">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ __('Password') }}</h3>
                                        <p class="text-sm text-slate-500">
                                            @if(auth()->user()->password_changed_at)
                                                {{ __('Last changed:') }} {{ auth()->user()->password_changed_at->diffForHumans() }}
                                            @else
                                                {{ __('Never changed') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <button type="button" @click="showPasswordModal = true" class="px-5 py-2.5 bg-white border-2 border-rose-200 text-rose-600 font-bold rounded-xl hover:bg-rose-50 hover:border-rose-300 transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    {{ __('Change Password') }}
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Two-Factor Authentication -->
                        <div class="bg-gradient-to-br from-violet-50 to-purple-50 border-2 border-violet-100 rounded-2xl p-6">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br {{ auth()->user()->hasTwoFactorEnabled() ? 'from-emerald-500 to-teal-500' : 'from-violet-500 to-purple-500' }} rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ __('Two-Factor Authentication') }}</h3>
                                        @if(auth()->user()->hasTwoFactorEnabled())
                                            <p class="text-sm text-emerald-600 font-semibold flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ __('Enabled since') }} {{ auth()->user()->two_factor_confirmed_at->format('M j, Y') }}
                                            </p>
                                        @else
                                            <p class="text-sm text-slate-500">{{ __('Add an extra layer of security to your account') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(auth()->user()->hasTwoFactorEnabled())
                                        <button type="button" @click="showRecoveryCodesModal = true" class="px-4 py-2.5 bg-white border-2 border-violet-200 text-violet-600 font-bold rounded-xl hover:bg-violet-50 hover:border-violet-300 transition-all text-sm">
                                            {{ __('View Recovery Codes') }}
                                        </button>
                                        <button type="button" @click="showDisable2FAModal = true" class="px-4 py-2.5 bg-white border-2 border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-300 transition-all text-sm">
                                            {{ __('Disable') }}
                                        </button>
                                    @else
                                        <button type="button" @click="enableTwoFactor()" :disabled="loading2FA" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all flex items-center gap-2 disabled:opacity-50">
                                            <template x-if="loading2FA">
                                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </template>
                                            <template x-if="!loading2FA">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </template>
                                            {{ __('Enable 2FA') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @if(auth()->user()->hasTwoFactorEnabled())
                            <div class="mt-4 pt-4 border-t border-violet-200">
                                <p class="text-sm text-slate-600">
                                    <span class="font-semibold">{{ __('Recovery codes remaining:') }}</span>
                                    <span class="ml-1 px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full text-xs font-bold">{{ auth()->user()->getRecoveryCodesCount() }}</span>
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Active Sessions -->
                        <div class="bg-gradient-to-br from-slate-50 to-emerald-50/50 border-2 border-slate-100 rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900">{{ __('Current Session') }}</h3>
                                    <p class="text-sm text-slate-500">{{ __('Your current active session') }}</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-slate-200">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ request()->header('User-Agent') ? substr(request()->header('User-Agent'), 0, 50) . '...' : 'Unknown Device' }}</p>
                                            <p class="text-xs text-slate-500">{{ request()->ip() }} - {{ __('Active now') }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                                        {{ __('This device') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl p-6">
                            <h3 class="font-bold text-red-700 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ __('Danger Zone') }}
                            </h3>
                            <p class="text-sm text-slate-600 mb-4">{{ __('Once you delete your account, there is no going back. Please be certain.') }}</p>
                            <button type="button" class="px-5 py-2.5 bg-white border-2 border-red-300 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-400 transition-all">
                                {{ __('Delete Account') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change Modal -->
            <div x-show="showPasswordModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" @keydown.escape.window="if(!changingPassword) { showPasswordModal = false; resetPasswordForm(); }">
                <!-- Simple Dark Overlay -->
                <div x-show="showPasswordModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 z-10 bg-slate-900/50" @click="if(!changingPassword) { showPasswordModal = false; resetPasswordForm(); }"></div>
                <!-- Modal Dialog -->
                <div x-show="showPasswordModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-20 bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.stop>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-rose-500 to-pink-500">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            {{ __('Change Password') }}
                        </h3>
                        <button type="button" @click="if(!changingPassword) { showPasswordModal = false; resetPasswordForm(); }" class="text-white/80 hover:text-white transition-colors" :class="{ 'opacity-50 cursor-not-allowed': changingPassword }">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Success Message -->
                    <div x-show="passwordSuccess" x-transition class="px-6 py-8 text-center">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-2">{{ __('Password Changed Successfully!') }}</h4>
                        <p class="text-slate-600">{{ __('Your password has been updated. Redirecting...') }}</p>
                    </div>

                    <!-- Form Content -->
                    <div x-show="!passwordSuccess">
                        <!-- Body -->
                        <div class="px-6 py-5 space-y-4">
                            <!-- Error Message -->
                            <div x-show="passwordError" x-transition class="bg-red-50 border border-red-200 rounded-lg p-3 flex items-start gap-2">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-red-700" x-text="passwordError"></span>
                            </div>

                            @if(auth()->user()->hasPassword())
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Current Password') }}</label>
                                <input type="password" x-model="currentPassword" :disabled="changingPassword" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all disabled:bg-slate-100 disabled:cursor-not-allowed" placeholder="{{ __('Enter current password') }}">
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('New Password') }}</label>
                                <input type="password" x-model="newPassword" :disabled="changingPassword" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all disabled:bg-slate-100 disabled:cursor-not-allowed" placeholder="{{ __('Enter new password') }}">
                                <p class="text-xs text-slate-500 mt-1">{{ __('Min 8 characters, with uppercase, lowercase, and numbers') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Confirm Password') }}</label>
                                <input type="password" x-model="confirmPassword" :disabled="changingPassword" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all disabled:bg-slate-100 disabled:cursor-not-allowed" placeholder="{{ __('Confirm new password') }}">
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                            <button type="button" @click="showPasswordModal = false; resetPasswordForm();" :disabled="changingPassword" class="px-4 py-2 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" @click="changePassword()" :disabled="changingPassword || !newPassword || !confirmPassword" class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold rounded-lg hover:from-rose-600 hover:to-pink-600 transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <template x-if="changingPassword">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <template x-if="!changingPassword">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                                <span x-text="changingPassword ? '{{ __('Updating...') }}' : '{{ __('Confirm Change') }}'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enable 2FA Modal -->
            <div x-show="showEnable2FAModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" @keydown.escape.window="showEnable2FAModal = false">
                <!-- Simple Dark Overlay -->
                <div x-show="showEnable2FAModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 z-10 bg-slate-900/50" @click="showEnable2FAModal = false; if(showRecoveryCodes) location.reload();"></div>
                <!-- Modal Dialog -->
                <div x-show="showEnable2FAModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-20 bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden" @click.stop>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-violet-500 to-purple-500">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Enable 2FA') }}
                        </h3>
                        <button type="button" @click="showEnable2FAModal = false; if(showRecoveryCodes) location.reload();" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="px-6 py-5 space-y-4 max-h-[60vh] overflow-y-auto">
                        <!-- Step 1: QR Code -->
                        <div x-show="!showRecoveryCodes">
                            <p class="text-sm text-slate-600 mb-3">{{ __('Scan this QR code with your authenticator app:') }}</p>
                            <div class="flex justify-center mb-3">
                                <div class="p-3 bg-white border border-slate-200 rounded-xl" x-html="qrCode"></div>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-3 mb-3">
                                <p class="text-xs text-slate-500 mb-1">{{ __('Or enter manually:') }}</p>
                                <p class="font-mono text-sm font-bold text-slate-900 break-all" x-text="secret"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Enter 6-digit code') }}</label>
                                <input type="text" x-model="verificationCode" maxlength="6" pattern="[0-9]*" inputmode="numeric" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all text-center text-xl font-mono tracking-widest" placeholder="000000">
                            </div>
                            <p x-show="error2FA" x-text="error2FA" class="text-sm text-red-600 mt-2"></p>
                        </div>
                        <!-- Step 2: Recovery Codes -->
                        <div x-show="showRecoveryCodes">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-emerald-700 font-semibold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('2FA Enabled!') }}
                                </p>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">{{ __('Save these recovery codes securely:') }}</p>
                            <div class="bg-slate-800 rounded-lg p-3 mb-3">
                                <div class="grid grid-cols-2 gap-1.5">
                                    <template x-for="code in recoveryCodes" :key="code">
                                        <code class="text-sm text-emerald-400 font-mono" x-text="code"></code>
                                    </template>
                                </div>
                            </div>
                            <button type="button" @click="copyRecoveryCodes()" class="w-full px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Copy Codes') }}
                            </button>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="showEnable2FAModal = false; if(showRecoveryCodes) location.reload();" class="px-4 py-2 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors">
                            {{ __('Close') }}
                        </button>
                        <button x-show="!showRecoveryCodes" type="button" @click="confirmTwoFactor()" :disabled="verificationCode.length !== 6 || confirming2FA" class="px-4 py-2 bg-violet-500 text-white font-medium rounded-lg hover:bg-violet-600 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <template x-if="confirming2FA">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            {{ __('Verify & Enable') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Disable 2FA Modal -->
            <div x-show="showDisable2FAModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" @keydown.escape.window="showDisable2FAModal = false">
                <!-- Simple Dark Overlay -->
                <div x-show="showDisable2FAModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 z-10 bg-slate-900/50" @click="showDisable2FAModal = false"></div>
                <!-- Modal Dialog -->
                <div x-show="showDisable2FAModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-20 bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden" @click.stop>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-red-500 to-rose-500">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            {{ __('Disable 2FA') }}
                        </h3>
                        <button type="button" @click="showDisable2FAModal = false" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="px-6 py-5 space-y-4">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-sm text-amber-700">{{ __('This will make your account less secure.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Confirm with password') }}</label>
                            <input type="password" x-model="disablePassword" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all" placeholder="{{ __('Your password') }}">
                        </div>
                        <p x-show="errorDisable2FA" x-text="errorDisable2FA" class="text-sm text-red-600"></p>
                    </div>
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="showDisable2FAModal = false" class="px-4 py-2 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" @click="disableTwoFactor()" :disabled="!disablePassword || disabling2FA" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <template x-if="disabling2FA">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            {{ __('Disable 2FA') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recovery Codes Modal -->
            <div x-show="showRecoveryCodesModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true" @keydown.escape.window="showRecoveryCodesModal = false">
                <!-- Simple Dark Overlay -->
                <div x-show="showRecoveryCodesModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 z-10 bg-slate-900/50" @click="showRecoveryCodesModal = false; viewingRecoveryCodes = false; recoveryPassword = ''; viewedRecoveryCodes = [];"></div>
                <!-- Modal Dialog -->
                <div x-show="showRecoveryCodesModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-20 bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden" @click.stop>
                    <!-- Header with close button -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-violet-500 to-purple-500">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            {{ __('Recovery Codes') }}
                        </h3>
                        <button type="button" @click="showRecoveryCodesModal = false; viewingRecoveryCodes = false; recoveryPassword = ''; viewedRecoveryCodes = [];" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="px-6 py-5 space-y-4">
                        <div x-show="!viewingRecoveryCodes">
                            <p class="text-sm text-slate-600 mb-3">{{ __('Enter your password to view recovery codes.') }}</p>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('Password') }}</label>
                                <input type="password" x-model="recoveryPassword" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 transition-all" placeholder="{{ __('Your password') }}">
                            </div>
                            <p x-show="errorRecovery" x-text="errorRecovery" class="text-sm text-red-600 mt-2"></p>
                        </div>
                        <div x-show="viewingRecoveryCodes">
                            <p class="text-sm text-slate-600 mb-3">{{ __('Store these codes securely:') }}</p>
                            <div class="bg-slate-800 rounded-lg p-3 mb-3">
                                <div class="grid grid-cols-2 gap-1.5">
                                    <template x-for="code in viewedRecoveryCodes" :key="code">
                                        <code class="text-sm text-emerald-400 font-mono" x-text="code"></code>
                                    </template>
                                </div>
                            </div>
                            <button type="button" @click="copyViewedRecoveryCodes()" class="w-full px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors flex items-center justify-center gap-2 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Copy Codes') }}
                            </button>
                            <button type="button" @click="regenerateRecoveryCodes()" :disabled="regenerating" class="w-full px-4 py-2 bg-amber-100 text-amber-700 font-medium rounded-lg hover:bg-amber-200 transition-colors flex items-center justify-center gap-2">
                                <template x-if="regenerating">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <template x-if="!regenerating">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </template>
                                {{ __('Regenerate') }}
                            </button>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="showRecoveryCodesModal = false; viewingRecoveryCodes = false; recoveryPassword = ''; viewedRecoveryCodes = [];" class="px-4 py-2 text-slate-600 font-medium rounded-lg hover:bg-slate-100 transition-colors">
                            {{ __('Close') }}
                        </button>
                        <button x-show="!viewingRecoveryCodes" type="button" @click="viewRecoveryCodes()" :disabled="!recoveryPassword || loadingRecovery" class="px-4 py-2 bg-violet-500 text-white font-medium rounded-lg hover:bg-violet-600 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <template x-if="loadingRecovery">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            {{ __('View Codes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File input display
    document.getElementById('cv_upload')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const label = this.parentElement.querySelector('label[for="cv_upload"]');
            label.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> ${fileName}`;
        }
    });

    // Security Settings Alpine.js Component
    function securitySettings() {
        return {
            // Password Modal
            showPasswordModal: false,
            changingPassword: false,
            passwordSuccess: false,
            passwordError: '',
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',

            // Change Password with AJAX
            async changePassword() {
                this.changingPassword = true;
                this.passwordError = '';
                this.passwordSuccess = false;

                try {
                    const response = await fetch('{{ route("security.password.change") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: this.currentPassword,
                            password: this.newPassword,
                            password_confirmation: this.confirmPassword
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.passwordSuccess = true;
                        this.currentPassword = '';
                        this.newPassword = '';
                        this.confirmPassword = '';
                        // Auto close modal after 2 seconds
                        setTimeout(() => {
                            this.showPasswordModal = false;
                            this.passwordSuccess = false;
                            // Reload to update "Last changed" text
                            location.reload();
                        }, 2000);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat();
                            this.passwordError = errorMessages.join(' ');
                        } else if (data.message) {
                            this.passwordError = data.message;
                        } else {
                            this.passwordError = '{{ __("Failed to change password. Please try again.") }}';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.passwordError = '{{ __("An error occurred. Please try again.") }}';
                } finally {
                    this.changingPassword = false;
                }
            },

            // Reset password form
            resetPasswordForm() {
                this.currentPassword = '';
                this.newPassword = '';
                this.confirmPassword = '';
                this.passwordError = '';
                this.passwordSuccess = false;
            },

            // 2FA Enable Modal
            showEnable2FAModal: false,
            loading2FA: false,
            confirming2FA: false,
            qrCode: '',
            secret: '',
            verificationCode: '',
            error2FA: '',
            showRecoveryCodes: false,
            recoveryCodes: [],

            // 2FA Disable Modal
            showDisable2FAModal: false,
            disabling2FA: false,
            disablePassword: '',
            errorDisable2FA: '',

            // Recovery Codes Modal
            showRecoveryCodesModal: false,
            viewingRecoveryCodes: false,
            loadingRecovery: false,
            regenerating: false,
            recoveryPassword: '',
            viewedRecoveryCodes: [],
            errorRecovery: '',

            // Enable 2FA - Step 1
            async enableTwoFactor() {
                this.loading2FA = true;
                this.error2FA = '';

                try {
                    const response = await fetch('{{ route("security.2fa.enable") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.qrCode = data.qr_code;
                        this.secret = data.secret;
                        this.showEnable2FAModal = true;
                    } else {
                        alert(data.message || '{{ __("Failed to enable 2FA") }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __("An error occurred. Please try again.") }}');
                } finally {
                    this.loading2FA = false;
                }
            },

            // Enable 2FA - Step 2: Confirm
            async confirmTwoFactor() {
                this.confirming2FA = true;
                this.error2FA = '';

                try {
                    const response = await fetch('{{ route("security.2fa.confirm") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            code: this.verificationCode
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.recoveryCodes = data.recovery_codes;
                        this.showRecoveryCodes = true;
                    } else {
                        this.error2FA = data.message || '{{ __("Invalid code") }}';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.error2FA = '{{ __("An error occurred. Please try again.") }}';
                } finally {
                    this.confirming2FA = false;
                }
            },

            // Disable 2FA
            async disableTwoFactor() {
                this.disabling2FA = true;
                this.errorDisable2FA = '';

                try {
                    const response = await fetch('{{ route("security.2fa.disable") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: this.disablePassword
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        location.reload();
                    } else {
                        this.errorDisable2FA = data.message || '{{ __("Failed to disable 2FA") }}';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.errorDisable2FA = '{{ __("An error occurred. Please try again.") }}';
                } finally {
                    this.disabling2FA = false;
                }
            },

            // View Recovery Codes
            async viewRecoveryCodes() {
                this.loadingRecovery = true;
                this.errorRecovery = '';

                try {
                    const response = await fetch('{{ route("security.2fa.recovery-codes") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: this.recoveryPassword
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.viewedRecoveryCodes = data.recovery_codes;
                        this.viewingRecoveryCodes = true;
                    } else {
                        this.errorRecovery = data.message || '{{ __("Failed to retrieve codes") }}';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.errorRecovery = '{{ __("An error occurred. Please try again.") }}';
                } finally {
                    this.loadingRecovery = false;
                }
            },

            // Regenerate Recovery Codes
            async regenerateRecoveryCodes() {
                if (!confirm('{{ __("This will invalidate your existing recovery codes. Continue?") }}')) {
                    return;
                }

                this.regenerating = true;

                try {
                    const response = await fetch('{{ route("security.2fa.recovery-codes") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: this.recoveryPassword
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.viewedRecoveryCodes = data.recovery_codes;
                        alert('{{ __("Recovery codes regenerated successfully!") }}');
                    } else {
                        alert(data.message || '{{ __("Failed to regenerate codes") }}');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('{{ __("An error occurred. Please try again.") }}');
                } finally {
                    this.regenerating = false;
                }
            },

            // Copy Recovery Codes
            copyRecoveryCodes() {
                const codes = this.recoveryCodes.join('\n');
                navigator.clipboard.writeText(codes).then(() => {
                    alert('{{ __("Recovery codes copied to clipboard!") }}');
                });
            },

            // Copy Viewed Recovery Codes
            copyViewedRecoveryCodes() {
                const codes = this.viewedRecoveryCodes.join('\n');
                navigator.clipboard.writeText(codes).then(() => {
                    alert('{{ __("Recovery codes copied to clipboard!") }}');
                });
            }
        }
    }
</script>
@endpush
