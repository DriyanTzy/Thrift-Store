<?php

namespace App\Exceptions;

use DomainException;

class OrderDomainException extends DomainException
{
    public static function outOfStock(): self
    {
        return new self('Item yang Anda pilih saat ini sedang kehabisan stok!');
    }

    public static function cannotBeCompleted(): self
    {
        return new self('Pesanan ini sudah selesai atau dibatalkan.');
    }

    public static function cannotBeCancelled(): self
    {
        return new self('Hanya pesanan yang masih menunggu pembayaran (Processing) yang dapat dibatalkan.');
    }
}
