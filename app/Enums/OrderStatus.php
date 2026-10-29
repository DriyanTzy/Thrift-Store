<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    /** Label bahasa Indonesia untuk ditampilkan di UI */
    public function label(): string
    {
        return match ($this) {
            self::Processing => 'Menunggu Pembayaran',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /** Warna badge Tailwind */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Processing => 'yellow',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }

    /** Status terminal — tidak dapat diubah lagi */
    public function isFinal(): bool
    {
        return match ($this) {
            self::Completed, self::Cancelled => true,
            default => false,
        };
    }
}
