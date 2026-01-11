@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
<div class="min-h-screen flex">
    <!-- Left Side - Premium Visual Section (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-animated">
        <!-- Floating Background Elements -->
        <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="floating-element absolute bottom-20 right-0 w-[32rem] h-[32rem] bg-white/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>

        <div class="relative z-10 flex flex-col justify-center px-8 lg:px-12 xl:px-16 2xl:px-20 text-white">
            <div class="animate-slide-in-up">
                <!-- Logo/Brand -->
                <div class="mb-6 lg:mb-8 flex justify-center lg:justify-start">
                    <div class="inline-flex items-center space-x-2 lg:space-x-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl px-4 lg:px-6 py-3 lg:py-4 shadow-2xl">
                        <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="w-10 h-10 lg:w-12 lg:h-12">
                        <span class="text-xl lg:text-2xl font-black">Mindova</span>
                    </div>
                </div>

                <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black mb-4 lg:mb-6 leading-tight">
                    {{ __('Create a New') }}<br/>
                    <span class="text-white/90">{{ __('Secure Password') }}</span>
                </h1>
                <p class="text-base md:text-lg lg:text-xl text-white/80 mb-8 lg:mb-12 leading-relaxed">
                    {{ __('Choose a strong password to protect your account and data.') }}
                </p>

                <!-- Features List -->
                <div class="space-y-4 lg:space-y-6">
                    <div class="flex items-start gap-3 lg:gap-4 animate-slide-in-up" style="animation-delay: 0.2s;">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base lg:text-lg font-bold mb-1">{{ __('Strong Encryption') }}</h3>
                            <p class="text-white/70 text-xs lg:text-sm">{{ __('Your password is encrypted with industry-leading security') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 lg:gap-4 animate-slide-in-up" style="animation-delay: 0.3s;">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base lg:text-lg font-bold mb-1">{{ __('Secure Reset') }}</h3>
                            <p class="text-white/70 text-xs lg:text-sm">{{ __('One-time secure token ensures only you can reset') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 lg:gap-4 animate-slide-in-up" style="animation-delay: 0.4s;">
                        <div class="flex-shrink-0 w-10 h-10 lg:w-12 lg:h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base lg:text-lg font-bold mb-1">{{ __('Instant Access') }}</h3>
                            <p class="text-white/70 text-xs lg:text-sm">{{ __('Get back to your work immediately after reset') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Reset Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 xl:px-12 py-8 sm:py-12 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="max-w-lg w-full">
            <div class="card-premium bg-white animate-slide-in-up px-6 sm:px-8 md:px-10 lg:px-12 xl:px-14 py-8 sm:py-10 md:py-12">
                <!-- Header -->
                <div class="text-center mb-8 sm:mb-10">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-6 sm:mb-8">
                        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm border border-gray-200 rounded-2xl px-4 sm:px-5 py-2 shadow-lg">
                            <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="w-8 h-8 sm:w-10 sm:h-10">
                            <span class="text-lg sm:text-xl font-black text-gradient">Mindova</span>
                        </div>
                    </div>

                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 mb-3 sm:mb-4">
                        {{ __('Reset') }} <span class="text-gradient">{{ __('Password') }}</span>
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 px-2 leading-relaxed">{{ __('Enter your new password below') }}</p>
                </div>

                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5 sm:space-y-6 md:space-y-7">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Email Address') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400 @error('email') border-red-500 @enderror"
                                   placeholder="{{ __('your.email@example.com') }}">
                        </div>
                        @error('email')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('New Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400 @error('password') border-red-500 @enderror"
                                   placeholder="{{ __('Enter new password') }}">
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">{{ __('Minimum 8 characters') }}</p>
                        @error('password')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Confirm Password') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base text-gray-900 font-medium placeholder-gray-400"
                                   placeholder="{{ __('Confirm your new password') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-blue text-white font-bold text-base sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-xl transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl">
                            {{ __('Reset Password') }}
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center pt-2 sm:pt-4">
                        <p class="text-xs sm:text-sm text-gray-600">
                            {{ __('Remember your password?') }}
                            <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
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
