<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class ChatConversationService
{
    /**
     * Dapatkan semua percakapan milik pengguna (sebagai pembeli atau penjual).
     *
     * @return Collection<int, Conversation>
     */
    public function getUserConversations(int $userId): Collection
    {
        return Conversation::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->with(['buyer', 'seller', 'product', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Cari percakapan antara dua pengguna atau buat percakapan baru.
     */
    public function getOrCreateConversation(int $userId, int $otherUserId, ?int $productId = null): Conversation
    {
        $conversation = Conversation::where(function ($q) use ($userId, $otherUserId) {
            $q->where('buyer_id', $userId)->where('seller_id', $otherUserId);
        })->orWhere(function ($q) use ($userId, $otherUserId) {
            $q->where('buyer_id', $otherUserId)->where('seller_id', $userId);
        })->first();

        if (! $conversation) {
            User::findOrFail($otherUserId);

            $conversation = Conversation::create([
                'buyer_id' => $userId,
                'seller_id' => $otherUserId,
                'product_id' => $productId,
                'last_message_at' => now(),
            ]);

            // Kirim salam pembuka otomatis jika user membuka chat dari kartu produk
            if ($productId) {
                $product = Product::find($productId);
                $intro = $product && $product->stock <= 0
                    ? 'Halo, apakah produk "'.$product->name.'" akan segera restock?'
                    : 'Halo, apakah produk "'.($product ? $product->name : 'ini').'" masih tersedia?';

                ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $userId,
                    'receiver_id' => $otherUserId,
                    'product_id' => $productId,
                    'message' => $intro,
                    'is_read' => false,
                ]);
            }
        } elseif ($productId && ! $conversation->product_id) {
            $conversation->update(['product_id' => $productId]);
        }

        return $conversation;
    }

    /**
     * Kirim pesan baru dalam percakapan.
     */
    public function sendMessage(Conversation $conversation, int $senderId, string $message, ?int $productId = null): ChatMessage
    {
        $receiverId = ($conversation->buyer_id === $senderId)
            ? $conversation->seller_id
            : $conversation->buyer_id;

        $msg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'product_id' => $productId ?: $conversation->product_id,
            'message' => trim($message),
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        return $msg;
    }

    /**
     * Tandai semua pesan yang diterima pengguna dalam percakapan menjadi terbaca (is_read = true).
     */
    public function markAsRead(Conversation $conversation, int $readerId): void
    {
        ChatMessage::where('conversation_id', $conversation->id)
            ->where('receiver_id', $readerId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
