@extends('layouts.app')

@section('title', $team->name)

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .float-anim { animation: floatAnim 3s-out infinite; }
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
<div class="relative overflow-hidden bg-primary-500 py-12 mb-12 rounded-3xl max-w-7xl mx-auto shadow-2xl">
    <!-- Animated Background Effects -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full "></div>
        <div class="absolute bottom-0 right-0 w-full h-full "></div>
        <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
        <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl float-anim"></div>
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
                        <div class="w-2.5 h-2.5 rounded-full
                            {{ $team->status === 'active' ? 'bg-emerald-400' : '' }}
                            {{ $team->status === 'forming' ? 'bg-amber-400' : '' }}
                            {{ $team->status === 'completed' ? 'bg-indigo-400' : '' }}"></div>
                        <div class="absolute inset-0 w-2.5 h-2.5 rounded-full
                            {{ $team->status === 'active' ? 'bg-emerald-400' : '' }}
                            {{ $team->status === 'forming' ? 'bg-amber-400' : '' }}
                            {{ $team->status === 'completed' ? 'bg-indigo-400' : '' }}"></div>
                    </div>
                    <span class="text-sm font-semibold !text-white" style="color: white !important;">{{ __(ucfirst($team->status)) }} {{ __('Team') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black !text-white mb-4 leading-tight" style="color: white !important;">
                    {{ $team->name }}
                </h1>
                <p class="text-lg !text-white/90 font-medium leading-relaxed max-w-2xl mb-8" style="color: rgba(255, 255, 255, 0.9) !important;">
                    {{ $team->description }}
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3">
                        <div class="w-10 h-10 bg-secondary-500 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black !text-white" style="color: white !important;">{{ $team->team_size }}</p>
                            <p class="text-xs !text-white/70 font-semibold uppercase tracking-wider" style="color: rgba(255, 255, 255, 0.7) !important;">{{ __('Members') }}</p>
                        </div>
                    </div>
                    @if($team->team_match_score)
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3">
                        <div class="w-10 h-10 bg-secondary-300 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black !text-white" style="color: white !important;">{{ $team->team_match_score }}%</p>
                            <p class="text-xs !text-white/70 font-semibold uppercase tracking-wider" style="color: rgba(255, 255, 255, 0.7) !important;">{{ __('Match Score') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Challenge Link Card -->
            <div class="lg:w-80">
                <div class="bg-white/10 backdrop-blur-xl border border-white/30 rounded-2xl p-6 shadow-xl hover:bg-white/15 transition-all duration-300 group">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] !text-white/80 font-bold uppercase tracking-widest" style="color: rgba(255, 255, 255, 0.8) !important;">{{ __('Working On') }}</p>
                            <p class="text-sm font-bold !text-white" style="color: white !important;">{{ __('Challenge') }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold !text-white mb-6 line-clamp-2 leading-snug min-h-[3rem]" style="color: white !important;">
                        {{ $team->challenge->title }}
                    </h3>
                    
                    <x-ui.button as="a" href="{{ route('challenges.show', $team->challenge) }}" size="md" fullWidth class="!bg-white !text-primary-600 hover:!bg-gray-50 border-0 shadow-lg font-bold">
                        {{ __('View Challenge') }}
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </x-ui.button>
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
                <div class="flex-shrink-0 w-6 h-6 bg-primary-500 rounded-lg flex items-center justify-center mt-0.5">
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
                <div class="bg-primary-500 h-3 rounded-full" style="width: {{ $team->skills_coverage['coverage_percentage'] ?? 0 }}%"></div>
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
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-20">
        <h2 class="text-2xl font-black text-slate-900 mb-6">{{ __('Team Members') }}</h2>
        <div class="block">
            @foreach($team->members as $member)
            <div class="group bg-slate-50 rounded-2xl p-4 border border-slate-200 hover:border-indigo-200 hover:shadow-md transition-all duration-300 {{ $member->status === 'invited' && $member->volunteer_id === auth()->user()->volunteer?->id ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-200' : '' }}"
                 style="{{ !$loop->last ? 'margin-bottom: 16px;' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-500 flex items-center justify-center text-white font-black text-lg shadow-sm">
                            {{ substr($member->volunteer->user->name, 0, 1) }}
                        </div>

                        <!-- Member Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h3 class="text-base font-black text-slate-900">{{ $member->volunteer->user->name }}</h3>
                                @if($member->role === 'leader')
                                <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] font-bold uppercase tracking-wide">{{ __('Team Leader') }}</span>
                                @elseif($member->role === 'specialist')
                                <span class="px-2.5 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-md text-[10px] font-bold uppercase tracking-wide">{{ __('Specialist') }}</span>
                                @endif
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border uppercase tracking-wide
                                    {{ $member->status === 'accepted' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $member->status === 'invited' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $member->status === 'declined' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}">
                                    {{ __(ucfirst($member->status)) }}
                                </span>
                            </div>

                            @if($member->role_description)
                            <p class="text-sm text-slate-600 mb-3 leading-relaxed line-clamp-2">{{ $member->role_description }}</p>
                            @endif

                            <!-- Member Skills -->
                            @if($member->assigned_skills && count($member->assigned_skills) > 0)
                            <div class="flex flex-wrap gap-3 mb-3">
                                @foreach($member->assigned_skills as $skill)
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-medium">{{ $skill }}</span>
                                @endforeach
                            </div>
                            @endif

                            <!-- Member Stats -->
                            <div class="flex flex-wrap items-center gap-4 text-xs mt-1">
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
                        <div class="mt-6 pt-6 border-t border-slate-200 lg:mt-0 lg:pt-0 lg:border-t-0 lg:border-l lg:ml-6 lg:pl-6 lg:w-[400px] flex-shrink-0">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-5 h-5 bg-primary-500 rounded flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ __('Performance Metrics') }}</h4>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <!-- Task Progress -->
                                <div class="flex flex-col items-center bg-white rounded-lg p-3 border border-slate-100 shadow-sm">
                                    <x-progress-circle
                                        :percentage="$taskProgress"
                                        :size="65"
                                        label=""
                                        stroke="5"
                                        color="#6366F1"
                                    />
                                    <p class="text-[10px] font-bold text-slate-500 mt-2 uppercase tracking-wide">{{ __('Tasks') }}</p>
                                    <p class="text-xs font-black text-slate-900">{{ $completedTasks }}/{{ $totalTasks }}</p>
                                </div>

                                <!-- Quality Score -->
                                <div class="flex flex-col items-center bg-white rounded-lg p-3 border border-slate-100 shadow-sm">
                                    <x-progress-circle
                                        :percentage="$performanceScore"
                                        :size="65"
                                        label=""
                                        stroke="5"
                                        color="#10b981"
                                    />
                                    <p class="text-[10px] font-bold text-slate-500 mt-2 uppercase tracking-wide">{{ __('Quality') }}</p>
                                    <p class="text-xs font-black text-slate-900">{{ round($performanceScore) }}%</p>
                                </div>

                                <!-- Time Efficiency -->
                                <div class="flex flex-col items-center bg-white rounded-lg p-3 border border-slate-100 shadow-sm">
                                    <x-progress-circle
                                        :percentage="$timeEfficiency"
                                        :size="65"
                                        label=""
                                        stroke="5"
                                        color="#f59e0b"
                                    />
                                    <p class="text-[10px] font-bold text-slate-500 mt-2 uppercase tracking-wide">{{ __('Efficiency') }}</p>
                                    <p class="text-xs font-black text-slate-900 whitespace-nowrap">
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
                    <div class="flex flex-col gap-2 ml-4">
                        <form action="{{ route('teams.accept', $team) }}" method="POST">
                            @csrf
                            <x-ui.button as="submit" variant="secondary" fullWidth>
                                {{ __('Accept Invitation') }}
                            </x-ui.button>
                        </form>
                        <form action="{{ route('teams.decline', $team) }}" method="POST">
                            @csrf
                            <x-ui.button as="submit" variant="outline" fullWidth>
                                {{ __('Decline') }}
                            </x-ui.button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            <!-- Team Chat (Only for accepted members) -->
    @php
        $myMembership = $team->members->where('volunteer_id', auth()->user()->volunteer?->id)->first();
        $isAcceptedMember = $myMembership && $myMembership->status === 'accepted';
    @endphp

    @if($isAcceptedMember)
    <div class="card-premium relative overflow-hidden m-10" x-data="teamChat({{ $team->id }})">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 leading-tight">{{ __('Team Chat') }}</h2>
                    <p class="text-sm font-medium text-slate-500">{{ __('Collaborate with your team members in real-time') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">{{ __('Live') }}</span>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="bg-slate-50/50 backdrop-blur-sm rounded-3xl border border-slate-200/60 p-6 h-[500px] flex flex-col relative z-10">
            
            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto pr-2 space-y-6 custom-scrollbar" id="chatMessages">
                <div x-show="loading" class="h-full flex flex-col items-center justify-center text-slate-400">
                    <svg class="w-10 h-10 animate-spin mb-3 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="font-medium text-sm animate-pulse">{{ __('Loading conversation...') }}</p>
                </div>

                <template x-for="message in messages" :key="message.id">
                    <div class="group flex flex-col transition-all duration-300 ease-out" 
                         :class="message.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                        
                        <div class="flex items-end gap-3 max-w-[85%] lg:max-w-[70%]" 
                             :class="message.user_id === {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
                            
                            <!-- Avatar Placeholder -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold ring-2 ring-white shadow-sm"
                                 :class="message.user_id === {{ auth()->id() }} ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-200 text-slate-600'">
                                <span x-text="message.user_name.charAt(0)"></span>
                            </div>

                            <div class="flex flex-col" :class="message.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                                <div class="flex items-center gap-2 mb-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-1">
                                    <span class="text-[10px] font-bold text-slate-500" x-text="message.user_name"></span>
                                    <span class="text-[10px] text-slate-400" x-text="message.time_ago"></span>
                                </div>
                                
                                <div class="relative px-5 py-3.5 shadow-sm text-sm leading-relaxed transition-transform duration-200 hover:scale-[1.01]"
                                     :class="message.user_id === {{ auth()->id() }} 
                                        ? 'bg-gradient-to-br from-indigo-600 to-primary-600 text-white rounded-2xl rounded-tr-sm shadow-indigo-500/20' 
                                        : 'bg-white text-slate-800 border border-slate-100 rounded-2xl rounded-tl-sm shadow-slate-200/50'">
                                    <p x-text="message.message" class="whitespace-pre-wrap"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="!loading && messages.length === 0" class="h-full flex flex-col items-center justify-center text-center p-8">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">{{ __('No messages yet') }}</h3>
                    <p class="text-sm text-slate-500 max-w-xs mx-auto mb-6">{{ __('Be the first to say hello and kickstart the collaboration!') }}</p>
                    <div class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold uppercase tracking-wider">
                        {{ __('Encrypted & Secure') }}
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="mt-4 relative z-20">
                <form @submit.prevent="sendMessage" class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-10 group-focus-within:opacity-20 transition duration-500"></div>
                    <div class="relative flex items-end gap-2 bg-white rounded-2xl p-2 shadow-lg border border-slate-100 focus-within:border-indigo-200 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-300">
                        <textarea
                            x-model="newMessage"
                            @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                            placeholder="{{ __('Type your message here...') }}"
                            class="w-full max-h-32 min-h-[50px] py-3 pl-4 pr-12 bg-transparent border-0 focus:ring-0 text-slate-800 placeholder:text-slate-400 resize-none text-sm leading-relaxed custom-scrollbar"
                            rows="1"
                            maxlength="2000"
                            required
                        ></textarea>
                        
                        <div class="absolute right-2 bottom-2">
                            <button 
                                type="submit" 
                                :disabled="sending || !newMessage.trim()"
                                class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transition-all duration-200"
                            >
                                <svg x-show="!sending" class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <svg x-show="sending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-2">
                    <p class="text-[10px] font-medium text-slate-400">{{ __('Press Enter to send, Shift + Enter for new line') }}</p>
                </div>
            </div>
        </div>

        <!-- Background Decorations -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-gradient-to-tr from-blue-500/10 to-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>
    @endif
</div>
        </div>
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
