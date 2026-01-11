# Contextual Guide System - Implementation Documentation

## Overview

The Contextual Guide System is an in-app guided assistant that provides page-specific contextual help across the entire Mindova platform. It guides users through each step of the platform clearly and confidently, without being intrusive.

## Features Implemented

### ✅ Core Functionality

1. **Help/Guide Button**
   - Fixed position button (? icon) in bottom-right corner
   - Always visible but non-intrusive
   - Smooth animations and hover effects
   - Pulse animation to draw attention when guide is available

2. **Page-Specific Guided Content**
   - 21 pre-configured guides for major platform pages
   - Contextual content that explains:
     - What the page is about
     - What the user should do
     - What the next step is

3. **Content Format**
   - Short, actionable bullet points
   - Clear, simple, non-technical language
   - Action-oriented guidance

4. **User Preferences**
   - Dismissible guides with "Don't show again" option
   - Preferences saved per user per page
   - Manual re-opening via help button always available
   - Reset functionality for testing/debugging

5. **UX & Performance**
   - Lightweight implementation
   - No impact on page load or responsiveness
   - Smooth animations (with reduced motion support)
   - Accessible keyboard navigation
   - Mobile-responsive design

## Technical Architecture

### Database

**Table:** `user_guide_preferences`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `page_identifier` - Route name (e.g., 'dashboard', 'challenges.index')
- `dismissed` - Boolean flag
- `dismissed_at` - Timestamp when dismissed
- `created_at`, `updated_at` - Timestamps

### Configuration

**File:** `config/contextual_guide.php`

Contains all guide content for 21 pages:
- Authentication: login, register, complete-profile
- Dashboard: dashboard
- NDA: nda.general, nda.challenge
- Challenges: challenges.index, challenges.create, challenges.show, challenges.analytics
- Tasks: tasks.available, tasks.show
- Assignments: assignments.my
- Teams: teams.show, teams.my
- Community: community.index
- Profile: profile.edit, settings.notifications
- Leaderboard: leaderboard
- Static: how-it-works, help

### Components

**Blade Component:** `resources/views/components/contextual-guide.blade.php`
- Automatically detects current page using `Route::currentRouteName()`
- Loads guide content from configuration
- Checks if user has dismissed the guide
- Renders help button and guide panel
- Handles visibility and animation states

### Styling

**CSS File:** `public/css/contextual-guide.css`
- Modern, clean design
- Responsive layout
- Accessibility enhancements
- Print-friendly (hidden when printing)
- High contrast mode support
- Reduced motion support
- Smooth animations and transitions

### JavaScript

**JS File:** `public/js/contextual-guide.js`
- Handles guide panel opening/closing
- Manages user interactions
- Saves dismissal preferences via API
- Keyboard navigation (Escape to close)
- Overlay click to close
- Pulse effect on trigger button

### Backend

**Model:** `app/Models/UserGuidePreference.php`
- Static methods for checking/setting dismissal
- Relationship with User model
- Helper methods: `isDismissed()`, `dismiss()`, `reset()`

**Controller:** `app/Http/Controllers/ContextualGuideController.php`
- `dismiss()` - Save user's dismissal preference
- `reset()` - Reset dismissal (show guide again)
- `checkStatus()` - Check if guide is dismissed

**Routes:** `routes/web.php`
- `POST /api/contextual-guide/dismiss` - Dismiss guide
- `POST /api/contextual-guide/reset` - Reset guide
- `GET /api/contextual-guide/status` - Check guide status

## Usage

### For Developers

#### Adding a New Guide

1. Open `config/contextual_guide.php`
2. Add a new entry in the `guides` array:

```php
'your-route-name' => [
    'title' => 'Page Title',
    'description' => 'Brief description of the page',
    'steps' => [
        'Step 1: Do this',
        'Step 2: Then do this',
        'Step 3: Finally this',
    ],
    'next_step' => 'What happens after completing these steps',
],
```

#### Testing Guides

To reset a guide and see it again:
1. Open browser console
2. Run: `resetContextualGuide()`
3. Page will reload and guide will appear again

### For Users

1. **View Guide:**
   - Click the blue ? button in bottom-right corner
   - Guide panel will slide in from the right

2. **Dismiss Guide:**
   - Check "Don't show this again"
   - Click "Got it!" button
   - Guide won't appear automatically anymore for this page

3. **Re-open Dismissed Guide:**
   - Click the ? button anytime to manually open the guide

## Pages with Guides

### Authentication & Onboarding
- ✅ Login page
- ✅ Registration page
- ✅ Complete profile page

### Dashboard
- ✅ Main dashboard

### NDA
- ✅ General NDA
- ✅ Challenge-specific NDA

### Challenges
- ✅ Browse challenges
- ✅ Create new challenge
- ✅ Challenge details
- ✅ Challenge analytics

### Tasks & Assignments
- ✅ Available tasks
- ✅ Task details
- ✅ My assignments

### Teams
- ✅ Team details
- ✅ My teams

### Community
- ✅ Community challenges

### Profile & Settings
- ✅ Edit profile
- ✅ Notification settings

### Other
- ✅ Leaderboard
- ✅ How it works
- ✅ Help center

## Customization

### Position

Edit `config/contextual_guide.php`:
```php
'settings' => [
    'position' => 'bottom-right', // or 'top-right', 'bottom-left', 'top-left'
    ...
],
```

### Colors

Edit `public/css/contextual-guide.css`:
- Change `#3B82F6` (blue) to your preferred color
- Update gradient colors in `.contextual-guide-trigger`

### Animation

Edit `config/contextual_guide.php`:
```php
'settings' => [
    'animation' => 'slide', // 'slide', 'fade', or 'none'
    ...
],
```

## Accessibility

- ✅ Keyboard navigation (Tab, Enter, Escape)
- ✅ ARIA labels for screen readers
- ✅ Focus management
- ✅ High contrast mode support
- ✅ Reduced motion support
- ✅ Semantic HTML

## Performance

- Lightweight: ~8KB CSS + ~4KB JS (uncompressed)
- No external dependencies
- Lazy-loaded content
- Minimal DOM manipulation
- Optimized animations

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Future Enhancements (Optional)

1. **UI Highlighting** - Highlight specific page elements
2. **Video Tutorials** - Embed tutorial videos
3. **Interactive Tours** - Step-by-step walkthroughs
4. **Analytics** - Track guide usage and effectiveness
5. **Multi-language** - Support for Arabic guides

## Troubleshooting

### Guide Not Showing

1. Check if guide content exists in `config/contextual_guide.php`
2. Verify route name matches configuration key
3. Check if user has dismissed the guide
4. Clear browser cache and reload

### JavaScript Errors

1. Ensure `contextual-guide.js` is loaded
2. Check browser console for errors
3. Verify CSRF token is present in page

### Styles Not Applied

1. Clear Laravel cache: `php artisan cache:clear`
2. Check CSS file path in layout
3. Hard refresh browser (Ctrl+Shift+R)

## API Reference

### Dismiss Guide
```javascript
fetch('/api/contextual-guide/dismiss', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        page_identifier: 'dashboard'
    })
});
```

### Reset Guide
```javascript
fetch('/api/contextual-guide/reset', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
        page_identifier: 'dashboard'
    })
});
```

### Check Status
```javascript
fetch('/api/contextual-guide/status?page_identifier=dashboard', {
    method: 'GET',
    headers: {
        'Accept': 'application/json',
    }
});
```

## Credits

Implemented as part of Mindova platform enhancement to improve user onboarding and reduce support requests through contextual, in-app guidance.
