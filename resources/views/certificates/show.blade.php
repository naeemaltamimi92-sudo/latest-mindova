@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('certificates.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back to Certificates') }}
            </a>
        </div>

        <!-- Certificate Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-12 text-white text-center">
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
                    <h2 class="text-4xl font-bold text-gray-900">{{ $certificate->volunteer->name }}</h2>
                    <p class="text-xl text-gray-600 mt-2">{{ $certificate->role }}</p>
                </div>

                <!-- Challenge Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $certificate->challenge->title }}</h3>
                    <p class="text-gray-600">{{ __('Domain:') }} {{ $certificate->challenge->domain ?? __('General') }}</p>
                </div>

                <!-- Contribution Summary -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Contribution Summary') }}</h4>
                    <p class="text-gray-700 leading-relaxed">{{ $certificate->contribution_summary }}</p>
                </div>

                <!-- Contribution Types -->
                @if(!empty($certificate->contribution_types))
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Areas of Contribution') }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($certificate->contribution_types as $type)
                                <span class="px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                    {{ $type }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Time Investment -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Time Investment') }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-xs text-gray-600 mb-1">{{ __('Total Hours') }}</p>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($certificate->total_hours, 1) }}</p>
                        </div>
                        @if($certificate->time_breakdown)
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-xs text-gray-600 mb-1">{{ __('Analysis') }}</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($certificate->time_breakdown['analysis'], 1) }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-xs text-gray-600 mb-1">{{ __('Execution') }}</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($certificate->time_breakdown['execution'], 1) }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-xs text-gray-600 mb-1">{{ __('Review') }}</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($certificate->time_breakdown['review'], 1) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Certificate Meta -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Certificate Number') }}</p>
                            <p class="text-lg font-mono font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                        </div>
                        <div class="md:text-right">
                            <p class="text-sm text-gray-600">{{ __('Issued On') }}</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $certificate->issued_at?->format('F d, Y') ?? $certificate->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    @if($certificate->is_revoked)
                        <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-red-800">{{ __('This certificate has been revoked') }}</p>
                                    <p class="text-sm text-red-700">{{ __('Revoked on') }} {{ $certificate->revoked_at?->format('F d, Y') ?? __('Unknown date') }}</p>
                                    <p class="text-sm text-red-700">{{ __('Reason:') }} {{ $certificate->revocation_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 text-center">
                            {{ __('Issued by') }} {{ $certificate->company->name ?? __('Company') }} {{ __('through the MINDOVA Platform') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-gray-50 px-8 py-6 flex justify-between items-center">
                @if(!$certificate->is_revoked)
                    <a href="{{ route('certificates.download', $certificate) }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Download PDF') }}
                    </a>
                @else
                    <div></div>
                @endif
                <div class="text-sm text-gray-500">
                    <a href="{{ route('certificates.verify') }}?certificate_number={{ $certificate->certificate_number }}" class="text-blue-600 hover:text-blue-700">
                        {{ __('Verify this certificate') }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
