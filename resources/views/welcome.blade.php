<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Mindova is the AI-powered innovation ecosystem where real challenges evolve into real solutions — built by a global community of entrepreneurs, researchers, and expert contributors.">
    <title>{{ $siteName ?? config('app.name', 'Mindova') }} - {{ __('Where Ideas Evolve Into Real Solutions') }}</title>

    <link rel="icon" href="{{ asset('images/brand/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @endif

    {{-- Landing page is light by default; only an explicit 'dark' preference applies. --}}
    <script>
        (function() {
            try {
                if (localStorage.getItem('mindova-theme') === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="antialiased landing-page">

    <!-- ===================================
         NAVIGATION
         =================================== -->
    <nav class="landing-navbar" id="navbar">
        <div class="navbar-pill liquid-glass max-w-5xl mx-auto px-6 py-3 rounded-full">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <x-brand.logo href="{{ url('/') }}" class="navbar-logo" />

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('how-it-works') }}" class="nav-link text-sm font-medium">{{ __('How it works') }}</a>
                    <a href="{{ route('challenges.index') }}" class="nav-link text-sm font-medium">{{ __('Challenges') }}</a>
                    <a href="{{ route('community.index') }}" class="nav-link text-sm font-medium">{{ __('Community') }}</a>
                    <a href="{{ route('success-stories') }}" class="nav-link text-sm font-medium">{{ __('Success Stories') }}</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <button x-data="{
                                isDark: localStorage.getItem('mindova-theme') === 'dark',
                                init() { document.documentElement.classList.toggle('dark', this.isDark); },
                                toggle() {
                                    this.isDark = !this.isDark;
                                    localStorage.setItem('mindova-theme', this.isDark ? 'dark' : 'light');
                                    document.documentElement.classList.toggle('dark', this.isDark);
                                }
                            }" @click="toggle()" type="button"
                            class="p-2 text-ink-70 hover-ink hover-tint rounded-full transition-premium-fast"
                            :aria-label="isDark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to dark mode') }}'">
                        <x-icon name="sun" class="w-5 h-5" x-show="isDark" x-cloak />
                        <x-icon name="moon" class="w-5 h-5" x-show="!isDark" x-cloak />
                    </button>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-ink-80 hover-ink px-3 py-2">
                        {{ __('Sign In') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn-glow inline-flex items-center px-5 py-2.5 text-white rounded-full text-sm font-semibold">
                        {{ __('Get Started') }}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 rounded-full text-ink-80 hover-tint" id="mobile-menu-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden liquid-glass max-w-5xl mx-auto mt-3 rounded-3xl" id="mobile-menu">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('how-it-works') }}" class="block px-4 py-2 text-ink-80 hover-tint hover-ink rounded-lg">{{ __('How it works') }}</a>
                <a href="{{ route('challenges.index') }}" class="block px-4 py-2 text-ink-80 hover-tint hover-ink rounded-lg">{{ __('Challenges') }}</a>
                <a href="{{ route('community.index') }}" class="block px-4 py-2 text-ink-80 hover-tint hover-ink rounded-lg">{{ __('Community') }}</a>
                <a href="{{ route('success-stories') }}" class="block px-4 py-2 text-ink-80 hover-tint hover-ink rounded-lg">{{ __('Success Stories') }}</a>
                <div class="pt-3 border-t border-tint-10 space-y-2">
                    <a href="{{ route('login') }}" class="block w-full px-4 py-2 text-center text-ink-80 border border-tint-15 rounded-lg">{{ __('Sign In') }}</a>
                    <a href="{{ route('register') }}" class="block w-full px-4 py-2 text-center text-ink bg-aurora rounded-lg">{{ __('Get Started') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================================
         HERO — IDEA EVOLUTION
         =================================== -->
    <section class="hero-section pt-16">

        <!-- Dark overlay: readability tint + seamless bottom page-blend -->
        <div class="hero-cover-overlay" aria-hidden="true"></div>

        <!-- Subtle grid texture on top of video -->
        <div class="absolute inset-0 opacity-[0.035] z-[2]" aria-hidden="true">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Hero Content — centred, full-width -->
        <div class="hero-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-36 w-full">
            <div class="max-w-4xl mx-auto text-center">

                <div class="inline-flex items-center gap-2 bg-tint-10 border border-tint-20 rounded-full px-4 py-1.5 mb-6 backdrop-blur-sm">
                    <x-icon name="sparkles" class="w-3.5 h-3.5 text-primary-500" />
                    <span class="text-sm font-medium text-ink">{{ __('An AI-Powered Innovation Ecosystem') }}</span>
                </div>

                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl leading-[1.05] mb-6 text-balance">
                    <span class="headline-word text-ink">{{ __('Turn') }}</span>
                    <span class="headline-word text-ink">{{ __('Bold') }}</span>
                    <span class="headline-word text-ink">{{ __('Ideas') }}</span><br>
                    <span class="headline-word text-gradient">{{ __('Into') }}</span>
                    <span class="headline-word text-gradient" style="font-style: italic;">{{ __('Real') }}</span>
                    <span class="headline-word text-gradient">{{ __('Solutions') }}</span>
                </h1>

                <p class="hero-subheadline text-lg md:text-xl text-ink-80 mb-10 max-w-2xl mx-auto leading-relaxed">
                    {{ __('Mindova is where ambitious challenges meet the global talent, AI-driven structure, and momentum to become real, validated outcomes — not just ideas left on a whiteboard.') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-16 justify-center">
                    <a href="{{ route('register') }}" class="hero-cta btn-magnetic inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold">
                        {{ __('Get Started Free') }}
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </a>
                    <a href="#journey" class="hero-cta-secondary btn-outline-white inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold">
                        {{ __('See How It Evolves') }}
                    </a>
                </div>

                <!-- Idea Evolution Stepper -->
                <div class="flex items-center gap-2 sm:gap-4 flex-wrap justify-center">
                    <div class="stat-item flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-tint-10 border border-tint-20 flex items-center justify-center animate-glow">
                            <x-icon name="lightbulb" class="w-5 h-5 text-primary-500" />
                        </div>
                        <span class="text-sm font-medium text-ink-80">{{ __('Idea') }}</span>
                    </div>
                    <x-icon name="arrow-right" class="w-4 h-4 text-ink-30 flex-shrink-0" />
                    <div class="stat-item flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-tint-10 border border-tint-20 flex items-center justify-center animate-glow" style="animation-delay: 0.6s;">
                            <x-icon name="layers" class="w-5 h-5 text-primary-500" />
                        </div>
                        <span class="text-sm font-medium text-ink-80">{{ __('AI Analysis') }}</span>
                    </div>
                    <x-icon name="arrow-right" class="w-4 h-4 text-ink-30 flex-shrink-0" />
                    <div class="stat-item flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-tint-10 border border-tint-20 flex items-center justify-center animate-glow" style="animation-delay: 1.2s;">
                            <x-icon name="users" class="w-5 h-5 text-primary-500" />
                        </div>
                        <span class="text-sm font-medium text-ink-80">{{ __('Collaboration') }}</span>
                    </div>
                    <x-icon name="arrow-right" class="w-4 h-4 text-ink-30 flex-shrink-0" />
                    <div class="stat-item flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-tint-10 border border-tint-20 flex items-center justify-center animate-glow" style="animation-delay: 1.8s;">
                            <x-icon name="rocket" class="w-5 h-5 text-primary-500" />
                        </div>
                        <span class="text-sm font-medium text-ink-80">{{ __('Real Solution') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================================
         LIVE ACTIVITY TICKER
         =================================== -->
    <div class="activity-ticker-section">
        <div class="activity-ticker-wrap">
            <span class="ticker-label">
                <span class="ticker-live-dot"></span>
                {{ __('Live') }}
            </span>
            <div class="ticker-scroll-area">
                <div class="ticker-inner">
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Ahmed from Saudi Arabia just matched with a fintech startup') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('New challenge posted: AI-powered supply chain optimization') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Maria from Brazil earned a verified contribution certificate') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('University of Cairo submitted 3 research challenges this week') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Challenge solved: Healthcare data privacy framework — 14 days') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('James from Kenya matched to a renewable energy challenge') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Startup from Berlin posted a UX research challenge') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Lena from Germany completed her 5th verified contribution') }}</span>
                    <!-- Duplicate for seamless loop -->
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Ahmed from Saudi Arabia just matched with a fintech startup') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('New challenge posted: AI-powered supply chain optimization') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Maria from Brazil earned a verified contribution certificate') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('University of Cairo submitted 3 research challenges this week') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Challenge solved: Healthcare data privacy framework — 14 days') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('James from Kenya matched to a renewable energy challenge') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Startup from Berlin posted a UX research challenge') }}</span>
                    <span class="ticker-item"><span class="ticker-dot"></span>{{ __('Lena from Germany completed her 5th verified contribution') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================================
         INNOVATION SOURCES — where challenges come from
         =================================== -->
    <section class="py-24" id="sources">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                    {{ __('Where It Begins') }}
                </span>
                <h2 class="font-display text-4xl md:text-5xl text-ink mb-4">
                    {{ __('Real Innovation Has') }} <em>{{ __('Many Sources') }}</em>
                </h2>
                <p class="text-lg text-ink-60">
                    {{ __('From classrooms to construction sites to packed conference halls — every breakthrough challenge on Mindova starts somewhere real.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 stagger-children">
                <a href="{{ route('community.index') }}" class="source-card reveal">
                    <img src="{{ asset('images/landing/source-universities.jpg') }}" alt="{{ __('Universities') }}" loading="lazy">
                    <div class="source-card-overlay"></div>
                    <div class="source-card-icon">
                        <x-icon name="building" class="w-5 h-5" />
                    </div>
                    <div class="source-card-content">
                        <h3>{{ __('Universities') }}</h3>
                        <p>{{ __('Collaborative research fuels innovation and discovery.') }}</p>
                    </div>
                </a>

                <a href="{{ route('community.index') }}" class="source-card reveal">
                    <img src="{{ asset('images/landing/source-engineers.jpg') }}" alt="{{ __('Engineers') }}" loading="lazy">
                    <div class="source-card-overlay"></div>
                    <div class="source-card-icon">
                        <x-icon name="zap" class="w-5 h-5" />
                    </div>
                    <div class="source-card-content">
                        <h3>{{ __('Engineers') }}</h3>
                        <p>{{ __('Practical solutions are born from real-world challenges.') }}</p>
                    </div>
                </a>

                <a href="{{ route('community.index') }}" class="source-card reveal">
                    <img src="{{ asset('images/landing/source-conferences.jpg') }}" alt="{{ __('Conferences') }}" loading="lazy">
                    <div class="source-card-overlay"></div>
                    <div class="source-card-icon">
                        <x-icon name="users" class="w-5 h-5" />
                    </div>
                    <div class="source-card-content">
                        <h3>{{ __('Conferences') }}</h3>
                        <p>{{ __('Networks grow through shared ideas and collaboration.') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- ===================================
         AUDIENCE STRIP — who Mindova is for
         =================================== -->
    <section class="py-16 border-b border-tint-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-ink-40 text-sm font-semibold uppercase tracking-wider mb-10 reveal">
                {{ __('Built for the people shaping what\'s next') }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 stagger-children">
                <div class="liquid-glass text-center p-5 rounded-2xl">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-tint-10 flex items-center justify-center">
                        <x-icon name="building" class="w-6 h-6 text-primary-500" />
                    </div>
                    <p class="font-semibold text-ink text-sm">{{ __('Companies & Founders') }}</p>
                </div>
                <div class="liquid-glass text-center p-5 rounded-2xl">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-tint-10 flex items-center justify-center">
                        <x-icon name="lightbulb" class="w-6 h-6 text-primary-500" />
                    </div>
                    <p class="font-semibold text-ink text-sm">{{ __('Researchers & Innovators') }}</p>
                </div>
                <div class="liquid-glass text-center p-5 rounded-2xl">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-tint-10 flex items-center justify-center">
                        <x-icon name="users" class="w-6 h-6 text-primary-500" />
                    </div>
                    <p class="font-semibold text-ink text-sm">{{ __('Skilled Contributors') }}</p>
                </div>
                <div class="liquid-glass text-center p-5 rounded-2xl">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-tint-10 flex items-center justify-center">
                        <x-icon name="target" class="w-6 h-6 text-primary-500" />
                    </div>
                    <p class="font-semibold text-ink text-sm">{{ __('Opportunity Seekers') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         THE IDEA EVOLUTION JOURNEY
         =================================== -->
    <section class="py-24" id="journey">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                    {{ __('The Process') }}
                </span>
                <h2 class="font-display text-4xl md:text-5xl text-ink mb-4">
                    {{ __('The Idea Evolution') }} <em>{{ __('Journey') }}</em>
                </h2>
                <p class="text-lg text-ink-60">
                    {{ __('From a rough challenge to a validated, real-world solution — here\'s what happens inside Mindova.') }}
                </p>
            </div>

            <!-- Journey Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stage 1 -->
                <div class="step-card liquid-glass reveal">
                    <span class="step-number">01</span>
                    <div class="step-icon">
                        <x-icon name="lightbulb" class="w-7 h-7 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-3">{{ __('Submit Your Challenge') }}</h3>
                    <p class="text-ink-60 leading-relaxed text-sm">
                        {{ __('Define the problem that matters. Our AI reads it instantly and frames it for real progress, not guesswork.') }}
                    </p>
                </div>

                <!-- Stage 2 -->
                <div class="step-card liquid-glass reveal">
                    <span class="step-number">02</span>
                    <div class="step-icon">
                        <x-icon name="layers" class="w-7 h-7 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-3">{{ __('AI Refines & Scores It') }}</h3>
                    <p class="text-ink-60 leading-relaxed text-sm">
                        {{ __('Mindova\'s AI evaluates complexity and decomposes the challenge into clear, achievable tasks.') }}
                    </p>
                </div>

                <!-- Stage 3 -->
                <div class="step-card liquid-glass reveal">
                    <span class="step-number">03</span>
                    <div class="step-icon">
                        <x-icon name="target" class="w-7 h-7 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-3">{{ __('Matched With Global Talent') }}</h3>
                    <p class="text-ink-60 leading-relaxed text-sm">
                        {{ __('Verified contributors are matched to each task by fit and skill — not by who happened to apply first.') }}
                    </p>
                </div>

                <!-- Stage 4 -->
                <div class="step-card liquid-glass reveal">
                    <span class="step-number">04</span>
                    <div class="step-icon">
                        <x-icon name="rocket" class="w-7 h-7 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-3">{{ __('Tracked to a Real Solution') }}</h3>
                    <p class="text-ink-60 leading-relaxed text-sm">
                        {{ __('Work happens in a secure, NDA-protected space with progress tracking until a verified solution ships.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         FEATURES SECTION
         =================================== -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                    {{ __('Platform') }}
                </span>
                <h2 class="font-display text-4xl md:text-5xl text-ink mb-4">
                    {{ __('Everything an Idea Needs to Become') }} <em>{{ __('Real') }}</em>
                </h2>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-children">
                <div class="feature-card liquid-glass featured-card">
                    <div class="feature-icon-wrapper">
                        <x-icon name="zap" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('AI-Powered Matching') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('Intelligent algorithms connect your challenge with the right expertise in minutes, not weeks of searching.') }}
                    </p>
                </div>

                <div class="feature-card liquid-glass">
                    <div class="feature-icon-wrapper">
                        <x-icon name="globe" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('Global Talent Network') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('A growing, borderless community of contributors across disciplines — opportunity isn\'t limited by geography.') }}
                    </p>
                </div>

                <div class="feature-card liquid-glass">
                    <div class="feature-icon-wrapper">
                        <x-icon name="shield" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('NDA-Protected Collaboration') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('Every collaboration is protected by digital NDAs by default, so sensitive ideas stay confidential.') }}
                    </p>
                </div>

                <div class="feature-card liquid-glass">
                    <div class="feature-icon-wrapper">
                        <x-icon name="trending-up" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('Real-Time Progress Tracking') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('Clear dashboards show exactly where every challenge stands, from analysis to a finished solution.') }}
                    </p>
                </div>

                <div class="feature-card liquid-glass">
                    <div class="feature-icon-wrapper">
                        <x-icon name="layers" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('Structured Task Decomposition') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('Big, vague challenges become specific, ownable tasks — so progress is always concrete, never stalled.') }}
                    </p>
                </div>

                <div class="feature-card liquid-glass">
                    <div class="feature-icon-wrapper">
                        <x-icon name="star" class="w-6 h-6 text-primary-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-ink mb-2">{{ __('Verified Certificates & Reputation') }}</h3>
                    <p class="text-ink-60 text-sm leading-relaxed">
                        {{ __('Completed work earns verifiable certificates and reputation that contributors can carry forward.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         TESTIMONIALS
         =================================== -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                    {{ __('Real Stories') }}
                </span>
                <h2 class="font-display text-4xl md:text-5xl text-ink mb-4">
                    {{ __('Trusted by') }} <em>{{ __('Builders Worldwide') }}</em>
                </h2>
                <p class="text-lg text-ink-60">
                    {{ __('From first-time founders to research institutions — here\'s what they\'re saying.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 stagger-children">
                <!-- Testimonial 1 -->
                <div class="testimonial-card liquid-glass reveal">
                    <div class="testimonial-stars">
                        @for($i = 0; $i < 5; $i++)
                        <svg viewBox="0 0 20 20" fill="#f59e0b"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="testimonial-quote">"{{ __('Mindova matched our logistics challenge with 3 verified contributors in under 48 hours. The AI structuring turned a vague problem into a clear, actionable roadmap. We shipped in 3 weeks instead of 6 months.') }}"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background: linear-gradient(135deg, #0d9488, #7c3aed);">S</div>
                        <div>
                            <div class="testimonial-name">Sarah Chen</div>
                            <div class="testimonial-role">{{ __('CTO, Logify Labs') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card liquid-glass reveal">
                    <div class="testimonial-stars">
                        @for($i = 0; $i < 5; $i++)
                        <svg viewBox="0 0 20 20" fill="#f59e0b"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="testimonial-quote">"{{ __('I\'ve contributed to four challenges on Mindova and the quality of problems is unlike anything else. Each one was real, structured, and matched my exact skill set. The certificate I earned opened doors at two companies.') }}"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background: linear-gradient(135deg, #4f46e5, #0ea5e9);">O</div>
                        <div>
                            <div class="testimonial-name">Omar Al-Rashid</div>
                            <div class="testimonial-role">{{ __('ML Engineer, Dubai') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card liquid-glass reveal">
                    <div class="testimonial-stars">
                        @for($i = 0; $i < 5; $i++)
                        <svg viewBox="0 0 20 20" fill="#f59e0b"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="testimonial-quote">"{{ __('We posted a research challenge on sustainable materials and had global experts engaging within hours — something that would have taken a year of conference networking. Mindova is what academic-industry collaboration should look like.') }}"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar" style="background: linear-gradient(135deg, #db2777, #ea580c);">E</div>
                        <div>
                            <div class="testimonial-name">Dr. Elena Vasquez</div>
                            <div class="testimonial-role">{{ __('Research Lead, TU Berlin') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         VISION SECTION
         =================================== -->
    <section class="py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 reveal">
            <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                {{ __('Our Vision') }}
            </span>
            <h2 class="font-display text-4xl md:text-5xl text-ink mb-6 text-balance">
                {{ __('Great Ideas Shouldn\'t Wait for') }} <em>{{ __('Permission') }}</em>
            </h2>
            <p class="text-lg text-ink-60 max-w-3xl mx-auto leading-relaxed">
                {{ __('Too many real problems stall for lack of the right people, structure, or momentum. Mindova exists to close that gap — pairing AI-driven structure with a global community so good ideas get the chance to become real solutions.') }}
            </p>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
            <div class="liquid-glass rounded-2xl p-8 reveal-left">
                <div class="w-12 h-12 rounded-xl bg-aurora flex items-center justify-center mb-5 glow-primary-sm">
                    <x-icon name="building" class="w-6 h-6 text-ink" />
                </div>
                <h3 class="text-xl font-bold text-ink mb-3">{{ __('For Companies & Founders') }}</h3>
                <ul class="space-y-2.5 mb-6 text-sm text-ink-60">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Turn a vague problem into a structured, solvable challenge') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Get matched with verified talent, not a pile of applications') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Track real progress with NDA protection built in') }}</li>
                </ul>
                <a href="{{ route('register') }}?type=company" class="inline-flex items-center gap-2 text-primary-500 font-semibold text-sm hover:gap-3 transition-all">
                    {{ __('Submit a Challenge') }} <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>

            <div class="liquid-glass rounded-2xl p-8 reveal-right">
                <div class="w-12 h-12 rounded-xl bg-aurora flex items-center justify-center mb-5 glow-primary-sm">
                    <x-icon name="users" class="w-6 h-6 text-ink" />
                </div>
                <h3 class="text-xl font-bold text-ink mb-3">{{ __('For Contributors & Experts') }}</h3>
                <ul class="space-y-2.5 mb-6 text-sm text-ink-60">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Get matched to tasks that genuinely fit your skills') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Work on real challenges from real companies, not busywork') }}</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400 mt-0.5 flex-shrink-0" /> {{ __('Build a verified reputation and certificates that travel with you') }}</li>
                </ul>
                <a href="{{ route('register') }}?type=volunteer" class="inline-flex items-center gap-2 text-primary-500 font-semibold text-sm hover:gap-3 transition-all">
                    {{ __('Start Contributing') }} <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>

    <!-- ===================================
         WHY MINDOVA
         =================================== -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <span class="inline-block px-4 py-1.5 bg-tint-10 text-primary-500 rounded-full text-sm font-semibold mb-4">
                    {{ __('Why Mindova') }}
                </span>
                <h2 class="font-display text-4xl md:text-5xl text-ink mb-4">
                    {{ __('Built Differently, On') }} <em>{{ __('Purpose') }}</em>
                </h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 stagger-children">
                <div class="counter-card liquid-glass">
                    <span class="counter-value" data-target="2400" data-suffix="+">0</span>
                    <div class="counter-label">{{ __('Challenges Posted') }}</div>
                    <div class="counter-sublabel">{{ __('and growing every week') }}</div>
                </div>
                <div class="counter-card liquid-glass">
                    <span class="counter-value" data-target="180" data-suffix="+">0</span>
                    <div class="counter-label">{{ __('Countries Represented') }}</div>
                    <div class="counter-sublabel">{{ __('truly borderless talent') }}</div>
                </div>
                <div class="counter-card liquid-glass">
                    <span class="counter-value" data-target="94" data-suffix="%">0</span>
                    <div class="counter-label">{{ __('Match Success Rate') }}</div>
                    <div class="counter-sublabel">{{ __('AI-matched, not left to chance') }}</div>
                </div>
                <div class="counter-card liquid-glass">
                    <span class="counter-value" data-target="48" data-suffix="h">0</span>
                    <div class="counter-label">{{ __('Avg. Time to First Match') }}</div>
                    <div class="counter-sublabel">{{ __('from submission to team') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         CTA SECTION
         =================================== -->
    <section class="cta-section">
        <div class="cta-gradient-bg"></div>
        <div class="rising-particle" style="left: 8%; animation-delay: 0s;"></div>
        <div class="rising-particle" style="left: 22%; animation-delay: 2s;"></div>
        <div class="rising-particle" style="left: 38%; animation-delay: 4s;"></div>
        <div class="rising-particle" style="left: 55%; animation-delay: 1s;"></div>
        <div class="rising-particle" style="left: 71%; animation-delay: 3s;"></div>
        <div class="rising-particle" style="left: 88%; animation-delay: 5s;"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl md:text-5xl text-ink mb-4 text-balance">
                {{ __('Your Next Breakthrough Starts') }} <em>{{ __('Here') }}</em>
            </h2>
            <p class="text-lg text-ink-90 mb-8 max-w-2xl mx-auto">
                {{ __('Join a growing community of companies and contributors turning real challenges into real solutions.') }}
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('register') }}" class="btn-magnetic inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold">
                    {{ __('Get Started Free') }}
                    <x-icon name="arrow-right" class="w-5 h-5" />
                </a>
                <a href="{{ route('contact') }}" class="btn-outline-white inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold">
                    {{ __('Talk to Us') }}
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center gap-6 text-ink-70 text-sm">
                <span class="flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5" />
                    {{ __('Free to join') }}
                </span>
                <span class="flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5" />
                    {{ __('NDA-protected by default') }}
                </span>
                <span class="flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5" />
                    {{ __('AI-matched in minutes') }}
                </span>
            </div>
        </div>
    </section>

    <!-- ===================================
         FOOTER
         =================================== -->
    <footer class="landing-footer pt-16 pb-8">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Column -->
                <div class="col-span-2">
                    <x-brand.logo href="{{ url('/') }}" variant="white" class="mb-4" />
                    <p class="text-ink-50 mb-6 max-w-xs text-sm">
                        {{ __('The AI-powered ecosystem where real challenges evolve into real solutions — built by a global community of innovators.') }}
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ $socialFacebookUrl ?: '#' }}" class="social-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="{{ $socialTwitterUrl ?: '#' }}" class="social-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="{{ $socialLinkedinUrl ?: '#' }}" class="social-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Product Links -->
                <div>
                    <h4 class="text-ink font-semibold mb-4 text-sm">{{ __('Product') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('features') }}" class="footer-link text-sm">{{ __('Features') }}</a></li>
                        <li><a href="{{ route('pricing') }}" class="footer-link text-sm">{{ __('Pricing') }}</a></li>
                        <li><a href="{{ route('security') }}" class="footer-link text-sm">{{ __('Security') }}</a></li>
                        <li><a href="{{ route('integrations') }}" class="footer-link text-sm">{{ __('Integrations') }}</a></li>
                        <li><a href="{{ route('changelog') }}" class="footer-link text-sm">{{ __('Changelog') }}</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="text-ink font-semibold mb-4 text-sm">{{ __('Company') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="footer-link text-sm">{{ __('About') }}</a></li>
                        <li><a href="{{ route('careers') }}" class="footer-link text-sm">{{ __('Careers') }}</a></li>
                        <li><a href="{{ route('press') }}" class="footer-link text-sm">{{ __('Press') }}</a></li>
                        <li><a href="{{ route('partners') }}" class="footer-link text-sm">{{ __('Partners') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link text-sm">{{ __('Contact') }}</a></li>
                    </ul>
                </div>

                <!-- Resources Links -->
                <div>
                    <h4 class="text-ink font-semibold mb-4 text-sm">{{ __('Resources') }}</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog') }}" class="footer-link text-sm">{{ __('Blog') }}</a></li>
                        <li><a href="{{ route('documentation') }}" class="footer-link text-sm">{{ __('Documentation') }}</a></li>
                        <li><a href="{{ route('help') }}" class="footer-link text-sm">{{ __('Help Center') }}</a></li>
                        <li><a href="{{ route('api-docs') }}" class="footer-link text-sm">{{ __('API Reference') }}</a></li>
                        <li><a href="{{ route('community.index') }}" class="footer-link text-sm">{{ __('Community') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="border-t border-tint-10 pt-8 mb-8">
                <div class="max-w-md mx-auto text-center">
                    <h4 class="text-ink font-semibold mb-2">{{ __('Stay Updated') }}</h4>
                    <p class="text-ink-50 text-sm mb-4">{{ __('Get the latest on AI-powered innovation') }}</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="{{ __('Enter your email') }}" class="newsletter-input flex-1 text-sm">
                        <button type="submit" class="btn-glow px-4 py-2 text-ink rounded-lg text-sm font-medium">
                            {{ __('Subscribe') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-tint-10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-ink-40 text-sm">
                    &copy; {{ date('Y') }} {{ $siteName ?? 'Mindova' }}. {{ __('All rights reserved.') }}
                </p>
                <div class="flex gap-6">
                    <a href="{{ route('terms') }}" class="footer-link text-sm">{{ __('Terms') }}</a>
                    <a href="{{ route('privacy') }}" class="footer-link text-sm">{{ __('Privacy') }}</a>
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

        // Navbar scrolled state
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });

        // Animated counters
        const counterEls = document.querySelectorAll('.counter-value');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting || entry.target.dataset.animated) return;
                entry.target.dataset.animated = '1';
                const el = entry.target;
                const target = parseInt(el.dataset.target, 10);
                const suffix = el.dataset.suffix || '';
                const duration = 1800;
                const steps = 60;
                let step = 0;
                const tick = setInterval(() => {
                    step++;
                    const progress = step / steps;
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const current = Math.round(eased * target);
                    el.textContent = current.toLocaleString() + suffix;
                    if (step >= steps) {
                        clearInterval(tick);
                        el.textContent = target.toLocaleString() + suffix;
                        el.closest('.counter-card')?.classList.add('is-counted');
                    }
                }, duration / steps);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.4 });
        counterEls.forEach(el => counterObserver.observe(el));

        // Scroll-reveal animations
        const revealTargets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-children');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealTargets.forEach(el => revealObserver.observe(el));
    </script>

</body>
</html>
