{{--
    shadcn-style Dropdown Menu Component for Laravel Blade

    Usage:
    <x-ui.dropdown-menu>
        <x-slot:trigger>
            <x-ui.button>Open Menu</x-ui.button>
        </x-slot:trigger>
        <x-ui.dropdown-menu-item href="/profile">Profile</x-ui.dropdown-menu-item>
        <x-ui.dropdown-menu-separator />
        <x-ui.dropdown-menu-item danger>Logout</x-ui.dropdown-menu-item>
    </x-ui.dropdown-menu>
--}}

@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => '',
])

@php
$alignClasses = [
    'left' => 'left-0 origin-top-left',
    'right' => 'right-0 origin-top-right',
    'center' => 'left-1/2 -translate-x-1/2 origin-top',
];
$alignClass = $alignClasses[$align] ?? $alignClasses['right'];
$widthClass = "w-{$width}";
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }} x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false">
    {{-- Trigger --}}
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    {{-- Dropdown Content --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $widthClass }} {{ $alignClass }} rounded-xl bg-white shadow-lg ring-1 ring-black/5 py-1 {{ $contentClasses }}"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>
