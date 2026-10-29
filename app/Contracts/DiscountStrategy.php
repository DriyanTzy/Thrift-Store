<?php

namespace App\Contracts;

interface DiscountStrategy
{
    /**
     * Hitung nilai diskon.
     *
     * @param  float  $subtotal  Total belanja sebelum diskon
     * @param  float  $value  Nilai diskon (persentase atau nominal)
     * @param  float  $maxDiscount  Batas maksimum diskon (0 = tidak ada batas)
     */
    public function calculate(float $subtotal, float $value, float $maxDiscount = 0): float;
}
