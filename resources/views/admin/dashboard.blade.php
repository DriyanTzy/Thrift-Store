@extends('layouts.admin')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.85rem; font-weight: 900; margin-bottom: 0.25rem; color: #0F172A;">Dashboard Overview</h1>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Ringkasan metrik penjualan, inventaris katalog, dan aktivitas pesanan marketplace Ruang Lemari.</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    
    <!-- Total Pendapatan -->
    <div class="admin-card" style="border-left: 4px solid #22C55E; background: #FFFFFF; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Total Pendapatan</span>
            <div style="width: 38px; height: 38px; background: rgba(34,197,94,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #16A34A; font-size: 1.1rem;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </div>
        <span style="font-size: 0.76rem; color: #16A34A; margin-top: 0.4rem; display: block; font-weight: 700;">
            <i class="fa-solid fa-circle-check"></i> Dari transaksi terbayar (Paid)
        </span>
    </div>

    <!-- Total Transaksi -->
    <div class="admin-card" style="border-left: 4px solid #3B82F6; background: #FFFFFF; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Total Transaksi</span>
            <div style="width: 38px; height: 38px; background: rgba(59,130,246,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563EB; font-size: 1.1rem;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
            {{ $totalOrders }} Pesanan
        </div>
        <span style="font-size: 0.76rem; color: #64748B; margin-top: 0.4rem; display: block; font-weight: 600;">
            Total order masuk di sistem
        </span>
    </div>

    <!-- Produk Aktif -->
    <div class="admin-card" style="border-left: 4px solid #8B5CF6; background: #FFFFFF; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Produk Aktif</span>
            <div style="width: 38px; height: 38px; background: rgba(139,92,246,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7C3AED; font-size: 1.1rem;">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
            {{ $totalProducts }} Item
        </div>
        <span style="font-size: 0.76rem; color: #64748B; margin-top: 0.4rem; display: block; font-weight: 600;">
            Terbagi dalam {{ $totalCategories }} kategori
        </span>
    </div>

    <!-- Stok Menipis -->
    <div class="admin-card" style="border-left: 4px solid #EF4444; background: #FFFFFF; border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Stok Menipis</span>
            <div style="width: 38px; height: 38px; background: rgba(239,68,68,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #DC2626; font-size: 1.1rem;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 900; color: #DC2626; letter-spacing: -0.5px;">
            {{ $lowStockProducts->count() }} Item
        </div>
        <span style="font-size: 0.76rem; color: #EF4444; margin-top: 0.4rem; display: block; font-weight: 700;">
            Stok &le; 5 unit, perlu restok
        </span>
    </div>

</div>

<!-- Recent Transactions Table -->
<div class="admin-card" style="padding: 0; overflow: hidden; border-radius: 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #FFFFFF;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">Pesanan Terbaru</h3>
            <span style="font-size: 0.78rem; color: #64748B;">Daftar transaksi pesanan terkini yang masuk dari pembeli</span>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-secondary" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px;">
            Lihat Semua Pesanan <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="sc-table" style="margin-top: 0;">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem; width: 18%;">No Pesanan</th>
                    <th style="width: 22%;">Pembeli / ID Pengguna</th>
                    <th style="width: 24%;">Item Produk</th>
                    <th style="width: 14%;">Total</th>
                    <th style="width: 10%;">Pembayaran</th>
                    <th style="width: 12%;">Status Pesanan</th>
                    <th style="text-align: right; padding-right: 1.5rem; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $ord)
                    <tr>
                        <td style="padding-left: 1.5rem;">
                            <span style="font-weight: 800; color: #0F172A; font-family: monospace; font-size: 0.88rem; display: block;">
                                #{{ $ord->order_number }}
                            </span>
                            <span style="font-size: 0.72rem; color: #94A3B8;">
                                {{ $ord->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #0F172A; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">
                                    {{ strtoupper(substr($ord->user?->name ?? ($ord->player_tag ?? 'G'), 0, 1)) }}
                                </div>
                                <div style="overflow: hidden;">
                                    <div style="font-weight: 700; color: #0F172A; font-size: 0.85rem; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                                        {{ $ord->user?->name ?? 'Tamu' }}
                                    </div>
                                    <div style="font-size: 0.74rem; color: #64748B; font-family: monospace;">
                                        {{ $ord->player_tag }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: #0F172A; font-weight: 700; font-size: 0.88rem;">
                                {{ $ord->items->first()?->product_name ?? 'Item Produk' }}
                                @if($ord->items->count() > 1)
                                    <span style="font-size: 0.72rem; color: #64748B; font-weight: 600;">+{{ $ord->items->count() - 1 }} lainnya</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 900; color: #0F172A; font-size: 0.95rem;">
                                Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $ord->payment_status }}">
                                {{ strtoupper($ord->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $ord->order_status }}">
                                {{ strtoupper($ord->order_status) }}
                            </span>
                        </td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn-admin btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; border-radius: 8px;">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 3rem 1.5rem;">
                            <i class="fa-solid fa-cart-shopping fa-2x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #0F172A; display: block;">Belum ada pesanan terbaru</span>
                            <span style="font-size: 0.8rem; color: #64748B;">Transaksi yang masuk dari pembeli akan muncul di sini secara otomatis.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
