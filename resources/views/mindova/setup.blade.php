<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Initial Setup') }} - Mindova Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4">
                <span class="text-2xl font-bold text-white">M</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Mindova Admin</h1>
            <p class="text-slate-400 mt-1">{{ __('Initial Setup') }}</p>
        </div>

        <!-- Setup Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-800">{{ __('Welcome!') }}</h3>
                        <p class="text-sm text-blue-700 mt-1">{{ __('Create the Owner account to get started with Mindova Admin.') }}</p>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-bold text-slate-900 mb-6">{{ __('Create Owner Account') }}</h2>

            @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('mindova.setup.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Full Name') }}</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                        placeholder="{{ __('Enter your full name') }}">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Email') }}</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'mindova.ai@gmail.com') }}" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                        placeholder="{{ __('Enter your email') }}">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="setup_key" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Setup Key') }}</label>
                    <input type="password" id="setup_key" name="setup_key" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('setup_key') border-red-500 @enderror"
                        placeholder="{{ __('Enter the setup key') }}">
                    @error('setup_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-500">{{ __('The setup key is configured in your environment file (MINDOVA_SETUP_KEY)') }}</p>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-500/50 transition-all">
                    {{ __('Create Owner Account') }}
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-500 text-sm mt-6">
            &copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}
        </p>
    </div>
</body>
</html>
