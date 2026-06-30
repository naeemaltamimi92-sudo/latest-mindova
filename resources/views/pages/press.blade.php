@extends('layouts.app')

@section('title', __('Press'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 dark:bg-gray-900 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Press') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('Mindova') }} <span class="text-primary-600 dark:text-primary-400">{{ __('Press Kit') }}</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl mx-auto">
                {{ __('Resources for journalists and writers covering Mindova. For anything not covered here, reach out directly.') }}
            </p>
        </div>
    </div>
</div>

<!-- Boilerplate + assets -->
<section class="py-16 bg-white dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Boilerplate') }}</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                {{ __('Mindova is an AI-powered collaboration platform that connects skilled volunteers with companies facing real-world challenges. The platform analyzes each challenge, breaks it into tasks, forms balanced contributor teams, and scores submitted work — turning open problems into delivered solutions.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Brand Assets') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Logos and wordmarks are available on request while we finalize a public downloadable kit. Please use the logo unmodified and avoid recoloring it.') }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Media Inquiries') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('For interviews, data requests, or commentary, contact us and a team member will follow up directly.') }}</p>
            </div>
        </div>

        <div class="text-center pt-4">
            <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">{{ __('Contact the Mindova Team') }}</x-ui.button>
        </div>
    </div>
</section>
@endsection
