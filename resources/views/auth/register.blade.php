@extends('layouts.app')

@section('title', __('Register'))

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
    /* 2027 Register Page Enhanced Animations */
    @keyframes float-register-2027 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-12px) rotate(1.5deg); }
        50% { transform: translateY(-6px) rotate(-1deg); }
        75% { transform: translateY(-9px) rotate(0.5deg); }
    }

    @keyframes slide-in-up-reg-2027 {
        0% { opacity: 0; transform: translateY(25px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes gradient-flow-2027 {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes pulse-border-2027 {
        0%, 100% { border-color: rgba(14, 165, 233, 0.3); }
        50% { border-color: rgba(99, 102, 241, 0.6); }
    }

    .animate-float-reg-2027 {
        animation: float-register-2027 7s ease-in-out infinite;
    }

    .animate-slide-in-up-reg-2027 {
        animation: slide-in-up-reg-2027 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        opacity: 0;
    }

    .animate-gradient-flow-2027 {
        background-size: 200% 200%;
        animation: gradient-flow-2027 4s ease infinite;
    }

    .animate-pulse-border-2027 {
        animation: pulse-border-2027 2s ease-in-out infinite;
    }

    /* Feature card 2027 style */
    .feature-card-reg-2027 {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(12px);
    }

    .feature-card-reg-2027:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.25) !important;
        border-color: rgba(255, 255, 255, 0.4) !important;
    }

    /* Submit button glow */
    .btn-glow-reg-2027 {
        position: relative;
        overflow: hidden;
    }

    .btn-glow-reg-2027::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transition: left 0.6s ease;
    }

    .btn-glow-reg-2027:hover::before {
        left: 100%;
    }

    /* Trust indicator card */
    .trust-indicator-2027 {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .trust-indicator-2027:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Account type selection card */
    .account-type-card-2027 {
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .account-type-card-2027:hover {
        transform: translateY(-2px);
    }

    /* Stagger animation delays */
    .stagger-reg-1 { animation-delay: 0.1s; }
    .stagger-reg-2 { animation-delay: 0.2s; }
    .stagger-reg-3 { animation-delay: 0.3s; }
    .stagger-reg-4 { animation-delay: 0.4s; }
    .stagger-reg-5 { animation-delay: 0.5s; }
</style>
@endpush

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Premium Visual Section - 2027 Design -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-sky-500 via-blue-500 to-indigo-500">
        <!-- Advanced Mesh Gradient Background - 2027 Enhanced -->
        <div class="absolute inset-0 opacity-25">
            <div class="floating-element absolute top-10 {{ $isRTL ? '-right-20' : '-left-20' }} w-96 h-96 bg-gradient-to-br from-rose-300 to-pink-400 rounded-full blur-3xl animate-float-reg-2027"></div>
            <div class="floating-element absolute top-1/3 {{ $isRTL ? 'left-0' : 'right-0' }} w-[28rem] h-[28rem] bg-gradient-to-br from-violet-300 to-purple-400 rounded-full blur-3xl animate-float-reg-2027" style="animation-delay: 2s;"></div>
            <div class="floating-element absolute bottom-10 {{ $isRTL ? 'right-1/4' : 'left-1/4' }} w-80 h-80 bg-gradient-to-br from-sky-300 to-blue-400 rounded-full blur-3xl animate-float-reg-2027" style="animation-delay: 4s;"></div>
        </div>

        <!-- Decorative Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px] opacity-20"></div>

        <div class="relative z-10 flex flex-col justify-start pt-16 px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div class="animate-slide-in-up">
                <!-- Logo/Brand - Enhanced -->


                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black mb-6 leading-[1.1] {{ $textAlign }}">
                    {{ __('Join the Future of') }}<br/>
                    <span class="text-white drop-shadow-lg" style="text-shadow: 0 2px 20px rgba(255,255,255,0.3);">{{ __('Collaborative Innovation') }}</span>
                </h1>
                <p class="text-lg lg:text-xl text-white mb-12 leading-relaxed font-medium max-w-xl {{ $textAlign }}" style="text-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                    {{ __('Connect with talented contributors or post challenges. Our AI-powered platform creates perfect teams for real-world projects.') }}
                </p>

                <!-- Features List - 2027 Enhanced -->
                <div class="space-y-4 lg:space-y-6">
                    <div class="feature-card-reg-2027 flex items-start gap-3 lg:gap-4 {{ $flexDir }} bg-white/10 border border-white/20 rounded-xl p-4 animate-slide-in-up-reg-2027 stagger-reg-2">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-rose-400 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }}">
                            <h3 class="text-base lg:text-lg font-bold mb-1 text-white drop-shadow-sm">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-white text-xs lg:text-sm opacity-90">{{ __('Advanced algorithms form optimal teams based on skills and experience') }}</p>
                        </div>
                    </div>

                    <div class="feature-card-reg-2027 flex items-start gap-3 lg:gap-4 {{ $flexDir }} bg-white/10 border border-white/20 rounded-xl p-4 animate-slide-in-up-reg-2027 stagger-reg-3">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }}">
                            <h3 class="text-base lg:text-lg font-bold mb-1 text-white drop-shadow-sm">{{ __('Micro Companies') }}</h3>
                            <p class="text-white text-xs lg:text-sm opacity-90">{{ __('Work in small, focused teams for maximum efficiency and collaboration') }}</p>
                        </div>
                    </div>

                    <div class="feature-card-reg-2027 flex items-start gap-3 lg:gap-4 {{ $flexDir }} bg-white/10 border border-white/20 rounded-xl p-4 animate-slide-in-up-reg-2027 stagger-reg-4">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-emerald-400 to-green-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }}">
                            <h3 class="text-base lg:text-lg font-bold mb-1 text-white drop-shadow-sm">{{ __('Proven Results') }}</h3>
                            <p class="text-white text-xs lg:text-sm opacity-90">{{ __('127+ challenges completed with 85% success rate') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Registration Form - 2027 Design -->
    <div class="w-full lg:w-1/2 flex items-start justify-center px-4 sm:px-6 lg:px-8 xl:px-12 pt-8 lg:pt-12 xl:pt-16 pb-8 bg-gradient-to-br from-slate-50 via-cyan-50 to-blue-50">
        <div class="max-w-lg w-full" dir="{{ $dir }}">
            <div class="relative group">
                <!-- Glow effect on hover -->
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400 via-blue-400 to-indigo-400 rounded-3xl blur-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-500"></div>

                <div class="relative bg-white/90 backdrop-blur-xl border-2 border-white/60 shadow-2xl rounded-3xl animate-slide-in-up px-6 sm:px-8 md:px-10 py-6 sm:py-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <!-- Mobile Logo -->
                        <div class="lg:hidden mb-6">
                            <div class="inline-flex items-center space-x-3 bg-gradient-to-br from-cyan-50 to-blue-50 border-2 border-cyan-200 rounded-2xl px-5 py-3 shadow-lg">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-600 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <span class="text-xl font-black bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">Mindova</span>
                            </div>
                        </div>

                        <h2 class="text-3xl font-black text-gray-900 mb-3 leading-tight">
                            {{ __('Create') }} <span class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ __('Account') }}</span>
                        </h2>
                        <p class="text-base text-gray-600 font-medium leading-relaxed">{{ __('Join Mindova to start solving challenges or finding talented contributors') }}</p>
                    </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-5">
                    @csrf

                    <!-- Account Type Selection -->
                    <div x-data="{ userType: 'volunteer' }">
                        <label class="block text-xs sm:text-sm font-bold text-gray-900 mb-3 sm:mb-4 {{ $textAlign }}">{{ __('I want to register as') }}</label>
                        <div class="grid grid-cols-1 xs:grid-cols-2 gap-2 sm:gap-3">
                            <label class="relative flex cursor-pointer rounded-xl border-2 bg-gradient-to-br from-blue-50/50 to-cyan-50/50 p-3 sm:p-4 transition-all hover:shadow-md"
                                   :class="userType === 'volunteer' ? 'border-blue-500 ring-2 ring-blue-200 shadow-lg' : 'border-gray-200'">
                                <input type="radio" name="user_type" value="volunteer" class="sr-only" checked @change="userType = 'volunteer'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Contributor') }}</span>
                                    </span>
                                    <span class="text-xs text-gray-600">{{ __('Contribute skills to challenges') }}</span>
                                </span>
                                <svg class="absolute top-2 right-2 sm:top-3 sm:right-3 h-4 w-4 sm:h-5 sm:w-5 text-blue-600 transition-opacity"
                                     :class="userType === 'volunteer' ? 'opacity-100' : 'opacity-0'"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-xl border-2 bg-gradient-to-br from-green-50/50 to-emerald-50/50 p-3 sm:p-4 transition-all hover:shadow-md"
                                   :class="userType === 'company' ? 'border-green-500 ring-2 ring-green-200 shadow-lg' : 'border-gray-200'">
                                <input type="radio" name="user_type" value="company" class="sr-only" @change="userType = 'company'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-bold text-gray-900">{{ __('Company') }}</span>
                                    </span>
                                    <span class="text-xs text-gray-600">{{ __('Post challenges to solve') }}</span>
                                </span>
                                <svg class="absolute top-2 right-2 sm:top-3 sm:right-3 h-4 w-4 sm:h-5 sm:w-5 text-green-600 transition-opacity"
                                     :class="userType === 'company' ? 'opacity-100' : 'opacity-0'"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>
                        </div>
                        @error('user_type')
                        <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5 {{ $textAlign }}">{{ __('Full Name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" name="name" type="text" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('John Doe') }}">
                        </div>
                        @error('name')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5 {{ $textAlign }}">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400"
                                   value="{{ old('email') }}"
                                   placeholder="{{ __('john@example.com') }}">
                        </div>
                        @error('email')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5 {{ $textAlign }}">{{ __('Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400"
                                   placeholder="••••••••">
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">{{ __('Minimum 8 characters') }}</p>
                        @error('password')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5 {{ $textAlign }}">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Profile Picture Upload (Volunteers Only) -->
                    <div x-show="userType === 'volunteer'" x-data="{ preview: null }" class="animate-slide-in-up">
                        <label for="profile_picture" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5 {{ $textAlign }}">{{ __('Profile Picture (Optional)') }}</label>
                        <div class="flex items-center gap-4">
                            <!-- Preview Circle -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 border-2 border-gray-200 flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </template>
                                </div>
                            </div>
                            <!-- Upload Button -->
                            <div class="flex-1">
                                <label for="profile_picture" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-xs sm:text-sm font-bold text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ __('Choose Photo') }}
                                </label>
                                <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden"
                                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
                                <p class="text-xs text-gray-500 mt-1.5 ml-1">{{ __('JPG, PNG or GIF (Max 2MB)') }}</p>
                            </div>
                        </div>
                        @error('profile_picture')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-0.5 cursor-pointer flex-shrink-0">
                        <label for="terms" class="ml-2 sm:ml-3 block text-xs sm:text-sm text-gray-700 cursor-pointer">
                            {{ __('I agree to the') }} <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">{{ __('Privacy Policy') }}</a>
                        </label>
                    </div>
                    @error('terms')
                    <p class="text-red-600 text-xs sm:text-sm font-semibold">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button - 2027 Enhanced -->
                    <div>
                        <button type="submit"
                                class="btn-glow-reg-2027 group w-full text-white font-bold text-base sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-xl transition-all transform hover:scale-[1.02] shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 animate-gradient-flow-2027"
                                style="background: linear-gradient(135deg, #0ea5e9, #3b82f6, #6366f1, #0ea5e9); background-size: 200% 200%;">
                            <span class="relative z-10">{{ __('Create Account') }}</span>
                            <svg class="relative z-10 w-4 h-4 sm:w-5 sm:h-5 {{ $isRTL ? 'rotate-180' : '' }} group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t-2 border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs sm:text-sm">
                            <span class="px-3 sm:px-4 bg-white text-gray-500 font-semibold">{{ __('Or continue with') }}</span>
                        </div>
                    </div>

                    <!-- LinkedIn Button -->
                    <div>
                        <a href="{{ route('auth.linkedin.redirect') }}"
                           class="w-full flex justify-center items-center px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-200 rounded-xl shadow-md text-xs sm:text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 hover:shadow-lg transition-all">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2 sm:mr-3" viewBox="0 0 24 24" fill="#0077B5">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                            {{ __('Continue with LinkedIn') }}
                        </a>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-2 sm:pt-4">
                        <p class="text-xs sm:text-sm text-gray-600">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators - 2027 Enhanced -->
            <div class="mt-6 sm:mt-8 text-center">
                <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-4 md:gap-6 text-xs sm:text-sm">
                    <div class="trust-indicator-2027 flex items-center gap-1.5 sm:gap-2 bg-white px-3 py-2 rounded-lg shadow-sm border border-slate-100 animate-slide-in-up-reg-2027 stagger-reg-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent whitespace-nowrap">{{ __('450+ Volunteers') }}</span>
                    </div>
                    <div class="trust-indicator-2027 flex items-center gap-1.5 sm:gap-2 bg-white px-3 py-2 rounded-lg shadow-sm border border-slate-100 animate-slide-in-up-reg-2027 stagger-reg-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent whitespace-nowrap">{{ __('85% Success Rate') }}</span>
                    </div>
                    <div class="trust-indicator-2027 flex items-center gap-1.5 sm:gap-2 bg-white px-3 py-2 rounded-lg shadow-sm border border-slate-100 animate-slide-in-up-reg-2027 stagger-reg-5">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent whitespace-nowrap">{{ __('Secure & Private') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
