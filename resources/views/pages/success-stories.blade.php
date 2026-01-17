@extends('layouts.app')

@section('title', __('Success Stories'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gray-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-primary-500 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute top-40 right-0 w-[32rem] h-[32rem] bg-primary-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute -bottom-20 left-1/3 w-80 h-80 bg-secondary-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Status Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('127+ Challenges Completed') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('Success') }} <span class="text-gradient">{{ __('Stories') }}</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                {{ __('Real challenges solved by talented contributors through Mindova\'s AI-powered platform') }}
            </p>
        </div>
    </div>
</div>

<!-- Quick Success Stats Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-500 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-gradient mb-2">127</div>
                <div class="text-sm text-gray-600 font-medium">{{ __('Challenges Completed') }}</div>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-500 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-gradient mb-2">450+</div>
                <div class="text-sm text-gray-600 font-medium">{{ __('Volunteers Participated') }}</div>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-400 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-gradient mb-2">85%</div>
                <div class="text-sm text-gray-600 font-medium">{{ __('Success Rate') }}</div>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-300 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-gradient mb-2">12K+</div>
                <div class="text-sm text-gray-600 font-medium">{{ __('Hours Contributed') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Success Stories Grid -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Recent') }} <span class="text-gradient">{{ __('Success Stories') }}</span></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Innovative solutions built by micro companies formed through AI-powered matching') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Story Card 1 -->
            <div class="card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-2xl shadow-lg">🚀</div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('TechStartup Inc.') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('E-commerce Platform') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A team of 5 volunteers built a complete e-commerce solution including product catalog, cart, checkout, and payment integration in just 8 weeks.') }}
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">React</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Node.js</span>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">PostgreSQL</span>
                </div>
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Team Size:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('5 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Duration:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('8 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Status:') }}</span>
                        <span class="font-bold text-green-600">{{ __('Live in Production') }}</span>
                    </div>
                </div>
            </div>

            <!-- Story Card 2 -->
            <div class="card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-secondary-500 rounded-full flex items-center justify-center text-2xl shadow-lg">🌱</div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('GreenFuture NGO') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Carbon Footprint Tracker') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A micro company of 4 developers created a mobile app to help individuals track and reduce their carbon footprint with AI-powered recommendations.') }}
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">React Native</span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Python</span>
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Firebase</span>
                </div>
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Team Size:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('4 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Duration:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('6 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Status:') }}</span>
                        <span class="font-bold text-green-600">{{ __('10K+ Downloads') }}</span>
                    </div>
                </div>
            </div>

            <!-- Story Card 3 -->
            <div class="card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-secondary-300 rounded-full flex items-center justify-center text-2xl shadow-lg">🎓</div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ __('EduTech Solutions') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Learning Management System') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A team of 7 volunteers developed a comprehensive LMS with video streaming, assessments, progress tracking, and analytics for a remote learning platform.') }}
                </p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Laravel</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Vue.js</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">MySQL</span>
                </div>
                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Team Size:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('7 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Duration:') }}</span>
                        <span class="font-bold text-gray-900">{{ __('12 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('Status:') }}</span>
                        <span class="font-bold text-green-600">{{ __('5K Active Users') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Success Stories (Detailed) -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Featured') }} <span class="text-gradient">{{ __('Success Stories') }}</span></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Deep dive into projects that made a real-world impact') }}</p>
        </div>

        <!-- Featured Story 1 -->
        <div class="card-premium mb-8 bg-gray-50 border-2 border-blue-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-4 py-1 bg-green-500 text-white rounded-full text-xs font-bold shadow-lg">{{ __('COMPLETED') }}</span>
                        <span class="text-sm font-semibold text-gray-700">{{ __('Healthcare Technology') }}</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Patient Management System for Rural Clinics') }}</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        {{ __('A healthcare startup needed a lightweight, offline-capable patient management system for rural clinics with limited internet connectivity. Our AI matched 6 volunteers with expertise in offline-first architecture, healthcare compliance, and mobile development.') }}
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        {{ __('The team delivered a Progressive Web App with offline sync, appointment scheduling, medical records management, and prescription tracking. The solution is now deployed in 15 clinics serving over 50,000 patients.') }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold">{{ __('Sarah M.') }}</span>
                            <span class="text-gray-500">{{ __('(Team Leader)') }}</span>
                        </div>
                        <span class="text-gray-400">•</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('10 weeks') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900 mb-3">{{ __('Technologies Used') }}</h4>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">React</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">PWA</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">IndexedDB</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Node.js</span>
                        </div>
                    </div>
                    <div class="bg-primary-500 rounded-2xl p-6 text-white shadow-xl">
                        <div class="text-sm font-semibold mb-2 opacity-90">{{ __('Impact') }}</div>
                        <div class="text-4xl font-black mb-1">50K+</div>
                        <div class="text-xs opacity-90">{{ __('Patients Served') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Story 2 -->
        <div class="card-premium bg-gray-50 border-2 border-green-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-4 py-1 bg-green-500 text-white rounded-full text-xs font-bold shadow-lg">{{ __('COMPLETED') }}</span>
                        <span class="text-sm font-semibold text-gray-700">{{ __('FinTech') }}</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Blockchain-Based Microfinance Platform') }}</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        {{ __('A social enterprise wanted to create a transparent microfinance platform using blockchain technology to serve underbanked communities. The challenge required expertise in blockchain, smart contracts, and financial systems.') }}
                    </p>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        {{ __('A diverse team of 5 specialists developed a platform with Ethereum smart contracts for transparent lending, a mobile-friendly interface for borrowers, and comprehensive admin tools. The platform has facilitated over $2M in microloans.') }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold">{{ __('David K.') }}</span>
                            <span class="text-gray-500">{{ __('(Blockchain Architect)') }}</span>
                        </div>
                        <span class="text-gray-400">•</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('14 weeks') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900 mb-3">{{ __('Technologies Used') }}</h4>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Solidity</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Ethereum</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Web3.js</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">React</span>
                        </div>
                    </div>
                    <div class="bg-secondary-500 rounded-2xl p-6 text-white shadow-xl">
                        <div class="text-sm font-semibold mb-2 opacity-90">{{ __('Loans Facilitated') }}</div>
                        <div class="text-4xl font-black mb-1">$2M+</div>
                        <div class="text-xs opacity-90">{{ __('Supporting 800+ Borrowers') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('What Our') }} <span class="text-gradient">{{ __('Community Says') }}</span></h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Hear from volunteers and companies who\'ve experienced success on Mindova') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Testimonial 1 -->
            <div class="card-premium bg-white/80 backdrop-blur-sm border-2 border-blue-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="icon-badge bg-primary-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ __('Michael Chen') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Full-Stack Developer') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">
                    {{ __('Mindova gave me the opportunity to work on real-world projects and build my portfolio while making a positive impact. The AI matching connected me with teams where I could contribute meaningfully and learn from experienced developers.') }}
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="card-premium bg-white/80 backdrop-blur-sm border-2 border-green-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="icon-badge bg-secondary-500">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ __('Jessica Rodriguez') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('CEO, TechStartup Inc.') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">
                    {{ __('We were amazed by the quality of talent Mindova connected us with. The AI-formed team had exactly the skills we needed, and they delivered a production-ready platform that exceeded our expectations. This changed our business trajectory.') }}
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="card-premium bg-white/80 backdrop-blur-sm border-2 border-purple-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="icon-badge bg-primary-400">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ __('Aisha Patel') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('UX Designer') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">
                    {{ __('As a designer, I loved being matched with projects that valued good UX. Working in micro companies taught me how to collaborate with developers effectively and see my designs come to life in real products.') }}
                </p>
            </div>

            <!-- Testimonial 4 -->
            <div class="card-premium bg-white/80 backdrop-blur-sm border-2 border-orange-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="icon-badge bg-secondary-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ __('Dr. James Wilson') }}</h4>
                        <p class="text-sm text-gray-600">{{ __('Director, GreenFuture NGO') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">
                    {{ __('The contributors who worked on our carbon tracking app were passionate, skilled, and committed. Mindova\'s platform made it easy to manage the project and communicate with the team. The app is now helping thousands reduce their environmental impact.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Animated CTA Section -->
<section class="py-24 bg-primary-500 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Ready to Create Your Success Story?') }}</h3>
        <p class="text-xl text-white/90 mb-10 leading-relaxed">{{ __('Join our community of talented contributors and innovative companies solving real-world challenges together.') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
                {{ __('Join as Contributor') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('register') }}" variant="outline" size="lg">
                {{ __('Post a Challenge') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
