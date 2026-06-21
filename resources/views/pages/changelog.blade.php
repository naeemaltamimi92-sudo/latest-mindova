@extends('layouts.app')

@section('title', __('Changelog'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Changelog') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __("What's New in") }} <span class="text-primary-600">{{ __('Mindova') }}</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('A running log of platform updates, redesigns, and fixes.') }}
            </p>
        </div>
    </div>
</div>

<!-- Entries -->
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-10">

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jun 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 mb-2">{{ __('Improved') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Upgraded AI Models') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('All challenge analysis, matching, and scoring now run on newer Claude models for more accurate and consistent results. Added support for parsing Word documents in addition to PDFs.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mb-2">{{ __('Design') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('New Landing Page & Navigation') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Introduced an animated world-map hero, refreshed navbar, and a cleaner team detail page. Increased input field padding across login and registration for easier tapping on mobile.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mb-2">{{ __('New') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Unified Design System') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Rolled out a new shared component library and normalized the color palette platform-wide, replacing one-off custom CSS with consistent Tailwind utilities.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 mb-2">{{ __('Improved') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Smarter Complexity Scoring') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Challenge complexity evaluation moved to a clearer 1–4 scale, with updated AI prompts for more accurate, consistent classification.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mb-2">{{ __('New') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Student Experience Level') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Volunteers can now self-identify as students, skipping full AI CV analysis while still being discoverable by companies looking for early-career talent.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 mb-2">{{ __('Fixed') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Reliability Fixes') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Fixed an issue where malformed AI responses could fail to parse, and refined matching logic for community-submitted challenge solutions.') }}</p>
                </div>
            </div>

            <div class="flex gap-6">
                <div class="flex-shrink-0 w-28 text-sm text-gray-500 pt-1">{{ __('Jan 2026') }}</div>
                <div class="flex-1 border-l-2 border-primary-200 pl-6 pb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mb-2">{{ __('New') }}</span>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Mindova Launches') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Initial release: AI-powered challenge analysis, task decomposition, volunteer matching, team formation, and community discussions.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
