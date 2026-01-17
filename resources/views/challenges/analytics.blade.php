@extends('layouts.app')

@section('title', $challenge->title . ' - ' . __('Analytics'))

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .progress-ring {
        transition: stroke-dashoffset 0.5s-out;
    }
    .timeline-item {
        animation: slideInLeft 0.5s forwards;
        opacity: 0;
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-count {
        animation: countUp 1s forwards;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Premium Header -->
    <div class="bg-primary-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('challenges.show', $challenge->id) }}" class="text-white/80 hover:text-whiteflex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back to Challenge') }}
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-3">
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ ucfirst($challenge->status) }}
                </span>
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                </span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $challenge->title }}</h1>
            <p class="text-white/80 text-lg">{{ __('Analytics & Insights Dashboard') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 -mt-12 mb-10 relative z-10">
            <!-- Completion Rate -->
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-slate-900">{{ $analytics['completion_rate'] }}%</p>
                        <p class="text-xs text-slate-500">{{ __('Completion') }}</p>
                    </div>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="h-full bg-secondary-500 rounded-full0" style="width: {{ $analytics['completion_rate'] }}%"></div>
                </div>
            </div>

            <!-- Active Contributors -->
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-slate-900">{{ $analytics['active_contributors'] }}</p>
                        <p class="text-xs text-slate-500">{{ __('Contributors') }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600">{{ __('Active volunteers working') }}</p>
            </div>

            <!-- Total Hours -->
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-slate-900">{{ $analytics['total_hours'] }}</p>
                        <p class="text-xs text-slate-500">{{ __('Hours') }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600">{{ __('Total time contributed') }}</p>
            </div>

            <!-- Response Time -->
            <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-secondary-300 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black text-slate-900">{{ $analytics['avg_response_time'] }}h</p>
                        <p class="text-xs text-slate-500">{{ __('Avg Response') }}</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600">{{ __('Hours to accept tasks') }}</p>
            </div>
        </div>

        @if($challenge->challenge_type === 'team_execution')
        <!-- Task Progress Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="bg-primary-500 px-6 py-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Task Progress by Workstream') }}
                </h2>
            </div>
            <div class="p-6">
                @forelse($challenge->workstreams as $workstream)
                <div class="mb-8 last:mb-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-primary-500"></div>
                            {{ $workstream->title }}
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold">
                                {{ $workstream->tasks->where('status', 'completed')->count() }}/{{ $workstream->tasks->count() }} {{ __('completed') }}
                            </span>
                        </div>
                    </div>

                    <div class="grid gap-3">
                        @foreach($workstream->tasks as $task)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-indigo-200 hover:shadow-sm">
                            <div class="flex-1">
                                <div class="font-semibold text-slate-900">{{ $task->title }}</div>
                                <div class="flex items-center gap-4 mt-1 text-sm text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7z"/>
                                        </svg>
                                        {{ __('Complexity') }}: {{ $task->complexity_score }}/10
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $task->estimated_hours }}h {{ __('estimated') }}
                                    </span>
                                </div>
                            </div>
                            <span class="px-4 py-2 text-sm font-bold rounded-lg
                                {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $task->status === 'assigned' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $task->status === 'open' ? 'bg-slate-100 text-slate-600' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Workstream Progress Bar -->
                    <div class="mt-4">
                        @php
                            $progress = $workstream->tasks->count() > 0
                                ? round(($workstream->tasks->where('status', 'completed')->count() / $workstream->tasks->count()) * 100)
                                : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="h-full bg-secondary-500 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-700 w-12 text-right">{{ $progress }}%</span>
                        </div>
                    </div>
                </div>

                @if(!$loop->last)
                <div class="border-t border-slate-200 my-6"></div>
                @endif
                @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-slate-500">{{ __('No workstreams created yet') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Contributor & Status Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Contributors -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-secondary-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ __('Top Contributors') }}
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($analytics['top_contributors'] as $index => $contributor)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($contributor['name'], 0, 1)) }}
                                </div>
                                @if($index < 3)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center text-xs font-bold text-amber-900">
                                    {{ $index + 1 }}
                                </div>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('volunteers.show', $contributor['volunteer_id']) }}" class="font-semibold text-slate-900 hover:text-violet-600 transition">
                                    {{ $contributor['name'] }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $contributor['completed_tasks'] }} {{ __('tasks completed') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-slate-900">{{ $contributor['hours'] }}h</p>
                            <p class="text-xs text-slate-500">{{ __('logged') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500">{{ __('No contributors yet') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Task Status Distribution -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-primary-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        {{ __('Task Status Distribution') }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($analytics['task_status_distribution'] as $status => $count)
                        @php
                            $percentage = $analytics['total_tasks'] > 0 ? round(($count / $analytics['total_tasks']) * 100) : 0;
                            $colors = [
                                'completed' => 'bg-secondary-500',
                                'in_progress' => 'bg-secondary-300',
                                'assigned' => 'bg-primary-500',
                                'open' => 'bg-gray-400',
                            ];
                            $color = $colors[$status] ?? 'bg-gray-400';
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-slate-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                <span class="text-sm font-bold text-slate-900">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="h-full {{ $color }} rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ __('Total Tasks') }}</span>
                            <span class="text-2xl font-black text-slate-900">{{ $analytics['total_tasks'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Community Ideas Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Ideas -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-secondary-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                        </svg>
                        {{ __('Top Rated Ideas') }}
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($analytics['top_ideas'] as $index => $idea)
                    <a href="{{ route('ideas.show', $idea->id) }}" class="block p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-emerald-300 hover:shadow-md">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($index === 0)
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded">{{ __('TOP') }}</span>
                                    @endif
                                    <h4 class="font-semibold text-slate-900">{{ $idea->title ?? Str::limit($idea->content, 50) }}</h4>
                                </div>
                                <p class="text-sm text-slate-500">{{ __('by') }} {{ $idea->volunteer->user->name }}</p>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-black text-emerald-600">{{ round($idea->final_score ?? $idea->ai_quality_score ?? 0) }}</div>
                                <div class="text-xs text-slate-500">{{ __('score') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="flex items-center gap-1 text-indigo-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 14a1 1 0 112 0 1 1 0 01-2 0zm1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                AI: {{ round($idea->ai_score ?? $idea->ai_quality_score ?? 0) }}
                            </span>
                            <span class="flex items-center gap-1 {{ ($idea->community_votes ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                </svg>
                                {{ ($idea->community_votes ?? 0) >= 0 ? '+' : '' }}{{ $idea->community_votes ?? 0 }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500">{{ __('No ideas submitted yet') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Idea Statistics -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-secondary-500 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        {{ __('Idea Statistics') }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="text-center p-4 bg-slate-50 rounded-xl">
                            <p class="text-3xl font-black text-slate-900">{{ $analytics['total_ideas'] }}</p>
                            <p class="text-sm text-slate-500">{{ __('Total Ideas') }}</p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-xl">
                            <p class="text-3xl font-black text-indigo-600">{{ round($analytics['avg_ai_score'], 1) }}</p>
                            <p class="text-sm text-slate-500">{{ __('Avg AI Score') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-slate-600">{{ __('Average AI Score') }}</span>
                                <span class="font-bold text-slate-900">{{ round($analytics['avg_ai_score'], 1) }}/10</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="h-full bg-primary-400 rounded-full" style="width: {{ $analytics['avg_ai_score'] * 10 }}%"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-slate-600">{{ __('Total Community Votes') }}</span>
                                <span class="font-bold text-slate-900">{{ $analytics['total_votes'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('Participation Rate') }}</span>
                                <span class="font-bold text-emerald-600">{{ $analytics['participation_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Timeline Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-primary-500 px-6 py-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Challenge Timeline') }}
                </h2>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-primary-500"></div>
                    <div class="space-y-6">
                        @forelse($analytics['timeline'] as $index => $event)
                        <div class="timeline-item relative flex items-start pl-14" style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="absolute left-0 w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white shadow-lg">
                                @if($event['type'] === 'created')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                </svg>
                                @elseif($event['type'] === 'completed')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                @else
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </div>
                            <div class="flex-1 bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-slate-900">{{ $event['title'] }}</h4>
                                        <p class="text-sm text-slate-600 mt-1">{{ $event['description'] }}</p>
                                    </div>
                                    <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">{{ $event['date'] }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <p class="text-slate-500">{{ __('No timeline events yet') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
