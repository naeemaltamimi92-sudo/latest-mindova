<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationService;

/**
 * Validate Translations Command
 *
 * Validates translation completeness by scanning blade files for translation keys
 * and comparing them against language files.
 *
 * Usage: php artisan translations:validate [--locale=ar] [--export] [--unused]
 *
 * @package App\Console\Commands
 * @author Mindova Team
 * @version 1.0.0
 */
class ValidateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:validate
                            {--locale=ar : The locale to validate}
                            {--export : Export missing translations to file}
                            {--unused : Find unused translations}
                            {--all : Validate all locales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate translation completeness and find missing/unused translations';

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

        $this->info('🔍 Validating Translations...');
        $this->newLine();

        if ($this->option('all')) {
            return $this->validateAllLocales();
        }

        $locale = $this->option('locale');
        return $this->validateLocale($locale);
    }

    /**
     * Validate all locales
     *
     * @return int
     */
    private function validateAllLocales(): int
    {
        $locales = ['en', 'ar'];
        $allValid = true;

        foreach ($locales as $locale) {
            $this->info("Validating locale: {$locale}");
            $this->line('─────────────────────────────────────');
            $result = $this->validateLocale($locale);
            if ($result !== 0) {
                $allValid = false;
            }
            $this->newLine();
        }

        return $allValid ? 0 : 1;
    }

    /**
     * Validate a specific locale
     *
     * @param string $locale
     * @return int
     */
    private function validateLocale(string $locale): int
    {
        // Extract all translation keys from blade files
        $usedKeys = $this->extractTranslationKeys();
        $this->info("✓ Found " . count($usedKeys) . " translation keys in blade files");

        // Get translations from language file
        $translations = $this->translationService->all($locale);
        $this->info("✓ Found " . count($translations) . " translations in {$locale}.json");
        $this->newLine();

        // Find missing translations
        $missing = array_diff($usedKeys, array_keys($translations));

        // Find unused translations
        $unused = [];
        if ($this->option('unused')) {
            $unused = array_diff(array_keys($translations), $usedKeys);
        }

        // Calculate coverage
        $coverage = count($usedKeys) > 0
            ? round((count($usedKeys) - count($missing)) / count($usedKeys) * 100, 2)
            : 100;

        // Display results
        $this->displayResults($locale, $missing, $unused, $coverage);

        // Export if requested
        if ($this->option('export') && !empty($missing)) {
            $this->exportMissingTranslations($locale, $missing);
        }

        return empty($missing) ? 0 : 1;
    }

    /**
     * Extract translation keys from blade files
     *
     * @return array<string>
     */
    private function extractTranslationKeys(): array
    {
        $keys = [];
        $bladeFiles = $this->getBladeFiles();

        $this->info("Scanning " . count($bladeFiles) . " blade files...");

        $progressBar = $this->output->createProgressBar(count($bladeFiles));
        $progressBar->start();

        foreach ($bladeFiles as $file) {
            $content = File::get($file);

            // Match __('key'), __("key"), @lang('key'), {{ __('key') }}, etc.
            preg_match_all(
                '/__\([\'"](.+?)[\'"]\)|@lang\([\'"](.+?)[\'"]\)|trans\([\'"](.+?)[\'"]\)/',
                $content,
                $matches
            );

            foreach ($matches as $matchGroup) {
                foreach ($matchGroup as $match) {
                    if (!empty($match) && !str_starts_with($match, '__') && !str_starts_with($match, '@lang') && !str_starts_with($match, 'trans')) {
                        $keys[] = $match;
                    }
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        return array_unique($keys);
    }

    /**
     * Get all blade files
     *
     * @return array<string>
     */
    private function getBladeFiles(): array
    {
        $resourcesPath = resource_path('views');

        if (!File::exists($resourcesPath)) {
            $this->error("Views directory not found: {$resourcesPath}");
            return [];
        }

        return File::allFiles($resourcesPath);
    }

    /**
     * Display validation results
     *
     * @param string $locale
     * @param array<string> $missing
     * @param array<string> $unused
     * @param float $coverage
     * @return void
     */
    private function displayResults(string $locale, array $missing, array $unused, float $coverage): void
    {
        // Coverage
        $this->newLine();
        $this->info("📊 Translation Coverage: {$coverage}%");

        if ($coverage === 100.0) {
            $this->info("✓ <fg=green>Perfect! All translations are present.</>");
        } else {
            $this->warn("⚠ Translation coverage is incomplete.");
        }

        $this->newLine();

        // Missing translations
        if (!empty($missing)) {
            $this->error("❌ Missing Translations (" . count($missing) . "):");
            $this->newLine();

            foreach (array_slice($missing, 0, 20) as $key) {
                $this->line("  <fg=red>✗</> {$key}");
            }

            if (count($missing) > 20) {
                $remaining = count($missing) - 20;
                $this->line("  ... and {$remaining} more");
            }

            $this->newLine();
        } else {
            $this->info("✓ <fg=green>No missing translations</>");
            $this->newLine();
        }

        // Unused translations
        if ($this->option('unused') && !empty($unused)) {
            $this->warn("⚠ Unused Translations (" . count($unused) . "):");
            $this->newLine();

            foreach (array_slice($unused, 0, 10) as $key) {
                $this->line("  <fg=yellow>!</> {$key}");
            }

            if (count($unused) > 10) {
                $remaining = count($unused) - 10;
                $this->line("  ... and {$remaining} more");
            }

            $this->newLine();
        }
    }

    /**
     * Export missing translations to file
     *
     * @param string $locale
     * @param array<string> $missing
     * @return void
     */
    private function exportMissingTranslations(string $locale, array $missing): void
    {
        $filename = "missing_translations_{$locale}_" . date('Y-m-d_His') . ".json";
        $filepath = storage_path("app/{$filename}");

        $export = [];
        foreach ($missing as $key) {
            $export[$key] = "[TRANSLATE] {$key}";
        }

        try {
            File::put($filepath, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("✓ Missing translations exported to: {$filepath}");
        } catch (\Exception $e) {
            $this->error("Failed to export missing translations: " . $e->getMessage());
        }
    }
}
