<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'player_tag',
        'total_price',
        'voucher_code',
        'discount_amount',
        'payment_method',
        'payment_status',
        'order_status',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'order_status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
        ];
    }

    // ──────────────────────────────────────────────
    //  Relasi
    // ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ──────────────────────────────────────────────
    //  Domain Invariants (FSM guard)
    // ──────────────────────────────────────────────

    /**
     * Pesanan dapat dibatalkan hanya jika masih dalam tahap Processing.
     */
    public function canBeCancelled(): bool
    {
        $orderSt = $this->order_status instanceof OrderStatus ? $this->order_status : OrderStatus::tryFrom($this->order_status);
        $paySt = $this->payment_status instanceof PaymentStatus ? $this->payment_status : PaymentStatus::tryFrom($this->payment_status);

        return $orderSt === OrderStatus::Processing && $paySt === PaymentStatus::Pending;
    }

    /**
     * Pesanan dapat diselesaikan hanya jika masih dalam tahap Processing.
     */
    public function canBeCompleted(): bool
    {
        $orderSt = $this->order_status instanceof OrderStatus ? $this->order_status : OrderStatus::tryFrom($this->order_status);
        $paySt = $this->payment_status instanceof PaymentStatus ? $this->payment_status : PaymentStatus::tryFrom($this->payment_status);

        return $orderSt === OrderStatus::Processing && $paySt === PaymentStatus::Pending;
    }

    /**
     * Apakah produk tertentu termasuk dalam pesanan ini?
     */
    public function containsProduct(int $productId): bool
    {
        return $this->items()->where('product_id', $productId)->exists();
    }

    /**
     * Tentukan nama pengulas secara otomatis berdasarkan status autentikasi atau identitas order.
     */
    public function resolveReviewerName(?User $user = null): string
    {
        if ($user && ! empty($user->name)) {
            return $user->name;
        }

        if (! empty($this->player_tag)) {
            return explode('@', $this->player_tag)[0];
        }

        return 'Pembeli';
    }

    // ──────────────────────────────────────────────
    //  State Transitions
    // ──────────────────────────────────────────────

    /**
     * Tandai pesanan sebagai lunas & selesai.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => PaymentStatus::Paid->value,
            'order_status' => OrderStatus::Completed->value,
        ]);
    }

    /**
     * Tandai pesanan sebagai dibatalkan.
     * Gunakan payment_status=failed karena enum DB tidak memiliki nilai 'cancelled'.
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'payment_status' => PaymentStatus::Failed->value,
            'order_status' => OrderStatus::Cancelled->value,
        ]);
    }

    /**
     * Update status pesanan secara eksplisit dari panel admin.
     */
    public function updateAdministrativeStatus(PaymentStatus|string $paymentStatus, OrderStatus|string $orderStatus): void
    {
        $paymentVal = $paymentStatus instanceof PaymentStatus ? $paymentStatus->value : $paymentStatus;
        $orderVal = $orderStatus instanceof OrderStatus ? $orderStatus->value : $orderStatus;

        $this->update([
            'payment_status' => $paymentVal,
            'order_status' => $orderVal,
        ]);
    }

    // ──────────────────────────────────────────────
    //  Computed Values
    // ──────────────────────────────────────────────

    /**
     * Jumlah yang benar-benar dibayar pembeli (setelah diskon).
     */
    public function getNetAmount(): float
    {
        return max(0, (float) $this->total_price);
    }

    /**
     * Enum typed accessor untuk order_status
     */
    public function getOrderStatusEnumAttribute(): OrderStatus
    {
        return OrderStatus::from($this->order_status);
    }

    /**
     * Enum typed accessor untuk payment_status
     */
    public function getPaymentStatusEnumAttribute(): PaymentStatus
    {
        return PaymentStatus::from($this->payment_status);
    }
}
