@extends('layouts.app')

@section('title', __('Work Submissions'))

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="submissionsPage()">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="bg-white rounded-xl border border-gray-200 mb-6">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ __('Work Submissions') }}</h1>
                            <p class="text-gray-500">{{ __('Review and manage volunteer deliverables') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($stats['pending'] > 0)
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <span class="text-sm font-medium text-amber-700">{{ $stats['pending'] }} {{ __('Pending') }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-full">
                            <span class="text-sm font-medium text-gray-700">{{ $stats['total'] }} {{ __('Total') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Stats --}}
            <div class="border-t border-gray-100">
                <div class="grid grid-cols-3 md:grid-cols-6">
                    @php
                    $statItems = [
                        ['key' => 'all', 'value' => $stats['total'], 'label' => 'All', 'color' => 'gray'],
                        ['key' => 'submitted', 'value' => $stats['pending'], 'label' => 'Pending', 'color' => 'amber'],
                        ['key' => 'under_review', 'value' => $stats['under_review'], 'label' => 'Reviewing', 'color' => 'blue'],
                        ['key' => 'approved', 'value' => $stats['approved'], 'label' => 'Approved', 'color' => 'emerald'],
                        ['key' => 'revision_requested', 'value' => $stats['revision_requested'], 'label' => 'Revisions', 'color' => 'orange'],
                        ['key' => 'rejected', 'value' => $stats['rejected'], 'label' => 'Rejected', 'color' => 'red'],
                    ];
                    @endphp

                    @foreach($statItems as $stat)
                    <button
                        @click="activeFilter = '{{ $stat['key'] }}'"
                        class="px-4 py-4 text-center border-b-2 transition-colors hover:bg-gray-50"
                        :class="activeFilter === '{{ $stat['key'] }}' ? 'border-{{ $stat['color'] }}-500 bg-{{ $stat['color'] }}-50/30' : 'border-transparent'"
                    >
                        <div class="text-xl font-bold transition-colors"
                            :class="activeFilter === '{{ $stat['key'] }}' ? 'text-{{ $stat['color'] }}-600' : ($stat['value'] > 0 ? 'text-gray-900' : 'text-gray-300')"
                        >
                            {{ $stat['value'] }}
                        </div>
                        <div class="text-xs font-medium mt-1 transition-colors"
                            :class="activeFilter === '{{ $stat['key'] }}' ? 'text-{{ $stat['color'] }}-600' : 'text-gray-500'"
                        >
                            {{ $stat['label'] }}
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
        <div class="mb-6" x-data="{ show: true }" x-show="show">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Submissions List --}}
        <div class="space-y-4">
            @forelse($submissions as $submission)
            @php
                $statusConfig = [
                    'submitted' => [
                        'bg' => 'bg-amber-50',
                        'border' => 'border-amber-200',
                        'text' => 'text-amber-700',
                        'label' => 'Pending Review',
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                    'under_review' => [
                        'bg' => 'bg-blue-50',
                        'border' => 'border-blue-200',
                        'text' => 'text-blue-700',
                        'label' => 'Under Review',
                        'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                    ],
                    'approved' => [
                        'bg' => 'bg-emerald-50',
                        'border' => 'border-emerald-200',
                        'text' => 'text-emerald-700',
                        'label' => 'Approved',
                        'icon' => 'M5 13l4 4L19 7'
                    ],
                    'revision_requested' => [
                        'bg' => 'bg-orange-50',
                        'border' => 'border-orange-200',
                        'text' => 'text-orange-700',
                        'label' => 'Revision Requested',
                        'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                    ],
                    'rejected' => [
                        'bg' => 'bg-red-50',
                        'border' => 'border-red-200',
                        'text' => 'text-red-700',
                        'label' => 'Rejected',
                        'icon' => 'M6 18L18 6M6 6l12 12'
                    ],
                ];
                $config = $statusConfig[$submission->status] ?? $statusConfig['submitted'];
            @endphp

            <div
                x-show="activeFilter === 'all' || activeFilter === '{{ $submission->status }}'"
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-gray-300 transition-colors"
            >
                <div class="p-5">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-5">
                        {{-- Main Content --}}
                        <div class="flex-1 min-w-0">
                            {{-- Header Row --}}
                            <div class="flex flex-wrap items-start gap-3 mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition-colors">
                                    <a href="{{ route('company.submissions.show', $submission->id) }}">
                                        {{ $submission->task->title ?? 'Task' }}
                                    </a>
                                </h3>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                    </svg>
                                    {{ $config['label'] }}
                                </span>
                                @if($submission->is_spam)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Spam
                                </span>
                                @endif
                            </div>

                            {{-- Challenge --}}
                            <p class="text-sm text-gray-500 flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="truncate">{{ $submission->task->challenge->title ?? 'Challenge' }}</span>
                            </p>

                            {{-- Volunteer & Meta --}}
                            <div class="flex flex-wrap items-center gap-4 mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-sm font-bold text-primary-700">
                                        {{ strtoupper(substr($submission->volunteer->user->name ?? 'V', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $submission->volunteer->user->name ?? 'Volunteer' }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $submission->hours_worked ?? 0 }}h
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $submission->submitted_at?->diffForHumans() ?? 'Recently' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Description Preview --}}
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
                                {{ Str::limit(strip_tags($submission->description), 180) }}
                            </p>

                            {{-- Tags --}}
                            <div class="flex flex-wrap items-center gap-2">
                                @if($submission->deliverable_url)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    {{ __('Has Deliverable') }}
                                </span>
                                @endif

                                @if($submission->ai_feedback)
                                @php $aiFeedback = is_string($submission->ai_feedback) ? json_decode($submission->ai_feedback, true) : $submission->ai_feedback; @endphp
                                @if($aiFeedback && isset($aiFeedback['strengths']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-violet-50 text-violet-700 rounded-lg text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    {{ count($aiFeedback['strengths']) }} {{ __('strengths') }}
                                </span>
                                @endif
                                @endif

                                @if($submission->reviewed_at)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Reviewed') }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-medium">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                    {{ __('Awaiting review') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Right Side: AI Score & Action --}}
                        <div class="flex lg:flex-col items-center lg:items-end gap-4 lg:min-w-[140px]">
                            {{-- AI Score --}}
                            @if($submission->ai_quality_score)
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <svg class="w-14 h-14 -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-gray-100" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path class="{{ $submission->ai_quality_score >= 80 ? 'text-emerald-500' : ($submission->ai_quality_score >= 60 ? 'text-amber-500' : 'text-red-500') }}" 
                                            stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none" 
                                            stroke-dasharray="{{ $submission->ai_quality_score }}, 100" 
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold {{ $submission->ai_quality_score >= 80 ? 'text-emerald-600' : ($submission->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                            {{ $submission->ai_quality_score }}
                                        </span>
                                    </div>
                                </div>
                                <div class="lg:hidden">
                                    <p class="text-xs text-gray-400">{{ __('AI Score') }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center gap-3 lg:flex-col">
                                <div class="w-14 h-14 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-400 lg:hidden">{{ __('Pending') }}</p>
                            </div>
                            @endif

                            {{-- Review Button --}}
                            <a href="{{ route('company.submissions.show', $submission->id) }}" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('Review') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Submissions Yet') }}</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ __('Work submissions from volunteers will appear here once they submit their deliverables for your challenges.') }}</p>
                <a href="{{ route('challenges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('View Your Challenges') }}
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($submissions->hasPages())
        <div class="mt-6">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function submissionsPage() {
    return {
        activeFilter: 'all'
    }
}
</script>
@endpush
@endsection
