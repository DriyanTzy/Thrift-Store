<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatMessageResource;
use App\Models\Conversation;
use App\Services\ChatConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatConversationService $chatService
    ) {}

    /**
     * Tampilkan antarmuka obrolan (Chat Inbox)
     */
    public function index(Request $request): View|RedirectResponse
    {
        $userId = auth()->id();

        // Ambil semua percakapan pengguna via Service
        $conversations = $this->chatService->getUserConversations($userId);

        $activeConversation = null;
        $activeMessages = collect();

        // Jika ada parameter user_id (ingin mulai chat dengan toko/penjual tertentu)
        if ($request->has('user_id') && $request->user_id != $userId) {
            $otherUserId = (int) $request->user_id;
            $productId = $request->query('product_id') ? (int) $request->query('product_id') : null;

            $conversation = $this->chatService->getOrCreateConversation($userId, $otherUserId, $productId);

            return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
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
            // Tandai pesan sebagai terbaca via Service
            $this->chatService->markAsRead($activeConversation, $userId);

            $activeMessages = $activeConversation->messages()
                ->with(['sender', 'product'])
                ->get();
        }

        return view('chat.index', compact('conversations', 'activeConversation', 'activeMessages'));
    }

    /**
     * Kirim pesan baru dalam percakapan
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $userId = auth()->id();

        // Validasi hak akses percakapan via Policy
        Gate::authorize('reply', $conversation);

        $request->validate([
            'message' => 'required|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $productId = $request->product_id ? (int) $request->product_id : null;
        $msg = $this->chatService->sendMessage($conversation, $userId, $request->message, $productId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => new ChatMessageResource($msg),
            ]);
        }

        return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
    }

    /**
     * Ambil pesan-pesan baru via polling AJAX
     */
    public function fetchMessages(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = auth()->id();

        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $lastMessageId = (int) $request->query('last_id', 0);

        // Ambil pesan setelah ID tertentu
        $newMessages = $conversation->messages()
            ->where('id', '>', $lastMessageId)
            ->with(['sender', 'product'])
            ->get();

        // Tandai terbaca pesan yang diterima via Service
        $this->chatService->markAsRead($conversation, $userId);

        return response()->json([
            'success' => true,
            'messages' => ChatMessageResource::collection($newMessages),
        ]);
    }

    /**
     * Ambil jumlah chat belum terbaca untuk navbar badge
     */
    public function unreadCount(): JsonResponse
    {
        $count = auth()->user()->unread_chat_count;

        return response()->json([
            'unread_count' => $count,
        ]);
    }
}
