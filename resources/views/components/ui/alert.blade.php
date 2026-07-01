{{--
    Shared flash/alert banner. Consolidates ~5 previously ad-hoc treatments
    (varying radius, green vs emerald, with/without icon, with/without
    dismiss) into one component built on the real success/warning/danger/
    info tokens fixed in Phase 1.

    Usage:
    <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
    <x-ui.alert type="error" dismissible>{{ session('error') }}</x-ui.alert>
--}}

@props([
    'type' => 'info', // success | error | warning | info
    'dismissible' => false,
])

@php
$tokens = [
    'success' => ['bg' => 'bg-success-50 dark:bg-success-500/10', 'border' => 'border-success-200 dark:border-success-500/30', 'text' => 'text-success-800 dark:text-success-300', 'icon' => 'text-success-500'],
    'error'   => ['bg' => 'bg-danger-50 dark:bg-danger-500/10', 'border' => 'border-danger-200 dark:border-danger-500/30', 'text' => 'text-danger-800 dark:text-danger-300', 'icon' => 'text-danger-500'],
    'warning' => ['bg' => 'bg-warning-50 dark:bg-warning-500/10', 'border' => 'border-warning-200 dark:border-warning-500/30', 'text' => 'text-warning-800 dark:text-warning-300', 'icon' => 'text-warning-500'],
    'info'    => ['bg' => 'bg-info-50 dark:bg-info-500/10', 'border' => 'border-info-200 dark:border-info-500/30', 'text' => 'text-info-800 dark:text-info-300', 'icon' => 'text-info-500'],
];
$t = $tokens[$type] ?? $tokens['info'];
$icons = [
    'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'error'   => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'info'    => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
];
@endphp

<div
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
    {{ $attributes->class(["flex items-start gap-3 {$t['bg']} border {$t['border']} {$t['text']} px-4 py-3 rounded-xl"]) }}
>
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $t['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$type] ?? $icons['info'] }}"/>
    </svg>
    <div class="flex-1 text-sm">{{ $slot }}</div>
    @if($dismissible)
    <button type="button" @click="show = false" class="flex-shrink-0 opacity-60 hover:opacity-100" aria-label="{{ __('Dismiss') }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    @endif
</div>
