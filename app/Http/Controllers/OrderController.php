<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\VoucherCheckResource;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderProcessingService;
use App\Services\VoucherValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use InvalidArgumentException;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderProcessingService $orderProcessingService,
        private readonly VoucherValidationService $voucherValidationService
    ) {}

    /**
     * Tampilkan halaman checkout
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $playerTag = $request->query('player_tag') ?? (auth()->check() ? auth()->user()->player_tag ?? auth()->user()->email : 'user@gmail.com');

        // Single product fallback or multiple items from cart
        $productId = $request->query('product_id');
        $cartData = $request->query('items');

        $products = [];
        $totalPrice = 0;

        if ($productId) {
            $product = Product::with('user')->findOrFail($productId);
            if (! $product->isAvailable()) {
                return redirect()->route('products.show', $product->slug)->with('error', 'Maaf, stok barang "'.$product->name.'" sedang habis dan tidak dapat dipesan.');
            }
            $product->checkout_qty = 1;
            $products[] = $product;
            $totalPrice = $product->price;
        } elseif ($cartData) {
            $items = json_decode($cartData, true) ?? [];
            foreach ($items as $item) {
                if (isset($item['id'])) {
                    $p = Product::with('user')->find($item['id']);
                    if ($p && $p->isAvailable()) {
                        $desiredQty = max(1, intval($item['qty'] ?? 1));
                        $p->checkout_qty = min($desiredQty, $p->stock);
                        $products[] = $p;
                        $totalPrice += ($p->price * $p->checkout_qty);
                    }
                }
            }
        }

        if (empty($products)) {
            return redirect('/')->with('error', 'Keranjang belanja Anda kosong atau item yang dipilih sedang kehabisan stok.');
        }

        return view('orders.checkout', compact('products', 'totalPrice', 'playerTag'));
    }

    /**
     * Validasi kode voucher promo via AJAX / API
     */
    public function checkVoucher(Request $request): JsonResponse
    {
        $code = trim($request->input('code', ''));
        $subtotal = floatval($request->input('subtotal', 0));
        $sellerIds = (array) $request->input('seller_ids', []);

        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Masukkan kode voucher terlebih dahulu.']);
        }

        $result = $this->voucherValidationService->check($code, $subtotal, $sellerIds);

        return (new VoucherCheckResource($result))->response();
    }

    /**
     * Proses transaksi: Order masuk ke tahap Pending / Processing melalui Domain Service
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $itemsData = json_decode($request->items, true) ?? [];

        if (empty($itemsData)) {
            return back()->with('error', 'Tidak ada item yang dipilih untuk diproses.');
        }

        try {
            $order = $this->orderProcessingService->createOrder(
                buyer: auth()->user(),
                itemsData: $itemsData,
                playerTag: $request->player_tag,
                paymentMethod: $request->payment_method,
                voucherCode: $request->voucher_code
            );

            if (! auth()->check()) {
                session()->push('guest_order_ids', $order->id);
            }

            return redirect()->route('orders.history')
                ->with('clear_cart', true)
                ->with('success', 'Pesanan #'.$order->order_number.' berhasil dibuat dan masuk tahap Processing (Menunggu Pembayaran). Selesaikan pembayaran atau batalkan pesanan di menu riwayat.');
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Konfirmasi / Selesaikan Pembayaran Pesanan (Finish Transfer)
     */
    public function pay(Order $order): RedirectResponse
    {
        if (auth()->check()) {
            Gate::authorize('pay', $order);
        }

        try {
            $this->orderProcessingService->completePayment($order);

            return redirect()->route('orders.history')
                ->with('success', 'Pembayaran pesanan #'.$order->order_number.' berhasil diverifikasi! Pesanan telah selesai.');
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Pembatalan Pesanan oleh Pengguna (Pulihkan Stok Barang)
     */
    public function cancel(Order $order): RedirectResponse
    {
        if (auth()->check()) {
            Gate::authorize('cancel', $order);
        }

        try {
            $this->orderProcessingService->cancelOrder($order, auth()->id());

            return redirect()->route('orders.history')
                ->with('success', 'Pesanan #'.$order->order_number.' berhasil dibatalkan. Stok barang telah dikembalikan ke inventaris.');
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Riwayat pesanan user dengan tab filter status
     */
    public function history(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = Order::with(['items.product.category', 'items.product.images', 'items.product.user', 'reviews'])
            ->orderBy('created_at', 'desc');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $guestOrderIds = session('guest_order_ids', []);
            $query->whereIn('id', $guestOrderIds);
        }

        if ($status !== 'all') {
            if ($status === OrderStatus::Processing->value) {
                $query->where(function ($q) {
                    $q->where('order_status', OrderStatus::Processing->value)
                        ->orWhere('payment_status', PaymentStatus::Pending->value);
                });
            } else {
                $query->where('order_status', $status);
            }
        }

        $orders = $query->get();

        // Hitung count untuk tab badges
        $baseQuery = auth()->check()
            ? Order::where('user_id', auth()->id())
            : Order::whereIn('id', session('guest_order_ids', []));

        $countAll = (clone $baseQuery)->count();
        $countProcessing = (clone $baseQuery)->where(function ($q) {
            $q->where('order_status', OrderStatus::Processing->value)
                ->orWhere('payment_status', PaymentStatus::Pending->value);
        })->count();
        $countCompleted = (clone $baseQuery)->where('order_status', OrderStatus::Completed->value)->count();
        $countCancelled = (clone $baseQuery)->where('order_status', OrderStatus::Cancelled->value)->count();

        $unpaidTotal = (float) (clone $baseQuery)->where(function ($q) {
            $q->where('order_status', OrderStatus::Processing->value)
                ->orWhere('payment_status', PaymentStatus::Pending->value);
        })->sum('total_price');

        $pendingOrders = (clone $baseQuery)->where(function ($q) {
            $q->where('order_status', OrderStatus::Processing->value)
                ->orWhere('payment_status', PaymentStatus::Pending->value);
        })->with(['items.product'])->latest('created_at')->get();

        return view('orders.history', compact(
            'orders',
            'status',
            'countAll',
            'countProcessing',
            'countCompleted',
            'countCancelled',
            'unpaidTotal',
            'pendingOrders'
        ));
    }

    /**
     * Tampilkan halaman Tanda Terima resmi
     */
    public function receipt(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.category', 'items.product.user', 'user'])
            ->firstOrFail();

        return view('orders.receipt', compact('order'));
    }
}
