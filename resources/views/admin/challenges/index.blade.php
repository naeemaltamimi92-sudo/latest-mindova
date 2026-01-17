@extends('layouts.app')

@section('title', __('Challenges Management'))

@section('content')
<div class="min-h-screen bg-gray-50" x-data="challengesManager()">
    <!-- Premium Hero Header -->
    <div class="relative overflow-hidden bg-primary-500 py-10 mb-8 rounded-b-[3rem] shadow-2xl mx-4 sm:mx-6 lg:mx-8">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full "></div>
            <div class="absolute bottom-0 right-0 w-full h-full "></div>
            <div class="floating-element absolute top-10 -left-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="floating-element absolute bottom-10 right-10 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-secondary-500 rounded-2xl blur opacity-40 group-hover:opacity-60duration-500"></div>
                        <div class="relative h-16 w-16 rounded-2xl bg-primary-500 flex items-center justify-center shadow-2xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full mb-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-white/80">{{ __('Admin Panel') }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ __('Challenges Management') }}</h1>
                        <p class="text-indigo-200/80 mt-1">{{ __('View and manage platform challenges') }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button as="a" href="{{ route('admin.challenges.analytics') }}" variant="secondary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Analytics') }}
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('admin.challenges.export', request()->query()) }}" variant="success">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Export') }}
                    </x-ui.button>
                    <x-ui.button as="a" href="{{ route('admin.dashboard') }}" variant="ghost">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-ui.button>
                </div>
            </div>

            <!-- Quick Stats in Header -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-indigo-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $stats['total'] }}</p>
                            <p class="text-xs text-indigo-200/70">{{ __('Total Challenges') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-yellow-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $stats['pending_review'] }}</p>
                            <p class="text-xs text-yellow-200/70">{{ __('Pending Review') }}</p>
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
                            <p class="text-2xl font-black text-white">{{ $stats['by_status']['active'] ?? 0 }}</p>
                            <p class="text-xs text-emerald-200/70">{{ __('Active') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-teal-500/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stats['growth_rate'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-white">{{ $stats['growth_rate'] >= 0 ? '+' : '' }}{{ $stats['growth_rate'] }}%</p>
                            <p class="text-xs text-teal-200/70">{{ __('Growth Rate') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
        <!-- Additional Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            <!-- Total -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl bg-primary-500 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('Total') }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-yellow-600">{{ $stats['pending_review'] }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('Pending') }}</p>
                    </div>
                </div>
            </div>

            <!-- Active -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-green-600">{{ $stats['by_status']['active'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('Active') }}</p>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-emerald-600">{{ $stats['by_status']['completed'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('Completed') }}</p>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-blue-600">{{ $stats['this_month'] }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('This Month') }}</p>
                    </div>
                </div>
            </div>

            <!-- Growth -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/60 shadow-sm hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-xl {{ $stats['growth_rate'] >= 0 ? 'bg-teal-100' : 'bg-red-100' }} flex items-center justify-center">
                        <svg class="h-5 w-5 {{ $stats['growth_rate'] >= 0 ? 'text-teal-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stats['growth_rate'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-black {{ $stats['growth_rate'] >= 0 ? 'text-teal-600' : 'text-red-600' }}">{{ $stats['growth_rate'] >= 0 ? '+' : '' }}{{ $stats['growth_rate'] }}%</p>
                        <p class="text-xs text-slate-500 font-medium">{{ __('Growth') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 mb-6">
            <form method="GET" action="{{ route('admin.challenges.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Search') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search challenges, companies...') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Status') }}</label>
                        <select name="status" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="">{{ __('All Statuses') }}</option>
                            @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ __($status['label']) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Type') }}</label>
                        <select name="challenge_type" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($challengeTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('challenge_type') === $key ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Company Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Company') }}</label>
                        <select name="company_id" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="">{{ __('All Companies') }}</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name ?? $company->user->name ?? __('Unknown') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Advanced Filters (Collapsible) -->
                <div x-data="{ open: false }" class="mt-4">
                    <button type="button" @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                        <svg class="h-4 w-4" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        {{ __('Advanced Filters') }}
                    </button>

                    <div x-show="open" x-collapse class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Source -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Source') }}</label>
                            <select name="source" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                                <option value="">{{ __('All Sources') }}</option>
                                <option value="company" {{ request('source') === 'company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                                <option value="volunteer" {{ request('source') === 'volunteer' ? 'selected' : '' }}>{{ __('Volunteer') }}</option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Date From') }}</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Date To') }}</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Sort & Buttons -->
                <div class="flex flex-wrap items-end gap-4 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <select name="sort_by" class="py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>{{ __('Date Created') }}</option>
                            <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>{{ __('Last Updated') }}</option>
                            <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>{{ __('Title') }}</option>
                            <option value="score" {{ request('sort_by') === 'score' ? 'selected' : '' }}>{{ __('Score') }}</option>
                        </select>
                        <select name="sort_order" class="py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-sm">
                            <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>{{ __('Oldest') }}</option>
                        </select>
                    </div>
                    <div class="flex gap-2 ms-auto">
                        <x-ui.button as="submit" variant="primary" size="sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            {{ __('Filter') }}
                        </x-ui.button>
                        <x-ui.button as="a" href="{{ route('admin.challenges.index') }}" variant="secondary" size="sm">
                            {{ __('Clear') }}
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bulk Delete Bar -->
        <div x-show="selectedIds.length > 0" x-cloak class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-red-700 font-semibold" x-text="selectedIds.length + ' {{ __('selected') }}'"></span>
                    <x-ui.button @click="selectedIds = []" variant="link" size="sm" class="text-red-600 hover:text-red-800">{{ __('Clear selection') }}</x-ui.button>
                </div>
                <div class="flex items-center gap-3">
                    <input type="text" x-model="bulkDeleteReason" placeholder="{{ __('Enter reason for deletion (required)...') }}" class="flex-1 md:w-96 px-4 py-2 rounded-lg border-red-200 focus:border-red-500 focus:ring-red-500 text-sm">
                    <x-ui.button @click="bulkDelete()" x-bind:disabled="!bulkDeleteReason || bulkDeleteReason.length < 10" variant="destructive" size="sm">
                        {{ __('Delete Selected') }}
                    </x-ui.button>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-2">{{ __('The owners will be notified with the reason for deletion.') }}</p>
        </div>

        <!-- Challenges List -->
        <div class="space-y-4">
            @forelse($challenges as $challenge)
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-xl hover:border-indigo-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Checkbox -->
                        <div class="pt-1">
                            <input type="checkbox" :value="{{ $challenge->id }}" x-model="selectedIds" class="h-5 w-5 rounded border-slate-300 text-red-600 focus:ring-red-500">
                        </div>

                        <!-- Challenge Icon -->
                        <div class="hidden sm:flex flex-shrink-0">
                            <div class="h-14 w-14 rounded-2xl bg-primary-100 flex items-center justify-center group-hover:bg-primary-500">
                                <svg class="h-7 w-7 text-indigo-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.challenges.show', $challenge) }}" class="text-lg font-bold text-slate-900 hover:text-indigo-600">
                                            {{ $challenge->title }}
                                        </a>
                                    </div>
                                    <p class="text-slate-600 mt-1.5 line-clamp-2 text-sm">{{ Str::limit($challenge->original_description ?? $challenge->description, 180) }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @php
                                        $statusInfo = $statuses[$challenge->status] ?? ['label' => ucfirst($challenge->status), 'color' => 'gray'];
                                        $colorMap = [
                                            'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
                                            'blue' => 'bg-blue-100 text-blue-700 ring-blue-200',
                                            'green' => 'bg-green-100 text-green-700 ring-green-200',
                                            'indigo' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
                                            'emerald' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                                            'teal' => 'bg-teal-100 text-teal-700 ring-teal-200',
                                            'gray' => 'bg-slate-100 text-slate-700 ring-slate-200',
                                            'red' => 'bg-red-100 text-red-700 ring-red-200',
                                        ];
                                    @endphp
                                    <span class="flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold ring-1 {{ $colorMap[$statusInfo['color']] ?? $colorMap['gray'] }}">
                                        {{ __($statusInfo['label']) }}
                                    </span>
                                    @if($challenge->score)
                                    <span class="text-xs font-medium text-slate-500">{{ __('Score') }}: <span class="font-bold text-indigo-600">{{ $challenge->score }}/10</span></span>
                                    @endif
                                </div>
                            </div>

                            <!-- Meta Info -->
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3">
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                    </svg>
                                    <span>
                                        @if($challenge->company)
                                            {{ $challenge->company->company_name ?? $challenge->company->user->name ?? __('Unknown') }}
                                        @elseif($challenge->volunteer)
                                            <span class="text-purple-600">{{ $challenge->volunteer->user->name ?? __('Contributor') }}</span>
                                        @else
                                            {{ __('Community') }}
                                        @endif
                                    </span>
                                </div>

                                @if($challenge->challenge_type)
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span>{{ __(ucfirst(str_replace('_', ' ', $challenge->challenge_type))) }}</span>
                                </div>
                                @endif

                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <span>{{ $challenge->workstreams->sum(fn($ws) => $ws->tasks->count()) }} {{ __('Tasks') }}</span>
                                </div>

                                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $challenge->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- View Button Only -->
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('admin.challenges.show', $challenge) }}" class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-indigo-500 text-slate-700 hover:text-white font-semibold px-4 py-2 rounded-lg text-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __('View') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar (if active) -->
                @if(in_array($challenge->status, ['active', 'in_progress']) && $challenge->progress_percentage > 0)
                <div class="px-6 pb-4">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                        <span>{{ __('Progress') }}</span>
                        <span class="font-bold">{{ $challenge->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $challenge->progress_percentage }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-12 text-center">
                <div class="h-20 w-20 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No challenges found') }}</h3>
                <p class="text-slate-500">{{ __('Try adjusting your filters or check back later.') }}</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($challenges->hasPages())
        <div class="mt-8">
            {{ $challenges->links() }}
        </div>
        @endif
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show" x-cloak
         
         
         
         
         
         
         class="fixed bottom-4 right-4 z-50">
        <div class="px-6 py-4 rounded-xl shadow-lg" :class="toast.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
            <p class="font-semibold" x-text="toast.message"></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function challengesManager() {
    return {
        selectedIds: [],
        bulkDeleteReason: '',
        toast: { show: false, message: '', type: 'success' },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => this.toast.show = false, 3000);
        },

        async bulkDelete() {
            if (this.selectedIds.length === 0) {
                this.showToast('{{ __("Please select at least one challenge") }}', 'error');
                return;
            }

            if (!this.bulkDeleteReason || this.bulkDeleteReason.length < 10) {
                this.showToast('{{ __("Please provide a deletion reason (at least 10 characters)") }}', 'error');
                return;
            }

            if (!confirm('{{ __("Are you sure you want to delete the selected challenges? This action cannot be undone. The owners will be notified.") }}')) {
                return;
            }

            try {
                const response = await fetch('{{ route("admin.challenges.bulkDelete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        challenge_ids: this.selectedIds,
                        deletion_reason: this.bulkDeleteReason
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showToast(data.message || '{{ __("Operation failed") }}', 'error');
                }
            } catch (error) {
                this.showToast('{{ __("An error occurred") }}', 'error');
            }
        }
    }
}
</script>
@endpush
@endsection
