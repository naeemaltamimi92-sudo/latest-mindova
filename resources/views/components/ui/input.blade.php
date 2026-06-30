{{--
    Premium text/email input with icon + error state. RTL-aware internally
    (mirrors the $isRTL/$pl/$left pattern already used by hand in the auth views).

    Usage:
    <x-ui.input name="email" type="email" label="Email Address" icon="mail" required autofocus />
--}}

@props([
    'label' => null,
    'icon' => null,
    'type' => 'text',
    'name',
    'id' => null,
    'value' => null,
    'required' => false,
    'autofocus' => false,
    'placeholder' => null,
    'hint' => null,
    'forceLtr' => false,
])

@php
$id = $id ?? $name;
$isRTL = app()->getLocale() === 'ar';
$textAlign = $isRTL ? 'text-right' : 'text-left';
$iconSide = $isRTL ? 'right' : 'left';
$paddingSide = $isRTL ? 'pr' : 'pl';
$hasError = $errors->has($name);
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 {{ $textAlign }}">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 {{ $iconSide }}-0 {{ $paddingSide }}-3 flex items-center pointer-events-none">
                <x-icon :name="$icon" class="h-4 w-4 text-gray-400" />
            </div>
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value ?? old($name) }}"
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($forceLtr) dir="ltr" @endif
            {{ $attributes->merge([
                'class' => 'w-full ' . ($icon ? "{$paddingSide}-10" : 'px-4') . ' ' . ($icon ? ($isRTL ? 'pl-4' : 'pr-4') : '') . ' py-2.5 bg-white dark:bg-gray-800 border rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 transition-premium-fast ' . $textAlign . ' ' . ($hasError ? 'border-red-400 focus:border-red-500 focus:ring-1 focus:ring-red-200' : 'border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-1 focus:ring-primary-200')
            ]) }}
        >
    </div>

    @if($hint && !$hasError)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $textAlign }}">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1 {{ $isRTL ? 'flex-row-reverse' : '' }}">
            <x-icon name="alert-circle" class="w-3.5 h-3.5 flex-shrink-0" />
            {{ $message }}
        </p>
    @enderror
</div>
