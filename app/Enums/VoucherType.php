<?php

namespace App\Enums;

enum VoucherType: string
{
    case Platform = 'platform';
    case Seller = 'seller';

    public function label(): string
    {
        return match ($this) {
            self::Platform => 'Platform (Ruang Lemari)',
            self::Seller => 'Voucher Toko',
        };
    }
}
