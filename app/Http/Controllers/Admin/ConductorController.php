<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ConductorController extends Controller
{
    /**
     * Tampilkan Conductor Control Center (Pusat Kendali Admin Terpadu)
     */
    public function index()
    {
        // 1. Metrik Keuangan & Penjualan
        $totalGrossRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $totalOrdersCount = Order::count();
        $completedOrdersCount = Order::where('order_status', 'completed')->count();
        $pendingOrdersCount = Order::where('order_status', 'pending')->orWhere('payment_status', 'pending')->count();

        // Bisnis Logic: Harga masuk 100% - 12% potongan
        // 5% profit aplikator + 3% pajak + 4% operasional = 12% total potongan
        $applicatorProfitRate = 0.05;
        $taxRate = 0.03;
        $operationalRate = 0.04;
        $totalDeductionRate = $applicatorProfitRate + $taxRate + $operationalRate; // 0.12 = 12%

        $applicatorProfit = $totalGrossRevenue * $applicatorProfitRate;
        $taxAmount = $totalGrossRevenue * $taxRate;
        $operationalCost = $totalGrossRevenue * $operationalRate;
        $totalDeductions = $totalGrossRevenue * $totalDeductionRate;
        $netRevenue = $totalGrossRevenue - $totalDeductions; // 88% dari gross

        // 2. Metrik Pengguna & Ekosistem (1 akun tetap 1 akun walau membuka store/toko)
        $totalUsers = User::distinct()->count('id');
        $totalAdmins = User::where('is_admin', true)->count();
        $totalSellers = User::where(function ($q) {
            $q->whereHas('products')->orWhereNotNull('store_name');
        })->count();
        $totalBuyers = max(0, $totalUsers - $totalSellers);

        // 3. Metrik Inventaris & Katalog
        $totalProducts = Product::count();
        $inStockProducts = Product::where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', '<=', 0)->count();
        $totalUnitsInStock = Product::sum('stock');
        $totalCategories = Category::count();

        // Nilai Valuasi Inventaris (Estimasi Aset Barang)
        $inventoryMarketValue = Product::select(DB::raw('SUM(stock * price) as val'))->value('val') ?? 0;
        $inventoryCostValue = Product::select(DB::raw('SUM(stock * COALESCE(cost_price, 0)) as val'))->value('val') ?? 0;

        // 4. Metrik Interaksi & Chat
        $totalConversations = Conversation::count();
        $totalMessages = ChatMessage::count();

        // 5. Audit Integritas Stok & Masalah
        $criticalStockProducts = Product::where('stock', '<=', 0)->with('user')->take(6)->get();
        $negativeStockCount = Product::where('stock', '<', 0)->count();

        // 6. Log Pergerakan Stok Terkini (Ledger Feed)
        $recentStockMovements = StockMovement::with(['product', 'user'])
            ->latest('id')
            ->take(8)
            ->get();

        // 7. Pesanan Terbaru
        $recentOrders = Order::with(['items', 'user'])
            ->latest('id')
            ->take(6)
            ->get();

        // 8. Roster Pengguna Terbaru
        $recentUsers = User::withCount(['products', 'orders'])
            ->latest('id')
            ->take(8)
            ->get();

        // 9. Diagnostik Server & Lingkungan
        $diagnostics = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => app()->environment(),
            'app_debug' => config('app.debug') ? 'Aktif (Development)' : 'Nonaktif (Production)',
            'database_driver' => DB::connection()->getDriverName(),
            'database_name' => DB::connection()->getDatabaseName(),
            'storage_linked' => file_exists(public_path('storage')),
            'server_time' => now()->format('d M Y, H:i:s T'),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2).' MB',
        ];

        // 10. Daftar Voucher & Promo Event (Platform & Seller)
        $vouchers = Voucher::with('user')->orderBy('id', 'desc')->get();

        return view('admin.conductor.index', compact(
            'totalGrossRevenue',
            'netRevenue',
            'applicatorProfit',
            'taxAmount',
            'operationalCost',
            'totalDeductions',
            'totalDeductionRate',
            'totalOrdersCount',
            'completedOrdersCount',
            'pendingOrdersCount',
            'totalUsers',
            'totalAdmins',
            'totalSellers',
            'totalBuyers',
            'totalProducts',
            'inStockProducts',
            'outOfStockProducts',
            'totalUnitsInStock',
            'totalCategories',
            'inventoryMarketValue',
            'inventoryCostValue',
            'totalConversations',
            'totalMessages',
            'criticalStockProducts',
            'negativeStockCount',
            'recentStockMovements',
            'recentOrders',
            'recentUsers',
            'diagnostics',
            'vouchers'
        ));
    }

    /**
     * Eksekusi aksi sistem dari Conductor Control Center
     */
    public function executeAction(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'clear_cache':
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                Artisan::call('route:clear');
                Artisan::call('config:clear');

                return back()->with('success', 'Conductor: Seluruh cache aplikasi, view, route, dan config berhasil dibersihkan!');

            case 'link_storage':
                if (! file_exists(public_path('storage'))) {
                    Artisan::call('storage:link');

                    return back()->with('success', 'Conductor: Symbolic link public storage berhasil dibuat!');
                }

                return back()->with('success', 'Conductor: Symbolic link public/storage sudah aktif dan valid.');

            case 'toggle_admin':
                $userId = $request->input('user_id');
                $targetUser = User::findOrFail($userId);

                if ($targetUser->id === auth()->id()) {
                    return back()->with('error', 'Conductor: Anda tidak dapat mengubah hak akses admin pada akun Anda sendiri!');
                }

                $targetUser->is_admin = ! $targetUser->is_admin;
                $targetUser->save();

                $statusText = $targetUser->is_admin ? 'dipromosikan menjadi Admin' : 'diturunkan dari Admin';

                return back()->with('success', "Conductor: Pengguna {$targetUser->name} berhasil {$statusText}!");

            case 'stock_audit':
                $outOfStockCount = Product::where('stock', '<=', 0)->count();
                $totalStock = Product::sum('stock');
                $movementsCount = StockMovement::count();

                return back()->with('success', "Conductor Audit Selesai: Total unit stok aktif adalah {$totalStock} unit. Ditemukan {$outOfStockCount} produk stok habis. Total histori mutasi tercatat: {$movementsCount} transaksi.");

            case 'create_voucher':
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
                $validated['type'] = 'platform';
                $validated['user_id'] = null;
                $validated['min_spend'] = $validated['min_spend'] ?? 0;
                $validated['is_active'] = true;

                Voucher::create($validated);

                return back()->with('success', "Conductor: Voucher Promo Aplikator {$validated['code']} berhasil diterbitkan dan berlaku platform-wide!");

            case 'toggle_voucher':
                $voucherId = $request->input('voucher_id');
                $voucher = Voucher::findOrFail($voucherId);
                $voucher->update(['is_active' => ! $voucher->is_active]);

                $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

                return back()->with('success', "Conductor: Voucher {$voucher->code} berhasil {$statusText}!");

            case 'delete_voucher':
                $voucherId = $request->input('voucher_id');
                $voucher = Voucher::findOrFail($voucherId);
                $voucher->delete();

                return back()->with('success', "Conductor: Voucher {$voucher->code} berhasil dihapus.");

            default:
                return back()->with('error', 'Conductor: Aksi tidak dikenali atau tidak didukung.');
        }
    }
}
