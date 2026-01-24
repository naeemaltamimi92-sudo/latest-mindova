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
    <section class="relative min-h-screen pt-32 pb-20 overflow-hidden bg-dark-hero">
        <!-- Interactive Map Background -->
        <div class="interactive-map-container" id="map-container">
            <!-- Dots will be injected here via JS for variety -->
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
            <div class="w-full text-center">
                <!-- Main Heading -->
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight max-w-4xl mx-auto">
                    <span class="text-white">Collaborative problem-solving</span> <br>
                    <span class="text-gradient-purple">powered by AI</span>
                </h1>

                <!-- Subheading -->
                <p class="text-lg md:text-xl text-white mb-12 max-w-2xl mx-auto leading-relaxed">
                    {{ __('Harness the collective expertise of a global community to tackle challenges and develop innovative solutions.') }}
                </p>

                <!-- CTA Search/Input Area -->
                <div class="search-input-container mb-16">
                    <input type="text" placeholder="{{ __('Describe your problem...') }}" class="w-full">
                    <a href="{{ route('register') }}" class="btn-primary-gradient whitespace-nowrap">
                        {{ __('Get started') }}
                    </a>
                </div>

                <!-- Stats Inline -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
                    <div class="text-center group">
                        <div class="stat-value text-white group-hover:text-primary-400 transition-colors">2486</div>
                        <div class="stat-label">{{ __('Active projects') }}</div>
                    </div>
                    <div class="text-center group border-x border-white/10 px-4">
                        <div class="stat-value text-white group-hover:text-primary-400 transition-colors">4953</div>
                        <div class="stat-label">{{ __('Problem-solvers') }}</div>
                    </div>
                    <div class="text-center group">
                        <div class="stat-value text-white group-hover:text-primary-400 transition-colors">86</div>
                        <div class="stat-label">{{ __('Countries represented') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 dark-section relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-white mb-2">{{ __('How it works') }}</h2>
                <div class="w-20 h-1 bg-primary-600 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="dark-card group">
                    <div class="icon-gradient text-primary-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 group-hover:text-primary-400 transition-colors">{{ __('Submit your challenge') }}</h3>
                    <p class="text-gray-300 leading-relaxed">{{ __('Define the problem you need to solve or an idea you would like to explore.') }}</p>
                </div>

                <!-- Step 2 -->
                <div class="dark-card group">
                    <div class="icon-gradient text-indigo-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 group-hover:text-indigo-400 transition-colors">{{ __('Team up with experts') }}</h3>
                    <p class="text-gray-300 leading-relaxed">{{ __('Collaborate with skilled professionals from diverse fields handpicked by AI.') }}</p>
                </div>

                <!-- Step 3 -->
                <div class="dark-card group">
                    <div class="icon-gradient text-purple-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4 group-hover:text-purple-400 transition-colors">{{ __('Solve together') }}</h3>
                    <p class="text-gray-300 leading-relaxed">{{ __('Work as a team to create effective, innovative solutions.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-black relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6" style="color: white !important;">{{ __('Why Mindova?') }}</h2>
                <p class="text-xl text-gray-200 max-w-2xl mx-auto" style="color: #e5e7eb !important;">{{ __('The platform designed for modern problem-solving.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors group">
                    <div class="w-12 h-12 rounded-lg bg-primary-600/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3" style="color: white !important;">{{ __('AI-Powered Matching') }}</h3>
                    <p class="text-gray-200" style="color: #e5e7eb !important;">{{ __('Our algorithms connect you with the right experts and opportunities instantly.') }}</p>
                </div>
                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors group">
                    <div class="w-12 h-12 rounded-lg bg-primary-600/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3" style="color: white !important;">{{ __('Global Community') }}</h3>
                    <p class="text-gray-200" style="color: #e5e7eb !important;">{{ __('Join a diverse network of innovators from over 80 countries.') }}</p>
                </div>
                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-500/50 transition-colors group">
                    <div class="w-12 h-12 rounded-lg bg-primary-600/20 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3" style="color: white !important;">{{ __('Secure & Private') }}</h3>
                    <p class="text-gray-200" style="color: #e5e7eb !important;">{{ __('Your IP is protected with built-in NDAs and secure collaboration tools.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-dark-hero relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-16 text-center" style="color: white !important;">{{ __('Community Voices') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="p-8 bg-white/5 rounded-2xl border border-white/5 relative hover:bg-white/10 transition-colors">
                    <div class="text-primary-500 text-6xl absolute top-4 left-4 opacity-20 font-serif">"</div>
                    <p class="text-lg text-gray-200 italic mb-6 relative z-10 pl-6" style="color: #e5e7eb !important;">{{ __('Mindova helped us solve a critical engineering challenge in weeks that had stalled us for months. The quality of experts is unmatched.') }}</p>
                    <div class="flex items-center gap-4 pl-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">JD</div>
                        <div>
                            <div class="text-white font-bold" style="color: white !important;">John Doe</div>
                            <div class="text-primary-400 text-sm" style="color: #818cf8 !important;">CTO, TechCorp</div>
                        </div>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="p-8 bg-white/5 rounded-2xl border border-white/5 relative hover:bg-white/10 transition-colors">
                    <div class="text-primary-500 text-6xl absolute top-4 left-4 opacity-20 font-serif">"</div>
                    <p class="text-lg text-gray-200 italic mb-6 relative z-10 pl-6" style="color: #e5e7eb !important;">{{ __('As a freelancer, I\'ve found the most interesting and rewarding projects here. The collaboration tools make working remotely a breeze.') }}</p>
                    <div class="flex items-center gap-4 pl-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">SS</div>
                        <div>
                            <div class="text-white font-bold" style="color: white !important;">Sarah Smith</div>
                            <div class="text-primary-400 text-sm" style="color: #818cf8 !important;">Senior Engineer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Network Section -->
    <section class="relative py-24 overflow-hidden bg-black flex flex-col items-center justify-center min-h-[600px] dark-section">
        <!-- Interactive Map Background Overlay (dots) -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="relative w-full max-w-7xl opacity-80" style="height: 600px; min-height: 400px;" id="global-map-container">
                <!-- Dots injected via JS -->
            </div>
        </div>
        
        <div class="relative z-30 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
             <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight" style="color: white !important;">
                {{ __('Join a global network of problem-solvers') }}
            </h2>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden bg-black">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-8 leading-tight" style="color: white !important;">{{ __('Ready to start solving?') }}</h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto" style="color: #d1d5db !important;">{{ __('Join thousands of companies and experts building the future together.') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-lg shadow-primary-600/25 flex items-center justify-center gap-2">
                    {{ __('Get Started Now') }}
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
                <a href="{{ route('how-it-works') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold text-lg transition-all backdrop-blur-sm border border-white/10 flex items-center justify-center">
                    {{ __('Learn More') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Dark Footer -->
    <footer class="relative overflow-hidden bg-black py-20 border-t border-white/5">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-primary-600 rounded flex items-center justify-center">
                            <span class="text-white font-bold text-sm">M</span>
                        </div>
                        <span class="text-xl font-black tracking-widest uppercase text-white" style="color: white !important;">MINDOVA</span>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        {{ __('A global ecosystem for collaborative innovation and problem-solving.') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary-600 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-primary-600 hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Footer Columns -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">{{ __('Platform') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('How it works') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Challenges') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Solutions') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">{{ __('Community') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Experts') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Guidelines') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Blog') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">{{ __('Company') }}</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('About') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Contact') }}</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-white transition-colors">{{ __('Privacy') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} MINDOVA. {{ __('All rights reserved.') }}</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-white transition-colors">{{ __('Terms') }}</a>
                    <a href="#" class="hover:text-white transition-colors">{{ __('Privacy') }}</a>
                    <a href="#" class="hover:text-white transition-colors">{{ __('Cookies') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ASCII World Map Grid for dense dot generation
            // 1 = simplified land mass, 0 = ocean
            // 60 rows x 120 cols approx
            const mapGrid = [
                "                           ..                                                                                   ",
                "                       ......                                                                                   ",
                "                     .......                                                                                    ",
                "                  .........  .....                       ......   .........                ...........          ",
                "               .............. ...                       ....... ............            ...............         ",
                "              ...................                      ......................           ................        ",
                "             .....................                    ........................          .................       ",
                "            .......................                  ..........................        ...................      ",
                "           ........................                  ..........................        ....................     ",
                "           .........................                ............................       .....................    ",
                "          ..........................                ............................       .....................    ",
                "         ............................               .............................      .....................    ",
                "        .............................               .............................       ...................     ",
                "        ............................                .............................       ...................     ",
                "        ............................                .............................        .................      ",
                "        ...........................                 ..............................       .................      ",
                "        ...........................                 ..............................        ...............       ",
                "         .........................                  ..............................        ...............       ",
                "          .......................                   .............................          .............        ",
                "           .....................                     ............................          ............         ",
                "            ...................                      ...........................            ..........          ",
                "             .................       ....            ..........................              ........           ",
                "              ...............       ......            .........................                                 ",
                "               .............       ........            .......................                                  ",
                "                ...........       ..........            .....................                                   ",
                "                 .........       ............            ...................                     ...            ",
                "                  .......        .............            .................                     .....           ",
                "                                 .............             ...............                     .......          ",
                "                                 .............              .............                     .........         ",
                "                                 .............               ...........                      .........         ",
                "                                  ...........                 .........                       .........         ",
                "                                   ..........                 ........                         .......          ",
                "                                    ........                   ......                           .....           ",
                "                                     ......                     ....                             ...            ",
                "                                      ....                       ..                                             "
            ];

            const createMapDots = (containerId) => {
                const container = document.getElementById(containerId);
                if (!container) return;
                
                // Clear existing
                container.innerHTML = '';

                // Grid dimensions
                const rows = mapGrid.length;
                const cols = mapGrid[0].length;
                
                // Iterate grid
                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        const char = mapGrid[r][c];
                        
                        // If char is not space, it's a land mass
                        if (char !== ' ') {
                            // Add some randomness to skip some dots for "texture"
                            if (Math.random() > 0.15) { 
                                const dot = document.createElement('div');
                                dot.className = 'map-dot';
                                
                                // Calculate percent position
                                // Add slight jitter
                                const x = (c / cols) * 100 + (Math.random() * 0.5 - 0.25);
                                const y = (r / rows) * 100 + (Math.random() * 0.5 - 0.25);
                                
                                dot.style.left = `${x}%`;
                                dot.style.top = `${y}%`;
                                
                                // Random animation delay
                                dot.style.animationDelay = `${Math.random() * 5}s`;
                                
                                // Randomly active
                                if (Math.random() > 0.8) {
                                    dot.classList.add('map-dot-active');
                                }
                                
                                container.appendChild(dot);
                                
                                // Hover effect
                                dot.addEventListener('mouseenter', () => {
                                    dot.style.transform = 'scale(4)';
                                    dot.style.backgroundColor = '#818cf8';
                                    dot.style.boxShadow = '0 0 15px #818cf8';
                                    dot.style.zIndex = '50';
                                });
                                
                                dot.addEventListener('mouseleave', () => {
                                    dot.style.transform = '';
                                    dot.style.backgroundColor = '';
                                    dot.style.boxShadow = '';
                                    dot.style.zIndex = '';
                                });
                            }
                        }
                    }
                }
            };

            createMapDots('global-map-container'); 
            // Also create for header if needed, using same or simpler grid
            // createMapDots('map-container');
        });
    </script>
</body>
</html>
