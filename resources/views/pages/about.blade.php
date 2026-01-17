@extends('layouts.app')

@section('title', __('About Us'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gray-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-primary-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute -bottom-20 right-0 w-[32rem] h-[32rem] bg-primary-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-blue-500 rounded-full-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('About Mindova') }}</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('Transforming Challenges Into') }} <span class="text-gradient">{{ __('Innovation') }}</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed">
                {{ __('Empowering collaboration between talented contributors and forward-thinking companies') }}
            </p>
        </div>
    </div>
</div>

<!-- Mission Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium text-center">
            <div class="icon-badge bg-primary-500 mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ __('Our Mission') }}</h2>
            <p class="text-xl text-gray-700 leading-relaxed">
                {{ __('To democratize access to talent and meaningful work by connecting skilled volunteers with companies facing real-world challenges, powered by intelligent AI matching.') }}
            </p>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ __('What') }} <span class="text-gradient">{{ __('We Do') }}</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Mindova is an AI-powered collaboration platform that transforms how organizations solve complex challenges') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="card-premium">
                <div class="icon-badge bg-primary-500 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Analyze & Decompose Challenges') }}</h3>
                <p class="text-gray-600">{{ __('Break down complex problems into manageable tasks using advanced AI analysis and strategic planning.') }}</p>
            </div>

            <div class="card-premium">
                <div class="icon-badge bg-secondary-500 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Match Talent with Opportunities') }}</h3>
                <p class="text-gray-600">{{ __('Connect skilled volunteers with projects that align perfectly with their expertise and career goals.') }}</p>
            </div>

            <div class="card-premium">
                <div class="icon-badge bg-primary-400 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Form Optimal Teams') }}</h3>
                <p class="text-gray-600">{{ __('Create balanced "micro companies" with complementary skills for collaborative problem-solving.') }}</p>
            </div>

            <div class="card-premium">
                <div class="icon-badge bg-secondary-300 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Facilitate Meaningful Work') }}</h3>
                <p class="text-gray-600">{{ __('Enable volunteers to build portfolios while solving real problems that make a difference.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Technology Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">
                {{ __('Our Technology') }}
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ __('Built on') }} <span class="text-gradient">{{ __('Cutting-Edge AI') }}</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Powered by GPT-4o and advanced algorithms for intelligent collaboration') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="card-premium bg-gray-50 border-2 border-blue-200">
                <div class="flex items-center mb-4">
                    <div class="icon-badge bg-primary-500 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">{{ __('Intelligent Analysis') }}</h4>
                </div>
                <p class="text-gray-600">{{ __('AI-powered challenge analysis and task decomposition using natural language processing') }}</p>
            </div>

            <div class="card-premium bg-gray-50 border-2 border-green-200">
                <div class="flex items-center mb-4">
                    <div class="icon-badge bg-secondary-500 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">{{ __('Smart Matching') }}</h4>
                </div>
                <p class="text-gray-600">{{ __('Advanced algorithms for optimal volunteer-task pairing with 80%+ accuracy') }}</p>
            </div>

            <div class="card-premium bg-gray-50 border-2 border-purple-200">
                <div class="flex items-center mb-4">
                    <div class="icon-badge bg-primary-400 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">{{ __('Team Formation') }}</h4>
                </div>
                <p class="text-gray-600">{{ __('Automated creation of balanced, high-performing teams with complementary skills') }}</p>
            </div>

            <div class="card-premium bg-gray-50 border-2 border-orange-200">
                <div class="flex items-center mb-4">
                    <div class="icon-badge bg-secondary-300 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">{{ __('Progress Tracking') }}</h4>
                </div>
                <p class="text-gray-600">{{ __('Real-time analytics and performance monitoring with comprehensive dashboards') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Mindova Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Why') }} <span class="text-gradient">{{ __('Mindova') }}</span>?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="card-premium bg-gray-50 border-2 border-blue-200">
                <div class="flex items-start mb-4">
                    <div class="icon-badge bg-primary-500 mr-4 flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('For Contributors') }}</h3>
                        <p class="text-gray-700 leading-relaxed">{{ __('Build your portfolio, gain real-world experience, and make a meaningful impact while working on challenges that match your skills and interests. Grow your reputation and network with professionals.') }}</p>
                    </div>
                </div>
            </div>

            <div class="card-premium bg-gray-50 border-2 border-green-200">
                <div class="flex items-start mb-4">
                    <div class="icon-badge bg-secondary-500 mr-4 flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ __('For Companies') }}</h3>
                        <p class="text-gray-700 leading-relaxed">{{ __('Access diverse talent, solve complex problems cost-effectively, and benefit from AI-optimized team formation and project management. Get results faster with perfectly matched volunteers.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-primary-500 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Ready to Make a Difference?') }}</h3>
        <p class="text-xl text-white/90 mb-10">{{ __('Join our community and start transforming challenges into innovation') }}</p>
        <x-ui.button as="a" href="{{ route('contact') }}" variant="secondary" size="lg">
            {{ __('Get in Touch') }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </x-ui.button>
    </div>
</section>
@endsection
