<?php

namespace App\Http\Resources;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ChatMessage
 */
class ChatMessageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth()->id();

        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'message' => $this->message,
            'created_at' => $this->created_at?->format('H:i'),
            'is_me' => $this->sender_id === $userId,
            'sender_name' => $this->sender?->name ?? 'User',
            'sender_avatar' => $this->sender?->avatar_url ?? $this->sender?->store_logo_url,
        ];
    }
}
