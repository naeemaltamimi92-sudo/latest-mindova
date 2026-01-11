@extends('mindova.layouts.app')

@section('title', __('Dashboard'))

@push('styles')
<style>
    /* Premium Animations */
    .slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .slide-up-1 { animation-delay: 0.05s; }
    .slide-up-2 { animation-delay: 0.1s; }
    .slide-up-3 { animation-delay: 0.15s; }
    .slide-up-4 { animation-delay: 0.2s; }
    .slide-up-5 { animation-delay: 0.25s; }
    .slide-up-6 { animation-delay: 0.3s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .float-anim { animation: floatAnim 6s ease-in-out infinite; }
    @keyframes floatAnim {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
    }

    .pulse-ring { animation: pulseRing 2s ease-out infinite; }
    @keyframes pulseRing {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }

    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    }

    .action-card {
        transition: all 0.25s ease;
    }
    .action-card:hover {
        transform: translateX(8px);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
    }

    .activity-item {
        transition: all 0.2s ease;
    }
    .activity-item:hover {
        transform: scale(1.01);
    }

    .grid-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .count-up {
        font-variant-numeric: tabular-nums;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Premium Welcome Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-3xl p-6 lg:p-8 slide-up slide-up-1">
        <!-- Animated Background -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl float-anim"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl float-anim" style="animation-delay: 2s;"></div>
        </div>
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white text-2xl lg:text-3xl font-bold border border-white/30">
                        {{ strtoupper(substr($teamMember->name, 0, 1)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">{{ __('Welcome back') }}, {{ explode(' ', $teamMember->name)[0] }}!</h1>
                    <p class="text-white/70 mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $teamMember->role->name }}
                        </span>
                        @if($teamMember->last_login_at)
                        <span class="text-white/50 text-sm hidden sm:inline">{{ __('Last login') }}: {{ $teamMember->last_login_at->diffForHumans() }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($teamMember->hasPermission('team.create'))
                <a href="{{ route('mindova.team.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    {{ __('Invite Member') }}
                </a>
                @endif
            </div>
        </div>

        <!-- Mini Stats in Header -->
        <div class="relative z-10 mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Team Members') }}</p>
                <p class="text-white text-xl font-bold mt-1 count-up">{{ $stats['team_members'] ?? 0 }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Active Today') }}</p>
                <p class="text-white text-xl font-bold mt-1 count-up">{{ $stats['active_today'] ?? 0 }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Total Users') }}</p>
                <p class="text-white text-xl font-bold mt-1 count-up">{{ number_format($stats['total_users'] ?? 0) }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                <p class="text-white/60 text-xs uppercase tracking-wider">{{ __('Companies') }}</p>
                <p class="text-white text-xl font-bold mt-1 count-up">{{ number_format($stats['companies'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $statCards = [
                ['key' => 'total_users', 'label' => 'Platform Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'gradient' => 'from-blue-500 to-cyan-500', 'bg' => 'bg-blue-50'],
                ['key' => 'volunteers', 'label' => 'Volunteers', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'gradient' => 'from-emerald-500 to-teal-500', 'bg' => 'bg-emerald-50'],
                ['key' => 'companies', 'label' => 'Companies', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'gradient' => 'from-violet-500 to-purple-500', 'bg' => 'bg-violet-50'],
                ['key' => 'opportunities', 'label' => 'Opportunities', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'gradient' => 'from-amber-500 to-orange-500', 'bg' => 'bg-amber-50'],
            ];
        @endphp

        @foreach($statCards as $index => $card)
            @if(isset($stats[$card['key']]))
            <div class="stat-card bg-white rounded-2xl p-5 border border-slate-200 shadow-sm slide-up slide-up-{{ $index + 2 }}">
                <div class="flex items-center justify-between">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $card['gradient'] }} rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="w-16 h-8 {{ $card['bg'] }} rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-6 text-slate-400" viewBox="0 0 48 24">
                            <path fill="none" stroke="currentColor" stroke-width="2" d="M2 20 Q12 12, 24 14 T46 4" opacity="0.5"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold text-slate-900 count-up">{{ number_format($stats[$card['key']]) }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ __($card['label']) }}</p>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-4">
            <div class="p-5 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    {{ __('Quick Actions') }}
                </h2>
            </div>
            <div class="p-4 space-y-2">
                @if($teamMember->hasPermission('team.create'))
                <a href="{{ route('mindova.team.create') }}" class="action-card flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-indigo-100">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900">{{ __('Invite Team Member') }}</p>
                        <p class="text-xs text-slate-500">{{ __('Send invitation with role') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif

                @if($teamMember->hasPermission('team.view'))
                <a href="{{ route('mindova.team.index') }}" class="action-card flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-indigo-100">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900">{{ __('Manage Team') }}</p>
                        <p class="text-xs text-slate-500">{{ __('View and edit members') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif

                @if($teamMember->hasPermission('audit.view'))
                <a href="{{ route('mindova.audit.index') }}" class="action-card flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-indigo-100">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900">{{ __('Audit Logs') }}</p>
                        <p class="text-xs text-slate-500">{{ __('View activity history') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif

                <a href="{{ route('mindova.password.change') }}" class="action-card flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-indigo-100">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900">{{ __('Change Password') }}</p>
                        <p class="text-xs text-slate-500">{{ __('Update your security') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Recent Activity Timeline -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-5">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    {{ __('Recent Activity') }}
                </h2>
                @if($teamMember->hasPermission('audit.view'))
                <a href="{{ route('mindova.audit.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                    {{ __('View All') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif
            </div>

            <div class="p-4">
                @if(count($recentLogs) > 0)
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-200 via-purple-200 to-transparent"></div>

                    <div class="space-y-4">
                        @foreach($recentLogs as $log)
                        <div class="activity-item relative flex items-start gap-4 p-3 rounded-xl hover:bg-slate-50">
                            <!-- Timeline Dot -->
                            <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm
                                @if($log->action_color === 'green') bg-emerald-100 text-emerald-600 ring-2 ring-emerald-50
                                @elseif($log->action_color === 'red') bg-red-100 text-red-600 ring-2 ring-red-50
                                @elseif($log->action_color === 'yellow') bg-amber-100 text-amber-600 ring-2 ring-amber-50
                                @elseif($log->action_color === 'blue') bg-blue-100 text-blue-600 ring-2 ring-blue-50
                                @elseif($log->action_color === 'purple') bg-purple-100 text-purple-600 ring-2 ring-purple-50
                                @else bg-slate-100 text-slate-600 ring-2 ring-slate-50
                                @endif
                            ">
                                @if($log->action === 'team_member.login')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                @elseif($log->action === 'team_member.logout')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                @elseif($log->action === 'team_member.created')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                @elseif(str_contains($log->action, 'password'))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-slate-900">{{ $log->action_label }}</span>
                                    <span class="text-slate-400">•</span>
                                    <span class="text-sm text-slate-500">{{ $log->teamMember?->name ?? 'System' }}</span>
                                </div>
                                <p class="text-sm text-slate-500 mt-0.5 truncate">{{ $log->description ?? '-' }}</p>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="text-xs text-slate-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">{{ __('No recent activity') }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Activity will appear here as team members use the platform') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Team Members Overview & Role Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Team Members Quick View -->
        @if($teamMember->hasPermission('team.view') && isset($teamMembers) && count($teamMembers) > 0)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden slide-up slide-up-5">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">{{ __('Team Members') }}</h2>
                <a href="{{ route('mindova.team.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">{{ __('View All') }}</a>
            </div>
            <div class="p-4">
                <div class="flex flex-wrap gap-3">
                    @foreach($teamMembers->take(8) as $member)
                    <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold
                            {{ $member->isOwner() ? 'bg-gradient-to-br from-amber-400 to-orange-500' : ($member->isAdmin() ? 'bg-gradient-to-br from-purple-500 to-indigo-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500') }}">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $member->name }}</p>
                            <p class="text-xs text-slate-500">{{ $member->role->name }}</p>
                        </div>
                        @if(!$member->is_active)
                        <span class="w-2 h-2 bg-red-400 rounded-full" title="{{ __('Inactive') }}"></span>
                        @elseif($member->last_login_at && $member->last_login_at->isToday())
                        <span class="w-2 h-2 bg-emerald-400 rounded-full" title="{{ __('Active Today') }}"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @if($teamMembers->count() > 8)
                <p class="text-sm text-slate-500 mt-3 text-center">{{ __('and :count more...', ['count' => $teamMembers->count() - 8]) }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Your Permissions -->
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-2xl p-6 text-white slide-up slide-up-6">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl">{{ $teamMember->role->name }}</h3>
                    <p class="text-white/70 text-sm mt-1">{{ $teamMember->role->description ?? __('Team member role with assigned permissions') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                @php
                    $permissionGroups = [
                        'team' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Team'],
                        'users' => ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Users'],
                        'companies' => ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5', 'label' => 'Companies'],
                        'audit' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Audit'],
                        'settings' => ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Settings'],
                        'dashboard' => ['icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', 'label' => 'Dashboard'],
                    ];
                @endphp

                @foreach($permissionGroups as $group => $data)
                    @if($teamMember->hasAnyPermission([$group . '.view', $group . '.create', $group . '.edit', $group . '.delete']))
                    <div class="flex items-center gap-2 px-3 py-2 bg-white/10 rounded-lg">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm text-white/90">{{ __($data['label']) }}</span>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
