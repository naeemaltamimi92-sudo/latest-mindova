<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mindova - Where human expertise meets AI innovation. Join a global community of problem-solvers.">
    <title>{{ config('app.name', 'Mindova') }} - {{ __('AI-Powered Challenge Platform') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">

    <!-- ===================================
         NAVIGATION
         =================================== -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 border-b border-gray-100" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">M</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900">Mindova</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('how-it-works') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('How it works') }}</a>
                    <a href="{{ route('challenges.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Challenges') }}</a>
                    <a href="{{ route('community.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Community') }}</a>
                    <a href="{{ route('success-stories') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">{{ __('Success Stories') }}</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2">
                        {{ __('Sign In') }}
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-500 text-white rounded-lg text-sm font-medium hover:bg-primary-600">
                        {{ __('Get Started') }}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-white border-t border-gray-100" id="mobile-menu">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('how-it-works') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('How it works') }}</a>
                <a href="{{ route('challenges.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('Challenges') }}</a>
                <a href="{{ route('community.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('Community') }}</a>
                <a href="{{ route('success-stories') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">{{ __('Success Stories') }}</a>
                <div class="pt-3 border-t border-gray-100 space-y-2">
                    <a href="{{ route('login') }}" class="block w-full px-4 py-2 text-center text-gray-700 border border-gray-200 rounded-lg">{{ __('Sign In') }}</a>
                    <a href="{{ route('register') }}" class="block w-full px-4 py-2 text-center text-white bg-primary-500 rounded-lg">{{ __('Get Started') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================================
         HERO SECTION
         =================================== -->
    <section class="relative bg-slate-900 min-h-screen flex items-center pt-16">
        <!-- Simple Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-primary-900"></div>
        
        <!-- Subtle Grid Pattern -->
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-3xl">
                <!-- Headline -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ __('Where Human Expertise') }}<br>
                    <span class="text-primary-400">{{ __('Meets AI Innovation') }}</span>
                </h1>

                <!-- Subheadline -->
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl leading-relaxed">
                    {{ __('Join a global community of problem-solvers. Submit challenges, collaborate with top talent, and build solutions that matter.') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary-600 rounded-lg font-semibold hover:bg-gray-50">
                        {{ __('Start Solving') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('challenges.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 text-white border border-white/20 rounded-lg font-semibold hover:bg-white/20">
                        {{ __('Explore Challenges') }}
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap gap-8 md:gap-12">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white">2,486</div>
                        <div class="text-gray-400 text-sm">{{ __('Active Projects') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white">4,953</div>
                        <div class="text-gray-400 text-sm">{{ __('Expert Solvers') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white">86</div>
                        <div class="text-gray-400 text-sm">{{ __('Countries') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         LOGO ECOSYSTEM SECTION
         =================================== -->
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <p class="text-center text-gray-500 text-sm font-medium uppercase tracking-wider">
                {{ __('Trusted by innovative teams worldwide') }}
            </p>
        </div>
        
        <div class="relative">
            <div class="flex items-center justify-center gap-12 flex-wrap max-w-5xl mx-auto px-4">
                <!-- Logo placeholders using SVG -->
                <div class="flex items-center justify-center h-8 px-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="h-6 w-auto text-gray-600" viewBox="0 0 120 30" fill="currentColor">
                        <rect x="0" y="10" width="20" height="10" rx="2"/>
                        <text x="28" y="20" font-size="12" font-weight="600">Acme Corp</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center h-8 px-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="h-6 w-auto text-gray-600" viewBox="0 0 140 30" fill="currentColor">
                        <circle cx="10" cy="15" r="8"/>
                        <text x="28" y="20" font-size="12" font-weight="600">GlobalTech</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center h-8 px-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="h-6 w-auto text-gray-600" viewBox="0 0 140 30" fill="currentColor">
                        <polygon points="10,5 20,25 0,25"/>
                        <text x="28" y="20" font-size="12" font-weight="600">InnovateLabs</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center h-8 px-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="h-6 w-auto text-gray-600" viewBox="0 0 160 30" fill="currentColor">
                        <rect x="0" y="8" width="14" height="14" rx="7"/>
                        <text x="22" y="20" font-size="12" font-weight="600">FutureSystems</text>
                    </svg>
                </div>
                <div class="flex items-center justify-center h-8 px-4 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="h-6 w-auto text-gray-600" viewBox="0 0 150 30" fill="currentColor">
                        <path d="M0 15 L10 5 L20 15 L10 25 Z"/>
                        <text x="28" y="20" font-size="12" font-weight="600">TechVentures</text>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         HOW IT WORKS SECTION
         =================================== -->
    <section class="py-20 bg-gray-50" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    {{ __('The Process') }}
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('How Mindova Works') }}
                </h2>
                <p class="text-lg text-gray-600">
                    {{ __('Three simple steps to transform your challenges into solutions') }}
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <span class="text-5xl font-bold text-primary-200 block mb-4">01</span>
                    <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Submit Your Challenge') }}</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ __('Define the problem you\'re facing. Our AI analyzes your requirements and categorizes the challenge for optimal matching.') }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <span class="text-5xl font-bold text-primary-200 block mb-4">02</span>
                    <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('AI Matches Experts') }}</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ __('Our intelligent system identifies the perfect mix of skills from our global network of verified problem-solvers.') }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <span class="text-5xl font-bold text-primary-200 block mb-4">03</span>
                    <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('Collaborate & Solve') }}</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        {{ __('Work together in a secure environment with built-in tools, NDAs, and progress tracking until your solution is ready.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         FEATURES SECTION
         =================================== -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    {{ __('Platform') }}
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('Everything You Need to Innovate') }}
                </h2>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="features-grid">
                <!-- Feature 1 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('AI-Powered Matching') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Intelligent algorithms connect your challenge with the perfect experts in seconds, not weeks.') }}
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Global Talent Pool') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Access verified professionals from 86+ countries across every discipline imaginable.') }}
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Secure Collaboration') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Enterprise-grade security with built-in NDAs, encrypted communications, and IP protection.') }}
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Real-time Analytics') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Track progress, measure impact, and gain insights with comprehensive dashboards.') }}
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Smart Workflows') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Automated task assignment, deadline management, and milestone tracking.') }}
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:border-primary-200 transition-colors">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Verified Results') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ __('Blockchain-backed certificates and reputation scoring you can trust.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         TESTIMONIALS SECTION
         =================================== -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    {{ __('Success Stories') }}
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('Loved by Teams Worldwide') }}
                </h2>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Testimonial 1 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <svg class="w-8 h-8 text-primary-200 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        {{ __('Mindova reduced our time-to-solution by 70%. The quality of experts and the AI matching is simply incredible. It\'s transformed how we approach R&D challenges.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold text-sm">
                            SC
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Sarah Chen</div>
                            <div class="text-sm text-primary-600">VP of Engineering, TechCorp</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <svg class="w-8 h-8 text-primary-200 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        {{ __('We\'ve solved challenges that were stalled for months. The collaborative environment drives real innovation. Our team is now more agile than ever.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm">
                            MJ
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Marcus Johnson</div>
                            <div class="text-sm text-primary-600">CTO, InnovateLabs</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <svg class="w-8 h-8 text-primary-200 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        {{ __('The security features give us confidence to share sensitive challenges. It\'s enterprise-ready from day one with all the compliance we need.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                            ER
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Elena Rodriguez</div>
                            <div class="text-sm text-primary-600">Head of R&D, FutureSystems</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <svg class="w-8 h-8 text-primary-200 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        {{ __('As a startup, we get access to talent we could never afford otherwise. Mindova levels the playing field and helps us compete with the big players.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-semibold text-sm">
                            DP
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">David Park</div>
                            <div class="text-sm text-primary-600">Founder, StartupX</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         STATS SECTION
         =================================== -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    {{ __('Our Impact') }}
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('Numbers That Speak') }}
                </h2>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">50K+</div>
                    <div class="text-gray-600 text-sm">{{ __('Challenges Solved') }}</div>
                </div>

                <!-- Stat 2 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">98%</div>
                    <div class="text-gray-600 text-sm">{{ __('Success Rate') }}</div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">$2.4B</div>
                    <div class="text-gray-600 text-sm">{{ __('Value Generated') }}</div>
                </div>

                <!-- Stat 4 -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-bold text-primary-600 mb-2">12K+</div>
                    <div class="text-gray-600 text-sm">{{ __('Active Experts') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         CTA SECTION
         =================================== -->
    <section class="relative bg-primary-500 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                {{ __('Ready to Transform How You Solve Problems?') }}
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                {{ __('Join thousands of companies and experts building the future together. Start your journey today.') }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary-600 rounded-lg font-semibold hover:bg-gray-50">
                    {{ __('Get Started Free') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-transparent text-white border border-white/50 rounded-lg font-semibold hover:bg-white/10">
                    {{ __('Talk to Sales') }}
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center gap-6 text-white/70 text-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('No credit card required') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Free 14-day trial') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Setup in under 5 minutes') }}
                </span>
            </div>
        </div>
    </section>

    <!-- ===================================
         FOOTER
         =================================== -->
    <footer class="bg-slate-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Column -->
                <div class="col-span-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">M</span>
                        </div>
                        <span class="text-lg font-bold text-white">Mindova</span>
                    </a>
                    <p class="text-gray-400 mb-6 max-w-xs text-sm">
                        {{ __('Where human expertise meets AI innovation. A global ecosystem for collaborative problem-solving.') }}
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-primary-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-primary-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-primary-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-gray-400 hover:bg-primary-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">{{ __('Product') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Features') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Pricing') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Security') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Integrations') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Changelog') }}</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">{{ __('Company') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('About') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Careers') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Press') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Partners') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                <!-- Resources Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">{{ __('Resources') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Blog') }}</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Documentation') }}</a></li>
                        <li><a href="{{ route('help') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Help Center') }}</a></li>
                        <li><a href="{{ route('api-docs') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('API Reference') }}</a></li>
                        <li><a href="{{ route('community.index') }}" class="text-gray-400 hover:text-white text-sm transition-colors">{{ __('Community') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="border-t border-slate-800 pt-8 mb-8">
                <div class="max-w-md mx-auto text-center">
                    <h4 class="text-white font-semibold mb-2">{{ __('Stay Updated') }}</h4>
                    <p class="text-gray-400 text-sm mb-4">{{ __('Get the latest on AI-powered innovation') }}</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="{{ __('Enter your email') }}" class="flex-1 px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm placeholder-gray-500 focus:border-primary-500 focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-sm font-medium transition-colors">
                            {{ __('Subscribe') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}
                </p>
                <div class="flex gap-6">
                    <a href="{{ route('terms') }}" class="text-gray-500 hover:text-white text-sm transition-colors">{{ __('Terms') }}</a>
                    <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-white text-sm transition-colors">{{ __('Privacy') }}</a>
                    <a href="#" class="text-gray-500 hover:text-white text-sm transition-colors">{{ __('Cookies') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================================
         SCRIPTS
         =================================== -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>
</html>
