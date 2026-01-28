@extends('layouts.app')

@section('title', __('How It Works'))

@section('content')
<!-- Hero Section -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 mb-6">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('Complete Guide to Mindova') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('How') }} <span class="text-primary-600">Mindova</span> {{ __('Works') }}
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Our AI-powered platform connects talented contributors with meaningful challenges, creating optimal teams to solve real-world problems.') }}
            </p>
        </div>
    </div>
</div>

<!-- For Contributors Section -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold border border-blue-200 mb-4">
                {{ __('For Contributors') }}
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                {{ __('Make an Impact with') }} <span class="text-primary-600">{{ __('Your Skills') }}</span>
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('Build your portfolio while contributing to meaningful projects') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-2">1</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Sign Up') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Create your profile and upload your CV. Our AI analyzes your skills, experience, and expertise.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-2">2</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Get Matched') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('AI matches you to tasks that align with your skills and interests. Receive team invitations for challenges.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-12 h-12 bg-violet-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-2">3</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Join Teams') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Accept invitations to join micro companies. Collaborate with other talented contributors on meaningful work.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 text-center">
                <div class="w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-primary-600 mb-2">4</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ __('Make Impact') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('Solve real challenges, build your reputation, and create portfolio-worthy projects.') }}</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                {{ __('What You Get') }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('AI-powered skill matching') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Portfolio-building projects') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Reputation system') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Team collaboration experience') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Make real-world impact') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Network with professionals') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- For Companies Section -->
<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-block px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold border border-emerald-200 mb-4">
                {{ __('For Companies') }}
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                {{ __('Solve Challenges with') }} <span class="text-primary-600">{{ __('AI-Assembled Teams') }}</span>
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('From challenge submission to solution delivery') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-10">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-secondary-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="text-xl font-bold text-primary-600 mb-1">1</div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Post Challenge') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Submit your challenge with detailed description.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div class="text-xl font-bold text-primary-600 mb-1">2</div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('AI Analysis') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Our AI refines your brief and decomposes into tasks.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-xl font-bold text-primary-600 mb-1">3</div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Team Formation') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('AI creates optimal teams matched to specific roles.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="text-xl font-bold text-primary-600 mb-1">4</div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Monitor Progress') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Track team progress and view analytics.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="text-xl font-bold text-primary-600 mb-1">5</div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Get Results') }}</h3>
                <p class="text-gray-600 text-xs">{{ __('Receive completed work and review deliverables.') }}</p>
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                {{ __('What You Get') }}
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('AI-powered task decomposition') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Optimal team formation') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Skill-matched volunteers') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Progress tracking & analytics') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Cost-effective solutions') }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-gray-700 text-sm">{{ __('Access to diverse talent') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Technology Section -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <div class="inline-block px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-sm font-semibold border border-violet-200 mb-4">
                {{ __('AI Technology') }}
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                {{ __('Powered by') }} <span class="text-primary-600">{{ __('Advanced AI') }}</span>
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('Our cutting-edge technology ensures optimal matching and team performance') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 mb-2">{{ __('Challenge Analysis') }}</h4>
                <p class="text-gray-600 text-sm">{{ __('GPT-4o analyzes your challenge, extracts objectives, identifies constraints, and creates a structured brief.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 mb-2">{{ __('Smart Matching') }}</h4>
                <p class="text-gray-600 text-sm">{{ __('Advanced algorithms match volunteer skills, experience, and availability to task requirements with 80%+ accuracy.') }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 mb-2">{{ __('Team Formation') }}</h4>
                <p class="text-gray-600 text-sm">{{ __('AI creates balanced teams with complementary skills, optimal size (3-7 members), and clear leadership.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-500 border-t border-primary-400">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ __('Ready to Get Started?') }}</h3>
        <p class="text-white/90 mb-8 max-w-xl mx-auto">{{ __('Join thousands of volunteers and companies transforming challenges into innovation') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
                {{ __('Join as Contributor') }}
                <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('register') }}" variant="outline" size="lg" class="!border-white !text-white hover:!bg-white hover:!text-primary-500">
                {{ __('Post a Challenge') }}
                <svg class="inline-block w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
