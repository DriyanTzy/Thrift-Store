<?php

namespace App\Services\Discount;

use App\Contracts\DiscountStrategy;

class PercentageDiscountStrategy implements DiscountStrategy
{
    /**
     * Hitung diskon berbasis persentase.
     * Contoh: $value=10 artinya diskon 10% dari subtotal.
     */
    public function calculate(float $subtotal, float $value, float $maxDiscount = 0): float
    {
        $discount = ($subtotal * $value) / 100;

        if ($maxDiscount > 0 && $discount > $maxDiscount) {
            $discount = $maxDiscount;
        }

        return max(0, $discount);
    }
}
