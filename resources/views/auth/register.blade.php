@extends('layouts.app')

@section('title', __('Register'))

@php
    $isRTL = app()->getLocale() === 'ar';
    $dir = $isRTL ? 'rtl' : 'ltr';
    $textAlign = $isRTL ? 'text-right' : 'text-left';
    $flexDir = $isRTL ? 'flex-row-reverse' : 'flex-row';
    $left = $isRTL ? 'right' : 'left';
    $pl = $isRTL ? 'pr' : 'pl';
@endphp

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-premium-dark min-h-full relative overflow-hidden">
        <div class="absolute inset-0 interactive-map-container">
            <div class="absolute top-1/4 left-1/3 w-2 h-2 rounded-full bg-primary-400/60 animate-glow"></div>
            <div class="absolute top-2/3 left-1/2 w-1.5 h-1.5 rounded-full bg-primary-300/50 animate-glow" style="animation-delay: 0.8s;"></div>
            <div class="absolute top-1/2 left-1/4 w-1.5 h-1.5 rounded-full bg-primary-300/40 animate-glow" style="animation-delay: 1.6s;"></div>
        </div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-display mb-4 {{ $textAlign }}">
                    {{ __('Join the Future of') }}<br/>
                    <span class="text-gradient-aurora">{{ __('Collaborative Innovation') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed max-w-xl {{ $textAlign }}">
                    {{ __('Connect with talented contributors or post challenges. Our AI-powered platform creates perfect teams for real-world projects.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-5 mt-8">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="zap" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Advanced algorithms form optimal teams based on skills and experience') }}</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="building" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Micro Companies') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Work in small, focused teams for maximum efficiency and collaboration') }}</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center">
                            <x-icon name="shield" class="w-5 h-5 text-primary-300" />
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Proven Results') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('127+ challenges completed with 85% success rate') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Registration Form -->
    <div class="w-full lg:w-1/2 flex items-start justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 bg-gray-50 dark:bg-gray-900">
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
                        {{ __('Create') }} <span class="text-primary-600">{{ __('Account') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Join Mindova to start solving challenges or finding talented contributors') }}</p>
                </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Account Type Selection -->
                    <div x-data="{ userType: 'volunteer' }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 {{ $textAlign }}">{{ __('I want to register as') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative flex cursor-pointer rounded-lg border p-3 transition-premium-fast"
                                   :class="userType === 'volunteer' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'">
                                <input type="radio" name="user_type" value="volunteer" class="sr-only" checked @change="userType = 'volunteer'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-1.5 mb-1">
                                        <x-icon name="user" class="w-4 h-4 text-primary-600" />
                                        <span class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ __('Contributor') }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400">{{ __('Contribute skills') }}</span>
                                </span>
                                <svg class="absolute top-2 right-2 h-3.5 w-3.5 text-primary-600"
                                     :class="userType === 'volunteer' ? 'opacity-100' : 'opacity-0'"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border p-3 transition-premium-fast"
                                   :class="userType === 'company' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900'">
                                <input type="radio" name="user_type" value="company" class="sr-only" @change="userType = 'company'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-1.5 mb-1">
                                        <x-icon name="building" class="w-4 h-4 text-emerald-600" />
                                        <span class="text-xs font-semibold text-gray-900 dark:text-gray-100">{{ __('Company') }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-600 dark:text-gray-400">{{ __('Post challenges') }}</span>
                                </span>
                                <svg class="absolute top-2 right-2 h-3.5 w-3.5 text-emerald-600"
                                     :class="userType === 'company' ? 'opacity-100' : 'opacity-0'"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>
                        </div>
                        @error('user_type')
                        <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-ui.input name="name" type="text" icon="user" :label="__('Full Name')" :placeholder="__('John Doe')" required />

                    <x-ui.input name="email" type="email" icon="mail" :label="__('Email Address')" :placeholder="__('john@example.com')" required forceLtr />

                    <x-ui.password-input name="password" :label="__('Password')" placeholder="••••••••" :hint="__('Minimum 8 characters')" required :showStrength="true" />

                    <x-ui.password-input name="password_confirmation" :label="__('Confirm Password')" placeholder="••••••••" required />

                    <!-- Profile Picture Upload (Volunteers Only) -->
                    <div x-show="userType === 'volunteer'" x-data="{ preview: null }">
                        <label for="profile_picture" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 {{ $textAlign }}">{{ __('Profile Picture (Optional)') }}</label>
                        <div class="flex items-center gap-3">
                            <!-- Preview Circle -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <x-icon name="user" class="w-6 h-6 text-gray-400" />
                                    </template>
                                </div>
                            </div>
                            <!-- Upload Button -->
                            <div class="flex-1">
                                <label for="profile_picture" class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:border-gray-400 transition-premium-fast">
                                    <x-icon name="file-text" class="w-4 h-4" />
                                    {{ __('Choose Photo') }}
                                </label>
                                <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="hidden"
                                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => preview = e.target.result; reader.readAsDataURL(file); }">
                                <p class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG (Max 2MB)') }}</p>
                            </div>
                        </div>
                        @error('profile_picture')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start gap-2">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-primary-500 focus:ring-primary-400 border-gray-300 rounded mt-0.5 cursor-pointer flex-shrink-0">
                        <label for="terms" class="text-xs text-gray-600 dark:text-gray-400 cursor-pointer">
                            {{ __('I agree to the') }} <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Privacy') }}</a>
                        </label>
                    </div>
                    @error('terms')
                    <p class="text-red-600 text-xs font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Create Account') }}
                        <x-icon name="arrow-right" class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" />
                    </x-ui.button>

                    <x-ui.divider :label="__('Or continue with')" />

                    <!-- LinkedIn Button -->
                    <x-ui.social-button provider="linkedin" :href="route('auth.linkedin.redirect')">
                        {{ __('Continue with LinkedIn') }}
                    </x-ui.social-button>

                    <!-- Login Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-4">
                <div class="grid grid-cols-3 gap-3">
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">450+</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Contributors') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">85%</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Success Rate') }}</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="text-xl font-bold mb-0.5 text-primary-600">100%</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Secure') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
