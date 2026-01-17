{{--
    shadcn-style Navbar Component for Laravel Blade

    Usage:
    <x-ui.navbar>
        <x-ui.navbar-brand />
        <x-ui.navbar-nav>...</x-ui.navbar-nav>
        <div>Right content</div>
    </x-ui.navbar>
--}}

@props([
    'sticky' => true,
    'height' => '20',
])

@php
$baseClasses = 'z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200/60 shadow-md';
$positionClass = $sticky ? 'sticky top-0' : 'relative';
$heightClass = "h-{$height}";
@endphp

<nav {{ $attributes->class([$baseClasses, $positionClass]) }} x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between {{ $heightClass }}">
            {{ $slot }}
        </div>
    </div>

    {{-- Mobile Menu Slot --}}
    @if(isset($mobile))
    {{ $mobile }}
    @endif
</nav>
