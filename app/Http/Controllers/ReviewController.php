<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan / rating produk dari pembeli setelah order
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Verify that the product is indeed in this order
        $hasProduct = $order->items()->where('product_id', $request->product_id)->exists();
        if (! $hasProduct) {
            return back()->with('error', 'Produk tidak ditemukan pada pesanan ini.');
        }

        // Determine reviewer name
        $reviewerName = auth()->check()
            ? (auth()->user()->name ?? 'Pengguna')
            : ($order->player_tag ? explode('@', $order->player_tag)[0] : 'Pembeli');

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
