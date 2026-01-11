/**
 * Mindova Guided Tour System
 * Contextual, role-aware onboarding for new users
 */

(function() {
    'use strict';

    class GuidedTour {
        constructor() {
            this.activeTooltips = [];
            this.currentSteps = [];
            this.userId = null;
            this.settings = {
                delay: 500,
                animation: 'fade'
            };
            this.init();
        }

        init() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.setup());
            } else {
                this.setup();
            }
        }

        setup() {
            // Get user ID from meta tag
            const userMeta = document.querySelector('meta[name="user-id"]');
            if (userMeta) {
                this.userId = userMeta.content;
            }

            // Load active steps from data attribute set by backend
            const stepsData = document.getElementById('guided-tour-data');
            if (stepsData) {
                try {
                    this.currentSteps = JSON.parse(stepsData.dataset.steps || '[]');
                    const settings = JSON.parse(stepsData.dataset.settings || '{}');
                    this.settings = { ...this.settings, ...settings };
                } catch (e) {
                    console.error('Failed to parse guided tour data:', e);
                    return;
                }
            }

            // Show steps if any exist
            if (this.currentSteps.length > 0) {
                setTimeout(() => this.showSteps(), this.settings.delay);
            }
        }

        showSteps() {
            this.currentSteps.forEach((step, index) => {
                setTimeout(() => {
                    this.showTooltip(step);
                }, index * 400); // Stagger tooltips by 400ms
            });
        }

        showTooltip(step) {
            const element = document.querySelector(step.element);
            if (!element) {
                console.warn('Element not found for guidance step:', step.element);
                return;
            }

            // Create tooltip
            const tooltip = this.createTooltip(step);

            // Add to DOM
            document.body.appendChild(tooltip);

            // Wait for DOM to render, then position tooltip
            requestAnimationFrame(() => {
                this.positionTooltip(tooltip, element, step.position);

                // Store reference
                this.activeTooltips.push({
                    tooltip,
                    step,
                    element
                });

                // Add highlight to target element
                element.classList.add('guided-tour-highlight');

                // Show with animation
                setTimeout(() => tooltip.classList.add('show'), 10);
            });
        }

        createTooltip(step) {
            const tooltip = document.createElement('div');
            tooltip.className = 'guided-tour-tooltip';
            tooltip.setAttribute('data-step-id', step.step_id);
            tooltip.setAttribute('role', 'tooltip');
            tooltip.setAttribute('aria-live', 'polite');

            tooltip.innerHTML = `
                <div class="guided-tour-content">
                    <div class="guided-tour-header">
                        <div class="guided-tour-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <button class="guided-tour-close"
                                data-step-id="${step.step_id}"
                                aria-label="Close guidance"
                                title="Close">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="guided-tour-text">${step.text}</div>
                    <div class="guided-tour-footer">
                        <button class="guided-tour-dismiss" data-step-id="${step.step_id}">
                            Got it, thanks!
                        </button>
                    </div>
                </div>
                <div class="guided-tour-arrow"></div>
            `;

            // Bind event handlers
            const closeBtn = tooltip.querySelector('.guided-tour-close');
            const dismissBtn = tooltip.querySelector('.guided-tour-dismiss');

            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.dismissTooltip(step.step_id, false); // Don't mark as complete, just hide
                });
            }

            if (dismissBtn) {
                dismissBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.dismissTooltip(step.step_id, true); // Mark as complete
                });
            }

            return tooltip;
        }

        positionTooltip(tooltip, element, position = 'bottom') {
            const rect = element.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;
            const scrollX = window.pageXOffset || document.documentElement.scrollLeft;

            let top, left;

            switch (position) {
                case 'top':
                    top = rect.top + scrollY - tooltipRect.height - 15;
                    left = rect.left + scrollX + (rect.width / 2) - (tooltipRect.width / 2);
                    tooltip.classList.add('position-top');
                    break;

                case 'bottom':
                    top = rect.bottom + scrollY + 15;
                    left = rect.left + scrollX + (rect.width / 2) - (tooltipRect.width / 2);
                    tooltip.classList.add('position-bottom');
                    break;

                case 'left':
                    top = rect.top + scrollY + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.left + scrollX - tooltipRect.width - 15;
                    tooltip.classList.add('position-left');
                    break;

                case 'right':
                    top = rect.top + scrollY + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.right + scrollX + 15;
                    tooltip.classList.add('position-right');
                    break;

                default:
                    top = rect.bottom + scrollY + 15;
                    left = rect.left + scrollX + (rect.width / 2) - (tooltipRect.width / 2);
                    tooltip.classList.add('position-bottom');
            }

            // Ensure tooltip stays within viewport
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            // Adjust horizontal position
            if (left < 10) {
                left = 10;
            } else if (left + tooltipRect.width > viewportWidth - 10) {
                left = viewportWidth - tooltipRect.width - 10;
            }

            // Adjust vertical position
            if (top < 10) {
                top = 10;
            } else if (top + tooltipRect.height > scrollY + viewportHeight - 10) {
                top = scrollY + viewportHeight - tooltipRect.height - 10;
            }

            tooltip.style.top = `${top}px`;
            tooltip.style.left = `${left}px`;
        }

        dismissTooltip(stepId, markComplete = true) {
            // Find tooltip
            const tooltipData = this.activeTooltips.find(t => t.step.step_id === stepId);
            if (!tooltipData) return;

            // Remove highlight from element
            tooltipData.element.classList.remove('guided-tour-highlight');

            // Hide tooltip with animation
            tooltipData.tooltip.classList.remove('show');

            // Remove from DOM after animation
            setTimeout(() => {
                if (tooltipData.tooltip.parentNode) {
                    tooltipData.tooltip.remove();
                }
            }, 300);

            // Remove from active list
            this.activeTooltips = this.activeTooltips.filter(t => t.step.step_id !== stepId);

            // Mark as completed on server if requested
            if (markComplete) {
                this.markStepCompleted(stepId);
            }
        }

        markStepCompleted(stepId) {
            if (!this.userId) {
                console.warn('No user ID available for marking step completed');
                return;
            }

            fetch('/api/guidance/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ step_id: stepId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to mark step as completed');
                }
                return response.json();
            })
            .catch(err => {
                console.error('Error marking step completed:', err);
            });
        }

        dismissAll() {
            this.activeTooltips.forEach(tooltipData => {
                this.dismissTooltip(tooltipData.step.step_id, false);
            });
        }
    }

    // Initialize and expose globally
    window.GuidedTour = new GuidedTour();

    // Expose helper functions for debugging
    window.resetGuidance = function() {
        fetch('/api/guidance/reset', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            }
        })
        .then(() => {
            console.log('Guidance progress reset. Reload page to see guidance again.');
            location.reload();
        })
        .catch(err => console.error('Failed to reset guidance:', err));
    };
})();
