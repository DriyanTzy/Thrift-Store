@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* Size Selector Pill buttons matching Shopee/Tokped */
    .size-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.85rem;
        background: #FFFFFF;
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 800;
        color: #0F172A;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .size-btn:hover:not(.disabled) {
        border-color: #0F172A;
        background: #F8FAFC;
    }

    .size-btn.active {
        background: #0F172A;
        color: #FFFFFF;
        border-color: #0F172A;
        box-shadow: 0 4px 12px rgba(15,23,42,0.18);
    }

    .size-btn.disabled {
        background: #F1F5F9;
        color: #94A3B8;
        border-color: #E2E8F0;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    .size-btn .size-qty-badge {
        font-size: 0.7rem;
        background: rgba(15,23,42,0.08);
        color: #334155;
        padding: 1px 5px;
        border-radius: 6px;
        font-weight: 700;
    }

    .size-btn.active .size-qty-badge {
        background: rgba(255,255,255,0.25);
        color: #FFFFFF;
    }

    /* 5-Column Responsive Product Grid for Related Products */
    .thrift-products-grid-5 {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1.1rem;
    }

    @media (max-width: 1280px) {
        .thrift-products-grid-5 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 1024px) {
        .thrift-products-grid-5 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .thrift-products-grid-5 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .thrift-products-grid-5 {
            grid-template-columns: 1fr;
        }
    }

    .product-ref-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        min-width: 0;
    }

    .product-ref-card:hover {
        transform: translateY(-4px);
        border-color: #0F172A;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }

    .product-photo-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        height: 165px;
        margin-bottom: 0.85rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-ref-card:hover .product-photo-box img {
        transform: scale(1.04);
    }

    .product-price-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 0.35rem;
    }

    .product-price-val {
        font-size: 1.05rem;
        font-weight: 900;
        color: #0F172A;
    }

    .product-card-title {
        font-size: 0.95rem;
        font-weight: 900;
        color: #0F172A;
        margin: 0 0 0.2rem;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-card-subtitle {
        color: #D97706;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 0.45rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-card-desc {
        font-size: 0.78rem;
        color: #64748B;
        line-height: 1.4;
        margin-bottom: 0.85rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.8em;
    }

    .product-card-bottom {
        border-top: 1px solid #F1F5F9;
        padding-top: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .product-spec-pill {
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        padding: 0.35rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        color: #64748B;
        text-align: center;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-cta-btn {
        background: #000000;
        color: #FFFFFF;
        border-radius: 30px;
        padding: 0.65rem 1rem;
        font-weight: 800;
        font-size: 0.8rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
    }

    .product-cta-btn:hover {
        background: #1E293B;
        transform: translateY(-1px);
        color: #FFFFFF;
    }
</style>
@endpush

<div style="max-width: 1100px; margin: 0 auto; padding-bottom: 3.5rem;">

    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1.5px solid #E2E8F0; padding-bottom: 1rem; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748B; font-weight: 700;">
            <a href="{{ route('products.index') }}" style="color: #64748B; text-decoration: none;"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="color: #64748B; text-decoration: none;">
                {{ $product->category->name }}
            </a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span style="color: #0F172A; font-weight: 800;">{{ $product->name }}</span>
        </div>
        <div style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.3rem 0.85rem; border-radius: 30px;">
            <i class="fa-solid fa-shield-check"></i> 100% Authentic Guarantee
        </div>
    </div>

    <!-- Main 2-Column Split: Left Product Card with Sizes, Right Shopping Cart Box -->
    <div style="display: grid; grid-template-columns: 370px 1fr; gap: 2.25rem; align-items: start; margin-bottom: 2.5rem;">
        
        <!-- LEFT COLUMN: Product Card with Image, Name, Price, and Size Buttons (Shopee/Tokped Style) -->
        <div>
            @php
                $imgs = $product->images;
                $firstImage = $imgs->count() > 0 ? asset('storage/' . $imgs->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : null);
                
                // Parse sizes: map of ["Size" => StockQty]
                $sizeStocksMap = $product->size_stocks;
                if (empty($sizeStocksMap)) {
                    $sizeStocksMap = ['All Size' => $product->stock ?: 1];
                }

                // Pick first available size as default
                $firstAvailableSize = null;
                foreach ($sizeStocksMap as $sName => $sQty) {
                    if ($sQty > 0) {
                        $firstAvailableSize = $sName;
                        break;
                    }
                }
                if (!$firstAvailableSize) {
                    $firstAvailableSize = array_key_first($sizeStocksMap);
                }
            @endphp

            <!-- Big Product Image Box -->
            <div style="background: #EEF2F6; border-radius: 20px; overflow: hidden; height: 320px; display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 1.25rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                @if($product->isOutOfStock())
                    <div style="position: absolute; top: 14px; right: 14px; background: #EF4444; color: #FFF; font-weight: 900; font-size: 0.8rem; padding: 0.35rem 0.85rem; border-radius: 8px; text-transform: uppercase; box-shadow: 0 4px 10px rgba(239,68,68,0.4); z-index: 5;">
                        <i class="fa-solid fa-ban"></i> STOK HABIS
                    </div>
                @endif

                @if($product->badge)
                    <div style="position: absolute; top: 14px; left: 14px; background: rgba(251, 191, 36, 0.2); border: 1px solid rgba(245, 158, 11, 0.4); color: #B45309; font-weight: 800; font-size: 0.72rem; padding: 0.25rem 0.65rem; border-radius: 8px; text-transform: uppercase;">
                        {{ $product->badge }}
                    </div>
                @endif

                @if($firstImage)
                    <img id="main-product-img" src="{{ $firstImage }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain; padding: 1.25rem; transition: opacity 0.2s; {{ $product->isOutOfStock() ? 'filter: grayscale(40%); opacity: 0.85;' : '' }}">
                @else
                    <div style="width: 90px; height: 90px; border-radius: 18px; background: #0F172A; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 2.75rem;">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                @endif
            </div>

            <!-- Multi-photo Thumbnails Strip -->
            @if($imgs->count() > 1)
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.5rem; margin-bottom: 1.25rem;">
                    @foreach($imgs as $idx => $imgItem)
                        @php $thumbUrl = asset('storage/' . $imgItem->image_path); @endphp
                        <div onclick="switchMainImage('{{ $thumbUrl }}', this)" 
                             class="thumb-item {{ $idx === 0 ? 'active-thumb' : '' }}"
                             style="aspect-ratio: 1/1; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid {{ $idx === 0 ? '#0F172A' : '#E2E8F0' }}; background: #FFF;">
                            <img src="{{ $thumbUrl }}" alt="Foto {{ $idx + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Product Title, Rating, & Price -->
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.35rem;">
                <h2 style="font-size: 1.3rem; font-weight: 900; color: #0F172A; margin: 0; line-height: 1.3;">
                    {{ $product->name }}
                </h2>
                @if($product->isOutOfStock())
                    <span style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; font-size: 0.72rem; font-weight: 900; padding: 0.2rem 0.6rem; border-radius: 6px; white-space: nowrap;">
                        STOK KOSONG
                    </span>
                @endif
            </div>

            <!-- Out of Stock Warning Banner -->
            @if($product->isOutOfStock())
                <div style="background: #FEF2F2; border: 1.5px solid #FCA5A5; border-radius: 14px; padding: 0.85rem 1rem; margin-bottom: 1rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                    <i class="fa-solid fa-circle-exclamation" style="color: #DC2626; font-size: 1.15rem; margin-top: 0.15rem; flex-shrink: 0;"></i>
                    <div>
                        <div style="font-weight: 900; font-size: 0.88rem; color: #991B1B;">Stok Habis — Tidak Dapat Diorder</div>
                        <div style="font-size: 0.78rem; color: #7F1D1D; margin-top: 0.2rem; line-height: 1.4;">
                            Barang ini ditampilkan sebagai referensi dari struk / riwayat pesanan Anda. Pemesanan baru dinonaktifkan sementara karena stok barang habis.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Rating & Sold Bar (Shopee/Tokped Style) -->
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; font-size: 0.85rem;">
                <div style="display: flex; align-items: center; gap: 0.25rem; color: #EAB308; font-weight: 800;">
                    <i class="fa-solid fa-star"></i>
                    <span style="color: #0F172A; font-weight: 900;">{{ number_format($product->average_rating, 1) }}</span>
                </div>
                <span style="color: #CBD5E1;">|</span>
                <a href="#reviews-section" style="color: #64748B; text-decoration: underline; font-weight: 700;">
                    {{ $product->total_reviews }} Penilaian
                </a>
                <span style="color: #CBD5E1;">|</span>
                <span style="color: #64748B; font-weight: 700;">
                    Total Sisa Stok: 
                    @if($product->isOutOfStock())
                        <strong style="color: #EF4444;">0 (Habis)</strong>
                    @else
                        <strong style="color: #0F172A;">{{ $product->stock }}</strong>
                    @endif
                </span>
            </div>

            <div style="font-size: 1.45rem; font-weight: 900; color: #0F172A; margin-bottom: 1.25rem;">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <!-- Size Selector Section with Individual Stock Qty -->
            <div style="margin-bottom: 1.25rem; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 1.15rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-size: 0.9rem; font-weight: 800; color: #0F172A;">
                        <i class="fa-solid fa-ruler" style="color: #22C55E; margin-right: 0.35rem;"></i> Ukuran & Ketersediaan:
                    </div>
                    <span id="selected-stock-display" style="font-size: 0.75rem; font-weight: 800; color: {{ $product->isOutOfStock() ? '#DC2626' : '#16A34A' }}; background: {{ $product->isOutOfStock() ? '#FEE2E2' : '#DCFCE7' }}; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        @if($product->isOutOfStock())
                            Stok Habis
                        @else
                            Tersedia: {{ $sizeStocksMap[$firstAvailableSize] ?? $product->stock }} Item
                        @endif
                    </span>
                </div>

                <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;" id="sizes-group">
                    @foreach($sizeStocksMap as $szName => $szStock)
                        @php
                            $isOutOfStock = ($product->isOutOfStock() || $szStock <= 0);
                            $isActive = ($szName === $firstAvailableSize && !$isOutOfStock);
                        @endphp
                        <button type="button" 
                                class="size-btn {{ $isActive ? 'active' : '' }} {{ $isOutOfStock ? 'disabled' : '' }}" 
                                onclick="{{ $isOutOfStock ? '' : "selectSize('$szName', $szStock, this)" }}"
                                {{ $isOutOfStock ? 'disabled' : '' }}
                                title="{{ $isOutOfStock ? 'Stok ukuran ini habis' : 'Stok: ' . $szStock }}">
                            <span>{{ $szName }}</span>
                            <span class="size-qty-badge">{{ $isOutOfStock ? 'Habis' : $szStock }}</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" id="selected-size-input" value="{{ $firstAvailableSize }}">
            </div>

            <!-- Description Box -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 1rem; font-size: 0.85rem; color: #64748B; line-height: 1.5;">
                <strong style="color: #0F172A; display: block; margin-bottom: 0.35rem;">
                    <i class="fa-solid fa-circle-info" style="color: #0F172A; margin-right: 0.25rem;"></i> Deskripsi & Detail Kondisi:
                </strong>
                {{ $product->description }}
            </div>
        </div>

        <!-- RIGHT COLUMN: Shopping Cart / Checkout Form Container -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 2rem 2.25rem; min-height: 480px; box-shadow: 0 8px 24px rgba(0,0,0,0.02);">
            
            <h2 style="font-size: 1.4rem; font-weight: 900; color: #0F172A; margin: 0 0 1.5rem; letter-spacing: -0.3px;">
                Shopping cart
            </h2>

            <!-- Selected Item Summary Pill -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 52px; height: 52px; border-radius: 10px; background: #EEF2F6; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                        @if($firstImage)
                            <img src="{{ $firstImage }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <i class="fa-solid fa-shirt" style="color: #0F172A;"></i>
                        @endif
                    </div>
                    <div>
                        <div style="font-size: 0.95rem; font-weight: 900; color: #0F172A; line-height: 1.25;">{{ $product->name }}</div>
                        <div style="font-size: 0.8rem; color: #64748B; margin-top: 0.25rem;">
                            @if($product->isOutOfStock())
                                <span style="color: #DC2626; font-weight: 800;"><i class="fa-solid fa-circle-xmark"></i> Stok Habis (Tidak dapat diorder)</span>
                            @else
                                Ukuran terpilih: <span id="summary-size-label" style="font-weight: 800; color: #0F172A;">{{ $firstAvailableSize }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="font-size: 1.15rem; font-weight: 900; color: #0F172A; text-align: right; white-space: nowrap;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
            </div>

            @if($product->isOutOfStock())
                <!-- Out of stock state: Disabled order controls & chat button -->
                <div style="background: #FEF2F2; border: 1.5px dashed #FCA5A5; border-radius: 16px; padding: 1.75rem; text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 52px; height: 52px; background: #FEE2E2; border-radius: 50%; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 0.75rem;">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <h3 style="font-size: 1.1rem; font-weight: 900; color: #991B1B; margin: 0 0 0.35rem;">Barang Sedang Habis</h3>
                    <p style="font-size: 0.85rem; color: #7F1D1D; margin: 0; line-height: 1.5;">
                        Pemesanan untuk barang ini ditutup karena stok kosong. Anda tetap bisa melihat data barang ini sebagai referensi struk atau menanyakan jadwal restock langsung ke penjual via Chat.
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <a href="{{ route('chat.index', ['user_id' => $product->user_id, 'product_id' => $product->id]) }}" 
                       style="padding: 1rem; font-size: 0.95rem; font-weight: 800; color: #FFF; background: #0F172A; border-radius: 14px; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(15,23,42,0.25); transition: background 0.2s;"
                       onmouseover="this.style.background='#1E293B';"
                       onmouseout="this.style.background='#0F172A';">
                        <i class="fa-regular fa-comment-dots" style="color: #22C55E;"></i> Chat Toko Penjual (Tanya Restock)
                    </a>

                    <button type="button" disabled style="padding: 0.9rem; font-size: 0.9rem; font-weight: 800; color: #94A3B8; background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 14px; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fa-solid fa-lock"></i> Tombol Order Dinonaktifkan
                    </button>
                </div>
            @else
                <!-- Normal Checkout Form when stock is available -->
                <form action="{{ route('orders.checkout') }}" method="GET">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="selected_size" id="checkout-size-hidden" value="{{ $firstAvailableSize }}">

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.5rem;">
                            <i class="fa-solid fa-envelope" style="color: #22C55E; margin-right: 0.35rem;"></i> Email / ID Pengguna Pengiriman <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" 
                               name="player_tag" 
                               value="{{ auth()->check() ? auth()->user()->email : '' }}"
                               placeholder="Masukkan email Anda (contoh: andriyanmaulana49@gmail.com)" 
                               required 
                               style="width: 100%; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; padding: 0.85rem 1rem; color: #0F172A; font-weight: 700; font-size: 0.95rem; outline: none; transition: all 0.2s;"
                               onfocus="this.style.borderColor='#0F172A'; this.style.background='#FFF';"
                               onblur="this.style.borderColor='#CBD5E1'; this.style.background='#F8FAFC';">
                    </div>

                    <!-- Total Row -->
                    <div style="border-top: 1px solid #E2E8F0; padding-top: 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-size: 0.95rem; font-weight: 700; color: #64748B;">Total Pembayaran</span>
                        <span style="font-size: 1.6rem; font-weight: 900; color: #0F172A;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Action Buttons: + Keranjang & Lanjut Pembayaran -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button type="button" 
                                onclick='addCurrentItemToCart()' 
                                style="padding: 1rem; font-size: 0.95rem; font-weight: 800; color: #0F172A; background: #FFFFFF; border: 1.5px solid #CBD5E1; border-radius: 14px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#F8FAFC'; this.style.borderColor='#0F172A';"
                                onmouseout="this.style.background='#FFFFFF'; this.style.borderColor='#CBD5E1';">
                            <i class="fa-solid fa-cart-plus" style="margin-right: 0.4rem;"></i> + Keranjang
                        </button>

                        <button type="submit" 
                                style="padding: 1rem; font-size: 0.95rem; font-weight: 800; color: #FFF; background: #000000; border: none; border-radius: 14px; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.25); transition: all 0.2s;"
                                onmouseover="this.style.background='#1E293B';"
                                onmouseout="this.style.background='#000000';">
                            Beli Sekarang <i class="fa-solid fa-arrow-right-long" style="margin-left: 0.4rem;"></i>
                        </button>
                    </div>

                    <!-- Chat Penjual secondary action -->
                    <a href="{{ route('chat.index', ['user_id' => $product->user_id, 'product_id' => $product->id]) }}"
                       style="margin-top: 1rem; width: 100%; box-sizing: border-box; padding: 0.75rem; font-size: 0.85rem; font-weight: 800; color: #0F172A; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 12px; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.2s;"
                       onmouseover="this.style.background='#EEF2F6'; this.style.borderColor='#0F172A';"
                       onmouseout="this.style.background='#F8FAFC'; this.style.borderColor='#CBD5E1';">
                        <i class="fa-regular fa-comment-dots" style="color: #22C55E;"></i> Chat Penjual Mengenai Barang Ini
                    </a>
                </form>
            @endif

            <div style="margin-top: 2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: #64748B; font-size: 0.8rem; font-weight: 600;">
                <i class="fa-solid fa-shield-halved" style="color: #22C55E;"></i> Transaksi terenkripsi & pembayaran terverifikasi otomatis
            </div>

        </div>

    </div>

    <!-- BAR PREVIEW TOKO PENJUAL (Shopee / Tokped Style) -->
    @php
        $seller = $product->user;
        $sellerStoreName = $seller ? $seller->store_name : 'Ruang Lemari Official Seller';
        $sellerSlogan = $seller ? $seller->store_slogan : 'Koleksi Thrifting & Preloved Fashion Terkurasi';
        $sellerCity = $seller ? $seller->store_city : 'Jakarta';
        $sellerRating = $seller ? $seller->store_rating : 5.0;
        $sellerReviewCount = $seller ? $seller->store_review_count : 0;
        $sellerTotalProducts = $seller ? $seller->products()->count() : 1;

        $storePp = null;
        if ($seller && $seller->store_logo) {
            $storePp = asset('storage/' . $seller->store_logo);
        } elseif ($seller && $seller->avatar) {
            $storePp = asset('storage/' . $seller->avatar);
        }
    @endphp
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.5rem 2rem; margin-bottom: 3.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
            
            <!-- Left Store Profile (Clickable Store Avatar & Name) -->
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <a href="{{ $seller ? route('shop.store', $seller->id) : '#' }}" 
                   title="Lihat Profil & Toko {{ $sellerStoreName }}"
                   style="width: 66px; height: 66px; border-radius: 18px; overflow: hidden; background: #0F172A; border: 2.5px solid #22C55E; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 1.85rem; font-weight: 900; box-shadow: 0 6px 16px rgba(15,23,42,0.12); flex-shrink: 0; text-decoration: none; transition: transform 0.2s;"
                   onmouseover="this.style.transform='scale(1.08)';"
                   onmouseout="this.style.transform='scale(1)';">
                    @if($storePp)
                        <img src="{{ $storePp }}" alt="PP Toko {{ $sellerStoreName }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 1.6rem; font-weight: 900;">
                            {{ strtoupper(substr($sellerStoreName, 0, 1)) }}
                        </div>
                    @endif
                </a>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <a href="{{ $seller ? route('shop.store', $seller->id) : '#' }}" 
                           style="font-size: 1.15rem; font-weight: 900; color: #0F172A; margin: 0; text-decoration: none;"
                           onmouseover="this.style.textDecoration='underline';"
                           onmouseout="this.style.textDecoration='none';">
                            {{ $sellerStoreName }}
                        </a>
                        <span style="background: rgba(34, 197, 94, 0.15); color: #16A34A; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 20px;">
                            <i class="fa-solid fa-circle-check"></i> STAR SELLER
                        </span>
                    </div>
                    <p style="font-size: 0.8rem; color: #64748B; margin: 0.25rem 0 0 0;">
                        <i class="fa-solid fa-location-dot" style="color: #22C55E;"></i> {{ $sellerCity }} | {{ $sellerSlogan }}
                    </p>
                </div>
            </div>

            <!-- Middle Stats (Shopee/Tokopedia metrics) -->
            <div style="display: flex; gap: 2rem; align-items: center; border-left: 1px solid #E2E8F0; padding-left: 2rem;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748B; font-weight: 700;">Penilaian Toko</div>
                    <div style="font-size: 1.05rem; font-weight: 900; color: #CA8A04; display: flex; align-items: center; gap: 0.25rem; margin-top: 0.15rem;">
                        <i class="fa-solid fa-star"></i> {{ number_format($sellerRating, 1) }} <span style="font-size: 0.75rem; color: #64748B;">({{ $sellerReviewCount }})</span>
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.75rem; color: #64748B; font-weight: 700;">Produk Jualan</div>
                    <div style="font-size: 1.05rem; font-weight: 900; color: #0F172A; margin-top: 0.15rem;">
                        {{ $sellerTotalProducts }} Barang
                    </div>
                </div>

                <div>
                    <div style="font-size: 0.75rem; color: #64748B; font-weight: 700;">Performa Chat</div>
                    <div style="font-size: 1.05rem; font-weight: 900; color: #16A34A; margin-top: 0.15rem;">
                        100% (Hitungan Menit)
                    </div>
                </div>
            </div>

            <!-- Right Actions: Chat Penjual & Kunjungi Toko -->
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <a href="{{ route('chat.index', ['user_id' => $seller?->id, 'product_id' => $product->id]) }}" 
                   style="background: #FFFFFF; color: #0F172A; font-size: 0.85rem; font-weight: 800; padding: 0.65rem 1.15rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: 1.5px solid #CBD5E1; transition: all 0.2s;" 
                   onmouseover="this.style.background='#F8FAFC'; this.style.borderColor='#0F172A';" 
                   onmouseout="this.style.background='#FFFFFF'; this.style.borderColor='#CBD5E1';">
                    <i class="fa-regular fa-comment-dots" style="color: #22C55E;"></i> Chat Toko
                </a>

                <a href="{{ $seller ? route('shop.store', $seller->id) : '#' }}" 
                   style="background: #0F172A; color: #FFF; font-size: 0.85rem; font-weight: 800; padding: 0.65rem 1.25rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s;" 
                   onmouseover="this.style.background='#1E293B';" 
                   onmouseout="this.style.background='#0F172A';">
                    <i class="fa-solid fa-store"></i> Kunjungi Toko
                </a>
            </div>

        </div>
    </div>

    <!-- REVIEW & RATING PRODUK SECTION (Shopee / Tokped Style) -->
    <div id="reviews-section" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 2rem 2.25rem; margin-bottom: 3.5rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.35rem; font-weight: 900; color: #0F172A; margin: 0;">
                    Penilaian & Review Produk ({{ $product->total_reviews }})
                </h3>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                    Ulasan otentik dari pembeli yang telah menyelesaikan transaksi
                </p>
            </div>

            <!-- Big Rating Badge -->
            <div style="display: flex; align-items: center; gap: 0.85rem; background: #FEFCE8; border: 1.5px solid #FEF08A; padding: 0.65rem 1.25rem; border-radius: 16px;">
                <div style="font-size: 1.85rem; font-weight: 900; color: #CA8A04;">
                    {{ number_format($product->average_rating, 1) }}
                </div>
                <div>
                    <div style="color: #EAB308; font-size: 0.85rem;">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa-solid fa-star {{ $i <= round($product->average_rating) ? '' : 'style=opacity:0.3;' }}"></i>
                        @endfor
                    </div>
                    <div style="font-size: 0.75rem; color: #854D0E; font-weight: 700; margin-top: 0.15rem;">
                        dari 5 bintang
                    </div>
                </div>
            </div>
        </div>

        @if($product->reviews->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach($product->reviews as $rev)
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #0F172A; color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.85rem;">
                                    {{ strtoupper(substr($rev->reviewer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 0.9rem; color: #0F172A;">
                                        {{ $rev->reviewer_name }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #94A3B8;">
                                        {{ $rev->created_at->format('d M Y, H:i') }} | Terverifikasi Membeli
                                    </div>
                                </div>
                            </div>

                            <div style="color: #EAB308; font-size: 0.85rem;">
                                @for($s=1; $s<=5; $s++)
                                    <i class="fa-solid fa-star {{ $s <= $rev->rating ? '' : 'style=color:#CBD5E1;' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <p style="font-size: 0.88rem; color: #334155; margin: 0.65rem 0 0 0; line-height: 1.5;">
                            {{ $rev->comment ?: 'Pembeli tidak meninggalkan komentar tertulis, penilaian 5 bintang sempurna!' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2.5rem 1rem; color: #64748B;">
                <i class="fa-regular fa-comment-dots fa-3x" style="color: #CBD5E1; margin-bottom: 0.85rem; display: block;"></i>
                <h4 style="font-size: 1.05rem; font-weight: 800; color: #0F172A; margin: 0 0 0.25rem;">Belum ada ulasan untuk barang ini</h4>
                <p style="font-size: 0.85rem; margin: 0;">Jadilah pembeli pertama yang memberikan ulasan setelah menyelesaikan pesanan!</p>
            </div>
        @endif

    </div>

    <!-- REKOMENDASI BARANG KATEGORI SERUPA (5 ITEMS PER ROW LAYOUT) -->
    @if($relatedProducts->count() > 0)
        <div style="border-top: 1.5px solid #E2E8F0; padding-top: 2.5rem;">
            
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.75rem;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 900; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                        Rekomendasi Barang Serupa
                    </h2>
                    <p style="color: #64748B; font-size: 0.88rem; margin-top: 0.25rem; margin-bottom: 0;">
                        Pilihan koleksi lain dalam kategori {{ strtolower($product->category->name) }}
                    </p>
                </div>

                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" style="color: #0F172A; font-weight: 800; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                    Lihat Semua <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem;"></i>
                </a>
            </div>

            <!-- 5 Items Grid Layout Matching Home -->
            <div class="thrift-products-grid-5">
                @foreach($relatedProducts as $rel)
                    @php
                        $relImgs = $rel->images;
                        $relFirstImg = $relImgs->count() > 0 ? asset('storage/' . $relImgs->first()->image_path) : ($rel->image ? asset('storage/' . $rel->image) : null);
                    @endphp

                    <div class="product-ref-card">
                        
                        <div class="product-photo-box">
                            @if($relFirstImg)
                                <img src="{{ $relFirstImg }}" alt="{{ $rel->name }}" loading="lazy">
                            @else
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94A3B8;">
                                    <div style="width: 50px; height: 50px; border-radius: 12px; background: #0F172A; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 1.4rem; margin-bottom: 0.4rem;">
                                        <i class="fa-solid fa-shirt"></i>
                                    </div>
                                    <span style="font-size: 0.7rem; font-weight: 700;">Preloved</span>
                                </div>
                            @endif
                        </div>

                        <div class="product-price-row">
                            <span class="product-price-val">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                        </div>

                        <div>
                            <h3 class="product-card-title" title="{{ $rel->name }}">{{ $rel->name }}</h3>
                            <div class="product-card-subtitle">
                                {{ $rel->badge ?? ($product->category->name . ' / Authentic') }}
                            </div>
                            <p class="product-card-desc">
                                {{ $rel->description }}
                            </p>
                        </div>

                        <div class="product-card-bottom">
                            <div class="product-spec-pill">
                                <i class="fa-solid fa-certificate" style="color: #22C55E; margin-right: 0.25rem;"></i>
                                {{ $rel->badge ? $rel->badge . ' • Authentic' : '100% Preloved Item' }}
                            </div>

                            <a href="{{ route('products.show', $rel->slug) }}" class="product-cta-btn">
                                Pilih item ini <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @endif

</div>

@push('scripts')
<script>
    function selectSize(size, stockQty, btnEl) {
        document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
        btnEl.classList.add('active');

        document.getElementById('selected-size-input').value = size;
        document.getElementById('checkout-size-hidden').value = size;
        document.getElementById('summary-size-label').textContent = size;
        document.getElementById('selected-stock-display').textContent = `Tersedia: ${stockQty} Item`;
    }

    function switchMainImage(src, thumbElement) {
        const mainImg = document.getElementById('main-product-img');
        if (mainImg) {
            mainImg.style.opacity = '0.3';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 120);
        }

        document.querySelectorAll('.thumb-item').forEach(el => {
            el.style.borderColor = '#E2E8F0';
        });
        thumbElement.style.borderColor = '#0F172A';
    }

    function addCurrentItemToCart() {
        const size = document.getElementById('selected-size-input').value || 'All Size';
        addToCart({
            id: {{ $product->id }},
            name: "{{ addslashes($product->name) }} (Size: " + size + ")",
            size: size,
            price: {{ $product->price }},
            badge: "{{ $product->badge ?? '' }}",
            category: "{{ $product->category->name }}"
        });
    }
</script>
@endpush
@endsection
