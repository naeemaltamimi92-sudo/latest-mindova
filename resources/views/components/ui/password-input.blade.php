{{--
    Password input with show/hide toggle and optional client-side strength meter.
    Strength meter is purely cosmetic (length/character-class heuristic) — it never
    blocks submission and has no bearing on server-side password validation rules.

    Usage:
    <x-ui.password-input name="password" label="Password" :showStrength="true" />
--}}

@props([
    'label' => null,
    'name',
    'id' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'showStrength' => false,
])

@php
$id = $id ?? $name;
$isRTL = app()->getLocale() === 'ar';
$textAlign = $isRTL ? 'text-right' : 'text-left';
$iconSide = $isRTL ? 'right' : 'left';
$paddingSide = $isRTL ? 'pr' : 'pl';
$toggleSide = $isRTL ? 'left' : 'right';
$hasError = $errors->has($name);
@endphp

<div x-data="{ visible: false, value: '', get score() {
        if (!this.value) return 0;
        let s = 0;
        if (this.value.length >= 8) s++;
        if (this.value.length >= 12) s++;
        if (/[A-Z]/.test(this.value) && /[a-z]/.test(this.value)) s++;
        if (/[0-9]/.test(this.value)) s++;
        if (/[^A-Za-z0-9]/.test(this.value)) s++;
        return Math.min(s, 4);
    } }">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5 {{ $textAlign }}">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <div class="absolute inset-y-0 {{ $iconSide }}-0 {{ $paddingSide }}-3 flex items-center pointer-events-none">
            <x-icon name="lock" class="h-4 w-4 text-gray-400" />
        </div>

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            :type="visible ? 'text' : 'password'"
            x-model="value"
            dir="ltr"
            @if($required) required @endif
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            {{ $attributes->merge([
                'class' => "w-full {$paddingSide}-10 " . ($isRTL ? 'pl-10' : 'pr-10') . " py-2.5 bg-white dark:bg-gray-800 border rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 transition-premium-fast " . $textAlign . ' ' . ($hasError ? 'border-red-400 focus:border-red-500 focus:ring-1 focus:ring-red-200' : 'border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-1 focus:ring-primary-200')
            ]) }}
        >

        <button type="button" @click="visible = !visible"
                class="absolute inset-y-0 {{ $toggleSide }}-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                tabindex="-1" aria-label="{{ __('Toggle password visibility') }}">
            <x-icon name="eye" class="h-4 w-4" x-show="!visible" />
            <x-icon name="eye-off" class="h-4 w-4" x-show="visible" x-cloak />
        </button>
    </div>

    @if($showStrength)
        <div class="mt-2" x-show="value.length > 0" x-cloak>
            <div class="flex gap-1">
                <template x-for="i in 4" :key="i">
                    <div class="h-1 flex-1 rounded-full transition-premium-fast"
                         :class="score >= i ? (score <= 1 ? 'bg-red-400' : score === 2 ? 'bg-amber-400' : score === 3 ? 'bg-primary-400' : 'bg-emerald-500') : 'bg-gray-200'"></div>
                </template>
            </div>
            <p class="text-xs mt-1 {{ $textAlign }}"
               :class="score <= 1 ? 'text-red-500' : score === 2 ? 'text-amber-500' : score === 3 ? 'text-primary-500' : 'text-emerald-600'"
               x-text="score <= 1 ? '{{ __('Weak password') }}' : score === 2 ? '{{ __('Fair password') }}' : score === 3 ? '{{ __('Good password') }}' : '{{ __('Strong password') }}'"></p>
        </div>
    @endif

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
