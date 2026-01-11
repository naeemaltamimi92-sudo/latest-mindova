<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'user_id',
        'whatsapp_message_id',
        'phone_number',
        'contact_name',
        'direction',
        'type',
        'template_name',
        'content',
        'payload',
        'response',
        'status',
        'error_message',
        'sent_at',
        'delivered_at',
        'read_at',
        'received_at',
        'status_updated_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'received_at' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for incoming messages.
     */
    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('direction', 'incoming');
    }

    /**
     * Scope for outgoing messages.
     */
    public function scopeOutgoing(Builder $query): Builder
    {
        return $query->where('direction', 'outgoing');
    }

    /**
     * Scope for messages by status.
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for sent messages.
     */
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for delivered messages.
     */
    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for read messages.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope for failed messages.
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for messages by phone number.
     */
    public function scopeForPhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone_number', $phone);
    }

    /**
     * Scope for template messages.
     */
    public function scopeTemplates(Builder $query): Builder
    {
        return $query->where('type', 'template');
    }

    /**
     * Scope for text messages.
     */
    public function scopeTextMessages(Builder $query): Builder
    {
        return $query->where('type', 'text');
    }

    /**
     * Scope for messages in date range.
     */
    public function scopeInDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for today's messages.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Check if message is incoming.
     */
    public function isIncoming(): bool
    {
        return $this->direction === 'incoming';
    }

    /**
     * Check if message is outgoing.
     */
    public function isOutgoing(): bool
    {
        return $this->direction === 'outgoing';
    }

    /**
     * Check if message was delivered.
     */
    public function wasDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'read']);
    }

    /**
     * Check if message was read.
     */
    public function wasRead(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Check if message failed.
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark message as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'status_updated_at' => now(),
        ]);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
            'status_updated_at' => now(),
        ]);
    }

    /**
     * Mark message as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'status_updated_at' => now(),
        ]);
    }

    /**
     * Get formatted phone number.
     */
    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->phone_number;

        if (strlen($phone) === 12 && str_starts_with($phone, '966')) {
            return '+' . substr($phone, 0, 3) . ' ' . substr($phone, 3, 2) . ' ' . substr($phone, 5, 3) . ' ' . substr($phone, 8);
        }

        return '+' . $phone;
    }

    /**
     * Get display name (contact name or formatted phone).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->contact_name ?? $this->user?->name ?? $this->formatted_phone;
    }

    /**
     * Get a short preview of the content.
     */
    public function getContentPreviewAttribute(): string
    {
        if (!$this->content) {
            return '[No content]';
        }

        return strlen($this->content) > 50
            ? substr($this->content, 0, 50) . '...'
            : $this->content;
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'sent' => 'blue',
            'delivered' => 'green',
            'read' => 'emerald',
            'failed' => 'red',
            'pending' => 'yellow',
            'received' => 'indigo',
            default => 'gray',
        };
    }

    /**
     * Get statistics for a user.
     */
    public static function getStatsForUser(int $userId): array
    {
        return [
            'total_sent' => self::where('user_id', $userId)->outgoing()->count(),
            'total_received' => self::where('user_id', $userId)->incoming()->count(),
            'delivered' => self::where('user_id', $userId)->outgoing()->delivered()->count(),
            'read' => self::where('user_id', $userId)->outgoing()->read()->count(),
            'failed' => self::where('user_id', $userId)->outgoing()->failed()->count(),
        ];
    }

    /**
     * Get daily message counts.
     */
    public static function getDailyStats(int $days = 7): array
    {
        return self::selectRaw('DATE(created_at) as date, direction, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date', 'direction')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($group) {
                return [
                    'incoming' => $group->where('direction', 'incoming')->sum('count'),
                    'outgoing' => $group->where('direction', 'outgoing')->sum('count'),
                ];
            })
            ->toArray();
    }
}
