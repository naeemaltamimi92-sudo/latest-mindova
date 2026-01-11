@extends('layouts.app')

@section('title', __('Page Not Found'))

@push('styles')
<style>
    .error-float { animation: errorFloat 6s ease-in-out infinite; }
    @keyframes errorFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .slide-up { animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-delay-1 { animation-delay: 0.1s; }
    .slide-up-delay-2 { animation-delay: 0.2s; }
    .slide-up-delay-3 { animation-delay: 0.3s; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
    }
    .number-gradient {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

@section('content')
<div class="min-h-[calc(100vh-10rem)] flex items-center justify-center bg-gradient-to-br from-slate-50 via-indigo-50/30 to-violet-50/50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl error-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-violet-400/10 rounded-full blur-3xl error-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[40rem] h-[40rem] bg-purple-400/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Grid Pattern -->
    <div class="absolute inset-0 opacity-[0.02]">
        <svg class="w-full h-full">
            <pattern id="error-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1" class="text-slate-900"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#error-grid)"/>
        </svg>
    </div>

    <div class="relative max-w-3xl mx-auto px-6 py-16 text-center">
        <!-- Large 404 Number -->
        <div class="slide-up mb-8">
            <span class="text-[12rem] md:text-[16rem] font-black number-gradient leading-none tracking-tighter">404</span>
        </div>

        <!-- Icon -->
        <div class="slide-up slide-up-delay-1 mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-3xl shadow-2xl shadow-indigo-500/30 pulse-glow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Title & Description -->
        <div class="slide-up slide-up-delay-2 mb-10">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">{{ __('Page Not Found') }}</h1>
            <p class="text-xl text-slate-600 max-w-xl mx-auto leading-relaxed">
                {{ __('Oops! The page you\'re looking for seems to have wandered off. It might have been moved, deleted, or never existed.') }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="slide-up slide-up-delay-3 flex flex-col sm:flex-row justify-center gap-4 mb-12">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold px-8 py-4 rounded-2xl transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Go to Dashboard') }}
            </a>
            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-3 bg-white hover:bg-slate-50 text-slate-700 font-bold px-8 py-4 rounded-2xl transition-all border-2 border-slate-200 hover:border-slate-300 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Go Back') }}
            </a>
        </div>

        <!-- Quick Links -->
        <div class="slide-up slide-up-delay-3">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-200/60 shadow-xl p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    {{ __('Try these instead') }}
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('challenges.index') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-700">{{ __('Challenges') }}</span>
                    </a>
                    @auth
                    @if(auth()->user()->isVolunteer())
                    <a href="{{ route('tasks.available') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-emerald-700">{{ __('Tasks') }}</span>
                    </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-50 hover:bg-violet-50 border border-slate-100 hover:border-violet-200 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-violet-700">{{ __('Profile') }}</span>
                    </a>
                    @endauth
                    <a href="{{ route('help') }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-amber-700">{{ __('Help') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
