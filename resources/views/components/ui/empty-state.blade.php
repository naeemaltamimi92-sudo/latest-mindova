{{--
    Shared empty-state block. Consolidates ~22 previously ad-hoc variants
    (icon-circle+text, plain text, dashed-border box) into one component.

    Usage:
    <x-ui.empty-state icon="file-text" title="No challenges yet" description="..." />
    <x-ui.empty-state icon="users" title="No contributors yet">
        <x-slot:action><x-ui.button as="a" href="...">Invite someone</x-ui.button></x-slot:action>
    </x-ui.empty-state>
--}}

@props([
    'icon' => 'file-text',
    'title',
    'description' => null,
    'size' => 'default', // 'sm' | 'default'
])

@php
$isSmall = $size === 'sm';
$iconBoxClass = $isSmall ? 'w-12 h-12 rounded-xl' : 'w-16 h-16 rounded-2xl';
$iconSizeClass = $isSmall ? 'w-6 h-6' : 'w-7 h-7';
@endphp

<div {{ $attributes->class(['text-center py-12']) }}>
    <div class="{{ $iconBoxClass }} bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
        <x-icon :name="$icon" class="{{ $iconSizeClass }} text-gray-400 dark:text-gray-500" stroke-width="1.5" />
    </div>
    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    @if($description)
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
