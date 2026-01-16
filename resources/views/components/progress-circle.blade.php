@props(['percentage' => 0, 'size' => 120, 'label' => '', 'color' => 'var(--progress-default)', 'showPercentage' => true])

<div class="progress-circle-container" style="width: {{ $size }}px; height: {{ $size }}px;">
    <div x-data="progressChart({{ $percentage }}, '{{ $label }}', '{{ $color }}')" class="relative">
        <canvas x-ref="canvas" width="{{ $size }}" height="{{ $size }}"></canvas>
        @if($showPercentage)
        <div class="progress-circle-label">
            <div class="text-2xl font-bold" style="color: {{ $color }}">{{ round($percentage) }}%</div>
            @if($label)
            <div class="text-xs text-gray-600 mt-1">{{ $label }}</div>
            @endif
        </div>
        @endif
    </div>
</div>
