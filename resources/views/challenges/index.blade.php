@extends('layouts.app')

@section('title', __('Challenges'))

@section('content')
<div class="max-w-7xl mx-auto px-6 sm:px-8 md:px-10 lg:px-12 xl:px-16 py-8">
    <!-- Advanced Filters -->
    <div x-data="{
        showFilters: false,
        selectedStatus: '{{ request('status', '') }}',
        selectedType: '{{ request('type', '') }}'
    }" class="mb-10 sm:mb-12">
        <!-- Enhanced Filter Toggle & Active Filters -->
        <div class="flex flex-wrap items-center gap-4 mb-8">
            <x-ui.button @click="showFilters = !showFilters" variant="secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                {{ __('Filters') }}
                <svg class="w-4 h-4" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </x-ui.button>

            <!-- Refined Active Filter Pills -->
            <div class="flex flex-wrap gap-2">
                @if(request('status'))
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-lg text-sm font-semibold shadow-sm">
                    {{ __('Status') }}: {{ ucfirst(request('status')) }}
                    <a href="{{ route('challenges.index', array_diff_key(request()->all(), ['status' => ''])) }}" class="hover:bg-indigo-100 rounded-full p-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
                @if(request('type'))
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-violet-50 border border-violet-200 text-violet-700 rounded-lg text-sm font-semibold shadow-sm">
                    {{ __('Type') }}: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                    <a href="{{ route('challenges.index', array_diff_key(request()->all(), ['type' => ''])) }}" class="hover:bg-violet-100 rounded-full p-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </span>
                @endif
                @if(request()->has('status') || request()->has('type'))
                <a href="{{ route('challenges.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg text-sm font-semibold shadow-sm hover:bg-slate-100">
                    {{ __('Clear All') }}
                </a>
                @endif
            </div>
        </div>

        <!-- Enhanced Filter Panel -->
        <div x-show="showFilters"
             
             
             
             
             
             
             class="bg-white rounded-3xl border border-slate-200 shadow-sm px-8 py-7">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-4">
                        <svg class="w-4 h-4 inline mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Status') }}
                    </label>
                    <div class="space-y-2.5">
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50" :class="selectedStatus === '' ? 'border-indigo-400 bg-indigo-50' : ''">
                            <input type="radio" name="status" value="" x-model="selectedStatus" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('All Statuses') }}</div>
                            <svg class="w-5 h-5 text-indigo-600" :class="selectedStatus === '' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-300 hover:bg-emerald-50" :class="selectedStatus === 'active' ? 'border-emerald-400 bg-emerald-50' : ''">
                            <input type="radio" name="status" value="active" x-model="selectedStatus" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('Active') }}</div>
                            <svg class="w-5 h-5 text-emerald-600" :class="selectedStatus === 'active' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-amber-300 hover:bg-amber-50" :class="selectedStatus === 'analyzing' ? 'border-amber-400 bg-amber-50' : ''">
                            <input type="radio" name="status" value="analyzing" x-model="selectedStatus" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('Analyzing') }}</div>
                            <svg class="w-5 h-5 text-amber-600" :class="selectedStatus === 'analyzing' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-violet-300 hover:bg-violet-50" :class="selectedStatus === 'submitted' ? 'border-violet-400 bg-violet-50' : ''">
                            <input type="radio" name="status" value="submitted" x-model="selectedStatus" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('Submitted') }}</div>
                            <svg class="w-5 h-5 text-violet-600" :class="selectedStatus === 'submitted' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                    </div>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-4">
                        <svg class="w-4 h-4 inline mr-2 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        {{ __('Type') }}
                    </label>
                    <div class="space-y-2.5">
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50" :class="selectedType === '' ? 'border-indigo-400 bg-indigo-50' : ''">
                            <input type="radio" name="type" value="" x-model="selectedType" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('All Types') }}</div>
                            <svg class="w-5 h-5 text-indigo-600" :class="selectedType === '' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50" :class="selectedType === 'community_discussion' ? 'border-indigo-400 bg-indigo-50' : ''">
                            <input type="radio" name="type" value="community_discussion" x-model="selectedType" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('Community Discussion') }}</div>
                            <svg class="w-5 h-5 text-indigo-600" :class="selectedType === 'community_discussion' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50" :class="selectedType === 'team_execution' ? 'border-indigo-400 bg-indigo-50' : ''">
                            <input type="radio" name="type" value="team_execution" x-model="selectedType" class="sr-only">
                            <div class="flex-1 font-semibold text-slate-700">{{ __('Team Execution') }}</div>
                            <svg class="w-5 h-5 text-indigo-600" :class="selectedType === 'team_execution' ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </label>
                    </div>
                </div>

                <!-- Enhanced Apply Button -->
                <div class="flex items-end">
                    <x-ui.button as="submit" variant="primary" fullWidth>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('Apply Filters') }}
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Challenges Grid -->
    @if($challenges->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($challenges as $index => $challenge)
        <!-- Premium Challenge Card -->
        <div class="group relative bg-white rounded-3xl p-7 shadow-sm hover:shadow-xl border border-slate-100 hover:border-indigo-200 cursor-pointer overflow-hidden"
             style="animation-delay: {{ $index * 0.05 }}s;"
             onclick="window.location='{{ route('challenges.show', $challenge->id) }}'">

            <!-- Subtle Top Indicator -->
            <div class="absolute top-0 left-0 right-0 h-1
                @if($challenge->status === 'active') bg-secondary-500
                @elseif($challenge->status === 'analyzing') bg-secondary-300
                @elseif($challenge->status === 'submitted') bg-secondary-400
                @else bg-gray-400
                @endif"></div>

            <!-- Subtle Glow on Hover -->
            <div class="absolute inset-0 bg-transparent group-hover:bg-primary-50 rounded-3xl"></div>

            <!-- Card Content -->
            <div class="relative">
                <!-- Refined Tags -->
                <div class="flex items-center gap-2 mb-4 flex-wrap">
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-lg border
                        @if($challenge->status === 'active') bg-emerald-50 text-emerald-700 border-emerald-200
                        @elseif($challenge->status === 'analyzing') bg-amber-50 text-amber-700 border-amber-200
                        @elseif($challenge->status === 'submitted') bg-violet-50 text-violet-700 border-violet-200
                        @else bg-slate-50 text-slate-700 border-slate-200
                        @endif shadow-sm">
                        {{ ucfirst($challenge->status) }}
                    </span>
                    @if($challenge->challenge_type)
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm">
                        {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                    </span>
                    @endif
                </div>

                <!-- Title with Better Hierarchy -->
                <h2 class="text-xl font-black text-slate-900 mb-3 line-clamp-2 group-hover:text-indigo-600 leading-tight">
                    {{ $challenge->title }}
                </h2>

                <!-- Description with Better Line Height -->
                <p class="text-base text-slate-600 leading-relaxed mb-6 line-clamp-3">
                    {{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 150) }}
                </p>

                <!-- Enhanced Metadata -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2.5 text-sm">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700">{{ $challenge->company?->company_name ?? 'N/A' }}</span>
                    </div>
                    @if($challenge->complexity_level)
                    <div class="flex items-center gap-2.5 text-sm">
                        <div class="w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700">Level {{ $challenge->complexity_level }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2.5 text-sm">
                        <div class="w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-slate-600 font-medium">{{ $challenge->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Enhanced Action Link -->
                <div class="pt-5 border-t border-slate-100">
                    <div class="inline-flex items-center text-indigo-600 font-bold text-sm group-hover:text-indigo-700">
                        {{ __('View Details') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        {{ $challenges->links() }}
    </div>

    @else
    <!-- Enhanced Empty State -->
    <div class="relative bg-gray-50 rounded-3xl p-20 text-center shadow-sm border border-slate-200 overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-indigo-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-80 h-80 bg-violet-400 rounded-full blur-3xl"></div>
        </div>

        <!-- Content -->
        <div class="relative">
            <div class="w-24 h-24 bg-primary-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">{{ __('No Challenges Found') }}</h3>
            <p class="text-base text-slate-600 mb-10 max-w-md mx-auto leading-relaxed">{{ __('Try adjusting your filters or check back later for new challenges.') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <x-ui.button as="a" href="{{ route('challenges.index') }}" variant="primary">
                    {{ __('Clear Filters') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </x-ui.button>
                @if(auth()->user()->isCompany())
                <x-ui.button as="a" href="{{ route('challenges.create') }}" variant="secondary">
                    {{ __('Create Challenge') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </x-ui.button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
