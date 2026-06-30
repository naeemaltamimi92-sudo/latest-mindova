@extends('layouts.app')

@section('title', __('Access Denied'))

@section('content')
<div class="min-h-[calc(100vh-10rem)] flex items-center justify-center relative overflow-hidden">
    <!-- Ambient glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[36rem] h-[36rem] bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center animate-fade-in">
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-aurora glow-primary-md">
                <x-icon name="shield" class="w-10 h-10 text-white" />
            </div>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('403 - Access Denied') }}</h1>

        <p class="text-xl text-gray-600 mb-8">
            {{ __('You don\'t have permission to access this resource.') }}
        </p>

        <p class="text-gray-500 mb-12">
            {{ $exception->getMessage() ?: __('This action is unauthorized. You may not have the required permissions or your account type may not support this feature.') }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button as="a" href="{{ route('dashboard') }}" variant="primary">
                {{ __('Go to Dashboard') }}
            </x-ui.button>
            <x-ui.button as="a" href="{{ url()->previous() }}" variant="outline">
                {{ __('Go Back') }}
            </x-ui.button>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Need help?') }}</h3>
            <p class="text-sm text-gray-600 mb-4">
                {{ __('If you believe this is an error, please contact support or check your account permissions.') }}
            </p>
            <div class="flex justify-center space-x-6 text-sm">
                <a href="#" class="text-primary-600 hover:underline">{{ __('Contact Support') }}</a>
                <a href="#" class="text-primary-600 hover:underline">{{ __('View Documentation') }}</a>
                @auth
                <a href="{{ route('profile.edit') }}" class="text-primary-600 hover:underline">{{ __('Account Settings') }}</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
