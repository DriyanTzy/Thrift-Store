@extends('layouts.app')

@section('content')
<div style="max-width: 720px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 24px; padding: 3rem 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 3rem;">

    <!-- Store Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.1rem; font-weight: 900; letter-spacing: 2px; color: #0F172A; text-transform: uppercase; margin-bottom: 0.5rem; text-decoration: underline; text-underline-offset: 6px;">
            RUANG LEMARI MARKETPLACE
        </h2>
        <h1 style="font-size: 2.25rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px; margin: 0;">
            TANDA TERIMA
        </h1>
    </div>

    <!-- Metadata Section -->
    <div style="font-size: 0.9rem; color: #1E293B; margin-bottom: 2.5rem; line-height: 1.8;">
        <div style="display: flex; gap: 0.5rem;">
            <span style="font-weight: 800; min-width: 150px; color: #0F172A;">Nomor pesanan:</span>
            <span style="font-family: monospace; font-weight: 700; color: #475569;">{{ $order->order_number }}</span>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <span style="font-weight: 800; min-width: 150px; color: #0F172A;">Tanggal:</span>
            <span>{{ $order->created_at->format('d/m/Y') }}</span>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <span style="font-weight: 800; min-width: 150px; color: #0F172A;">Email / Alamat:</span>
            <span style="font-weight: 700; color: #0F172A;">{{ $order->player_tag }}</span>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <span style="font-weight: 800; min-width: 150px; color: #0F172A;">Negara penagihan:</span>
            <span>Indonesia</span>
        </div>
    </div>

    <!-- Purchased Items List -->
    <div style="border-top: 1px solid #E2E8F0; border-bottom: 1px solid #E2E8F0; padding: 1.5rem 0; margin-bottom: 1.5rem;">
        @foreach($order->items as $item)
            @php
                $itemProduct = $item->product;
                $itemPhoto = null;
                if ($itemProduct) {
                    if ($itemProduct->images->count() > 0) {
                        $itemPhoto = asset('storage/' . $itemProduct->images->first()->image_path);
                    } elseif ($itemProduct->image) {
                        $itemPhoto = asset('storage/' . $itemProduct->image);
                    }
                }
                $productUrl = $itemProduct ? route('products.show', $itemProduct->slug) : null;
            @endphp
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    @if($productUrl)
                        <a href="{{ $productUrl }}" title="Lihat referensi produk {{ $item->product_name }}" style="text-decoration: none; display: block;">
                            <div style="width: 58px; height: 58px; background: #0F172A; border-radius: 14px; overflow: hidden; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.35rem; flex-shrink: 0; border: 1px solid #E2E8F0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.08)';" onmouseout="this.style.transform='scale(1)';">
                                @if($itemPhoto)
                                    <img src="{{ $itemPhoto }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-shirt"></i>
                                @endif
                            </div>
                        </a>
                    @else
                        <div style="width: 58px; height: 58px; background: #0F172A; border-radius: 14px; overflow: hidden; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.35rem; flex-shrink: 0;">
                            @if($itemPhoto)
                                <img src="{{ $itemPhoto }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-shirt"></i>
                            @endif
                        </div>
                    @endif

                    <div>
                        @if($productUrl)
                            <a href="{{ $productUrl }}" style="font-size: 1.05rem; font-weight: 900; color: #0F172A; text-decoration: none;" onmouseover="this.style.color='#22C55E'; this.style.textDecoration='underline';" onmouseout="this.style.color='#0F172A'; this.style.textDecoration='none';" title="Klik untuk membuka barang dari tanda terima ini">
                                {{ $item->product_name }}
                            </a>
                        @else
                            <h4 style="font-size: 1.05rem; font-weight: 900; color: #0F172A; margin: 0;">{{ $item->product_name }}</h4>
                        @endif

                        <p style="font-size: 0.8rem; color: #64748B; margin: 0.2rem 0 0 0;">
                            {{ $item->product->category->name ?? 'Thrifting Apparel' }}
                            @if($itemProduct && $itemProduct->stock <= 0)
                                <span style="background: #FEE2E2; color: #EF4444; font-size: 0.7rem; font-weight: 800; padding: 1px 6px; border-radius: 4px; margin-left: 0.4rem;">
                                    Stok Habis
                                </span>
                            @endif
                        </p>
                        <div style="display: inline-flex; align-items: center; gap: 0.35rem; background: #F1F5F9; border-radius: 6px; padding: 0.15rem 0.5rem; margin-top: 0.35rem; font-size: 0.75rem; font-weight: 700; color: #334155;">
                            <i class="fa-solid fa-user-tag" style="color: #0F172A;"></i> {{ $order->player_tag }}
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 2rem; text-align: right;">
                    <span style="font-size: 0.9rem; font-weight: 800; color: #64748B;">x{{ $item->quantity }}</span>
                    <span style="font-size: 1rem; font-weight: 900; color: #0F172A; min-width: 100px;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Price Breakdown -->
    @php
        $tax = round($order->total_price * 11 / 111);
    @endphp
    <div style="margin-bottom: 2.5rem; font-size: 0.95rem; line-height: 1.8;">
        <div style="display: flex; justify-content: space-between; font-weight: 900; color: #0F172A; font-size: 1.05rem;">
            <span>Total</span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; color: #64748B; font-size: 0.85rem;">
            <span>Termasuk pajak (11%)</span>
            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
        </div>

        <div style="display: flex; justify-content: space-between; font-weight: 800; color: #0F172A; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed #E2E8F0;">
            <span style="color: #64748B; font-weight: 600;">Dibayar dengan:</span>
            <span>{{ strtoupper($order->payment_method) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-weight: 900; color: #0F172A;">
            <span></span>
            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Legal & Support Footer -->
    <div style="font-size: 0.8rem; color: #94A3B8; text-align: center; line-height: 1.6; border-top: 1px solid #F1F5F9; padding-top: 1.5rem; margin-bottom: 2rem;">
        Lihat informasi selengkapnya di <a href="#" style="color: #0F172A; text-decoration: underline; font-weight: 700;">Ketentuan Layanan</a> (belum tersedia dalam bahasa Indonesia). Jika memerlukan bantuan terkait pembelian, kami sarankan untuk menghubungi kami melalui fitur Dukungan dalam setiap transaksi Ruang Lemari supaya kami dapat membalas dengan lebih cepat. Kamu juga bisa mengirimkan email ke <a href="mailto:support@ruanglemari.com" style="color: #0F172A; text-decoration: underline; font-weight: 700;">support@ruanglemari.com</a> dengan menyertakan Informasi berikut: nomor pesanan, tanggal pembelian, dan metode pembayaran.
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('orders.history') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #F1F5F9; color: #334155; font-size: 0.875rem; font-weight: 800; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; border: 1px solid #CBD5E1;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #0F172A; color: #FFFFFF; font-size: 0.875rem; font-weight: 800; padding: 0.75rem 1.75rem; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(15,23,42,0.25);">
            <i class="fa-solid fa-print"></i> Cetak / Simpan Tanda Terima
        </button>
    </div>

</div>
@endsection
