@extends('layouts.app')

@section('title', __('Contact Us'))

@section('content')
<!-- Hero Section -->
<div class="bg-gray-50 pt-24 pb-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 mb-6">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ __('We\'re Here to Help') }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('Get In') }} <span class="text-primary-600">{{ __('Touch') }}</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-xl mx-auto">
                {{ __('Have questions, feedback, or need support? We\'d love to hear from you!') }}
            </p>
        </div>
    </div>
</div>

<!-- Contact Content Section -->
<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Contact Information') }}</h2>

                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center border border-primary-200 flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ __('General Inquiries') }}</h3>
                                <a href="mailto:mindova.ai@gmail.com" class="text-primary-600 hover:text-primary-700 text-sm font-medium">mindova.ai@gmail.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center border border-emerald-200 flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ __('Support') }}</h3>
                                <a href="mailto:mindova.ai@gmail.com" class="text-primary-600 hover:text-primary-700 text-sm font-medium">mindova.ai@gmail.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center border border-violet-200 flex-shrink-0">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ __('Business Partnerships') }}</h3>
                                <a href="mailto:mindova.ai@gmail.com" class="text-primary-600 hover:text-primary-700 text-sm font-medium">mindova.ai@gmail.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center border border-amber-200 flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ __('Privacy & Legal') }}</h3>
                                <a href="mailto:mindova.ai@gmail.com" class="text-primary-600 hover:text-primary-700 text-sm font-medium">mindova.ai@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{ __('Helpful Resources') }}
                    </h3>
                    <div class="space-y-2">
                        <p class="text-sm">
                            <a href="{{ route('help') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Help Center →') }}</a>
                            <span class="text-gray-600">{{ __('FAQs and guides') }}</span>
                        </p>
                        <p class="text-sm">
                            <a href="{{ route('guidelines') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Community Guidelines →') }}</a>
                            <span class="text-gray-600">{{ __('Best practices') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Send us a Message') }}</h2>
                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Name') }}</label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm" placeholder="{{ __('Your name') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Email') }}</label>
                            <input type="email" name="email" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm" placeholder="{{ __('your@email.com') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Subject') }}</label>
                            <select name="subject" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm">
                                <option>{{ __('General Inquiry') }}</option>
                                <option>{{ __('Technical Support') }}</option>
                                <option>{{ __('Business Partnership') }}</option>
                                <option>{{ __('Feedback') }}</option>
                                <option>{{ __('Other') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Message') }}</label>
                            <textarea name="message" rows="5" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg resize-none text-sm" placeholder="{{ __('Tell us how we can help...') }}"></textarea>
                        </div>

                        <x-ui.button as="submit" variant="primary" size="sm" fullWidth>
                            {{ __('Send Message') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-primary-500 border-t border-primary-400">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-xl md:text-2xl font-bold text-white mb-3">{{ __('Need Immediate Assistance?') }}</h3>
        <p class="text-white/90 mb-6 text-sm">{{ __('Check out our Help Center for instant answers to common questions') }}</p>
        <x-ui.button as="a" href="{{ route('help') }}" variant="secondary" size="sm">
            {{ __('Visit Help Center') }}
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </x-ui.button>
    </div>
</section>
@endsection
