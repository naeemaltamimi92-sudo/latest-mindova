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

@push('styles')
<style>
    /* Form Input Focus Effects */
    .input-2027:focus {
        outline: none;
        border-color: transparent;
        background: linear-gradient(white, white) padding-box,
                    var(--gradient-vibrant, linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7)) border-box;
    }

    /* Button Shimmer Effect */
    .btn-shimmer-2027::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }

    /* Trust Indicators Hover */
    .trust-card-2027 {
    }

    /* Feature Cards Enhanced */
    .feature-card-2027 {
    }

    /* Social Button Gradient Border */
    .social-btn-2027 {
        position: relative;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #e2e8f0, #cbd5e1) border-box;
        border: 2px solid transparent;
    }

    .social-btn-2027:hover {
        background: linear-gradient(white, white) padding-box,
                    var(--gradient-linkedin, linear-gradient(135deg, #0077B5, #00a0dc)) border-box;
    }
</style>
@endpush

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Premium Visual Section (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background: var(--gradient-vibrant, linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7));">
        <!-- Advanced Mesh Gradient Background - 2027 Enhanced -->
        <div class="absolute inset-0 opacity-25">
            <div class="floating-element absolute top-10 {{ $isRTL ? '-right-20' : '-left-20' }} w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
            <div class="floating-element absolute top-1/3 {{ $isRTL ? 'left-0' : 'right-0' }} w-[28rem] h-[28rem] bg-secondary-500 rounded-full blur-3xl"></div>
            <div class="floating-element absolute bottom-10 {{ $isRTL ? 'right-1/4' : 'left-1/4' }} w-80 h-80 bg-secondary-600 rounded-full blur-3xl"></div>
        </div>

        <!-- Decorative Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px] opacity-20"></div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 2xl:px-20 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black mb-6 leading-[1.1] {{ $textAlign }}">
                    {{ __('Welcome Back to') }}<br/>
                    <span class="text-white drop-shadow-lg" style="text-shadow: 0 2px 20px rgba(255,255,255,0.3);">{{ __('Your Innovation Hub') }}</span>
                </h1>
                <p class="text-lg lg:text-xl text-white mb-12 leading-relaxed font-medium max-w-xl {{ $textAlign }}" style="text-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    {{ __('Continue your journey of solving challenges and making an impact with talented teams worldwide.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-5">
                    <!-- Feature 1 -->
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30">
                        <div class="flex-shrink-0 w-14 h-14 bg-primary-400 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $textAlign }}">
                            <h3 class="text-lg font-black mb-2 text-white drop-shadow-sm">{{ __('Track Your Progress') }}</h3>
                            <p class="text-white text-sm leading-relaxed opacity-90">{{ __('Monitor tasks, achievements, and team collaboration in real-time with advanced analytics') }}</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30">
                        <div class="flex-shrink-0 w-14 h-14 bg-secondary-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $textAlign }}">
                            <h3 class="text-lg font-black mb-2 text-white drop-shadow-sm">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-white text-sm leading-relaxed opacity-90">{{ __('Get personalized task recommendations and optimal team matches powered by advanced AI') }}</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30">
                        <div class="flex-shrink-0 w-14 h-14 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 {{ $textAlign }}">
                            <h3 class="text-lg font-black mb-2 text-white drop-shadow-sm">{{ __('Build Your Reputation') }}</h3>
                            <p class="text-white text-sm leading-relaxed opacity-90">{{ __('Earn rewards, badges, and recognition for quality contributions to the community') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-12 bg-gray-50">
        <div class="max-w-lg w-full" dir="{{ $dir }}">
            <div class="relative group">
                <!-- Glow effect on hover -->
                <div class="absolute inset-0 bg-primary-300 rounded-3xl blur-2xl opacity-0 group-hover:opacity-15"></div>

                <div class="relative bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-xl rounded-3xl px-6 sm:px-8 md:px-10 lg:px-12 py-8 sm:py-10 md:py-12">
                    <!-- Header -->
                    <div class="text-center mb-8 sm:mb-10">
                        <!-- Mobile Logo -->
                        <div class="lg:hidden mb-6 sm:mb-8">
                            <div class="inline-flex items-center gap-3 bg-gray-50 border border-primary-200 rounded-2xl px-5 py-3 shadow-md">
                                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <span class="text-xl font-black text-secondary-500">Mindova</span>
                            </div>
                        </div>

                        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3 leading-tight">
                            {{ __('Welcome') }} <span class="text-secondary-500">{{ __('Back') }}</span>
                        </h2>
                        <p class="text-base text-gray-600 font-medium leading-relaxed">{{ __('Sign in to continue your innovation journey') }}</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.submit') }}" class="space-y-5 sm:space-y-6 md:space-y-7">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-slate-900 mb-3 {{ $textAlign }}">{{ __('Email Address') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-4 flex items-center pointer-events-none">
                                    <div class="w-5 h-5 rounded-lg bg-primary-500 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="w-full {{ $pl }}-12 {{ $pr }}-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 text-base text-slate-900 font-medium placeholder-slate-400 hover:border-slate-300 {{ $textAlign }} @error('email') border-rose-400 focus:border-rose-400 @enderror"
                                       placeholder="{{ __('Enter your email address') }}"
                                       dir="ltr">
                            </div>
                            @error('email')
                            <p class="text-red-600 text-sm mt-2 font-semibold flex items-center gap-1 {{ $flexDir }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-900 mb-3 {{ $textAlign }}">{{ __('Password') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-4 flex items-center pointer-events-none">
                                    <div class="w-5 h-5 rounded-lg bg-secondary-500 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input id="password" type="password" name="password" required
                                       class="w-full {{ $pl }}-12 {{ $pr }}-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:border-violet-400 focus:ring-4 focus:ring-violet-100 text-base text-slate-900 font-medium placeholder-slate-400 hover:border-slate-300 {{ $textAlign }} @error('password') border-rose-400 focus:border-rose-400 @enderror"
                                       placeholder="{{ __('Enter your password') }}"
                                       dir="ltr">
                            </div>
                            @error('password')
                            <p class="text-red-600 text-sm mt-2 font-semibold flex items-center gap-1 {{ $flexDir }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot Password -->
                        <div class="flex items-center justify-between text-xs sm:text-sm {{ $flexDir }}">
                            <div class="flex items-center gap-2 {{ $flexDir }}">
                                <input id="remember" type="checkbox" name="remember"
                                       class="h-4 w-4 sm:h-5 sm:w-5 text-indigo-500 focus:ring-indigo-400 border-slate-300 rounded cursor-pointer">
                                <label for="remember" class="block text-slate-700 font-medium cursor-pointer">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-700 font-bold hover:underline">
                                {{ __('Forgot password?') }}
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <x-ui.button as="submit" variant="primary" size="lg" fullWidth class="btn-shimmer-2027 relative overflow-hidden rounded-2xl" style="background: var(--gradient-vibrant, linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7));">
                                <span class="relative z-10">{{ __('Sign In') }}</span>
                                <svg class="relative z-10 w-5 h-5 {{ $isRTL ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </x-ui.button>
                        </div>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t-2 border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="px-6 bg-white text-gray-500 font-bold text-sm">{{ __('Or continue with') }}</span>
                            </div>
                        </div>

                        <!-- LinkedIn Button -->
                        <div>
                            <x-ui.button as="a" href="{{ route('auth.linkedin.redirect') }}" variant="secondary" size="lg" fullWidth class="social-btn-2027 group relative rounded-2xl">
                                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100" style="background: linear-gradient(to right, rgba(0, 119, 181, 0.05), rgba(0, 119, 181, 0.1));"></div>
                                <svg class="relative h-5 w-5" viewBox="0 0 24 24" style="fill: var(--color-linkedin, #0077B5);">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                                <span class="relative">{{ __('Continue with LinkedIn') }}</span>
                            </x-ui.button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center pt-2 sm:pt-4">
                            <p class="text-xs sm:text-sm text-gray-600">
                                {{ __("Don't have an account?") }}
                                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                    {{ __('Sign up for free') }}
                                </a>
                            </p>
                        </div>

                        <div class="text-center text-xs text-gray-500">
                            <p>{{ __('By continuing, you agree to our') }} <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline">{{ __('Privacy Policy') }}</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Trust Indicators - 2027 Enhanced -->
            <div class="mt-6 sm:mt-8">
                <div class="grid grid-cols-3 gap-3 sm:gap-4">
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100">
                        <div class="text-2xl sm:text-3xl font-black mb-1 text-secondary-500">1000+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Contributors') }}</div>
                    </div>
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100">
                        <div class="text-2xl sm:text-3xl font-black mb-1 text-secondary-500">500+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Challenges') }}</div>
                    </div>
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100">
                        <div class="text-2xl sm:text-3xl font-black mb-1 text-secondary-500">2000+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Tasks Done') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
