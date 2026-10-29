<?php

namespace App\Models;

use App\Enums\DiscountType;
use App\Enums\VoucherType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'user_id',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_discount',
        'quota',
        'used_count',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_spend' => 'float',
        'max_discount' => 'float',
        'quota' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'type' => VoucherType::class,
        'discount_type' => DiscountType::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────
    //  Domain Methods
    // ──────────────────────────────────────────────

    /**
     * Apakah voucher valid untuk transaksi ini?
     *
     * @param  array<int>  $sellerIds
     */
    public function isValidFor(float $subtotal, array $sellerIds = []): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->quota > 0 && $this->used_count >= $this->quota) {
            return false;
        }

        if ($this->start_date && now()->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && now()->gt($this->end_date)) {
            return false;
        }

        if ($subtotal < $this->min_spend) {
            return false;
        }

        $typeVal = $this->type instanceof VoucherType ? $this->type : VoucherType::tryFrom($this->type);

        if (
            $typeVal === VoucherType::Seller
            && $this->user_id
            && ! in_array($this->user_id, $sellerIds)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Hitung diskon via Strategy Pattern melalui DiscountType enum.
     */
    public function calculateDiscount(float $subtotal, ?int $sellerId = null): float
    {
        if (! $this->isValidFor($subtotal, $sellerId ? [$sellerId] : [])) {
            return 0;
        }

        $discountType = $this->discount_type instanceof DiscountType
            ? $this->discount_type
            : (DiscountType::tryFrom($this->discount_type) ?? DiscountType::Fixed);

        $strategy = $discountType->toStrategy();

        return $strategy->calculate($subtotal, $this->discount_value, (float) $this->max_discount);
    }

    /**
     * Tambah pemakaian (konsumsi kuota).
     */
    public function consume(): void
    {
        $this->increment('used_count');
    }

    /**
     * Kembalikan kuota (saat pesanan dibatalkan).
     */
    public function release(): void
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
    }
}
