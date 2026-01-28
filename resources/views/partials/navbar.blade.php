{{--
    Unified Navbar Partial
    Clean minimal design for consistent navigation
--}}

<x-ui.navbar>
    {{-- Logo and primary navigation --}}
    <div class="flex">
        {{-- Brand Logo --}}
        <div class="flex-shrink-0 flex items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="h-9 w-9">
                <span class="text-xl font-bold text-gray-900">Mindova</span>
            </a>
        </div>

        {{-- Desktop Navigation Links --}}
        @auth
        <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
            @if(auth()->user()->isAdmin())
                {{-- Admin Navigation --}}
                <x-ui.navbar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('admin.challenges.index') }}" :active="request()->routeIs('admin.challenges.*')">
                    {{ __('Challenges') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('admin.companies.index') }}" :active="request()->routeIs('admin.companies.*')">
                    {{ __('Companies') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('admin.volunteers.index') }}" :active="request()->routeIs('admin.volunteers.*')">
                    {{ __('Contributors') }}
                </x-ui.navbar-link>
            @elseif(auth()->user()->isVolunteer())
                {{-- Contributor Navigation --}}
                <x-ui.navbar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('assignments.my') }}" :active="request()->routeIs('assignments.*')">
                    {{ __('My Tasks') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('teams.my') }}" :active="request()->routeIs('teams.*')">
                    {{ __('My Teams') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('community.index') }}" :active="request()->routeIs('community.*') && !request()->routeIs('volunteer.challenges.*')">
                    {{ __('Community') }}
                </x-ui.navbar-link>
                @if(auth()->user()->volunteer && \App\Models\Challenge::where('volunteer_id', auth()->user()->volunteer->id)->where('source_type', 'volunteer')->exists())
                <x-ui.navbar-link href="{{ route('volunteer.challenges.index') }}" :active="request()->routeIs('volunteer.challenges.*')">
                    {{ __('My Challenges') }}
                </x-ui.navbar-link>
                @endif
            @elseif(auth()->user()->isCompany())
                {{-- Company Navigation --}}
                <x-ui.navbar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('challenges.index') }}" :active="request()->routeIs('challenges.*') && !request()->routeIs('community.*')">
                    {{ __('My Challenges') }}
                </x-ui.navbar-link>
                <x-ui.navbar-link href="{{ route('company.submissions.index') }}" :active="request()->routeIs('company.submissions.*')">
                    {{ __('Work Submissions') }}
                </x-ui.navbar-link>
            @endif
        </div>
        @else
        {{-- Guest Navigation --}}
        <div class="hidden sm:ml-6 sm:flex sm:space-x-6">
            <x-ui.navbar-link href="{{ route('how-it-works') }}" :active="request()->routeIs('how-it-works')">
                {{ __('How It Works') }}
            </x-ui.navbar-link>
            <x-ui.navbar-link href="{{ route('success-stories') }}" :active="request()->routeIs('success-stories')">
                {{ __('Success Stories') }}
            </x-ui.navbar-link>
            <x-ui.navbar-link href="{{ route('help') }}" :active="request()->routeIs('help')">
                {{ __('Help') }}
            </x-ui.navbar-link>
        </div>
        @endauth
    </div>

    {{-- Right side navigation --}}
    <div class="flex items-center gap-4">
        @auth
        {{-- Notifications Dropdown --}}
        <div class="relative" x-data="notificationDropdown()">
            <button @click="toggleNotifications" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full"></span>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-80 bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden z-50" style="display: none;">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('Notifications') }}</h3>
                    <button @click="markAllRead" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('Mark all as read') }}
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div :class="{'bg-primary-50': !notification.is_read}" class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer" @click="handleNotificationClick(notification)">
                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                            <p class="text-xs text-gray-600 mt-0.5" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="timeAgo(notification.created_at)"></p>
                        </div>
                    </template>
                    <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
                        {{ __('No notifications') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- User Dropdown --}}
        <x-ui.dropdown-menu align="right" width="48">
            <x-slot:trigger>
                <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-lg">
                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                    <span class="sm:hidden">{{ Str::limit(auth()->user()->name, 8) }}</span>
                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
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

        {{-- Mobile Menu Button --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @else
        {{-- Guest Actions --}}
        <div class="hidden sm:flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2">
                {{ __('Sign In') }}
            </a>
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600">
                {{ __('Get Started') }}
            </a>
        </div>

        {{-- Mobile Menu Button for Guests --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        @endauth
    </div>

    {{-- Mobile Menu --}}
    <x-slot:mobile>
        <x-ui.navbar-mobile>
            @auth
                @if(auth()->user()->isAdmin())
                    <x-ui.navbar-mobile-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('admin.challenges.index') }}" :active="request()->routeIs('admin.challenges.*')">
                        {{ __('Challenges') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('admin.companies.index') }}" :active="request()->routeIs('admin.companies.*')">
                        {{ __('Companies') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('admin.volunteers.index') }}" :active="request()->routeIs('admin.volunteers.*')">
                        {{ __('Contributors') }}
                    </x-ui.navbar-mobile-link>
                @elseif(auth()->user()->isVolunteer())
                    <x-ui.navbar-mobile-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('assignments.my') }}" :active="request()->routeIs('assignments.*')">
                        {{ __('My Tasks') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('teams.my') }}" :active="request()->routeIs('teams.*')">
                        {{ __('My Teams') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('community.index') }}" :active="request()->routeIs('community.*')">
                        {{ __('Community') }}
                    </x-ui.navbar-mobile-link>
                    @if(auth()->user()->volunteer && \App\Models\Challenge::where('volunteer_id', auth()->user()->volunteer->id)->where('source_type', 'volunteer')->exists())
                    <x-ui.navbar-mobile-link href="{{ route('volunteer.challenges.index') }}" :active="request()->routeIs('volunteer.challenges.*')">
                        {{ __('My Challenges') }}
                    </x-ui.navbar-mobile-link>
                    @endif
                @elseif(auth()->user()->isCompany())
                    <x-ui.navbar-mobile-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('challenges.index') }}" :active="request()->routeIs('challenges.*')">
                        {{ __('My Challenges') }}
                    </x-ui.navbar-mobile-link>
                    <x-ui.navbar-mobile-link href="{{ route('company.submissions.index') }}" :active="request()->routeIs('company.submissions.*')">
                        {{ __('Work Submissions') }}
                    </x-ui.navbar-mobile-link>
                @endif

                <div class="border-t border-gray-200 my-2"></div>

                <x-ui.navbar-mobile-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')">
                    {{ __('Profile') }}
                </x-ui.navbar-mobile-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50">
                        {{ __('Logout') }}
                    </button>
                </form>
            @else
                <x-ui.navbar-mobile-link href="{{ route('how-it-works') }}" :active="request()->routeIs('how-it-works')">
                    {{ __('How It Works') }}
                </x-ui.navbar-mobile-link>
                <x-ui.navbar-mobile-link href="{{ route('success-stories') }}" :active="request()->routeIs('success-stories')">
                    {{ __('Success Stories') }}
                </x-ui.navbar-mobile-link>
                <x-ui.navbar-mobile-link href="{{ route('help') }}" :active="request()->routeIs('help')">
                    {{ __('Help') }}
                </x-ui.navbar-mobile-link>

                <div class="border-t border-gray-200 my-2"></div>

                <div class="px-4 py-2 space-y-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                        {{ __('Sign In') }}
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-2.5 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600">
                        {{ __('Get Started') }}
                    </a>
                </div>
            @endauth
        </x-ui.navbar-mobile>
    </x-slot:mobile>
</x-ui.navbar>
