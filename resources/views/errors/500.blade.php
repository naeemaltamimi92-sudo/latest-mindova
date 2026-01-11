@extends('layouts.app')

@section('title', __('Server Error'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="mb-8">
        <span class="text-9xl">⚠️</span>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('500 - Server Error') }}</h1>

    <p class="text-xl text-gray-600 mb-8">
        {{ __('Something went wrong on our end.') }}
    </p>

    <p class="text-gray-500 mb-12">
        {{ __('We\'re experiencing technical difficulties. Our team has been notified and is working to fix the issue. Please try again in a few moments.') }}
    </p>

    <div class="flex justify-center space-x-4">
        <button onclick="window.location.reload()" class="btn-primary">
            {{ __('Try Again') }}
        </button>
        <a href="{{ route('dashboard') }}" class="btn-secondary">
            {{ __('Go to Dashboard') }}
        </a>
    </div>

    <div class="mt-12 pt-8 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('What can you do?') }}</h3>
        <div class="space-y-2 text-sm text-gray-600">
            <p>• {{ __('Refresh the page and try again') }}</p>
            <p>• {{ __('Check back in a few minutes') }}</p>
            <p>• {{ __('If the problem persists, contact support') }}</p>
        </div>
        <div class="mt-6">
            <a href="#" class="text-primary-600 hover:underline text-sm">{{ __('Contact Support →') }}</a>
        </div>
    </div>
</div>
@endsection
