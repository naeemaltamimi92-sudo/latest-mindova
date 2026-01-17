@extends('layouts.app')

@section('title', __('Contributors Management'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Premium Header -->
    <div class="relative overflow-hidden bg-primary-500 py-10 mb-8 rounded-b-[3rem] shadow-2xl mx-4 sm:mx-6 lg:mx-8">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full "></div>
            <div class="absolute bottom-0 right-0 w-full h-full "></div>
            <div class="floating-element absolute top-10 -left-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="floating-element absolute bottom-10 right-10 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-secondary-500 rounded-2xl blur opacity-40 group-hover:opacity-60duration-500"></div>
                        <div class="relative h-16 w-16 rounded-2xl bg-secondary-500 flex items-center justify-center shadow-2xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full mb-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-white/80">{{ __('Admin Panel') }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ __('Contributors Management') }}</h1>
                        <p class="text-emerald-200/80 mt-1">{{ __('View and manage all contributors on the platform') }}</p>
                    </div>
                </div>
                <x-ui.button as="a" href="{{ route('admin.dashboard') }}" variant="secondary">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </x-ui.button>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $volunteers->total() }}</p>
                            <p class="text-xs text-emerald-200/70">{{ __('Total Contributors') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-amber-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ number_format($volunteers->avg('reputation_score') ?? 0) }}</p>
                            <p class="text-xs text-amber-200/70">{{ __('Avg Reputation') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-violet-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $volunteers->sum('task_assignments_count') }}</p>
                            <p class="text-xs text-violet-200/70">{{ __('Total Tasks') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-cyan-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $volunteers->filter(fn($v) => $v->created_at->isCurrentMonth())->count() }}</p>
                            <p class="text-xs text-cyan-200/70">{{ __('New This Month') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
        <!-- Premium Filters Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-secondary-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-secondary-500 flex items-center justify-center shadow-lg">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ __('Search & Filter') }}</h2>
                        <p class="text-sm text-slate-500">{{ __('Find contributors quickly') }}</p>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.volunteers.index') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[280px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Search Contributors') }}</label>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Name, email, or field...') }}" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50/50">
                        </div>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Experience Level') }}</label>
                        <select name="experience_level" class="w-full py-3.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50/50">
                            <option value="">{{ __('All Levels') }}</option>
                            <option value="Student" {{ request('experience_level') === 'Student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                            <option value="Junior" {{ request('experience_level') === 'Junior' ? 'selected' : '' }}>{{ __('Junior') }}</option>
                            <option value="Mid" {{ request('experience_level') === 'Mid' ? 'selected' : '' }}>{{ __('Mid') }}</option>
                            <option value="Expert" {{ request('experience_level') === 'Expert' ? 'selected' : '' }}>{{ __('Expert') }}</option>
                            <option value="Manager" {{ request('experience_level') === 'Manager' ? 'selected' : '' }}>{{ __('Manager') }}</option>
                        </select>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Sort By') }}</label>
                        <select name="sort_by" class="w-full py-3.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50/50">
                            <option value="reputation_score" {{ request('sort_by') === 'reputation_score' ? 'selected' : '' }}>{{ __('Reputation') }}</option>
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>{{ __('Registration Date') }}</option>
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Order') }}</label>
                        <select name="sort_order" class="w-full py-3.5 rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50/50">
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>{{ __('Highest First') }}</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>{{ __('Lowest First') }}</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <x-ui.button as="submit" variant="primary">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('Search') }}
                        </x-ui.button>
                        <x-ui.button as="a" href="{{ route('admin.volunteers.index') }}" variant="secondary">{{ __('Clear') }}</x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contributors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @forelse($volunteers as $index => $volunteer)
            <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-2xl hover:border-emerald-200 overflow-hidden" style="animation-delay: {{ $index * 0.05 }}s;">
                <!-- Top Accent -->
                <div class="h-1 bg-secondary-500"></div>

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            @if($volunteer->profile_picture)
                            <img src="{{ asset('storage/' . $volunteer->profile_picture) }}" alt="{{ $volunteer->user->name }}" class="h-16 w-16 rounded-2xl object-cover shadow-lg ring-2 ring-slate-100">
                            @else
                            <div class="h-16 w-16 rounded-2xl bg-secondary-500 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl">{{ substr($volunteer->user->name, 0, 2) }}</span>
                            </div>
                            @endif
                            @if($index < 3 && request('sort_by', 'reputation_score') === 'reputation_score' && request('sort_order', 'desc') === 'desc')
                            <div class="absolute -top-2 -right-2 h-7 w-7 rounded-full flex items-center justify-center text-xs font-bold shadow-lg border-2 border-white
                                @if($index === 0) bg-secondary-300 text-yellow-900
                                @elseif($index === 1) bg-gray-300 text-slate-700
                                @else bg-secondary-400 text-white
                                @endif">
                                {{ $index + 1 }}
                            </div>
                            @endif
                            @if($volunteer->reputation_score >= 100)
                            <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-emerald-600 truncate">
                                {{ $volunteer->user->name }}
                            </h3>
                            <p class="text-sm text-slate-500 truncate">{{ $volunteer->user->email }}</p>
                            @if($volunteer->field)
                            <span class="inline-flex items-center mt-2 text-xs font-semibold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-lg">
                                {{ $volunteer->field }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-amber-100/50">
                            <p class="text-xl font-black text-amber-600">{{ number_format($volunteer->reputation_score) }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ __('Points') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-indigo-100/50">
                            <p class="text-xl font-black text-indigo-600">{{ $volunteer->task_assignments_count }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ __('Tasks') }}</p>
                        </div>
                        <div class="bg-secondary-50 rounded-xl p-3 text-center border border-yellow-100/50">
                            <p class="text-xl font-black text-yellow-600">{{ $volunteer->certificates_count }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ __('Certs') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50/50 group-hover:bg-emerald-50/50 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $volunteer->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-emerald-600 font-semibold text-sm">
                        {{ __('View Profile') }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full bg-white rounded-3xl shadow-sm border border-slate-100 p-16 text-center">
                <div class="w-24 h-24 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ __('No Contributors Found') }}</h3>
                <p class="text-slate-500 mb-6">{{ __('Try adjusting your search filters or check back later.') }}</p>
                <x-ui.button as="a" href="{{ route('admin.volunteers.index') }}" variant="primary">
                    {{ __('Clear Filters') }}
                </x-ui.button>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($volunteers->hasPages())
        <div class="flex justify-center">
            {{ $volunteers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
