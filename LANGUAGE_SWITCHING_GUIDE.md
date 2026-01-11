# MINDOVA Language Switching System - Complete Guide

## Overview

The MINDOVA platform now features a robust, production-ready English ↔ Arabic language switching system with intelligent locale detection, seamless persistence, and comprehensive error handling.

## Architecture

### Components

1. **Configuration** (`config/languages.php`)
   - Centralized language settings
   - Supported locales: `en`, `ar`
   - RTL language definitions
   - Browser detection settings
   - Cookie configuration

2. **Helper Class** (`app/Helpers/LanguageHelper.php`)
   - Utility functions for language operations
   - RTL detection
   - Locale validation
   - Browser language detection
   - Centralized language data access

3. **Middleware** (`app/Http/Middleware/SetLocale.php`)
   - Executes early in request lifecycle
   - Intelligent locale detection with fallback chain
   - Debug logging for troubleshooting

4. **Controller** (`app/Http/Controllers/LanguageController.php`)
   - Language switching endpoint
   - Current language information endpoint
   - Comprehensive error handling
   - Database and session synchronization

5. **UI Component** (`resources/views/components/language-switcher.blade.php`)
   - Dropdown language selector
   - Dynamic language display
   - AJAX-based switching

6. **View Component** (`app/View/Components/LanguageSwitcher.php`)
   - Provides data to language switcher blade component
   - Uses LanguageHelper for consistency

## Locale Detection Priority

The system determines the user's locale using the following priority order:

1. **Authenticated User's Database Preference** (highest priority)
   - Stored in `users.locale` column
   - Persists across sessions and devices

2. **Session Locale**
   - Set when guest users switch language
   - Persists during the browser session

3. **Cookie Locale**
   - Long-lived cookie (1 year default)
   - Allows guests to maintain preference across sessions

4. **Browser Language Detection** (guests only)
   - Parses `Accept-Language` HTTP header
   - Respects quality values
   - Only matches supported locales

5. **Default Locale** (fallback)
   - English (`en`) as configured in `config/languages.php`

## Features

### For Guest Users

- Automatic browser language detection
- Language preference saved in session
- Language preference saved in cookie (1 year)
- Instant UI updates after language switch
- No account required

### For Authenticated Users

- Language preference saved to database
- Synced across all devices
- Session and cookie also updated for consistency
- Preference survives logout/login

### Technical Features

- **RTL Support**: Automatic detection and `dir` attribute switching
- **Meta Tags**: SEO-friendly language meta tags
- **Validation**: Dynamic validation using config
- **Error Handling**: Graceful degradation on failures
- **Logging**: Debug logs for troubleshooting
- **Type Safety**: Full PHP type hints
- **Testing**: Comprehensive test suite

## API Endpoints

### POST /language/switch

Switch the application language.

**Request:**
```json
{
  "locale": "ar"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "locale": "ar",
  "message": "Language changed successfully.",
  "language_name": "العربية",
  "is_rtl": true,
  "direction": "rtl"
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Invalid language selection.",
  "errors": {
    "locale": ["The selected locale is invalid."]
  },
  "locale": "en"
}
```

### GET /language/current

Get current language information.

**Success Response (200):**
```json
{
  "locale": "ar",
  "name": "العربية",
  "is_rtl": true,
  "direction": "rtl",
  "supported_locales": ["en", "ar"],
  "language_names": {
    "en": "English",
    "ar": "العربية"
  }
}
```

## Helper Functions

### LanguageHelper Methods

```php
use App\Helpers\LanguageHelper;

// Check if locale is RTL
LanguageHelper::isRTL('ar'); // true
LanguageHelper::isRTL('en'); // false

// Get text direction
LanguageHelper::getDirection('ar'); // 'rtl'
LanguageHelper::getDirection('en'); // 'ltr'

// Get supported locales
LanguageHelper::getSupportedLocales(); // ['en', 'ar']

// Get current locale with validation
LanguageHelper::getCurrentLocale(); // 'en' or 'ar'

// Get default locale
LanguageHelper::getDefaultLocale(); // 'en'

// Get language names
LanguageHelper::getLanguageNames(); // ['en' => 'English', 'ar' => 'العربية']

// Get specific language name
LanguageHelper::getLanguageName('ar'); // 'العربية'

// Check if locale is supported
LanguageHelper::isSupported('ar'); // true
LanguageHelper::isSupported('fr'); // false

// Detect browser locale
LanguageHelper::detectBrowserLocale('ar-SA,ar;q=0.9,en;q=0.8'); // 'ar'

// Get config value
LanguageHelper::config('rtl'); // ['ar']
```

## Blade Templates

### Using Locale in Templates

```blade
{{-- Get current locale --}}
{{ app()->getLocale() }}

{{-- Check if RTL --}}
@if(app()->getLocale() === 'ar')
    <div dir="rtl">Arabic content</div>
@endif

{{-- Using helper in Blade --}}
@php
    use App\Helpers\LanguageHelper;
@endphp

@if(LanguageHelper::isRTL())
    <div class="text-right">RTL content</div>
@endif

{{-- HTML attributes set automatically in layouts/app.blade.php --}}
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
```

## Adding New Languages

To add a new language (e.g., French):

1. **Update config/languages.php:**
```php
'supported' => ['en', 'ar', 'fr'],
'names' => [
    'en' => 'English',
    'ar' => 'العربية',
    'fr' => 'Français',
],
// Add to rtl array if RTL language
'rtl' => ['ar'],
```

2. **Create translation files:**
```bash
mkdir resources/lang/fr
cp resources/lang/en/* resources/lang/fr/
# Translate the files
```

3. **No code changes needed!** The system is fully dynamic.

## Testing

Run the comprehensive test suite:

```bash
php artisan test --filter LanguageSwitchingTest
```

**Tests cover:**
- Default locale configuration
- Supported locales validation
- RTL/LTR detection
- Guest language switching
- Authenticated user language switching
- Session persistence
- Database persistence
- Cookie setting
- Browser detection
- Priority ordering
- Error handling
- API endpoint responses

## Troubleshooting

### Enable Debug Logging

Check `storage/logs/laravel.log` for language-related logs:

- Locale changes (debug level)
- Invalid locale attempts (warning level)
- Language switch failures (error level)

### Common Issues

**Issue:** Language doesn't switch
- Check session is working: `php artisan session:table && php artisan migrate`
- Verify CSRF token is included in AJAX requests
- Check browser console for JavaScript errors

**Issue:** Database not updating for authenticated users
- Verify `locale` column exists in `users` table
- Check if column is in `$fillable` array in User model
- Review error logs for database exceptions

**Issue:** Browser detection not working
- Ensure `browser_detection` is `true` in config
- Check `Accept-Language` header is being sent
- Verify user is a guest (detection disabled for authenticated users)

**Issue:** Cookie not persisting
- Check cookie settings in config
- Verify domain settings allow cookies
- Check browser cookie settings

## Security Considerations

1. **Validation**: All locale inputs are validated against whitelist
2. **SQL Injection**: Uses Eloquent ORM with parameterized queries
3. **XSS Protection**: Locale codes are validated before output
4. **CSRF Protection**: POST requests require valid CSRF token
5. **No User Input in Translation Keys**: Locale codes only from config

## Performance Considerations

1. **Early Execution**: Middleware runs early to set locale once
2. **Cached Config**: Config values are cached in production
3. **Minimal Queries**: Only one DB query for authenticated users
4. **Efficient Detection**: Browser detection uses regex, not external calls
5. **Optimized Autoload**: Helper is pre-loaded via composer autoload

## Database Schema

The `users` table includes:

```sql
`locale` varchar(5) NULL DEFAULT NULL
```

This column:
- Is nullable (allows NULL for users who haven't set preference)
- Is fillable via mass assignment
- Defaults to NULL (middleware will use fallback chain)

## Browser Support

The language switcher works on:
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

Requires JavaScript enabled for switching (graceful degradation planned).

## Migration Guide

If upgrading from a previous version:

1. Run composer autoload:
```bash
composer dump-autoload
```

2. Clear config cache:
```bash
php artisan config:clear
```

3. Verify locale column exists:
```bash
php artisan migrate
```

4. Test language switching:
```bash
php artisan test --filter LanguageSwitchingTest
```

## Future Enhancements

Potential improvements for future versions:

- [ ] Support for additional languages (French, Spanish, etc.)
- [ ] Automatic RTL CSS switching
- [ ] Language-specific date/time formatting
- [ ] Translation management UI
- [ ] Locale-specific number formatting
- [ ] Fallback translation support
- [ ] Language-specific fonts
- [ ] No-JS fallback with form submission

## Support

For issues or questions:
1. Check this documentation
2. Review test cases for examples
3. Check application logs
4. Review Laravel localization docs: https://laravel.com/docs/localization

---

**Last Updated:** 2025-12-23
**Version:** 1.0.0
**Maintainer:** MINDOVA Development Team
