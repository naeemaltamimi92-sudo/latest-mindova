@extends('mindova.layouts.app')

@section('title', __('Audit Logs'))

@section('content')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-slide-up { animation: slideUp 0.5s ease-out forwards; }
    .animate-slide-up-delay-1 { animation: slideUp 0.5s ease-out 0.1s forwards; opacity: 0; }
    .animate-slide-up-delay-2 { animation: slideUp 0.5s ease-out 0.2s forwards; opacity: 0; }
    .timeline-line { position: absolute; left: 27px; top: 60px; bottom: 0; width: 2px; background: linear-gradient(to bottom, #e2e8f0, #f1f5f9); }
</style>

<div class="space-y-6" x-data="auditLogs()">
    <!-- Premium Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-3xl p-8 animate-slide-up">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-purple-600/20"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ __('Audit Logs') }}</h1>
                        <p class="text-white/60">{{ __('Track and monitor all team activities') }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">{{ $logs->total() }}</div>
                    <div class="text-xs text-white/60 uppercase tracking-wide">{{ __('Total Logs') }}</div>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-400">{{ $stats['logins_today'] ?? 0 }}</div>
                    <div class="text-xs text-white/60 uppercase tracking-wide">{{ __('Logins Today') }}</div>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400">{{ $stats['actions_today'] ?? 0 }}</div>
                    <div class="text-xs text-white/60 uppercase tracking-wide">{{ __('Actions Today') }}</div>
                </div>
            </div>

            @if($currentTeamMember->hasPermission('audit.export'))
            <a href="{{ route('mindova.audit.export', request()->query()) }}"
                class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all border border-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                {{ __('Export CSV') }}
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-1">
        <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                {{ __('Filter Logs') }}
            </h3>
            <button type="button" @click="showFilters = !showFilters" class="text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="w-5 h-5 transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div x-show="showFilters" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <form action="{{ route('mindova.audit.index') }}" method="GET" class="p-6">
                <!-- Action Type Quick Filters -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-3">{{ __('Quick Filter by Action') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $actionTypes = [
                                'login' => ['label' => __('Logins'), 'color' => 'emerald'],
                                'logout' => ['label' => __('Logouts'), 'color' => 'slate'],
                                'password_changed' => ['label' => __('Password Changes'), 'color' => 'blue'],
                                'team_member_invited' => ['label' => __('Invitations'), 'color' => 'purple'],
                                'role_changed' => ['label' => __('Role Changes'), 'color' => 'amber'],
                                'team_member_deactivated' => ['label' => __('Deactivations'), 'color' => 'red'],
                            ];
                        @endphp
                        @foreach($actionTypes as $action => $meta)
                        <button type="button" @click="toggleAction('{{ $action }}')"
                            class="px-4 py-2 rounded-xl text-sm font-medium transition-all border-2"
                            :class="selectedAction === '{{ $action }}'
                                ? 'bg-{{ $meta['color'] }}-100 border-{{ $meta['color'] }}-500 text-{{ $meta['color'] }}-700'
                                : 'bg-slate-50 border-slate-200 text-slate-600 hover:border-slate-300'">
                            {{ $meta['label'] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="action" class="block text-sm font-medium text-slate-700 mb-2">{{ __('Action Keyword') }}</label>
                        <div class="relative">
                            <input type="text" id="action" name="action" :value="selectedAction || '{{ $filters['action'] ?? '' }}'"
                                class="w-full px-4 py-3 pl-11 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="{{ __('Search actions...') }}">
                            <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label for="team_member_id" class="block text-sm font-medium text-slate-700 mb-2">{{ __('Team Member') }}</label>
                        <select id="team_member_id" name="team_member_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">{{ __('All Members') }}</option>
                            @foreach($teamMembers ?? [] as $member)
                            <option value="{{ $member->id }}" {{ ($filters['team_member_id'] ?? '') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="from_date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('From Date') }}</label>
                        <input type="date" id="from_date" name="from_date" value="{{ $filters['from_date'] ?? '' }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label for="to_date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('To Date') }}</label>
                        <input type="date" id="to_date" name="to_date" value="{{ $filters['to_date'] ?? '' }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-slate-200">
                    <a href="{{ route('mindova.audit.index') }}" class="px-5 py-2.5 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition-colors">
                        {{ __('Clear Filters') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('Apply Filters') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex items-center justify-between animate-slide-up-delay-1">
        <div class="flex items-center gap-2 bg-white rounded-xl p-1 border border-slate-200">
            <button type="button" @click="viewMode = 'timeline'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                :class="viewMode === 'timeline' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100'">
                <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Timeline') }}
            </button>
            <button type="button" @click="viewMode = 'table'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                :class="viewMode === 'table' ? 'bg-blue-100 text-blue-700' : 'text-slate-600 hover:bg-slate-100'">
                <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                {{ __('Table') }}
            </button>
        </div>

        <p class="text-sm text-slate-500">
            {{ __('Showing') }} <span class="font-semibold text-slate-700">{{ $logs->firstItem() ?? 0 }}</span>
            {{ __('to') }} <span class="font-semibold text-slate-700">{{ $logs->lastItem() ?? 0 }}</span>
            {{ __('of') }} <span class="font-semibold text-slate-700">{{ $logs->total() }}</span> {{ __('results') }}
        </p>
    </div>

    <!-- Timeline View -->
    <div x-show="viewMode === 'timeline'" class="animate-slide-up-delay-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @forelse($logs as $log)
            @php
                $isFirstOfDay = !isset($currentDate) || $currentDate !== $log->created_at->format('Y-m-d');
                $currentDate = $log->created_at->format('Y-m-d');
            @endphp

            @if($isFirstOfDay)
            <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-slate-700">
                        {{ $log->created_at->isToday() ? __('Today') : ($log->created_at->isYesterday() ? __('Yesterday') : $log->created_at->format('l, F j, Y')) }}
                    </span>
                </div>
            </div>
            @endif

            <div class="relative px-6 py-4 hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-b-0">
                <div class="flex items-start gap-4">
                    <!-- Action Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                        @if($log->action === 'login') bg-emerald-100 text-emerald-600
                        @elseif($log->action === 'logout') bg-slate-100 text-slate-600
                        @elseif($log->action === 'password_changed') bg-blue-100 text-blue-600
                        @elseif(str_contains($log->action, 'invited')) bg-purple-100 text-purple-600
                        @elseif(str_contains($log->action, 'role')) bg-amber-100 text-amber-600
                        @elseif(str_contains($log->action, 'deactivated')) bg-red-100 text-red-600
                        @elseif(str_contains($log->action, 'activated')) bg-emerald-100 text-emerald-600
                        @else bg-slate-100 text-slate-600
                        @endif">
                        @if($log->action === 'login')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        @elseif($log->action === 'logout')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        @elseif($log->action === 'password_changed')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        @elseif(str_contains($log->action, 'invited'))
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        @elseif(str_contains($log->action, 'role'))
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide
                                @if($log->action_color === 'green') bg-emerald-100 text-emerald-700
                                @elseif($log->action_color === 'red') bg-red-100 text-red-700
                                @elseif($log->action_color === 'yellow') bg-amber-100 text-amber-700
                                @elseif($log->action_color === 'blue') bg-blue-100 text-blue-700
                                @elseif($log->action_color === 'purple') bg-purple-100 text-purple-700
                                @else bg-slate-100 text-slate-700
                                @endif">
                                {{ $log->action_label }}
                            </span>
                            <span class="text-sm text-slate-400">{{ $log->created_at->format('g:i A') }}</span>
                        </div>

                        @if($log->teamMember)
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($log->teamMember->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-slate-800">{{ $log->teamMember->name }}</span>
                            <span class="text-sm text-slate-500">({{ $log->teamMember->role->name }})</span>
                        </div>
                        @endif

                        @if($log->description)
                        <p class="text-sm text-slate-600">{{ $log->description }}</p>
                        @endif

                        @if($log->metadata && count($log->metadata) > 0)
                        <div class="mt-2 p-3 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500 space-y-1">
                                @foreach($log->metadata as $key => $value)
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="text-slate-700">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Right Side Info -->
                    <div class="flex-shrink-0 text-right">
                        @if($log->ip_address)
                        <div class="text-xs text-slate-400 font-mono">{{ $log->ip_address }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-1">{{ __('No audit logs found') }}</h3>
                <p class="text-slate-500">{{ __('Try adjusting your filters or check back later.') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Table View -->
    <div x-show="viewMode === 'table'" class="animate-slide-up-delay-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Date & Time') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Action') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Team Member') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('Description') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">{{ __('IP Address') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $log->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $log->created_at->format('g:i:s A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold
                                    @if($log->action_color === 'green') bg-emerald-100 text-emerald-700
                                    @elseif($log->action_color === 'red') bg-red-100 text-red-700
                                    @elseif($log->action_color === 'yellow') bg-amber-100 text-amber-700
                                    @elseif($log->action_color === 'blue') bg-blue-100 text-blue-700
                                    @elseif($log->action_color === 'purple') bg-purple-100 text-purple-700
                                    @else bg-slate-100 text-slate-700
                                    @endif
                                ">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->teamMember)
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($log->teamMember->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $log->teamMember->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->teamMember->role->name }}</div>
                                    </div>
                                </div>
                                @else
                                <span class="text-sm text-slate-500">{{ __('System') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 max-w-xs truncate" title="{{ $log->description }}">
                                    {{ $log->description ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-500 font-mono bg-slate-100 px-2 py-1 rounded">{{ $log->ip_address ?? '-' }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-800 mb-1">{{ __('No audit logs found') }}</h3>
                                <p class="text-slate-500">{{ __('Try adjusting your filters or check back later.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm px-6 py-4">
        {{ $logs->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script>
function auditLogs() {
    return {
        showFilters: {{ !empty($filters['action']) || !empty($filters['from_date']) || !empty($filters['to_date']) ? 'true' : 'false' }},
        viewMode: 'timeline',
        selectedAction: '{{ $filters['action'] ?? '' }}',
        toggleAction(action) {
            if (this.selectedAction === action) {
                this.selectedAction = '';
            } else {
                this.selectedAction = action;
            }
        }
    }
}
</script>
@endsection
