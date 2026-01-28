@extends('layouts.app')

@section('title', __('Review Submission'))

@push('styles')
<style>
    .line-clamp-5 {
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
@php
    $statusConfig = [
        'submitted' => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'text' => 'text-amber-700',
            'iconBg' => 'bg-amber-100',
            'label' => __('Pending Review'),
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        ],
        'under_review' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-700',
            'iconBg' => 'bg-blue-100',
            'label' => __('Under Review'),
            'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        ],
        'approved' => [
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-200',
            'text' => 'text-emerald-700',
            'iconBg' => 'bg-emerald-100',
            'label' => __('Approved'),
            'icon' => 'M5 13l4 4L19 7'
        ],
        'revision_requested' => [
            'bg' => 'bg-orange-50',
            'border' => 'border-orange-200',
            'text' => 'text-orange-700',
            'iconBg' => 'bg-orange-100',
            'label' => __('Revision Requested'),
            'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
        ],
        'rejected' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-700',
            'iconBg' => 'bg-red-100',
            'label' => __('Rejected'),
            'icon' => 'M6 18L18 6M6 6l12 12'
        ],
    ];
    $config = $statusConfig[$submission->status] ?? $statusConfig['submitted'];
@endphp

<div class="min-h-screen bg-gray-50" x-data="reviewPage()">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Back Navigation --}}
        <a href="{{ route('company.submissions.index') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">{{ __('Back to Submissions') }}</span>
        </a>

        {{-- Header Card --}}
        <div class="bg-white rounded-xl border border-gray-200 mb-6">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    {{-- Left: Title & Status --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                                {{ $config['label'] }}
                            </span>
                            @if($submission->ai_quality_score)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-violet-50 text-violet-700 border border-violet-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                AI {{ $submission->ai_quality_score }}%
                            </span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $submission->task->title ?? 'Task Submission' }}
                        </h1>
                        <p class="text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $submission->task->challenge->title ?? 'Challenge' }}
                        </p>
                    </div>

                    {{-- Right: Meta Info --}}
                    <div class="flex items-center gap-6 text-sm">
                        <div class="text-right">
                            <p class="text-gray-500 mb-0.5">{{ __('Submitted') }}</p>
                            <p class="font-semibold text-gray-900">
                                {{ $submission->submitted_at?->format('M d, Y') ?? 'N/A' }}
                            </p>
                            <p class="text-gray-400 text-xs">
                                {{ $submission->submitted_at?->format('h:i A') ?? '' }}
                            </p>
                        </div>
                        <div class="w-px h-10 bg-gray-200"></div>
                        <div class="text-right">
                            <p class="text-gray-500 mb-0.5">{{ __('Hours') }}</p>
                            <p class="font-semibold text-gray-900">{{ $submission->hours_worked ?? 0 }}h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Submission Description --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ expanded: false }">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Submission Description') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <div x-ref="description" 
                                 :class="expanded ? '' : 'line-clamp-5'" 
                                 class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $submission->description }}</div>
                        </div>
                        @if(strlen($submission->description ?? '') > 400)
                        <button @click="expanded = !expanded"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                            <span x-text="expanded ? '{{ __('Show less') }}' : '{{ __('Read more') }}'"></span>
                            <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Deliverable --}}
                @if($submission->deliverable_url)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            {{ __('Deliverable') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <a href="{{ $submission->deliverable_url }}" target="_blank" rel="noopener noreferrer"
                            class="group flex items-center gap-4 p-4 bg-gray-50 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50/30 transition-all">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 group-hover:text-primary-700 transition-colors">
                                    {{ __('View Deliverable') }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ $submission->deliverable_url }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                {{-- AI Analysis --}}
                @if($submission->ai_feedback)
                @php $aiFeedback = is_string($submission->ai_feedback) ? json_decode($submission->ai_feedback, true) : $submission->ai_feedback; @endphp
                @if($aiFeedback)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-violet-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            {{ __('AI Quality Analysis') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        @if(isset($aiFeedback['feedback']))
                        <p class="text-gray-700 mb-6 leading-relaxed">{{ $aiFeedback['feedback'] }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if(isset($aiFeedback['strengths']) && count($aiFeedback['strengths']) > 0)
                            <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                                <h3 class="font-medium text-emerald-800 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Strengths') }}
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($aiFeedback['strengths'] as $strength)
                                    <li class="flex items-start gap-2 text-sm text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></span>
                                        <span>{{ $strength }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if(isset($aiFeedback['areas_for_improvement']) && count($aiFeedback['areas_for_improvement']) > 0)
                            <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                                <h3 class="font-medium text-amber-800 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ __('Areas for Improvement') }}
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($aiFeedback['areas_for_improvement'] as $area)
                                    <li class="flex items-start gap-2 text-sm text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                                        <span>{{ $area }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif

                {{-- Review History --}}
                @if($submission->reviews && $submission->reviews->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Review History') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($submission->reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($review->decision === 'approved') bg-emerald-100 text-emerald-700
                                        @elseif($review->decision === 'revision_requested') bg-orange-100 text-orange-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        @if($review->decision === 'approved')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @elseif($review->decision === 'revision_requested')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        @endif
                                        {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ $review->feedback }}</p>
                                
                                @if($review->quality_score || $review->timeliness_score || $review->communication_score)
                                <div class="flex flex-wrap gap-4 pt-3 border-t border-gray-100">
                                    @if($review->quality_score)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-500">{{ __('Quality') }}:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $review->quality_score }}%</span>
                                    </div>
                                    @endif
                                    @if($review->timeliness_score)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-500">{{ __('Timeliness') }}:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $review->timeliness_score }}%</span>
                                    </div>
                                    @endif
                                    @if($review->communication_score)
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-gray-500">{{ __('Communication') }}:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $review->communication_score }}%</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Review Form --}}
                @if(in_array($submission->status, ['submitted', 'under_review', 'revision_requested']))
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ __('Submit Your Review') }}
                        </h2>
                    </div>
                    <form action="{{ route('company.submissions.review', $submission->id) }}" method="POST" class="p-6">
                        @csrf
                        <div class="space-y-6">

                            {{-- Decision Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('Your Decision') }}</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="approved" class="peer sr-only" required x-model="decision">
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 hover:bg-emerald-50/50 transition-all text-center">
                                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-emerald-500 peer-checked:text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900 text-sm">{{ __('Approve') }}</p>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="revision_requested" class="peer sr-only" x-model="decision">
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300 hover:bg-orange-50/50 transition-all text-center">
                                            <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-orange-500 peer-checked:text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900 text-sm">{{ __('Request Revision') }}</p>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="decision" value="rejected" class="peer sr-only" x-model="decision">
                                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300 hover:bg-red-50/50 transition-all text-center">
                                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-2 peer-checked:bg-red-500 peer-checked:text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <p class="font-medium text-gray-900 text-sm">{{ __('Reject') }}</p>
                                        </div>
                                    </label>
                                </div>
                                @error('decision')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Score Sliders --}}
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h3 class="font-medium text-gray-900 mb-1">{{ __('Rate the Submission') }}</h3>
                                <p class="text-sm text-gray-500 mb-4" x-show="decision === 'approved'">
                                    <span class="text-red-500">*</span> {{ __('Quality score is required when approving.') }}
                                </p>
                                <p class="text-sm text-gray-500 mb-4" x-show="decision !== 'approved'">
                                    {{ __('Optional ratings for the submission.') }}
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm text-gray-600">
                                                {{ __('Quality') }}
                                                <span x-show="decision === 'approved'" class="text-red-500">*</span>
                                            </label>
                                            <span class="text-sm font-semibold text-gray-900" x-text="qualityScore > 0 ? qualityScore + '%' : '-'"></span>
                                        </div>
                                        <input type="range" name="quality_score" min="0" max="100" value="0"
                                            x-model="qualityScore"
                                            :required="decision === 'approved'"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm text-gray-600">{{ __('Timeliness') }}</label>
                                            <span class="text-sm font-semibold text-gray-900" x-text="timelinessScore > 0 ? timelinessScore + '%' : '-'"></span>
                                        </div>
                                        <input type="range" name="timeliness_score" min="0" max="100" value="0"
                                            x-model="timelinessScore"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-sm text-gray-600">{{ __('Communication') }}</label>
                                            <span class="text-sm font-semibold text-gray-900" x-text="communicationScore > 0 ? communicationScore + '%' : '-'"></span>
                                        </div>
                                        <input type="range" name="communication_score" min="0" max="100" value="0"
                                            x-model="communicationScore"
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600">
                                    </div>
                                </div>
                            </div>

                            {{-- Feedback --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Your Feedback') }}</label>
                                <textarea name="feedback" rows="4" required minlength="10"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none text-gray-700"
                                    placeholder="{{ __('Provide detailed, constructive feedback about the submission...') }}"></textarea>
                                @error('feedback')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <a href="{{ route('company.submissions.index') }}" 
                                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" 
                                    class="px-6 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-500/20 transition-all inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('Submit Review') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Contributor Profile --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 bg-primary-600">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center text-lg font-bold text-white">
                                {{ strtoupper(substr($submission->volunteer->user->name ?? 'V', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-white truncate">{{ $submission->volunteer->user->name ?? 'Volunteer' }}</h3>
                                <p class="text-white/80 text-sm truncate">{{ $submission->volunteer->field ?? 'Expert' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Experience') }}</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $submission->volunteer->experience_level ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Years') }}</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $submission->volunteer->years_of_experience ?? 0 }} {{ __('years') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Reputation') }}</dt>
                                <dd class="text-sm font-medium text-emerald-600">{{ $submission->volunteer->reputation_score ?? 50 }}</dd>
                            </div>
                        </dl>

                        @if($submission->volunteer->skills && $submission->volunteer->skills->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2 font-medium uppercase tracking-wide">{{ __('Skills') }}</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($submission->volunteer->skills->take(8) as $skill)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">{{ $skill->skill_name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Task Details --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ __('Task Details') }}
                        </h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Estimated Hours') }}</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $submission->task->estimated_hours ?? 0 }}h</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Actual Hours') }}</dt>
                                <dd class="text-sm font-medium {{ ($submission->hours_worked ?? 0) <= ($submission->task->estimated_hours ?? 0) ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $submission->hours_worked ?? 0 }}h
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Complexity') }}</dt>
                                <dd class="flex items-center gap-2">
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                        <div class="w-1.5 h-3 rounded-sm {{ $i <= (($submission->task->complexity_score ?? 0) / 2) ? 'bg-primary-500' : 'bg-gray-200' }}"></div>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $submission->task->complexity_score ?? 'N/A' }}</span>
                                </dd>
                            </div>
                            @if($submission->task->workstream)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">{{ __('Workstream') }}</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $submission->task->workstream->title }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- AI Score --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            {{ __('AI Analysis') }}
                        </h3>
                    </div>
                    <div class="p-5">
                        @if($submission->ai_quality_score)
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-100" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="{{ $submission->ai_quality_score >= 80 ? 'text-emerald-500' : ($submission->ai_quality_score >= 60 ? 'text-amber-500' : 'text-red-500') }}" 
                                        stroke="currentColor" stroke-width="3" stroke-linecap="round" fill="none" 
                                        stroke-dasharray="{{ $submission->ai_quality_score }}, 100" 
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-lg font-bold {{ $submission->ai_quality_score >= 80 ? 'text-emerald-600' : ($submission->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ $submission->ai_quality_score }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($submission->ai_quality_score >= 80)
                                    {{ __('Excellent Quality') }}
                                    @elseif($submission->ai_quality_score >= 60)
                                    {{ __('Good Quality') }}
                                    @else
                                    {{ __('Needs Improvement') }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ __('AI evaluated score') }}</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-3 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span class="text-sm">{{ __('AI analysis pending') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-primary-600 rounded-xl p-5 text-white">
                    <h3 class="font-semibold mb-4">{{ __('Quick Stats') }}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/10 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">{{ $submission->reviews->count() }}</div>
                            <div class="text-xs text-white/80">{{ __('Reviews') }}</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold">#{{ $submission->id }}</div>
                            <div class="text-xs text-white/80">{{ __('ID') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reviewPage() {
    return {
        decision: '',
        qualityScore: 0,
        timelinessScore: 0,
        communicationScore: 0
    }
}
</script>
@endpush
@endsection
