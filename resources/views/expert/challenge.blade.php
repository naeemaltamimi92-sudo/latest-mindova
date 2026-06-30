@extends('layouts.app')

@section('title', 'Expert Review — ' . $challenge->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back --}}
    <a href="{{ route('expert.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Expert Dashboard
    </a>

    {{-- Challenge Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">
                        {{ ['lead_expert' => '👑 Lead Expert', 'domain_expert' => '🔬 Domain Expert', 'quality_reviewer' => '✅ Quality Reviewer'][$assignment->role] }}
                    </span>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300">
                        Selection Score: {{ number_format($assignment->selection_score, 1) }}/100
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $challenge->title }}</h1>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($challenge->company)
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $challenge->company->company_name ?? 'Company' }}</span>
                    <span>·</span>
                    @endif
                    <span>{{ $challenge->field ?? 'General' }}</span>
                    @if($challenge->score)
                    <span>· Complexity {{ $challenge->score }}/10</span>
                    @endif
                    <span>· Status: <strong>{{ ucfirst($challenge->status) }}</strong></span>
                </div>
            </div>
        </div>

        @if($challenge->refined_brief)
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Challenge Brief</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ Str::limit($challenge->refined_brief, 400) }}</p>
        </div>
        @endif
    </div>

    {{-- Expert Team --}}
    @if($challenge->expertAssignments->count() > 1)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Expert Team</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($challenge->expertAssignments as $ea)
            @if(in_array($ea->status, ['accepted','active','completed']))
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-500/20 flex items-center justify-center text-xs font-bold text-primary-700 dark:text-primary-300">
                    {{ strtoupper(substr($ea->volunteer->user->name ?? 'E', 0, 1)) }}
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-900 dark:text-white">{{ $ea->volunteer->user->name ?? 'Expert' }}</div>
                    <div class="text-xs text-gray-500">{{ ['lead_expert' => 'Lead', 'domain_expert' => 'Domain', 'quality_reviewer' => 'Reviewer'][$ea->role] }}</div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tasks & Submissions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Task Submissions for Review</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($challenge->tasks as $task)
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs px-2 py-0.5 rounded font-medium
                                @if($task->status === 'completed') bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300
                                @elseif($task->status === 'in_progress') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300
                                @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                        @foreach($task->assignments as $ta)
                        @foreach($ta->workSubmissions as $ws)
                        <div class="mt-3 pl-3 border-l-2 border-gray-200 dark:border-gray-600">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $ta->volunteer->user->name ?? 'Volunteer' }}</span>
                                <span class="text-xs text-gray-400">· {{ $ws->submitted_at?->format('M d, Y') }}</span>
                                @if($ws->ai_quality_score)
                                <span class="text-xs font-semibold {{ $ws->ai_quality_score >= 80 ? 'text-emerald-600' : ($ws->ai_quality_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                    Quality: {{ $ws->ai_quality_score }}/100
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($ws->description, 200) }}</p>
                            @if($ws->deliverable_url)
                            <a href="{{ $ws->deliverable_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 mt-1 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                View Deliverable
                            </a>
                            @endif
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 dark:text-gray-500 text-sm">No tasks yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Certificates Awaiting Expert Approval --}}
    @if($pendingCerts->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-amber-200 dark:border-amber-500/30 mb-6">
        <div class="px-6 py-4 border-b border-amber-100 dark:border-amber-500/20 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $pendingCerts->count() }} Certificate(s) Awaiting Your Approval</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($pendingCerts as $cert)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cert->user->name ?? 'Contributor' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cert->role }} · {{ $cert->total_hours }}h · {{ $cert->certificate_number }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($cert->contribution_summary, 120) }}</p>
                </div>
                <form method="POST" action="{{ route('expert.certificate.approve', $cert) }}" class="shrink-0">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        ✓ Approve &amp; Seal
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
