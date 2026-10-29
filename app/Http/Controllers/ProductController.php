<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan katalog produk ThriftStore (hanya menampilkan barang yang ada stoknya)
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategorySlug = $request->query('category');
        $searchQuery = $request->query('q');

        // Barang yang stoknya habis tidak tampil di etalase toko
        $query = Product::availableInStore()
            ->search($searchQuery)
            ->with(['category', 'images', 'reviews', 'user']);

        if ($selectedCategorySlug) {
            $category = Category::where('slug', $selectedCategorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $products = $query->orderBy('id', 'desc')->get();

        // Fitur pencarian User & Toko (Profil Admin dikecualikan / tidak bisa dicari demi privasi)
        $matchedUsers = collect();
        if ($searchQuery) {
            $matchedUsers = User::searchablePublic($searchQuery)
                ->withCount('products')
                ->take(6)
                ->get();
        }

        return view('products.index', compact('products', 'categories', 'selectedCategorySlug', 'searchQuery', 'matchedUsers'));
    }

    /**
     * Tampilkan detail produk.
     * Jika diakses dari referensi (struk, riwayat transaksi, tautan langsung),
     * barang tetap tampil meskipun stok habis, namun dengan penanda Stok Habis dan tombol order dinonaktifkan.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'images', 'reviews', 'user'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->availableInStore()
            ->with(['category', 'images', 'reviews', 'user'])
            ->take(5)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
