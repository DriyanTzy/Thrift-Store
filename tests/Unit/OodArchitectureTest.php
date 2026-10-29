<?php

namespace Tests\Unit;

use App\Contracts\DiscountStrategy;
use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\VoucherType;
use App\Services\Discount\FixedDiscountStrategy;
use App\Services\Discount\PercentageDiscountStrategy;
use App\ValueObjects\RevenueSplit;
use PHPUnit\Framework\TestCase;

class OodArchitectureTest extends TestCase
{
    public function test_discount_type_factory_returns_strategy(): void
    {
        $percentStrategy = DiscountType::Percent->toStrategy();
        $this->assertInstanceOf(DiscountStrategy::class, $percentStrategy);
        $this->assertInstanceOf(PercentageDiscountStrategy::class, $percentStrategy);

        $fixedStrategy = DiscountType::Fixed->toStrategy();
        $this->assertInstanceOf(DiscountStrategy::class, $fixedStrategy);
        $this->assertInstanceOf(FixedDiscountStrategy::class, $fixedStrategy);
    }

    public function test_percentage_discount_calculation_with_cap(): void
    {
        $strategy = new PercentageDiscountStrategy;

        // 10% of 100,000 = 10,000
        $discount = $strategy->calculate(subtotal: 100000, value: 10, maxDiscount: 0);
        $this->assertEquals(10000, $discount);

        // 50% of 100,000 = 50,000, capped at 20,000
        $capped = $strategy->calculate(subtotal: 100000, value: 50, maxDiscount: 20000);
        $this->assertEquals(20000, $capped);
    }

    public function test_fixed_discount_calculation(): void
    {
        $strategy = new FixedDiscountStrategy;

        // 25,000 fixed
        $discount = $strategy->calculate(subtotal: 100000, value: 25000);
        $this->assertEquals(25000, $discount);

        // Fixed higher than subtotal capped at subtotal
        $discountExceeded = $strategy->calculate(subtotal: 15000, value: 25000);
        $this->assertEquals(15000, $discountExceeded);
    }

    public function test_revenue_split_immutable_value_object(): void
    {
        $gross = 100000.0;
        $split = new RevenueSplit($gross);

        // Applicator fee (5%) = 5,000
        $this->assertEquals(5000.0, $split->getApplicatorFee());

        // Tax (3%) = 3,000
        $this->assertEquals(3000.0, $split->getTax());

        // Operational cost (4%) = 4,000
        $this->assertEquals(4000.0, $split->getOperationalCost());

        // Total deductions (12%) = 12,000
        $this->assertEquals(12000.0, $split->getTotalDeductions());

        // Net seller (88%) = 88,000
        $this->assertEquals(88000.0, $split->getNetSellerAmount());

        $this->assertEquals([
            'gross' => 100000.0,
            'applicator_fee' => 5000.0,
            'tax' => 3000.0,
            'operational' => 4000.0,
            'deductions' => 12000.0,
            'net_seller' => 88000.0,
        ], $split->toArray());
    }

    public function test_order_status_enums(): void
    {
        $this->assertFalse(OrderStatus::Processing->isFinal());
        $this->assertTrue(OrderStatus::Completed->isFinal());
        $this->assertTrue(OrderStatus::Cancelled->isFinal());

        $this->assertEquals('Menunggu Pembayaran', OrderStatus::Processing->label());
        $this->assertEquals('yellow', OrderStatus::Processing->badgeColor());
    }

    public function test_payment_status_enums(): void
    {
        $this->assertTrue(PaymentStatus::Paid->isPaid());
        $this->assertFalse(PaymentStatus::Pending->isPaid());
        $this->assertFalse(PaymentStatus::Failed->isPaid());
    }

    public function test_voucher_type_enums(): void
    {
        $this->assertEquals('platform', VoucherType::Platform->value);
        $this->assertEquals('seller', VoucherType::Seller->value);
    }
}
