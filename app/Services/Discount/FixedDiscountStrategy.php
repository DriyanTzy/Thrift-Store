<?php

namespace App\Services\Discount;

use App\Contracts\DiscountStrategy;

class FixedDiscountStrategy implements DiscountStrategy
{
    /**
     * Hitung diskon nominal tetap.
     * Diskon tidak boleh melebihi subtotal.
     */
    public function calculate(float $subtotal, float $value, float $maxDiscount = 0): float
    {
        $discount = min($value, $subtotal);

        // maxDiscount diabaikan untuk nominal tetap — nilainya sudah pasti
        return max(0, $discount);
    }
}
