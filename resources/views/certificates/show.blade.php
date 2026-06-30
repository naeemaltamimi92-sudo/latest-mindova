@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('certificates.index') }}" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-premium-fast">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back to Certificates') }}
            </a>
        </div>

        <!-- Certificate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl elevation-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Header with Gradient -->
            <div class="bg-aurora px-8 py-12 text-white text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-20 w-20 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">{{ __('Certificate of') }} {{ ucfirst($certificate->certificate_type) }}</h1>
                <p class="text-xl opacity-90">{{ __('This certifies that') }}</p>
            </div>

            <!-- Certificate Content -->
            <div class="px-8 py-10">
                <!-- Volunteer Name -->
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $certificate->volunteer->name }}</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mt-2">{{ $certificate->role }}</p>
                </div>

                <!-- Challenge Info -->
                <div class="bg-gray-50 dark:bg-gray-900/40 rounded-lg p-6 mb-8 border border-transparent dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">{{ $certificate->challenge->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('Domain:') }} {{ $certificate->challenge->domain ?? __('General') }}</p>
                </div>

                <!-- Contribution Summary -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Contribution Summary') }}</h4>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $certificate->contribution_summary }}</p>
                </div>

                <!-- Contribution Types -->
                @if(!empty($certificate->contribution_types))
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Areas of Contribution') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($certificate->contribution_types as $type)
                                <span class="px-4 py-2 bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 text-sm font-medium rounded-full">
                                    {{ $type }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Time Investment -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('Time Investment') }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg text-center border border-transparent dark:border-gray-700">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Total Hours') }}</p>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ number_format($certificate->total_hours, 1) }}</p>
                        </div>
                        @if($certificate->time_breakdown)
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg text-center border border-transparent dark:border-gray-700">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Analysis') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($certificate->time_breakdown['analysis'], 1) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg text-center border border-transparent dark:border-gray-700">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Execution') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($certificate->time_breakdown['execution'], 1) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-lg text-center border border-transparent dark:border-gray-700">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('Review') }}</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($certificate->time_breakdown['review'], 1) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Certificate Meta -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Certificate Number') }}</p>
                            <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">{{ $certificate->certificate_number }}</p>
                        </div>
                        <div class="md:text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Issued On') }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $certificate->issued_at?->format('F d, Y') ?? $certificate->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    @if($certificate->is_revoked)
                        <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-red-800 dark:text-red-300">{{ __('This certificate has been revoked') }}</p>
                                    <p class="text-sm text-red-700 dark:text-red-400">{{ __('Revoked on') }} {{ $certificate->revoked_at?->format('F d, Y') ?? __('Unknown date') }}</p>
                                    <p class="text-sm text-red-700 dark:text-red-400">{{ __('Reason:') }} {{ $certificate->revocation_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                            {{ __('Issued by') }} {{ $certificate->company->name ?? __('Company') }} {{ __('through the MINDOVA Platform') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 px-8 py-6">

                @if(!$certificate->is_revoked)
                @php
                    $certUrl      = route('certificates.show', $certificate);
                    $certName     = ucfirst($certificate->certificate_type) . ' Certificate — ' . $certificate->challenge->title;
                    $issueYear    = ($certificate->issued_at ?? $certificate->created_at)->format('Y');
                    $issueMonth   = ($certificate->issued_at ?? $certificate->created_at)->format('n');

                    $linkedInAddUrl = 'https://www.linkedin.com/profile/add?' . http_build_query([
                        'startTask'        => 'CERTIFICATION_NAME',
                        'name'             => $certName,
                        'organizationName' => 'Mindova',
                        'issueYear'        => $issueYear,
                        'issueMonth'       => $issueMonth,
                        'certUrl'          => $certUrl,
                        'certId'           => $certificate->certificate_number,
                    ]);

                    $linkedInShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?' . http_build_query([
                        'url' => $certUrl,
                    ]);
                @endphp

                {{-- LinkedIn CTAs --}}
                <div class="mb-5 p-4 rounded-xl border border-[#0A66C2]/20 bg-gradient-to-r from-[#EFF6FF] to-[#DBEAFE]">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 flex-shrink-0" style="color:#0A66C2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span class="text-sm font-bold text-blue-900">{{ __('Share your achievement on LinkedIn') }}</span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        {{-- Add to LinkedIn Profile (official certification feature) --}}
                        <a href="{{ $linkedInAddUrl }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white shadow-sm transition-all hover:opacity-90 hover:shadow-md"
                           style="background:#0A66C2;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            {{ __('Add to LinkedIn Profile') }}
                        </a>
                        {{-- Share as post --}}
                        <a href="{{ $linkedInShareUrl }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold border-2 transition-all hover:bg-[#0A66C2] hover:text-white hover:border-[#0A66C2]"
                           style="border-color:#0A66C2;color:#0A66C2;background:white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            {{ __('Share on LinkedIn') }}
                        </a>
                    </div>
                    <p class="text-[11px] text-blue-600 mt-2">
                        {{ __('Adding to your profile puts this certificate in your LinkedIn Certifications section — visible to recruiters.') }}
                    </p>
                </div>
                @endif

                <div class="flex justify-between items-center">
                    @if(!$certificate->is_revoked)
                        <x-ui.button as="a" href="{{ route('certificates.download', $certificate) }}" variant="primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('Download PDF') }}
                        </x-ui.button>
                    @else
                        <div></div>
                    @endif
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('certificates.verify') }}?certificate_number={{ $certificate->certificate_number }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                            {{ __('Verify this certificate') }} →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
