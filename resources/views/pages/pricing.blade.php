@extends('layouts.app')

@section('title', __('Pricing'))

@section('content')
<!-- Hero -->
<div class="relative overflow-hidden pt-24 pb-20">
    <div class="absolute inset-0 bg-aurora-soft opacity-40 pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white/[0.06] border border-white/10 rounded-full px-4 py-1.5 mb-6 backdrop-blur-sm">
                <div class="w-2 h-2 bg-primary-400 rounded-full"></div>
                <span class="text-sm font-medium text-gray-300">{{ __('Pricing') }}</span>
            </div>

            <h1 class="text-display text-gray-900 dark:text-white mb-5">
                {{ __('Free for Contributors.') }} <span class="text-gradient-aurora">{{ __('Simple for Companies.') }}</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-white/60 leading-relaxed max-w-2xl mx-auto">
                {{ __('Volunteers never pay to join, contribute, or get certified. Companies choose a plan that matches how many challenges they run.') }}
            </p>
        </div>
    </div>
</div>

<!-- Plans -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">

            <!-- Starter -->
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-2xl p-8 flex flex-col transition-premium dark:hover:bg-white/[0.05]">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Starter') }}</h3>
                <p class="text-sm text-gray-500 dark:text-white/50 mb-6">{{ __('For companies running their first few challenges') }}</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">$0</span>
                    <span class="text-gray-500 dark:text-white/50 text-sm">/{{ __('month') }}</span>
                </div>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-white/60 mb-8 flex-1">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Up to 2 active challenges') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('AI brief analysis & task decomposition') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('AI volunteer matching & team formation') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Standard email support') }}</li>
                </ul>
                <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" fullWidth>{{ __('Start Free') }}</x-ui.button>
            </div>

            <!-- Growth -->
            <div class="bg-white dark:bg-white/[0.04] border-2 border-primary-500 rounded-2xl p-8 flex flex-col relative glow-primary-sm">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-aurora text-white text-xs font-semibold px-3 py-1 rounded-full">{{ __('Most Popular') }}</div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Growth') }}</h3>
                <p class="text-sm text-gray-500 dark:text-white/50 mb-6">{{ __('For companies running challenges continuously') }}</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ __('Custom') }}</span>
                </div>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-white/60 mb-8 flex-1">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Unlimited active challenges') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('NDA-gated challenges & attachments') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Full analytics dashboard per challenge') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Priority matching queue') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Priority support') }}</li>
                </ul>
                <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" fullWidth>{{ __('Talk to Sales') }}</x-ui.button>
            </div>

            <!-- Enterprise -->
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-2xl p-8 flex flex-col transition-premium dark:hover:bg-white/[0.05]">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Enterprise') }}</h3>
                <p class="text-sm text-gray-500 dark:text-white/50 mb-6">{{ __('For large organizations with custom requirements') }}</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ __('Custom') }}</span>
                </div>
                <ul class="space-y-3 text-sm text-gray-600 dark:text-white/60 mb-8 flex-1">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Everything in Growth') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Dedicated onboarding & account manager') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('Custom security & compliance review') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('API access for internal tooling') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-5 h-5 text-primary-500 dark:text-primary-400 flex-shrink-0 mt-0.5" />{{ __('SLA-backed support') }}</li>
                </ul>
                <x-ui.button as="a" href="{{ route('contact') }}" variant="secondary" fullWidth>{{ __('Contact Sales') }}</x-ui.button>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500 dark:text-white/50 mt-8">
            {{ __('Growth and Enterprise pricing depends on challenge volume and team size.') }}
            <a href="{{ route('contact') }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ __('Get in touch') }}</a>
            {{ __('for a quote.') }}
        </p>
    </div>
</section>

<!-- FAQ -->
<section class="py-16 bg-gray-50 dark:bg-transparent">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white text-center mb-10">{{ __('Pricing Questions') }}</h2>
        <div class="space-y-4">
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ __('Is Mindova really free for contributors?') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('Yes. Creating a profile, joining teams, submitting work, and earning certificates never costs a contributor anything.') }}</p>
            </div>
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ __('Can I switch plans later?') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('Yes. Start on Starter and move to Growth or Enterprise whenever your challenge volume grows — just contact us.') }}</p>
            </div>
            <div class="bg-white dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ __('Do you offer discounts for nonprofits or universities?') }}</h3>
                <p class="text-gray-600 dark:text-white/60 text-sm">{{ __('We do — reach out through the contact page and mention your organization type.') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
