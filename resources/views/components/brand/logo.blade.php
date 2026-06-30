{{--
    Mindova Logo Component

    The SVG mark uses currentColor, so color is driven by the CSS `color` property
    on the wrapper — this makes dark/light mode work automatically.

    Props:
      variant   : 'color' (default) | 'white' | 'mono-dark'
      size      : 'xs' | 'sm' | 'md' (default) | 'lg' | 'xl'
      icon-only : bool — mark only, no wordmark
      href      : URL to link to (wraps in <a>, otherwise a <div>)
--}}
@props([
    'variant'  => 'color',
    'size'     => 'md',
    'iconOnly' => false,
    'href'     => null,
])

@php
// Tailwind classes that set `color` — the SVG uses currentColor
$colorClass = match($variant) {
    'white'     => 'text-white',
    'mono-dark' => 'text-gray-900',
    // Default: brand violet in light mode, white in dark mode
    default     => 'text-primary-600 dark:text-white',
};

[$iconClass, $textClass] = match($size) {
    'xs'    => ['h-5 w-5',  'text-sm   font-bold  tracking-tight'],
    'sm'    => ['h-6 w-6',  'text-base font-bold  tracking-tight'],
    'lg'    => ['h-10 w-10','text-xl   font-bold  tracking-tight'],
    'xl'    => ['h-14 w-14','text-3xl  font-black tracking-tight'],
    default => ['h-8 w-8',  'text-lg   font-bold  tracking-tight'],
};
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => "inline-flex items-center gap-2.5 $colorClass"]) }}>
@else
<div {{ $attributes->merge(['class' => "inline-flex items-center gap-2.5 $colorClass"]) }}>
@endif

    {{-- Mark — paths use currentColor so they inherit the wrapper's text-* color --}}
    <svg class="{{ $iconClass }}" viewBox="0 0 50 50" fill="none"
         xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <path d="M 8,44 L 8,8 L 25,24 L 42,8 L 42,44"
              stroke="currentColor"
              stroke-width="5.5"
              stroke-linecap="round"
              stroke-linejoin="round"/>
        {{-- Outer peak nodes: full color --}}
        <circle cx="8"  cy="8"  r="6.5" fill="currentColor"/>
        <circle cx="42" cy="8"  r="6.5" fill="currentColor"/>
        {{-- Center connector node: same hue, reduced opacity for visual differentiation --}}
        <circle cx="25" cy="24" r="5"   fill="currentColor" opacity="0.55"/>
    </svg>

    {{-- Wordmark --}}
    @unless($iconOnly)
        <span class="{{ $textClass }}">Mindova</span>
    @endunless

@if($href)
</a>
@else
</div>
@endif
