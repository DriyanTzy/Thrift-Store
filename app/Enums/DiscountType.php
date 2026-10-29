<?php

namespace App\Enums;

use App\Contracts\DiscountStrategy;
use App\Services\Discount\FixedDiscountStrategy;
use App\Services\Discount\PercentageDiscountStrategy;

enum DiscountType: string
{
    case Percent = 'percent';
    case Fixed = 'fixed';

    /** Factory method — menghasilkan Strategy yang sesuai tanpa if/switch di luar */
    public function toStrategy(): DiscountStrategy
    {
        return match ($this) {
            self::Percent => new PercentageDiscountStrategy,
            self::Fixed => new FixedDiscountStrategy,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Percent => 'Persentase (%)',
            self::Fixed => 'Nominal Tetap (Rp)',
        };
    }
}
