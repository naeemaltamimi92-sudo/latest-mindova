@extends('layouts.app')

@section('title', __('Forgot Password'))

@php
    $isRTL = app()->getLocale() === 'ar';
    $dir = $isRTL ? 'rtl' : 'ltr';
    $textAlign = $isRTL ? 'text-right' : 'text-left';
    $flexDir = $isRTL ? 'flex-row-reverse' : 'flex-row';
    $pl = $isRTL ? 'pr' : 'pl';
    $left = $isRTL ? 'right' : 'left';
@endphp

@section('content')
<div class="min-h-[calc(100vh-5rem)] flex" data-auth-page="true" style="flex-direction: row !important;">
    <!-- Left Side - Visual Section -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 min-h-full">
        <div class="flex flex-col justify-center px-8 lg:px-12 xl:px-16 text-white" dir="{{ $dir }}">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 {{ $textAlign }}">
                    {{ __('Password Recovery') }}<br/>
                    <span class="text-white">{{ __('Made Simple') }}</span>
                </h1>
                <p class="text-base text-gray-300 mb-8 leading-relaxed {{ $textAlign }}">
                    {{ __("Don't worry! We'll send you reset instructions to get you back on track.") }}
                </p>

                <!-- Features List -->
                <div class="space-y-5">
                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Secure Process') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Industry-standard encryption for password resets') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="{{ $textAlign }} pt-1">
                            <h3 class="text-base font-semibold text-white mb-1">{{ __('Quick Recovery') }}</h3>
                            <p class="text-slate-400 text-sm">{{ __('Receive reset link instantly in your inbox') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 {{ $flexDir }}">
                        <div class="flex-shrink-0 w-10 h-10 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
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
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 bg-gray-50">
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
                        {{ __('Forgot') }} <span class="text-primary-600">{{ __('Password') }}</span>
                    </h2>
                    <p class="text-sm text-gray-600">{{ __('Enter your email to receive a password reset link') }}</p>
                </div>

                @if (session('status'))
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm font-medium">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                                   class="w-full {{ $pl }}-9 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 {{ $textAlign }}"
                                   placeholder="{{ __('your.email@example.com') }}">
                        </div>
                        @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                        {{ __('Send Reset Link') }}
                        <svg class="w-4 h-4 {{ $isRTL ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </x-ui.button>

                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
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
