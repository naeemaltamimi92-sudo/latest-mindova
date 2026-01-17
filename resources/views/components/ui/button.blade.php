{{--
    shadcn-style Button Component for Laravel Blade

    Usage:
    <x-ui.button>Click me</x-ui.button>
    <x-ui.button variant="secondary">Secondary</x-ui.button>
    <x-ui.button variant="destructive" size="sm">Delete</x-ui.button>
    <x-ui.button as="a" href="/dashboard">Dashboard</x-ui.button>
    <x-ui.button as="submit" size="lg" fullWidth>Sign In</x-ui.button>
    <x-ui.button @click="open = true" x-bind:disabled="!isValid">Submit</x-ui.button>
    <x-ui.button :loading="true">Saving...</x-ui.button>
--}}

@props([
    'variant' => 'default',
    'size' => 'default',
    'as' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'iconOnly' => false,
    'fullWidth' => false,
])

@php
// Base classes - always applied
$base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

// Variant styles
$variants = [
    'default' => 'bg-white text-primary-500 hover:bg-primary-50 focus:ring-primary-500 shadow-lg shadow-primary-500/20 hover:shadow-xl hover:shadow-primary-500/30 border border-primary-200',
    'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 shadow-lg shadow-primary-500/20 hover:shadow-xl hover:shadow-primary-500/30',
    'secondary' => 'bg-white text-gray-700 border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 focus:ring-gray-300',
    'destructive' => 'bg-danger-500 text-white hover:bg-danger-600 focus:ring-danger-500 shadow-lg shadow-danger-500/20 hover:shadow-xl hover:shadow-danger-500/30',
    'outline' => 'border-2 border-secondary-500 text-secondary-500 bg-transparent hover:bg-secondary-50 focus:ring-secondary-500',
    'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-300',
    'link' => 'text-secondary-500 underline-offset-4 hover:underline focus:ring-secondary-500 p-0 h-auto shadow-none',
    'success' => 'bg-success-500 text-white hover:bg-success-600 focus:ring-success-500 shadow-lg shadow-success-500/20 hover:shadow-xl hover:shadow-success-500/30',
];

// Size styles
$sizes = [
    'sm' => 'px-4 py-2 text-sm',
    'default' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
    'xl' => 'px-10 py-5 text-lg',
    'icon' => 'p-3',
];

// Compile classes
$classes = implode(' ', array_filter([
    $base,
    $variants[$variant] ?? $variants['default'],
    $sizes[$size] ?? $sizes['default'],
    $fullWidth ? 'w-full' : '',
    $iconOnly ? 'aspect-square' : '',
]));

// Determine element type
$tag = $href ? 'a' : ($as === 'submit' ? 'button' : $as);
$type = $tag === 'button' ? ($as === 'submit' ? 'submit' : 'button') : null;
@endphp

<{{ $tag }}
    {{ $attributes->class([$classes])->merge([
        'href' => $href,
        'type' => $type,
        'disabled' => $tag === 'button' ? ($disabled || $loading) : null,
        'aria-disabled' => ($disabled || $loading) ? 'true' : null,
    ]) }}
>
    @if($loading)
        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    {{ $slot }}
</{{ $tag }}>
