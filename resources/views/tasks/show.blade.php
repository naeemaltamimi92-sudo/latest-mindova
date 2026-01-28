@extends('layouts.app')

@section('title', $task->title)

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .pulse-ring { animation: pulseRing 2s-out infinite; }
    @keyframes pulseRing {
        0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        50% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
    }
    .requirement-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .requirement-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.15); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Back Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 slide-up">
        <a href="{{ route('assignments.my') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('Back') }}
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 mt-6">
        <!-- Application Status Section (Flat Design) -->
        @php
        $myAssignment = $task->assignments->where('volunteer_id', auth()->user()->volunteer?->id)->first();
        @endphp

        @if($myAssignment)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm mb-8 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100">
                        @if($myAssignment->invitation_status === 'accepted')
                            <span class="text-2xl">🚀</span>
                        @elseif($myAssignment->invitation_status === 'in_progress')
                            <span class="text-2xl">⚡</span>
                        @elseif($myAssignment->invitation_status === 'submitted')
                             <span class="text-2xl">📨</span>
                        @elseif($myAssignment->invitation_status === 'completed')
                            <span class="text-2xl">🏆</span>
                        @else
                            <span class="text-2xl">👋</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ __('Your Assignment') }}</h3>
                        <p class="text-slate-500 text-sm">{{ __('Current Status:') }} <span class="font-semibold text-slate-700">{{ ucfirst(str_replace('_', ' ', $myAssignment->invitation_status)) }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($myAssignment->invitation_status === 'invited')
                        <form action="{{ route('assignments.accept', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" variant="primary" size="sm">
                                {{ __('Accept Assignment') }}
                            </x-ui.button>
                        </form>
                        <form action="{{ route('assignments.decline', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" variant="outline" size="sm">
                                {{ __('Decline') }}
                            </x-ui.button>
                        </form>

                    @elseif($myAssignment->invitation_status === 'accepted')
                        <form action="{{ route('assignments.start', $myAssignment->id) }}" method="POST" class="inline-block">
                            @csrf
                            <x-ui.button as="submit" variant="primary" size="sm">
                                {{ __('Start Working') }}
                            </x-ui.button>
                        </form>

                    @elseif($myAssignment->invitation_status === 'in_progress')
                        <x-ui.button onclick="showSubmitSolutionModal({{ $myAssignment->id }})" class="!text-white cursor-pointer" variant="primary" size="sm">
                            {{ __('Submit Solution') }}
                        </x-ui.button>

                    @elseif($myAssignment->invitation_status === 'submitted')
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm font-semibold border border-blue-100">
                            {{ __('Under Review') }}
                        </span>

                    @elseif($myAssignment->invitation_status === 'completed')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold border border-emerald-100">
                            {{ __('Completed') }}
                        </span>
                    @endif
                </div>
            </div>

            @php
                $latestSubmission = $myAssignment->workSubmissions()->latest()->first();
            @endphp

            @if($latestSubmission)
            <div class="p-6 bg-slate-50/50">
                @if($latestSubmission->status === 'revision_requested')
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 bg-white border border-orange-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">⚠️</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ __('Revision Required') }}</h4>
                                <div class="flex items-center gap-2 mt-1 mb-2">
                                     <div class="h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-500" style="width: {{ $latestSubmission->ai_quality_score }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-500">{{ $latestSubmission->ai_quality_score }}% Score</span>
                                </div>
                                @if($latestSubmission->ai_feedback)
                                    <p class="text-sm text-slate-600 leading-relaxed mt-2">
                                        @php $feedback = json_decode($latestSubmission->ai_feedback, true); @endphp
                                        {{ is_array($feedback) ? ($feedback['feedback'] ?? $latestSubmission->ai_feedback) : $latestSubmission->ai_feedback }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($latestSubmission->status === 'approved')
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 bg-white border border-emerald-200 rounded-xl p-5 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">✅</div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ __('Excellent Work!') }}</h4>
                                <p class="text-sm text-slate-600 mt-1">{{ __('Your solution has been approved.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($latestSubmission->status === 'submitted')
                 <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 bg-white border border-blue-200 rounded-xl p-5 shadow-sm text-center">
                        <div class="inline-block w-6 h-6 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 mt-2">{{ __('The company is reviewing your submission...') }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">


                <!-- Task Description -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.2s">
                    <div class="bg-gray-50 px-8 py-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ __('Task Description') }}</h2>
                                <p class="text-sm text-slate-600">{{ __('Overview of what needs to be done') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                </div>

                <!-- AI-Analyzed Requirements -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.3s">
                    <div class="bg-gray-50 px-8 py-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg pulse-ring">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">{{ __('AI-Analyzed Task Requirements') }}</h2>
                                <p class="text-sm text-slate-600">{{ __("Analyzed by AI to help you understand exactly what's needed") }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <!-- Expected Output & Complexity -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- What to Deliver -->
                            <div class="requirement-card bg-gray-50 rounded-2xl p-6 border border-emerald-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-900">{{ __('What to Deliver') }}</h3>
                                </div>
                                <p class="text-slate-700 text-sm leading-relaxed">{{ $task->expected_output }}</p>
                            </div>

                            <!-- Complexity & Priority -->
                            <div class="requirement-card bg-gray-50 rounded-2xl p-6 border border-slate-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-900">{{ __('Complexity & Priority') }}</h3>
                                </div>
                                <div class="space-y-4">
                                    <!-- Complexity Level -->
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">{{ __('Complexity Level') }}</p>
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-2xl font-black text-slate-900">{{ $task->complexity_score }}</span>
                                            <span class="text-sm text-slate-500">/10</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden mb-2">
                                            <div class="h-full rounded-full
                                                @if($task->complexity_score <= 3) bg-secondary-500
                                                @elseif($task->complexity_score <= 6) bg-secondary-300
                                                @elseif($task->complexity_score <= 8) bg-secondary-300
                                                @else bg-secondary-700
                                                @endif"
                                                style="width: {{ ($task->complexity_score / 10) * 100 }}%">
                                            </div>
                                        </div>
                                        <span class="inline-flex px-3 py-1 rounded-lg text-xs font-bold
                                            @if($task->complexity_score <= 3) bg-emerald-100 text-emerald-700 border border-emerald-200
                                            @elseif($task->complexity_score <= 6) bg-amber-100 text-amber-700 border border-amber-200
                                            @elseif($task->complexity_score <= 8) bg-orange-100 text-orange-700 border border-orange-200
                                            @else bg-red-100 text-red-700 border border-red-200
                                            @endif">
                                            @if($task->complexity_score <= 3) {{ __('Simple') }}
                                            @elseif($task->complexity_score <= 6) {{ __('Moderate') }}
                                            @elseif($task->complexity_score <= 8) {{ __('Complex') }}
                                            @else {{ __('Advanced') }}
                                            @endif
                                        </span>
                                    </div>
                                    <!-- Priority -->
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">{{ __('Priority') }}</p>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold
                                            @if($task->priority === 'critical') bg-red-100 text-red-700 border border-red-200
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-700 border border-orange-200
                                            @elseif($task->priority === 'medium') bg-blue-100 text-blue-700 border border-blue-200
                                            @else bg-slate-100 text-slate-700 border border-slate-200
                                            @endif">
                                            @if($task->priority === 'critical') 🔥
                                            @elseif($task->priority === 'high') ⚡
                                            @elseif($task->priority === 'medium') 📌
                                            @else 📋
                                            @endif
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    <!-- Required Experience -->
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">{{ __('Required Experience') }}</p>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-sm font-bold">
                                            🎯 {{ $task->required_experience_level }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Criteria -->
                        <div class="requirement-card bg-gray-50 rounded-2xl p-6 border border-green-100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-900">{{ __('Success Criteria') }}</h3>
                                </div>
                                <span class="text-xs text-slate-500 font-medium">{{ __('Must meet all criteria') }}</span>
                            </div>
                            <div class="space-y-3">
                                @foreach($task->acceptance_criteria as $criteria)
                                <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-green-200 hover:border-green-300">
                                    <div class="flex-shrink-0 w-5 h-5 mt-0.5 border-2 border-green-500 rounded bg-white"></div>
                                    <p class="text-sm text-slate-700 flex-1">{{ $criteria }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Required Skills -->
                        <div class="requirement-card bg-gray-50 rounded-2xl p-6 border border-violet-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-900">{{ __('Required Skills') }}</h3>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($task->required_skills as $skill)
                                <span class="px-4 py-2 bg-white text-violet-700 rounded-xl text-sm font-semibold border border-violet-200 shadow-sm hover:shadow-md">
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
                <div class="bg-gray-50 rounded-3xl shadow-lg border border-blue-200 overflow-hidden slide-up" style="animation-delay: 0.35s">
                    <div class="px-8 py-6 border-b border-blue-200 flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ __('Team Leader View') }}</h3>
                            <p class="text-sm text-blue-700">{{ __('You can see the full challenge context as a team manager.') }}</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">{{ $task->challenge->title }}</h4>
                                <p class="text-sm text-slate-600">{{ Str::limit($task->challenge->refined_brief, 150) }}</p>
                            </div>
                            <x-ui.button as="a" href="{{ route('challenges.show', $task->challenge->id) }}" variant="outline">
                                {{ __('View Challenge') }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </x-ui.button>
                        </div>
                    </div>
                </div>
                @endif


            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Task Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.25s">
                    <div class="bg-gray-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Task Info') }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Estimated Time') }}</p>
                            <p class="text-2xl font-black text-indigo-600">{{ $task->estimated_hours }} <span class="text-base font-medium text-slate-500">{{ __('hours') }}</span></p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">{{ __('Complexity') }}</p>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-2xl font-black text-slate-900">{{ $task->complexity_score }}</span>
                                <span class="text-sm text-slate-500">/10</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden mb-2">
                                <div class="h-full rounded-full
                                    @if($task->complexity_score <= 3) bg-secondary-500
                                    @elseif($task->complexity_score <= 6) bg-secondary-300
                                    @elseif($task->complexity_score <= 8) bg-secondary-300
                                    @else bg-secondary-700
                                    @endif"
                                    style="width: {{ ($task->complexity_score / 10) * 100 }}%">
                                </div>
                            </div>
                            <span class="inline-flex px-3 py-1 rounded-lg text-xs font-bold
                                @if($task->complexity_score <= 3) bg-emerald-100 text-emerald-700 border border-emerald-200
                                @elseif($task->complexity_score <= 6) bg-amber-100 text-amber-700 border border-amber-200
                                @elseif($task->complexity_score <= 8) bg-orange-100 text-orange-700 border border-orange-200
                                @else bg-red-100 text-red-700 border border-red-200
                                @endif">
                                @if($task->complexity_score <= 3) {{ __('Simple') }}
                                @elseif($task->complexity_score <= 6) {{ __('Moderate') }}
                                @elseif($task->complexity_score <= 8) {{ __('Complex') }}
                                @else {{ __('Advanced') }}
                                @endif
                            </span>
                        </div>

                        @if($task->deadline)
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Deadline') }}</p>
                            <p class="text-lg font-bold text-slate-900">{{ $task->deadline->translatedFormat('M d, Y') }}</p>
                        </div>
                        @endif

                        @if($task->workstream)
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">{{ __('Workstream') }}</p>
                            <p class="text-lg font-bold text-slate-900">{{ $task->workstream->title }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($task->assignments->where('invitation_status', 'accepted')->count() > 0)
                <!-- Assigned Contributors -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.3s">
                    <div class="bg-gray-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ __('Assigned Contributors') }}
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($task->assignments->where('invitation_status', '!=', 'rejected') as $assignment)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100">
                            <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                                {{ substr($assignment->volunteer->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-900">{{ $assignment->volunteer->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ ucfirst($assignment->invitation_status) }}</p>
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
<div id="submitSolutionModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-0 w-full max-w-2xl">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="bg-gray-100 px-8 py-6">
                <h3 class="text-xl font-bold ">{{ __('Submit Your Solution') }}</h3>
                <p class="text-indigo-100 text-sm mt-1">{{ __('Describe your work and submit for AI review') }}</p>
            </div>
            <form id="submitSolutionForm" method="POST" action="" enctype="multipart/form-data" class="p-8">
                @csrf

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-4">
                    <p class="font-bold mb-2">{{ __('Please fix the following errors:') }}</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Solution Description') }} *</label>
                        <textarea name="description" rows="4" required class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2"
                                  placeholder="{{ __('Describe your solution, the approach you took, and any key decisions...') }}"></textarea>
                        <p class="text-xs text-slate-500 mt-1">{{ __('Explain what you built and how it solves the task') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Deliverable URL') }}</label>
                        <input type="url" name="deliverable_url" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2"
                               placeholder="https://github.com/username/repo">
                        <p class="text-xs text-slate-500 mt-1">{{ __('Link to your code repository, demo, or deliverable') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Attachments (Optional)') }}</label>
                        <input type="file" name="attachments[]" multiple class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg">
                        <p class="text-xs text-slate-500 mt-1">{{ __('Upload supporting files (max 10MB each)') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Hours Worked') }} *</label>
                        <input type="number" name="hours_worked" step="1" min="1" required class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2"
                               placeholder="{{ __('e.g., 5') }}">
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 border border-blue-200">
                        <p class="text-sm text-blue-900">
                            <strong class="text-indigo-700">📝 {{ __('Note:') }}</strong> {{ __('Your solution will be reviewed by the company to assess quality and completeness. High-quality solutions that solve the task will be combined with other team members\' work and presented to the challenge owner.') }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                    <x-ui.button type="button" onclick="closeSubmitSolutionModal()" variant="ghost">{{ __('Cancel') }}</x-ui.button>
                    <x-ui.button type="submit" variant="secondary">{{ __('Submit Solution') }}</x-ui.button>
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
