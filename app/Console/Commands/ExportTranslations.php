<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Services\TranslationService;
use App\Models\Translation;

/**
 * Export Translations Command
 *
 * Export translations to various formats (JSON, CSV, Excel-compatible CSV).
 *
 * Usage: php artisan translations:export [--format=json] [--locale=all]
 *
 * @package App\Console\Commands
 * @author Mindova Team
 * @version 1.0.0
 */
class ExportTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:export
                            {--format=json : Export format (json, csv)}
                            {--locale=all : Locale to export (en, ar, all)}
                            {--output= : Output file path}
                            {--source=file : Source (file, database)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export translations to JSON or CSV format';

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

        $format = $this->option('format');
        $locale = $this->option('locale');
        $source = $this->option('source');

        $this->info("Exporting translations...");
        $this->info("Format: {$format}");
        $this->info("Locale: {$locale}");
        $this->info("Source: {$source}");
        $this->newLine();

        $locales = $locale === 'all' ? ['en', 'ar'] : [$locale];

        foreach ($locales as $loc) {
            try {
                $translations = $this->loadTranslations($loc, $source);

                if (empty($translations)) {
                    $this->warn("No translations found for locale: {$loc}");
                    continue;
                }

                $outputPath = $this->getOutputPath($format, $loc);

                match ($format) {
                    'json' => $this->exportJson($translations, $outputPath, $loc),
                    'csv' => $this->exportCsv($translations, $outputPath, $loc),
                    default => throw new \InvalidArgumentException("Unsupported format: {$format}"),
                };

                $this->info("✓ Exported {$loc} translations to: {$outputPath}");
            } catch (\Exception $e) {
                $this->error("Failed to export {$loc}: " . $e->getMessage());
                return 1;
            }
        }

        $this->newLine();
        $this->info('✓ Export completed successfully');

        return 0;
    }

    /**
     * Load translations from source
     *
     * @param string $locale
     * @param string $source
     * @return array<string, string>
     */
    private function loadTranslations(string $locale, string $source): array
    {
        if ($source === 'database') {
            return Translation::getAllForLocale($locale);
        }

        // Load from file
        return $this->translationService->all($locale);
    }

    /**
     * Get output file path
     *
     * @param string $format
     * @param string $locale
     * @return string
     */
    private function getOutputPath(string $format, string $locale): string
    {
        if ($this->option('output')) {
            return $this->option('output');
        }

        $timestamp = date('Y-m-d_His');
        $filename = "translations_{$locale}_{$timestamp}.{$format}";

        return storage_path("app/exports/{$filename}");
    }

    /**
     * Export to JSON format
     *
     * @param array<string, string> $translations
     * @param string $outputPath
     * @param string $locale
     * @return void
     */
    private function exportJson(array $translations, string $outputPath, string $locale): void
    {
        // Create exports directory if it doesn't exist
        $directory = dirname($outputPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Add metadata
        $export = [
            'metadata' => [
                'locale' => $locale,
                'exported_at' => now()->toIso8601String(),
                'total_translations' => count($translations),
                'export_version' => '1.0.0',
            ],
            'translations' => $translations,
        ];

        File::put(
            $outputPath,
            json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Export to CSV format
     *
     * @param array<string, string> $translations
     * @param string $outputPath
     * @param string $locale
     * @return void
     */
    private function exportCsv(array $translations, string $outputPath, string $locale): void
    {
        // Create exports directory if it doesn't exist
        $directory = dirname($outputPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $handle = fopen($outputPath, 'w');

        if ($handle === false) {
            throw new \RuntimeException("Could not open file for writing: {$outputPath}");
        }

        // Write BOM for UTF-8 (for Excel compatibility)
        fwrite($handle, "\xEF\xBB\xBF");

        // Write header
        fputcsv($handle, ['Key', 'Translation', 'Locale']);

        // Write translations
        foreach ($translations as $key => $value) {
            fputcsv($handle, [$key, $value, $locale]);
        }

        fclose($handle);
    }
}
