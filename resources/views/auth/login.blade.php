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
    /* 2027 Login Page Enhanced Animations */
    @keyframes float-2027 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-15px) rotate(2deg); }
        50% { transform: translateY(-5px) rotate(-1deg); }
        75% { transform: translateY(-10px) rotate(1deg); }
    }

    @keyframes slide-in-up-2027 {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes slide-in-right-2027 {
        0% { opacity: 0; transform: translateX({{ $isRTL ? '-30px' : '30px' }}); }
        100% { opacity: 1; transform: translateX(0); }
    }

    @keyframes shimmer-2027 {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes glow-pulse-2027 {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.5); }
    }

    @keyframes border-flow-2027 {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animate-float-2027 {
        animation: float-2027 6s ease-in-out infinite;
    }

    .animate-slide-in-up-2027 {
        animation: slide-in-up-2027 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        opacity: 0;
    }

    .animate-slide-in-right-2027 {
        animation: slide-in-right-2027 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        opacity: 0;
    }

    .animate-shimmer-2027 {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200% 100%;
        animation: shimmer-2027 2s ease-in-out infinite;
    }

    .animate-glow-pulse-2027 {
        animation: glow-pulse-2027 3s ease-in-out infinite;
    }

    /* Form Input Focus Effects */
    .input-2027:focus {
        outline: none;
        border-color: transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7) border-box;
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
        transition: left 0.5s;
    }

    .btn-shimmer-2027:hover::before {
        left: 100%;
    }

    /* Trust Indicators Hover */
    .trust-card-2027 {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .trust-card-2027:hover {
        transform: translateY(-5px) scale(1.02);
    }

    /* Feature Cards Enhanced */
    .feature-card-2027 {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .feature-card-2027:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.2) !important;
    }

    /* Social Button Gradient Border */
    .social-btn-2027 {
        position: relative;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #e2e8f0, #cbd5e1) border-box;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .social-btn-2027:hover {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #0077B5, #00a0dc) border-box;
    }

    /* Language-aware Stagger Delays */
    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }
    .stagger-5 { animation-delay: 0.5s; }
    .stagger-6 { animation-delay: 0.6s; }
</style>
@endpush

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Premium Visual Section (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-indigo-500 via-violet-500 to-purple-500">
        <!-- Advanced Mesh Gradient Background - 2027 Enhanced -->
        <div class="absolute inset-0 opacity-25">
            <div class="floating-element absolute top-10 {{ $isRTL ? '-right-20' : '-left-20' }} w-96 h-96 bg-gradient-to-br from-sky-300 to-blue-400 rounded-full blur-3xl animate-float-2027"></div>
            <div class="floating-element absolute top-1/3 {{ $isRTL ? 'left-0' : 'right-0' }} w-[28rem] h-[28rem] bg-gradient-to-br from-violet-300 to-purple-400 rounded-full blur-3xl animate-float-2027" style="animation-delay: 2s;"></div>
            <div class="floating-element absolute bottom-10 {{ $isRTL ? 'right-1/4' : 'left-1/4' }} w-80 h-80 bg-gradient-to-br from-fuchsia-300 to-pink-400 rounded-full blur-3xl animate-float-2027" style="animation-delay: 4s;"></div>
        </div>

        <!-- Decorative Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px] opacity-20"></div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 2xl:px-20 text-white" dir="{{ $dir }}">
            <div class="animate-slide-in-up">
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
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30 animate-slide-in-up-2027 stagger-2">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-sky-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
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
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30 animate-slide-in-up-2027 stagger-3">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
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
                    <div class="group flex items-start gap-4 {{ $flexDir }} feature-card-2027 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 hover:border-white/30 animate-slide-in-up-2027 stagger-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
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
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-12 bg-gradient-to-br from-slate-50 via-indigo-50/30 to-violet-50/40">
        <div class="max-w-lg w-full" dir="{{ $dir }}">
            <div class="relative group">
                <!-- Glow effect on hover -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-300 via-violet-300 to-purple-300 rounded-3xl blur-2xl opacity-0 group-hover:opacity-15 transition-opacity duration-500"></div>

                <div class="relative bg-white/95 backdrop-blur-xl border border-slate-200/60 shadow-xl rounded-3xl animate-slide-in-up px-6 sm:px-8 md:px-10 lg:px-12 py-8 sm:py-10 md:py-12">
                    <!-- Header -->
                    <div class="text-center mb-8 sm:mb-10">
                        <!-- Mobile Logo -->
                        <div class="lg:hidden mb-6 sm:mb-8">
                            <div class="inline-flex items-center gap-3 bg-gradient-to-br from-indigo-50 to-violet-50 border border-indigo-200 rounded-2xl px-5 py-3 shadow-md">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <span class="text-xl font-black bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">Mindova</span>
                            </div>
                        </div>

                        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3 leading-tight">
                            {{ __('Welcome') }} <span class="bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500 bg-clip-text text-transparent">{{ __('Back') }}</span>
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
                                    <div class="w-5 h-5 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center group-focus-within:scale-110 transition-transform duration-200">
                                        <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="w-full {{ $pl }}-12 {{ $pr }}-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 transition-all text-base text-slate-900 font-medium placeholder-slate-400 hover:border-slate-300 {{ $textAlign }} @error('email') border-rose-400 focus:border-rose-400 @enderror"
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
                                    <div class="w-5 h-5 rounded-lg bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center group-focus-within:scale-110 transition-transform duration-200">
                                        <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input id="password" type="password" name="password" required
                                       class="w-full {{ $pl }}-12 {{ $pr }}-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:border-violet-400 focus:ring-4 focus:ring-violet-100 transition-all text-base text-slate-900 font-medium placeholder-slate-400 hover:border-slate-300 {{ $textAlign }} @error('password') border-rose-400 focus:border-rose-400 @enderror"
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

                        <!-- Submit Button - 2027 Enhanced -->
                        <div>
                            <button type="submit"
                                    class="btn-shimmer-2027 group relative w-full overflow-hidden text-white font-bold text-lg px-8 py-4 rounded-2xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-3 animate-glow-pulse-2027"
                                    style="background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);">
                                <span class="relative z-10">{{ __('Sign In') }}</span>
                                <svg class="relative z-10 w-5 h-5 {{ $isRTL ? 'rotate-180' : '' }} group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
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

                        <!-- LinkedIn Button - 2027 Enhanced -->
                        <div>
                            <a href="{{ route('auth.linkedin.redirect') }}"
                               class="social-btn-2027 group relative w-full flex justify-center items-center gap-3 px-6 py-4 rounded-2xl shadow-md text-base font-bold text-gray-700 hover:shadow-lg transition-all hover:scale-[1.02]">
                                <div class="absolute inset-0 bg-gradient-to-r from-[#0077B5]/5 to-[#0077B5]/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <svg class="relative h-5 w-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="#0077B5">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                                <span class="relative">{{ __('Continue with LinkedIn') }}</span>
                            </a>
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
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100 animate-slide-in-up-2027 stagger-4">
                        <div class="text-2xl sm:text-3xl font-black mb-1 bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">1000+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Contributors') }}</div>
                    </div>
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100 animate-slide-in-up-2027 stagger-5">
                        <div class="text-2xl sm:text-3xl font-black mb-1 bg-gradient-to-r from-violet-500 to-purple-500 bg-clip-text text-transparent">500+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Challenges') }}</div>
                    </div>
                    <div class="trust-card-2027 text-center p-3 sm:p-4 rounded-xl bg-white shadow-md border border-slate-100 animate-slide-in-up-2027 stagger-6">
                        <div class="text-2xl sm:text-3xl font-black mb-1 bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">2000+</div>
                        <div class="text-xs sm:text-sm text-slate-700 font-semibold">{{ __('Tasks Done') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
