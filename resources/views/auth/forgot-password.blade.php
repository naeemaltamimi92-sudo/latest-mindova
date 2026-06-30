@extends('layouts.app')

@section('title', __('Forgot Password'))

@php
    $isRTL = app()->getLocale() === 'ar';
    $dir = $isRTL ? 'rtl' : 'ltr';
    $textAlign = $isRTL ? 'text-right' : 'text-left';
    $flexDir = $isRTL ? 'flex-row-reverse' : 'flex-row';
@endphp

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-premium-dark min-h-full relative overflow-hidden">
        <div class="absolute inset-0 interactive-map-container">
            <div class="absolute top-1/4 left-1/3 w-2 h-2 rounded-full bg-primary-400/60 animate-glow"></div>
            <div class="absolute top-2/3 left-1/2 w-1.5 h-1.5 rounded-full bg-primary-300/50 animate-glow" style="animation-delay: 0.8s;"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-display mb-4 {{ $textAlign }}">
                    {{ __('Password Recovery') }}<br/>
                    <span class="text-gradient-aurora">{{ __('Made Simple') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed {{ $textAlign }}">
                    {{ __("Don't worry! We'll send you reset instructions to get you back on track.") }}
                </p>

                <!-- Features List -->
                <div class="space-y-5">
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="lock" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Secure Process') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Industry-standard encryption for password resets') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="zap" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Quick Recovery') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Receive reset link instantly in your inbox') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="shield" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Data Protected') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Your information is always safe with us') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Reset Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-md w-full animate-fade-in" dir="{{ $dir }}">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl elevation-lg px-6 py-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-5">
                        <div class="inline-flex items-center gap-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2">
                            <x-brand.logo size="sm" href="{{ url('/') }}" />
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ __('Forgot') }} <span class="text-primary-600">{{ __('Password') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Enter your email to receive a password reset link') }}</p>
                </div>

                @if (session('status'))
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                        <div class="flex items-start gap-2">
                            <x-icon name="check-circle" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" />
                            <p class="text-sm font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <x-ui.input
                        name="email"
                        type="email"
                        icon="mail"
                        :label="__('Email Address')"
                        :placeholder="__('your.email@example.com')"
                        required
                        autofocus
                        forceLtr
                    />

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Send Reset Link') }}
                        <x-icon name="arrow-right" class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" />
                    </x-ui.button>

                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Remember your password?') }}
                            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                                {{ __('Back to Login') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
