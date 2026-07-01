{{--
    Clean Minimal Navbar Component for Laravel Blade
    Matching Landing Page Style with Centered Navigation
--}}

@props([
    'fixed' => true,
    'height' => '16',
])

@php
$baseClasses = 'z-50 border-b bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 elevation-xs';
$positionClass = $fixed ? 'fixed top-0 left-0 right-0' : 'relative';
$heightClass = "h-{$height}";
@endphp

<nav {{ $attributes->class([$baseClasses, $positionClass]) }}
     x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $heightClass }} grid grid-cols-[auto_1fr_auto] items-center">
            {{ $slot }}
        </div>
    </div>

    {{-- Mobile Menu Slot --}}
    @if(isset($mobile))
    {{ $mobile }}
    @endif
</nav>
