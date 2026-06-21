@extends('layouts.app')

@section('title', __('Integrations'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Integrations') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Mindova Connects to the') }} <span class="text-primary-600">{{ __('Tools You Already Use') }}</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('Sign in with the identity provider you trust, get notified where you already are, and let AI do the heavy lifting in the background.') }}
            </p>
        </div>
    </div>
</div>

<!-- Integrations grid -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#0077B5;">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="white">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('LinkedIn') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Sign in or register with your LinkedIn account — no separate password to remember, and your professional details can pre-fill your contributor profile.') }}</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#25D366;">
                    <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-1.732-.866-2.866-1.547-4.005-3.504-.302-.521.302-.484.864-1.61.095-.198.05-.371-.05-.52-.099-.149-.669-1.611-.916-2.206-.247-.595-.5-.514-.67-.524-.173-.01-.371-.012-.57-.012-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.05 3.13 4.97 4.263.694.298 1.236.477 1.658.61.696.222 1.33.19 1.83.115.558-.084 1.758-.72 2.006-1.414.247-.694.247-1.288.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12.004 2C6.486 2 2 6.487 2 12.004c0 1.94.55 3.75 1.503 5.29L2 22l4.85-1.475A9.953 9.953 0 0012.004 22C17.521 22 22 17.521 22 12.004 22 6.487 17.521 2 12.004 2zm0 18.123a8.1 8.1 0 01-4.13-1.13l-.297-.176-3.062.93.943-2.99-.193-.31a8.07 8.07 0 01-1.249-4.318c0-4.464 3.633-8.097 8.098-8.097 4.464 0 8.097 3.633 8.097 8.097 0 4.464-3.633 8.097-8.207 8.097z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('WhatsApp') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Opt in to receive task invitations, status updates, and submission notifications on WhatsApp via the official Cloud API — no extra app to install.') }}</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('Claude AI') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Every challenge analysis, task decomposition, volunteer match, and quality score is generated by Anthropic\'s Claude models — the engine behind the entire AI pipeline.') }}</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-start gap-4">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('SMS & Voice') }}</h3>
                    <p class="text-gray-600 text-sm">{{ __('Twilio powers fallback SMS delivery for time-sensitive notifications when a contributor isn\'t reachable on WhatsApp.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- More coming -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ __('Want to See a Specific Integration?') }}</h2>
        <p class="text-gray-600 mb-8">
            {{ __('Slack, Microsoft Teams, and calendar sync are on our roadmap. Tell us what would help your team most.') }}
        </p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">
            {{ __('Request an Integration') }}
        </x-ui.button>
    </div>
</section>
@endsection
