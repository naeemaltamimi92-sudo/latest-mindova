@extends('layouts.app')

@section('title', 'Hire ' . $volunteer->user->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <a href="{{ route('talent.profile', $volunteer) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Profile
    </a>

    @php
        $tierColor = $volunteer->tier_color ?? '#6366f1';
        $tierName  = $volunteer->tier_name  ?? 'Explorer';
    @endphp

    {{-- Professional summary --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden mb-6">
        <div class="h-1.5" style="background: {{ $tierColor }}"></div>
        <div class="p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl font-bold text-white shrink-0"
                 style="background: linear-gradient(135deg, {{ $tierColor }}, {{ $tierColor }}99)">
                {{ strtoupper(substr($volunteer->user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $volunteer->user->name }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:{{ $tierColor }}15; color:{{ $tierColor }}">{{ $tierName }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $volunteer->stars ?? $volunteer->reputation_score }} stars</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">·</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Trust {{ number_format($volunteer->trust_score ?? 100, 0) }}/100</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">·</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $score['verified_projects'] }} verified projects</span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-bold" style="color: {{ $tierColor }}">{{ $score['total'] }}</div>
                <div class="text-[10px] text-gray-400 uppercase tracking-wider">Talent Score</div>
            </div>
        </div>
        @if($certificates->count() > 0)
        <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/40 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-3">
            @foreach($certificates->take(3) as $cert)
            <span class="inline-flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ Str::limit($cert->challenge->title ?? 'Project', 30) }}
            </span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Hire Request Form --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">Send a Hire Request</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">This creates a verified, traceable hiring record on the Mindova platform.</p>
        </div>

        <form method="POST" action="{{ route('talent.hire.store', $volunteer) }}" class="p-6 space-y-5">
            @csrf

            @if($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg">
                @foreach($errors->all() as $error)
                <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Engagement type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Engagement Type</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach([
                        'full_time'  => ['Full-time', 'Permanent employment'],
                        'part_time'  => ['Part-time', 'Flexible employment'],
                        'consulting' => ['Consulting', 'Advisory contract'],
                        'project'    => ['Project',   'Defined scope work'],
                        'invitation' => ['Invitation', 'Private challenge'],
                    ] as $val => [$label, $desc])
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="{{ $val }}" class="peer sr-only" {{ old('type', 'project') === $val ? 'checked' : '' }}>
                        <div class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg peer-checked:border-violet-500 dark:peer-checked:border-violet-400 peer-checked:bg-violet-50 dark:peer-checked:bg-violet-500/10 transition-colors">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $label }}</div>
                            <div class="text-[11px] text-gray-400 dark:text-gray-500">{{ $desc }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Position title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Position Title <span class="text-red-500">*</span></label>
                <input type="text" name="position_title" value="{{ old('position_title') }}" required
                       placeholder="e.g. Senior Data Analyst, Digital Transformation Lead"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
            </div>

            {{-- Message --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Message to Professional <span class="text-red-500">*</span></label>
                <textarea name="message" rows="5" required
                          placeholder="Explain the opportunity, why you chose this professional, and what you're looking for…"
                          class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent resize-none">{{ old('message') }}</textarea>
            </div>

            {{-- Salary + Start Date --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Compensation Range</label>
                    <input type="text" name="salary_range" value="{{ old('salary_range') }}"
                           placeholder="e.g. 8,000–12,000 SAR / month"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Proposed Start Date</label>
                    <input type="date" name="proposed_start_date" value="{{ old('proposed_start_date') }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
            </div>

            {{-- Private challenge toggle --}}
            <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <input type="checkbox" name="is_private_challenge" value="1"
                       {{ old('is_private_challenge') ? 'checked' : '' }}
                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                <div>
                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">Create Private Challenge</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Publish an invitation-only challenge specifically for this professional.</div>
                </div>
            </label>

            {{-- Verification notice --}}
            <div class="flex items-start gap-3 p-4 bg-violet-50 dark:bg-violet-500/10 border border-violet-200 dark:border-violet-500/30 rounded-lg">
                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-3.138-3.138 3.066 3.066 0 00-.806-1.946 3.066 3.066 0 010-3.976 3.066 3.066 0 00.806-1.946 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="text-xs text-violet-700 dark:text-violet-300 leading-relaxed">
                    When accepted, Mindova automatically generates a <strong>Verified Hiring Record</strong> with a unique verification ID, snapshot of their portfolio, and professional reputation at time of hire.
                </p>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('talent.profile', $volunteer) }}"
                   class="flex-1 text-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 px-5 py-2.5 text-white text-sm font-bold rounded-xl hover:opacity-90 transition-opacity"
                        style="background: {{ $tierColor }}">
                    Send Hire Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
