@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* Hero Banner */
    .thrift-hero-banner {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%);
        border-radius: 24px;
        padding: 2.25rem;
        color: #FFFFFF;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
    }

    .thrift-hero-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: -5%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(34, 197, 94, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
        pointer-events: none;
    }

    .thrift-hero-title {
        font-size: 2rem;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 0.4rem;
    }

    .thrift-hero-subtitle {
        color: #94A3B8;
        font-size: 0.95rem;
        margin-bottom: 1.75rem;
        max-width: 650px;
    }

    /* Category Filter Grid with Custom SVG Icons */
    .thrift-category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.85rem;
    }

    .thrift-category-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 1.1rem 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        color: #FFF;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .thrift-category-card:hover, .thrift-category-card.active {
        background: rgba(255, 255, 255, 0.2);
        border-color: #22C55E;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .thrift-category-card.active {
        border-width: 2px;
        border-color: #22C55E;
    }

    .thrift-category-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #0F172A;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #22C55E;
        margin-bottom: 0.65rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        transition: transform 0.2s ease;
    }

    .thrift-category-card:hover .thrift-category-icon {
        transform: scale(1.1);
    }

    .thrift-category-name {
        font-weight: 800;
        font-size: 0.82rem;
        color: #FFF;
    }

    /* Category Section */
    .category-section {
        margin-bottom: 3.5rem;
    }

    .category-section-header {
        border-bottom: 1.5px solid #E2E8F0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .category-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #0F172A;
        color: #22C55E;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .category-section-title {
        font-size: 1.5rem;
        font-weight: 900;
        color: #0F172A;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .category-pill-count {
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #64748B;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
    }

    .category-section-subtitle {
        color: #64748B;
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }

    /* 5-Column Responsive Product Grid */
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

    /* Clean Card matching uploaded reference */
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
        min-width: 0; /* Prevents flex/grid overflow */
    }

    .product-ref-card:hover {
        transform: translateY(-4px);
        border-color: #0F172A;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }

    /* Product Photo Container / Gallery */
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

    /* Mini Gallery Grid for multiple photos (2-5 photos) */
    .product-photo-grid {
        display: grid;
        width: 100%;
        height: 100%;
        gap: 1.5px;
        background: #E2E8F0;
    }

    .product-photo-grid.grid-2 { grid-template-columns: 1fr 1fr; }
    .product-photo-grid.grid-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .product-photo-grid.grid-3 > div:first-child { grid-row: span 2; }
    .product-photo-grid.grid-4 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .product-photo-grid.grid-5 { grid-template-columns: repeat(3, 1fr); grid-template-rows: 1fr 1fr; }
    .product-photo-grid.grid-5 > div:first-child { grid-column: span 2; grid-row: span 2; }

    .product-photo-grid div {
        overflow: hidden;
        position: relative;
    }

    .product-photo-grid img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-count-badge {
        position: absolute;
        bottom: 6px;
        right: 6px;
        background: rgba(15, 23, 42, 0.85);
        color: #FFFFFF;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 12px;
        backdrop-filter: blur(4px);
    }

    /* Price tag */
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
        color: #D97706; /* Warm amber / orange accent like reference */
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

    /* Bottom Info Badge & Black CTA Button */
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

{{-- Helper for category icons --}}
@php
    if (!function_exists('renderCategoryIcon')) {
        function renderCategoryIcon($catSlug, $faClass = null) {
            if ($catSlug === 'hoodie-crewneck') {
                // Clean inline Hoodie SVG Icon
                return '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8l3-3 2 1 3-3 3 3 2-1 3 3v13H4z"/><path d="M9 16a3 3 0 0 0 6 0"/><path d="M12 9v4"/></svg>';
            } elseif ($catSlug === 'celana-denim') {
                // Clean inline Jeans / Trousers SVG Icon
                return '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12v4l-1 14h-4l-1-10-1 10H7L6 7z"/></svg>';
            } elseif ($catSlug === 'outerwear-jaket') {
                return '<i class="fa-solid fa-vest"></i>';
            } elseif ($catSlug === 'kaos-vintage-tee') {
                return '<i class="fa-solid fa-shirt"></i>';
            } elseif ($catSlug === 'sepatu-sneakers') {
                return '<i class="fa-solid fa-shoe-prints"></i>';
            } elseif ($catSlug === 'topi-aksesoris') {
                return '<i class="fa-solid fa-hat-cowboy"></i>';
            }
            return '<i class="fa-solid ' . ($faClass ?? 'fa-tag') . '"></i>';
        }
    }
@endphp

{{-- HERO CATEGORY PICKER --}}
<div class="thrift-hero-banner">
    <div class="thrift-hero-title">Ruang Lemari</div>
    <div class="thrift-hero-subtitle">Temukan barang thrifting & preloved apparel original terkurasi dengan kondisi terbaik, garansi keaslian, dan foto real detail.</div>

    <div class="thrift-category-grid">
        <div class="thrift-category-card active" data-cat-slug="all" onclick="filterCategory('all', this)">
            <div class="thrift-category-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="thrift-category-name">Semua Barang</div>
        </div>

        @foreach($categories as $category)
            <div class="thrift-category-card" data-cat-slug="{{ $category->slug }}" onclick="filterCategory('{{ $category->slug }}', this)">
                <div class="thrift-category-icon">
                    {!! renderCategoryIcon($category->slug, $category->icon) !!}
                </div>
                <div class="thrift-category-name">{{ $category->name }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- Search Results Indicator --}}
@if(!empty($searchQuery))
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(34,197,94,0.15); color: #16A34A; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div>
                <div style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">
                    Hasil pencarian untuk: <span style="color: #16A34A;">"{{ $searchQuery }}"</span>
                </div>
                <div style="font-size: 0.8rem; color: #64748B;">
                    Ditemukan {{ $products->count() }} produk @if(!empty($matchedUsers) && $matchedUsers->count() > 0) & {{ $matchedUsers->count() }} toko/pengguna @endif
                </div>
            </div>
        </div>
        <a href="{{ route('products.index', $selectedCategorySlug ? ['category' => $selectedCategorySlug] : []) }}" 
           style="background: #0F172A; color: #FFFFFF; text-decoration: none; font-weight: 700; font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 0.4rem; transition: background 0.2s;">
            <i class="fa-solid fa-xmark"></i> Hapus Pencarian
        </a>
    </div>

    {{-- Hasil Pencarian Pengguna & Toko (Admin tidak dapat dicari) --}}
    @if(!empty($matchedUsers) && $matchedUsers->count() > 0)
        <div style="margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-store" style="color: #22C55E;"></i> Toko & Pengguna Terkait
                </h3>
                <span style="font-size: 0.75rem; color: #64748B; font-weight: 700;">{{ $matchedUsers->count() }} Toko Ditemukan</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                @foreach($matchedUsers as $mUser)
                    @php
                        $userStoreLogo = $mUser->store_logo ? asset('storage/' . $mUser->store_logo) : ($mUser->avatar ? asset('storage/' . $mUser->avatar) : null);
                    @endphp
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); transition: all 0.2s;" onmouseover="this.style.borderColor='#0F172A'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#E2E8F0'; this.style.transform='translateY(0)';">
                        <div style="display: flex; align-items: center; gap: 0.85rem; overflow: hidden;">
                            <div style="width: 52px; height: 52px; border-radius: 14px; overflow: hidden; background: #0F172A; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 2px solid #22C55E;">
                                @if($userStoreLogo)
                                    <img src="{{ $userStoreLogo }}" alt="{{ $mUser->store_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span style="color: #FFFFFF; font-weight: 900; font-size: 1.3rem;">{{ strtoupper(substr($mUser->store_name ?: $mUser->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div style="overflow: hidden;">
                                <div style="font-weight: 800; font-size: 0.95rem; color: #0F172A; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                                    {{ $mUser->store_name }}
                                </div>
                                <div style="font-size: 0.78rem; color: #64748B; margin-top: 0.15rem;">
                                    Oleh: <strong>{{ $mUser->name }}</strong>
                                </div>
                                <div style="font-size: 0.72rem; color: #16A34A; font-weight: 700; margin-top: 0.2rem;">
                                    <i class="fa-solid fa-location-dot"></i> {{ $mUser->store_city }} • {{ $mUser->products_count }} Barang
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('shop.store', $mUser->id) }}" style="background: #0F172A; color: #FFFFFF; text-decoration: none; font-weight: 800; font-size: 0.78rem; padding: 0.55rem 0.95rem; border-radius: 10px; white-space: nowrap; flex-shrink: 0; transition: background 0.2s;" onmouseover="this.style.background='#1E293B';" onmouseout="this.style.background='#0F172A';">
                            Kunjungi Toko
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endif

@if($products->count() === 0)
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 3.5rem 2rem; text-align: center; margin-bottom: 3rem;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: #F1F5F9; color: #94A3B8; display: inline-flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 style="font-weight: 900; font-size: 1.25rem; color: #0F172A; margin-bottom: 0.35rem;">
            @if(!empty($searchQuery))
                Tidak Ada Produk yang Cocok dengan "{{ $searchQuery }}"
            @else
                Belum Ada Produk Tersedia
            @endif
        </h3>
        <p style="color: #64748B; font-size: 0.88rem; max-width: 460px; margin: 0 auto 1.5rem;">
            @if(!empty($searchQuery))
                Coba gunakan kata kunci lain seperti nama brand, jenis jaket, hoodie, atau kategori lainnya.
            @else
                Semua barang sedang dalam kurasi atau stok belum ditambahkan oleh penjual.
            @endif
        </p>
        @if(!empty($searchQuery))
            <a href="{{ route('products.index') }}" style="background: #0F172A; color: #FFFFFF; text-decoration: none; font-weight: 800; font-size: 0.85rem; padding: 0.65rem 1.5rem; border-radius: 30px; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-arrow-left"></i> Lihat Semua Produk
            </a>
        @endif
    </div>
@endif

{{-- PRODUCTS GROUPED BY CATEGORY (5 ITEMS PER ROW, CLEAN NON-OVERLAPPING LAYOUT) --}}
@php
    $groupedProducts = $products->groupBy('category_id');
@endphp

@foreach($categories as $cat)
    @php
        $catProducts = $groupedProducts->get($cat->id, collect());
    @endphp

    @if($catProducts->count() > 0)
        <div class="category-section" id="cat-section-{{ $cat->slug }}" data-cat-slug="{{ $cat->slug }}">
            
            <!-- Section Header -->
            <div class="category-section-header">
                <div>
                    <div class="category-section-title-wrap">
                        <div class="category-section-icon">
                            {!! renderCategoryIcon($cat->slug, $cat->icon) !!}
                        </div>
                        <h2 class="category-section-title">{{ $cat->name }}</h2>
                        <span class="category-pill-count">{{ $catProducts->count() }} Pilihan Koleksi</span>
                    </div>
                    <div class="category-section-subtitle">
                        Pilihan original & terkurasi untuk kategori {{ strtolower($cat->name) }}
                    </div>
                </div>

                <div style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: rgba(34,197,94,0.1); padding: 0.3rem 0.75rem; border-radius: 30px; border: 1px solid rgba(34,197,94,0.25);">
                    <i class="fa-solid fa-shield-check"></i> 100% Authentic Guaranteed
                </div>
            </div>

            <!-- Product Cards Grid: 5 Items Per Row -->
            <div class="thrift-products-grid-5">
                @foreach($catProducts as $product)
                    @php
                        $imgs = $product->images;
                        $imgCount = $imgs->count();
                    @endphp

                    <div class="product-ref-card" data-searchable="{{ strtolower($product->name . ' ' . $cat->name . ' ' . $product->badge) }}">
                        
                        <!-- Photo Box / Multi-photo Preview -->
                        <div class="product-photo-box">
                            @if($imgCount > 1)
                                <div class="product-photo-grid grid-{{ min($imgCount, 5) }}">
                                    @foreach($imgs->take(5) as $pImg)
                                        <div>
                                            <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                <span class="photo-count-badge">
                                    <i class="fa-solid fa-images"></i> {{ $imgCount }} Foto
                                </span>
                            @elseif($imgCount === 1)
                                <img src="{{ asset('storage/' . $imgs->first()->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <!-- Clean Fallback Graphic with Category Icon -->
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94A3B8;">
                                    <div style="width: 50px; height: 50px; border-radius: 12px; background: #0F172A; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 1.4rem; margin-bottom: 0.4rem;">
                                        {!! renderCategoryIcon($cat->slug, $cat->icon) !!}
                                    </div>
                                    <span style="font-size: 0.7rem; font-weight: 700;">Preloved Item</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Row -->
                        <div class="product-price-row">
                            <span class="product-price-val">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Title, Store & Rating, Subtitle, & Description -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.35rem;">
                                <a href="{{ $product->user ? route('shop.store', $product->user->id) : '#' }}" style="text-decoration: none; max-width: 65%; overflow: hidden;" title="{{ $product->user ? $product->user->store_name : 'Ruang Lemari' }}">
                                    <span style="font-size: 0.72rem; color: #16A34A; font-weight: 800; background: #DCFCE7; padding: 0.15rem 0.45rem; border-radius: 6px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block;">
                                        <i class="fa-solid fa-store" style="font-size: 0.65rem;"></i> {{ $product->user ? Str::limit($product->user->store_name, 15) : 'Ruang Lemari' }}
                                    </span>
                                </a>
                                <span style="font-size: 0.72rem; color: #CA8A04; font-weight: 800; display: inline-flex; align-items: center; gap: 0.2rem;">
                                    <i class="fa-solid fa-star" style="font-size: 0.68rem; color: #EAB308;"></i> {{ number_format($product->average_rating, 1) }}
                                </span>
                            </div>

                            <h3 class="product-card-title" title="{{ $product->name }}">{{ $product->name }}</h3>
                            <div class="product-card-subtitle">
                                {{ $product->badge ?? ($cat->name . ' / Authentic') }}
                            </div>
                            <p class="product-card-desc">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Bottom Spec Pill & CTA Button -->
                        <div class="product-card-bottom">
                            <div class="product-spec-pill" title="{{ $product->badge ? $product->badge . ' • Authentic Thrift' : '100% Original Preloved Item' }}">
                                <i class="fa-solid fa-certificate" style="color: #22C55E; margin-right: 0.25rem;"></i>
                                {{ $product->badge ? $product->badge . ' • Authentic' : '100% Preloved Item' }}
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}" class="product-cta-btn">
                                Pilih item ini <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @endif
@endforeach

@push('scripts')
<script>
    function filterCategory(slug, el) {
        document.querySelectorAll('.thrift-category-card').forEach(c => c.classList.remove('active'));
        el.classList.add('active');

        const sections = document.querySelectorAll('.category-section');
        sections.forEach(sec => {
            if (slug === 'all' || sec.getAttribute('data-cat-slug') === slug) {
                sec.style.display = 'block';
            } else {
                sec.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
