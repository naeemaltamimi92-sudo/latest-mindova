@extends('layouts.app')

@section('title', 'Agency Portal Setup — Mindova')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-100 dark:bg-violet-500/20 mb-4">
            <svg class="w-7 h-7 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">White-Label Recruitment Portal</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 max-w-md mx-auto">
            Create your branded talent marketplace. Your clients see your logo and colors — the technology is powered by Mindova.
        </p>
    </div>

    {{-- Feature list --}}
    <div class="grid grid-cols-2 gap-3 mb-8">
        @foreach([
            ['Branded Portal', 'Custom logo, colors & domain'],
            ['Talent Database', 'Access to all verified professionals'],
            ['Candidate Pipeline', 'Manage hire requests & status'],
            ['Client Management', 'Organize candidates per client'],
            ['Verified Records', 'Tamper-proof hiring verification'],
            ['Analytics', 'Placement rates & talent insights'],
        ] as [$feat, $desc])
        <div class="flex items-start gap-2.5 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            <div>
                <div class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $feat }}</div>
                <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('agency.store') }}" enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        @csrf

        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <h2 class="font-semibold text-gray-900 dark:text-white">Portal Configuration</h2>
        </div>

        <div class="p-6 space-y-5">
            @if($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg">
                @foreach($errors->all() as $error)
                <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Agency Name <span class="text-red-500">*</span></label>
                <input type="text" name="agency_name" value="{{ old('agency_name', $portal?->agency_name) }}" required
                       placeholder="Your Recruitment Agency"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="What your agency specializes in…"
                          class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent resize-none">{{ old('description', $portal?->description) }}</textarea>
            </div>

            {{-- Brand Colors --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Primary Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="primary_color" value="{{ old('primary_color', $portal?->primary_color ?? '#6366f1') }}"
                               class="w-10 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                        <input type="text" id="primary_color_text" value="{{ old('primary_color', $portal?->primary_color ?? '#6366f1') }}"
                               class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Secondary Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="secondary_color" value="{{ old('secondary_color', $portal?->secondary_color ?? '#8b5cf6') }}"
                               class="w-10 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                        <input type="text" id="secondary_color_text" value="{{ old('secondary_color', $portal?->secondary_color ?? '#8b5cf6') }}"
                               class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono" readonly>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Agency Logo</label>
                @if($portal?->logo_path)
                <div class="mb-2">
                    <img src="{{ $portal->logo_url }}" alt="Logo" class="h-10 object-contain">
                </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">PNG, JPG, SVG. Max 2MB.</p>
            </div>

            {{-- Contact --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $portal?->contact_email) }}"
                           placeholder="hire@youragency.com"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Website</label>
                    <input type="url" name="website" value="{{ old('website', $portal?->website) }}"
                           placeholder="https://youragency.com"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
            </div>

            <button type="submit"
                    class="w-full px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm rounded-xl transition-colors">
                {{ $portal ? 'Update Portal' : 'Create My Agency Portal' }}
            </button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
        const textId = this.name + '_text';
        const textEl = document.getElementById(textId);
        if (textEl) textEl.value = this.value;
    });
});
</script>
@endsection
