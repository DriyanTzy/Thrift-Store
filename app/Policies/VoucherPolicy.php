<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Penjual hanya boleh mengelola voucher miliknya sendiri.
     */
    public function manage(User $user, Voucher $voucher): bool
    {
        return $voucher->user_id === $user->id;
    }
}
