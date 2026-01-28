@extends('layouts.app')

@section('title', __('My Teams'))

@section('content')
@php
    $pendingTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'invited';
    });

    $activeTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'accepted';
    });

    $declinedTeams = $teams->filter(function($team) {
        $member = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
        return $member && $member->status === 'declined';
    });

    $hasActiveTask = \App\Models\TaskAssignment::where('volunteer_id', auth()->user()->volunteer->id)
        ->whereIn('invitation_status', ['accepted', 'in_progress', 'submitted'])
        ->first();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-6">
    @if($teams->count() > 0)

    <!-- Tab Navigation -->
    <div x-data="{ activeTab: '{{ $pendingTeams->count() > 0 ? 'pending' : 'active' }}' }" class="mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-1.5 flex flex-wrap gap-1">
            <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2 rounded-lg font-medium text-sm border flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Invitations') }}</span>
                <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $pendingTeams->count() }}</span>
            </button>
            <button @click="activeTab = 'active'"
                    :class="activeTab === 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2 rounded-lg font-medium text-sm border flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Active') }}</span>
                <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $activeTeams->count() }}</span>
            </button>
            <button @click="activeTab = 'declined'"
                    :class="activeTab === 'declined' ? 'bg-gray-100 text-gray-700 border-gray-200' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                    class="flex-1 sm:flex-none whitespace-nowrap px-4 py-2 rounded-lg font-medium text-sm border flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span class="hidden sm:inline">{{ __('Declined') }}</span>
                <span class="px-1.5 py-0.5 bg-white rounded text-[10px] font-bold">{{ $declinedTeams->count() }}</span>
            </button>
        </div>

        <!-- Pending Invitations Tab -->
        <div x-show="activeTab === 'pending'" class="mt-6">
            @if($pendingTeams->count() > 0)
                <!-- Active Task Warning -->
                @if($hasActiveTask)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-amber-900 mb-0.5">{{ __('Active Task in Progress') }}</h3>
                            <p class="text-sm text-amber-800 mb-1">
                                {{ __('You currently have an active task:') }}
                                <a href="{{ route('tasks.show', $hasActiveTask->task_id) }}" class="underline font-semibold hover:text-amber-900">
                                    {{ $hasActiveTask->task->title }}
                                </a>
                            </p>
                            <p class="text-xs text-amber-700">
                                {{ __('Complete your current task before joining new teams. You can only work on one task at a time.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-4">
                    @foreach($pendingTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                    @endphp
                    <div class="bg-amber-50 border border-amber-200 rounded-xl overflow-hidden">
                        <div class="p-5">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                                <!-- Team Info -->
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $team->name }}</h3>
                                        @if($myMembership->role === 'leader')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-[10px] font-bold border border-yellow-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            {{ __('Team Leader') }}
                                        </span>
                                        @elseif($myMembership->role === 'specialist')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-100 text-violet-800 rounded text-[10px] font-bold border border-violet-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            {{ __('Specialist') }}
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-[10px] font-bold border border-blue-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ __('Team Member') }}
                                        </span>
                                        @endif
                                    </div>

                                    <p class="text-gray-700 text-sm mb-3">{{ $team->description }}</p>

                                    <!-- Challenge Link -->
                                    <a href="{{ route('challenges.show', $team->challenge) }}" class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-semibold text-sm mb-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        {{ $team->challenge->title }}
                                    </a>

                                    <!-- Team Stats -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                        <div class="bg-white rounded-lg p-2.5 text-center border border-amber-200">
                                            <div class="text-lg font-bold text-gray-900">{{ $team->members->count() }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">{{ __('Members') }}</div>
                                        </div>
                                        @if($team->team_match_score)
                                        <div class="bg-white rounded-lg p-2.5 text-center border border-amber-200">
                                            <div class="text-lg font-bold text-primary-600">{{ number_format($team->team_match_score) }}%</div>
                                            <div class="text-[10px] text-gray-500 font-medium">{{ __('Match') }}</div>
                                        </div>
                                        @endif
                                        @if($team->estimated_total_hours)
                                        <div class="bg-white rounded-lg p-2.5 text-center border border-amber-200">
                                            <div class="text-lg font-bold text-violet-600">{{ $team->estimated_total_hours }}h</div>
                                            <div class="text-[10px] text-gray-500 font-medium">{{ __('Est. Hours') }}</div>
                                        </div>
                                        @endif
                                        <div class="bg-white rounded-lg p-2.5 text-center border border-amber-200">
                                            <div class="text-lg font-bold text-emerald-600">{{ $team->acceptedMembers()->count() }}</div>
                                            <div class="text-[10px] text-gray-500 font-medium">{{ __('Accepted') }}</div>
                                        </div>
                                    </div>

                                    <!-- Your Role Description -->
                                    @if($myMembership->role_description)
                                    <div class="bg-white border border-amber-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-amber-900 mb-0.5">{{ __('Your Role:') }}</p>
                                        <p class="text-xs text-amber-800">{{ $myMembership->role_description }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Right Side: Members & Actions -->
                                <div class="lg:w-56 space-y-3">
                                    <!-- Team Members Preview -->
                                    <div class="bg-white rounded-lg p-3 border border-amber-200">
                                        <p class="text-xs font-semibold text-gray-700 mb-2">{{ __('Team Members') }}</p>
                                        <div class="flex items-center mb-2">
                                            @foreach($team->members->take(5) as $idx => $member)
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white -ml-2 first:ml-0
                                                {{ $member->role === 'leader' ? 'bg-amber-500' : ($member->status === 'invited' ? 'bg-gray-400' : 'bg-primary-500') }}"
                                                title="{{ $member->volunteer->user->name ?? 'Unknown' }}">
                                                {{ strtoupper(substr($member->volunteer->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            @endforeach
                                            @if($team->members->count() > 5)
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white -ml-2 bg-gray-500">
                                                +{{ $team->members->count() - 5 }}
                                            </div>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            {{ $team->acceptedMembers()->count() }}/{{ $team->members->count() }} {{ __('members joined') }}
                                        </p>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="space-y-2">
                                        @if($hasActiveTask)
                                        <x-ui.button disabled variant="outline" fullWidth size="sm" class="opacity-50 cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            {{ __('Blocked') }}
                                        </x-ui.button>
                                        @else
                                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                                            @csrf
                                            <x-ui.button as="submit" variant="success" fullWidth size="sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ __('Accept') }}
                                            </x-ui.button>
                                        </form>
                                        @endif

                                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                                            @csrf
                                            <x-ui.button as="submit" variant="outline" fullWidth size="sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Decline') }}
                                            </x-ui.button>
                                        </form>

                                        <x-ui.button as="a" href="{{ route('teams.show', $team) }}" variant="primary" fullWidth size="sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View Details') }}
                                        </x-ui.button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Pending State -->
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Pending Invitations') }}</h2>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">
                        {{ __("You don't have any pending team invitations. Keep your profile updated to get matched with new opportunities!") }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Active Teams Tab -->
        <div x-show="activeTab === 'active'" class="mt-6">
            @if($activeTeams->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($activeTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                        $isLeader = $myMembership->role === 'leader';
                        $acceptedCount = $team->acceptedMembers()->count();
                        $totalCount = $team->members->count();
                        $acceptanceRate = $totalCount > 0 ? ($acceptedCount / $totalCount) * 100 : 0;
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <!-- Card Header -->
                        <div class="px-4 py-4 border-b border-gray-100
                            {{ $team->status === 'active' ? 'bg-emerald-50' : ($team->status === 'forming' ? 'bg-amber-50' : 'bg-blue-50') }}">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 mb-1">{{ $team->name }}</h3>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold border
                                        {{ $team->status === 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                                           ($team->status === 'forming' ? 'bg-amber-100 text-amber-700 border-amber-200' : 
                                           'bg-blue-100 text-blue-700 border-blue-200') }}">
                                        @if($team->status === 'active')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        @elseif($team->status === 'forming')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        @else
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endif
                                        {{ ucfirst($team->status) }}
                                    </span>
                                </div>
                                @if($isLeader)
                                <div class="w-7 h-7 bg-amber-100 rounded-full flex items-center justify-center" title="{{ __('Team Leader') }}">
                                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Member Avatars -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @foreach($team->members->take(4) as $idx => $member)
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold border-2 border-white -ml-2 first:ml-0
                                        {{ $member->role === 'leader' ? 'bg-amber-500' : ($member->status !== 'accepted' ? 'bg-gray-400' : 'bg-primary-500') }}"
                                        title="{{ $member->volunteer->user->name ?? 'Unknown' }}">
                                        {{ strtoupper(substr($member->volunteer->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    @endforeach
                                    @if($team->members->count() > 4)
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold border-2 border-white -ml-2 bg-gray-500">
                                        +{{ $team->members->count() - 4 }}
                                    </div>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 font-medium">{{ $acceptedCount }}/{{ $totalCount }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <p class="text-gray-600 text-sm mb-3 leading-relaxed">{{ Str::limit($team->description, 80) }}</p>

                            <!-- Challenge Link -->
                            <a href="{{ route('challenges.show', $team->challenge) }}" class="flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium text-sm mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span class="truncate">{{ Str::limit($team->challenge->title, 30) }}</span>
                            </a>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                @if($team->team_match_score)
                                <div class="bg-primary-50 rounded-lg p-2.5 text-center border border-primary-100">
                                    <div class="text-lg font-bold text-primary-600">{{ number_format($team->team_match_score) }}%</div>
                                    <div class="text-[10px] text-primary-600/70 font-medium">{{ __('Match') }}</div>
                                </div>
                                @endif
                                @if($team->estimated_total_hours)
                                <div class="bg-violet-50 rounded-lg p-2.5 text-center border border-violet-100">
                                    <div class="text-lg font-bold text-violet-600">{{ $team->estimated_total_hours }}h</div>
                                    <div class="text-[10px] text-violet-600/70 font-medium">{{ __('Est. Hours') }}</div>
                                </div>
                                @endif
                            </div>

                            <!-- Team Progress -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-gray-500 font-medium">{{ __('Team Formation') }}</span>
                                    <span class="text-gray-900 font-semibold">{{ number_format($acceptanceRate) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $acceptanceRate }}%;"></div>
                                </div>
                            </div>

                            <!-- Your Skills -->
                            @if($myMembership->assigned_skills && count($myMembership->assigned_skills) > 0)
                            <div class="mb-3">
                                <p class="text-xs font-medium text-gray-500 mb-1.5">{{ __('Your Skills:') }}</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($myMembership->assigned_skills, 0, 3) as $skill)
                                    <span class="px-1.5 py-0.5 bg-primary-50 text-primary-700 rounded text-[10px] font-medium border border-primary-100">{{ $skill }}</span>
                                    @endforeach
                                    @if(count($myMembership->assigned_skills) > 3)
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-medium">+{{ count($myMembership->assigned_skills) - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- View Team Button -->
                            <x-ui.button as="a" href="{{ route('teams.show', $team) }}" fullWidth size="sm">
                                {{ __('View Team') }}
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </x-ui.button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Active State -->
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Active Teams') }}</h2>
                    <p class="text-gray-500 text-sm max-w-md mx-auto mb-4">
                        {{ __("You haven't joined any teams yet. Accept a pending invitation to start collaborating!") }}
                    </p>
                    @if($pendingTeams->count() > 0)
                    <x-ui.button @click="activeTab = 'pending'">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19"/>
                        </svg>
                        {{ __('View Invitations') }} ({{ $pendingTeams->count() }})
                    </x-ui.button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Declined Teams Tab -->
        <div x-show="activeTab === 'declined'" class="mt-6">
            @if($declinedTeams->count() > 0)
                <div class="space-y-3">
                    @foreach($declinedTeams as $index => $team)
                    @php
                        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer->id)->first();
                    @endphp
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 opacity-75">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-700">{{ $team->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $team->challenge->title }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($myMembership->declined_at)
                                <span class="text-xs text-gray-500">
                                    {{ __('Declined') }} {{ $myMembership->declined_at->diffForHumans() }}
                                </span>
                                @endif
                                <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold">
                                    {{ __('Declined') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Declined State -->
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">{{ __('No Declined Invitations') }}</h2>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">
                        {{ __("You haven't declined any team invitations. All past invitations were either accepted or are still pending.") }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    @else
    <!-- No Teams Empty State -->
    <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('No Teams Yet') }}</h2>
        <p class="text-gray-500 mb-6 max-w-lg mx-auto text-sm">
            {{ __("You haven't been invited to any teams yet. Complete your profile and upload your CV to get matched with exciting team opportunities!") }}
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <x-ui.button as="a" href="{{ route('profile.edit') }}">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('Update Profile') }}
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('assignments.my') }}" variant="outline">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                {{ __('View My Tasks') }}
            </x-ui.button>
        </div>
    </div>
    @endif
</div>
@endsection
