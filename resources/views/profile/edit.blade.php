@extends('layouts.app')

@section('title', __('My Profile'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ activeTab: '{{ session('active_tab', 'profile') }}' }">
    <!-- Header -->
    <div class="mb-6">
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="px-5 py-5 border-b border-gray-100">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-5">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-primary-100 border-2 border-primary-200 flex-shrink-0">
                        @if(auth()->user()->isVolunteer() && auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-full h-full object-cover">
                        @elseif(auth()->user()->isCompany() && auth()->user()->company && auth()->user()->company->logo_path)
                            <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}" 
                                 alt="{{ auth()->user()->company->company_name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-xl font-bold text-primary-700">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-xl font-bold text-gray-900 mb-2">{{ auth()->user()->name }}</h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-sm">
                            <span class="flex items-center gap-1.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ auth()->user()->email }}
                            </span>
                            <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium border border-gray-200">
                                @if(auth()->user()->isVolunteer())
                                    {{ __('Contributor') }}
                                @else
                                    {{ __('Company') }}
                                @endif
                            </span>
                            @if(auth()->user()->created_at)
                            <span class="flex items-center gap-1.5 text-gray-500 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if(auth()->user()->isVolunteer())
                    <div class="flex-shrink-0">
                        <a href="{{ route('volunteers.show', auth()->user()->volunteer->id ?? 0) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">
                            {{ __('Public Profile') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <nav class="flex space-x-1 bg-white border border-gray-200 p-1 rounded-xl max-w-fit">
            <button @click="activeTab = 'profile'" 
                    :class="{ 'bg-primary-50 text-primary-700 border border-primary-200': activeTab === 'profile', 'text-gray-600 hover:bg-gray-50': activeTab !== 'profile' }"
                    class="px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('Profile') }}
            </button>
            <button @click="activeTab = 'account'" 
                    :class="{ 'bg-primary-50 text-primary-700 border border-primary-200': activeTab === 'account', 'text-gray-600 hover:bg-gray-50': activeTab !== 'account' }"
                    class="px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ __('Account') }}
            </button>
            <button @click="activeTab = 'security'" 
                    :class="{ 'bg-primary-50 text-primary-700 border border-primary-200': activeTab === 'security', 'text-gray-600 hover:bg-gray-50': activeTab !== 'security' }"
                    class="px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                {{ __('Security') }}
            </button>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Left Sidebar / Stats -->
        <div class="hidden lg:block space-y-4">
            <!-- Completion Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h3 class="font-semibold text-gray-900 text-sm mb-3">{{ __('Profile Completion') }}</h3>
                @php
                    $completionPercentage = 0;
                    $totalFields = 5;
                    $completedFields = 0;
                    if(auth()->user()->name) $completedFields++;
                    if(auth()->user()->email) $completedFields++;
                    if(auth()->user()->isVolunteer() && auth()->user()->volunteer) {
                        if(auth()->user()->volunteer->profile_picture) $completedFields++;
                        if(auth()->user()->volunteer->bio) $completedFields++;
                        if(auth()->user()->volunteer->skills && auth()->user()->volunteer->skills->count() > 0) $completedFields++;
                    } elseif(auth()->user()->isCompany() && auth()->user()->company) {
                        if(auth()->user()->company->logo_path) $completedFields++;
                        if(auth()->user()->company->description) $completedFields++;
                        if(auth()->user()->company->industry) $completedFields++;
                    }
                    $completionPercentage = round(($completedFields / $totalFields) * 100);
                @endphp
                <div class="mb-2">
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-xs font-medium {{ $completionPercentage < 100 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $completionPercentage < 100 ? __('In Progress') : __('Completed') }}
                        </span>
                        <span class="text-xs font-semibold text-gray-600">{{ $completionPercentage }}%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:{{ $completionPercentage }}%"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-500">{{ __('Complete your profile to increase visibility.') }}</p>
            </div>

            <!-- Stats (If volunteer) -->
            @if(auth()->user()->isVolunteer() && auth()->user()->volunteer)
            <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-3">
                <h3 class="font-semibold text-gray-900 text-sm">{{ __('Impact Stats') }}</h3>
                
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900">{{ auth()->user()->volunteer->reputation_score ?? 0 }}</div>
                        <div class="text-xs text-gray-500">{{ __('Reputation') }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-gray-900">{{ auth()->user()->volunteer->availability_hours_per_week ?? 0 }}h</div>
                        <div class="text-xs text-gray-500">{{ __('Availability') }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Form Content -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 text-sm">{{ __('Edit Profile') }}</h2>
                        <span class="text-xs font-medium px-2 py-0.5 bg-gray-200 rounded text-gray-600">{{ auth()->user()->user_type }}</span>
                    </div>

                    <form action="{{ auth()->user()->isVolunteer() ? route('profile.volunteer.update') : route('profile.company.update') }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="p-5 space-y-6">
                        @csrf

                        <!-- Profile Picture Upload Section -->
                        <div class="space-y-3"
                             x-data="{ photoReview: null }">
                            <label class="block text-sm font-semibold text-gray-700">
                                {{ auth()->user()->isCompany() ? __('Company Logo') : __('Profile Picture') }}
                            </label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-4 p-4 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                                <!-- Preview Area -->
                                <div class="shrink-0">
                                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-white border border-gray-200">
                                        <template x-if="!photoReview">
                                            @if(auth()->user()->isVolunteer() && auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                                                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}" class="w-full h-full object-cover">
                                            @elseif(auth()->user()->isCompany() && auth()->user()->company && auth()->user()->company->logo_path)
                                                <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </template>
                                        <template x-if="photoReview">
                                            <img :src="photoReview" class="w-full h-full object-cover">
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Button Area -->
                                <div class="flex-1 text-center sm:text-left">
                                    <div class="relative">
                                        <input type="file" 
                                               name="{{ auth()->user()->isCompany() ? 'logo' : 'profile_picture' }}" 
                                               id="photo_upload" 
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               accept="image/*"
                                               @change="
                                                    const file = $event.target.files[0];
                                                    if(file) {
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => { photoReview = e.target.result };
                                                        reader.readAsDataURL(file);
                                                    }
                                               ">
                                        <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium text-sm hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ __('Change Photo') }}
                                        </button>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500">{{ __('JPG, PNG. 1MB max.') }}</p>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->isVolunteer())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">{{ __('Availability (Hours/Week)') }}</label>
                                    <input type="number" name="availability_hours_per_week" value="{{ auth()->user()->volunteer->availability_hours_per_week }}" min="0" max="168"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"
                                           placeholder="e.g. 10">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">{{ __('Field of Expertise') }}</label>
                                    <input type="text" name="field" value="{{ auth()->user()->volunteer->field }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"
                                           placeholder="e.g. Web Development">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700">{{ __('Bio') }}</label>
                                <textarea name="bio" rows="4" 
                                          class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm resize-none"
                                          placeholder="Tell us a bit about yourself...">{{ auth()->user()->volunteer->bio }}</textarea>
                            </div>

                            <!-- CV Upload -->
                            <div class="border-t border-gray-100 pt-5">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Resume / CV') }}</h3>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900">{{ __('Update your resume') }}</p>
                                        <p class="text-xs text-blue-700 mt-0.5 mb-2">{{ __('Uploading a new CV will trigger a new AI Skills analysis.') }}</p>
                                        <input type="file" name="cv" accept=".pdf,.doc,.docx"
                                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:font-medium file:bg-blue-100 file:text-blue-700">
                                    </div>
                                </div>
                            </div>

                        @elseif(auth()->user()->isCompany())
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" value="{{ auth()->user()->company->company_name }}"
                                       class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">{{ __('Industry') }}</label>
                                    <input type="text" name="industry" value="{{ auth()->user()->company->industry }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-semibold text-gray-700">{{ __('Website') }}</label>
                                    <input type="url" name="website" value="{{ auth()->user()->company->website }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm"
                                           placeholder="https://">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-gray-700">{{ __('Description') }}</label>
                                <textarea name="description" rows="4" 
                                          class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm resize-none"
                                          placeholder="Describe your company...">{{ auth()->user()->company->description }}</textarea>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Tab -->
            <div x-show="activeTab === 'account'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900 text-sm">{{ __('Account Information') }}</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Full Name') }}</label>
                                <div class="font-semibold text-gray-900 text-sm">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Email Address') }}</label>
                                <div class="font-semibold text-gray-900 text-sm break-all">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        
                        <div class="p-3 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-900 text-sm">{{ __('LinkedIn Connection') }}</h4>
                                @if(auth()->user()->linkedin_id)
                                    <p class="text-xs text-blue-700 mt-0.5">{{ __('Connected') }}</p>
                                @else
                                    <p class="text-xs text-blue-700 mt-0.5">{{ __('Not connected') }}</p>
                                @endif
                            </div>
                            @if(auth()->user()->linkedin_id)
                                <a href="{{ auth()->user()->linkedin_profile_url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-800">{{ __('View') }} &rarr;</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div x-show="activeTab === 'security'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-900 text-sm">{{ __('Security Settings') }}</h2>
                    </div>
                    <div class="p-5 space-y-6">
                        <!-- Password Change -->
                        <form action="{{ route('security.password.change') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    {{ __('Change Password') }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <input type="password" name="current_password" placeholder="Current Password" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <input type="password" name="password" placeholder="New Password" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg text-sm hover:bg-gray-200">
                                    {{ __('Update Password') }}
                                </button>
                            </div>
                        </form>

                        <hr class="border-gray-100">

                        <!-- 2FA -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                {{ __('Two-Factor Authentication') }}
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-3 flex items-center justify-between border border-gray-100">
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ __('Add an extra layer of security') }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ __('Protect your account with your phone.') }}</p>
                                </div>
                                @if(auth()->user()->two_factor_enabled)
                                    <form action="{{ route('security.2fa.disable') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-semibold border border-red-200 hover:bg-red-100">{{ __('Disable') }}</button>
                                    </form>
                                @else
                                    <form action="{{ route('security.2fa.enable') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-primary-500 text-white rounded-lg text-xs font-semibold hover:bg-primary-600">{{ __('Enable') }}</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
