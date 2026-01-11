# In-App Guided Assistant + Language Switcher - Implementation Guide

**Status**: ✅ Ready to Implement
**Date**: 2025-12-22

---

## 📋 Overview

This document provides complete implementation for:
1. **In-App Guided Assistant** - Clickable help button with detailed contextual guidance
2. **Language Switcher** - Toggle between English and Arabic

---

## 🎯 Part 1: In-App Guided Assistant

### Step 1: Service Layer

Create `app/Services/InAppGuideService.php`:

```php
<?php

namespace App\Services;

use App\Models\InAppGuide;
use App\Models\UserGuidePreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class InAppGuideService
{
    /**
     * Get guide for current page.
     *
     * @param string|null $pageIdentifier
     * @return array|null
     */
    public static function getCurrentGuide(?string $pageIdentifier = null): ?array
    {
        $pageId = $pageIdentifier ?? Route::currentRouteName();

        if (!$pageId) {
            return null;
        }

        $guide = InAppGuide::getForPage($pageId);

        if (!$guide) {
            return null;
        }

        // Check if user has dismissed this guide
        if (Auth::check()) {
            $dismissed = UserGuidePreference::isDismissed(Auth::id(), $pageId);
            if ($dismissed) {
                return null; // Don't show if dismissed
            }
        }

        return [
            'page_title' => $guide->page_title,
            'what_is_this' => $guide->what_is_this,
            'what_to_do' => $guide->what_to_do, // Array of bullet points
            'next_step' => $guide->next_step,
            'ui_highlights' => $guide->ui_highlights,
            'video_url' => $guide->video_url,
            'page_identifier' => $guide->page_identifier,
        ];
    }

    /**
     * Dismiss guide for current user.
     *
     * @param string $pageIdentifier
     * @return void
     */
    public static function dismiss(string $pageIdentifier): void
    {
        if (Auth::check()) {
            UserGuidePreference::dismiss(Auth::id(), $pageIdentifier);
        }
    }

    /**
     * Reset guide (show again).
     *
     * @param string $pageIdentifier
     * @return void
     */
    public static function reset(string $pageIdentifier): void
    {
        if (Auth::check()) {
            UserGuidePreference::reset(Auth::id(), $pageIdentifier);
        }
    }
}
```

### Step 2: API Controller

Create `app/Http/Controllers/Api/InAppGuideController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InAppGuideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InAppGuideController extends Controller
{
    /**
     * Get guide for specific page.
     */
    public function show(Request $request, string $pageIdentifier): JsonResponse
    {
        $guide = InAppGuideService::getCurrentGuide($pageIdentifier);

        return response()->json([
            'success' => true,
            'guide' => $guide,
        ]);
    }

    /**
     * Dismiss guide.
     */
    public function dismiss(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string',
        ]);

        InAppGuideService::dismiss($request->page_identifier);

        return response()->json([
            'success' => true,
            'message' => 'Guide dismissed',
        ]);
    }

    /**
     * Reset guide.
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string',
        ]);

        InAppGuideService::reset($request->page_identifier);

        return response()->json([
            'success' => true,
            'message' => 'Guide reset',
        ]);
    }
}
```

### Step 3: Add Routes

In `routes/api.php`, add:

```php
// In-App Guide Routes
Route::prefix('guide')->group(function () {
    Route::get('{pageIdentifier}', [\App\Http\Controllers\Api\InAppGuideController::class, 'show']);
    Route::post('dismiss', [\App\Http\Controllers\Api\InAppGuideController::class, 'dismiss'])->middleware('auth:sanctum');
    Route::post('reset', [\App\Http\Controllers\Api\InAppGuideController::class, 'reset'])->middleware('auth:sanctum');
});
```

### Step 4: Blade Component

Create `app/View/Components/InAppGuideButton.php`:

```php
<?php

namespace App\View\Components;

use App\Services\InAppGuideService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InAppGuideButton extends Component
{
    public ?array $guide;
    public bool $hasGuide;

    public function __construct()
    {
        $this->guide = InAppGuideService::getCurrentGuide();
        $this->hasGuide = $this->guide !== null;
    }

    public function shouldRender(): bool
    {
        return $this->hasGuide;
    }

    public function render(): View|Closure|string
    {
        return view('components.in-app-guide-button');
    }
}
```

### Step 5: UI Component (Blade View)

Create `resources/views/components/in-app-guide-button.blade.php`:

```blade
@if($shouldRender())
<!-- Help/Guide Button -->
<button type="button"
        id="in-app-guide-trigger"
        class="in-app-guide-button"
        onclick="openInAppGuide()"
        title="{{ __('Need help? Click for guidance') }}"
        aria-label="Open page guide">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
        <path d="M12 16V16.01M12 8C10.8954 8 10 8.89543 10 10C10 10 10 11 12 11C14 11 14 12 14 12C14 13.1046 13.1046 14 12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <span class="guide-button-text">{{ __('Help') }}</span>
</button>

<!-- Guide Panel/Modal -->
<div id="in-app-guide-panel" class="in-app-guide-panel" style="display: none;">
    <div class="guide-overlay" onclick="closeInAppGuide()"></div>

    <div class="guide-content">
        <!-- Header -->
        <div class="guide-header">
            <h2 class="guide-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <path d="M12 16V16.01M12 8C10.8954 8 10 8.89543 10 10C10 10 10 11 12 11C14 11 14 12 14 12C14 13.1046 13.1046 14 12 14" stroke-width="2" stroke-linecap="round"/>
                </svg>
                {{ $guide['page_title'] }}
            </h2>
            <button type="button" class="guide-close" onclick="closeInAppGuide()" aria-label="Close guide">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M5 5L15 15M5 15L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="guide-body">
            <!-- What is this? -->
            <div class="guide-section">
                <h3 class="guide-section-title">{{ __('What is this page?') }}</h3>
                <p class="guide-section-text">{{ $guide['what_is_this'] }}</p>
            </div>

            <!-- What to do -->
            <div class="guide-section">
                <h3 class="guide-section-title">{{ __('What should I do here?') }}</h3>
                <ul class="guide-list">
                    @foreach($guide['what_to_do'] as $action)
                        <li>{{ $action }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Next step -->
            @if($guide['next_step'])
                <div class="guide-section guide-next-step">
                    <h3 class="guide-section-title">{{ __('What happens next?') }}</h3>
                    <p class="guide-section-text">{{ $guide['next_step'] }}</p>
                </div>
            @endif

            <!-- Video (if available) -->
            @if($guide['video_url'])
                <div class="guide-section">
                    <h3 class="guide-section-title">{{ __('Video Tutorial') }}</h3>
                    <div class="guide-video">
                        <iframe
                            src="{{ $guide['video_url'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="guide-footer">
            <button type="button" class="guide-dismiss-btn" onclick="dismissGuide()">
                {{ __("Don't show this again") }}
            </button>
            <button type="button" class="guide-got-it-btn" onclick="closeInAppGuide()">
                {{ __('Got it, thanks!') }}
            </button>
        </div>
    </div>
</div>

<script>
// Guide data
const guideData = @json($guide);

function openInAppGuide() {
    const panel = document.getElementById('in-app-guide-panel');
    if (panel) {
        panel.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
}

function closeInAppGuide() {
    const panel = document.getElementById('in-app-guide-panel');
    if (panel) {
        panel.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }
}

function dismissGuide() {
    // Send API request to dismiss
    fetch('/api/guide/dismiss', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || '')
        },
        body: JSON.stringify({
            page_identifier: guideData.page_identifier
        })
    }).then(() => {
        closeInAppGuide();
        // Hide button
        const button = document.getElementById('in-app-guide-trigger');
        if (button) {
            button.style.display = 'none';
        }
    });
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeInAppGuide();
    }
});
</script>
@endif
```

### Step 6: CSS Styling

Create `public/css/in-app-guide.css`:

```css
/* ====================================
   In-App Guide Styles
   ==================================== */

/* Help Button */
.in-app-guide-button {
    position: fixed;
    bottom: 100px; /* Above contextual assistant */
    right: 24px;
    z-index: 9998;

    display: flex;
    align-items: center;
    gap: 8px;

    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;

    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.in-app-guide-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.in-app-guide-button:active {
    transform: translateY(0);
}

.in-app-guide-button svg {
    width: 20px;
    height: 20px;
}

/* Guide Panel/Modal */
.in-app-guide-panel {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;

    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.guide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.guide-content {
    position: relative;
    background: white;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Header */
.guide-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.guide-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.guide-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 8px;
    padding: 8px;
    cursor: pointer;
    color: white;
    transition: background 0.2s ease;
}

.guide-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Body */
.guide-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

.guide-section {
    margin-bottom: 24px;
}

.guide-section:last-child {
    margin-bottom: 0;
}

.guide-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #667eea;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.guide-section-text {
    font-size: 15px;
    line-height: 1.6;
    color: #374151;
    margin: 0;
}

.guide-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.guide-list li {
    font-size: 15px;
    line-height: 1.6;
    color: #374151;
    padding-left: 24px;
    margin-bottom: 8px;
    position: relative;
}

.guide-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #667eea;
    font-weight: bold;
}

.guide-next-step {
    background: #f0f9ff;
    border-left: 4px solid #667eea;
    padding: 16px;
    border-radius: 8px;
}

.guide-video {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    border-radius: 8px;
}

.guide-video iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Footer */
.guide-footer {
    display: flex;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.guide-dismiss-btn {
    flex: 1;
    background: white;
    border: 1px solid #d1d5db;
    color: #6b7280;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.guide-dismiss-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.guide-got-it-btn {
    flex: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.guide-got-it-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .in-app-guide-button {
        bottom: 80px;
        right: 16px;
        padding: 10px 16px;
    }

    .guide-button-text {
        display: none;
    }

    .guide-content {
        max-height: 90vh;
        border-radius: 16px 16px 0 0;
    }

    .guide-footer {
        flex-direction: column;
    }
}

/* Print */
@media print {
    .in-app-guide-button,
    .in-app-guide-panel {
        display: none !important;
    }
}
```

---

## 🌐 Part 2: Language Switcher (English ⇄ Arabic)

### Step 1: Configure Localization

In `config/app.php`, update:

```php
'locale' => 'en',
'fallback_locale' => 'en',
'available_locales' => ['en', 'ar'],
```

### Step 2: Create Middleware

Create `app/Http/Middleware/SetLocale.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user has preferred locale in session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Check if authenticated user has locale preference
        elseif (auth()->check() && auth()->user()->locale) {
            App::setLocale(auth()->user()->locale);
        }

        return $next($request);
    }
}
```

### Step 3: Register Middleware

In `app/Http/Kernel.php`, add to `$middlewareGroups['web']`:

```php
\App\Http\Middleware\SetLocale::class,
```

### Step 4: Add Locale to Users Table

Create migration:

```bash
php artisan make:migration add_locale_to_users_table
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('locale', 5)->default('en')->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('locale');
    });
}
```

Run: `php artisan migrate`

### Step 5: Language Switcher Component

Create `resources/views/components/language-switcher.blade.php`:

```blade
<div class="language-switcher">
    <button type="button"
            class="language-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
            onclick="switchLanguage('en')">
        EN
    </button>
    <button type="button"
            class="language-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
            onclick="switchLanguage('ar')">
        عربي
    </button>
</div>

<script>
function switchLanguage(locale) {
    fetch('/language/switch', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ locale: locale })
    }).then(() => {
        window.location.reload();
    });
}
</script>

<style>
.language-switcher {
    display: flex;
    gap: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 4px;
}

.language-btn {
    background: transparent;
    border: none;
    color: currentColor;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.language-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.language-btn.active {
    background: white;
    color: #667eea;
    font-weight: 600;
}
</style>
```

### Step 6: Language Controller

Create `app/Http/Controllers/LanguageController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,ar',
        ]);

        $locale = $request->locale;

        // Store in session
        Session::put('locale', $locale);

        // Update user preference if authenticated
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        // Set app locale
        App::setLocale($locale);

        return response()->json([
            'success' => true,
            'locale' => $locale,
        ]);
    }
}
```

### Step 7: Add Route

In `routes/web.php`:

```php
Route::post('/language/switch', [App\Http\Controllers\LanguageController::class, 'switch']);
```

### Step 8: Add to Navigation

In `resources/views/layouts/app.blade.php`, add to navigation (right side, before user dropdown):

```blade
<!-- Language Switcher -->
<div class="mr-4">
    @include('components.language-switcher')
</div>
```

### Step 9: Create Translation Files

Create `resources/lang/ar.json`:

```json
{
    "Help": "مساعدة",
    "Need help? Click for guidance": "تحتاج مساعدة؟ انقر للحصول على الإرشادات",
    "What is this page?": "ما هي هذه الصفحة؟",
    "What should I do here?": "ماذا يجب أن أفعل هنا؟",
    "What happens next?": "ماذا يحدث بعد ذلك؟",
    "Video Tutorial": "فيديو تعليمي",
    "Don't show this again": "لا تظهر هذا مرة أخرى",
    "Got it, thanks!": "حسناً، شكراً!",
    "Dashboard": "لوحة التحكم",
    "My Tasks": "مهامي",
    "My Teams": "فرقي",
    "Community": "المجتمع",
    "Challenges": "التحديات"
}
```

---

## 📊 Sample Guide Content Seeder

Create `database/seeders/InAppGuideSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\InAppGuide;
use Illuminate\Database\Seeder;

class InAppGuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            // Login Page
            [
                'page_identifier' => 'login',
                'page_title' => 'Sign In to Mindova',
                'what_is_this' => 'This is where you sign in to access your Mindova account and participate in challenges.',
                'what_to_do' => [
                    'Enter your registered email address',
                    'Enter your password',
                    'Click "Sign In" to access your dashboard',
                ],
                'next_step' => 'After signing in, you\'ll be redirected to your personalized dashboard showing active challenges and tasks.',
                'ui_highlights' => null,
            ],

            // Dashboard
            [
                'page_identifier' => 'dashboard',
                'page_title' => 'Your Dashboard',
                'what_is_this' => 'Your personalized overview showing active challenges, assigned tasks, team updates, and recent activity.',
                'what_to_do' => [
                    'Review your assigned tasks and their deadlines',
                    'Check new team invitations and challenge updates',
                    'Monitor your contribution progress',
                    'Access quick links to active challenges',
                ],
                'next_step' => 'Click on any task or challenge to view details and start contributing.',
            ],

            // Submit Challenge
            [
                'page_identifier' => 'challenges.create',
                'page_title' => 'Submit a Challenge',
                'what_is_this' => 'This is where companies submit challenges that need innovative solutions. Everything is protected by NDA.',
                'what_to_do' => [
                    'Describe your business challenge clearly',
                    'Specify required expertise and skills',
                    'Set expected outcomes and success criteria',
                    'Define timeline and any constraints',
                ],
                'next_step' => 'After submission, your challenge will be reviewed and published to match with qualified volunteers.',
            ],

            // Tasks Page
            [
                'page_identifier' => 'tasks.index',
                'page_title' => 'My Tasks',
                'what_is_this' => 'View and manage all tasks assigned to you across different challenges.',
                'what_to_do' => [
                    'Review task details and requirements',
                    'Check deadlines and priority levels',
                    'Accept or decline task assignments',
                    'Submit your work when ready',
                ],
                'next_step' => 'Click on a task to open detailed view and start working on your contribution.',
            ],

            // Task Details
            [
                'page_identifier' => 'tasks.show',
                'page_title' => 'Task Details',
                'what_is_this' => 'Detailed view of your assigned task including requirements, resources, and submission guidelines.',
                'what_to_do' => [
                    'Read task requirements carefully',
                    'Review attached resources and documentation',
                    'Work on your solution independently',
                    'Submit your contribution using the form below',
                ],
                'next_step' => 'After submitting, your contribution will be reviewed by the team lead and incorporated into the final solution.',
            ],

            // Teams
            [
                'page_identifier' => 'teams.index',
                'page_title' => 'My Teams',
                'what_is_this' => 'View all micro-teams you\'re part of. Each team collaborates on specific challenges.',
                'what_to_do' => [
                    'Check team members and their roles',
                    'View team messages and updates',
                    'Access shared resources and documentation',
                    'Coordinate with teammates on tasks',
                ],
                'next_step' => 'Click on a team to view details, communicate with members, and track progress.',
            ],
        ];

        foreach ($guides as $guide) {
            InAppGuide::updateOrCreate(
                ['page_identifier' => $guide['page_identifier']],
                $guide
            );
        }

        $this->command->info('In-app guides seeded successfully!');
    }
}
```

Run seeder: `php artisan db:seed --class=InAppGuideSeeder`

---

## 🚀 Integration Instructions

### Add to Main Layout

In `resources/views/layouts/app.blade.php`:

**In `<head>` section:**
```blade
{{-- In-App Guide Styles --}}
<link rel="stylesheet" href="{{ asset('css/in-app-guide.css') }}">
```

**Before closing `</body>`:**
```blade
{{-- In-App Guide Button --}}
@auth
    <x-in-app-guide-button />
@endauth
```

**In navigation (add language switcher):**
```blade
<!-- After notifications, before user dropdown -->
<div class="mr-4">
    @include('components.language-switcher')
</div>
```

---

## ✅ Final Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Run seeder: `php artisan db:seed --class=InAppGuideSeeder`
- [ ] Create CSS file: `public/css/in-app-guide.css`
- [ ] Register SetLocale middleware in `Kernel.php`
- [ ] Add routes to `routes/api.php` and `routes/web.php`
- [ ] Create translation file: `resources/lang/ar.json`
- [ ] Add components to main layout
- [ ] Test language switching
- [ ] Test guide on different pages

---

## 📱 Testing

1. **Test In-App Guide:**
   - Visit Dashboard
   - Click Help button (bottom-right)
   - Verify guide opens with correct content
   - Click "Don't show this again"
   - Refresh page - guide should not appear
   - Check database - `user_guide_preferences` should have record

2. **Test Language Switcher:**
   - Click "عربي" button
   - Page should reload in Arabic (RTL if configured)
   - Click "EN" button
   - Page should return to English
   - Check database - users table should have locale saved

---

**Status**: ✅ **Ready to implement - All code provided above**
