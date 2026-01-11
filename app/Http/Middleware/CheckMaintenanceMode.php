<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteSetting;

class CheckMaintenanceMode
{
    /**
     * Routes that should be accessible during maintenance mode
     */
    protected array $except = [
        'login',
        'login.submit',
        'logout',
        'admin.*',
        'mindova.*',
        'maintenance',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (!SiteSetting::isMaintenanceMode()) {
            return $next($request);
        }

        // Allow admin users to bypass maintenance mode
        if ($this->isAdminUser($request)) {
            return $next($request);
        }

        // Check if current route is in the exception list
        if ($this->isExceptedRoute($request)) {
            return $next($request);
        }

        // Show maintenance page
        return response()->view('maintenance', [
            'title' => __('Under Maintenance'),
            'message' => __('We are currently performing scheduled maintenance. Please check back soon.'),
        ], 503);
    }

    /**
     * Check if the current user is an admin (Mindova owner)
     */
    protected function isAdminUser(Request $request): bool
    {
        $user = $request->user();

        if (!$user) {
            return false;
        }

        return $user->isAdmin();
    }

    /**
     * Check if the current route is in the exception list
     */
    protected function isExceptedRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return false;
        }

        foreach ($this->except as $pattern) {
            if ($routeName === $pattern) {
                return true;
            }

            // Handle wildcard patterns like 'admin.*'
            if (str_ends_with($pattern, '.*')) {
                $prefix = substr($pattern, 0, -2);
                if (str_starts_with($routeName, $prefix . '.') || $routeName === $prefix) {
                    return true;
                }
            }
        }

        return false;
    }
}
