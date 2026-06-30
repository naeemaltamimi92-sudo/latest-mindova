{{--
    Clean Minimal Navbar Component for Laravel Blade
    Matching Landing Page Style with Centered Navigation
--}}

@props([
    'fixed' => true,
    'height' => '16',
])

@php
$baseClasses = 'z-50 border-b';
$positionClass = $fixed ? 'fixed top-0 left-0 right-0' : 'relative';
$heightClass = "h-{$height}";
@endphp

<nav {{ $attributes->class([$baseClasses, $positionClass]) }}
     x-data="{ mobileMenuOpen: false }"
     style="background:#ffffff;border-color:#E4E6EB;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $heightClass }}" style="display:grid;grid-template-columns:auto 1fr auto;align-items:center;">
            {{ $slot }}
        </div>
    </div>

    {{-- Mobile Menu Slot --}}
    @if(isset($mobile))
    {{ $mobile }}
    @endif
</nav>
