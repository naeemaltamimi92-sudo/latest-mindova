@extends('layouts.app')

@section('title', __('Edit Challenge'))

@section('content')
<!-- Premium Hero Section -->
<div class="relative overflow-hidden bg-primary-500 py-12 mb-12 rounded-3xl max-w-5xl mx-auto shadow-2xl">
    <!-- Animated Background Effects -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full "></div>
        <div class="absolute bottom-0 right-0 w-full h-full "></div>
        <div class="floating-element absolute top-10 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="floating-element absolute bottom-10 right-10 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-6 sm:px-8 text-center">
        <!-- Status Badge -->
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2.5 mb-6 shadow-lg">
            <div class="relative">
                <div class="w-2.5 h-2.5 bg-amber-400 rounded-full"></div>
                <div class="absolute inset-0 w-2.5 h-2.5 bg-amber-400 rounded-full"></div>
            </div>
            <span class="text-sm font-semibold text-white/90">{{ __('Editing Challenge') }}</span>
        </div>

        <!-- Main Heading -->
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4 leading-tight tracking-tight">
            {{ __('Edit') }}
            <span class="text-secondary-200">{{ __('Challenge') }}</span>
        </h1>
        <p class="text-lg text-white/80 font-medium leading-relaxed max-w-2xl mx-auto">
            {{ __('Update your challenge details. Changes will trigger a new AI analysis.') }}
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <!-- Warning Banner -->
    @if($challenge->status !== 'submitted' && $challenge->status !== 'rejected')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold text-amber-800">{{ __('Important Notice') }}</p>
                <p class="text-sm text-amber-700 mt-1">
                    {{ __('Editing this challenge will reset its status and trigger a new AI analysis. All existing tasks and workstreams will be regenerated.') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <form action="{{ route('challenges.update', $challenge) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-10" id="editChallengeForm">
            @csrf
            @method('PUT')

            <!-- Step 1: Challenge Title -->
            <div class="space-y-4">
                <label class="flex items-center gap-4 mb-4">
                    <span class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">1</span>
                    <div>
                        <span class="block text-lg font-black text-slate-900">{{ __('Challenge Title') }}</span>
                        <span class="text-sm text-slate-500">{{ __('Give your challenge a clear, descriptive name') }}</span>
                    </div>
                </label>
                <input type="text" name="title" required
                       class="w-full px-6 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 text-lg text-slate-900 placeholder-slate-400 shadow-sm"
                       value="{{ old('title', $challenge->title) }}"
                       placeholder="{{ __('e.g., Reduce office energy consumption by 30%') }}">
                @error('title')
                <p class="text-red-600 text-sm mt-3 font-medium flex items-center gap-2 bg-red-50 px-4 py-2 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
            </div>

            <!-- Step 2: Description -->
            <div class="space-y-4">
                <label class="flex items-center gap-4 mb-4">
                    <span class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">2</span>
                    <div>
                        <span class="block text-lg font-black text-slate-900">{{ __('Description') }}</span>
                        <span class="text-sm text-slate-500">{{ __('Explain your challenge in detail - the more context, the better') }}</span>
                    </div>
                </label>
                <div class="relative">
                    <textarea name="description" rows="12" required
                              class="w-full px-6 py-5 border-2 border-indigo-200 bg-white rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 placeholder-slate-400 resize-none shadow-sm leading-relaxed"
                              placeholder="{{ __('Describe your challenge in detail...') }}">{{ old('description', $challenge->description ?? $challenge->original_description) }}</textarea>
                    <div class="absolute bottom-4 right-4 text-xs text-slate-400 font-medium bg-white/80 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                        {{ __('Min 100 characters') }}
                    </div>
                </div>
                @error('description')
                <p class="text-red-600 text-sm mt-3 font-medium flex items-center gap-2 bg-red-50 px-4 py-2 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
            </div>

            <!-- Step 3: Deadlines -->
            <div class="space-y-4">
                <label class="flex items-center gap-4 mb-4">
                    <span class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">3</span>
                    <div>
                        <span class="block text-lg font-black text-slate-900">{{ __('Deadlines') }}</span>
                        <span class="inline-flex items-center gap-2 text-sm text-slate-500">
                            {{ __('Set timeframes for your challenge') }}
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-xs font-semibold rounded-lg">{{ __('Optional') }}</span>
                        </span>
                    </div>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ __('Submission Deadline') }}
                        </label>
                        <input type="date" name="submission_deadline" lang="en"
                               class="w-full px-5 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 shadow-sm group-hover:border-indigo-300"
                               value="{{ old('submission_deadline', $challenge->submission_deadline?->format('Y-m-d')) }}"
                               style="color-scheme: light;">
                        <p class="text-xs text-slate-500 mt-2">{{ __('When should volunteers submit their work?') }}</p>
                        @error('submission_deadline')
                        <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="group">
                        <label class="block text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Completion Deadline') }}
                        </label>
                        <input type="date" name="completion_deadline" lang="en"
                               class="w-full px-5 py-4 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-900 shadow-sm group-hover:border-violet-300"
                               value="{{ old('completion_deadline', $challenge->completion_deadline?->format('Y-m-d')) }}"
                               style="color-scheme: light;">
                        <p class="text-xs text-slate-500 mt-2">{{ __('When should the challenge be fully completed?') }}</p>
                        @error('completion_deadline')
                        <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
            </div>

            <!-- Step 4: Existing Attachments -->
            @if($challenge->attachments->count() > 0)
            <div class="space-y-4">
                <label class="flex items-center gap-4 mb-4">
                    <span class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">4</span>
                    <div>
                        <span class="block text-lg font-black text-slate-900">{{ __('Existing Attachments') }}</span>
                        <span class="text-sm text-slate-500">{{ __('Current documents attached to this challenge') }}</span>
                    </div>
                </label>

                <input type="hidden" name="remove_attachments" id="removeAttachments" value="">

                <div id="existingAttachmentsList" class="space-y-3">
                    @foreach($challenge->attachments as $attachment)
                    <div class="flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl p-5 shadow-sm" data-attachment-id="{{ $attachment->id }}">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-bold text-slate-900 truncate">{{ $attachment->file_name }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                            </div>
                            <a href="{{ route('challenges.attachments.download', [$challenge, $attachment]) }}" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 p-2.5 rounded-xl" title="{{ __('Download') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            <button type="button" onclick="removeExistingAttachment({{ $attachment->id }})" class="flex-shrink-0 text-red-600 hover:text-red-800 hover:bg-red-50 p-2.5 rounded-xl" title="{{ __('Remove') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
            </div>
            @endif

            <!-- Step 5: Upload New Attachments -->
            <div class="space-y-4">
                <label class="flex items-center gap-4 mb-4">
                    <span class="flex-shrink-0 w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg">{{ $challenge->attachments->count() > 0 ? '5' : '4' }}</span>
                    <div>
                        <span class="block text-lg font-black text-slate-900">{{ __('Add New Documents') }}</span>
                        <span class="inline-flex items-center gap-2 text-sm text-slate-500">
                            {{ __('Upload additional supporting documents') }}
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-xs font-semibold rounded-lg">{{ __('Optional') }}</span>
                        </span>
                    </div>
                </label>

                <!-- Upload Zone -->
                <div id="upload-zone" class="relative border-2 border-dashed border-indigo-300 rounded-2xl p-10 text-center bg-gray-50 hover:border-indigo-400 hover:bg-indigo-50 cursor-pointer group">
                    <input type="file" id="attachment-upload" name="attachments[]" accept=".pdf" multiple class="hidden">

                    <div class="space-y-4">
                        <div class="w-20 h-20 bg-primary-500 rounded-2xl flex items-center justify-center mx-auto shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>

                        <div>
                            <x-ui.button type="button" onclick="document.getElementById('attachment-upload').click()" variant="primary">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                {{ __('Choose PDF Files') }}
                            </x-ui.button>
                            <p class="text-sm text-slate-600 mt-3">{{ __('or drag and drop PDF here') }}</p>
                        </div>

                        <div class="flex items-center justify-center gap-6 text-xs text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                {{ __('PDF only') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 101.665 6.58L10 11.414l2.835-2.834A3.5 3.5 0 1015.5 2H5.5zM4 5.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm10 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                </svg>
                                {{ __('Max 10 MB each') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- New Files List -->
                <div id="newAttachmentsList" class="space-y-3"></div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-slate-200">
                <x-ui.button as="a" href="{{ route('challenges.show', $challenge) }}" variant="secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Cancel') }}
                </x-ui.button>
                <x-ui.button as="submit" id="submitBtn" variant="primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Save Changes') }}
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('attachment-upload');
    const newAttachmentsList = document.getElementById('newAttachmentsList');
    const removeAttachmentsInput = document.getElementById('removeAttachments');
    const form = document.getElementById('editChallengeForm');
    const submitBtn = document.getElementById('submitBtn');

    let newFiles = [];
    let attachmentsToRemove = [];

    // Drag and drop handlers
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-indigo-500', 'bg-indigo-100');
    });

    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-indigo-500', 'bg-indigo-100');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-indigo-500', 'bg-indigo-100');
        const files = Array.from(e.dataTransfer.files);
        handleNewFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleNewFiles(files);
    });

    function handleNewFiles(files) {
        const pdfFiles = files.filter(file => file.type === 'application/pdf');
        if (pdfFiles.length === 0) {
            showNotification('Please upload PDF files only', 'error');
            return;
        }

        pdfFiles.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                showNotification(`${file.name} is too large (max 10MB)`, 'error');
                return;
            }

            if (!newFiles.find(f => f.name === file.name)) {
                newFiles.push(file);
                addNewFileToList(file);
            }
        });

        // Update the file input
        updateFileInput();
    }

    function addNewFileToList(file) {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between bg-emerald-50 border-2 border-emerald-200 rounded-xl p-5 shadow-sm';
        fileItem.dataset.fileName = file.name;

        const fileSize = formatFileSize(file.size);
        fileItem.innerHTML = `
            <div class="flex items-center gap-4 flex-1">
                <div class="flex-shrink-0 w-12 h-12 bg-secondary-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base font-bold text-slate-900 truncate">${file.name}</p>
                    <p class="text-sm text-slate-500">${fileSize}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">New</span>
                <button type="button" onclick="removeNewFile('${file.name}')" class="flex-shrink-0 text-red-600 hover:text-red-800 hover:bg-red-50 p-2.5 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        newAttachmentsList.appendChild(fileItem);
    }

    window.removeNewFile = function(fileName) {
        newFiles = newFiles.filter(f => f.name !== fileName);
        const fileItem = newAttachmentsList.querySelector(`[data-file-name="${fileName}"]`);
        if (fileItem) fileItem.remove();
        updateFileInput();
        showNotification('File removed', 'success');
    };

    window.removeExistingAttachment = function(attachmentId) {
        attachmentsToRemove.push(attachmentId);
        removeAttachmentsInput.value = attachmentsToRemove.join(',');

        const attachmentItem = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
        if (attachmentItem) {
            attachmentItem.style.opacity = '0.5';
            attachmentItem.innerHTML = `
                <div class="flex items-center gap-4 flex-1">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-medium text-slate-500 line-through">Marked for removal</p>
                    </div>
                    <button type="button" onclick="undoRemoveAttachment(${attachmentId})" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 px-3 py-1.5 rounded-lg text-sm font-semibold">
                        Undo
                    </button>
                </div>
            `;
        }
    };

    window.undoRemoveAttachment = function(attachmentId) {
        attachmentsToRemove = attachmentsToRemove.filter(id => id !== attachmentId);
        removeAttachmentsInput.value = attachmentsToRemove.join(',');
        location.reload(); // Simplest way to restore the attachment UI
    };

    function updateFileInput() {
        // Create a new DataTransfer to update the file input
        const dt = new DataTransfer();
        newFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
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
            'success': 'bg-emerald-100 border-emerald-300 text-emerald-800',
            'error': 'bg-red-100 border-red-300 text-red-800',
            'info': 'bg-blue-100 border-blue-300 text-blue-800',
        };

        const notification = document.createElement('div');
        notification.className = `fixed bottom-6 right-6 ${colors[type]} border-2 rounded-xl px-6 py-4 shadow-xl z-50 animate-slide-in font-semibold`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Form submit loading state
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ __('Saving...') }}
        `;
    });
});
</script>

<style>
@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.3s; }
</style>
@endsection
