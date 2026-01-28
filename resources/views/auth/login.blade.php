@extends('layouts.app')

@section('title', __('Login'))

@php
    $isRTL = app()->getLocale() === 'ar';
    $dir = $isRTL ? 'rtl' : 'ltr';
    $textAlign = $isRTL ? 'text-right' : 'text-left';
    $flexDir = $isRTL ? 'flex-row-reverse' : 'flex-row';
    $ml = $isRTL ? 'mr' : 'ml';
    $mr = $isRTL ? 'ml' : 'mr';
    $pl = $isRTL ? 'pr' : 'pl';
    $pr = $isRTL ? 'pl' : 'pr';
    $left = $isRTL ? 'right' : 'left';
    $right = $isRTL ? 'left' : 'right';
@endphp

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 min-h-full">
        <div class="flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 {{ $textAlign }}">
                    {{ __('Welcome Back to') }}<br/>
                    <span class="text-white">{{ __('Your Innovation Hub') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed max-w-xl {{ $textAlign }}">
                    {{ __('Continue your journey of solving challenges and making an impact with talented teams worldwide.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-5 mt-8">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Track Your Progress') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Monitor tasks, achievements, and team collaboration in real-time') }}</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Get personalized task recommendations and optimal team matches') }}</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
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
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-12 bg-gray-50">
        <div class="max-w-md w-full" dir="{{ $dir }}">
            <div class="bg-white border border-gray-200 rounded-xl px-6 py-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-5">
                        <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                            <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold">M</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">Mindova</span>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ __('Welcome') }} <span class="text-primary-600">{{ __('Back') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600">{{ __('Sign in to continue your innovation journey') }}</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 {{ $textAlign }}">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }} @error('email') border-red-400 @enderror"
                                   placeholder="{{ __('Enter your email address') }}"
                                   dir="ltr">
                        </div>
                        @error('email')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1 {{ $flexDir }}">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5 {{ $textAlign }}">{{ __('Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }} @error('password') border-red-400 @enderror"
                                   placeholder="{{ __('Enter your password') }}"
                                   dir="ltr">
                        </div>
                        @error('password')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1 {{ $flexDir }}">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between text-sm {{ $flexDir }}">
                        <div class="flex items-center gap-2 {{ $flexDir }}">
                            <input id="remember" type="checkbox" name="remember"
                                   class="h-4 w-4 text-primary-500 focus:ring-primary-400 border-gray-300 rounded cursor-pointer">
                            <label for="remember" class="text-gray-600 cursor-pointer">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                            {{ __('Forgot password?') }}
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Sign In') }}
                        <svg class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </x-ui.button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-white text-gray-500 text-xs">{{ __('Or continue with') }}</span>
                        </div>
                    </div>

                    <!-- LinkedIn Button -->
                    <x-ui.button as="a" href="{{ route('auth.linkedin.redirect') }}" variant="outline" size="sm" fullWidth>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" style="fill: #0077B5;">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        <span>{{ __('Continue with LinkedIn') }}</span>
                    </x-ui.button>

                    <!-- Register Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                                {{ __('Sign up for free') }}
                            </a>
                        </p>
                    </div>

                    <div class="text-center text-xs text-gray-500">
                        <p>{{ __('By continuing, you agree to our') }} <a href="{{ route('terms') }}" class="text-primary-600 hover:underline">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-primary-600 hover:underline">{{ __('Privacy Policy') }}</a></p>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-6">
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center p-3 rounded-lg bg-white border border-gray-200">
                        <div class="text-xl font-bold text-primary-600 mb-0.5">1000+</div>
                        <div class="text-xs text-gray-600">{{ __('Contributors') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-lg bg-white border border-gray-200">
                        <div class="text-xl font-bold text-primary-600 mb-0.5">500+</div>
                        <div class="text-xs text-gray-600">{{ __('Challenges') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-lg bg-white border border-gray-200">
                        <div class="text-xl font-bold text-primary-600 mb-0.5">2000+</div>
                        <div class="text-xs text-gray-600">{{ __('Tasks Done') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
