<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }

    /** Label bahasa Indonesia untuk UI */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Paid => 'Lunas',
            self::Failed => 'Gagal / Dibatalkan',
        };
    }
}
