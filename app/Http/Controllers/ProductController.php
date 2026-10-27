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
        $query = Product::availableInStore()->with(['category', 'images', 'reviews', 'user']);

        // Fitur pencarian berdasarkan nama, deskripsi, atau badge produk
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%'.$searchQuery.'%')
                    ->orWhere('description', 'like', '%'.$searchQuery.'%')
                    ->orWhere('badge', 'like', '%'.$searchQuery.'%');
            });
        }

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
            $matchedUsers = User::where('is_admin', false)
                ->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'like', '%'.$searchQuery.'%')
                        ->orWhere('store_name', 'like', '%'.$searchQuery.'%')
                        ->orWhere('player_tag', 'like', '%'.$searchQuery.'%')
                        ->orWhere('store_city', 'like', '%'.$searchQuery.'%');
                })
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
