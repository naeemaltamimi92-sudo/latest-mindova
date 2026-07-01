@extends('layouts.app')

@section('title', __('Edit Feedback'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('feedback.show', $item) }}" class="text-sm text-slate-500 hover:text-primary-600 flex items-center gap-1 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        {{ __('Back') }}
    </a>

    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6">
        <h1 class="text-xl font-bold text-slate-900 mb-6">{{ __('Edit Feedback') }}</h1>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('feedback.update', $item) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Type') }}</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($types as $type)
                    <label class="flex items-center justify-center px-3 py-2.5 rounded-xl border cursor-pointer text-sm font-medium has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 has-[:checked]:text-primary-700 border-slate-200 text-slate-600">
                        <input type="radio" name="type" value="{{ $type }}" class="sr-only" {{ old('type', $item->type) === $type ? 'checked' : '' }} required>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" maxlength="150" required
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Description') }}</label>
                <textarea name="description" id="description" rows="6" minlength="20" maxlength="3000" required
                          class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 resize-none">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">{{ __('Category') }} <span class="text-slate-400 font-normal">({{ __('optional') }})</span></label>
                <input type="text" name="category" id="category" value="{{ old('category', $item->category) }}" maxlength="100"
                       class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <x-ui.button as="a" href="{{ route('feedback.show', $item) }}" variant="secondary">{{ __('Cancel') }}</x-ui.button>
                <x-ui.button type="submit" variant="primary">{{ __('Save Changes') }}</x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection
