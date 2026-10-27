<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $recentOrders = Order::with(['items', 'user'])->orderBy('created_at', 'desc')->take(8)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCategories',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
