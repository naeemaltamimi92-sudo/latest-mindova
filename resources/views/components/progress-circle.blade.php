@props(['percentage' => 0, 'size' => 120, 'label' => '', 'color' => 'var(--progress-default)', 'showPercentage' => true, 'stroke' => 10, 'textSize' => 'text-2xl'])

@php
    $radius = ($size - $stroke) / 2;
    $circumference = 2 * pi() * $radius;
    $dashoffset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div class="progress-circle-wrapper relative flex items-center justify-center" style="width: {{ $size }}px; height: {{ $size }}px;">
    <!-- Background Circle -->
    <svg class="w-full h-full transform -rotate-90" width="{{ $size }}" height="{{ $size }}">
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            fill="transparent"
            stroke="var(--progress-empty, #E6E6FA)"
            stroke-width="{{ $stroke }}"
        />
        <!-- Progress Circle -->
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            fill="transparent"
            stroke="{{ $color }}"
            stroke-width="{{ $stroke }}"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $dashoffset }}"
            stroke-linecap="round"
            style="transition: stroke-dashoffset 0.5s ease-out;"
        />
    </svg>

    <!-- Content -->
    @if($showPercentage)
    <div class="absolute inset-0 flex flex-col items-center justify-center">
        <div class="{{ $textSize }} font-bold" style="color: {{ $color }}">{{ round($percentage) }}%</div>
        @if($label)
        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">{{ $label }}</div>
        @endif
    </div>
    @endif
</div>
