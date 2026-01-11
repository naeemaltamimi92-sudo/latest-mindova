@extends('layouts.app')

@section('title', $volunteer->user->name . ' - ' . __('Contributor Profile'))

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
    .skill-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .skill-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15); }
</style>
@endpush

@section('content')
<!-- Premium Hero Header -->
<div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-900 to-violet-900 py-16 mb-12 rounded-3xl shadow-2xl max-w-7xl mx-auto slide-up">
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
            <pattern id="volunteer-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#volunteer-grid)"/>
        </svg>
    </div>

    <div class="relative max-w-6xl mx-auto px-6 sm:px-8">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
            <!-- Profile Avatar -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500 rounded-3xl blur opacity-40 group-hover:opacity-60 transition duration-500"></div>
                <div class="relative w-32 h-32 lg:w-40 lg:h-40 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-2xl ring-4 ring-white/20 pulse-ring">
                    <span class="text-4xl lg:text-5xl font-black text-white">{{ substr($volunteer->user->name, 0, 2) }}</span>
                </div>
            </div>

            <!-- Volunteer Info -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-4">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 rounded-full text-sm font-semibold text-emerald-300">
                        <div class="relative">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <div class="absolute inset-0 w-2 h-2 bg-emerald-400 rounded-full animate-ping"></div>
                        </div>
                        {{ __('Active Contributor') }}
                    </span>
                    @if($volunteer->availability_hours_per_week >= 20)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/20 backdrop-blur-md border border-amber-400/30 rounded-full text-sm font-semibold text-amber-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                        </svg>
                        {{ __('High Availability') }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 tracking-tight">
                    {{ $volunteer->user->name }}
                </h1>

                @if($volunteer->bio)
                <p class="text-lg text-white/80 max-w-2xl leading-relaxed mb-6">
                    {{ Str::limit($volunteer->bio, 200) }}
                </p>
                @endif

                <!-- Quick Actions -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    @if($volunteer->user->linkedin_profile_url)
                    <a href="{{ $volunteer->user->linkedin_profile_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-6 py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        {{ __('LinkedIn Profile') }}
                    </a>
                    @endif
                    <div class="inline-flex items-center gap-2 text-white/70 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $volunteer->availability_hours_per_week }}{{ __('h/week available') }}
                    </div>
                </div>
            </div>

            <!-- Reputation Score Card -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 text-center min-w-[140px]">
                <div class="text-4xl font-black text-white mb-1">{{ $volunteer->reputation_score }}</div>
                <div class="text-sm text-white/70 font-medium">{{ __('Reputation') }}</div>
                <div class="w-full bg-white/20 rounded-full h-2 mt-3">
                    <div class="bg-gradient-to-r from-emerald-400 to-teal-400 h-2 rounded-full transition-all" style="width: {{ min(($volunteer->reputation_score / 1000) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 sm:px-8">
    <!-- Premium Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12 -mt-8 relative z-10 slide-up" style="animation-delay: 0.1s">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900">{{ $stats['completed_tasks'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Tasks Completed') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-indigo-600">{{ $stats['active_tasks'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Active Tasks') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-violet-600">{{ $stats['total_hours'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Total Hours') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-amber-600">{{ $stats['ideas_count'] }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ __('Ideas Submitted') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Skills & Expertise -->
            @if($volunteer->skills->count() > 0)
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-r from-indigo-50 to-violet-50 px-8 py-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Skills & Expertise') }}</h2>
                            <p class="text-sm text-slate-600">{{ __(':count verified skills', ['count' => $volunteer->skills->count()]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($volunteer->skills as $skill)
                        <div class="skill-card bg-gradient-to-br from-slate-50 to-indigo-50/30 rounded-xl p-5 border border-slate-200">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold text-slate-900">{{ $skill->skill_name }}</h3>
                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                    {{ $skill->proficiency_level === 'expert' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $skill->proficiency_level === 'advanced' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                                    {{ $skill->proficiency_level === 'intermediate' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                    {{ $skill->proficiency_level === 'beginner' ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                    {{ ucfirst($skill->proficiency_level) }}
                                </span>
                            </div>
                            @if($skill->years_of_experience)
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $skill->years_of_experience }} {{ __('years experience') }}
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
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.3s">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-8 py-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Completed Work') }}</h2>
                            <p class="text-sm text-slate-600">{{ __(':count tasks successfully completed', ['count' => $completedAssignments->count()]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($completedAssignments as $assignment)
                    <div class="px-8 py-6 hover:bg-slate-50 transition-all">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h3 class="text-lg font-bold text-slate-900">{{ $assignment->task->title }}</h3>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Completed') }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 mb-3">{{ $assignment->task->challenge->title }}</p>
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $assignment->actual_hours ?? 0 }}h {{ __('logged') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $assignment->updated_at->format('M d, Y') }}
                                    </span>
                                </div>
                                @if($assignment->completion_notes)
                                <p class="text-sm text-slate-600 mt-3 line-clamp-2">{{ Str::limit($assignment->completion_notes, 150) }}</p>
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
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.4s">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-8 py-6 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ __('Community Ideas') }}</h2>
                            <p class="text-sm text-slate-600">{{ __(':count ideas shared with the community', ['count' => $ideas->count()]) }}</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($ideas as $idea)
                    <a href="{{ route('ideas.show', $idea->id) }}" class="block px-8 py-6 hover:bg-slate-50 transition-all group">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">
                                    {{ $idea->title }}
                                </h3>
                                <p class="text-sm text-slate-600 mb-3">{{ $idea->challenge->title }}</p>
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-2 mb-4">
                                    {{ Str::limit($idea->description, 150) }}
                                </p>
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                    @if($idea->status === 'scored')
                                    <span class="flex items-center gap-1.5 text-indigo-600 font-semibold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('AI Score:') }} {{ round($idea->ai_score) }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 {{ $idea->community_votes >= 0 ? 'text-emerald-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $idea->community_votes >= 0 ? '+' : '' }}{{ $idea->community_votes }} {{ __('votes') }}
                                    </span>
                                    @endif
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $idea->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            @if($idea->status === 'scored')
                            <div class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-4 text-center min-w-[80px] shadow-lg">
                                <div class="text-2xl font-black text-white">{{ round($idea->final_score) }}</div>
                                <div class="text-xs text-white/80">{{ __('Score') }}</div>
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
        <div class="space-y-6">
            <!-- Reputation Breakdown -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden slide-up" style="animation-delay: 0.3s">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ __('Reputation Level') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-slate-700">{{ __('Progress') }}</span>
                            <span class="text-sm font-bold text-indigo-600">{{ $volunteer->reputation_score }} / 1000</span>
                        </div>
                        <div class="h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-600 rounded-full transition-all duration-500" style="width: {{ min(($volunteer->reputation_score / 1000) * 100, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ __('Task Completions') }}</span>
                            </div>
                            <span class="text-sm font-bold text-emerald-600">+{{ $stats['completed_tasks'] * 10 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ __('Quality Ratings') }}</span>
                            </div>
                            <span class="text-sm font-bold text-indigo-600">+{{ $stats['rating_points'] ?? 0 }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-xl border border-amber-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ __('Idea Votes') }}</span>
                            </div>
                            <span class="text-sm font-bold text-amber-600">+{{ $stats['vote_points'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Info -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold">{{ __('Member Since') }}</h3>
                        <p class="text-slate-300 text-sm">{{ $volunteer->created_at->format('F Y') }}</p>
                    </div>
                </div>
                <div class="text-sm text-slate-400">
                    {{ __('Active for :time', ['time' => $volunteer->created_at->diffForHumans(['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW])]) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
