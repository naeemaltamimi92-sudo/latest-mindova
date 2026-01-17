{{--
    shadcn-style Navbar Mobile Menu Component for Laravel Blade

    Usage:
    <x-ui.navbar-mobile>
        <x-ui.navbar-mobile-link href="/dashboard" :active="true">Dashboard</x-ui.navbar-mobile-link>
    </x-ui.navbar-mobile>
--}}

@props([
    'position' => 'right',
])

@php
$positionClasses = [
    'left' => 'left-0',
    'right' => 'right-0',
];
$positionClass = $positionClasses[$position] ?? $positionClasses['right'];

$slideDirection = $position === 'left' ? '-translate-x-full' : 'translate-x-full';
@endphp

{{-- Backdrop --}}
<div
    x-show="mobileMenuOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileMenuOpen = false"
    class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm sm:hidden"
    style="display: none;"
></div>

{{-- Slide-out Panel --}}
<div
    x-show="mobileMenuOpen"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="{{ $slideDirection }}"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="{{ $slideDirection }}"
    class="fixed top-0 {{ $positionClass }} z-50 h-full w-72 bg-white shadow-xl sm:hidden"
    style="display: none;"
>
    {{-- Close Button --}}
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
        <span class="text-lg font-semibold text-gray-900">{{ __('Menu') }}</span>
        <button @click="mobileMenuOpen = false" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Menu Content --}}
    <div class="overflow-y-auto h-[calc(100%-65px)] py-4">
        {{ $slot }}
    </div>
</div>
