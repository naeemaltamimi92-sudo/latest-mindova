<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Mindova Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
    <style>
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
</head>
<body class="bg-slate-100 min-h-screen">
    <div x-data="{
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        mobileMenuOpen: false,
        userMenuOpen: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        }
    }" class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="fixed inset-y-0 left-0 z-50 sidebar-gradient text-white hidden lg:flex lg:flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
                <a href="{{ route('mindova.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30">M</div>
                    <span x-show="sidebarOpen" class="font-bold text-lg text-white">Mindova</span>
                </a>
                <button @click="toggleSidebar()" class="p-1.5 rounded-lg hover:bg-white/10" title="Toggle Sidebar (Ctrl+B)">
                    <svg class="w-5 h-5" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto scrollbar-thin">
                <!-- Section Title -->
                <div x-show="sidebarOpen" class="px-3 pt-2 pb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Main Menu') }}</span>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('mindova.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.dashboard') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarOpen">{{ __('Dashboard') }}</span>
                </a>

                @if($currentTeamMember->hasPermission('team.view'))
                <!-- Team Members -->
                <a href="{{ route('mindova.team.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.team.*') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">{{ __('Team Members') }}</span>
                </a>
                @endif

                @if($currentTeamMember->hasPermission('audit.view'))
                <!-- Audit Logs -->
                <a href="{{ route('mindova.audit.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl group {{ request()->routeIs('mindova.audit.*') ? 'bg-primary-500 text-white shadow-lg shadow-blue-500/25 nav-item-active' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="sidebarOpen">{{ __('Audit Logs') }}</span>
                </a>
                @endif

                <!-- Divider -->
                <div class="border-t border-white/10 my-4"></div>

                <!-- Section Title -->
                <div x-show="sidebarOpen" class="px-3 pt-2 pb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ __('Quick Links') }}</span>
                </div>

                <!-- Back to Main Site -->
                <a href="{{ url('/') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white group"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <span x-show="sidebarOpen">{{ __('Main Site') }}</span>
                </a>

                <!-- Settings (if permission) -->
                @if($currentTeamMember->isOwner() || $currentTeamMember->isAdmin())
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-white/10 hover:text-white group"
                   :class="!sidebarOpen ? 'justify-center' : ''">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">{{ __('Admin Settings') }}</span>
                </a>
                @endif
            </nav>

            <!-- User Menu -->
            <div class="p-4 border-t border-white/10 bg-black/20">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-white/10 text-left">
                        <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center font-bold text-white shadow-lg shadow-emerald-500/30 flex-shrink-0">
                            {{ strtoupper(substr($currentTeamMember->name, 0, 1)) }}
                        </div>
                        <div x-show="sidebarOpen" class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ $currentTeamMember->name }}</p>
                            <p class="text-xs text-slate-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                {{ $currentTeamMember->role->name }}
                            </p>
                        </div>
                        <svg x-show="sidebarOpen" class="w-4 h-4 text-slate-400" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-cloak @click.away="open = false"
                         
                         
                         
                         :class="sidebarOpen ? 'bottom-full mb-2 left-0 right-0' : 'left-full ml-2 bottom-0'"
                         class="absolute bg-slate-800 rounded-xl shadow-xl border border-white/10 overflow-hidden min-w-[200px] z-50">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-semibold text-white">{{ $currentTeamMember->name }}</p>
                            <p class="text-xs text-slate-400">{{ $currentTeamMember->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('mindova.password.change') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                {{ __('Change Password') }}
                            </a>
                        </div>
                        <div class="border-t border-white/10 py-1">
                            <form action="{{ route('mindova.logout') }}" method="POST">
                                @csrf
                                <x-ui.button as="submit" variant="ghost" fullWidth class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Logout') }}
                                </x-ui.button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-slate-900 text-white h-16 flex items-center justify-between px-4 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center font-bold text-lg shadow-lg">M</div>
                <span class="font-bold text-lg">Mindova</span>
            </div>
            <div class="flex items-center gap-2">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm"></div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak
             
             
             
             
             
             
             class="lg:hidden fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-white flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center font-bold text-lg shadow-lg">M</div>
                    <span class="font-bold text-lg">Mindova</span>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- User Info -->
            <div class="px-4 py-4 border-b border-white/10">
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

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('mindova.dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.dashboard') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
                @if($currentTeamMember->hasPermission('team.view'))
                <a href="{{ route('mindova.team.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.team.*') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    {{ __('Team Members') }}
                </a>
                @endif
                @if($currentTeamMember->hasPermission('audit.view'))
                <a href="{{ route('mindova.audit.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-xl {{ request()->routeIs('mindova.audit.*') ? 'bg-primary-500 text-white' : 'text-slate-300 hover:bg-white/10' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('Audit Logs') }}
                </a>
                @endif

                <div class="border-t border-white/10 my-4"></div>

                <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    {{ __('Main Site') }}
                </a>

                <a href="{{ route('mindova.password.change') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    {{ __('Change Password') }}
                </a>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-white/10">
                <form action="{{ route('mindova.logout') }}" method="POST">
                    @csrf
                    <x-ui.button as="submit" variant="destructive" fullWidth class="flex items-center justify-center gap-2 px-4 py-3 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Logout') }}
                    </x-ui.button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <main :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'" class="flex-1 pt-16 lg:pt-0 min-h-screen">
            <!-- Top Header Bar (Desktop) -->
            <header class="hidden lg:flex items-center justify-between h-16 px-8 bg-white border-b border-slate-200 sticky top-0 z-30">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm">
                    <a href="{{ route('mindova.dashboard') }}" class="text-slate-500 hover:text-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="font-medium text-slate-800">@yield('title', 'Dashboard')</span>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- Quick Actions -->
                    @if($currentTeamMember->hasPermission('team.invite'))
                    <x-ui.button as="a" href="{{ route('mindova.team.create') }}" variant="primary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        {{ __('Invite Member') }}
                    </x-ui.button>
                    @endif

                    <!-- Current Time -->
                    <div class="text-sm text-slate-500" x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) }, 1000)">
                        <span x-text="time"></span>
                    </div>
                </div>
            </header>

            <div class="p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     
                     
                     
                     class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show"
                     
                     
                     
                     class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="px-6 lg:px-8 py-4 border-t border-slate-200 bg-white mt-auto">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} Mindova. {{ __('All rights reserved.') }}</p>
                    <p class="flex items-center gap-1">
                        {{ __('Logged in as') }} <span class="font-medium text-slate-700">{{ $currentTeamMember->name }}</span>
                    </p>
                </div>
            </footer>
        </main>
    </div>

    <!-- Keyboard Shortcuts -->
    <script>
        document.addEventListener('keydown', function(e) {
            // Ctrl+B to toggle sidebar
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                window.dispatchEvent(new CustomEvent('toggle-sidebar'));
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
