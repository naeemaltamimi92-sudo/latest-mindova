@extends('layouts.app')

@section('title', $team->name)

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .float-anim { animation: floatAnim 3s ease-in-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }
    .team-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-12 mb-12 rounded-3xl max-w-7xl mx-auto shadow-2xl">
    <!-- Animated Background Effects -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-500/20 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-violet-500/20 via-transparent to-transparent"></div>
        <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
        <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="team-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#team-grid)"/>
        </svg>
    </div>

    <div class="relative max-w-6xl mx-auto px-6 sm:px-8 slide-up">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <!-- Team Info -->
            <div class="flex-1">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2.5 mb-6 shadow-lg">
                    <div class="relative">
                        <div class="w-2.5 h-2.5 rounded-full animate-pulse
                            {{ $team->status === 'active' ? 'bg-emerald-400' : '' }}
                            {{ $team->status === 'forming' ? 'bg-amber-400' : '' }}
                            {{ $team->status === 'completed' ? 'bg-indigo-400' : '' }}"></div>
                        <div class="absolute inset-0 w-2.5 h-2.5 rounded-full animate-ping
                            {{ $team->status === 'active' ? 'bg-emerald-400' : '' }}
                            {{ $team->status === 'forming' ? 'bg-amber-400' : '' }}
                            {{ $team->status === 'completed' ? 'bg-indigo-400' : '' }}"></div>
                    </div>
                    <span class="text-sm font-semibold text-white/90">{{ __(ucfirst($team->status)) }} {{ __('Team') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                    {{ $team->name }}
                </h1>
                <p class="text-lg text-white/80 font-medium leading-relaxed max-w-2xl mb-8">
                    {{ $team->description }}
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $team->team_size }}</p>
                            <p class="text-xs text-white/60 font-semibold uppercase tracking-wider">{{ __('Members') }}</p>
                        </div>
                    </div>
                    @if($team->team_match_score)
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $team->team_match_score }}%</p>
                            <p class="text-xs text-white/60 font-semibold uppercase tracking-wider">{{ __('Match Score') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Challenge Link Card -->
            <div class="lg:w-80">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-violet-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/60 font-semibold uppercase tracking-wider">{{ __('Working On') }}</p>
                            <p class="text-sm font-bold text-white">{{ __('Challenge') }}</p>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-4 line-clamp-2">{{ $team->challenge->title }}</h3>
                    <a href="{{ route('challenges.show', $team->challenge) }}" class="inline-flex items-center justify-center w-full gap-2 bg-white/20 hover:bg-white/30 text-white font-bold px-5 py-3 rounded-xl transition-all">
                        {{ __('View Challenge') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Team Objectives -->
    @if($team->objectives && count($team->objectives) > 0)
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-12">
        <h2 class="text-2xl font-black text-slate-900 mb-6">{{ __('Team Objectives') }}</h2>
        <ul class="space-y-4">
            @foreach($team->objectives as $objective)
            <li class="flex items-start gap-3">
                <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-lg flex items-center justify-center mt-0.5">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-base text-slate-700 leading-relaxed">{{ $objective }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Skills Coverage -->
    @if($team->skills_coverage)
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-12">
        <h2 class="text-2xl font-black text-slate-900 mb-6">{{ __('Skills Coverage') }}</h2>

        <!-- Coverage Progress -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-bold text-slate-700">{{ __('Overall Coverage') }}</span>
                <span class="text-lg font-black text-indigo-600">{{ $team->skills_coverage['coverage_percentage'] ?? 0 }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-violet-600 h-3 rounded-full transition-all duration-500" style="width: {{ $team->skills_coverage['coverage_percentage'] ?? 0 }}%"></div>
            </div>
        </div>

        @if(isset($team->skills_coverage['covered_skills']) && count($team->skills_coverage['covered_skills']) > 0)
        <div class="mb-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">{{ __('Covered Skills') }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($team->skills_coverage['covered_skills'] as $skill)
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-sm font-medium">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($team->skills_coverage['missing_skills']) && count($team->skills_coverage['missing_skills']) > 0)
        <div>
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">{{ __('Missing Skills') }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($team->skills_coverage['missing_skills'] as $skill)
                <span class="px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-lg text-sm font-medium">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Team Members -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-12">
        <h2 class="text-2xl font-black text-slate-900 mb-6">{{ __('Team Members') }}</h2>
        <div class="space-y-5">
            @foreach($team->members as $member)
            <div class="group bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:border-indigo-200 hover:shadow-md transition-all duration-300 {{ $member->status === 'invited' && $member->volunteer_id === auth()->user()->volunteer?->id ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-200' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-black text-xl shadow-sm">
                            {{ substr($member->volunteer->user->name, 0, 1) }}
                        </div>

                        <!-- Member Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="text-lg font-black text-slate-900">{{ $member->volunteer->user->name }}</h3>
                                @if($member->role === 'leader')
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold">{{ __('Team Leader') }}</span>
                                @elseif($member->role === 'specialist')
                                <span class="px-3 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold">{{ __('Specialist') }}</span>
                                @endif
                                <span class="px-3 py-1 rounded-lg text-xs font-bold border
                                    {{ $member->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $member->status === 'invited' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $member->status === 'declined' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}">
                                    {{ __(ucfirst($member->status)) }}
                                </span>
                            </div>

                            @if($member->role_description)
                            <p class="text-sm text-slate-600 mb-3 leading-relaxed">{{ $member->role_description }}</p>
                            @endif

                            <!-- Member Skills -->
                            @if($member->assigned_skills && count($member->assigned_skills) > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($member->assigned_skills as $skill)
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-medium">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @endif

                            <!-- Member Stats -->
                            <div class="flex flex-wrap items-center gap-4 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                    <span class="font-semibold text-slate-700">{{ __(ucfirst($member->volunteer->experience_level ?? 'intermediate')) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                    <span class="font-semibold text-slate-700">{{ __('Reputation:') }} {{ $member->volunteer->reputation_score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Member Progress Charts (Only for accepted members with tasks) -->
                    @if($member->status === 'accepted')
                        @php
                            // Get tasks assigned to this volunteer for this challenge
                            $memberTasks = \App\Models\TaskAssignment::where('volunteer_id', $member->volunteer_id)
                                ->whereHas('task', function($q) use ($team) {
                                    $q->where('challenge_id', $team->challenge_id);
                                })
                                ->with('task')
                                ->get();

                            $totalTasks = $memberTasks->count();
                            $completedTasks = $memberTasks->where('task.status', 'completed')->count();
                            $inProgressTasks = $memberTasks->whereIn('invitation_status', ['in_progress', 'accepted'])->count();

                            $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

                            // Calculate performance based on work submissions
                            $avgQuality = $memberTasks->flatMap(function($assignment) {
                                return $assignment->task->submissions ?? [];
                            })->where('volunteer_id', $member->volunteer_id)
                              ->where('status', 'approved')
                              ->avg('ai_quality_score') ?? 0;

                            $performanceScore = round($avgQuality, 1);

                            // Time efficiency: compare estimated vs actual hours
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
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-5 h-5 bg-gradient-to-br from-indigo-500 to-violet-500 rounded flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Performance Metrics') }}</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-6">
                                <!-- Task Progress -->
                                <div class="flex flex-col items-center bg-white rounded-xl p-4 border border-slate-200">
                                    <x-progress-circle
                                        :percentage="$taskProgress"
                                        :size="80"
                                        label=""
                                        color="#6366F1"
                                    />
                                    <p class="text-xs font-bold text-slate-600 mt-3 uppercase tracking-wider">{{ __('Tasks') }}</p>
                                    <p class="text-sm font-black text-slate-900">{{ $completedTasks }}/{{ $totalTasks }}</p>
                                </div>

                                <!-- Quality Score -->
                                <div class="flex flex-col items-center bg-white rounded-xl p-4 border border-slate-200">
                                    <x-progress-circle
                                        :percentage="$performanceScore"
                                        :size="80"
                                        label=""
                                        color="#10b981"
                                    />
                                    <p class="text-xs font-bold text-slate-600 mt-3 uppercase tracking-wider">{{ __('Quality') }}</p>
                                    <p class="text-sm font-black text-slate-900">{{ round($performanceScore) }}%</p>
                                </div>

                                <!-- Time Efficiency -->
                                <div class="flex flex-col items-center bg-white rounded-xl p-4 border border-slate-200">
                                    <x-progress-circle
                                        :percentage="$timeEfficiency"
                                        :size="80"
                                        label=""
                                        color="#f59e0b"
                                    />
                                    <p class="text-xs font-bold text-slate-600 mt-3 uppercase tracking-wider">{{ __('Efficiency') }}</p>
                                    <p class="text-sm font-black text-slate-900">
                                        @if($totalActual > 0)
                                            {{ $totalActual }}h
                                        @else
                                            {{ __('Not started') }}
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
                    <div class="flex flex-col gap-2 ml-4">
                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                {{ __('Accept Invitation') }}
                            </button>
                        </form>
                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl border border-slate-200 hover:bg-slate-200 transition-all duration-300">
                                {{ __('Decline') }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
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
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100" x-data="teamChat({{ $team->id }})">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-lg flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-slate-900">{{ __('Team Chat') }}</h2>
        </div>

        <!-- Chat Messages -->
        <div class="bg-slate-50 rounded-2xl p-5 h-96 overflow-y-auto mb-5 border border-slate-200" id="chatMessages">
            <div x-show="loading" class="text-center text-slate-500 py-8">
                <p class="font-medium">{{ __('Loading messages...') }}</p>
            </div>

            <template x-for="message in messages" :key="message.id">
                <div class="mb-4" :class="message.user_id === {{ auth()->id() }} ? 'text-right' : 'text-left'">
                    <div class="inline-block max-w-[75%]">
                        <div class="flex items-center gap-2 mb-1.5" :class="message.user_id === {{ auth()->id() }} ? 'flex-row-reverse' : ''">
                            <span class="text-xs font-bold text-slate-700" x-text="message.user_name"></span>
                            <span class="text-xs text-slate-500" x-text="message.time_ago"></span>
                        </div>
                        <div class="rounded-xl p-3.5 shadow-sm" :class="message.user_id === {{ auth()->id() }} ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white' : 'bg-white border border-slate-200 text-slate-900'">
                            <p class="text-sm whitespace-pre-wrap leading-relaxed" x-text="message.message"></p>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="!loading && messages.length === 0" class="text-center text-slate-500 py-12">
                <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="font-semibold">{{ __('No messages yet') }}</p>
                <p class="text-sm mt-1">{{ __('Start the conversation with your team!') }}</p>
            </div>
        </div>

        <!-- Send Message Form -->
        <form @submit.prevent="sendMessage" class="flex gap-3">
            <input
                type="text"
                x-model="newMessage"
                placeholder="{{ __('Type your message...') }}"
                class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                maxlength="2000"
                required
            >
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 transition-all duration-300" :disabled="sending || !newMessage.trim()">
                <span x-show="!sending">{{ __('Send') }}</span>
                <span x-show="sending">{{ __('Sending...') }}</span>
            </button>
        </form>
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
            // Poll for new messages every 5 seconds
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

                // Scroll to bottom
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
