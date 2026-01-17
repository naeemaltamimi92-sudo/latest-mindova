@extends('layouts.app')

@section('title', __('Blog'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gray-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 -left-32 w-96 h-96 bg-primary-400 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute top-40 right-0 w-[32rem] h-[32rem] bg-secondary-300 opacity-20 rounded-full blur-3xl"></div>
    <div class="floating-element absolute -bottom-20 left-1/3 w-80 h-80 bg-primary-500 opacity-20 rounded-full blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Status Badge -->
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-purple-500 rounded-full-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('Insights & Updates') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                Mindova <span class="text-gradient">{{ __('Blog') }}</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                {{ __('Insights, updates, and stories from the Mindova community') }}
            </p>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="py-12 bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-3 justify-center">
            <span class="px-5 py-2 bg-primary-500 text-white rounded-full text-sm font-semibold shadow-lg cursor-pointer hover:shadow-xl">{{ __('All Posts') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-blue-500 hover:text-blue-600 cursor-pointer">{{ __('Product Updates') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-green-500 hover:text-green-600 cursor-pointer">{{ __('Success Stories') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-purple-500 hover:text-purple-600 cursor-pointer">{{ __('Engineering') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-orange-500 hover:text-orange-600 cursor-pointer">{{ __('Best Practices') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-pink-500 hover:text-pink-600 cursor-pointer">{{ __('Community') }}</span>
            <span class="px-5 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full text-sm font-semibold hover:border-blue-500 hover:text-blue-600 cursor-pointer">{{ __('AI & Technology') }}</span>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Blog Post 1 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-primary-500 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">📝</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-blue-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>January 2025</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('5 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600">{{ __('AI-Powered Team Formation') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('Discover how our advanced AI algorithms create optimal teams by analyzing skills, experience, and collaboration patterns.') }}</p>
                <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

            <!-- Blog Post 2 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-secondary-500 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">🎯</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-green-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>December 2024</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('8 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600">{{ __('Success Story: Building an E-commerce Platform') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('How a diverse team of volunteers helped a startup build their MVP in just 6 weeks using Mindova\'s collaboration tools.') }}</p>
                <div class="flex items-center text-green-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

            <!-- Blog Post 3 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-secondary-300 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">💡</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-orange-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>December 2024</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('6 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600">{{ __('Best Practices for Remote Collaboration') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('Tips and strategies for effective teamwork in distributed micro companies, based on data from hundreds of successful projects.') }}</p>
                <div class="flex items-center text-orange-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

            <!-- Blog Post 4 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-primary-400 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">🚀</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-purple-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>January 2025</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('7 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600">{{ __('Product Update: New Features in 2025') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('Explore the latest features we\'ve added to enhance your experience, including improved matching algorithms and team analytics.') }}</p>
                <div class="flex items-center text-purple-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

            <!-- Blog Post 5 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-secondary-500 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">👥</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-red-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>December 2024</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('10 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600">{{ __('Building Your Developer Portfolio') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('How volunteers are using Mindova to gain real-world experience and showcase their skills to potential employers.') }}</p>
                <div class="flex items-center text-red-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

            <!-- Blog Post 6 -->
            <div class="card-premium group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl mb-4 bg-primary-500 h-48 flex items-center justify-center">
                    <span class="text-7xl transform">🔧</span>
                    <div class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-blue-600">{{ __('Coming Soon') }}</div>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>January 2025</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ __('12 min read') }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600">{{ __('Technical Deep Dive: CV Analysis') }}</h3>
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ __('An inside look at how we extract and categorize skills from resumes using natural language processing and machine learning.') }}</p>
                <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:gap-2">
                    <span>{{ __('Read more') }}</span>
                    <svg class="w-4 h-4 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-premium bg-gray-50 border-2 border-blue-200 text-center">
            <div class="icon-badge bg-primary-500 mx-auto mb-6">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Stay') }} <span class="text-gradient">{{ __('Updated') }}</span></h2>
            <p class="text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto">{{ __('Subscribe to our newsletter to receive the latest blog posts, platform updates, and community highlights directly in your inbox.') }}</p>
            <form action="#" method="POST" class="max-w-md mx-auto">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="email" name="email" placeholder="your@email.com" class="flex-1 px-5 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-gray-900 font-medium" required>
                    <x-ui.button as="submit" variant="primary">
                        {{ __('Subscribe') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </x-ui.button>
                </div>
                <p class="text-xs text-gray-500 mt-3">{{ __('No spam. Unsubscribe anytime.') }}</p>
            </form>
        </div>
    </div>
</section>

<!-- Animated CTA Section -->
<section class="py-24 bg-primary-500 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-4xl md:text-5xl font-bold mb-6">{{ __('Ready to Join Our Community?') }}</h3>
        <p class="text-xl text-white/90 mb-10 leading-relaxed">{{ __('Start collaborating on real-world projects and build your portfolio with Mindova.') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <x-ui.button as="a" href="{{ route('register') }}" variant="secondary" size="lg">
                {{ __('Get Started') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </x-ui.button>
            <x-ui.button as="a" href="{{ route('success-stories') }}" variant="outline" size="lg">
                {{ __('View Success Stories') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </x-ui.button>
        </div>
    </div>
</section>
@endsection
