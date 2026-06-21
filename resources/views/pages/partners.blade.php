@extends('layouts.app')

@section('title', __('Partners'))

@section('content')
<!-- Hero -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 mb-6">
                <div class="w-2 h-2 bg-primary-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Partners') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Grow the Network with') }} <span class="text-primary-600">{{ __('Mindova') }}</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('We partner with universities, nonprofits, and technology providers who share our goal of connecting talent with meaningful work.') }}
            </p>
        </div>
    </div>
</div>

<!-- Partner tracks -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Academic Partners') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Universities and bootcamps can offer Mindova challenges to students as real-world, portfolio-building experience alongside coursework.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-secondary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Nonprofit & NGO Partners') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Mission-driven organizations can post challenges to our contributor community at reduced or no cost — reach out to discuss eligibility.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="w-12 h-12 bg-primary-400 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Technology Partners') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Build on top of Mindova or integrate your tool into our workflow — we work with a small set of technology partners on a case-by-case basis.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ __('Interested in Partnering?') }}</h2>
        <p class="text-gray-600 mb-8">
            {{ __('Tell us a bit about your organization and what you have in mind, and we’ll follow up to discuss fit.') }}
        </p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">{{ __('Start a Conversation') }}</x-ui.button>
    </div>
</section>
@endsection
