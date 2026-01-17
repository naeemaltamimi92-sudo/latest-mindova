@extends('layouts.app')

@section('title', $volunteer->user->name . ' - Contributor Details')

@push('styles')
<style>
    /* Premium Animations */
    .slide-up { animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-delay-1 { animation-delay: 0.1s; }
    .slide-up-delay-2 { animation-delay: 0.2s; }
    .slide-up-delay-3 { animation-delay: 0.3s; }
    .slide-up-delay-4 { animation-delay: 0.4s; }
    .slide-up-delay-5 { animation-delay: 0.5s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .float-anim { animation: floatAnim 8s-out infinite; }
    .float-anim-delayed { animation: floatAnim 8s-out infinite; animation-delay: 3s; }
    .float-anim-slow { animation: floatAnim 12s-out infinite; animation-delay: 1.5s; }

    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
        33% { transform: translateY(-15px) rotate(2deg) scale(1.02); }
        66% { transform: translateY(-8px) rotate(-1deg) scale(0.98); }
    }

    .pulse-glow { animation: pulseGlow 3s-out infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px var(--shadow-color-primary-light); }
        50% { box-shadow: 0 0 40px var(--shadow-color-primary), 0 0 60px var(--shadow-color-secondary-light); }
    }

    .shine-effect {
        position: relative;
        overflow: hidden;
    }
    .shine-effect::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shine 3s-out infinite;
    }
    @keyframes shine {
        0%, 100% { left: -100%; }
        50% { left: 100%; }
    }

    .card-3d {
        transform-style: preserve-3d;
        perspective: 1000px;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .card-3d:hover {
        transform: translateY(-8px) rotateX(2deg);
    }

    .stat-counter {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        backdrop-filter: blur(10px);
    }

    .gradient-border {
        position: relative;
        background: white;
        border-radius: 1.5rem;
    }
    .gradient-border::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary), var(--color-secondary-400), var(--color-primary));
        border-radius: calc(1.5rem + 2px);
        z-index: -1;
        background-size: 200% 200%;
        animation: gradientShift 4s ease infinite;
    }
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .badge-glow {
        animation: badgeGlow 2s-out infinite alternate;
    }
    @keyframes badgeGlow {
        from { filter: drop-shadow(0 0 8px var(--shadow-color-warning-light)); }
        to { filter: drop-shadow(0 0 16px var(--shadow-color-warning)); }
    }

    .ripple-effect {
        position: relative;
        overflow: hidden;
    }
    .ripple-effect::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .ripple-effect:hover::after {
        width: 300px;
        height: 300px;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring__circle {
        transition: stroke-dashoffset 1s-out;
        transform-origin: center;
    }

    .skill-bar {
        height: 8px;
        border-radius: 4px;
        background: var(--gradient-vibrant);
        position: relative;
        overflow: hidden;
    }
    .skill-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: skillShine 2s-out infinite;
    }
    @keyframes skillShine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .timeline-connector {
        background: var(--gradient-primary-vertical);
    }

    .certificate-card {
        background: linear-gradient(135deg, var(--color-amber) 0%, var(--color-warning) 50%, var(--color-warning-dark) 100%);
        position: relative;
        overflow: hidden;
    }
    .certificate-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255,255,255,0.1) 10px,
            rgba(255,255,255,0.1) 20px
        );
        animation: certificatePattern 20s linear infinite;
    }
    @keyframes certificatePattern {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .avatar-ring {
        animation: avatarPulse 3s-out infinite;
    }
    @keyframes avatarPulse {
        0%, 100% { box-shadow: 0 0 0 0 var(--shadow-color-primary), 0 0 0 0 var(--shadow-color-secondary-light); }
        50% { box-shadow: 0 0 0 15px transparent, 0 0 0 30px transparent; }
    }

    .hover-lift {
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }
    .hover-lift:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .text-gradient {
        background: var(--gradient-vibrant);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .neon-border {
        box-shadow: 0 0 5px theme('colors.indigo.400'),
                    0 0 10px theme('colors.indigo.400'),
                    0 0 20px theme('colors.indigo.400'),
                    0 0 40px theme('colors.indigo.400');
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-primary-500 slide-up">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <!-- Gradient Orbs -->
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="float-anim absolute top-10 left-[10%] w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <div class="float-anim-delayed absolute top-40 right-[15%] w-80 h-80 bg-violet-500/25 rounded-full blur-3xl"></div>
                <div class="float-anim-slow absolute bottom-20 left-[30%] w-72 h-72 bg-purple-500/20 rounded-full blur-3xl"></div>
                <div class="float-anim absolute bottom-10 right-[5%] w-64 h-64 bg-blue-500/15 rounded-full blur-3xl"></div>
            </div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 opacity-[0.03]">
                <svg class="w-full h-full">
                    <pattern id="hero-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#hero-grid)"/>
                </svg>
            </div>

            <!-- Radial Gradients -->
            <div class="absolute top-0 left-1/4 w-full h-full "></div>
            <div class="absolute bottom-0 right-1/4 w-full h-full "></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Back Navigation -->
            <a href="{{ route('admin.volunteers.index') }}" class="inline-flex items-center gap-2 text-white/60 hover:text-white font-medium mb-8 group">
                <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/20">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </div>
                <span>{{ __('Back to Contributors') }}</span>
            </a>

            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-8">
                <!-- Profile Avatar with Animation -->
                <div class="relative group">
                    <div class="absolute -inset-1.5 bg-secondary-500 rounded-3xl blur opacity-50 group-hover:opacity-75"></div>
                    @if($volunteer->profile_picture)
                    <img src="{{ asset('storage/' . $volunteer->profile_picture) }}"
                         alt="{{ $volunteer->user->name }}"
                         class="relative h-32 w-32 lg:h-40 lg:w-40 rounded-2xl object-cover ring-4 ring-white/20 avatar-ring">
                    @else
                    <div class="relative h-32 w-32 lg:h-40 lg:w-40 rounded-2xl bg-secondary-500 flex items-center justify-center ring-4 ring-white/20 avatar-ring shine-effect">
                        <span class="text-white font-black text-5xl lg:text-6xl">{{ substr($volunteer->user->name, 0, 1) }}</span>
                    </div>
                    @endif

                    <!-- Online Status Indicator -->
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center ring-4 ring-slate-900 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <!-- Reputation Level Badge -->
                        @php
                            $level = 'Newcomer';
                            $levelBg = 'bg-slate-500/20 border-slate-400/30';
                            if($stats['reputation_score'] >= 1000) {
                                $level = 'Legend';
                                $levelBg = 'bg-amber-500/20 border-amber-400/30';
                            } elseif($stats['reputation_score'] >= 500) {
                                $level = 'Expert';
                                $levelBg = 'bg-violet-500/20 border-violet-400/30';
                            } elseif($stats['reputation_score'] >= 200) {
                                $level = 'Advanced';
                                $levelBg = 'bg-blue-500/20 border-blue-400/30';
                            } elseif($stats['reputation_score'] >= 50) {
                                $level = 'Intermediate';
                                $levelBg = 'bg-emerald-500/20 border-emerald-400/30';
                            }
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 {{ $levelBg }} backdrop-blur-md border rounded-full text-sm font-bold text-white badge-glow">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ $level }}
                        </span>

                        @if($volunteer->field)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 rounded-full text-sm font-semibold text-emerald-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $volunteer->field }}
                        </span>
                        @endif

                        @if($volunteer->experience_level)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500/20 backdrop-blur-md border border-indigo-400/30 rounded-full text-sm font-semibold text-indigo-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            {{ ucfirst($volunteer->experience_level) }} {{ __('Level') }}
                        </span>
                        @endif

                        @if($volunteer->general_nda_signed)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500/20 backdrop-blur-md border border-teal-400/30 rounded-full text-sm font-semibold text-teal-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('NDA Verified') }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-black text-white mb-3 tracking-tight">
                        {{ $volunteer->user->name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-white/60 text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $volunteer->user->email }}
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Member since') }} {{ $volunteer->created_at->format('M Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            {{ __('ID:') }} #{{ $volunteer->id }}
                        </span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col gap-3">
                    <x-ui.button onclick="openEmailModal()" variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('Send Email') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section - Overlapping Hero -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 slide-up slide-up-delay-1">
            <!-- Reputation Score -->
            <div class="group bg-white rounded-2xl shadow-xl border border-slate-100 p-6 hover-lift overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary-300/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-secondary-300 flex items-center justify-center shadow-lg shadow-amber-500/30 shine-effect">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded-lg">{{ $level }}</span>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-slate-900 mb-1">{{ number_format($stats['reputation_score']) }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ __('Reputation Score') }}</p>
                    <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary-300 rounded-full skill-bar" style="width: {{ min(($stats['reputation_score'] / 1000) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Total Assignments -->
            <div class="group bg-white rounded-2xl shadow-xl border border-slate-100 p-6 hover-lift overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-400/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary-400 flex items-center justify-center shadow-lg shadow-blue-500/30 shine-effect">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-slate-900 mb-1">{{ $stats['total_assignments'] }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ __('Total Assignments') }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Active') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks -->
            <div class="group bg-white rounded-2xl shadow-xl border border-slate-100 p-6 hover-lift overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary-500/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-secondary-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 shine-effect">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @if($stats['total_assignments'] > 0)
                        <div class="text-right">
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-lg">{{ round(($stats['completed_tasks'] / max($stats['total_assignments'], 1)) * 100) }}%</span>
                        </div>
                        @endif
                    </div>
                    <p class="text-4xl font-black text-slate-900 mb-1">{{ $stats['completed_tasks'] }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ __('Completed Tasks') }}</p>
                    <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary-500 rounded-full skill-bar" style="width: {{ ($stats['total_assignments'] > 0) ? round(($stats['completed_tasks'] / $stats['total_assignments']) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Certificates -->
            <div class="group bg-white rounded-2xl shadow-xl border border-slate-100 p-6 hover-lift overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary-200/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-secondary-200 flex items-center justify-center shadow-lg shadow-yellow-500/30 shine-effect">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-4xl font-black text-slate-900 mb-1">{{ $stats['certificates_earned'] }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ __('Certificates Earned') }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        @if($stats['certificates_earned'] > 0)
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ __('Certified') }}
                        </span>
                        @else
                        <span class="text-xs text-slate-400">{{ __('No certificates yet') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Information Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden hover-lift slide-up slide-up-delay-2">
                    <div class="relative bg-primary-500 px-6 py-5">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">{{ __('Profile Information') }}</h2>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Full Name') }}</p>
                            <p class="text-base font-bold text-slate-900 group-hover:text-indigo-600">{{ $volunteer->user->name }}</p>
                        </div>
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Email Address') }}</p>
                            <a href="mailto:{{ $volunteer->user->email }}" class="text-base font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                                {{ $volunteer->user->email }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Field of Expertise') }}</p>
                            <p class="text-base font-bold text-slate-900">{{ $volunteer->field ?? __('Not specified') }}</p>
                        </div>
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Experience Level') }}</p>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ ucfirst($volunteer->experience_level ?? __('Not specified')) }}
                            </span>
                        </div>
                        <div class="pt-5 border-t border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Member Since') }}</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-slate-900">{{ $volunteer->created_at->format('F d, Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $volunteer->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NDA Status Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden hover-lift slide-up slide-up-delay-3">
                    <div class="relative bg-secondary-500 px-6 py-5">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">{{ __('NDA Status') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($volunteer->general_nda_signed)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-emerald-200">
                            <div class="w-14 h-14 rounded-2xl bg-secondary-500 flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-emerald-800 text-lg">{{ __('General NDA Signed') }}</p>
                                <p class="text-sm text-emerald-600 mt-1">{{ $volunteer->general_nda_signed_at?->format('M d, Y \a\t g:i A') ?? __('Date not recorded') }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-red-200">
                            <div class="w-14 h-14 rounded-2xl bg-secondary-700 flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-red-800 text-lg">{{ __('NDA Not Signed') }}</p>
                                <p class="text-sm text-red-600 mt-1">{{ __('Pending signature') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="bg-primary-500 rounded-3xl shadow-xl p-6 text-white hover-lift slide-up slide-up-delay-4">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Activity Summary') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">{{ __('Tasks This Month') }}</span>
                            <span class="font-bold text-xl text-white">{{ $volunteer->taskAssignments()->whereMonth('created_at', now()->month)->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">{{ __('Completion Rate') }}</span>
                            <span class="font-bold text-xl text-emerald-400">{{ $stats['total_assignments'] > 0 ? round(($stats['completed_tasks'] / $stats['total_assignments']) * 100) : 0 }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">{{ __('Average Match Score') }}</span>
                            <span class="font-bold text-xl text-indigo-400">{{ round($volunteer->taskAssignments()->whereNotNull('ai_match_score')->avg('ai_match_score') ?? 0) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Task Assignments -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden slide-up slide-up-delay-2">
                    <div class="relative bg-primary-500 px-8 py-6">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">{{ __('Task Assignments') }}</h2>
                                    <p class="text-white/70 text-sm">{{ __('All tasks assigned to this contributor') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="bg-white/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-xl">
                                    {{ $volunteer->taskAssignments->count() }} {{ __('Total') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($volunteer->taskAssignments as $index => $assignment)
                        <div class="p-6 hover:bg-gray-50 group">
                            <div class="flex items-start gap-4">
                                <!-- Timeline Indicator -->
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg
                                        @if($assignment->completed_at) bg-secondary-500
                                        @elseif($assignment->invitation_status === 'accepted') bg-primary-400
                                        @elseif($assignment->invitation_status === 'pending') bg-secondary-300
                                        @else bg-gray-400
                                        @endif">
                                        @if($assignment->completed_at)
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @else
                                        <span class="text-white font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Task Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <a href="{{ route('admin.challenges.show', $assignment->task->challenge) }}" class="text-lg font-bold text-slate-900 hover:text-blue-600 line-clamp-1 group-hover:text-blue-600">
                                                {{ $assignment->task->title }}
                                            </a>
                                            <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ Str::limit($assignment->task->challenge->title, 50) }}
                                            </p>

                                            <div class="flex flex-wrap items-center gap-3 mt-4">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold
                                                    @if($assignment->completed_at) bg-emerald-100 text-emerald-700 border border-emerald-200
                                                    @elseif($assignment->invitation_status === 'accepted') bg-blue-100 text-blue-700 border border-blue-200
                                                    @elseif($assignment->invitation_status === 'pending') bg-amber-100 text-amber-700 border border-amber-200
                                                    @else bg-slate-100 text-slate-700 border border-slate-200
                                                    @endif">
                                                    @if($assignment->completed_at)
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ __('Completed') }}
                                                    @else
                                                    {{ ucfirst($assignment->invitation_status) }}
                                                    @endif
                                                </span>

                                                @if($assignment->ai_match_score)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                                    </svg>
                                                    {{ number_format($assignment->ai_match_score, 0) }}% {{ __('Match') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($assignment->completed_at)
                                        <div class="flex-shrink-0">
                                            <div class="bg-emerald-100 px-4 py-3 rounded-2xl border border-emerald-200">
                                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">{{ __('Completed') }}</p>
                                                <p class="text-sm font-bold text-emerald-800">{{ $assignment->completed_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No task assignments yet') }}</h3>
                            <p class="text-slate-500 max-w-sm mx-auto">{{ __("This contributor hasn't been assigned to any tasks yet.") }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Certificates Section -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden slide-up slide-up-delay-3">
                    <div class="relative bg-secondary-300 px-8 py-6">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">{{ __('Certificates Earned') }}</h2>
                                    <p class="text-white/70 text-sm">{{ __('Recognition of achievements') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="bg-white/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-xl">
                                    {{ $volunteer->certificates->count() }} {{ __('Total') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($volunteer->certificates->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($volunteer->certificates as $certificate)
                            <div class="group relative overflow-hidden rounded-2xl certificate-card p-6 text-white hover-lift">
                                <div class="relative z-10">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </div>
                                        <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-lg">
                                            {{ ucfirst($certificate->certificate_type) }}
                                        </span>
                                    </div>

                                    <h3 class="text-lg font-bold mb-2 line-clamp-2">{{ $certificate->challenge->title }}</h3>
                                    <p class="text-white/80 text-sm mb-4">{{ __('Certificate') }} #{{ $certificate->certificate_number }}</p>

                                    <div class="flex items-center justify-between pt-4 border-t border-white/20">
                                        <div>
                                            <p class="text-xs text-white/60 uppercase tracking-wider">{{ __('Issued') }}</p>
                                            <p class="text-sm font-bold">{{ $certificate->issued_at ? $certificate->issued_at->format('M d, Y') : $certificate->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <button class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No certificates yet') }}</h3>
                            <p class="text-slate-500 max-w-sm mx-auto">{{ __("This contributor hasn't earned any certificates yet.") }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div id="emailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" onclick="closeEmailModal()"></div>

    <!-- Modal Content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-xl w-full mx-auto overflow-hidden transform">
            <!-- Modal Header -->
            <div class="relative bg-primary-500 px-8 py-6">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">{{ __('Send Email') }}</h2>
                            <p class="text-white/70 text-sm">{{ __('To:') }} {{ $volunteer->user->name }}</p>
                        </div>
                    </div>
                    <button onclick="closeEmailModal()" class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form id="emailForm" action="{{ route('admin.volunteers.send-email', $volunteer) }}" method="POST" class="p-8">
                @csrf

                <!-- Success/Error Messages -->
                <div id="emailSuccessMessage" class="hidden mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-emerald-800">{{ __('Email Sent Successfully!') }}</p>
                            <p class="text-sm text-emerald-600">{{ __('Your email has been delivered.') }}</p>
                        </div>
                    </div>
                </div>

                <div id="emailErrorMessage" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-red-800">{{ __('Failed to Send Email') }}</p>
                            <p class="text-sm text-red-600" id="emailErrorText">{{ __('Please try again later.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recipient Info -->
                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="flex items-center gap-4">
                        @if($volunteer->profile_picture)
                        <img src="{{ asset('storage/' . $volunteer->profile_picture) }}" alt="{{ $volunteer->user->name }}" class="w-12 h-12 rounded-xl object-cover">
                        @else
                        <div class="w-12 h-12 rounded-xl bg-primary-500 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ substr($volunteer->user->name, 0, 1) }}</span>
                        </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-900">{{ $volunteer->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $volunteer->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label for="email_subject" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Subject') }}</label>
                    <input type="text"
                           name="subject"
                           id="email_subject"
                           required
                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-slate-900 font-medium"
                           placeholder="{{ __('Enter email subject...') }}">
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="email_message" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Message') }}</label>
                    <textarea name="message"
                              id="email_message"
                              rows="6"
                              required
                              class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 text-slate-900 resize-none"
                              placeholder="{{ __('Write your message here...') }}"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4">
                    <x-ui.button onclick="closeEmailModal()" variant="secondary">
                        {{ __('Cancel') }}
                    </x-ui.button>
                    <x-ui.button as="submit" id="sendEmailBtn" variant="primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span id="sendEmailBtnText">{{ __('Send Email') }}</span>
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEmailModal() {
        document.getElementById('emailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        // Reset form
        document.getElementById('emailForm').reset();
        document.getElementById('emailSuccessMessage').classList.add('hidden');
        document.getElementById('emailErrorMessage').classList.add('hidden');
    }

    function closeEmailModal() {
        document.getElementById('emailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Handle form submission via AJAX
    document.getElementById('emailForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const sendBtn = document.getElementById('sendEmailBtn');
        const btnText = document.getElementById('sendEmailBtnText');
        const successMsg = document.getElementById('emailSuccessMessage');
        const errorMsg = document.getElementById('emailErrorMessage');
        const errorText = document.getElementById('emailErrorText');

        // Hide messages
        successMsg.classList.add('hidden');
        errorMsg.classList.add('hidden');

        // Disable button and show loading
        sendBtn.disabled = true;
        btnText.textContent = '{{ __("Sending...") }}';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                successMsg.classList.remove('hidden');
                form.reset();
                // Close modal after 2 seconds
                setTimeout(() => {
                    closeEmailModal();
                }, 2000);
            } else {
                errorText.textContent = data.message || '{{ __("Please try again later.") }}';
                errorMsg.classList.remove('hidden');
            }
        } catch (error) {
            errorText.textContent = '{{ __("Network error. Please try again.") }}';
            errorMsg.classList.remove('hidden');
        } finally {
            // Re-enable button
            sendBtn.disabled = false;
            btnText.textContent = '{{ __("Send Email") }}';
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmailModal();
        }
    });
</script>
@endpush
