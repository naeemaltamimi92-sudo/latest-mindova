<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsAppMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $message;
    public ?User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(array $message, ?User $user = null)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new Channel('whatsapp-messages'),
        ];

        if ($this->user) {
            $channels[] = new PrivateChannel('user.' . $this->user->id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'whatsapp.message.received';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->message['id'] ?? null,
            'from' => $this->message['from'] ?? null,
            'type' => $this->message['type'] ?? 'text',
            'content' => $this->getMessageContent(),
            'timestamp' => $this->message['timestamp'] ?? now()->timestamp,
            'user_id' => $this->user?->id,
            'user_name' => $this->user?->name,
        ];
    }

    /**
     * Extract message content based on type.
     */
    protected function getMessageContent(): ?string
    {
        $type = $this->message['type'] ?? 'text';

        return match ($type) {
            'text' => $this->message['text']['body'] ?? null,
            'image' => $this->message['image']['caption'] ?? '[Image]',
            'document' => $this->message['document']['caption'] ?? '[Document]',
            'audio' => '[Audio message]',
            'video' => '[Video message]',
            'location' => '[Location]',
            'contacts' => '[Contact shared]',
            'interactive' => $this->message['interactive']['button_reply']['title']
                ?? $this->message['interactive']['list_reply']['title']
                ?? '[Interactive response]',
            default => null,
        };
    }
}
