<?php

namespace App\ValueObjects;

use App\Models\Product;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Value Object immutable — metrik dan ringkasan etalase toko seller.
 */
final class StoreMetrics
{
    /**
     * @param  Collection<int, Product>  $products
     * @param  Collection<int, Review>  $reviews
     * @param  Collection<int, StockMovement>  $stockMovements
     */
    public function __construct(
        public readonly int $totalItems,
        public readonly int $activeItems,
        public readonly int $soldOutItems,
        public readonly float $rating,
        public readonly int $reviewCount,
        public readonly int $totalStockIn,
        public readonly int $totalStockOut,
        public readonly int $currentStock,
    ) {}

    /**
     * Factory method untuk mengkalkulasi metrik toko milik seorang User / Seller.
     */
    public static function forUser(User $user): self
    {
        $products = Product::where('user_id', $user->id)
            ->with(['reviews'])
            ->get();

        $productIds = $products->pluck('id');

        $totalItems = $products->count();
        $activeItems = $products->where('stock', '>', 0)->count();
        $soldOutItems = $products->where('stock', '<=', 0)->count();
        $currentStock = (int) $products->sum('stock');

        $storeReviews = Review::whereIn('product_id', $productIds)->get();
        $reviewCount = $storeReviews->count();
        $rating = $reviewCount > 0 ? round((float) $storeReviews->avg('rating'), 1) : 5.0;

        $totalStockIn = (int) StockMovement::whereIn('product_id', $productIds)
            ->where('type', 'in')
            ->sum('quantity');

        $totalStockOut = (int) StockMovement::whereIn('product_id', $productIds)
            ->where('type', 'out')
            ->sum('quantity');

        return new self(
            totalItems: $totalItems,
            activeItems: $activeItems,
            soldOutItems: $soldOutItems,
            rating: $rating,
            reviewCount: $reviewCount,
            totalStockIn: $totalStockIn,
            totalStockOut: $totalStockOut,
            currentStock: $currentStock
        );
    }
}
