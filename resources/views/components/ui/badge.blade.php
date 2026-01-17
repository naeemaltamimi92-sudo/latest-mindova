{{--
    shadcn-style Badge Component for Laravel Blade

    Usage:
    <x-ui.badge count="5" />
    <x-ui.badge count="150" max="99" />
    <x-ui.badge count="0" showZero />
    <x-ui.badge variant="success">New</x-ui.badge>
--}}

@props([
    'count' => null,
    'variant' => 'danger',
    'showZero' => false,
    'max' => 99,
])

@php
$variants = [
    'primary' => 'bg-primary-500 text-white',
    'secondary' => 'bg-white text-primary-500 border border-primary-200',
    'danger' => 'bg-red-500 text-white',
    'success' => 'bg-green-500 text-white',
    'warning' => 'bg-yellow-500 text-white',
    'info' => 'bg-blue-500 text-white',
];
$variantClass = $variants[$variant] ?? $variants['danger'];

// For count-based badges
$displayCount = null;
$shouldShow = true;

if (!is_null($count)) {
    $numCount = (int) $count;
    $displayCount = $numCount > $max ? "{$max}+" : $numCount;
    $shouldShow = $showZero || $numCount > 0;
}
@endphp

@if($shouldShow)
<span {{ $attributes->class(["inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full min-w-[1.25rem]", $variantClass]) }}>
    @if(!is_null($displayCount))
        {{ $displayCount }}
    @else
        {{ $slot }}
    @endif
</span>
@endif
