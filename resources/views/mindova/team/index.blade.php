@extends('mindova.layouts.app')

@section('title', __('Team Members'))

@push('styles')
<style>
    .slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-1 { animation-delay: 0.05s; }
    .slide-up-2 { animation-delay: 0.1s; }
    .slide-up-3 { animation-delay: 0.15s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .member-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12);
    }

    .role-badge {
        transition: all 0.2s ease;
    }
    .role-badge:hover {
        transform: scale(1.05);
    }

    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }

    .filter-chip {
        transition: all 0.2s ease;
    }
    .filter-chip:hover {
        transform: translateY(-1px);
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="teamManager()" class="space-y-6">
    <!-- Header with Stats -->
    <div class="relative overflow-hidden bg-primary-500 rounded-3xl p-6 lg:p-8 slide-up slide-up-1">
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-white">{{ __('Team Members') }}</h1>
                <p class="text-white/70 mt-1">{{ __('Manage your internal organization team') }}</p>
            </div>

            @if($currentMember->hasPermission('team.create'))
            <x-ui.button as="a" href="{{ route('mindova.team.create') }}" variant="secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                {{ __('Invite Member') }}
            </x-ui.button>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="relative z-10 mt-6 grid grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Total') }}</p>
                <p class="text-white text-2xl font-bold mt-1">{{ $members->count() }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Active') }}</p>
                <p class="text-white text-2xl font-bold mt-1">{{ $members->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Pending') }}</p>
                <p class="text-white text-2xl font-bold mt-1">{{ $members->where('password_changed', false)->count() }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Online Today') }}</p>
                <p class="text-white text-2xl font-bold mt-1">{{ $members->filter(fn($m) => $m->last_login_at && $m->last_login_at->isToday())->count() }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Inactive') }}</p>
                <p class="text-white text-2xl font-bold mt-1">{{ $members->where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 slide-up slide-up-2">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="search" placeholder="{{ __('Search by name or email...') }}"
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Role Filter -->
            <div class="flex items-center gap-2 flex-wrap">
                <button @click="roleFilter = 'all'" :class="roleFilter === 'all' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                    class="filter-chip px-4 py-2 text-sm font-medium rounded-lg border">
                    {{ __('All Roles') }}
                </button>
                @foreach($roles as $role)
                <button @click="roleFilter = '{{ $role->slug }}'" :class="roleFilter === '{{ $role->slug }}' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                    class="filter-chip px-4 py-2 text-sm font-medium rounded-lg border">
                    {{ $role->name }}
                </button>
                @endforeach
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                    class="filter-chip px-4 py-2 text-sm font-medium rounded-lg border">
                    {{ __('All') }}
                </button>
                <button @click="statusFilter = 'active'" :class="statusFilter === 'active' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                    class="filter-chip px-4 py-2 text-sm font-medium rounded-lg border">
                    {{ __('Active') }}
                </button>
                <button @click="statusFilter = 'inactive'" :class="statusFilter === 'inactive' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                    class="filter-chip px-4 py-2 text-sm font-medium rounded-lg border">
                    {{ __('Inactive') }}
                </button>
            </div>

            <!-- View Toggle -->
            <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-white shadow-sm' : ''" class="p-2 rounded-md">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button @click="view = 'list'" :class="view === 'list' ? 'bg-white shadow-sm' : ''" class="p-2 rounded-md">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Team Members Grid View -->
    <div x-show="view === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 slide-up slide-up-3">
        @foreach($members as $index => $member)
        <div x-show="filterMember('{{ strtolower($member->name) }}', '{{ strtolower($member->email) }}', '{{ $member->role->slug }}', {{ $member->is_active ? 'true' : 'false' }})"
            
            
            
            class="member-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Card Header with Gradient -->
            <div class="h-2 {{ $member->isOwner() ? 'bg-secondary-400' : ($member->isAdmin() ? 'bg-secondary-500' : ($member->role->slug === 'accounting' ? 'bg-secondary-500' : ($member->role->slug === 'support' ? 'bg-primary-500' : 'bg-gray-400'))) }}"></div>

            <div class="p-5">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-14 h-14 {{ $member->isOwner() ? 'bg-secondary-400' : ($member->isAdmin() ? 'bg-secondary-500' : 'bg-primary-500') }} rounded-2xl flex items-center justify-center font-bold text-white text-xl shadow-lg">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        @if($member->is_active)
                            @if($member->last_login_at && $member->last_login_at->isToday())
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white" title="{{ __('Online Today') }}"></div>
                            @endif
                        @else
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-red-400 rounded-full border-2 border-white" title="{{ __('Inactive') }}"></div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-slate-900 truncate">{{ $member->name }}</h3>
                            @if($member->isOwner())
                            <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20" title="{{ __('Owner') }}">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500 truncate">{{ $member->email }}</p>

                        <div class="flex items-center gap-2 mt-3">
                            <span class="role-badge px-3 py-1 text-xs font-semibold rounded-full cursor-default
                                {{ $member->role->slug === 'owner' ? 'bg-secondary-100 text-amber-700' : '' }}
                                {{ $member->role->slug === 'admin' ? 'bg-secondary-100 text-purple-700' : '' }}
                                {{ $member->role->slug === 'accounting' ? 'bg-secondary-100 text-emerald-700' : '' }}
                                {{ $member->role->slug === 'support' ? 'bg-primary-100 text-blue-700' : '' }}
                                {{ $member->role->slug === 'feedback-qa' ? 'bg-primary-100 text-cyan-700' : '' }}
                            ">
                                {{ $member->role->name }}
                            </span>
                            @if(!$member->password_changed)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">{{ __('Pending') }}</span>
                            @endif
                            @if(!$member->is_active)
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider">{{ __('Last Login') }}</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $member->last_login_at ? $member->last_login_at->diffForHumans() : __('Never') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider">{{ __('Joined') }}</p>
                        <p class="text-sm font-medium text-slate-700 mt-0.5">{{ $member->invited_at ? $member->invited_at->format('M j, Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Footer -->
            @if($currentMember->canManage($member) || $currentMember->isOwner())
            <div class="px-5 py-3 bg-gray-50 border-t border-slate-200 flex items-center justify-between">
                <div class="text-xs text-slate-400">
                    @if($member->invitedByMember)
                    {{ __('Invited by') }} {{ $member->invitedByMember->name }}
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    @if(!$member->password_changed && $currentMember->hasPermission('team.edit'))
                    <form action="{{ route('mindova.team.resend-invitation', $member) }}" method="POST" class="inline">
                        @csrf
                        <x-ui.button as="submit" variant="ghost" size="sm" class="action-btn p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="{{ __('Resend Invitation') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </x-ui.button>
                    </form>
                    @endif

                    @if($currentMember->hasPermission('team.edit'))
                    <a href="{{ route('mindova.team.edit', $member) }}" class="action-btn p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg" title="{{ __('Edit') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    @endif

                    @if($member->is_active && !$member->isOwner())
                    <form action="{{ route('mindova.team.deactivate', $member) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to deactivate this member?') }}')">
                        @csrf
                        <x-ui.button as="submit" variant="ghost" size="sm" class="action-btn p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="{{ __('Deactivate') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </x-ui.button>
                    </form>
                    @elseif(!$member->is_active)
                    <form action="{{ route('mindova.team.activate', $member) }}" method="POST" class="inline">
                        @csrf
                        <x-ui.button as="submit" variant="ghost" size="sm" class="action-btn p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg" title="{{ __('Activate') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-ui.button>
                    </form>
                    @endif

                    @if($currentMember->hasPermission('team.delete') && !$member->isOwner() && $member->id !== $currentMember->id)
                    <form action="{{ route('mindova.team.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to remove this member? This action cannot be undone.') }}')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button as="submit" variant="ghost" size="sm" class="action-btn p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" title="{{ __('Remove') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </x-ui.button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Team Members List View -->
    <div x-show="view === 'list'" x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-3">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Member') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Role') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Last Login') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Joined') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($members as $member)
                    <tr x-show="filterMember('{{ strtolower($member->name) }}', '{{ strtolower($member->email) }}', '{{ $member->role->slug }}', {{ $member->is_active ? 'true' : 'false' }})"
                        class="hover:bg-slate-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $member->isOwner() ? 'bg-secondary-400' : ($member->isAdmin() ? 'bg-secondary-500' : 'bg-primary-500') }} rounded-xl flex items-center justify-center font-bold text-white">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $member->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $member->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $member->role->slug === 'owner' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $member->role->slug === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $member->role->slug === 'accounting' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $member->role->slug === 'support' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $member->role->slug === 'feedback-qa' ? 'bg-cyan-100 text-cyan-700' : '' }}
                            ">
                                {{ $member->role->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($member->is_active)
                                @if(!$member->password_changed)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                    {{ __('Pending Activation') }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    {{ __('Active') }}
                                </span>
                                @endif
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                {{ __('Inactive') }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $member->last_login_at ? $member->last_login_at->diffForHumans() : __('Never') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $member->invited_at ? $member->invited_at->format('M j, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($currentMember->canManage($member) || $currentMember->isOwner())
                            <div class="flex items-center justify-end gap-1">
                                @if($currentMember->hasPermission('team.edit'))
                                <a href="{{ route('mindova.team.edit', $member) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($members->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 slide-up slide-up-3">
        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('No team members yet') }}</h3>
        <p class="text-slate-500 mb-6">{{ __('Start building your team by inviting the first member.') }}</p>
        @if($currentMember->hasPermission('team.create'))
        <x-ui.button as="a" href="{{ route('mindova.team.create') }}" variant="primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            {{ __('Invite First Member') }}
        </x-ui.button>
        @endif
    </div>
    @endif

    <!-- Roles Overview -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-3">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <div class="w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                {{ __('Roles & Permission Levels') }}
            </h3>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($roles as $role)
                <div class="relative p-4 rounded-xl border-2 hover:shadow-md
                    {{ $role->slug === 'owner' ? 'border-amber-200 bg-gray-50' : '' }}
                    {{ $role->slug === 'admin' ? 'border-purple-200 bg-secondary-50' : '' }}
                    {{ $role->slug === 'accounting' ? 'border-emerald-200 bg-gray-50' : '' }}
                    {{ $role->slug === 'support' ? 'border-blue-200 bg-gray-50' : '' }}
                    {{ $role->slug === 'feedback-qa' ? 'border-cyan-200 bg-primary-50' : '' }}
                ">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-900">{{ $role->name }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-white/80 text-slate-500">L{{ $role->level }}</span>
                    </div>
                    <p class="text-xs text-slate-600 line-clamp-2">{{ $role->description }}</p>
                    <div class="mt-3 flex items-center gap-1">
                        <span class="text-xs text-slate-400">{{ $members->where('role_id', $role->id)->count() }} {{ __('members') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function teamManager() {
    return {
        search: '',
        roleFilter: 'all',
        statusFilter: 'all',
        view: 'grid',

        filterMember(name, email, role, isActive) {
            // Search filter
            if (this.search) {
                const searchLower = this.search.toLowerCase();
                if (!name.includes(searchLower) && !email.includes(searchLower)) {
                    return false;
                }
            }

            // Role filter
            if (this.roleFilter !== 'all' && role !== this.roleFilter) {
                return false;
            }

            // Status filter
            if (this.statusFilter === 'active' && !isActive) {
                return false;
            }
            if (this.statusFilter === 'inactive' && isActive) {
                return false;
            }

            return true;
        }
    }
}
</script>
@endpush
@endsection
