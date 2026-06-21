@extends('layouts.app')

@section('title', __('Security'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Security') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Confidential Challenges,') }} <span class="text-primary-600">{{ __('Protected by Design') }}</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('Companies often share sensitive problem statements. Mindova is built to keep that information access-controlled at every step.') }}
            </p>
        </div>
    </div>
</div>

<!-- Pillars -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('NDA-Gated Access') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Companies can require contributors to sign a non-disclosure agreement before viewing a challenge brief, attachments, or task details. Signing status is tracked per challenge and enforced server-side, not just hidden in the UI.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3zm0 0c0 1.657 1.343 3 3 3s3-1.343 3-3-1.343-3-3-3-3 1.343-3 3zM12 11V7m0 4v4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Two-Factor Authentication') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Any account can enable TOTP-based 2FA. Secrets and recovery codes are stored encrypted, never in plain text, and recovery codes are single-use.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-400 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Role-Based Access Control') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Volunteers, companies, and internal team members each see only what their role permits — enforced by middleware on every request, not just hidden navigation links.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-300 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h3.5a2.5 2.5 0 010 5H13M9 17h6m-6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 104 0 2 2 0 00-4 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Token-Based API Auth') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('API access uses Laravel Sanctum personal access tokens scoped to the authenticated user, with the ability to revoke tokens at any time from account settings.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Private File Storage') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Challenge attachments are stored on access-controlled storage and served only through an authorized download endpoint — never via a public, guessable URL.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Audit Logging') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Sensitive administrative actions inside the internal Mindova team console are logged for accountability and review.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Responsible disclosure -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ __('Found a Security Issue?') }}</h2>
        <p class="text-gray-600 mb-8">
            {{ __("We take security reports seriously. If you've found a vulnerability, please report it privately so we can investigate and fix it before any public disclosure.") }}
        </p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">
            {{ __('Report a Vulnerability') }}
        </x-ui.button>
    </div>
</section>
@endsection
