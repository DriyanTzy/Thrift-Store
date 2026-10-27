@extends('layouts.app')

@section('content')
@push('styles')
<style>
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
        justify-content: space-between;
        align-items: baseline;
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

    /* Modal Styling for Stock & Price Manager */
    .stock-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1050;
    }

    .stock-modal-box {
        background: #FFFFFF;
        border-radius: 20px;
        max-width: 480px;
        width: 90%;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalFade 0.25s ease-out;
    }

    @keyframes modalFade {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 3.5rem;">

    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748B; font-weight: 700;">
            <a href="{{ route('products.index') }}" style="color: #64748B; text-decoration: none;"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span style="color: #0F172A; font-weight: 800;">Dashboard Toko & Inventaris</span>
        </div>
        
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('profile.edit', ['tab' => 'store']) }}" style="background: #FFFFFF; color: #0F172A; font-weight: 800; font-size: 0.8rem; padding: 0.45rem 1rem; border-radius: 12px; border: 1.5px solid #CBD5E1; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="fa-solid fa-pen-to-square" style="color: #3B82F6;"></i> Pengaturan Profil Toko
            </a>
            <a href="{{ route('shop.store', $user->id) }}" target="_blank" style="background: #0F172A; color: #FFF; font-weight: 800; font-size: 0.8rem; padding: 0.45rem 1rem; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                <i class="fa-solid fa-eye" style="color: #22C55E;"></i> Lihat Etalase Publik
            </a>
        </div>
    </div>

    <!-- Store Profile Banner (Separated from personal user profile, 1 email) -->
    @php
        $storePp = null;
        if ($user->store_logo) {
            $storePp = asset('storage/' . $user->store_logo);
        } elseif ($user->avatar) {
            $storePp = asset('storage/' . $user->avatar);
        }

        $storeBannerBg = $user->store_banner 
            ? "background: linear-gradient(rgba(15, 23, 42, 0.82), rgba(15, 23, 42, 0.95)), url('" . asset('storage/' . $user->store_banner) . "') center/cover no-repeat;" 
            : "background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);";
    @endphp

    <div style="{{ $storeBannerBg }} border-radius: 24px; padding: 2.25rem 2.5rem; color: #FFFFFF; margin-bottom: 2.25rem; box-shadow: 0 12px 30px rgba(15,23,42,0.15); border: 1.5px solid rgba(255,255,255,0.08); position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; position: relative; z-index: 2;">
            
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <!-- PP Toko -->
                <div style="width: 76px; height: 76px; border-radius: 20px; overflow: hidden; background: #0F172A; border: 3px solid #22C55E; box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3); display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative;">
                    @if($storePp)
                        <img src="{{ $storePp }}" alt="PP Toko {{ $user->store_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #16A34A 0%, #22C55E 100%); display: flex; align-items: center; justify-content: center; color: #020617; font-size: 2rem; font-weight: 900;">
                            {{ strtoupper(substr($user->store_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                        <h1 style="font-size: 1.85rem; font-weight: 900; margin: 0; letter-spacing: -0.5px;">
                            {{ $user->store_name }}
                        </h1>
                        <span style="background: rgba(34, 197, 94, 0.2); color: #4ADE80; font-size: 0.75rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 20px; border: 1px solid rgba(34, 197, 94, 0.4);">
                            <i class="fa-solid fa-circle-check"></i> TOKO TERVERIFIKASI
                        </span>
                    </div>
                    <p style="color: #94A3B8; font-size: 0.88rem; margin-top: 0.35rem; margin-bottom: 0;">
                        <i class="fa-solid fa-location-dot" style="color: #22C55E;"></i> {{ $user->store_city }} • {{ $user->store_slogan }} (Akun: {{ $user->email }})
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('seller.products.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #22C55E; color: #0F172A; font-weight: 900; font-size: 0.95rem; padding: 0.85rem 1.5rem; border-radius: 14px; text-decoration: none; box-shadow: 0 4px 15px rgba(34, 197, 94, 0.35); transition: all 0.2s;">
                    <i class="fa-solid fa-plus"></i> Upload Barang Baru
                </a>
            </div>
        </div>

        <!-- Accurate Stock & Inventory Summary Grid (Masuk, Keluar, Sisa Terkini) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
            
            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.78rem; color: #60A5FA; font-weight: 700;">
                    <i class="fa-solid fa-arrow-down" style="color: #60A5FA;"></i> Total Stok Masuk
                </span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #60A5FA; margin-top: 0.2rem;">
                    +{{ number_format($totalStockIn) }} <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">pcs</span>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.78rem; color: #F87171; font-weight: 700;">
                    <i class="fa-solid fa-arrow-up" style="color: #F87171;"></i> Total Stok Keluar (Terjual)
                </span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #F87171; margin-top: 0.2rem;">
                    -{{ number_format($totalStockOut) }} <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">pcs</span>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1.5px solid rgba(34,197,94,0.4);">
                <span style="font-size: 0.78rem; color: #4ADE80; font-weight: 800;">
                    <i class="fa-solid fa-boxes-stacked" style="color: #4ADE80;"></i> Sisa Stok Saat Ini (Akurat)
                </span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #4ADE80; margin-top: 0.2rem;">
                    {{ number_format($totalCurrentStock) }} <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">pcs</span>
                </div>
            </div>

            <div style="background: rgba(255,255,255,0.05); padding: 1rem 1.25rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.78rem; color: #FBBF24; font-weight: 700;">
                    <i class="fa-solid fa-star"></i> Rating Toko
                </span>
                <div style="font-size: 1.6rem; font-weight: 900; color: #FBBF24; margin-top: 0.2rem;">
                    {{ number_format($storeRating, 1) }} <span style="font-size: 0.85rem; color: #94A3B8; font-weight: 600;">({{ $storeReviewCount }} Ulasan)</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Kelola Voucher & Promo Toko (Pengaturan Etalase Seller) -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 1.75rem 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-ticket" style="color: #22C55E;"></i> Voucher & Kode Promo Toko Saya
                </h2>
                <p style="font-size: 0.82rem; color: #64748B; margin-top: 0.2rem; margin-bottom: 0;">
                    Tingkatkan penjualan dengan memberikan kupon diskon khusus untuk produk-produk di toko Anda.
                </p>
            </div>
            <button type="button" onclick="document.getElementById('sellerVoucherForm').style.display = document.getElementById('sellerVoucherForm').style.display === 'none' ? 'block' : 'none';" style="background: #0F172A; color: #FFF; font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1.1rem; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-plus" style="color: #22C55E;"></i> Buat Voucher Toko
            </button>
        </div>

        <!-- Form Tambah Voucher Seller -->
        <div id="sellerVoucherForm" style="display: none; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 16px; padding: 1.25rem; margin-top: 1.25rem;">
            <div style="font-size: 0.9rem; font-weight: 800; color: #0F172A; margin-bottom: 0.85rem;">
                <i class="fa-solid fa-circle-plus" style="color: #16A34A;"></i> Penerbitan Voucher Promo Etalase Baru
            </div>
            <form action="{{ route('seller.vouchers.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Kode Promo (Kapital)</label>
                        <input type="text" name="code" placeholder="Contoh: DISKONTOKO10" required style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800; text-transform: uppercase;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Nama Voucher</label>
                        <input type="text" name="name" placeholder="Contoh: Diskon Pelanggan Baru" required style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Tipe Diskon</label>
                        <select name="discount_type" style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Nilai Diskon</label>
                        <input type="number" name="discount_value" placeholder="Contoh: 10 (untuk 10%) atau 15000" min="1" step="any" required style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_spend" value="0" min="0" style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Maksimal Diskon (Rp, Opsional)</label>
                        <input type="number" name="max_discount" placeholder="Kosongkan jika bebas" min="0" style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Kuota Penggunaan</label>
                        <input type="number" name="quota" value="50" min="1" required style="width: 100%; padding: 0.55rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                    <button type="button" onclick="document.getElementById('sellerVoucherForm').style.display='none';" style="background: #E2E8F0; color: #475569; font-weight: 800; padding: 0.55rem 1.1rem; border-radius: 8px; border: none; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="background: #22C55E; color: #0F172A; font-weight: 900; padding: 0.55rem 1.35rem; border-radius: 8px; border: none; cursor: pointer;">
                        <i class="fa-solid fa-check"></i> Simpan & Aktifkan
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Voucher Toko -->
        <div style="margin-top: 1.25rem;">
            @if(isset($vouchers) && $vouchers->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 0.85rem;">
                    @foreach($vouchers as $sv)
                        <div style="background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 14px; padding: 1rem; position: relative;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <div>
                                    <span style="font-family: monospace; font-size: 1rem; font-weight: 900; color: #0F172A; background: #FFFFFF; padding: 0.2rem 0.6rem; border-radius: 6px; border: 1px solid #E2E8F0; display: inline-block;">
                                        {{ $sv->code }}
                                    </span>
                                    <div style="font-size: 0.85rem; font-weight: 800; color: #0F172A; margin-top: 0.4rem;">
                                        {{ $sv->name }}
                                    </div>
                                </div>
                                <span style="font-size: 0.7rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 20px; {{ $sv->is_active ? 'background: #DCFCE7; color: #16A34A;' : 'background: #FEE2E2; color: #DC2626;' }}">
                                    {{ $sv->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </div>

                            <div style="font-size: 0.78rem; color: #64748B; margin-bottom: 0.75rem;">
                                Diskon: <strong>{{ $sv->discount_type === 'percent' ? $sv->discount_value.'%' : 'Rp '.number_format($sv->discount_value, 0, ',', '.') }}</strong>
                                @if($sv->min_spend > 0) • Min. Belanja: Rp {{ number_format($sv->min_spend, 0, ',', '.') }} @endif
                                <br>
                                Terpakai: <strong>{{ $sv->used_count }} / {{ $sv->quota }}</strong>
                            </div>

                            <div style="display: flex; gap: 0.45rem; justify-content: flex-end; border-top: 1px solid #E2E8F0; padding-top: 0.65rem;">
                                <form action="{{ route('seller.vouchers.toggle', $sv->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.72rem; font-weight: 800; cursor: pointer; color: {{ $sv->is_active ? '#D97706' : '#16A34A' }};">
                                        {{ $sv->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('seller.vouchers.destroy', $sv->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus voucher {{ $sv->code }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #FFFFFF; border: 1px solid #FECACA; color: #DC2626; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.72rem; cursor: pointer;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 1.5rem 1rem; color: #94A3B8; font-size: 0.85rem; background: #F8FAFC; border-radius: 12px; border: 1px dashed #CBD5E1;">
                    <i class="fa-solid fa-ticket fa-2x" style="color: #CBD5E1; margin-bottom: 0.5rem; display: block;"></i>
                    Belum ada voucher promo toko. Klik 'Buat Voucher Toko' untuk memberikan kupon promosi pada pembeli.
                </div>
            @endif
        </div>
    </div>

    <!-- Etalase Barang Saya Section -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 900; color: #0F172A; margin: 0;">Etalase Barang Jualan Saya</h2>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.2rem; margin-bottom: 0;">
                    Stok masuk dan keluar tercatat otomatis. Barang yang habis akan otomatis disembunyikan dari katalog toko.
                </p>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <span style="font-size: 0.78rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.3rem 0.75rem; border-radius: 20px;">
                    {{ $activeItems }} Ready
                </span>
                <span style="font-size: 0.78rem; font-weight: 800; color: #DC2626; background: #FEE2E2; padding: 0.3rem 0.75rem; border-radius: 20px;">
                    {{ $soldOutItems }} Habis
                </span>
            </div>
        </div>

        @if(count($products) > 0)
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
                                    STOK HABIS
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
                            @if($prod->cost_price > 0)
                                <span style="font-size: 0.68rem; color: #64748B;" title="Estimasi Harga Modal">Modal: Rp {{ number_format($prod->cost_price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- Title, Subtitle, & Description -->
                        <div>
                            <h3 class="product-card-title" title="{{ $prod->name }}">{{ $prod->name }}</h3>
                            <div class="product-card-subtitle">
                                {{ $prod->badge ?? ($prod->category->name . ' / Authentic') }}
                            </div>
                            <p class="product-card-desc">
                                {{ $prod->description }}
                            </p>
                        </div>

                        <!-- Bottom Controls: Restock / Edit Stock & Price Button -->
                        <div class="product-card-bottom">
                            <button type="button" 
                                    onclick="openStockModal({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->stock }}, {{ $prod->price }}, {{ $prod->cost_price ?? round($prod->price * 0.7) }})"
                                    style="background: #F1F5F9; color: #0F172A; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.5rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.35rem; transition: all 0.2s;"
                                    onmouseover="this.style.background='#E2E8F0'; this.style.borderColor='#0F172A';"
                                    onmouseout="this.style.background='#F1F5F9'; this.style.borderColor='#CBD5E1';">
                                <i class="fa-solid fa-boxes-packing" style="color: #22C55E;"></i> Kelola Stok & Harga
                            </button>

                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 0.4rem; align-items: center;">
                                <a href="{{ route('products.show', $prod->slug) }}" 
                                   style="background: #000000; color: #FFF; text-decoration: none; border-radius: 20px; padding: 0.55rem 0.75rem; font-size: 0.75rem; font-weight: 800; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem; transition: all 0.2s;">
                                    Lihat <i class="fa-solid fa-arrow-right-long" style="font-size: 0.7rem;"></i>
                                </a>

                                <form action="{{ route('seller.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari etalase Anda?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; border-radius: 20px; padding: 0.55rem 0.75rem; font-size: 0.75rem; font-weight: 800; cursor: pointer; transition: all 0.2s;"
                                            title="Hapus Barang">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3.5rem 1rem; color: #64748B;">
                <i class="fa-solid fa-box-open fa-3x" style="color: #CBD5E1; margin-bottom: 1rem; display: block;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 800; color: #0F172A;">Etalase Toko Anda Masih Kosong</h3>
                <p style="font-size: 0.875rem; margin-top: 0.25rem; margin-bottom: 1.5rem;">Yuk mulai jualan barang thrifting original Anda sekarang!</p>
                <a href="{{ route('seller.products.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #0F172A; color: #FFF; font-weight: 800; font-size: 0.9rem; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Upload Barang Pertama
                </a>
            </div>
        @endif

    </div>

    <!-- KARTU STOK & RIWAYAT MUTASI LENGKAP (INVENTORY MOVEMENT LEDGER) -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <h2 style="font-size: 1.35rem; font-weight: 900; color: #0F172A; margin: 0;">Kartu Stok & Riwayat Mutasi Barang</h2>
                    <span style="background: #DCFCE7; color: #16A34A; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 12px;">
                        <i class="fa-solid fa-check-double"></i> Realtime & Presisi
                    </span>
                </div>
                <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem; margin-bottom: 0;">
                    Pencatatan akurat setiap stok masuk, penjualan keluar, dan sisa stok berjalan toko Anda.
                </p>
            </div>
        </div>

        @if($stockMovements->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                    <thead>
                        <tr style="background: #F8FAFC; border-bottom: 1.5px solid #E2E8F0; color: #64748B; font-weight: 800; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 0.85rem 1rem;">Tanggal & Waktu</th>
                            <th style="padding: 0.85rem 1rem;">Nama Produk</th>
                            <th style="padding: 0.85rem 1rem; text-align: center;">Jenis Mutasi</th>
                            <th style="padding: 0.85rem 1rem; text-align: center;">Perubahan</th>
                            <th style="padding: 0.85rem 1rem; text-align: center;">Stok Awal</th>
                            <th style="padding: 0.85rem 1rem; text-align: center;">Sisa Stok</th>
                            <th style="padding: 0.85rem 1rem; text-align: right;">Harga Jual</th>
                            <th style="padding: 0.85rem 1rem;">Keterangan & Referensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockMovements as $mov)
                            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s;" onmouseover="this.style.background='#F8FAFC';" onmouseout="this.style.background='transparent';">
                                <td style="padding: 0.85rem 1rem; color: #64748B; font-weight: 600;">
                                    {{ $mov->created_at->format('d M Y, H:i') }}
                                </td>

                                <td style="padding: 0.85rem 1rem; font-weight: 800; color: #0F172A;">
                                    @if($mov->product)
                                        <a href="{{ route('products.show', $mov->product->slug) }}" style="color: #0F172A; text-decoration: none;" onmouseover="this.style.color='#22C55E';" onmouseout="this.style.color='#0F172A';">
                                            {{ $mov->product->name }}
                                        </a>
                                    @else
                                        Barang dihapus
                                    @endif
                                </td>

                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    @if($mov->type === 'in')
                                        <span style="background: #DCFCE7; color: #16A34A; font-weight: 800; font-size: 0.72rem; padding: 0.25rem 0.65rem; border-radius: 20px;">
                                            <i class="fa-solid fa-arrow-down"></i> Masuk
                                        </span>
                                    @elseif($mov->type === 'out')
                                        <span style="background: #FEE2E2; color: #DC2626; font-weight: 800; font-size: 0.72rem; padding: 0.25rem 0.65rem; border-radius: 20px;">
                                            <i class="fa-solid fa-arrow-up"></i> Keluar
                                        </span>
                                    @else
                                        <span style="background: #E0F2FE; color: #0284C7; font-weight: 800; font-size: 0.72rem; padding: 0.25rem 0.65rem; border-radius: 20px;">
                                            <i class="fa-solid fa-sliders"></i> Penyesuaian
                                        </span>
                                    @endif
                                </td>

                                <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 900; color: {{ $mov->type === 'in' ? '#16A34A' : ($mov->type === 'out' ? '#DC2626' : '#0284C7') }};">
                                    {{ $mov->type === 'in' ? '+' : ($mov->type === 'out' ? '-' : '±') }}{{ $mov->quantity }}
                                </td>

                                <td style="padding: 0.85rem 1rem; text-align: center; color: #64748B; font-weight: 700;">
                                    {{ $mov->stock_before }} pcs
                                </td>

                                <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 900; color: #0F172A;">
                                    {{ $mov->stock_after }} pcs
                                </td>

                                <td style="padding: 0.85rem 1rem; text-align: right; font-weight: 800; color: #0F172A;">
                                    Rp {{ number_format($mov->price, 0, ',', '.') }}
                                </td>

                                <td style="padding: 0.85rem 1rem; color: #475569; font-size: 0.8rem;">
                                    <div style="font-weight: 700;">{{ $mov->notes ?: '-' }}</div>
                                    @if($mov->reference_id)
                                        <span style="font-family: monospace; font-size: 0.72rem; color: #64748B;">Ref: {{ $mov->reference_id }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 2.5rem 1rem; color: #94A3B8;">
                <i class="fa-solid fa-list-check fa-2x" style="color: #CBD5E1; margin-bottom: 0.5rem; display: block;"></i>
                <p style="margin: 0; font-size: 0.85rem;">Belum ada riwayat mutasi stok tercatat.</p>
            </div>
        @endif

    </div>

</div>

<!-- MODAL POPUP: KELOLA STOK & HARGA AKURAT -->
<div id="stockModalBackdrop" class="stock-modal-backdrop" onclick="closeStockModal(event)">
    <div class="stock-modal-box" onclick="event.stopPropagation()">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.75rem;">
            <div>
                <h3 style="font-size: 1.15rem; font-weight: 900; color: #0F172A; margin: 0;">Kelola Stok & Harga</h3>
                <p id="modalProdTitle" style="font-size: 0.8rem; color: #64748B; margin: 0.2rem 0 0 0; font-weight: 700;"></p>
            </div>
            <button type="button" onclick="closeStockModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748B; cursor: pointer;">
                &times;
            </button>
        </div>

        <form id="stockModalForm" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                    Aksi Mutasi Stok <span style="color: #EF4444;">*</span>
                </label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1.5px solid #CBD5E1; padding: 0.65rem 0.85rem; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-weight: 700;">
                        <input type="radio" name="type" value="in" checked onchange="toggleStockMode('in')">
                        <span><i class="fa-solid fa-plus" style="color: #16A34A;"></i> Restock (Tambah)</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1.5px solid #CBD5E1; padding: 0.65rem 0.85rem; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-weight: 700;">
                        <input type="radio" name="type" value="adjustment" onchange="toggleStockMode('adjustment')">
                        <span><i class="fa-solid fa-sliders" style="color: #0284C7;"></i> Set Sisa Baru</span>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label id="quantityLabel" style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                    Jumlah Unit Tambahan (+ pcs) <span style="color: #EF4444;">*</span>
                </label>
                <input type="number" id="modalQuantity" name="quantity" min="1" value="5" required
                       style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.75rem 1rem; font-weight: 800; font-size: 0.95rem; outline: none;">
                <span id="currentStockHint" style="font-size: 0.75rem; color: #64748B; margin-top: 0.25rem; display: block;"></span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                        Harga Jual (Rp)
                    </label>
                    <input type="number" id="modalPrice" name="price" min="0" step="1000"
                           style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.65rem 0.85rem; font-weight: 700; font-size: 0.88rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                        Harga Modal (Rp)
                    </label>
                    <input type="number" id="modalCostPrice" name="cost_price" min="0" step="1000"
                           style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.65rem 0.85rem; font-weight: 700; font-size: 0.88rem; outline: none;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 800; font-size: 0.85rem; color: #0F172A; margin-bottom: 0.4rem;">
                    Catatan Mutasi
                </label>
                <input type="text" name="notes" placeholder="Contoh: Restock kloter kedua / Koreksi stok fisik"
                       style="width: 100%; box-sizing: border-box; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0.65rem 0.85rem; font-weight: 600; font-size: 0.85rem; outline: none;">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="closeStockModal()" style="background: #F1F5F9; color: #64748B; font-weight: 800; font-size: 0.88rem; padding: 0.75rem 1.25rem; border-radius: 10px; border: 1px solid #CBD5E1; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #0F172A; color: #FFF; font-weight: 900; font-size: 0.88rem; padding: 0.75rem 1.5rem; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(15,23,42,0.25);">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Mutasi Stok
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    let currentProdStock = 0;

    function openStockModal(productId, productName, currentStock, price, costPrice) {
        currentProdStock = currentStock;
        document.getElementById('modalProdTitle').textContent = productName;
        document.getElementById('currentStockHint').textContent = `Sisa stok saat ini di etalase: ${currentStock} pcs`;
        document.getElementById('modalPrice').value = price;
        document.getElementById('modalCostPrice').value = costPrice || Math.round(price * 0.7);
        document.getElementById('modalQuantity').value = 5;

        const form = document.getElementById('stockModalForm');
        form.action = `/seller/products/${productId}/stock-movement`;

        document.getElementById('stockModalBackdrop').style.display = 'flex';
    }

    function closeStockModal() {
        document.getElementById('stockModalBackdrop').style.display = 'none';
    }

    function toggleStockMode(mode) {
        const lbl = document.getElementById('quantityLabel');
        const inp = document.getElementById('modalQuantity');
        if (mode === 'in') {
            lbl.innerHTML = 'Jumlah Unit Tambahan (+ pcs) <span style="color: #EF4444;">*</span>';
            inp.value = 5;
        } else {
            lbl.innerHTML = 'Set Total Sisa Stok Baru (pcs) <span style="color: #EF4444;">*</span>';
            inp.value = currentProdStock;
        }
    }
</script>
@endpush
@endsection
