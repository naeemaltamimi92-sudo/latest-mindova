@extends('layouts.app')

@section('title', __('Login'))

@php
    $isRTL = app()->getLocale() === 'ar';
    $dir = $isRTL ? 'rtl' : 'ltr';
    $textAlign = $isRTL ? 'text-right' : 'text-left';
    $flexDir = $isRTL ? 'flex-row-reverse' : 'flex-row';
@endphp

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-premium-dark min-h-full relative overflow-hidden">
        <div class="absolute inset-0 interactive-map-container">
            <div class="absolute top-1/4 left-1/3 w-2 h-2 rounded-full bg-primary-400/60 animate-glow"></div>
            <div class="absolute top-2/3 left-1/2 w-1.5 h-1.5 rounded-full bg-primary-300/50 animate-glow" style="animation-delay: 0.8s;"></div>
            <div class="absolute top-1/2 left-1/4 w-1.5 h-1.5 rounded-full bg-primary-300/40 animate-glow" style="animation-delay: 1.6s;"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-display mb-4 {{ $textAlign }}">
                    {{ __('Welcome Back to') }}<br/>
                    <span class="text-gradient-aurora">{{ __('Your Innovation Hub') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed max-w-xl {{ $textAlign }}">
                    {{ __('Continue your journey of solving challenges and making an impact with talented teams worldwide.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-5 mt-8">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="trending-up" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Track Your Progress') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Monitor tasks, achievements, and team collaboration in real-time') }}</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="zap" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Get personalized task recommendations and optimal team matches') }}</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="sparkles" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Build Your Reputation') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Earn rewards and recognition for quality contributions') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-md w-full" dir="{{ $dir }}">
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
                        {{ __('Welcome') }} <span class="text-primary-600">{{ __('Back') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Sign in to continue your innovation journey') }}</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf

                    <x-ui.input
                        name="email"
                        type="email"
                        icon="mail"
                        :label="__('Email Address')"
                        :placeholder="__('Enter your email address')"
                        required
                        autofocus
                        forceLtr
                    />

                    <x-ui.password-input
                        name="password"
                        :label="__('Password')"
                        :placeholder="__('Enter your password')"
                        required
                    />

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between text-sm {{ $flexDir }}">
                        <div class="flex items-center gap-2 {{ $flexDir }}">
                            <input id="remember" type="checkbox" name="remember"
                                   class="h-4 w-4 text-primary-500 focus:ring-primary-400 border-gray-300 dark:border-gray-600 rounded cursor-pointer">
                            <label for="remember" class="cursor-pointer text-gray-700 dark:text-gray-300">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-700">
                            {{ __('Forgot password?') }}
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Sign In') }}
                        <x-icon name="arrow-right" class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" />
                    </x-ui.button>

                    <x-ui.divider :label="__('Or continue with')" />

                    <!-- LinkedIn Button -->
                    <x-ui.social-button provider="linkedin" :href="route('auth.linkedin.redirect')">
                        {{ __('Continue with LinkedIn') }}
                    </x-ui.social-button>

                    <!-- Register Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                                {{ __('Sign up for free') }}
                            </a>
                        </p>
                    </div>

                    <div class="text-center text-xs text-gray-400 dark:text-gray-500">
                        <p>{{ __('By continuing, you agree to our') }} <a href="{{ route('terms') }}" class="hover:underline text-primary-600">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="hover:underline text-primary-600">{{ __('Privacy Policy') }}</a></p>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">1000+</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Contributors') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">500+</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Challenges') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">2000+</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Tasks Done') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
