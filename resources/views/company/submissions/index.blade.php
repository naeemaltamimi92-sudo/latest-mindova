@extends('layouts.app')

@section('title', __('Work Submissions'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .submission-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .submission-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }
    .submission-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .submission-card:hover::before {
        opacity: 1;
    }
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .stat-card:hover {
        transform: scale(1.02);
    }
    .stat-card.active {
        background: linear-gradient(135deg, var(--active-from), var(--active-to));
        color: white;
    }
    .circular-progress {
        transform: rotate(-90deg);
    }
    .circular-progress circle {
        transition: stroke-dashoffset 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .pulse-dot {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.1); }
    }
    .filter-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .filter-btn:hover {
        transform: translateY(-2px);
    }
    .filter-btn.active {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }
    .volunteer-avatar {
        transition: transform 0.3s;
    }
    .submission-card:hover .volunteer-avatar {
        transform: scale(1.1);
    }
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .glass-effect {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.8);
    }
    .priority-badge {
        animation: glow 2s ease-in-out infinite alternate;
    }
    @keyframes glow {
        from { box-shadow: 0 0 5px currentColor; }
        to { box-shadow: 0 0 15px currentColor; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30" x-data="submissionsPage()">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Premium Header -->
        <div class="mb-8 slide-up">
            <div class="bg-white rounded-3xl shadow-2xl shadow-emerald-500/10 border border-slate-200/60 overflow-hidden">
                <!-- Hero Section -->
                <div class="relative bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 px-8 py-8 overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <defs>
                                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#grid)"/>
                        </svg>
                    </div>
                    <!-- Floating Shapes -->
                    <div class="absolute top-4 right-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-0 left-1/4 w-48 h-48 bg-teal-400/20 rounded-full blur-3xl"></div>

                    <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30 shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ __('Work Submissions') }}</h1>
                                <p class="text-emerald-100 mt-1 text-lg">{{ __('Review and manage volunteer deliverables') }}</p>
                            </div>
                        </div>

                        <!-- Quick Stats Pills -->
                        <div class="flex flex-wrap gap-3">
                            @if($stats['pending'] > 0)
                            <div class="px-4 py-2 bg-amber-400/20 backdrop-blur-sm border border-amber-300/30 rounded-full flex items-center gap-2">
                                <span class="w-2 h-2 bg-amber-400 rounded-full pulse-dot"></span>
                                <span class="text-white font-bold">{{ $stats['pending'] }} {{ __('Pending') }}</span>
                            </div>
                            @endif
                            <div class="px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full">
                                <span class="text-white font-bold">{{ $stats['total'] }} Total</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-6 divide-x divide-y md:divide-y-0 divide-slate-100">
                    @php
                    $statItems = [
                        ['key' => 'all', 'value' => $stats['total'], 'label' => 'All', 'color' => 'slate', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['key' => 'submitted', 'value' => $stats['pending'], 'label' => 'Pending', 'color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['key' => 'under_review', 'value' => $stats['under_review'], 'label' => 'Reviewing', 'color' => 'blue', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['key' => 'approved', 'value' => $stats['approved'], 'label' => 'Approved', 'color' => 'emerald', 'icon' => 'M5 13l4 4L19 7'],
                        ['key' => 'revision_requested', 'value' => $stats['revision_requested'], 'label' => 'Revisions', 'color' => 'orange', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                        ['key' => 'rejected', 'value' => $stats['rejected'], 'label' => 'Rejected', 'color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                    ];
                    @endphp

                    @foreach($statItems as $stat)
                    <button
                        @click="activeFilter = '{{ $stat['key'] }}'"
                        :class="activeFilter === '{{ $stat['key'] }}' ? 'bg-{{ $stat['color'] }}-50 border-b-2 border-{{ $stat['color'] }}-500' : 'hover:bg-slate-50'"
                        class="stat-card px-4 py-5 text-center transition-all relative group"
                    >
                        <div class="flex flex-col items-center">
                            <div class="mb-2 p-2 rounded-xl transition-all"
                                :class="activeFilter === '{{ $stat['key'] }}' ? 'bg-{{ $stat['color'] }}-100' : 'bg-slate-100 group-hover:bg-{{ $stat['color'] }}-100'"
                            >
                                <svg class="w-5 h-5 transition-colors"
                                    :class="activeFilter === '{{ $stat['key'] }}' ? 'text-{{ $stat['color'] }}-600' : 'text-slate-400 group-hover:text-{{ $stat['color'] }}-500'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="text-2xl font-black transition-colors"
                                :class="activeFilter === '{{ $stat['key'] }}' ? 'text-{{ $stat['color'] }}-600' : '{{ $stat['value'] > 0 ? 'text-slate-900' : 'text-slate-300' }}'"
                            >
                                {{ $stat['value'] }}
                            </div>
                            <div class="text-xs font-semibold text-slate-500 mt-1">{{ $stat['label'] }}</div>
                        </div>
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-{{ $stat['color'] }}-500 transition-all duration-300"
                            :class="activeFilter === '{{ $stat['key'] }}' ? 'w-full' : 'group-hover:w-1/2'"
                        ></div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 slide-up" style="animation-delay: 0.1s" x-data="{ show: true }" x-show="show" x-transition>
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center justify-between shadow-lg shadow-emerald-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Submissions List -->
        <div class="space-y-5">
            @forelse($submissions as $index => $submission)
            @php
                $statusConfig = [
                    'submitted' => [
                        'bg' => 'amber',
                        'gradient' => 'from-amber-500 via-orange-500 to-amber-600',
                        'lightGradient' => 'from-amber-50 to-orange-50',
                        'text' => 'amber-700',
                        'label' => 'Pending Review',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'priority' => true
                    ],
                    'under_review' => [
                        'bg' => 'blue',
                        'gradient' => 'from-blue-500 via-indigo-500 to-blue-600',
                        'lightGradient' => 'from-blue-50 to-indigo-50',
                        'text' => 'blue-700',
                        'label' => 'Under Review',
                        'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                        'priority' => false
                    ],
                    'approved' => [
                        'bg' => 'emerald',
                        'gradient' => 'from-emerald-500 via-teal-500 to-emerald-600',
                        'lightGradient' => 'from-emerald-50 to-teal-50',
                        'text' => 'emerald-700',
                        'label' => 'Approved',
                        'icon' => 'M5 13l4 4L19 7',
                        'priority' => false
                    ],
                    'revision_requested' => [
                        'bg' => 'orange',
                        'gradient' => 'from-orange-500 via-amber-500 to-orange-600',
                        'lightGradient' => 'from-orange-50 to-amber-50',
                        'text' => 'orange-700',
                        'label' => 'Revision Requested',
                        'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        'priority' => true
                    ],
                    'rejected' => [
                        'bg' => 'red',
                        'gradient' => 'from-red-500 via-rose-500 to-red-600',
                        'lightGradient' => 'from-red-50 to-rose-50',
                        'text' => 'red-700',
                        'label' => 'Rejected',
                        'icon' => 'M6 18L18 6M6 6l12 12',
                        'priority' => false
                    ],
                ];
                $config = $statusConfig[$submission->status] ?? $statusConfig['submitted'];
            @endphp

            <div
                x-show="activeFilter === 'all' || activeFilter === '{{ $submission->status }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="submission-card bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-200/60 overflow-hidden slide-up group"
                style="animation-delay: {{ 0.15 + ($index * 0.05) }}s"
            >
                <!-- Status Bar -->
                <div class="h-1.5 bg-gradient-to-r {{ $config['gradient'] }}"></div>

                <div class="p-6 lg:p-8">
                    <div class="flex flex-col xl:flex-row xl:items-start gap-6">
                        <!-- Left: Main Info -->
                        <div class="flex-1 min-w-0">
                            <!-- Header Row -->
                            <div class="flex flex-wrap items-start gap-3 mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <h3 class="text-xl font-bold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $submission->task->title ?? 'Task' }}
                                        </h3>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-{{ $config['bg'] }}-100 text-{{ $config['text'] }} rounded-full text-xs font-bold {{ $config['priority'] ? 'priority-badge' : '' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                            </svg>
                                            {{ $config['label'] }}
                                        </span>
                                        @if($submission->is_spam)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            Spam
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500 text-sm">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="truncate">{{ $submission->task->challenge->title ?? 'Challenge' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Volunteer & Meta Info -->
                            <div class="flex flex-wrap items-center gap-4 mb-5">
                                <!-- Volunteer Badge -->
                                <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-4 py-2.5">
                                    <div class="volunteer-avatar w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30">
                                        {{ strtoupper(substr($submission->volunteer->user->name ?? 'V', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm">{{ $submission->volunteer->user->name ?? 'Volunteer' }}</p>
                                        <p class="text-xs text-slate-500">{{ $submission->volunteer->field ?? 'Expert' }}</p>
                                    </div>
                                </div>

                                <!-- Meta Pills -->
                                <div class="flex flex-wrap gap-2">
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 rounded-lg text-xs text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-medium">{{ $submission->hours_worked ?? 0 }}h worked</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 rounded-lg text-xs text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-medium">{{ $submission->submitted_at?->diffForHumans() ?? 'Recently' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Preview -->
                            <div class="bg-gradient-to-r {{ $config['lightGradient'] }} rounded-2xl p-5 mb-5 border border-{{ $config['bg'] }}-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-4 h-4 text-{{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-700 line-clamp-2 leading-relaxed">
                                            {{ Str::limit(strip_tags($submission->description), 200) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Deliverable & AI Analysis -->
                            <div class="flex flex-wrap items-center gap-3">
                                @if($submission->deliverable_url)
                                <a href="{{ $submission->deliverable_url }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-sm font-semibold transition-all hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    View Deliverable
                                </a>
                                @endif

                                @if($submission->ai_feedback)
                                @php $aiFeedback = is_string($submission->ai_feedback) ? json_decode($submission->ai_feedback, true) : $submission->ai_feedback; @endphp
                                @if($aiFeedback && isset($aiFeedback['strengths']))
                                <div class="flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-700 rounded-xl text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <span class="font-medium">{{ count($aiFeedback['strengths']) }} strengths identified</span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>

                        <!-- Right: AI Score & Actions -->
                        <div class="flex xl:flex-col items-center xl:items-end gap-4 xl:gap-5">
                            <!-- AI Score Circle -->
                            @if($submission->ai_quality_score)
                            <div class="relative">
                                <svg class="w-20 h-20 circular-progress" viewBox="0 0 36 36">
                                    <path class="text-slate-100"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        fill="none"
                                        d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="{{ $submission->ai_quality_score >= 80 ? 'text-emerald-500' : ($submission->ai_quality_score >= 60 ? 'text-amber-500' : 'text-red-500') }}"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-linecap="round"
                                        fill="none"
                                        stroke-dasharray="{{ $submission->ai_quality_score }}, 100"
                                        d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xl font-black {{ $submission->ai_quality_score >= 80 ? 'text-emerald-600' : ($submission->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ $submission->ai_quality_score }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">AI Score</span>
                                </div>
                            </div>
                            @else
                            <div class="w-20 h-20 rounded-full bg-slate-100 flex flex-col items-center justify-center">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span class="text-[10px] text-slate-400 font-medium mt-1">Pending</span>
                            </div>
                            @endif

                            <!-- Review Button -->
                            <a href="{{ route('company.submissions.show', $submission->id) }}"
                                class="group/btn relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Review</span>
                                <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between mt-5 pt-5 border-t border-slate-100">
                        <div class="text-sm text-slate-500">
                            @if($submission->reviewed_at)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Reviewed {{ $submission->reviewed_at->diffForHumans() }}
                            </span>
                            @else
                            <span class="flex items-center gap-1.5 text-amber-600">
                                <span class="w-2 h-2 bg-amber-500 rounded-full pulse-dot"></span>
                                Awaiting your review
                            </span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-400">
                            ID: #{{ $submission->id }}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-12 text-center slide-up" style="animation-delay: 0.2s">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">No Submissions Yet</h3>
                    <p class="text-slate-500 mb-6">Work submissions from volunteers will appear here once they submit their deliverables for your challenges.</p>
                    <a href="{{ route('challenges.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        View Your Challenges
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($submissions->hasPages())
        <div class="mt-8 slide-up" style="animation-delay: 0.3s">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function submissionsPage() {
    return {
        activeFilter: 'all'
    }
}
</script>
@endpush
@endsection
