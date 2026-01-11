@extends('layouts.app')

@section('title', __('Review Submission'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .slide-in-right { animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .prose pre { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #e2e8f0; padding: 1.25rem; border-radius: 1rem; overflow-x: auto; border: 1px solid rgba(255,255,255,0.1); }
    .prose code { background: #f1f5f9; padding: 0.125rem 0.5rem; border-radius: 0.375rem; font-size: 0.875rem; color: #6366f1; }
    .prose pre code { background: transparent; padding: 0; color: #e2e8f0; }
    .decision-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .decision-card:hover {
        transform: translateY(-4px);
    }
    .decision-card.selected {
        transform: scale(1.02);
    }
    .score-slider {
        -webkit-appearance: none;
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(to right, #ef4444 0%, #f59e0b 50%, #22c55e 100%);
    }
    .score-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: white;
        border: 3px solid #6366f1;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    .score-slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(99, 102, 241, 0.3);
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        bottom: -1.5rem;
        width: 2px;
        background: linear-gradient(to bottom, #e2e8f0, transparent);
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .circular-progress {
        transform: rotate(-90deg);
    }
    .pulse-ring {
        animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
    }
    .markdown-content h1, .markdown-content h2, .markdown-content h3 {
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .markdown-content h2 { font-size: 1.25rem; }
    .markdown-content h3 { font-size: 1.125rem; }
    .markdown-content ul, .markdown-content ol {
        padding-left: 1.5rem;
        margin: 1rem 0;
    }
    .markdown-content li { margin: 0.5rem 0; }
    .markdown-content p { margin: 0.75rem 0; line-height: 1.7; }
</style>
@endpush

@section('content')
@php
    $statusConfig = [
        'submitted' => [
            'bg' => 'amber',
            'gradient' => 'from-amber-500 via-orange-500 to-amber-600',
            'lightGradient' => 'from-amber-50 to-orange-50',
            'text' => 'amber-700',
            'label' => __('Pending Review'),
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        ],
        'under_review' => [
            'bg' => 'blue',
            'gradient' => 'from-blue-500 via-indigo-500 to-blue-600',
            'lightGradient' => 'from-blue-50 to-indigo-50',
            'text' => 'blue-700',
            'label' => __('Under Review'),
            'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        ],
        'approved' => [
            'bg' => 'emerald',
            'gradient' => 'from-emerald-500 via-teal-500 to-emerald-600',
            'lightGradient' => 'from-emerald-50 to-teal-50',
            'text' => 'emerald-700',
            'label' => __('Approved'),
            'icon' => 'M5 13l4 4L19 7'
        ],
        'revision_requested' => [
            'bg' => 'orange',
            'gradient' => 'from-orange-500 via-amber-500 to-orange-600',
            'lightGradient' => 'from-orange-50 to-amber-50',
            'text' => 'orange-700',
            'label' => __('Revision Requested'),
            'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
        ],
        'rejected' => [
            'bg' => 'red',
            'gradient' => 'from-red-500 via-rose-500 to-red-600',
            'lightGradient' => 'from-red-50 to-rose-50',
            'text' => 'red-700',
            'label' => __('Rejected'),
            'icon' => 'M6 18L18 6M6 6l12 12'
        ],
    ];
    $config = $statusConfig[$submission->status] ?? $statusConfig['submitted'];
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30" x-data="reviewPage()">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Back Navigation -->
        <a href="{{ route('company.submissions.index') }}"
            class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-semibold mb-6 slide-up group">
            <div class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-200 flex items-center justify-center group-hover:bg-indigo-50 group-hover:border-indigo-200 transition-all">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </div>
            <span>{{ __('Back to Submissions') }}</span>
        </a>

        <!-- Hero Header -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden mb-8 slide-up" style="animation-delay: 0.1s">
            <div class="relative bg-gradient-to-br {{ $config['gradient'] }} px-8 py-10 overflow-hidden">
                <!-- Background Elements -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="heroGrid" width="8" height="8" patternUnits="userSpaceOnUse">
                                <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#heroGrid)"/>
                    </svg>
                </div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-black/5 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="flex-1">
                        <!-- Status & AI Score Badges -->
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-bold border border-white/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                                {{ $config['label'] }}
                            </span>
                            @if($submission->ai_quality_score)
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-bold border border-white/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                AI Score: {{ $submission->ai_quality_score }}%
                            </span>
                            @endif
                        </div>

                        <!-- Task Title -->
                        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
                            {{ $submission->task->title ?? 'Task Submission' }}
                        </h1>
                        <p class="text-white/80 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $submission->task->challenge->title ?? 'Challenge' }}
                        </p>
                    </div>

                    <!-- Submission Date Card -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
                        <div class="text-white/70 text-sm mb-1">{{ __('Submitted') }}</div>
                        <div class="text-white font-bold text-lg">{{ $submission->submitted_at?->format('M d, Y') ?? 'N/A' }}</div>
                        <div class="text-white/70 text-sm">{{ $submission->submitted_at?->format('h:i A') ?? '' }}</div>
                        <div class="mt-3 pt-3 border-t border-white/20">
                            <div class="text-white/70 text-sm">{{ __('Hours Worked') }}</div>
                            <div class="text-white font-bold text-xl">{{ $submission->hours_worked ?? 0 }}h</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Submission Description -->
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-up" style="animation-delay: 0.15s">
                    <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <h2 class="font-bold text-slate-900 flex items-center gap-3 text-lg">
                            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            {{ __('Submission Description') }}
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="prose prose-slate max-w-none markdown-content">
                            {!! nl2br(e($submission->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Deliverable -->
                @if($submission->deliverable_url)
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-up" style="animation-delay: 0.2s">
                    <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <h2 class="font-bold text-slate-900 flex items-center gap-3 text-lg">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                            {{ __('Deliverable') }}
                        </h2>
                    </div>
                    <div class="p-8">
                        <a href="{{ $submission->deliverable_url }}" target="_blank"
                            class="group flex items-center gap-5 p-6 bg-gradient-to-r from-indigo-50 via-purple-50 to-indigo-50 border-2 border-indigo-100 rounded-2xl hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100 transition-all">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-lg group-hover:text-indigo-700 transition-colors">{{ __('View Deliverable') }}</p>
                                <p class="text-sm text-slate-500 truncate mt-1">{{ $submission->deliverable_url }}</p>
                            </div>
                            <svg class="w-6 h-6 text-indigo-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                <!-- AI Analysis -->
                @if($submission->ai_feedback)
                @php $aiFeedback = is_string($submission->ai_feedback) ? json_decode($submission->ai_feedback, true) : $submission->ai_feedback; @endphp
                @if($aiFeedback)
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-up" style="animation-delay: 0.25s">
                    <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-violet-50 via-purple-50 to-violet-50">
                        <h2 class="font-bold text-violet-900 flex items-center gap-3 text-lg">
                            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center pulse-ring">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            {{ __('AI Quality Analysis') }}
                        </h2>
                    </div>
                    <div class="p-8">
                        @if(isset($aiFeedback['feedback']))
                        <p class="text-slate-700 text-lg leading-relaxed mb-6">{{ $aiFeedback['feedback'] }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @if(isset($aiFeedback['strengths']) && count($aiFeedback['strengths']) > 0)
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-200">
                                <h3 class="font-bold text-emerald-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Strengths') }}
                                </h3>
                                <ul class="space-y-3">
                                    @foreach($aiFeedback['strengths'] as $strength)
                                    <li class="flex items-start gap-3">
                                        <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <span class="text-slate-700">{{ $strength }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if(isset($aiFeedback['areas_for_improvement']) && count($aiFeedback['areas_for_improvement']) > 0)
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200">
                                <h3 class="font-bold text-amber-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ __('Areas for Improvement') }}
                                </h3>
                                <ul class="space-y-3">
                                    @foreach($aiFeedback['areas_for_improvement'] as $area)
                                    <li class="flex items-start gap-3">
                                        <div class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-slate-700">{{ $area }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif

                <!-- Review History Timeline -->
                @if($submission->reviews && $submission->reviews->count() > 0)
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-up" style="animation-delay: 0.3s">
                    <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <h2 class="font-bold text-slate-900 flex items-center gap-3 text-lg">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ __('Review History') }}
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            @foreach($submission->reviews as $review)
                            <div class="timeline-item relative pl-14">
                                <!-- Timeline Dot -->
                                <div class="absolute left-0 top-0 w-10 h-10 rounded-full flex items-center justify-center
                                    @if($review->decision === 'approved') bg-emerald-100
                                    @elseif($review->decision === 'revision_requested') bg-orange-100
                                    @else bg-red-100
                                    @endif">
                                    @if($review->decision === 'approved')
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    @elseif($review->decision === 'revision_requested')
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    @endif
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                            @if($review->decision === 'approved') bg-emerald-100 text-emerald-700
                                            @elseif($review->decision === 'revision_requested') bg-orange-100 text-orange-700
                                            @else bg-red-100 text-red-700
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                                        </span>
                                        <span class="text-sm text-slate-500">{{ $review->created_at->format('M d, Y - h:i A') }}</span>
                                    </div>
                                    <p class="text-slate-700 leading-relaxed">{{ $review->feedback }}</p>

                                    @if($review->quality_score || $review->timeliness_score || $review->communication_score)
                                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-slate-200">
                                        @if($review->quality_score)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-slate-500">Quality</div>
                                                <div class="font-bold text-slate-900">{{ $review->quality_score }}%</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($review->timeliness_score)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-slate-500">Timeliness</div>
                                                <div class="font-bold text-slate-900">{{ $review->timeliness_score }}%</div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($review->communication_score)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-slate-500">Communication</div>
                                                <div class="font-bold text-slate-900">{{ $review->communication_score }}%</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Review Form -->
                @if(in_array($submission->status, ['submitted', 'under_review', 'revision_requested']))
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden slide-up" style="animation-delay: 0.35s">
                    <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50">
                        <h2 class="font-bold text-emerald-900 flex items-center gap-3 text-lg">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            {{ __('Submit Your Review') }}
                        </h2>
                    </div>
                    <form action="{{ route('company.submissions.review', $submission->id) }}" method="POST" class="p-8">
                        @csrf
                        <div class="space-y-8">

                            <!-- Decision Cards -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-4">{{ __('Your Decision') }}</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Approve -->
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="approved" class="peer sr-only" required x-model="decision">
                                        <div class="decision-card p-6 border-2 border-slate-200 rounded-2xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-lg peer-checked:shadow-emerald-100 transition-all hover:border-emerald-300 hover:bg-emerald-50/50">
                                            <div class="w-14 h-14 bg-emerald-100 peer-checked:bg-emerald-500 rounded-xl flex items-center justify-center mb-4 transition-colors">
                                                <svg class="w-7 h-7 text-emerald-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <h3 class="font-bold text-slate-900 text-lg mb-1">{{ __('Approve') }}</h3>
                                            <p class="text-sm text-slate-500">{{ __('Accept this submission as complete') }}</p>
                                        </div>
                                    </label>

                                    <!-- Request Revision -->
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="revision_requested" class="peer sr-only" x-model="decision">
                                        <div class="decision-card p-6 border-2 border-slate-200 rounded-2xl peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:shadow-lg peer-checked:shadow-orange-100 transition-all hover:border-orange-300 hover:bg-orange-50/50">
                                            <div class="w-14 h-14 bg-orange-100 peer-checked:bg-orange-500 rounded-xl flex items-center justify-center mb-4 transition-colors">
                                                <svg class="w-7 h-7 text-orange-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </div>
                                            <h3 class="font-bold text-slate-900 text-lg mb-1">{{ __('Request Revision') }}</h3>
                                            <p class="text-sm text-slate-500">{{ __('Ask for changes or improvements') }}</p>
                                        </div>
                                    </label>

                                    <!-- Reject -->
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="rejected" class="peer sr-only" x-model="decision">
                                        <div class="decision-card p-6 border-2 border-slate-200 rounded-2xl peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:shadow-lg peer-checked:shadow-red-100 transition-all hover:border-red-300 hover:bg-red-50/50">
                                            <div class="w-14 h-14 bg-red-100 peer-checked:bg-red-500 rounded-xl flex items-center justify-center mb-4 transition-colors">
                                                <svg class="w-7 h-7 text-red-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <h3 class="font-bold text-slate-900 text-lg mb-1">{{ __('Reject') }}</h3>
                                            <p class="text-sm text-slate-500">{{ __("This submission doesn't meet requirements") }}</p>
                                        </div>
                                    </label>
                                </div>
                                @error('decision')
                                <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Score Sliders -->
                            <div class="bg-slate-50 rounded-2xl p-6">
                                <h3 class="font-bold text-slate-900 mb-5">{{ __('Rate the Submission (Optional)') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Quality Score -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-medium text-slate-700">{{ __('Quality') }}</label>
                                            <span class="text-sm font-bold text-indigo-600" x-text="qualityScore || '-'"></span>
                                        </div>
                                        <input type="range" name="quality_score" min="0" max="100" value="0"
                                            x-model="qualityScore"
                                            class="w-full score-slider">
                                    </div>

                                    <!-- Timeliness Score -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-medium text-slate-700">{{ __('Timeliness') }}</label>
                                            <span class="text-sm font-bold text-indigo-600" x-text="timelinessScore || '-'"></span>
                                        </div>
                                        <input type="range" name="timeliness_score" min="0" max="100" value="0"
                                            x-model="timelinessScore"
                                            class="w-full score-slider">
                                    </div>

                                    <!-- Communication Score -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm font-medium text-slate-700">{{ __('Communication') }}</label>
                                            <span class="text-sm font-bold text-indigo-600" x-text="communicationScore || '-'"></span>
                                        </div>
                                        <input type="range" name="communication_score" min="0" max="100" value="0"
                                            x-model="communicationScore"
                                            class="w-full score-slider">
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback Textarea -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">{{ __('Your Feedback') }}</label>
                                <textarea name="feedback" rows="5" required minlength="10"
                                    class="w-full px-5 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all resize-none text-slate-700"
                                    placeholder="{{ __('Provide detailed, constructive feedback about the submission...') }}"></textarea>
                                @error('feedback')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('company.submissions.index') }}"
                                    class="px-6 py-3 text-slate-600 hover:text-slate-900 font-semibold transition-colors">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit"
                                    class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:scale-105 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Submit Review') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Contributor Profile Card -->
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-in-right" style="animation-delay: 0.1s">
                    <div class="p-6 bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 text-white relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-2xl font-black border border-white/30">
                                {{ strtoupper(substr($submission->volunteer->user->name ?? 'V', 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">{{ $submission->volunteer->user->name ?? 'Volunteer' }}</h3>
                                <p class="text-white/80">{{ $submission->volunteer->field ?? 'Expert' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Experience
                            </span>
                            <span class="font-bold text-slate-900">{{ $submission->volunteer->experience_level ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Years
                            </span>
                            <span class="font-bold text-slate-900">{{ $submission->volunteer->years_of_experience ?? 0 }} years</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Reputation
                            </span>
                            <span class="font-bold text-emerald-600">{{ $submission->volunteer->reputation_score ?? 50 }}</span>
                        </div>

                        @if($submission->volunteer->skills && $submission->volunteer->skills->count() > 0)
                        <div class="pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-500 mb-3 font-semibold uppercase tracking-wide">Skills</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($submission->volunteer->skills->take(8) as $skill)
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium">{{ $skill->skill_name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Task Details Card -->
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-in-right" style="animation-delay: 0.15s">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                        <h2 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Task Details
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-500">Estimated</span>
                            <span class="font-bold text-slate-900">{{ $submission->task->estimated_hours ?? 0 }}h</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-500">Actual</span>
                            <span class="font-bold text-{{ ($submission->hours_worked ?? 0) <= ($submission->task->estimated_hours ?? 0) ? 'emerald' : 'amber' }}-600">{{ $submission->hours_worked ?? 0 }}h</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-500">Complexity</span>
                            <div class="flex items-center gap-2">
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 10; $i++)
                                    <div class="w-2 h-4 rounded-sm {{ $i <= ($submission->task->complexity_score ?? 0) ? 'bg-indigo-500' : 'bg-slate-200' }}"></div>
                                    @endfor
                                </div>
                                <span class="font-bold text-slate-900">{{ $submission->task->complexity_score ?? 'N/A' }}</span>
                            </div>
                        </div>
                        @if($submission->task->workstream)
                        <div class="flex items-center justify-between py-3">
                            <span class="text-slate-500">Workstream</span>
                            <span class="font-bold text-slate-900 text-right text-sm">{{ $submission->task->workstream->title }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- AI Score Card -->
                <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden hover-lift slide-in-right" style="animation-delay: 0.2s">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-purple-50">
                        <h2 class="font-bold text-violet-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            AI Analysis
                        </h2>
                    </div>
                    <div class="p-6 flex flex-col items-center">
                        @if($submission->ai_quality_score)
                        <div class="relative mb-4">
                            <svg class="w-32 h-32 circular-progress" viewBox="0 0 36 36">
                                <path class="text-slate-100"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    fill="none"
                                    d="M18 2.0845
                                        a 15.9155 15.9155 0 0 1 0 31.831
                                        a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="{{ $submission->ai_quality_score >= 80 ? 'text-emerald-500' : ($submission->ai_quality_score >= 60 ? 'text-amber-500' : 'text-red-500') }}"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    fill="none"
                                    stroke-dasharray="{{ $submission->ai_quality_score }}, 100"
                                    d="M18 2.0845
                                        a 15.9155 15.9155 0 0 1 0 31.831
                                        a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black {{ $submission->ai_quality_score >= 80 ? 'text-emerald-600' : ($submission->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ $submission->ai_quality_score }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">AI Score</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold {{ $submission->ai_quality_score >= 80 ? 'text-emerald-600' : ($submission->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                @if($submission->ai_quality_score >= 80)
                                Excellent Quality
                                @elseif($submission->ai_quality_score >= 60)
                                Good Quality
                                @else
                                Needs Improvement
                                @endif
                            </p>
                        </div>
                        @else
                        <div class="text-center py-6">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 text-sm">AI analysis pending</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 rounded-3xl shadow-lg p-6 text-white slide-in-right" style="animation-delay: 0.25s">
                    <h3 class="font-bold text-lg mb-4">Quick Stats</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                            <div class="text-3xl font-black">{{ $submission->reviews->count() }}</div>
                            <div class="text-sm text-white/80">Reviews</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center">
                            <div class="text-3xl font-black">#{{ $submission->id }}</div>
                            <div class="text-sm text-white/80">ID</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reviewPage() {
    return {
        decision: '',
        qualityScore: 0,
        timelinessScore: 0,
        communicationScore: 0
    }
}
</script>
@endpush
@endsection
