<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $textDirection ?? 'ltr' }}" class="{{ ($darkModeEnabled ?? false) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/brand/favicon.svg') }}" type="image/svg+xml">
    <title>Admin — @yield('title', 'Dashboard') | {{ $siteName ?? config('app.name', 'Mindova') }}</title>
    <script>(function(){try{var t=localStorage.getItem('mindova-theme');document.documentElement.classList.toggle('dark',t==='dark');}catch(e){}})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand-primary: {{ $brandPrimaryColor ?? '#5A3DEB' }};
            --brand-secondary: {{ $brandSecondaryColor ?? '#8b5cf6' }};
            --sidebar-w: 260px;
            --topbar-h: 64px;
            --aurora: linear-gradient(135deg, #775FEE 0%, #5A3DEB 50%, #4338CA 100%);
        }
        [x-cloak] { display: none !important; }

        /* ── Sidebar ── */
        #admin-sidebar {
            width: var(--sidebar-w);
            background: var(--aurora);
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            will-change: transform;
        }
        #admin-sidebar.collapsed { transform: translateX(-100%); }

        /* ── Topbar ── */
        #admin-topbar { height: var(--topbar-h); }

        /* ── Main content offset ── */
        #admin-main {
            margin-left: var(--sidebar-w);
            transition: margin-left 0.3s cubic-bezier(.4,0,.2,1);
        }
        #admin-main.full { margin-left: 0; }

        @media (max-width: 1023px) {
            #admin-main { margin-left: 0 !important; }
            #admin-sidebar { position: fixed; z-index: 40; top: 0; bottom: 0; left: 0; }
            #admin-sidebar.collapsed { transform: translateX(-100%); }
            #admin-sidebar:not(.collapsed) { transform: translateX(0); }
        }

        /* ── Nav items ── */
        .admin-nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; border-radius: 12px;
            color: rgba(255,255,255,0.75);
            font-weight: 600; font-size: 0.875rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .admin-nav-item:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            transform: translateX(3px);
        }
        .admin-nav-item.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .admin-nav-item .nav-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.15);
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .admin-nav-item.active .nav-icon,
        .admin-nav-item:hover .nav-icon {
            background: rgba(255,255,255,0.25);
        }

        /* ── Stat cards ── */
        .adm-stat {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            transition: all 0.25s ease;
        }
        .adm-stat:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(90,61,235,0.12); }

        /* ── Glass badge ── */
        .glass-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
        }

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 39;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.active { display: block; }

        /* Dark mode */
        html.dark .adm-stat { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        html.dark #admin-topbar { background: #0f172a; border-color: #1e293b; }
        html.dark #admin-main > div { background: #0f172a; }

        /* Entrance animation — pure CSS, runs automatically on paint.
           Content must NEVER depend on JS to become visible: if any script
           on the page throws (ad blocker, extension conflict, race
           condition), a JS-gated opacity:0 pattern leaves the entire
           content area permanently blank while its flex/min-h containers
           still reserve full viewport height, producing a large empty gap. */
        @keyframes adm-fade-in {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .adm-reveal {
            animation: adm-fade-in 0.45s cubic-bezier(.4,0,.2,1) both;
        }
        @media (prefers-reduced-motion: reduce) {
            .adm-reveal { animation: none; }
        }
    </style>
    @if(!empty($customCss ?? '')) <style>{{ $customCss }}</style> @endif
    @stack('styles')
    @auth
    <script>
        @if(session('api_token'))
        localStorage.setItem('api_token', '{{ session('api_token') }}');
        @endif
    </script>
    @endauth
</head>
<body class="bg-gray-50 dark:bg-slate-900 font-sans antialiased" x-data="adminLayout()">

{{-- Mobile overlay --}}
<div id="sidebar-overlay" :class="{ 'active': sidebarOpen }" @click="sidebarOpen = false"></div>

{{-- ═══ SIDEBAR ═══ --}}
<aside id="admin-sidebar" :class="{ 'collapsed': !sidebarOpen }" class="fixed top-0 left-0 h-full z-40 flex flex-col lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div>
            <p class="text-white font-black text-base leading-none">Mindova</p>
            <p class="text-white/60 text-xs mt-0.5">Admin Panel</p>
        </div>
    </div>

    {{-- Admin user --}}
    <div class="flex items-center gap-3 mx-4 mt-4 mb-2 px-3 py-3 rounded-xl bg-white/10">
        <div class="h-8 w-8 rounded-full bg-white/25 flex items-center justify-center flex-shrink-0">
            <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
        </div>
        <div class="min-w-0">
            <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
            <p class="text-white/60 text-xs">{{ __('Platform Owner') }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-3 space-y-1 overflow-y-auto">
        <p class="text-white/40 text-xs font-bold uppercase tracking-wider px-2 mb-2">{{ __('Main') }}</p>

        <a href="{{ route('admin.dashboard') }}"
           class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </span>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('admin.challenges.index') }}"
           class="admin-nav-item {{ request()->routeIs('admin.challenges*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </span>
            {{ __('Challenges') }}
        </a>

        <a href="{{ route('admin.feedback.index') }}"
           class="admin-nav-item {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </span>
            {{ __('Feedback & Ideas') }}
        </a>

        <a href="{{ route('admin.volunteers.index') }}"
           class="admin-nav-item {{ request()->routeIs('admin.volunteers*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            {{ __('Contributors') }}
        </a>

        <a href="{{ route('admin.companies.index') }}"
           class="admin-nav-item {{ request()->routeIs('admin.companies*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </span>
            {{ __('Companies') }}
        </a>

        <a href="{{ route('admin.challenges.analytics') }}"
           class="admin-nav-item {{ request()->routeIs('admin.challenges.analytics') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </span>
            {{ __('Analytics') }}
        </a>

        <div class="pt-3">
            <p class="text-white/40 text-xs font-bold uppercase tracking-wider px-2 mb-2">{{ __('System') }}</p>
        </div>

        <a href="{{ route('admin.settings.index') }}"
           class="admin-nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            {{ __('Settings') }}
        </a>

        <a href="{{ route('admin.challenges.export') }}"
           class="admin-nav-item">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
            {{ __('Export Data') }}
        </a>
    </nav>

    {{-- Bottom links --}}
    <div class="px-4 py-4 border-t border-white/10 space-y-1">
        <a href="{{ route('dashboard') }}" class="admin-nav-item">
            <span class="nav-icon">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </span>
            {{ __('View Platform') }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-nav-item w-full text-left">
                <span class="nav-icon">
                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </span>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

{{-- ═══ MAIN AREA ═══ --}}
<div id="admin-main" class="min-h-screen flex flex-col" :class="{ 'full': !sidebarOpen && window.innerWidth >= 1024 }">

    {{-- Topbar --}}
    <header id="admin-topbar" class="sticky top-0 z-30 bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-4 px-4 lg:px-6">
        {{-- Hamburger --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="h-9 w-9 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumb / title --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-base font-bold text-gray-900 dark:text-white truncate">@yield('page-title', __('Dashboard'))</h1>
            <p class="text-xs text-gray-400 hidden sm:block">@yield('page-subtitle', now()->format('l, F j, Y'))</p>
        </div>

        {{-- Right actions --}}
        <div class="flex items-center gap-2">
            {{-- Dark mode toggle --}}
            <button onclick="toggleDarkMode()" class="h-9 w-9 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition" title="{{ __('Toggle dark mode') }}">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            {{-- Notifications --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative h-9 w-9 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(($unreadNotificationsCount ?? 0) > 0)
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
                <div x-show="open" x-cloak @click.outside="open = false"
                     class="absolute right-0 top-11 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 z-50 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ __('Notifications') }}</p>
                        @if(($unreadNotificationsCount ?? 0) > 0)
                        <form method="POST" action="{{ route('admin.notifications.markRead') }}">
                            @csrf
                            <button type="submit" class="text-xs text-indigo-600 font-semibold hover:underline">{{ __('Mark all read') }}</button>
                        </form>
                        @endif
                    </div>
                    @if(isset($notifications) && $notifications->count())
                    <div class="divide-y divide-gray-50 dark:divide-slate-700 max-h-64 overflow-y-auto">
                        @foreach($notifications->take(5) as $notif)
                        <div class="px-4 py-3 {{ !$notif->read_at ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $notif->data['message'] ?? __('New notification') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-4 py-8 text-center text-sm text-gray-400">{{ __('No notifications') }}</div>
                    @endif
                </div>
            </div>

            {{-- User avatar / logout menu --}}
            <x-ui.dropdown-menu align="right" width="48">
                <x-slot:trigger>
                    <button type="button" class="h-8 w-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xs">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                    </button>
                </x-slot:trigger>

                <x-ui.dropdown-menu-item href="{{ route('profile.edit') }}">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </x-ui.dropdown-menu-item>
                <x-ui.dropdown-menu-separator />
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.dropdown-menu-item as="submit" danger>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Logout') }}
                    </x-ui.dropdown-menu-item>
                </form>
            </x-ui.dropdown-menu>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success') || session('error'))
    <div class="px-4 lg:px-6 pt-4">
        @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
    </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 p-4 lg:p-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-4 lg:px-6 py-3 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between text-xs text-gray-400">
        <span>Mindova Admin &copy; {{ date('Y') }}</span>
        <span>Laravel v{{ app()->version() }} &bull; PHP v{{ phpversion() }}</span>
    </footer>
</div>

<script>
function adminLayout() {
    return {
        sidebarOpen: window.innerWidth >= 1024,
        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) this.sidebarOpen = true;
            });
        }
    }
}

function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('mindova-theme', isDark ? 'dark' : 'light');
}

</script>

{{-- Mindy - Mindova's AI Guide (real conversational assistant) --}}
@include('components.mindy-chat-widget')

@stack('scripts')
</body>
</html>
