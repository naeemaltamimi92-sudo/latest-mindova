@extends('layouts.app')

@section('title', __('Complete Your Profile'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <!-- Clean Hero Section -->
    <div class="bg-secondary-500 py-10 mb-8 mx-4 sm:mx-8 lg:mx-auto lg:max-w-6xl rounded-2xl">
        <div class="max-w-4xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center">
                <!-- Welcome Badge -->
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 mb-5">
                    <div class="w-2 h-2 bg-emerald-300 rounded-full"></div>
                    <span class="text-sm font-medium text-white">{{ __('Welcome to MINDOVA') }}</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">
                    {{ __('Complete Your') }} <span class="text-secondary-200">{{ __('Profile') }}</span>
                </h1>

                <p class="text-base text-white/90 max-w-2xl mx-auto leading-relaxed">
                    {{ __('Set up your profile to unlock personalized challenges, AI-powered skill matching, and join a global community of innovators.') }}
                </p>

                <!-- Step Progress Indicator -->
                <div class="flex items-center justify-center gap-3 mt-8">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold text-sm">
                            1
                        </div>
                        <span class="text-white/90 text-sm font-medium hidden sm:inline">{{ __('Profile') }}</span>
                    </div>
                    <div class="w-8 h-0.5 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            2
                        </div>
                        <span class="text-white/60 text-sm font-medium hidden sm:inline">{{ __('NDA') }}</span>
                    </div>
                    <div class="w-8 h-0.5 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            3
                        </div>
                        <span class="text-white/60 text-sm font-medium hidden sm:inline">{{ __('Dashboard') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if(request()->get('type') === 'volunteer' || auth()->user()->isVolunteer())
        <!-- Volunteer Profile Form -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gray-50 px-6 sm:px-8 py-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('Volunteer Profile Setup') }}</h2>
                        <p class="text-sm text-gray-600">{{ __('Tell us about your expertise to get matched with relevant challenges') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('complete-profile.volunteer') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" id="profileForm">
                @csrf

                <!-- Field of Expertise Section -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-4">
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
                        <div class="field-card bg-white border-2 border-gray-200 rounded-xl p-4 text-center cursor-pointer hover:border-emerald-300 {{ old('field') == $field['name'] ? 'border-emerald-500 bg-emerald-50' : '' }}"
                             data-field="{{ $field['name'] }}" onclick="selectField('{{ $field['name'] }}')">
                            <div class="w-10 h-10 mx-auto bg-gray-100 rounded-lg flex items-center justify-center mb-2 {{ old('field') == $field['name'] ? 'bg-emerald-500' : '' }}">
                                <svg class="w-5 h-5 {{ old('field') == $field['name'] ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $field['icon'] !!}</svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">{{ __($field['name']) }}</span>
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
                    <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Availability Section -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-4">
                        <span class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        {{ __('Weekly Availability') }} <span class="text-red-500">*</span>
                    </label>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="flex-1 w-full">
                                <input type="range" name="availability_hours_per_week" id="availabilitySlider"
                                       min="1" max="40" value="{{ old('availability_hours_per_week', 10) }}"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                       oninput="updateAvailability(this.value)">
                                <div class="flex justify-between text-xs text-gray-500 mt-2">
                                    <span>1h</span>
                                    <span>10h</span>
                                    <span>20h</span>
                                    <span>30h</span>
                                    <span>40h</span>
                                </div>
                            </div>
                            <div class="text-center bg-white rounded-xl px-6 py-3 border border-cyan-200">
                                <div class="text-3xl font-bold text-cyan-600" id="availabilityDisplay">{{ old('availability_hours_per_week', 10) }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ __('Hours/Week') }}</div>
                            </div>
                        </div>
                    </div>
                    @error('availability_hours_per_week')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio Section -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-3">
                        <span class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </span>
                        {{ __('About You') }} <span class="text-gray-400 text-xs font-normal">({{ __('Optional') }})</span>
                    </label>

                    <div class="relative">
                        <textarea name="bio" rows="4" id="bioTextarea"
                                  class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 resize-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                                  placeholder="{{ __('Share your experience, interests, and what motivates you to contribute...') }}"
                                  maxlength="500">{{ old('bio') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                            <span id="bioCharCount">{{ strlen(old('bio', '')) }}</span>/500
                        </div>
                    </div>
                    @error('bio')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CV Upload Section -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </span>
                        {{ __('Upload Your CV') }}
                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">{{ __('AI-Powered') }}</span>
                    </label>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-400 bg-gray-50" id="cvDropZone" onclick="document.getElementById('cvInput').click()">
                        <input type="file" name="cv" id="cvInput" accept=".pdf,.doc,.docx" class="hidden" onchange="handleCVUpload(this)">

                        <div id="cvUploadContent">
                            <div class="w-14 h-14 mx-auto bg-amber-100 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 11115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">{{ __('Drop your CV here') }}</h3>
                            <p class="text-sm text-gray-500 mb-3">{{ __('or click to browse files') }}</p>
                            <span class="inline-flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-200">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('PDF, DOC, DOCX (Max 10MB)') }}
                            </span>
                        </div>

                        <div id="cvFileInfo" class="hidden">
                            <div class="w-14 h-14 mx-auto bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-emerald-700 mb-1" id="cvFileName">{{ __('File Selected') }}</h3>
                            <p class="text-sm text-gray-500" id="cvFileSize"></p>
                            <button type="button" onclick="removeCVFile(event)" class="mt-2 text-sm text-red-600 hover:text-red-700 font-medium">
                                {{ __('Remove') }}
                            </button>
                        </div>
                    </div>

                    <!-- Student Option -->
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_student" id="isStudentCheckbox"
                                   value="1" {{ old('is_student') ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 text-emerald-600 border-gray-300 rounded"
                                   onchange="toggleStudentMode(this.checked)">
                            <div>
                                <span class="font-semibold text-gray-900 text-sm">{{ __('I am a student') }}</span>
                                <p class="text-sm text-gray-600 mt-0.5">
                                    {{ __('Select this if you are currently enrolled in a degree program. Your profile will be marked as "Student" level and CV analysis will be skipped.') }}
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- AI Analysis Info -->
                    <div id="aiAnalysisInfo" class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-800 text-sm">{{ __('AI-Powered Analysis') }}</h4>
                                <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                                    {{ __('Your CV will be analyzed by our AI to automatically extract your skills, experience level, and education. This helps match you with the most relevant challenges and tasks.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @error('cv')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NDA Notice -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ __('Next Step: NDA Signature') }}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ __('After completing your profile, you\'ll be asked to sign a Non-Disclosure Agreement (NDA). This protects both you and the companies sharing confidential information on our platform.') }}
                            </p>
                            <div class="flex items-center gap-4 mt-3">
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="font-medium">{{ __('Secure Digital Signature') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="font-medium">{{ __('Takes 2 minutes') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <x-ui.button as="submit" variant="primary" size="lg" fullWidth>
                        {{ __('Complete Profile & Continue to NDA') }}
                        <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </x-ui.button>

                    <p class="text-center text-xs text-gray-500 mt-3">
                        {{ __('By completing your profile, you agree to our') }}
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Terms of Service') }}</a>
                        {{ __('and') }}
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Privacy Policy') }}</a>
                    </p>
                </div>
            </form>
        </div>

        @else
        <!-- Company Profile Form -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gray-50 px-6 sm:px-8 py-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('Company Profile Setup') }}</h2>
                        <p class="text-sm text-gray-600">{{ __('Set up your organization to start posting challenges') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('complete-profile.company') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Company Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" required
                               class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500" value="{{ old('company_name') }}">
                        @error('company_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Commercial Register Number') }}</label>
                        <input type="text" name="commercial_register"
                               class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500" value="{{ old('commercial_register') }}"
                               placeholder="{{ __('e.g., CR-123456789') }}">
                        @error('commercial_register')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Industry Selection -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-4">
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
                        <div class="field-card bg-white border-2 border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:border-primary-300 {{ old('industry') == $industry['name'] ? 'border-primary-500 bg-primary-50' : '' }}"
                             data-industry="{{ $industry['name'] }}" onclick="selectIndustry('{{ $industry['name'] }}')">
                            <div class="w-10 h-10 mx-auto bg-gray-100 rounded-lg flex items-center justify-center mb-2 {{ old('industry') == $industry['name'] ? 'bg-primary-500' : '' }}">
                                <svg class="w-5 h-5 {{ old('industry') == $industry['name'] ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $industry['icon'] !!}</svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">{{ __($industry['name']) }}</span>
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
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Website') }}</label>
                    <input type="url" name="website"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500" value="{{ old('website') }}"
                           placeholder="{{ __('https://yourcompany.com') }}">
                    @error('website')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Description') }}</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm resize-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                              placeholder="{{ __('Tell us about your company and your mission...') }}">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">{{ __('Company Logo') }} <span class="text-gray-400 text-xs font-normal">({{ __('Optional') }})</span></label>
                    <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm">
                    @error('logo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <x-ui.button as="submit" variant="primary" size="lg" fullWidth>
                        {{ __('Complete Profile') }}
                        <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
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
    document.querySelectorAll('#fieldCards .field-card').forEach(card => {
        card.classList.remove('border-emerald-500', 'bg-emerald-50');
        card.querySelector('.w-10').classList.remove('bg-emerald-500');
        card.querySelector('.w-10').classList.add('bg-gray-100');
        card.querySelector('svg').classList.remove('text-white');
        card.querySelector('svg').classList.add('text-gray-600');
    });
    const selected = document.querySelector(`[data-field="${fieldName}"]`);
    selected.classList.add('border-emerald-500', 'bg-emerald-50');
    selected.querySelector('.w-10').classList.remove('bg-gray-100');
    selected.querySelector('.w-10').classList.add('bg-emerald-500');
    selected.querySelector('svg').classList.remove('text-gray-600');
    selected.querySelector('svg').classList.add('text-white');
    document.getElementById('selectedField').value = fieldName;
}

// Industry Selection (Company)
function selectIndustry(industryName) {
    document.querySelectorAll('#industryCards .field-card').forEach(card => {
        card.classList.remove('border-primary-500', 'bg-primary-50');
        card.querySelector('.w-10').classList.remove('bg-primary-500');
        card.querySelector('.w-10').classList.add('bg-gray-100');
        card.querySelector('svg').classList.remove('text-white');
        card.querySelector('svg').classList.add('text-gray-600');
    });
    const selected = document.querySelector(`[data-industry="${industryName}"]`);
    selected.classList.add('border-primary-500', 'bg-primary-50');
    selected.querySelector('.w-10').classList.remove('bg-gray-100');
    selected.querySelector('.w-10').classList.add('bg-primary-500');
    selected.querySelector('svg').classList.remove('text-gray-600');
    selected.querySelector('svg').classList.add('text-white');
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
        cvDropZone.addEventListener(eventName, () => cvDropZone.classList.add('border-emerald-400'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        cvDropZone.addEventListener(eventName, () => cvDropZone.classList.remove('border-emerald-400'), false);
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
    }
}

function removeCVFile(e) {
    e.stopPropagation();
    document.getElementById('cvInput').value = '';
    document.getElementById('cvUploadContent').classList.remove('hidden');
    document.getElementById('cvFileInfo').classList.add('hidden');
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
