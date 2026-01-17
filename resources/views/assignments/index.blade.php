@extends('layouts.app')

@section('title', __('My Tasks'))

@push('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce-in {
        0% { opacity: 0; transform: scale(0.3); }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 0 5px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.6), 0 0 30px rgba(99, 102, 241, 0.4); }
    }

    .animate-float { animation: float 6s-out infinite; }
    .animate-slide-up { animation: slide-up 0.6s forwards; }
    .animate-bounce-in { animation: bounce-in 0.6s forwards; }
    .animate-glow { animation: glow 2s-out infinite; }

    .shimmer-effect {
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    /* Premium Card Styles */
    .task-card-premium {
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .task-card-premium::before {
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

    .task-card-premium:hover::before {
        transform: scaleX(1);
    }

    .task-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .task-card-premium.invited { --card-gradient: var(--gradient-invited); }
    .task-card-premium.accepted { --card-gradient: var(--gradient-accepted); }
    .task-card-premium.in_progress { --card-gradient: var(--gradient-in-progress); }
    .task-card-premium.completed { --card-gradient: var(--gradient-completed); }
    .task-card-premium.declined { --card-gradient: var(--gradient-declined); }

    /* Stats Card Hover */
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-4px);
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-icon {
        transition: transform 0.3s ease;
    }

    /* Premium Tabs */
    .premium-tab {
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .premium-tab::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: currentColor;
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 3px;
    }

    .premium-tab:hover::before {
        width: 50%;
    }

    .premium-tab.active::before {
        width: 80%;
    }

    /* Match Score Ring */
    .match-ring {
        position: relative;
    }

    .match-ring svg {
        transform: rotate(-90deg);
    }

    .match-ring .ring-bg {
        stroke: #e5e7eb;
    }

    .match-ring .ring-progress {
        transition: stroke-dashoffset 1s ease;
    }

    /* Skill Pills */
    .skill-pill {
        transition: all 0.2s ease;
    }

    .skill-pill:hover {
        transform: translateY(-2px) scale(1.05);
    }

    /* Action Buttons */
    .btn-action {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-action:hover::before {
        left: 100%;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state-illustration {
        animation: float 4s-out infinite;
    }

    /* Progress Bar */
    .progress-bar {
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    /* Deadline Warning */
    .deadline-urgent {
        animation: glow 1s-out infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">

    @php
    $assignments = \App\Models\TaskAssignment::where('volunteer_id', auth()->user()->volunteer?->id)
        ->with(['task.challenge', 'task.workstream'])
        ->latest()
        ->get()
        ->groupBy('invitation_status');
    $counts = [
        'invited' => $assignments->get('invited', collect())->count(),
        'accepted' => $assignments->get('accepted', collect())->count(),
        'in_progress' => $assignments->get('in_progress', collect())->count(),
        'completed' => $assignments->get('completed', collect())->count(),
        'declined' => $assignments->get('declined', collect())->count(),
    ];
    $totalTasks = array_sum($counts);
    @endphp

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        <!-- Premium Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
            <!-- Invitations -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-lg shadow-amber-500/5 border-2 border-amber-100 hover:border-amber-300" onclick="switchTab('invited')">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @if($counts['invited'] > 0)
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ __('New') }}</span>
                    @endif
                </div>
                <div class="text-3xl font-black {{ $counts['invited'] > 0 ? 'text-amber-600' : 'text-slate-300' }}">{{ $counts['invited'] }}</div>
                <div class="text-sm text-slate-500 font-semibold">{{ __('Invitations') }}</div>
            </div>

            <!-- Accepted -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-lg shadow-blue-500/5 border-2 border-blue-100 hover:border-blue-300" onclick="switchTab('accepted')">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black {{ $counts['accepted'] > 0 ? 'text-blue-600' : 'text-slate-300' }}">{{ $counts['accepted'] }}</div>
                <div class="text-sm text-slate-500 font-semibold">{{ __('Accepted') }}</div>
            </div>

            <!-- In Progress -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-lg shadow-purple-500/5 border-2 border-purple-100 hover:border-purple-300" onclick="switchTab('in_progress')">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" style="animation-duration: 3s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    @if($counts['in_progress'] > 0)
                    <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">{{ __('Active') }}</span>
                    @endif
                </div>
                <div class="text-3xl font-black {{ $counts['in_progress'] > 0 ? 'text-purple-600' : 'text-slate-300' }}">{{ $counts['in_progress'] }}</div>
                <div class="text-sm text-slate-500 font-semibold">{{ __('In Progress') }}</div>
            </div>

            <!-- Completed -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-lg shadow-emerald-500/5 border-2 border-emerald-100 hover:border-emerald-300" onclick="switchTab('completed')">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black {{ $counts['completed'] > 0 ? 'text-emerald-600' : 'text-slate-300' }}">{{ $counts['completed'] }}</div>
                <div class="text-sm text-slate-500 font-semibold">{{ __('Completed') }}</div>
            </div>

            <!-- Declined -->
            <div class="stat-card bg-white rounded-2xl p-5 shadow-lg shadow-red-500/5 border-2 border-red-100 hover:border-red-300" onclick="switchTab('declined')">
                <div class="flex items-center justify-between mb-3">
                    <div class="stat-icon w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black {{ $counts['declined'] > 0 ? 'text-red-500' : 'text-slate-300' }}">{{ $counts['declined'] }}</div>
                <div class="text-sm text-slate-500 font-semibold">{{ __('Declined') }}</div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div x-data="{ activeTab: '{{ $counts['invited'] > 0 ? 'invited' : ($counts['in_progress'] > 0 ? 'in_progress' : 'invited') }}' }" class="animate-slide-up">
            <!-- Premium Tab Navigation -->
            <div class="bg-white rounded-[1.5rem] shadow-xl border border-slate-200/60 p-3 mb-8">
                <nav class="flex flex-wrap gap-2">
                    <button @click="activeTab = 'invited'" id="tab-invited"
                        :class="activeTab === 'invited' ? 'bg-secondary-300 text-slate-900 shadow-lg shadow-amber-500/30' : 'text-slate-600 hover:bg-amber-50'"
                        class="premium-tab flex-1 sm:flex-none whitespace-nowrap px-6 py-3.5 rounded-xl font-bold text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Invitations') }}
                            @if($counts['invited'] > 0)
                            <span :class="activeTab === 'invited' ? 'bg-white/25' : 'bg-amber-100 text-amber-700'" class="px-2 py-0.5 rounded-full text-xs font-bold">{{ $counts['invited'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'accepted'" id="tab-accepted"
                        :class="activeTab === 'accepted' ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50'"
                        class="premium-tab flex-1 sm:flex-none whitespace-nowrap px-6 py-3.5 rounded-xl font-bold text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Accepted') }}
                            @if($counts['accepted'] > 0)
                            <span :class="activeTab === 'accepted' ? 'bg-white/25' : 'bg-blue-100 text-blue-700'" class="px-2 py-0.5 rounded-full text-xs font-bold">{{ $counts['accepted'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'in_progress'" id="tab-in_progress"
                        :class="activeTab === 'in_progress' ? 'bg-white text-primary-500 border border-primary-200 shadow-lg shadow-purple-500/30' : 'text-slate-600 hover:bg-purple-50'"
                        class="premium-tab flex-1 sm:flex-none whitespace-nowrap px-6 py-3.5 rounded-xl font-bold text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('In Progress') }}
                            @if($counts['in_progress'] > 0)
                            <span :class="activeTab === 'in_progress' ? 'bg-white/25' : 'bg-purple-100 text-purple-700'" class="px-2 py-0.5 rounded-full text-xs font-bold">{{ $counts['in_progress'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'completed'" id="tab-completed"
                        :class="activeTab === 'completed' ? 'bg-white text-primary-500 border border-primary-200 shadow-lg shadow-emerald-500/30' : 'text-slate-600 hover:bg-emerald-50'"
                        class="premium-tab flex-1 sm:flex-none whitespace-nowrap px-6 py-3.5 rounded-xl font-bold text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Completed') }}
                            @if($counts['completed'] > 0)
                            <span :class="activeTab === 'completed' ? 'bg-white/25' : 'bg-emerald-100 text-emerald-700'" class="px-2 py-0.5 rounded-full text-xs font-bold">{{ $counts['completed'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'declined'" id="tab-declined"
                        :class="activeTab === 'declined' ? 'bg-white text-primary-500 border border-primary-200 shadow-lg shadow-red-500/30' : 'text-slate-600 hover:bg-red-50'"
                        class="premium-tab flex-1 sm:flex-none whitespace-nowrap px-6 py-3.5 rounded-xl font-bold text-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('Declined') }}
                            @if($counts['declined'] > 0)
                            <span :class="activeTab === 'declined' ? 'bg-white/25' : 'bg-red-100 text-red-600'" class="px-2 py-0.5 rounded-full text-xs font-bold">{{ $counts['declined'] }}</span>
                            @endif
                        </span>
                    </button>
                </nav>
            </div>

            <!-- INVITED TAB -->
            <div x-show="activeTab === 'invited'"    class="space-y-6">
                @forelse($assignments->get('invited', collect()) as $index => $assignment)
                <div class="task-card-premium invited bg-white rounded-[1.5rem] shadow-xl border-2 border-amber-100" style="animation-delay: {{ 0.1 * $index }}s; --card-gradient: var(--gradient-invited);">
                    <!-- Top Accent Bar -->
                    <div class="h-1.5 bg-secondary-300"></div>

                    <div class="p-8">
                        <!-- Header Row -->
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 mb-6">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <span class="px-4 py-1.5 bg-gray-100 text-amber-700 rounded-full text-xs font-black uppercase tracking-wider border border-amber-200">
                                        {{ __('New Invitation') }}
                                    </span>
                                    @if($assignment->task->deadline && $assignment->task->deadline->isPast())
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold flex items-center gap-1 deadline-urgent">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ __('Overdue') }}
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $assignment->task->title }}</h3>
                                <div class="flex items-center gap-3 text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ $assignment->task->challenge->title }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Match Score Ring -->
                            <div class="match-ring flex flex-col items-center">
                                <div class="relative w-24 h-24">
                                    <svg class="w-24 h-24" viewBox="0 0 100 100">
                                        <circle class="ring-bg" cx="50" cy="50" r="42" fill="none" stroke-width="8"/>
                                        <circle class="ring-progress" cx="50" cy="50" r="42" fill="none" stroke-width="8"
                                            stroke="{{ $assignment->match_score >= 80 ? '#10b981' : ($assignment->match_score >= 60 ? '#f59e0b' : '#6366f1') }}"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ 2 * 3.14159 * 42 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 42 * (1 - $assignment->match_score / 100) }}"/>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-2xl font-black {{ $assignment->match_score >= 80 ? 'text-emerald-600' : ($assignment->match_score >= 60 ? 'text-amber-600' : 'text-indigo-600') }}">{{ $assignment->match_score }}%</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-slate-500 mt-2">{{ __('Match Score') }}</span>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                            <!-- Task Description -->
                            <div class="lg:col-span-2 bg-gray-50 rounded-2xl p-6 border border-slate-200">
                                <h4 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Task Description') }}
                                </h4>
                                <p class="text-slate-600 leading-relaxed">{{ Str::limit($assignment->task->description, 300) }}</p>
                            </div>

                            <!-- Task Metrics -->
                            <div class="space-y-4">
                                <div class="bg-indigo-50 rounded-2xl p-5 border border-indigo-100">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-indigo-600 font-semibold">{{ __('Estimated Time') }}</p>
                                            <p class="text-xl font-black text-indigo-700">{{ $assignment->task->estimated_hours }}h</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-purple-50 rounded-2xl p-5 border border-purple-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-purple-600 font-semibold">{{ __('Complexity') }}</p>
                                            <p class="text-xl font-black text-purple-700">{{ $assignment->task->complexity_score ?? 5 }}/10</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Skills -->
                        @if($assignment->task->required_skills && count($assignment->task->required_skills) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ __('Required Skills') }}
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assignment->task->required_skills as $skill)
                                <span class="skill-pill px-4 py-2 bg-gray-50 text-indigo-700 border-2 border-indigo-200 rounded-xl text-sm font-semibold">
                                    {{ $skill }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Match Reasoning -->
                        @php $reasoning = json_decode($assignment->match_reasoning, true); @endphp
                        @if($reasoning)
                        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border-2 border-emerald-200">
                            <h4 class="font-bold text-emerald-800 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                {{ __('Why You Were Matched') }}
                            </h4>
                            @if(isset($reasoning['reasoning']))
                            <p class="text-slate-700 mb-4 leading-relaxed">{{ $reasoning['reasoning'] }}</p>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(isset($reasoning['strengths']) && count($reasoning['strengths']) > 0)
                                <div class="bg-white/70 rounded-xl p-4 border border-emerald-200">
                                    <p class="text-xs font-black text-emerald-700 mb-3 uppercase tracking-wider">{{ __('Your Strengths') }}</p>
                                    <ul class="space-y-2">
                                        @foreach($reasoning['strengths'] as $strength)
                                        <li class="flex items-start gap-2 text-sm text-slate-700">
                                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $strength }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if(isset($reasoning['gaps']) && count($reasoning['gaps']) > 0)
                                <div class="bg-white/70 rounded-xl p-4 border border-amber-200">
                                    <p class="text-xs font-black text-amber-700 mb-3 uppercase tracking-wider">{{ __('Growth Areas') }}</p>
                                    <ul class="space-y-2">
                                        @foreach($reasoning['gaps'] as $gap)
                                        <li class="flex items-start gap-2 text-sm text-slate-700">
                                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $gap }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Bar -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t-2 border-slate-100">
                            <a href="{{ route('tasks.show', $assignment->task->id) }}" class="text-indigo-600 hover:text-indigo-700 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View Full Details') }}
                            </a>
                            <div class="flex gap-3">
                                <form action="{{ route('assignments.decline', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <x-ui.button as="submit" variant="ghost" onclick="return confirm('{{ __('Are you sure you want to decline this invitation?') }}')">
                                        {{ __('Decline') }}
                                    </x-ui.button>
                                </form>
                                <form action="{{ route('assignments.accept', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <x-ui.button as="submit" variant="secondary">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Accept Invitation') }}
                                    </x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="bg-white rounded-[2rem] shadow-xl border-2 border-dashed border-amber-200 p-16 text-center">
                    <div class="empty-state-illustration w-32 h-32 mx-auto bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('No Pending Invitations') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">{{ __('When you\'re matched with tasks based on your skills and expertise, new invitations will appear here.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- ACCEPTED TAB -->
            <div x-show="activeTab === 'accepted'"    class="space-y-6">
                @forelse($assignments->get('accepted', collect()) as $assignment)
                <div class="task-card-premium accepted bg-white rounded-[1.5rem] shadow-xl border-2 border-blue-100" style="--card-gradient: var(--gradient-accepted);">
                    <div class="h-1.5 bg-primary-500"></div>
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div class="flex-1">
                                <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-black uppercase tracking-wider mb-3">{{ __('Ready to Start') }}</span>
                                <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $assignment->task->title }}</h3>
                                <p class="text-slate-600 mb-3">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Accepted') }} {{ $assignment->responded_at?->diffForHumans() ?? __('recently') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->task->estimated_hours }}h {{ __('estimated') }}
                                    </span>
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" variant="primary" size="lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Start Working') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] shadow-xl border-2 border-dashed border-blue-200 p-16 text-center">
                    <div class="empty-state-illustration w-32 h-32 mx-auto bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('No Accepted Tasks') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">{{ __('Accept task invitations to see them here and start working on them.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- IN PROGRESS TAB -->
            <div x-show="activeTab === 'in_progress'"    class="space-y-6">
                @forelse($assignments->get('in_progress', collect()) as $assignment)
                <div class="task-card-premium in_progress bg-white rounded-[1.5rem] shadow-xl border-2 border-purple-100" style="--card-gradient: var(--gradient-in-progress);">
                    <div class="h-1.5 bg-secondary-500"></div>
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-purple-100 text-purple-700 rounded-full text-xs font-black uppercase tracking-wider">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                        {{ __('In Progress') }}
                                    </span>
                                    @if($assignment->task->deadline)
                                    @php
                                        $daysLeft = now()->diffInDays($assignment->task->deadline, false);
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $daysLeft < 0 ? 'bg-red-100 text-red-700' : ($daysLeft <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        @if($daysLeft < 0)
                                            {{ abs($daysLeft) }} {{ __('days overdue') }}
                                        @else
                                            {{ $daysLeft }} {{ __('days left') }}
                                        @endif
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $assignment->task->title }}</h3>
                                <p class="text-slate-600 mb-3">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->task->estimated_hours }}h {{ __('estimated') }}
                                    </span>
                                    @if($assignment->task->deadline)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ __('Due:') }} {{ $assignment->task->deadline->translatedFormat('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" variant="secondary" size="lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                {{ __('Continue') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] shadow-xl border-2 border-dashed border-purple-200 p-16 text-center">
                    <div class="empty-state-illustration w-32 h-32 mx-auto bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('No Tasks In Progress') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">{{ __('Start working on accepted tasks to track your progress here.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- COMPLETED TAB -->
            <div x-show="activeTab === 'completed'"    class="space-y-6">
                @forelse($assignments->get('completed', collect()) as $assignment)
                <div class="task-card-premium completed bg-white rounded-[1.5rem] shadow-xl border-2 border-emerald-100" style="--card-gradient: var(--gradient-completed);">
                    <div class="h-1.5 bg-secondary-500"></div>
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            <div class="flex-1">
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black uppercase tracking-wider mb-3">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Completed') }}
                                </span>
                                <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $assignment->task->title }}</h3>
                                <p class="text-slate-600 mb-3">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span>{{ __('Completed') }} {{ $assignment->completed_at?->diffForHumans() }}</span>
                                    @if($assignment->actual_hours)
                                    <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->actual_hours }}h {{ __('logged') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" variant="ghost">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View Details') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] shadow-xl border-2 border-dashed border-emerald-200 p-16 text-center">
                    <div class="empty-state-illustration w-32 h-32 mx-auto bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('No Completed Tasks Yet') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">{{ __('Complete your first task to see your accomplishments here!') }}</p>
                </div>
                @endforelse
            </div>

            <!-- DECLINED TAB -->
            <div x-show="activeTab === 'declined'"    class="space-y-6">
                @forelse($assignments->get('declined', collect()) as $assignment)
                <div class="task-card-premium declined bg-white rounded-[1.5rem] shadow-sm border border-red-100 opacity-75" style="--card-gradient: var(--gradient-declined);">
                    <div class="h-1 bg-secondary-700"></div>
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex-1">
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold mb-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('Declined') }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-500 line-through mb-1">{{ $assignment->task->title }}</h3>
                                <p class="text-sm text-slate-400">{{ $assignment->task->challenge->title }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ __('Declined') }} {{ $assignment->responded_at?->diffForHumans() ?? __('recently') }}</p>
                            </div>
                            <p class="text-xs text-slate-400 max-w-[200px] text-right">{{ __('This task may be offered to other contributors') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] shadow-xl border-2 border-dashed border-red-200 p-16 text-center">
                    <div class="empty-state-illustration w-32 h-32 mx-auto bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">{{ __('No Declined Tasks') }}</h3>
                    <p class="text-slate-500 max-w-md mx-auto leading-relaxed">{{ __('You haven\'t declined any task invitations.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const tabButton = document.querySelector(`#tab-${tab}`);
    if (tabButton) {
        tabButton.click();
    }
}
</script>
@endsection
