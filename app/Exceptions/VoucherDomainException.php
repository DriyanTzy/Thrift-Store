<?php

namespace App\Exceptions;

use DomainException;

class VoucherDomainException extends DomainException
{
    public static function notFound(): self
    {
        return new self('Kode voucher tidak ditemukan atau sudah tidak aktif.');
    }

    public static function quotaExhausted(): self
    {
        return new self('Kuota penukaran voucher ini sudah habis.');
    }

    public static function notActiveYet(): self
    {
        return new self('Voucher promo ini belum aktif.');
    }

    public static function expired(): self
    {
        return new self('Voucher promo ini sudah kedaluwarsa.');
    }

    public static function minSpendNotMet(float $minSpend): self
    {
        return new self('Minimum belanja untuk voucher ini adalah Rp '.number_format($minSpend, 0, ',', '.'));
    }

    public static function sellerMismatch(): self
    {
        return new self('Voucher ini khusus berlaku untuk produk dari etalase toko penerbit.');
    }
}
