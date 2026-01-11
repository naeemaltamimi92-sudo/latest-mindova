<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ChallengeAttachment extends Model
{
    protected $fillable = [
        'challenge_id',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'uploaded_by',
        'extracted_text',
        'processed',
        'processed_at',
    ];

    protected $casts = [
        'processed' => 'boolean',
        'processed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Allowed file types and their MIME types (PDF only)
     */
    public const ALLOWED_TYPES = [
        'pdf' => ['application/pdf'],
    ];

    /**
     * Maximum file size in bytes (10MB)
     */
    public const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Get the challenge this attachment belongs to
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the user who uploaded this attachment
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get human-readable file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file type is allowed
     */
    public static function isAllowedType(string $mimeType): bool
    {
        foreach (self::ALLOWED_TYPES as $types) {
            if (in_array($mimeType, $types)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get file extension from MIME type
     */
    public static function getExtensionFromMime(string $mimeType): ?string
    {
        foreach (self::ALLOWED_TYPES as $ext => $types) {
            if (in_array($mimeType, $types)) {
                return $ext;
            }
        }
        return null;
    }

    /**
     * Mark attachment as processed
     */
    public function markAsProcessed(string $extractedText = null): void
    {
        $this->update([
            'processed' => true,
            'processed_at' => now(),
            'extracted_text' => $extractedText,
        ]);
    }

    /**
     * Scope for unprocessed attachments
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }

    /**
     * Scope for processed attachments
     */
    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }
}
