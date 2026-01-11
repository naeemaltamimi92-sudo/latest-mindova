<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminSettingsController extends Controller
{
    /**
     * Setting groups configuration with icons and colors
     */
    protected array $settingGroups = [
        'general' => [
            'label' => 'General Settings',
            'description' => 'Basic platform configuration',
            'icon' => 'cog',
            'color' => 'from-slate-500 to-slate-600',
            'priority' => 1,
        ],
        'branding' => [
            'label' => 'Branding',
            'description' => 'Logo, colors, and visual identity',
            'icon' => 'color-swatch',
            'color' => 'from-pink-500 to-rose-600',
            'priority' => 2,
        ],
        'features' => [
            'label' => 'Feature Controls',
            'description' => 'Enable or disable platform features',
            'icon' => 'lightning-bolt',
            'color' => 'from-violet-500 to-purple-600',
            'priority' => 3,
        ],
        'users' => [
            'label' => 'User & Registration',
            'description' => 'User accounts and registration settings',
            'icon' => 'users',
            'color' => 'from-blue-500 to-indigo-600',
            'priority' => 4,
        ],
        'challenges' => [
            'label' => 'Challenge Settings',
            'description' => 'Configure challenge behavior and limits',
            'icon' => 'flag',
            'color' => 'from-amber-500 to-orange-600',
            'priority' => 5,
        ],
        'gamification' => [
            'label' => 'Gamification',
            'description' => 'Points, badges, and rewards system',
            'icon' => 'trophy',
            'color' => 'from-yellow-500 to-amber-600',
            'priority' => 6,
        ],
        'ai' => [
            'label' => 'AI Features',
            'description' => 'AI-powered functionality settings',
            'icon' => 'chip',
            'color' => 'from-cyan-500 to-teal-600',
            'priority' => 7,
        ],
        'notifications' => [
            'label' => 'Notifications',
            'description' => 'Email and notification preferences',
            'icon' => 'bell',
            'color' => 'from-red-500 to-pink-600',
            'priority' => 8,
        ],
        'email' => [
            'label' => 'Email Settings',
            'description' => 'Email configuration and templates',
            'icon' => 'mail',
            'color' => 'from-sky-500 to-blue-600',
            'priority' => 9,
        ],
        'community' => [
            'label' => 'Community',
            'description' => 'Community features and social settings',
            'icon' => 'user-group',
            'color' => 'from-green-500 to-emerald-600',
            'priority' => 10,
        ],
        'certificates' => [
            'label' => 'Certificates',
            'description' => 'Certificate generation settings',
            'icon' => 'academic-cap',
            'color' => 'from-indigo-500 to-violet-600',
            'priority' => 11,
        ],
        'social' => [
            'label' => 'Social Media',
            'description' => 'Social media links and sharing',
            'icon' => 'share',
            'color' => 'from-blue-400 to-indigo-500',
            'priority' => 12,
        ],
        'seo' => [
            'label' => 'SEO & Meta',
            'description' => 'Search engine optimization settings',
            'icon' => 'search',
            'color' => 'from-emerald-500 to-teal-600',
            'priority' => 13,
        ],
        'analytics' => [
            'label' => 'Analytics',
            'description' => 'Tracking and analytics integration',
            'icon' => 'chart-bar',
            'color' => 'from-purple-500 to-indigo-600',
            'priority' => 14,
        ],
        'localization' => [
            'label' => 'Localization',
            'description' => 'Language and regional settings',
            'icon' => 'globe',
            'color' => 'from-teal-500 to-cyan-600',
            'priority' => 15,
        ],
        'security' => [
            'label' => 'Security',
            'description' => 'Security and authentication settings',
            'icon' => 'shield-check',
            'color' => 'from-red-500 to-rose-600',
            'priority' => 16,
        ],
        'legal' => [
            'label' => 'Legal & Compliance',
            'description' => 'GDPR, cookies, and legal requirements',
            'icon' => 'document-text',
            'color' => 'from-gray-500 to-slate-600',
            'priority' => 17,
        ],
        'api' => [
            'label' => 'API Settings',
            'description' => 'API access and rate limiting',
            'icon' => 'code',
            'color' => 'from-orange-500 to-red-600',
            'priority' => 18,
        ],
        'uploads' => [
            'label' => 'File Uploads',
            'description' => 'File upload limits and allowed types',
            'icon' => 'cloud-upload',
            'color' => 'from-sky-500 to-blue-600',
            'priority' => 19,
        ],
        'performance' => [
            'label' => 'Performance',
            'description' => 'Caching and optimization settings',
            'icon' => 'lightning-bolt',
            'color' => 'from-lime-500 to-green-600',
            'priority' => 20,
        ],
        'onboarding' => [
            'label' => 'Onboarding',
            'description' => 'New user onboarding experience',
            'icon' => 'play',
            'color' => 'from-fuchsia-500 to-pink-600',
            'priority' => 21,
        ],
        'developer' => [
            'label' => 'Developer',
            'description' => 'Advanced developer settings',
            'icon' => 'terminal',
            'color' => 'from-gray-700 to-gray-900',
            'priority' => 22,
        ],
    ];

    /**
     * Setting presets for quick configuration
     */
    protected array $presets = [
        'production' => [
            'name' => 'Production',
            'description' => 'Optimized for live production environment',
            'icon' => 'server',
            'color' => 'from-green-500 to-emerald-600',
            'settings' => [
                'maintenance_mode' => false,
                'debug_mode' => false,
                'cache_enabled' => true,
                'minify_assets' => true,
                'log_level' => 'error',
                'api_debug_enabled' => false,
            ],
        ],
        'development' => [
            'name' => 'Development',
            'description' => 'Settings optimized for development',
            'icon' => 'code',
            'color' => 'from-blue-500 to-indigo-600',
            'settings' => [
                'maintenance_mode' => false,
                'debug_mode' => true,
                'cache_enabled' => false,
                'minify_assets' => false,
                'log_level' => 'debug',
                'api_debug_enabled' => true,
            ],
        ],
        'maintenance' => [
            'name' => 'Maintenance',
            'description' => 'Enable maintenance mode for updates',
            'icon' => 'wrench',
            'color' => 'from-amber-500 to-orange-600',
            'settings' => [
                'maintenance_mode' => true,
                'registration_enabled' => false,
                'volunteer_registration_enabled' => false,
                'company_registration_enabled' => false,
            ],
        ],
        'secure' => [
            'name' => 'Maximum Security',
            'description' => 'Enhanced security settings',
            'icon' => 'shield-check',
            'color' => 'from-red-500 to-rose-600',
            'settings' => [
                'force_https' => true,
                'email_verification_required' => true,
                'nda_required_general' => true,
                'nda_required_challenge' => true,
                'max_login_attempts' => 3,
                'session_lifetime_minutes' => 60,
                'password_min_length' => 12,
            ],
        ],
    ];

    /**
     * Display the settings page
     */
    public function index(Request $request): View
    {
        $allSettings = SiteSetting::getAllSettings();
        $settingsByGroup = [];
        $searchQuery = $request->get('search', '');
        $activeGroup = $request->get('group', 'general');

        // Get all settings from database grouped
        $groups = array_keys($this->settingGroups);

        foreach ($groups as $group) {
            $query = SiteSetting::where('group', $group)->orderBy('id');

            // Apply search filter if provided
            if ($searchQuery) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('key', 'like', "%{$searchQuery}%")
                        ->orWhere('label', 'like', "%{$searchQuery}%")
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                });
            }

            $settings = $query->get();

            if ($settings->isNotEmpty()) {
                $settingsByGroup[$group] = [
                    'config' => $this->settingGroups[$group],
                    'settings' => $settings,
                ];
            }
        }

        // Sort by priority
        uasort($settingsByGroup, function ($a, $b) {
            return ($a['config']['priority'] ?? 99) - ($b['config']['priority'] ?? 99);
        });

        // Calculate statistics
        $stats = [
            'total' => SiteSetting::count(),
            'groups' => count($settingsByGroup),
            'booleans' => SiteSetting::where('type', 'boolean')->count(),
            'enabled' => SiteSetting::where('type', 'boolean')->where('value', '1')->count(),
            'strings' => SiteSetting::where('type', 'string')->count(),
            'integers' => SiteSetting::where('type', 'integer')->count(),
            'public' => SiteSetting::where('is_public', true)->count(),
        ];

        return view('admin.settings.index', compact(
            'settingsByGroup',
            'allSettings',
            'searchQuery',
            'activeGroup',
            'stats'
        ))->with('presets', $this->presets);
    }

    /**
     * Update settings
     */
    public function update(Request $request): RedirectResponse
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // Handle unchecked checkboxes (boolean settings)
        $booleanSettings = SiteSetting::where('type', 'boolean')->pluck('key')->toArray();

        foreach ($booleanSettings as $key) {
            if (!isset($settings[$key])) {
                SiteSetting::set($key, false);
            }
        }

        // Clear cache
        SiteSetting::clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', __('Settings updated successfully'));
    }

    /**
     * Toggle a specific setting via AJAX
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string|exists:site_settings,key',
        ]);

        $setting = SiteSetting::where('key', $request->key)->first();

        if (!$setting || $setting->type !== 'boolean') {
            return response()->json([
                'success' => false,
                'message' => __('Invalid setting'),
            ], 400);
        }

        $currentValue = SiteSetting::get($request->key);
        $newValue = !$currentValue;

        SiteSetting::set($request->key, $newValue);

        // Log the change
        Log::info('Setting toggled', [
            'key' => $request->key,
            'old_value' => $currentValue,
            'new_value' => $newValue,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'key' => $request->key,
            'value' => $newValue,
            'message' => __($setting->label) . ' ' . ($newValue ? __('enabled') : __('disabled')),
        ]);
    }

    /**
     * Update a single setting via AJAX
     */
    public function updateSingle(Request $request): JsonResponse
    {
        $request->validate([
            'key' => 'required|string|exists:site_settings,key',
            'value' => 'present',
        ]);

        $setting = SiteSetting::where('key', $request->key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => __('Setting not found'),
            ], 404);
        }

        $value = $request->value;

        // Validate value based on type
        if ($setting->type === 'integer') {
            if ($value !== '' && !is_numeric($value)) {
                return response()->json([
                    'success' => false,
                    'message' => __('Value must be a number'),
                ], 400);
            }
            $value = $value === '' ? 0 : (int) $value;
        }

        $oldValue = SiteSetting::get($request->key);
        SiteSetting::set($request->key, $value);

        // Log the change
        Log::info('Setting updated', [
            'key' => $request->key,
            'old_value' => $oldValue,
            'new_value' => $value,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'key' => $request->key,
            'value' => $value,
            'message' => __($setting->label) . ' ' . __('updated successfully'),
        ]);
    }

    /**
     * Apply a preset configuration
     */
    public function applyPreset(Request $request): JsonResponse
    {
        $request->validate([
            'preset' => 'required|string',
        ]);

        $presetKey = $request->preset;

        if (!isset($this->presets[$presetKey])) {
            return response()->json([
                'success' => false,
                'message' => __('Preset not found'),
            ], 404);
        }

        $preset = $this->presets[$presetKey];
        $applied = [];

        foreach ($preset['settings'] as $key => $value) {
            if (SiteSetting::where('key', $key)->exists()) {
                SiteSetting::set($key, $value);
                $applied[] = $key;
            }
        }

        SiteSetting::clearCache();

        Log::info('Preset applied', [
            'preset' => $presetKey,
            'settings_applied' => $applied,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'preset' => $presetKey,
            'applied_count' => count($applied),
            'message' => __(':name preset applied successfully', ['name' => $preset['name']]),
        ]);
    }

    /**
     * Import settings from JSON
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        $imported = 0;
        $skipped = 0;

        foreach ($request->settings as $key => $data) {
            $setting = SiteSetting::where('key', $key)->first();

            if ($setting) {
                $value = is_array($data) ? ($data['value'] ?? $data) : $data;
                SiteSetting::set($key, $value);
                $imported++;
            } else {
                $skipped++;
            }
        }

        SiteSetting::clearCache();

        Log::info('Settings imported', [
            'imported' => $imported,
            'skipped' => $skipped,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'message' => __(':count settings imported successfully', ['count' => $imported]),
        ]);
    }

    /**
     * Export all settings as JSON
     */
    public function export(): JsonResponse
    {
        $settings = SiteSetting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => [
                'value' => SiteSetting::get($setting->key),
                'type' => $setting->type,
                'group' => $setting->group,
                'label' => $setting->label,
            ]];
        });

        return response()->json([
            'success' => true,
            'exported_at' => now()->toISOString(),
            'version' => '2.0',
            'settings' => $settings,
        ]);
    }

    /**
     * Clear all caches
     */
    public function clearCache(): JsonResponse
    {
        try {
            SiteSetting::clearCache();
            Cache::flush();
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');

            Log::info('All caches cleared', ['user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => __('All caches cleared successfully'),
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => __('Failed to clear cache'),
            ], 500);
        }
    }

    /**
     * Reset settings to defaults for a specific group
     */
    public function resetGroup(Request $request): JsonResponse
    {
        $request->validate([
            'group' => 'required|string',
        ]);

        // For now, just clear cache - in the future, we could store default values
        SiteSetting::clearCache();

        Log::info('Group cache cleared', [
            'group' => $request->group,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Cache cleared for group: :group', ['group' => $request->group]),
        ]);
    }

    /**
     * Search settings
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => [],
            ]);
        }

        $settings = SiteSetting::where('key', 'like', "%{$query}%")
            ->orWhere('label', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(20)
            ->get()
            ->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'label' => __($setting->label),
                    'description' => __($setting->description),
                    'group' => $setting->group,
                    'type' => $setting->type,
                    'value' => SiteSetting::get($setting->key),
                ];
            });

        return response()->json([
            'success' => true,
            'results' => $settings,
        ]);
    }

    /**
     * Get current settings status (for AJAX)
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'settings' => SiteSetting::getPublicSettings(),
            'stats' => [
                'total' => SiteSetting::count(),
                'enabled_features' => SiteSetting::where('type', 'boolean')
                    ->where('group', 'features')
                    ->where('value', '1')
                    ->count(),
            ],
        ]);
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        $updated = 0;

        foreach ($request->settings as $key => $value) {
            if (SiteSetting::where('key', $key)->exists()) {
                SiteSetting::set($key, $value);
                $updated++;
            }
        }

        SiteSetting::clearCache();

        return response()->json([
            'success' => true,
            'updated' => $updated,
            'message' => __(':count settings updated', ['count' => $updated]),
        ]);
    }
}
