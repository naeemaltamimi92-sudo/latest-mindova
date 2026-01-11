{{-- Toggle Switch Component --}}
<div x-data="{ enabled: {{ $value ? 'true' : 'false' }}, loading: false }" class="relative">
    <button
        type="button"
        @click="
            loading = true;
            fetch('{{ route('admin.settings.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ key: '{{ $setting->key }}' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    enabled = data.value;
                    showToast(data.message, data.value ? 'success' : 'info');
                }
                loading = false;
            })
            .catch(() => {
                showToast('{{ __('Error updating setting') }}', 'error');
                loading = false;
            });
        "
        :class="enabled ? 'bg-gradient-to-r {{ $color }}' : 'bg-slate-300'"
        class="relative inline-flex h-8 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50"
        :disabled="loading"
    >
        <span
            :class="enabled ? 'ltr:translate-x-6 rtl:-translate-x-6' : 'translate-x-0'"
            class="pointer-events-none relative inline-block h-7 w-7 transform rounded-full bg-white shadow-lg ring-0 transition-transform duration-300 ease-in-out"
        >
            <span
                :class="enabled ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in'"
                class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
            >
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 12 12">
                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span
                :class="enabled ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out'"
                class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity"
            >
                <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 12 12">
                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"/>
                </svg>
            </span>
        </span>
    </button>
    <div x-show="loading" class="absolute inset-0 flex items-center justify-center">
        <svg class="animate-spin h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
