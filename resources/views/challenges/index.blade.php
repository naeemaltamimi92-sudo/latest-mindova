@extends('layouts.app')

@section('title', __('Challenges'))

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Challenges') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Browse and manage your challenges') }}</p>
            </div>
            @if(auth()->user()->isCompany())
            <a href="{{ route('challenges.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('Create Challenge') }}
            </a>
            @endif
        </div>

        {{-- Filters --}}
        <div x-data="{
            showFilters: {{ request()->has('status') || request()->has('type') ? 'true' : 'false' }},
            selectedStatus: '{{ request('status', '') }}',
            selectedType: '{{ request('type', '') }}'
        }" class="mb-6">
            
            {{-- Filter Toggle & Active Filters --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <button @click="showFilters = !showFilters" 
                    class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('Filters') }}
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Active Filter Pills --}}
                @if(request('status'))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm">
                    {{ __('Status') }}: {{ ucfirst(request('status')) }}
                    <a href="{{ route('challenges.index', array_diff_key(request()->all(), ['status' => ''])) }}" class="hover:text-blue-900">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </span>
                @endif
                @if(request('type'))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-violet-50 border border-violet-200 text-violet-700 rounded-lg text-sm">
                    {{ __('Type') }}: {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                    <a href="{{ route('challenges.index', array_diff_key(request()->all(), ['type' => ''])) }}" class="hover:text-violet-900">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </span>
                @endif
                @if(request()->has('status') || request()->has('type'))
                <a href="{{ route('challenges.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    {{ __('Clear all') }}
                </a>
                @endif
            </div>

            {{-- Filter Panel --}}
            <div x-show="showFilters" x-cloak class="bg-white rounded-xl border border-gray-200 p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('Status') }}</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedStatus === '' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="status" value="" x-model="selectedStatus" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedStatus === '' ? 'text-primary-700 font-medium' : 'text-gray-700'">{{ __('All Statuses') }}</span>
                                <svg x-show="selectedStatus === ''" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedStatus === 'active' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="status" value="active" x-model="selectedStatus" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedStatus === 'active' ? 'text-emerald-700 font-medium' : 'text-gray-700'">{{ __('Active') }}</span>
                                <svg x-show="selectedStatus === 'active'" class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedStatus === 'analyzing' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="status" value="analyzing" x-model="selectedStatus" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedStatus === 'analyzing' ? 'text-amber-700 font-medium' : 'text-gray-700'">{{ __('Analyzing') }}</span>
                                <svg x-show="selectedStatus === 'analyzing'" class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedStatus === 'submitted' ? 'border-violet-500 bg-violet-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="status" value="submitted" x-model="selectedStatus" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedStatus === 'submitted' ? 'text-violet-700 font-medium' : 'text-gray-700'">{{ __('Submitted') }}</span>
                                <svg x-show="selectedStatus === 'submitted'" class="w-5 h-5 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                        </div>
                    </div>

                    {{-- Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('Type') }}</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedType === '' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="type" value="" x-model="selectedType" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedType === '' ? 'text-primary-700 font-medium' : 'text-gray-700'">{{ __('All Types') }}</span>
                                <svg x-show="selectedType === ''" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedType === 'community_discussion' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="type" value="community_discussion" x-model="selectedType" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedType === 'community_discussion' ? 'text-primary-700 font-medium' : 'text-gray-700'">{{ __('Community Discussion') }}</span>
                                <svg x-show="selectedType === 'community_discussion'" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-colors"
                                :class="selectedType === 'team_execution' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="type" value="team_execution" x-model="selectedType" class="sr-only">
                                <span class="flex-1 text-sm" :class="selectedType === 'team_execution' ? 'text-primary-700 font-medium' : 'text-gray-700'">{{ __('Team Execution') }}</span>
                                <svg x-show="selectedType === 'team_execution'" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                        </div>
                    </div>

                    {{-- Apply Button --}}
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('Apply Filters') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Challenges Grid --}}
        @if($challenges->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($challenges as $challenge)
            <a href="{{ route('challenges.show', $challenge->id) }}" 
                class="group bg-white rounded-xl border border-gray-200 p-5 hover:border-gray-300 transition-colors">
                
                {{-- Status Bar --}}
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-xl
                    @if($challenge->status === 'active') bg-emerald-500
                    @elseif($challenge->status === 'analyzing') bg-amber-500
                    @elseif($challenge->status === 'submitted') bg-violet-500
                    @else bg-gray-400
                    @endif">
                </div>

                {{-- Tags --}}
                <div class="flex flex-wrap items-center gap-2 mb-3 pt-1">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium border
                        @if($challenge->status === 'active') bg-emerald-50 text-emerald-700 border-emerald-200
                        @elseif($challenge->status === 'analyzing') bg-amber-50 text-amber-700 border-amber-200
                        @elseif($challenge->status === 'submitted') bg-violet-50 text-violet-700 border-violet-200
                        @else bg-gray-50 text-gray-700 border-gray-200
                        @endif">
                        {{ ucfirst($challenge->status) }}
                    </span>
                    @if($challenge->challenge_type)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                        {{ ucfirst(str_replace('_', ' ', $challenge->challenge_type)) }}
                    </span>
                    @endif
                </div>

                {{-- Title --}}
                <h2 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                    {{ $challenge->title }}
                </h2>

                {{-- Description --}}
                <p class="text-sm text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                    {{ Str::limit($challenge->refined_brief ?? $challenge->original_description, 140) }}
                </p>

                {{-- Meta Info --}}
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="truncate">{{ $challenge->company?->company_name ?? 'N/A' }}</span>
                    </div>
                    @if($challenge->complexity_level)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>{{ __('Level') }} {{ $challenge->complexity_level }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $challenge->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-600 group-hover:text-primary-700 inline-flex items-center gap-1">
                        {{ __('View Details') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    <span class="text-xs text-gray-400">#{{ $challenge->id }}</span>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($challenges->hasPages())
        <div class="mt-8">
            {{ $challenges->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('No Challenges Found') }}</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ __('Try adjusting your filters or check back later for new challenges.') }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('challenges.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    {{ __('Clear Filters') }}
                </a>
                @if(auth()->user()->isCompany())
                <a href="{{ route('challenges.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:border-gray-300 transition-colors">
                    {{ __('Create Challenge') }}
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
