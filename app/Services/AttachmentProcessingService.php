<?php

namespace App\Services;

use App\Models\ChallengeAttachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class AttachmentProcessingService
{
    /**
     * Extract text content from PDF attachment
     *
     * @param ChallengeAttachment $attachment
     * @return string|null
     */
    public function extractText(ChallengeAttachment $attachment): ?string
    {
        try {
            $filePath = Storage::path($attachment->file_path);

            if (!file_exists($filePath)) {
                Log::error("PDF file not found: {$filePath}");
                return null;
            }

            // Only PDF files are supported
            if ($attachment->file_type !== 'pdf') {
                Log::error("Unsupported file type: {$attachment->file_type}");
                return null;
            }

            $extractedText = $this->extractFromPdf($filePath);

            if ($extractedText) {
                $attachment->markAsProcessed($extractedText);
                Log::info("Successfully extracted text from PDF: {$attachment->id}", [
                    'text_length' => strlen($extractedText),
                    'file_name' => $attachment->file_name,
                ]);
            }

            return $extractedText;
        } catch (Exception $e) {
            Log::error("Error extracting text from PDF {$attachment->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract text from PDF file
     * Enhanced extraction for better AI analysis
     */
    private function extractFromPdf(string $filePath): ?string
    {
        try {
            $extractedText = '';

            // Method 1: Try pdftotext command-line tool (most reliable)
            if (function_exists('shell_exec')) {
                $output = shell_exec("pdftotext \"$filePath\" -");
                if ($output && trim($output)) {
                    Log::info("PDF extracted using pdftotext command");
                    return $this->cleanExtractedText($output);
                }
            }

            // Method 2: Try reading PDF with basic text extraction
            // This is a fallback method for when pdftotext is not available
            $content = file_get_contents($filePath);

            // Extract text between text markers in PDF
            if (preg_match_all('/BT\s*(.*?)\s*ET/s', $content, $matches)) {
                foreach ($matches[1] as $text) {
                    // Extract text from Tj and TJ operators
                    if (preg_match_all('/\[(.*?)\]/s', $text, $textMatches)) {
                        foreach ($textMatches[1] as $textContent) {
                            $extractedText .= $textContent . ' ';
                        }
                    }
                }
            }

            if (trim($extractedText)) {
                Log::info("PDF extracted using fallback method");
                return $this->cleanExtractedText($extractedText);
            }

            // If no extraction worked, return basic info
            Log::warning("Could not extract text from PDF, returning metadata");
            return "PDF Document: " . basename($filePath) . "\n\nNote: This PDF contains " .
                   round(filesize($filePath) / 1024, 2) . " KB of data. " .
                   "Text extraction may require manual review.";

        } catch (Exception $e) {
            Log::error("PDF extraction failed: " . $e->getMessage());
            return "PDF Document: " . basename($filePath) . " (extraction failed)";
        }
    }

    /**
     * Clean and format extracted text
     */
    private function cleanExtractedText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove control characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);

        // Trim and return
        return trim($text);
    }


    /**
     * Process all unprocessed attachments for a challenge
     *
     * @param int $challengeId
     * @return array
     */
    public function processAllAttachments(int $challengeId): array
    {
        $attachments = ChallengeAttachment::where('challenge_id', $challengeId)
            ->unprocessed()
            ->get();

        $results = [
            'processed' => 0,
            'failed' => 0,
            'extracted_texts' => [],
        ];

        foreach ($attachments as $attachment) {
            $extractedText = $this->extractText($attachment);

            if ($extractedText) {
                $results['processed']++;
                $results['extracted_texts'][] = [
                    'attachment_id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'text' => $extractedText,
                ];
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Get PDF content for AI analysis
     *
     * @param int $challengeId
     * @return string
     */
    public function getCombinedAttachmentText(int $challengeId): string
    {
        $attachment = ChallengeAttachment::where('challenge_id', $challengeId)
            ->processed()
            ->first();

        if (!$attachment) {
            return '';
        }

        $header = "=== CHALLENGE PDF DOCUMENT ===\n";
        $header .= "File: {$attachment->file_name}\n";
        $header .= "Size: " . round($attachment->file_size / 1024, 2) . " KB\n";
        $header .= "Processed: " . $attachment->processed_at->format('Y-m-d H:i:s') . "\n";
        $header .= "=== CONTENT ===\n\n";

        return $header . $attachment->extracted_text;
    }

    /**
     * Get PDF content directly for a challenge
     *
     * @param int $challengeId
     * @return string|null
     */
    public function getPdfContent(int $challengeId): ?string
    {
        $attachment = ChallengeAttachment::where('challenge_id', $challengeId)
            ->processed()
            ->first();

        return $attachment?->extracted_text;
    }

}
