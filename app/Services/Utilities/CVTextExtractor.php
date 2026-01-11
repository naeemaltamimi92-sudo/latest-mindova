<?php

namespace App\Services\Utilities;

use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class CVTextExtractor
{
    /**
     * Extract text from a CV file.
     *
     * @param string $filePath The storage path to the CV file
     * @return string The extracted text
     * @throws \Exception If file cannot be read or parsed
     */
    public function extract(string $filePath): string
    {
        // Get the full path to the file
        $fullPath = Storage::path($filePath);

        if (!file_exists($fullPath)) {
            throw new \Exception("CV file not found: {$filePath}");
        }

        // Get file extension
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => $this->extractFromPdf($fullPath),
            'txt' => $this->extractFromText($fullPath),
            'doc', 'docx' => throw new \Exception('DOC/DOCX extraction not yet implemented. Please use PDF format.'),
            default => throw new \Exception("Unsupported file format: {$extension}"),
        };
    }

    /**
     * Extract text from a PDF file.
     */
    protected function extractFromPdf(string $filePath): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();

            if (empty(trim($text))) {
                throw new \Exception('No text could be extracted from PDF');
            }

            return $this->cleanText($text);
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse PDF: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from a plain text file.
     */
    protected function extractFromText(string $filePath): string
    {
        $text = file_get_contents($filePath);

        if ($text === false) {
            throw new \Exception('Failed to read text file');
        }

        return $this->cleanText($text);
    }

    /**
     * Clean and normalize extracted text.
     */
    protected function cleanText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // Normalize line breaks
        $text = preg_replace('/\r\n|\r/', "\n", $text);

        // Remove excessive line breaks (more than 2 consecutive)
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Trim whitespace from each line
        $lines = array_map('trim', explode("\n", $text));
        $text = implode("\n", $lines);

        return trim($text);
    }

    /**
     * Validate that extracted text contains meaningful content.
     */
    public function validateExtractedText(string $text): bool
    {
        // Minimum 100 characters
        if (strlen($text) < 100) {
            return false;
        }

        // Should contain at least some alphabetic characters
        if (!preg_match('/[a-zA-Z]/', $text)) {
            return false;
        }

        return true;
    }
}
