{{--
    Shared modal shell. Consolidates 3+ previously ad-hoc chrome treatments
    (radius xl/2xl/3xl, header none/gray-band/gradient-band, overlay
    gray-900/70 vs slate-900/70) into one component.

    Purely presentational - takes the Alpine boolean *expression* already
    driving an existing modal (e.g. show="showImportModal") so it wraps
    existing JS state without rewiring any interaction logic.

    Usage:
    <x-ui.modal show="showImportModal" title="Import Settings" icon="upload">
        ...body...
        <x-slot:footer>
            <x-ui.button @click="showImportModal = false" variant="secondary">Cancel</x-ui.button>
            <x-ui.button @click="importSettings()" variant="primary">Import</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
--}}

@props([
    'show',
    'title' => null,
    'icon' => null,
    'maxWidth' => 'max-w-lg',
])

<div x-show="{{ $show }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div x-show="{{ $show }}" @click="{{ $show }} = false" x-transition.opacity class="fixed inset-0 bg-gray-900/60 dark:bg-black/70 backdrop-blur-sm"></div>

        <div x-show="{{ $show }}" x-transition
             {{ $attributes->class(["relative bg-white dark:bg-gray-800 rounded-2xl elevation-xl {$maxWidth} w-full overflow-hidden"]) }}>
            @if($title)
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 {{ $icon ? 'bg-aurora' : '' }}">
                <div class="flex items-center gap-3">
                    @if($icon)
                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-xl flex items-center justify-center ring-1 ring-white/20 flex-shrink-0">
                        <x-icon :name="$icon" class="w-5 h-5 text-white" />
                    </div>
                    @endif
                    <h3 class="text-lg font-bold {{ $icon ? 'text-white' : 'text-gray-900 dark:text-white' }}">{{ $title }}</h3>
                </div>
            </div>
            @endif

            <div class="p-6">
                {{ $slot }}
            </div>

            @isset($footer)
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 flex justify-end gap-3">
                {{ $footer }}
            </div>
            @endisset
        </div>
    </div>
</div>
