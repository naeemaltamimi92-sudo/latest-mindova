@extends('layouts.app')

@section('title', __('My Challenges'))

@push('styles')
<style>
    .challenge-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .challenge-card:hover {
        transform: translateY(-4px);
    }
    .status-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
    .slide-up {
        animation: slideUp 0.5s forwards;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .toast {
        animation: toastIn 0.3s forwards;
    }
    @keyframes toastIn {
        from { opacity: 0; transform: translateX(100%); }
        to { opacity: 1; transform: translateX(0); }
    }
    .gradient-border {
        position: relative;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #8b5cf6, #ec4899) border-box;
        border: 2px solid transparent;
    }
    .filter-chip {
        transition: all 0.2s ease;
    }
    .filter-chip:hover {
        transform: scale(1.05);
    }
    .filter-chip.active {
        box-shadow: 0 4px 14px -3px rgba(139, 92, 246, 0.5);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100] space-y-2"></div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Premium Header with Stats -->
        <div class="mb-8 slide-up">
            <div class="bg-white rounded-3xl shadow-xl shadow-purple-500/5 border border-slate-200/60 overflow-hidden">
                <div class="bg-secondary-500 px-8 py-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-white">{{ __('My Challenges') }}</h1>
                                <p class="text-purple-100 mt-1">{{ __('Track and manage challenges you\'ve submitted') }}</p>
                            </div>
                        </div>
                        <x-ui.button type="button" id="openChallengeModalBtn" variant="outline" class="bg-white text-purple-600 hover:bg-purple-50">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Submit New Challenge') }}
                        </x-ui.button>
                    </div>
                </div>

                <!-- Stats Bar -->
                @php
                    $totalChallenges = $challenges->total();
                    $activeChallenges = $challenges->where('status', 'active')->count();
                    $analyzingChallenges = $challenges->filter(fn($c) => in_array($c->ai_analysis_status, ['pending', 'processing']))->count();
                    $completedChallenges = $challenges->where('status', 'completed')->count();
                    $highQualitySolutions = $challenges->sum(fn($c) => $c->comments->where('ai_score', '>=', 7)->count());
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-5 divide-x divide-slate-100">
                    <div class="px-6 py-4 text-center hover:bg-purple-50/50 cursor-pointer" onclick="filterByStatus('all')">
                        <div class="text-2xl font-black text-slate-900">{{ $totalChallenges }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ __('Total') }}</div>
                    </div>
                    <div class="px-6 py-4 text-center hover:bg-emerald-50/50 cursor-pointer" onclick="filterByStatus('active')">
                        <div class="text-2xl font-black text-emerald-600">{{ $activeChallenges }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ __('Active') }}</div>
                    </div>
                    <div class="px-6 py-4 text-center hover:bg-purple-50/50 cursor-pointer" onclick="filterByStatus('analyzing')">
                        <div class="text-2xl font-black text-purple-600">{{ $analyzingChallenges }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ __('Analyzing') }}</div>
                    </div>
                    <div class="px-6 py-4 text-center hover:bg-blue-50/50 cursor-pointer" onclick="filterByStatus('completed')">
                        <div class="text-2xl font-black text-blue-600">{{ $completedChallenges }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ __('Completed') }}</div>
                    </div>
                    <div class="px-6 py-4 text-center bg-gray-50">
                        <div class="text-2xl font-black text-amber-600">{{ $highQualitySolutions }}</div>
                        <div class="text-sm text-slate-500 font-medium">{{ __('Quality Solutions') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Sorting -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 slide-up" style="animation-delay: 0.1s">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-slate-500 mr-2">{{ __('Filter:') }}</span>
                <button type="button" class="filter-chip active px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-700 hover:bg-purple-200" data-filter="all">
                    {{ __('All') }}
                </button>
                <button type="button" class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-filter="active">
                    {{ __('Active') }}
                </button>
                <button type="button" class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-filter="analyzing">
                    {{ __('Analyzing') }}
                </button>
                <button type="button" class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-filter="completed">
                    {{ __('Completed') }}
                </button>
                <button type="button" class="filter-chip px-4 py-2 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200" data-filter="rejected">
                    {{ __('Rejected') }}
                </button>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-slate-500">{{ __('Sort:') }}</label>
                <select id="sortSelect" class="text-sm border-slate-200 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="newest">{{ __('Newest First') }}</option>
                    <option value="oldest">{{ __('Oldest First') }}</option>
                    <option value="score">{{ __('Highest Score') }}</option>
                </select>
            </div>
        </div>

        <!-- Challenges List -->
        @if($challenges->count() > 0)
        <div class="space-y-5" id="challengesList">
            @foreach($challenges as $index => $challenge)
            <div class="challenge-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden slide-up"
                 data-challenge-id="{{ $challenge->id }}"
                 data-status="{{ $challenge->status }}"
                 data-analyzing="{{ in_array($challenge->ai_analysis_status, ['pending', 'processing']) ? 'true' : 'false' }}"
                 data-score="{{ $challenge->score ?? 0 }}"
                 data-created="{{ $challenge->created_at->timestamp }}"
                 style="animation-delay: {{ 0.1 + ($index * 0.05) }}s">

                <!-- Status Indicator Bar -->
                <div class="h-1.5
                    @if($challenge->status === 'active') bg-secondary-500
                    @elseif($challenge->status === 'completed') bg-primary-500
                    @elseif($challenge->status === 'analyzing' || in_array($challenge->ai_analysis_status, ['pending', 'processing'])) bg-secondary-500 status-pulse
                    @elseif($challenge->status === 'submitted') bg-secondary-300
                    @elseif($challenge->status === 'rejected') bg-secondary-700
                    @else bg-gray-400
                    @endif">
                </div>

                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <!-- Title & Badges Row -->
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <a href="{{ route('volunteer.challenges.show', $challenge) }}"
                                   class="text-xl font-bold text-slate-900 hover:text-purple-600 truncate">
                                    {{ $challenge->title }}
                                </a>

                                <!-- Status Badge -->
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                                    @if($challenge->status === 'active') bg-emerald-100 text-emerald-700
                                    @elseif($challenge->status === 'completed') bg-blue-100 text-blue-700
                                    @elseif($challenge->status === 'analyzing') bg-purple-100 text-purple-700
                                    @elseif($challenge->status === 'submitted') bg-amber-100 text-amber-700
                                    @elseif($challenge->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    @if(in_array($challenge->ai_analysis_status, ['pending', 'processing']))
                                        <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('AI Analyzing...') }}
                                    @elseif($challenge->status === 'rejected')
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Rejected') }}
                                    @else
                                        {{ ucfirst($challenge->status) }}
                                    @endif
                                </span>

                                <!-- Pending Review Badge -->
                                @php
                                    $latestAnalysis = $challenge->challengeAnalyses()->latest()->first();
                                    $needsVerification = $latestAnalysis && $latestAnalysis->requires_human_review && $challenge->status !== 'rejected';
                                @endphp
                                @if($needsVerification)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ __('Under Review') }}
                                </span>
                                @endif

                                <!-- Challenge Type Badge -->
                                @if($challenge->challenge_type)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold
                                    @if($challenge->challenge_type === 'community_discussion') bg-cyan-100 text-cyan-700
                                    @else bg-indigo-100 text-indigo-700
                                    @endif">
                                    @if($challenge->challenge_type === 'community_discussion')
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                        </svg>
                                        {{ __('Community') }}
                                    @else
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ __('Team') }}
                                    @endif
                                </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-slate-600 line-clamp-2 mb-4 leading-relaxed">{{ $challenge->original_description }}</p>

                            <!-- Rejection Message -->
                            @if($challenge->status === 'rejected' && $challenge->rejection_reason)
                            <div class="mb-4 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-700">{{ __('Rejection Reason') }}</p>
                                    <p class="text-sm text-red-600 mt-0.5">{{ $challenge->rejection_reason }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Meta Info -->
                            <div class="flex flex-wrap items-center gap-4">
                                @if($challenge->score)
                                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-bold text-amber-700">{{ $challenge->score }}/10</span>
                                </div>
                                @endif

                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <span class="font-medium">{{ $challenge->comments_count ?? 0 }}</span>
                                    <span>{{ __('comments') }}</span>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $challenge->created_at->diffForHumans() }}</span>
                                </div>

                                @if($challenge->tasks_count ?? 0 > 0)
                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <span class="font-medium">{{ $challenge->tasks_count }}</span>
                                    <span>{{ __('tasks') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions - Fixed clickable buttons -->
                        <div class="flex-shrink-0 flex items-center gap-2 relative z-10">
                            <!-- View Button - Always visible -->
                            <x-ui.button as="a" href="{{ url('/my-challenges/' . $challenge->id) }}" variant="secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View') }}
                            </x-ui.button>

                            @if(!in_array($challenge->status, ['completed', 'delivered']))
                            <!-- Edit Button -->
                            <x-ui.button as="a" href="{{ url('/my-challenges/' . $challenge->id) }}?action=edit" variant="ghost">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ __('Edit') }}
                            </x-ui.button>

                            <!-- Delete Button -->
                            <x-ui.button type="button" onclick="confirmDelete({{ $challenge->id }}, '{{ addslashes($challenge->title) }}')" variant="destructive">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ __('Delete') }}
                            </x-ui.button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Progress Bar for Active Team Challenges -->
                @if($challenge->score && $challenge->score >= 3 && $challenge->status === 'active')
                <div class="px-6 pb-4">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                        <span class="font-medium">{{ __('Challenge Progress') }}</span>
                        <span class="font-bold text-purple-600">{{ $challenge->progress_percentage ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-secondary-500 h-full rounded-full"
                             style="width: {{ $challenge->progress_percentage ?? 0 }}%"></div>
                    </div>
                </div>
                @endif

                <!-- High Quality Solutions Banner -->
                @php
                    $highScoreComments = $challenge->comments->where('ai_score', '>=', 7)->count();
                @endphp
                @if($highScoreComments > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-emerald-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <span class="font-semibold">{{ __(':count high-quality solutions received!', ['count' => $highScoreComments]) }}</span>
                    </div>
                    <a href="{{ route('volunteer.challenges.show', $challenge) }}#solutions" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                        {{ __('View Solutions') }} &rarr;
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($challenges->hasPages())
        <div class="mt-8">
            {{ $challenges->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 p-12 text-center slide-up">
            <div class="relative">
                <div class="h-24 w-24 rounded-3xl bg-secondary-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center" style="left: calc(50% + 30px);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">{{ __('No Challenges Yet') }}</h3>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">{{ __('You haven\'t submitted any challenges to the community yet. Start by sharing a problem you\'d like solved!') }}</p>
            <x-ui.button type="button" onclick="openChallengeModal()" variant="secondary" size="lg">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Submit Your First Challenge') }}
            </x-ui.button>
        </div>
        @endif
    </div>
</div>

<!-- Submit Challenge Modal -->
<div id="challengeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40" onclick="closeChallengeModal()"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <form id="challengeForm" enctype="multipart/form-data">
                @csrf
                <!-- Modal Header -->
                <div class="bg-secondary-500 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">{{ __('Submit a Community Challenge') }}</h3>
                            <p class="text-purple-100 text-sm mt-1">{{ __('Your challenge will be analyzed and scored by AI') }}</p>
                        </div>
                        <button type="button" onclick="closeChallengeModal()" class="text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 space-y-6 max-h-[60vh] overflow-y-auto">
                    <!-- Title -->
                    <div>
                        <label for="challenge_title" class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('Challenge Title') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="challenge_title" required
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-purple-500 focus:ring-purple-500"
                            placeholder="{{ __('Enter a clear, descriptive title...') }}">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="challenge_description" class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('Challenge Description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="challenge_description" rows="5" required minlength="50"
                            class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-purple-500 focus:ring-purple-500 resize-none"
                            placeholder="{{ __('Describe your challenge in detail. Include the problem you want to solve, any constraints, and desired outcomes...') }}"></textarea>
                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-slate-500">{{ __('Minimum 50 characters') }}</p>
                            <p class="text-xs text-slate-500"><span id="charCount">0</span>/50</p>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('Attachments') }} <span class="text-slate-400 font-normal">({{ __('optional') }})</span>
                        </label>
                        <div id="dropZone" class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-purple-400 hover:bg-purple-50/30 cursor-pointer">
                            <input type="file" name="attachments[]" id="challenge_attachments" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                            <label for="challenge_attachments" class="cursor-pointer">
                                <div class="w-12 h-12 mx-auto mb-3 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <svg class="h-6 w-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-700">{{ __('Click to upload or drag and drop') }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ __('PDF, DOC, XLS, PPT, Images up to 10MB each') }}</p>
                            </label>
                        </div>
                        <div id="fileList" class="mt-3 space-y-2"></div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-secondary-50 border border-purple-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-purple-900 text-sm mb-2">{{ __('How scoring works:') }}</p>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 bg-cyan-100 text-cyan-700 rounded-lg flex items-center justify-center font-bold">1-2</span>
                                        <span class="text-slate-600">{{ __('Community Discussion') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center font-bold">3-10</span>
                                        <span class="text-slate-600">{{ __('Team Execution') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <x-ui.button type="button" onclick="closeChallengeModal()" variant="outline">
                        {{ __('Cancel') }}
                    </x-ui.button>
                    <x-ui.button as="submit" id="submitBtn" variant="secondary">
                        <span id="submitText" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('Submit Challenge') }}
                        </span>
                        <span id="submitLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Submitting...') }}
                        </span>
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center" onclick="event.stopPropagation()">
            <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Delete Challenge?') }}</h3>
            <p class="text-slate-500 mb-2">{{ __('You\'re about to delete:') }}</p>
            <p class="text-slate-900 font-semibold mb-4 text-lg" id="deleteTitle"></p>
            <p class="text-sm text-slate-400 mb-8">{{ __('This action cannot be undone. All comments and data will be permanently removed.') }}</p>
            <div class="flex justify-center gap-3">
                <x-ui.button type="button" onclick="closeDeleteModal()" variant="ghost">
                    {{ __('Cancel') }}
                </x-ui.button>
                <x-ui.button type="button" id="deleteConfirmBtn" onclick="executeDelete()" variant="destructive">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Delete') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

<script>
// Toast Notifications
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast flex items-center gap-3 px-5 py-4 rounded-xl shadow-lg ${
        type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-500' : 'bg-slate-700'
    } text-white`;

    const icon = type === 'success'
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

    toast.innerHTML = `${icon}<span class="font-medium">${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Modal Functions
function openChallengeModal() {
    document.getElementById('challengeModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeChallengeModal() {
    document.getElementById('challengeModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    document.getElementById('challengeForm').reset();
    document.getElementById('fileList').innerHTML = '';
    document.getElementById('charCount').textContent = '0';
}

// Character count
document.getElementById('challenge_description')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('charCount').textContent = count;
    document.getElementById('charCount').classList.toggle('text-emerald-600', count >= 50);
});

// Open modal button
document.getElementById('openChallengeModalBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    openChallengeModal();
});

// File handling
const attachmentsInput = document.getElementById('challenge_attachments');
const dropZone = document.getElementById('dropZone');

if (attachmentsInput) {
    attachmentsInput.addEventListener('change', updateFileList);
}

if (dropZone) {
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-purple-400', 'bg-purple-50');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-purple-400', 'bg-purple-50');
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-purple-400', 'bg-purple-50');
        attachmentsInput.files = e.dataTransfer.files;
        updateFileList();
    });
}

function updateFileList() {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';
    Array.from(attachmentsInput.files).forEach((file, index) => {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 bg-slate-50 rounded-lg px-4 py-2';
        div.innerHTML = `
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="flex-1 text-sm text-slate-700 truncate">${file.name}</span>
            <span class="text-xs text-slate-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
        `;
        fileList.appendChild(div);
    });
}

// Form submission
document.getElementById('challengeForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    document.getElementById('submitText').classList.add('hidden');
    document.getElementById('submitLoading').classList.remove('hidden');

    try {
        const response = await fetch('{{ route("volunteer.challenges.store") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closeChallengeModal();
            showToast(data.message || '{{ __("Challenge submitted successfully!") }}', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            const errorMsg = data.errors ? Object.values(data.errors)[0][0] : (data.message || '{{ __("Failed to submit challenge") }}');
            showToast(errorMsg, 'error');
        }
    } catch (error) {
        showToast('{{ __("An error occurred. Please try again.") }}', 'error');
    } finally {
        btn.disabled = false;
        document.getElementById('submitText').classList.remove('hidden');
        document.getElementById('submitLoading').classList.add('hidden');
    }
});

// Delete functionality
let deleteTargetId = null;

function confirmDelete(id, title) {
    deleteTargetId = id;
    document.getElementById('deleteTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    deleteTargetId = null;
}

async function executeDelete() {
    if (!deleteTargetId) return;

    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

    try {
        const response = await fetch('/my-challenges/' + deleteTargetId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok && data.success) {
            closeDeleteModal();
            showToast(data.message || '{{ __("Challenge deleted successfully") }}', 'success');

            // Animate and remove the card
            const card = document.querySelector(`[data-challenge-id="${deleteTargetId}"]`);
            if (card) {
                card.style.transition = 'all 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    card.remove();
                    // Reload if no challenges left
                    if (document.querySelectorAll('[data-challenge-id]').length === 0) {
                        window.location.reload();
                    }
                }, 300);
            } else {
                setTimeout(() => window.location.reload(), 1000);
            }
        } else {
            showToast(data.message || '{{ __("Failed to delete challenge") }}', 'error');
            resetDeleteButton();
        }
    } catch (error) {
        console.error('Delete error:', error);
        showToast('{{ __("An error occurred. Please try again.") }}', 'error');
        resetDeleteButton();
    }
}

function resetDeleteButton() {
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled = false;
    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>{{ __("Delete") }}';
}

// Filter functionality
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        document.querySelectorAll('.filter-chip').forEach(c => {
            c.classList.remove('active', 'bg-purple-100', 'text-purple-700');
            c.classList.add('bg-slate-100', 'text-slate-600');
        });
        this.classList.add('active', 'bg-purple-100', 'text-purple-700');
        this.classList.remove('bg-slate-100', 'text-slate-600');

        const filter = this.dataset.filter;
        filterChallenges(filter);
    });
});

function filterChallenges(status) {
    document.querySelectorAll('.challenge-card').forEach(card => {
        const cardStatus = card.dataset.status;
        const isAnalyzing = card.dataset.analyzing === 'true';

        if (status === 'all') {
            card.style.display = '';
        } else if (status === 'analyzing') {
            card.style.display = isAnalyzing ? '' : 'none';
        } else {
            card.style.display = cardStatus === status ? '' : 'none';
        }
    });
}

function filterByStatus(status) {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        if (chip.dataset.filter === status) {
            chip.click();
        }
    });
}

// Sort functionality
document.getElementById('sortSelect')?.addEventListener('change', function() {
    const list = document.getElementById('challengesList');
    const cards = Array.from(list.querySelectorAll('.challenge-card'));

    cards.sort((a, b) => {
        switch(this.value) {
            case 'newest': return b.dataset.created - a.dataset.created;
            case 'oldest': return a.dataset.created - b.dataset.created;
            case 'score': return b.dataset.score - a.dataset.score;
        }
    });

    cards.forEach(card => list.appendChild(card));
});

// Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeChallengeModal();
        closeDeleteModal();
    }
});
</script>
@endsection
