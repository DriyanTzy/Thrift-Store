<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Tampilkan halaman checkout
     */
    public function checkout(Request $request)
    {
        $playerTag = $request->query('player_tag') ?? (auth()->check() ? auth()->user()->player_tag ?? auth()->user()->email : 'user@gmail.com');

        // Single product fallback or multiple items from cart
        $productId = $request->query('product_id');
        $cartData = $request->query('items'); // JSON string or comma separated ids

        $products = [];
        $totalPrice = 0;

        if ($productId) {
            $product = Product::with('user')->findOrFail($productId);
            if ($product->stock <= 0) {
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
                    // Hanya izinkan produk yang stoknya tersedia
                    if ($p && $p->stock > 0) {
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
    public function checkVoucher(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        $subtotal = floatval($request->input('subtotal', 0));
        $sellerIds = (array) $request->input('seller_ids', []);

        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Masukkan kode voucher terlebih dahulu.']);
        }

        $voucher = Voucher::where('code', $code)->where('is_active', true)->first();

        if (! $voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak ditemukan atau sudah tidak aktif.']);
        }

        if ($voucher->quota > 0 && $voucher->used_count >= $voucher->quota) {
            return response()->json(['success' => false, 'message' => 'Kuota penukaran voucher ini sudah habis.']);
        }

        if ($voucher->start_date && now()->lt($voucher->start_date)) {
            return response()->json(['success' => false, 'message' => 'Voucher promo ini belum aktif.']);
        }

        if ($voucher->end_date && now()->gt($voucher->end_date)) {
            return response()->json(['success' => false, 'message' => 'Voucher promo ini sudah kedaluwarsa.']);
        }

        if ($subtotal < $voucher->min_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum belanja untuk voucher ini adalah Rp '.number_format($voucher->min_spend, 0, ',', '.'),
            ]);
        }

        if ($voucher->type === 'seller' && $voucher->user_id) {
            if (! in_array($voucher->user_id, $sellerIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher ini khusus berlaku untuk produk dari etalase toko penerbit.',
                ]);
            }
        }

        $discount = $voucher->calculateDiscount($subtotal);
        $finalTotal = max(0, $subtotal - $discount);

        return response()->json([
            'success' => true,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'discount_amount' => $discount,
            'final_total' => $finalTotal,
            'message' => 'Voucher "'.$voucher->name.'" berhasil diterapkan! Hemat Rp '.number_format($discount, 0, ',', '.'),
        ]);
    }

    /**
     * Proses transaksi: Order masuk ke tahap Pending / Processing (tidak langsung auto-paid)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|string',
            'player_tag' => 'required|string|min:3',
            'payment_method' => 'required|string',
            'voucher_code' => 'nullable|string',
        ]);

        $itemsData = json_decode($request->items, true) ?? [];

        if (empty($itemsData)) {
            return back()->with('error', 'Tidak ada item yang dipilih untuk diproses.');
        }

        $orderNumber = 'RP-'.date('Ymd').'-'.strtoupper(Str::random(6));
        $playerTag = strtolower(trim($request->player_tag));

        $totalPrice = 0;
        $orderItems = [];
        $sellerIds = [];

        foreach ($itemsData as $itemData) {
            $p = Product::find($itemData['id']);
            if ($p && $p->stock >= 1) {
                if ($p->user_id) {
                    $sellerIds[] = $p->user_id;
                }
                $requestedQty = max(1, intval($itemData['qty'] ?? 1));
                $qty = min($requestedQty, $p->stock);
                $subtotal = $p->price * $qty;
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'price' => $p->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];

                // Potong stok per ukuran jika ada
                $chosenSize = $itemData['size'] ?? null;
                $currentSizes = $p->size_stocks;
                if (! empty($currentSizes)) {
                    if ($chosenSize && isset($currentSizes[$chosenSize])) {
                        $currentSizes[$chosenSize] = max(0, $currentSizes[$chosenSize] - $qty);
                    } else {
                        foreach ($currentSizes as $szKey => $szVal) {
                            if ($szVal > 0) {
                                $currentSizes[$szKey] = max(0, $szVal - $qty);
                                break;
                            }
                        }
                    }
                    $p->sizes = json_encode($currentSizes);
                }

                $stockBefore = $p->stock;
                $stockAfter = max(0, $stockBefore - $qty);
                $p->stock = $stockAfter;
                $p->save();

                // Catat mutasi stok keluar
                StockMovement::create([
                    'product_id' => $p->id,
                    'user_id' => auth()->id(),
                    'type' => 'out',
                    'quantity' => $qty,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'price' => $p->price,
                    'cost_price' => $p->cost_price ?: round($p->price * 0.7),
                    'reference_type' => 'order',
                    'reference_id' => $orderNumber,
                    'notes' => 'Pesanan Baru (Processing) #'.$orderNumber,
                ]);
            }
        }

        if (empty($orderItems)) {
            return back()->with('error', 'Item yang Anda pilih saat ini sedang kehabisan stok!');
        }

        // Hitung diskon voucher jika ada
        $voucherCode = strtoupper(trim($request->input('voucher_code', '')));
        $discountAmount = 0;
        if (! empty($voucherCode)) {
            $voucher = Voucher::where('code', $voucherCode)->where('is_active', true)->first();
            if ($voucher && ($voucher->quota <= 0 || $voucher->used_count < $voucher->quota) && $totalPrice >= $voucher->min_spend) {
                // Periksa jika voucher penjual
                if ($voucher->type !== 'seller' || in_array($voucher->user_id, $sellerIds)) {
                    $discountAmount = $voucher->calculateDiscount($totalPrice);
                    $voucher->increment('used_count');
                } else {
                    $voucherCode = null;
                }
            } else {
                $voucherCode = null;
            }
        }

        $finalPrice = max(0, $totalPrice - $discountAmount);

        // Pesanan dibuat dengan status PENDING & PROCESSING
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'player_tag' => $playerTag,
            'total_price' => $finalPrice,
            'voucher_code' => $voucherCode ?: null,
            'discount_amount' => $discountAmount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'processing',
        ]);

        foreach ($orderItems as $oItem) {
            $oItem['order_id'] = $order->id;
            OrderItem::create($oItem);
        }

        if (auth()->check()) {
            if (empty(auth()->user()->player_tag)) {
                auth()->user()->update(['player_tag' => $playerTag]);
            }
        } else {
            session()->push('guest_order_ids', $order->id);
        }

        return redirect()->route('orders.history')
            ->with('clear_cart', true)
            ->with('success', 'Pesanan #'.$orderNumber.' berhasil dibuat dan masuk tahap Processing (Menunggu Pembayaran). Selesaikan pembayaran atau batalkan pesanan di menu riwayat.');
    }

    /**
     * Konfirmasi / Selesaikan Pembayaran Pesanan (Finish Transfer)
     */
    public function pay(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id() && ! auth()->user()->is_admin) {
            abort(403);
        }

        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan ini sudah dibatalkan dan tidak dapat dibayar.');
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        return redirect()->route('orders.history')
            ->with('success', 'Pembayaran pesanan #'.$order->order_number.' berhasil diverifikasi! Pesanan telah selesai.');
    }

    /**
     * Pembatalan Pesanan oleh Pengguna (Pulihkan Stok Barang)
     */
    public function cancel(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id() && ! auth()->user()->is_admin) {
            abort(403);
        }

        if ($order->order_status !== 'processing' && $order->payment_status !== 'pending') {
            return back()->with('error', 'Hanya pesanan yang masih menunggu pembayaran (Processing) yang dapat dibatalkan.');
        }

        // Kembalikan stok untuk setiap barang pesanan
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore + $item->quantity;
                $product->stock = $stockAfter;
                $product->save();

                // Catat mutasi pengembalian stok
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
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

        // Pulihkan kuota voucher jika ada
        if ($order->voucher_code) {
            Voucher::where('code', $order->voucher_code)->decrement('used_count');
        }

        $order->update([
            'payment_status' => 'failed',
            'order_status' => 'cancelled',
        ]);

        return redirect()->route('orders.history')
            ->with('success', 'Pesanan #'.$order->order_number.' berhasil dibatalkan. Stok barang telah dikembalikan ke inventaris.');
    }

    /**
     * Riwayat pesanan user dengan tab filter status
     */
    public function history(Request $request)
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
            if ($status === 'processing') {
                $query->where(function ($q) {
                    $q->where('order_status', 'processing')->orWhere('payment_status', 'pending');
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
            $q->where('order_status', 'processing')->orWhere('payment_status', 'pending');
        })->count();
        $countCompleted = (clone $baseQuery)->where('order_status', 'completed')->count();
        $countCancelled = (clone $baseQuery)->where('order_status', 'cancelled')->count();

        $unpaidTotal = (float) (clone $baseQuery)->where(function ($q) {
            $q->where('order_status', 'processing')->orWhere('payment_status', 'pending');
        })->sum('total_price');

        $pendingOrders = (clone $baseQuery)->where(function ($q) {
            $q->where('order_status', 'processing')->orWhere('payment_status', 'pending');
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
    public function receipt(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product.category', 'items.product.user', 'user'])
            ->firstOrFail();

        return view('orders.receipt', compact('order'));
    }
}
