@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 900; margin-bottom: 0.25rem; color: #0F172A;">Kelola Semua Pesanan</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Pantau status pembayaran & pengiriman barang transaksi pengguna.</p>
    </div>

    <!-- Filter by Status -->
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.orders.index') }}" class="btn-admin {{ !$status ? 'btn-yellow' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px;">
            Semua
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn-admin {{ $status === 'processing' ? 'btn-yellow' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px;">
            Processing / Menunggu
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn-admin {{ $status === 'completed' ? 'btn-yellow' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px;">
            Completed / Selesai
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn-admin {{ $status === 'cancelled' ? 'btn-yellow' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; border-radius: 8px;">
            Cancelled / Dibatalkan
        </a>
    </div>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden; border-radius: 16px;">
    <div style="overflow-x: auto;">
        <table class="sc-table" style="margin-top: 0;">
            <thead>
                <tr>
                    <th style="padding-left: 1.5rem; width: 16%;">No Transaksi</th>
                    <th style="width: 14%;">Tanggal</th>
                    <th style="width: 20%;">ID Pengguna / Email</th>
                    <th style="width: 20%;">Item Produk</th>
                    <th style="width: 14%;">Total Bayar</th>
                    <th style="width: 10%;">Payment</th>
                    <th style="width: 10%;">Status Item</th>
                    <th style="text-align: right; padding-right: 1.5rem; width: 8%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $ord)
                    <tr>
                        <td style="padding-left: 1.5rem;">
                            <span style="font-weight: 800; color: #0F172A; font-family: monospace; font-size: 0.88rem;">
                                #{{ $ord->order_number }}
                            </span>
                        </td>
                        <td style="color: var(--text-muted); font-size: 0.82rem;">
                            {{ $ord->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span style="color: #0F172A; font-weight: 700; font-size: 0.85rem;">
                                {{ $ord->player_tag }}
                            </span>
                        </td>
                        <td>
                            <span style="color: #0F172A; font-weight: 700; font-size: 0.85rem;">
                                {{ $ord->items->first()?->product_name ?? '-' }}
                                @if($ord->items->count() > 1)
                                    <span style="font-size: 0.72rem; color: #64748B;">+{{ $ord->items->count() - 1 }}</span>
                                @endif
                            </span>
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
                                <i class="fa-solid fa-gear"></i> Kelola
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 3rem 1.5rem;">
                            <i class="fa-solid fa-receipt fa-2x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #0F172A; display: block;">Belum ada pesanan</span>
                            <span style="font-size: 0.8rem; color: #64748B;">Pesanan yang masuk akan tercatat di sini.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrapper" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-color); background: #FFFFFF;">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
    .pagination-wrapper nav svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        max-height: 16px !important;
        display: inline-block !important;
    }
    .pagination-wrapper nav p {
        margin: 0;
        font-size: 0.82rem;
        color: #64748B;
    }
    .pagination-wrapper nav a, .pagination-wrapper nav span {
        font-size: 0.82rem;
    }
</style>
@endpush
@endsection
