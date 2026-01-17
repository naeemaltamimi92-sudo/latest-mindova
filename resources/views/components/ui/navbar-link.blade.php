{{--
    shadcn-style Navbar Link Component for Laravel Blade

    Usage:
    <x-ui.navbar-link href="/dashboard" :active="request()->routeIs('dashboard')">Dashboard</x-ui.navbar-link>
--}}

@props([
    'href' => '#',
    'active' => false,
])

@php
$baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-150';
$activeClasses = $active
    ? 'border-primary-500 text-gray-900'
    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700';
@endphp

<a href="{{ $href }}" {{ $attributes->class([$baseClasses, $activeClasses]) }}>
    {{ $slot }}
</a>
