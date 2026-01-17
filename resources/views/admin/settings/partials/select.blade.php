{{-- Select Dropdown Component --}}
@php
    $options = [];
    if (!empty($setting->options)) {
        $options = explode(',', $setting->options);
    }
@endphp
<div x-data="{ value: '{{ $value }}', saving: false, originalValue: '{{ $value }}' }" class="flex items-center gap-2">
    <select
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
        class="w-48 px-4 py-2 text-sm font-medium text-slate-900 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 appearance-none cursor-pointer"
        :class="saving && 'opacity-50'"
        :disabled="saving"
    >
        @foreach($options as $option)
            <option value="{{ trim($option) }}" {{ $value === trim($option) ? 'selected' : '' }}>
                {{ __(ucfirst(str_replace(['_', '-'], ' ', trim($option)))) }}
            </option>
        @endforeach
    </select>
    <div x-show="saving" class="text-slate-400">
        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
