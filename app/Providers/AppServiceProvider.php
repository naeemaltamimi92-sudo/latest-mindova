<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure LinkedIn OAuth provider
        $socialite = $this->app->make(SocialiteFactory::class);
        $socialite->extend('linkedin', function ($app) use ($socialite) {
            $config = $app['config']['services.linkedin'];
            return $socialite->buildProvider(
                \SocialiteProviders\LinkedIn\Provider::class,
                $config
            );
        });
    }
}
