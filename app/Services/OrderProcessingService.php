<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Events\OrderCancelled;
use App\Events\OrderPlaced;
use App\Exceptions\OrderDomainException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderProcessingService
{
    public function __construct(
        private readonly StockLedgerService $ledger,
        private readonly VoucherValidationService $voucherService,
    ) {}

    /**
     * Buat pesanan baru dalam satu atomic transaction.
     *
     * @param  array<array{id: int, qty: int, size?: string}>  $itemsData
     *
     * @throws InvalidArgumentException jika keranjang kosong / voucher invalid
     */
    public function createOrder(
        ?User $buyer,
        array $itemsData,
        string $playerTag,
        string $paymentMethod,
        ?string $voucherCode = null,
    ): Order {
        return DB::transaction(function () use ($buyer, $itemsData, $playerTag, $paymentMethod, $voucherCode) {
            $orderNumber = 'RP-'.date('Ymd').'-'.strtoupper(Str::random(6));
            $playerTag = strtolower(trim($playerTag));

            [$orderItems, $subtotal, $sellerIds] = $this->prepareItems($itemsData);

            if (empty($orderItems)) {
                throw OrderDomainException::outOfStock();
            }

            // Validasi & terapkan voucher
            [$discountAmount, $resolvedCode] = $this->applyVoucher($voucherCode, $subtotal, $sellerIds);
            $finalPrice = max(0, $subtotal - $discountAmount);

            // Potong stok & catat ke ledger
            foreach ($orderItems as $item) {
                $this->ledger->record(
                    product: $item['product'],
                    quantity: $item['quantity'],
                    type: 'out',
                    reason: 'Pesanan Baru (Processing) #'.$orderNumber,
                    reference: $orderNumber,
                    userId: $buyer?->id,
                );

                // Sinkronkan stok per ukuran jika ada
                $this->syncSizeStock($item['product'], $item['quantity'], $item['size'] ?? null);
            }

            // Buat record Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $buyer?->id,
                'player_tag' => $playerTag,
                'total_price' => $finalPrice,
                'voucher_code' => $resolvedCode,
                'discount_amount' => $discountAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => PaymentStatus::Pending->value,
                'order_status' => OrderStatus::Processing->value,
            ]);

            // Buat item pesanan
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['product']->price * $item['quantity'],
                ]);
            }

            // Update player_tag user jika belum ada
            if ($buyer && empty($buyer->player_tag)) {
                $buyer->update(['player_tag' => $playerTag]);
            }

            if ($resolvedCode) {
                Voucher::where('code', $resolvedCode)->increment('used_count');
            }

            // Dispatch domain event: OrderPlaced
            OrderPlaced::dispatch($order);

            return $order;
        });
    }

    /**
     * Konfirmasi pembayaran pesanan → status Completed.
     *
     * @throws OrderDomainException jika pesanan tidak bisa diselesaikan
     */
    public function completePayment(Order $order): void
    {
        if (! $order->canBeCompleted()) {
            throw OrderDomainException::cannotBeCompleted();
        }

        $order->markAsPaid();
    }

    /**
     * Batalkan pesanan → pulihkan stok + kuota voucher.
     *
     * @throws OrderDomainException jika pesanan tidak bisa dibatalkan
     */
    public function cancelOrder(Order $order, ?int $actorId = null): void
    {
        if (! $order->canBeCancelled()) {
            throw OrderDomainException::cannotBeCancelled();
        }

        DB::transaction(function () use ($order, $actorId) {
            $this->ledger->restoreFromOrder($order, $actorId);

            // Pulihkan kuota voucher
            if ($order->voucher_code) {
                Voucher::where('code', $order->voucher_code)->decrement('used_count');
            }

            $order->markAsCancelled();

            // Dispatch domain event: OrderCancelled
            OrderCancelled::dispatch($order, $actorId);
        });
    }

    // ──────────────────────────────────────────────
    //  Private helpers
    // ──────────────────────────────────────────────

    /**
     * Siapkan array item yang valid beserta subtotal dan seller IDs.
     *
     * @param  array<array{id: int, qty?: int, size?: string}>  $itemsData
     * @return array{0: array<array{product: Product, quantity: int, size: string|null}>, 1: float, 2: array<int>}
     */
    private function prepareItems(array $itemsData): array
    {
        $orderItems = [];
        $subtotal = 0.0;
        $sellerIds = [];

        foreach ($itemsData as $itemData) {
            $product = Product::find($itemData['id']);
            if (! $product || $product->stock < 1) {
                continue;
            }

            $qty = min(max(1, intval($itemData['qty'] ?? 1)), $product->stock);

            if ($product->user_id) {
                $sellerIds[] = $product->user_id;
            }

            $subtotal += $product->price * $qty;
            $orderItems[] = [
                'product' => $product,
                'quantity' => $qty,
                'size' => $itemData['size'] ?? null,
            ];
        }

        return [$orderItems, $subtotal, $sellerIds];
    }

    /**
     * Validasi voucher dan kembalikan [diskon, kodeVoucher].
     *
     * @param  array<int>  $sellerIds
     * @return array{0: float, 1: string|null}
     */
    private function applyVoucher(?string $voucherCode, float $subtotal, array $sellerIds): array
    {
        if (empty($voucherCode)) {
            return [0.0, null];
        }

        $result = $this->voucherService->check($voucherCode, $subtotal, $sellerIds);

        if (! $result['success']) {
            // Voucher tidak valid — abaikan (tidak batalkan order)
            return [0.0, null];
        }

        return [$result['discount_amount'], $result['voucher']->code];
    }

    /**
     * Sinkronkan stok per ukuran (sizes JSON) setelah stok utama dipotong.
     */
    private function syncSizeStock(Product $product, int $qty, ?string $chosenSize): void
    {
        $currentSizes = $product->size_stocks;
        if (empty($currentSizes)) {
            return;
        }

        if ($chosenSize && isset($currentSizes[$chosenSize])) {
            $currentSizes[$chosenSize] = max(0, $currentSizes[$chosenSize] - $qty);
        } else {
            foreach ($currentSizes as $key => $val) {
                if ($val > 0) {
                    $currentSizes[$key] = max(0, $val - $qty);
                    break;
                }
            }
        }

        $product->sizes = json_encode($currentSizes);
        $product->saveQuietly();
    }
}
