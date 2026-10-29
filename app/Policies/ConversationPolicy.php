<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Izinkan admin mengakses percakapan apapun jika diperlukan.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * User hanya boleh melihat/membalas percakapan jika merupakan pembeli atau penjual terkait.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->buyer_id === $user->id || $conversation->seller_id === $user->id;
    }

    public function reply(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }
}
