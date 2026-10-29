<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Izinkan admin melakukan aksi apapun pada order.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Apakah user berhak melihat/mengakses detail order ini?
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    /**
     * Apakah user berhak membayar pesanan ini?
     */
    public function pay(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->canBeCompleted();
    }

    /**
     * Apakah user berhak membatalkan pesanan ini?
     */
    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->canBeCancelled();
    }
}
