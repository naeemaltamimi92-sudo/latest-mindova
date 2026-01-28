@extends('layouts.app')

@section('title', __('My Tasks'))

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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            <!-- Invitations -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-amber-300" onclick="switchTab('invited')">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @if($counts['invited'] > 0)
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold">{{ __('New') }}</span>
                    @endif
                </div>
                <div class="text-2xl font-bold {{ $counts['invited'] > 0 ? 'text-amber-600' : 'text-gray-300' }}">{{ $counts['invited'] }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ __('Invitations') }}</div>
            </div>

            <!-- Accepted -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-300" onclick="switchTab('accepted')">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold {{ $counts['accepted'] > 0 ? 'text-blue-600' : 'text-gray-300' }}">{{ $counts['accepted'] }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ __('Accepted') }}</div>
            </div>

            <!-- In Progress -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-violet-300" onclick="switchTab('in_progress')">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    @if($counts['in_progress'] > 0)
                    <span class="px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full text-[10px] font-bold">{{ __('Active') }}</span>
                    @endif
                </div>
                <div class="text-2xl font-bold {{ $counts['in_progress'] > 0 ? 'text-violet-600' : 'text-gray-300' }}">{{ $counts['in_progress'] }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ __('In Progress') }}</div>
            </div>

            <!-- Completed -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-emerald-300" onclick="switchTab('completed')">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold {{ $counts['completed'] > 0 ? 'text-emerald-600' : 'text-gray-300' }}">{{ $counts['completed'] }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ __('Completed') }}</div>
            </div>

            <!-- Declined -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-red-300" onclick="switchTab('declined')">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold {{ $counts['declined'] > 0 ? 'text-red-500' : 'text-gray-300' }}">{{ $counts['declined'] }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ __('Declined') }}</div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div x-data="{ activeTab: '{{ $counts['invited'] > 0 ? 'invited' : ($counts['in_progress'] > 0 ? 'in_progress' : 'invited') }}' }">
            <!-- Tab Navigation -->
            <div class="bg-white border border-gray-200 rounded-xl p-2 mb-6">
                <nav class="flex flex-wrap gap-1">
                    <button @click="activeTab = 'invited'" id="tab-invited"
                        :class="activeTab === 'invited' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                        class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2.5 rounded-lg font-medium text-sm border">
                        <span class="flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Invitations') }}
                            @if($counts['invited'] > 0)
                            <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $counts['invited'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'accepted'" id="tab-accepted"
                        :class="activeTab === 'accepted' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                        class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2.5 rounded-lg font-medium text-sm border">
                        <span class="flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Accepted') }}
                            @if($counts['accepted'] > 0)
                            <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $counts['accepted'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'in_progress'" id="tab-in_progress"
                        :class="activeTab === 'in_progress' ? 'bg-violet-100 text-violet-700 border-violet-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                        class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2.5 rounded-lg font-medium text-sm border">
                        <span class="flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('In Progress') }}
                            @if($counts['in_progress'] > 0)
                            <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $counts['in_progress'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'completed'" id="tab-completed"
                        :class="activeTab === 'completed' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                        class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2.5 rounded-lg font-medium text-sm border">
                        <span class="flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Completed') }}
                            @if($counts['completed'] > 0)
                            <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $counts['completed'] }}</span>
                            @endif
                        </span>
                    </button>
                    <button @click="activeTab = 'declined'" id="tab-declined"
                        :class="activeTab === 'declined' ? 'bg-red-100 text-red-700 border-red-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                        class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2.5 rounded-lg font-medium text-sm border">
                        <span class="flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('Declined') }}
                            @if($counts['declined'] > 0)
                            <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $counts['declined'] }}</span>
                            @endif
                        </span>
                    </button>
                </nav>
            </div>

            <!-- INVITED TAB -->
            <div x-show="activeTab === 'invited'" class="space-y-4">
                @forelse($assignments->get('invited', collect()) as $assignment)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <!-- Top Accent Bar -->
                    <div class="h-1 bg-amber-400"></div>

                    <div class="p-5">
                        <!-- Header Row -->
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold border border-amber-200">
                                        {{ __('New Invitation') }}
                                    </span>
                                    @if($assignment->task->deadline && $assignment->task->deadline->isPast())
                                    <span class="px-2 py-1 bg-red-50 text-red-700 rounded-lg text-xs font-semibold border border-red-200 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ __('Overdue') }}
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $assignment->task->title }}</h3>
                                <div class="flex items-center gap-2 text-gray-500 text-sm">
                                    <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ $assignment->task->challenge->title }}</span>
                                </div>
                            </div>

                            <!-- Match Score Ring -->
                            <div class="flex flex-col items-center">
                                <div class="relative w-16 h-16">
                                    <svg class="w-16 h-16" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                                        <circle cx="50" cy="50" r="42" fill="none" stroke-width="8"
                                            stroke="{{ $assignment->match_score >= 80 ? '#10b981' : ($assignment->match_score >= 60 ? '#f59e0b' : '#6366f1') }}"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ 2 * 3.14159 * 42 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 42 * (1 - $assignment->match_score / 100) }}"
                                            transform="rotate(-90 50 50)"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold {{ $assignment->match_score >= 80 ? 'text-emerald-600' : ($assignment->match_score >= 60 ? 'text-amber-600' : 'text-primary-600') }}">{{ (int)$assignment->match_score }}%</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-semibold text-gray-400 mt-1">{{ __('Match') }}</span>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                            <!-- Task Description -->
                            <div class="lg:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-2 flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Task Description') }}
                                </h4>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit($assignment->task->description, 200) }}</p>
                            </div>

                            <!-- Task Metrics -->
                            <div class="space-y-3">
                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-blue-600 font-semibold uppercase">{{ __('Estimated Time') }}</p>
                                            <p class="text-lg font-bold text-blue-700">{{ $assignment->task->estimated_hours }}h</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-violet-50 rounded-lg p-3 border border-violet-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-violet-600 font-semibold uppercase">{{ __('Complexity') }}</p>
                                            <p class="text-lg font-bold text-violet-700">{{ $assignment->task->complexity_score ?? 5 }}/10</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Skills -->
                        @if($assignment->task->required_skills && count($assignment->task->required_skills) > 0)
                        <div class="mb-4">
                            <h4 class="text-xs font-semibold text-gray-500 mb-2 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ __('Required Skills') }}
                            </h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($assignment->task->required_skills as $skill)
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-xs font-medium">
                                    {{ $skill }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Match Reasoning -->
                        @php $reasoning = json_decode($assignment->match_reasoning, true); @endphp
                        @if($reasoning)
                        <div class="bg-emerald-50 rounded-lg p-4 mb-4 border border-emerald-200">
                            <h4 class="font-semibold text-emerald-800 mb-3 flex items-center gap-2 text-sm">
                                <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                {{ __('Why You Were Matched') }}
                            </h4>
                            @if(isset($reasoning['reasoning']))
                            <p class="text-gray-700 mb-3 text-sm">{{ $reasoning['reasoning'] }}</p>
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @if(isset($reasoning['strengths']) && count($reasoning['strengths']) > 0)
                                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                                    <p class="text-[10px] font-semibold text-emerald-700 mb-2 uppercase tracking-wider">{{ __('Your Strengths') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($reasoning['strengths'] as $strength)
                                        <li class="flex items-start gap-1.5 text-xs text-gray-700">
                                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $strength }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if(isset($reasoning['gaps']) && count($reasoning['gaps']) > 0)
                                <div class="bg-white rounded-lg p-3 border border-amber-200">
                                    <p class="text-[10px] font-semibold text-amber-700 mb-2 uppercase tracking-wider">{{ __('Growth Areas') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($reasoning['gaps'] as $gap)
                                        <li class="flex items-start gap-1.5 text-xs text-gray-700">
                                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('tasks.show', $assignment->task->id) }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View Full Details') }}
                            </a>
                            <div class="flex gap-2">
                                <form action="{{ route('assignments.decline', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <x-ui.button as="submit" variant="ghost" size="sm" onclick="return confirm('{{ __('Are you sure you want to decline this invitation?') }}')">
                                        {{ __('Decline') }}
                                    </x-ui.button>
                                </form>
                                <form action="{{ route('assignments.accept', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <x-ui.button as="submit" size="sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Accept') }}
                                    </x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Pending Invitations') }}</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">{{ __('When you\'re matched with tasks based on your skills and expertise, new invitations will appear here.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- ACCEPTED TAB -->
            <div x-show="activeTab === 'accepted'" class="space-y-4">
                @forelse($assignments->get('accepted', collect()) as $assignment)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="h-1 bg-blue-500"></div>
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex-1">
                                <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-200 mb-2">{{ __('Ready to Start') }}</span>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $assignment->task->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Accepted') }} {{ $assignment->responded_at?->diffForHumans() ?? __('recently') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->task->estimated_hours }}h {{ __('estimated') }}
                                    </span>
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" size="sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Start Working') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Accepted Tasks') }}</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">{{ __('Accept task invitations to see them here and start working on them.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- IN PROGRESS TAB -->
            <div x-show="activeTab === 'in_progress'" class="space-y-4">
                @forelse($assignments->get('in_progress', collect()) as $assignment)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="h-1 bg-violet-500"></div>
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 rounded-lg text-xs font-semibold border border-violet-200">
                                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full"></span>
                                        {{ __('In Progress') }}
                                    </span>
                                    @if($assignment->task->deadline)
                                    @php
                                        $daysLeft = now()->diffInDays($assignment->task->deadline, false);
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold border {{ $daysLeft < 0 ? 'bg-red-50 text-red-700 border-red-200' : ($daysLeft <= 3 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-gray-50 text-gray-600 border-gray-200') }}">
                                        @if($daysLeft < 0)
                                            {{ abs($daysLeft) }} {{ __('days overdue') }}
                                        @else
                                            {{ $daysLeft }} {{ __('days left') }}
                                        @endif
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $assignment->task->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->task->estimated_hours }}h {{ __('estimated') }}
                                    </span>
                                    @if($assignment->task->deadline)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ __('Due:') }} {{ $assignment->task->deadline->translatedFormat('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" variant="secondary" size="sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                {{ __('Continue') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Tasks In Progress') }}</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">{{ __('Start working on accepted tasks to track your progress here.') }}</p>
                </div>
                @endforelse
            </div>

            <!-- COMPLETED TAB -->
            <div x-show="activeTab === 'completed'" class="space-y-4">
                @forelse($assignments->get('completed', collect()) as $assignment)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="h-1 bg-emerald-500"></div>
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex-1">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-200 mb-2">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('Completed') }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $assignment->task->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span>{{ __('Completed') }} {{ $assignment->completed_at?->diffForHumans() }}</span>
                                    @if($assignment->actual_hours)
                                    <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->actual_hours }}h {{ __('logged') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <x-ui.button as="a" href="{{ route('tasks.show', $assignment->task->id) }}" variant="ghost" size="sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View Details') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Completed Tasks Yet') }}</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">{{ __('Complete your first task to see your accomplishments here!') }}</p>
                </div>
                @endforelse
            </div>

            <!-- DECLINED TAB -->
            <div x-show="activeTab === 'declined'" class="space-y-4">
                @forelse($assignments->get('declined', collect()) as $assignment)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden opacity-75">
                    <div class="h-1 bg-gray-400"></div>
                    <div class="p-4">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                            <div class="flex-1">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold border border-red-200 mb-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('Declined') }}
                                </span>
                                <h3 class="text-base font-semibold text-gray-500 line-through mb-0.5">{{ $assignment->task->title }}</h3>
                                <p class="text-sm text-gray-400">{{ $assignment->task->challenge->title }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ __('Declined') }} {{ $assignment->responded_at?->diffForHumans() ?? __('recently') }}</p>
                            </div>
                            <p class="text-xs text-gray-400 max-w-[200px] text-right">{{ __('This task may be offered to other contributors') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Declined Tasks') }}</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">{{ __('You haven\'t declined any task invitations.') }}</p>
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
