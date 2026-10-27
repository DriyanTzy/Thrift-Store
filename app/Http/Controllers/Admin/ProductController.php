<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'user', 'images'])->orderBy('id', 'desc')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'badge' => 'nullable|string|max:100',
            'image' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        $validated['is_active'] = $request->has('is_active');
        $validated['user_id'] = auth()->id();

        $product = Product::create($validated);

        if ($product->stock > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $product->stock,
                'stock_before' => 0,
                'stock_after' => $product->stock,
                'price' => $product->price,
                'cost_price' => $product->cost_price ?: round($product->price * 0.7),
                'reference_type' => 'initial',
                'notes' => 'Stok awal barang ditambahkan oleh Admin',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan dan mutasi stok tercatat!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'badge' => 'nullable|string|max:100',
            'image' => 'nullable|string',
        ]);

        if ($product->name !== $request->name) {
            $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        }

        $validated['is_active'] = $request->has('is_active');

        $oldStock = $product->stock;
        $newStock = (int) $validated['stock'];

        $product->update($validated);

        if ($oldStock !== $newStock) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $newStock > $oldStock ? 'in' : 'adjustment',
                'quantity' => abs($newStock - $oldStock),
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'price' => $product->price,
                'cost_price' => $product->cost_price,
                'reference_type' => $newStock > $oldStock ? 'restock' : 'adjustment',
                'notes' => 'Penyesuaian stok oleh Admin dari '.$oldStock.' menjadi '.$newStock.' pcs',
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui dan mutasi stok tercatat!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
