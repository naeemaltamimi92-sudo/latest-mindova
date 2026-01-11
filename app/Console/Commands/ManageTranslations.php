<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationService;

/**
 * Manage Translations Command
 *
 * Interactive CLI tool for managing translations.
 *
 * Usage: php artisan translations:manage
 *
 * @package App\Console\Commands
 * @author Mindova Team
 * @version 1.0.0
 */
class ManageTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:manage
                            {--locale=ar : Target locale}
                            {--add= : Add a new translation key}
                            {--search= : Search for translations}
                            {--delete= : Delete a translation key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive translation management tool';

    /**
     * Translation service
     */
    private TranslationService $translationService;

    /**
     * Execute the console command.
     *
     * @param TranslationService $translationService
     * @return int
     */
    public function handle(TranslationService $translationService): int
    {
        $this->translationService = $translationService;

        if ($this->option('add')) {
            return $this->addTranslation($this->option('add'));
        }

        if ($this->option('search')) {
            return $this->searchTranslations($this->option('search'));
        }

        if ($this->option('delete')) {
            return $this->deleteTranslation($this->option('delete'));
        }

        return $this->interactiveMenu();
    }

    /**
     * Display interactive menu
     *
     * @return int
     */
    private function interactiveMenu(): int
    {
        $this->info('===========================================');
        $this->info('   Translation Management Tool');
        $this->info('===========================================');
        $this->newLine();

        $choice = $this->choice(
            'What would you like to do?',
            [
                '1' => 'Add new translation',
                '2' => 'Update existing translation',
                '3' => 'Delete translation',
                '4' => 'Search translations',
                '5' => 'View statistics',
                '6' => 'Find duplicates',
                '7' => 'Sync locales',
                '8' => 'Exit',
            ],
            '8'
        );

        return match ($choice) {
            '1' => $this->interactiveAdd(),
            '2' => $this->interactiveUpdate(),
            '3' => $this->interactiveDelete(),
            '4' => $this->interactiveSearch(),
            '5' => $this->showStatistics(),
            '6' => $this->findDuplicates(),
            '7' => $this->syncLocales(),
            default => 0,
        };
    }

    /**
     * Interactive add translation
     *
     * @return int
     */
    private function interactiveAdd(): int
    {
        $locale = $this->choice('Select locale', ['en', 'ar'], 'ar');
        $key = $this->ask('Enter translation key (e.g., welcome.message)');

        if (empty($key)) {
            $this->error('Key cannot be empty');
            return 1;
        }

        // Check if key already exists
        if ($this->translationService->has($key, $locale)) {
            $this->warn("Key '{$key}' already exists in {$locale}");
            $existing = $this->translationService->translate($key, [], $locale);
            $this->line("Current value: {$existing}");

            if (!$this->confirm('Do you want to overwrite it?')) {
                return 0;
            }
        }

        $value = $this->ask('Enter translation value');

        if (empty($value)) {
            $this->error('Value cannot be empty');
            return 1;
        }

        return $this->addTranslation($key, $value, $locale);
    }

    /**
     * Add translation
     *
     * @param string $key Translation key
     * @param string|null $value Translation value
     * @param string|null $locale Locale
     * @return int
     */
    private function addTranslation(string $key, ?string $value = null, ?string $locale = null): int
    {
        $locale = $locale ?? $this->option('locale');

        if ($value === null) {
            $value = $this->ask("Enter translation value for '{$key}'");
        }

        if (empty($value)) {
            $this->error('Translation value cannot be empty');
            return 1;
        }

        try {
            $translations = $this->loadTranslations($locale);
            $translations[$key] = $value;
            $this->saveTranslations($locale, $translations);

            $this->info("✓ Added translation for '{$key}' in {$locale}");

            // Ask if user wants to add to other locale
            if ($this->confirm('Do you want to add translation for other locale?', false)) {
                $otherLocale = $locale === 'ar' ? 'en' : 'ar';
                $otherValue = $this->ask("Enter translation for {$otherLocale}");

                if (!empty($otherValue)) {
                    $this->addTranslation($key, $otherValue, $otherLocale);
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to add translation: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Interactive update translation
     *
     * @return int
     */
    private function interactiveUpdate(): int
    {
        $locale = $this->choice('Select locale', ['en', 'ar'], 'ar');
        $key = $this->ask('Enter translation key to update');

        if (empty($key)) {
            $this->error('Key cannot be empty');
            return 1;
        }

        if (!$this->translationService->has($key, $locale)) {
            $this->error("Key '{$key}' not found in {$locale}");
            return 1;
        }

        $current = $this->translationService->translate($key, [], $locale);
        $this->info("Current value: {$current}");

        $newValue = $this->ask('Enter new value');

        if (empty($newValue)) {
            $this->error('Value cannot be empty');
            return 1;
        }

        try {
            $translations = $this->loadTranslations($locale);
            $translations[$key] = $newValue;
            $this->saveTranslations($locale, $translations);

            $this->info("✓ Updated translation for '{$key}' in {$locale}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to update translation: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Interactive delete translation
     *
     * @return int
     */
    private function interactiveDelete(): int
    {
        $locale = $this->choice('Select locale', ['en', 'ar', 'both'], 'ar');
        $key = $this->ask('Enter translation key to delete');

        if (empty($key)) {
            $this->error('Key cannot be empty');
            return 1;
        }

        if ($locale === 'both') {
            $this->deleteTranslation($key, 'en');
            return $this->deleteTranslation($key, 'ar');
        }

        return $this->deleteTranslation($key, $locale);
    }

    /**
     * Delete translation
     *
     * @param string $key Translation key
     * @param string|null $locale Locale
     * @return int
     */
    private function deleteTranslation(string $key, ?string $locale = null): int
    {
        $locale = $locale ?? $this->option('locale');

        try {
            $translations = $this->loadTranslations($locale);

            if (!isset($translations[$key])) {
                $this->warn("Key '{$key}' not found in {$locale}");
                return 1;
            }

            unset($translations[$key]);
            $this->saveTranslations($locale, $translations);

            $this->info("✓ Deleted translation for '{$key}' from {$locale}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to delete translation: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Interactive search translations
     *
     * @return int
     */
    private function interactiveSearch(): int
    {
        $query = $this->ask('Enter search query');

        if (empty($query)) {
            $this->error('Search query cannot be empty');
            return 1;
        }

        return $this->searchTranslations($query);
    }

    /**
     * Search translations
     *
     * @param string $query Search query
     * @return int
     */
    private function searchTranslations(string $query): int
    {
        $this->info("Searching for: {$query}");
        $this->newLine();

        $found = false;

        foreach (['en', 'ar'] as $locale) {
            $translations = $this->loadTranslations($locale);
            $results = [];

            foreach ($translations as $key => $value) {
                if (
                    str_contains(strtolower($key), strtolower($query)) ||
                    str_contains(strtolower($value), strtolower($query))
                ) {
                    $results[$key] = $value;
                }
            }

            if (!empty($results)) {
                $found = true;
                $this->info("Results in {$locale} (" . count($results) . "):");
                $this->newLine();

                foreach (array_slice($results, 0, 10) as $key => $value) {
                    $this->line("<fg=cyan>{$key}</>: {$value}");
                }

                if (count($results) > 10) {
                    $this->line("... and " . (count($results) - 10) . " more");
                }

                $this->newLine();
            }
        }

        if (!$found) {
            $this->warn('No results found');
        }

        return 0;
    }

    /**
     * Show translation statistics
     *
     * @return int
     */
    private function showStatistics(): int
    {
        $this->info('===========================================');
        $this->info('   Translation Statistics');
        $this->info('===========================================');
        $this->newLine();

        $stats = $this->translationService->statistics();

        foreach ($stats['locales'] as $locale => $data) {
            $this->info("Locale: {$locale}");
            $this->line("  Total translations: {$data['count']}");
            $this->line("  Coverage: {$data['coverage']}%");
            $this->line("  Missing: {$data['missing']}");
            $this->newLine();
        }

        return 0;
    }

    /**
     * Find duplicate translations
     *
     * @return int
     */
    private function findDuplicates(): int
    {
        $this->info('Searching for duplicates...');
        $this->newLine();

        foreach (['en', 'ar'] as $locale) {
            $translations = $this->loadTranslations($locale);
            $values = [];
            $duplicates = [];

            foreach ($translations as $key => $value) {
                if (isset($values[$value])) {
                    $duplicates[] = [
                        'value' => $value,
                        'keys' => [$values[$value], $key],
                    ];
                } else {
                    $values[$value] = $key;
                }
            }

            if (!empty($duplicates)) {
                $this->warn("Duplicates in {$locale} (" . count($duplicates) . "):");
                $this->newLine();

                foreach (array_slice($duplicates, 0, 5) as $duplicate) {
                    $this->line("Value: {$duplicate['value']}");
                    $this->line("Keys: " . implode(', ', $duplicate['keys']));
                    $this->newLine();
                }
            } else {
                $this->info("No duplicates found in {$locale}");
            }
        }

        return 0;
    }

    /**
     * Sync translations between locales
     *
     * @return int
     */
    private function syncLocales(): int
    {
        $this->info('Syncing translations...');

        $enTranslations = $this->loadTranslations('en');
        $arTranslations = $this->loadTranslations('ar');

        $missingInAr = array_diff_key($enTranslations, $arTranslations);
        $missingInEn = array_diff_key($arTranslations, $enTranslations);

        if (!empty($missingInAr)) {
            $this->warn('Keys missing in Arabic: ' . count($missingInAr));
            foreach (array_slice(array_keys($missingInAr), 0, 10) as $key) {
                $this->line("  - {$key}");
            }
        }

        if (!empty($missingInEn)) {
            $this->warn('Keys missing in English: ' . count($missingInEn));
            foreach (array_slice(array_keys($missingInEn), 0, 10) as $key) {
                $this->line("  - {$key}");
            }
        }

        if (empty($missingInAr) && empty($missingInEn)) {
            $this->info('✓ All translations are in sync');
        }

        return 0;
    }

    /**
     * Load translations from file
     *
     * @param string $locale Locale
     * @return array<string, string>
     */
    private function loadTranslations(string $locale): array
    {
        $path = lang_path("{$locale}.json");

        if (!File::exists($path)) {
            return [];
        }

        $content = File::get($path);
        return json_decode($content, true) ?? [];
    }

    /**
     * Save translations to file
     *
     * @param string $locale Locale
     * @param array<string, string> $translations Translations
     * @return void
     */
    private function saveTranslations(string $locale, array $translations): void
    {
        $path = lang_path("{$locale}.json");

        // Sort by key
        ksort($translations);

        File::put(
            $path,
            json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        // Clear cache
        $cacheService = app(\App\Services\TranslationCacheService::class);
        $cacheService->clear($locale);
    }
}
