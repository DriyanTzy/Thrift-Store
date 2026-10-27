<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Tampilkan antarmuka obrolan (Chat Inbox)
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Ambil semua percakapan pengguna (baik sebagai pembeli maupun penjual)
        $conversations = Conversation::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->with(['buyer', 'seller', 'product', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $activeConversation = null;
        $activeMessages = collect();

        // Jika ada parameter user_id (ingin mulai chat dengan toko/penjual tertentu)
        if ($request->has('user_id') && $request->user_id != $userId) {
            $otherUserId = (int) $request->user_id;
            $productId = $request->query('product_id');

            // Cari apakah sudah ada obrolan
            $activeConversation = Conversation::where(function ($q) use ($userId, $otherUserId) {
                $q->where('buyer_id', $userId)->where('seller_id', $otherUserId);
            })->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('buyer_id', $otherUserId)->where('seller_id', $userId);
            })->first();

            // Jika belum ada percakapan, buat baru
            if (! $activeConversation) {
                $otherUser = User::findOrFail($otherUserId);
                $activeConversation = Conversation::create([
                    'buyer_id' => $userId,
                    'seller_id' => $otherUserId,
                    'product_id' => $productId,
                    'last_message_at' => now(),
                ]);

                // Kirim salam pembuka otomatis jika dari produk
                if ($productId) {
                    $prod = Product::find($productId);
                    $intro = $prod && $prod->stock <= 0
                        ? 'Halo, apakah produk "'.$prod->name.'" akan segera restock?'
                        : 'Halo, apakah produk "'.($prod ? $prod->name : 'ini').'" masih tersedia?';

                    ChatMessage::create([
                        'conversation_id' => $activeConversation->id,
                        'sender_id' => $userId,
                        'receiver_id' => $otherUserId,
                        'product_id' => $productId,
                        'message' => $intro,
                        'is_read' => false,
                    ]);
                }
            } elseif ($productId && ! $activeConversation->product_id) {
                $activeConversation->update(['product_id' => $productId]);
            }

            return redirect()->route('chat.index', ['conversation_id' => $activeConversation->id]);
        }

        // Tentukan percakapan aktif berdasarkan conversation_id atau percakapan pertama
        $conversationId = $request->query('conversation_id');
        if ($conversationId) {
            $activeConversation = $conversations->firstWhere('id', (int) $conversationId);
        }

        if (! $activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = $conversations->first();
        }

        if ($activeConversation) {
            // Tandai pesan belum dibaca sebagai terbaca
            ChatMessage::where('conversation_id', $activeConversation->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $activeMessages = $activeConversation->messages()
                ->with(['sender', 'product'])
                ->get();
        }

        return view('chat.index', compact('conversations', 'activeConversation', 'activeMessages'));
    }

    /**
     * Kirim pesan baru dalam percakapan
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $userId = auth()->id();

        // Validasi hak akses percakapan
        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            abort(403, 'Anda tidak berhak mengakses percakapan ini.');
        }

        $request->validate([
            'message' => 'required|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $receiverId = ($conversation->buyer_id === $userId) ? $conversation->seller_id : $conversation->buyer_id;

        $msg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'product_id' => $request->product_id ?: $conversation->product_id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('H:i'),
                    'is_me' => true,
                ],
            ]);
        }

        return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
    }

    /**
     * API untuk AJAX polling pesan baru (realtime tanpa reload halaman)
     */
    public function fetchMessages(Request $request, Conversation $conversation)
    {
        $userId = auth()->id();

        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastMessageId = (int) $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->get();

        // Tandai yang diterima sebagai terbaca
        ChatMessage::where('conversation_id', $conversation->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages->map(function ($m) use ($userId) {
                return [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'sender_name' => $m->sender->name,
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('H:i'),
                    'is_me' => ($m->sender_id === $userId),
                ];
            }),
        ]);
    }

    /**
     * API untuk menghitung total pesan belum dibaca (badge notifikasi navbar)
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
