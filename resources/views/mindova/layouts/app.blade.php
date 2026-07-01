<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Mindova Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EDE9FD', 100: '#D4CBFA', 200: '#B5A6F6', 300: '#9681F2',
                            400: '#775FEE', 500: '#5A3DEB', 600: '#4B32C9', 700: '#3C28A7',
                            800: '#2D1E85', 900: '#1E1463', DEFAULT: '#5A3DEB',
                        },
                        secondary: {
                            50: '#F9FAFB', 100: '#F3F4F6', 200: '#E5E7EB', 300: '#D1D5DB',
                            400: '#9CA3AF', 500: '#6B7280', 600: '#4B5563', 700: '#374151',
                            800: '#1F2937', 900: '#111827', DEFAULT: '#6B7280',
                        },
                    },
                },
            },
        };
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>
        html, body { font-family: 'Inter', sans-serif; height: 100%; overscroll-behavior: none; }
        [x-cloak] { display: none !important; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .nav-item-active { position: relative; }
        .nav-item-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: linear-gradient(180deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 0 4px 4px 0;
        }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
    </style>
    @stack('styles')
</head>
<body class="h-screen overflow-hidden bg-slate-100">
    <div
        x-data="{
            sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
            mobileMenuOpen: false,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebarOpen', this.sidebarOpen);
            }
        }"
        @toggle-sidebar.window="toggleSidebar()"
        class="flex h-screen overflow-hidden"
    >
        {{-- ══════════════ Desktop Sidebar ══════════════ --}}
        <aside
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="hidden lg:flex lg:flex-col h-screen flex-shrink-0 sidebar-gradient text-white transition-all duration-200"
        >
            {{-- Logo / Brand --}}
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10 flex-shrink-0">
                <a href="{{ route('mindova.dashboard') }}" class="flex items-center gap-3 min-w-0">
                    <svg class="h-8 w-8 flex-shrink-0" viewBox="0 0 50 50" fill="none" aria-hidden="true">
                        <path d="M 8,44 L 8,8 L 25,24 L 42,8 L 42,44" stroke="#ffffff" stroke-width="5.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="8"  cy="8"  r="6.5" fill="#ffffff"/>
                        <circle cx="25" cy="24" r="5"   fill="rgba(255,255,255,0.75)"/>
                        <circle cx="42" cy="8"  r="6.5" fill="#ffffff"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak class="font-bold text-lg text-white truncate">Mindova</span>
                </a>
                <button @click="toggleSidebar()" class="p-1.5 rounded-lg hover:bg-white/10 flex-shrink-0" title="Toggle Sidebar (Ctrl+B)">
                    <svg class="w-5 h-5 transition-transform" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation (scrolls independently if it ever overflows) --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin p-4 space-y-1">
                <div x-show="sidebarOpen" x-cloak class="px-3 pt-2 pb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Main Menu') }}</span>
                </div>

                <a href="{{ route('mindova.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.dashboard') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>{{ __('Dashboard') }}</span>
                </a>

                @if($currentTeamMember->hasPermission('team.view'))
                <a href="{{ route('mindova.team.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.team.*') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>{{ __('Team Members') }}</span>
                </a>
                @endif

                @if($currentTeamMember->hasPermission('audit.view'))
                <a href="{{ route('mindova.audit.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.audit.*') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>{{ __('Audit Logs') }}</span>
                </a>
                @endif

                <div class="border-t border-white/10 my-4"></div>

                <div x-show="sidebarOpen" x-cloak class="px-3 pt-2 pb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Quick Links') }}</span>
                </div>

                <a href="{{ url('/') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white group"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>{{ __('Main Site') }}</span>
                </a>

                @if($currentTeamMember->isOwner() || $currentTeamMember->isAdmin())
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white group"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" x-cloak>{{ __('Admin Settings') }}</span>
                </a>
                @endif
            </nav>

            {{-- User menu (pinned to bottom of sidebar) --}}
            <div class="p-4 border-t border-white/10 bg-black/20 flex-shrink-0">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-white/10 text-left">
                        <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center font-bold text-white shadow-lg shadow-emerald-500/30 flex-shrink-0">
                            {{ strtoupper(substr($currentTeamMember->name, 0, 1)) }}
                        </div>
                        <div x-show="sidebarOpen" x-cloak class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $currentTeamMember->name }}</p>
                            <p class="text-xs text-slate-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                {{ $currentTeamMember->role->name }}
                            </p>
                        </div>
                        <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute bg-slate-800 rounded-xl shadow-xl border border-white/10 overflow-hidden min-w-[200px] z-50"
                         :class="sidebarOpen ? 'bottom-full mb-2 left-0 right-0' : 'left-full ml-2 bottom-0'">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-semibold text-white">{{ $currentTeamMember->name }}</p>
                            <p class="text-xs text-slate-400">{{ $currentTeamMember->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('mindova.password.change') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                {{ __('Change Password') }}
                            </a>
                        </div>
                        <div class="border-t border-white/10 py-1">
                            <form action="{{ route('mindova.logout') }}" method="POST">
                                @csrf
                                <x-ui.button as="submit" variant="ghost" fullWidth class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ __('Logout') }}
                                </x-ui.button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ══════════════ Mobile Sidebar (overlay, fixed above the layout) ══════════════ --}}
        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="lg:hidden fixed inset-y-0 left-0 z-50 w-72 h-screen sidebar-gradient text-white flex flex-col">
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10 flex-shrink-0">
                <x-brand.logo variant="white" />
                <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-4 py-4 border-b border-white/10 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-secondary-500 rounded-full flex items-center justify-center font-bold text-white text-lg shadow-lg">
                        {{ strtoupper(substr($currentTeamMember->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ $currentTeamMember->name }}</p>
                        <p class="text-xs text-slate-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                            {{ $currentTeamMember->role->name }}
                        </p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto scrollbar-thin p-4 space-y-1">
                <a href="{{ route('mindova.dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.dashboard') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
                @if($currentTeamMember->hasPermission('team.view'))
                <a href="{{ route('mindova.team.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.team.*') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    {{ __('Team Members') }}
                </a>
                @endif
                @if($currentTeamMember->hasPermission('audit.view'))
                <a href="{{ route('mindova.audit.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.audit.*') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('Audit Logs') }}
                </a>
                @endif

                <div class="border-t border-white/10 my-4"></div>

                <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    {{ __('Main Site') }}
                </a>

                <a href="{{ route('mindova.password.change') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    {{ __('Change Password') }}
                </a>
            </nav>

            <div class="p-4 border-t border-white/10 flex-shrink-0">
                <form action="{{ route('mindova.logout') }}" method="POST">
                    @csrf
                    <x-ui.button as="submit" variant="destructive" fullWidth class="flex items-center justify-center gap-2 px-4 py-3 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Logout') }}
                    </x-ui.button>
                </form>
            </div>
        </div>

        {{-- ══════════════ Main Column: header + scrollable content ══════════════ --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">

            {{-- Header (fixed height, never scrolls) --}}
            <header class="flex items-center justify-between h-16 px-4 lg:px-8 bg-white border-b border-slate-200 flex-shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Mobile menu trigger --}}
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 flex-shrink-0">
                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Desktop breadcrumb --}}
                    <div class="hidden lg:flex items-center gap-2 text-sm min-w-0">
                        <a href="{{ route('mindova.dashboard') }}" class="text-slate-500 hover:text-slate-700 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>
                        <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium text-slate-800 truncate">@yield('title', 'Dashboard')</span>
                    </div>

                    {{-- Mobile page title --}}
                    <span class="lg:hidden font-bold text-slate-800 truncate">@yield('title', 'Dashboard')</span>
                </div>

                <div class="flex items-center gap-2 lg:gap-4 flex-shrink-0">
                    @if($currentTeamMember->hasPermission('team.invite'))
                    <x-ui.button as="a" href="{{ route('mindova.team.create') }}" variant="primary" size="sm" class="hidden sm:inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        {{ __('Invite Member') }}
                    </x-ui.button>
                    @endif

                    <div class="hidden lg:block text-sm text-slate-500 tabular-nums"
                         x-data="{ time: '' }"
                         x-init="time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }); setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) }, 30000)">
                        <span x-text="time"></span>
                    </div>
                </div>
            </header>

            {{-- Scrollable content region (the ONLY element that scrolls) --}}
            <main class="flex-1 overflow-y-auto flex flex-col">
                <div class="flex-1 p-6 lg:p-8">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-cloak
                         class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-cloak
                         class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endif

                    @yield('content')
                </div>

                {{-- Footer scrolls with content, but sits flush at the bottom on short pages --}}
                <footer class="px-6 lg:px-8 py-4 border-t border-slate-200 bg-white flex-shrink-0">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-500">
                        <p>&copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}</p>
                        <p class="flex items-center gap-1">
                            {{ __('Logged in as') }} <span class="font-medium text-slate-700">{{ $currentTeamMember->name }}</span>
                        </p>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                window.dispatchEvent(new CustomEvent('toggle-sidebar'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
