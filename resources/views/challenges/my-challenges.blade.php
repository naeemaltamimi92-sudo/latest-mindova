@extends('layouts.app')

@section('title', __('My Challenges'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-primary-500 py-12 mb-10 rounded-3xl shadow-2xl mx-4 sm:mx-6 lg:mx-8">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 -left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-blue-300 rounded-full blur-3xl"></div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-white/70 text-sm mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-white">{{ __('Dashboard') }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white font-medium">{{ __('My Challenges') }}</span>
                </div>

                <!-- Title & Description -->
                <h1 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">
                    {{ __('My Challenges') }}
                </h1>
                <p class="text-lg text-white/80 max-w-2xl leading-relaxed">
                    {{ __('Manage all your submitted challenges, track progress, and monitor volunteer activity.') }}
                </p>
            </div>

            <!-- Create New Challenge CTA -->
            <x-ui.button as="a" href="{{ route('challenges.create') }}" variant="secondary" class="bg-white text-indigo-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('New Challenge') }}
            </x-ui.button>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <!-- Total Challenges -->
        <div class="relative group">
            <div class="absolute inset-0 bg-primary-400 rounded-2xl opacity-0 group-hover:opacity-20 blur-xl"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg border border-slate-100 hover:border-indigo-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['total'] }}</h3>
                <p class="text-sm text-slate-500 font-medium">{{ __('Total Challenges') }}</p>
            </div>
        </div>

        <!-- Active -->
        <div class="relative group">
            <div class="absolute inset-0 bg-secondary-400 rounded-2xl opacity-0 group-hover:opacity-20 blur-xl"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg border border-slate-100 hover:border-emerald-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['active'] }}</h3>
                <p class="text-sm text-slate-500 font-medium">{{ __('Active') }}</p>
            </div>
        </div>

        <!-- Analyzing -->
        <div class="relative group">
            <div class="absolute inset-0 bg-secondary-300 rounded-2xl opacity-0 group-hover:opacity-20 blur-xl"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg border border-slate-100 hover:border-amber-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-secondary-300 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['analyzing'] }}</h3>
                <p class="text-sm text-slate-500 font-medium">{{ __('Analyzing') }}</p>
            </div>
        </div>

        <!-- Completed -->
        <div class="relative group">
            <div class="absolute inset-0 bg-secondary-400 rounded-2xl opacity-0 group-hover:opacity-20 blur-xl"></div>
            <div class="relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg border border-slate-100 hover:border-violet-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900">{{ $stats['completed'] }}</h3>
                <p class="text-sm text-slate-500 font-medium">{{ __('Completed') }}</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <form method="GET" action="{{ route('challenges.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Search') }}</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('Search challenges...') }}"
                               class="w-full pl-12 pr-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 text-sm">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Status') }}</label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 text-sm bg-white">
                        <option value="all">{{ __('All Status') }}</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                        <option value="analyzing" {{ request('status') === 'analyzing' ? 'selected' : '' }}>{{ __('Analyzing') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Type') }}</label>
                    <select name="type" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 text-sm bg-white">
                        <option value="all">{{ __('All Types') }}</option>
                        <option value="team_execution" {{ request('type') === 'team_execution' ? 'selected' : '' }}>{{ __('Team Execution') }}</option>
                        <option value="community_discussion" {{ request('type') === 'community_discussion' ? 'selected' : '' }}>{{ __('Community') }}</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Sort By') }}</label>
                    <select name="sort" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 text-sm bg-white">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>{{ __('Oldest First') }}</option>
                        <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>{{ __('Title A-Z') }}</option>
                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>{{ __('Status') }}</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <x-ui.button as="submit" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('Apply Filters') }}
                </x-ui.button>

                @if(request()->hasAny(['search', 'status', 'type', 'sort']))
                <a href="{{ route('challenges.index') }}" class="inline-flex items-center px-4 py-2.5 text-slate-600 hover:text-slate-900 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Clear Filters') }}
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-600">
            {{ __('Showing') }} <span class="font-bold text-slate-900">{{ $challenges->firstItem() ?? 0 }}</span> -
            <span class="font-bold text-slate-900">{{ $challenges->lastItem() ?? 0 }}</span> {{ __('of') }}
            <span class="font-bold text-slate-900">{{ $challenges->total() }}</span> {{ __('challenges') }}
        </p>
    </div>

    <!-- Challenges Grid -->
    @if($challenges->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        @foreach($challenges as $challenge)
        <div class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl border border-slate-100 hover:border-indigo-200 overflow-hidden" data-challenge-id="{{ $challenge->id }}">
            <!-- Status Ribbon -->
            <div class="absolute top-4 right-4 z-10">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                    {{ $challenge->status === 'active' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                    {{ $challenge->status === 'completed' ? 'bg-violet-100 text-violet-700 border border-violet-200' : '' }}
                    {{ $challenge->status === 'analyzing' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                    {{ $challenge->status === 'submitted' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                    {{ $challenge->status === 'in_progress' ? 'bg-cyan-100 text-cyan-700 border border-cyan-200' : '' }}
                    {{ $challenge->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                    {{ $challenge->status === 'delivered' ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}">
                    @if($challenge->status === 'analyzing' || $challenge->status === 'submitted')
                        <svg class="w-3 h-3 mr-1.5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    @endif
                    {{ ucfirst(str_replace('_', ' ', $challenge->status)) }}
                </span>
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <!-- Title & Type -->
                <div class="mb-4 pr-24">
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 mb-2 line-clamp-2">
                        {{ $challenge->title }}
                    </h3>
                    @if($challenge->challenge_type)
                    <span class="inline-flex items-center text-xs font-medium text-slate-500">
                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                    </span>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-sm text-slate-600 mb-5 line-clamp-2 leading-relaxed">
                    {{ Str::limit($challenge->refined_brief ?? $challenge->original_description ?? $challenge->description, 120) }}
                </p>

                <!-- Progress Bar (if has tasks) -->
                @if($challenge->total_tasks > 0)
                <div class="mb-5">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span class="font-semibold text-slate-700">{{ __('Progress') }}</span>
                        <span class="font-bold text-indigo-600">{{ $challenge->progress_percentage }}%</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-primary-500"
                             style="width: {{ $challenge->progress_percentage }}%"></div>
                    </div>
                </div>
                @endif

                <!-- Stats Grid -->
                <div class="grid grid-cols-4 gap-3 mb-5">
                    <!-- Tasks -->
                    <div class="text-center p-3 bg-slate-50 rounded-xl">
                        <div class="text-lg font-bold text-slate-900">{{ $challenge->total_tasks }}</div>
                        <div class="text-xs text-slate-500 font-medium">{{ __('Tasks') }}</div>
                    </div>

                    <!-- Volunteers -->
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <div class="text-lg font-bold text-blue-600">{{ $challenge->active_volunteers }}</div>
                        <div class="text-xs text-slate-500 font-medium">{{ __('Working') }}</div>
                    </div>

                    <!-- Submissions -->
                    <div class="text-center p-3 bg-violet-50 rounded-xl">
                        <div class="text-lg font-bold text-violet-600">{{ $challenge->submissions_count }}</div>
                        <div class="text-xs text-slate-500 font-medium">{{ __('Submitted') }}</div>
                    </div>

                    <!-- Approved -->
                    <div class="text-center p-3 bg-emerald-50 rounded-xl">
                        <div class="text-lg font-bold text-emerald-600">{{ $challenge->approved_count }}</div>
                        <div class="text-xs text-slate-500 font-medium">{{ __('Approved') }}</div>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="flex items-center flex-wrap gap-4 text-xs text-slate-500 mb-5 pb-5 border-b border-slate-100">
                    @if($challenge->score)
                    <span class="inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ __('Score') }}: {{ $challenge->score }}/10
                    </span>
                    @endif

                    @if($challenge->total_estimated_hours > 0)
                    <span class="inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $challenge->total_estimated_hours }}{{ __('h estimated') }}
                    </span>
                    @endif

                    <span class="inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $challenge->created_at->format('M d, Y') }}
                    </span>
                </div>

                <!-- Actions - Ensure buttons are clickable with proper z-index -->
                <div class="relative z-20 flex items-center flex-wrap gap-2 pt-4 border-t border-slate-100">
                    <!-- View Button - Always visible -->
                    <x-ui.button as="a" href="{{ route('challenges.show', $challenge) }}" variant="primary" size="sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ __('View') }}
                    </x-ui.button>

                    <!-- Edit Button - Only if editable -->
                    @if(!in_array($challenge->status, ['completed', 'delivered']))
                    <x-ui.button as="a" href="{{ route('challenges.edit', $challenge) }}" variant="secondary" size="sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('Edit') }}
                    </x-ui.button>

                    <!-- Delete Button -->
                    <x-ui.button type="button" onclick="confirmDelete({{ $challenge->id }}, '{{ addslashes($challenge->title) }}')" variant="destructive" size="sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('Delete') }}
                    </x-ui.button>
                    @endif

                    <!-- Analytics Button - Only for active/in_progress -->
                    @if($challenge->status === 'active' || $challenge->status === 'in_progress')
                    <a href="{{ route('challenges.analytics', $challenge) }}"
                       class="inline-flex items-center px-3 py-2.5 text-indigo-600 hover:text-indigo-700 font-medium text-sm cursor-pointer"
                       title="{{ __('Analytics') }}"
                       style="pointer-events: auto;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $challenges->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="relative bg-gray-50 rounded-3xl p-16 text-center shadow-sm border border-slate-200 overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 bg-violet-400 rounded-full blur-3xl"></div>
        </div>

        <div class="relative">
            <div class="w-24 h-24 bg-primary-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>

            @if(request()->hasAny(['search', 'status', 'type']))
                <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('No Challenges Found') }}</h3>
                <p class="text-slate-600 mb-8 max-w-md mx-auto">{{ __('No challenges match your current filters. Try adjusting your search criteria.') }}</p>
                <x-ui.button as="a" href="{{ route('challenges.index') }}" variant="outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Clear Filters') }}
                </x-ui.button>
            @else
                <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('No Challenges Yet') }}</h3>
                <p class="text-slate-600 mb-8 max-w-md mx-auto">{{ __('Start your innovation journey by submitting your first challenge to the community.') }}</p>
                <x-ui.button as="a" href="{{ route('challenges.create') }}" variant="primary" size="lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Submit Your First Challenge') }}
                </x-ui.button>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75" aria-hidden="true" onclick="closeDeleteModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-100 sm:mx-0 sm:h-12 sm:w-12">
                        <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                            {{ __('Delete Challenge') }}
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">
                                {{ __('Are you sure you want to delete this challenge?') }}
                            </p>
                            <p class="mt-2 text-sm font-semibold text-gray-900" id="deleteChallengeTitle"></p>
                            <p class="mt-3 text-sm text-red-600 bg-red-50 p-3 rounded-lg">
                                <strong>{{ __('Warning:') }}</strong> {{ __('This action cannot be undone. All associated tasks, workstreams, and attachments will be permanently deleted.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                <x-ui.button type="button" id="confirmDeleteBtn" onclick="executeDelete()" variant="destructive">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Delete Challenge') }}
                </x-ui.button>
                <x-ui.button type="button" onclick="closeDeleteModal()" variant="secondary">
                    {{ __('Cancel') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 z-50 hidden transform translate-y-full opacity-0">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 p-4 max-w-sm">
        <div class="flex items-center gap-3">
            <div id="toastIcon" class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"></div>
            <div class="flex-1">
                <p id="toastMessage" class="text-sm font-semibold text-slate-900"></p>
            </div>
            <button onclick="hideToast()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    let deleteTargetId = null;

    function confirmDelete(id, title) {
        deleteTargetId = id;
        document.getElementById('deleteChallengeTitle').textContent = '"' + title + '"';
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = '';
        deleteTargetId = null;
    }

    async function executeDelete() {
        if (!deleteTargetId) return;

        const deleteBtn = document.getElementById('confirmDeleteBtn');
        const originalContent = deleteBtn.innerHTML;

        // Show loading state
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Deleting...') }}
        `;

        try {
            const response = await fetch('/challenges/' + deleteTargetId, {
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
                showToast('{{ __("Challenge deleted successfully.") }}', 'success');

                // Remove the challenge card from the DOM
                const challengeCard = document.querySelector(`[data-challenge-id="${deleteTargetId}"]`);
                if (challengeCard) {
                    challengeCard.style.transition = 'all 0.3s';
                    challengeCard.style.opacity = '0';
                    challengeCard.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        challengeCard.remove();
                        // Check if no challenges left
                        const remainingCards = document.querySelectorAll('[data-challenge-id]');
                        if (remainingCards.length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    // Fallback: reload page
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                showToast(data.message || '{{ __("Failed to delete challenge.") }}', 'error');
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalContent;
            }
        } catch (error) {
            console.error('Delete error:', error);
            showToast('{{ __("An error occurred. Please try again.") }}', 'error');
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalContent;
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');

        toastMessage.textContent = message;

        if (type === 'success') {
            toastIcon.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-green-100';
            toastIcon.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        } else {
            toastIcon.className = 'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-red-100';
            toastIcon.innerHTML = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        }

        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.remove('translate-y-full', 'opacity-0');
        }, 10);

        // Auto hide after 5 seconds
        setTimeout(hideToast, 5000);
    }

    function hideToast() {
        const toast = document.getElementById('toast');
        toast.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>
@endsection
