@extends('layouts.app')

@php
    $canSeeSpam = $canSeeSpam ?? (auth()->check() && (auth()->user()->isAdmin() || (auth()->user()->isCompany() && auth()->user()->company?->id === $challenge->company_id)));
@endphp

@section('title', $challenge->title . ' - ' . __('Community Discussion'))

@push('styles')
<style>
    /* Premium Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
        50% { box-shadow: 0 0 40px rgba(139, 92, 246, 0.6); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
    .animate-shimmer {
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    .animate-slide-in-up { animation: slide-in-up 0.6s ease-out forwards; }
    .animate-bounce-subtle { animation: bounce-subtle 2s ease-in-out infinite; }

    /* Idea Card Hover Effects */
    .idea-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .idea-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.15);
    }

    /* Quality Badge Animation */
    .quality-badge {
        animation: pulse-glow 2s infinite;
    }

    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Vote Button Styles */
    .vote-btn {
        transition: all 0.3s ease;
    }
    .vote-btn:hover {
        transform: scale(1.1);
    }
    .vote-btn.active-up {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-color: transparent;
    }
    .vote-btn.active-down {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-color: transparent;
    }

    /* Prose Styles */
    .prose-content h2 { font-size: 1.25rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e293b; }
    .prose-content h3 { font-size: 1.1rem; font-weight: 600; margin-top: 1.25rem; margin-bottom: 0.5rem; color: #334155; }
    .prose-content p { margin-bottom: 0.75rem; }
    .prose-content ul, .prose-content ol { margin-left: 1.5rem; margin-bottom: 0.75rem; }
    .prose-content li { margin-bottom: 0.25rem; }
    .prose-content code { background: #f1f5f9; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875rem; }
    .prose-content pre { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1rem; }
    .prose-content strong { font-weight: 600; color: #1e293b; }

    /* Tab Styles */
    .tab-btn {
        transition: all 0.3s ease;
    }
    .tab-btn.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    /* Sticky Sidebar */
    .sticky-sidebar {
        position: sticky;
        top: 100px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-50">
    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-700">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="animate-float absolute -top-20 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="animate-float absolute top-40 right-10 w-96 h-96 bg-purple-300/20 rounded-full blur-3xl" style="animation-delay: 2s;"></div>
            <div class="animate-float absolute bottom-0 left-1/3 w-72 h-72 bg-indigo-300/20 rounded-full blur-3xl" style="animation-delay: 4s;"></div>
        </div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm mb-6 animate-slide-in-up">
                <a href="{{ route('community.index') }}" class="text-white/70 hover:text-white transition-colors flex items-center gap-1.5 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Community') }}
                </a>
                <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white/90 font-medium">{{ Str::limit($challenge->title, 30) }}</span>
            </nav>

            <!-- Badges Row -->
            <div class="flex flex-wrap items-center gap-3 mb-6 animate-slide-in-up" style="animation-delay: 0.1s;">
                <!-- Field Badge -->
                @if($challenge->field)
                <span class="inline-flex items-center px-4 py-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full text-sm font-semibold text-white shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    {{ $challenge->field }}
                </span>
                @endif

                <!-- Score Badge -->
                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full text-sm font-bold text-white shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ __('Complexity') }}: {{ $challenge->score }}/10
                </span>

                <!-- Type Badge -->
                <span class="inline-flex items-center px-4 py-2 bg-purple-500/40 backdrop-blur-md border border-purple-300/30 rounded-full text-sm font-semibold text-white shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    {{ __('Community Discussion') }}
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-6 leading-tight animate-slide-in-up" style="animation-delay: 0.2s;">
                {{ $challenge->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 text-white/80 animate-slide-in-up" style="animation-delay: 0.3s;">
                @if($challenge->isVolunteerSubmitted())
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ $challenge->volunteer->user->name ?? __('Contributor') }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-medium">{{ $challenge->company->company_name }}</span>
                    </div>
                @endif

                <span class="w-1.5 h-1.5 bg-white/40 rounded-full"></span>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $challenge->created_at->diffForHumans() }}</span>
                </div>

                @if($challenge->deadline)
                <span class="w-1.5 h-1.5 bg-white/40 rounded-full"></span>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ __('Ends') }} {{ \Carbon\Carbon::parse($challenge->deadline)->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards - Floating Above -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 -mt-16 mb-10 relative z-10">
            <!-- Total Ideas -->
            <div class="group animate-slide-in-up" style="animation-delay: 0.1s;">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-slate-100 hover:border-blue-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-slate-900">{{ $totalIdeas }}</p>
                            <p class="text-sm text-slate-500 font-medium">{{ __('Total Ideas') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- High Quality -->
            <div class="group animate-slide-in-up" style="animation-delay: 0.2s;">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-slate-100 hover:border-emerald-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-emerald-600">{{ $highQualityIdeas->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">{{ __('High Quality') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contributors -->
            <div class="group animate-slide-in-up" style="animation-delay: 0.3s;">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-slate-100 hover:border-violet-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-slate-900">{{ $challenge->ideas->unique('volunteer_id')->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">{{ __('Contributors') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Status -->
            <div class="group animate-slide-in-up" style="animation-delay: 0.4s;">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-slate-100 hover:border-amber-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            @if($challenge->deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($challenge->deadline);
                                    $daysLeft = max(0, now()->floatDiffInDays($deadline, false));
                                @endphp
                                <p class="text-3xl font-black {{ $daysLeft < 7 ? 'text-red-600' : 'text-slate-900' }}">{{ number_format($daysLeft, 2) }}</p>
                                <p class="text-sm text-slate-500 font-medium">{{ __('Days Left') }}</p>
                            @else
                                <p class="text-3xl font-black text-emerald-600">{{ __('Open') }}</p>
                                <p class="text-sm text-slate-500 font-medium">{{ __('No Deadline') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Challenge Description Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden animate-slide-in-up" style="animation-delay: 0.5s;">
                    <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 px-6 py-5">
                        <h2 class="text-lg font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            {{ __('Challenge Details') }}
                        </h2>
                    </div>
                    <div class="p-6 lg:p-8">
                        <div class="prose max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $challenge->original_description }}
                        </div>

                        @if($challenge->refined_brief)
                        <div class="mt-8 relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-20"></div>
                            <div class="relative bg-gradient-to-br from-indigo-50 via-violet-50 to-purple-50 border border-indigo-200 rounded-2xl p-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 7H7v6h6V7z"/>
                                            <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-indigo-900 mb-3 flex items-center gap-2">
                                            {{ __('AI-Refined Brief') }}
                                            <span class="px-2 py-0.5 bg-indigo-200 text-indigo-700 text-xs rounded-full">{{ __('Enhanced') }}</span>
                                        </h4>
                                        <p class="text-sm text-indigo-800 leading-relaxed whitespace-pre-line">{{ $challenge->refined_brief }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Community Ideas Section -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden animate-slide-in-up" style="animation-delay: 0.6s;">
                    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-white flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                                    </svg>
                                </div>
                                {{ __('Community Ideas') }}
                            </h2>
                            <span class="px-4 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-bold text-white">
                                {{ $totalIdeas }} {{ Str::plural(__('idea'), $totalIdeas) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 lg:p-8">
                        @if($challenge->ideas->count() > 0)
                            <div class="space-y-6">
                                @foreach($challenge->ideas->sortByDesc('ai_quality_score') as $index => $idea)
                                <div class="idea-card relative {{ $canSeeSpam && $idea->is_spam ? 'ring-2 ring-red-300 bg-gradient-to-br from-red-50 to-rose-50 border-red-200' : ($idea->ai_quality_score >= 7 ? 'ring-2 ring-emerald-300 bg-gradient-to-br from-emerald-50 to-teal-50 border-emerald-200' : 'bg-gradient-to-br from-slate-50 to-white border-slate-200') }} rounded-2xl p-6 border" style="animation-delay: {{ 0.1 * $index }}s;">
                                    <!-- High Quality Ribbon -->
                                    @if($idea->ai_quality_score >= 7)
                                    <div class="absolute -top-3 -right-3">
                                        <div class="quality-badge w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center shadow-lg">
                                            <span class="text-white text-lg">⭐</span>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Spam Badge (Admin Only) -->
                                    @if($canSeeSpam && $idea->is_spam)
                                    <div class="absolute -top-3 -left-3">
                                        <div class="px-3 py-1 bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-lg">
                                            <span class="text-white text-xs font-bold">{{ __('Spam') }}</span>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Idea Header -->
                                    <div class="flex items-start justify-between mb-5">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                    {{ strtoupper(substr($idea->volunteer->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                @if($idea->ai_quality_score >= 7)
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center border-2 border-white">
                                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900">{{ $idea->volunteer->user->name ?? __('Anonymous') }}</p>
                                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                                    @if($idea->volunteer->field)
                                                    <span class="px-2 py-0.5 bg-slate-100 rounded-full">{{ $idea->volunteer->field }}</span>
                                                    @endif
                                                    <span>{{ $idea->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @if($idea->ai_quality_score)
                                        <div class="flex items-center gap-2">
                                            <span class="px-4 py-2 rounded-xl text-sm font-bold shadow-sm
                                                {{ $idea->ai_quality_score >= 7 ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white' : ($idea->ai_quality_score >= 5 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600') }}">
                                                {{ number_format($idea->ai_quality_score, 1) }}/10
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Idea Content -->
                                    <div class="prose-content text-slate-700 text-sm leading-relaxed mb-5">
                                        {!! nl2br(e($idea->content)) !!}
                                    </div>

                                    <!-- Spam Reason (Admin Only) -->
                                    @if($canSeeSpam && $idea->is_spam && $idea->spam_reason)
                                    <div class="bg-gradient-to-r from-red-100 to-rose-100 border border-red-200 rounded-xl p-4 mb-5">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-red-900 mb-1">{{ __('Spam Detected') }}</h5>
                                                <p class="text-xs text-red-800 leading-relaxed">{{ $idea->spam_reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- AI Feedback for High-Quality Ideas -->
                                    @if($idea->ai_quality_score >= 7 && $idea->ai_feedback)
                                    <div class="bg-gradient-to-r from-emerald-100 to-teal-100 border border-emerald-200 rounded-xl p-4 mb-5">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 7H7v6h6V7z"/>
                                                    <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-emerald-900 mb-1">{{ __('AI Assessment') }}</h5>
                                                <p class="text-xs text-emerald-800 leading-relaxed">{{ $idea->ai_feedback['feedback'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Vote Section -->
                                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                                        @auth
                                            @if(auth()->user()->isVolunteer())
                                                @php
                                                    $userVote = $userVotes->get($idea->id);
                                                    $hasUpvoted = $userVote && $userVote->vote_type === 'up';
                                                    $hasDownvoted = $userVote && $userVote->vote_type === 'down';
                                                    $isOwnIdea = auth()->user()->volunteer->id === $idea->volunteer_id;
                                                @endphp

                                                @if($isOwnIdea)
                                                    <!-- Own idea - voting disabled -->
                                                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                        </svg>
                                                        <span>{{ $idea->community_votes_up ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                        <span>{{ $idea->community_votes_down ?? 0 }}</span>
                                                    </div>
                                                    <span class="text-xs text-slate-400 italic">{{ __('Your idea') }}</span>
                                                @else
                                                    <!-- Upvote Form -->
                                                    <form action="{{ route('community.idea.vote', $idea) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="vote_type" value="up">
                                                        <button type="submit" class="vote-btn flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all
                                                            {{ $hasUpvoted
                                                                ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white border-transparent shadow-lg'
                                                                : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600' }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                            </svg>
                                                            <span>{{ $idea->community_votes_up ?? 0 }}</span>
                                                        </button>
                                                    </form>

                                                    <!-- Downvote Form -->
                                                    <form action="{{ route('community.idea.vote', $idea) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="vote_type" value="down">
                                                        <button type="submit" class="vote-btn flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all
                                                            {{ $hasDownvoted
                                                                ? 'bg-gradient-to-r from-red-500 to-rose-500 text-white border-transparent shadow-lg'
                                                                : 'bg-white border border-slate-200 text-slate-600 hover:bg-red-50 hover:border-red-300 hover:text-red-600' }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                            </svg>
                                                            <span>{{ $idea->community_votes_down ?? 0 }}</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <!-- Company users can't vote -->
                                                <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                    </svg>
                                                    <span>{{ $idea->community_votes_up ?? 0 }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                    <span>{{ $idea->community_votes_down ?? 0 }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Guest users - show counts only -->
                                            <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                                <span>{{ $idea->community_votes_up ?? 0 }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                                <span>{{ $idea->community_votes_down ?? 0 }}</span>
                                            </div>
                                        @endauth

                                        <div class="flex-1"></div>
                                        <span class="text-xs text-slate-400">
                                            {{ __('Net votes') }}: {{ ($idea->community_votes_up ?? 0) - ($idea->community_votes_down ?? 0) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No ideas yet') }}</h3>
                                <p class="text-slate-500 max-w-sm mx-auto">{{ __('Be the first to share your insights and help solve this challenge!') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <div class="sticky-sidebar space-y-6">
                    <!-- Submit Idea Form -->
                    @auth
                        @if(auth()->user()->isVolunteer())
                        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden animate-slide-in-up" style="animation-delay: 0.7s;">
                            <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 px-6 py-5">
                                <h3 class="text-lg font-bold text-white flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center animate-bounce-subtle">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                                        </svg>
                                    </div>
                                    {{ __('Share Your Idea') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('community.comment', $challenge) }}" method="POST">
                                    @csrf
                                    <div class="mb-5">
                                        <textarea name="content" rows="6" required
                                            class="w-full rounded-2xl border-2 border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm p-4 transition-colors resize-none"
                                            placeholder="{{ __('Share your thoughts, suggestions, or solutions for this challenge...') }}">{{ old('content') }}</textarea>
                                        @error('content')
                                        <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Tips Card -->
                                    <div class="bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-200 rounded-xl p-4 mb-5">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-violet-900 mb-1">{{ __('Pro Tip') }}</p>
                                                <p class="text-xs text-violet-700">{{ __('High-quality ideas (score 7+) will be highlighted and the challenge owner will be notified!') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold rounded-2xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        {{ __('Submit Idea') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <!-- Company View -->
                        <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-6 animate-slide-in-up" style="animation-delay: 0.7s;">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-slate-900 mb-2">{{ __('Your Challenge') }}</h4>
                                <p class="text-sm text-slate-600">{{ __('Monitor community feedback and insights for your challenge.') }}</p>
                            </div>
                        </div>
                        @endif
                    @else
                    <!-- Guest CTA -->
                    <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8 text-center animate-slide-in-up" style="animation-delay: 0.7s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 mb-2">{{ __('Join the Discussion') }}</h4>
                        <p class="text-sm text-slate-500 mb-6">{{ __('Sign in to share your ideas and insights.') }}</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('Sign In') }}
                        </a>
                    </div>
                    @endauth

                    <!-- Challenge Owner Info -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 animate-slide-in-up" style="animation-delay: 0.8s;">
                        <h4 class="font-bold text-slate-900 mb-5 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Posted By') }}
                        </h4>
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ strtoupper(substr($challenge->company?->company_name ?? ($challenge->volunteer?->user?->name ?? 'C'), 0, 1)) }}
                            </div>
                            <div>
                                @if($challenge->company)
                                    <p class="font-bold text-slate-900">{{ $challenge->company->company_name }}</p>
                                    @if($challenge->company->industry)
                                    <p class="text-sm text-slate-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $challenge->company->industry }}
                                    </p>
                                    @endif
                                @elseif($challenge->volunteer)
                                    <p class="font-bold text-slate-900">{{ $challenge->volunteer->user->name ?? 'Volunteer' }}</p>
                                    <p class="text-sm text-slate-500">{{ __('Community Volunteer') }}</p>
                                @else
                                    <p class="font-bold text-slate-900">{{ __('Anonymous') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Top Contributors -->
                    @if($challenge->ideas->count() > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 animate-slide-in-up" style="animation-delay: 0.9s;">
                        <h4 class="font-bold text-slate-900 mb-5 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Top Contributors') }}
                        </h4>
                        <div class="space-y-4">
                            @foreach($challenge->ideas->sortByDesc('ai_quality_score')->take(5) as $rank => $topIdea)
                            <div class="flex items-center gap-3 p-3 rounded-xl {{ $rank === 0 ? 'bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200' : 'bg-slate-50 border border-slate-100' }} transition-all hover:shadow-sm">
                                <!-- Rank Badge -->
                                <div class="w-8 h-8 {{ $rank === 0 ? 'bg-gradient-to-br from-amber-400 to-yellow-500' : ($rank === 1 ? 'bg-gradient-to-br from-slate-300 to-slate-400' : ($rank === 2 ? 'bg-gradient-to-br from-amber-600 to-orange-700' : 'bg-slate-200')) }} rounded-full flex items-center justify-center text-{{ $rank < 3 ? 'white' : 'slate-600' }} text-xs font-bold shadow-sm">
                                    {{ $rank + 1 }}
                                </div>

                                <!-- Avatar -->
                                <div class="w-10 h-10 bg-gradient-to-br from-violet-400 to-purple-500 rounded-xl flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($topIdea->volunteer->user->name ?? 'U', 0, 1)) }}
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $topIdea->volunteer->user->name ?? __('Anonymous') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Score') }}: {{ number_format($topIdea->ai_quality_score, 1) }}</p>
                                </div>

                                <!-- Star for top quality -->
                                @if($topIdea->ai_quality_score >= 7)
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <span class="text-emerald-500 text-xs">⭐</span>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Participation Stats -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-lg p-6 text-white animate-slide-in-up" style="animation-delay: 1s;">
                        <h4 class="font-bold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            {{ __('Participation') }}
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">{{ __('Total Ideas') }}</span>
                                <span class="font-bold">{{ $totalIdeas }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">{{ __('High Quality') }}</span>
                                <span class="font-bold text-emerald-300">{{ $highQualityIdeas->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-white/80 text-sm">{{ __('Success Rate') }}</span>
                                <span class="font-bold">
                                    {{ $totalIdeas > 0 ? round(($highQualityIdeas->count() / $totalIdeas) * 100) : 0 }}%
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4 pt-4 border-t border-white/20">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="text-white/70">{{ __('Quality Distribution') }}</span>
                            </div>
                            <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full transition-all" style="width: {{ $totalIdeas > 0 ? ($highQualityIdeas->count() / $totalIdeas) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
