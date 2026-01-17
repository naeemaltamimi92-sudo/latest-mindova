@extends('layouts.app')

@section('title', __('Platform Settings'))

@push('styles')
<style>
    /* Premium Animations */
    .slide-up { animation: slideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-1 { animation-delay: 0.1s; }
    .slide-up-2 { animation-delay: 0.15s; }
    .slide-up-3 { animation-delay: 0.2s; }
    .slide-up-4 { animation-delay: 0.25s; }
    .slide-up-5 { animation-delay: 0.3s; }
    .slide-up-6 { animation-delay: 0.35s; }
    .slide-up-7 { animation-delay: 0.4s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .float-anim { animation: floatAnim 6s-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }

    .pulse-glow { animation: pulseGlow 3s-out infinite; }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
        50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.5); }
    }

    .shine-effect::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    .shine-effect:hover::before { left: 100%; }

    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }

    .category-btn {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-btn:hover {
        transform: translateX(4px);
    }

    .setting-card {
        transition: all 0.2s ease;
    }
    .setting-card:hover {
        transform: scale(1.01);
    }

    .grid-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    [x-cloak] { display: none !important; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s; }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(139, 92, 246, 0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.5); }
</style>
@endpush

@section('content')
<div x-data="settingsPage()" class="min-h-screen bg-gray-50">

    <!-- Premium Hero Header -->
    <div class="relative overflow-hidden bg-secondary-500 py-8 lg:py-10 mb-8">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-10 left-10 w-64 h-64 bg-white/10 rounded-full blur-3xl float-anim"></div>
            <div class="absolute bottom-0 right-10 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl float-anim"></div>
            <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-pink-500/10 rounded-full blur-3xl float-anim"></div>
        </div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 grid-pattern opacity-30"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Title Section -->
                <div class="flex items-center gap-5 slide-up">
                    <div class="relative">
                        <div class="h-16 w-16 lg:h-20 lg:w-20 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center shadow-2xl pulse-glow">
                            <svg class="h-8 w-8 lg:h-10 lg:w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-400 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-black text-white mb-1">{{ __('Platform Settings') }}</h1>
                        <p class="text-white/70 text-sm lg:text-base">{{ __('Complete control over your platform configuration') }}</p>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 slide-up slide-up-1">
                    <x-ui.button @click="clearCache()" variant="secondary" size="sm">
                        @include('admin.settings.partials.icon', ['icon' => 'refresh', 'class' => 'h-4 w-4'])
                        {{ __('Clear Cache') }}
                    </x-ui.button>
                    <x-ui.button @click="showImportModal = true" variant="secondary" size="sm">
                        @include('admin.settings.partials.icon', ['icon' => 'upload', 'class' => 'h-4 w-4'])
                        {{ __('Import') }}
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('admin.settings.export') }}" variant="secondary" size="sm">
                        @include('admin.settings.partials.icon', ['icon' => 'download', 'class' => 'h-4 w-4'])
                        {{ __('Export') }}
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('admin.dashboard') }}" variant="primary" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Quick Stats - Premium Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
            <div class="stat-card slide-up slide-up-1 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-violet-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gray-600 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900">{{ $stats['total'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Total Settings') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-2 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-violet-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-secondary-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-violet-600">{{ $stats['groups'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Categories') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-3 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-emerald-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-secondary-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600">{{ $stats['enabled'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Enabled') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-4 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-amber-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-secondary-300 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-amber-600">{{ $stats['booleans'] - $stats['enabled'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Disabled') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-5 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-blue-600">{{ $stats['booleans'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Toggles') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-6 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-indigo-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-indigo-600">{{ $stats['integers'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Numbers') }}</div>
            </div>

            <div class="stat-card slide-up slide-up-7 bg-white rounded-2xl p-4 border border-slate-200/60 shadow-lg shadow-slate-200/50 hover:shadow-xl hover:border-pink-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-secondary-400 flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-pink-600">{{ $stats['strings'] }}</div>
                <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ __('Text') }}</div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-5 bg-gray-50 border border-emerald-200 rounded-2xl flex items-center gap-4 shadow-lg animate-fade-in">
            <div class="h-12 w-12 rounded-xl bg-secondary-500 flex items-center justify-center flex-shrink-0 shadow-lg">
                @include('admin.settings.partials.icon', ['icon' => 'check', 'class' => 'h-6 w-6 text-white'])
            </div>
            <div>
                <p class="text-emerald-800 font-bold text-lg">{{ __('Success!') }}</p>
                <p class="text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Toast Container -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

        <!-- Main Content Grid -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-80 flex-shrink-0 space-y-6">
                <!-- Search Box -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 p-5 slide-up">
                    <div class="relative">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="searchSettings()"
                               placeholder="{{ __('Search settings...') }}"
                               class="w-full pl-12 pr-10 py-3.5 text-sm border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 font-medium text-slate-900 placeholder-slate-400">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <div class="w-6 h-6 rounded-lg bg-secondary-500 flex items-center justify-center">
                                @include('admin.settings.partials.icon', ['icon' => 'search', 'class' => 'h-3.5 w-3.5 text-white'])
                            </div>
                        </div>
                        <div x-show="searchQuery" @click="searchQuery = ''; searchResults = []" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
                            <div class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center">
                                @include('admin.settings.partials.icon', ['icon' => 'x', 'class' => 'h-3.5 w-3.5 text-slate-500'])
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="searchResults.length > 0" x-cloak class="mt-4 space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                        <template x-for="result in searchResults" :key="result.key">
                            <div @click="goToSetting(result.group)" class="p-3 rounded-xl bg-gray-50 hover:bg-primary-600 cursor-pointer border border-violet-100">
                                <div class="text-sm font-bold text-slate-900" x-text="result.label"></div>
                                <div class="text-xs text-violet-600 font-medium" x-text="result.group"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Mindova Organization - Team Management -->
                <div class="bg-primary-500 rounded-3xl shadow-xl shadow-purple-500/30 p-5 slide-up slide-up-1 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
                    </div>

                    <div class="relative">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-black text-white text-lg">{{ __('Mindova Organization') }}</h3>
                                <p class="text-white/70 text-xs">{{ __('Internal Team Management') }}</p>
                            </div>
                        </div>

                        <p class="text-white/80 text-sm mb-4">{{ __('Manage internal team members, assign roles and permissions, and control platform access.') }}</p>

                        <div class="space-y-2">
                            <x-ui.button as="a" href="{{ route('mindova.team.index') }}" variant="primary" fullWidth>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                {{ __('Manage Team') }}
                            </x-ui.button>
                            <div class="flex items-center justify-center gap-4 pt-2">
                                <div class="flex items-center gap-1.5 text-white/70 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    {{ __('5 Roles') }}
                                </div>
                                <div class="flex items-center gap-1.5 text-white/70 text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    {{ __('Secure') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 p-5 slide-up slide-up-2">
                    <h3 class="font-black text-slate-900 mb-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-secondary-300 flex items-center justify-center shadow-lg">
                            @include('admin.settings.partials.icon', ['icon' => 'lightning-bolt', 'class' => 'h-4 w-4 text-white'])
                        </div>
                        {{ __('Quick Presets') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($presets as $key => $preset)
                        <button @click="applyPreset('{{ $key }}')"
                                class="w-full flex items-center gap-4 p-3.5 rounded-2xl hover:{{ $preset['color'] }} hover:text-white text-slate-700 hover:shadow-lg group border border-slate-200 hover:border-transparent">
                            <div class="h-10 w-10 rounded-xl {{ $preset['color'] }} flex items-center justify-center shadow-lg">
                                @include('admin.settings.partials.icon', ['icon' => $preset['icon'], 'class' => 'h-5 w-5 text-white'])
                            </div>
                            <div class="text-left flex-1">
                                <div class="text-sm font-bold group-hover:text-white">{{ __($preset['name']) }}</div>
                                <div class="text-xs text-slate-500 group-hover:text-white/80">{{ __($preset['description']) }}</div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Category Navigation -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden slide-up slide-up-2">
                    <div class="p-5 border-b border-slate-100 bg-gray-50">
                        <h3 class="font-black text-slate-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-secondary-500 flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            {{ __('Categories') }}
                        </h3>
                    </div>
                    <nav class="p-3 max-h-[450px] overflow-y-auto custom-scrollbar">
                        @foreach($settingsByGroup as $groupKey => $group)
                        <button @click="activeTab = '{{ $groupKey }}'; scrollToTop()"
                                :class="activeTab === '{{ $groupKey }}' ? '{{ $group['config']['color'] }} text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100'"
                                class="category-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left mb-2">
                            <div :class="activeTab === '{{ $groupKey }}' ? 'bg-white/20' : '{{ $group['config']['color'] }}'"
                                 class="h-10 w-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                @include('admin.settings.partials.icon', ['icon' => $group['config']['icon'], 'class' => 'h-5 w-5 text-white'])
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold truncate">{{ __($group['config']['label']) }}</div>
                                <div class="text-xs opacity-70">{{ $group['settings']->count() }} {{ __('settings') }}</div>
                            </div>
                            <svg :class="activeTab === '{{ $groupKey }}' ? 'text-white' : 'text-slate-400'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="flex-1 min-w-0">
                @foreach($settingsByGroup as $groupKey => $group)
                <div x-show="activeTab === '{{ $groupKey }}'" x-cloak class="animate-fade-in">
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
                        <!-- Group Header -->
                        <div class="px-8 py-6 border-b border-slate-100 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-5">
                                    <div class="h-14 w-14 rounded-2xl {{ $group['config']['color'] }} flex items-center justify-center shadow-xl">
                                        @include('admin.settings.partials.icon', ['icon' => $group['config']['icon'], 'class' => 'h-7 w-7 text-white'])
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-black text-slate-900">{{ __($group['config']['label']) }}</h2>
                                        <p class="text-sm text-slate-500">{{ __($group['config']['description']) }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-slate-500 bg-slate-100 px-4 py-2 rounded-xl">
                                    {{ $group['settings']->count() }} {{ __('settings') }}
                                </span>
                            </div>
                        </div>

                        <!-- Settings List -->
                        <div class="p-6 space-y-4">
                            @foreach($group['settings'] as $setting)
                            <div class="setting-card group p-5 rounded-2xl bg-gray-50 hover:bg-gray-100 border border-transparent hover:border-slate-200 hover:shadow-lg">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-bold text-slate-900 text-lg">{{ __($setting->label) }}</h3>
                                            @if($setting->is_public)
                                            <span class="text-xs px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg font-bold">{{ __('Public') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-600 mb-3">{{ __($setting->description) }}</p>
                                        <span class="inline-block text-xs font-mono text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg">{{ $setting->key }}</span>
                                    </div>

                                    <div class="flex-shrink-0">
                                        @if($setting->type === 'boolean')
                                            @include('admin.settings.partials.toggle', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? false, 'color' => $group['config']['color']])
                                        @elseif($setting->type === 'integer')
                                            @include('admin.settings.partials.number', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? 0])
                                        @elseif($setting->type === 'color')
                                            @include('admin.settings.partials.color', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? '#000000'])
                                        @elseif($setting->type === 'text')
                                            @include('admin.settings.partials.textarea', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? ''])
                                        @elseif($setting->type === 'select')
                                            @include('admin.settings.partials.select', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? ''])
                                        @else
                                            @include('admin.settings.partials.text', ['setting' => $setting, 'value' => $allSettings[$setting->key] ?? ''])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div x-show="showImportModal"    @click="showImportModal = false" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"></div>

            <div x-show="showImportModal"    class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden">
                <!-- Modal Header -->
                <div class="px-8 py-6 border-b border-slate-100 bg-secondary-500">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-xl flex items-center justify-center">
                            @include('admin.settings.partials.icon', ['icon' => 'upload', 'class' => 'h-6 w-6 text-white'])
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">{{ __('Import Settings') }}</h3>
                            <p class="text-sm text-white/70">{{ __('Paste your JSON configuration') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <textarea x-model="importData" rows="12" class="w-full px-5 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 font-mono text-sm resize-none" placeholder='{"settings": {...}}'></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 flex justify-end gap-4">
                    <x-ui.button @click="showImportModal = false" variant="secondary">{{ __('Cancel') }}</x-ui.button>
                    <x-ui.button @click="importSettings()" variant="primary">{{ __('Import') }}</x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function settingsPage() {
    return {
        activeTab: '{{ $activeGroup }}',
        searchQuery: '{{ $searchQuery }}',
        searchResults: [],
        showImportModal: false,
        importData: '',

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async searchSettings() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }
            try {
                const response = await fetch(`{{ route('admin.settings.search') }}?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                this.searchResults = data.results || [];
            } catch (e) {
                console.error('Search failed:', e);
            }
        },

        goToSetting(group) {
            this.activeTab = group;
            this.searchResults = [];
            this.scrollToTop();
        },

        async applyPreset(preset) {
            if (!confirm('{{ __('Apply this preset? This will override related settings.') }}')) return;

            try {
                const response = await fetch('{{ route('admin.settings.applyPreset') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ preset })
                });
                const data = await response.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || '{{ __('Failed to apply preset') }}', 'error');
                }
            } catch (e) {
                showToast('{{ __('Error applying preset') }}', 'error');
            }
        },

        async clearCache() {
            try {
                const response = await fetch('{{ route('admin.settings.clearCache') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (e) {
                showToast('{{ __('Error clearing cache') }}', 'error');
            }
        },

        async importSettings() {
            try {
                const parsed = JSON.parse(this.importData);
                const settings = parsed.settings || parsed;

                const response = await fetch('{{ route('admin.settings.import') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ settings })
                });
                const data = await response.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    this.showImportModal = false;
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || '{{ __('Import failed') }}', 'error');
                }
            } catch (e) {
                showToast('{{ __('Invalid JSON format') }}', 'error');
            }
        }
    };
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-secondary-500',
        error: 'bg-secondary-700',
        info: 'bg-gray-600',
        warning: 'bg-secondary-300'
    };
    const icons = {
        success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        error: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        info: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
    };
    toast.className = `${colors[type] || colors.info} text-white px-6 py-4 rounded-2xl shadow-2xl transform translate-x-full font-semibold text-sm flex items-center gap-3`;
    toast.innerHTML = `${icons[type] || icons.info}<span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.classList.remove('translate-x-full'); toast.classList.add('translate-x-0'); }, 10);
    setTimeout(() => {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
