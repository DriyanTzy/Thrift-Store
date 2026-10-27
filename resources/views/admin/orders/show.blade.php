@extends('layouts.admin')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 2rem;">

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 900; color: #0F172A; margin: 0;">Detail Pesanan #{{ $order->order_number }}</h1>
            <span style="font-size: 0.82rem; color: #64748B;">Transaksi pada {{ $order->created_at->format('d F Y, H:i') }}</span>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-secondary" style="font-size: 0.8rem; border-radius: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali Ke Pesanan
        </a>
    </div>

    <!-- Informasi Transaksi -->
    <div class="admin-card" style="margin-bottom: 1.5rem; border-radius: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem; color: #0F172A;">
            Informasi Transaksi & Pengguna
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; font-weight: 700;">ID Pengguna / Email:</span>
                <span style="font-size: 1.1rem; font-weight: 900; color: #0F172A;">{{ $order->player_tag }}</span>
            </div>

            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; font-weight: 700;">Metode Pembayaran:</span>
                <span style="font-size: 1rem; font-weight: 800; color: #0F172A;">{{ strtoupper($order->payment_method) }}</span>
            </div>

            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; font-weight: 700;">Nama Pembeli Terdaftar:</span>
                <span style="font-size: 0.95rem; font-weight: 700; color: #0F172A;">{{ $order->user ? $order->user->name . ' ('.$order->user->email.')' : 'Tamu (Guest)' }}</span>
            </div>

            <div>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: block; font-weight: 700;">Waktu Transaksi Masuk:</span>
                <span style="font-size: 0.95rem; font-weight: 700; color: #0F172A;">{{ $order->created_at->format('d F Y, H:i:s') }}</span>
            </div>
        </div>
    </div>

    <!-- Items List -->
    <div class="admin-card" style="margin-bottom: 1.5rem; border-radius: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem; color: #0F172A;">
            Item Yang Dipesan
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; align-items: center; background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 1rem 1.25rem; border-radius: 12px;">
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin: 0 0 0.2rem;">{{ $item->product_name }}</h4>
                        <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Qty: {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                    <span style="font-size: 1.1rem; font-weight: 900; color: #16A34A;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div style="border-top: 1.5px solid #E2E8F0; margin-top: 1.25rem; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 800; font-size: 1rem; color: #0F172A;">Total Transaksi:</span>
            <span style="font-weight: 900; font-size: 1.35rem; color: #0F172A;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Update Status Form -->
    <div class="admin-card" style="border-radius: 16px;">
        <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem; color: #0F172A;">
            Update Status Pesanan
        </h3>

        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Status Pembayaran</label>
                    <select name="payment_status" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>PENDING (Menunggu Pembayaran)</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>PAID (Terbayar)</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>FAILED (Gagal)</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>REFUNDED (Dana Dikembalikan)</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 0.4rem;">Status Pengiriman Barang</label>
                    <select name="order_status" style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem; color: #0F172A; font-weight: 700; outline: none;">
                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>PROCESSING (Sedang Diproses)</option>
                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>COMPLETED (Selesai Dikirim)</option>
                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>CANCELLED (Dibatalkan)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-admin btn-yellow" style="width: 100%; justify-content: center; padding: 0.85rem; background: #0F172A; color: #FFFFFF; border-radius: 10px; font-weight: 800;">
                <i class="fa-solid fa-floppy-disk" style="color: #22C55E;"></i> Simpan Perubahan Status
            </button>
        </form>
    </div>

</div>
@endsection
