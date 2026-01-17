@extends('layouts.app')

@section('title', __('Available Tasks'))

@push('styles')
<style>
    .task-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .task-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }
    .skill-badge {
        transition: all 0.3s ease;
    }
    .task-card:hover .skill-badge {
        transform: scale(1.05);
    }
    .complexity-bar {
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .animate-slide-in-up {
        animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    @php
        $tasks = \App\Models\Task::with(['challenge', 'workstream'])
            ->whereIn('status', ['pending', 'matching'])
            ->whereHas('challenge', function($q) {
                $q->where('status', 'active');
            })
            ->latest()
            ->paginate(20);
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-8">
        <!-- Premium Filters Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-8" style="animation-delay: 0.1s" x-data="{ complexity: '{{ request('complexity_max', 10) }}', showFilters: true }">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-primary-500 flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ __('Filter Tasks') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Find the perfect task for you') }}</p>
                    </div>
                </div>
                <x-ui.button @click="showFilters = !showFilters" variant="ghost" size="sm">
                    <svg class="w-6 h-6" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </x-ui.button>
            </div>

            <form method="GET" x-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Skills') }}</label>
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="skill" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50/50" placeholder="{{ __('Filter by skill...') }}" value="{{ request('skill') }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ __('Max Complexity:') }} <span class="text-indigo-600 font-bold" x-text="complexity"></span>/10
                    </label>
                    <div class="pt-3">
                        <input type="range" name="complexity_max" min="1" max="10" x-model="complexity" class="w-full h-3 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <div class="flex justify-between text-xs text-slate-400 mt-2">
                            <span>{{ __('Easy') }}</span>
                            <span>{{ __('Hard') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <x-ui.button as="submit" variant="primary" fullWidth>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('Apply Filters') }}
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Tasks Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($tasks as $index => $task)
            <div class="task-card bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden" style="animation-delay: {{ 0.15 + ($index * 0.05) }}s">
                <!-- Top Accent Bar -->
                <div class="h-1.5 bg-primary-500"></div>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-slate-900 mb-2 line-clamp-2 hover:text-indigo-600">
                                {{ $task->title }}
                            </h2>
                            <a href="{{ route('challenges.show', $task->challenge) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ Str::limit($task->challenge->title, 35) }}
                            </a>
                        </div>
                        @if($task->complexity_score)
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg
                                {{ $task->complexity_score <= 3 ? 'bg-emerald-100 text-emerald-600' : ($task->complexity_score <= 6 ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                                {{ $task->complexity_score }}
                            </div>
                            <span class="text-xs text-slate-400 mt-1">{{ __('Level') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-slate-600 text-sm leading-relaxed mb-5 line-clamp-3">
                        {{ Str::limit($task->description, 180) }}
                    </p>

                    <!-- Skills -->
                    @if($task->required_skills && count($task->required_skills) > 0)
                    <div class="mb-5">
                        <div class="flex flex-wrap gap-2">
                            @foreach(array_slice($task->required_skills, 0, 4) as $skill)
                            <span class="skill-badge px-3 py-1.5 bg-gray-50 text-indigo-700 rounded-lg text-xs font-semibold border border-indigo-100">
                                {{ $skill }}
                            </span>
                            @endforeach
                            @if(count($task->required_skills) > 4)
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">
                                +{{ count($task->required_skills) - 4 }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 mb-5 text-sm">
                        <div class="flex items-center gap-2 text-slate-500">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium">{{ $task->estimated_hours ?? 0 }} {{ __('hours') }}</span>
                        </div>
                        @if($task->deadline)
                        <div class="flex items-center gap-2 text-slate-500">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="font-medium">{{ $task->deadline->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold
                            {{ $task->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        <x-ui.button as="a" href="{{ route('tasks.show', $task->id) }}" variant="primary">
                            {{ __('View Details') }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </x-ui.button>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full bg-white rounded-3xl shadow-lg border border-slate-100 p-16 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('No Tasks Available') }}</h3>
                    <p class="text-slate-500 mb-6">{{ __('No available tasks matching your profile at the moment. Upload your CV to improve task matching!') }}</p>
                    <x-ui.button as="a" href="{{ route('profile.edit') }}" variant="primary" size="lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('Update Profile') }}
                    </x-ui.button>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
        <div class="mt-10 flex justify-center" style="animation-delay: 0.4s">
            {{ $tasks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
