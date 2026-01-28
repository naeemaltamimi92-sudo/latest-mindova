<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ $textDirection ?? 'ltr' }}" class="{{ ($darkModeEnabled ?? false) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Language Meta Tags -->
    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">
    <meta name="language" content="{{ app()->getLocale() }}">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}?lang=ar">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <!-- SEO Meta Tags -->
    @if(!empty($seoMetaDescription ?? ''))
    <meta name="description" content="{{ $seoMetaDescription }}">
    @endif
    @if(!empty($seoMetaKeywords ?? ''))
    <meta name="keywords" content="{{ $seoMetaKeywords }}">
    @endif

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seoMetaTitle ?? ($siteName ?? config('app.name', 'Mindova')) }}">
    <meta property="og:description" content="{{ $seoMetaDescription ?? '' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Favicon -->
    @if(!empty($brandFaviconUrl ?? ''))
    <link rel="icon" href="{{ $brandFaviconUrl }}" type="image/x-icon">
    @endif

    <title>{{ $siteName ?? config('app.name', 'Mindova') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Brand Colors -->
    <style>
        :root {
            --brand-primary: {{ $brandPrimaryColor ?? '#6366f1' }};
            --brand-secondary: {{ $brandSecondaryColor ?? '#8b5cf6' }};
        }
        .text-brand-primary { color: var(--brand-primary); }
        .bg-brand-primary { background-color: var(--brand-primary); }
        .border-brand-primary { border-color: var(--brand-primary); }
        .text-brand-secondary { color: var(--brand-secondary); }
        .bg-brand-secondary { background-color: var(--brand-secondary); }
        .from-brand-primary { --tw-gradient-from: var(--brand-primary); }
        .to-brand-secondary { --tw-gradient-to: var(--brand-secondary); }

        /* Dark Mode Styles */
        @if($darkModeEnabled ?? false)
        html.dark body {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        html.dark nav {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .bg-white {
            background-color: #1e293b !important;
        }
        html.dark .bg-gray-50, html.dark .bg-slate-50 {
            background-color: #0f172a !important;
        }
        html.dark .bg-gray-100, html.dark .bg-slate-100 {
            background-color: #1e293b !important;
        }
        html.dark .text-gray-900, html.dark .text-slate-900 {
            color: #f1f5f9 !important;
        }
        html.dark .text-gray-700, html.dark .text-slate-700 {
            color: #cbd5e1 !important;
        }
        html.dark .text-gray-600, html.dark .text-slate-600 {
            color: #94a3b8 !important;
        }
        html.dark .text-gray-500, html.dark .text-slate-500 {
            color: #64748b !important;
        }
        html.dark .border-gray-200, html.dark .border-slate-200 {
            border-color: #334155 !important;
        }
        html.dark .border-gray-300, html.dark .border-slate-300 {
            border-color: #475569 !important;
        }
        html.dark input, html.dark textarea, html.dark select {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        html.dark input:focus, html.dark textarea:focus, html.dark select:focus {
            border-color: var(--brand-primary) !important;
        }
        html.dark .shadow-md, html.dark .shadow-lg, html.dark .shadow-xl {
            --tw-shadow-color: rgba(0, 0, 0, 0.3);
        }
        html.dark footer {
            background: linear-gradient(to bottom right, #020617, #0f172a, #1e293b) !important;
        }
        @endif
    </style>

    <!-- Custom CSS from Settings -->
    @if(!empty($customCss ?? ''))
    <style>{{ $customCss }}</style>
    @endif

    <!-- Google Analytics -->
    @if(!empty($googleAnalyticsId ?? ''))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $googleAnalyticsId }}');
    </script>
    @endif

    <!-- Facebook Pixel -->
    @if(!empty($facebookPixelId ?? ''))
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $facebookPixelId }}');
        fbq('track', 'PageView');
    </script>
    @endif

    <!-- Hotjar -->
    @if(!empty($hotjarId ?? ''))
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:{{ $hotjarId }},hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    @endif

    <!-- Custom Head Scripts -->
    @if(!empty($customHeadScripts ?? ''))
    {!! $customHeadScripts !!}
    @endif

    {{-- Contextual Page Assistant Styles --}}
    <link rel="stylesheet" href="{{ asset('css/contextual-assistant.css') }}">

    {{-- Guided Tour System Styles --}}
    <link rel="stylesheet" href="{{ asset('css/guided-tour.css') }}">


    {{-- RTL Support Styles --}}
    @if($isRTL ?? false)
    <style>
        /* RTL Base Styles */
        body { font-family: 'Tajawal', 'Segoe UI', Tahoma, sans-serif; }

        /* Flip flexbox directions for RTL */
        [dir="rtl"] .flex-row { flex-direction: row-reverse; }
        [dir="rtl"] .space-x-2 > * + * { margin-right: 0.5rem; margin-left: 0; }
        [dir="rtl"] .space-x-3 > * + * { margin-right: 0.75rem; margin-left: 0; }
        [dir="rtl"] .space-x-4 > * + * { margin-right: 1rem; margin-left: 0; }
        [dir="rtl"] .space-x-6 > * + * { margin-right: 1.5rem; margin-left: 0; }
        [dir="rtl"] .space-x-8 > * + * { margin-right: 2rem; margin-left: 0; }

        /* Text alignment */
        [dir="rtl"] .text-left { text-align: right; }
        [dir="rtl"] .text-right { text-align: left; }

        /* Margins */
        [dir="rtl"] .ml-1 { margin-right: 0.25rem; margin-left: 0; }
        [dir="rtl"] .ml-2 { margin-right: 0.5rem; margin-left: 0; }
        [dir="rtl"] .ml-3 { margin-right: 0.75rem; margin-left: 0; }
        [dir="rtl"] .ml-4 { margin-right: 1rem; margin-left: 0; }
        [dir="rtl"] .ml-6 { margin-right: 1.5rem; margin-left: 0; }
        [dir="rtl"] .ml-8 { margin-right: 2rem; margin-left: 0; }
        [dir="rtl"] .mr-1 { margin-left: 0.25rem; margin-right: 0; }
        [dir="rtl"] .mr-2 { margin-left: 0.5rem; margin-right: 0; }
        [dir="rtl"] .mr-3 { margin-left: 0.75rem; margin-right: 0; }
        [dir="rtl"] .mr-4 { margin-left: 1rem; margin-right: 0; }
        [dir="rtl"] .mr-6 { margin-left: 1.5rem; margin-right: 0; }
        [dir="rtl"] .mr-8 { margin-left: 2rem; margin-right: 0; }

        /* Paddings */
        [dir="rtl"] .pl-1 { padding-right: 0.25rem; padding-left: 0; }
        [dir="rtl"] .pl-2 { padding-right: 0.5rem; padding-left: 0; }
        [dir="rtl"] .pl-3 { padding-right: 0.75rem; padding-left: 0; }
        [dir="rtl"] .pl-4 { padding-right: 1rem; padding-left: 0; }
        [dir="rtl"] .pl-6 { padding-right: 1.5rem; padding-left: 0; }
        [dir="rtl"] .pr-1 { padding-left: 0.25rem; padding-right: 0; }
        [dir="rtl"] .pr-2 { padding-left: 0.5rem; padding-right: 0; }
        [dir="rtl"] .pr-3 { padding-left: 0.75rem; padding-right: 0; }
        [dir="rtl"] .pr-4 { padding-left: 1rem; padding-right: 0; }
        [dir="rtl"] .pr-6 { padding-left: 1.5rem; padding-right: 0; }

        /* Borders */
        [dir="rtl"] .border-l { border-left: 0; border-right-width: 1px; }
        [dir="rtl"] .border-r { border-right: 0; border-left-width: 1px; }
        [dir="rtl"] .border-l-2 { border-left: 0; border-right-width: 2px; }
        [dir="rtl"] .border-r-2 { border-right: 0; border-left-width: 2px; }
        [dir="rtl"] .border-l-4 { border-left: 0; border-right-width: 4px; }
        [dir="rtl"] .border-r-4 { border-right: 0; border-left-width: 4px; }

        /* Rounded corners */
        [dir="rtl"] .rounded-l { border-radius: 0 0.25rem 0.25rem 0; }
        [dir="rtl"] .rounded-r { border-radius: 0.25rem 0 0 0.25rem; }
        [dir="rtl"] .rounded-l-lg { border-radius: 0 0.5rem 0.5rem 0; }
        [dir="rtl"] .rounded-r-lg { border-radius: 0.5rem 0 0 0.5rem; }
        [dir="rtl"] .rounded-l-xl { border-radius: 0 0.75rem 0.75rem 0; }
        [dir="rtl"] .rounded-r-xl { border-radius: 0.75rem 0 0 0.75rem; }

        /* Positioning */
        [dir="rtl"] .left-0 { left: auto; right: 0; }
        [dir="rtl"] .right-0 { right: auto; left: 0; }
        [dir="rtl"] .left-4 { left: auto; right: 1rem; }
        [dir="rtl"] .right-4 { right: auto; left: 1rem; }

        /* Transforms */
        [dir="rtl"] .translate-x-full { transform: translateX(-100%); }
        [dir="rtl"] .-translate-x-full { transform: translateX(100%); }

        /* Icons */
        [dir="rtl"] .rotate-180 { transform: rotate(0deg); }

        /* Gradient directions */
        [dir="rtl"] .bg-gradient-to-r { background-image: linear-gradient(to left, var(--tw-gradient-stops)); }
        [dir="rtl"] .bg-gradient-to-l { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }

        /* Navigation specific */
        [dir="rtl"] nav .sm\:ml-6 { margin-left: 0; margin-right: 1.5rem; }
        [dir="rtl"] nav .sm\:ml-8 { margin-left: 0; margin-right: 2rem; }

        /* Form inputs */
        [dir="rtl"] input, [dir="rtl"] textarea, [dir="rtl"] select { text-align: right; }
        [dir="rtl"] input[type="email"], [dir="rtl"] input[type="url"] { text-align: left; direction: ltr; }

        /* Login/Register page split layout - DO NOT reverse */
        [dir="rtl"] .min-h-\[calc\(100vh-5rem\)\].flex { flex-direction: row; }
        [dir="rtl"] .min-h-screen.flex { flex-direction: row; }

        /* Input icons positioning for RTL */
        [dir="rtl"] .pl-9, [dir="rtl"] .pl-10, [dir="rtl"] .pl-12 {
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
        }
        [dir="rtl"] .absolute.inset-y-0.left-0 {
            left: auto !important;
            right: 0 !important;
        }
        [dir="rtl"] input.pl-9, [dir="rtl"] input.pl-10, [dir="rtl"] input.pl-12 {
            padding-right: 2.75rem !important;
            padding-left: 1rem !important;
        }

        /* Keep text content aligned properly */
        [dir="rtl"] .text-center { text-align: center !important; }

        /* Login form specific fixes */
        [dir="rtl"] .lg\:w-1\/2 { direction: ltr; }
        [dir="rtl"] .lg\:w-1\/2 > * { direction: rtl; }

        /* Arrow icons should flip in RTL */
        [dir="rtl"] svg.group-hover\:translate-x-1 { transform: scaleX(-1); }
        [dir="rtl"] .group:hover svg.group-hover\:translate-x-1 { transform: scaleX(-1) translateX(-0.25rem); }

        /* Fix feature cards gap */
        [dir="rtl"] .gap-4 > * { direction: rtl; }

        /* Modal and dropdown fixes */
        [dir="rtl"] .origin-top-right { transform-origin: top left; }
        [dir="rtl"] .origin-top-left { transform-origin: top right; }

        /* List items */
        [dir="rtl"] .list-disc { padding-right: 1.5rem; padding-left: 0; }
        [dir="rtl"] ul, [dir="rtl"] ol { padding-right: 1.5rem; padding-left: 0; }

        /* Checkboxes and radio alignment */
        [dir="rtl"] input[type="checkbox"], [dir="rtl"] input[type="radio"] {
            margin-left: 0.5rem;
            margin-right: 0;
        }
        [dir="rtl"] input[type="checkbox"] + label, [dir="rtl"] input[type="radio"] + label {
            margin-right: 0;
        }

        /* Button icon spacing */
        [dir="rtl"] button svg.ml-2, [dir="rtl"] a svg.ml-2 { margin-left: 0; margin-right: 0.5rem; }
        [dir="rtl"] button svg.mr-2, [dir="rtl"] a svg.mr-2 { margin-right: 0; margin-left: 0.5rem; }
        [dir="rtl"] button svg.mr-3, [dir="rtl"] a svg.mr-3 { margin-right: 0; margin-left: 0.75rem; }

        /* Stats and cards grid */
        [dir="rtl"] .grid { direction: rtl; }

        /* Navigation gaps */
        [dir="rtl"] .gap-2 > *, [dir="rtl"] .gap-3 > *, [dir="rtl"] .gap-4 > * { direction: rtl; }

        /* Auth pages - Keep split layout order intact */
        [dir="rtl"] [data-auth-page="true"] { flex-direction: row !important; }
        [dir="rtl"] [data-auth-page="true"] > div { direction: rtl; }
        [dir="rtl"] [data-auth-page="true"] .lg\:w-1\/2:first-child { order: 1; }
        [dir="rtl"] [data-auth-page="true"] .lg\:w-1\/2:last-child { order: 2; }

        /* Auth form input icons - ensure proper RTL positioning */
        [dir="rtl"] [data-auth-page="true"] .relative .absolute.left-0.pl-4,
        [dir="rtl"] [data-auth-page="true"] .relative .absolute.inset-y-0.left-0.pl-4 {
            left: auto !important;
            right: 0 !important;
            padding-left: 0 !important;
            padding-right: 1rem !important;
        }
        [dir="rtl"] [data-auth-page="true"] input.pl-12 {
            padding-left: 1rem !important;
            padding-right: 3rem !important;
        }

        /* Feature cards in auth pages */
        [dir="rtl"] [data-auth-page="true"] .items-start.gap-4 {
            flex-direction: row !important;
        }

        /* Keep form centered */
        [dir="rtl"] [data-auth-page="true"] form { text-align: right; }
        [dir="rtl"] [data-auth-page="true"] form .text-center { text-align: center !important; }

        /* Fix remember me checkbox alignment */
        [dir="rtl"] [data-auth-page="true"] .flex.items-center input[type="checkbox"] {
            margin-left: 0.5rem;
            margin-right: 0;
        }

        /* Auth page gradient buttons - keep gradient direction */
        [dir="rtl"] [data-auth-page="true"] .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important;
        }

        /* Input field icon positioning for auth pages */
        [dir="rtl"] [data-auth-page="true"] .relative .absolute.inset-y-0.left-0 {
            left: auto !important;
            right: 0 !important;
        }
        [dir="rtl"] [data-auth-page="true"] .relative .absolute.inset-y-0.left-0.pl-4 {
            padding-left: 0 !important;
            padding-right: 1rem !important;
        }
        [dir="rtl"] [data-auth-page="true"] input.pl-12 {
            padding-left: 1rem !important;
            padding-right: 3rem !important;
        }

        /* Remember me label spacing */
        [dir="rtl"] [data-auth-page="true"] label.ml-2 {
            margin-left: 0 !important;
            margin-right: 0.5rem !important;
        }

        /* Social login buttons */
        [dir="rtl"] [data-auth-page="true"] a.inline-flex svg.mr-3 {
            margin-right: 0 !important;
            margin-left: 0.75rem !important;
        }

        /* Feature cards on left panel - keep LTR layout */
        [dir="rtl"] [data-auth-page="true"] .lg\:w-1\/2:first-child .flex.items-start.gap-4 {
            flex-direction: row !important;
        }
        [dir="rtl"] [data-auth-page="true"] .lg\:w-1\/2:first-child .flex-1 {
            text-align: right;
        }
    </style>

    {{-- Arabic Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    @endif

    @stack('styles')

    @auth
    <script>
        // Store API token in localStorage for API requests
        @if(session('api_token'))
        localStorage.setItem('api_token', '{{ session('api_token') }}');
        @endif
    </script>
    @endauth
</head>
<body class="lang-loaded" data-locale="{{ $currentLocale ?? app()->getLocale() }}" data-direction="{{ $textDirection ?? 'ltr' }}">
    <!-- Page Load Animation Script -->
    <script>
        // Remove animation class after it completes to prevent re-animation
        document.body.addEventListener('animationend', function(e) {
            if (e.animationName === 'langLoadedSlide') {
                document.body.classList.remove('lang-loaded');
            }
        });
    </script>

    <!-- Unified Navigation -->
    @include('partials.navbar')

    <!-- Page content -->
    <main class="{{ request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('community.challenge') || request()->routeIs('profile.edit') ? '' : 'py-8' }}">
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Modern Footer - 2027 Design (Only for Guests) -->
    @guest
    <style>
        footer.bg-primary-500 h3,
        footer.bg-primary-500 a,
        footer.bg-primary-500 p,
        footer.bg-primary-500 span {
            color: white !important;
        }
        footer.bg-primary-500 a:hover {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
    <footer class="bg-primary-500 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Brand Section -->
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <!-- Mindova Logo -->
                        <img src="{{ asset('images/mindova-logo.svg') }}" alt="Mindova Logo" class="h-10 w-10">
                        <span class="text-xl font-black" style="color: white !important;">Mindova</span>
                    </div>
                    <p class="text-white/90 text-sm leading-relaxed mb-4" style="color: rgba(255, 255, 255, 0.9) !important;">
                        {{ __('AI-powered platform connecting talented contributors with real-world challenges') }}
                    </p>
                </div>

                <!-- Platform Links -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4" style="color: white !important;">{{ __('Platform') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('how-it-works') }}" class="text-white hover:text-white/80 text-sm" style="color: white !important;">{{ __('How It Works') }}</a></li>
                        <li><a href="{{ route('register') }}?type=volunteer" class="text-white hover:text-white/80 text-sm">{{ __('For Contributors') }}</a></li>
                        <li><a href="{{ route('register') }}?type=company" class="text-white hover:text-white/80 text-sm">{{ __('For Companies') }}</a></li>
                        <li><a href="{{ route('success-stories') }}" class="text-white hover:text-white/80 text-sm">{{ __('Success Stories') }}</a></li>
                    </ul>
                </div>

                <!-- Resources Links -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4" style="color: white !important;">{{ __('Resources') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('help') }}" class="text-white hover:text-white/80 text-sm">{{ __('Help Center') }}</a></li>
                        <li><a href="{{ route('guidelines') }}" class="text-white hover:text-white/80 text-sm">{{ __('Community Guidelines') }}</a></li>
                        <li><a href="{{ route('api-docs') }}" class="text-white hover:text-white/80 text-sm">{{ __('API Documentation') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-white hover:text-white/80 text-sm">{{ __('Blog') }}</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4" style="color: white !important;">{{ __('Company') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-white hover:text-white/80 text-sm">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white hover:text-white/80 text-sm">{{ __('Contact') }}</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-white hover:text-white/80 text-sm">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="{{ route('terms') }}" class="text-white hover:text-white/80 text-sm">{{ __('Terms of Service') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-white/10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-white/80 text-sm">
                        © 2025 Mindova. {{ __('All Rights Reserved') }}. {{ __('Made with') }} <span class="text-pink-500">❤️</span> {{ __('for innovation') }}.
                    </p>
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('privacy') }}" class="text-white hover:text-white/80 text-sm">{{ __('Privacy') }}</a>
                        <a href="{{ route('terms') }}" class="text-white hover:text-white/80 text-sm">{{ __('Terms') }}</a>
                        <a href="{{ route('contact') }}" class="text-white hover:text-white/80 text-sm">{{ __('Contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @endguest

    <script>
        function notificationDropdown() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                toggleNotifications() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },
                async fetchNotifications() {
                    try {
                        const response = await fetch('/api/notifications', {
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.notifications = data.notifications.data || [];
                        this.unreadCount = data.unread_count || 0;
                    } catch (error) {
                        console.error('{{ __("Failed to fetch notifications:") }}', error);
                    }
                },
                async handleNotificationClick(notification) {
                    // Mark as read
                    await this.markAsRead(notification.id);

                    // Close dropdown
                    this.open = false;

                    // Redirect to action URL if it exists
                    if (notification.action_url) {
                        window.location.href = notification.action_url;
                    }
                },
                async markAsRead(id) {
                    try {
                        await fetch(`/api/notifications/${id}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        this.fetchNotifications();
                    } catch (error) {
                        console.error('{{ __("Failed to mark notification as read:") }}', error);
                    }
                },
                async markAllRead() {
                    try {
                        await fetch('/api/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        this.fetchNotifications();
                    } catch (error) {
                        console.error('{{ __("Failed to mark all as read:") }}', error);
                    }
                },
                timeAgo(date) {
                    const seconds = Math.floor((new Date() - new Date(date)) / 1000);
                    if (seconds < 60) return '{{ __("Just now") }}';
                    const minutes = Math.floor(seconds / 60);
                    if (minutes < 60) return `${minutes}{{ __("m ago") }}`;
                    const hours = Math.floor(minutes / 60);
                    if (hours < 24) return `${hours}{{ __("h ago") }}`;
                    const days = Math.floor(hours / 24);
                    return `${days}{{ __("d ago") }}`;
                }
            }
        }

        // Fetch unread count on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            const notificationComponent = document.querySelector('[x-data*="notificationDropdown"]');
            if (notificationComponent) {
                Alpine.$data(notificationComponent).fetchNotifications();
            }
            @endauth
        });
    </script>

    {{-- Bug Report Button - Minimal friction capture tool (NOT a helpdesk) --}}
    {{-- Hide for admin/owner users --}}
    @if(!auth()->check() || !auth()->user()->isAdmin())
    @include('components.bug-report-button')
    @endif

    {{-- Contextual Page Assistant - Lightweight UI guide (NOT a chatbot/helpdesk/tutorial) --}}
    <x-contextual-assistant />

    {{-- Guided Tour System - Context-aware onboarding --}}
    <x-guided-tour />

    {{-- Guided Tour JavaScript --}}
    <script src="{{ asset('js/guided-tour.js') }}"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    <!-- Cookie Consent Banner -->
    @if($cookieConsentEnabled ?? false)
    <div x-data="{ show: !localStorage.getItem('cookie_consent') }" x-show="show" x-cloak
         class="fixed bottom-0 inset-x-0 z-50 p-4 bg-slate-900 text-white shadow-2xl">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-300">
                {{ $cookieConsentText ?? 'We use cookies to enhance your experience. By continuing, you agree to our cookie policy.' }}
                <a href="{{ route('privacy') }}" class="underline hover:text-white">{{ __('Learn more') }}</a>
            </p>
            <div class="flex items-center gap-3">
                <x-ui.button @click="localStorage.setItem('cookie_consent', 'declined'); show = false"
                        variant="ghost" size="sm" class="text-slate-400 hover:text-white">
                    {{ __('Decline') }}
                </x-ui.button>
                <x-ui.button @click="localStorage.setItem('cookie_consent', 'accepted'); show = false"
                        variant="primary" size="sm">
                    {{ __('Accept') }}
                </x-ui.button>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom Body Scripts -->
    @if(!empty($customBodyScripts ?? ''))
    {!! $customBodyScripts !!}
    @endif

    <!-- Custom JavaScript from Settings -->
    @if(!empty($customJs ?? ''))
    <script>{{ $customJs }}</script>
    @endif
</body>
</html>
