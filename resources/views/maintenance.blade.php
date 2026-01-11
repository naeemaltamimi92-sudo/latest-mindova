<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ config('app.name', 'Mindova') }} - {{ __('Under Maintenance') }}</title>

    @vite(['resources/css/app.css'])

    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', 'Segoe UI', Tahoma, sans-serif; }
    </style>
    @endif

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-violet-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Grid pattern overlay -->
    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 32 32\" width=\"32\" height=\"32\" fill=\"none\" stroke=\"white\"><path d=\"M0 .5H31.5V32\"/></svg>');"></div>

    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto">
        <!-- Animated Icon -->
        <div class="relative mb-10">
            <!-- Pulse rings -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 rounded-full bg-indigo-500/20 pulse-ring"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center" style="animation-delay: 0.5s;">
                <div class="w-32 h-32 rounded-full bg-violet-500/20 pulse-ring" style="animation-delay: 0.5s;"></div>
            </div>

            <!-- Main icon -->
            <div class="relative float-animation">
                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl shadow-indigo-500/50 flex items-center justify-center transform rotate-12">
                    <svg class="w-16 h-16 text-white transform -rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="h-10 w-10">
            <span class="text-3xl font-black text-white">Mindova</span>
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">
            {{ __('We\'re Under Maintenance') }}
        </h1>

        <!-- Description -->
        <p class="text-lg md:text-xl text-white/70 mb-10 leading-relaxed">
            {{ __('We\'re performing scheduled maintenance to improve your experience. We\'ll be back shortly!') }}
        </p>

        <!-- Status indicators -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-12">
            <div class="flex items-center gap-3 px-5 py-3 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/10">
                <div class="w-3 h-3 bg-amber-400 rounded-full animate-pulse"></div>
                <span class="text-white/80 font-medium">{{ __('Maintenance in Progress') }}</span>
            </div>
        </div>

        <!-- Admin login link -->
        <div class="mt-16 pt-8 border-t border-white/10">
            <p class="text-white/40 text-sm mb-4">{{ __('Mindova Administrator?') }}</p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold rounded-xl border border-white/20 hover:border-white/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('Admin Login') }}
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-16 text-white/30 text-sm">
            &copy; {{ date('Y') }} Mindova. {{ __('All Rights Reserved') }}.
        </div>
    </div>
</body>
</html>
