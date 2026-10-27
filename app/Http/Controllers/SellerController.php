<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    /**
     * Tampilkan Halaman Toko & Etalase Saya (Dashboard Penjual)
     */
    public function store()
    {
        $user = auth()->user();
        $products = Product::where('user_id', $user->id)
            ->with(['category', 'images', 'reviews', 'stockMovements'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalItems = $products->count();
        $activeItems = $products->where('stock', '>', 0)->count();
        $soldOutItems = $products->where('stock', '<=', 0)->count();

        // Calculate store overall rating
        $productIds = $products->pluck('id');
        $storeReviews = Review::whereIn('product_id', $productIds)->get();
        $storeReviewCount = $storeReviews->count();
        $storeRating = $storeReviewCount > 0 ? round($storeReviews->avg('rating'), 1) : 5.0;

        // Riwayat Mutasi Stok & Harga (Masuk, Keluar, Sisa)
        $stockMovements = StockMovement::whereIn('product_id', $productIds)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        $totalStockIn = (int) StockMovement::whereIn('product_id', $productIds)
            ->where('type', 'in')
            ->sum('quantity');

        $totalStockOut = (int) StockMovement::whereIn('product_id', $productIds)
            ->where('type', 'out')
            ->sum('quantity');

        $totalCurrentStock = (int) $products->sum('stock');

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
    public function storeVoucher(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:30|unique:vouchers,code',
            'name' => 'required|string|max:100',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['type'] = 'seller';
        $validated['user_id'] = auth()->id();
        $validated['min_spend'] = $validated['min_spend'] ?? 0;
        $validated['is_active'] = true;

        Voucher::create($validated);

        return back()->with('success', 'Kode voucher promo etalase toko berhasil dibuat!');
    }

    /**
     * Aktifkan / Nonaktifkan voucher toko
     */
    public function toggleVoucher(Voucher $voucher)
    {
        if ($voucher->user_id !== auth()->id() && ! auth()->user()->is_admin) {
            abort(403);
        }

        $voucher->update(['is_active' => ! $voucher->is_active]);

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Voucher promo {$voucher->code} berhasil {$status}.");
    }

    /**
     * Hapus voucher toko
     */
    public function destroyVoucher(Voucher $voucher)
    {
        if ($voucher->user_id !== auth()->id() && ! auth()->user()->is_admin) {
            abort(403);
        }

        $voucher->delete();

        return back()->with('success', "Voucher promo {$voucher->code} berhasil dihapus.");
    }

    /**
     * Tampilkan Halaman Toko Publik Penjual (Bisa dilihat siapa saja)
     */
    public function showStore($userId)
    {
        $seller = User::findOrFail($userId);
        $products = Product::where('user_id', $seller->id)
            ->with(['category', 'images', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalItems = $products->count();
        $activeItems = $products->where('stock', '>', 0)->count();
        $soldOutItems = $products->where('stock', '<=', 0)->count();

        $productIds = $products->pluck('id');
        $storeReviews = Review::whereIn('product_id', $productIds)->with(['product', 'user'])->get();
        $storeReviewCount = $storeReviews->count();
        $storeRating = $storeReviewCount > 0 ? round($storeReviews->avg('rating'), 1) : 5.0;

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
     * Form Tambah Barang ke Etalase
     */
    public function createProduct()
    {
        $categories = Category::all();

        return view('seller.create_product', compact('categories'));
    }

    /**
     * Simpan Barang Baru ke Etalase Toko
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'badge' => 'nullable|string|max:100',
            'sizes' => 'nullable|array',
            'description' => 'required|string',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'images.required' => 'Upload minimal 1 foto barang.',
            'images.max' => 'Maksimal 5 foto per barang.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran foto maksimal 2MB per file.',
        ]);

        $slug = Str::slug($request->name).'-'.strtolower(Str::random(5));

        // Use first image as main product thumbnail
        $mainImagePath = null;
        if ($request->hasFile('images')) {
            $mainImagePath = $request->file('images')[0]->store('products', 'public');
        }

        // Process size stocks: map of ["Size" => StockQty]
        $sizeStocks = [];
        $totalStock = 0;
        if ($request->has('size_stocks') && is_array($request->size_stocks)) {
            foreach ($request->size_stocks as $sz => $qty) {
                $q = max(0, intval($qty));
                if ($q > 0) {
                    $sizeStocks[$sz] = $q;
                    $totalStock += $q;
                }
            }
        }

        // Fallback if user filled general stock but no individual size stocks
        if (empty($sizeStocks)) {
            $totalStock = max(1, intval($request->stock));
            if ($request->has('sizes') && is_array($request->sizes)) {
                $each = max(1, (int) floor($totalStock / max(1, count($request->sizes))));
                foreach ($request->sizes as $sz) {
                    $sizeStocks[$sz] = $each;
                }
            } else {
                $sizeStocks['All Size'] = $totalStock;
            }
        }

        $sizesJson = json_encode($sizeStocks);
        $costPrice = $request->cost_price ?: round($request->price * 0.7);

        $product = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'cost_price' => $costPrice,
            'stock' => $totalStock,
            'sizes' => $sizesJson,
            'badge' => $request->badge,
            'image' => $mainImagePath,
            'is_active' => true,
        ]);

        // Catat stok awal masuk (In) secara akurat di kartu stok
        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => 'in',
            'quantity' => $totalStock,
            'stock_before' => 0,
            'stock_after' => $totalStock,
            'price' => $request->price,
            'cost_price' => $costPrice,
            'reference_type' => 'initial',
            'notes' => 'Stok awal barang baru di etalase',
        ]);

        // Store all images in product_images table
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = ($index === 0 && $mainImagePath)
                    ? $mainImagePath
                    : $imageFile->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('seller.store')->with('success', 'Barang "'.$request->name.'" berhasil ditambahkan ke Etalase Toko Anda dengan stok '.$totalStock.' item!');
    }

    /**
     * Tambah Stok (Restock) atau Penyesuaian Stok & Harga Akurat
     */
    public function updateStock(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'type' => 'required|in:in,adjustment',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $stockBefore = $product->stock;

        if ($request->type === 'in') {
            $stockAfter = $stockBefore + (int) $request->quantity;
            $notes = $request->notes ?: ('Restock penambahan +'.$request->quantity.' pcs');
            $refType = 'restock';
        } else {
            // Penyesuaian / adjustment ke jumlah baru
            $stockAfter = max(0, (int) $request->quantity);
            $notes = $request->notes ?: ('Penyesuaian stok menjadi '.$stockAfter.' pcs');
            $refType = 'adjustment';
        }

        $product->stock = $stockAfter;

        if ($request->filled('price')) {
            $product->price = $request->price;
        }

        if ($request->filled('cost_price')) {
            $product->cost_price = $request->cost_price;
        }

        $product->save();

        // Catat mutasi stok
        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->type === 'in' ? (int) $request->quantity : abs($stockAfter - $stockBefore),
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'price' => $product->price,
            'cost_price' => $product->cost_price,
            'reference_type' => $refType,
            'notes' => $notes,
        ]);

        return back()->with('success', 'Stok & Harga produk "'.$product->name.'" berhasil diperbarui! Sisa stok terkini: '.$stockAfter.' pcs.');
    }

    /**
     * Hapus Barang dari Etalase
     */
    public function destroyProduct(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        // Delete associated images from storage
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $product->delete();

        return redirect()->route('seller.store')->with('success', 'Barang berhasil dihapus dari etalase Anda.');
    }
}
