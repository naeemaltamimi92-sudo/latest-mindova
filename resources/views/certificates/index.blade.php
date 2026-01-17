@extends('layouts.app')

@section('title', __('My Certificates'))

@push('styles')
<style>
    .certificate-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .certificate-card:hover {
        transform: translateY(-6px);
    }
    .animate-slide-in-up {
        animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .badge-shine {
        position: relative;
        overflow: hidden;
    }
    .badge-shine::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to right,
            transparent,
            rgba(255, 255, 255, 0.3),
            transparent
        );
        transform: rotate(45deg);
        animation: shine 3s infinite;
    }
    @keyframes shine {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Premium Hero Section -->
    <div class="relative overflow-hidden bg-secondary-300 py-12 mb-10 mx-4 sm:mx-6 lg:mx-8 rounded-3xl shadow-2xl">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full "></div>
            <div class="absolute bottom-0 right-0 w-full h-full "></div>
            <div class="floating-element absolute top-10 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="floating-element absolute bottom-10 right-10 w-80 h-80 bg-yellow-300/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-slide-in-up">
                <!-- Badge Icon -->
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-md border border-white/30 rounded-3xl mb-6 badge-shine">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>

                <!-- Status Badge -->
                <div class="inline-flex items-center space-x-2 bg-white/15 backdrop-blur-md border border-white/20 rounded-full px-4 py-2 mb-4">
                    <div class="relative">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                    <span class="text-xs font-semibold text-white/95 tracking-wide">{{ __('Professional Achievements') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-3xl md:text-4xl font-black text-white mb-3 leading-tight tracking-tight">
                    {{ __('My') }} <span class="text-white">{{ __('Certificates') }}</span>
                </h1>

                <p class="text-base text-white/90 font-medium max-w-2xl mx-auto">
                    {{ __('View and download your professional certificates that showcase your valuable contributions') }}
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <!-- Certificates Grid -->
        @forelse($certificates as $index => $certificate)
        <div class="certificate-card bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden mb-6" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s">
            <!-- Top Accent -->
            <div class="h-1.5 bg-secondary-400"></div>

            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                    <div class="flex-1">
                        <!-- Certificate Header -->
                        <div class="flex items-start gap-5 mb-6">
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-secondary-400 rounded-2xl blur opacity-40 group-hover:opacity-60 transition"></div>
                                <div class="relative w-16 h-16 bg-secondary-300 rounded-2xl flex items-center justify-center shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-slate-900 mb-1">{{ $certificate->challenge->title }}</h2>
                                <p class="text-slate-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $certificate->company->name ?? __('Company') }}
                                </p>
                            </div>
                        </div>

                        <!-- Certificate Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-2xl p-4 border border-amber-100">
                                <p class="text-xs font-bold text-amber-600 uppercase tracking-wide mb-1">{{ __('Certificate Type') }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ ucfirst($certificate->certificate_type) }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-indigo-100">
                                <p class="text-xs font-bold text-indigo-600 uppercase tracking-wide mb-1">{{ __('Role') }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ $certificate->role }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-emerald-100">
                                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wide mb-1">{{ __('Total Hours') }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ number_format($certificate->total_hours, 1) }} hrs</p>
                            </div>
                        </div>

                        <!-- Contribution Summary -->
                        <div class="bg-gray-50 border-l-4 border-amber-500 rounded-r-xl p-5 mb-5">
                            <p class="text-slate-700 leading-relaxed">{{ $certificate->contribution_summary }}</p>
                        </div>

                        <!-- Contribution Types -->
                        @if(!empty($certificate->contribution_types))
                        <div class="flex flex-wrap gap-2 mb-5">
                            @foreach($certificate->contribution_types as $type)
                            <span class="px-4 py-2 bg-secondary-100 text-amber-800 text-sm font-semibold rounded-xl border border-amber-200">
                                {{ $type }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Status Badge -->
                        @if($certificate->is_revoked)
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 text-sm font-bold rounded-xl mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ __('Revoked') }}
                        </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <span class="font-mono font-semibold text-slate-700">{{ $certificate->certificate_number }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ __('Issued:') }} {{ $certificate->issued_at?->format('F d, Y') ?? $certificate->created_at->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex lg:flex-col gap-3">
                        <x-ui.button as="a" href="{{ route('certificates.show', $certificate) }}" variant="secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ __('View') }}
                        </x-ui.button>
                        @if(!$certificate->is_revoked)
                        <x-ui.button as="a" href="{{ route('certificates.download', $certificate) }}" variant="outline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            {{ __('Download') }}
                        </x-ui.button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Premium Empty State -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="relative group inline-block mb-8">
                    <div class="absolute -inset-2 bg-secondary-400 rounded-3xl blur opacity-30"></div>
                    <div class="relative w-28 h-28 bg-secondary-300 rounded-3xl flex items-center justify-center shadow-2xl">
                        <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-3">{{ __('No Certificates Yet') }}</h3>
                <p class="text-slate-500 text-lg mb-8">{{ __('Complete challenges to earn professional certificates that showcase your contributions.') }}</p>
                <x-ui.button as="a" href="{{ route('tasks.available') }}" variant="secondary" size="lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    {{ __('Browse Available Tasks') }}
                </x-ui.button>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
