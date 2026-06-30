{{--
    shadcn-style Avatar Component for Laravel Blade

    Usage:
    <x-ui.avatar name="John Doe" />
    <x-ui.avatar src="/path/to/image.jpg" alt="User photo" />
    <x-ui.avatar name="Jane Smith" size="lg" />
--}}

@props([
    'src' => null,
    'alt' => '',
    'name' => '',
    'size' => 'md',
])

@php
$sizes = [
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-lg',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];

// Generate initials from name
$initials = collect(explode(' ', $name))
    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
    ->take(2)
    ->join('');

// Deterministic per-person color from a curated palette (matches each
// person getting a distinct, consistent avatar color across the app).
$palette = [
    '#6D5BD0', '#4C8BF5', '#34B27B', '#F0A93B',
    '#E8567A', '#3FBFC4', '#B968E0', '#F0773F',
];
$avatarColor = $name !== '' ? $palette[crc32($name) % count($palette)] : null;
@endphp

<div {{ $attributes->class(["relative inline-flex items-center justify-center rounded-full text-white font-semibold overflow-hidden", $avatarColor ? '' : 'bg-primary-500', $sizeClass]) }} @if($avatarColor) style="background-color: {{ $avatarColor }}" @endif>
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt ?: $name }}" class="w-full h-full object-cover">
    @else
        <span>{{ $initials }}</span>
    @endif
</div>
