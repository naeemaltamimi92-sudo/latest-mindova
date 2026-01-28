@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Back Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('assignments.my') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-gray-700 font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('Back') }}
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 mt-4">
        <!-- Application Status Section -->
        @php
        $myAssignment = $task->assignments->where('volunteer_id', auth()->user()->volunteer?->id)->first();
        @endphp

        @if($myAssignment)
        <div class="bg-white rounded-xl border border-gray-200 mb-6 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        @if($myAssignment->invitation_status === 'accepted')
                            <span class="text-lg">🚀</span>
                        @elseif($myAssignment->invitation_status === 'in_progress')
                            <span class="text-lg">⚡</span>
                        @elseif($myAssignment->invitation_status === 'submitted')
                             <span class="text-lg">📨</span>
                        @elseif($myAssignment->invitation_status === 'completed')
                            <span class="text-lg">🏆</span>
                        @else
                            <span class="text-lg">👋</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ __('Your Assignment') }}</h3>
                        <p class="text-gray-500 text-xs">{{ __('Current Status:') }} <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $myAssignment->invitation_status)) }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($myAssignment->invitation_status === 'invited')
                        <form action="{{ route('assignments.accept', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" size="sm">
                                {{ __('Accept') }}
                            </x-ui.button>
                        </form>
                        <form action="{{ route('assignments.decline', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" variant="ghost" size="sm">
                                {{ __('Decline') }}
                            </x-ui.button>
                        </form>

                    @elseif($myAssignment->invitation_status === 'accepted')
                        <form action="{{ route('assignments.start', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" size="sm">
                                {{ __('Start Working') }}
                            </x-ui.button>
                        </form>

                    @elseif($myAssignment->invitation_status === 'in_progress')
                        <x-ui.button onclick="showSubmitSolutionModal({{ $myAssignment->id }})" size="sm">
                            {{ __('Submit Solution') }}
                        </x-ui.button>

                    @elseif($myAssignment->invitation_status === 'submitted')
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-200">
                            {{ __('Under Review') }}
                        </span>

                    @elseif($myAssignment->invitation_status === 'completed')
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-200">
                            {{ __('Completed') }}
                        </span>
                    @endif
                </div>
            </div>

            @php
                $latestSubmission = $myAssignment->workSubmissions()->latest()->first();
            @endphp

            @if($latestSubmission)
            <div class="p-4 bg-gray-50">
                @if($latestSubmission->status === 'revision_requested')
                <div class="bg-white border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="text-lg">⚠️</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ __('Revision Required') }}</h4>
                            <div class="flex items-center gap-2 mt-1 mb-2">
                                 <div class="h-1.5 w-20 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500" style="width: {{ $latestSubmission->ai_quality_score }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-500">{{ $latestSubmission->ai_quality_score }}%</span>
                            </div>
                            @if($latestSubmission->ai_feedback)
                                <p class="text-sm text-gray-600 mt-2">
                                    @php $feedback = json_decode($latestSubmission->ai_feedback, true); @endphp
                                    {{ is_array($feedback) ? ($feedback['feedback'] ?? $latestSubmission->ai_feedback) : $latestSubmission->ai_feedback }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($latestSubmission->status === 'approved')
                <div class="bg-white border border-emerald-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="text-lg">✅</div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ __('Excellent Work!') }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ __('Your solution has been approved.') }}</p>
                        </div>
                    </div>
                </div>
                @elseif($latestSubmission->status === 'submitted')
                 <div class="bg-white border border-blue-200 rounded-xl p-4 text-center">
                    <div class="inline-block w-5 h-5 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ __('The company is reviewing your submission...') }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Task Description -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-gray-900">{{ __('Task Description') }}</h2>
                                <p class="text-xs text-gray-500">{{ __('Overview of what needs to be done') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                </div>

                <!-- AI-Analyzed Requirements -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-semibold text-gray-900">{{ __('AI-Analyzed Task Requirements') }}</h2>
                                <p class="text-xs text-gray-500">{{ __("Analyzed by AI to help you understand exactly what's needed") }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <!-- Expected Output & Complexity -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- What to Deliver -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ __('What to Deliver') }}</h3>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $task->expected_output }}</p>
                            </div>

                            <!-- Complexity & Priority -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ __('Complexity & Priority') }}</h3>
                                </div>
                                <div class="space-y-3">
                                    <!-- Complexity Level -->
                                    <div>
                                        <p class="text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('Complexity Level') }}</p>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xl font-bold text-gray-900">{{ $task->complexity_score }}</span>
                                            <span class="text-xs text-gray-500">/10</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                            <div class="h-full rounded-full
                                                @if($task->complexity_score <= 3) bg-emerald-500
                                                @elseif($task->complexity_score <= 6) bg-amber-500
                                                @else bg-red-500
                                                @endif"
                                                style="width: {{ ($task->complexity_score / 10) * 100 }}%">
                                            </div>
                                        </div>
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold
                                            @if($task->complexity_score <= 3) bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @elseif($task->complexity_score <= 6) bg-amber-50 text-amber-700 border border-amber-200
                                            @else bg-red-50 text-red-700 border border-red-200
                                            @endif">
                                            @if($task->complexity_score <= 3) {{ __('Simple') }}
                                            @elseif($task->complexity_score <= 6) {{ __('Moderate') }}
                                            @else {{ __('Complex') }}
                                            @endif
                                        </span>
                                    </div>
                                    <!-- Priority -->
                                    <div>
                                        <p class="text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('Priority') }}</p>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold
                                            @if($task->priority === 'critical') bg-red-50 text-red-700 border border-red-200
                                            @elseif($task->priority === 'high') bg-orange-50 text-orange-700 border border-orange-200
                                            @elseif($task->priority === 'medium') bg-blue-50 text-blue-700 border border-blue-200
                                            @else bg-gray-50 text-gray-700 border border-gray-200
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    <!-- Required Experience -->
                                    <div>
                                        <p class="text-[10px] font-semibold text-gray-500 uppercase mb-1">{{ __('Required Experience') }}</p>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded text-xs font-semibold">
                                            {{ $task->required_experience_level }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Criteria -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ __('Success Criteria') }}</h3>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">{{ __('Must meet all criteria') }}</span>
                            </div>
                            <div class="space-y-2">
                                @foreach($task->acceptance_criteria as $criteria)
                                <div class="flex items-start gap-2 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0 w-4 h-4 mt-0.5 border border-emerald-400 rounded bg-white"></div>
                                    <p class="text-sm text-gray-700 flex-1">{{ $criteria }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Required Skills -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 text-sm">{{ __('Required Skills') }}</h3>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($task->required_skills as $skill)
                                <span class="px-2.5 py-1 bg-white text-violet-700 rounded-lg text-xs font-medium border border-violet-200">
                                    {{ $skill }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $isTeamLeader = false;
                    if (auth()->user()->volunteer) {
                        $isTeamLeader = $task->challenge->teams()
                            ->where('leader_id', auth()->user()->volunteer->id)
                            ->exists();
                    }
                @endphp

                @if($isTeamLeader)
                <!-- Team Leader View -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-blue-200 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">{{ __('Team Leader View') }}</h3>
                            <p class="text-xs text-blue-700">{{ __('You can see the full challenge context as a team manager.') }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm mb-0.5">{{ $task->challenge->title }}</h4>
                                <p class="text-xs text-gray-600">{{ Str::limit($task->challenge->refined_brief, 120) }}</p>
                            </div>
                            <x-ui.button as="a" href="{{ route('challenges.show', $task->challenge->id) }}" variant="outline" size="sm">
                                {{ __('View Challenge') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <!-- Task Info Card -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Task Info') }}
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('Estimated Time') }}</p>
                            <p class="text-xl font-bold text-primary-600">{{ $task->estimated_hours }} <span class="text-sm font-medium text-gray-500">{{ __('hours') }}</span></p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('Complexity') }}</p>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xl font-bold text-gray-900">{{ $task->complexity_score }}</span>
                                <span class="text-sm text-gray-500">/10</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                                <div class="h-full rounded-full
                                    @if($task->complexity_score <= 3) bg-emerald-500
                                    @elseif($task->complexity_score <= 6) bg-amber-500
                                    @else bg-red-500
                                    @endif"
                                    style="width: {{ ($task->complexity_score / 10) * 100 }}%">
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold
                                @if($task->complexity_score <= 3) bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($task->complexity_score <= 6) bg-amber-50 text-amber-700 border border-amber-200
                                @else bg-red-50 text-red-700 border border-red-200
                                @endif">
                                @if($task->complexity_score <= 3) {{ __('Simple') }}
                                @elseif($task->complexity_score <= 6) {{ __('Moderate') }}
                                @else {{ __('Complex') }}
                                @endif
                            </span>
                        </div>

                        @if($task->deadline)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('Deadline') }}</p>
                            <p class="text-base font-semibold text-gray-900">{{ $task->deadline->translatedFormat('M d, Y') }}</p>
                        </div>
                        @endif

                        @if($task->workstream)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ __('Workstream') }}</p>
                            <p class="text-base font-semibold text-gray-900">{{ $task->workstream->title }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($task->assignments->where('invitation_status', 'accepted')->count() > 0)
                <!-- Assigned Contributors -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ __('Assigned Contributors') }}
                        </h3>
                    </div>
                    <div class="p-3 space-y-2">
                        @foreach($task->assignments->where('invitation_status', '!=', 'rejected') as $assignment)
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 font-semibold text-sm">
                                {{ substr($assignment->volunteer->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $assignment->volunteer->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($assignment->invitation_status) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Submit Solution Modal -->
<div id="submitSolutionModal" class="hidden fixed inset-0 bg-gray-900/70 z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-0 w-full max-w-2xl px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Submit Your Solution') }}</h3>
                <p class="text-gray-500 text-sm mt-0.5">{{ __('Describe your work and submit for AI review') }}</p>
            </div>
            <form id="submitSolutionForm" method="POST" action="" enctype="multipart/form-data" class="p-6">
                @csrf

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-4">
                    <p class="font-semibold text-sm mb-2">{{ __('Please fix the following errors:') }}</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Solution Description') }} *</label>
                        <textarea name="description" rows="4" required class="w-full rounded-lg border-gray-300 text-sm p-3"
                                  placeholder="{{ __('Describe your solution, the approach you took, and any key decisions...') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Explain what you built and how it solves the task') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Deliverable URL') }}</label>
                        <input type="url" name="deliverable_url" class="w-full rounded-lg border-gray-300 text-sm p-3"
                               placeholder="https://github.com/username/repo">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Link to your code repository, demo, or deliverable') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Attachments (Optional)') }}</label>
                        <input type="file" name="attachments[]" multiple class="w-full text-sm" accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg">
                        <p class="text-xs text-gray-500 mt-1">{{ __('Upload supporting files (max 10MB each)') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Hours Worked') }} *</label>
                        <input type="number" name="hours_worked" step="1" min="1" required class="w-full rounded-lg border-gray-300 text-sm p-3"
                               placeholder="{{ __('e.g., 5') }}">
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <strong>{{ __('Note:') }}</strong> {{ __('Your solution will be reviewed by the company to assess quality and completeness. High-quality solutions that solve the task will be combined with other team members\' work and presented to the challenge owner.') }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                    <x-ui.button type="button" onclick="closeSubmitSolutionModal()" variant="ghost" size="sm">{{ __('Cancel') }}</x-ui.button>
                    <x-ui.button type="submit" size="sm">{{ __('Submit Solution') }}</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showSubmitSolutionModal(assignmentId) {
    const baseUrl = '{{ url("/") }}';
    document.getElementById('submitSolutionForm').action = `${baseUrl}/assignments/${assignmentId}/submit-solution`;
    document.getElementById('submitSolutionModal').classList.remove('hidden');
}

function closeSubmitSolutionModal() {
    document.getElementById('submitSolutionModal').classList.add('hidden');
}

// Auto-open modal if there are errors
@if(session('error') || $errors->any())
document.addEventListener('DOMContentLoaded', function() {
    @if($myAssignment)
    showSubmitSolutionModal({{ $myAssignment->id }});
    @endif
});
@endif
</script>
@endsection
