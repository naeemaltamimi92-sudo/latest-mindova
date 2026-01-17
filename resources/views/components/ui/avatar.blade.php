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
@endphp

<div {{ $attributes->class(["relative inline-flex items-center justify-center rounded-full bg-primary-500 text-white font-semibold overflow-hidden", $sizeClass]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt ?: $name }}" class="w-full h-full object-cover">
    @else
        <span>{{ $initials }}</span>
    @endif
</div>
