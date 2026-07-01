{{-- Number Input Component --}}
<div x-data="{ value: '{{ $value }}', saving: false, originalValue: '{{ $value }}' }" class="flex items-center gap-2">
    <input
        type="number"
        x-model="value"
        @change="
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
        class="w-28 px-3.5 py-2 text-sm font-medium text-slate-900 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        :class="saving && 'opacity-50'"
        :disabled="saving"
    >
    <div x-show="saving" class="text-primary-500">
        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
