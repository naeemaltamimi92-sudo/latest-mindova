@extends('layouts.app')

@section('title', __('Contact Us'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 pt-32 pb-24">
    <!-- Floating Background Elements -->
    <div class="floating-element absolute top-20 right-0 w-96 h-96 bg-gradient-blue opacity-20 rounded-full blur-3xl animate-float"></div>
    <div class="floating-element absolute -bottom-20 left-0 w-[32rem] h-[32rem] bg-gradient-purple opacity-20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-slide-in-up">
            <div class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-white/40 rounded-full px-6 py-2 mb-8 shadow-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse-glow"></div>
                <span class="text-sm font-semibold text-gray-700">{{ __('We\'re Here to Help') }}</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-gray-900 mb-6">
                {{ __('Get In') }} <span class="text-gradient">{{ __('Touch') }}</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('Have questions, feedback, or need support? We\'d love to hear from you!') }}
            </p>
        </div>
    </div>
</div>

<!-- Contact Content Section -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="animate-slide-in-up">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Contact Information') }}</h2>

                <div class="space-y-6">
                    <div class="card-premium">
                        <div class="flex items-start">
                            <div class="icon-badge bg-gradient-blue mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">{{ __('General Inquiries') }}</h3>
                                <p class="text-gray-700 mb-1">
                                    <a href="mailto:mindova.ai@gmail.com" class="text-blue-600 hover:text-blue-700 font-medium">mindova.ai@gmail.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-premium">
                        <div class="flex items-start">
                            <div class="icon-badge bg-gradient-green mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">{{ __('Support') }}</h3>
                                <p class="text-gray-700 mb-1">
                                    <a href="mailto:mindova.ai@gmail.com" class="text-blue-600 hover:text-blue-700 font-medium">mindova.ai@gmail.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-premium">
                        <div class="flex items-start">
                            <div class="icon-badge bg-gradient-purple mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">{{ __('Business Partnerships') }}</h3>
                                <p class="text-gray-700 mb-1">
                                    <a href="mailto:mindova.ai@gmail.com" class="text-blue-600 hover:text-blue-700 font-medium">mindova.ai@gmail.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-premium">
                        <div class="flex items-start">
                            <div class="icon-badge bg-gradient-orange mr-4 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">{{ __('Privacy & Legal') }}</h3>
                                <p class="text-gray-700 mb-1">
                                    <a href="mailto:mindova.ai@gmail.com" class="text-blue-600 hover:text-blue-700 font-medium">mindova.ai@gmail.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 card-premium bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-blue-200">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ __('Helpful Resources') }}
                    </h3>
                    <div class="space-y-2">
                        <p class="text-gray-700">
                            <a href="{{ route('help') }}" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">{{ __('Help Center →') }}</a> {{ __('FAQs and guides') }}
                        </p>
                        <p class="text-gray-700">
                            <a href="{{ route('guidelines') }}" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">{{ __('Community Guidelines →') }}</a> {{ __('Best practices') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="animate-slide-in-up" style="animation-delay: 0.1s;">
                <div class="card-premium bg-gradient-to-br from-white to-blue-50/30 border-2 border-blue-100 px-6 sm:px-8 md:px-10 lg:px-12 xl:px-14 py-8 sm:py-10 md:py-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">{{ __('Send us a Message') }}</h2>
                    <form action="#" method="POST" class="space-y-5 sm:space-y-6 md:space-y-7">
                        @csrf
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Name') }}</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base" placeholder="{{ __('Your name') }}">
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Email') }}</label>
                            <input type="email" name="email" required class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base" placeholder="{{ __('your@email.com') }}">
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Subject') }}</label>
                            <select name="subject" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm sm:text-base">
                                <option>{{ __('General Inquiry') }}</option>
                                <option>{{ __('Technical Support') }}</option>
                                <option>{{ __('Business Partnership') }}</option>
                                <option>{{ __('Feedback') }}</option>
                                <option>{{ __('Other') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Message') }}</label>
                            <textarea name="message" rows="6" required class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all resize-none text-sm sm:text-base" placeholder="{{ __('Tell us how we can help...') }}"></textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-blue text-white font-bold text-base sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-xl transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl group">
                            <span class="flex items-center justify-center">
                                {{ __('Send Message') }}
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-animated text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-3xl md:text-4xl font-bold mb-4">{{ __('Need Immediate Assistance?') }}</h3>
        <p class="text-lg text-white/90 mb-8">{{ __('Check out our Help Center for instant answers to common questions') }}</p>
        <a href="{{ route('help') }}" class="inline-flex items-center bg-white text-blue-600 hover:text-blue-700 font-semibold text-lg px-8 py-3 rounded-xl transition-all transform hover:scale-105 shadow-2xl">
            {{ __('Visit Help Center') }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>
@endsection
