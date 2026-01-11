<!-- Premium Language Switcher Component - 2027 Design -->
@if(\App\Models\SiteSetting::isLanguageSwitcherEnabled())
<div class="language-switcher-2027" x-data="languageSwitcher2027()">
    <!-- Toggle Button - Glassmorphism Style -->
    <button
        type="button"
        @click="toggle()"
        class="lang-toggle-2027"
        :class="{ 'active': isOpen }"
        aria-label="{{ __('Change Language') }}"
        aria-expanded="false"
        :aria-expanded="isOpen.toString()"
        aria-haspopup="listbox"
    >
        <!-- Current Language Flag with 3D Effect -->
        <div class="lang-flag-2027">
            <template x-if="currentLocale === 'en'">
                <svg viewBox="0 0 36 36">
                    <path fill="#00247D" d="M0 9.059V13h5.628zM4.664 31H13v-5.837zM23 25.164V31h8.335zM0 23v3.941L5.63 23zM31.337 5H23v5.837zM36 26.942V23h-5.631zM36 13V9.059L30.371 13zM13 5H4.664L13 10.837z"/>
                    <path fill="#CF1B2B" d="M25.14 23l9.712 6.801c.471-.479.808-1.082.99-1.749L28.627 23H25.14zM13 23h-2.141l-9.711 6.8c.521.53 1.189.909 1.938 1.085L13 23.943V23zm10-10h2.141l9.711-6.8c-.521-.53-1.188-.909-1.937-1.085L23 12.057V13zm-10 0H10.86L1.148 6.2C.677 6.68.34 7.282.157 7.949L7.372 13h5.628z"/>
                    <path fill="#EEE" d="M36 21H21v10h2v-5.836L31.335 31H32c1.117 0 2.126-.461 2.852-1.199L25.14 23h3.487l7.215 5.052c.093-.337.158-.686.158-1.052v-.058L30.369 23H36v-2zM0 21v2h5.63L0 26.941V27c0 1.091.439 2.078 1.148 2.8l9.711-6.8H13v.943l-9.914 6.941c.294.07.598.116.914.116h.664L13 25.163V31h2V21H0zM36 9c0-1.091-.439-2.078-1.148-2.8L25.141 13H23v-.943l9.915-6.942C32.62 5.046 32.316 5 32 5h-.663L23 10.837V5h-2v10h15v-2h-5.629L36 9.059V9zM13 5v5.837L4.664 5H4c-1.118 0-2.126.461-2.852 1.2l9.711 6.8H7.372L.157 7.949C.065 8.286 0 8.634 0 9v.058L5.63 13H0v2h15V5h-2z"/>
                    <path fill="#CF1B2B" d="M21 15V5h-6v10H0v6h15v10h6V21h15v-6z"/>
                </svg>
            </template>
            <template x-if="currentLocale === 'ar'">
                <svg viewBox="0 0 36 36">
                    <path fill="#006C35" d="M36 27c0 2.209-1.791 4-4 4H4c-2.209 0-4-1.791-4-4V9c0-2.209 1.791-4 4-4h28c2.209 0 4 1.791 4 4v18z"/>
                    <path fill="#FFF" d="M0 13h36v10H0z"/>
                    <path fill="#000" d="M0 9c0-2.209 1.791-4 4-4h28c2.209 0 4 1.791 4 4v4H0V9z"/>
                    <path fill="#CE1126" d="M4 5C1.791 5 0 6.791 0 9v4h9l6-8H4z"/>
                </svg>
            </template>
        </div>

        <!-- Current Language Name with Gradient -->
        <span class="lang-name-2027" x-text="getLanguageName(currentLocale)"></span>

        <!-- Animated Chevron Icon -->
        <svg
            class="lang-chevron-2027"
            :class="{ 'active': isOpen }"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2.5"
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>

        <!-- Loading Overlay -->
        <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-[1rem]">
            <svg class="lang-spinner-2027" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </button>

    <!-- Dropdown Panel - Modern Glass Design -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 transform -translate-y-3 scale-95"
        @click.away="isOpen = false"
        @keydown.escape.window="isOpen = false"
        class="lang-dropdown-2027"
        :class="{ 'open': isOpen }"
        role="listbox"
        aria-label="{{ __('Select language') }}"
        style="display: none;"
    >
        <!-- Animated Arrow -->
        <div class="lang-dropdown-arrow-2027"></div>

        <!-- Language Options -->
        <div class="lang-options-2027">
            @foreach($availableLocales ?? ['en' => 'English', 'ar' => 'العربية'] as $locale => $name)
            <button
                type="button"
                @click="switchLanguage('{{ $locale }}')"
                class="lang-option-2027"
                :class="{ 'active': currentLocale === '{{ $locale }}', 'loading': isLoading && selectedLocale === '{{ $locale }}' }"
                :disabled="currentLocale === '{{ $locale }}' || isLoading"
                role="option"
                :aria-selected="currentLocale === '{{ $locale }}'"
            >
                <!-- Flag Icon -->
                <div class="lang-option-flag-2027">
                    @if($locale === 'en')
                    <svg viewBox="0 0 36 36">
                        <path fill="#00247D" d="M0 9.059V13h5.628zM4.664 31H13v-5.837zM23 25.164V31h8.335zM0 23v3.941L5.63 23zM31.337 5H23v5.837zM36 26.942V23h-5.631zM36 13V9.059L30.371 13zM13 5H4.664L13 10.837z"/>
                        <path fill="#CF1B2B" d="M25.14 23l9.712 6.801c.471-.479.808-1.082.99-1.749L28.627 23H25.14zM13 23h-2.141l-9.711 6.8c.521.53 1.189.909 1.938 1.085L13 23.943V23zm10-10h2.141l9.711-6.8c-.521-.53-1.188-.909-1.937-1.085L23 12.057V13zm-10 0H10.86L1.148 6.2C.677 6.68.34 7.282.157 7.949L7.372 13h5.628z"/>
                        <path fill="#EEE" d="M36 21H21v10h2v-5.836L31.335 31H32c1.117 0 2.126-.461 2.852-1.199L25.14 23h3.487l7.215 5.052c.093-.337.158-.686.158-1.052v-.058L30.369 23H36v-2zM0 21v2h5.63L0 26.941V27c0 1.091.439 2.078 1.148 2.8l9.711-6.8H13v.943l-9.914 6.941c.294.07.598.116.914.116h.664L13 25.163V31h2V21H0zM36 9c0-1.091-.439-2.078-1.148-2.8L25.141 13H23v-.943l9.915-6.942C32.62 5.046 32.316 5 32 5h-.663L23 10.837V5h-2v10h15v-2h-5.629L36 9.059V9zM13 5v5.837L4.664 5H4c-1.118 0-2.126.461-2.852 1.2l9.711 6.8H7.372L.157 7.949C.065 8.286 0 8.634 0 9v.058L5.63 13H0v2h15V5h-2z"/>
                        <path fill="#CF1B2B" d="M21 15V5h-6v10H0v6h15v10h6V21h15v-6z"/>
                    </svg>
                    @else
                    <svg viewBox="0 0 36 36">
                        <path fill="#006C35" d="M36 27c0 2.209-1.791 4-4 4H4c-2.209 0-4-1.791-4-4V9c0-2.209 1.791-4 4-4h28c2.209 0 4 1.791 4 4v18z"/>
                        <path fill="#FFF" d="M0 13h36v10H0z"/>
                        <path fill="#000" d="M0 9c0-2.209 1.791-4 4-4h28c2.209 0 4 1.791 4 4v4H0V9z"/>
                        <path fill="#CE1126" d="M4 5C1.791 5 0 6.791 0 9v4h9l6-8H4z"/>
                    </svg>
                    @endif
                </div>

                <!-- Language Name -->
                <span class="lang-option-name-2027">{{ $name }}</span>

                <!-- Check Icon (for active) -->
                <template x-if="currentLocale === '{{ $locale }}'">
                    <svg
                        class="lang-check-2027"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="3"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </template>

                <!-- Loading Spinner -->
                <template x-if="isLoading && selectedLocale === '{{ $locale }}'">
                    <svg class="lang-spinner-2027" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
            </button>
            @endforeach
        </div>

        <!-- Footer with Globe Animation -->
        <div class="lang-dropdown-footer-2027">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>
            <span>{{ __('Choose your preferred language') }}</span>
        </div>
    </div>
</div>

<!-- Alpine.js Language Switcher Component - 2027 Enhanced -->
<script>
function languageSwitcher2027() {
    return {
        isOpen: false,
        isLoading: false,
        selectedLocale: null,
        currentLocale: '{{ $currentLocale ?? app()->getLocale() }}',
        languages: {
            'en': 'English',
            'ar': 'العربية'
        },

        toggle() {
            this.isOpen = !this.isOpen;
        },

        getLanguageName(locale) {
            return this.languages[locale] || locale;
        },

        async switchLanguage(locale) {
            if (locale === this.currentLocale || this.isLoading) return;

            this.isLoading = true;
            this.selectedLocale = locale;

            // Add page transition class
            document.body.classList.add('lang-switching');

            try {
                const response = await fetch('/language/switch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ locale: locale })
                });

                const data = await response.json();

                if (data.success) {
                    // Update current locale
                    this.currentLocale = locale;

                    // Create smooth transition overlay
                    this.createTransitionOverlay(locale);

                    // Reload page after transition
                    setTimeout(() => {
                        window.location.reload();
                    }, 400);
                } else {
                    console.error('{{ __("Language switch failed") }}:', data.message);
                    this.resetState();
                }
            } catch (error) {
                console.error('{{ __("Error switching language") }}:', error);
                this.resetState();
            }
        },

        createTransitionOverlay(locale) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-[9999] flex items-center justify-center';
            overlay.style.cssText = `
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(168, 85, 247, 0.95) 100%);
                backdrop-filter: blur(20px);
                animation: overlayFadeIn 0.3s ease-out;
            `;
            overlay.innerHTML = `
                <div style="text-align: center; color: white;">
                    <div style="width: 48px; height: 48px; margin: 0 auto 16px; border: 3px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                    <p style="font-size: 1.125rem; font-weight: 600; font-family: ${locale === 'ar' ? "'Tajawal', sans-serif" : "'Inter', sans-serif"};">
                        ${locale === 'ar' ? 'جاري تغيير اللغة...' : 'Switching language...'}
                    </p>
                </div>
            `;

            // Add keyframes
            const style = document.createElement('style');
            style.textContent = `
                @keyframes overlayFadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
            document.body.appendChild(overlay);
        },

        resetState() {
            this.isLoading = false;
            this.selectedLocale = null;
            document.body.classList.remove('lang-switching');
        }
    };
}
</script>

<!-- Additional Inline Styles for 2027 Design -->
<style>
/* Language Switcher 2027 - Core Styles */
.language-switcher-2027 {
    position: relative;
    z-index: 50;
}

.lang-toggle-2027 {
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.625rem 1rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 255, 0.9) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(99, 102, 241, 0.15);
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow:
        0 4px 16px rgba(99, 102, 241, 0.08),
        0 1px 3px rgba(0, 0, 0, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    position: relative;
    overflow: hidden;
}

.lang-toggle-2027::before {
    content: '';
    position: absolute;
    inset: -2px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7, #6366f1);
    background-size: 300% 300%;
    border-radius: inherit;
    z-index: -2;
    opacity: 0;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: gradientBorder 4s ease infinite;
}

.lang-toggle-2027:hover::before {
    opacity: 1;
}

.lang-toggle-2027::after {
    content: '';
    position: absolute;
    inset: 1px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 255, 0.95) 100%);
    border-radius: calc(1rem - 1px);
    z-index: -1;
}

@keyframes gradientBorder {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.lang-toggle-2027:hover {
    transform: translateY(-2px);
    box-shadow:
        0 8px 32px rgba(99, 102, 241, 0.18),
        0 2px 8px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.lang-toggle-2027:active {
    transform: translateY(0);
    transition-duration: 0.1s;
}

.lang-toggle-2027.active {
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    border-color: #a5b4fc;
}

.lang-flag-2027 {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 0.375rem;
    overflow: hidden;
    box-shadow:
        0 2px 8px rgba(0, 0, 0, 0.12),
        0 1px 3px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    flex-shrink: 0;
}

.lang-toggle-2027:hover .lang-flag-2027 {
    transform: scale(1.1) rotate(-3deg);
}

.lang-flag-2027 svg {
    width: 100%;
    height: 100%;
}

.lang-name-2027 {
    min-width: 3.5rem;
    font-weight: 600;
    background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.lang-toggle-2027:hover .lang-name-2027 {
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    -webkit-background-clip: text;
    background-clip: text;
}

.lang-chevron-2027 {
    width: 1.125rem;
    height: 1.125rem;
    color: #94a3b8;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.lang-toggle-2027:hover .lang-chevron-2027 {
    color: #6366f1;
}

.lang-chevron-2027.active {
    transform: rotate(180deg);
    color: #6366f1;
}

.lang-dropdown-2027 {
    position: absolute;
    top: calc(100% + 0.75rem);
    right: 0;
    min-width: 14rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 255, 0.95) 100%);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(99, 102, 241, 0.1);
    border-radius: 1.25rem;
    box-shadow:
        0 25px 60px -15px rgba(0, 0, 0, 0.2),
        0 10px 30px -10px rgba(99, 102, 241, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
    overflow: hidden;
}

[dir="rtl"] .lang-dropdown-2027 {
    right: auto;
    left: 0;
}

.lang-dropdown-arrow-2027 {
    position: absolute;
    top: -8px;
    right: 1.5rem;
    width: 16px;
    height: 16px;
    background: white;
    transform: rotate(45deg);
    border-top: 1px solid rgba(99, 102, 241, 0.1);
    border-left: 1px solid rgba(99, 102, 241, 0.1);
    box-shadow: -3px -3px 10px rgba(99, 102, 241, 0.05);
}

[dir="rtl"] .lang-dropdown-arrow-2027 {
    right: auto;
    left: 1.5rem;
}

.lang-options-2027 {
    padding: 0.625rem;
}

.lang-option-2027 {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    width: 100%;
    padding: 0.875rem 1rem;
    border: none;
    background: transparent;
    border-radius: 0.75rem;
    font-size: 0.9375rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: left;
    position: relative;
    overflow: hidden;
}

[dir="rtl"] .lang-option-2027 {
    text-align: right;
}

.lang-option-2027::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    opacity: 0;
    transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}

.lang-option-2027:hover::before {
    opacity: 1;
}

.lang-option-2027:hover:not(.active) {
    transform: translateX(4px);
}

[dir="rtl"] .lang-option-2027:hover:not(.active) {
    transform: translateX(-4px);
}

.lang-option-2027.active {
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    color: #6366f1;
    cursor: default;
}

.lang-option-2027.active::after {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: linear-gradient(180deg, #6366f1, #a855f7);
    border-radius: 0 4px 4px 0;
}

[dir="rtl"] .lang-option-2027.active::after {
    left: auto;
    right: 0;
    border-radius: 4px 0 0 4px;
}

.lang-option-flag-2027 {
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 0.375rem;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.lang-option-flag-2027 svg {
    width: 100%;
    height: 100%;
}

.lang-option-name-2027 {
    flex: 1;
    position: relative;
    z-index: 1;
}

.lang-check-2027 {
    width: 1.25rem;
    height: 1.25rem;
    color: #6366f1;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    animation: checkPop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes checkPop {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.lang-spinner-2027 {
    width: 1.25rem;
    height: 1.25rem;
    color: #8b5cf6;
    flex-shrink: 0;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.lang-dropdown-footer-2027 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    font-size: 0.75rem;
    color: #64748b;
}

.lang-dropdown-footer-2027 svg {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
    animation: globeSpin 10s linear infinite;
}

@keyframes globeSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Page Transition Animation */
.lang-switching {
    animation: langSwitchFade 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes langSwitchFade {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(0.99); filter: blur(2px); }
    100% { opacity: 1; transform: scale(1); filter: blur(0); }
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .lang-toggle-2027 {
        padding: 0.5rem 0.75rem;
    }

    .lang-name-2027 {
        display: none;
    }

    .lang-dropdown-2027 {
        right: -0.5rem;
        min-width: 11rem;
    }

    [dir="rtl"] .lang-dropdown-2027 {
        right: auto;
        left: -0.5rem;
    }

    .lang-dropdown-arrow-2027 {
        right: 1rem;
    }

    [dir="rtl"] .lang-dropdown-arrow-2027 {
        right: auto;
        left: 1rem;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .lang-toggle-2027 {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%);
        border-color: rgba(71, 85, 105, 0.5);
        color: #e2e8f0;
    }

    .lang-toggle-2027::after {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.98) 0%, rgba(30, 41, 59, 0.95) 100%);
    }

    .lang-name-2027 {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        background-clip: text;
    }

    .lang-dropdown-2027 {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.98) 0%, rgba(30, 41, 59, 0.95) 100%);
        border-color: rgba(71, 85, 105, 0.5);
    }

    .lang-option-2027 {
        color: #e2e8f0;
    }

    .lang-option-2027::before {
        background: linear-gradient(135deg, rgba(71, 85, 105, 0.3) 0%, rgba(51, 65, 85, 0.3) 100%);
    }

    .lang-option-2027.active {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%);
    }

    .lang-dropdown-footer-2027 {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.8) 100%);
        border-color: rgba(71, 85, 105, 0.5);
        color: #94a3b8;
    }

    .lang-dropdown-arrow-2027 {
        background: #334155;
        border-color: rgba(71, 85, 105, 0.5);
    }
}
</style>
@endif
