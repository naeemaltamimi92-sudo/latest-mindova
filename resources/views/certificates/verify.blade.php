@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-4">
                <svg class="h-20 w-20 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                {{ __('Verify Certificate') }}
            </h1>
            <p class="text-lg text-gray-600">
                {{ __('Enter a certificate number to verify its authenticity') }}
            </p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <form action="{{ route('certificates.verify') }}" method="GET">
                <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Certificate Number') }}
                </label>
                <div class="flex gap-3">
                    <input type="text"
                        name="certificate_number"
                        id="certificate_number"
                        value="{{ request('certificate_number') }}"
                        placeholder="MDVA-2025-XXXXXX"
                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-lg font-mono"
                        required>
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        {{ __('Verify') }}
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    {{ __('Format: MDVA-YYYY-XXXXXX (e.g., MDVA-2025-A7B3C9)') }}
                </p>
            </form>
        </div>

        <!-- Results -->
        @if(isset($searched) && $searched)
            @if($found)
                <!-- Certificate Found -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Success Header -->
                    <div class="bg-green-50 border-b-4 border-green-500 px-8 py-6">
                        <div class="flex items-center">
                            <svg class="h-12 w-12 text-green-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h2 class="text-2xl font-bold text-green-800">{{ __('Certificate Verified') }}</h2>
                                <p class="text-green-700">{{ __('This is a valid MINDOVA certificate') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Details -->
                    <div class="px-8 py-8">
                        @if($certificate->is_revoked)
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-red-800">{{ __('This certificate has been revoked') }}</p>
                                        <p class="text-sm text-red-700">{{ __('Revoked on') }} {{ $certificate->revoked_at?->format('F d, Y') ?? __('Unknown date') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <!-- Volunteer -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Awarded To') }}</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $certificate->volunteer->name }}</p>
                            </div>

                            <!-- Challenge -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Challenge') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->challenge->title }}</p>
                                <p class="text-sm text-gray-600">{{ $certificate->challenge->domain ?? __('General') }}</p>
                            </div>

                            <!-- Role -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Role') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->role }}</p>
                            </div>

                            <!-- Type -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Certificate Type') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($certificate->certificate_type) }}</p>
                            </div>

                            <!-- Time -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Time Investment') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($certificate->total_hours, 1) }} {{ __('hours') }}</p>
                            </div>

                            <!-- Company -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Issued By') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->company->name ?? __('Company') }}</p>
                            </div>

                            <!-- Date -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Issue Date') }}</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $certificate->issued_at?->format('F d, Y') ?? $certificate->created_at->format('F d, Y') }}</p>
                            </div>

                            <!-- Certificate Number -->
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Certificate Number') }}</p>
                                <p class="text-lg font-mono font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-xs text-gray-500 text-center">
                                {{ __('Verified through the MINDOVA Platform on') }} {{ now()->format('F d, Y \a\t H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Certificate Not Found -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="text-center">
                        <svg class="h-16 w-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Certificate Not Found') }}</h2>
                        <p class="text-gray-600 mb-6">
                            {{ __('No certificate with number') }} <span class="font-mono font-semibold">{{ $certificate_number }}</span> {{ __('was found in our system.') }}
                        </p>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 text-left">
                            <p class="text-sm text-yellow-800">
                                <strong>{{ __('Please check:') }}</strong>
                            </p>
                            <ul class="list-disc list-inside text-sm text-yellow-700 mt-2 space-y-1">
                                <li>{{ __('The certificate number is correct') }}</li>
                                <li>{{ __('There are no extra spaces or typos') }}</li>
                                <li>{{ __('The format matches: MDVA-YYYY-XXXXXX') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('About Certificate Verification') }}</h3>
            <ul class="space-y-3">
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('All MINDOVA certificates have unique verification numbers') }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('Certificates are issued by companies after challenge completion') }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('Verification is instant and publicly accessible') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
