@extends('layouts.app')

@section('title', ($company->company_name ?? $company->user->name) . ' - Company Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50/30">
    <!-- Premium Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-cyan-900 py-12 mb-8 rounded-b-[3rem] shadow-2xl mx-4 sm:mx-6 lg:mx-8">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-blue-500/20 via-transparent to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-cyan-500/20 via-transparent to-transparent"></div>
            <div class="floating-element absolute top-10 -left-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
            <div class="floating-element absolute bottom-10 right-10 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 sm:px-8">
            <!-- Breadcrumb -->
            <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white font-medium mb-6 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Companies
            </a>

            <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                <!-- Company Logo -->
                <div class="relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-400 via-cyan-400 to-teal-400 rounded-3xl blur opacity-40 group-hover:opacity-60 transition duration-500"></div>
                    @if($company->logo_path)
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->company_name }}" class="relative h-28 w-28 lg:h-32 lg:w-32 rounded-2xl object-cover shadow-2xl ring-4 ring-white/20">
                    @else
                    <div class="relative h-28 w-28 lg:h-32 lg:w-32 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-2xl ring-4 ring-white/20">
                        <span class="text-white font-black text-4xl">{{ substr($company->company_name ?? $company->user->name, 0, 2) }}</span>
                    </div>
                    @endif
                </div>

                <!-- Company Info -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-xs font-semibold text-white/80">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                            </span>
                            Admin View
                        </span>
                        @if($company->industry)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-500/20 backdrop-blur-md border border-blue-400/30 rounded-full text-sm font-semibold text-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $company->industry }}
                        </span>
                        @endif
                        <span class="px-3 py-1.5 bg-slate-500/20 backdrop-blur-md border border-slate-400/30 rounded-full text-sm font-medium text-slate-300">
                            ID: #{{ $company->id }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
                        {{ $company->company_name ?? $company->user->name }}
                    </h1>

                    <p class="text-blue-200/80 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $company->user->email }}
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('companies.show', $company->id) }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white text-blue-600 font-bold px-6 py-3 rounded-xl hover:bg-blue-50 transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View Public Profile
                    </a>
                    @if($company->user->email)
                    <a href="mailto:{{ $company->user->email }}" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 -mt-4 relative z-10">
            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900">{{ $stats['total_challenges'] }}</p>
                        <p class="text-sm text-slate-500">Total Challenges</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-emerald-600">{{ $stats['active_challenges'] }}</p>
                        <p class="text-sm text-slate-500">Active Now</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-violet-600">{{ $stats['completed_challenges'] }}</p>
                        <p class="text-sm text-slate-500">Completed</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-lg border border-slate-100 hover:shadow-xl transition-all hover:-translate-y-1 group">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-amber-600">{{ $stats['certificates_issued'] }}</p>
                        <p class="text-sm text-slate-500">Certificates</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Company Information Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-5">
                        <h2 class="text-lg font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            Company Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Company Name</p>
                            <p class="text-base font-semibold text-slate-900">{{ $company->company_name ?? 'Not set' }}</p>
                        </div>
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Contact Person</p>
                            <p class="text-base font-semibold text-slate-900">{{ $company->user->name }}</p>
                        </div>
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email Address</p>
                            <a href="mailto:{{ $company->user->email }}" class="text-base font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-2">
                                {{ $company->user->email }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @if($company->website)
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Website</p>
                            <a href="{{ $company->website }}" target="_blank" class="text-base font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-2">
                                {{ $company->website }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                        @if($company->phone)
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</p>
                            <p class="text-base font-semibold text-slate-900">{{ $company->phone }}</p>
                        </div>
                        @endif
                        @if($company->address)
                        <div class="group">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Address</p>
                            <p class="text-base font-semibold text-slate-900">{{ $company->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Member Info Card -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">Member Since</h3>
                            <p class="text-slate-300">{{ $company->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 mt-4">
                        <p class="text-sm text-slate-300">
                            Active for <span class="font-bold text-white">{{ $company->created_at->diffForHumans(['parts' => 2, 'join' => ', ']) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Quick Links Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900">Quick Links</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="font-medium text-slate-700 group-hover:text-slate-900">All Companies</span>
                        </a>
                        <a href="{{ route('admin.challenges.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="font-medium text-slate-700 group-hover:text-slate-900">All Challenges</span>
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span class="font-medium text-slate-700 group-hover:text-slate-900">Admin Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Challenges List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            All Challenges
                        </h2>
                        <span class="bg-white/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-xl">
                            {{ $company->challenges->count() }} total
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($company->challenges as $index => $challenge)
                        <a href="{{ route('admin.challenges.show', $challenge) }}" class="block p-6 hover:bg-slate-50 transition-all group animate-slide-in-up" style="animation-delay: {{ $index * 0.05 }}s;">
                            <div class="flex items-start justify-between gap-6">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold
                                            @if($challenge->status === 'active') bg-emerald-100 text-emerald-700 border border-emerald-200
                                            @elseif($challenge->status === 'completed') bg-blue-100 text-blue-700 border border-blue-200
                                            @elseif($challenge->status === 'pending') bg-amber-100 text-amber-700 border border-amber-200
                                            @elseif($challenge->status === 'analyzing') bg-violet-100 text-violet-700 border border-violet-200
                                            @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                            @if($challenge->status === 'active')
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            @endif
                                            {{ ucfirst($challenge->status) }}
                                        </span>
                                        @if($challenge->challenge_type === 'team_execution')
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-bold">
                                            Team Execution
                                        </span>
                                        @else
                                        <span class="px-3 py-1 bg-violet-100 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold">
                                            Community
                                        </span>
                                        @endif
                                    </div>

                                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-indigo-600 transition-colors mb-2">
                                        {{ $challenge->title }}
                                    </h3>

                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                        {{ Str::limit($challenge->description ?? $challenge->refined_brief, 150) }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $challenge->created_at->diffForHumans() }}
                                        </span>
                                        @if($challenge->complexity_level)
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                            </svg>
                                            Level {{ $challenge->complexity_level }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-3">
                                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 px-4 py-3 rounded-xl border border-slate-200 text-center min-w-[80px]">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            <span class="text-lg font-bold text-slate-700">{{ $challenge->workstreams->sum(fn($ws) => $ws->tasks->count()) }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">Tasks</p>
                                    </div>

                                    <div class="flex items-center gap-2 text-indigo-600 font-semibold text-sm group-hover:translate-x-1 transition-transform">
                                        View
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center mx-auto mb-6">
                                <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">No Challenges Yet</h3>
                            <p class="text-slate-500">This company hasn't submitted any challenges.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
