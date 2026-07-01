@extends('layouts.app')

@section('title', __('Page Not Found'))

@section('content')
<div class="min-h-[calc(100vh-10rem)] flex items-center justify-center relative overflow-hidden">
    <!-- Ambient glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[36rem] h-[36rem] bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center animate-fade-in">
        <div class="mb-8 flex justify-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-aurora glow-primary-md">
                <x-icon name="search" class="w-10 h-10 text-white" />
            </div>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('404 - Page Not Found') }}</h1>

        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
            {{ __('Oops! The page you\'re looking for seems to have wandered off.') }}
        </p>

        <p class="text-gray-500 dark:text-gray-400 mb-12">
            {{ __('It might have been moved, deleted, or never existed. Let\'s get you back on track.') }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button as="a" href="{{ route('dashboard') }}" variant="primary">
                {{ __('Go to Dashboard') }}
            </x-ui.button>
            <x-ui.button as="a" href="{{ url()->previous() }}" variant="outline">
                {{ __('Go Back') }}
            </x-ui.button>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Try these instead') }}</h3>
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
                <a href="{{ route('challenges.index') }}" class="text-primary-600 hover:underline">{{ __('Browse Challenges') }}</a>
                @auth
                @if(auth()->user()->isVolunteer())
                <a href="{{ route('tasks.available') }}" class="text-primary-600 hover:underline">{{ __('Available Tasks') }}</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="text-primary-600 hover:underline">{{ __('Your Profile') }}</a>
                @endauth
                <a href="{{ route('help') }}" class="text-primary-600 hover:underline">{{ __('Help Center') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
