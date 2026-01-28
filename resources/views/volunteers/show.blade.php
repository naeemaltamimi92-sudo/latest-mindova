@extends('layouts.app')

@section('title', $volunteer->user->name . ' - ' . __('Contributor Profile'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-5">
                    <!-- Profile Avatar -->
                    <div class="w-20 h-20 bg-primary-100 rounded-xl flex items-center justify-center border-2 border-primary-200">
                        <span class="text-2xl font-bold text-primary-700">{{ substr($volunteer->user->name, 0, 2) }}</span>
                    </div>

                    <!-- Volunteer Info -->
                    <div class="flex-1 text-center lg:text-left">
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                {{ __('Active Contributor') }}
                            </span>
                            @if($volunteer->availability_hours_per_week >= 20)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('High Availability') }}
                            </span>
                            @endif
                        </div>

                        <h1 class="text-xl font-bold text-gray-900 mb-2">{{ $volunteer->user->name }}</h1>

                        @if($volunteer->bio)
                        <p class="text-sm text-gray-600 max-w-2xl leading-relaxed mb-4">{{ Str::limit($volunteer->bio, 200) }}</p>
                        @endif

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                            @if($volunteer->user->linkedin_profile_url)
                            <x-ui.button as="a" href="{{ $volunteer->user->linkedin_profile_url }}" target="_blank" rel="noopener noreferrer" variant="ghost" size="sm">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                                {{ __('LinkedIn') }}
                            </x-ui.button>
                            @endif
                            <div class="inline-flex items-center gap-1.5 text-gray-500 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $volunteer->availability_hours_per_week }}{{ __('h/week') }}
                            </div>
                        </div>
                    </div>

                    <!-- Reputation Score Card -->
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 text-center min-w-[100px]">
                        <div class="text-2xl font-bold text-primary-700 mb-0.5">{{ $volunteer->reputation_score }}</div>
                        <div class="text-xs text-primary-600 font-medium">{{ __('Reputation') }}</div>
                        <div class="w-full bg-primary-200 rounded-full h-1.5 mt-2">
                            <div class="bg-primary-500 h-1.5 rounded-full" style="width: {{ min(($volunteer->reputation_score / 1000) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-gray-900">{{ $stats['completed_tasks'] }}</div>
                    <div class="text-xs text-gray-500">{{ __('Tasks Done') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-primary-600">{{ $stats['active_tasks'] }}</div>
                    <div class="text-xs text-gray-500">{{ __('Active Tasks') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-violet-600">{{ $stats['total_hours'] }}</div>
                    <div class="text-xs text-gray-500">{{ __('Hours') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-bold text-amber-600">{{ $stats['ideas_count'] }}</div>
                    <div class="text-xs text-gray-500">{{ __('Ideas') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Skills & Expertise -->
            @if($volunteer->skills->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900 text-sm">{{ __('Skills & Expertise') }}</h2>
                    <span class="text-xs text-gray-400">({{ $volunteer->skills->count() }})</span>
                </div>

                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($volunteer->skills as $skill)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $skill->skill_name }}</h3>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded border
                                    {{ $skill->proficiency_level === 'expert' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $skill->proficiency_level === 'advanced' ? 'bg-primary-50 text-primary-700 border-primary-200' : '' }}
                                    {{ $skill->proficiency_level === 'intermediate' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $skill->proficiency_level === 'beginner' ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}">
                                    {{ ucfirst($skill->proficiency_level) }}
                                </span>
                            </div>
                            @if($skill->years_of_experience)
                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $skill->years_of_experience }} {{ __('years') }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Completed Tasks -->
            @if($completedAssignments->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900 text-sm">{{ __('Completed Work') }}</h2>
                    <span class="text-xs text-gray-400">({{ $completedAssignments->count() }})</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($completedAssignments as $assignment)
                    <div class="px-4 py-4 hover:bg-gray-50/50">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-sm font-bold text-gray-900">{{ $assignment->task->title }}</h3>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Completed') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->actual_hours ?? 0 }}h {{ __('logged') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $assignment->updated_at->format('M d, Y') }}
                                    </span>
                                </div>
                                @if($assignment->completion_notes)
                                <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ Str::limit($assignment->completion_notes, 150) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Community Ideas -->
            @if($ideas->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <h2 class="font-semibold text-gray-900 text-sm">{{ __('Community Ideas') }}</h2>
                    <span class="text-xs text-gray-400">({{ $ideas->count() }})</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($ideas as $idea)
                    <a href="{{ route('ideas.show', $idea->id) }}" class="block px-4 py-4 hover:bg-gray-50/50">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-gray-900 hover:text-primary-600 mb-1">{{ $idea->title }}</h3>
                                <p class="text-xs text-gray-600 mb-2">{{ $idea->challenge->title }}</p>
                                <p class="text-gray-600 text-xs leading-relaxed line-clamp-2 mb-3">{{ Str::limit($idea->description, 150) }}</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                    @if($idea->status === 'scored')
                                    <span class="flex items-center gap-1 text-primary-600 font-semibold">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('AI:') }} {{ round($idea->ai_score) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 {{ $idea->community_votes >= 0 ? 'text-emerald-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $idea->community_votes >= 0 ? '+' : '' }}{{ $idea->community_votes }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $idea->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            @if($idea->status === 'scored')
                            <div class="bg-primary-100 rounded-lg p-2 text-center min-w-[60px] border border-primary-200">
                                <div class="text-lg font-bold text-primary-700">{{ round($idea->final_score) }}</div>
                                <div class="text-[10px] text-primary-600">{{ __('Score') }}</div>
                            </div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Reputation Breakdown -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <h3 class="font-semibold text-gray-900 text-sm">{{ __('Reputation Level') }}</h3>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-xs font-medium text-gray-600">{{ __('Progress') }}</span>
                            <span class="text-xs font-semibold text-primary-600">{{ $volunteer->reputation_score }} / 1000</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full">
                            <div class="h-full bg-primary-500 rounded-full" style="width: {{ min(($volunteer->reputation_score / 1000) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-emerald-100 rounded flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ __('Tasks') }}</span>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600">+{{ $stats['completed_tasks'] * 10 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-primary-50 rounded-lg border border-primary-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-primary-100 rounded flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ __('Ratings') }}</span>
                            </div>
                            <span class="text-xs font-semibold text-primary-600">+{{ $stats['rating_points'] ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-amber-50 rounded-lg border border-amber-100">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-amber-100 rounded flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ __('Idea Votes') }}</span>
                            </div>
                            <span class="text-xs font-semibold text-amber-600">+{{ $stats['vote_points'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Info -->
            <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-900 text-sm">{{ __('Member Since') }}</h3>
                        <p class="text-primary-700 text-xs">{{ $volunteer->created_at->format('F Y') }}</p>
                    </div>
                </div>
                <p class="text-xs text-primary-600">
                    {{ __('Active for :time', ['time' => $volunteer->created_at->diffForHumans(['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW])]) }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
