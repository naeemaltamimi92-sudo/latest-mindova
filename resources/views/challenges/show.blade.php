@extends('layouts.app')

@php
    $canSeeSpam = $canSeeSpam ?? (auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isCompany() && auth()->user()->company?->id === $challenge->company_id)));
@endphp

@section('title', $challenge->title)

@push('styles')
<style>
    .fb-page { background: var(--color-background); min-height: 100vh; }
    .fb-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
    }
    .fb-header {
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border);
    }
    .fb-text-primary   { color: var(--color-text-primary); }
    .fb-text-secondary { color: var(--color-text-secondary); }
    .fb-text-accent    { color: var(--color-primary-500); }
    .fb-bg-accent      { background: var(--color-primary-500); }
    .fb-bg-accent-soft { background: var(--color-primary-50); }
    .fb-border-accent  { border-color: var(--color-primary-500); }

    /* Tab bar */
    .fb-tabs { border-bottom: 1px solid var(--color-border); background: var(--color-surface); }
    .fb-tab {
        padding: 14px 20px;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--color-text-secondary);
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: color 0.15s, border-color 0.15s;
        user-select: none;
        background: none;
        border-top: none; border-left: none; border-right: none;
    }
    .fb-tab:hover  { color: var(--color-text-primary); }
    .fb-tab.active { color: var(--color-primary-500); border-bottom-color: var(--color-primary-500); }

    /* Badge */
    .fb-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 600;
    }

    /* Stat card */
    .fb-stat { text-align: center; padding: 14px 8px; }
    .fb-stat-value { font-size: 1.5rem; font-weight: 700; color: var(--color-text-primary); line-height: 1; }
    .fb-stat-label { font-size: 0.75rem; color: var(--color-text-secondary); margin-top: 4px; }

    /* Skill tag */
    .fb-skill {
        display: inline-block;
        padding: 4px 12px; border-radius: 20px;
        background: var(--color-background); color: var(--color-text-primary);
        font-size: 0.8125rem; font-weight: 500;
        border: 1px solid var(--color-border);
    }

    /* Sidebar info row */
    .fb-info-row { display: flex; flex-direction: column; gap: 2px; padding: 12px 0; border-bottom: 1px solid var(--color-border); }
    .fb-info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .fb-info-row dt { font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-secondary); }
    .fb-info-row dd { font-size: 0.9375rem; font-weight: 600; color: var(--color-text-primary); }

    /* Task item */
    .fb-task-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px; border-radius: 8px;
        border: 1px solid var(--color-border); background: var(--color-surface-secondary);
        transition: border-color 0.15s;
    }
    .fb-task-item:hover { border-color: var(--color-primary-200); background: var(--color-surface); }

    /* Idea card */
    .fb-idea-card {
        background: var(--color-surface); border: 1px solid var(--color-border);
        border-radius: 12px; padding: 16px;
        box-shadow: var(--shadow-xs);
        transition: box-shadow 0.15s;
    }
    .fb-idea-card:hover { box-shadow: var(--shadow-md); }
    .fb-idea-card.fb-idea-spam { border-color: var(--color-danger-300); background: var(--color-danger-50); }
    .dark .fb-idea-card.fb-idea-spam { border-color: rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); }
    .fb-card.fb-card-success { border-color: var(--color-success-300); background: var(--color-success-50); }
    .dark .fb-card.fb-card-success { border-color: rgba(16,185,129,0.35); background: rgba(16,185,129,0.08); }

    /* Deadline box */
    .fb-deadline-block {
        background: var(--color-background); border: 1px solid var(--color-border);
        border-radius: 12px; padding: 16px; text-align: center;
    }
    .fb-countdown-digit {
        background: var(--color-text-primary); color: var(--color-surface);
        border-radius: 8px; padding: 8px 14px;
        font-size: 1.25rem; font-weight: 700; line-height: 1;
    }
    .fb-countdown-label { font-size: 0.625rem; color: var(--color-text-light); margin-top: 4px; }

    /* Primary button */
    .fb-btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 10px 20px; background: var(--color-primary-500); color: #FFFFFF;
        border-radius: 8px; font-size: 0.9375rem; font-weight: 600;
        border: none; cursor: pointer;
        transition: background 0.15s, box-shadow 0.15s;
    }
    .fb-btn-primary:hover { background: var(--color-primary-600); box-shadow: var(--shadow-glow-primary-sm); }

    .fb-btn-ghost {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: var(--color-primary-50); color: var(--color-primary-500);
        border-radius: 8px; font-size: 0.875rem; font-weight: 600;
        transition: background 0.15s;
    }
    .fb-btn-ghost:hover { background: var(--color-primary-100); }

    /* Company avatar */
    .fb-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--gradient-aurora);
        display: flex; align-items: center; justify-content: center;
        color: #FFFFFF; font-size: 1.125rem; font-weight: 700; flex-shrink: 0;
    }
    .fb-avatar-sm {
        width: 20px; height: 20px; border-radius: 50%;
        background: var(--color-border-medium);
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--color-text-secondary); font-size: 0.625rem; font-weight: 700; flex-shrink: 0;
    }

    /* Progress bar */
    .fb-progress-track { height: 6px; background: var(--color-border-medium); border-radius: 99px; overflow: hidden; }
    .fb-progress-fill  { height: 100%; background: var(--color-primary-500); border-radius: 99px; }

    /* Attachment */
    .fb-attachment {
        display: flex; align-items: center; gap: 12px;
        padding: 12px; border-radius: 8px;
        border: 1px solid var(--color-border); background: var(--color-surface-secondary);
        transition: border-color 0.15s;
        text-decoration: none;
    }
    .fb-attachment:hover { border-color: var(--color-primary-200); background: var(--color-primary-50); }

    /* Workstream header */
    .fb-workstream-num {
        width: 24px; height: 24px; border-radius: 6px;
        background: var(--color-primary-50); color: var(--color-primary-500);
        display: inline-flex; align-items: center; justify-center: center;
        font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
        align-items: center; justify-content: center;
    }
</style>
@endpush

@section('content')
<div x-data="{ activeTab: 'overview' }" class="fb-page">

    {{-- ── PAGE HEADER ── --}}
    <div class="fb-header">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-5">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm fb-text-secondary mb-4">
                <a href="{{ route('challenges.index') }}" class="hover:fb-text-primary fb-text-secondary">{{ __('Challenges') }}</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="truncate fb-text-primary">{{ Str::limit($challenge->title, 50) }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                <div class="flex-1">
                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        @php
                            $statusMap = [
                                'draft'       => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                                'submitted'   => 'bg-primary-50 dark:bg-primary-500/15 text-primary-600 dark:text-primary-300 border-primary-100 dark:border-primary-500/30',
                                'analyzing'   => 'bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                                'active'      => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                'in_progress' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                'completed'   => 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800',
                                'delivered'   => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                'rejected'    => 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                            ];
                            $sc = $statusMap[$challenge->status] ?? $statusMap['draft'];
                        @endphp
                        <span class="fb-badge border {{ $sc }}">
                            {{ __(ucfirst(str_replace('_', ' ', $challenge->status))) }}
                        </span>
                        @if($challenge->challenge_type)
                        <span class="fb-badge border bg-primary-50 dark:bg-primary-500/15 text-primary-600 dark:text-primary-300 border-primary-100 dark:border-primary-500/30">
                            @if($challenge->challenge_type === 'team_execution')
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/></svg>
                            @else
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            @endif
                            {{ __(ucfirst(str_replace('_', ' ', $challenge->challenge_type))) }}
                        </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="text-2xl sm:text-3xl font-bold mb-3 fb-text-primary" style="line-height:1.25;">{{ $challenge->title }}</h1>

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm fb-text-secondary">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                            {{ $challenge->company->company_name }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            {{ $challenge->created_at->translatedFormat('M d, Y') }}
                        </span>
                        @if($challenge->field)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $challenge->field }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Deadline --}}
                @if($challenge->deadline && $challenge->deadline->isFuture())
                @php $diff = now()->diff($challenge->deadline); @endphp
                <div class="fb-deadline-block" style="min-width:160px;">
                    <p class="text-xs font-bold uppercase tracking-widest mb-3 fb-text-secondary">{{ __('Deadline') }}</p>
                    <div class="flex items-start justify-center gap-2">
                        <div>
                            <div class="fb-countdown-digit">{{ $diff->days }}</div>
                            <p class="fb-countdown-label">{{ __('Days') }}</p>
                        </div>
                        <span class="font-bold mt-2 fb-text-secondary">:</span>
                        <div>
                            <div class="fb-countdown-digit">{{ $diff->h }}</div>
                            <p class="fb-countdown-label">{{ __('Hours') }}</p>
                        </div>
                    </div>
                    <p class="text-xs mt-3 fb-text-secondary">{{ $challenge->deadline->translatedFormat('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tab Bar --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex items-center gap-1 fb-tabs -mb-px" style="border-bottom:none;">
                <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'active' : ''"
                        class="fb-tab">{{ __('Overview') }}</button>

                @if($challenge->challenge_type === 'team_execution')
                <button @click="activeTab = 'tasks'"
                        :class="activeTab === 'tasks' ? 'active' : ''"
                        class="fb-tab">{{ __('Tasks') }}</button>
                @else
                <button @click="activeTab = 'ideas'"
                        :class="activeTab === 'ideas' ? 'active' : ''"
                        class="fb-tab">{{ __('Ideas') }}</button>
                @endif

                @if($challenge->teams->count() > 0)
                <button @click="activeTab = 'teams'"
                        :class="activeTab === 'teams' ? 'active' : ''"
                        class="fb-tab">{{ __('Teams') }}</button>
                @endif

                @if($latestAnalysis)
                <button @click="activeTab = 'analysis'"
                        :class="activeTab === 'analysis' ? 'active' : ''"
                        class="fb-tab">{{ __('AI Analysis') }}</button>
                @endif
            </div>
            <div class="h-px bg-gray-200 dark:bg-gray-800"></div>
        </div>
    </div>

    {{-- ── STATS BAR ── --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-5">
        <div class="fb-card">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-200 dark:divide-gray-800">
                @if($challenge->challenge_type === 'team_execution')
                @php $progress = $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0; @endphp
                <div class="fb-stat">
                    <div class="fb-stat-value fb-text-accent">{{ $progress }}%</div>
                    <div class="fb-stat-label">{{ __('Progress') }}</div>
                    <div class="fb-progress-track mt-2 mx-4">
                        <div class="fb-progress-fill" style="width:{{ $progress }}%;"></div>
                    </div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $stats['completed_tasks'] }}<span class="text-base fb-text-secondary">/{{ $stats['total_tasks'] }}</span></div>
                    <div class="fb-stat-label">{{ __('Tasks Done') }}</div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $stats['total_hours'] }}<span class="text-base fb-text-secondary">h</span></div>
                    <div class="fb-stat-label">{{ __('Est. Hours') }}</div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $stats['active_volunteers'] }}</div>
                    <div class="fb-stat-label">{{ __('Contributors') }}</div>
                </div>
                @else
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $stats['total_ideas'] }}</div>
                    <div class="fb-stat-label">{{ __('Ideas') }}</div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value fb-text-accent">{{ round($challenge->ideas->max('final_score') ?? 0) }}</div>
                    <div class="fb-stat-label">{{ __('Top Score') }}</div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $challenge->ideas->sum('community_votes') }}</div>
                    <div class="fb-stat-label">{{ __('Votes') }}</div>
                </div>
                <div class="fb-stat">
                    <div class="fb-stat-value">{{ $challenge->ideas->pluck('volunteer_id')->unique()->count() }}</div>
                    <div class="fb-stat-label">{{ __('Contributors') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── MAIN LAYOUT ── --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── LEFT / MAIN COLUMN ── --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- OVERVIEW TAB --}}
                <div x-show="activeTab === 'overview'">
                    {{-- Description --}}
                    <div class="fb-card p-5 mb-4">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 fb-text-primary">
                            <svg class="w-5 h-5 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('About this Challenge') }}
                        </h2>
                        <p class="text-sm leading-relaxed whitespace-pre-line fb-text-primary">{{ $challenge->refined_brief ?? $challenge->original_description }}</p>
                    </div>

                    {{-- Required Skills --}}
                    @if($requiredSkills->count() > 0)
                    <div class="fb-card p-5 mb-4">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 fb-text-primary">
                            <svg class="w-5 h-5 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            {{ __('Skills Needed') }}
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($requiredSkills as $skill)
                            <span class="fb-skill">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Attachments --}}
                    @if($challenge->attachments->count() > 0)
                    <div class="fb-card p-5">
                        <h2 class="text-base font-bold mb-3 flex items-center gap-2 fb-text-primary">
                            <svg class="w-5 h-5 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ __('Attachments') }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($challenge->attachments as $attachment)
                            @php
                                $ext = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                                $extColor = match($ext) {
                                    'pdf' => 'text-red-600 dark:text-red-400', 'doc','docx' => 'text-blue-600 dark:text-blue-400',
                                    'xls','xlsx' => 'text-green-600 dark:text-green-400', 'jpg','jpeg','png','gif' => 'text-violet-600 dark:text-violet-400',
                                    default => 'fb-text-secondary'
                                };
                            @endphp
                            <a href="{{ route('challenges.attachments.download', [$challenge, $attachment]) }}" class="fb-attachment">
                                <svg class="w-8 h-8 flex-shrink-0 {{ $extColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold truncate fb-text-primary">{{ $attachment->filename }}</p>
                                    <p class="text-xs fb-text-secondary">{{ strtoupper($ext) }}</p>
                                </div>
                                <svg class="w-4 h-4 flex-shrink-0 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- TASKS TAB --}}
                @if($challenge->challenge_type === 'team_execution')
                <div x-show="activeTab === 'tasks'" x-cloak>
                    @forelse($challenge->workstreams as $index => $workstream)
                    <div class="fb-card p-5 mb-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-3">
                                <span class="fb-workstream-num">{{ $index + 1 }}</span>
                                <div>
                                    <h3 class="font-bold fb-text-primary">{{ $workstream->title }}</h3>
                                    <p class="text-sm mt-0.5 fb-text-secondary">{{ $workstream->description }}</p>
                                </div>
                            </div>
                            <span class="fb-badge border bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700">
                                {{ $workstream->tasks->count() }} {{ __('tasks') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            @foreach($workstream->tasks as $task)
                            @php
                                $ts = [
                                    'open'        => ['chip' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400',  'icon' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400'],
                                    'assigned'    => ['chip' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',       'icon' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'],
                                    'in_progress' => ['chip' => 'bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300', 'icon' => 'bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300'],
                                    'completed'   => ['chip' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300', 'icon' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'],
                                ];
                                $tc = $ts[$task->status] ?? $ts['open'];
                            @endphp
                            <div class="fb-task-item">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $tc['icon'] }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold fb-text-primary">{{ $task->title }}</h4>
                                    <div class="flex flex-wrap items-center gap-3 mt-1 text-xs fb-text-secondary">
                                        <span>{{ $task->estimated_hours }}h</span>
                                        <span>{{ __('Complexity') }}: {{ $task->complexity_score }}/10</span>
                                        <span class="fb-badge {{ $tc['chip'] }}" style="padding:2px 8px;">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center bg-primary-50 dark:bg-primary-500/15 text-primary-600 dark:text-primary-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="fb-card p-12 text-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 bg-gray-100 dark:bg-gray-800">
                            <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="font-semibold fb-text-primary">{{ __('No tasks yet') }}</p>
                        <p class="text-sm mt-1 fb-text-secondary">{{ __('Tasks will appear once AI analysis is complete.') }}</p>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- IDEAS TAB --}}
                @if($challenge->challenge_type === 'community_discussion')
                <div x-show="activeTab === 'ideas'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-base fb-text-primary">{{ __('Community Ideas') }}</h2>
                        @if(auth()->user() && auth()->user()->isVolunteer())
                        <a href="{{ route('ideas.create', $challenge) }}" class="fb-btn-primary" style="font-size:0.875rem;padding:8px 16px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Submit Idea') }}
                        </a>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @forelse($challenge->ideas->sortByDesc('final_score') as $idea)
                        <div class="fb-idea-card {{ $canSeeSpam && $idea->is_spam ? 'relative fb-idea-spam' : '' }}">
                            @if($canSeeSpam && $idea->is_spam)
                            <span class="absolute -top-2 -left-2 px-2 py-0.5 text-white text-xs font-bold rounded-full bg-red-600">{{ __('Spam') }}</span>
                            @endif
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $canSeeSpam && $idea->is_spam ? 'bg-red-100 dark:bg-red-900/30' : 'bg-primary-50 dark:bg-primary-500/15' }}">
                                    <svg class="w-5 h-5 {{ $canSeeSpam && $idea->is_spam ? 'text-red-600 dark:text-red-400' : 'fb-text-accent' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-sm mb-1 fb-text-primary">{{ $idea->title }}</h3>
                                    <p class="text-sm mb-2 fb-text-secondary">{{ Str::limit($idea->description, 120) }}</p>
                                    <div class="flex flex-wrap items-center gap-4 text-xs">
                                        <span class="flex items-center gap-1 font-semibold fb-text-accent">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            AI: {{ round($idea->ai_score) }}
                                        </span>
                                        <span class="flex items-center gap-1 fb-text-secondary">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/></svg>
                                            {{ $idea->community_votes }} {{ __('votes') }}
                                        </span>
                                        <span class="flex items-center gap-1 font-semibold text-emerald-700 dark:text-emerald-400">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            {{ __('Final') }}: {{ round($idea->final_score) }}
                                        </span>
                                        @if($idea->volunteer && $idea->volunteer->user)
                                        <span class="flex items-center gap-1.5 fb-text-secondary">
                                            <span class="fb-avatar-sm">{{ strtoupper(substr($idea->volunteer->user->name, 0, 1)) }}</span>
                                            {{ $idea->volunteer->user->name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('ideas.show', $idea) }}" class="fb-btn-ghost flex-shrink-0" style="padding:6px 14px;font-size:0.8125rem;">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="fb-card p-12 text-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 bg-gray-100 dark:bg-gray-800">
                                <svg class="w-6 h-6 fb-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <p class="font-semibold fb-text-primary">{{ __('No ideas submitted yet') }}</p>
                            <p class="text-sm mt-1 fb-text-secondary">{{ __('Be the first to share your idea!') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                {{-- TEAMS TAB --}}
                @if($challenge->teams->count() > 0)
                <div x-show="activeTab === 'teams'" x-cloak>
                    <div class="space-y-3">
                        @foreach($challenge->teams as $team)
                        <div class="fb-card p-4">
                            <div class="flex items-center gap-4">
                                <div class="fb-avatar" style="border-radius:10px;width:44px;height:44px;font-size:1rem;">
                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                        <h3 class="font-bold text-sm fb-text-primary">{{ $team->name }}</h3>
                                        @php
                                            $teamStatus = [
                                                'active'    => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                                'forming'   => 'bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                                                'completed' => 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800',
                                            ];
                                            $tsc = $teamStatus[$team->status] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700';
                                        @endphp
                                        <span class="fb-badge border {{ $tsc }}">
                                            {{ __(ucfirst($team->status ?? 'forming')) }}
                                        </span>
                                    </div>
                                    @if($team->leader && $team->leader->user)
                                    <p class="text-xs fb-text-secondary">
                                        {{ __('Led by') }} {{ $team->leader->user->name }} &bull; {{ $team->members->count() + 1 }} {{ __('members') }}
                                    </p>
                                    @endif
                                </div>
                                <a href="{{ route('teams.show', $team) }}" class="fb-btn-ghost" style="padding:7px 14px;font-size:0.8125rem;">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- AI ANALYSIS TAB --}}
                @if($latestAnalysis)
                <div x-show="activeTab === 'analysis'" x-cloak>
                    @if($latestAnalysis->summary)
                    <div class="fb-card p-5 mb-4">
                        <h3 class="font-bold mb-3 fb-text-primary">{{ __('AI Summary') }}</h3>
                        <p class="text-sm leading-relaxed fb-text-primary">{{ $latestAnalysis->summary }}</p>
                    </div>
                    @endif
                    @if($latestAnalysis->objectives && count($latestAnalysis->objectives) > 0)
                    <div class="fb-card p-5">
                        <h3 class="font-bold mb-3 fb-text-primary">{{ __('Objectives') }}</h3>
                        <ul class="space-y-2">
                            @foreach($latestAnalysis->objectives as $objective)
                            <li class="flex items-start gap-2 text-sm fb-text-primary">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-emerald-700 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $objective }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- ── RIGHT SIDEBAR ── --}}
            <div class="space-y-4">

                {{-- Challenge Info --}}
                <div class="fb-card p-5">
                    <h3 class="font-bold mb-1 fb-text-primary" style="font-size:0.9375rem;">{{ __('Challenge Details') }}</h3>
                    <p class="text-xs mb-4 fb-text-secondary">{{ __('Key information about this challenge') }}</p>
                    <dl>
                        @if($challenge->complexity_level)
                        <div class="fb-info-row">
                            <dt>{{ __('Complexity') }}</dt>
                            <dd class="flex items-center gap-2">
                                {{ $challenge->complexity_level }}/4
                                <div class="fb-progress-track flex-1">
                                    <div class="fb-progress-fill" style="width:{{ ($challenge->complexity_level / 4) * 100 }}%;"></div>
                                </div>
                            </dd>
                        </div>
                        @endif
                        @if($latestAnalysis && $latestAnalysis->estimated_effort_hours)
                        <div class="fb-info-row">
                            <dt>{{ __('Estimated Effort') }}</dt>
                            <dd>{{ round($latestAnalysis->estimated_effort_hours) }} {{ __('hours') }}</dd>
                        </div>
                        @endif
                        @if($latestAnalysis && $latestAnalysis->confidence_score)
                        <div class="fb-info-row">
                            <dt>{{ __('AI Confidence') }}</dt>
                            <dd>{{ round($latestAnalysis->confidence_score) }}%</dd>
                        </div>
                        @endif
                        @if($challenge->deadline)
                        <div class="fb-info-row">
                            <dt>{{ __('Deadline') }}</dt>
                            <dd>{{ $challenge->deadline->translatedFormat('M d, Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Posted By --}}
                <div class="fb-card p-5">
                    <h3 class="font-bold mb-4 fb-text-primary" style="font-size:0.9375rem;">{{ __('Posted By') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="fb-avatar">{{ strtoupper(substr($challenge->company->company_name, 0, 2)) }}</div>
                        <div>
                            <p class="font-semibold text-sm fb-text-primary">{{ $challenge->company->company_name }}</p>
                            @if($challenge->company->industry)
                            <p class="text-xs fb-text-secondary">{{ $challenge->company->industry }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Company Actions --}}
                @auth
                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id && $challenge->canIssueCertificates())
                    <div class="fb-card fb-card-success p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30">
                                <svg class="w-5 h-5 text-emerald-700 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-sm fb-text-primary">{{ __('Issue Certificates') }}</h3>
                        </div>
                        <p class="text-xs mb-3 fb-text-secondary">{{ __('Confirm completion and generate certificates for volunteers.') }}</p>
                        <a href="{{ route('challenges.confirm', $challenge) }}"
                           class="block w-full text-center py-2 rounded-lg text-sm font-semibold text-white transition-all bg-emerald-700 hover:bg-emerald-800">
                            {{ __('Confirm & Issue') }}
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id)
                    <a href="{{ route('challenges.analytics', $challenge) }}" class="fb-card p-4 flex items-center gap-3 hover:border-purple-300 transition-all no-underline">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-primary-50 dark:bg-primary-500/15">
                            <svg class="w-5 h-5 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm fb-text-primary">{{ __('View Analytics') }}</p>
                            <p class="text-xs fb-text-secondary">{{ __('Track progress and performance') }}</p>
                        </div>
                        <svg class="w-4 h-4 flex-shrink-0 fb-text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                @endauth

            </div>
        </div>
    </div>
</div>
@endsection
