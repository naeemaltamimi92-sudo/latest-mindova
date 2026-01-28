{{--
    Clean Minimal Navbar Component for Laravel Blade
    Matching Landing Page Style with Centered Navigation
--}}

@props([
    'fixed' => true,
    'height' => '16',
])

@php
$baseClasses = 'z-50 bg-white/95 border-b border-gray-100';
$positionClass = $fixed ? 'fixed top-0 left-0 right-0' : 'relative';
$heightClass = "h-{$height}";
@endphp

<nav {{ $attributes->class([$baseClasses, $positionClass]) }} x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between {{ $heightClass }}">
            {{ $slot }}
        </div>
    </div>

    {{-- Mobile Menu Slot --}}
    @if(isset($mobile))
    {{ $mobile }}
    @endif
</nav>
