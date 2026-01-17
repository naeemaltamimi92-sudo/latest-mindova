@extends('layouts.app')

@section('title', __('Companies Management'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Premium Header -->
    <div class="relative overflow-hidden bg-primary-500 py-10 mb-8 rounded-b-[3rem] shadow-2xl mx-4 sm:mx-6 lg:mx-8">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full "></div>
            <div class="absolute bottom-0 right-0 w-full h-full "></div>
            <div class="floating-element absolute top-10 -left-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="floating-element absolute bottom-10 right-10 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-primary-400 rounded-2xl blur opacity-40 group-hover:opacity-60duration-500"></div>
                        <div class="relative h-16 w-16 rounded-2xl bg-primary-500 flex items-center justify-center shadow-2xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full mb-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-white/80">{{ __('Admin Panel') }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ __('Companies Management') }}</h1>
                        <p class="text-blue-200/80 mt-1">{{ __('View and manage all companies on the platform') }}</p>
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
                        <div class="h-10 w-10 rounded-xl bg-blue-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $companies->total() }}</p>
                            <p class="text-xs text-blue-200/70">Total Companies</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $companies->where(fn($c) => $c->challenges->where('status', 'active')->count() > 0)->count() }}</p>
                            <p class="text-xs text-emerald-200/70">With Active Challenges</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-violet-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $companies->sum('challenges_count') }}</p>
                            <p class="text-xs text-violet-200/70">Total Challenges</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-amber-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $companies->filter(fn($c) => $c->created_at->isCurrentMonth())->count() }}</p>
                            <p class="text-xs text-amber-200/70">New This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
        <!-- Premium Filters Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Search & Filter</h2>
                        <p class="text-sm text-slate-500">Find companies quickly</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.companies.index') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[280px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Search Companies</label>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Company name, email, or industry..." class="w-full pl-12 pr-4 py-3.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50">
                        </div>
                    </div>
                    <div class="min-w-[180px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sort By</label>
                        <select name="sort_by" class="w-full py-3.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Registration Date</option>
                            <option value="challenges_count" {{ request('sort_by') === 'challenges_count' ? 'selected' : '' }}>Challenge Count</option>
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Order</label>
                        <select name="sort_order" class="w-full py-3.5 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50">
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Newest First</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <x-ui.button as="submit" variant="primary">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </x-ui.button>
                        <x-ui.button as="a" href="{{ route('admin.companies.index') }}" variant="secondary">
                            Clear
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Companies Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            @forelse($companies as $index => $company)
            <a href="{{ route('admin.companies.show', $company) }}" class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-2xl hover:border-blue-200 overflow-hidden" style="animation-delay: {{ $index * 0.05 }}s;">
                <!-- Top Accent -->
                <div class="h-1 bg-primary-500"></div>

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            @if($company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }}" class="h-16 w-16 rounded-2xl object-cover shadow-lg ring-2 ring-slate-100">
                            @else
                            <div class="h-16 w-16 rounded-2xl bg-primary-500 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-xl">{{ substr($company->company_name ?? $company->user->name, 0, 2) }}</span>
                            </div>
                            @endif
                            @if($company->challenges->where('status', 'active')->count() > 0)
                            <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-lg text-slate-900 group-hover:text-blue-600 truncate">
                                {{ $company->company_name ?? $company->user->name }}
                            </h3>
                            <p class="text-sm text-slate-500 truncate">{{ $company->user->email }}</p>
                            @if($company->industry)
                            <span class="inline-flex items-center mt-2 text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                                {{ $company->industry }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-blue-100/50">
                            <p class="text-xl font-black text-blue-600">{{ $company->challenges_count }}</p>
                            <p class="text-xs text-slate-500 font-medium">Challenges</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-emerald-100/50">
                            <p class="text-xl font-black text-emerald-600">{{ $company->challenges->where('status', 'active')->count() }}</p>
                            <p class="text-xs text-slate-500 font-medium">Active</p>
                        </div>
                        <div class="bg-secondary-50 rounded-xl p-3 text-center border border-violet-100/50">
                            <p class="text-xl font-black text-violet-600">{{ $company->challenges->where('status', 'completed')->count() }}</p>
                            <p class="text-xs text-slate-500 font-medium">Completed</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50/50 group-hover:bg-blue-50/50 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $company->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-blue-600 font-semibold text-sm">
                        View Details
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">No Companies Found</h3>
                <p class="text-slate-500 mb-6">Try adjusting your search filters or check back later.</p>
                <x-ui.button as="a" href="{{ route('admin.companies.index') }}" variant="primary">
                    Clear Filters
                </x-ui.button>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($companies->hasPages())
        <div class="flex justify-center">
            {{ $companies->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
