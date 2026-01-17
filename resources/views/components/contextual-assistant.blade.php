@if($shouldRender())
<div id="contextual-assistant" class="contextual-assistant">
    {{-- Assistant Character --}}
    <div class="assistant-character">
        <div class="assistant-avatar">
            <div class="assistant-face">
                {{-- Eyes that can subtly follow mouse --}}
                <div class="assistant-eyes">
                    <div class="eye left-eye">
                        <div class="pupil"></div>
                    </div>
                    <div class="eye right-eye">
                        <div class="pupil"></div>
                    </div>
                </div>
                {{-- Subtle smile --}}
                <div class="assistant-mouth"></div>
            </div>
        </div>
    </div>

    {{-- Guidance Bubble --}}
    <div class="assistant-bubble">
        <div class="bubble-content">
            <span class="bubble-icon">{{ $guidance['icon'] }}</span>
            <p class="bubble-text">{{ $guidance['text'] }}</p>
        </div>
        {{-- Optional close button (subtle) --}}
        <x-ui.button variant="ghost" size="sm" class="assistant-dismiss" onclick="dismissAssistant()" aria-label="Dismiss assistant" title="Dismiss (for this session)">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L11 11M1 11L11 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </x-ui.button>
    </div>
</div>

{{-- Inline JavaScript for dismiss functionality --}}
<script>
function dismissAssistant() {
    const assistant = document.getElementById('contextual-assistant');
    if (assistant) {
        // Fade out animation
        assistant.style.opacity = '0';
        assistant.style.transform = 'translateY(20px)';

        setTimeout(() => {
            assistant.style.display = 'none';

            // Send AJAX request to remember dismissal
            fetch('/api/contextual-assistant/dismiss', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
        }, 300);
    }
}

// Optional: Subtle eye-tracking effect
(function() {
    const eyes = document.querySelectorAll('.assistant-eyes .pupil');
    if (eyes.length === 0) return;

    let isTracking = true; // Can be configured
    if (!isTracking) return;

    // Throttle mouse movement tracking for performance
    let ticking = false;

    document.addEventListener('mousemove', function(e) {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                movePupils(e.clientX, e.clientY);
                ticking = false;
            });
            ticking = true;
        }
    });

    function movePupils(mouseX, mouseY) {
        eyes.forEach(function(pupil) {
            const eye = pupil.parentElement;
            const eyeRect = eye.getBoundingClientRect();
            const eyeCenterX = eyeRect.left + eyeRect.width / 2;
            const eyeCenterY = eyeRect.top + eyeRect.height / 2;

            // Calculate angle
            const angle = Math.atan2(mouseY - eyeCenterY, mouseX - eyeCenterX);

            // Maximum pupil movement distance (subtle)
            const maxDistance = 3; // pixels

            // Calculate pupil position
            const pupilX = Math.cos(angle) * maxDistance;
            const pupilY = Math.sin(angle) * maxDistance;

            pupil.style.transform = `translate(${pupilX}px, ${pupilY}px)`;
        });
    }
})();
</script>
@endif