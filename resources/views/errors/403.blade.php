@extends('layouts.app')

@section('title', __('Access Denied'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="mb-8">
        <span class="text-9xl">🚫</span>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('403 - Access Denied') }}</h1>

    <p class="text-xl text-gray-600 mb-8">
        {{ __('You don\'t have permission to access this resource.') }}
    </p>

    <p class="text-gray-500 mb-12">
        {{ $exception->getMessage() ?: __('This action is unauthorized. You may not have the required permissions or your account type may not support this feature.') }}
    </p>

    <div class="flex justify-center space-x-4">
        <a href="{{ route('dashboard') }}" class="btn-primary">
            {{ __('Go to Dashboard') }}
        </a>
        <a href="{{ url()->previous() }}" class="btn-secondary">
            {{ __('Go Back') }}
        </a>
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
@endsection
