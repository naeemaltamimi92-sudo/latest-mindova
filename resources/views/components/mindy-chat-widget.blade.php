@auth
<div x-data="mindyChat()" x-init="init()" class="fixed bottom-4 left-4 z-40">
    {{-- Launcher bubble --}}
    <button
        x-show="!open"
        @click="toggle()"
        type="button"
        class="w-14 h-14 rounded-full bg-aurora shadow-lg hover:shadow-xl flex items-center justify-center text-white transition-shadow focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:ring-offset-2"
        aria-label="{{ __('Chat with Mindy') }}"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span x-show="unreadHint" x-cloak class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 ring-2 ring-white"></span>
    </button>

    {{-- Chat panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        class="w-[22rem] max-w-[90vw] h-[32rem] max-h-[75vh] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden"
    >
        <div class="bg-aurora px-4 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center ring-1 ring-white/20">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-none">Mindy</p>
                    <p class="text-[11px] text-white/70 mt-0.5">{{ __("Mindova's AI Guide") }}</p>
                </div>
            </div>
            <button @click="toggle()" type="button" class="text-white/70 hover:text-white" aria-label="{{ __('Close') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-ref="scrollArea" class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-slate-50">
            <template x-if="messages.length === 0 && !loadingHistory">
                <p class="text-sm text-slate-500 text-center mt-6">{{ __("Ask me anything about Mindova — challenges, Stars, certificates, or what to do next.") }}</p>
            </template>
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div
                        class="max-w-[85%] px-3.5 py-2 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap"
                        :class="msg.role === 'user' ? 'bg-aurora text-white rounded-br-sm' : 'bg-white text-slate-800 border border-slate-200 rounded-bl-sm'"
                        x-text="msg.content"
                    ></div>
                </div>
            </template>
            <div x-show="sending" class="flex justify-start">
                <div class="px-3.5 py-2.5 rounded-2xl bg-white border border-slate-200 rounded-bl-sm">
                    <svg class="animate-spin h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <form @submit.prevent="send()" class="flex-shrink-0 border-t border-slate-100 p-3 flex items-end gap-2">
            <textarea
                x-model="draft"
                @keydown.enter.prevent="if (!$event.shiftKey) send()"
                rows="1"
                maxlength="2000"
                placeholder="{{ __('Message Mindy...') }}"
                class="flex-1 resize-none px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 max-h-24"
                :disabled="sending"
            ></textarea>
            <button
                type="submit"
                class="w-9 h-9 flex-shrink-0 rounded-xl bg-aurora text-white flex items-center justify-center disabled:opacity-50"
                :disabled="sending || !draft.trim()"
                aria-label="{{ __('Send') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
function mindyChat() {
    return {
        open: false,
        messages: [],
        draft: '',
        sending: false,
        loadingHistory: false,
        unreadHint: false,
        historyLoaded: false,

        init() {},

        toggle() {
            this.open = !this.open;
            this.unreadHint = false;
            if (this.open && !this.historyLoaded) {
                this.loadHistory();
            }
        },

        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        },

        async loadHistory() {
            this.loadingHistory = true;
            try {
                const response = await fetch('{{ route('mindy.history') }}');
                const data = await response.json();
                if (data.success) {
                    this.messages = data.messages.map(m => ({ role: m.role, content: m.content }));
                    this.historyLoaded = true;
                    this.scrollToBottom();
                }
            } catch (e) {
                console.error('Failed to load Mindy history:', e);
            } finally {
                this.loadingHistory = false;
            }
        },

        async send() {
            const message = this.draft.trim();
            if (!message || this.sending) return;

            this.messages.push({ role: 'user', content: message });
            this.draft = '';
            this.sending = true;
            this.scrollToBottom();

            try {
                const response = await fetch('{{ route('mindy.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken(),
                    },
                    body: JSON.stringify({ message, current_page: window.location.pathname }),
                });
                const data = await response.json();
                if (data.success) {
                    this.messages.push({ role: 'assistant', content: data.reply });
                } else {
                    this.messages.push({ role: 'assistant', content: data.message || "{{ __('Something went wrong. Please try again.') }}" });
                }
            } catch (e) {
                this.messages.push({ role: 'assistant', content: "{{ __('Something went wrong. Please try again.') }}" });
            } finally {
                this.sending = false;
                this.scrollToBottom();
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.scrollArea) {
                    this.$refs.scrollArea.scrollTop = this.$refs.scrollArea.scrollHeight;
                }
            });
        },
    };
}
</script>
@endauth
