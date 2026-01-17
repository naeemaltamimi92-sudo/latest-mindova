/**
 * Contextual Guide JavaScript
 * Handles the contextual help system across the Mindova platform
 */

(function() {
    'use strict';

    // ContextualGuide Class
    class ContextualGuide {
        constructor() {
            this.trigger = null;
            this.panel = null;
            this.overlay = null;
            this.closeButton = null;
            this.gotItButton = null;
            this.dismissCheckbox = null;
            this.pageId = null;
            this.isOpen = false;

            this.init();
        }

        /**
         * Initialize the contextual guide
         */
        init() {
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.setup());
            } else {
                this.setup();
            }
        }

        /**
         * Setup the guide elements and event listeners
         */
        setup() {
            // Get elements
            this.trigger = document.getElementById('contextual-guide-trigger');
            this.panel = document.getElementById('contextual-guide-panel');
            this.overlay = document.getElementById('contextual-guide-overlay');
            this.closeButton = document.getElementById('contextual-guide-close');
            this.gotItButton = document.getElementById('contextual-guide-got-it');
            this.dismissCheckbox = document.getElementById('contextual-guide-dismiss-checkbox');

            // Get page ID
            const wrapper = document.querySelector('.contextual-guide-wrapper');
            if (wrapper) {
                this.pageId = wrapper.dataset.pageId;
            }

            // Check if all elements exist
            if (!this.trigger || !this.panel || !this.overlay) {
                console.warn('Contextual guide elements not found');
                return;
            }

            // Bind event listeners
            this.bindEvents();

            // Check if panel should be visible on load
            this.checkInitialVisibility();
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Open guide when trigger is clicked
            if (this.trigger) {
                this.trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.open();
                });
            }

            // Close guide when close button is clicked
            if (this.closeButton) {
                this.closeButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.close();
                });
            }

            // Close and optionally dismiss when "Got it" is clicked
            if (this.gotItButton) {
                this.gotItButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleGotIt();
                });
            }

            // Close when overlay is clicked
            if (this.overlay) {
                this.overlay.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.close();
                });
            }

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });
        }

        /**
         * Check if panel should be visible on page load
         */
        checkInitialVisibility() {
            if (this.panel && !this.panel.classList.contains('hidden')) {
                this.isOpen = true;
            }
        }

        /**
         * Open the guide panel
         */
        open() {
            if (this.isOpen) return;

            // Show panel and overlay immediately
            this.panel.classList.remove('hidden');
            this.overlay.classList.remove('hidden');
            this.panel.classList.add('contextual-guide-show');
            this.overlay.classList.add('contextual-guide-overlay-show');

            this.isOpen = true;

            // Focus on panel for accessibility
            this.panel.focus();
        }

        /**
         * Close the guide panel
         */
        close() {
            if (!this.isOpen) return;

            // Hide panel and overlay immediately
            this.panel.classList.remove('contextual-guide-show');
            this.overlay.classList.remove('contextual-guide-overlay-show');
            this.panel.classList.add('hidden');
            this.overlay.classList.add('hidden');

            this.isOpen = false;
        }

        /**
         * Handle "Got it" button click
         */
        async handleGotIt() {
            const shouldDismiss = this.dismissCheckbox && this.dismissCheckbox.checked;

            if (shouldDismiss) {
                await this.dismissGuide();
            }

            this.close();
        }

        /**
         * Dismiss the guide (save preference)
         */
        async dismissGuide() {
            if (!this.pageId) {
                console.warn('No page ID found for dismissal');
                return;
            }

            try {
                const response = await fetch('/api/contextual-guide/dismiss', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        page_identifier: this.pageId
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to save dismissal preference');
                }

                console.log('Guide dismissed successfully for page:', this.pageId);
            } catch (error) {
                console.error('Error dismissing guide:', error);
            }
        }

        /**
         * Get CSRF token from meta tag
         */
        getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        }

        /**
         * Reset guide (for testing/debugging)
         */
        async resetGuide() {
            if (!this.pageId) return;

            try {
                const response = await fetch('/api/contextual-guide/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        page_identifier: this.pageId
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to reset guide');
                }

                console.log('Guide reset successfully for page:', this.pageId);
                location.reload(); // Reload to show guide again
            } catch (error) {
                console.error('Error resetting guide:', error);
            }
        }
    }

    // Initialize ContextualGuide and expose to window
    window.ContextualGuide = new ContextualGuide();

    // Expose reset method for debugging
    window.resetContextualGuide = function() {
        if (window.ContextualGuide) {
            window.ContextualGuide.resetGuide();
        }
    };
})();
