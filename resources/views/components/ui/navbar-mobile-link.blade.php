{{--
    Clean Minimal Navbar Mobile Link Component

    Usage:
    <x-ui.navbar-mobile-link href="/dashboard" :active="true">Dashboard</x-ui.navbar-mobile-link>
--}}

@props([
    'href' => '#',
    'active' => false,
])

@php
$baseClasses = 'flex items-center px-4 py-3 text-sm font-medium';
$activeClasses = $active
    ? 'bg-primary-50 text-primary-700 border-l-4 border-primary-500'
    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-4 border-transparent';
@endphp

<a href="{{ $href }}" {{ $attributes->class([$baseClasses, $activeClasses]) }} @click="mobileMenuOpen = false">
    {{ $slot }}
</a>
