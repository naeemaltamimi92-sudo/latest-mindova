{{-- Textarea Component --}}
@php $escapedValue = str_replace(['\\', "'", "\n", "\r"], ['\\\\', "\\'", "\\n", ""], $value); @endphp
<div x-data="{
    value: '{{ $escapedValue }}',
    saving: false,
    originalValue: '{{ $escapedValue }}',
    expanded: false
}" class="w-full lg:w-80">
    <div class="relative">
        <textarea
            x-model="value"
            @blur="
                if (value !== originalValue) {
                    saving = true;
                    fetch('{{ route('admin.settings.updateSingle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ key: '{{ $setting->key }}', value: value })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            originalValue = value;
                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message, 'error');
                        }
                        saving = false;
                    })
                    .catch(() => {
                        showToast('{{ __('Error updating setting') }}', 'error');
                        saving = false;
                    });
                }
            "
            :rows="expanded ? 8 : 3"
            class="w-full px-4 py-3 text-sm text-slate-900 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all resize-none"
            :class="saving && 'opacity-50'"
            :disabled="saving"
            placeholder="{{ __('Enter text...') }}"
        ></textarea>
        <button
            @click="expanded = !expanded"
            type="button"
            class="absolute bottom-2 right-2 p-1 text-slate-400 hover:text-slate-600 transition"
            :title="expanded ? '{{ __('Collapse') }}' : '{{ __('Expand') }}'"
        >
            <svg x-show="!expanded" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
            </svg>
            <svg x-show="expanded" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
            </svg>
        </button>
    </div>
    <div x-show="saving" class="flex items-center gap-2 mt-2 text-slate-500 text-sm">
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>{{ __('Saving...') }}</span>
    </div>
</div>
