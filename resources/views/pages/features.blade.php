@extends('layouts.app')

@section('title', __('Features'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Platform Features') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Everything You Need to Turn Challenges Into') }} <span class="text-primary-600">{{ __('Results') }}</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('From AI-powered analysis to team formation and certification, Mindova handles the full lifecycle of a challenge.') }}
            </p>
        </div>
    </div>
</div>

<!-- AI Pipeline -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-violet-50 border border-violet-200 rounded-full px-4 py-1.5 mb-4">
                <span class="text-sm font-medium text-violet-700">{{ __('AI-Powered Pipeline') }}</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                {{ __('From Brief to') }} <span class="text-primary-600">{{ __('Delivered Solution') }}</span>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                {{ __('Every challenge is automatically analyzed, broken down, and routed to the right people.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Challenge Brief Analysis') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('AI refines a raw problem statement into a clear, actionable brief and assigns a complexity score.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Automatic Task Decomposition') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Challenges are split into workstreams and tasks with estimated hours and required skills.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-400 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Volunteer Matching') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Contributors are matched to tasks based on skills, experience level, and availability.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-300 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Team Formation') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('AI groups matched volunteers into balanced "micro companies" with a designated leader.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Solution Quality Scoring') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Submitted work is scored against the original brief, with feedback before final approval.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Aggregated Reporting') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Companies get a single rolled-up view of every workstream once a challenge is complete.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Platform-wide features -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                {{ __('Built for') }} <span class="text-primary-600">{{ __('Both Sides') }}</span> {{ __('of the Marketplace') }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('For Contributors') }}</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('CV-based skill extraction so your profile builds itself') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Reputation score and leaderboard ranking for quality contributions') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Verifiable certificates on completed challenges') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Community discussions and idea submissions, even before joining a team') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('WhatsApp notifications for invitations and task updates') }}</li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('For Companies') }}</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Post a challenge in two fields — AI handles the rest') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('NDA gating for confidential briefs and attachments') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Per-challenge analytics: task status, hours, contributor breakdown') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Review, approve, or request revisions on every submission') }}</li>
                    <li class="flex items-start gap-2"><svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>{{ __('Issue branded completion certificates to contributors') }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-primary-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('See It in Action') }}</h3>
        <p class="text-lg text-white/90 mb-8">{{ __('Create a free account and post your first challenge in minutes.') }}</p>
        <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
            {{ __('Get Started') }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </x-ui.button>
    </div>
</section>
@endsection
