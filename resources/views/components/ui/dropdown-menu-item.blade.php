{{--
    shadcn-style Dropdown Menu Item Component for Laravel Blade

    Usage:
    <x-ui.dropdown-menu-item href="/profile">Profile</x-ui.dropdown-menu-item>
    <x-ui.dropdown-menu-item @click="logout()" danger>Logout</x-ui.dropdown-menu-item>
    <x-ui.dropdown-menu-item as="button" type="submit">Submit</x-ui.dropdown-menu-item>
--}}

@props([
    'as' => 'a',
    'href' => null,
    'danger' => false,
    'disabled' => false,
])

@php
$tag = $href ? 'a' : ($as === 'submit' ? 'button' : $as);
$type = $tag === 'button' ? ($as === 'submit' ? 'submit' : 'button') : null;

$baseClasses = 'flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium transition-colors duration-150';
$colorClasses = $danger
    ? 'text-danger-500 hover:bg-danger-50 hover:text-danger-600'
    : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900';
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
@endphp

<{{ $tag }}
    {{ $attributes->class([$baseClasses, $colorClasses, $disabledClasses])->merge([
        'href' => $href,
        'type' => $type,
        'disabled' => $tag === 'button' ? $disabled : null,
    ]) }}
    @if(!$disabled)
    @click="open = false"
    @endif
>
    {{ $slot }}
</{{ $tag }}>
