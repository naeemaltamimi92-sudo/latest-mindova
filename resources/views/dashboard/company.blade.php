@extends('layouts.app')

@section('title', __('Dashboard'))

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Stats Overview --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            {{-- Total Challenges --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $company->total_challenges_submitted }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Challenges') }}</p>
            </div>

            {{-- Active Challenges --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active_challenges'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Active') }}</p>
            </div>

            {{-- Tasks in Progress --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['tasks_in_progress'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('In Progress') }}</p>
            </div>

            {{-- Completed Tasks --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Completed') }}</p>
            </div>

            {{-- Volunteers Working --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_volunteers_working'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Contributors') }}</p>
            </div>

            {{-- Solutions Received --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_approved_solutions'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Solutions') }}</p>
            </div>
        </div>

        {{-- Latest Challenge Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Latest Challenge') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('Your most recently submitted challenge') }}</p>
                </div>
                <a href="{{ route('challenges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 transition-colors">
                    {{ __('View All') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($challenges->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                @foreach($challenges as $challenge)
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $challenge->title }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium border
                                    @if($challenge->status === 'active') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($challenge->status === 'completed') bg-violet-50 text-violet-700 border-violet-200
                                    @elseif($challenge->status === 'analyzing') bg-amber-50 text-amber-700 border-amber-200
                                    @elseif($challenge->status === 'submitted') bg-blue-50 text-blue-700 border-blue-200
                                    @else bg-gray-50 text-gray-700 border-gray-200
                                    @endif">
                                    {{ ucfirst($challenge->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 200) }}</p>
                        </div>
                        <a href="{{ route('challenges.show', $challenge->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors whitespace-nowrap">
                            {{ __('View Details') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    @php
                        $hasAnalysis = $challenge->refined_brief !== null;
                        $hasComplexity = $challenge->score !== null;
                        $hasTasks = $challenge->tasks()->count() > 0;
                        $hasAssignments = $challenge->tasks()->whereHas('assignments')->count() > 0;
                    @endphp

                    {{-- AI Workflow Progress --}}
                    @if($challenge->status === 'analyzing' || $challenge->status === 'submitted' || !$hasTasks)
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            {{ __('AI Analysis Workflow') }}
                        </h4>
                        <div class="space-y-2">
                            {{-- Step 1: Brief Analysis --}}
                            <div class="flex items-center text-sm">
                                @if($hasAnalysis)
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-emerald-700 font-medium">{{ __('Brief Analyzed & Refined') }}</span>
                                @else
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="text-amber-700">{{ __('Analyzing Challenge Brief...') }}</span>
                                @endif
                            </div>

                            {{-- Step 2: Complexity Evaluation --}}
                            <div class="flex items-center text-sm">
                                @if($hasComplexity)
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-emerald-700 font-medium">{{ __('Complexity Evaluated (Score: :score)', ['score' => $challenge->score]) }}</span>
                                @elseif($hasAnalysis)
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="text-amber-700">{{ __('Evaluating Complexity...') }}</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                    </svg>
                                    <span class="text-gray-500">{{ __('Complexity Evaluation (Pending)') }}</span>
                                @endif
                            </div>

                            {{-- Step 3: Task Decomposition --}}
                            <div class="flex items-center text-sm">
                                @if($hasTasks)
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-emerald-700 font-medium">{{ __('Tasks Created (:count tasks)', ['count' => $challenge->tasks()->count()]) }}</span>
                                @elseif($hasComplexity)
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="text-amber-700">{{ __('Decomposing into Tasks...') }}</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                    </svg>
                                    <span class="text-gray-500">{{ __('Task Decomposition (Pending)') }}</span>
                                @endif
                            </div>

                            {{-- Step 4: Volunteer Matching --}}
                            <div class="flex items-center text-sm">
                                @if($hasAssignments)
                                    <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span class="text-emerald-700 font-medium">{{ __('Volunteers Matched & Invited') }}</span>
                                @elseif($hasTasks)
                                    <svg class="w-4 h-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="text-amber-700">{{ __('Matching Volunteers...') }}</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h4a1 1 0 100-2H8z"/>
                                    </svg>
                                    <span class="text-gray-500">{{ __('Volunteer Matching (Pending)') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Solution Progress Section --}}
                    @if($challenge->tasks()->count() > 0 && ($challenge->status === 'active' || $challenge->status === 'in_progress'))
                    <div class="bg-emerald-50 rounded-lg border border-emerald-200 p-4 mb-4">
                        <h4 class="text-sm font-medium text-emerald-900 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Solution Progress') }}
                        </h4>

                        {{-- Progress Stats Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500">{{ __('Working') }}</span>
                                </div>
                                <p class="text-xl font-bold text-gray-900">{{ $challenge->active_volunteers_count }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 bg-amber-100 rounded flex items-center justify-center">
                                        <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500">{{ __('Invited') }}</span>
                                </div>
                                <p class="text-xl font-bold text-gray-900">{{ $challenge->invited_volunteers_count }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 bg-violet-100 rounded flex items-center justify-center">
                                        <svg class="w-3 h-3 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500">{{ __('Submissions') }}</span>
                                </div>
                                <p class="text-xl font-bold text-gray-900">{{ $challenge->submissions_count }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 bg-emerald-100 rounded flex items-center justify-center">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500">{{ __('Approved') }}</span>
                                </div>
                                <p class="text-xl font-bold text-emerald-600">{{ $challenge->approved_submissions_count }}</p>
                            </div>
                        </div>

                        {{-- Tasks with Solutions & Quality Score --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">{{ __('Tasks with Solutions') }}</span>
                                    <span class="text-sm font-bold text-emerald-600">{{ $challenge->tasks_with_solutions }}/{{ $challenge->tasks()->count() }}</span>
                                </div>
                                @php
                                    $solutionPercentage = $challenge->tasks()->count() > 0
                                        ? round(($challenge->tasks_with_solutions / $challenge->tasks()->count()) * 100)
                                        : 0;

                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-full rounded-full bg-primary-500" style="width: {{ $solutionPercentage }}%"></div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">{{ __('Average Quality Score') }}</span>
                                    @if($challenge->avg_submission_quality)
                                        <span class="text-sm font-bold
                                            @if($challenge->avg_submission_quality >= 80) text-emerald-600
                                            @elseif($challenge->avg_submission_quality >= 60) text-blue-600
                                            @elseif($challenge->avg_submission_quality >= 40) text-amber-600
                                            @else text-red-600
                                            @endif">
                                            {{ $challenge->avg_submission_quality }}/100
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">{{ __('N/A') }}</span>
                                    @endif
                                </div>
                                @if($challenge->avg_submission_quality)
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-full rounded-full
                                            @if($challenge->avg_submission_quality >= 8) bg-emerald-500
                                            @elseif($challenge->avg_submission_quality >= 6) bg-blue-500
                                            @elseif($challenge->avg_submission_quality >= 4) bg-amber-500
                                            @else bg-red-500
                                            @endif" 
                                            style="width: {{ ($challenge->avg_submission_quality / 10) * 100 }}%"></div>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400">{{ __('No submissions scored yet') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($challenge->submissions_count > 0)
                        <div class="mt-3 pt-3 border-t border-emerald-200">
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="inline-flex items-center text-sm font-medium text-emerald-700 hover:text-emerald-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View All Submissions') }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Progress Metrics with Circular Charts --}}
                    @if($challenge->tasks()->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-4">
                        <div class="flex flex-col items-center">
                            <x-progress-circle
                                :percentage="$challenge->progress_percentage"
                                :size="80"
                                
                                color="#5A3DEB"
                            />
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('Task Completion') }}</p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ $challenge->tasks()->where('status', 'completed')->count() }} / {{ $challenge->tasks()->count() }}
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <x-progress-circle
                                :percentage="$challenge->performance_score"
                                :size="80"
                                
                                color="#10b981"
                            />
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('Performance Score') }}</p>
                            @php
                                $healthStatus = $challenge->health_status;
                                $healthColors = [
                                    'on_track' => 'text-emerald-600',
                                    'at_risk' => 'text-amber-600',
                                    'behind' => 'text-red-600'
                                ];
                            @endphp
                            <p class="text-sm font-bold {{ $healthColors[$healthStatus] ?? 'text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $healthStatus)) }}
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <x-progress-circle
                                :percentage="$challenge->time_based_progress"
                                :size="80"
                                
                                color="#8b5cf6"
                            />
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('Time Remaining') }}</p>
                            <p class="text-sm font-bold text-gray-900">
                                @if($challenge->estimated_remaining_hours > 0)
                                    {{ $challenge->estimated_remaining_hours }}{{ __('h left') }}
                                @else
                                    <span class="text-emerald-600">{{ __('Done!') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="flex items-center flex-wrap gap-4 text-xs text-gray-600">
                        @if($challenge->complexity_level)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            {{ __('Level') }} {{ $challenge->complexity_level }}
                        </span>
                        @endif
                        @if($challenge->challenge_type)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                        </span>
                        @endif
                        @if($challenge->total_estimated_hours > 0)
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            {{ __(':hours h total', ['hours' => $challenge->total_estimated_hours]) }}
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $challenge->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Challenges Yet') }}</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ __('Start your innovation journey by submitting your first challenge to the community.') }}</p>
                <a href="{{ route('challenges.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Submit Your First Challenge') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
