@extends('layouts.app')

@section('title', 'Verify Hiring Record — ' . $record->hiring_verification_id)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-xl">
        <div class="h-2 bg-gradient-to-r from-violet-500 to-emerald-500"></div>
        <div class="p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-3.138-3.138 3.066 3.066 0 00-.806-1.946 3.066 3.066 0 010-3.976 3.066 3.066 0 00.806-1.946 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Verified Hiring Record</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This employment record has been verified by Mindova Platform.</p>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 text-left mb-6 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Professional</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $record->volunteer->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Position</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $record->position_title }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Engagement</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->engagementLabel() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Company</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->company->company?->company_name ?? $record->company->name ?? 'Company' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Hired On</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->hired_at->format('d F Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Professional Level</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->professional_level }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Stars at Hire</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->reputation_stars_at_hire }} ⭐</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Trust Score at Hire</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $record->trust_score_at_hire }}/100</span>
                </div>
                @if(!empty($record->skills_used))
                <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Verified Skills</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($record->skills_used as $skill)
                        <span class="text-[11px] px-2 py-0.5 bg-violet-50 dark:bg-violet-500/10 text-violet-700 dark:text-violet-300 rounded font-medium">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
                <p class="text-xs font-mono text-emerald-700 dark:text-emerald-300 break-all">{{ $record->hiring_verification_id }}</p>
                <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1">Verified Hiring Record ID · Mindova Platform</p>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('talent.index') }}" class="px-5 py-2 text-sm font-semibold text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/30 rounded-lg hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-colors">
                    Talent Marketplace
                </a>
                <a href="{{ route('volunteers.show', $record->volunteer_id) }}" class="px-5 py-2 text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors">
                    View Portfolio
                </a>
            </div>
        </div>
        <div class="h-1 bg-gradient-to-r from-emerald-500 to-violet-500"></div>
    </div>
</div>
@endsection
