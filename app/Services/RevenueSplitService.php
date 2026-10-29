<?php

namespace App\Services;

use App\ValueObjects\RevenueSplit;

class RevenueSplitService
{
    /**
     * Hitung pembagian pendapatan dari jumlah bruto pesanan.
     */
    public function split(float $grossAmount): RevenueSplit
    {
        return new RevenueSplit($grossAmount);
    }
}
