<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type', // 'in', 'out', 'adjustment'
        'quantity',
        'stock_before',
        'stock_after',
        'price',
        'cost_price',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'stock_before' => 'integer',
            'stock_after' => 'integer',
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat mutasi stok baru secara ringkas dan terstruktur.
     */
    public static function record(
        Product $product,
        string $type,
        int $qty,
        string $notes,
        ?float $unitCost = null,
        ?float $unitPrice = null,
        ?int $userId = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): self {
        $stockBefore = (int) $product->stock;
        $stockAfter = match ($type) {
            'in' => $stockBefore + $qty,
            'out' => max(0, $stockBefore - $qty),
            'adjustment' => $qty,
            default => $stockBefore,
        };

        return static::create([
            'product_id' => $product->id,
            'user_id' => $userId ?: auth()->id(),
            'type' => $type,
            'quantity' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'price' => $unitPrice ?? $product->price,
            'cost_price' => $unitCost ?? $product->cost_price ?? 0,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }
}
