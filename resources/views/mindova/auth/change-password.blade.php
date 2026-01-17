<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Change Password') }} - Mindova Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-primary-500 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-500 rounded-2xl mb-4">
                <span class="text-2xl font-bold text-white">M</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Mindova Admin</h1>
            <p class="text-slate-400 mt-1">{{ __('Secure your account') }}</p>
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if($isFirstTime)
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-amber-800">{{ __('First Login') }}</h3>
                        <p class="text-sm text-amber-700 mt-1">{{ __('You must change your temporary password before continuing.') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <h2 class="text-xl font-bold text-slate-900 mb-6">{{ __('Change Password') }}</h2>

            <form action="{{ route('mindova.password.change.submit') }}" method="POST" class="space-y-5">
                @csrf

                @if(!$isFirstTime)
                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Current Password') }}</label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror"
                        placeholder="{{ __('Enter current password') }}">
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('New Password') }}</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="{{ __('Enter new password') }}">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-500">{{ __('Minimum 8 characters with uppercase, lowercase, numbers, and symbols') }}</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Confirm Password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="{{ __('Confirm new password') }}">
                </div>

                <x-ui.button as="submit" variant="primary" fullWidth>
                    {{ __('Change Password') }}
                </x-ui.button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 text-sm mt-6">
            &copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}
        </p>
    </div>
</body>
</html>
