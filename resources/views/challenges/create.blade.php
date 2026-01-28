@extends('layouts.app')

@section('title', __('Submit Challenge'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary-50 border border-primary-200 rounded-lg text-xs font-medium text-primary-700">
                    <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                    {{ __('New Challenge Submission') }}
                </span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Submit a New Challenge') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('Share your innovation challenge with our community of talented contributors') }}</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            {{-- Progress Steps --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between max-w-2xl mx-auto">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">1</div>
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ __('Details') }}</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-3">
                        <div class="h-full w-1/4 bg-primary-600 rounded-full"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-sm font-bold">2</div>
                        <span class="text-sm text-gray-500 hidden sm:block">{{ __('Documents') }}</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-3"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-sm font-bold">3</div>
                        <span class="text-sm text-gray-500 hidden sm:block">{{ __('Review') }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('challenges.store') }}" method="POST" class="p-6 space-y-8">
                @csrf

                {{-- Step 1: Challenge Title --}}
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 text-sm font-bold">1</span>
                        <div>
                            <span class="block text-base font-semibold text-gray-900">{{ __('Challenge Title') }}</span>
                            <span class="text-xs text-gray-500">{{ __('Give your challenge a clear, descriptive name') }}</span>
                        </div>
                    </label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-gray-900 placeholder-gray-400"
                           value="{{ old('title') }}"
                           placeholder="{{ __('e.g., Reduce office energy consumption by 30%') }}">
                    @error('title')
                    <p class="text-red-600 text-sm flex items-center gap-2 bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Step 2: Description --}}
                <div class="space-y-3">
                    <label class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 text-sm font-bold">2</span>
                        <div>
                            <span class="block text-base font-semibold text-gray-900">{{ __('Description') }}</span>
                            <span class="text-xs text-gray-500">{{ __('Explain your challenge in detail - the more context, the better') }}</span>
                        </div>
                    </label>
                    <div class="relative">
                        <textarea name="description" rows="8" required
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-gray-900 placeholder-gray-400 resize-none leading-relaxed"
                                  placeholder="{{ __('Describe your challenge in detail...\n\nInclude:\n- What problem are you trying to solve?\n- What are the current challenges?\n- What outcomes do you expect?\n- Any specific requirements or constraints?') }}">{{ old('description') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400 bg-white px-2 py-1 rounded">
                            {{ __('Min 100 characters') }}
                        </div>
                    </div>
                    @error('description')
                    <p class="text-red-600 text-sm flex items-center gap-2 bg-red-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Step 3: PDF Upload --}}
                <div class="space-y-3" id="challenge-form">
                    <label class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-700 text-sm font-bold">3</span>
                        <div>
                            <span class="block text-base font-semibold text-gray-900">{{ __('Challenge PDF Document') }}</span>
                            <span class="inline-flex items-center gap-2 text-xs text-gray-500">
                                {{ __('Upload supporting documentation') }}
                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 text-xs rounded border border-amber-200">{{ __('Recommended') }}</span>
                            </span>
                        </div>
                    </label>

                    {{-- Upload Zone --}}
                    <div id="upload-zone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 hover:border-primary-400 hover:bg-primary-50/50 cursor-pointer transition-colors">
                        <input type="file" id="attachment-upload" accept=".pdf" class="hidden">

                        <div class="space-y-4">
                            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mx-auto">
                                <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>

                            <div>
                                <button type="button" onclick="document.getElementById('attachment-upload').click()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    {{ __('Choose PDF File') }}
                                </button>
                                <p class="text-sm text-gray-500 mt-2">{{ __('or drag and drop PDF here') }}</p>
                            </div>

                            <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    {{ __('PDF only') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 101.665 6.58L10 11.414l2.835-2.834A3.5 3.5 0 1015.5 2H5.5zM4 5.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    </svg>
                                    {{ __('Max 10 MB') }}
                                </span>
                            </div>
                        </div>

                        {{-- Upload Progress --}}
                        <div id="upload-progress" class="hidden mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="upload-progress-bar" class="bg-primary-600 h-2 rounded-full" style="width: 0%"></div>
                            </div>
                            <p id="upload-status" class="text-sm text-gray-600 mt-2"></p>
                        </div>
                    </div>

                    {{-- Uploaded Files List --}}
                    <div id="attachments-list" class="space-y-2"></div>

                    {{-- PDF Benefits --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ __('How PDF documents enhance analysis') }}</h4>
                                <ul class="text-sm text-gray-600 space-y-1.5">
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('AI reads PDF alongside your description for complete understanding') }}
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Extracts technical requirements and specifications') }}
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('Generates more accurate task breakdowns') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Cards --}}
                <div class="grid md:grid-cols-2 gap-4">
                    {{-- NDA Protection --}}
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-emerald-900 mb-1">{{ __('NDA Protection') }}</h3>
                                <p class="text-sm text-emerald-700">
                                    {{ __('All challenges are protected by NDAs. Volunteers must sign before viewing your challenge details.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- AI Analysis --}}
                    <div class="bg-violet-50 border border-violet-200 rounded-xl p-5">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-violet-900 mb-1">{{ __('AI-Powered Analysis') }}</h3>
                                <p class="text-sm text-violet-700">
                                    {{ __('Our AI analyzes your challenge, breaks it into tasks, and matches qualified volunteers automatically.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- What Happens Next --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('What happens next?') }}
                    </h3>

                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach([
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => __('Challenge Submitted'), 'desc' => __('Securely stored in our database')],
                            ['icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'title' => __('AI Analysis'), 'desc' => __('Brief refined & complexity evaluated')],
                            ['icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'title' => __('Task Breakdown'), 'desc' => __('Decomposed into manageable tasks')],
                            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => __('Volunteer Matching'), 'desc' => __('Qualified volunteers assigned')]
                        ] as $step)
                        <div class="flex items-start gap-3 bg-white rounded-lg p-3 border border-gray-100">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $step['title'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ __('Submit Challenge') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('attachment-upload');
    const attachmentsList = document.getElementById('attachments-list');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('upload-progress-bar');
    const uploadStatus = document.getElementById('upload-status');
    const form = document.querySelector('form');

    let pendingFiles = [];
    let challengeId = null;

    // Drag and drop handlers
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-primary-500', 'bg-primary-50');
    });

    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-primary-500', 'bg-primary-50');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-primary-500', 'bg-primary-50');
        const files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
        fileInput.value = '';
    });

    function handleFiles(files) {
        const pdfFiles = Array.from(files).filter(file => file.type === 'application/pdf');
        if (pdfFiles.length === 0) {
            showNotification('{{ __("Please upload a PDF file only") }}', 'error');
            return;
        }

        const file = pdfFiles[0];
        if (file.size > 10 * 1024 * 1024) {
            showNotification('{{ __("File size must be less than 10MB") }}', 'error');
            return;
        }

        pendingFiles = [];
        attachmentsList.innerHTML = '';
        pendingFiles.push(file);
        addFileToList(file, 'pending');
        showNotification('{{ __("PDF ready to upload. Submit the challenge to upload the document.") }}', 'info');
    }

    function addFileToList(file, status = 'pending') {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between bg-white border border-gray-200 rounded-lg p-4';
        fileItem.dataset.fileName = file.name;

        const fileSize = formatFileSize(file.size);
        fileItem.innerHTML = `
            <div class="flex items-center gap-3 flex-1">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize}</p>
                </div>
                <div class="flex-shrink-0">
                    ${getStatusBadge(status)}
                </div>
                <button type="button" onclick="removeFile('${file.name}')" class="flex-shrink-0 text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        attachmentsList.appendChild(fileItem);
    }

    window.removeFile = function(fileName) {
        pendingFiles = pendingFiles.filter(f => f.name !== fileName);
        const fileItem = attachmentsList.querySelector(`[data-file-name="${fileName}"]`);
        if (fileItem) fileItem.remove();
        showNotification('{{ __("File removed") }}', 'success');
    };

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">{{ __("Pending") }}</span>',
            'uploading': '<span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">{{ __("Uploading...") }}</span>',
            'uploaded': '<span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">{{ __("Uploaded") }}</span>',
            'error': '<span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">{{ __("Error") }}</span>',
        };
        return badges[status] || badges.pending;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    function showNotification(message, type = 'info') {
        const colors = {
            'success': 'bg-emerald-50 border-emerald-200 text-emerald-800',
            'error': 'bg-red-50 border-red-200 text-red-800',
            'info': 'bg-blue-50 border-blue-200 text-blue-800',
        };

        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 ${colors[type]} border rounded-lg px-4 py-3 shadow-lg z-50 text-sm font-medium`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    form.addEventListener('submit', async function(e) {
        if (pendingFiles.length > 0) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __("Submitting...") }}';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (response.redirected) {
                    const redirectUrl = response.url;
                    const match = redirectUrl.match(/challenges\/(\d+)/);
                    if (match && match[1]) {
                        challengeId = match[1];
                        await uploadAllFiles(challengeId);
                        window.location.href = redirectUrl;
                    } else {
                        window.location.href = redirectUrl;
                    }
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    showNotification('{{ __("Error submitting challenge") }}', 'error');
                }
            } catch (error) {
                console.error('Submission error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                showNotification('{{ __("Error submitting challenge") }}', 'error');
            }
        }
    });

    async function uploadAllFiles(challengeId) {
        if (pendingFiles.length === 0) return;
        uploadProgress.classList.remove('hidden');
        for (let i = 0; i < pendingFiles.length; i++) {
            const file = pendingFiles[i];
            const progress = ((i + 1) / pendingFiles.length) * 100;
            progressBar.style.width = progress + '%';
            uploadStatus.textContent = `{{ __("Uploading") }} ${i + 1} {{ __("of") }} ${pendingFiles.length}: ${file.name}`;
            await uploadFile(file, challengeId);
        }
        uploadProgress.classList.add('hidden');
        pendingFiles = [];
    }

    async function uploadFile(file, challengeId) {
        const formData = new FormData();
        formData.append('file', file);
        try {
            const response = await fetch(`/challenges/${challengeId}/attachments`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                updateFileStatus(file.name, 'uploaded');
            } else {
                updateFileStatus(file.name, 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            updateFileStatus(file.name, 'error');
        }
    }

    function updateFileStatus(fileName, status) {
        const fileItem = attachmentsList.querySelector(`[data-file-name="${fileName}"]`);
        if (fileItem) {
            const statusBadge = fileItem.querySelector('span');
            if (statusBadge) statusBadge.outerHTML = getStatusBadge(status);
        }
    }
});
</script>
@endsection
