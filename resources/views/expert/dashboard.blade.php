@extends('layouts.app')

@section('title', 'Expert Dashboard — Mindova')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $volunteer->tier_color }}22; border: 1px solid {{ $volunteer->tier_color }}44;">
                        <svg class="w-5 h-5" style="color: {{ $volunteer->tier_color }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Expert Dashboard</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $volunteer->tier_name }} · {{ $volunteer->stars }} ⭐ · Trust {{ $volunteer->trust_score }}/100</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        @foreach([
            ['label' => 'Assigned',        'value' => $stats['total_assigned'],  'color' => 'blue'],
            ['label' => 'Active',           'value' => $stats['active'],          'color' => 'emerald'],
            ['label' => 'Pending Invite',   'value' => $stats['pending_invite'],  'color' => 'amber'],
            ['label' => 'Completed',        'value' => $stats['completed'],       'color' => 'violet'],
            ['label' => 'Certs to Approve', 'value' => $stats['pending_approval'],'color' => 'rose'],
        ] as $stat)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
            <div class="text-2xl font-bold text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">{{ $stat['value'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Assignments List --}}
    <div class="space-y-4">
        @forelse($assignments as $assignment)
        @php
            $ch = $assignment->challenge;
            $statusColors = [
                'invited'   => 'amber',
                'accepted'  => 'blue',
                'active'    => 'emerald',
                'completed' => 'violet',
                'declined'  => 'gray',
                'withdrawn' => 'gray',
            ];
            $color = $statusColors[$assignment->status] ?? 'gray';
            $roleLabels = [
                'lead_expert'      => '👑 Lead Expert',
                'domain_expert'    => '🔬 Domain Expert',
                'quality_reviewer' => '✅ Quality Reviewer',
            ];
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-500/20 text-{{ $color }}-700 dark:text-{{ $color }}-300">
                                {{ ucfirst($assignment->status) }}
                            </span>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $roleLabels[$assignment->role] ?? $assignment->role }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                Score: {{ number_format($assignment->selection_score, 1) }}/100
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $ch->title }}</h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($ch->company)
                            <span>{{ $ch->company->company_name ?? 'Company' }}</span>
                            <span>·</span>
                            @endif
                            <span>{{ $ch->field ?? 'General' }}</span>
                            @if($ch->score)
                            <span>· Complexity {{ $ch->score }}/10</span>
                            @endif
                            <span>· Invited {{ $assignment->invited_at?->diffForHumans() }}</span>
                        </div>
                        @if($assignment->selection_reasoning)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 italic">{{ $assignment->selection_reasoning }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($assignment->status === 'invited')
                        <form method="POST" action="{{ route('expert.accept', $assignment) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                Accept
                            </button>
                        </form>
                        <form method="POST" action="{{ route('expert.decline', $assignment) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-colors">
                                Decline
                            </button>
                        </form>
                        @elseif(in_array($assignment->status, ['accepted', 'active']))
                        <a href="{{ route('expert.challenge', $ch) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            Open Challenge
                        </a>
                        @elseif($assignment->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 text-sm text-violet-600 dark:text-violet-400 font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Completed
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400 dark:text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <p class="text-lg font-medium">No expert assignments yet</p>
            <p class="text-sm mt-1">You'll be automatically selected when a matching challenge is published.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
