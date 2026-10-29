<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;

class StockLedgerService
{
    /**
     * Catat satu mutasi stok ke ledger.
     *
     * @param  string  $type  'in' | 'out'
     * @param  string  $reason  Keterangan singkat
     * @param  string  $reference  Nomor referensi (order number, dll.)
     */
    public function record(
        Product $product,
        int $quantity,
        string $type,
        string $reason,
        string $reference,
        ?int $userId = null,
    ): StockMovement {
        $stockBefore = $product->stock;
        $stockAfter = $type === 'out'
            ? max(0, $stockBefore - $quantity)
            : $stockBefore + $quantity;

        $product->stock = $stockAfter;
        $product->save();

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'price' => $product->price,
            'cost_price' => $product->cost_price ?: round($product->price * 0.7),
            'reference_type' => $reference,
            'reference_id' => $reference,
            'notes' => $reason,
        ]);
    }

    /**
     * Pulihkan stok semua item dari satu pesanan yang dibatalkan.
     */
    public function restoreFromOrder(Order $order, ?int $actorId = null): void
    {
        foreach ($order->items as $item) {
            /** @var OrderItem $item */
            $product = $item->product;
            if (! $product) {
                continue;
            }

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore + $item->quantity;

            $product->stock = $stockAfter;
            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $actorId,
                'type' => 'in',
                'quantity' => $item->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'price' => $product->price,
                'cost_price' => $product->cost_price ?: round($product->price * 0.7),
                'reference_type' => 'order_cancel',
                'reference_id' => $order->order_number,
                'notes' => 'Pengembalian stok pembatalan pesanan #'.$order->order_number,
            ]);
        }
    }
}
