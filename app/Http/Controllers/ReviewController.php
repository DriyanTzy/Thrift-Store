<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan / rating produk dari pembeli setelah order
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $order = Order::findOrFail($request->order_id);

        // Verifikasi kepemilikan produk dalam pesanan via Domain Method
        if (! $order->containsProduct((int) $request->product_id)) {
            return back()->with('error', 'Produk tidak ditemukan pada pesanan ini.');
        }

        // Tentukan nama pengulas via Domain Method
        $reviewerName = $order->resolveReviewerName(auth()->user());

        // Check if already reviewed
        $existing = Review::where('order_id', $order->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'reviewer_name' => $reviewerName,
            ]);

            return back()->with('success', 'Ulasan & rating bintang Anda berhasil diperbarui!');
        }

        Review::create([
            'order_id' => $order->id,
            'product_id' => $request->product_id,
            'user_id' => auth()->id() ?? $order->user_id,
            'reviewer_name' => $reviewerName,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan & rating bintang berhasil dikirim.');
    }
}
