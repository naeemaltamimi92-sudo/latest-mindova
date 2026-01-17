@extends('layouts.app')

@section('title', __('How It Works'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gray-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-primary-500 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute top-40 right-0 w-[32rem] h-[32rem] bg-primary-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute -bottom-20 left-1/3 w-80 h-80 bg-secondary-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Status Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('Complete Guide to Mindova') }}</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('How') }} <span class="text-gradient">Mindova</span> {{ __('Works') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ __('Our AI-powered platform connects talented contributors with meaningful challenges, creating optimal teams to solve real-world problems.') }}
            </p>
        </div>
    </div>
</div>

<!-- For Contributors Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">
                {{ __('For Contributors') }}
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ __('Make an Impact with') }} <span class="text-gradient">{{ __('Your Skills') }}</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('Build your portfolio while contributing to meaningful projects') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-500 mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="mb-3 text-4xl font-bold text-gradient">1</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Sign Up') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Create your profile and upload your CV. Our AI analyzes your skills, experience, and expertise.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-500 mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mb-3 text-4xl font-bold text-gradient">2</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Get Matched') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('AI matches you to tasks that align with your skills and interests. Receive team invitations for challenges.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-400 mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="mb-3 text-4xl font-bold text-gradient">3</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Join Teams') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Accept invitations to join micro companies. Collaborate with other talented contributors on meaningful work.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-500 mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="mb-3 text-4xl font-bold text-gradient">4</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Make Impact') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Solve real challenges, build your reputation, and create portfolio-worthy projects.') }}</p>
            </div>
        </div>

        <div class="card-premium bg-gray-50 border-2 border-blue-200">
            <h4 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                {{ __('What You Get') }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('AI-powered skill matching') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Portfolio-building projects') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Reputation system') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Team collaboration experience') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Make real-world impact') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Network with professionals') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- For Companies Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                {{ __('For Companies') }}
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                {{ __('Solve Complex Challenges with') }} <span class="text-gradient">{{ __('AI-Assembled Teams') }}</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ __('From challenge submission to solution delivery') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-12">
            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-500 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="mb-2 text-3xl font-bold text-gradient">1</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Post Challenge') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Submit your challenge with detailed description of the problem you want to solve.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-500 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div class="mb-2 text-3xl font-bold text-gradient">2</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('AI Analysis') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Our AI refines your brief, evaluates complexity, and decomposes into manageable tasks.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-400 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="mb-2 text-3xl font-bold text-gradient">3</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Team Formation') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('AI creates optimal "micro companies" with volunteers matched to specific roles and tasks.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-secondary-300 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="mb-2 text-3xl font-bold text-gradient">4</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Monitor Progress') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Track team progress, view analytics, and communicate with contributors.') }}</p>
            </div>

            <div class="card-premium text-center">
                <div class="icon-badge bg-primary-500 mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="mb-2 text-3xl font-bold text-gradient">5</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Get Results') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Receive completed work, review deliverables, and solve your challenge.') }}</p>
            </div>
        </div>

        <div class="card-premium bg-gray-50 border-2 border-green-200">
            <h4 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                {{ __('What You Get') }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('AI-powered task decomposition') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Optimal team formation') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Skill-matched volunteers') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Progress tracking & analytics') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Cost-effective solutions') }}</span>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 text-2xl">✓</span>
                    <span class="text-gray-700 font-medium">{{ __('Access to diverse talent') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Technology Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium bg-gray-50 backdrop-blur-lg border-2 border-purple-200">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">
                    {{ __('AI Technology') }}
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ __('Powered by') }} <span class="text-gradient">{{ __('Advanced AI') }}</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Our cutting-edge technology ensures optimal matching and team performance') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-premium">
                    <div class="icon-badge bg-primary-500 mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">{{ __('Challenge Analysis') }}</h4>
                    <p class="text-gray-600">{{ __('GPT-4o analyzes your challenge, extracts objectives, identifies constraints, and creates a structured brief.') }}</p>
                </div>

                <div class="card-premium">
                    <div class="icon-badge bg-secondary-500 mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">{{ __('Smart Matching') }}</h4>
                    <p class="text-gray-600">{{ __('Advanced algorithms match volunteer skills, experience, and availability to task requirements with 80%+ accuracy.') }}</p>
                </div>

                <div class="card-premium">
                    <div class="icon-badge bg-primary-400 mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">{{ __('Team Formation') }}</h4>
                    <p class="text-gray-600">{{ __('AI creates balanced teams with complementary skills, optimal size (3-7 members), and clear leadership.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-primary-500 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Ready to Get Started?') }}</h3>
        <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">{{ __('Join thousands of volunteers and companies transforming challenges into innovation') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
                {{ __('Join as Contributor') }}
                <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('register') }}" variant="outline" size="lg">
                {{ __('Post a Challenge') }}
                <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
