@extends('layouts.app')

@section('title', __('Help Center'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gray-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-primary-500 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute top-40 right-0 w-[32rem] h-[32rem] bg-primary-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute -bottom-20 left-1/3 w-80 h-80 bg-secondary-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('24/7 Support Available') }}</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('How Can We') }} <span class="text-gradient">{{ __('Help You') }}</span>?
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                {{ __('Find answers to common questions and learn how to get the most out of Mindova') }}
            </p>
        </div>
    </div>
</div>

<!-- FAQ Sections -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Getting Started -->
            <div class="card-premium">
                <div class="flex items-center mb-6">
                    <div class="icon-badge bg-primary-500 mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Getting Started') }}</h2>
                </div>

                <div class="space-y-4">
                    <details class="group border-b border-gray-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('How do I create an account?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Click "Get Started" and choose either Volunteer or Company account. Fill in your details or sign up with LinkedIn for faster registration. You\'ll need to provide basic information and upload your CV if you\'re registering as a contributor.') }}</p>
                    </details>

                    <details class="group border-b border-gray-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('What\'s the difference between Volunteer and Company accounts?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Volunteer accounts are for individuals contributing skills and time to challenges. You\'ll receive task recommendations based on your skills and can join teams. Company accounts are for organizations posting challenges and seeking solutions from our volunteer community.') }}</p>
                    </details>

                    <details class="group border-b border-gray-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('How does the AI matching work?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Our AI analyzes your CV using GPT-4o to extract skills, experience levels, and expertise areas. It then matches you to tasks based on skill alignment, experience requirements, and availability. Match scores of 60% or higher are recommended for optimal collaboration.') }}</p>
                    </details>
                </div>
            </div>

            <!-- For Contributors -->
            <div class="card-premium bg-gray-50 border-2 border-blue-200">
                <div class="flex items-center mb-6">
                    <div class="icon-badge bg-secondary-500 mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('For Contributors') }}</h2>
                </div>

                <div class="space-y-4">
                    <details class="group border-b border-blue-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('How do I find tasks?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Once you complete your profile and upload your CV, our AI automatically matches you to suitable tasks. You\'ll receive notifications when invited to teams or assigned tasks. Check your dashboard regularly for new opportunities that match your skills.') }}</p>
                    </details>

                    <details class="group border-b border-blue-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('What happens after I\'m invited to a team?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Review the team details, your assigned role, and responsibilities in your notifications. You can accept to join or decline if it\'s not a good fit. There are no penalties for declining invitations - we want you to work on projects that excite you!') }}</p>
                    </details>

                    <details class="group border-b border-blue-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-blue-600">
                            <span>{{ __('How is my reputation score calculated?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Your reputation increases based on completed tasks, quality of work submissions, team contributions, and peer reviews. It starts at 50 and can grow through consistent positive engagement. Higher reputation scores lead to better task matches and leadership opportunities.') }}</p>
                    </details>
                </div>
            </div>

            <!-- For Companies -->
            <div class="card-premium bg-gray-50 border-2 border-green-200">
                <div class="flex items-center mb-6">
                    <div class="icon-badge bg-primary-400 mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('For Companies') }}</h2>
                </div>

                <div class="space-y-4">
                    <details class="group border-b border-green-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-green-600">
                            <span>{{ __('How do I post a challenge?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Navigate to "Challenges" → "Create Challenge" from your dashboard. Provide a detailed description (minimum 100 characters) of your problem, expected outcomes, and optional deadlines. Our AI will then analyze and decompose it into manageable tasks.') }}</p>
                    </details>

                    <details class="group border-b border-green-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-green-600">
                            <span>{{ __('How long does AI analysis take?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Complete analysis takes 15-20 minutes total: brief analysis (3 min), complexity evaluation (3 min), task decomposition (5 min), and team formation (10 min). You\'ll receive notifications at each stage and can monitor progress in your dashboard.') }}</p>
                    </details>

                    <details class="group border-b border-green-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-green-600">
                            <span>{{ __('Can I see who\'s on my teams?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('Yes! View your challenge page to see all formed teams, team members, their skills, roles, and real-time progress. You can access detailed analytics, communication history, and performance metrics for each team and member.') }}</p>
                    </details>
                </div>
            </div>

            <!-- Teams & Collaboration -->
            <div class="card-premium bg-gray-50 border-2 border-purple-200">
                <div class="flex items-center mb-6">
                    <div class="icon-badge bg-secondary-300 mr-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Teams & Collaboration') }}</h2>
                </div>

                <div class="space-y-4">
                    <details class="group border-b border-purple-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-purple-600">
                            <span>{{ __('What is a "micro company"?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('A micro company is an AI-formed team of 3-7 volunteers with complementary skills, created specifically to solve a challenge. Each member has a defined role and responsibilities, working together like a small startup focused on one project.') }}</p>
                    </details>

                    <details class="group border-b border-purple-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-purple-600">
                            <span>{{ __('Who becomes the team leader?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('AI selects leaders based on experience level, reputation score, skill breadth, and leadership history. Leaders coordinate team efforts, facilitate communication, and have visibility into the full challenge scope and strategic context.') }}</p>
                    </details>

                    <details class="group border-b border-purple-200 pb-4">
                        <summary class="flex justify-between items-center font-semibold text-gray-900 cursor-pointer hover:text-purple-600">
                            <span>{{ __('Can I leave a team?') }}</span>
                            <svg class="w-5 h-5 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ __('You can decline invitations before accepting them. After accepting, please communicate with your team leader if you need to leave, as it may affect the project timeline. We encourage open communication to minimize disruption to the team.') }}</p>
                    </details>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Need More Help Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium text-center bg-white border-2 border-blue-200">
            <div class="icon-badge bg-primary-500 mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Still Have Questions?') }}</h3>
            <p class="text-gray-700 mb-8 text-lg">{{ __('Can\'t find what you\'re looking for? Our support team is here to help!') }}</p>
            <x-ui.button as="a" href="{{ route('contact') }}" variant="primary" size="lg">
                {{ __('Contact Support') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-primary-500 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Ready to Get Started?') }}</h3>
        <p class="text-xl text-white/90 mb-10">{{ __('Join our community and start transforming challenges into innovation') }}</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
