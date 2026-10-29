<?php

namespace App\ValueObjects;

/**
 * Value Object immutable — pembagian pendapatan per transaksi.
 *
 * Rumus:
 *   - Applicator fee : 5% dari gross
 *   - Pajak          : 3% dari gross
 *   - Operasional    : 4% dari gross
 *   - Net seller     : 88% (100% - 12%)
 */
final class RevenueSplit
{
    private const APPLICATOR_RATE = 0.05;

    private const TAX_RATE = 0.03;

    private const OPERATIONAL_RATE = 0.04;

    /** @param  float  $grossAmount  Total yang dibayar pembeli (setelah diskon) */
    public function __construct(private readonly float $grossAmount) {}

    public function getGrossAmount(): float
    {
        return $this->grossAmount;
    }

    public function getApplicatorFee(): float
    {
        return round($this->grossAmount * self::APPLICATOR_RATE, 2);
    }

    public function getTax(): float
    {
        return round($this->grossAmount * self::TAX_RATE, 2);
    }

    public function getOperationalCost(): float
    {
        return round($this->grossAmount * self::OPERATIONAL_RATE, 2);
    }

    public function getTotalDeductions(): float
    {
        return round($this->getApplicatorFee() + $this->getTax() + $this->getOperationalCost(), 2);
    }

    public function getNetSellerAmount(): float
    {
        return round($this->grossAmount - $this->getTotalDeductions(), 2);
    }

    /** @return array{gross: float, applicator_fee: float, tax: float, operational: float, deductions: float, net_seller: float} */
    public function toArray(): array
    {
        return [
            'gross' => $this->getGrossAmount(),
            'applicator_fee' => $this->getApplicatorFee(),
            'tax' => $this->getTax(),
            'operational' => $this->getOperationalCost(),
            'deductions' => $this->getTotalDeductions(),
            'net_seller' => $this->getNetSellerAmount(),
        ];
    }
}
