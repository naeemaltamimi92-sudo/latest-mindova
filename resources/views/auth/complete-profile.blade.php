@extends('layouts.app')

@section('title', __('Complete Your Profile'))

@push('styles')
<style>
    /* Premium Profile Wizard Styles */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(3deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce-in {
        0% { opacity: 0; transform: scale(0.3); }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }

    .animate-float { animation: float 8s-out infinite; }
    .animate-slide-up { animation: slide-up 0.6s forwards; }
    .animate-bounce-in { animation: bounce-in 0.6s forwards; }

    .gradient-animate {
        background-size: 200% 200%;
        animation: gradient-shift 4s ease infinite;
    }

    .shimmer-effect {
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    /* Step Indicator Styles */
    .step-circle {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step-circle.active {
        transform: scale(1.1);
        box-shadow: 0 0 0 4px var(--shadow-color-success-light, rgba(16, 185, 129, 0.2));
    }

    .step-circle.completed {
        background: linear-gradient(135deg, var(--color-success, #10b981) 0%, var(--color-success-dark, #059669) 100%);
    }

    /* Form Field Enhancements */
    .form-field-premium {
        position: relative;
        transition: all 0.3s ease;
    }

    .form-field-premium:focus-within {
        transform: translateY(-2px);
    }

    .form-field-premium label {
        transition: all 0.3s ease;
    }

    .form-field-premium:focus-within label {
        color: var(--color-success, #10b981);
    }

    .input-premium {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .input-premium:hover {
        border-color: #d1d5db;
    }

    .input-premium:focus {
        border-color: var(--color-success, #10b981);
        box-shadow: 0 0 0 4px var(--shadow-color-success-light, rgba(16, 185, 129, 0.1));
        outline: none;
    }

    /* CV Upload Zone */
    .cv-upload-zone {
        transition: all 0.3s ease;
        border: 3px dashed #d1d5db;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    }

    .cv-upload-zone:hover,
    .cv-upload-zone.dragover {
        border-color: var(--color-success, #10b981);
        background: linear-gradient(135deg, var(--color-success-50, #ecfdf5) 0%, var(--color-success-100, #d1fae5) 100%);
        transform: scale(1.02);
    }

    .cv-upload-zone.has-file {
        border-color: var(--color-success, #10b981);
        border-style: solid;
        background: linear-gradient(135deg, var(--color-success-50, #ecfdf5) 0%, var(--color-success-100, #d1fae5) 100%);
    }

    /* Premium Button */
    .btn-premium {
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .btn-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-premium:hover::before {
        left: 100%;
    }

    .btn-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -10px var(--shadow-color-success, rgba(16, 185, 129, 0.4));
    }

    /* Avatar Preview */
    .avatar-preview-container {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .avatar-preview-ring {
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-success, #10b981), var(--color-cyan, #06b6d4), var(--color-secondary, #8b5cf6));
        animation: spin 3s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Field expertise cards */
    .field-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .field-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
    }

    .field-card.selected {
        border-color: var(--color-success, #10b981);
        background: linear-gradient(135deg, var(--color-success-50, #ecfdf5) 0%, var(--color-success-100, #d1fae5) 100%);
        transform: scale(1.02);
    }

    .field-card.selected .field-icon {
        background: linear-gradient(135deg, var(--color-success, #10b981) 0%, var(--color-success-dark, #059669) 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8">

    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-secondary-500 py-12 mb-10 mx-4 sm:mx-8 lg:mx-auto lg:max-w-6xl rounded-[2.5rem] shadow-2xl">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/2 w-72 h-72 bg-cyan-300/10 rounded-full blur-3xl"></div>

            <!-- Decorative Grid -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
        </div>

        <div class="relative max-w-4xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center">
                <!-- Welcome Badge -->
                <div class="inline-flex items-center gap-3 bg-white/20 backdrop-blur-xl border border-white/30 rounded-full px-6 py-2.5 mb-6 shadow-xl">
                    <div class="relative">
                        <div class="w-3 h-3 bg-emerald-300 rounded-full"></div>
                        <div class="absolute inset-0 w-3 h-3 bg-emerald-300 rounded-full"></div>
                    </div>
                    <span class="text-sm font-bold text-white tracking-wide">{{ __('Welcome to MINDOVA') }}</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                    {{ __('Complete Your') }}
                    <span class="relative inline-block">
                        <span class="text-secondary-300">{{ __('Profile') }}</span>
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none">
                            <path d="M2 10C50 2 150 2 198 10" stroke="rgba(255,255,255,0.4)" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>

                <p class="text-lg md:text-xl text-white/90 font-medium max-w-2xl mx-auto leading-relaxed">
                    {{ __('Set up your profile to unlock personalized challenges, AI-powered skill matching, and join a global community of innovators.') }}
                </p>

                <!-- Step Progress Indicator -->
                <div class="flex items-center justify-center gap-4 mt-10">
                    <div class="flex items-center gap-2">
                        <div class="step-circle active w-10 h-10 bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold shadow-lg">
                            1
                        </div>
                        <span class="text-white/90 font-medium hidden sm:inline">{{ __('Profile') }}</span>
                    </div>
                    <div class="w-12 h-0.5 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="step-circle w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white font-bold">
                            2
                        </div>
                        <span class="text-white/60 font-medium hidden sm:inline">{{ __('NDA') }}</span>
                    </div>
                    <div class="w-12 h-0.5 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="step-circle w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white font-bold">
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

        @if(request()->get('type') === 'volunteer' || auth()->user()->isVolunteer())
        <!-- Volunteer Profile Form -->
        <div class="bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden">

            <!-- Form Header -->
            <div class="bg-gray-50 px-8 sm:px-12 py-8 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-secondary-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">{{ __('Volunteer Profile Setup') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('Tell us about your expertise to get matched with relevant challenges') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('complete-profile.volunteer') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12 space-y-10" id="profileForm">
                @csrf

                <!-- Field of Expertise Section -->
                <div class="form-field-premium">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        {{ __('Field of Expertise') }} <span class="text-red-500">*</span>
                    </label>

                    <!-- Visual Field Selection -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-4" id="fieldCards">
                        @php
                            $fields = [
                                ['name' => 'Chemical Engineering', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>'],
                                ['name' => 'Technology', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                                ['name' => 'Healthcare', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                                ['name' => 'Finance', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                ['name' => 'Marketing', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>'],
                                ['name' => 'Education', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>'],
                                ['name' => 'Engineering', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                                ['name' => 'Design', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>'],
                                ['name' => 'Business', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                                ['name' => 'Other', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>'],
                            ];
                        @endphp
                        @foreach($fields as $field)
                        <div class="field-card bg-white border-2 border-gray-100 rounded-xl p-4 text-center hover:shadow-lg {{ old('field') == $field['name'] ? 'selected' : '' }}"
                             data-field="{{ $field['name'] }}" onclick="selectField('{{ $field['name'] }}')">
                            <div class="field-icon w-12 h-12 mx-auto bg-gray-100 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $field['icon'] !!}</svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ __($field['name']) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="field" id="selectedField" value="{{ old('field') }}" required>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('This determines which challenges and tasks you\'ll see') }}
                    </p>
                    @error('field')
                    <p class="text-red-600 text-sm mt-2 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Availability Section -->
                <div class="form-field-premium">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        {{ __('Weekly Availability') }} <span class="text-red-500">*</span>
                    </label>

                    <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6">
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <div class="flex-1 w-full">
                                <input type="range" name="availability_hours_per_week" id="availabilitySlider"
                                       min="1" max="40" value="{{ old('availability_hours_per_week', 10) }}"
                                       class="w-full h-3 bg-white rounded-lg appearance-none cursor-pointer accent-teal-500"
                                       oninput="updateAvailability(this.value)">
                                <div class="flex justify-between text-xs text-gray-500 mt-2 font-medium">
                                    <span>1h</span>
                                    <span>10h</span>
                                    <span>20h</span>
                                    <span>30h</span>
                                    <span>40h</span>
                                </div>
                            </div>
                            <div class="text-center bg-white rounded-2xl px-8 py-4 shadow-sm border border-cyan-100">
                                <div class="text-4xl font-black text-teal-600" id="availabilityDisplay">{{ old('availability_hours_per_week', 10) }}</div>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Hours/Week') }}</div>
                            </div>
                        </div>
                    </div>
                    @error('availability_hours_per_week')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio Section -->
                <div class="form-field-premium">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </span>
                        {{ __('About You') }} <span class="text-gray-400 text-xs font-normal">({{ __('Optional') }})</span>
                    </label>

                    <div class="relative">
                        <textarea name="bio" rows="4" id="bioTextarea"
                                  class="input-premium w-full px-5 py-4 rounded-2xl text-gray-800 placeholder-gray-400 resize-none"
                                  placeholder="{{ __('Share your experience, interests, and what motivates you to contribute...') }}"
                                  maxlength="500">{{ old('bio') }}</textarea>
                        <div class="absolute bottom-4 right-4 text-xs text-gray-400">
                            <span id="bioCharCount">{{ strlen(old('bio', '')) }}</span>/500
                        </div>
                    </div>
                    @error('bio')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CV Upload Section -->
                <div class="form-field-premium">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </span>
                        {{ __('Upload Your CV') }}
                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ __('AI-Powered') }}</span>
                    </label>

                    <div class="cv-upload-zone rounded-2xl p-8 text-center cursor-pointer" id="cvDropZone" onclick="document.getElementById('cvInput').click()">
                        <input type="file" name="cv" id="cvInput" accept=".pdf,.doc,.docx" class="hidden" onchange="handleCVUpload(this)">

                        <div id="cvUploadContent">
                            <div class="w-20 h-20 mx-auto bg-secondary-300 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ __('Drop your CV here') }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ __('or click to browse files') }}</p>
                            <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full text-xs font-medium text-gray-600 border border-gray-200">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('PDF, DOC, DOCX (Max 10MB)') }}
                            </div>
                        </div>

                        <div id="cvFileInfo" class="hidden">
                            <div class="w-20 h-20 mx-auto bg-secondary-100 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-emerald-700 mb-1" id="cvFileName">{{ __('File Selected') }}</h3>
                            <p class="text-sm text-gray-500" id="cvFileSize"></p>
                            <x-ui.button variant="link" size="sm" onclick="removeCVFile(event)" class="mt-3 text-red-500 hover:text-red-700">
                                {{ __('Remove') }}
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Student Option -->
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_student" id="isStudentCheckbox"
                                   value="1" {{ old('is_student') ? 'checked' : '' }}
                                   class="mt-1 w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                   onchange="toggleStudentMode(this.checked)">
                            <div>
                                <span class="font-bold text-gray-900">{{ __('I am a student') }}</span>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ __('Select this if you are currently enrolled in a degree program. Your profile will be marked as "Student" level and CV analysis will be skipped.') }}
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- AI Analysis Info -->
                    <div id="aiAnalysisInfo" class="mt-4 bg-secondary-50 border border-secondary-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-amber-800 text-sm">{{ __('AI-Powered Analysis') }}</h4>
                                <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                                    {{ __('Your CV will be analyzed by our AI to automatically extract your skills, experience level, and education. This helps match you with the most relevant challenges and tasks.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @error('cv')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NDA Notice -->
                <div class="bg-gray-50 border-2 border-primary-200 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-900 text-lg mb-2">{{ __('Next Step: NDA Signature') }}</h3>
                            <p class="text-sm text-blue-700 leading-relaxed">
                                {{ __('After completing your profile, you\'ll be asked to sign a Non-Disclosure Agreement (NDA). This protects both you and the companies sharing confidential information on our platform.') }}
                            </p>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center gap-2 text-xs text-blue-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="font-semibold">{{ __('Secure Digital Signature') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-blue-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="font-semibold">{{ __('Takes 2 minutes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <x-ui.button as="submit" variant="primary" size="xl" fullWidth class="btn-premium rounded-2xl">
                        <span class="relative z-10 inline-flex items-center justify-center w-full gap-3">
                            <span>{{ __('Complete Profile & Continue to NDA') }}</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </x-ui.button>

                    <p class="text-center text-xs text-gray-500 mt-4">
                        {{ __('By completing your profile, you agree to our') }}
                        <a href="#" class="text-emerald-600 hover:underline font-semibold">{{ __('Terms of Service') }}</a>
                        {{ __('and') }}
                        <a href="#" class="text-emerald-600 hover:underline font-semibold">{{ __('Privacy Policy') }}</a>
                    </p>
                </div>
            </form>
        </div>

        @else
        <!-- Company Profile Form -->
        <div class="bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden">

            <!-- Form Header -->
            <div class="bg-gray-50 px-8 sm:px-12 py-8 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-primary-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">{{ __('Company Profile Setup') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('Set up your organization to start posting challenges') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('complete-profile.company') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-field-premium">
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('Company Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" required
                               class="input-premium w-full px-5 py-4 rounded-xl" value="{{ old('company_name') }}">
                        @error('company_name')
                        <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-field-premium">
                        <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('Commercial Register Number') }}</label>
                        <input type="text" name="commercial_register"
                               class="input-premium w-full px-5 py-4 rounded-xl" value="{{ old('commercial_register') }}"
                               placeholder="{{ __('e.g., CR-123456789') }}">
                        @error('commercial_register')
                        <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Industry Selection -->
                <div class="form-field-premium">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </span>
                        {{ __('Industry') }}
                    </label>

                    @php
                        $industries = [
                            ['name' => 'Technology', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                            ['name' => 'Healthcare', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                            ['name' => 'Finance', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                            ['name' => 'Manufacturing', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>'],
                            ['name' => 'E-commerce', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                            ['name' => 'Education', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>'],
                            ['name' => 'Energy', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                            ['name' => 'Real Estate', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                            ['name' => 'Consulting', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                            ['name' => 'Other', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>'],
                        ];
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-4" id="industryCards">
                        @foreach($industries as $industry)
                        <div class="field-card bg-white border-2 border-gray-100 rounded-xl p-4 text-center hover:shadow-lg {{ old('industry') == $industry['name'] ? 'selected' : '' }}"
                             data-industry="{{ $industry['name'] }}" onclick="selectIndustry('{{ $industry['name'] }}')">
                            <div class="field-icon w-12 h-12 mx-auto bg-gray-100 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $industry['icon'] !!}</svg>
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ __($industry['name']) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="industry" id="selectedIndustry" value="{{ old('industry') }}">
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Select the industry that best describes your company') }}
                    </p>
                    @error('industry')
                    <p class="text-red-600 text-sm mt-2 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Website -->
                <div class="form-field-premium">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('Website') }}</label>
                    <input type="url" name="website"
                           class="input-premium w-full px-5 py-4 rounded-xl" value="{{ old('website') }}"
                           placeholder="{{ __('https://yourcompany.com') }}">
                    @error('website')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field-premium">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('Description') }}</label>
                    <textarea name="description" rows="4"
                              class="input-premium w-full px-5 py-4 rounded-xl resize-none"
                              placeholder="{{ __('Tell us about your company and your mission...') }}">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field-premium">
                    <label class="block text-sm font-bold text-gray-900 mb-2">{{ __('Company Logo') }} <span class="text-gray-400 text-xs font-normal">({{ __('Optional') }})</span></label>
                    <input type="file" name="logo" accept="image/*" class="input-premium w-full px-5 py-4 rounded-xl">
                    @error('logo')
                    <p class="text-red-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <x-ui.button as="submit" variant="primary" size="xl" fullWidth class="btn-premium rounded-2xl">
                        <span class="relative z-10 inline-flex items-center justify-center w-full gap-3">
                            <span>{{ __('Complete Profile') }}</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </span>
                    </x-ui.button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
// Student Mode Toggle
function toggleStudentMode(isStudent) {
    const aiAnalysisInfo = document.getElementById('aiAnalysisInfo');
    if (aiAnalysisInfo) {
        aiAnalysisInfo.style.display = isStudent ? 'none' : 'block';
    }
}

// Initialize student mode on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('isStudentCheckbox');
    if (checkbox) {
        toggleStudentMode(checkbox.checked);
    }
});

// Field Selection
function selectField(fieldName) {
    document.querySelectorAll('.field-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[data-field="${fieldName}"]`).classList.add('selected');
    document.getElementById('selectedField').value = fieldName;
}

// Industry Selection (Company)
function selectIndustry(industryName) {
    document.querySelectorAll('#industryCards .field-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[data-industry="${industryName}"]`).classList.add('selected');
    document.getElementById('selectedIndustry').value = industryName;
}

// Availability Slider
function updateAvailability(value) {
    document.getElementById('availabilityDisplay').textContent = value;
}

// Bio Character Count
document.getElementById('bioTextarea')?.addEventListener('input', function() {
    document.getElementById('bioCharCount').textContent = this.value.length;
});

// CV Upload Handling
const cvDropZone = document.getElementById('cvDropZone');
const cvInput = document.getElementById('cvInput');

if (cvDropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        cvDropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        cvDropZone.addEventListener(eventName, () => cvDropZone.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        cvDropZone.addEventListener(eventName, () => cvDropZone.classList.remove('dragover'), false);
    });

    cvDropZone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length) {
            cvInput.files = files;
            handleCVUpload(cvInput);
        }
    }, false);
}

function handleCVUpload(input) {
    const file = input.files[0];
    if (file) {
        const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!validTypes.includes(file.type)) {
            alert('{{ __("Please upload a PDF, DOC, or DOCX file.") }}');
            input.value = '';
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('{{ __("File size must be less than 10MB.") }}');
            input.value = '';
            return;
        }

        document.getElementById('cvUploadContent').classList.add('hidden');
        document.getElementById('cvFileInfo').classList.remove('hidden');
        document.getElementById('cvFileName').textContent = file.name;
        document.getElementById('cvFileSize').textContent = formatFileSize(file.size);
        document.getElementById('cvDropZone').classList.add('has-file');
    }
}

function removeCVFile(e) {
    e.stopPropagation();
    document.getElementById('cvInput').value = '';
    document.getElementById('cvUploadContent').classList.remove('hidden');
    document.getElementById('cvFileInfo').classList.add('hidden');
    document.getElementById('cvDropZone').classList.remove('has-file');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form Validation
document.getElementById('profileForm')?.addEventListener('submit', function(e) {
    const field = document.getElementById('selectedField').value;
    if (!field) {
        e.preventDefault();
        alert('{{ __("Please select your field of expertise.") }}');
        document.getElementById('fieldCards').scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection
