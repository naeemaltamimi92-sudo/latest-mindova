<?php

namespace Tests\Feature;

use App\Helpers\LanguageHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LanguageSwitchingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the default locale is set correctly.
     */
    public function test_default_locale_is_english(): void
    {
        $this->assertEquals('en', LanguageHelper::getDefaultLocale());
    }

    /**
     * Test that supported locales include English and Arabic.
     */
    public function test_supported_locales_include_english_and_arabic(): void
    {
        $supported = LanguageHelper::getSupportedLocales();

        $this->assertContains('en', $supported);
        $this->assertContains('ar', $supported);
    }

    /**
     * Test that Arabic is detected as RTL.
     */
    public function test_arabic_is_rtl(): void
    {
        $this->assertTrue(LanguageHelper::isRTL('ar'));
        $this->assertEquals('rtl', LanguageHelper::getDirection('ar'));
    }

    /**
     * Test that English is detected as LTR.
     */
    public function test_english_is_ltr(): void
    {
        $this->assertFalse(LanguageHelper::isRTL('en'));
        $this->assertEquals('ltr', LanguageHelper::getDirection('en'));
    }

    /**
     * Test guest user can switch language to Arabic.
     */
    public function test_guest_can_switch_language_to_arabic(): void
    {
        $response = $this->postJson('/language/switch', [
            'locale' => 'ar',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'locale' => 'ar',
                'is_rtl' => true,
            ]);

        $this->assertEquals('ar', Session::get('locale'));
    }

    /**
     * Test guest user can switch language to English.
     */
    public function test_guest_can_switch_language_to_english(): void
    {
        Session::put('locale', 'ar');

        $response = $this->postJson('/language/switch', [
            'locale' => 'en',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'locale' => 'en',
                'is_rtl' => false,
            ]);

        $this->assertEquals('en', Session::get('locale'));
    }

    /**
     * Test authenticated user can switch language and it saves to database.
     */
    public function test_authenticated_user_can_switch_language(): void
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/language/switch', [
                'locale' => 'ar',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'locale' => 'ar',
            ]);

        $this->assertEquals('ar', Session::get('locale'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'locale' => 'ar',
        ]);
    }

    /**
     * Test that switching to unsupported locale fails.
     */
    public function test_switching_to_unsupported_locale_fails(): void
    {
        $response = $this->postJson('/language/switch', [
            'locale' => 'fr',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test that switching without locale parameter fails.
     */
    public function test_switching_without_locale_parameter_fails(): void
    {
        $response = $this->postJson('/language/switch', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['locale']);
    }

    /**
     * Test session locale is respected on subsequent requests.
     */
    public function test_session_locale_is_respected_on_subsequent_requests(): void
    {
        Session::put('locale', 'ar');

        $response = $this->get('/');

        $this->assertEquals('ar', App::getLocale());
    }

    /**
     * Test authenticated user's database locale takes priority.
     */
    public function test_user_database_locale_takes_priority_over_session(): void
    {
        $user = User::factory()->create([
            'locale' => 'ar',
        ]);

        Session::put('locale', 'en');

        $this->actingAs($user)
            ->get('/dashboard');

        $this->assertEquals('ar', App::getLocale());
    }

    /**
     * Test browser language detection for guest users.
     */
    public function test_browser_language_detection_works_for_guests(): void
    {
        $response = $this->withHeaders([
            'Accept-Language' => 'ar-SA,ar;q=0.9,en;q=0.8',
        ])->get('/');

        $this->assertEquals('ar', Session::get('locale'));
    }

    /**
     * Test current language endpoint returns correct information.
     */
    public function test_current_language_endpoint_returns_correct_information(): void
    {
        App::setLocale('ar');

        $response = $this->getJson('/language/current');

        $response->assertStatus(200)
            ->assertJson([
                'locale' => 'ar',
                'is_rtl' => true,
                'direction' => 'rtl',
            ]);
    }

    /**
     * Test language switcher component loads with correct data.
     */
    public function test_language_switcher_component_has_correct_languages(): void
    {
        $languages = LanguageHelper::getLanguageNames();

        $this->assertArrayHasKey('en', $languages);
        $this->assertArrayHasKey('ar', $languages);
        $this->assertEquals('English', $languages['en']);
        $this->assertEquals('العربية', $languages['ar']);
    }

    /**
     * Test locale validation with Rule::in() works correctly.
     */
    public function test_locale_validation_uses_supported_locales(): void
    {
        $response = $this->postJson('/language/switch', [
            'locale' => 'de',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test page reload preserves language choice for guest.
     */
    public function test_page_reload_preserves_language_for_guest(): void
    {
        // First request: switch language
        $this->postJson('/language/switch', [
            'locale' => 'ar',
        ]);

        // Second request: verify it persists
        $this->get('/');
        $this->assertEquals('ar', Session::get('locale'));
    }

    /**
     * Test page reload preserves language choice for authenticated user.
     */
    public function test_page_reload_preserves_language_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'locale' => 'ar',
        ]);

        // Make request as authenticated user
        $this->actingAs($user)
            ->get('/dashboard');

        $this->assertEquals('ar', App::getLocale());
    }

    /**
     * Test that cookie is set when guest switches language.
     */
    public function test_cookie_is_set_when_guest_switches_language(): void
    {
        $response = $this->postJson('/language/switch', [
            'locale' => 'ar',
        ]);

        $response->assertCookie('mindova_locale', 'ar');
    }

    /**
     * Test browser detection respects quality values.
     */
    public function test_browser_detection_respects_quality_values(): void
    {
        $locale = LanguageHelper::detectBrowserLocale('en;q=0.5,ar;q=0.9');

        $this->assertEquals('ar', $locale);
    }

    /**
     * Test browser detection returns null for unsupported languages.
     */
    public function test_browser_detection_returns_null_for_unsupported_languages(): void
    {
        $locale = LanguageHelper::detectBrowserLocale('fr-FR,de-DE;q=0.9');

        $this->assertNull($locale);
    }

    /**
     * Test that middleware sets locale early in request lifecycle.
     */
    public function test_middleware_sets_locale_early_in_request(): void
    {
        Session::put('locale', 'ar');

        $this->get('/');

        $this->assertEquals('ar', App::getLocale());
    }
}
