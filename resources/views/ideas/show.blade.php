@extends('layouts.app')

@section('title', __('Idea Details'))

@push('styles')
<style>
    .idea-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .idea-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .quality-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('community.index') }}" class="text-white/80 hover:text-white transition flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Community') }}
                </a>
                <span class="text-white/50">/</span>
                <span class="text-white/80">{{ $idea->challenge->field ?? __('Challenge') }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-3">
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ __('Score') }}: {{ $idea->challenge->score ?? 'N/A' }}/10
                </span>
                @if($idea->challenge->field)
                <span class="px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ $idea->challenge->field }}
                </span>
                @endif
                <span class="px-3 py-1.5 bg-purple-500/50 backdrop-blur rounded-full text-sm font-semibold">
                    {{ __('Community Discussion') }}
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold">{{ $idea->challenge->title }}</h1>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Challenge Details Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Challenge Details') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4 text-sm text-slate-500">
                            @if($idea->challenge->company)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $idea->challenge->company->company_name }}
                            </span>
                            @endif
                            <span>{{ $idea->challenge->created_at->diffForHumans() }}</span>
                            @if($idea->challenge->deadline)
                            <span class="text-amber-600">
                                {{ __('Ends') }} {{ \Carbon\Carbon::parse($idea->challenge->deadline)->format('M d, Y') }}
                            </span>
                            @endif
                        </div>

                        <div class="prose max-w-none text-slate-700 whitespace-pre-line mb-4">
                            {{ $idea->challenge->original_description }}
                        </div>

                        @if($idea->challenge->refined_brief)
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-5">
                            <h4 class="text-sm font-bold text-indigo-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 14a1 1 0 112 0 1 1 0 01-2 0zm1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ __('AI-Refined Brief') }}
                            </h4>
                            <p class="text-sm text-indigo-800 whitespace-pre-line">{{ $idea->challenge->refined_brief }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Current Idea Highlight -->
                <div class="bg-white rounded-2xl shadow-sm border-2 border-violet-300 overflow-hidden ring-2 ring-violet-100">
                    <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ __('This Idea') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($idea->volunteer->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $idea->volunteer->user->name ?? __('Anonymous') }}</p>
                                <p class="text-xs text-slate-500">{{ $idea->volunteer->field ?? '' }} • {{ $idea->created_at->diffForHumans() }}</p>
                            </div>
                            @if($idea->ai_quality_score >= 7)
                            <span class="quality-badge ml-auto px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-bold rounded-full">
                                {{ __('High Quality') }}
                            </span>
                            @endif
                        </div>

                        <div class="prose max-w-none text-slate-700 whitespace-pre-line">
                            {{ $idea->content }}
                        </div>

                        @if($idea->ai_feedback)
                        <div class="mt-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl p-4">
                            <h5 class="text-sm font-bold text-emerald-900 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('AI Assessment') }}
                            </h5>
                            <p class="text-sm text-emerald-800">{{ $idea->ai_feedback['feedback'] ?? '' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Other High-Quality Ideas from Community -->
                @php
                    $otherIdeas = $idea->challenge->ideas()
                        ->where('id', '!=', $idea->id)
                        ->where('ai_quality_score', '>=', 7)
                        ->orderBy('ai_quality_score', 'desc')
                        ->with('volunteer.user')
                        ->get();
                @endphp

                @if($otherIdeas->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                            </svg>
                            {{ __('Other High-Quality Ideas') }} ({{ $otherIdeas->count() }})
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($otherIdeas as $otherIdea)
                        <div class="idea-card bg-slate-50 rounded-xl p-5 border border-slate-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($otherIdea->volunteer->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm">{{ $otherIdea->volunteer->user->name ?? __('Anonymous') }}</p>
                                        <p class="text-xs text-slate-500">{{ $otherIdea->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                    {{ number_format($otherIdea->ai_quality_score, 1) }}/10
                                </span>
                            </div>
                            <p class="text-sm text-slate-700 line-clamp-4">{{ Str::limit($otherIdea->content, 300) }}</p>
                            @if(strlen($otherIdea->content) > 300)
                            <a href="{{ route('ideas.show', $otherIdea->id) }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium mt-2 inline-block">
                                {{ __('Read more') }} →
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- All Ideas from Community -->
                @php
                    $allIdeas = $idea->challenge->ideas()
                        ->where('id', '!=', $idea->id)
                        ->where('ai_quality_score', '<', 7)
                        ->orderBy('ai_quality_score', 'desc')
                        ->with('volunteer.user')
                        ->take(5)
                        ->get();
                @endphp

                @if($allIdeas->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-600 to-slate-700 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z"/>
                            </svg>
                            {{ __('Other Community Ideas') }}
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($allIdeas as $otherIdea)
                        <div class="idea-card bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-gradient-to-br from-slate-400 to-slate-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($otherIdea->volunteer->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 text-sm">{{ $otherIdea->volunteer->user->name ?? __('Anonymous') }}</p>
                                    </div>
                                </div>
                                @if($otherIdea->ai_quality_score)
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-full">
                                    {{ number_format($otherIdea->ai_quality_score, 1) }}/10
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600 line-clamp-3">{{ Str::limit($otherIdea->content, 200) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Idea Score Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white">{{ __('Idea Score') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full
                                {{ $idea->ai_quality_score >= 7 ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : ($idea->ai_quality_score >= 5 ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : 'bg-gradient-to-br from-slate-400 to-slate-500') }} text-white">
                                <span class="text-3xl font-black">{{ number_format($idea->ai_quality_score ?? 0, 1) }}</span>
                            </div>
                            <p class="text-sm text-slate-500 mt-2">{{ __('AI Quality Score') }}</p>
                        </div>

                        <div class="space-y-3">
                            @if($idea->ai_relevance_score)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('Relevance') }}</span>
                                <span class="font-bold text-slate-900">{{ number_format($idea->ai_relevance_score, 1) }}</span>
                            </div>
                            @endif
                            @if($idea->ai_feasibility_score)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('Feasibility') }}</span>
                                <span class="font-bold text-slate-900">{{ number_format($idea->ai_feasibility_score, 1) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="border-t border-slate-200 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">{{ __('Community Votes') }}</span>
                                <span class="font-bold {{ ($idea->community_votes_up - $idea->community_votes_down) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ ($idea->community_votes_up ?? 0) - ($idea->community_votes_down ?? 0) >= 0 ? '+' : '' }}{{ ($idea->community_votes_up ?? 0) - ($idea->community_votes_down ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Challenge Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h4 class="font-semibold text-slate-900 mb-4">{{ __('Challenge Stats') }}</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">{{ __('Total Ideas') }}</span>
                            <span class="font-bold text-slate-900">{{ $idea->challenge->ideas->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">{{ __('High Quality') }}</span>
                            <span class="font-bold text-emerald-600">{{ $idea->challenge->ideas->where('ai_quality_score', '>=', 7)->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">{{ __('Contributors') }}</span>
                            <span class="font-bold text-slate-900">{{ $idea->challenge->ideas->unique('volunteer_id')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Posted By -->
                @if($idea->challenge->company)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h4 class="font-semibold text-slate-900 mb-4">{{ __('Challenge By') }}</h4>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($idea->challenge->company->company_name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $idea->challenge->company->company_name }}</p>
                            <p class="text-sm text-slate-500">{{ $idea->challenge->company->industry ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
