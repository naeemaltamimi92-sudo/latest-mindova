@extends('layouts.app')

@section('title', __('Documentation'))

@section('content')
<!-- Hero -->
<div class="bg-aurora-soft pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700 dark:text-[#A8A8B8]">{{ __('Documentation') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-[#F2F2F5] mb-4">
                {{ __('Guides for') }} <span class="text-primary-600 dark:text-[#775FEE]">{{ __('Getting the Most Out of Mindova') }}</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-[#A8A8B8] leading-relaxed max-w-2xl mx-auto">
                {{ __('Whether you’re posting your first challenge or integrating with our API, start here.') }}
            </p>

            <div class="max-w-xl mx-auto mt-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-[#74748A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('help') }}" class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-left text-gray-400 dark:text-[#74748A] text-sm hover:border-primary-300 dark:hover:border-primary-400/50 transition-premium">
                        {{ __('Search the Help Center…') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Doc categories -->
<section class="py-16 bg-white dark:bg-transparent">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <a href="{{ route('how-it-works') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('Getting Started') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('How challenges, tasks, and teams work end-to-end on Mindova.') }}</p>
            </a>

            <a href="{{ route('guidelines') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('For Contributors') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('Building your profile, accepting tasks, submitting work, and community guidelines.') }}</p>
            </a>

            <a href="{{ route('how-it-works') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-primary-400 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('For Companies') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('Posting challenges, setting up NDAs, reviewing submissions, and reading analytics.') }}</p>
            </a>

            <a href="{{ route('api-docs') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-gray-900 dark:bg-[#26262F] rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('API Reference') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('Authentication, endpoints, error codes, and rate limits for developers.') }}</p>
            </a>

            <a href="{{ route('security') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-secondary-300 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('Security & Privacy') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('NDAs, two-factor authentication, and how challenge data is protected.') }}</p>
            </a>

            <a href="{{ route('help') }}" class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 hover:border-primary-300 dark:hover:border-primary-400/40 hover:shadow-sm dark:hover:elevation-md transition-premium block">
                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-[#F2F2F5] mb-2">{{ __('FAQs & Troubleshooting') }}</h3>
                <p class="text-gray-600 dark:text-[#A8A8B8] text-sm">{{ __('Common questions about accounts, notifications, and AI scoring.') }}</p>
            </a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-gray-50 dark:bg-white/[0.02]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-[#F2F2F5] mb-4">{{ __("Can't Find What You Need?") }}</h2>
        <p class="text-gray-600 dark:text-[#A8A8B8] mb-8">{{ __('Our team is happy to help directly.') }}</p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">{{ __('Contact Support') }}</x-ui.button>
    </div>
</section>
@endsection
