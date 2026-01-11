@extends('mindova.layouts.app')

@section('title', __('Invite Team Member'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-1 { animation-delay: 0.05s; }
    .slide-up-2 { animation-delay: 0.1s; }
    .slide-up-3 { animation-delay: 0.15s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .role-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .role-card:hover {
        transform: translateY(-2px);
    }
    .role-card.selected {
        transform: scale(1.02);
    }

    .input-field {
        transition: all 0.2s ease;
    }
    .input-field:focus-within {
        transform: translateY(-1px);
    }

    .float-anim { animation: floatAnim 6s ease-in-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="inviteForm()" class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-3xl p-6 lg:p-8 slide-up slide-up-1">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl float-anim"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative z-10 flex items-center gap-4">
            <a href="{{ route('mindova.team.index') }}" class="p-3 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-xl transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">{{ __('Invite Team Member') }}</h1>
                <p class="text-white/70 mt-1">{{ __('Add a new member to your Mindova organization') }}</p>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="relative z-10 mt-6 flex items-center justify-center gap-4">
            <div class="flex items-center gap-2">
                <div :class="step >= 1 ? 'bg-white text-indigo-600' : 'bg-white/20 text-white'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all">1</div>
                <span class="text-white/80 text-sm hidden sm:inline">{{ __('Details') }}</span>
            </div>
            <div class="w-12 h-0.5 bg-white/30"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 2 ? 'bg-white text-indigo-600' : 'bg-white/20 text-white'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all">2</div>
                <span class="text-white/80 text-sm hidden sm:inline">{{ __('Role') }}</span>
            </div>
            <div class="w-12 h-0.5 bg-white/30"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 3 ? 'bg-white text-indigo-600' : 'bg-white/20 text-white'" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all">3</div>
                <span class="text-white/80 text-sm hidden sm:inline">{{ __('Confirm') }}</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('mindova.team.store') }}" method="POST" @submit="validateForm($event)">
        @csrf

        <!-- Step 1: Member Details -->
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-2">
            <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    {{ __('Member Details') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-10">{{ __('Enter the basic information for the new team member') }}</p>
            </div>

            <div class="p-6 space-y-6">
                <div class="input-field">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ __('Full Name') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" x-model="form.name" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all @error('name') border-red-500 bg-red-50 @enderror"
                            placeholder="{{ __('e.g., John Smith') }}">
                    </div>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="input-field">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ __('Email Address') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" x-model="form.email" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all @error('email') border-red-500 bg-red-50 @enderror"
                            placeholder="{{ __('e.g., john@company.com') }}">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('An invitation with login credentials will be sent to this email') }}
                    </p>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="button" @click="nextStep()" :disabled="!form.name || !form.email" :class="(!form.name || !form.email) ? 'opacity-50 cursor-not-allowed' : 'hover:from-indigo-700 hover:to-purple-700'"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl transition-all">
                        {{ __('Continue') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Select Role -->
        <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-violet-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    {{ __('Select Role') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-10">{{ __('Choose the appropriate role for this team member') }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                    @php
                        $roleColors = [
                            'owner' => ['border' => 'border-amber-300', 'bg' => 'bg-gradient-to-br from-amber-50 to-orange-50', 'icon' => 'from-amber-400 to-orange-500', 'badge' => 'bg-amber-100 text-amber-700'],
                            'admin' => ['border' => 'border-purple-300', 'bg' => 'bg-gradient-to-br from-purple-50 to-indigo-50', 'icon' => 'from-purple-500 to-indigo-500', 'badge' => 'bg-purple-100 text-purple-700'],
                            'accounting' => ['border' => 'border-emerald-300', 'bg' => 'bg-gradient-to-br from-emerald-50 to-teal-50', 'icon' => 'from-emerald-500 to-teal-500', 'badge' => 'bg-emerald-100 text-emerald-700'],
                            'support' => ['border' => 'border-blue-300', 'bg' => 'bg-gradient-to-br from-blue-50 to-cyan-50', 'icon' => 'from-blue-500 to-cyan-500', 'badge' => 'bg-blue-100 text-blue-700'],
                            'feedback-qa' => ['border' => 'border-cyan-300', 'bg' => 'bg-gradient-to-br from-cyan-50 to-sky-50', 'icon' => 'from-cyan-500 to-sky-500', 'badge' => 'bg-cyan-100 text-cyan-700'],
                        ];
                        $colors = $roleColors[$role->slug] ?? ['border' => 'border-slate-300', 'bg' => 'bg-slate-50', 'icon' => 'from-slate-400 to-slate-500', 'badge' => 'bg-slate-100 text-slate-700'];
                    @endphp

                    @if($role->slug !== 'owner' || !$ownerExists)
                    <label class="role-card relative block cursor-pointer" :class="form.role_id == {{ $role->id }} ? 'selected ring-2 ring-indigo-500 {{ $colors['border'] }} {{ $colors['bg'] }}' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="role_id" value="{{ $role->id }}" x-model="form.role_id" class="sr-only">
                        <div class="p-5 border-2 rounded-xl transition-all" :class="form.role_id == {{ $role->id }} ? '{{ $colors['border'] }} {{ $colors['bg'] }}' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $colors['icon'] }} rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                    @if($role->slug === 'owner')
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                                    </svg>
                                    @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-slate-900">{{ $role->name }}</h3>
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $colors['badge'] }}">L{{ $role->level }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ $role->description }}</p>
                                </div>
                            </div>

                            <!-- Selection Indicator -->
                            <div x-show="form.role_id == {{ $role->id }}" class="absolute top-3 right-3">
                                <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    @endforeach
                </div>

                @error('role_id')
                <p class="mt-4 text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror

                <div class="flex justify-between pt-6 border-t border-slate-100 mt-6">
                    <button type="button" @click="prevStep()" class="inline-flex items-center gap-2 px-5 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('Back') }}
                    </button>
                    <button type="button" @click="nextStep()" :disabled="!form.role_id" :class="!form.role_id ? 'opacity-50 cursor-not-allowed' : 'hover:from-indigo-700 hover:to-purple-700'"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl transition-all">
                        {{ __('Continue') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 3: Confirm & Send -->
        <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    {{ __('Confirm & Send Invitation') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-10">{{ __('Review the details before sending the invitation') }}</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Summary Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                    <h3 class="font-semibold text-indigo-900 mb-4">{{ __('Invitation Summary') }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/80 rounded-lg p-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Name') }}</p>
                            <p class="font-semibold text-slate-900" x-text="form.name || '-'"></p>
                        </div>
                        <div class="bg-white/80 rounded-lg p-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Email') }}</p>
                            <p class="font-semibold text-slate-900" x-text="form.email || '-'"></p>
                        </div>
                        <div class="bg-white/80 rounded-lg p-4 md:col-span-2">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Role') }}</p>
                            <p class="font-semibold text-slate-900" x-text="getSelectedRoleName()"></p>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900">{{ __('Security Information') }}</h4>
                            <ul class="mt-2 space-y-1.5 text-sm text-blue-700">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('A secure temporary password will be generated automatically') }}
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('The member must change their password on first login') }}
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('All activities will be logged for security auditing') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-4 border-t border-slate-100">
                    <button type="button" @click="prevStep()" class="inline-flex items-center gap-2 px-5 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('Back') }}
                    </button>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('mindova.team.index') }}" class="px-5 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Send Invitation') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function inviteForm() {
    return {
        step: 1,
        form: {
            name: '{{ old('name', '') }}',
            email: '{{ old('email', '') }}',
            role_id: '{{ old('role_id', '') }}'
        },
        roles: @json($roles),

        nextStep() {
            if (this.step < 3) this.step++;
        },

        prevStep() {
            if (this.step > 1) this.step--;
        },

        getSelectedRoleName() {
            const role = this.roles.find(r => r.id == this.form.role_id);
            return role ? role.name : '-';
        },

        validateForm(event) {
            if (!this.form.name || !this.form.email || !this.form.role_id) {
                event.preventDefault();
                alert('{{ __('Please fill in all required fields.') }}');
                return false;
            }
            return true;
        }
    }
}
</script>
@endpush
@endsection
