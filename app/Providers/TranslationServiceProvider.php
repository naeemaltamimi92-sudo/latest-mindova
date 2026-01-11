<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\Events\MessageHandled;
use App\Services\TranslationService;
use App\Services\TranslationCacheService;

/**
 * Translation Service Provider
 *
 * Registers translation services and event listeners for missing translation logging.
 *
 * @package App\Providers
 * @author Mindova Team
 * @version 1.0.0
 */
class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register Translation Cache Service
        $this->app->singleton(TranslationCacheService::class, function ($app) {
            return new TranslationCacheService();
        });

        // Register Translation Service
        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService($app->make(TranslationCacheService::class));
        });

        // Create alias for easier access
        $this->app->alias(TranslationService::class, 'translation');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Listen for missing translations
        Event::listen('Illuminate\Translation\Events\MessageHandled', function ($event) {
            $this->handleTranslationEvent($event);
        });

        // Register custom translation loader if needed
        $this->registerTranslationLoader();
    }

    /**
     * Handle translation event for missing translation logging
     *
     * @param MessageHandled $event
     * @return void
     */
    private function handleTranslationEvent(MessageHandled $event): void
    {
        // Skip if in console mode (avoid logging during migrations, etc.)
        if ($this->app->runningInConsole()) {
            return;
        }

        // Check if translation was not found (key equals translation)
        if ($event->key === $event->translation) {
            try {
                $translationService = $this->app->make(TranslationService::class);
                $translationService->logMissing($event->key, $event->locale);
            } catch (\Exception $e) {
                Log::error("Failed to log missing translation: " . $e->getMessage());
            }
        }
    }

    /**
     * Register custom translation loader
     *
     * @return void
     */
    private function registerTranslationLoader(): void
    {
        // Custom translation loader logic can be added here
        // For example, loading translations from database
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            TranslationService::class,
            TranslationCacheService::class,
            'translation',
        ];
    }
}
