@extends('layouts.app')

@section('title', __('Success Stories'))

@section('content')
<!-- Hero Section -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 mb-6">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('127+ Challenges Completed') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Success') }} <span class="text-primary-600">{{ __('Stories') }}</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Real challenges solved by talented contributors through Mindova\'s AI-powered platform') }}
            </p>
        </div>
    </div>
</div>

<!-- Stats Section -->
<section class="py-12 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-1">127</div>
                <div class="text-xs text-gray-600 font-medium">{{ __('Challenges Completed') }}</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-1">450+</div>
                <div class="text-xs text-gray-600 font-medium">{{ __('Volunteers') }}</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-1">85%</div>
                <div class="text-xs text-gray-600 font-medium">{{ __('Success Rate') }}</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-1">12K+</div>
                <div class="text-xs text-gray-600 font-medium">{{ __('Hours Contributed') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Success Stories -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ __('Recent') }} <span class="text-primary-600">{{ __('Success Stories') }}</span></h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('Innovative solutions built by micro companies formed through AI-powered matching') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Story Card 1 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center text-xl border border-primary-200">🚀</div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">{{ __('TechStartup Inc.') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('E-commerce Platform') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A team of 5 volunteers built a complete e-commerce solution including product catalog, cart, checkout, and payment integration in just 8 weeks.') }}
                </p>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded border border-blue-200 text-xs font-medium">React</span>
                    <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded border border-green-200 text-xs font-medium">Node.js</span>
                    <span class="px-2 py-0.5 bg-violet-50 text-violet-700 rounded border border-violet-200 text-xs font-medium">PostgreSQL</span>
                </div>
                <div class="border-t border-gray-100 pt-3 space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Team:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('5 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Duration:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('8 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Status:') }}</span>
                        <span class="font-medium text-emerald-600">{{ __('Live') }}</span>
                    </div>
                </div>
            </div>

            <!-- Story Card 2 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-xl border border-emerald-200">🌱</div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">{{ __('GreenFuture NGO') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Carbon Footprint Tracker') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A micro company of 4 developers created a mobile app to help individuals track and reduce their carbon footprint with AI-powered recommendations.') }}
                </p>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded border border-blue-200 text-xs font-medium">React Native</span>
                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded border border-amber-200 text-xs font-medium">Python</span>
                    <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded border border-red-200 text-xs font-medium">Firebase</span>
                </div>
                <div class="border-t border-gray-100 pt-3 space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Team:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('4 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Duration:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('6 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Status:') }}</span>
                        <span class="font-medium text-emerald-600">{{ __('10K+ Downloads') }}</span>
                    </div>
                </div>
            </div>

            <!-- Story Card 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center text-xl border border-violet-200">🎓</div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">{{ __('EduTech Solutions') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Learning Management System') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                    {{ __('A team of 7 volunteers developed a comprehensive LMS with video streaming, assessments, progress tracking, and analytics for a remote learning platform.') }}
                </p>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <span class="px-2 py-0.5 bg-violet-50 text-violet-700 rounded border border-violet-200 text-xs font-medium">Laravel</span>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded border border-blue-200 text-xs font-medium">Vue.js</span>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded border border-emerald-200 text-xs font-medium">MySQL</span>
                </div>
                <div class="border-t border-gray-100 pt-3 space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Team:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('7 volunteers') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Duration:') }}</span>
                        <span class="font-medium text-gray-900">{{ __('12 weeks') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">{{ __('Status:') }}</span>
                        <span class="font-medium text-emerald-600">{{ __('5K Active Users') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Success Stories -->
<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ __('Featured') }} <span class="text-primary-600">{{ __('Success Stories') }}</span></h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('Deep dive into projects that made a real-world impact') }}</p>
        </div>

        <!-- Featured Story 1 -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2 py-0.5 bg-emerald-500 text-white rounded text-[10px] font-bold">{{ __('COMPLETED') }}</span>
                        <span class="text-xs font-medium text-gray-600">{{ __('Healthcare Technology') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Patient Management System for Rural Clinics') }}</h3>
                    <p class="text-gray-600 mb-3 text-sm leading-relaxed">
                        {{ __('A healthcare startup needed a lightweight, offline-capable patient management system for rural clinics with limited internet connectivity. Our AI matched 6 volunteers with expertise in offline-first architecture, healthcare compliance, and mobile development.') }}
                    </p>
                    <p class="text-gray-600 mb-3 text-sm leading-relaxed">
                        {{ __('The team delivered a Progressive Web App with offline sync, appointment scheduling, medical records management, and prescription tracking. The solution is now deployed in 15 clinics serving over 50,000 patients.') }}
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-600">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">{{ __('Sarah M.') }}</span>
                            <span class="text-gray-400">{{ __('(Team Leader)') }}</span>
                        </div>
                        <span class="text-gray-300">•</span>
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
                        <h4 class="font-semibold text-gray-900 text-sm mb-2">{{ __('Technologies') }}</h4>
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            <span class="px-2 py-0.5 bg-white text-blue-700 rounded border border-blue-200 text-xs font-medium">React</span>
                            <span class="px-2 py-0.5 bg-white text-violet-700 rounded border border-violet-200 text-xs font-medium">PWA</span>
                            <span class="px-2 py-0.5 bg-white text-green-700 rounded border border-green-200 text-xs font-medium">IndexedDB</span>
                            <span class="px-2 py-0.5 bg-white text-amber-700 rounded border border-amber-200 text-xs font-medium">Node.js</span>
                        </div>
                    </div>
                    <div class="bg-primary-500 rounded-xl p-4 text-white">
                        <div class="text-xs font-medium mb-1 opacity-90">{{ __('Impact') }}</div>
                        <div class="text-2xl font-bold mb-0.5">50K+</div>
                        <div class="text-xs opacity-90">{{ __('Patients Served') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Story 2 -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2 py-0.5 bg-emerald-500 text-white rounded text-[10px] font-bold">{{ __('COMPLETED') }}</span>
                        <span class="text-xs font-medium text-gray-600">{{ __('FinTech') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Blockchain-Based Microfinance Platform') }}</h3>
                    <p class="text-gray-600 mb-3 text-sm leading-relaxed">
                        {{ __('A social enterprise wanted to create a transparent microfinance platform using blockchain technology to serve underbanked communities. The challenge required expertise in blockchain, smart contracts, and financial systems.') }}
                    </p>
                    <p class="text-gray-600 mb-3 text-sm leading-relaxed">
                        {{ __('A diverse team of 5 specialists developed a platform with Ethereum smart contracts for transparent lending, a mobile-friendly interface for borrowers, and comprehensive admin tools. The platform has facilitated over $2M in microloans.') }}
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-600">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">{{ __('David K.') }}</span>
                            <span class="text-gray-400">{{ __('(Blockchain Architect)') }}</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ __('14 weeks') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm mb-2">{{ __('Technologies') }}</h4>
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            <span class="px-2 py-0.5 bg-white text-violet-700 rounded border border-violet-200 text-xs font-medium">Solidity</span>
                            <span class="px-2 py-0.5 bg-white text-blue-700 rounded border border-blue-200 text-xs font-medium">Ethereum</span>
                            <span class="px-2 py-0.5 bg-white text-green-700 rounded border border-green-200 text-xs font-medium">Web3.js</span>
                            <span class="px-2 py-0.5 bg-white text-cyan-700 rounded border border-cyan-200 text-xs font-medium">React</span>
                        </div>
                    </div>
                    <div class="bg-emerald-500 rounded-xl p-4 text-white">
                        <div class="text-xs font-medium mb-1 opacity-90">{{ __('Loans Facilitated') }}</div>
                        <div class="text-2xl font-bold mb-0.5">$2M+</div>
                        <div class="text-xs opacity-90">{{ __('800+ Borrowers') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ __('What Our') }} <span class="text-primary-600">{{ __('Community Says') }}</span></h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('Hear from volunteers and companies who\'ve experienced success on Mindova') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Testimonial 1 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center border border-primary-200">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ __('Michael Chen') }}</h4>
                        <p class="text-xs text-gray-500">{{ __('Full-Stack Developer') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('Mindova gave me the opportunity to work on real-world projects and build my portfolio while making a positive impact. The AI matching connected me with teams where I could contribute meaningfully.') }}
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center border border-emerald-200">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ __('Jessica Rodriguez') }}</h4>
                        <p class="text-xs text-gray-500">{{ __('CEO, TechStartup Inc.') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('We were amazed by the quality of talent Mindova connected us with. The AI-formed team had exactly the skills we needed, and they delivered a production-ready platform that exceeded our expectations.') }}
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center border border-violet-200">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ __('Aisha Patel') }}</h4>
                        <p class="text-xs text-gray-500">{{ __('UX Designer') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('As a designer, I loved being matched with projects that valued good UX. Working in micro companies taught me how to collaborate with developers effectively and see my designs come to life.') }}
                </p>
            </div>

            <!-- Testimonial 4 -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center border border-amber-200">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ __('Dr. James Wilson') }}</h4>
                        <p class="text-xs text-gray-500">{{ __('Director, GreenFuture NGO') }}</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('The contributors who worked on our carbon tracking app were passionate, skilled, and committed. Mindova\'s platform made it easy to manage the project and communicate with the team.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-500 border-t border-primary-400">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ __('Ready to Create Your Success Story?') }}</h3>
        <p class="text-white/90 mb-8 max-w-xl mx-auto">{{ __('Join our community of talented contributors and innovative companies solving real-world challenges together.') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
                {{ __('Join as Contributor') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('register') }}" variant="outline" size="lg" class="!border-white !text-white hover:!bg-white hover:!text-primary-500">
                {{ __('Post a Challenge') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
