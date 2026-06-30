{{--
    Branded social sign-in button (currently only LinkedIn is wired up server-side).

    Usage:
    <x-ui.social-button provider="linkedin" href="{{ route('auth.linkedin.redirect') }}">
        Continue with LinkedIn
    </x-ui.social-button>
--}}

@props([
    'provider' => 'linkedin',
    'href',
])

<a href="{{ $href }}"
   {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2.5 w-full px-4 py-2.5 rounded-lg text-sm font-semibold border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:border-[#0077B5]/40 hover:bg-[#0077B5]/5 transition-premium-fast'
   ]) }}>
    <x-icon name="{{ $provider }}" class="h-4 w-4" />
    {{ $slot }}
</a>
