@extends('layouts.app')

@section('title', __('Help Center'))

@section('content')
<!-- Hero Section -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 mb-6">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('24/7 Support Available') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('How Can We') }} <span class="text-primary-600">{{ __('Help You') }}</span>?
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                {{ __('Find answers to common questions and learn how to get the most out of Mindova') }}
            </p>
        </div>
    </div>
</div>

<!-- FAQ Sections -->
<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Getting Started -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Getting Started') }}</h2>
                </div>

                <div class="space-y-3">
                    <details class="group border-b border-gray-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-primary-600 text-sm">
                            <span>{{ __('How do I create an account?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Click "Get Started" and choose either Volunteer or Company account. Fill in your details or sign up with LinkedIn for faster registration. You\'ll need to provide basic information and upload your CV if you\'re registering as a contributor.') }}</p>
                    </details>

                    <details class="group border-b border-gray-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-primary-600 text-sm">
                            <span>{{ __('What\'s the difference between Volunteer and Company accounts?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Volunteer accounts are for individuals contributing skills and time to challenges. You\'ll receive task recommendations based on your skills and can join teams. Company accounts are for organizations posting challenges and seeking solutions from our volunteer community.') }}</p>
                    </details>

                    <details class="group border-b border-gray-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-primary-600 text-sm">
                            <span>{{ __('How does the AI matching work?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Our AI analyzes your CV using GPT-4o to extract skills, experience levels, and expertise areas. It then matches you to tasks based on skill alignment, experience requirements, and availability. Match scores of 60% or higher are recommended for optimal collaboration.') }}</p>
                    </details>
                </div>
            </div>

            <!-- For Contributors -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('For Contributors') }}</h2>
                </div>

                <div class="space-y-3">
                    <details class="group border-b border-blue-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600 text-sm">
                            <span>{{ __('How do I find tasks?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Once you complete your profile and upload your CV, our AI automatically matches you to suitable tasks. You\'ll receive notifications when invited to teams or assigned tasks. Check your dashboard regularly for new opportunities that match your skills.') }}</p>
                    </details>

                    <details class="group border-b border-blue-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600 text-sm">
                            <span>{{ __('What happens after I\'m invited to a team?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Review the team details, your assigned role, and responsibilities in your notifications. You can accept to join or decline if it\'s not a good fit. There are no penalties for declining invitations - we want you to work on projects that excite you!') }}</p>
                    </details>

                    <details class="group border-b border-blue-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600 text-sm">
                            <span>{{ __('How is my reputation score calculated?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Your reputation increases based on completed tasks, quality of work submissions, team contributions, and peer reviews. It starts at 50 and can grow through consistent positive engagement. Higher reputation scores lead to better task matches and leadership opportunities.') }}</p>
                    </details>
                </div>
            </div>

            <!-- For Companies -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('For Companies') }}</h2>
                </div>

                <div class="space-y-3">
                    <details class="group border-b border-emerald-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-emerald-600 text-sm">
                            <span>{{ __('How do I post a challenge?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Navigate to "Challenges" → "Create Challenge" from your dashboard. Provide a detailed description (minimum 100 characters) of your problem, expected outcomes, and optional deadlines. Our AI will then analyze and decompose it into manageable tasks.') }}</p>
                    </details>

                    <details class="group border-b border-emerald-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-emerald-600 text-sm">
                            <span>{{ __('How long does AI analysis take?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Complete analysis takes 15-20 minutes total: brief analysis (3 min), complexity evaluation (3 min), task decomposition (5 min), and team formation (10 min). You\'ll receive notifications at each stage and can monitor progress in your dashboard.') }}</p>
                    </details>

                    <details class="group border-b border-emerald-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-emerald-600 text-sm">
                            <span>{{ __('Can I see who\'s on my teams?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('Yes! View your challenge page to see all formed teams, team members, their skills, roles, and real-time progress. You can access detailed analytics, communication history, and performance metrics for each team and member.') }}</p>
                    </details>
                </div>
            </div>

            <!-- Teams & Collaboration -->
            <div class="bg-violet-50 border border-violet-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-violet-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Teams & Collaboration') }}</h2>
                </div>

                <div class="space-y-3">
                    <details class="group border-b border-violet-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-violet-600 text-sm">
                            <span>{{ __('What is a "micro company"?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('A micro company is an AI-formed team of 3-7 volunteers with complementary skills, created specifically to solve a challenge. Each member has a defined role and responsibilities, working together like a small startup focused on one project.') }}</p>
                    </details>

                    <details class="group border-b border-violet-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-violet-600 text-sm">
                            <span>{{ __('Who becomes the team leader?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('AI selects leaders based on experience level, reputation score, skill breadth, and leadership history. Leaders coordinate team efforts, facilitate communication, and have visibility into the full challenge scope and strategic context.') }}</p>
                    </details>

                    <details class="group border-b border-violet-100 pb-3">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-violet-600 text-sm">
                            <span>{{ __('Can I leave a team?') }}</span>
                            <svg class="w-4 h-4 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-2 text-xs leading-relaxed">{{ __('You can decline invitations before accepting them. After accepting, please communicate with your team leader if you need to leave, as it may affect the project timeline. We encourage open communication to minimize disruption to the team.') }}</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Need More Help Section -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-5 border border-primary-200">
                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('Still Have Questions?') }}</h3>
            <p class="text-gray-600 mb-6 text-sm">{{ __('Can\'t find what you\'re looking for? Our support team is here to help!') }}</p>
            <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="sm">
                {{ __('Contact Support') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-500 border-t border-primary-400">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ __('Ready to Get Started?') }}</h3>
        <p class="text-white/90 mb-8 max-w-xl mx-auto">{{ __('Join our community and start transforming challenges into innovation') }}</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
