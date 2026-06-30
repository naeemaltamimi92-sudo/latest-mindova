{{--
    "Or continue with" style divider for auth forms.

    Usage:
    <x-ui.divider label="Or continue with" />
--}}

@props([
    'label' => null,
])

<div {{ $attributes->merge(['class' => 'relative my-6']) }}>
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
    </div>
    @if($label)
        <div class="relative flex justify-center">
            <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs">{{ $label }}</span>
        </div>
    @endif
</div>
