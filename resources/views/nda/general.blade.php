@extends('layouts.app')

@section('title', __('Sign General NDA'))

@push('styles')
<style>
    /* Premium NDA Styles */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        50% { box-shadow: 0 0 20px 10px rgba(99, 102, 241, 0.1); }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes check-bounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animate-float { animation: float 8s-out infinite; }
    .animate-slide-up { animation: slide-up 0.6s forwards; }
    .animate-pulse-glow { animation: pulse-glow 2s-out infinite; }
    .animate-check-bounce { animation: check-bounce 0.4s forwards; }

    .gradient-animate {
        background-size: 200% 200%;
        animation: gradient-shift 4s ease infinite;
    }

    /* NDA Document Styles */
    .nda-document {
        position: relative;
        background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .nda-document::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary-horizontal);
    }

    .nda-content {
        font-family: 'Georgia', 'Times New Roman', serif;
        line-height: 1.8;
        color: #374151;
    }

    .nda-content::-webkit-scrollbar {
        width: 8px;
    }

    .nda-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .nda-content::-webkit-scrollbar-thumb {
        background: var(--gradient-primary-vertical);
        border-radius: 10px;
    }

    /* Signature Box */
    .signature-box {
        position: relative;
        transition: all 0.3s ease;
        border: 2px solid var(--color-gray-200);
    }

    .signature-box:hover {
        border-color: var(--color-primary-300);
    }

    .signature-box:focus-within {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 4px var(--shadow-color-primary-light);
    }

    .signature-input {
        font-family: 'Brush Script MT', 'Segoe Script', cursive;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }

    /* Consent Checkbox */
    .consent-checkbox {
        appearance: none;
        width: 24px;
        height: 24px;
        border: 2px solid var(--color-gray-300);
        border-radius: 6px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .consent-checkbox:hover {
        border-color: var(--color-primary);
        background: var(--color-secondary-50);
    }

    .consent-checkbox:checked {
        background: var(--gradient-primary);
        border-color: var(--color-primary);
    }

    .consent-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 7px;
        top: 3px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
        animation: check-bounce 0.3s;
    }

    /* Premium Button */
    .btn-sign {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .btn-sign::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-sign:hover::before {
        left: 100%;
    }

    .btn-sign:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.4);
    }

    .btn-sign:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Progress Steps */
    .step-completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .step-active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    }

    /* Trust Badges */
    .trust-badge {
        transition: all 0.3s ease;
    }

    .trust-badge:hover {
        transform: translateY(-2px);
    }

    /* Read Progress Indicator */
    .read-progress {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: #e5e7eb;
        z-index: 10;
    }

    .read-progress-bar {
        height: 100%;
        background: var(--gradient-primary-horizontal);
        transition: width 0.1s ease;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">

    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-primary-500 py-12 mb-10 mx-4 sm:mx-8 lg:mx-auto lg:max-w-6xl rounded-[2.5rem] shadow-2xl gradient-animate">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-96 h-96 bg-cyan-300/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/2 w-72 h-72 bg-pink-300/10 rounded-full blur-3xl"></div>

            <!-- Security Pattern -->
            <div class="absolute inset-0 opacity-5">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="shield-pattern" width="60" height="60" patternUnits="userSpaceOnUse">
                            <path d="M30 5 L50 15 L50 30 C50 42 40 52 30 55 C20 52 10 42 10 30 L10 15 Z" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#shield-pattern)" />
                </svg>
            </div>
        </div>

        <div class="relative max-w-4xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center">
                <!-- Security Badge -->
                <div class="inline-flex items-center gap-3 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full px-6 py-2.5 mb-6 shadow-xl">
                    <svg class="w-5 h-5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-bold text-white tracking-wide">{{ __('Secure Legal Document') }}</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                    {{ __('Non-Disclosure') }}
                    <span class="relative inline-block">
                        <span class="text-secondary-200">{{ __('Agreement') }}</span>
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none">
                            <path d="M2 10C50 2 150 2 198 10" stroke="rgba(255,255,255,0.4)" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="text-lg md:text-xl text-white/90 font-medium max-w-2xl mx-auto leading-relaxed">
                    {{ __('Protect confidential information and build trust with our community by signing this agreement.') }}
                </p>

                <!-- Step Progress Indicator -->
                <div class="flex items-center justify-center gap-4 mt-10">
                    <div class="flex items-center gap-2">
                        <div class="step-completed w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-white/70 font-medium hidden sm:inline">{{ __('Profile') }}</span>
                    </div>
                    <div class="w-12 h-0.5 bg-white/50 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="step-active w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shadow-lg-glow">
                            2
                        </div>
                        <span class="text-white font-medium hidden sm:inline">{{ __('NDA') }}</span>
                    </div>
                    <div class="w-12 h-0.5 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white font-bold">
                            3
                        </div>
                        <span class="text-white/60 font-medium hidden sm:inline">{{ __('Dashboard') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        <!-- Trust Indicators -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="trust-badge bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100">
                <div class="w-12 h-12 mx-auto bg-emerald-100 rounded-xl flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-700">{{ __('Legally Binding') }}</p>
            </div>
            <div class="trust-badge bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100">
                <div class="w-12 h-12 mx-auto bg-blue-100 rounded-xl flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-700">{{ __('Encrypted Storage') }}</p>
            </div>
            <div class="trust-badge bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100">
                <div class="w-12 h-12 mx-auto bg-violet-100 rounded-xl flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-700">{{ __('Digital Signature') }}</p>
            </div>
            <div class="trust-badge bg-white rounded-2xl p-4 text-center shadow-sm border border-gray-100">
                <div class="w-12 h-12 mx-auto bg-amber-100 rounded-xl flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-700">{{ __('2 Min Process') }}</p>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="bg-primary-50 border-2 border-blue-200 rounded-2xl p-5 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900 text-lg mb-1">{{ __('Why is this required?') }}</h3>
                    <p class="text-sm text-blue-700 leading-relaxed">
                        {{ __('All volunteers must sign a general NDA before participating in challenges. This protects both you and the companies sharing their confidential information on our platform, ensuring a safe and trustworthy environment for innovation.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- NDA Document Card -->
        <div class="nda-document bg-white rounded-[2rem] shadow-2xl overflow-hidden mb-8">

            <!-- Document Header -->
            <div class="bg-gray-50 px-8 sm:px-12 py-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">{{ $nda->title }}</h2>
                            <p class="text-sm text-gray-500">{{ __('Please read the entire agreement before signing') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold">
                            {{ __('Version') }} {{ $nda->version }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Document Content -->
            <div class="px-8 sm:px-12 py-8">
                <!-- Read Progress -->
                <div class="read-progress rounded-full overflow-hidden mb-6">
                    <div class="read-progress-bar" id="readProgress" style="width: 0%"></div>
                </div>

                <!-- NDA Text Content -->
                <div class="nda-content border-2 border-gray-200 rounded-2xl p-8 bg-white max-h-[500px] overflow-y-auto" id="ndaContent">
                    <div class="prose prose-slate max-w-none whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $nda->content }}</div>
                </div>

                <div class="flex items-center justify-between mt-4 text-sm">
                    <span class="text-gray-500" id="scrollStatus">{{ __('Scroll to read the complete agreement') }}</span>
                    <span class="text-indigo-600 font-semibold" id="readPercentage">0%</span>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="bg-gray-50 px-8 sm:px-12 py-8 border-t border-gray-100">
                <form method="POST" action="{{ route('nda.general.sign') }}" class="space-y-8" id="ndaForm">
                    @csrf

                    <!-- Signature Input -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                            <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </span>
                            {{ __('Your Legal Signature') }} <span class="text-red-500">*</span>
                        </label>

                        <div class="signature-box bg-white rounded-2xl p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex-1 border-b-2 border-dashed border-gray-300 pb-2">
                                    <input type="text" id="full_name" name="full_name"
                                           value="{{ old('full_name', $volunteer->user->name) }}"
                                           required
                                           class="signature-input w-full bg-transparent border-none outline-none text-indigo-700 placeholder-gray-400"
                                           placeholder="{{ __('Type your full legal name') }}">
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ __('Date') }}</p>
                                    <p class="text-sm font-bold text-gray-700">{{ now()->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('This should match the name on your official documents') }}
                            </p>
                        </div>
                        @error('full_name')
                        <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Consent Checkbox -->
                    <div class="bg-gray-50 border-2 border-amber-200 rounded-2xl p-6">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <input type="checkbox" id="agree" name="agree" required
                                   class="consent-checkbox mt-1 flex-shrink-0">
                            <div>
                                <span class="text-sm font-bold text-gray-900 block mb-1">
                                    {{ __('I have read and agree to the terms of this Non-Disclosure Agreement') }} <span class="text-red-500">*</span>
                                </span>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    {{ __('By checking this box and submitting this form, you are electronically signing this agreement and agree to be legally bound by its terms.') }}
                                </p>
                            </div>
                        </label>
                        @error('agree')
                        <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Digital Signature Notice -->
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm mb-2">{{ __('Digital Signature Notice') }}</h4>
                                <p class="text-xs text-gray-600 mb-3">{{ __('Your electronic signature will be recorded along with:') }}</p>
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('Date and time of signing') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('IP address:') }} {{ request()->ip() }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('Browser information') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $volunteer->user->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <x-ui.button as="a" href="{{ route('dashboard') }}" variant="ghost" class="order-2 sm:order-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('Cancel') }}
                        </x-ui.button>
                        <x-ui.button as="submit" id="submitBtn" disabled variant="primary" size="lg" class="order-1 sm:order-2 w-full sm:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            {{ __('Sign NDA & Continue') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Support Section -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                {{ __('Have questions about this NDA?') }}
                <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold ml-1 inline-flex items-center gap-1">
                    {{ __('Contact our support team') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ndaContent = document.getElementById('ndaContent');
    const readProgress = document.getElementById('readProgress');
    const readPercentage = document.getElementById('readPercentage');
    const scrollStatus = document.getElementById('scrollStatus');
    const submitBtn = document.getElementById('submitBtn');
    const agreeCheckbox = document.getElementById('agree');
    const fullNameInput = document.getElementById('full_name');

    let hasReadAll = false;

    // Update submit button state
    function updateSubmitButton() {
        const isValid = hasReadAll && agreeCheckbox.checked && fullNameInput.value.trim().length > 0;
        submitBtn.disabled = !isValid;

        if (isValid) {
            submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
        } else {
            submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
        }
    }

    // Check if content requires scrolling
    function checkScrollRequired() {
        const scrollableHeight = ndaContent.scrollHeight - ndaContent.clientHeight;

        // If content fits in container (no scroll needed), mark as read
        if (scrollableHeight <= 0) {
            hasReadAll = true;
            readProgress.style.width = '100%';
            readPercentage.textContent = '100%';
            scrollStatus.textContent = '{{ __("You have read the complete agreement") }}';
            scrollStatus.classList.add('text-emerald-600', 'font-semibold');
            scrollStatus.classList.remove('text-gray-500');
            updateSubmitButton();
        }
    }

    // Run check after content loads
    checkScrollRequired();

    // Track scroll progress
    ndaContent.addEventListener('scroll', function() {
        const scrollTop = this.scrollTop;
        const scrollableHeight = this.scrollHeight - this.clientHeight;

        // Prevent division by zero
        if (scrollableHeight <= 0) {
            return;
        }

        const progress = Math.min((scrollTop / scrollableHeight) * 100, 100);

        readProgress.style.width = progress + '%';
        readPercentage.textContent = Math.round(progress) + '%';

        if (progress >= 95) {
            hasReadAll = true;
            scrollStatus.textContent = '{{ __("You have read the complete agreement") }}';
            scrollStatus.classList.add('text-emerald-600', 'font-semibold');
            scrollStatus.classList.remove('text-gray-500');
            updateSubmitButton();
        }
    });

    agreeCheckbox.addEventListener('change', updateSubmitButton);
    fullNameInput.addEventListener('input', updateSubmitButton);

    // Form submission
    document.getElementById('ndaForm').addEventListener('submit', function(e) {
        if (!hasReadAll) {
            e.preventDefault();
            alert('{{ __("Please read the entire agreement before signing.") }}');
            ndaContent.scrollIntoView({ behavior: 'smooth' });
            return false;
        }

        if (!agreeCheckbox.checked) {
            e.preventDefault();
            alert('{{ __("Please check the agreement checkbox.") }}');
            agreeCheckbox.focus();
            return false;
        }

        if (!fullNameInput.value.trim()) {
            e.preventDefault();
            alert('{{ __("Please enter your full legal name.") }}');
            fullNameInput.focus();
            return false;
        }

        // Confirm before submission
        if (!confirm('{{ __("By clicking OK, you confirm that you have read and understood the NDA and agree to be legally bound by its terms. Do you wish to proceed?") }}')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
