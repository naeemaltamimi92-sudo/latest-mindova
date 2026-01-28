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

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 min-h-full">
        <div class="flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 {{ $textAlign }}">
                    {{ __('Join the Future of') }}<br/>
                    <span class="text-white">{{ __('Collaborative Innovation') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed max-w-xl {{ $textAlign }}">
                    {{ __('Connect with talented contributors or post challenges. Our AI-powered platform creates perfect teams for real-world projects.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-5 mt-8">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('AI-Powered Matching') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Advanced algorithms form optimal teams based on skills and experience') }}</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Micro Companies') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Work in small, focused teams for maximum efficiency and collaboration') }}</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
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
    <div class="w-full lg:w-1/2 flex items-start justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 bg-gray-50">
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
                        {{ __('Create') }} <span class="text-primary-600">{{ __('Account') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600">{{ __('Join Mindova to start solving challenges or finding talented contributors') }}</p>
                </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Account Type Selection -->
                    <div x-data="{ userType: 'volunteer' }">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 {{ $textAlign }}">{{ __('I want to register as') }}</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative flex cursor-pointer rounded-lg border p-3"
                                   :class="userType === 'volunteer' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 bg-gray-50'">
                                <input type="radio" name="user_type" value="volunteer" class="sr-only" checked @change="userType = 'volunteer'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-1.5 mb-1">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-900">{{ __('Contributor') }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-600">{{ __('Contribute skills') }}</span>
                                </span>
                                <svg class="absolute top-2 right-2 h-3.5 w-3.5 text-primary-600"
                                     :class="userType === 'volunteer' ? 'opacity-100' : 'opacity-0'"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border p-3"
                                   :class="userType === 'company' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 bg-gray-50'">
                                <input type="radio" name="user_type" value="company" class="sr-only" @change="userType = 'company'">
                                <span class="flex flex-1 flex-col">
                                    <span class="flex items-center gap-1.5 mb-1">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-900">{{ __('Company') }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-600">{{ __('Post challenges') }}</span>
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

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5 {{ $textAlign }}">{{ __('Full Name') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input id="name" name="name" type="text" required
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }}"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('John Doe') }}">
                        </div>
                        @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 {{ $textAlign }}">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }}"
                                   value="{{ old('email') }}"
                                   placeholder="{{ __('john@example.com') }}">
                        </div>
                        @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
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
                            <input id="password" name="password" type="password" required
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }}"
                                   placeholder="••••••••">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Minimum 8 characters') }}</p>
                        @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5 {{ $textAlign }}">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 {{ $left }}-0 {{ $pl }}-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="w-full {{ $pl }}-9 {{ $pr }}-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }}"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Profile Picture Upload (Volunteers Only) -->
                    <div x-show="userType === 'volunteer'" x-data="{ preview: null }">
                        <label for="profile_picture" class="block text-sm font-semibold text-gray-700 mb-2 {{ $textAlign }}">{{ __('Profile Picture (Optional)') }}</label>
                        <div class="flex items-center gap-3">
                            <!-- Preview Circle -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </template>
                                </div>
                            </div>
                            <!-- Upload Button -->
                            <div class="flex-1">
                                <label for="profile_picture" class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:border-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
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
                        <label for="terms" class="text-xs text-gray-600 cursor-pointer">
                            {{ __('I agree to the') }} <a href="{{ route('terms') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Privacy') }}</a>
                        </label>
                    </div>
                    @error('terms')
                    <p class="text-red-600 text-xs font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Create Account') }}
                        <svg class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </x-ui.button>

                    <!-- Divider -->
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500">{{ __('Or continue with') }}</span>
                        </div>
                    </div>

                    <!-- LinkedIn Button -->
                    <x-ui.button as="a" href="{{ route('auth.linkedin.redirect') }}" variant="outline" size="sm" fullWidth>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" style="fill: #0077B5;">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                        {{ __('Continue with LinkedIn') }}
                    </x-ui.button>

                    <!-- Login Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-5 text-center">
                <div class="flex flex-wrap justify-center items-center gap-2 text-xs">
                    <div class="flex items-center gap-1 bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-gray-700">{{ __('450+ Volunteers') }}</span>
                    </div>
                    <div class="flex items-center gap-1 bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-gray-700">{{ __('85% Success') }}</span>
                    </div>
                    <div class="flex items-center gap-1 bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-gray-700">{{ __('Secure') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
