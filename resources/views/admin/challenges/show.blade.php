@extends('layouts.app')

@section('title', $challenge->title . ' - ' . __('Challenge Details'))

@push('styles')
<style>
    /* Circular Progress Ring */
    .progress-ring {
        transform: rotate(-90deg);
    }
    .progress-ring__circle {
        transition: stroke-dashoffset 0.5s-out;
        transform-origin: 50% 50%;
    }

    /* Animated gradient background */
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: shimmer 15s linear infinite;
    }
    @keyframes shimmer {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Status Timeline */
    .status-timeline {
        position: relative;
    }
    .status-timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 100%;
        background: linear-gradient(to bottom, #e2e8f0, #cbd5e1);
    }

    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Pulse Animation */
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(1.3); opacity: 0; }
    }
    .pulse-indicator::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: currentColor;
        animation: pulse-ring 1.5s infinite;
        opacity: 0.3;
    }

    /* Countdown Timer */
    .countdown-digit {
        font-variant-numeric: tabular-nums;
    }

    /* Tab Animation */
    .tab-indicator {
        transition: transform 0.3s ease, width 0.3s ease;
    }

    /* Skill Badge Hover */
    .skill-badge {
        transition: all 0.2s ease;
    }
    .skill-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* Card Hover Effect */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="challengeDetails()">

    <!-- Hero Header -->
    <div class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-white/70 text-sm mb-6">
                <a href="{{ route('admin.challenges.index') }}" class="hover:text-white transition">{{ __('Challenges') }}</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-medium">{{ Str::limit($challenge->title, 40) }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <!-- Challenge Icon -->
                        <div class="hidden sm:flex h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-sm items-center justify-center flex-shrink-0">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-black">{{ $challenge->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-white/80 text-sm">
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    {{ $challenge->id }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $challenge->created_at->format('M d, Y') }}
                                </span>
                                @if($challenge->field)
                                <span class="px-3 py-1 bg-white/20 rounded-full">{{ $challenge->field }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <x-ui.button as="a" href="{{ route('admin.challenges.index') }}" variant="secondary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back') }}
                    </x-ui.button>
                    <x-ui.button @click="showDeleteModal = true" variant="destructive">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('Delete') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="bg-white border-b border-slate-200 shadow-sm -mt-4 relative z-10 mx-4 lg:mx-8 rounded-2xl">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 divide-x divide-slate-100">
                <!-- Status -->
                @php
                    $statusInfo = $statuses[$challenge->status] ?? ['label' => ucfirst($challenge->status), 'color' => 'gray'];
                    $statusBgColors = [
                        'yellow' => 'bg-yellow-500',
                        'blue' => 'bg-blue-500',
                        'green' => 'bg-green-500',
                        'indigo' => 'bg-indigo-500',
                        'emerald' => 'bg-emerald-500',
                        'teal' => 'bg-teal-500',
                        'gray' => 'bg-slate-500',
                        'red' => 'bg-red-500',
                    ];
                @endphp
                <div class="p-4 text-center hover-lift cursor-default">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $statusBgColors[$statusInfo['color']] ?? 'bg-slate-500' }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 {{ $statusBgColors[$statusInfo['color']] ?? 'bg-slate-500' }}"></span>
                        </span>
                        <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">{{ __('Status') }}</span>
                    </div>
                    <p class="text-lg font-black text-slate-900">{{ __($statusInfo['label']) }}</p>
                </div>

                <!-- Progress Circle -->
                <div class="p-4 text-center hover-lift cursor-default">
                    <div class="flex items-center justify-center gap-3">
                        <div class="relative">
                            <svg class="progress-ring h-12 w-12" viewBox="0 0 36 36">
                                <path class="text-slate-100" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="progress-ring__circle text-indigo-500" stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="{{ $challenge->progress_percentage }}, 100"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-black text-indigo-600">{{ $challenge->progress_percentage }}%</span>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-slate-500 font-medium">{{ __('Progress') }}</p>
                            <p class="text-sm font-bold text-slate-900">{{ __('Completion') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Score -->
                <div class="p-4 text-center hover-lift cursor-default">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">{{ __('Score') }}</p>
                    <div class="flex items-center justify-center gap-1">
                        <span class="text-2xl font-black text-purple-600">{{ $challenge->score ?? '-' }}</span>
                        <span class="text-sm text-slate-400">/10</span>
                    </div>
                </div>

                <!-- Complexity -->
                <div class="p-4 text-center hover-lift cursor-default">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">{{ __('Complexity') }}</p>
                    <div class="flex items-center justify-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= ($challenge->complexity_level ?? 0) ? 'text-orange-500' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                    <p class="text-sm font-bold text-slate-900 mt-1">{{ __('Level') }} {{ $challenge->complexity_level ?? '-' }}</p>
                </div>

                <!-- Tasks -->
                @php
                    $totalTasks = $challenge->workstreams->sum(fn($ws) => $ws->tasks->count());
                    $completedTasks = $challenge->workstreams->sum(fn($ws) => $ws->tasks->where('status', 'completed')->count());
                @endphp
                <div class="p-4 text-center hover-lift cursor-default">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">{{ __('Tasks') }}</p>
                    <p class="text-2xl font-black text-emerald-600">{{ $completedTasks }}<span class="text-slate-300">/</span>{{ $totalTasks }}</p>
                    <p class="text-xs text-slate-500">{{ __('Completed') }}</p>
                </div>

                <!-- Health -->
                @php
                    $healthStatus = $challenge->health_status;
                    $healthConfig = [
                        'on_track' => ['label' => __('On Track'), 'color' => 'text-green-600', 'bg' => 'bg-green-100', 'icon' => 'M5 13l4 4L19 7'],
                        'at_risk' => ['label' => __('At Risk'), 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-100', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                        'behind' => ['label' => __('Behind'), 'color' => 'text-red-600', 'bg' => 'bg-red-100', 'icon' => 'M6 18L18 6M6 6l12 12'],
                    ];
                    $health = $healthConfig[$healthStatus] ?? $healthConfig['on_track'];
                @endphp
                <div class="p-4 text-center hover-lift cursor-default">
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">{{ __('Health') }}</p>
                    <div class="flex items-center justify-center gap-2">
                        <div class="h-8 w-8 rounded-lg {{ $health['bg'] }} flex items-center justify-center">
                            <svg class="h-4 w-4 {{ $health['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $health['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="font-bold {{ $health['color'] }}">{{ $health['label'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Deadline Countdown (if applicable) -->
        @if($challenge->deadline)
        @php
            $daysLeft = now()->diffInDays($challenge->deadline, false);
            $isOverdue = $daysLeft < 0;
            $isUrgent = $daysLeft >= 0 && $daysLeft <= 3;
        @endphp
        <div class="mb-8 p-6 rounded-2xl {{ $isOverdue ? 'bg-red-50 border-2 border-red-200' : ($isUrgent ? 'bg-yellow-50 border-2 border-yellow-200' : 'bg-gray-50 border border-gray-100') }}">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl {{ $isOverdue ? 'bg-red-100' : ($isUrgent ? 'bg-yellow-100' : 'bg-white') }} flex items-center justify-center shadow-sm">
                        <svg class="h-7 w-7 {{ $isOverdue ? 'text-red-600' : ($isUrgent ? 'text-yellow-600' : 'text-indigo-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold {{ $isOverdue ? 'text-red-900' : ($isUrgent ? 'text-yellow-900' : 'text-slate-900') }}">
                            {{ $isOverdue ? __('Deadline Passed') : ($isUrgent ? __('Deadline Approaching') : __('Challenge Deadline')) }}
                        </h3>
                        <p class="text-sm {{ $isOverdue ? 'text-red-600' : ($isUrgent ? 'text-yellow-600' : 'text-slate-500') }}">
                            {{ $challenge->deadline->format('l, F d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-6" x-data="countdown('{{ $challenge->deadline->toISOString() }}')">
                    @if(!$isOverdue)
                    <div class="text-center">
                        <span class="countdown-digit text-3xl font-black {{ $isUrgent ? 'text-yellow-600' : 'text-indigo-600' }}" x-text="days">0</span>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">{{ __('Days') }}</p>
                    </div>
                    <div class="text-center">
                        <span class="countdown-digit text-3xl font-black {{ $isUrgent ? 'text-yellow-600' : 'text-indigo-600' }}" x-text="hours">0</span>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">{{ __('Hours') }}</p>
                    </div>
                    <div class="text-center">
                        <span class="countdown-digit text-3xl font-black {{ $isUrgent ? 'text-yellow-600' : 'text-indigo-600' }}" x-text="minutes">0</span>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">{{ __('Mins') }}</p>
                    </div>
                    @else
                    <div class="px-4 py-2 bg-red-200 text-red-800 rounded-xl font-bold">
                        {{ abs($daysLeft) }} {{ __('days overdue') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Progress Visualization -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-6 bg-primary-500">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            {{ __('Progress Overview') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Main Progress Bar -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-slate-700">{{ __('Overall Completion') }}</span>
                                <span class="text-lg font-black text-indigo-600">{{ $challenge->progress_percentage }}%</span>
                            </div>
                            <div class="relative w-full bg-slate-100 rounded-full h-5 overflow-hidden">
                                <div class="absolute inset-0 bg-primary-500 rounded-full0" style="width: {{ max($challenge->progress_percentage, 2) }}%"></div>
                                <div class="absolute inset-0 bg-white/30"></div>
                            </div>
                        </div>

                        <!-- Progress Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="h-10 w-10 rounded-lg bg-indigo-500 text-white flex items-center justify-center mx-auto mb-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-black text-indigo-600">{{ $challenge->total_estimated_hours }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('Total Hours') }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="h-10 w-10 rounded-lg bg-orange-500 text-white flex items-center justify-center mx-auto mb-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-black text-orange-600">{{ $challenge->estimated_remaining_hours }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('Remaining Hours') }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="h-10 w-10 rounded-lg bg-purple-500 text-white flex items-center justify-center mx-auto mb-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-black text-purple-600">{{ number_format($challenge->time_based_progress, 1) }}%</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('Time Progress') }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="h-10 w-10 rounded-lg bg-emerald-500 text-white flex items-center justify-center mx-auto mb-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-black text-emerald-600">{{ number_format($challenge->performance_score, 1) }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('Performance') }}</p>
                            </div>
                        </div>

                        <!-- Task Status Breakdown -->
                        @if($totalTasks > 0)
                        <div class="pt-6 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                {{ __('Task Status Breakdown') }}
                            </h3>
                            @php
                                $taskStatuses = [
                                    'pending' => ['label' => __('Pending'), 'color' => 'bg-slate-400', 'count' => 0],
                                    'in_progress' => ['label' => __('In Progress'), 'color' => 'bg-blue-500', 'count' => 0],
                                    'completed' => ['label' => __('Completed'), 'color' => 'bg-emerald-500', 'count' => 0],
                                ];
                                foreach($challenge->workstreams as $ws) {
                                    foreach($ws->tasks as $task) {
                                        if(isset($taskStatuses[$task->status])) {
                                            $taskStatuses[$task->status]['count']++;
                                        } else {
                                            $taskStatuses['pending']['count']++;
                                        }
                                    }
                                }
                            @endphp
                            <div class="flex h-4 rounded-full overflow-hidden bg-slate-100 shadow-inner">
                                @foreach($taskStatuses as $status => $data)
                                    @if($data['count'] > 0)
                                    <div class="{{ $data['color'] }}" style="width: {{ ($data['count'] / $totalTasks) * 100 }}%" title="{{ $data['label'] }}: {{ $data['count'] }}"></div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex flex-wrap gap-6 mt-4">
                                @foreach($taskStatuses as $status => $data)
                                <div class="flex items-center gap-2">
                                    <div class="h-4 w-4 rounded-full {{ $data['color'] }} shadow-sm"></div>
                                    <span class="text-sm text-slate-600">{{ $data['label'] }}: <strong class="text-slate-900">{{ $data['count'] }}</strong></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- AI Analysis Details with Tabs -->
                @if($challenge->challengeAnalyses->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift" x-data="{ activeTab: 'overview' }">
                    <div class="p-6 border-b border-slate-100 bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">{{ __('AI Analysis Details') }}</h2>
                                    <p class="text-sm text-slate-500">{{ __('Comprehensive AI-powered challenge analysis') }}</p>
                                </div>
                            </div>
                            @php $analysis = $challenge->challengeAnalyses->first(); @endphp
                            @if($analysis)
                            <div class="flex items-center gap-2">
                                @if($analysis->confidence_score)
                                <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold">
                                    {{ $analysis->confidence_score }}% {{ __('Confidence') }}
                                </span>
                                @endif
                                @if($analysis->requires_human_review)
                                <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-bold">
                                    {{ __('Review Required') }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Tabs -->
                        <div class="flex flex-wrap gap-2 mt-6">
                            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-white shadow-sm text-indigo-600 font-bold' : 'text-slate-600 hover:bg-white/50'" class="px-4 py-2 rounded-xl text-sm transition">
                                {{ __('Overview') }}
                            </button>
                            <button @click="activeTab = 'objectives'" :class="activeTab === 'objectives' ? 'bg-white shadow-sm text-indigo-600 font-bold' : 'text-slate-600 hover:bg-white/50'" class="px-4 py-2 rounded-xl text-sm transition">
                                {{ __('Objectives') }}
                            </button>
                            <button @click="activeTab = 'risks'" :class="activeTab === 'risks' ? 'bg-white shadow-sm text-indigo-600 font-bold' : 'text-slate-600 hover:bg-white/50'" class="px-4 py-2 rounded-xl text-sm transition">
                                {{ __('Risks & Constraints') }}
                            </button>
                            <button @click="activeTab = 'stakeholders'" :class="activeTab === 'stakeholders' ? 'bg-white shadow-sm text-indigo-600 font-bold' : 'text-slate-600 hover:bg-white/50'" class="px-4 py-2 rounded-xl text-sm transition">
                                {{ __('Stakeholders') }}
                            </button>
                            <button @click="activeTab = 'approach'" :class="activeTab === 'approach' ? 'bg-white shadow-sm text-indigo-600 font-bold' : 'text-slate-600 hover:bg-white/50'" class="px-4 py-2 rounded-xl text-sm transition">
                                {{ __('Approach') }}
                            </button>
                        </div>
                    </div>

                    @foreach($challenge->challengeAnalyses as $analysis)
                    <div class="p-6">
                        <!-- Overview Tab -->
                        <div x-show="activeTab === 'overview'"   >
                            @if($analysis->summary)
                            <div class="p-5 bg-gray-50 rounded-xl border border-indigo-100 mb-6">
                                <h3 class="text-sm font-bold text-indigo-700 mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Summary') }}
                                </h3>
                                <p class="text-slate-700 leading-relaxed">{{ $analysis->summary }}</p>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-4 bg-slate-50 rounded-xl text-center">
                                    <p class="text-2xl font-black text-indigo-600">{{ $analysis->complexity_level ?? '-' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Complexity Level') }}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl text-center">
                                    <p class="text-2xl font-black text-purple-600">{{ $analysis->estimated_effort_hours ?? '-' }}h</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Est. Effort') }}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl text-center">
                                    <p class="text-2xl font-black text-emerald-600">{{ $analysis->confidence_score ?? '-' }}%</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Confidence') }}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl text-center">
                                    <p class="text-sm font-bold {{ $analysis->validation_status === 'passed' ? 'text-green-600' : 'text-yellow-600' }}">{{ __(ucfirst($analysis->validation_status ?? 'pending')) }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Validation') }}</p>
                                </div>
                            </div>

                            @if($analysis->complexity_justification)
                            <div class="mt-6 p-4 bg-orange-50 rounded-xl border border-orange-200">
                                <h3 class="text-sm font-bold text-orange-700 mb-2">{{ __('Complexity Justification') }}</h3>
                                <p class="text-sm text-orange-900">{{ $analysis->complexity_justification }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Objectives Tab -->
                        <div x-show="activeTab === 'objectives'"   >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($analysis->objectives && count($analysis->objectives) > 0)
                                <div class="p-5 bg-green-50 rounded-xl border border-green-100">
                                    <h3 class="text-sm font-bold text-green-700 mb-4 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Objectives') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach($analysis->objectives as $obj)
                                        <li class="flex items-start gap-3 text-sm text-green-900">
                                            <span class="h-5 w-5 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="h-3 w-3 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            {{ $obj }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($analysis->success_criteria && count($analysis->success_criteria) > 0)
                                <div class="p-5 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <h3 class="text-sm font-bold text-emerald-700 mb-4 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Success Criteria') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach($analysis->success_criteria as $criteria)
                                        <li class="flex items-start gap-3 text-sm text-emerald-900">
                                            <span class="h-5 w-5 rounded-full bg-emerald-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="h-3 w-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            {{ $criteria }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                            @if($analysis->assumptions && count($analysis->assumptions) > 0)
                            <div class="mt-6 p-5 bg-blue-50 rounded-xl border border-blue-100">
                                <h3 class="text-sm font-bold text-blue-700 mb-4 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Assumptions') }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($analysis->assumptions as $assumption)
                                    <div class="flex items-start gap-2 text-sm text-blue-900">
                                        <span class="text-blue-500 mt-1">&#8226;</span>
                                        {{ $assumption }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Risks Tab -->
                        <div x-show="activeTab === 'risks'"   >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($analysis->constraints && count($analysis->constraints) > 0)
                                <div class="p-5 bg-red-50 rounded-xl border border-red-100">
                                    <h3 class="text-sm font-bold text-red-700 mb-4 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ __('Constraints') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach($analysis->constraints as $constraint)
                                        <li class="flex items-start gap-3 text-sm text-red-900">
                                            <span class="text-red-500 mt-1">&#8226;</span>
                                            {{ $constraint }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($analysis->missing_information && count($analysis->missing_information) > 0)
                                <div class="p-5 bg-yellow-50 rounded-xl border border-yellow-100">
                                    <h3 class="text-sm font-bold text-yellow-700 mb-4 flex items-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ __('Missing Information') }}
                                    </h3>
                                    <ul class="space-y-3">
                                        @foreach($analysis->missing_information as $info)
                                        <li class="flex items-start gap-3 text-sm text-yellow-900">
                                            <span class="text-yellow-500 mt-1">!</span>
                                            {{ $info }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                            @if($analysis->risk_assessment)
                            <div class="mt-6 p-5 bg-gray-50 rounded-xl border border-red-100">
                                <h3 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ __('Risk Assessment') }}
                                </h3>
                                <p class="text-sm text-red-900 leading-relaxed">{{ $analysis->risk_assessment }}</p>
                            </div>
                            @endif

                            @if($analysis->validation_errors && count($analysis->validation_errors) > 0)
                            <div class="mt-6 p-5 bg-red-100 rounded-xl border-2 border-red-200">
                                <h3 class="text-sm font-bold text-red-800 mb-2">{{ __('Validation Errors') }}</h3>
                                <ul class="space-y-2">
                                    @foreach($analysis->validation_errors as $error)
                                    <li class="text-sm text-red-700 flex items-start gap-2">
                                        <span class="text-red-600 mt-1">&#10007;</span>
                                        {{ $error }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <!-- Stakeholders Tab -->
                        <div x-show="activeTab === 'stakeholders'"   >
                            @if($analysis->stakeholders && count($analysis->stakeholders) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($analysis->stakeholders as $stakeholder)
                                <div class="p-4 bg-purple-50 rounded-xl border border-purple-100 flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-secondary-400 flex items-center justify-center flex-shrink-0">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-purple-900">{{ $stakeholder }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <p class="text-slate-500">{{ __('No stakeholders identified') }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Approach Tab -->
                        <div x-show="activeTab === 'approach'"   >
                            @if($analysis->recommended_approach)
                            <div class="p-5 bg-gray-50 rounded-xl border border-green-100">
                                <h3 class="text-sm font-bold text-green-700 mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    {{ __('Recommended Approach') }}
                                </h3>
                                <p class="text-sm text-green-900 leading-relaxed">{{ $analysis->recommended_approach }}</p>
                            </div>
                            @else
                            <div class="text-center py-8">
                                <p class="text-slate-500">{{ __('No recommended approach available') }}</p>
                            </div>
                            @endif

                            <!-- AI Model Info -->
                            @if($analysis->openai_model_used || $analysis->tokens_used)
                            <div class="mt-6 p-4 bg-slate-50 rounded-xl flex flex-wrap items-center gap-4 text-sm text-slate-500">
                                @if($analysis->openai_model_used)
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ __('Model') }}: <span class="font-mono text-slate-700">{{ $analysis->openai_model_used }}</span>
                                </span>
                                @endif
                                @if($analysis->tokens_used)
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ __('Tokens') }}: <span class="font-mono text-slate-700">{{ number_format($analysis->tokens_used) }}</span>
                                </span>
                                @endif
                                <span class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Analyzed') }}: {{ $analysis->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- No Analysis Yet -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-8 text-center hover-lift">
                    <div class="h-20 w-20 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No AI Analysis Yet') }}</h3>
                    <p class="text-slate-500 mb-4">{{ __('AI analysis has not been performed on this challenge yet.') }}</p>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Status') }}: {{ __(ucfirst(str_replace('_', ' ', $challenge->ai_analysis_status ?? 'pending'))) }}
                    </span>
                </div>
                @endif

                <!-- Challenge Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-6 bg-gray-50 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            {{ __('Challenge Description') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-slate-700">
                            <p class="whitespace-pre-wrap leading-relaxed">{{ $challenge->original_description }}</p>
                        </div>

                        @if($challenge->refined_brief)
                        <div class="mt-6 p-5 bg-gray-50 rounded-xl border border-indigo-100">
                            <h3 class="text-sm font-bold text-indigo-700 mb-3 flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                {{ __('AI Refined Brief') }}
                            </h3>
                            <p class="text-sm text-indigo-900 whitespace-pre-wrap leading-relaxed">{{ $challenge->refined_brief }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Workstreams & Tasks -->
                @if($challenge->workstreams->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-6 bg-secondary-500">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                {{ __('Workstreams & Tasks') }}
                            </h2>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white rounded-lg text-sm font-semibold">{{ $challenge->workstreams->count() }} {{ __('Workstreams') }}</span>
                                <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white rounded-lg text-sm font-semibold">{{ $totalTasks }} {{ __('Tasks') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($challenge->workstreams as $workstream)
                        @php
                            $wsTasks = $workstream->tasks;
                            $wsCompleted = $wsTasks->where('status', 'completed')->count();
                            $wsTotal = $wsTasks->count();
                            $wsProgress = $wsTotal > 0 ? round(($wsCompleted / $wsTotal) * 100) : 0;
                        @endphp
                        <div class="p-6" x-data="{ open: true }">
                            <div class="flex items-center justify-between cursor-pointer group" @click="open = !open">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition">{{ $workstream->title ?? $workstream->name }}</h3>
                                        <div class="flex items-center gap-3 text-sm text-slate-500 mt-1">
                                            <span>{{ $wsCompleted }}/{{ $wsTotal }} {{ __('tasks completed') }}</span>
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded font-bold text-xs">{{ $wsProgress }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-32 bg-slate-100 rounded-full h-2.5 hidden sm:block">
                                        <div class="bg-primary-500 h-2.5 rounded-full" style="width: {{ $wsProgress }}%"></div>
                                    </div>
                                    <svg class="h-5 w-5 text-slate-400" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @if($workstream->description)
                            <p class="text-sm text-slate-500 mt-3 ml-16">{{ $workstream->description }}</p>
                            @endif
                            <div x-show="open" x-collapse class="mt-4 space-y-3 ml-16">
                                @foreach($workstream->tasks as $task)
                                <div class="p-5 bg-gray-50 rounded-xl border border-slate-200 hover:border-indigo-200 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="font-bold text-slate-900">{{ $task->title }}</h4>
                                                @if($task->priority)
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                        'normal' => 'bg-blue-100 text-blue-600 border-blue-200',
                                                        'high' => 'bg-orange-100 text-orange-600 border-orange-200',
                                                        'urgent' => 'bg-red-100 text-red-600 border-red-200',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-full text-xs font-bold border {{ $priorityColors[$task->priority] ?? $priorityColors['normal'] }}">
                                                    {{ __(ucfirst($task->priority)) }}
                                                </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-slate-600 mt-2">{{ $task->description }}</p>

                                            <!-- Task Meta -->
                                            <div class="flex flex-wrap items-center gap-4 mt-3">
                                                @if($task->estimated_hours)
                                                <span class="flex items-center gap-1.5 text-xs text-slate-500 bg-white px-2 py-1 rounded-lg">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $task->estimated_hours }}h
                                                </span>
                                                @endif
                                                @if($task->complexity_score)
                                                <span class="flex items-center gap-1.5 text-xs text-slate-500 bg-white px-2 py-1 rounded-lg">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    {{ __('Complexity') }}: {{ $task->complexity_score }}
                                                </span>
                                                @endif
                                            </div>

                                            <!-- Required Skills -->
                                            @if($task->required_skills && count($task->required_skills) > 0)
                                            <div class="flex flex-wrap gap-1.5 mt-3">
                                                @foreach($task->required_skills as $skill)
                                                <span class="skill-badge px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-medium cursor-default">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold flex-shrink-0 {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600') }}">
                                            {{ __(ucfirst(str_replace('_', ' ', $task->status))) }}
                                        </span>
                                    </div>

                                    <!-- Assignments -->
                                    @if($task->assignments->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-slate-200">
                                        <span class="text-xs text-slate-500 font-medium">{{ __('Assigned to') }}:</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach($task->assignments as $assignment)
                                            <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg border border-slate-200 shadow-sm">
                                                <div class="h-6 w-6 rounded-full bg-secondary-400 flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">{{ substr($assignment->volunteer->user->name ?? 'V', 0, 1) }}</span>
                                                </div>
                                                <span class="text-xs font-medium text-slate-700">{{ $assignment->volunteer->user->name ?? 'Volunteer' }}</span>
                                                <span class="text-xs px-1.5 py-0.5 rounded {{ $assignment->invitation_status === 'accepted' ? 'bg-green-100 text-green-600' : ($assignment->invitation_status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-slate-100 text-slate-600') }}">
                                                    {{ __(ucfirst($assignment->invitation_status ?? 'pending')) }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Admin Activity Log -->
                @if($adminLogs->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-6 bg-gray-50 border-b border-slate-200">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Admin Activity Log') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($adminLogs as $log)
                            <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                                <div class="h-10 w-10 rounded-full bg-primary-400 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-sm font-bold">{{ substr($log->admin_name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm">
                                            <span class="font-bold text-slate-900">{{ $log->admin_name }}</span>
                                            <span class="text-slate-500">{{ $log->description }}</span>
                                        </p>
                                        <span class="px-2.5 py-1 bg-slate-200 text-slate-600 rounded-lg text-xs font-bold flex-shrink-0">{{ $log->action }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Submitter Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-4 bg-gray-50 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wide">{{ __('Submitter') }}</h3>
                    </div>
                    <div class="p-6">
                        @if($challenge->company)
                        <div class="flex items-center gap-4">
                            @if($challenge->company->logo_path)
                            <img src="{{ asset('storage/' . $challenge->company->logo_path) }}" alt="{{ $challenge->company->company_name }}" class="h-14 w-14 rounded-xl object-cover shadow-lg">
                            @else
                            <div class="h-14 w-14 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg">
                                <span class="text-white text-lg font-bold">{{ substr($challenge->company->company_name ?? 'CO', 0, 2) }}</span>
                            </div>
                            @endif
                            <div>
                                <a href="{{ route('admin.companies.show', $challenge->company) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition">
                                    {{ $challenge->company->company_name ?? $challenge->company->user->name }}
                                </a>
                                <p class="text-sm text-slate-500 flex items-center gap-1 mt-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ __('Company') }}
                                </p>
                            </div>
                        </div>
                        @elseif($challenge->volunteer)
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-full bg-secondary-500 flex items-center justify-center shadow-lg">
                                <span class="text-white text-lg font-bold">{{ substr($challenge->volunteer->user->name ?? 'V', 0, 1) }}</span>
                            </div>
                            <div>
                                <a href="{{ route('admin.volunteers.show', $challenge->volunteer) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition">
                                    {{ $challenge->volunteer->user->name }}
                                </a>
                                <p class="text-sm text-slate-500 flex items-center gap-1 mt-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('Volunteer') }}
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-full bg-gray-400 flex items-center justify-center">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ __('Community') }}</p>
                                <p class="text-sm text-slate-500">{{ __('Community Submission') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Challenge Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-4 bg-gray-50 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wide">{{ __('Details') }}</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-sm text-slate-500">{{ __('Type') }}</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ __(ucfirst(str_replace('_', ' ', $challenge->challenge_type ?? 'N/A'))) }}</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-sm text-slate-500">{{ __('Field') }}</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ $challenge->field ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-sm text-slate-500">{{ __('AI Status') }}</dt>
                                <dd class="text-sm font-semibold {{ $challenge->ai_analysis_status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $challenge->ai_analysis_status ?? 'pending'))) }}
                                </dd>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-sm text-slate-500">{{ __('Views') }}</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ number_format($challenge->view_count ?? 0) }}</dd>
                            </div>
                            @if($challenge->deadline)
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-sm text-slate-500">{{ __('Deadline') }}</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ $challenge->deadline->format('M d, Y') }}</dd>
                            </div>
                            @endif
                            @if($challenge->ai_analyzed_at)
                            <div class="flex justify-between items-center py-2">
                                <dt class="text-sm text-slate-500">{{ __('Analyzed At') }}</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ $challenge->ai_analyzed_at->format('M d, Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Teams -->
                @if($challenge->teams && $challenge->teams->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-4 bg-gray-50 border-b border-purple-100">
                        <h3 class="text-sm font-bold text-purple-700 uppercase tracking-wide flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('Teams') }}
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($challenge->teams as $team)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-slate-900">{{ $team->name }}</span>
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full font-bold">{{ $team->members->count() }} {{ __('members') }}</span>
                            </div>
                            <div class="flex -space-x-2">
                                @foreach($team->members->take(6) as $member)
                                <div class="h-8 w-8 rounded-full bg-secondary-400 flex items-center justify-center border-2 border-white shadow-sm" title="{{ $member->volunteer->user->name ?? 'Member' }}">
                                    <span class="text-white text-xs font-bold">{{ substr($member->volunteer->user->name ?? 'M', 0, 1) }}</span>
                                </div>
                                @endforeach
                                @if($team->members->count() > 6)
                                <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center border-2 border-white shadow-sm">
                                    <span class="text-slate-600 text-xs font-bold">+{{ $team->members->count() - 6 }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Attachments -->
                @if($challenge->attachments && $challenge->attachments->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden hover-lift">
                    <div class="p-4 bg-gray-50 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wide flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ __('Attachments') }}
                        </h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($challenge->attachments as $attachment)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100cursor-pointer">
                            <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-slate-700 truncate flex-1">{{ $attachment->original_name ?? $attachment->file_name ?? 'Attachment' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal"       class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal"       class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.challenges.destroy', $challenge) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 h-14 w-14 rounded-2xl bg-red-100 flex items-center justify-center">
                                <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900" id="modal-title">{{ __('Delete Challenge') }}</h3>
                                <p class="text-sm text-slate-500">{{ __('This action cannot be undone.') }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('Reason for deletion') }} <span class="text-red-500">*</span></label>
                            <textarea name="deletion_reason" rows="4" required minlength="10" maxlength="1000" class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 text-sm shadow-sm" placeholder="{{ __('Please explain why this challenge is being deleted. This message will be sent to the owner.') }}"></textarea>
                            <p class="text-xs text-slate-500 mt-2 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('The owner will be notified via notification with this reason.') }}
                            </p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <x-ui.button as="submit" variant="destructive">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('Delete & Notify Owner') }}
                        </x-ui.button>
                        <x-ui.button @click="showDeleteModal = false" variant="secondary">
                            {{ __('Cancel') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function challengeDetails() {
    return {
        showDeleteModal: false
    }
}

function countdown(deadline) {
    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        init() {
            this.updateCountdown();
            setInterval(() => this.updateCountdown(), 1000);
        },
        updateCountdown() {
            const now = new Date().getTime();
            const target = new Date(deadline).getTime();
            const diff = target - now;

            if (diff > 0) {
                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
            }
        }
    }
}
</script>
@endpush
@endsection
