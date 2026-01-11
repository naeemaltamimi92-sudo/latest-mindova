<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);

        $middleware->alias([
            'nda.general' => \App\Http\Middleware\EnsureGeneralNdaSigned::class,
            'nda.challenge' => \App\Http\Middleware\EnsureChallengeNdaSigned::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'mindova.auth' => \App\Http\Middleware\MindovaTeamAuth::class,
            'mindova.permission' => \App\Http\Middleware\MindovaPermission::class,
            'mindova.owner' => \App\Http\Middleware\MindovaOwnerOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
