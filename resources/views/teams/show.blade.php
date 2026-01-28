@extends('layouts.app')

@section('title', $team->name)

@section('content')
<!-- Clean Header Section -->
<div class="bg-white border-b border-gray-200 py-8 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Team Info -->
            <div class="flex-1">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1.5 mb-4">
                    <div class="w-2 h-2 rounded-full
                        {{ $team->status === 'active' ? 'bg-emerald-500' : '' }}
                        {{ $team->status === 'forming' ? 'bg-amber-500' : '' }}
                        {{ $team->status === 'completed' ? 'bg-blue-500' : '' }}"></div>
                    <span class="text-sm font-semibold text-gray-700">{{ __(ucfirst($team->status)) }} {{ __('Team') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-tight">
                    {{ $team->name }}
                </h1>
                <p class="text-gray-600 leading-relaxed max-w-2xl mb-6">
                    {{ $team->description }}
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $team->team_size }}</p>
                            <p class="text-[10px] text-gray-500 font-semibold uppercase">{{ __('Members') }}</p>
                        </div>
                    </div>
                    @if($team->team_match_score)
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $team->team_match_score }}%</p>
                            <p class="text-[10px] text-gray-500 font-semibold uppercase">{{ __('Match Score') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Challenge Link Card -->
            <div class="lg:w-72">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-semibold uppercase">{{ __('Working On') }}</p>
                            <p class="text-xs font-semibold text-gray-900">{{ __('Challenge') }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 line-clamp-2 leading-snug">
                        {{ $team->challenge->title }}
                    </h3>
                    
                    <x-ui.button as="a" href="{{ route('challenges.show', $team->challenge) }}" size="sm" fullWidth>
                        {{ __('View Challenge') }}
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <!-- Team Objectives -->
    @if($team->objectives && count($team->objectives) > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('Team Objectives') }}</h2>
        <ul class="space-y-3">
            @foreach($team->objectives as $objective)
            <li class="flex items-start gap-3">
                <div class="flex-shrink-0 w-5 h-5 bg-primary-100 rounded-lg flex items-center justify-center mt-0.5">
                    <svg class="w-3 h-3 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-700 leading-relaxed">{{ $objective }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Skills Coverage -->
    @if($team->skills_coverage)
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('Skills Coverage') }}</h2>

        <!-- Coverage Progress -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">{{ __('Overall Coverage') }}</span>
                <span class="text-lg font-bold text-primary-600">{{ $team->skills_coverage['coverage_percentage'] ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $team->skills_coverage['coverage_percentage'] ?? 0 }}%"></div>
            </div>
        </div>

        @if(isset($team->skills_coverage['covered_skills']) && count($team->skills_coverage['covered_skills']) > 0)
        <div class="mb-4">
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Covered Skills') }}</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($team->skills_coverage['covered_skills'] as $skill)
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-medium">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($team->skills_coverage['missing_skills']) && count($team->skills_coverage['missing_skills']) > 0)
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-2">{{ __('Missing Skills') }}</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($team->skills_coverage['missing_skills'] as $skill)
                <span class="px-2.5 py-1 bg-gray-50 text-gray-600 border border-gray-200 rounded-lg text-xs font-medium">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Team Members -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('Team Members') }}</h2>
        <div class="space-y-3">
            @foreach($team->members as $member)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 {{ $member->status === 'invited' && $member->volunteer_id === auth()->user()->volunteer?->id ? 'bg-amber-50 border-amber-200' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3 flex-1">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-base">
                            {{ substr($member->volunteer->user->name, 0, 1) }}
                        </div>

                        <!-- Member Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 class="text-sm font-bold text-gray-900">{{ $member->volunteer->user->name }}</h3>
                                @if($member->role === 'leader')
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded text-[10px] font-bold">{{ __('Leader') }}</span>
                                @elseif($member->role === 'specialist')
                                <span class="px-2 py-0.5 bg-violet-100 text-violet-800 border border-violet-200 rounded text-[10px] font-bold">{{ __('Specialist') }}</span>
                                @endif
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border
                                    {{ $member->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $member->status === 'invited' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $member->status === 'declined' ? 'bg-gray-100 text-gray-600 border-gray-200' : '' }}">
                                    {{ __(ucfirst($member->status)) }}
                                </span>
                            </div>

                            @if($member->role_description)
                            <p class="text-xs text-gray-600 mb-2 leading-relaxed line-clamp-2">{{ $member->role_description }}</p>
                            @endif

                            <!-- Member Skills -->
                            @if($member->assigned_skills && count($member->assigned_skills) > 0)
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach($member->assigned_skills as $skill)
                                <span class="px-2 py-0.5 bg-primary-50 text-primary-700 border border-primary-200 rounded-lg text-[10px] font-medium">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @endif

                            <!-- Member Stats -->
                            <div class="flex flex-wrap items-center gap-3 text-xs">
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                                    <span class="font-medium text-gray-700">{{ __(ucfirst($member->volunteer->experience_level ?? 'intermediate')) }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                    <span class="font-medium text-gray-700">{{ __('Rep:') }} {{ $member->volunteer->reputation_score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Member Progress Charts (Only for accepted members with tasks) -->
                    @if($member->status === 'accepted')
                        @php
                            $memberTasks = \App\Models\TaskAssignment::where('volunteer_id', $member->volunteer_id)
                                ->whereHas('task', function($q) use ($team) {
                                    $q->where('challenge_id', $team->challenge_id);
                                })
                                ->with('task')
                                ->get();

                            $totalTasks = $memberTasks->count();
                            $completedTasks = $memberTasks->where('task.status', 'completed')->count();
                            $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

                            $avgQuality = $memberTasks->flatMap(function($assignment) {
                                return $assignment->task->submissions ?? [];
                            })->where('volunteer_id', $member->volunteer_id)
                              ->where('status', 'approved')
                              ->avg('ai_quality_score') ?? 0;

                            $performanceScore = round($avgQuality, 1);

                            $totalEstimated = $memberTasks->sum('task.estimated_hours');
                            $totalActual = $memberTasks->flatMap(function($assignment) {
                                return $assignment->task->submissions ?? [];
                            })->where('volunteer_id', $member->volunteer_id)
                              ->where('status', 'approved')
                              ->sum('hours_spent');

                            $timeEfficiency = 100;
                            if ($totalActual > 0 && $totalEstimated > 0) {
                                $timeEfficiency = min(round(($totalEstimated / $totalActual) * 100, 1), 100);
                            }
                        @endphp

                        @if($totalTasks > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:ml-4 lg:pl-4 lg:w-80 flex-shrink-0">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-4 h-4 bg-primary-100 rounded flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xs font-bold text-gray-900 uppercase">{{ __('Performance') }}</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <!-- Task Progress -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <x-progress-circle
                                        :percentage="$taskProgress"
                                        :size="55"
                                        text-size="text-[12px]"
                                        label=""
                                        stroke="2"
                                        color="#6366F1"
                                    />
                                    <p class="text-[10px] font-bold text-gray-500 mt-1">{{ __('Tasks') }}</p>
                                    <p class="text-xs font-bold text-gray-900">{{ $completedTasks }}/{{ $totalTasks }}</p>
                                </div>

                                <!-- Quality Score -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <x-progress-circle
                                        :percentage="$performanceScore"
                                        :size="55"
                                        text-size="text-[12px]"
                                        label=""
                                        stroke="2"
                                        color="#10b981"
                                    />
                                    <p class="text-[10px] font-bold text-gray-500 mt-1">{{ __('Quality') }}</p>
                                    <p class="text-xs font-bold text-gray-900">{{ round($performanceScore) }}%</p>
                                </div>

                                <!-- Time Efficiency -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 text-center">
                                    <x-progress-circle
                                        :percentage="$timeEfficiency"
                                        :size="55"
                                        text-size="text-[12px]"
                                        label=""
                                        stroke="2"
                                        color="#f59e0b"
                                    />
                                    <p class="text-[10px] font-bold text-gray-500 mt-1">{{ __('Efficiency') }}</p>
                                    <p class="text-xs font-bold text-gray-900">
                                        @if($totalActual > 0)
                                            {{ $totalActual }}h
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>

                <!-- Accept/Decline Buttons (only for invited member viewing their own invitation) -->
                @if($member->status === 'invited' && $member->volunteer_id === auth()->user()->volunteer?->id)
                <div class="flex flex-col gap-2 ml-12 mt-3">
                    <form action="{{ route('teams.accept', $team) }}" method="POST">
                        @csrf
                        <x-ui.button as="submit" size="sm">
                            {{ __('Accept Invitation') }}
                        </x-ui.button>
                    </form>
                    <form action="{{ route('teams.decline', $team) }}" method="POST">
                        @csrf
                        <x-ui.button as="submit" variant="ghost" size="sm">
                            {{ __('Decline') }}
                        </x-ui.button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Team Chat (Only for accepted members) -->
    @php
        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer?->id)->first();
        $isAcceptedMember = $myMembership && $myMembership->status === 'accepted';
    @endphp

    @if($isAcceptedMember)
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden" x-data="teamChat({{ $team->id }})">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">{{ __('Team Chat') }}</h2>
                    <p class="text-xs text-gray-500">{{ __('Collaborate with your team members') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold text-emerald-600">{{ __('Live') }}</span>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="bg-gray-50 p-4 h-96 flex flex-col">
            
            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto pr-2 space-y-4" id="chatMessages">
                <div x-show="loading" class="h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-8 h-8 mb-2 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">{{ __('Loading conversation...') }}</p>
                </div>

                <template x-for="message in messages" :key="message.id">
                    <div class="flex flex-col" 
                         :class="message.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                        
                        <div class="flex items-end gap-2 max-w-[85%] lg:max-w-[70%]" 
                             :class="message.user_id === {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
                            
                            <!-- Avatar -->
                            <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold border-2 border-white bg-gray-200 text-gray-600"
                                 :class="message.user_id === {{ auth()->id() }} ? 'bg-primary-100 text-primary-600' : 'bg-gray-200 text-gray-600'">
                                <span x-text="message.user_name.charAt(0)"></span>
                            </div>

                            <div class="flex flex-col" :class="message.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                                <div class="flex items-center gap-2 mb-0.5 px-1">
                                    <span class="text-[10px] font-bold text-gray-500" x-text="message.user_name"></span>
                                    <span class="text-[10px] text-gray-400" x-text="message.time_ago"></span>
                                </div>
                                
                                <div class="px-4 py-2.5 text-sm leading-relaxed"
                                     :class="message.user_id === {{ auth()->id() }} 
                                        ? 'bg-primary-500 text-white rounded-2xl rounded-tr-sm' 
                                        : 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-tl-sm'">
                                    <p x-text="message.message" class="whitespace-pre-wrap"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="!loading && messages.length === 0" class="h-full flex flex-col items-center justify-center text-center p-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 border border-gray-200">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-0.5">{{ __('No messages yet') }}</h3>
                    <p class="text-sm text-gray-500 max-w-xs mx-auto mb-4">{{ __('Be the first to say hello and kickstart the collaboration!') }}</p>
                    <div class="px-3 py-1.5 bg-primary-50 text-primary-700 rounded-lg text-xs font-semibold">
                        {{ __('Encrypted & Secure') }}
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="mt-3">
                <form @submit.prevent="sendMessage">
                    <div class="flex items-end gap-2 bg-white rounded-xl p-2 border border-gray-200 focus-within:border-primary-300 focus-within:ring-2 focus-within:ring-primary-100">
                        <textarea
                            x-model="newMessage"
                            @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                            placeholder="{{ __('Type your message here...') }}"
                            class="w-full max-h-24 min-h-[44px] py-2.5 pl-3 pr-10 bg-transparent border-0 focus:ring-0 text-sm text-gray-800 placeholder:text-gray-400 resize-none"
                            rows="1"
                            maxlength="2000"
                            required
                        ></textarea>
                        
                        <div class="flex-shrink-0">
                            <button 
                                type="submit" 
                                :disabled="sending || !newMessage.trim()"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-500 text-white hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg x-show="!sending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-1.5">
                    <p class="text-[10px] text-gray-400">{{ __('Press Enter to send, Shift + Enter for new line') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function teamChat(teamId) {
    return {
        messages: [],
        newMessage: '',
        loading: true,
        sending: false,
        pollInterval: null,

        init() {
            this.fetchMessages();
            this.pollInterval = setInterval(() => this.fetchMessages(), 5000);
        },

        async fetchMessages() {
            try {
                const response = await fetch(`/api/teams/${teamId}/messages`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                this.messages = data.messages;
                this.loading = false;

                this.$nextTick(() => {
                    const container = document.getElementById('chatMessages');
                    container.scrollTop = container.scrollHeight;
                });
            } catch (error) {
                console.error('Failed to fetch messages:', error);
                this.loading = false;
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.sending) return;

            this.sending = true;
            try {
                const response = await fetch(`/api/teams/${teamId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: this.newMessage
                    })
                });

                if (response.ok) {
                    this.newMessage = '';
                    await this.fetchMessages();
                } else {
                    alert('{{ __("Failed to send message") }}');
                }
            } catch (error) {
                console.error('Failed to send message:', error);
                alert('{{ __("Failed to send message") }}');
            } finally {
                this.sending = false;
            }
        },

        destroy() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
            }
        }
    }
}
</script>
@endsection
