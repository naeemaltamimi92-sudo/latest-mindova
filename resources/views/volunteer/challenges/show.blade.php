@extends('layouts.app')

@section('title', $challenge->title . ' - ' . __('My Challenges'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.5s ease-out forwards; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .toast { animation: toastIn 0.3s ease-out forwards; }
    @keyframes toastIn {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    .timeline-dot { transition: all 0.3s ease; }
    .timeline-dot.active { transform: scale(1.2); box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.2); }
    .info-card { transition: all 0.3s ease; }
    .info-card:hover { transform: translateY(-2px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); }
    .pulse-ring { animation: pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite; }
    @keyframes pulseRing {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-purple-50/30">
    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100] space-y-2"></div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-6 slide-up">
            <a href="{{ route('volunteer.challenges.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-purple-600 transition-colors group">
                <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">{{ __('Back to My Challenges') }}</span>
            </a>
        </div>

        <!-- Hero Header -->
        <div class="mb-8 slide-up" style="animation-delay: 0.1s">
            <div class="bg-white rounded-3xl shadow-xl shadow-purple-500/5 border border-slate-200/60 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-pink-500 px-8 py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h1 class="text-2xl lg:text-3xl font-black text-white truncate">{{ $challenge->title }}</h1>
                                @if($challenge->score)
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white font-bold rounded-lg text-sm">
                                    {{ $challenge->score }}/10
                                </span>
                                @endif
                            </div>
                            <p class="text-purple-100 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Submitted') }} {{ $challenge->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if(!in_array($challenge->status, ['completed', 'delivered']))
                            <button type="button" onclick="openEditModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur-sm text-white rounded-xl hover:bg-white/20 transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ __('Edit') }}
                            </button>
                            <button type="button" onclick="openDeleteModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/20 backdrop-blur-sm text-white rounded-xl hover:bg-red-500/30 transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ __('Delete') }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="px-8 py-5 bg-slate-50/50">
                    @php
                        $stages = [
                            ['key' => 'submitted', 'label' => __('Submitted'), 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                            ['key' => 'analyzing', 'label' => __('AI Analysis'), 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                            ['key' => 'active', 'label' => __('Active'), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            ['key' => 'completed', 'label' => __('Completed'), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ];
                        $currentIndex = match(true) {
                            $challenge->status === 'completed' => 3,
                            $challenge->status === 'active' || $challenge->status === 'in_progress' => 2,
                            in_array($challenge->ai_analysis_status, ['pending', 'processing']) || $challenge->status === 'analyzing' => 1,
                            default => 0
                        };
                        if ($challenge->status === 'rejected') $currentIndex = -1;
                    @endphp
                    <div class="flex items-center justify-between">
                        @foreach($stages as $index => $stage)
                        <div class="flex items-center {{ $index < count($stages) - 1 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="timeline-dot w-10 h-10 rounded-full flex items-center justify-center
                                    {{ $index <= $currentIndex ? 'bg-purple-500 text-white' : 'bg-slate-200 text-slate-400' }}
                                    {{ $index === $currentIndex ? 'active' : '' }}">
                                    @if($index === $currentIndex && in_array($challenge->ai_analysis_status, ['pending', 'processing']))
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stage['icon'] }}"/>
                                    </svg>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-medium {{ $index <= $currentIndex ? 'text-purple-600' : 'text-slate-400' }}">{{ $stage['label'] }}</span>
                            </div>
                            @if($index < count($stages) - 1)
                            <div class="flex-1 h-1 mx-3 rounded-full {{ $index < $currentIndex ? 'bg-purple-500' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if($challenge->status === 'rejected' && $challenge->rejection_reason)
        <div class="mb-6 slide-up" style="animation-delay: 0.15s">
            <div class="bg-red-50 border border-red-200 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-500 to-pink-600 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-white">{{ __('Challenge Rejected') }}</h2>
                </div>
                <div class="p-6">
                    <p class="text-red-800 leading-relaxed mb-4">{{ $challenge->rejection_reason }}</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-red-200">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-red-600">{{ __('Please edit your challenge to provide clearer, more detailed information and resubmit.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @php
            $latestAnalysis = $challenge->challengeAnalyses()->latest()->first();
            $needsVerification = $latestAnalysis && $latestAnalysis->requires_human_review;
        @endphp
        @if($needsVerification && $challenge->status !== 'rejected')
        <div class="mb-6 slide-up" style="animation-delay: 0.15s">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-amber-900 mb-1">{{ __('Pending Verification') }}</h3>
                    <p class="text-amber-700">{{ __('Your challenge is being reviewed by our team. This typically takes 24-48 hours.') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(in_array($challenge->ai_analysis_status, ['pending', 'processing']))
        <div class="mb-6 slide-up" style="animation-delay: 0.15s">
            <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 relative">
                    <div class="absolute inset-0 bg-indigo-200 rounded-xl pulse-ring"></div>
                    <svg class="w-6 h-6 text-indigo-600 animate-spin relative z-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-indigo-900 mb-1">{{ __('AI Analysis in Progress') }}</h3>
                    <p class="text-indigo-700">{{ __('Your challenge is being analyzed. This usually takes 1-2 minutes.') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Original Description -->
                <div class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.2s">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-700 to-slate-800 flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-white">{{ __('Challenge Description') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            {!! nl2br(e($challenge->original_description)) !!}
                        </div>
                    </div>
                </div>

                <!-- AI Analysis Results -->
                @if($challenge->ai_analysis_status === 'completed' && $challenge->refined_brief)
                <div class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.25s">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-white">{{ __('AI Analysis Results') }}</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4">
                            <h3 class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-2">{{ __('Refined Brief') }}</h3>
                            <p class="text-slate-700 leading-relaxed">{{ $challenge->refined_brief }}</p>
                        </div>

                        @php $analysis = $challenge->challengeAnalyses()->where('stage', 'brief')->latest()->first(); @endphp

                        @if($analysis)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($analysis->objectives && count($analysis->objectives) > 0)
                            <div class="bg-emerald-50 rounded-xl p-4">
                                <h3 class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Objectives') }}
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($analysis->objectives as $objective)
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mt-1.5 flex-shrink-0"></span>
                                        {{ $objective }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($analysis->constraints && count($analysis->constraints) > 0)
                            <div class="bg-amber-50 rounded-xl p-4">
                                <h3 class="text-xs font-semibold text-amber-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ __('Constraints') }}
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($analysis->constraints as $constraint)
                                    <li class="flex items-start gap-2 text-sm text-slate-700">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full mt-1.5 flex-shrink-0"></span>
                                        {{ $constraint }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        @if($analysis->success_criteria && count($analysis->success_criteria) > 0)
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Success Criteria') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($analysis->success_criteria as $criterion)
                                <div class="flex items-start gap-2 text-sm text-slate-700 bg-white rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $criterion }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($analysis->confidence_score)
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-slate-600">{{ __('AI Confidence Level') }}</span>
                                <span class="text-sm font-bold {{ $analysis->confidence_score >= 70 ? 'text-emerald-600' : ($analysis->confidence_score >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ number_format($analysis->confidence_score, 0) }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500
                                    {{ $analysis->confidence_score >= 70 ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : ($analysis->confidence_score >= 40 ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-red-400 to-red-500') }}"
                                    style="width: {{ min($analysis->confidence_score, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @elseif($challenge->ai_analysis_status === 'failed')
                <div class="info-card bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden slide-up" style="animation-delay: 0.25s">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ __('Analysis Failed') }}</h3>
                        <p class="text-slate-500">{{ __('There was an issue analyzing your challenge. Our team has been notified.') }}</p>
                    </div>
                </div>
                @endif

                <!-- Attachments -->
                @if($challenge->attachments && $challenge->attachments->count() > 0)
                <div class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.3s">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ __('Attachments') }}
                        </h2>
                        <span class="text-sm text-slate-500">{{ $challenge->attachments->count() }} {{ __('files') }}</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($challenge->attachments as $attachment)
                            <a href="{{ route('challenges.attachments.download', [$challenge, $attachment]) }}" class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all group">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-700 truncate group-hover:text-purple-600 transition-colors">{{ $attachment->file_name }}</p>
                                    <p class="text-xs text-slate-400">{{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                </div>
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Community Solutions -->
                @if($challenge->challenge_type === 'community_discussion')
                <div id="solutions" class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.35s">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-between">
                        <h2 class="font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            {{ __('Community Solutions') }}
                        </h2>
                        <span class="bg-white/20 text-white text-sm font-bold px-3 py-1 rounded-lg">{{ $challenge->comments->count() }}</span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($challenge->comments->sortByDesc('ai_score') as $comment)
                        <div class="p-5 {{ $comment->ai_score >= 7 ? 'bg-gradient-to-r from-emerald-50/50 to-transparent' : '' }}">
                            <div class="flex items-start gap-4">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm">{{ substr($comment->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="font-bold text-slate-900">{{ $comment->user->name ?? 'Anonymous' }}</span>
                                        @if($comment->ai_score)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-bold
                                            {{ $comment->ai_score >= 7 ? 'bg-emerald-100 text-emerald-700' : ($comment->ai_score >= 4 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                            @if($comment->ai_score >= 7)
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            @endif
                                            {{ $comment->ai_score }}/10
                                        </span>
                                        @endif
                                        <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-slate-600 text-sm leading-relaxed">
                                        {!! nl2br(e($comment->content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-1">{{ __('No solutions yet') }}</h3>
                            <p class="text-slate-500 text-sm">{{ __('The community is reviewing your challenge.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Tasks & Progress -->
                @if($challenge->challenge_type === 'team_execution' && $challenge->workstreams && $challenge->workstreams->count() > 0)
                <div class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.35s">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-between">
                        <h2 class="font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            {{ __('Tasks & Progress') }}
                        </h2>
                    </div>
                    @foreach($challenge->workstreams as $workstream)
                    <div class="border-b border-slate-100 last:border-0">
                        <div class="px-6 py-3 bg-slate-50 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900">{{ $workstream->title }}</h3>
                            <span class="text-sm text-slate-500">{{ $workstream->tasks->count() }} {{ __('tasks') }}</span>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach($workstream->tasks as $task)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                        {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-600' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400') }}">
                                        @if($task->status === 'completed')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $task->title }}</p>
                                        @if($task->assignments && $task->assignments->count() > 0)
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ __('Assigned:') }}
                                            @foreach($task->assignments->take(2) as $assignment)
                                            <span class="text-purple-600">{{ $assignment->volunteer->user->name ?? 'Volunteer' }}</span>{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                            @if($task->assignments->count() > 2)
                                            <span class="text-slate-400">+{{ $task->assignments->count() - 2 }}</span>
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-xs font-bold
                                    {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="info-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.2s">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-700 to-slate-800">
                        <h2 class="font-bold text-white">{{ __('Challenge Info') }}</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-slate-500 text-sm">{{ __('Status') }}</span>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold
                                {{ $challenge->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($challenge->status === 'completed' ? 'bg-blue-100 text-blue-700' : ($challenge->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')) }}">
                                {{ ucfirst($challenge->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-slate-100">
                            <span class="text-slate-500 text-sm">{{ __('AI Analysis') }}</span>
                            <span class="text-sm font-medium
                                {{ $challenge->ai_analysis_status === 'completed' ? 'text-emerald-600' : ($challenge->ai_analysis_status === 'processing' ? 'text-amber-600' : 'text-slate-600') }}">
                                {{ ucfirst($challenge->ai_analysis_status) }}
                            </span>
                        </div>
                        @if($challenge->score)
                        <div class="flex items-center justify-between py-2 border-t border-slate-100">
                            <span class="text-slate-500 text-sm">{{ __('Score') }}</span>
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-bold rounded-lg">{{ $challenge->score }}/10</span>
                        </div>
                        @endif
                        @if($challenge->challenge_type)
                        <div class="flex items-center justify-between py-2 border-t border-slate-100">
                            <span class="text-slate-500 text-sm">{{ __('Type') }}</span>
                            <span class="text-sm font-medium text-indigo-600">
                                {{ $challenge->challenge_type === 'community_discussion' ? __('Community') : __('Team Execution') }}
                            </span>
                        </div>
                        @endif
                        @if($challenge->field)
                        <div class="flex items-center justify-between py-2 border-t border-slate-100">
                            <span class="text-slate-500 text-sm">{{ __('Field') }}</span>
                            <span class="text-sm font-medium text-slate-900">{{ $challenge->field }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- What's Next -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-5 slide-up" style="animation-delay: 0.25s">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-purple-900 mb-2">{{ __('What happens next?') }}</h3>
                            <ul class="text-sm text-purple-700 space-y-2">
                                @if($challenge->score && $challenge->score <= 2)
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Challenge is live for community discussion') }}</li>
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Members will provide solutions and feedback') }}</li>
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('You\'ll be notified of quality solutions') }}</li>
                                @elseif($challenge->score && $challenge->score >= 3)
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Tasks are assigned to volunteers') }}</li>
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Teams will work on solutions') }}</li>
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Track progress in Tasks section') }}</li>
                                @else
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('AI is analyzing your challenge') }}</li>
                                <li class="flex items-start gap-2"><span class="text-purple-400">•</span> {{ __('Score determines the next steps') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
@if(!in_array($challenge->status, ['completed', 'delivered']))
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40" onclick="closeEditModal()"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">{{ __('Edit Challenge') }}</h3>
                <button type="button" onclick="closeEditModal()" class="text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editChallengeForm" class="overflow-y-auto max-h-[calc(90vh-140px)]" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <div>
                        <label for="editTitle" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Title') }} *</label>
                        <input type="text" id="editTitle" name="title" value="{{ $challenge->title }}" required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    </div>

                    <div>
                        <label for="editDescription" class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Description') }} *</label>
                        <textarea id="editDescription" name="description" rows="5" required minlength="50"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none">{{ $challenge->original_description }}</textarea>
                        <p class="text-xs text-slate-500 mt-1">{{ __('Minimum 50 characters') }}</p>
                    </div>

                    @if($challenge->attachments && $challenge->attachments->count() > 0)
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Current Attachments') }}</label>
                        <div class="space-y-2" id="existingAttachments">
                            @foreach($challenge->attachments as $attachment)
                            <div class="flex items-center justify-between bg-slate-50 rounded-lg px-4 py-2" data-attachment-id="{{ $attachment->id }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm text-slate-700">{{ $attachment->file_name }}</span>
                                    <span class="text-xs text-slate-400">({{ number_format($attachment->file_size / 1024, 1) }} KB)</span>
                                </div>
                                <button type="button" onclick="markAttachmentForRemoval({{ $attachment->id }}, this)" class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="remove_attachments" id="removeAttachments" value="">
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Add Attachments') }}</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-purple-400 transition-colors">
                            <input type="file" name="attachments[]" id="editAttachments" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <label for="editAttachments" class="cursor-pointer">
                                <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mt-1 text-sm text-slate-600">{{ __('Click to upload') }}</p>
                            </label>
                        </div>
                        <div id="editFileList" class="mt-2 space-y-1"></div>
                    </div>

                    @if($challenge->score && $challenge->score >= 3)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-amber-800">{{ __('This challenge has assigned tasks. Editing will trigger re-analysis and notify volunteers.') }}</p>
                        </div>
                    </div>
                    @endif

                    <div id="editError" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"></div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-slate-600 hover:text-slate-900 font-medium">{{ __('Cancel') }}</button>
                    <button type="submit" id="editSubmitBtn" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Delete Modal -->
@if(!in_array($challenge->status, ['completed', 'delivered']))
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center" onclick="event.stopPropagation()">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Delete Challenge?') }}</h3>
            <p class="text-slate-500 mb-6">{{ __('This action cannot be undone. All data will be permanently removed.') }}</p>

            <form id="deleteChallengeForm">
                @csrf
                @method('DELETE')
                <div id="deleteError" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4"></div>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">{{ __('Cancel') }}</button>
                    <button type="submit" id="deleteSubmitBtn" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast flex items-center gap-3 px-5 py-4 rounded-xl shadow-lg ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'} text-white`;
    toast.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg><span class="font-medium">${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 4000);
}

let attachmentsToRemove = [];

function openEditModal() {
    document.getElementById('editModal')?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    attachmentsToRemove = [];
    const removeInput = document.getElementById('removeAttachments');
    if (removeInput) removeInput.value = '';
}

function closeEditModal() {
    document.getElementById('editModal')?.classList.add('hidden');
    document.body.style.overflow = '';
}

function markAttachmentForRemoval(id, btn) {
    const container = btn.closest('[data-attachment-id]');
    if (container) {
        container.style.opacity = '0.4';
        container.style.textDecoration = 'line-through';
        btn.disabled = true;
        attachmentsToRemove.push(id);
        document.getElementById('removeAttachments').value = attachmentsToRemove.join(',');
    }
}

document.getElementById('editAttachments')?.addEventListener('change', function(e) {
    const fileList = document.getElementById('editFileList');
    fileList.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 bg-purple-50 rounded-lg px-3 py-2';
        div.innerHTML = `<svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="text-sm text-purple-700 flex-1 truncate">${file.name}</span>`;
        fileList.appendChild(div);
    });
});

function openDeleteModal() {
    document.getElementById('deleteModal')?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal')?.classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('editChallengeForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('editSubmitBtn');
    const errorDiv = document.getElementById('editError');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
    errorDiv.classList.add('hidden');

    try {
        const response = await fetch('{{ route("volunteer.challenges.update", $challenge) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
            body: new FormData(this)
        });
        const data = await response.json();
        if (data.success) {
            showToast('{{ __("Challenge updated successfully!") }}', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            errorDiv.textContent = data.message || '{{ __("Failed to update") }}';
            errorDiv.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = '{{ __("Save Changes") }}';
        }
    } catch (error) {
        errorDiv.textContent = '{{ __("An error occurred") }}';
        errorDiv.classList.remove('hidden');
        btn.disabled = false;
        btn.textContent = '{{ __("Save Changes") }}';
    }
});

document.getElementById('deleteChallengeForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('deleteSubmitBtn');
    const errorDiv = document.getElementById('deleteError');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
    errorDiv.classList.add('hidden');

    try {
        const response = await fetch('{{ route("volunteer.challenges.destroy", $challenge) }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success) {
            showToast('{{ __("Challenge deleted") }}', 'success');
            setTimeout(() => window.location.href = '{{ route("volunteer.challenges.index") }}', 1000);
        } else {
            errorDiv.textContent = data.message || '{{ __("Failed to delete") }}';
            errorDiv.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '{{ __("Delete") }}';
        }
    } catch (error) {
        errorDiv.textContent = '{{ __("An error occurred") }}';
        errorDiv.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = '{{ __("Delete") }}';
    }
});

document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeEditModal(); closeDeleteModal(); } });

document.addEventListener('DOMContentLoaded', function() {
    if (new URLSearchParams(window.location.search).get('action') === 'edit') {
        openEditModal();
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Status polling for challenges in processing state
    const currentStatus = '{{ $challenge->status }}';
    const currentAiStatus = '{{ $challenge->ai_analysis_status }}';

    if (currentStatus === 'analyzing' || currentAiStatus === 'pending' || currentAiStatus === 'processing') {
        let pollCount = 0;
        const maxPolls = 60; // Stop after 5 minutes (60 * 5 seconds)

        const pollStatus = async () => {
            pollCount++;
            if (pollCount > maxPolls) {
                console.log('Status polling stopped after max attempts');
                return;
            }

            try {
                const response = await fetch('{{ route("volunteer.challenges.status", $challenge) }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                // Check if status has changed
                if (data.status !== currentStatus || data.ai_analysis_status !== currentAiStatus) {
                    console.log('Status changed:', data);
                    showToast('{{ __("Challenge status updated! Refreshing...") }}', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                    return;
                }

                // Continue polling every 5 seconds
                setTimeout(pollStatus, 5000);
            } catch (error) {
                console.error('Status poll error:', error);
                // Retry after 10 seconds on error
                setTimeout(pollStatus, 10000);
            }
        };

        // Start polling after 3 seconds
        setTimeout(pollStatus, 3000);
    }
});
</script>
@endpush
