<?php

namespace App\Models;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung diskon berdasarkan subtotal belanja dan seller_id yang bersangkutan
     */
    public function calculateDiscount(float $subtotal, ?int $sellerId = null): float
    {
        if (! $this->is_active) {
            return 0;
        }

        if ($this->quota > 0 && $this->used_count >= $this->quota) {
            return 0;
        }

        if ($subtotal < $this->min_spend) {
            return 0;
        }

        if ($this->type === 'seller' && $this->user_id && $sellerId !== null && $this->user_id !== $sellerId) {
            return 0;
        }

        $discount = 0;
        if ($this->discount_type === 'percent') {
            $discount = ($subtotal * $this->discount_value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = min($this->discount_value, $subtotal);
        }

        return max(0, $discount);
    }
}
