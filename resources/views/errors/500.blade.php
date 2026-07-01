@extends('layouts.app')

@section('title', __('Server Error'))

@section('content')
<div class="min-h-[calc(100vh-10rem)] flex items-center justify-center relative overflow-hidden">
    <!-- Ambient glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[36rem] h-[36rem] bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center animate-fade-in">
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-aurora glow-primary-md">
                <x-icon name="alert-circle" class="w-10 h-10 text-white" />
            </div>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('500 - Server Error') }}</h1>

        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
            {{ __('Something went wrong on our end.') }}
        </p>

        <p class="text-gray-500 dark:text-gray-400 mb-12">
            {{ __('We\'re experiencing technical difficulties. Our team has been notified and is working to fix the issue. Please try again in a few moments.') }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button @click="window.location.reload()" variant="primary">
                {{ __('Try Again') }}
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('dashboard') }}" variant="outline">
                {{ __('Go to Dashboard') }}
            </x-ui.button>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('What can you do?') }}</h3>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <p>• {{ __('Refresh the page and try again') }}</p>
                <p>• {{ __('Check back in a few minutes') }}</p>
                <p>• {{ __('If the problem persists, contact support') }}</p>
            </div>
            <div class="mt-6">
                <a href="#" class="text-primary-600 hover:underline text-sm">{{ __('Contact Support →') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
