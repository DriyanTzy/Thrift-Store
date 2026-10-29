<?php

namespace App\Http\Controllers;

use App\DTO\CreateProductDTO;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use App\Services\ProductCatalogService;
use App\ValueObjects\StoreMetrics;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function __construct(
        private readonly ProductCatalogService $catalogService
    ) {}

    /**
     * Tampilkan Halaman Toko & Etalase Saya (Dashboard Penjual)
     */
    public function store(): View
    {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)
            ->with(['category', 'images', 'reviews', 'stockMovements'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung metrik toko via Value Object StoreMetrics
        $metrics = StoreMetrics::forUser($user);

        $totalItems = $metrics->totalItems;
        $activeItems = $metrics->activeItems;
        $soldOutItems = $metrics->soldOutItems;
        $storeRating = $metrics->rating;
        $storeReviewCount = $metrics->reviewCount;
        $totalStockIn = $metrics->totalStockIn;
        $totalStockOut = $metrics->totalStockOut;
        $totalCurrentStock = $metrics->currentStock;

        // Riwayat Mutasi Stok & Harga
        $productIds = $products->pluck('id');
        $stockMovements = StockMovement::whereIn('product_id', $productIds)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        // Daftar Voucher Promo Toko Penjual
        $vouchers = Voucher::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('seller.store', compact(
            'user',
            'products',
            'totalItems',
            'activeItems',
            'soldOutItems',
            'storeRating',
            'storeReviewCount',
            'stockMovements',
            'totalStockIn',
            'totalStockOut',
            'totalCurrentStock',
            'vouchers'
        ));
    }

    /**
     * Simpan kode voucher / promo toko baru milik seller
     */
    public function storeVoucher(StoreVoucherRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['type'] = 'seller';
        $validated['user_id'] = auth()->id();
        $validated['min_spend'] = $validated['min_spend'] ?? 0;
        $validated['is_active'] = true;

        Voucher::create($validated);

        return back()->with('success', 'Kode voucher promo etalase toko berhasil dibuat!');
    }

    /**
     * Ubah status aktif / nonaktif voucher toko
     */
    public function toggleVoucher(Voucher $voucher): RedirectResponse
    {
        Gate::authorize('manage', $voucher);

        $voucher->update(['is_active' => ! $voucher->is_active]);
        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Voucher "'.$voucher->code.'" berhasil '.$statusText.'.');
    }

    /**
     * Hapus voucher toko
     */
    public function destroyVoucher(Voucher $voucher): RedirectResponse
    {
        Gate::authorize('manage', $voucher);

        $voucher->delete();

        return back()->with('success', 'Voucher promo toko berhasil dihapus.');
    }

    /**
     * Tampilkan Halaman Profil Toko Publik (Dilihat oleh Pembeli lain)
     */
    public function showStore(int|string $userId): View
    {
        $seller = User::findOrFail($userId);

        $products = Product::where('user_id', $seller->id)
            ->where('is_active', true)
            ->with(['category', 'images', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();

        $metrics = StoreMetrics::forUser($seller);

        $totalItems = $metrics->totalItems;
        $activeItems = $metrics->activeItems;
        $soldOutItems = $metrics->soldOutItems;
        $storeRating = $metrics->rating;
        $storeReviewCount = $metrics->reviewCount;

        $productIds = $products->pluck('id');
        $storeReviews = Review::whereIn('product_id', $productIds)->with(['product', 'user'])->get();

        return view('seller.public_store', compact(
            'seller',
            'products',
            'totalItems',
            'activeItems',
            'soldOutItems',
            'storeRating',
            'storeReviewCount',
            'storeReviews'
        ));
    }

    /**
     * Alias untuk route yang memakai model binding (jika ada)
     */
    public function publicStore(User $seller): View
    {
        return $this->showStore($seller->id);
    }

    /**
     * Form Tambah Barang ke Etalase
     */
    public function createProduct(): View
    {
        $categories = Category::all();

        return view('seller.create_product', compact('categories'));
    }

    /**
     * Simpan Barang Baru ke Etalase Toko via ProductCatalogService
     */
    public function storeProduct(StoreProductRequest $request): RedirectResponse
    {
        $dto = CreateProductDTO::fromArray(
            data: $request->validated(),
            images: $request->file('images', [])
        );

        $product = $this->catalogService->createProduct(auth()->user(), $dto);

        return redirect()->route('seller.store')->with('success', 'Barang "'.$product->name.'" berhasil ditambahkan ke Etalase Toko Anda dengan stok '.$product->stock.' item!');
    }

    /**
     * Tambah Stok (Restock) atau Penyesuaian Stok & Harga Akurat
     */
    public function updateStock(UpdateStockRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('updateStock', $product);

        $stockBefore = $product->stock;
        $qty = intval($request->quantity);

        if ($request->type === 'in') {
            $stockAfter = $stockBefore + $qty;
            $typeLabel = 'in';
            $defaultNotes = 'Penambahan Stok Barang (Restock) +'.$qty.' item';
        } else {
            $stockAfter = $qty;
            $qtyDiff = $stockAfter - $stockBefore;
            $typeLabel = $qtyDiff >= 0 ? 'in' : 'out';
            $defaultNotes = 'Penyesuaian Manual Stok dari '.$stockBefore.' menjadi '.$stockAfter;
        }

        $newPrice = $request->filled('price') ? $request->price : $product->price;
        $newCostPrice = $request->filled('cost_price') ? $request->cost_price : $product->cost_price;

        $product->update([
            'stock' => max(0, $stockAfter),
            'price' => $newPrice,
            'cost_price' => $newCostPrice,
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $typeLabel,
            'quantity' => abs($stockAfter - $stockBefore),
            'stock_before' => $stockBefore,
            'stock_after' => max(0, $stockAfter),
            'price' => $newPrice,
            'cost_price' => $newCostPrice ?: round($newPrice * 0.7),
            'reference_type' => 'manual',
            'notes' => $request->notes ?: $defaultNotes,
        ]);

        return redirect()->route('seller.store')->with('success', 'Stok dan harga barang "'.$product->name.'" berhasil diperbarui!');
    }

    /**
     * Update Pengaturan Informasi Toko Penjual
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'store_name' => 'required|string|max:100',
            'store_slogan' => 'nullable|string|max:255',
            'store_city' => 'nullable|string|max:100',
            'store_phone' => 'nullable|string|max:20',
            'store_description' => 'nullable|string|max:1000',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'store_name' => $request->store_name,
            'store_slogan' => $request->store_slogan,
            'store_city' => $request->store_city,
            'store_phone' => $request->store_phone,
            'store_description' => $request->store_description,
        ];

        if ($request->hasFile('store_logo')) {
            if ($user->store_logo) {
                Storage::disk('public')->delete($user->store_logo);
            }
            $data['store_logo'] = $request->file('store_logo')->store('stores/logos', 'public');
        }

        if ($request->hasFile('store_banner')) {
            if ($user->store_banner) {
                Storage::disk('public')->delete($user->store_banner);
            }
            $data['store_banner'] = $request->file('store_banner')->store('stores/banners', 'public');
        }

        $user->update($data);

        return redirect()->route('seller.store')->with('success', 'Informasi etalase toko berhasil diperbarui!');
    }
}
