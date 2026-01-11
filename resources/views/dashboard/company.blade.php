@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<!-- Premium Hero Section - Enhanced 2027 Design -->
<div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-500 py-10 mb-12 rounded-3xl shadow-lg">
    <!-- Softer Animated Background Mesh -->
    <div class="absolute inset-0 opacity-10">
        <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-white rounded-full blur-3xl animate-float"></div>
        <div class="floating-element absolute top-10 right-0 w-[32rem] h-[32rem] bg-purple-200 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="floating-element absolute -bottom-20 left-1/3 w-80 h-80 bg-blue-200 rounded-full blur-3xl animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 animate-slide-in-up">
            <div class="flex-1">
                <!-- Status Badge - Refined -->
                <div class="inline-flex items-center space-x-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-4 py-2 mb-4 shadow-sm">
                    <div class="relative">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse-glow"></div>
                        <div class="absolute inset-0 w-2 h-2 bg-emerald-400 rounded-full animate-ping"></div>
                    </div>
                    <span class="text-xs font-semibold text-white/95 tracking-wide">{{ __('Dashboard Active') }}</span>
                </div>

                <!-- Main Heading - Enhanced Hierarchy -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-3 leading-tight">
                    {{ __('Welcome back,') }}
                    <span class="bg-gradient-to-r from-yellow-300 via-amber-200 to-orange-300 bg-clip-text text-transparent">
                        {{ $company->company_name }}
                    </span>!
                </h1>
                <p class="text-base text-white/80 font-normal leading-relaxed max-w-2xl">
                    {{ __('Track your challenges, monitor team progress, and drive innovation forward') }}
                </p>
            </div>

            <!-- Enhanced Primary CTA -->
            <a href="{{ route('challenges.create') }}" class="group inline-flex items-center justify-center bg-white text-indigo-600 font-bold px-8 py-4 rounded-2xl transition-all transform hover:scale-105 hover:shadow-2xl shadow-xl whitespace-nowrap">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Submit New Challenge') }}
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 sm:px-8 md:px-10 lg:px-12 xl:px-16">

    <!-- Premium Stats Overview - Enhanced Design -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6 mb-14">
        <!-- Total Challenges Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.1s;">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-indigo-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('Challenges') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors duration-300">{{ $company->total_challenges_submitted }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ __('total') }}</p>
            </div>
        </div>

        <!-- Active Challenges Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.2s;">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-emerald-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('Active') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors duration-300">{{ $stats['active_challenges'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ __('in progress') }}</p>
            </div>
        </div>

        <!-- Tasks in Progress Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.3s;">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-blue-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-blue-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('In Progress') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-blue-600 transition-colors duration-300">{{ $stats['tasks_in_progress'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ __('tasks') }}</p>
            </div>
        </div>

        <!-- Completed Tasks Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.4s;">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-400 to-violet-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-violet-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('Completed') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-violet-600 transition-colors duration-300">{{ $stats['completed_tasks'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ __('tasks done') }}</p>
            </div>
        </div>

        <!-- Volunteers Working Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-teal-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-teal-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('Contributors') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-teal-600 transition-colors duration-300">{{ $stats['total_volunteers_working'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ __('working now') }}</p>
            </div>
        </div>

        <!-- Solutions Received Card -->
        <div class="relative group animate-slide-in-up" style="animation-delay: 0.6s;">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 blur-xl"></div>
            <div class="relative bg-white rounded-3xl p-5 shadow-sm hover:shadow-lg border border-slate-100 hover:border-amber-200 transition-all duration-500 group-hover:-translate-y-1">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">{{ __('Solutions') }}</p>
                        <h3 class="text-3xl font-black text-slate-900 group-hover:text-amber-600 transition-colors duration-300">{{ $stats['total_approved_solutions'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-emerald-600 font-semibold">{{ __('approved') }}</p>
            </div>
        </div>
    </div>

    <!-- Latest Challenge Section - Enhanced Typography -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 tracking-tight">{{ __('Latest Challenge') }}</h2>
                <p class="text-base text-slate-500 font-medium">{{ __('Your most recently submitted challenge') }}</p>
            </div>
            <a href="{{ route('challenges.index') }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-2xl transition-all hover:shadow-lg hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                {{ __('View All Challenges') }}
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @if($challenges->count() > 0)
        <div class="grid grid-cols-1 gap-8">
            @foreach($challenges as $index => $challenge)
            <!-- Enhanced Challenge Card -->
            <div class="group relative bg-white rounded-3xl p-8 md:p-10 shadow-sm hover:shadow-xl border border-slate-100 hover:border-indigo-200 transition-all duration-500 hover:-translate-y-1 animate-slide-in-up" style="animation-delay: {{ 0.1 * ($index + 1) }}s;">
                <!-- Subtle Glow Effect on Hover -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-400/0 to-violet-400/0 group-hover:from-indigo-400/5 group-hover:to-violet-400/5 rounded-3xl transition-all duration-500"></div>

                <div class="relative flex justify-between items-start mb-6">
                    <div class="flex-1 pr-6">
                        <div class="flex items-center gap-3 flex-wrap mb-4">
                            <h3 class="text-2xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors duration-300">{{ $challenge->title }}</h3>
                            <span class="px-4 py-2 rounded-xl text-sm font-bold border
                                {{ $challenge->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                {{ $challenge->status === 'completed' ? 'bg-violet-50 text-violet-700 border-violet-200' : '' }}
                                {{ $challenge->status === 'analyzing' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                {{ $challenge->status === 'submitted' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                shadow-sm">
                                {{ ucfirst($challenge->status) }}
                            </span>
                        </div>
                        <p class="text-base text-slate-600 leading-relaxed">{{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 200) }}</p>
                    </div>
                    <!-- Enhanced View Details CTA -->
                    <a href="{{ route('challenges.show', $challenge->id) }}" class="group/btn flex-shrink-0 inline-flex items-center justify-center bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold px-6 py-3.5 rounded-xl transition-all transform hover:scale-105 hover:shadow-lg shadow-md whitespace-nowrap">
                        <span class="hidden sm:inline">{{ __('View Details') }}</span>
                        <span class="sm:hidden">{{ __('View') }}</span>
                        <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- AI Workflow Progress -->
                @php
                    $hasAnalysis = $challenge->refined_brief !== null;
                    $hasComplexity = $challenge->score !== null;
                    $hasTasks = $challenge->tasks()->count() > 0;
                    $hasAssignments = $challenge->tasks()->whereHas('assignments')->count() > 0;
                @endphp

                @if($challenge->status === 'analyzing' || $challenge->status === 'submitted' || !$hasTasks)
                <!-- Enhanced AI Workflow Section -->
                <div class="relative mb-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl shadow-sm">
                    <h4 class="text-sm font-bold text-indigo-900 mb-5 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z"/>
                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        {{ __('AI Analysis Workflow') }}
                    </h4>
                    <div class="space-y-3">
                        <!-- Step 1: Brief Analysis -->
                        <div class="flex items-center text-xs sm:text-sm">
                            @if($hasAnalysis)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span class="text-green-700 font-semibold">{{ __('✓ Brief Analyzed & Refined') }}</span>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2 sm:mr-3 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-yellow-700 font-medium">{{ __('Analyzing Challenge Brief...') }}</span>
                            @endif
                        </div>

                        <!-- Step 2: Complexity Evaluation -->
                        <div class="flex items-center text-xs sm:text-sm">
                            @if($hasComplexity)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span class="text-green-700 font-semibold">{{ __('✓ Complexity Evaluated (Score: :score)', ['score' => $challenge->score]) }}</span>
                            @elseif($hasAnalysis)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2 sm:mr-3 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-yellow-700 font-medium">{{ __('Evaluating Complexity...') }}</span>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                </svg>
                                <span class="text-gray-500 font-medium">{{ __('Complexity Evaluation (Pending)') }}</span>
                            @endif
                        </div>

                        <!-- Step 3: Task Decomposition -->
                        <div class="flex items-center text-xs sm:text-sm">
                            @if($hasTasks)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span class="text-green-700 font-semibold">{{ __('✓ Tasks Created (:count tasks)', ['count' => $challenge->tasks()->count()]) }}</span>
                            @elseif($hasComplexity)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2 sm:mr-3 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-yellow-700 font-medium">{{ __('Decomposing into Tasks...') }}</span>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                </svg>
                                <span class="text-gray-500 font-medium">{{ __('Task Decomposition (Pending)') }}</span>
                            @endif
                        </div>

                        <!-- Step 4: Volunteer Matching -->
                        <div class="flex items-center text-xs sm:text-sm">
                            @if($hasAssignments)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span class="text-green-700 font-semibold">{{ __('✓ Volunteers Matched & Invited') }}</span>
                            @elseif($hasTasks)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2 sm:mr-3 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-yellow-700 font-medium">{{ __('Matching Volunteers...') }}</span>
                            @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                </svg>
                                <span class="text-gray-500 font-medium">{{ __('Volunteer Matching (Pending)') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Solution Progress Section - NEW -->
                @if($challenge->tasks()->count() > 0 && ($challenge->status === 'active' || $challenge->status === 'in_progress'))
                <div class="relative mb-6 p-6 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl shadow-sm">
                    <h4 class="text-sm font-bold text-emerald-900 mb-5 flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        {{ __('Solution Progress') }}
                    </h4>

                    <!-- Progress Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                        <!-- Active Contributors -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ __('Working') }}</span>
                            </div>
                            <p class="text-2xl font-black text-slate-900">{{ $challenge->active_volunteers_count }}</p>
                            <p class="text-xs text-slate-500">{{ __('contributors') }}</p>
                        </div>

                        <!-- Invited/Pending -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ __('Invited') }}</span>
                            </div>
                            <p class="text-2xl font-black text-slate-900">{{ $challenge->invited_volunteers_count }}</p>
                            <p class="text-xs text-slate-500">{{ __('pending') }}</p>
                        </div>

                        <!-- Submissions -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ __('Submissions') }}</span>
                            </div>
                            <p class="text-2xl font-black text-slate-900">{{ $challenge->submissions_count }}</p>
                            <p class="text-xs text-slate-500">{{ __('received') }}</p>
                        </div>

                        <!-- Approved Solutions -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500 uppercase">{{ __('Approved') }}</span>
                            </div>
                            <p class="text-2xl font-black text-emerald-600">{{ $challenge->approved_submissions_count }}</p>
                            <p class="text-xs text-slate-500">{{ __('solutions') }}</p>
                        </div>
                    </div>

                    <!-- Solution Quality & Tasks Progress -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tasks with Solutions -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-slate-700">{{ __('Tasks with Solutions') }}</span>
                                <span class="text-sm font-bold text-emerald-600">{{ $challenge->tasks_with_solutions }}/{{ $challenge->tasks()->count() }}</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                @php
                                    $solutionPercentage = $challenge->tasks()->count() > 0
                                        ? round(($challenge->tasks_with_solutions / $challenge->tasks()->count()) * 100)
                                        : 0;
                                @endphp
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-all duration-500"
                                     style="width: {{ $solutionPercentage }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">{{ $solutionPercentage }}% {{ __('of tasks have approved solutions') }}</p>
                        </div>

                        <!-- Average Quality Score -->
                        <div class="bg-white rounded-xl p-4 border border-emerald-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-slate-700">{{ __('Average Quality Score') }}</span>
                                @if($challenge->avg_submission_quality)
                                    <span class="text-sm font-bold
                                        @if($challenge->avg_submission_quality >= 8) text-emerald-600
                                        @elseif($challenge->avg_submission_quality >= 6) text-blue-600
                                        @elseif($challenge->avg_submission_quality >= 4) text-amber-600
                                        @else text-red-600
                                        @endif">
                                        {{ $challenge->avg_submission_quality }}/10
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">{{ __('N/A') }}</span>
                                @endif
                            </div>
                            @if($challenge->avg_submission_quality)
                                <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500
                                        @if($challenge->avg_submission_quality >= 8) bg-gradient-to-r from-emerald-400 to-emerald-500
                                        @elseif($challenge->avg_submission_quality >= 6) bg-gradient-to-r from-blue-400 to-blue-500
                                        @elseif($challenge->avg_submission_quality >= 4) bg-gradient-to-r from-amber-400 to-amber-500
                                        @else bg-gradient-to-r from-red-400 to-red-500
                                        @endif"
                                         style="width: {{ ($challenge->avg_submission_quality / 10) * 100 }}%"></div>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">
                                    @if($challenge->avg_submission_quality >= 8)
                                        {{ __('Excellent quality submissions') }}
                                    @elseif($challenge->avg_submission_quality >= 6)
                                        {{ __('Good quality submissions') }}
                                    @elseif($challenge->avg_submission_quality >= 4)
                                        {{ __('Average quality submissions') }}
                                    @else
                                        {{ __('Needs improvement') }}
                                    @endif
                                </p>
                            @else
                                <p class="text-xs text-slate-400 mt-2">{{ __('No submissions scored yet') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- View Submissions Link -->
                    @if($challenge->submissions_count > 0)
                    <div class="mt-4 pt-4 border-t border-emerald-200">
                        <a href="{{ route('challenges.show', $challenge->id) }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ __('View All Submissions & Details') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Enhanced Progress Metrics with Circular Charts -->
                @if($challenge->tasks()->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-7 bg-gradient-to-br from-slate-50 to-white rounded-2xl border border-slate-200 mb-6 shadow-sm">
                    <!-- Task Completion Progress -->
                    <div class="flex flex-col items-center">
                        <x-progress-circle
                            :percentage="$challenge->progress_percentage"
                            :size="100"
                            label="Tasks"
                            color="#0284c7"
                        />
                        <p class="text-sm text-slate-500 mt-4 font-semibold">{{ __('Task Completion') }}</p>
                        <p class="text-base font-black text-slate-900 mt-1.5">
                            {{ $challenge->tasks()->where('status', 'completed')->count() }} / {{ $challenge->tasks()->count() }}
                        </p>
                    </div>

                    <!-- Performance Score -->
                    <div class="flex flex-col items-center">
                        <x-progress-circle
                            :percentage="$challenge->performance_score"
                            :size="100"
                            label="Quality"
                            color="#10b981"
                        />
                        <p class="text-sm text-slate-500 mt-4 font-semibold">{{ __('Performance Score') }}</p>
                        <p class="text-base font-black mt-1.5">
                            @php
                                $healthStatus = $challenge->health_status;
                                $healthColors = [
                                    'on_track' => 'text-emerald-600',
                                    'at_risk' => 'text-amber-600',
                                    'behind' => 'text-red-600'
                                ];
                            @endphp
                            <span class="{{ $healthColors[$healthStatus] ?? 'text-slate-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $healthStatus)) }}
                            </span>
                        </p>
                    </div>

                    <!-- Time Progress -->
                    <div class="flex flex-col items-center">
                        <x-progress-circle
                            :percentage="$challenge->time_based_progress"
                            :size="100"
                            label="Time"
                            color="#a855f7"
                        />
                        <p class="text-sm text-slate-500 mt-4 font-semibold">{{ __('Time Remaining') }}</p>
                        <p class="text-base font-black text-slate-900 mt-1.5">
                            @if($challenge->estimated_remaining_hours > 0)
                                {{ $challenge->estimated_remaining_hours }}{{ __('h left') }}
                            @else
                                <span class="text-emerald-600">{{ __('Done!') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <!-- Enhanced Metadata Section -->
                <div class="flex items-center flex-wrap gap-4 mt-5 text-sm text-slate-600">
                    @if($challenge->complexity_level)
                    <span class="inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        {{ __('Level') }} {{ $challenge->complexity_level }}
                    </span>
                    @endif
                    @if($challenge->challenge_type)
                    <span class="inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-violet-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                    </span>
                    @endif
                    @if($challenge->total_estimated_hours > 0)
                    <span class="inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        {{ __(':hours h total', ['hours' => $challenge->total_estimated_hours]) }}
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $challenge->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Enhanced Empty State -->
        <div class="relative bg-gradient-to-br from-slate-50 via-indigo-50 to-violet-50 rounded-3xl p-16 text-center shadow-sm border border-slate-200 animate-slide-in-up overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-400 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-80 h-80 bg-violet-400 rounded-full blur-3xl"></div>
            </div>

            <!-- Content -->
            <div class="relative">
                <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg transform hover:scale-110 transition-transform duration-300">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">{{ __('No Challenges Yet') }}</h3>
                <p class="text-base text-slate-600 mb-10 max-w-md mx-auto leading-relaxed">{{ __('Start your innovation journey by submitting your first challenge to the community.') }}</p>
                <a href="{{ route('challenges.create') }}" class="group inline-flex items-center justify-center bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold px-10 py-4 rounded-2xl transition-all transform hover:scale-105 shadow-lg hover:shadow-2xl">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Submit Your First Challenge') }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
