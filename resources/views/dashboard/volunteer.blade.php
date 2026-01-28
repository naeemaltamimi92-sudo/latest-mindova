@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<!-- CV Analysis Notification Modal -->
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'pending')
<div id="cvAnalysisModal" class="fixed inset-0 bg-gray-900/70 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full overflow-hidden border border-gray-200 shadow-xl">
        <!-- Modal Header -->
        <div class="bg-primary-500 px-6 py-5 text-center">
            <div class="w-16 h-16 mx-auto mb-3 bg-white rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white mb-1">{{ __('Analyzing Your CV') }}</h2>
            <p class="text-white/90 text-sm">{{ __('Our AI is extracting your skills and experience') }}</p>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-6">
            <div class="text-center mb-5">
                <div class="inline-flex items-center justify-center bg-gray-50 border border-gray-200 rounded-xl px-6 py-3">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600" id="countdownTimer">2:00</div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-1">{{ __('Estimated Time') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-5">
                <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <div class="w-7 h-7 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-emerald-800 text-sm">{{ __('CV Uploaded Successfully') }}</p>
                        <p class="text-xs text-emerald-600">{{ __('Your document is ready for processing') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-primary-50 border border-primary-200 rounded-lg">
                    <div class="w-7 h-7 bg-primary-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-primary-800 text-sm">{{ __('AI Analysis in Progress') }}</p>
                        <p class="text-xs text-primary-600">{{ __('Extracting skills, education, and experience...') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg opacity-60">
                    <div class="w-7 h-7 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xs">3</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-500 text-sm">{{ __('Profile Updated') }}</p>
                        <p class="text-xs text-gray-400">{{ __('Your profile will be enhanced with extracted data') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-amber-800">
                        {{ __('The page will automatically refresh when analysis is complete.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <x-ui.button onclick="closeModal()" fullWidth>
                {{ __('Continue to Dashboard') }}
            </x-ui.button>
        </div>
    </div>
</div>

<script>
let timeLeft = 120;
const timerElement = document.getElementById('countdownTimer');
const modal = document.getElementById('cvAnalysisModal');

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    if (timeLeft > 0) {
        timeLeft--;
        setTimeout(updateTimer, 1000);
    } else {
        location.reload();
    }
}

function closeModal() {
    modal.style.display = 'none';
}

updateTimer();
setTimeout(() => { if (modal.style.display !== 'none') closeModal(); }, 10000);
</script>
@endif

<!-- CV Processing Banner -->
@if($volunteer->cv_file_path && $volunteer->ai_analysis_status === 'processing')
<div class="bg-amber-50 border-b border-amber-200 py-4 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-amber-900 text-sm">{{ __('CV Analysis in Progress') }}</p>
                <p class="text-xs text-amber-700">{{ __('Your skills are being extracted. Page will refresh automatically.') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-amber-600">{{ __('Est. time') }}</p>
            <p class="font-semibold text-amber-900 text-sm">~1 min</p>
        </div>
    </div>
</div>
<script>setTimeout(() => location.reload(), 60000);</script>
@endif

<div class="max-w-7xl mx-auto px-4 lg:px-8 pb-12 pt-6">
    <!-- Primary Action Card -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('My Task Invitations & Progress') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('View and manage your assignments') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if(($stats['pending_assignments'] ?? 0) > 0)
                <span class="px-3 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg font-semibold text-xs">
                    {{ $stats['pending_assignments'] }} {{ __('New') }}
                </span>
                @endif
                @if(isset($activeTasks) && $activeTasks->count() > 0)
                <span class="px-3 py-1.5 bg-primary-50 border border-primary-200 text-primary-700 rounded-lg font-semibold text-xs">
                    {{ $activeTasks->count() }} {{ __('Active') }}
                </span>
                @endif
                <x-ui.button as="a" href="{{ route('assignments.my') }}" size="sm">
                    {{ __('View All Tasks') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Team Invitations -->
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('Team Invitations') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['team_invitations'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            @if(($stats['team_invitations'] ?? 0) > 0)
            <a href="{{ route('teams.my') }}" class="text-xs text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center gap-1">
                {{ __('View invitations') }}
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @else
            <p class="text-xs text-gray-400">{{ __('No pending invitations') }}</p>
            @endif
        </div>

        <!-- Task Assignments -->
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('Task Invitations') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending_assignments'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
            @if(($stats['pending_assignments'] ?? 0) > 0)
            <p class="text-xs text-primary-600 font-semibold">{{ __('Awaiting response') }}</p>
            @else
            <p class="text-xs text-gray-400">{{ __('No pending tasks') }}</p>
            @endif
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('Completed Tasks') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['completed_tasks'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ __('Successfully finished') }}
            </p>
        </div>

        <!-- Reputation Score -->
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('Reputation Score') }}</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $volunteer->reputation_score ?? 50 }}</h3>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400">{{ __('Community standing') }}</p>
        </div>
    </div>

    <!-- Team Invitations Section -->
    @if(isset($teamInvitations) && $teamInvitations->count() > 0)
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ __('Team Invitations') }}</h2>
            </div>
            <a href="{{ route('teams.my') }}" class="text-xs text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center gap-1">
                {{ __('View all') }}
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        <div class="space-y-3">
            @foreach($teamInvitations->take(2) as $team)
            @php $myMembership = $team->members->where('volunteer_id', $volunteer->id)->first(); @endphp
            <div class="bg-violet-50 border border-violet-200 rounded-xl p-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-gray-900">{{ $team->name }}</h3>
                            @if($myMembership->role === 'leader')
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-[10px] font-bold">{{ __('Leader') }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-1">{{ Str::limit($team->description, 100) }}</p>
                        <p class="text-xs text-violet-700 font-medium">{{ $team->challenge->title }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                            @csrf
                            <x-ui.button as="submit" size="sm" variant="success">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Accept') }}
                            </x-ui.button>
                        </form>
                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                            @csrf
                            <x-ui.button as="submit" size="sm" variant="danger">{{ __('Decline') }}</x-ui.button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Pending Assignments Section -->
    @if($pendingAssignments->count() > 0)
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ __('New Task Assignments') }}</h2>
                <p class="text-xs text-gray-500">{{ __('Matched based on your skills') }}</p>
            </div>
        </div>
        <div class="space-y-3">
            @foreach($pendingAssignments as $assignment)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-gray-900">{{ $assignment->task->title }}</h3>
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-semibold">
                                {{ $assignment->match_score }}% {{ __('Match') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($assignment->task->description, 120) }}</p>
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $assignment->task->estimated_hours }}{{ __('h') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                {{ Str::limit($assignment->task->challenge->title, 30) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="showAcceptModal({{ $assignment->id }}, '{{ addslashes($assignment->task->title) }}')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('Accept') }}
                        </button>
                        <button type="button" onclick="showDeclineModal({{ $assignment->id }}, '{{ addslashes($assignment->task->title) }}')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                            {{ __('Decline') }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Active Tasks Section -->
    @if(isset($activeTasks) && $activeTasks->count() > 0)
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ __('My Active Tasks') }}</h2>
            </div>
            <span class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg font-semibold text-xs">
                {{ $activeTasks->count() }} {{ Str::plural(__('task'), $activeTasks->count()) }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($activeTasks as $task)
            @php $assignment = $task->assignments->first(); @endphp
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h3 class="font-semibold text-gray-900 mb-1">{{ $task->title }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ Str::limit($task->challenge->title, 40) }}</p>
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $assignment->invitation_status === 'in_progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                        {{ ucfirst($assignment->invitation_status) }}
                    </span>
                    <a href="{{ route('tasks.show', $task->id) }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm inline-flex items-center gap-1">
                        {{ __('View') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Profile & Skills Section -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Your Profile & Skills') }}</h2>
                    <p class="text-xs text-gray-500">{{ __('Professional profile based on CV analysis') }}</p>
                </div>
            </div>
            @if($volunteer->cv_file_path)
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border
                {{ $volunteer->ai_analysis_status === 'completed' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' :
                   ($volunteer->ai_analysis_status === 'processing' ? 'bg-amber-50 border-amber-200 text-amber-700' :
                   'bg-gray-50 border-gray-200 text-gray-700') }}">
                {{ __('CV:') }} {{ __(ucfirst($volunteer->ai_analysis_status)) }}
            </span>
            @else
            <x-ui.button as="a" href="{{ route('complete-profile') }}" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                {{ __('Upload CV') }}
            </x-ui.button>
            @endif
        </div>

        <!-- Profile Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h3 class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">{{ __('Field of Expertise') }}</h3>
                <p class="text-base font-semibold text-gray-900">{{ $volunteer->field ?? __('Not set') }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h3 class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">{{ __('Experience Level') }}</h3>
                <p class="text-base font-semibold text-gray-900">{{ $volunteer->experience_level ?? __('Not analyzed') }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h3 class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">{{ __('Years of Experience') }}</h3>
                <p class="text-base font-semibold text-gray-900">{{ $volunteer->years_of_experience ?? 0 }} {{ __('years') }}</p>
            </div>
        </div>

        <!-- Skills -->
        @if($volunteer->skills && $volunteer->skills->count() > 0)
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4">
            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ __('Your Skills') }}
                <span class="ml-auto text-xs font-semibold text-gray-600 bg-white px-2 py-0.5 rounded border border-gray-200">{{ $volunteer->skills->count() }}</span>
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($volunteer->skills as $skill)
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium
                    {{ $skill->proficiency_level === 'expert' ? 'bg-violet-100 text-violet-700 border border-violet-200' : '' }}
                    {{ $skill->proficiency_level === 'advanced' ? 'bg-primary-100 text-primary-700 border border-primary-200' : '' }}
                    {{ $skill->proficiency_level === 'intermediate' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                    {{ ($skill->proficiency_level ?? 'beginner') === 'beginner' ? 'bg-gray-100 text-gray-700 border border-gray-200' : '' }}">
                    {{ $skill->skill_name }}
                    @if($skill->proficiency_level)
                    <span class="text-[10px] opacity-70">({{ ucfirst($skill->proficiency_level) }})</span>
                    @endif
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Education -->
        @if($volunteer->education && is_array($volunteer->education) && count($volunteer->education) > 0)
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4">
            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                {{ __('Education') }}
            </h3>
            <div class="space-y-3">
                @foreach($volunteer->education as $edu)
                <div class="border-l-2 border-cyan-400 pl-3 py-1">
                    <h4 class="font-semibold text-gray-900 text-sm">{{ $edu['degree'] ?? __('Degree') }}</h4>
                    <p class="text-xs text-cyan-700 font-medium">{{ $edu['institution'] ?? __('Institution') }}</p>
                    @if(isset($edu['year']) || isset($edu['graduation_year']))
                    <p class="text-xs text-gray-400 mt-0.5">{{ $edu['year'] ?? $edu['graduation_year'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Work Experience -->
        @if($volunteer->work_experience && is_array($volunteer->work_experience) && count($volunteer->work_experience) > 0)
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ __('Work Experience') }}
            </h3>
            <div class="space-y-3">
                @foreach($volunteer->work_experience as $exp)
                <div class="border-l-2 border-amber-400 pl-3 py-1">
                    <h4 class="font-semibold text-gray-900 text-sm">{{ $exp['job_title'] ?? $exp['title'] ?? $exp['position'] ?? __('Position') }}</h4>
                    <p class="text-xs text-amber-700 font-medium">{{ $exp['company'] ?? $exp['organization'] ?? __('Company') }}</p>
                    @if(isset($exp['duration']) || isset($exp['years']))
                    <p class="text-xs text-gray-400 mt-0.5">{{ $exp['duration'] ?? $exp['years'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Empty State for Profile -->
        @if(!($volunteer->skills && $volunteer->skills->count() > 0) && !($volunteer->education && is_array($volunteer->education) && count($volunteer->education) > 0))
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            @if($volunteer->cv_file_path && ($volunteer->ai_analysis_status === 'processing' || $volunteer->ai_analysis_status === 'pending'))
            <h3 class="text-lg font-semibold text-gray-700 mb-1">{{ __('CV Analysis in Progress') }}</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto">{{ __('Our AI is extracting your skills and experience. This usually takes about 2 minutes.') }}</p>
            <div class="mt-3 inline-flex items-center text-primary-600 font-medium text-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Analyzing...') }}
            </div>
            @elseif(!$volunteer->cv_file_path)
            <h3 class="text-lg font-semibold text-gray-700 mb-1">{{ __('No Profile Data Yet') }}</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mb-4">{{ __('Upload your CV to automatically extract your skills, education, and work experience.') }}</p>
            <x-ui.button as="a" href="{{ route('complete-profile') }}" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                {{ __('Upload Your CV') }}
            </x-ui.button>
            @endif
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('assignments.my') }}" class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-primary-300">
            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="font-semibold text-gray-900 text-sm">{{ __('My Tasks') }}</p>
            <p class="text-xs text-gray-500">{{ __('View assignments') }}</p>
        </a>
        <a href="{{ route('teams.my') }}" class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-violet-300">
            <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="font-semibold text-gray-900 text-sm">{{ __('My Teams') }}</p>
            <p class="text-xs text-gray-500">{{ __('Collaborate') }}</p>
        </a>
        <a href="{{ route('profile.edit') }}" class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-gray-400">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <p class="font-semibold text-gray-900 text-sm">{{ __('Edit Profile') }}</p>
            <p class="text-xs text-gray-500">{{ __('Update info') }}</p>
        </a>
        <a href="{{ route('community.index') }}" class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-emerald-300">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <p class="font-semibold text-gray-900 text-sm">{{ __('Community') }}</p>
            <p class="text-xs text-gray-500">{{ __('Discussions') }}</p>
        </a>
    </div>
</div>

<!-- Accept Assignment Modal -->
<div id="acceptAssignmentModal" class="hidden fixed inset-0 bg-gray-900/70 z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-0 w-full max-w-md px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Accept Task Assignment') }}</h3>
                <p class="text-gray-500 text-sm mt-0.5">{{ __('Confirm your participation') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-semibold text-sm" id="acceptTaskTitle"></p>
                        <p class="text-xs text-gray-500">{{ __('You will be assigned to this task') }}</p>
                    </div>
                </div>
                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 mb-5">
                    <p class="text-sm text-emerald-800">
                        <strong>{{ __('Note:') }}</strong> {{ __('By accepting, you commit to completing this task. You can only work on one task at a time.') }}
                    </p>
                </div>
                <form id="acceptAssignmentForm" method="POST" action="">
                    @csrf
                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" onclick="closeAcceptModal()" variant="ghost" size="sm">{{ __('Cancel') }}</x-ui.button>
                        <button class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600" type="submit">{{ __('Accept Task') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Decline Assignment Modal -->
<div id="declineAssignmentModal" class="hidden fixed inset-0 bg-gray-900/70 z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-0 w-full max-w-md px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Decline Task Assignment') }}</h3>
                <p class="text-gray-500 text-sm mt-0.5">{{ __('This task will be offered to others') }}</p>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-900 font-semibold text-sm" id="declineTaskTitle"></p>
                        <p class="text-xs text-gray-500">{{ __('Are you sure you want to decline?') }}</p>
                    </div>
                </div>
                <form id="declineAssignmentForm" method="POST" action="">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Reason (optional)') }}</label>
                        <textarea name="reason" rows="4" class="w-full rounded-lg border-gray-300 text-sm p-3" placeholder="{{ __('Let us know why you are declining...') }}"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" onclick="closeDeclineModal()" variant="ghost" size="sm">{{ __('Cancel') }}</x-ui.button>
                        <x-ui.button type="submit" variant="danger" size="sm">{{ __('Decline Task') }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showAcceptModal(assignmentId, taskTitle) {
    document.getElementById('acceptTaskTitle').textContent = taskTitle;
    document.getElementById('acceptAssignmentForm').action = '{{ url("assignments") }}/' + assignmentId + '/accept';
    document.getElementById('acceptAssignmentModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptAssignmentModal').classList.add('hidden');
}

function showDeclineModal(assignmentId, taskTitle) {
    document.getElementById('declineTaskTitle').textContent = taskTitle;
    document.getElementById('declineAssignmentForm').action = '{{ url("assignments") }}/' + assignmentId + '/decline';
    document.getElementById('declineAssignmentModal').classList.remove('hidden');
}

function closeDeclineModal() {
    document.getElementById('declineAssignmentModal').classList.add('hidden');
}

// Close modals on backdrop click
document.getElementById('acceptAssignmentModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeAcceptModal();
});
document.getElementById('declineAssignmentModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDeclineModal();
});
</script>
@endsection
