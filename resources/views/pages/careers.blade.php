@extends('layouts.app')

@section('title', __('Careers'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 dark:bg-gray-900 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Careers') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('Help Us Build the Future of') }} <span class="text-primary-600 dark:text-primary-400">{{ __('Collaborative Work') }}</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl mx-auto">
                {{ __("We're a small team obsessed with matching the right people to the right problems. If that excites you, we'd like to hear from you.") }}
            </p>
        </div>
    </div>
</div>

<!-- Values -->
<section class="py-16 bg-white dark:bg-gray-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">{{ __('How We Work') }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Ship Real Things') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('We move fast, learn from real usage, and would rather fix something live than perfect it in theory.') }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Remote-First') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Our own product connects people across borders — so does our team. We hire wherever the right person is.') }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-400 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Impact Over Hierarchy') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __("Good ideas win regardless of title — the same principle we built the platform's community discussions on.") }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Open roles -->
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Open Positions') }}</h2>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-10">
            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('No open roles right now') }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">{{ __("We're not actively hiring at the moment, but we're always glad to hear from people who'd be a great fit for where we're headed.") }}</p>
            <x-ui.button as="a" href="{{ route('contact') }}" variant="primary">{{ __('Introduce Yourself') }}</x-ui.button>
        </div>
    </div>
</section>
@endsection
