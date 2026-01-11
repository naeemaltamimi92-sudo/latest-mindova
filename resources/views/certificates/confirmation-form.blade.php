@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                {{ __('Confirm Challenge Completion') }}
            </h1>
            <p class="text-lg text-gray-600">
                {{ __('Issue certificates to contributors who contributed to this challenge') }}
            </p>
        </div>

        <!-- Challenge Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">{{ $challenge->title }}</h2>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>{{ $challenge->domain ?? __('General') }}</span>
                </div>
            </div>

            <!-- Volunteers List -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('Contributors who participated') }} ({{ $volunteers->count() }})
                </h3>
                <div class="space-y-3">
                    @forelse($volunteers as $volunteer)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-lg">
                                    {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $volunteer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $volunteer->email }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            {{ __('No volunteers found for this challenge') }}
                        </div>
                    @endforelse
                </div>
            </div>

            @if($volunteers->count() > 0)
                <!-- Confirmation Form -->
                <form action="{{ route('challenges.issue-certificates', $challenge) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Confirmation Checkbox -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <input type="checkbox" name="confirmed" id="confirmed" required
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                            </div>
                            <div class="ml-3">
                                <label for="confirmed" class="text-base font-medium text-gray-900">
                                    {{ __('I confirm that this challenge was successfully addressed through the Mindova platform') }}
                                </label>
                                <p class="mt-2 text-sm text-gray-700">
                                    {{ __('By checking this box, you confirm that the volunteers listed above made valuable contributions to solving this challenge, and you authorize the issuance of certificates.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Type Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            {{ __('Certificate Type') }}
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <input type="radio" name="certificate_type" id="type_participation" value="participation" checked
                                    class="peer sr-only">
                                <label for="type_participation"
                                    class="flex flex-col p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                    <span class="text-lg font-semibold text-gray-900 mb-2">{{ __('Participation') }}</span>
                                    <span class="text-sm text-gray-600">
                                        {{ __('Volunteers participated and contributed to the challenge') }}
                                    </span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="certificate_type" id="type_completion" value="completion"
                                    class="peer sr-only">
                                <label for="type_completion"
                                    class="flex flex-col p-6 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                    <span class="text-lg font-semibold text-gray-900 mb-2">{{ __('Completion') }}</span>
                                    <span class="text-sm text-gray-600">
                                        {{ __('Challenge was fully completed and delivered successfully') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Company Logo Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            {{ __('Company Logo') }} ({{ __('Optional') }})
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="company_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ __('Upload a file') }}</span>
                                        <input id="company_logo" name="company_logo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">{{ __('or drag and drop') }}</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ __('PNG, JPG up to 2MB') }}
                                </p>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('Your company logo will appear on all certificates. If not provided, a placeholder will be used.') }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('challenges.show', $challenge) }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            {{ __('Generate Certificates for :count Volunteers', ['count' => $volunteers->count()]) }}
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('What happens next?') }}</h3>
            <ul class="space-y-3">
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('AI will generate personalized contribution summaries for each volunteer') }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('Certificates will be generated as professional PDF documents') }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('Volunteers will be able to view and download their certificates from their profile') }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-700">{{ __('Each certificate will have a unique verification number') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
