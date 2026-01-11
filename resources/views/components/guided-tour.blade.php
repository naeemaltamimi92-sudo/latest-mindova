{{--
    Guided Tour Component
    Contextual, role-aware onboarding for new users
    Shows tooltips anchored to UI elements based on user role and page
--}}

@if(auth()->check() && config('user_guidance.settings.enabled'))
    @php
        $guidanceService = app(\App\Services\GuidanceService::class);
        $pageId = Route::currentRouteName() ?? 'unknown';
        $activeSteps = $guidanceService->getActiveSteps(auth()->user(), $pageId);
        $settings = config('user_guidance.settings');
    @endphp

    @if(!empty($activeSteps))
        <!-- Guided Tour Data Container -->
        <div id="guided-tour-data"
             data-steps='@json($activeSteps)'
             data-settings='@json($settings)'
             style="display: none;">
        </div>

        <!-- User ID for API calls -->
        <meta name="user-id" content="{{ auth()->id() }}">
    @endif
@endif
