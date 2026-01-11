@props(['pageId' => null])

@php
    // Get current route name if pageId not provided
    $pageIdentifier = $pageId ?? Route::currentRouteName() ?? 'unknown';

    // Get guide content from config
    $guideConfig = config('contextual_guide.guides.' . $pageIdentifier);
    $settings = config('contextual_guide.settings');

    // Check if user has dismissed this guide
    $isDismissed = false;
    $showOnFirstVisit = false;

    if (auth()->check()) {
        $isDismissed = \App\Models\UserGuidePreference::isDismissed(auth()->id(), $pageIdentifier);
        $showOnFirstVisit = !$isDismissed && $settings['show_on_first_visit'];
    }

    // Don't render if no guide content exists
    if (!$guideConfig || empty($guideConfig['text'])) {
        return;
    }
@endphp

<!-- Contextual Guide Assistant (MVP - Minimal & Lightweight) -->
<div class="contextual-guide-assistant" data-page-id="{{ $pageIdentifier }}">

    <!-- Help Icon Button (Always Visible) -->
    <button
        type="button"
        class="guide-help-icon"
        id="guideHelpIcon"
        aria-label="Page help"
        title="Click for page guidance"
    >
        {{ $settings['icon'] ?? '❓' }}
    </button>

    <!-- Small Tooltip/Drawer (Hidden by default) -->
    <div
        class="guide-tooltip {{ $showOnFirstVisit ? 'guide-visible' : '' }}"
        id="guideTooltip"
        role="tooltip"
    >
        <div class="guide-tooltip-content">
            <p class="guide-text">{{ $guideConfig['text'] }}</p>

            @if($settings['dismissible'])
            <button
                type="button"
                class="guide-dismiss-link"
                id="guideDismiss"
            >
                Don't show again
            </button>
            @endif
        </div>

        <!-- Small close icon -->
        <button
            type="button"
            class="guide-close-icon"
            id="guideClose"
            aria-label="Close"
        >
            ×
        </button>
    </div>
</div>

@push('scripts')
<script>
// Minimal contextual guide script
(function() {
    'use strict';

    const helpIcon = document.getElementById('guideHelpIcon');
    const tooltip = document.getElementById('guideTooltip');
    const closeBtn = document.getElementById('guideClose');
    const dismissBtn = document.getElementById('guideDismiss');
    const pageId = document.querySelector('.contextual-guide-assistant')?.dataset.pageId;

    if (!helpIcon || !tooltip) return;

    // Toggle tooltip on icon click
    helpIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        tooltip.classList.toggle('guide-visible');
    });

    // Close tooltip
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            tooltip.classList.remove('guide-visible');
        });
    }

    // Dismiss permanently
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function() {
            tooltip.classList.remove('guide-visible');

            // Save dismissal preference
            if (pageId) {
                fetch('/api/contextual-guide/dismiss', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ page_identifier: pageId })
                }).catch(err => console.error('Failed to save preference:', err));
            }
        });
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!helpIcon.contains(e.target) && !tooltip.contains(e.target)) {
            tooltip.classList.remove('guide-visible');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && tooltip.classList.contains('guide-visible')) {
            tooltip.classList.remove('guide-visible');
        }
    });
})();
</script>
@endpush
