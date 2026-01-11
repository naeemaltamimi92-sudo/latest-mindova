{{-- Bug Report Button - Strictly NOT a helpdesk, only for capturing friction signals --}}
@auth
<div x-data="bugReportModal()">
    {{-- Fixed Button (Bottom Right Corner) --}}
    <button
        @click="open = true"
        type="button"
        class="fixed bottom-6 right-6 z-40 flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-3 rounded-lg shadow-lg transition-all hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
        title="{{ __('Report an Issue') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm font-semibold">{{ __('Report an Issue') }}</span>
    </button>

    {{-- Modal --}}
    <div x-show="open"
         x-cloak
         @click.away="open = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        {{-- Modal Content --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div @click.stop
                 class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">

                {{-- Header --}}
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('Report an Issue') }}</h3>
                    <p class="text-sm text-slate-600">{{ __('Help us identify bugs and friction points.') }}</p>
                </div>

                {{-- Form --}}
                <form @submit.prevent="submit" id="bugReportForm">
                    @csrf

                    {{-- Issue Type --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('What happened?') }} <span class="text-red-500">*</span>
                        </label>
                        <select x-model="form.issue_type" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('Select type...') }}</option>
                            <option value="bug">{{ __('Bug') }}</option>
                            <option value="ui_ux_issue">{{ __('UI / UX issue') }}</option>
                            <option value="confusing_flow">{{ __('Confusing flow') }}</option>
                            <option value="something_didnt_work">{{ __("Something didn't work") }}</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('Description') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="form.description" required rows="4"
                                  placeholder="{{ __('What happened?') }}"
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        <p class="text-xs text-slate-500 mt-1" x-text="`${form.description.length}/1000`"></p>
                    </div>

                    {{-- Screenshot Upload --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ __('Screenshot (Optional)') }}
                        </label>
                        <input type="file"
                               @change="handleScreenshot"
                               accept="image/*"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <p class="text-xs text-slate-500 mt-1">{{ __('Max 5MB') }}</p>
                    </div>

                    {{-- Critical Signal Checkbox --}}
                    <div class="mb-6">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox"
                                   x-model="form.blocked_user"
                                   class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">
                                <span class="font-semibold">{{ __('This blocked me from continuing') }}</span>
                                <span class="block text-xs text-slate-500 mt-0.5">
                                    {{ __("Check this if you couldn't complete your task") }}
                                </span>
                            </span>
                        </label>
                    </div>

                    {{-- Disclaimer --}}
                    <div class="mb-6 p-3 bg-slate-50 border border-slate-200 rounded-lg">
                        <p class="text-xs text-slate-600">
                            {{ __('Bug reports are confidential and used only to improve the platform.') }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button"
                                @click="open = false"
                                class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                                :disabled="submitting"
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">{{ __('Submit') }}</span>
                            <span x-show="submitting">{{ __('Sending...') }}</span>
                        </button>
                    </div>
                </form>

                {{-- Success Message --}}
                <div x-show="success" x-cloak class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800 font-semibold">
                        {{ __('Thank you for your report. This helps us improve the platform.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bugReportModal() {
    return {
        open: false,
        success: false,
        submitting: false,
        form: {
            issue_type: '',
            description: '',
            blocked_user: false,
            current_page: window.location.pathname,
            screenshot: null
        },

        handleScreenshot(event) {
            this.form.screenshot = event.target.files[0];
        },

        async submit() {
            if (this.submitting) return;

            this.submitting = true;
            this.success = false;

            try {
                const formData = new FormData();
                formData.append('issue_type', this.form.issue_type);
                formData.append('description', this.form.description);
                formData.append('current_page', this.form.current_page);
                formData.append('blocked_user', this.form.blocked_user ? '1' : '0');

                if (this.form.screenshot) {
                    formData.append('screenshot', this.form.screenshot);
                }

                const response = await fetch('{{ route("bug-reports.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });

                if (response.ok) {
                    this.success = true;
                    this.form = {
                        issue_type: '',
                        description: '',
                        blocked_user: false,
                        current_page: window.location.pathname,
                        screenshot: null
                    };
                    document.getElementById('bugReportForm').reset();

                    // Auto close after 2 seconds
                    setTimeout(() => {
                        this.open = false;
                        this.success = false;
                    }, 2000);
                } else {
                    alert('{{ __("Failed to submit report. Please try again.") }}');
                }
            } catch (error) {
                console.error('Bug report submission error:', error);
                alert('{{ __("Failed to submit report. Please try again.") }}');
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>
@endauth
