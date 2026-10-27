@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- Title Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2.25rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px; margin: 0;">AKUN</h1>
    </div>

    <!-- 1. Masuk Sebagai Card -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">Masuk sebagai:</span>
            
            @auth
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #3B82F6; font-size: 0.85rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
                        Ganti akun / Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color: #3B82F6; font-size: 0.85rem; font-weight: 700; text-decoration: underline;">
                    Masuk ke Akun
                </a>
            @endauth
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1rem;">
            <div style="width: 54px; height: 54px; background: #0F172A; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-weight: 900; font-size: 1.25rem; letter-spacing: -0.5px; flex-shrink: 0;">
                ID
            </div>
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A; margin: 0;">
                    {{ auth()->check() ? auth()->user()->name : 'Shaw Nouzen' }}
                </h3>
                <p style="font-size: 0.875rem; color: #64748B; margin: 0.15rem 0 0 0;">
                    {{ auth()->check() ? auth()->user()->email : 'andriyanmaulana49@gmail.com' }}
                </p>
            </div>
        </div>
    </div>



    <!-- 3. Informasi Pembayaran Section -->
    <div style="background: #FFFFFF; border: 1.5px solid {{ ($countProcessing ?? 0) > 0 ? '#FDE68A' : '#E2E8F0' }}; border-radius: 20px; padding: 1.75rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; cursor: pointer;" onclick="toggleSection('paySection', 'payCaret')">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0;">Informasi Pembayaran</h3>
                @if(($countProcessing ?? 0) > 0)
                    <span style="background: #FEF3C7; color: #B45309; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 20px; border: 1px solid #FDE68A; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <i class="fa-solid fa-clock"></i> {{ $countProcessing }} Menunggu Finishing
                    </span>
                @else
                    <span style="background: #DCFCE7; color: #166534; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.65rem; border-radius: 20px; border: 1px solid #86EFAC; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <i class="fa-solid fa-circle-check"></i> Normal
                    </span>
                @endif
            </div>
            <i id="payCaret" class="fa-solid fa-chevron-up" style="color: #64748B; font-size: 0.9rem; transition: transform 0.2s;"></i>
        </div>

        <div id="paySection">
            @if(($countProcessing ?? 0) > 0)
                <!-- Warning Notice Box -->
                <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 14px; padding: 1.25rem; margin-top: 0.5rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 900; color: #92400E;">
                                    Terdapat {{ $countProcessing }} Pesanan Menunggu Finishing Pembayaran
                                </h4>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.82rem; color: #B45309; line-height: 1.4;">
                                    Total tagihan tertunda: <strong style="font-size: 0.95rem; color: #78350F;">Rp {{ number_format($unpaidTotal ?? 0, 0, ',', '.') }}</strong>. Harap segera menyelesaikan transfer pembayaran agar penjual dapat memproses dan mengirimkan pesanan Anda.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('orders.history', ['status' => 'processing']) }}" 
                           style="background: #D97706; color: #FFFFFF; font-weight: 800; font-size: 0.8rem; padding: 0.55rem 1.1rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 8px rgba(217,119,6,0.3); transition: all 0.2s;"
                           onmouseover="this.style.background='#B45309';"
                           onmouseout="this.style.background='#D97706';">
                            <i class="fa-solid fa-list-check"></i> Filter Pesanan Pending ({{ $countProcessing }})
                        </a>
                    </div>
                </div>

                <!-- Quick List of Pending Finishing Orders -->
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($pendingOrders as $po)
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 0.85rem 1.15rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-family: monospace; font-weight: 900; font-size: 0.85rem; color: #0F172A;">
                                        #{{ $po->order_number }}
                                    </span>
                                    <span style="background: #FEF3C7; color: #B45309; font-weight: 800; font-size: 0.68rem; padding: 0.15rem 0.45rem; border-radius: 4px; text-transform: uppercase;">
                                        {{ strtoupper($po->payment_method) }}
                                    </span>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.2rem;">
                                    {{ $po->created_at->format('d/m/Y H:i') }} • {{ $po->items->count() }} item • Tagihan: <strong style="color: #0F172A;">Rp {{ number_format($po->total_price, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <form action="{{ route('orders.pay', $po->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: #22C55E; color: #0F172A; font-weight: 800; font-size: 0.75rem; padding: 0.4rem 0.85rem; border: none; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <i class="fa-solid fa-check"></i> Selesaikan Transfer
                                    </button>
                                </form>
                                <form action="{{ route('orders.cancel', $po->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Batalkan pesanan #{{ $po->order_number }}?');">
                                    @csrf
                                    <button type="submit" style="background: #FFFFFF; color: #DC2626; border: 1px solid #FECACA; font-weight: 700; font-size: 0.75rem; padding: 0.38rem 0.7rem; border-radius: 8px; cursor: pointer;">
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Normal Status Box -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.75rem; text-align: center; color: #64748B; margin-top: 0.5rem;">
                    <i class="fa-solid fa-circle-check fa-2x" style="color: #16A34A; margin-bottom: 0.65rem; display: block;"></i>
                    <div style="font-weight: 800; font-size: 0.95rem; color: #0F172A;">Status Pembayaran Normal</div>
                    <p style="font-size: 0.82rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                        Tidak ada tagihan yang tertunda atau menunggu konfirmasi. Seluruh transaksi pembayaran Anda lancar dan telah terselesaikan.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- 4. Riwayat Pembelian Section -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; cursor: pointer;" onclick="toggleSection('historySection', 'historyCaret')">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0;">Riwayat Pembelian & Pesanan</h3>
            <i id="historyCaret" class="fa-solid fa-chevron-up" style="color: #64748B; font-size: 0.9rem; transition: transform 0.2s;"></i>
        </div>

        <!-- Filter Status Pesanan Tabs -->
        @php
            $currentStatus = $status ?? 'all';
        @endphp
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 0.25rem;">
            <a href="{{ route('orders.history', ['status' => 'all']) }}" 
               style="text-decoration: none; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.35rem; white-space: nowrap; {{ $currentStatus === 'all' ? 'background: #0F172A; color: #FFFFFF;' : 'background: #F1F5F9; color: #475569;' }}">
                Semua @if(isset($countAll)) ({{ $countAll }}) @endif
            </a>
            <a href="{{ route('orders.history', ['status' => 'processing']) }}" 
               style="text-decoration: none; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.35rem; white-space: nowrap; {{ $currentStatus === 'processing' ? 'background: #D97706; color: #FFFFFF;' : 'background: #FEF3C7; color: #B45309;' }}">
                <i class="fa-solid fa-clock"></i> Menunggu Pembayaran @if(isset($countProcessing)) ({{ $countProcessing }}) @endif
            </a>
            <a href="{{ route('orders.history', ['status' => 'completed']) }}" 
               style="text-decoration: none; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.35rem; white-space: nowrap; {{ $currentStatus === 'completed' ? 'background: #16A34A; color: #FFFFFF;' : 'background: #DCFCE7; color: #15803D;' }}">
                <i class="fa-solid fa-circle-check"></i> Selesai @if(isset($countCompleted)) ({{ $countCompleted }}) @endif
            </a>
            <a href="{{ route('orders.history', ['status' => 'cancelled']) }}" 
               style="text-decoration: none; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.35rem; white-space: nowrap; {{ $currentStatus === 'cancelled' ? 'background: #DC2626; color: #FFFFFF;' : 'background: #FEE2E2; color: #B91C1C;' }}">
                <i class="fa-solid fa-circle-xmark"></i> Dibatalkan @if(isset($countCancelled)) ({{ $countCancelled }}) @endif
            </a>
        </div>

        <div id="historySection">
            @forelse($orders as $order)
                @php
                    $firstItemProduct = $order->items->first()?->product;
                    $seller = $firstItemProduct?->user;
                    $sellerName = $seller ? $seller->store_name : 'Ruang Lemari Official Store';
                    $isPending = ($order->order_status === 'processing' || $order->payment_status === 'pending');
                    $isCancelled = ($order->order_status === 'cancelled' || $order->payment_status === 'cancelled');
                    $isCompleted = ($order->order_status === 'completed' && $order->payment_status === 'paid');
                @endphp
                <div style="background: #FFFFFF; border: 1.5px solid {{ $isPending ? '#FDE68A' : ($isCancelled ? '#FECACA' : '#E2E8F0') }}; border-radius: 16px; margin-bottom: 1.25rem; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.02); transition: border-color 0.2s;">
                    
                    <!-- 1. Header Order: Toko, Tanggal, No Pesanan, & Status -->
                    <div style="background: {{ $isPending ? '#FFFBEB' : ($isCancelled ? '#FEF2F2' : '#F8FAFC') }}; border-bottom: 1px solid #E2E8F0; padding: 0.9rem 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                            <div style="width: 28px; height: 28px; background: #0F172A; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 0.8rem;">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            
                            @if($seller)
                                <a href="{{ route('shop.store', $seller->id) }}" 
                                   style="font-size: 0.9rem; font-weight: 800; color: #0F172A; text-decoration: none; display: flex; align-items: center; gap: 0.35rem;"
                                   onmouseover="this.style.color='#16A34A';"
                                   onmouseout="this.style.color='#0F172A';">
                                    {{ $sellerName }}
                                    <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem; color: #94A3B8;"></i>
                                </a>
                            @else
                                <span style="font-size: 0.9rem; font-weight: 800; color: #0F172A;">
                                    {{ $sellerName }}
                                </span>
                            @endif

                            <span style="color: #CBD5E1;">|</span>
                            <span style="font-size: 0.78rem; color: #64748B;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span style="color: #CBD5E1;">|</span>
                            <span style="font-size: 0.78rem; font-family: monospace; color: #64748B;">#{{ $order->order_number }}</span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            @if($isPending)
                                <span style="background: #FEF3C7; color: #B45309; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid #FDE68A;">
                                    <i class="fa-solid fa-clock"></i> Menunggu Pembayaran (Processing)
                                </span>
                            @elseif($isCancelled)
                                <span style="background: #FEE2E2; color: #B91C1C; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid #FECACA;">
                                    <i class="fa-solid fa-circle-xmark"></i> Dibatalkan
                                </span>
                            @else
                                <span style="background: rgba(34, 197, 94, 0.12); color: #16A34A; font-weight: 800; font-size: 0.75rem; padding: 0.25rem 0.65rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid rgba(34, 197, 94, 0.3);">
                                    <i class="fa-solid fa-circle-check"></i> Selesai
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Order Items List (Shopee / Tokopedia Style Item Rows) -->
                    <div style="padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: 0.85rem;">
                        @foreach($order->items as $item)
                            @php
                                $prod = $item->product;
                                $itemImg = null;
                                if ($prod) {
                                    if ($prod->images && $prod->images->count() > 0) {
                                        $itemImg = asset('storage/' . $prod->images->first()->image_path);
                                    } elseif ($prod->image) {
                                        $itemImg = asset('storage/' . $prod->image);
                                    }
                                }
                                $itemLink = $prod ? route('products.show', $prod->slug) : null;
                            @endphp
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-bottom: 0.85rem; border-bottom: {{ !$loop->last ? '1px dashed #E2E8F0' : 'none' }};">
                                
                                <!-- Item Photo & Info -->
                                <div style="display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 240px;">
                                    @if($itemLink)
                                        <a href="{{ $itemLink }}" style="display: block; flex-shrink: 0; text-decoration: none;" title="Buka detail {{ $item->product_name }}">
                                            <div style="width: 64px; height: 64px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                                @if($itemImg)
                                                    <img src="{{ $itemImg }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="fa-solid fa-shirt" style="color: #64748B; font-size: 1.5rem;"></i>
                                                @endif
                                            </div>
                                        </a>
                                    @else
                                        <div style="width: 64px; height: 64px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            @if($itemImg)
                                                <img src="{{ $itemImg }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fa-solid fa-shirt" style="color: #64748B; font-size: 1.5rem;"></i>
                                            @endif
                                        </div>
                                    @endif

                                    <div style="min-width: 0;">
                                        @if($itemLink)
                                            <a href="{{ $itemLink }}" 
                                               style="font-size: 0.95rem; font-weight: 800; color: #0F172A; text-decoration: none; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                               onmouseover="this.style.color='#22C55E'; this.style.textDecoration='underline';"
                                               onmouseout="this.style.color='#0F172A'; this.style.textDecoration='none';"
                                               title="Klik untuk membuka detail produk">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            <div style="font-size: 0.95rem; font-weight: 800; color: #0F172A; line-height: 1.35;">
                                                {{ $item->product_name }}
                                            </div>
                                        @endif

                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.35rem; font-size: 0.78rem; color: #64748B; flex-wrap: wrap;">
                                            @if($prod && $prod->category)
                                                <span style="background: #F1F5F9; padding: 2px 8px; border-radius: 6px; font-weight: 700; color: #475569;">
                                                    {{ $prod->category->name }}
                                                </span>
                                            @endif
                                            <span>x{{ $item->quantity }} barang</span>
                                            @if($itemLink)
                                                <a href="{{ $itemLink }}" style="color: #3B82F6; font-weight: 700; text-decoration: underline; margin-left: 0.35rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                    Lihat Detail Barang <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Item Price -->
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-size: 0.95rem; font-weight: 900; color: #0F172A;">
                                        Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}
                                    </div>
                                    @if($item->quantity > 1)
                                        <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.15rem;">
                                            @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- 3. Footer Order: Total Belanja & Tombol Aksi -->
                    <div style="background: #FAFAFA; border-top: 1px solid #E2E8F0; padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                                <span style="font-size: 0.82rem; color: #64748B; font-weight: 700;">Total Belanja:</span>
                                <span style="font-size: 1.15rem; font-weight: 900; color: #0F172A;">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($order->voucher_code)
                                <div style="font-size: 0.72rem; color: #16A34A; font-weight: 700; margin-top: 0.15rem;">
                                    <i class="fa-solid fa-tag"></i> Hemat Rp {{ number_format($order->discount_amount, 0, ',', '.') }} (Voucher {{ $order->voucher_code }})
                                </div>
                            @endif
                        </div>

                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <!-- Chat Penjual Button -->
                            @if($seller)
                                <a href="{{ route('chat.index', ['user_id' => $seller->id, 'product_id' => $firstItemProduct?->id]) }}" 
                                   style="background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #0F172A; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" 
                                   onmouseover="this.style.background='#F8FAFC'; this.style.borderColor='#0F172A';" 
                                   onmouseout="this.style.background='#FFFFFF'; this.style.borderColor='#CBD5E1';" 
                                   title="Chat penjual terkait pesanan ini">
                                    <i class="fa-regular fa-comment-dots" style="color: #22C55E;"></i> Chat Penjual
                                </a>
                            @endif

                            @if($isPending)
                                <!-- Selesaikan Pembayaran (Finish Transfer) -->
                                <form action="{{ route('orders.pay', $order->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" 
                                            style="background: #22C55E; border: 1.5px solid #16A34A; color: #0F172A; border-radius: 10px; font-size: 0.8rem; font-weight: 900; padding: 0.45rem 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 8px rgba(34,197,94,0.3); transition: all 0.2s;" 
                                            onmouseover="this.style.background='#16A34A'; this.style.color='#FFFFFF';" 
                                            onmouseout="this.style.background='#22C55E'; this.style.color='#0F172A';">
                                        <i class="fa-solid fa-money-bill-transfer"></i> Selesaikan Pembayaran
                                    </button>
                                </form>

                                <!-- Batalkan Pesanan (Cancel Order with Stock Restore) -->
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $order->order_number }}? Sisa stok barang akan dikembalikan ke toko.');">
                                    @csrf
                                    <button type="submit" 
                                            style="background: #FFFFFF; border: 1.5px solid #FECACA; color: #DC2626; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" 
                                            onmouseover="this.style.background='#FEE2E2';" 
                                            onmouseout="this.style.background='#FFFFFF';">
                                        <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @elseif($isCancelled)
                                @if($firstItemProduct)
                                    <a href="{{ route('products.show', $firstItemProduct->slug) }}" 
                                       style="background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #0F172A; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <i class="fa-solid fa-rotate-right"></i> Pesan Ulang
                                    </a>
                                @endif
                            @else
                                <!-- Selesai Action Buttons -->
                                @if($firstItemProduct)
                                    <a href="{{ route('products.show', $firstItemProduct->slug) }}" 
                                       style="background: #FFFFFF; border: 1.5px solid #22C55E; color: #16A34A; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" 
                                       onmouseover="this.style.background='#22C55E'; this.style.color='#FFFFFF';" 
                                       onmouseout="this.style.background='#FFFFFF'; this.style.color='#16A34A';" 
                                       title="Beli produk ini lagi">
                                        <i class="fa-solid fa-rotate-right"></i> Beli Lagi
                                    </a>
                                @endif

                                <button type="button" 
                                        onclick="openReviewModal({{ $order->id }}, '{{ $order->order_number }}', {{ json_encode($order->items->map(fn($it) => ['id' => $it->product_id, 'name' => $it->product_name])) }})" 
                                        style="background: #FEF08A; border: 1.5px solid #EAB308; color: #854D0E; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" 
                                        onmouseover="this.style.background='#FDE047';" 
                                        onmouseout="this.style.background='#FEF08A';">
                                    <i class="fa-solid fa-star" style="color: #CA8A04;"></i> Beri Ulasan
                                </button>

                                <a href="{{ route('orders.receipt', $order->order_number) }}" 
                                   style="background: #0F172A; border: 1.5px solid #0F172A; color: #FFFFFF; border-radius: 10px; font-size: 0.8rem; font-weight: 800; padding: 0.45rem 0.95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: all 0.2s;" 
                                   onmouseover="this.style.background='#1E293B';" 
                                   onmouseout="this.style.background='#0F172A';">
                                    <i class="fa-solid fa-file-invoice"></i> Tanda Terima
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div style="background: #F8FAFC; border-radius: 12px; padding: 2.5rem 1rem; text-align: center; color: #64748B;">
                    <i class="fa-solid fa-bag-shopping fa-3x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                    <div style="font-weight: 800; font-size: 1rem; color: #0F172A;">Belum ada riwayat pembelian</div>
                    <p style="font-size: 0.85rem; margin-top: 0.25rem;">Yuk jelajahi barang thrifting pilihan di etalase kami!</p>
                    <a href="{{ route('products.index') }}" style="display: inline-block; margin-top: 0.5rem; background: #0F172A; color: #FFF; font-weight: 800; font-size: 0.85rem; padding: 0.6rem 1.25rem; border-radius: 12px; text-decoration: none;">Mulai Belanja</a>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- REVIEW MODAL POPUP -->
<div id="reviewModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #FFFFFF; border-radius: 24px; max-width: 480px; width: 100%; padding: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0;">Beri Ulasan & Nilai Produk</h3>
                <p id="modalOrderText" style="font-size: 0.8rem; color: #64748B; margin-top: 0.2rem; margin-bottom: 0;">Pesanan #</p>
            </div>
            <button type="button" onclick="closeReviewModal()" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer; padding: 0.25rem;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" id="modalOrderId">

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.4rem;">Pilih Produk yang Diulas:</label>
                <select name="product_id" id="modalProductSelect" required style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.9rem; font-weight: 700; color: #0F172A; outline: none;">
                </select>
            </div>

            <div style="margin-bottom: 1.25rem; text-align: center; background: #F8FAFC; border-radius: 16px; padding: 1.25rem; border: 1px solid #E2E8F0;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.5rem;">Kualitas Produk & Kepuasan (Rating):</label>
                <div style="display: flex; justify-content: center; gap: 0.5rem; font-size: 2rem; color: #CBD5E1;" id="starRatingContainer">
                    <i class="fa-solid fa-star star-icon" data-value="1" onclick="setRating(1)" style="cursor: pointer; transition: color 0.15s;"></i>
                    <i class="fa-solid fa-star star-icon" data-value="2" onclick="setRating(2)" style="cursor: pointer; transition: color 0.15s;"></i>
                    <i class="fa-solid fa-star star-icon" data-value="3" onclick="setRating(3)" style="cursor: pointer; transition: color 0.15s;"></i>
                    <i class="fa-solid fa-star star-icon" data-value="4" onclick="setRating(4)" style="cursor: pointer; transition: color 0.15s;"></i>
                    <i class="fa-solid fa-star star-icon" data-value="5" onclick="setRating(5)" style="cursor: pointer; transition: color 0.15s;"></i>
                </div>
                <input type="hidden" name="rating" id="modalRatingInput" value="5">
                <span id="ratingLabel" style="display: block; font-size: 0.8rem; font-weight: 800; color: #CA8A04; margin-top: 0.4rem;">⭐⭐⭐⭐⭐ Sangat Puas (5 Bintang)</span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-bottom: 0.4rem;">Tulis Ulasan & Pengalaman:</label>
                <textarea name="comment" rows="3" placeholder="Ceritakan kondisi barang, bahan, pengemasan, dan respon penjual..." style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.85rem; color: #0F172A; outline: none; line-height: 1.5;"></textarea>
            </div>

            <button type="submit" style="width: 100%; background: #0F172A; color: #FFF; border: none; border-radius: 12px; padding: 0.85rem; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s;">
                Kirim Ulasan Sekarang
            </button>
        </form>

    </div>
</div>

<script>
    function toggleSection(sectionId, caretId) {
        const sec = document.getElementById(sectionId);
        const caret = document.getElementById(caretId);
        if (sec.style.display === 'none') {
            sec.style.display = 'block';
            caret.style.transform = 'rotate(0deg)';
        } else {
            sec.style.display = 'none';
            caret.style.transform = 'rotate(180deg)';
        }
    }

    function openReviewModal(orderId, orderNumber, items) {
        document.getElementById('modalOrderId').value = orderId;
        document.getElementById('modalOrderText').textContent = 'Pesanan #' + orderNumber;
        
        const select = document.getElementById('modalProductSelect');
        select.innerHTML = '';
        items.forEach(it => {
            const opt = document.createElement('option');
            opt.value = it.id;
            opt.textContent = it.name;
            select.appendChild(opt);
        });

        setRating(5);
        document.getElementById('reviewModal').style.display = 'flex';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    function setRating(val) {
        document.getElementById('modalRatingInput').value = val;
        const stars = document.querySelectorAll('#starRatingContainer .star-icon');
        const labels = {
            1: '⭐ Kurang Puas (1 Bintang)',
            2: '⭐⭐ Cukup (2 Bintang)',
            3: '⭐⭐⭐ Baik (3 Bintang)',
            4: '⭐⭐⭐⭐ Puas (4 Bintang)',
            5: '⭐⭐⭐⭐⭐ Sangat Puas (5 Bintang)'
        };
        document.getElementById('ratingLabel').textContent = labels[val] || '';

        stars.forEach((s, idx) => {
            if (idx < val) {
                s.style.color = '#EAB308';
            } else {
                s.style.color = '#CBD5E1';
            }
        });
    }
</script>
@endsection
