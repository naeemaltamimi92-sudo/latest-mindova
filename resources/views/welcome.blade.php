<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mindova') }} - {{ __('AI-Powered Challenge Platform') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Unified Navigation -->
    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="floating-element w-96 h-96 bg-primary-500 absolute -top-48 -left-48"></div>
            <div class="floating-element w-[32rem] h-[32rem] bg-primary-400 absolute top-1/4 -right-64"></div>
            <div class="floating-element w-80 h-80 bg-secondary-500 absolute bottom-0 left-1/3"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Logo Display -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="w-32 h-32 md:w-40 md:h-40">
                </div>

                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-white/40 shadow-lg mb-8">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-sm font-semibold text-gray-700">{{ __('AI-Powered Collaboration Platform') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-6xl md:text-7xl font-black text-gray-900 mb-6 leading-tight">
                    {{ __('Transform Challenges') }}<br>
                    {{ __('Into') }} <span class="text-gradient">{{ __('Innovation') }}</span>
                </h1>

                <!-- Subheading -->
                <p class="text-xl md:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    {{ __('Connect talented contributors with real-world challenges. Our AI matches skills, creates teams, and drives meaningful impact.') }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                    <x-ui.button as="a" href="{{ route('register') }}" variant="primary" size="xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                        {{ __('Join as Contributor') }}
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="xl">
                        {{ __('Post Your Challenge') }}
                    </x-ui.button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gradient mb-2">1000+</div>
                        <div class="text-sm text-gray-600">{{ __('Active Contributors') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gradient mb-2">500+</div>
                        <div class="text-sm text-gray-600">{{ __('Challenges Solved') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gradient mb-2">95%</div>
                        <div class="text-sm text-gray-600">{{ __('Success Rate') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Why Choose Mindova?') }}</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">{{ __('Powered by AI, driven by human potential') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-premium group">
                    <div class="icon-badge bg-primary-500 mb-6">
                        <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('AI-Powered Matching') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('Our intelligent system analyzes skills, experience, and availability to create perfect team matches for every challenge.') }}</p>
                </div>

                <!-- Feature 2 -->
                <div class="card-premium group">
                    <div class="icon-badge bg-secondary-500 mb-6">
                        <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Team Collaboration') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('Built-in tools for seamless teamwork, real-time communication, and progress tracking throughout your journey.') }}</p>
                </div>

                <!-- Feature 3 -->
                <div class="card-premium group">
                    <div class="icon-badge bg-primary-400 mb-6">
                        <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Secure & Trusted') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('Enterprise-grade security, NDA protection, and quality assurance for every project and collaboration.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Soft Gradient Background -->
        <div class="absolute inset-0 bg-gray-50"></div>

        <!-- Decorative Circles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-20">
            <div class="floating-element w-72 h-72 bg-primary-400 absolute top-0 left-1/4"></div>
            <div class="floating-element w-64 h-64 bg-secondary-300 absolute bottom-0 right-1/4"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('How It Works') }}</h2>
                <p class="text-xl text-gray-600">{{ __('Simple, powerful, effective') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-primary-500 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Submit Challenge') }}</h3>
                    <p class="text-gray-600">{{ __('Companies post real-world challenges they need solved') }}</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-secondary-500 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('AI Analysis') }}</h3>
                    <p class="text-gray-600">{{ __('Our AI breaks down challenges into tasks and matches skills') }}</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-primary-400 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Team Formation') }}</h3>
                    <p class="text-gray-600">{{ __('Volunteers are matched and teams are automatically formed') }}</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-20 h-20 rounded-full bg-secondary-300 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6 shadow-lg">4</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Deliver Solutions') }}</h3>
                    <p class="text-gray-600">{{ __('Teams collaborate and deliver high-quality solutions') }}</p>
                </div>
            </div>

            <div class="text-center mt-12">
                <x-ui.button as="a" href="{{ route('how-it-works') }}" variant="primary">{{ __('Learn More') }}</x-ui.button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-primary-500"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-5xl font-bold text-white mb-6">{{ __('Ready to Make an Impact?') }}</h2>
            <p class="text-xl text-white/90 mb-10">{{ __('Join thousands of volunteers and companies creating meaningful change') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="xl">
                    {{ __('Get Started Free') }}
                </x-ui.button>
                <x-ui.button as="a" href="{{ route('contact') }}" variant="outline" size="xl">
                    {{ __('Contact Sales') }}
                </x-ui.button>
            </div>
        </div>
    </section>

    <!-- Premium Footer -->
    <footer class="relative overflow-hidden">
        <!-- Gradient Background -->
        <div class="absolute inset-0 bg-gray-50"></div>

        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-30">
            <div class="floating-element w-64 h-64 bg-primary-500 absolute -bottom-32 -left-32"></div>
            <div class="floating-element w-96 h-96 bg-primary-400 absolute bottom-0 right-0"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 flex items-center justify-center">
                            <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="w-12 h-12">
                        </div>
                        <span class="text-2xl font-bold text-gray-900">Mindova</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4">{{ __('AI-powered platform connecting talented contributors with real-world challenges') }}</p>
                    <!-- Social Links -->
                    <div class="flex items-center space-x-3">
                        <a href="#" class="w-10 h-10 rounded-lg bg-white shadow-md hover:shadow-lg flex items-center justify-center text-gray-600 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white shadow-md hover:shadow-lg flex items-center justify-center text-gray-600 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white shadow-md hover:shadow-lg flex items-center justify-center text-gray-600 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-lg bg-white shadow-md hover:shadow-lg flex items-center justify-center text-gray-600 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Platform -->
                <div>
                    <h3 class="text-gray-900 font-bold text-lg mb-4">{{ __('Platform') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('how-it-works') }}" class="text-gray-600 hover:text-primary-600">{{ __('How It Works') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-primary-600">{{ __('For Contributors') }}</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-primary-600">{{ __('For Companies') }}</a></li>
                        <li><a href="{{ route('success-stories') }}" class="text-gray-600 hover:text-primary-600">{{ __('Success Stories') }}</a></li>
                    </ul>
                </div>

                <!-- Resources -->
                <div>
                    <h3 class="text-gray-900 font-bold text-lg mb-4">{{ __('Resources') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('help') }}" class="text-gray-600 hover:text-primary-600">{{ __('Help Center') }}</a></li>
                        <li><a href="{{ route('guidelines') }}" class="text-gray-600 hover:text-primary-600">{{ __('Community Guidelines') }}</a></li>
                        <li><a href="{{ route('api-docs') }}" class="text-gray-600 hover:text-primary-600">{{ __('API Documentation') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-600 hover:text-primary-600">{{ __('Blog') }}</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-gray-900 font-bold text-lg mb-4">{{ __('Company') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-gray-600 hover:text-primary-600">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-600 hover:text-primary-600">{{ __('Contact') }}</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-gray-600 hover:text-primary-600">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="{{ route('terms') }}" class="text-gray-600 hover:text-primary-600">{{ __('Terms of Service') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Mindova. {{ __('All Rights Reserved') }}. {{ __('Made with') }} <span class="text-pink-500">❤️</span> {{ __('for innovation') }}.</p>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('privacy') }}" class="text-sm text-gray-500 hover:text-primary-600">{{ __('Privacy') }}</a>
                        <a href="{{ route('terms') }}" class="text-sm text-gray-500 hover:text-primary-600">{{ __('Terms') }}</a>
                        <a href="{{ route('contact') }}" class="text-sm text-gray-500 hover:text-primary-600">{{ __('Contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
