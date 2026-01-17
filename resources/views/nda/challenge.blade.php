@extends('layouts.app')

@section('title', __('Sign Challenge NDA'))

@section('content')
<div class="max-w-4xl mx-auto py-8 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 sm:mb-10">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">{{ __('Challenge-Specific NDA') }}</h1>
        <p class="text-sm sm:text-base text-gray-600">{{ __('Sign the NDA to access full challenge details') }}</p>
    </div>

    <!-- Challenge Info -->
    <div class="card-premium bg-white px-6 sm:px-8 md:px-10 py-6 sm:py-8 mb-6 sm:mb-8">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $challenge->title }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('Challenge ID:') }} #{{ $challenge->id }}</p>
                <div class="mt-2 flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($challenge->confidentiality_level === 'critical') bg-red-100 text-red-800
                        @elseif($challenge->confidentiality_level === 'high') bg-orange-100 text-orange-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($challenge->confidentiality_level ?? 'standard') }} {{ __('Confidentiality') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Warning for High/Critical Confidentiality -->
    @if(in_array($challenge->confidentiality_level, ['high', 'critical']))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ $challenge->confidentiality_level === 'critical' ? __('CRITICAL') : __('HIGH') }} {{ __('CONFIDENTIALITY LEVEL') }}
                </h3>
                <p class="text-sm text-red-700 mt-1">
                    {{ __('This challenge contains highly sensitive information. Unauthorized disclosure may result in serious legal consequences. Please read the NDA carefully before proceeding.') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- NDA Content Card -->
    <div class="card-premium bg-white px-6 sm:px-8 md:px-10 lg:px-12 xl:px-14 py-8 sm:py-10 md:py-12 mb-6 sm:mb-8">
        <div class="mb-4 sm:mb-5 flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $ndaTemplate->title }}</h2>
            <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                {{ __('Version') }} {{ $ndaTemplate->version }}
            </span>
        </div>

        <!-- Info Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-5 mb-4 sm:mb-6">
            <p class="text-sm text-blue-800">
                <strong>{{ __('Note:') }}</strong> {{ __('This is a challenge-specific NDA in addition to the general NDA you signed upon registration. It contains specific terms and conditions for this particular challenge.') }}
            </p>
        </div>

        <!-- Scrollable NDA Content -->
        <div class="border border-gray-300 rounded-lg p-6 bg-gray-50 max-h-96 overflow-y-auto mb-6 sm:mb-8">
            <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $ndaContent }}</div>
        </div>

        <!-- Signature Form -->
        <form method="POST" action="{{ route('nda.challenge.sign', $challenge) }}" class="space-y-5 sm:space-y-6 md:space-y-7" id="ndaForm">
            @csrf

            <div>
                <label for="full_name" class="block text-xs sm:text-sm font-bold text-gray-900 mb-2 sm:mb-2.5">{{ __('Full Legal Name') }} <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="{{ old('full_name', $volunteer->user->name) }}"
                    required
                    class="input-field @error('full_name') border-red-500 @enderror"
                    placeholder="{{ __('Enter your full legal name') }}"
                >
                @error('full_name')
                    <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">{{ __('This should match the name on your official documents') }}</p>
            </div>

            <!-- Challenge-Specific Terms (if any) -->
            @if($challenge->nda_custom_terms)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-yellow-900 mb-2">{{ __('Additional Challenge-Specific Terms:') }}</h3>
                <div class="text-sm text-yellow-800 whitespace-pre-wrap">{{ $challenge->nda_custom_terms }}</div>
            </div>
            @endif

            <!-- Consent Checkbox -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="agree"
                            name="agree"
                            type="checkbox"
                            required
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded @error('agree') border-red-500 @enderror"
                        >
                    </div>
                    <div class="ml-3">
                        <label for="agree" class="text-sm font-medium text-gray-900">
                            {{ __('I have read and agree to the terms of this Challenge-Specific NDA') }} <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ __('By checking this box and submitting this form, you are electronically signing this agreement and agree to be legally bound by its terms. You acknowledge that:') }}
                        </p>
                        <ul class="text-xs text-gray-600 mt-1 ml-4 list-disc list-inside">
                            <li>{{ __('You will maintain strict confidentiality of all challenge information') }}</li>
                            <li>{{ __('You will not disclose any details outside your assigned team') }}</li>
                            <li>{{ __('Breach of this agreement may result in legal action') }}</li>
                        </ul>
                        @error('agree')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 sm:mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Legal Notice -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-5 text-xs text-gray-600">
                <p class="mb-2"><strong>{{ __('Digital Signature Information:') }}</strong></p>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Challenge ID:') }} #{{ $challenge->id }}</li>
                    <li>{{ __('Confidentiality Level:') }} {{ ucfirst($challenge->confidentiality_level ?? 'standard') }}</li>
                    <li>{{ __('Date and time:') }} {{ now()->format('Y-m-d H:i:s') }}</li>
                    <li>{{ __('IP address:') }} {{ request()->ip() }}</li>
                    <li>{{ __('User:') }} {{ $volunteer->user->email }}</li>
                </ul>
                <p class="mt-2">{{ __('This signature will be cryptographically hashed and stored securely for verification purposes.') }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t">
                <x-ui.button as="a" href="{{ route('challenges.index') }}" variant="ghost">
                    {{ __('Cancel') }}
                </x-ui.button>
                <x-ui.button as="submit" variant="primary" size="lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Sign NDA & View Challenge') }}
                </x-ui.button>
            </div>
        </form>
    </div>

    <!-- Support Section -->
    <div class="text-center text-sm text-gray-500">
        <p>{{ __('Have questions about this NDA?') }}
            <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700">{{ __('Contact our support team') }}</a>
        </p>
    </div>
</div>

<script>
document.getElementById('ndaForm').addEventListener('submit', function(e) {
    const checkbox = document.getElementById('agree');
    const fullName = document.getElementById('full_name');

    if (!checkbox.checked) {
        e.preventDefault();
        alert('{{ __('Please read and agree to the NDA terms before proceeding.') }}');
        checkbox.focus();
        return false;
    }

    if (!fullName.value.trim()) {
        e.preventDefault();
        alert('{{ __('Please enter your full legal name.') }}');
        fullName.focus();
        return false;
    }

    // Confirm before submission
    const confirmMessage = '{{ __('By clicking OK, you confirm that you have read and understood the challenge-specific NDA and agree to be legally bound by its terms.\n\nYou acknowledge that unauthorized disclosure of challenge information may result in legal consequences.\n\nDo you wish to proceed?') }}';
    if (!confirm(confirmMessage)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
