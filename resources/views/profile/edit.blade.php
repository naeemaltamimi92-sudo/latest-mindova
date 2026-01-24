@extends('layouts.app')

@section('title', __('My Profile'))

@section('content')
<div class="min-h-screen bg-gray-50/50" x-data="{ activeTab: '{{ session('active_tab', 'profile') }}' }">
    <!-- Premium Header Section -->
    <div class="relative bg-white pb-12 overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-slate-800"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-[url('/img/grid.svg')] opacity-[0.03]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-8 mb-4">
                <!-- Left Group: Avatar + Info -->
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
                    <!-- Avatar -->
                    <div class="relative group shrink-0">
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/10 shadow-2xl overflow-hidden bg-slate-700 relative z-10">
                            @if(auth()->user()->isVolunteer() && auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="w-full h-full object-cover">
                            @elseif(auth()->user()->isCompany() && auth()->user()->company && auth()->user()->company->logo_path)
                                <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}" 
                                     alt="{{ auth()->user()->company->company_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-800 text-white font-bold text-4xl">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Online Status or Badge -->
                        <div class="absolute bottom-2 right-2 z-20 w-6 h-6 bg-emerald-500 border-4 border-slate-900 rounded-full"></div>
                    </div>

                    <!-- User Info -->
                    <div class="text-center md:text-left mb-2">
                        <h1 class="text-3xl md:text-4xl font-bold !text-white mb-2 tracking-tight">{{ auth()->user()->name }}</h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm">
                            <span class="flex items-center gap-1.5 !text-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ auth()->user()->email }}
                            </span>
                            <span class="hidden md:inline w-1 h-1 !bg-slate-400 rounded-full"></span>
                            <span class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 !text-white font-medium border border-white/5">
                                @if(auth()->user()->isVolunteer())
                                    {{ __('Contributor') }}
                                @else
                                    {{ __('Company') }}
                                @endif
                            </span>
                            @if(auth()->user()->created_at)
                            <span class="flex items-center gap-1.5 !text-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Joined {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Button (Optional, maybe view public profile) -->
                @if(auth()->user()->isVolunteer())
                <div class="mb-2 shrink-0">
                    <a href="{{ route('volunteers.show', auth()->user()->volunteer->id ?? 0) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 !text-white rounded-xl transition-all duration-200 font-medium text-sm backdrop-blur-sm border border-white/10">
                        {{ __('View Public Profile') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 pb-20">
        
        <!-- Tabs Navigation -->
        <nav class="flex space-x-1 bg-white/90 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-gray-100 mb-10 max-w-fit mx-auto md:mx-0">
            <button @click="activeTab = 'profile'" 
                    :class="{ 'bg-slate-900 !text-white shadow-md': activeTab === 'profile', 'text-slate-600 hover:bg-slate-50': activeTab !== 'profile' }"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('Profile Details') }}
            </button>
            <button @click="activeTab = 'account'" 
                    :class="{ 'bg-slate-900 !text-white shadow-md': activeTab === 'account', 'text-slate-600 hover:bg-slate-50': activeTab !== 'account' }"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ __('Account') }}
            </button>
            <button @click="activeTab = 'security'" 
                    :class="{ 'bg-slate-900 !text-white shadow-md': activeTab === 'security', 'text-slate-600 hover:bg-slate-50': activeTab !== 'security' }"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                {{ __('Security') }}
            </button>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Sidebar / Stats (Visible on desktop) -->
            <div class="hidden lg:block space-y-6">
                <!-- Completion Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">{{ __('Profile Completion') }}</h3>
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
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-emerald-600 bg-emerald-200">
                                    {{ $completionPercentage < 100 ? 'In Progress' : 'Completed' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-emerald-600">
                                    {{ $completionPercentage }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-emerald-200">
                            <div style="width:{{ $completionPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-500 transition-all duration-500"></div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        {{ __('Complete your profile to increase your visibility and credibility in the community.') }}
                    </p>
                </div>

                <!-- Stats (If volunteer) -->
                @if(auth()->user()->isVolunteer() && auth()->user()->volunteer)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <h3 class="font-bold text-slate-900">{{ __('Impact Stats') }}</h3>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-900">{{ auth()->user()->volunteer->reputation_score ?? 0 }}</div>
                            <div class="text-xs text-slate-500">{{ __('Reputation Score') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-900">{{ auth()->user()->volunteer->availability_hours_per_week ?? 0 }}h</div>
                            <div class="text-xs text-slate-500">{{ __('Availability / Week') }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Main Form Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Profile Tab -->
                <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                            <h2 class="font-bold text-lg text-slate-800">{{ __('Edit Profile Details') }}</h2>
                            <span class="text-xs font-semibold px-2 py-1 bg-slate-200 rounded text-slate-600 uppercase">{{ auth()->user()->user_type }}</span>
                        </div>

                        <form action="{{ auth()->user()->isVolunteer() ? route('profile.volunteer.update') : route('profile.company.update') }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              class="p-6 md:p-8 space-y-8">
                            @csrf

                            <!-- Profile Picture Upload Section -->
                            <div class="space-y-4">
                                <label class="block text-sm font-bold text-slate-700">
                                    {{ auth()->user()->isCompany() ? __('Company Logo') : __('Profile Picture') }}
                                </label>
                                
                                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50 hover:border-slate-300 transition-colors"
                                     x-data="{ photoReview: null }">
                                    
                                    <!-- Preview Area -->
                                    <div class="shrink-0">
                                        <div class="w-24 h-24 rounded-full overflow-hidden bg-white shadow-sm ring-4 ring-white">
                                            <!-- Current Image -->
                                            <template x-if="!photoReview">
                                                @if(auth()->user()->isVolunteer() && auth()->user()->volunteer && auth()->user()->volunteer->profile_picture)
                                                    <img src="{{ asset('storage/' . auth()->user()->volunteer->profile_picture) }}" class="w-full h-full object-cover">
                                                @elseif(auth()->user()->isCompany() && auth()->user()->company && auth()->user()->company->logo_path)
                                                    <img src="{{ asset('storage/' . auth()->user()->company->logo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </template>
                                            <!-- New Preview -->
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
                                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 font-medium shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ __('Change Photo') }}
                                            </button>
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500">
                                            {{ __('JPG, GIF or PNG. 1MB max.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->isVolunteer())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700">{{ __('Availability (Hours/Week)') }}</label>
                                        <input type="number" name="availability_hours_per_week" value="{{ auth()->user()->volunteer->availability_hours_per_week }}" min="0" max="168"
                                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all"
                                               placeholder="e.g. 10">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700">{{ __('Field of Expertise') }}</label>
                                        <input type="text" name="field" value="{{ auth()->user()->volunteer->field }}"
                                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all"
                                               placeholder="e.g. Web Development">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700">{{ __('Bio') }}</label>
                                    <textarea name="bio" rows="4" 
                                              class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all resize-none"
                                              placeholder="Tell us a bit about yourself...">{{ auth()->user()->volunteer->bio }}</textarea>
                                </div>

                                <!-- CV Upload -->
                                <div class="border-t border-slate-100 pt-6">
                                    <h3 class="text-sm font-bold text-slate-900 mb-4">{{ __('Resume / CV') }}</h3>
                                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                                        <div class="shrink-0 text-blue-500 mt-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-900">{{ __('Update your resume') }}</p>
                                            <p class="text-xs text-blue-700 mt-1 mb-3">{{ __('Uploading a new CV will trigger a new AI Skills analysis.') }}</p>
                                            <input type="file" name="cv" accept=".pdf,.doc,.docx"
                                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-colors">
                                        </div>
                                    </div>
                                </div>

                            @elseif(auth()->user()->isCompany())
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700">{{ __('Company Name') }}</label>
                                    <input type="text" name="company_name" value="{{ auth()->user()->company->company_name }}"
                                           class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700">{{ __('Industry') }}</label>
                                        <input type="text" name="industry" value="{{ auth()->user()->company->industry }}"
                                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-slate-700">{{ __('Website') }}</label>
                                        <input type="url" name="website" value="{{ auth()->user()->company->website }}"
                                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all"
                                               placeholder="https://">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700">{{ __('Description') }}</label>
                                    <textarea name="description" rows="4" 
                                              class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500/20 shadow-sm transition-all resize-none"
                                              placeholder="Describe your company...">{{ auth()->user()->company->description }}</textarea>
                                </div>
                            @endif

                            <div class="pt-6 border-t border-gray-100 flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-all transform hover:-translate-y-0.5">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Tab -->
                <div x-show="activeTab === 'account'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h2 class="font-bold text-lg text-slate-800">{{ __('Account Information') }}</h2>
                        </div>
                        <div class="p-6 md:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('Full Name') }}</label>
                                    <div class="font-bold text-slate-900 text-lg">{{ auth()->user()->name }}</div>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ __('Email Address') }}</label>
                                    <div class="font-bold text-slate-900 text-lg break-all">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            
                            <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-blue-900">{{ __('LinkedIn Connection') }}</h4>
                                    @if(auth()->user()->linkedin_id)
                                        <p class="text-sm text-blue-700 mt-1">{{ __('Connected') }}</p>
                                    @else
                                        <p class="text-sm text-blue-700 mt-1">{{ __('Not connected') }}</p>
                                    @endif
                                </div>
                                @if(auth()->user()->linkedin_id)
                                    <a href="{{ auth()->user()->linkedin_profile_url }}" target="_blank" class="text-sm font-bold text-blue-600 hover:text-blue-800">{{ __('View Profile') }} &rarr;</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <h2 class="font-bold text-lg text-slate-800">{{ __('Security Settings') }}</h2>
                        </div>
                        <div class="p-6 md:p-8 space-y-8">
                            <!-- Password Change -->
                            <form action="{{ route('security.password.change') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="space-y-4">
                                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        {{ __('Change Password') }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <input type="password" name="current_password" placeholder="Current Password" class="w-full px-4 py-2.5 rounded-lg border-slate-200 text-sm focus:border-slate-500 focus:ring-slate-500/20">
                                        <input type="password" name="password" placeholder="New Password" class="w-full px-4 py-2.5 rounded-lg border-slate-200 text-sm focus:border-slate-500 focus:ring-slate-500/20">
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-4 py-2.5 rounded-lg border-slate-200 text-sm focus:border-slate-500 focus:ring-slate-500/20">
                                    </div>
                                    <button type="submit" class="px-6 py-2 bg-slate-100 text-slate-700 font-bold rounded-lg text-sm hover:bg-slate-200 transition-colors">
                                        {{ __('Update Password') }}
                                    </button>
                                </div>
                            </form>

                            <hr class="border-slate-100">

                            <!-- 2FA -->
                            <div class="space-y-4">
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ __('Two-Factor Authentication') }}
                                </h3>
                                <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ __('Add an extra layer of security') }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ __('Protect your account with your phone.') }}</p>
                                    </div>
                                    @if(auth()->user()->two_factor_enabled)
                                        <form action="{{ route('security.2fa.disable') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-bold hover:bg-red-200">{{ __('Disable') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('security.2fa.enable') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-slate-800">{{ __('Enable') }}</button>
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
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
