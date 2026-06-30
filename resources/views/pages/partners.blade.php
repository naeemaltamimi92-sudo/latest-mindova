@extends('layouts.app')

@section('title', __('Partners'))

@section('content')
<!-- Hero -->
<div class="relative overflow-hidden pt-24 pb-20">
    <div class="absolute inset-0 bg-aurora-soft opacity-40 pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white/[0.06] border border-white/10 rounded-full px-4 py-1.5 mb-6 backdrop-blur-sm">
                <div class="w-2 h-2 bg-primary-400 rounded-full"></div>
                <span class="text-sm font-medium text-gray-300">{{ __('Partners') }}</span>
            </div>

            <h1 class="text-display text-gray-900 dark:text-white mb-5">
                {{ __('Grow the Network with') }} <span class="text-gradient-aurora">{{ __('Mindova') }}</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-white/60 leading-relaxed max-w-2xl mx-auto">
                {{ __('We partner with universities, nonprofits, and technology providers who share our goal of connecting talent with meaningful work.') }}
            </p>
        </div>
    </div>
</div>

<!-- Partner tracks -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 transition-premium hover:elevation-sm dark:hover:bg-white/[0.05]">
                <div class="w-12 h-12 bg-aurora rounded-lg flex items-center justify-center mb-4 glow-primary-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Academic Partners') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('Universities and bootcamps can offer Mindova challenges to students as real-world, portfolio-building experience alongside coursework.') }}</p>
            </div>

            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 transition-premium hover:elevation-sm dark:hover:bg-white/[0.05]">
                <div class="w-12 h-12 bg-aurora rounded-lg flex items-center justify-center mb-4 glow-primary-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Nonprofit & NGO Partners') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('Mission-driven organizations can post challenges to our contributor community at reduced or no cost — reach out to discuss eligibility.') }}</p>
            </div>

            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6 transition-premium hover:elevation-sm dark:hover:bg-white/[0.05]">
                <div class="w-12 h-12 bg-aurora rounded-lg flex items-center justify-center mb-4 glow-primary-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Technology Partners') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('Build on top of Mindova or integrate your tool into our workflow — we work with a small set of technology partners on a case-by-case basis.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-gray-50 dark:bg-transparent">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Interested in Partnering?') }}</h2>
        <p class="text-gray-600 dark:text-white/60 mb-8">
            {{ __('Tell us a bit about your organization and what you have in mind, and we’ll follow up to discuss fit.') }}
        </p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">{{ __('Start a Conversation') }}</x-ui.button>
    </div>
</section>
@endsection
