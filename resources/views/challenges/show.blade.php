@extends('layouts.app')

@php
    $canSeeSpam = $canSeeSpam ?? (auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isCompany() && auth()->user()->company?->id === $challenge->company_id)));
@endphp

@section('title', $challenge->title)

@section('content')
<div x-data="{ activeTab: 'overview' }" class="min-h-screen bg-gray-50">
    
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                <a href="{{ route('challenges.index') }}" class="hover:text-gray-700">{{ __('Challenges') }}</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-900 truncate">{{ Str::limit($challenge->title, 40) }}</span>
            </nav>

            {{-- Title & Status --}}
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        @php
                            $statusConfig = [
                                'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200'],
                                'submitted' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                'analyzing' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                'active' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                'in_progress' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                                'completed' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
                                'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                                'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                            ];
                            $config = $statusConfig[$challenge->status] ?? $statusConfig['draft'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                            {{ __(ucfirst(str_replace('_', ' ', $challenge->status))) }}
                        </span>
                        @if($challenge->challenge_type)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                            @if($challenge->challenge_type === 'team_execution')
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                </svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ __(ucfirst(str_replace('_', ' ', $challenge->challenge_type))) }}
                        </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">{{ $challenge->title }}</h1>
                    <div class="flex items-center flex-wrap gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                            </svg>
                            {{ $challenge->company->company_name }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Created') }} {{ $challenge->created_at->translatedFormat('M d, Y') }}
                        </span>
                        @if($challenge->field)
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $challenge->field }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Deadline --}}
                @if($challenge->deadline && $challenge->deadline->isFuture())
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-center">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">{{ __('Deadline') }}</p>
                    @php
                        $diff = now()->diff($challenge->deadline);
                        $days = $diff->days;
                        $hours = $diff->h;
                    @endphp
                    <div class="flex items-center gap-2">
                        <div class="bg-gray-900 text-white px-3 py-2 rounded-lg">
                            <span class="text-xl font-bold">{{ $days }}</span>
                            <p class="text-[10px] text-gray-400">{{ __('Days') }}</p>
                        </div>
                        <span class="text-gray-400 font-bold">:</span>
                        <div class="bg-gray-900 text-white px-3 py-2 rounded-lg">
                            <span class="text-xl font-bold">{{ $hours }}</span>
                            <p class="text-[10px] text-gray-400">{{ __('Hours') }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ $challenge->deadline->translatedFormat('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($challenge->challenge_type === 'team_execution')
            {{-- Progress --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                @php $progress = $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0; @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $progress }}%</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Progress') }}</p>
            </div>

            {{-- Tasks --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] }}/{{ $stats['total_tasks'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Tasks Completed') }}</p>
            </div>

            {{-- Hours --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_hours'] }}h</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Estimated Hours') }}</p>
            </div>

            {{-- Volunteers --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active_volunteers'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Active Contributors') }}</p>
            </div>
            @else
            {{-- Ideas Count --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ideas'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Ideas Submitted') }}</p>
            </div>

            {{-- Top Score --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                @php $topScore = $challenge->ideas->max('final_score') ?? 0; @endphp
                <p class="text-2xl font-bold text-gray-900">{{ round($topScore) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Top Score') }}</p>
            </div>

            {{-- Total Votes --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $challenge->ideas->sum('community_votes') }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Total Votes') }}</p>
            </div>

            {{-- Contributors --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $challenge->ideas->pluck('volunteer_id')->unique()->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Contributors') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Tab Navigation --}}
                <div class="flex flex-wrap gap-1 p-1 bg-gray-100 rounded-lg">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('Overview') }}
                    </button>
                    @if($challenge->challenge_type === 'team_execution')
                    <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('Tasks') }}
                    </button>
                    @else
                    <button @click="activeTab = 'ideas'" :class="activeTab === 'ideas' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('Ideas') }}
                    </button>
                    @endif
                    @if($challenge->teams->count() > 0)
                    <button @click="activeTab = 'teams'" :class="activeTab === 'teams' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('Teams') }}
                    </button>
                    @endif
                    @if($latestAnalysis)
                    <button @click="activeTab = 'analysis'" :class="activeTab === 'analysis' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('AI Analysis') }}
                    </button>
                    @endif
                </div>

                {{-- Overview Tab --}}
                <div x-show="activeTab === 'overview'">
                    {{-- Description --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Challenge Description') }}
                        </h2>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $challenge->refined_brief ?? $challenge->original_description }}</p>
                        </div>
                    </div>

                    {{-- Required Skills --}}
                    @if($requiredSkills->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            {{ __('Required Skills') }}
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($requiredSkills as $skill)
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Attachments --}}
                    @if($challenge->attachments->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ __('Attachments') }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($challenge->attachments as $attachment)
                            <a href="{{ route('challenges.attachments.download', [$challenge, $attachment]) }}" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors">
                                @php
                                    $ext = pathinfo($attachment->filename, PATHINFO_EXTENSION);
                                    $iconColor = match($ext) {
                                        'pdf' => 'text-red-500',
                                        'doc', 'docx' => 'text-blue-500',
                                        'xls', 'xlsx' => 'text-green-500',
                                        'jpg', 'jpeg', 'png', 'gif' => 'text-purple-500',
                                        default => 'text-gray-500'
                                    };
                                @endphp
                                <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->filename }}</p>
                                    <p class="text-xs text-gray-500">{{ strtoupper($ext) }}</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Tasks Tab --}}
                @if($challenge->challenge_type === 'team_execution')
                <div x-show="activeTab === 'tasks'" x-cloak>
                    @forelse($challenge->workstreams as $index => $workstream)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 bg-primary-100 rounded text-primary-700 text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $workstream->title }}</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-8">{{ $workstream->description }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                {{ $workstream->tasks->count() }} {{ __('Tasks') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            @foreach($workstream->tasks as $task)
                            <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-gray-200 transition-colors">
                                @php
                                    $taskStatusConfig = [
                                        'open' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                        'assigned' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                        'in_progress' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
                                        'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
                                    ];
                                    $taskConfig = $taskStatusConfig[$task->status] ?? $taskStatusConfig['open'];
                                @endphp
                                <div class="w-8 h-8 {{ $taskConfig['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 {{ $taskConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                    <div class="flex items-center flex-wrap gap-2 mt-1 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $task->estimated_hours }}h
                                        </span>
                                        <span>{{ __('Complexity') }}: {{ $task->complexity_score }}/10</span>
                                        <span class="px-1.5 py-0.5 {{ $taskConfig['bg'] }} {{ $taskConfig['text'] }} rounded text-xs capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('tasks.show', $task) }}" class="flex-shrink-0 w-8 h-8 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center hover:bg-primary-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium">{{ __('No tasks have been created yet.') }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Tasks will appear here once AI analysis is complete.') }}</p>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- Ideas Tab --}}
                @if($challenge->challenge_type === 'community_discussion')
                <div x-show="activeTab === 'ideas'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('Community Ideas') }}</h2>
                        @if(auth()->user() && auth()->user()->isVolunteer())
                        <a href="{{ route('ideas.create', $challenge) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Submit Idea') }}
                        </a>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @forelse($challenge->ideas->sortByDesc('final_score') as $idea)
                        <div class="relative {{ $canSeeSpam && $idea->is_spam ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200' }} rounded-xl border p-4">
                            @if($canSeeSpam && $idea->is_spam)
                            <span class="absolute -top-2 -left-2 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">{{ __('Spam') }}</span>
                            @endif
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 {{ $canSeeSpam && $idea->is_spam ? 'bg-red-100' : 'bg-primary-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 {{ $canSeeSpam && $idea->is_spam ? 'text-red-600' : 'text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $idea->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($idea->description, 120) }}</p>
                                    <div class="flex items-center flex-wrap gap-3 text-sm">
                                        <span class="flex items-center gap-1 text-primary-600 font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            AI: {{ round($idea->ai_score) }}
                                        </span>
                                        <span class="flex items-center gap-1 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            {{ $idea->community_votes }} {{ __('votes') }}
                                        </span>
                                        <span class="flex items-center gap-1 text-emerald-600 font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ __('Final') }}: {{ round($idea->final_score) }}
                                        </span>
                                        @if($idea->volunteer && $idea->volunteer->user)
                                        <span class="flex items-center gap-1.5 text-gray-500 text-xs">
                                            <span class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center text-[10px] font-bold text-gray-600">{{ strtoupper(substr($idea->volunteer->user->name, 0, 1)) }}</span>
                                            {{ $idea->volunteer->user->name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('ideas.show', $idea) }}" class="flex-shrink-0 px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg text-sm font-medium hover:bg-primary-100">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600 font-medium">{{ __('No ideas submitted yet') }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ __('Be the first to share your idea!') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                {{-- Teams, AI Analysis tabs would continue here... --}}
                @if($challenge->teams->count() > 0)
                <div x-show="activeTab === 'teams'" x-cloak>
                    <div class="space-y-4">
                        @foreach($challenge->teams as $team)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center text-primary-700 font-bold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <h3 class="text-base font-semibold text-gray-900">{{ $team->name }}</h3>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium
                                            @if($team->status === 'active') bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @elseif($team->status === 'forming') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($team->status === 'completed') bg-violet-50 text-violet-700 border border-violet-200
                                            @else bg-gray-50 text-gray-500 border border-gray-200
                                            @endif">
                                            {{ __(ucfirst($team->status ?? 'forming')) }}
                                        </span>
                                    </div>
                                    @if($team->leader && $team->leader->user)
                                    <p class="text-sm text-gray-500">{{ __('Led by') }} {{ $team->leader->user->name }} • {{ $team->members->count() + 1 }} {{ __('members') }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('teams.show', $team) }}" class="flex-shrink-0 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($latestAnalysis)
                <div x-show="activeTab === 'analysis'" x-cloak>
                    @if($latestAnalysis->summary)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('AI Summary') }}</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $latestAnalysis->summary }}</p>
                    </div>
                    @endif

                    @if($latestAnalysis->objectives && count($latestAnalysis->objectives) > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Objectives') }}</h3>
                        <ul class="space-y-2">
                            @foreach($latestAnalysis->objectives as $objective)
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
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

            {{-- Right Column (Sidebar) --}}
            <div class="space-y-6">
                {{-- Challenge Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-100">{{ __('Challenge Info') }}</h3>

                    <dl class="space-y-4">
                        @if($challenge->complexity_level)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Complexity') }}</dt>
                            <dd class="flex items-center gap-2">
                                <span class="text-xl font-bold text-gray-900">{{ $challenge->complexity_level }}</span>
                                <span class="text-sm text-gray-500">/4</span>
                            </dd>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                <div class="h-full rounded-full bg-primary-500" style="width: {{ ($challenge->complexity_level / 4) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        @if($latestAnalysis && $latestAnalysis->estimated_effort_hours)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Est. Effort') }}</dt>
                            <dd class="text-base font-semibold text-gray-900">{{ round($latestAnalysis->estimated_effort_hours) }} {{ __('hours') }}</dd>
                        </div>
                        @endif

                        @if($latestAnalysis && $latestAnalysis->confidence_score)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('AI Confidence') }}</dt>
                            <dd class="text-base font-semibold text-gray-900">{{ round($latestAnalysis->confidence_score) }}%</dd>
                        </div>
                        @endif

                        @if($challenge->deadline)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Deadline') }}</dt>
                            <dd class="text-base font-semibold text-gray-900">{{ $challenge->deadline->translatedFormat('M d, Y') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Company Actions --}}
                @auth
                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id && $challenge->canIssueCertificates())
                    <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">{{ __('Issue Certificates') }}</h3>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ __('Confirm completion and generate certificates for participating volunteers.') }}</p>
                        <a href="{{ route('challenges.confirm', $challenge) }}" class="block w-full text-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                            {{ __('Confirm & Issue') }}
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->company && auth()->user()->company->id === $challenge->company_id)
                    <a href="{{ route('challenges.analytics', $challenge) }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-200 hover:border-gray-300 transition-colors">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ __('View Analytics') }}</h4>
                            <p class="text-xs text-gray-500">{{ __('Track progress and performance') }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                @endauth

                {{-- Company Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">{{ __('Posted By') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center text-primary-700 font-bold text-lg">
                            {{ strtoupper(substr($challenge->company->company_name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $challenge->company->company_name }}</h4>
                            @if($challenge->company->industry)
                            <p class="text-sm text-gray-500">{{ $challenge->company->industry }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
