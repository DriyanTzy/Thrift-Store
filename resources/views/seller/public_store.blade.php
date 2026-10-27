@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* 5-Column Responsive Product Grid - Exact Same as Home */
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

    /* Clean Card matching Home */
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

<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 3.5rem;">

    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748B; font-weight: 700; margin-bottom: 1.5rem;">
        <a href="{{ route('products.index') }}" style="color: #64748B; text-decoration: none;"><i class="fa-solid fa-house"></i> Home</a>
        <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
        <span style="color: #0F172A; font-weight: 800;">Profil Toko {{ $seller->name }}</span>
    </div>

    <!-- Store Profile Header (Shopee / Tokopedia Style Store Banner) -->
    @php
        $storePp = null;
        if ($seller->store_logo) {
            $storePp = asset('storage/' . $seller->store_logo);
        } elseif ($seller->avatar) {
            $storePp = asset('storage/' . $seller->avatar);
        }

        $headerBgStyle = $seller->store_banner 
            ? "background: linear-gradient(rgba(15, 23, 42, 0.82), rgba(15, 23, 42, 0.95)), url('" . asset('storage/' . $seller->store_banner) . "') center/cover no-repeat;" 
            : "background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);";
    @endphp

    <div style="{{ $headerBgStyle }} border-radius: 24px; padding: 2.5rem; color: #FFFFFF; margin-bottom: 2.5rem; box-shadow: 0 12px 30px rgba(15,23,42,0.15); border: 1.5px solid rgba(255,255,255,0.08); position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; position: relative; z-index: 2;">
            
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <!-- Foto Profil / Logo Toko (PP Toko) -->
                <div style="width: 84px; height: 84px; border-radius: 22px; overflow: hidden; background: #0F172A; border: 3px solid #22C55E; box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative;">
                    @if($storePp)
                        <img src="{{ $storePp }}" alt="PP Toko {{ $seller->store_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #16A34A 0%, #22C55E 100%); display: flex; align-items: center; justify-content: center; color: #020617; font-size: 2.2rem; font-weight: 900;">
                            {{ strtoupper(substr($seller->store_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                        <h1 style="font-size: 1.85rem; font-weight: 900; margin: 0; letter-spacing: -0.5px;">{{ $seller->store_name }}</h1>
                        <span style="background: rgba(34, 197, 94, 0.2); color: #4ADE80; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 20px; border: 1px solid rgba(34, 197, 94, 0.4);">
                            <i class="fa-solid fa-circle-check"></i> STAR SELLER
                        </span>
                    </div>
                    <p style="color: #94A3B8; font-size: 0.9rem; margin-top: 0.35rem; margin-bottom: 0;">
                        <i class="fa-solid fa-location-dot" style="color: #4ADE80;"></i> {{ $seller->store_city }} • {{ $seller->store_slogan }} | Bergabung sejak {{ $seller->created_at->format('M Y') }}
                    </p>
                </div>
            </div>

            <!-- Follow / Chat buttons -->
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <button type="button" onclick="alert('Anda sekarang mengikuti toko {{ $seller->store_name }}!')" style="background: #22C55E; color: #0F172A; font-weight: 900; font-size: 0.9rem; padding: 0.75rem 1.4rem; border-radius: 12px; border: none; cursor: pointer; transition: all 0.2s;">
                    <i class="fa-solid fa-plus"></i> Ikuti Toko
                </button>
                <a href="{{ route('chat.index', ['user_id' => $seller->id]) }}" style="background: rgba(255,255,255,0.15); color: #FFF; font-weight: 800; font-size: 0.9rem; padding: 0.75rem 1.25rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
                    <i class="fa-regular fa-comment-dots" style="color: #4ADE80;"></i> Chat Penjual
                </a>
            </div>

        </div>

        <!-- Store Stats Grid (Rating, Total Items, Chat Performance) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
            
            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.8rem; color: #FBBF24; font-weight: 700;">
                    <i class="fa-solid fa-star"></i> Penilaian Akumulasi Toko
                </span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #FBBF24; margin-top: 0.2rem; display: flex; align-items: baseline; gap: 0.4rem;">
                    {{ number_format($storeRating, 1) }} <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">/ 5.0 ({{ $storeReviewCount }} Ulasan)</span>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.8rem; color: #94A3B8; font-weight: 600;">Total Produk Jualan</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #FFF; margin-top: 0.2rem;">{{ $totalItems }} Barang</div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.8rem; color: #4ADE80; font-weight: 600;">Barang Siap Kirim (Ready)</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #4ADE80; margin-top: 0.2rem;">{{ $activeItems }} Item</div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.8rem; color: #38BDF8; font-weight: 600;">Performa Chat & Respon</span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #38BDF8; margin-top: 0.2rem;">100% (Hitungan Menit)</div>
            </div>

        </div>
    </div>

    <!-- SEMUA BARANG JUALAN TOKO INI SECTION -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 24px; padding: 2rem 2.25rem; margin-bottom: 3.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
            <div>
                <h2 style="font-size: 1.4rem; font-weight: 900; color: #0F172A; margin: 0;">
                    Koleksi Barang yang Dijual oleh {{ $seller->store_name }}
                </h2>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                    Semua barang thrifting original yang siap dibeli dari toko ini
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <span style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.25rem 0.85rem; border-radius: 20px;">
                    {{ $activeItems }} Barang Ready
                </span>
                @if($soldOutItems > 0)
                    <span style="font-size: 0.8rem; font-weight: 800; color: #DC2626; background: #FEE2E2; padding: 0.25rem 0.85rem; border-radius: 20px;">
                        {{ $soldOutItems }} Habis
                    </span>
                @endif
            </div>
        </div>

        @if(count($products) > 0)
            <!-- 5 Items Grid Layout - Exact Same Shape and Position as Home -->
            <div class="thrift-products-grid-5">
                @foreach($products as $prod)
                    @php
                        $pImgs = $prod->images;
                        $pImgCount = $pImgs->count();
                        $firstImg = $pImgCount > 0 ? asset('storage/' . $pImgs->first()->image_path) : ($prod->image ? asset('storage/' . $prod->image) : null);
                    @endphp

                    <div class="product-ref-card">
                        
                        <!-- Ready / Sold Badge on Photo Corner -->
                        <div class="product-photo-box">
                            @if($prod->stock > 0)
                                <span style="position: absolute; top: 6px; left: 6px; background: rgba(34, 197, 94, 0.9); color: #FFF; font-size: 0.65rem; font-weight: 900; padding: 2px 7px; border-radius: 20px; z-index: 2;">
                                    READY ({{ $prod->stock }})
                                </span>
                            @else
                                <span style="position: absolute; top: 6px; left: 6px; background: rgba(239, 68, 68, 0.9); color: #FFF; font-size: 0.65rem; font-weight: 900; padding: 2px 7px; border-radius: 20px; z-index: 2;">
                                    SOLD OUT
                                </span>
                            @endif

                            @if($pImgCount > 1)
                                <div class="product-photo-grid grid-{{ min($pImgCount, 5) }}">
                                    @foreach($pImgs->take(5) as $pImg)
                                        <div>
                                            <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $prod->name }}" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                                <span class="photo-count-badge">
                                    <i class="fa-solid fa-images"></i> {{ $pImgCount }} Foto
                                </span>
                            @elseif($firstImg)
                                <img src="{{ $firstImg }}" alt="{{ $prod->name }}" loading="lazy">
                            @else
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94A3B8;">
                                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #0F172A; display: flex; align-items: center; justify-content: center; color: #22C55E; font-size: 1.35rem; margin-bottom: 0.35rem;">
                                        <i class="fa-solid fa-shirt"></i>
                                    </div>
                                    <span style="font-size: 0.7rem; font-weight: 700;">Preloved</span>
                                </div>
                            @endif
                        </div>

                        <!-- Price Row -->
                        <div class="product-price-row">
                            <span class="product-price-val">Rp {{ number_format($prod->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Title, Rating, Subtitle, & Description -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.3rem;">
                                <span style="font-size: 0.72rem; color: #64748B; font-weight: 800; text-transform: uppercase;">
                                    {{ $prod->category->name }}
                                </span>
                                <span style="font-size: 0.72rem; color: #CA8A04; font-weight: 800; display: inline-flex; align-items: center; gap: 0.2rem;">
                                    <i class="fa-solid fa-star" style="font-size: 0.68rem; color: #EAB308;"></i> {{ number_format($prod->average_rating, 1) }}
                                </span>
                            </div>

                            <h3 class="product-card-title" title="{{ $prod->name }}">{{ $prod->name }}</h3>
                            <div class="product-card-subtitle">
                                {{ $prod->badge ?? ($prod->category->name . ' / Authentic') }}
                            </div>
                            <p class="product-card-desc">
                                {{ $prod->description }}
                            </p>
                        </div>

                        <!-- Bottom Spec Pill & CTA Button -->
                        <div class="product-card-bottom">
                            <div class="product-spec-pill" title="Ukuran Ready: {{ $prod->sizes ?? 'All Size' }}">
                                <i class="fa-solid fa-ruler-combined" style="color: #22C55E; margin-right: 0.25rem;"></i>
                                Size: {{ $prod->sizes ? Str::limit($prod->sizes, 16) : 'Ready' }}
                            </div>

                            <a href="{{ route('products.show', $prod->slug) }}" class="product-cta-btn">
                                Pilih item ini <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3.5rem 1rem; color: #64748B;">
                <i class="fa-solid fa-box-open fa-3x" style="color: #CBD5E1; margin-bottom: 1rem; display: block;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0F172A;">Toko Ini Belum Memiliki Produk Aktif</h3>
                <p style="font-size: 0.875rem; margin-top: 0.25rem;">Silakan cek kembali di lain waktu.</p>
            </div>
        @endif
    </div>

    <!-- REVIEW / TESTIMONI TOKO SECTION -->
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 24px; padding: 2rem 2.25rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem;">
            <div>
                <h3 style="font-size: 1.3rem; font-weight: 900; color: #0F172A; margin: 0;">
                    Ulasan & Reputasi Toko ({{ $storeReviewCount }})
                </h3>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                    Akumulasi review dari seluruh pembeli di toko ini
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.65rem; background: #FEFCE8; border: 1.5px solid #FEF08A; padding: 0.5rem 1rem; border-radius: 14px;">
                <span style="font-size: 1.4rem; font-weight: 900; color: #CA8A04;">{{ number_format($storeRating, 1) }}</span>
                <div style="color: #EAB308; font-size: 0.8rem;">
                    @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= round($storeRating) ? '' : 'style=opacity:0.3;' }}"></i>
                    @endfor
                </div>
            </div>
        </div>

        @if($storeReviews->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem;">
                @foreach($storeReviews as $rev)
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.15rem;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.4rem;">
                            <span style="font-weight: 800; font-size: 0.9rem; color: #0F172A;">{{ $rev->reviewer_name }}</span>
                            <div style="color: #EAB308; font-size: 0.75rem;">
                                @for($s=1; $s<=5; $s++)
                                    <i class="fa-solid fa-star {{ $s <= $rev->rating ? '' : 'style=color:#CBD5E1;' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <div style="font-size: 0.75rem; color: #16A34A; font-weight: 700; margin-bottom: 0.5rem;">
                            Membeli: {{ $rev->product ? $rev->product->name : 'Produk Thrift' }}
                        </div>
                        <p style="font-size: 0.82rem; color: #334155; margin: 0; line-height: 1.45;">
                            {{ $rev->comment ?: 'Barang sangat bagus, pengiriman cepat dan kondisi istimewa.' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: #64748B; font-size: 0.9rem;">
                Belum ada ulasan untuk toko ini.
            </div>
        @endif
    </div>

</div>
@endsection
