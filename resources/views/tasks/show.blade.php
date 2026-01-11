@extends('layouts.app')

@section('title', $task->title)

@push('styles')
<style>
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .float-anim { animation: floatAnim 6s ease-in-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .pulse-ring { animation: pulseRing 2s ease-in-out infinite; }
    @keyframes pulseRing {
        0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        50% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
    }
    .requirement-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .requirement-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.15); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
    <!-- Back Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 slide-up">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('Back') }}
        </a>
    </div>

    <!-- Premium Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-12 mb-8 mx-4 sm:mx-6 lg:mx-8 mt-4 rounded-3xl shadow-2xl max-w-7xl lg:mx-auto slide-up" style="animation-delay: 0.1s">
        <!-- Animated Background Effects -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-400/20 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-violet-400/20 via-transparent to-transparent"></div>
        </div>
        <div class="absolute inset-0 overflow-hidden">
            <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
            <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
        </div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="task-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#task-grid)"/>
            </svg>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 sm:px-8">
            <div class="flex flex-col lg:flex-row items-start gap-8">
                <div class="flex-1">
                    <!-- Status Badge -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold backdrop-blur-md border
                            {{ $task->status === 'pending' ? 'bg-amber-500/20 border-amber-400/30 text-amber-300' : '' }}
                            {{ $task->status === 'in_progress' ? 'bg-blue-500/20 border-blue-400/30 text-blue-300' : '' }}
                            {{ $task->status === 'completed' ? 'bg-emerald-500/20 border-emerald-400/30 text-emerald-300' : '' }}
                            {{ $task->status === 'matching' ? 'bg-violet-500/20 border-violet-400/30 text-violet-300' : '' }}">
                            <div class="relative">
                                <div class="w-2 h-2 rounded-full animate-pulse
                                    {{ $task->status === 'pending' ? 'bg-amber-400' : '' }}
                                    {{ $task->status === 'in_progress' ? 'bg-blue-400' : '' }}
                                    {{ $task->status === 'completed' ? 'bg-emerald-400' : '' }}
                                    {{ $task->status === 'matching' ? 'bg-violet-400' : '' }}"></div>
                            </div>
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                        @if($task->priority === 'critical')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 backdrop-blur-md border border-red-400/30 rounded-full text-sm font-bold text-red-300">
                            <span class="text-lg">🔥</span>{{ __('Critical') }}
                        </span>
                        @elseif($task->priority === 'high')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500/20 backdrop-blur-md border border-orange-400/30 rounded-full text-sm font-bold text-orange-300">
                            <span class="text-lg">⚡</span>{{ __('High Priority') }}
                        </span>
                        @endif
                    </div>

                    <!-- Task Title -->
                    <h1 class="text-3xl md:text-4xl font-black text-white mb-4 tracking-tight">
                        {{ $task->title }}
                    </h1>

                    <!-- Challenge Link -->
                    <a href="{{ route('challenges.show', $task->challenge) }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors text-sm mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $task->challenge->title }}
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="flex flex-wrap gap-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[100px]">
                        <div class="text-3xl font-black text-white mb-1">{{ $task->estimated_hours }}</div>
                        <div class="text-xs text-white/70 font-medium">{{ __('Hours Est.') }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[100px]">
                        <div class="text-3xl font-black text-white mb-1">{{ $task->complexity_score }}</div>
                        <div class="text-xs text-white/70 font-medium">{{ __('Complexity') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Task Description -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.2s">
                    <div class="bg-gradient-to-r from-slate-50 to-indigo-50/30 px-8 py-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
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
                    <div class="bg-gradient-to-r from-indigo-50 to-violet-50 px-8 py-6 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg pulse-ring">
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
                            <div class="requirement-card bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-slate-900">{{ __('What to Deliver') }}</h3>
                                </div>
                                <p class="text-slate-700 text-sm leading-relaxed">{{ $task->expected_output }}</p>
                            </div>

                            <!-- Complexity & Priority -->
                            <div class="requirement-card bg-gradient-to-br from-slate-50 to-indigo-50/30 rounded-2xl p-6 border border-slate-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
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
                                            <div class="h-full rounded-full transition-all
                                                @if($task->complexity_score <= 3) bg-gradient-to-r from-emerald-400 to-emerald-500
                                                @elseif($task->complexity_score <= 6) bg-gradient-to-r from-amber-400 to-amber-500
                                                @elseif($task->complexity_score <= 8) bg-gradient-to-r from-orange-400 to-orange-500
                                                @else bg-gradient-to-r from-red-500 to-red-600
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
                        <div class="requirement-card bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
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
                                <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-green-200 hover:border-green-300 transition-colors">
                                    <div class="flex-shrink-0 w-5 h-5 mt-0.5 border-2 border-green-500 rounded bg-white"></div>
                                    <p class="text-sm text-slate-700 flex-1">{{ $criteria }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Required Skills -->
                        <div class="requirement-card bg-gradient-to-br from-violet-50 to-purple-50 rounded-2xl p-6 border border-violet-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-900">{{ __('Required Skills') }}</h3>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($task->required_skills as $skill)
                                <span class="px-4 py-2 bg-white text-violet-700 rounded-xl text-sm font-semibold border border-violet-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
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
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl shadow-lg border border-blue-200 overflow-hidden slide-up" style="animation-delay: 0.35s">
                    <div class="px-8 py-6 border-b border-blue-200 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
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
                            <a href="{{ route('challenges.show', $task->challenge->id) }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-5 py-2.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 border border-indigo-200">
                                {{ __('View Challenge') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @php
                $myAssignment = $task->assignments->where('volunteer_id', auth()->user()->volunteer?->id)->first();
                @endphp

                @if($myAssignment)
                <!-- My Assignment -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl shadow-lg border border-blue-200 overflow-hidden slide-up" style="animation-delay: 0.4s">
                    <div class="px-8 py-6 border-b border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-900">{{ __('Your Assignment') }}</h3>
                            </div>
                            <span class="px-4 py-2 rounded-xl text-sm font-bold
                                {{ $myAssignment->invitation_status === 'accepted' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                {{ $myAssignment->invitation_status === 'in_progress' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                {{ $myAssignment->invitation_status === 'submitted' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                                {{ $myAssignment->invitation_status === 'completed' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $myAssignment->invitation_status === 'invited' ? 'bg-violet-100 text-violet-700 border border-violet-200' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $myAssignment->invitation_status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        @php
                            $latestSubmission = $myAssignment->workSubmissions()->latest()->first();
                        @endphp

                        @if($latestSubmission && $latestSubmission->status === 'revision_requested')
                        <!-- Revision Required Notice -->
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-2xl p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                    <span class="text-xl">⚠️</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-orange-900">{{ __('Revision Required') }}</h4>
                                    <p class="text-sm text-orange-700">{{ __('Score: :score/100 - Below 70% threshold', ['score' => $latestSubmission->ai_quality_score]) }}</p>
                                </div>
                            </div>

                            @if($latestSubmission->ai_feedback)
                            @php
                                $feedback = json_decode($latestSubmission->ai_feedback, true);
                            @endphp
                            <div class="bg-white rounded-xl p-4 space-y-3">
                                <p class="text-sm text-slate-700"><strong class="text-slate-900">{{ __('AI Feedback:') }}</strong></p>
                                <p class="text-sm text-slate-600">{{ is_array($feedback) ? ($feedback['feedback'] ?? $latestSubmission->ai_feedback) : $latestSubmission->ai_feedback }}</p>

                                @if(is_array($feedback) && isset($feedback['areas_for_improvement']))
                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <p class="text-xs font-bold text-orange-700 mb-2">{{ __('Areas for Improvement:') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($feedback['areas_for_improvement'] as $area)
                                        <li class="text-sm text-slate-700 flex items-start gap-2">
                                            <span class="text-orange-500 mt-1">•</span>{{ $area }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if(is_array($feedback) && isset($feedback['missing_requirements']))
                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <p class="text-xs font-bold text-red-700 mb-2">{{ __('Missing Requirements:') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($feedback['missing_requirements'] as $req)
                                        <li class="text-sm text-slate-700 flex items-start gap-2">
                                            <span class="text-red-500 mt-1">•</span>{{ $req }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif

                            <button onclick="showSubmitSolutionModal({{ $myAssignment->id }})" class="w-full mt-4 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold px-6 py-3 rounded-xl hover:from-orange-600 hover:to-amber-600 transition-all shadow-lg">
                                <span class="text-lg">📝</span>{{ __('Submit Improved Solution') }}
                            </button>
                        </div>
                        @elseif($latestSubmission && $latestSubmission->status === 'approved')
                        <!-- Approved Solution Notice -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <span class="text-xl">✅</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-green-900">{{ __('Solution Approved!') }}</h4>
                                    <p class="text-sm text-green-700">{{ __('Score: :score/100 - Great work!', ['score' => $latestSubmission->ai_quality_score]) }}</p>
                                </div>
                            </div>

                            @if($latestSubmission->ai_feedback)
                            @php
                                $feedback = json_decode($latestSubmission->ai_feedback, true);
                            @endphp
                            <div class="bg-white rounded-xl p-4">
                                <p class="text-sm text-slate-700 mb-2"><strong class="text-slate-900">{{ __('AI Feedback:') }}</strong></p>
                                <p class="text-sm text-slate-600">{{ is_array($feedback) ? ($feedback['feedback'] ?? $latestSubmission->ai_feedback) : $latestSubmission->ai_feedback }}</p>

                                @if(is_array($feedback) && isset($feedback['strengths']))
                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <p class="text-xs font-bold text-green-700 mb-2">{{ __('Strengths:') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($feedback['strengths'] as $strength)
                                        <li class="text-sm text-slate-700 flex items-start gap-2">
                                            <span class="text-green-500 mt-1">✓</span>{{ $strength }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                        @elseif($latestSubmission && $latestSubmission->ai_analysis_status === 'pending')
                        <!-- Analysis Pending -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center animate-pulse">
                                    <span class="text-xl">⏳</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-900">{{ __('AI Analysis in Progress...') }}</h4>
                                    <p class="text-sm text-blue-700">{{ __('Your solution is being analyzed. This usually takes 30-60 seconds.') }}</p>
                                </div>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full animate-pulse" style="width: 70%"></div>
                            </div>
                        </div>
                        @endif

                        @if($myAssignment->match_reasoning)
                        @php
                        $reasoning = json_decode($myAssignment->match_reasoning, true);
                        @endphp
                        <div class="bg-white rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <h4 class="font-bold text-slate-900">{{ __('Why you were matched') }} <span class="text-indigo-600">({{ $myAssignment->match_score }}%)</span></h4>
                            </div>
                            <p class="text-sm text-slate-700 mb-4">{{ $reasoning['reasoning'] ?? '' }}</p>

                            <div class="grid md:grid-cols-2 gap-4">
                                @if(isset($reasoning['strengths']))
                                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                    <p class="text-xs font-bold text-emerald-700 mb-2">{{ __('Your Strengths') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($reasoning['strengths'] as $strength)
                                        <li class="text-sm text-slate-700 flex items-start gap-2">
                                            <span class="text-emerald-500 mt-1">✓</span>{{ $strength }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if(isset($reasoning['gaps']) && count($reasoning['gaps']) > 0)
                                <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                                    <p class="text-xs font-bold text-amber-700 mb-2">{{ __('Areas to Develop') }}</p>
                                    <ul class="space-y-1">
                                        @foreach($reasoning['gaps'] as $gap)
                                        <li class="text-sm text-slate-700 flex items-start gap-2">
                                            <span class="text-amber-500 mt-1">•</span>{{ $gap }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-blue-200">
                            @if($myAssignment->invitation_status === 'invited')
                            <form action="{{ route('assignments.accept', $myAssignment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold px-6 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Accept Assignment') }}
                                </button>
                            </form>
                            <form action="{{ route('assignments.decline', $myAssignment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 font-bold px-6 py-3 rounded-xl hover:bg-slate-200 transition-all border border-slate-200">
                                    {{ __('Decline') }}
                                </button>
                            </form>
                            @elseif($myAssignment->invitation_status === 'accepted')
                            <form action="{{ route('assignments.start', $myAssignment->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-violet-500 text-white font-bold px-6 py-3 rounded-xl hover:from-indigo-600 hover:to-violet-600 transition-all shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Start Working') }}
                                </button>
                            </form>
                            @elseif($myAssignment->invitation_status === 'in_progress')
                            <button onclick="showSubmitSolutionModal({{ $myAssignment->id }})" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold px-6 py-3 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Submit Solution') }}
                            </button>
                            @elseif($myAssignment->invitation_status === 'submitted')
                            <span class="inline-flex items-center gap-2 text-blue-700 font-bold px-4 py-2 bg-blue-100 rounded-xl border border-blue-200">
                                <span class="animate-pulse">⏳</span>{{ __('Solution Submitted - Under Review') }}
                            </span>
                            @elseif($myAssignment->invitation_status === 'completed')
                            <span class="inline-flex items-center gap-2 text-emerald-700 font-bold px-4 py-2 bg-emerald-100 rounded-xl border border-emerald-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('Completed') }}
                            </span>
                            @if($myAssignment->actual_hours)
                            <span class="text-sm text-slate-600">{{ $myAssignment->actual_hours }} {{ __('hours logged') }}</span>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Task Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.25s">
                    <div class="bg-gradient-to-r from-slate-50 to-indigo-50/30 px-6 py-4 border-b border-slate-100">
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
                                <div class="h-full rounded-full transition-all
                                    @if($task->complexity_score <= 3) bg-gradient-to-r from-emerald-400 to-emerald-500
                                    @elseif($task->complexity_score <= 6) bg-gradient-to-r from-amber-400 to-amber-500
                                    @elseif($task->complexity_score <= 8) bg-gradient-to-r from-orange-400 to-orange-500
                                    @else bg-gradient-to-r from-red-500 to-red-600
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
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ __('Assigned Contributors') }}
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($task->assignments->where('invitation_status', '!=', 'rejected') as $assignment)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
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
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-6">
                <h3 class="text-xl font-bold text-white">{{ __('Submit Your Solution') }}</h3>
                <p class="text-indigo-100 text-sm mt-1">{{ __('Describe your work and submit for AI review') }}</p>
            </div>
            <form id="submitSolutionForm" method="POST" action="" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Solution Description') }} *</label>
                        <textarea name="description" rows="4" required class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                  placeholder="{{ __('Describe your solution, the approach you took, and any key decisions...') }}"></textarea>
                        <p class="text-xs text-slate-500 mt-1">{{ __('Explain what you built and how it solves the task') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">{{ __('Deliverable URL') }}</label>
                        <input type="url" name="deliverable_url" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
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
                        <input type="number" name="hours_worked" step="0.5" min="0.5" required class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                               placeholder="{{ __('e.g., 5.5') }}">
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                        <p class="text-sm text-blue-900">
                            <strong class="text-indigo-700">📝 {{ __('Note:') }}</strong> {{ __('Your solution will be analyzed by AI to assess quality and completeness. High-quality solutions that solve the task will be combined with other team members\' work and presented to the challenge owner.') }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                    <button type="button" onclick="closeSubmitSolutionModal()" class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-all">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">{{ __('Submit Solution') }}</button>
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
</script>
@endsection
