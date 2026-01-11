@if($hasGuide)
<!-- In-App Guide Help Button -->
<div class="in-app-guide-container">
    <!-- Help Button -->
    <button
        type="button"
        class="in-app-guide-button"
        id="guideButton"
        aria-label="{{ __('Help & Guide') }}"
        title="{{ __('Help & Guide') }}"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="guide-icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
        </svg>
    </button>

    <!-- Guide Panel -->
    <div class="in-app-guide-panel" id="guidePanel" style="display: none;">
        <div class="guide-header">
            <h3 class="guide-title">{{ $guide['page_title'] }}</h3>
            <button type="button" class="guide-close" id="guideClose" aria-label="{{ __('Close') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="guide-content">
            <!-- What is this section -->
            <div class="guide-section">
                <h4 class="guide-section-title">{{ __('What is this page?') }}</h4>
                <p class="guide-text">{{ $guide['what_is_this'] }}</p>
            </div>

            <!-- What to do section -->
            <div class="guide-section">
                <h4 class="guide-section-title">{{ __('What should I do?') }}</h4>
                <ul class="guide-list">
                    @foreach($guide['what_to_do'] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Next step section -->
            @if(!empty($guide['next_step']))
            <div class="guide-section">
                <h4 class="guide-section-title">{{ __('What happens next?') }}</h4>
                <p class="guide-text">{{ $guide['next_step'] }}</p>
            </div>
            @endif

            <!-- Video tutorial -->
            @if(!empty($guide['video_url']))
            <div class="guide-section">
                <h4 class="guide-section-title">{{ __('Video Tutorial') }}</h4>
                <div class="guide-video">
                    <a href="{{ $guide['video_url'] }}" target="_blank" class="guide-video-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                        </svg>
                        {{ __('Watch Tutorial') }}
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="guide-footer">
            <button type="button" class="guide-dismiss-btn" id="guideDismiss">
                {{ __('Got it, don\'t show again') }}
            </button>
        </div>
    </div>
</div>

<!-- JavaScript for Guide Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guideButton = document.getElementById('guideButton');
    const guidePanel = document.getElementById('guidePanel');
    const guideClose = document.getElementById('guideClose');
    const guideDismiss = document.getElementById('guideDismiss');
    const pageIdentifier = @json($guide['page_identifier']);

    // Toggle guide panel
    guideButton.addEventListener('click', function() {
        const isVisible = guidePanel.style.display !== 'none';
        guidePanel.style.display = isVisible ? 'none' : 'block';

        // Add highlight effects if UI highlights are specified
        @if(!empty($guide['ui_highlights']))
        if (!isVisible) {
            highlightElements(@json($guide['ui_highlights']));
        } else {
            removeHighlights();
        }
        @endif
    });

    // Close guide panel
    guideClose.addEventListener('click', function() {
        guidePanel.style.display = 'none';
        removeHighlights();
    });

    // Dismiss guide permanently
    guideDismiss.addEventListener('click', function() {
        fetch('/api/in-app-guide/dismiss', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                page_identifier: pageIdentifier
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                guidePanel.style.display = 'none';
                guideButton.style.display = 'none';
                removeHighlights();
            }
        })
        .catch(error => console.error('Error dismissing guide:', error));
    });

    // Close when clicking outside
    document.addEventListener('click', function(event) {
        if (!guideButton.contains(event.target) &&
            !guidePanel.contains(event.target) &&
            guidePanel.style.display !== 'none') {
            guidePanel.style.display = 'none';
            removeHighlights();
        }
    });

    // Highlight UI elements
    function highlightElements(selectors) {
        removeHighlights();

        selectors.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.classList.add('guide-highlighted');
            }
        });
    }

    // Remove highlights
    function removeHighlights() {
        document.querySelectorAll('.guide-highlighted').forEach(el => {
            el.classList.remove('guide-highlighted');
        });
    }
});
</script>
@endif
