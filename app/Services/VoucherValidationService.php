<?php

namespace App\Services;

use App\Enums\VoucherType;
use App\Exceptions\VoucherDomainException;
use App\Models\Voucher;

class VoucherValidationService
{
    /**
     * Validasi voucher dan kembalikan objeknya.
     * Lempar VoucherDomainException dengan pesan UI-ready jika tidak valid.
     *
     * @param  array<int>  $sellerIds  ID seller dari item keranjang
     */
    public function validate(string $code, float $subtotal, array $sellerIds = []): Voucher
    {
        $code = strtoupper(trim($code));

        $voucher = Voucher::where('code', $code)->where('is_active', true)->first();

        if (! $voucher) {
            throw VoucherDomainException::notFound();
        }

        if ($voucher->quota > 0 && $voucher->used_count >= $voucher->quota) {
            throw VoucherDomainException::quotaExhausted();
        }

        if ($voucher->start_date && now()->lt($voucher->start_date)) {
            throw VoucherDomainException::notActiveYet();
        }

        if ($voucher->end_date && now()->gt($voucher->end_date)) {
            throw VoucherDomainException::expired();
        }

        if ($subtotal < $voucher->min_spend) {
            throw VoucherDomainException::minSpendNotMet((float) $voucher->min_spend);
        }

        $typeVal = $voucher->type instanceof VoucherType ? $voucher->type : VoucherType::tryFrom($voucher->type);

        if (
            $typeVal === VoucherType::Seller
            && $voucher->user_id
            && ! in_array($voucher->user_id, $sellerIds)
        ) {
            throw VoucherDomainException::sellerMismatch();
        }

        return $voucher;
    }

    /**
     * Versi "safe" — kembalikan array hasil cek tanpa exception.
     *
     * @param  array<int>  $sellerIds
     * @return array{success: bool, voucher: Voucher|null, discount_amount: float, final_total: float, message: string}
     */
    public function check(string $code, float $subtotal, array $sellerIds = []): array
    {
        try {
            $voucher = $this->validate($code, $subtotal, $sellerIds);
            $discount = $voucher->calculateDiscount($subtotal);
            $finalTotal = max(0, $subtotal - $discount);

            return [
                'success' => true,
                'voucher' => $voucher,
                'discount_amount' => $discount,
                'final_total' => $finalTotal,
                'message' => 'Voucher "'.$voucher->name.'" berhasil diterapkan! Hemat Rp '.number_format($discount, 0, ',', '.'),
            ];
        } catch (VoucherDomainException $e) {
            return [
                'success' => false,
                'voucher' => null,
                'discount_amount' => 0,
                'final_total' => $subtotal,
                'message' => $e->getMessage(),
            ];
        }
    }
}
